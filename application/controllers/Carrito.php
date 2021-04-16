<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Carrito extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);
        // if( !$this->is_role('admin') or !$this->is_role('mesero') ){
        //     redirect('restringido');
        // }
        $this->load->model('Carrito_model');
    }

    // function index($id){

    //     $data['mesa'] = $id;

    //     $this->load->model('Producto_model');
    //     $data['productos'] = $this->Producto_model->get_all_productos();

    //     $this->load->model('Categoria_model');
    //     $data['categorias'] = $this->Categoria_model->get_all_categorias();

    //     $data['_view'] = 'carrito/index';
    //     $this->load->view('layouts/mainCarrito',$data);
    // }
    function index(){
        redirect('PuntoDeVenta');
    }

    function order($id=0){
        if ($id==0) {
            redirect('PuntoDeVenta');
        }
        if( $this->is_role('admin') or $this->is_role('mesero') ){            
           
            $data['mesa'] = $id;

            $this->load->model('Mesa_model');
            $data['orderMesa'] = $this->Mesa_model->get_mesa($id);
            

            $this->load->model('Producto_model');
            $data['productos'] = $this->Producto_model->get_all_productos();

            $this->load->model('Categoria_model');
            $data['categorias'] = $this->Categoria_model->get_all_categorias();

            $data['alertas'] = $this->check_min_stock();

            $data['_view'] = 'carrito/index';
            $this->load->view('layouts/mainCarrito',$data);
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

    /**
     * GET CARRITO BY ID MESA
     */
    function getCarrito(){
        $idmesa = $this->input->post('id');
        $data['carrito'] = $this->Carrito_model->get_carrito($idmesa);
        if(isset($data['carrito'])){
            $data['success'] = TRUE;
        }else{
            $data['success'] = FALSE;
        }
        echo json_encode($data);
    }
     /**
     * INSERT / UPDATE CARRITO BY ID MESA
     * SI LA MESA NO EXISTE SE CREA
     * SI LA MESA EXISTE SE UPDATE
     */
    function insertUpdate(){
        $id_mesa = $this->input->post('id');
        $carrito = $this->input->post('carrito');
        $data['carrito'] = $this->Carrito_model->get_carrito($id_mesa);
        if(isset($data['carrito'])){
            //MESA EXISTE, UPDATE CARRITO
            $this->edit($id_mesa,$carrito);
            $data['success'] = TRUE;
        }else{
            //MESA NO EXISTE, ADD CARRITO
            $this->add($id_mesa,$carrito);
            $data['success'] = FALSE;
        }
        $datax['cart'] = json_decode($carrito);
        echo json_encode($datax);
    }


    
    /*
     * Adding a new carrito
     */
    function add($id_mesa,$carrito){   
        $params = array(
            'id_mesa' => $id_mesa,
            'carrito' => $carrito
        );        
        $carrito_id = $this->Carrito_model->add_carrito($params);        
    }  

    /*
     * Editing a carrito
     */
    function edit($id_mesa,$carrito){   
        $params = array(
            'carrito' => $carrito,
        );
        $this->Carrito_model->update_carrito($id_mesa,$params);   
    } 

    /*
     * Deleting carrito
     */
    function remove($id)
    {
        $carrito = $this->Carrito_model->get_carrito($id);

        // check if the carrito exists before trying to delete it
        if(isset($carrito['id']))
        {
            $this->Carrito_model->delete_carrito($id);
            //redirect('carrito/index');
            $msg['success']=TRUE;
        }
        else{
            $msg['success']=FALSE;
        }            
        echo json_encode($msg);
    }


}