<?php

class Cortes_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Tabla::abonos
    */
    function suma_importe($metodo){
        $this->db->select_sum('importe');
        $this->db->where('metodo',$metodo);
        return $this->db->get('abonos')->result_array();
    }
    /**
     * Tabla::cuenta
     */
    function suma_importes_cuenta(){
        $this->db->select_sum('importe');
        return $this->db->get('cuenta_depositos')->result_array();
    }
    function insert_deposito($importe,$usuario){
        $this->db->insert('cuenta_depositos',array('importe'=>$importe,'usuario'=>$usuario));
        return $this->db->insert_id();
    }
    








}//end of line