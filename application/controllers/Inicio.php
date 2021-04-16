<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Inicio extends MY_Controller{

	public function __construct()
	{
		parent::__construct();

		// Force SSL
		//$this->force_ssl();

    }

    public function index(){
        if( $this->require_min_level(1) ){

            $admin = $this->is_role('admin');
			if ($admin) {
				$data['nivel'] = "Hola Administrador";
			}
			$manager = $this->is_role('mesero');
			if ($manager) {
				$data['nivel'] =  "Hola mesero";
			}
			$vendedor = $this->is_role('cocina');
			if ($vendedor) {
				$data['nivel'] =  "Hola cocina";
			}						
            
            print_r($data);
        }
    }
}