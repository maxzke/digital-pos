<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
*   REST_Controller EXTIENDE DE MY_Controller
*   AHORA POSEE LOS METODOS DE COMUNITY AUTH
*   Y METODOS PROPIOS REST
*/
require_once(APPPATH.'libraries/REST_Controller.php');
use Restserver\libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
/**
 * PRINT TICKET POS
 */
require __DIR__ . '../autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class Ventas extends REST_Controller {
    /**
     * Params para Nota
     */
    private $folio;//id de la venta
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
    private $saldo=0;


    function __construct(){        
        parent::__construct();
        $this->load->model('ventas_model');
        $this->load->model('producto_model');        
        $this->require_min_level(1);
    }

    public function index_get(){
        if( $this->require_min_level(1) ){
            redirect('punto-de-venta');
        }
    }
    public function index_post(){
        
    }

    public function registrar_venta_post(){
        if( $this->require_min_level(1) ){
            $parametros = $this->input->post('data');
            
            $msgArray = [];
            $band = true;

            /**
             * Si no existe cliente
             */
            if ($parametros['id_cliente'] == '') {
                $msg = array(
                    'msg' => 'Agregar Cliente'
                );
                array_push($msgArray,$msg);
                $band = false;
            }
            /**
             * Si no existe carrito
             */
            if (!isset($parametros['carrito'])) {
                $msg = array(
                    'msg' => 'Agregar Articulos'
                );
                array_push($msgArray,$msg);
                $band = false;
            }

            if ($band == true) {
                $this->id_cliente = $parametros['id_cliente'];
                $this->id_usuario = $parametros['id_usuario'];
                $this->id_mesa = $parametros['id_mesa'];
                $this->carrito = $parametros['carrito'];
                $this->pagos = $parametros['pagos'];
                $this->importe_pago = $parametros['importe_pago'];
                $this->netoPagado = $parametros['netoPagado'];
                
                $this->folio = $this->registrar_nota();
                $this->importeNota = $this->registrar_detalle();
                /**
                 * By default status = debe on mysql
                 */
                $this->checkStatus();
                $this->registrar_abono();
                $this->elimina_carrito_this_mesa();
                $this->renombrar_alias_mesa($parametros['id_mesa']);
                /**
                 * MANDA IMPRIMIR TICKET
                 */
                //$this->printTicket($this->importeNota,$this->netoPagado,$this->carrito);

                $respuesta = array(
                        'success' => true,
                        'folio' => $this->folio,
                        'usuario' => $this->id_usuario,
                        'carrito' => $this->carrito,
                        'importe_pago' => $this->importe_pago,
                        'importeNota' => $this->importeNota,
                        'statusNota' => $this->statusNota,
                        'pagos' => $this->pagos,
                        'cliente' => $this->id_cliente,
                        'error' => $msgArray
                    );
                $this->response($respuesta,200);
            }else{
                $respuesta = array(
                    'success' => false,
                    'error' => $msgArray,
                    'params' => $parametros
                );
            $this->response($respuesta,200);
            }
        }
    }
    /**
     * RENOMBRA alias MESA AL FINALIZAR LA VENTA Y LIBERAR LA MESA
     */
    private function renombrar_alias_mesa($id){
        $this->load->model('Mesa_model');
        $params = array(
            'alias' => "",
        );
        $this->Mesa_model->rename_mesa($params,$id);
    }

    /**
     * OBTIENE LOS ARTICULOS DEL TICKET
     * RECUBE ID_VENTA
     */
    function get_ticket_by_id_venta_post(){
        //recibe id_cliente porque la funcion js se reutiliza y manda post id_cliente
        //pero recibe id_venta
        $id = $this->input->post('id_cliente');
        $data['ticket'] = $this->ventas_model->getTicket($id);
        $data['total_ticket'] = $this->ventas_model->getTotalTicket($id);
        $data['abonos'] = $this->ventas_model->getAbonosByIdVenta($id);
        $data['success'] = TRUE;
        $this->response($data,200);
    }

    /**
     * CARGA LISTADO DE CLIENTES CON CREDITOS A PAGAR
     */
    function creditos_get(){
        $data['clientes'] = $this->ventas_model->get_clientes_deben();
        $this->load->model('Metodos_pago_model');
        $data['metodos_pago'] = $this->Metodos_pago_model->get_all_metodos_pago();
        $data['alertas'] = $this->check_min_stock();
        $data['_view'] = 'creditos/index';
        $this->load->view('layouts/admin',$data);
        //print_r($data);
    }

    function creditos_cliente_post(){
        $id_cliente = $this->input->post('id_cliente');
        $data['clientes'] = $this->ventas_model->get_ventas_clientes_deben($id_cliente);
        foreach ($data['clientes'] as $key) {
            $this->getSaldoNota($key['id_venta']);
        }
        $data['saldo'] = $this->saldo;
        $data['success'] = TRUE;
        $this->response($data, 200);
    }

    private function registrar_venta_a_credito($id_venta){
        $params = array(
            'id_venta' => $id_venta
        ); 
        $this->ventas_model->registrar_venta_a_credito($params);
    }
    private function elimina_venta_a_credito($id_venta){
        $params = array(
            'id_venta' => $id_venta
        ); 
        $this->ventas_model->elimina_venta_a_credito($params);
    }

    private function registrar_nota(){
            $params = array(
                'id_cliente' => $this->id_cliente,
                'id_usuario' => $this->id_usuario,
                'id_mesa' => $this->id_mesa,
                'pagada' => 0,
                //'status'  => 'debe' //By default is debe on mysql
            ); 
            $folio = $this->ventas_model->insert_datos_nota($params);
            return $folio;
    }

    private function registrar_detalle(){
            $importeN = 0;
            foreach ($this->carrito as $key => $value) {
                $costoThis = $this->getCostoProductoByNombreProduct($value['name']);
                $params[] = array(
                    'id_venta' => $this->folio,
                    'producto' => $value['name'],
                    'cantidad'  => $value['count'],
                    'precio'   => ($value['price'])/($value['count']),
                    'importe'  => $value['price'],
                    'costo' => $costoThis
                );
                //array_push($this->carritoTicket,$params);
                $importeN += $value['price'];
                /**
                 * DESCUENTA CADA PRODUCTO DE STOCK
                 */
                $this->stock_descuenta($value['name'],$value['count']);
                
            }         
            $this->ventas_model->insert_datos_detalle($params);
            return $importeN;
    }
    /**
     * DESCUENTA CANTIDADES STOCK
     * RECIBE 2 PARAMETROS:
     * NOMBRE-PRODUCTO Y CANTIDAD
     */
    private function stock_descuenta($nombreProducto,$cantidadProducto){
        // $nombreProducto = $this->input->post('nombre');
        // $cantidadProducto = $this->input->post('cantidad');        
        $resutl = $this->producto_model->descuentaStock($nombreProducto,$cantidadProducto);
        //echo json_encode($resutl);
    }
    private function getCostoProductoByNombreProduct($nombreProducto){
        $costo = $this->ventas_model->getCostoProductoByNombreProducto($nombreProducto);
        return floatval($costo['costo']);
    }
    // public function costo_post(){
    //     $nombre = $this->input->post('nombre');
    //     $costo = $this->getCostoProductoByNombreProduct($nombre);
    //     //$num = floatval($costo['costo']);
    //     $this->response($costo,200);
    // }

    /**
     * ABONOS PAGOS DIFERIDOS DE VENTAS A CREDITO
     */
    public function recibe_pago_venta_a_credito_post(){
        /**
         * PARAMETROS VIENEN EN id_cliente
        */
        $params = $this->input->post('id_cliente');
        // $paramsResponse = array (
        //         'success' => TRUE,
        //         'data' => $params,
        //         'pago' => $params['importe_pago']
        //     );
        // $this->response($paramsResponse,200);
        //********************************************** */
        $pagoGeneralRecibido = floatval($params['importe_pago']);
        /**
         * BUSCA VENTAS CON SALDO PENDIENTE
         */
        $data['ventas'] = $this->ventas_model->get_ventas_clientes_deben($params['id_cliente']);
        foreach ($data['ventas'] as $key) {
            if ($pagoGeneralRecibido>0) {
                
                //$cambioRest = $pagoGeneralRecibido - $this->saldo;
                $cambioRest = $this->abonarVenta_diferida($key['id_venta'],$pagoGeneralRecibido,$params['metodo_pago']);
                $pagoGeneralRecibido = $cambioRest;
            } 
        }
        $paramsResponse = array (
            'success' => TRUE,
            'data' => $params,
            'pago' => $pagoGeneralRecibido,
            'ventas' => $data,
            'metodo' => $params['metodo_pago']
        );
        $this->response($paramsResponse,200);

    }
    private function abonarVenta_diferida($id_venta,$importe_pagado,$id_forma_pago){
        
        $this->getSaldoNota($id_venta);
        $saldo = $this->saldo;
        

        $importe = 0;
        $cambio = 0;

        if ($importe_pagado>=$saldo) {
            $importe = $saldo;
            $cambio = $importe_pagado - $saldo;
            $status = "1";
            $this->ventas_model->updateStatus($id_venta,$status);
            $this->elimina_venta_a_credito($id_venta);
        }else{
            $importe = $importe_pagado;
        }
        $params = array(
            'id_venta' => $id_venta,
            'id_metodo' => $id_forma_pago,
            'importe' => $importe
        );        
        if ($importe > 0) {
            $this->ventas_model->insert_abono_diferido($params);
        }        
        return $cambio;
    }
    private function registrar_abono(){
        $tam = sizeof($this->pagos);
        if (isset($this->pagos) and $tam > 0) {
            $band=0;
            foreach ($this->pagos as $key => $value) {
                if ($value['importe'] > 0) {
                    $band=1;//POR LO MENOS 1 PAGO > 0 SERA GUARDADO
                    $params[] = array(
                        'id_venta' => $this->folio,
                        'id_metodo' => $value['metodo'],
                        'importe' => $value['importe']
                    );
                }
            }         
            if ($band==1) {
                $this->ventas_model->insert_datos_abono($params);
            }            
        }else{
            
        }
        
    }

    //******************************************** */
    private function getSaldoNota($id){
        $total = $this->getImporteNota($id);
        $abonos = $this->getAbonosNota($id);
        $this->saldo += $total[0]['importe'] - $abonos[0]['importe'];
    }
    private function getAbonosNota($folio){
        return $this->ventas_model->abonosNota($folio);
    }
    private function getImporteNota($folio){
        return $this->ventas_model->importeTotalNota($folio);
    }
    //******************************************** */
    
    private function checkStatus(){
        /**
         * 0 - debe
         * 1 - pagado
         */
        if ($this->importeNota <= $this->importe_pago) {
            //PAGADO
            $this->statusNota = '1';
            $this->updateStatusNota();   
            $this->elimina_venta_a_credito($this->folio);
            $this->importe_pago = $this->importeNota;
        }else{
            //DEBE
            $this->statusNota = '0';
            $this->registrar_venta_a_credito($this->folio);
        }
    }

    private function updateStatusNota(){
        $this->ventas_model->updateStatus($this->folio,$this->statusNota);
    }

    /**
     * GET CLIENTE ALL INFO CLIENTE BY ID_CLIENTE
     */
    public function getCliente_post(){
        $id = $this->input->post('id');
        //if( $this->require_min_level(1) ){
            $this->load->model('Cliente_model');
            $cliente = $this->Cliente_model->get_cliente($id);

            // check if the cliente exists before trying to delete it
            if(isset($cliente['id'])){
                $respuesta = array(
                    'success' => true,
                    'data' => $cliente
                );
                $this->response($respuesta,200);
            }else{
                $respuesta = array(
                    'success' => false,
                    'data' => ''
                );
                $this->response($respuesta,200);
            }
        //}
    }

    /**
     * ELIMINA CARRITO ASIGNADO A MESA PAGADA AL PAGAR LA MESA
     * BY ID MESA PAGADA
     */
    private function elimina_carrito_this_mesa(){
        $this->ventas_model->delete_carrito_mesa($this->id_mesa);
    }

    
    
    public function printTicket($importeNota,$pago,$detalles){
        /**
         * $pago: numer
         * $detalles: [
         *  {
         *      producto:'',
         *      cantidad:'',
         *      precio:'',
         *      importe:'',
         *  }
         * ]
         */
        try {

            //Fecha y hora actual
            date_default_timezone_set('America/Mexico_City');
            $fecha= date("d/m/Y H:i:s");
            //$hora= date("H:i:s");
            $connector = NULL;
            //El nombre de la impresora es POS-58
            $connector = new WindowsPrintConnector("POS-58");
            $printer = new Printer($connector);   

            //Establecemos los datos del TICKET a imprimir

            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            /**
             * PARA LOGO.PNG
             * RUTA: CONTROLLERS/LOGO.PNG
             */
            try{
                $logo = EscposImage::load(__DIR__."\logo.png", false);
                $printer->bitImage($logo);
            }catch(Exception $e){/*No hacemos nada si hay error*/
                $printer -> text($e -> getMessage()."\n");
            }
            $printer -> setTextSize(3, 1);
            //$printer -> text("MANHATTAN\n\n");
            //$printer -> text("CANTABAR\n\n");
            $printer -> feed(2);   
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> setTextSize(1, 1);
            $printer -> text("AV 16 DE SEPTIEMBRE #31\n");
            $printer -> text("COLONIA CENTRO\n");
            $printer -> text("LOMA BONITA, OAX\n");
            $printer -> text($fecha."\n");  
            //$printer -> text($hora);  
            //$printer -> feed(1);       
            $printer -> setEmphasis(false);
            $printer -> setTextSize(2, 1);
            $printer->text("\n----------------");
            $printer -> feed(1); 
            $printer -> setJustification(); // Reset

            $charset='UTF-8'; // o 'UTF-8' setea  
            $totalapagar = $importeNota;
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            //*********************************************************************************
            $CantAbono = $pago;
            $printer -> setTextSize(1, 1);
            $printer -> text("ARTICULOS\n");
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text("----------------\n\n");
            $printer -> setTextSize(1, 1);
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            foreach ($detalles as $item) {               
                //Mayusculas
                $descripcion = mb_convert_case($item['name'], MB_CASE_UPPER, "UTF-8");            
                //Elimino acentos y ñ        
                // $str = iconv($charset, 'ASCII//TRANSLIT', $descripcion);
                // $nuevo = preg_replace("/[^A-Za-z0-9 ]/", '', $str);

                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                //$printer -> text(wordwrap(utf8_encode($nuevo), 29, "\n    " ,TRUE)); 
                $printer -> text($descripcion); 
                $printer -> feed(1);    
                //$printer -> text("\n");
                //concateno CANT-PRECIO-IMPORTE
                $printer -> setJustification(Printer::JUSTIFY_RIGHT);
                $imp = $item['price'];
                $formatImporte = number_format($imp,2,".","," );
                /**
                 * FORMATO COLUMNAS
                 */
                $longImporte = strlen($formatImporte);
                $precioUnitario = ($item['price']/$item['count']);
                if ($longImporte==4) {
                    $contaten = $item['count']." x $".number_format($precioUnitario,2,".","," )."        $".$formatImporte;
                    $printer -> text($contaten."\n");
                }
                if ($longImporte==5) {
                    $contaten = $item['count']." x $".number_format($precioUnitario,2,".","," )."       $".$formatImporte;
                    $printer -> text($contaten."\n");
                }
                if ($longImporte==6) {
                    $contaten = $item['count']." x $".number_format($precioUnitario,2,".","," )."      $".$formatImporte;
                    $printer -> text($contaten."\n");
                }
                if ($longImporte==8) {
                    $contaten = $item['count']." x $".number_format($precioUnitario,2,".","," )."    $".$formatImporte;
                    $printer -> text($contaten."\n");
                }
                if ($longImporte==9) {
                    $contaten = $item['count']." x $".number_format($precioUnitario,2,".","," )."   $".$formatImporte;
                    $printer -> text($contaten."\n");
                }
                if ($longImporte==10) {
                    $contaten = $item['count']." x $".number_format($precioUnitario,2,".","," )."  $".$formatImporte;
                    $printer -> text($contaten."\n");
                }
                /**
                 * FIN FORMATO COLUMNAS
                 */                
                // $contaten = $item['count']." x $".number_format($item['price'],2,".","," )." = $".$formatImporte;
                // $printer -> text($contaten."\n");
                $printer -> setJustification();
            }
            $printer -> setJustification(); // Reset
            $printer -> setTextSize(2, 1);
            //*********************************************************************************
            $printer -> setJustification(Printer::JUSTIFY_RIGHT); 
            $printer -> feed(2);    
            $printer -> text("Total $".number_format($totalapagar,2,".","," )."\n");
            $printer -> text("Pago $".number_format($CantAbono,2,".","," )."\n");
            if ($totalapagar > $CantAbono) {
                $printer -> text("Debe $".number_format($totalapagar-$CantAbono,2,".","," )."\n");
            }  
            if ($totalapagar < $CantAbono) {
                $cambio = $CantAbono - $totalapagar;
                $printer -> text("Cambio $".number_format($cambio,2,".","," )."\n");
            }          
            $printer -> feed(2);    
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text("Gracias por\n");
            $printer -> text("su visita\n");
            $printer -> feed(5);
            /* Pulse Manda un PULSO y abre la caja registradora
            pulse($pin, $on_ms, $off_ms)
            Generate a pulse, for opening a cash drawer if one is connected. The default settings (0, 120, 240) should open an Epson drawer.*/
            $printer -> pulse();
            $printer->cut();
            /* Close printer */
            $printer -> close();
            //$this->cart->destroy();
            // echo "Ticket Impreso";
        } catch (Exception $e) {
            echo "No se pudo imprimir el Ticket" . $e -> getMessage() . "\n";
        }
    }
    /*
    *   FIN Funcion IMPRIMIR TICKET
    */


    /**
     * CHECA MINIMO STOCK
     */
    public function check_min_stock(){
        $this->load->model('Producto_model');
        $data['alerta_stock'] = $this->Producto_model->get_minimo_stock_productos();
        $abastecer = array();
        $cant = 0;
        foreach ($data['alerta_stock'] as $key) {
            if ($key['abastecer'] == "true") {
                $cant++;
                array_push($abastecer,$key);                
            }               
        }
        $response = array(
            'items' => $abastecer ,
            'cantidad' => $cant 
        );
        return $response;        
        //echo json_encode($response);
    }
    
    

















}//End Of Line