<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Metodos_pago extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);
        if( !$this->is_role('admin') ){
            redirect('restringido');
        }
        $this->load->model('Metodos_pago_model');
    } 

    /*
     * Listing of metodos_pago
     */
    function index()
    {
        $data['metodos_pago'] = $this->Metodos_pago_model->get_all_metodos_pago();
        
        $data['_view'] = 'metodos_pago/index';
        $data['alertas'] = $this->check_min_stock();
        $this->load->view('layouts/admin',$data);
    }

    /*
     * Adding a new metodos_pago
     */
    function add()
    {   
        $this->load->library('form_validation');

		$this->form_validation->set_rules('metodo','Metodo','required|is_unique[metodos_pago.metodo]');
		
		if($this->form_validation->run())     
        {   
            $params = array(
				'metodo' => $this->input->post('metodo'),
            );
            
            $metodos_pago_id = $this->Metodos_pago_model->add_metodos_pago($params);
            redirect('metodos_pago/index');
        }
        else
        {            
            $data['_view'] = 'metodos_pago/add';
            $data['alertas'] = $this->check_min_stock();
            $this->load->view('layouts/admin',$data);
        }
    }  

    /*
     * Editing a metodos_pago
     */
    function edit($id)
    {   
        // check if the metodos_pago exists before trying to edit it
        $data['metodos_pago'] = $this->Metodos_pago_model->get_metodos_pago($id);
        
        if(isset($data['metodos_pago']['id']))
        {
            $this->load->library('form_validation');

			$this->form_validation->set_rules('metodo','Metodo','required|is_unique[metodos_pago.metodo]');
		
			if($this->form_validation->run())     
            {   
                $params = array(
					'metodo' => $this->input->post('metodo'),
                );

                $this->Metodos_pago_model->update_metodos_pago($id,$params);            
                redirect('metodos_pago/index');
            }
            else
            {
                $data['_view'] = 'metodos_pago/edit';
                $data['alertas'] = $this->check_min_stock();
                $this->load->view('layouts/admin',$data);
            }
        }
        else
            show_error('The metodos_pago you are trying to edit does not exist.');
    } 

    /*
     * Deleting metodos_pago
     */
    function remove($id)
    {
        $metodos_pago = $this->Metodos_pago_model->get_metodos_pago($id);

        // check if the metodos_pago exists before trying to delete it
        if(isset($metodos_pago['id']))
        {
            $this->Metodos_pago_model->delete_metodos_pago($id);
            redirect('metodos_pago/index');
        }
        else
            show_error('The metodos_pago you are trying to delete does not exist.');
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
