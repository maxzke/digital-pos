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

class Corte extends REST_Controller{

    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);  
        $this->load->model('Cortes_model');      
        $this->load->model('Ventas_model');  
    } 

    private $cajaEfectivo = 0;
    private $cuentaBanco = 0;
    private $x = 0;
    private $a = 0;
    private $y = 0;
    private $z = 0;
    private $totalVentas = 0;


    public function index_get(){
        $data['caja'] = $this->get_total('caja');
        $data['cuenta_banco'] = $this->get_total('banco');
        $data['_view'] = 'cortes/diseno';
        $data['active'] = 'corte';
        $this->load->view('layouts/main',$data);
    }

    /**
     * Obtiene importe total por TIPO de tabla::cuenta_caja
     * id::banco::caja
     */
    private function get_total($tipo){
        $data = $this->Cortes_model->get_importe_caja($tipo);
        return number_format($data[0][$tipo],2,'.',',');
    }
    private function get_total_sin_formato($tipo){
        $data = $this->Cortes_model->get_importe_caja($tipo);
        return $data[0][$tipo];
    }

    public function desgloce_por_fecha_post(){
        $parametros = $this->input->post('params');
        $fechaInicial =  $parametros['desde'];
        $fechaFinal =  $parametros['hasta'];

        if ($fechaInicial==0 || $fechaFinal==0) {
            $respuesta = array(
                'success' => false,
                'msg' => 'Rango no válido!'
            ); 
        }else{
            $fechaInicial = date("Y-m-d 00:00:00", strtotime($fechaInicial));
            $fechaFinal   = date("d-m-Y 23:59:59", strtotime($fechaFinal));
            $respuesta = array(
                'success' => true,
                'arrayventas' => $this->ventas($fechaInicial,$fechaFinal),
                'cobrado_en_efectivo' => $this->get_suma_ventas_por_metodo('efectivo',$fechaInicial,$fechaFinal),
                'cobrado_en_transferencia' => $this->get_suma_ventas_por_metodo('transferencia',$fechaInicial,$fechaFinal),
                'cobrado_en_tarjeta' => $this->get_suma_ventas_por_metodo('tarjeta',$fechaInicial,$fechaFinal),
                'cobrado_en_cheque' => $this->get_suma_ventas_por_metodo('cheque',$fechaInicial,$fechaFinal),
                'importe_total_ventas' => number_format($this->totalVentas,2,'.',','),

                'arraypagos' => $this->pagos($fechaInicial,$fechaFinal),
                'total_pagos' => $this->total_pagos($fechaInicial,$fechaFinal),
                'arraydepositos' => $this->depositos($fechaInicial,$fechaFinal),
                'total_depositos' => $this->total_depositos($fechaInicial,$fechaFinal),

                'caja_efectivo' => number_format(($this->x) - ($this->y) - ($this->z),2,'.',','),
                'cuenta_banco' =>  number_format(($this->a)+($this->z),2,'.',',')
            );            
        }
        $this->response($respuesta,200);
    }

    private function get_suma_ventas_por_metodo($metodo,$fechaInicial,$fechaFinal){
        $data = $this->Cortes_model->total_cobrado_en($metodo,$fechaInicial,$fechaFinal);  
        $this->totalVentas += $data[0]['importe'];    
        if ($metodo == 'efectivo') {
            $this->x = $data[0]['importe'];
        }else{
            $this->a += $data[0]['importe'];
        }
        return number_format($data[0]['importe'],2,'.',',');
    }

    /**
     * LISTADO DE INGRESOS ( VENTAS )
     */
    private function ventas($desde,$hasta){
        $data['client'] = $this->Ventas_model->get_ingresos_corte($desde,$hasta);        
        if ($data['client']) {
            foreach ($data['client'] as $key) {  
                $abono = $this->getAbonosNota($key['id']); 
                if ($abono[0]['importe'] > 0) {
                    $params[] = array(
                        'folio' => $key['id'],
                        'abonos' => number_format($abono[0]['importe'],2,'.',',')
                    );
                }           
                
            }
            return $params;
        }       
    }
    private function getAbonosNota($folio){
        return $this->Ventas_model->abonosNota($folio);
    }
    /**
     * LISTADO DE EGRESOS ( PAGOS )
     */
    private function pagos($desde,$hasta){
        $pagos = $this->Cortes_model->get_pagos_between($desde,$hasta);
        if ($pagos) {
            foreach ($pagos as $key) {   
                    $params[] = array(
                        'folio' => $key['id'],
                        'total' => number_format($key['total'],2,'.',',')
                    ); 
            }
            return $params;
        } 
    }
    private function total_pagos($desde,$hasta){
        $total = $this->Cortes_model->get_suma_pagos_between($desde,$hasta);
        if ($total) {
            $this->y = $total[0]['total'];
            return number_format($total[0]['total'],2,'.',',');
        }
    }
    /**
     * LISTADO DE DEPOSITOS A CUENTA
     */
    private function depositos($desde,$hasta){
        $depositos = $this->Cortes_model->get_depositos_between($desde,$hasta);
        if ($depositos) {
            foreach ($depositos as $key) {   
                    $params[] = array(
                        'folio' => $key['id'],
                        'total' => number_format($key['importe'],2,'.',',')
                    ); 
            }
            return $params;
        } 
    }
    private function total_depositos($desde,$hasta){
        $total = $this->Cortes_model->get_suma_depositos_between($desde,$hasta);
        if ($total) {
            $this->z = $total[0]['importe'];
            return number_format($total[0]['importe'],2,'.',',');
        }
    }

    // private function get_total_importe_sin_formato($metodo){
    //     $data = $this->Cortes_model->suma_importe($metodo);
    //     return floatval($data[0]['importe']);
    // }
    // /**
    //  * suma historial cuenta_depositos
    //  */
    // private function get_total_cuenta(){
    //     $data = $this->Cortes_model->suma_importes_cuenta();
    //     return floatval($data[0]['importe']);
    // }

    // private function cuenta_banco(){
    //     $cheque = $this->get_total_importe_sin_formato('cheque');
    //     $transferencia = $this->get_total_importe_sin_formato('transferencia');
    //     $tarjeta = $this->get_total_importe_sin_formato('tarjeta');
    //     $cuenta = $this->get_total_cuenta();
    //     $suma = $cheque + $transferencia + $tarjeta+$cuenta;
    //     return number_format($suma,2,'.',',');
    // }

    public function store_post(){
        $importe = $this->input->post('params');
        $maximo_importe =  $this->get_total_sin_formato('caja');
        if ($maximo_importe === null) {
            $maximo_importe = 0;
        }
        if (floatval($importe) <=0 || floatval($importe) > floatval($maximo_importe)) {            
            $respuesta = array(
                'success' => false,
                'msg' => 'Verificar importe'
            );  
        }else{
            $this->Cortes_model->insert_deposito(floatval($importe),$this->auth_username);
            $this->Cortes_model->add_cuenta_banco($importe);
            $this->Cortes_model->subs_efectivo_caja($importe);
            $respuesta = array(
                'success' => true,
                'msg' => 'Depósito guardado!',
                'saldo_efectivo' => $this->get_total('caja'),
                'saldo_banco' => $this->get_total('banco')
            );
        }             
        $this->response($respuesta,200);
    }


    













}//end of line

