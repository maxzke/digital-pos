<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
*   REST_Controller EXTIENDE DE MY_Controller
*   AHORA POSEE LOS METODOS DE COMUNITY AUTH
*   Y METODOS PROPIOS REST
*/
require_once(APPPATH . 'libraries/REST_Controller.php');

use Restserver\libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Pos extends REST_Controller
{

    /**
     * Params para Nota
     */
    private $folio; //id de la venta
    private $id_cliente;
    private $id_usuario;
    private $id_mesa;
    private $statusNota;
    private $netoPagado;
    private $importeNota;
    private $saldoNota;
    /**
     * Params para Detalle
     */
    private $carrito = array();
    private $carritoTicket = array();
    /**
     * Params para Abono
     */
    private $pagos;
    //Saldo total cliente debe
    private $saldo = 0;

    function __construct()
    {
        parent::__construct();
        $this->require_min_level(1);
        $this->load->model('pos_model');
        $this->load->model('Cortes_model');
    }

    function index_get()
    {
        $data['_view'] = 'pos/index';
        $data['active'] = 'pos';
        $this->load->view('layouts/main', $data);
    }

    /**
     * Guarda Nota de Venta
     */
    public function store_post()
    {
        $parametros = $this->input->post('params');

        $id_venta = $this->registrar_nota(
            $parametros['datos']['facturar'],
            $parametros['datos']['cotizar'],
            strtolower($parametros['datos']['cliente']),
            strtolower($parametros['datos']['direccion']),
            $parametros['datos']['telefono'],
            strtolower($parametros['datos']['empresa']),
            $this->auth_username
        );

        $this->registrar_detalle($id_venta, $parametros['carrito']);

        $this->registrar_abono(
            $id_venta,
            $parametros['datos']['metodo_pago'],
            $parametros['datos']['abono']
        );

        $rest = 0;
        if (floatval($parametros['datos']['abono']) < floatval($parametros['datos']['total'])) {
            $this->registrar_credito($id_venta);
            $rest = floatval($parametros['datos']['total']) - floatval($parametros['datos']['abono']);
        } else {
            $rest = floatval($parametros['datos']['abono']) - floatval($parametros['datos']['total']);
            $this->load->model('ventas_model');
            $this->ventas_model->set_as_pagado($id_venta);
        }

        $pdfData = $this->email(
            $id_venta,
            strtolower($parametros['datos']['cliente']),
            strtolower($parametros['datos']['direccion']),
            $parametros['datos']['telefono'],
            strtolower($parametros['datos']['empresa']),
            $parametros['datos']['facturar'],
            $parametros['datos']['subtotal'],
            $parametros['datos']['iva'],
            $parametros['datos']['total'],
            $parametros['datos']['abono'],
            $parametros['datos']['metodo_pago'],
            $rest,
            $parametros['carrito']
        );

        $respuesta = array(
            'success' => true,
            'params' => strtolower($parametros['datos']['cliente']),
            'pdfData' => $pdfData
        );

        $this->response($respuesta, 200);
    }
    /**
     * Guarda datos ventas:table
     */
    private function registrar_nota($facturar, $cotizar, $cliente, $direccion, $telefono, $empresa, $usuario)
    {
        $params = array(
            'facturar' => $facturar,
            'cotizar' => $cotizar,
            'cliente' => $cliente,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'empresa' => $empresa,
            'usuario'=>$this->auth_username
        );
        $folio = $this->pos_model->insert_datos_nota($params);
        return $folio;
    }
    /**
     * Guarda detalles cart
     * detalles:table
     */
    private function registrar_detalle($id_venta, $carrito)
    {
        foreach ($carrito as $key => $value) {
            $params[] = array(
                'id_venta' => $id_venta,
                'producto' => strtolower($value['producto']),
                'cantidad'  => $value['cantidad'],
                'precio'   => $value['precio'],
                'importe'  => $value['importe']
            );
        }
        $this->pos_model->insert_datos_detalle($params);
    }
    /**
     * Guarda Abono
     */
    private function registrar_abono($id_venta, $metodo, $importe)
    {
        $params = array(
            'id_venta' => $id_venta,
            'metodo' => $metodo,
            'importe' => $importe,
            'usuario' => $this->auth_username
        );
        $this->pos_model->insert_abono($params);
        if (strtolower($metodo) === 'efectivo') {
            /**
             * Cada que hay un ingreso en EFECTIVO
             */
            $this->Cortes_model->add_efectivo_caja($importe);
        } else {
            $this->Cortes_model->add_cuenta_banco($importe);
        }
    }

    /**
     * Guarda Venta a Credito
     */
    private function registrar_credito($id_venta)
    {
        $params = array(
            'id_venta' => $id_venta,
            'usuario' => $this->auth_username
        );
        $this->pos_model->insert_credito($params);
    }

    private function email($folio, $cliente, $direccion, $telefono, $empresa, $factura, $subtotal, $iva, $total, $abono, $metodo, $restante, $cart)
    {
        $to = "digital-estudio@live.com.mx,ramzdav@hotmail.com";
        $subject = "Nota de Venta " . $folio;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        if ($factura == 1) {
            $facturar = "Si";
        } else {
            $facturar = "No";
        }
        $message = "
            <html>
            <head>
            <title>HTML</title>
            </head>
            <body style='background-color: #e7e7e7; padding-left: 20px; padding-top: 10px; padding-bottom: 20px;'>
            <h2>Nota de Venta Folio: {$folio} </h2>
            <span><strong>Cliente: </strong> {$cliente}</span><br>
            <span><strong>Direcci??n : </strong> {$direccion} </span><br>
            <span><strong>Telefono : </strong> {$telefono} </span><br>
            <span><strong>Empresa : </strong> {$empresa} </span><br>
            <span><strong>Facturar : </strong> {$facturar} </span><br>
            <span><strong>SubTotal : </strong> {$subtotal} </span><br>
            <span><strong>Iva : </strong> {$iva} </span><br>
            <span><strong>Total : </strong> {$total} </span><br>
            <span><strong>Abono : </strong> {$abono} <strong>Metodo Pago : </strong> {$metodo}</span><br>
            <span><strong>Restante : </strong> {$restante} </span><br><br>
            <table style='border: 1px solid black;'>
                <thead style='background-color: black; color: white;'>
                    <tr>
                        <td>Cantidad</td>
                        <td>Descripcion</td>
                        <td>Precio</td>
                        <td>SubTotal</td>
                    </tr>
                </thead>
                <tbody>";
        foreach ($cart as $key => $item) :
            $message .= "<tr>
                        <td>" . $item['cantidad'] . "</td>
                        <td>" . $item['producto'] . "</td>
                        <td>" . $item['precio'] . "</td>
                        <td>" . number_format($item['importe'], 2, ".", ",") . "</td>
                    </tr>";
        endforeach;
        $message .= "</tbody>
            </table>
            </body>
            </html>";

        $params = array(
            'name' => $subject,
            'data' => $message
        );

        mail($to, $subject, $message, $headers);
        return $params;
    }
}
