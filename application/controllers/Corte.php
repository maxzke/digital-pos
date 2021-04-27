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
    } 

    public function index_get(){
        $data['caja'] = $this->get_total('caja');
        $data['cuenta_banco'] = $this->get_total('banco');
        $data['_view'] = 'cortes/diseno';
        $data['active'] = 'corte';
        $this->load->view('layouts/main',$data);
    }

    private function get_total($tipo){
        $data = $this->Cortes_model->get_importe_caja($tipo);
        return number_format($data[0][$tipo],2,'.',',');
    }

    private function get_total_importe($metodo){
        $data = $this->Cortes_model->suma_importe($metodo);
        return number_format($data[0]['importe'],2,'.',',');
    }

    private function get_total_importe_sin_formato($metodo){
        $data = $this->Cortes_model->suma_importe($metodo);
        return floatval($data[0]['importe']);
    }
    private function get_total_cuenta(){
        $data = $this->Cortes_model->suma_importes_cuenta();
        return floatval($data[0]['importe']);
    }

    private function cuenta_banco(){
        $cheque = $this->get_total_importe_sin_formato('cheque');
        $transferencia = $this->get_total_importe_sin_formato('transferencia');
        $tarjeta = $this->get_total_importe_sin_formato('tarjeta');
        $cuenta = $this->get_total_cuenta();
        $suma = $cheque + $transferencia + $tarjeta+$cuenta;
        return number_format($suma,2,'.',',');
    }

    public function store_post(){
        $importe = $this->input->post('params');
        if (floatval($importe <=0)) {            
            $respuesta = array(
                'success' => false,
                'msg' => 'Verificar importe'
            );  
        }else{
            $this->Cortes_model->insert_deposito(floatval($importe),$this->auth_username);
            $this->Cortes_model->add_cuenta_banco($importe);
            $respuesta = array(
                'success' => true,
                'msg' => 'Depósito guardado!'
            );
        }             
        $this->response($respuesta,200);
    }


    













}//end of line