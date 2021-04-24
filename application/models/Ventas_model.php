<?php

class Ventas_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /*
     * Get all ventas a credito count
     */
    function get_all_ventas_a_credito_count()
    {
        $this->db->from('ventas_a_credito');
        return $this->db->count_all_results();
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

    function update_cancelar($id){
        $this->db->where('id',$id);
        return $this->db->update('ventas',array('cancelada'=>1));
    }
    function insert_cancelar($params){
        return $this->db->insert('ventas_canceladas',$params);
    }
    
    /*
     * function to delete venta
     */
    function delete_venta($id)
    {
        return $this->db->delete('ventas',array('id'=>$id));
    }
    function delete_venta_credito($id)
    {
        return $this->db->delete('ventas_a_credito',array('id_venta'=>$id));
    }

    function get_clientes_deben($limit,$offset){
        
        if(isset($offset) && !empty($offset))
        {
            $this->db->select('ventas_a_credito.id, ventas_a_credito.id_venta, ventas.facturar, ventas.cliente AS cliente, DATE_FORMAT(ventas.fecha,\'%d/%m/%Y\') AS fecha');
            $this->db->from('ventas_a_credito');
            $this->db->join('ventas', 'ventas.id = ventas_a_credito.id_venta');
            $this->db->where('ventas.cotizar', 0);
            $this->db->where('ventas.cancelada', 0);
            $this->db->order_by('ventas_a_credito.id', 'desc');
            $this->db->limit($limit, $offset);
        }else{
            $this->db->select('ventas_a_credito.id, ventas_a_credito.id_venta, ventas.facturar, ventas.cliente AS cliente, DATE_FORMAT(ventas.fecha,\'%d/%m/%Y\') AS fecha');
            $this->db->from('ventas_a_credito');
            $this->db->join('ventas', 'ventas.id = ventas_a_credito.id_venta');
            $this->db->where('ventas.cotizar', 0);
            $this->db->where('ventas.cancelada', 0);
            $this->db->order_by('ventas_a_credito.id', 'desc');
            $this->db->limit($limit, 0);
        }
        $query = $this->db->get();
        return $query->result_array();
        
        
        
        //------------------
        // $sql="SELECT ventas_a_credito.id,ventas_a_credito.id_venta,ventas.facturar,ventas.cliente as cliente,DATE_FORMAT(ventas.fecha,'%d/%m/%Y') AS fecha
        //         FROM ventas_a_credito 
        //         JOIN ventas
        //         ON ventas.id = ventas_a_credito.id_venta
        //         AND ventas.cotizar = false";
        // $query = $this->db->query($sql);
        // return $query->result_array();
    }
    function get_ventas_clientes_deben($id_cliente){
        $sql="SELECT ventas_a_credito.id_venta, DATE_FORMAT(ventas.fecha,'%d/%m/%Y') AS niceDate
                FROM ventas_a_credito 
                JOIN ventas
                ON ventas.id = ventas_a_credito.id_venta
                JOIN clientes
                ON ventas.id_cliente = clientes.id
                WHERE clientes.id=".$id_cliente;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    function importeTotalNota($folio){
        $this->db->select_sum('importe');
        $this->db->where('id_venta',$folio);
        return $this->db->get('detalles')->result_array();
    }
    function abonosNota($folio){
        $this->db->select_sum('importe');
        $this->db->where('id_venta',$folio);
        return $this->db->get('abonos')->result_array();
    }
    function insert_abono($params){
        $this->db->insert('abonos',$params);   
    }
    function ifFacturarNota($folio){
        $this->db->select('facturar');
        $this->db->where('id',$folio);
        return $this->db->get('ventas')->result_array();
    }

    function get_all_ventas_search_by_folio($tipo,$folio)
    {                    
        switch ($tipo) {
            case 'pendientes':
                $this->db->select('ventas_a_credito.id, ventas_a_credito.id_venta, ventas.facturar, ventas.cliente AS cliente, DATE_FORMAT(ventas.fecha,\'%d/%m/%Y\') AS fecha');
                $this->db->from('ventas_a_credito');
                $this->db->join('ventas', 'ventas.id = ventas_a_credito.id_venta');
                $this->db->where('ventas.cotizar', 0);
                $this->db->like('ventas.id',$folio);
                $this->db->order_by('ventas_a_credito.id', 'desc');
                $this->db->limit(RECORDS_PER_PAGE);
                $query = $this->db->get();
                return $query->result_array();
                break;
            
            default:
                # code...
                break;
        }  
    }

    function get_all_ventas_search_by_cliente($tipo,$cliente)
    {                   
        switch ($tipo) {
            case 'pendientes':
                $this->db->select('ventas_a_credito.id, ventas_a_credito.id_venta, ventas.facturar, ventas.cliente AS cliente, DATE_FORMAT(ventas.fecha,\'%d/%m/%Y\') AS fecha');
                $this->db->from('ventas_a_credito');
                $this->db->join('ventas', 'ventas.id = ventas_a_credito.id_venta');
                $this->db->where('ventas.cotizar', 0);
                $this->db->like('ventas.cliente',$cliente);
                $this->db->order_by('ventas_a_credito.id', 'desc');
                $this->db->limit(RECORDS_PER_PAGE);
                $query = $this->db->get();
                return $query->result_array();
                break;
            
            default:
                # code...
                break;
        }         
    }






}
