<?php

class Ventas_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    function insert_datos_nota($params){
        $this->db->insert('ventas',$params);
        return $this->db->insert_id();
    }

    function insert_datos_detalle($params){
        $this->db->insert_batch('detalles',$params);        
    }

    function insert_datos_abono($params){
        $this->db->insert_batch('pagos',$params);   
    }
    function insert_abono_diferido($params){
        $this->db->insert('pagos',$params);
    }

    function abonosNota($folio){
        $this->db->select_sum('importe');
        $this->db->where('id_venta',$folio);
        return $this->db->get('pagos')->result_array();
    }

    function importeTotalNota($folio){
        $this->db->select_sum('importe');
        $this->db->where('id_venta',$folio);
        return $this->db->get('detalles')->result_array();
    }

    function updateStatus($folio,$status){
        $this->db->where('id', $folio);
        $this->db->update('ventas',array('pagada' => $status));
    }

    function delete_carrito_mesa($id){
        return $this->db->delete('carrito',array('id_mesa'=>$id));
    }

    function registrar_venta_a_credito($params){
        $this->db->insert('ventas_a_credito',$params);
    }
    function elimina_venta_a_credito($params){
        $this->db->delete('ventas_a_credito',$params);
    }

    function get_clientes_deben(){
        $sql="SELECT ventas_a_credito.id,ventas_a_credito.id_venta,clientes.nombre,clientes.id as id_cliente
                FROM ventas_a_credito 
                JOIN ventas
                ON ventas.id = ventas_a_credito.id_venta
                JOIN clientes
                ON ventas.id_cliente = clientes.id
                GROUP BY clientes.nombre";
        $query = $this->db->query($sql);
        return $query->result_array();
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
    function getTicket($id){
        $this->db->select('producto,cantidad,precio,importe');        
        $this->db->where('id_venta',$id);
        return $this->db->get('detalles')->result_array();
    }
    function getTotalTicket($id){
        $this->db->select_sum('importe');       
        $this->db->where('id_venta',$id);
        return $this->db->get('detalles')->result_array();
    }
    function getAbonosByIdVenta($id){
        $sql="SELECT metodos_pago.metodo,pagos.importe,DATE_FORMAT(pagos.fecha,'%d/%m/%Y') AS fecha 
                FROM pagos 
                JOIN metodos_pago
                ON metodos_pago.id = pagos.id_metodo
                WHERE pagos.id_venta =".$id;
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    function getCostoProductoByNombreProducto($name){
        $this->db->select('costo');
        $this->db->where('nombre',$name);
        return $this->db->get('productos')->row_array();
    }
    


    
    




















}//End of Line