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
require __DIR__ . '../autoload.php';
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
        $data['clientes'] = $this->creditos();
        $data['_view'] = 'ventas/index';
        $this->load->view('layouts/main',$data);
    }
    public function index_post(){
        
    }

    /**
     * Guarda Abono fetch
     */
    public function abonar_post(){
        $parametros = $this->input->post('params');
        $this->store_abono($parametros['folio'],$parametros['metodo'],$parametros['importe']);
        $respuesta = array(
            'success' => true,
            'data' => $parametros
        );
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
    private function creditos(){
        $data['clientes'] = $this->ventas_model->get_clientes_deben();
        foreach ($data['clientes'] as $key) {
            //$this->getSaldoNota($key['id_venta']);
            $params[] = array(
                'folio' => $key['id_venta'],
                'cliente' => $key['cliente'],
                'total' => $this->getImporteNota($key['id_venta']),
                'abonos' => $this->getAbonosNota($key['id_venta']),
                'resta' => $this->getSaldoNota($key['id_venta']),
                'fecha' => $key['fecha']
            );
        }
        return $params;
    }
    private function getSaldoNota($id){
        $total = $this->getImporteNota($id);
        $abonos = $this->getAbonosNota($id);
        $resta = $total[0]['importe'] - $abonos[0]['importe'];
        return $resta;
    }
    private function getAbonosNota($folio){
        return $this->ventas_model->abonosNota($folio);

    }
    private function getImporteNota($folio){
        return $this->ventas_model->importeTotalNota($folio);
    }

    
    
    

















}//End Of Line


