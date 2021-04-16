<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Pos extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);        
    } 

    function index(){  
        if( $this->is_role('admin') or $this->is_role('mesero') ){            
            // $this->load->model('Seccion_model');        
            // $this->load->model('Mesa_model');
            // $data['mesa_ocupada'] = $this->Mesa_model->get_mesa_ocupada();
            // $data['seccion'] = $this->Seccion_model->get_all_seccion();
            // $data['mesas'] = $this->Mesa_model->get_all_mesas(); 
            // $data['alertas'] = $this->check_min_stock();
            $data['_view'] = 'pos/index';
            $this->load->view('layouts/main',$data);
        }else{
            redirect('Cocina');
        }
    }

    /**
     * CHECA MINIMO STOCK
     */
    public function check_min_stock(){
        $this->load->model('Producto_model');
        $data['alerta_stock'] = $this->Producto_model->get_minimo_stock_productos();
        $abastecer = array();
        $cant = 0;
        foreach ($data['alerta_stock'] as $key) {
            if ($key['abastecer'] == "true") {
                $cant++;
                array_push($abastecer,$key);                
            }               
        }
        $response = array(
            'items' => $abastecer ,
            'cantidad' => $cant 
        );
        return $response;        
        //echo json_encode($response);
    }


    
}