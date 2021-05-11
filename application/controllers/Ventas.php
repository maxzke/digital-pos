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
        $this->load->model('Cortes_model');
    }

    public function index_get(){
        $this->load->library('pagination');
        $config['base_url'] = site_url('ventas?');
        $limit = RECORDS_PER_PAGE;
        $offset = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;        
        $config = $this->config->item('pagination');
        
        $config['total_rows'] = $this->ventas_model->get_all_ventas_a_credito_count();
        $this->pagination->initialize($config);

        $data['pendientes'] = $this->creditos($limit,$offset);
        $data['_view'] = 'ventas/index';
        $data['active'] = 'ventas';
        $this->load->view('layouts/main',$data);
    }
    public function pagados_get(){
        $this->load->library('pagination');
        $config['base_url'] = site_url('ventas?');
        $limit = RECORDS_PER_PAGE;
        $offset = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;        
        $config = $this->config->item('pagination');
        
        $config['total_rows'] = $this->ventas_model->get_all_ventas_pagadas_count();
        $this->pagination->initialize($config);

        $data['pagados'] = $this->pagados($limit,$offset);
        $data['_view'] = 'ventas/pagadas';
        $data['active'] = 'ventas';
        $this->load->view('layouts/main',$data);
    }
    public function cancelados_get(){
        $this->load->library('pagination');
        $config['base_url'] = site_url('ventas?');
        $limit = RECORDS_PER_PAGE;
        $offset = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;        
        $config = $this->config->item('pagination');
        
        $config['total_rows'] = $this->ventas_model->get_all_ventas_cancelada_count();
        $this->pagination->initialize($config);

        $data['cancelados'] = $this->cancelados($limit,$offset);
        $data['_view'] = 'ventas/canceladas';
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
                $cambio = $abono - $saldo;
                $abono = $saldo;
                $respuesta = array(
                    'success' => true,
                    'msg' => "Su Cambio $".number_format($cambio,1,'.',',')
                );
                $this->store_abono($parametros['folio'],$parametros['metodo'],$abono);
                $this->eliminaVentaCredito($parametros['folio']);
            }else{
                $this->store_abono($parametros['folio'],$parametros['metodo'],$abono);
                $respuesta = array(
                    'success' => true,
                    'msg' => "Abono guardado !"
                );
            }
            
            
        }                
        $this->response($respuesta,200);
    }
    private function store_abono($id_venta,$metodo,$importe){
        $params = array(
            'id_venta' => $id_venta,
            'metodo' => $metodo,
            'importe' => $importe,
            'usuario' =>$this->auth_username
        );
        $this->ventas_model->insert_abono($params);
        if (strtolower($metodo) == 'efectivo') {
            /**
             * Cada que hay un ingreso en EFECTIVO
             */
            $this->Cortes_model->add_efectivo_caja($importe);
        }else{
            $this->Cortes_model->add_cuenta_banco($importe);
        }
    }

    /**
     * CARGA LISTADO DE CLIENTES CON CREDITOS A PAGAR
     */
    private function creditos($limit,$offset){
        $data['client'] = $this->ventas_model->get_clientes_deben($limit,$offset);
        
        if ($data['client']) {
            foreach ($data['client'] as $key) {                
                $this->eliminaVentaCredito($key['id_venta']);
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
    /**
     * CARGA LISTADO DE CLIENTES PAGADOS
     */
    private function pagados($limit,$offset){
        $data['client'] = $this->ventas_model->get_clientes_pagados($limit,$offset);
        
        if ($data['client']) {
            foreach ($data['client'] as $key) {                
                $this->eliminaVentaCredito($key['id_venta']);
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
    /**
     * CARGA LISTADO DE VENTAS CANCELADAS   
     */
    private function cancelados($limit,$offset){
        $data['client'] = $this->ventas_model->get_clientes_cancelados($limit,$offset);
        
        if ($data['client']) {
            foreach ($data['client'] as $key) {                
                $this->eliminaVentaCredito($key['id_venta']);
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
        $saldo = number_format($this->getSaldoNota($id_venta),1,'.',',');
        if ( $saldo == 0){
            $this->ventas_model->delete_venta_credito($id_venta);
            $this->load->model('ventas_model');
            $this->ventas_model->set_as_pagado($id_venta);
        }        
    }


    public function search_post(){
        $info = $this->input->post('params'); 
        $data['client'] = $this->searh_by_folio($info['tipo'],$info['param'],$info['seccion']);
        if ($data['client']) {
            foreach ($data['client'] as $key) {                
                $this->eliminaVentaCredito($key['id_venta']);
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
        if ($tipo == 'cliente') {
            return $this->ventas_model->get_all_ventas_search_by_cliente($seccion,$param);
        }
        
    }

    public function cancelar_post(){
        $parametros = $this->input->post('params');
        if ($parametros['motivo']=="" || $parametros['importe']=="") {
            if ($parametros['motivo']=="") {
                $respuesta = array(
                    'success' => false,
                    'msg' => 'Agregar Motivo'
                );
            }
            if ($parametros['importe']=="") {
                $respuesta = array(
                    'success' => false,
                    'msg' => 'Agregar Importe'
                );
            }
        }else{
            $update = $this->setStatusCancel($parametros['folio']);
            $insert = $this->store_venta_cancelada($parametros['folio'],$parametros['motivo'],$this->auth_username);
            $this->eliminaVentaCredito($parametros['folio']);
            if ($update && $insert) {
                $respuesta = array(
                    'success' => true
                );
            }else{
                $respuesta = array(
                    'success' => true,
                    'update' => $update,
                    'insert' => $insert
                );
            }
        }        
        $this->response($respuesta,200);
    }

    private function setStatusCancel($folio){
        //ventas::cancelada=1
        $this->ventas_model->update_cancelar($folio);
    }

    private function store_venta_cancelada($folio,$motivo,$usuario){
        $params = array(
            'id_venta' => $folio,
            'motivo' => $motivo,
            'usuario' => $usuario
        );
        $this->ventas_model->insert_cancelar($params);
    }

    public function detalles_post(){
        $obtiene = $this->input->post('params');
        $folio = $obtiene['id'];
        $venta = $this->ventas_model->get_venta($folio);
        $listadoabonos = $this->ventas_model->listadoAbonosNota($folio);
        $cart = $this->ventas_model->get_detalles_venta($folio);
        $iva = $this->getIva($folio);
        $totalNota = $this->getImporteNota($folio);
        
        foreach ($venta as $key) {                
            
            $params[] = array(
                'folio' => $key['id'],
                'cliente' => $key['cliente'],
                'subtotal' => $totalNota[0]['importe'],
                'iva' => $iva,
                'direccion' => $key['direccion'],
                'telefono' => $key['telefono'],
                'empresa' => $key['empresa'],
                'facturar' => $key['facturar'],
                'abonos' => $this->getAbonosNota($key['id']),
                'resta' => $this->getSaldoNota($key['id']),
                'fecha' => $key['fecha']
            );
        }

        $totalN = floatval($iva)+floatval($totalNota[0]['importe']);

        $cachar = $this->pdf(
            $folio,
            $params[0]['cliente'],
            $params[0]['direccion'],
            $params[0]['telefono'],
            $params[0]['empresa'],
            $params[0]['facturar'],
            $params[0]['subtotal'],
            $params[0]['iva'],
            $totalN,
            $params[0]['abonos'],
            $params[0]['resta'],
            $cart,
            $listadoabonos
        );

        $respuesta = array(
            'success' => true,
            'ventas' => $venta,
            'listadoabonos' => $listadoabonos,
            'cart' => $cart,
            'totalNota' => $totalNota,
            'iva' => $iva,
            'cachar' => $cachar
        );
        
        $this->response($respuesta,200);
    }

    private function pdf($folio,$cliente,$direccion,$telefono,$empresa,$factura,$subtotal,$iva,$total,$abono,$restante,$cart,$abonos){
        $to = "digital-estudio@live.com.mx,ramzdav@hotmail.com";
        $subject = "Nota de Venta ".$folio;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";   
        if ($factura == 1) {
            $facturar = "Si";
        }else{
            $facturar = "No";
        }
        $currentAbono = $abono[0]['importe'];
        $message = "
            <html>
            <head>
            <title>HTML</title>
            </head>
            <body style='background-color: #e7e7e7; padding-left: 20px; padding-top: 10px; padding-bottom: 20px;'>
            <h2>Nota de Venta Folio: {$folio} </h2>
            <span><strong>Cliente: </strong> {$cliente}</span><br>
            <span><strong>Dirección : </strong> {$direccion} </span><br>
            <span><strong>Telefono : </strong> {$telefono} </span><br>
            <span><strong>Empresa : </strong> {$empresa} </span><br>
            <span><strong>Facturar : </strong> {$facturar} </span><br>
            <span><strong>SubTotal : </strong> {$subtotal} </span><br>
            <span><strong>Iva : </strong> {$iva} </span><br>
            <span><strong>Total : </strong> {$total} </span><br>
            <span><strong>Abono : </strong> {$currentAbono} <strong>
            <span><strong>Restante : </strong> {$restante} </span><br><br>
            <table style='border: 1px solid black;'>
                <thead style='background-color: black; color: white;'>
                    <tr>
                        <td>Cantidad</td>
                        <td>Descripcion</td>
                        <td>Precio</td>
                        <td>SubTotal</td>
                    </tr>
                </thead>
                <tbody>";
                foreach ($cart as $key=>$item): 
                    $message .= "<tr>
                        <td>".$item['cantidad']."</td>
                        <td>".$item['producto']."</td>
                        <td>".$item['precio']."</td>
                        <td>".number_format($item['importe'],2,".",",")."</td>
                    </tr>";
                endforeach; 
                $message .= "</tbody>
            </table>
            <span><strong>Restante : </strong> Historial Abonos </span><br><br>
            <table style='border: 1px solid black;'>
                <thead style='background-color: black; color: white;'>
                    <tr>
                        <td>Importe</td>
                        <td>Metodo</td>
                        <td>Fecha</td>
                    </tr>
                </thead>
                <tbody>";
                foreach ($abonos as $key=>$item): 
                    $message .= "<tr>
                        <td>".number_format($item['importe'],2,".",",")."</td>
                        <td>".$item['metodo']."</td>
                        <td>".date("d-m-Y H:i:s", strtotime($item['fecha']))."</td>
                    </tr>";
                endforeach; 
                $message .= "</tbody>
            </table>
            </body>
            </html>";

        $params = array(
            'name'=>$subject,
            'data'=>$message
        );                
        return $params;
    }

    
    
    

















}//End Of Line


