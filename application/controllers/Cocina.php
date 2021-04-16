<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Cocina extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);
        //$this->load->model('Carrito_model');
        $this->load->model('cocina_model');
    }
    function index(){
        $data['alertas'] = $this->check_min_stock();
        $data['orden'] = $this->cocina_model->getOrdenes();
        $data['variable'] = "david ramirez flores";
        $data['_view'] = 'cocina/index';
        $this->load->view('layouts/main',$data);
    }
    public function addorden(){
        $categoria = $this->input->post('categoria');
        if ($categoria == 'botanas') {
            $params = array(
                'producto' => $this->input->post('producto'),
                'mesa' => $this->input->post('mesa'),
                //'categoria' => $this->input->post('categoria')
             );
             
             $this->cocina_model->agregaOrden($params);
        }
    }
    
    private function getOrdenes(){
        $data['ordenes'] = $this->cocina_model->getOrdenes();
        return $data;
    }

    /**
     * ALERTA ORDENES LISTAS: STATUS = 3
     */
    // public function getordenslistas(){
    //     $data['ordenes'] = $this->cocina_model->getOrdenesListas();
    //     echo json_encode($data);
    // }

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

    public function status($id=0){
        
        if( $this->is_role('admin') or $this->is_role('mesero') ){            
            //redirect('Cocina');
            //$x = $this->cocina_model->updateStatus($id);
            //no es cocinero quien hizo el cambio
            redirect('Cocina');
        }else{            
            //cocinero actualiza el status del pedido
            $x = $this->cocina_model->updateStatus($id);
            redirect('Cocina');
        }
    }

}
