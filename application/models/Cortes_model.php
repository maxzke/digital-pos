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
    function total_cobrado_en($metodo,$fechaInicio,$fechaFin){
        $this->db->select_sum('importe');
        $this->db->like('metodo',$metodo);
        $this->db->where('fecha >=',$fechaInicio);
        $this->db->where('fecha <=',$fechaFin);
        $this->db->where('usuario', $this->auth_username);
        return $this->db->get('abonos')->result_array();
    }
    /**
     * Tabla::cuenta_depositos
     */
    function suma_importes_cuenta(){
        $this->db->select_sum('importe');
        $this->db->where('usuario', $this->auth_username);
        return $this->db->get('cuenta_depositos')->result_array();
    }
    function insert_deposito($importe,$usuario){
        $this->db->insert('cuenta_depositos',array('importe'=>$importe,'usuario'=>$usuario));
        return $this->db->insert_id();
    }
    /**
     * Tabla::cuenta_caja
     */
    function get_importe_caja($tipo){
        $this->db->select($tipo);
        $this->db->where('usuario', $this->auth_username);
        return $this->db->get('cuenta_caja')->result_array();
    }
    /**
     * Agrega
     */
    function add_efectivo_caja($importe){
        $this->db->select('caja');
        $this->db->where('usuario', $this->auth_username);
        $caja = $this->db->get('cuenta_caja')->result_array();
        $nuevo_importe = floatval($caja[0]['caja']) + floatval($importe);
        
        $this->db->where('usuario', $this->auth_username);
        $this->db->update('cuenta_caja', array('caja' =>$nuevo_importe));
    
    }
    /**
     * Substrae
     */
    function subs_efectivo_caja($importe){
        $this->db->select('caja');
        $this->db->where('usuario', $this->auth_username);
        $caja = $this->db->get('cuenta_caja')->result_array();
        $nuevo_importe = floatval($caja[0]['caja']) - floatval($importe);
        
        $this->db->where('usuario', $this->auth_username);
        $this->db->update('cuenta_caja', array('caja' =>$nuevo_importe));
    
    }
    /**
     * Agrega
     */
    function add_cuenta_banco($importe){
        $this->db->select('banco');
        $this->db->where('usuario', $this->auth_username);
        $caja = $this->db->get('cuenta_caja')->result_array();
        $nuevo_importe = floatval($caja[0]['banco']) + floatval($importe);
        
        $this->db->where('usuario', $this->auth_username);
        $this->db->update('cuenta_caja', array('banco' =>$nuevo_importe));
    }

    /**
     * GET PAGOS BY RANGO FECHAS
     * GET ID::TOTAL
     */
    function get_pagos_between($desde,$hasta){
        $this->db->select('id,total');
        $this->db->where('fecha >=',$desde);
        $this->db->where('fecha <=',$hasta);
        $this->db->where('usuario', $this->auth_username);
        return $this->db->get('pagos')->result_array();
    }
    function get_suma_pagos_between($desde,$hasta){
        $this->db->select_sum('total');
        $this->db->like('metodo','efectivo');
        $this->db->where('fecha >=',$desde);
        $this->db->where('fecha <=',$hasta);
        $this->db->where('usuario', $this->auth_username);
        $this->db->like('metodo','efectivo');
        return $this->db->get('pagos')->result_array();
    }

     /**
     * GET DEPOSITOS A CUENTA BY RANGO FECHAS
     * GET ID::IMPORTE
     */
    function get_depositos_between($desde,$hasta){
        $this->db->select('id,importe');
        $this->db->where('fecha >=',$desde);
        $this->db->where('fecha <=',$hasta);
        $this->db->where('usuario', $this->auth_username);
        return $this->db->get('cuenta_depositos')->result_array();
    }
    function get_suma_depositos_between($desde,$hasta){
        $this->db->select_sum('importe');
        $this->db->where('fecha >=',$desde);
        $this->db->where('fecha <=',$hasta);
        $this->db->where('usuario', $this->auth_username);
        return $this->db->get('cuenta_depositos')->result_array();
    }

    /**
     * CADA QUE SE CREA UN NUEVO USUARIO
     * SE INICIALIZA SU CAJA EN LA TABLA
     * CUENTA_CAJA
     */
    function create_cuenta_caja($params){        
        $this->db->insert('cuenta_caja',$params);
    }
    function create_cuenta_cortes($params){        
        $this->db->insert('cortes',$params);
    }
    
    function get_desde(){
        $this->db->select('desde');
        return $this->db->get('cortes')->result_array();
    }








}//end of line