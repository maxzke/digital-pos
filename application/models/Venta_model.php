<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Venta_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get venta by id
     */
    function get_venta($id)
    {
        return $this->db->get_where('ventas',array('id'=>$id))->row_array();
    }
        
    /*
     * Get all ventas
     */
    function get_all_ventas()
    {
        $this->db->order_by('id', 'desc');
        return $this->db->get('ventas')->result_array();
    }
        
    /*
     * function to add new venta
     */
    function add_venta($params)
    {
        $this->db->insert('ventas',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update venta
     */
    function update_venta($id,$params)
    {
        $this->db->where('id',$id);
        return $this->db->update('ventas',$params);
    }
    
    /*
     * function to delete venta
     */
    function delete_venta($id)
    {
        return $this->db->delete('ventas',array('id'=>$id));
    }
}
