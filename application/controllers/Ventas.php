<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
*   REST_Controller EXTIENDE DE MY_Controller
*   AHORA POSEE LOS METODOS DE COMUNITY AUTH
*   Y METODOS PROPIOS REST
*/
require_once(APPPATH.'libraries/REST_Controller.php');
use Restserver\libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
/**
 * PRINT TICKET POS
 */
//require __DIR__ . '../autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class Ventas extends REST_Controller {
    


    function __construct(){        
        parent::__construct();              
        $this->require_min_level(1);
        $this->load->model('ventas_model');
    }

    public function index_get(){
        $this->load->library('pagination');
        $config['base_url'] = site_url('ventas?');
        $limit = RECORDS_PER_PAGE;
        $offset = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;        
        $config = $this->config->item('pagination');
        
        $config['total_rows'] = $this->ventas_model->get_all_ventas_a_credito_count();
        $this->pagination->initialize($config);

        //$data['users'] = $this->User_model->get_all_users($params);
        $temp = $this->creditos($limit,$offset);
        $data['clientes'] = $temp;
        $data['_view'] = 'ventas/index';
        $data['active'] = 'ventas';
        $this->load->view('layouts/main',$data);
    }
    public function index_post(){
        
    }

    /**
     * Guarda Abono fetch
     */
    public function abonar_post(){
        $parametros = $this->input->post('params');
        $abono = $parametros['importe'];

        if ($parametros['importe'] <= 0) {
            $respuesta = array(
                'success' => false,
                'msg' => "Importe no válido!"
            );
        }else{
            $saldo = $this->getSaldoNota($parametros['folio']);
            if ($abono > $saldo) {                
                $cambio = ($abono) - ($saldo);
                $abono = $saldo;
                $respuesta = array(
                    'success' => true,
                    'msg' => "Su Cambio $".$cambio
                );
            }else{
                $respuesta = array(
                    'success' => true,
                    'msg' => ""
                );
            }
            
            $this->store_abono($parametros['folio'],$parametros['metodo'],$abono);
        }                
        $this->response($respuesta,200);
    }
    private function store_abono($id_venta,$metodo,$importe){
        $params = array(
            'id_venta' => $id_venta,
            'metodo' => $metodo,
            'importe' => $importe
        );
        $this->ventas_model->insert_abono($params);
    }

    /**
     * CARGA LISTADO DE CLIENTES CON CREDITOS A PAGAR
     */
    private function creditos($limit,$offset){
        $data['client'] = $this->ventas_model->get_clientes_deben($limit,$offset);
        if ($data['client']) {
            foreach ($data['client'] as $key) {
                /**
                 * revisa si saldo == 0
                 * si : borra venta de tabla creditos
                 */
                if ($this->getSaldoNota($key['id_venta']) == 0) {
                    $this->eliminaVentaCredito($key['id_venta']);
                }
                $iva = $this->getIva($key['id_venta']);
                $totalNota = $this->getImporteNota($key['id_venta']);
                $params[] = array(
                    'folio' => $key['id_venta'],
                    'cliente' => $key['cliente'],
                    'total' => $totalNota,
                    'iva' => $iva,
                    'abonos' => $this->getAbonosNota($key['id_venta']),
                    'resta' => $this->getSaldoNota($key['id_venta']),
                    'fecha' => $key['fecha']
                );
            }
            return $params;
        }
        
    }
    private function getSaldoNota($id){
        $total = $this->getImporteNota($id);
        $abonos = $this->getAbonosNota($id);
        $resta = $total[0]['importe'] - $abonos[0]['importe'] + $this->getIva($id);
        return $resta;
    }
    private function getAbonosNota($folio){
        return $this->ventas_model->abonosNota($folio);

    }
    private function getImporteNota($folio){
        return $this->ventas_model->importeTotalNota($folio);
    }
    private function getIva($id){
        $facturar = $this->getFacturarNota($id);
        $subtotal = $this->getImporteNota($id);
        $iva = 0;
        if ($facturar[0]['facturar']) {
            $iva = (0.16)*($subtotal[0]['importe']);
        }
        return $iva;
    }
    private function getFacturarNota($folio){
        return $this->ventas_model->ifFacturarNota($folio);
    }
    private function eliminaVentaCredito($id_venta){
        $this->ventas_model->delete_venta_credito($id_venta);
    }


    public function search_post(){
        $info = $this->input->post('params'); 
        $data['client'] = $this->searh_by_folio($info['tipo'],$info['param'],$info['seccion']);
        if ($data['client']) {
            foreach ($data['client'] as $key) {
                /**
                 * revisa si saldo == 0
                 * si : borra venta de tabla creditos
                 */
                if ($this->getSaldoNota($key['id_venta']) == 0) {
                    $this->eliminaVentaCredito($key['id_venta']);
                }
                $iva = $this->getIva($key['id_venta']);
                $totalNota = $this->getImporteNota($key['id_venta']);
                $params[] = array(
                    'folio' => $key['id_venta'],
                    'cliente' => $key['cliente'],
                    'total' => $totalNota,
                    'iva' => $iva,
                    'abonos' => $this->getAbonosNota($key['id_venta']),
                    'resta' => $this->getSaldoNota($key['id_venta']),
                    'fecha' => $key['fecha']
                );
            }
            $respuesta = array(
                'success' => true,
                'params' => $params
            );            
        }else{
            $respuesta = array(
                'success' => false
            );
        }
        $this->response($respuesta,200);
    }

    private function searh_by_folio($tipo,$param,$seccion){
        if ($tipo == 'folio') {
            return $this->ventas_model->get_all_ventas_search_by_folio($seccion,$param);
        }
        if ($tipo == 'proveedor') {
            return $this->ventas_model->get_all_ventas_search_by_cliente($seccion,$param);
        }
        
    }

    
    
    

















}//End Of Line


