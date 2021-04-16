<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Actualiza extends MY_Controller{
    function __construct()
    {
        parent::__construct();        
    }

    /**
     * ACTUALIZA MESA VIA AJAX POST
     */
    function mesa_draggable(){
        $this->load->model('Mesa_model');
        $idpost = $this->input->post('id');
        $id = $this->getIdMesa($idpost);
        $params = array(
            'arriba' => $this->input->post('item1'),
            'izquierda' => $this->input->post('item2')
        );
        $response['success'] = $this->Mesa_model->update_mesa($id,$params);
        echo json_encode($id);
    }
    function mesa_resizable(){
        $this->load->model('Mesa_model');
        $idpost = $this->input->post('id');
        $id = $this->getIdMesa($idpost);
        $params = array(
            'alto' => $this->input->post('item1'),
            'ancho' => $this->input->post('item2'),
        );
        $response['success'] = $this->Mesa_model->update_mesa($id,$params);
        echo json_encode($response);
    }
    function getIdMesa($id){
        $text = strval($id);
        $tam = strlen($text);
        $n = substr($id,3,$tam);
        return intval($n);
    }






}