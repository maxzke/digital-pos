<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
*   REST_Controller EXTIENDE DE MY_Controller
*   AHORA POSEE LOS METODOS DE COMUNITY AUTH
*   Y METODOS PROPIOS REST
*/
require_once(APPPATH.'libraries/REST_Controller.php');
use Restserver\libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Pagos extends REST_Controller{

    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);  
        $this->load->model('pagos_model');      
    } 

    function index_get(){   
        $this->load->library('pagination');
        $config['base_url'] = site_url('pagos?');
        $limit = RECORDS_PER_PAGE;
        $offset = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;        
        $config = $this->config->item('pagination'); 
               
        $config['total_rows'] = $this->pagos_model->pagos_count();
        $this->pagination->initialize($config);

        $data['pagos'] = $this->pagos_model->get_all_pagos($limit,$offset);
        $data['_view'] = 'pagos/index';
        $data['active'] = 'pagos';
        $this->load->view('layouts/main',$data);
    }

    /**
     * Guarda Pago a Proveedores / Gastos
     */
    public function store_post(){
        $parametros = $this->input->post('params');
        $respuesta = array(
            'success' => true,
            'params' => $parametros,
        );

        $id_pago = $this->registrar_pago(
            $parametros['datos']['proveedor'],
            $parametros['datos']['folio'],
            $parametros['datos']['metodo'],
            $parametros['datos']['facturado'],
            $parametros['datos']['subtotal'],
            $parametros['datos']['iva'],
            $parametros['datos']['total'],
            $parametros['datos']['usuario']
        );

        $this->registrar_detalle($id_pago,$parametros['carrito']);        
        $this->email(
            $parametros['datos']['folio'],
            $parametros['datos']['proveedor'],
            $parametros['datos']['facturado'],
            $parametros['datos']['subtotal'],
            $parametros['datos']['iva'],
            $parametros['datos']['total'],
            $parametros['datos']['metodo'],
            $parametros['carrito']
        );
                
        $this->response($respuesta,200);
    }

    /**
     * Guarda pago table:pagos
     */
    private function registrar_pago($proveedor,$folio,$metodo,$facturado,$subtotal,$iva,$total,$usuario){
        $params = array(
            'proveedor' => $proveedor,
            'folio' => $folio,
            'metodo' => $metodo,
            'facturado' => $facturado,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'usuario' => $usuario
        ); 
        $folio = $this->pagos_model->insert_datos_pago($params);
        if (strtolower($metodo) == 'efectivo') {
            /**
             * Cada que hay un ingreso en EFECTIVO
             */
            $this->load->model('Cortes_model');
            $this->Cortes_model->subs_efectivo_caja($total);
        }
        return $folio;
    }

    private function registrar_detalle($id_pago,$carrito){
        foreach ($carrito as $key => $value) {
            $params[] = array(
                'id_pago' => $id_pago,
                'producto' => $value['producto'],
                'cantidad'  => $value['cantidad'],
                'precio'   => $value['precio'],
                'importe'  => $value['importe']
            );            
        }         
        $this->pagos_model->insert_datos_detalle($params);
    }

    public function updateFacturado_post(){
        $parametros = $this->input->post('params');
        $id = $parametros['folio'];
        $params = array(
            'facturado' => 1
        );
        $this->pagos_model->update_status_facturado($id,$params);
        $respuesta = array(
            'success' => true,
            'params' => $parametros,
        );
        $this->response($respuesta,200);
    }

    private function email($folio,$proveedor,$facturado,$subtotal,$iva,$total,$metodo,$cart){
        $to = "digital-estudio@live.com.mx,ramzdav@hotmail.com";
        $subject = "Pagos a Proveedores ".$folio;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";   
        if ($facturado == 1) {
            $facturar = "Si";
        }else{
            $facturar = "No";
        }
        $message = "
            <html>
            <head>
            <title>HTML</title>
            </head>
            <body style='background-color: rgb(231, 231, 231); padding-left: 20px; padding-top: 10px; padding-bottom: 20px;'>
            <h1>Digital Estudio</h1>
            <h2>Pago a Proveedor Folio: {$folio} </h2>
            <span><strong>Proveedor: </strong> {$proveedor}</span><br>
            <span><strong>Facturado : </strong> {$facturar} </span><br>
            <span><strong>SubTotal : </strong> {$subtotal} </span><br>
            <span><strong>Iva : </strong> {$iva} </span><br>
            <span><strong>Total : </strong> {$total} </span><br>
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
                        <td>".$item['importe']."</td>
                    </tr>";
                endforeach; 
                $message .= "</tbody>
            </table>
            </body>
            </html>";
        
        mail($to, $subject, $message, $headers);
    }

    public function search_post(){
        $info = $this->input->post('params');
        $data['pagos'] = $this->searh_by_folio($info['tipo'],$info['param']);
        if ($data['pagos']) {
            $respuesta = array(
                'success' => true,
                'params' => $data['pagos']
            );
        }else{
            $respuesta = array(
                'success' => false
            );
        }        
        $this->response($respuesta,200);        
    }

    private function searh_by_folio($tipo,$param){
        if ($tipo == 'folio') {
            return $this->pagos_model->get_all_pagos_search_by_folio($param);
        }
        if ($tipo == 'proveedor') {
            return $this->pagos_model->get_all_pagos_search_by_proveedor($param);
        }
        
    }



















}//end of line