<?php

class Pagos_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get pago by id
     */
    function get_pago($id)
    {
        return $this->db->get_where('pagos',array('id'=>$id))->row_array();
    }
        
    /*
     * Get all pagos
     */
    function get_all_pagos()
    {
        $this->db->order_by('id', 'desc');
        return $this->db->get('pagos')->result_array();
    }
        
    /*
     * function to add new pago
     */
    function add_pago($params)
    {
        $this->db->insert('pagos',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update pago
     */
    function update_pago($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('pagos',$params);
    }
    
    /*
     * function to delete pago
     */
    function delete_pago($id)
    {
        return $this->db->delete('pagos',array('id'=>$id));
    }
}
