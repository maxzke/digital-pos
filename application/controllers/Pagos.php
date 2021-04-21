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

        // $this->registrar_abono(
        //     $id_venta,
        //     $parametros['datos']['metodo_pago'],
        //     $parametros['datos']['abono']
        // );

        // $rest = 0;
        // if (floatval($parametros['datos']['abono']) < floatval($parametros['datos']['total']) ) {
        //     $this->registrar_credito($id_venta);
        //     $rest = floatval($parametros['datos']['total']) - floatval($parametros['datos']['abono']);
        // }else{
        //     $rest = floatval($parametros['datos']['abono']) - floatval($parametros['datos']['total']);
        // }

        // $this->email(
        //     $id_venta,
        //     $parametros['datos']['cliente'],
        //     $parametros['datos']['direccion'],
        //     $parametros['datos']['telefono'],
        //     $parametros['datos']['empresa'],
        //     $parametros['datos']['facturar'],
        //     $parametros['datos']['total'],
        //     $parametros['datos']['abono'],
        //     $parametros['datos']['metodo_pago'],
        //     $rest,
        //     $parametros['carrito']
        // );
                
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



















}//end of line