<?php
defined('BASEPATH') or exit('No direct script access allowed');

Class Reportes_model extends CI_model{
    
    function reportToday($hoy){
        $sql="SELECT detalles.producto, detalles.costo,
                SUM(detalles.cantidad) AS cantidad, 
                detalles.precio, 
                SUM(detalles.importe) AS total 
                FROM detalles 
                JOIN ventas
                ON ventas.id = detalles.id_venta
                WHERE ventas.fecha
                LIKE '%".date('Y-m-d', strtotime($hoy))."%'
                GROUP BY producto";
        $query = $this->db->query($sql);
        return $query->result_array();
        //return $sql;
    }
    function reportFecha($fecha){
        $sql="SELECT detalles.producto, 
                SUM(detalles.cantidad) AS cantidad, 
                detalles.precio, 
                SUM(detalles.importe) AS total 
                FROM detalles 
                JOIN ventas
                ON ventas.id = detalles.id_venta
                WHERE ventas.fecha
                LIKE '%".date('Y-m-d', strtotime($fecha))."%'
                GROUP BY producto";
        $query = $this->db->query($sql);
        return $query->result_array();
        //return $sql;
    }
    function reportRango($desde,$hasta){
        $sql="SELECT detalles.producto, 
        SUM(detalles.cantidad) AS cantidad, 
        detalles.precio, 
        SUM(detalles.importe) AS total 
        FROM detalles 
        JOIN ventas
        ON ventas.id = detalles.id_venta
        WHERE ventas.fecha  BETWEEN '". date('Y-m-d 00:00:00', strtotime($desde))." ' and '". date('Y-m-d 23:59:59', strtotime($hasta))."'
        GROUP BY producto";
        $query = $this->db->query($sql);
        return $query->result_array();
        //return $sql;
    }
    
}