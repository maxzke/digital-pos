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

class Pos extends REST_Controller{

     /**
     * Params para Nota
     */
    private $folio;//id de la venta
    private $id_cliente;
    private $id_usuario;
    private $id_mesa;
    private $statusNota;
    private $netoPagado;
    private $importeNota;
    private $saldoNota;
    /**
     * Params para Detalle
     */
    private $carrito = array();
    private $carritoTicket = array();
    /**
     * Params para Abono
     */
    private $pagos;
    //Saldo total cliente debe
    private $saldo=0;

    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);  
        $this->load->model('pos_model');      
    } 

    function index_get(){   
        $data['_view'] = 'pos/index';
        $data['active'] = 'pos';
        $this->load->view('layouts/main',$data);
    }

    /**
     * Guarda Nota de Venta
     */
    public function store_post(){
        $parametros = $this->input->post('params');
        $respuesta = array(
            'success' => true,
            'params' => $parametros['datos']['cliente'],
        );

        $id_venta = $this->registrar_nota(
            $parametros['datos']['facturar'],
            $parametros['datos']['cotizar'],
            $parametros['datos']['cliente'],
            $parametros['datos']['direccion'],
            $parametros['datos']['telefono'],
            $parametros['datos']['empresa'],
            $parametros['datos']['user']
        );

        $this->registrar_detalle($id_venta,$parametros['carrito']);

        $this->registrar_abono(
            $id_venta,
            $parametros['datos']['metodo_pago'],
            $parametros['datos']['abono']
        );

        if (floatval($parametros['datos']['abono']) < floatval($parametros['datos']['total']) ) {
            $this->registrar_credito($id_venta);
        }
                
        $this->response($respuesta,200);
    }
    /**
     * Guarda datos ventas:table
     */
    private function registrar_nota($facturar,$cotizar,$cliente,$direccion,$telefono,$empresa,$usuario){
        $params = array(
            'facturar' => $facturar,
            'cotizar' => $cotizar,
            'cliente' => $cliente,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'empresa' => $empresa,
            'usuario' => $usuario,
        ); 
        $folio = $this->pos_model->insert_datos_nota($params);
        return $folio;
    }
    /**
     * Guarda detalles cart
     * detalles:table
     */
    private function registrar_detalle($id_venta,$carrito){
        foreach ($carrito as $key => $value) {
            $params[] = array(
                'id_venta' => $id_venta,
                'producto' => $value['producto'],
                'cantidad'  => $value['cantidad'],
                'precio'   => $value['precio'],
                'importe'  => $value['importe']
            );            
        }         
        $this->pos_model->insert_datos_detalle($params);
    }
    /**
     * Guarda Abono
     */
    private function registrar_abono($id_venta,$metodo,$importe){
        $params = array(
            'id_venta' => $id_venta,
            'metodo' => $metodo,
            'importe' => $importe
        );
        $this->pos_model->insert_abono($params);
    }

    /**
     * Guarda Venta a Credito
     */
    private function registrar_credito($id_venta){
        $params = array(
            'id_venta' => $id_venta
        );
        $this->pos_model->insert_credito($params);
    }
    


    
}