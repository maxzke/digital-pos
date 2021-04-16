<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Admin extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);
        if( !$this->is_role('admin') ){
            redirect('restringido');
        }
    }

    public function index(){
        $data['_view'] = 'admin/index';
        $this->load->view('layouts/admin',$data);
    }
}