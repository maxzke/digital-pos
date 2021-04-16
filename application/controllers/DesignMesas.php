<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DesignMesas extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);
        if( !$this->is_role('admin') ){
            redirect('restringido');
        }
        // $this->load->model('Mesa_model');
    } 

    function index(){
        $this->load->model('Seccion_model');        
        $this->load->model('Mesa_model');
        $data['mesa_ocupada'] = $this->Mesa_model->get_mesa_ocupada();
        $data['seccion'] = $this->Seccion_model->get_all_seccion();
        $data['mesas'] = $this->Mesa_model->get_all_mesas();
        $data['_view'] = 'point_of_sale/index';
        $this->load->view('layouts/mainDesignMesas',$data);
    }











}