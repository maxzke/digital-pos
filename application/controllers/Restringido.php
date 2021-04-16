<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Restringido extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);
    } 

    function index(){
        $data['_view'] = 'errors/custom_404';
        $this->load->view('layouts/main',$data);
    }

    function sin_permiso(){
        $data['page']="inicio";
        $data['_view'] = 'errors/custom_401';
        $this->load->view('layouts/main',$data);
    }

}