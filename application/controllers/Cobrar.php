<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Cobrar extends MY_Controller{

	public function __construct()
	{
        parent::__construct();
        $this->require_min_level(1);

    }

    public function index(){
        
    }

    function payment($id=0){
        if ($id==0 or !$this->is_role('admin')) {
            redirect('PuntoDeVenta');
        }
        $this->load->model('Metodos_pago_model');
        $data['metodos_pago'] = $this->Metodos_pago_model->get_all_metodos_pago();
        $data['mesa'] = $id;
        $this->load->model('Mesa_model');
        $data['orderMesa'] = $this->Mesa_model->get_mesa($id);
        $data['alertas'] = $this->check_min_stock();
        //print_r($data);
        if(isset($data['orderMesa'])){
            $data['page']="inicio";
            $data['_view'] = 'cobrar/index';
            $this->load->view('layouts/mainCobrar',$data);
        }else{
            redirect('PuntoDeVenta');
        }
    }

    /**
     * lista clientes
     */
    function clientes($id){
        $data['mesa'] = $id;
        $this->load->model('Mesa_model');
        $data['orderMesa'] = $this->Mesa_model->get_mesa($id);
        $data['page']="inicio";
        $this->load->model('Cliente_model');
        $data['clientes'] = $this->Cliente_model->get_all_clientes();        
        $data['_view'] = 'cliente/index';
        $data['alertas'] = $this->check_min_stock();
        $this->load->view('layouts/mainCobrar',$data);
    }

    

    function addcliente($id)
    {   
        $data['mesa'] = $id;
        $this->load->model('Mesa_model');
        $data['orderMesa'] = $this->Mesa_model->get_mesa($id);
        $data['page']="inicio";
        $this->load->model('Cliente_model');

        $this->load->library('form_validation');

		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('telefono','Telefono','max_length[10]');
		
		if($this->form_validation->run())     
        {   
            $params = array(
				'nombre' => $this->input->post('nombre'),
				'telefono' => $this->input->post('telefono'),
            );
            
            $cliente_id = $this->Cliente_model->add_cliente($params);
            redirect('cobrar/clientes/'.$id);
        }
        else
        {            
            $data['_view'] = 'cliente/add';
            $this->load->view('layouts/mainCobrar',$data);
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





}//End of Line