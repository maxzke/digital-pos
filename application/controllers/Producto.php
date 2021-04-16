<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Producto extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);
        if( !$this->is_role('admin') ){
            redirect('restringido');
        }
        $this->load->model('Producto_model');
    } 

    /**
     * ABASTECER PRODUCTO
     */
    public function abastecer(){
        $this->load->library('form_validation');
        $this->load->library('session'); 

		$this->form_validation->set_rules('id','Nombre','required');
        $this->form_validation->set_rules('cantidad','Precio','required|numeric');
        if($this->form_validation->run()){
            $id = $this->input->post('id');
            $cantidad = $this->input->post('cantidad');
            $result = $this->Producto_model->addStock($id,$cantidad);
            $text = "Agregado ".$result;
            $this->session->set_flashdata('item',$text);
            //print_r($result);
            redirect('productos');
        }else{
            redirect('productos');
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

    /*
     * Listing of productos
     */
    function index()
    {
        $data['productos'] = $this->Producto_model->get_all_productos();

        $this->load->model('Categoria_model');
        $data['categorias'] = $this->Categoria_model->get_all_categorias();
        $data['alertas'] = $this->check_min_stock();
        $data['_view'] = 'producto/index';
        $this->load->view('layouts/admin',$data);
    }

    /*
     * Adding a new producto
     */
    function add()
    {   
        $this->load->library('form_validation');

		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('precio','Precio','required|numeric');
		$this->form_validation->set_rules('stock','Stock','integer');
		$this->form_validation->set_rules('id_categoria','Id Categoria','required');
		$this->form_validation->set_rules('costo','Costo','numeric');
		
		if($this->form_validation->run())     
        {   
            /*====================================================
            =            Validar Archivo FOTO a Subir            =
            ====================================================*/     
            /**
             * GENERO NOMBRE ALEATOIO
             */       
            $extension = $_FILES['imagen']['type'];
            $randonStr=rand(); 
            $randonName = sha1($randonStr);
            //ENCRIPTO NOMBRE DEL ARCHIVO
            $config['encrypt_name'] = TRUE;
            $config['upload_path']='./assets/img/productos/';
            $config['allowed_types']='jpg|png|gif';
            $config['max_size']             = 0;
            $config['max_width']            = 0;
            $config['max_height']           = 0;
            $config['max_filename']         = 0;

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('imagen')){

                $data['img_error'] = "Formato no valido";
                $this->load->model('Categoria_model');
                $data['all_categorias'] = $this->Categoria_model->get_all_categorias();
                
                $data['_view'] = 'producto/add';
                $this->load->view('layouts/admin',$data);
                
            }
            else{   
                
                    $data = array('upload_data' => $this->upload->data());
                    //RECUPERO NOMBRE DEL PRODUCTO CON EXTENSION ENCRIPTADO
                    $str = $data['upload_data']['file_name'];
                    $rutaFoto = base_url()."assets/img/productos/".$str;
                    $inventariable = 0;
                    if ($this->input->post('inventariable')) {
                        $inventariable = 1;
                    }
                    $params = array(
                        'inventariable' => $inventariable,
                        'id_categoria' => $this->input->post('id_categoria'),
                        'stock_minimo' => $this->input->post('stock_minimo'),
                        'nombre' => $this->input->post('nombre'),
                        'precio' => $this->input->post('precio'),
                        'costo' => $this->input->post('costo'),
                        'imagen' => $rutaFoto,
                        'stock' => $this->input->post('stock'),
                    );
                    
                    $producto_id = $this->Producto_model->add_producto($params);
                    redirect('producto/index');
                }
        }
        else
        {
			$this->load->model('Categoria_model');
			$data['all_categorias'] = $this->Categoria_model->get_all_categorias();
            $data['img_error'] = "";
            $data['_view'] = 'producto/add';
            $data['alertas'] = $this->check_min_stock();
            $this->load->view('layouts/admin',$data);
        }
    }  

    /*
     * Editing a producto
     */
    function edit($id)
    {   
        // check if the producto exists before trying to edit it
        $data['producto'] = $this->Producto_model->get_producto($id);
        
        if(isset($data['producto']['id']))
        {
            $this->load->library('form_validation');

			$this->form_validation->set_rules('nombre','Nombre','required');
			$this->form_validation->set_rules('precio','Precio','required|decimal');
			$this->form_validation->set_rules('stock','Stock','integer');
			$this->form_validation->set_rules('id_categoria','Id Categoria','required');
			$this->form_validation->set_rules('costo','Costo','decimal');
		
			if($this->form_validation->run())     
            {   
                $params = array(
					'inventariable' => $this->input->post('inventariable'),
					'id_categoria' => $this->input->post('id_categoria'),
					'stock_minimo' => $this->input->post('stock_minimo'),
					'nombre' => $this->input->post('nombre'),
					'precio' => $this->input->post('precio'),
					'costo' => $this->input->post('costo'),
					'imagen' => $this->input->post('imagen'),
					'stock' => $this->input->post('stock'),
                );

                $this->Producto_model->update_producto($id,$params);            
                redirect('producto/index');
            }
            else
            {
				$this->load->model('Categoria_model');
				$data['all_categorias'] = $this->Categoria_model->get_all_categorias();
                $data['alertas'] = $this->check_min_stock();
                $data['_view'] = 'producto/edit';
                $this->load->view('layouts/admin',$data);
            }
        }
        else
            show_error('The producto you are trying to edit does not exist.');
    } 

    /*
     * Deleting producto
     */
    function remove($id)
    {
        $producto = $this->Producto_model->get_producto($id);

        // check if the producto exists before trying to delete it
        if(isset($producto['id']))
        {
            $this->Producto_model->delete_producto($id);
            redirect('producto/index');
        }
        else
            show_error('The producto you are trying to delete does not exist.');
    }
    
}
