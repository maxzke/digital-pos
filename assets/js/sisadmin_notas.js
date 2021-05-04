//$(document).ready(function() {
//window.jsPDF = window.jspdf.jsPDF        
//contador lleva la cuenta de cuantos articulos se han agregado a la nota
var hay_articulo = 0;
var contador = 1;
var subtotal_articuls = 0;
var subtotal_articuls2 = 0;
var cotizacion = 0;
var facturacion = 0;
let opcion_facturar = 0;
//en secciona pagos, todo es con iva incluido
const opcion_facturar_pago = 1;
let opcion_pago_facturado = 0;
let opcion_cotizar = 0;
const url = $('#url_base').val();


//uso DOCUMENT porque el div lo estoy cargando en LOAD dentro de otro div de lo contrario usaria
//$('#cobrarNota').click(function() {                 
$(document).on("click", "#cobrarNota", function () {

  var confirmaNota = validaCamposVAcios();
  var Ffolio = $("#txt_folio").val();

  if (cotizacion == 1 && confirmaNota == 1) {

    //alert("cotizacion");
    cotizacionPdfNotaPost();
    window.open('bar_progresspdf.php?folio=' + Ffolio + '&bit=2', '_blank', '');
    return;

  };


  if (confirmaNota == 1 && cotizacion == 0) {
    alertify.confirm("Guardar Venta", function (e) {
      if (e) {
        // user clicked "ok"
        //se guarda la nota   
        $('#cobrarNota').prop("disabled", true);
        $('#spinner_loading').css('display', 'inherit');

        cobrarNotaPost();
        // window.open('bar_progresspdf.php?folio='+Ffolio+'&bit=1','_blank',''); 
        // window.open('bar_progressGuardandoNota.php','_self','');  

        //se cobra la NOTA y se recarga el div refrescarNota DENTRO de contenedorNotas
        //$('#contenedorNotas').load(document.URL +  ' #refrescarNota');
        //alertify.success("Guardado Correctamente");
        //window.open('bar_progresspdf.php?folio='+Ffolio+'&bit=1','_blank','');                            
        //window.setTimeout('location.reload()', 10);

      } else {
        // user clicked "cancel"
        return
      }
    });



  }

});

$(document).on("click", "#guardarPago", function () {

  var confirmaNota = validaCamposVAcios();
  var Ffolio = $("#txt_folio").val();

  if (confirmaNota == 1 && cotizacion == 0) {
    alertify.confirm("Guardar Pago", function (e) {
      if (e) {
        // user clicked "ok"
        //se guarda la nota   
        $('#guardarPago').prop("disabled", true);
        $('#spinner_loading').css('display', 'inherit');

        guardarPagoPost();

      } else {
        // user clicked "cancel"
        return
      }
    });



  }

});


$(".chkCotizacion").on('change', function () {
  if ($(this).is(':checked')) {
    opcion_cotizar = 1;
  } else {
    opcion_cotizar = 0;
  }

});

$(".select_facturar").on('change', function () {
  if ($(this).is(':checked')) {
    opcion_facturar = 1;
    //alertify.error("facturacion 1");                  
  } else {
    opcion_facturar = 0;
    //alertify.error("facturacion 0");                  
  }
  calculaCuentas();
});

$("#customSwitchPagos").on('change', function () {
  if ($(this).is(':checked')) {
    opcion_pago_facturado = 1;
    //alertify.error("facturacion 1");                  
  } else {
    opcion_pago_facturado = 0;
    //alertify.error("facturacion 0");                  
  }
  //calculaCuentas();
});





function validaCamposVAcios() {
  var procedeNota = 1;
  if ($('#txt_cliente').val().trim() === '') {
    procedeNota = 0;
    $('#txt_cliente').focus();
    alertify.error("Nombre del Cliente");
    return;
  }
  // if ($('#txt_direccion').val().trim() === '') {
  //   procedeNota=0;
  //   $('#txt_direccion').focus();
  //   alertify.error("Direccion del Cliente");
  //   return;
  // }
  // if ($('#txt_telefono').val().trim() === '') {
  //   procedeNota=0;
  //   $('#txt_telefono').focus();
  //   alertify.error("Telefono del Cliente");
  //   return;
  // }
  var elementos = $('.total_hidden');
  var size = elementos.length;
  hay_articulo = size;

  for (var i = 1; i <= contador; i++) {
    if ($('#txtcantidad' + i).val() == 0) {
      procedeNota = 0;
      $('#txtcantidad' + i).focus();
      $('#txtcantidad' + i).select();
      alertify.error("Cantidad");
      return;
    }
    if ($('#txtdescripcion' + i).val() == 0) {
      procedeNota = 0;
      $('#txtdescripcion' + i).focus();
      alertify.error("Descripcion");
      return;
    }
    if ($('#txtpunit' + i).val() == 0) {
      procedeNota = 0;
      $('#txtpunit' + i).focus();
      $('#txtpunit' + i).select();
      alertify.error("Precio Unitario");
      return;
    }
  }




  return procedeNota;
}

/*
* Funcion asincrona para ajax
* envia los datos de la venta para guardarla
* devuelve true or false
 */
async function postData(arraydatos, url) {
  const response = $.ajax({
    url: url,
    data: { params: arraydatos },
    method: 'post',
    async: true,
    dataType: 'json',
  });
  const data = await response;
  return data;
}

async function cobrarNotaPost() {
  //cuenta elementos de la clase
  //Si no hay no se manda el POST                      
  var elementos = $('.total_hidden');
  var size = elementos.length;
  hay_articulo = size;
  var AbonoNota = parseFloat($("#txt_abono").val());
  let datosNota = {
    'subtotal': $("#txt_subtotal").val(),
    'iva': $("#txt_iva").val(),
    'total': $("#txt_total").val(),
    'abono': $("#txt_abono").val(),
    'metodo_pago': $("#metodoPago").val(),
    'cliente': $("#txt_cliente").val(),
    'direccion': $("#txt_direccion").val(),
    'telefono': $("#txt_telefono").val(),
    'empresa': $("#txt_empresa").val(),
    'facturar': opcion_facturar,
    'cotizar': opcion_cotizar,
    'user': $("#txt_idUser").val()
  }
  //Arreglo de Objetos
  var cart = [];
  for (var i = 1; i <= contador; i++) {
    var cant = $('#' + "txtcantidad" + i).val();
    var deskrip = $('#' + "txtdescripcion" + i).val();
    var punit = $('#' + "txtpunit" + i).val();
    var tot = $('#' + "txttotal" + i).val();
    //Arreglo con datos de articulos
    var arregloArticulos = {};
    if (cant) {
      //Si existe "cant" se hace ya que segeneran varios txtDinamicos pero solo se guardan los que estan llenos
      arregloArticulos.cantidad = cant;
      arregloArticulos.producto = deskrip;
      arregloArticulos.precio = punit;
      arregloArticulos.importe = tot;
      //Meto arreglos al Arreglo de objetos
      cart.push(arregloArticulos);
    }
  }
  // console.log(datosNota); 
  // console.log(cart);
  parametros = {
    'datos': datosNota,
    'carrito': cart
  }
  const respAsyncDetalles = await postData(parametros, url + '/pos/store');
  if (respAsyncDetalles.success) {
    generarPdf(respAsyncDetalles.pdfData.data, respAsyncDetalles.pdfData.name);
    alertify.success("Venta Guardar !");
    $('#cobrarNota').prop("disabled", false);
    $('#spinner_loading').css('display', 'none');
    $('#formVenta')[0].reset();
  } else {
    alertify.error("Hubo un error, intente de nuevo.");
    $('#cobrarNota').prop("disabled", false);
    $('#spinner_loading').css('display', 'none');
  }

}

async function guardarPagoPost() {
  //cuenta elementos de la clase
  //Si no hay no se manda el POST                      
  var elementos = $('.total_hidden');
  var size = elementos.length;
  hay_articulo = size;
  var AbonoNota = parseFloat($("#txt_abono").val());
  let datosNota = {
    'subtotal': $("#txt_subtotal").val(),
    'iva': $("#txt_iva").val(),
    'total': $("#txt_total").val(),
    //'abono': $("#txt_abono").val(), 
    'metodo': $("#metodoPago").val(),
    'proveedor': $("#txt_cliente").val(),
    'folio': $("#txt_direccion").val(),
    //'telefono': $("#txt_telefono").val(), 
    //'empresa': $("#txt_empresa").val(), 
    'facturado': opcion_pago_facturado,
    //'cotizar' : opcion_cotizar,
    'usuario': $("#txt_idUser").val()
  }
  //Arreglo de Objetos
  var cart = [];
  for (var i = 1; i <= contador; i++) {
    var cant = $('#' + "txtcantidad" + i).val();
    var deskrip = $('#' + "txtdescripcion" + i).val();
    var punit = $('#' + "txtpunit" + i).val();
    var tot = $('#' + "txttotal" + i).val();
    //Arreglo con datos de articulos
    var arregloArticulos = {};
    if (cant) {
      //Si existe "cant" se hace ya que segeneran varios txtDinamicos pero solo se guardan los que estan llenos
      arregloArticulos.cantidad = cant;
      arregloArticulos.producto = deskrip;
      arregloArticulos.precio = punit;
      arregloArticulos.importe = tot;
      //Meto arreglos al Arreglo de objetos
      cart.push(arregloArticulos);
    }
  }
  // console.log(datosNota); 
  // console.log(cart);
  parametros = {
    'datos': datosNota,
    'carrito': cart
  }
  const respAsyncDetalles = await postData(parametros, url + '/pagos/store');
  if (respAsyncDetalles.success) {
    console.log(respAsyncDetalles);
    alertify.success("Pago Guardado !");
    $('#guardarPago').prop("disabled", false);
    $('#spinner_loading').css('display', 'none');
    $('#formPago')[0].reset();
  } else {
    alertify.error(respAsyncDetalles.msg);
    $('#guardarPago').prop("disabled", false);
    $('#spinner_loading').css('display', 'none');
  }

}

function cotizacionPdfNotaPost() {
  //cuenta elementos de la clase
  //Si no hay no se manda el POST                      
  var elementos = $('.total_hidden');
  var size = elementos.length;
  hay_articulo = size;
  var AbonoNota = parseFloat($("#txt_abono").val());
  var foliop = $("#txt_folio").val();
  // $.post("controllers/guarda_notaventa.php", {currentSubtotal: $("#txt_subtotal").val(), currentIva:$("#txt_iva").val(),total: $("#txt_total").val(), abono: $("#txt_abono").val(), cliente: $("#txt_cliente").val(), direccion: $("#txt_direccion").val(), telefono: $("#txt_telefono").val(), folio: foliop, facturar: $("#select_facturar").val()});                            

  if (AbonoNota > 0) {
    //$.post("controllers/guarda_abonosnota.php",{folioAbono: foliop, importeAbono: $("#txt_abono").val()});
    //alert("se abono");
  }
  //Arreglo de Objetos
  var arregloArticulosPost = [];

  for (var i = 1; i <= contador; i++) {
    var cant = $('#' + "txtcantidad" + i).val();
    var deskrip = $('#' + "txtdescripcion" + i).val();
    var punit = $('#' + "txtpunit" + i).val();
    var tot = $('#' + "txttotal" + i).val();
    //Arreglo con datos de articulos
    var arregloArticulos = {};
    if (cant) {
      //Si existe "cant" se hace ya que segeneran varios txtDinamicos pero solo se guardan los que estan llenos
      // $.post("controllers/guarda_articulosnota.php", {cantidad: cant, p_descrip: deskrip, p_unit: punit, p_total: tot, folio_v: foliop});                      

      arregloArticulos.cantidad = cant;
      arregloArticulos.p_descrip = deskrip;
      arregloArticulos.p_unit = punit;
      arregloArticulos.p_total = tot;
      //Meto arreglos al Arreglo de objetos
      arregloArticulosPost.push(arregloArticulos);

    }
  }


  //Genera el PDF de la cotizacion
  $.post("pdf/generador.php", {
    BitTipo: '0',
    currentSubtotal: $("#txt_subtotal").val(),
    currentIva: $("#txt_iva").val(),
    total: $("#txt_total").val(),
    abono: $("#txt_abono").val(),
    cliente: $("#txt_cliente").val(),
    direccion: $("#txt_direccion").val(),
    telefono: $("#txt_telefono").val(),
    folio: foliop,
    facturar: $("#select_facturar").val(),
    articulosArray: arregloArticulosPost
  });
}



$("#add").click(function () {
  contador = contador + 1;
  var intId = $("#buildyourform div").length + 1;
  //var fieldWrapper = $("<div class=\"fieldwrapper\" id=\"field" + intId + "\"/>");                  

  var fieldtable1 = $("<div class='row mt-1'>");
  var fcantidad = $("<div class='col-md-1'><input type=\"number\" min=\"1\" onclick=\"this.select()\" autocomplete=\"off\" size=\"5\" value=\"0\" class=\"form-control form-control-sm fieldname\" id=\"txtcantidad" + contador + "\" name=\"" + contador + "\" required/></div>");
  var fdescripcion = $("<div class='col-md-7'><input type=\"text\" size='55' class=\"form-control form-control-sm fieldname text-capitalize\" autocomplete=\"off\" id=\"txtdescripcion" + contador + "\" name=\"txt_descripcion" + contador + "\" required/></div>");
  var fpreciounitario = $("<div class='col-md-1'><input type=\"number\" min=\"1\" size=\"5\" onclick=\"this.select()\" value=\"0\" autocomplete=\"off\" class=\"form-control form-control-sm fieldname\" id=\"txtpunit" + contador + "\" name=\"" + contador + "\" required/></div>");
  var fTotal = $("<div class='col-md-1'><input type=\"text\" class=\"form-control form-control-sm fieldname\" size=\"5\" value=\"0\" id=\"txttotal" + contador + "\" disabled/></div>");
  var fDescuento = $("<div class='col-md-1'><input type=\"number\" min=\"0\" onclick=\"this.select()\" class=\"form-control form-control-sm fieldname\" size=\"5\" value=\"0\" id=\"txtdescuento" + contador + "\" name=\"" + contador + "\"/></div>");
  var removeButton = $("<div class='col-md-1'><i class=\"fas fa-times text-danger\"></i></div></div>");
  var fTotalhidden = $("<div class='col-md-1'><input type=\"hidden\" value=\"0\" class=\"total_hidden\" id=\"txttotal_hidden" + contador + "\" /></div>");
  removeButton.click(function () {
    $(this).parent().remove();
    //contador=contador-1;
    //si se remueve un item se actualiza el Subtotal
    //cuenta elementos de la clase
    subtotal_articuls2 = 0;
    var elementos = $('.total_hidden');
    var size = elementos.length;
    hay_articulo = size;
    var arrayID = [];
    $.each(elementos, function (i, val) {
      var v_hidden = parseFloat($(this).val());
      subtotal_articuls2 = subtotal_articuls2 + v_hidden;
      arrayID.push($(val).parent().attr('id'));

    });//Fin cuenta elementos de la clase
    // $("#txt_subtotal").val(subtotal_articuls);
    $("#txt_subtotal").val(subtotal_articuls2.toFixed(2));
  });

  fieldtable1.append(fieldtable1);
  fieldtable1.append(fcantidad);
  fieldtable1.append(fdescripcion);
  fieldtable1.append(fpreciounitario);
  fieldtable1.append(fTotal);
  fieldtable1.append(fDescuento);
  fieldtable1.append(removeButton);
  fieldtable1.append(fTotalhidden);
  $("#buildyourform").append(fieldtable1);
});

$("#addPago").click(function () {
  contador = contador + 1;
  var intId = $("#buildyourform div").length + 1;
  //var fieldWrapper = $("<div class=\"fieldwrapper\" id=\"field" + intId + "\"/>");                  

  var fieldtable1 = $("<div class='row mt-1'>");
  var fcantidad = $("<div class='col-md-1 offset-1'><input type=\"number\" onclick=\"this.select()\" autocomplete=\"off\" size=\"5\" value=\"0\" class=\"form-control form-control-sm fieldnamePago\" id=\"txtcantidad" + contador + "\" name=\"" + contador + "\" required/></div>");
  var fdescripcion = $("<div class='col-md-7'><input type=\"text\" size='55' class=\"form-control form-control-sm fieldnamePago text-capitalize\" autocomplete=\"off\" id=\"txtdescripcion" + contador + "\" name=\"txt_descripcion" + contador + "\" required/></div>");
  var fpreciounitario = $("<div class='col-md-1'><input type=\"number\" size=\"5\" onclick=\"this.select()\" value=\"0\" autocomplete=\"off\" class=\"form-control form-control-sm fieldnamePago\" id=\"txtpunit" + contador + "\" name=\"" + contador + "\" required/></div>");
  var fTotal = $("<div class='col-md-1'><input type=\"text\" class=\"form-control form-control-sm fieldnamePago\" size=\"5\" value=\"0\" id=\"txttotal" + contador + "\" disabled/></div>");
  //var fDescuento = $("<div class='col-md-1'><input type=\"number\" onclick=\"this.select()\" class=\"form-control form-control-sm fieldnamePago\" size=\"5\" value=\"0\" id=\"txtdescuento" + contador + "\" name=\""+contador+"\"/></div>");                  
  var removeButton = $("<div class='col-md-1'><i class=\"fas fa-times text-danger\"></i></div></div>");
  var fTotalhidden = $("<div class='col-md-1'><input type=\"hidden\" value=\"0\" class=\"total_hidden\" id=\"txttotal_hidden" + contador + "\" /></div>");
  removeButton.click(function () {
    $(this).parent().remove();
    //contador=contador-1;
    //si se remueve un item se actualiza el Subtotal
    //cuenta elementos de la clase
    subtotal_articuls2 = 0;
    var elementos = $('.total_hidden');
    var size = elementos.length;
    hay_articulo = size;
    var arrayID = [];
    $.each(elementos, function (i, val) {
      var v_hidden = parseFloat($(this).val());
      subtotal_articuls2 = subtotal_articuls2 + v_hidden;
      arrayID.push($(val).parent().attr('id'));

    });//Fin cuenta elementos de la clase
    // $("#txt_subtotal").val(subtotal_articuls);
    $("#txt_subtotal").val(subtotal_articuls2.toFixed(2));
  });

  fieldtable1.append(fieldtable1);
  fieldtable1.append(fcantidad);
  fieldtable1.append(fdescripcion);
  fieldtable1.append(fpreciounitario);
  fieldtable1.append(fTotal);
  //fieldtable1.append(fDescuento);                  
  fieldtable1.append(removeButton);
  fieldtable1.append(fTotalhidden);
  $("#buildyourform").append(fieldtable1);
});

//selecciono el texto del input text clickeado                
$(document).on("click", "input[type='text']", function () {
  //$(this).select();                  
});

function calculaCuentasPagos() {
  var global_subtotal = parseFloat($("#txt_subtotal").val());
  var global_factura = facturacion;
  var global_iva = 0;
  var global_total = 0;
  if (opcion_facturar_pago == 0) {
    global_total = global_subtotal;
    //alert("sin iva"+global_total);
  }
  //siempre incluye iva por tanto opcion_facturar_pago==1
  if (opcion_facturar_pago == 1) {
    global_iva = (global_subtotal) * (0.16);
    global_total = global_subtotal + eval(global_iva);
  }
  $("#txt_iva").val(global_iva.toFixed(2));
  $("#txt_total").val(global_total.toFixed(2));
  var currentAbono = $("#txt_abono").val();
  var currentResta = (global_total.toFixed(2)) - currentAbono;
  //verifico que abono no sea mayor a total
  if (currentResta < 0) {
    alertify.error("Abono no puede ser mayor a total");
    $("#txt_abono").val(0);
    $("#txt_abono").select();
    $("#txt_resta").val($("#txt_total").val());
  } else {
    $("#txt_resta").val(currentResta.toFixed(2));
  }
}

//uso DOCUMENT porque son generado dinamicamente
$(document).on("keyup", ".fieldnamePago", function () {
  subtotal_articuls = 0;
  subtotal_articuls2 = 0;
  var p_id = $(this).attr("name");
  var v1 = $('#' + "txtcantidad" + p_id).val();
  var v2 = $('#' + "txtpunit" + p_id).val();
  //var v3 = $('#'+"txtdescuento"+p_id).val();
  const floatRegex = '[-+]?([0-9]*.[0-9]+|[0-9]+)';
  let evaluar = $(this).data("regex");
  if (evaluar === "si") {

    if (!v1.match(floatRegex)) {
      $(this).val(0);
      alertify.error("Ingrese una cantidad válida");
      return
    }

    if (!v2.match(floatRegex)) {
      $(this).val(0);
      alertify.error("Ingrese una cantidad válida");
      return
    }
  }
  var cantidad_product = parseFloat(v1);
  var precio_unitario = parseFloat(v2);
  //var descuento_producto = parseFloat(v3);
  if (v1 != '' && v2 != '') {
    var precioBruto = cantidad_product * precio_unitario;
    //var cantDescuento = (descuento_producto*precioBruto)/100;
    $('#' + "txttotal" + p_id).val(precioBruto);
    $('#' + "txttotal_hidden" + p_id).val(precioBruto);
    for (var i = 1; i <= contador; i++) {
      var v3 = parseFloat($('#' + "txttotal" + i).val());
      subtotal_articuls = subtotal_articuls + v3;;
    };
    //cuenta elementos de la clase
    var elementos = $('.total_hidden');
    var size = elementos.length;
    var arrayID = [];
    $.each(elementos, function (i, val) {
      var v_hidden = parseFloat($(this).val());
      subtotal_articuls2 = subtotal_articuls2 + v_hidden;
      arrayID.push($(val).parent().attr('id'));
    });//Fin cuenta elementos de la clase
    // $("#txt_subtotal").val(subtotal_articuls);
    $("#txt_subtotal").val(subtotal_articuls2.toFixed(2));
  }
  calculaCuentasPagos();
});//Fin KEYUP


//uso DOCUMENT porque son generado dinamicamente
$(document).on("keyup", ".fieldname", function () {
  subtotal_articuls = 0;
  subtotal_articuls2 = 0;
  var p_id = $(this).attr("name");
  var v1 = $('#' + "txtcantidad" + p_id).val();
  var v2 = $('#' + "txtpunit" + p_id).val();
  var v3 = $('#' + "txtdescuento" + p_id).val();
  const floatRegex = '[-+]?([0-9]*.[0-9]+|[0-9]+)';
  let evaluar = $(this).data("regex");
  if (evaluar === "si") {

    if (!v1.match(floatRegex)) {
      $(this).val(0);
      alertify.error("Ingrese una cantidad válida");
      return
    }

    if (!v2.match(floatRegex)) {
      $(this).val(0);
      alertify.error("Ingrese una cantidad válida");
      return
    }
    if (!v3.match(floatRegex)) {
      $(this).val(0);
      alertify.error("Ingrese una cantidad válida");
      return
    }
  }
  var cantidad_product = parseFloat(v1);
  var precio_unitario = parseFloat(v2);
  var descuento_producto = parseFloat(v3);

  if (v1 != '' && v2 != '') {
    var precioBruto = cantidad_product * precio_unitario;
    var cantDescuento = (descuento_producto * precioBruto) / 100;
    $('#' + "txttotal" + p_id).val(precioBruto - cantDescuento);
    $('#' + "txttotal_hidden" + p_id).val(precioBruto - cantDescuento);

    for (var i = 1; i <= contador; i++) {
      var v3 = parseFloat($('#' + "txttotal" + i).val());
      subtotal_articuls = subtotal_articuls + v3;;
    };
    //cuenta elementos de la clase
    var elementos = $('.total_hidden');
    var size = elementos.length;
    var arrayID = [];
    $.each(elementos, function (i, val) {
      var v_hidden = parseFloat($(this).val());
      subtotal_articuls2 = subtotal_articuls2 + v_hidden;
      arrayID.push($(val).parent().attr('id'));
    });//Fin cuenta elementos de la clase
    $("#txt_subtotal").val(subtotal_articuls2.toFixed(2));
  }
  calculaCuentas();
});//Fin KEYUP


$("#select_facturar").change(function () {

  calculaCuentas();
});

$("#txt_abono").keyup(function () {

  var validaAbono = $("#txt_abono").val();
  var floatRegex = '[-+]?([0-9]*.[0-9]+|[0-9]+)';
  if (!validaAbono.match(floatRegex)) {
    //alert("Bien");
    $(this).val(0);
    alertify.error("Ingrese un Abono válido");
    return
  }
  calculaCuentas();
});

function calculaCuentas() {
  var global_subtotal = parseFloat($("#txt_subtotal").val());
  var global_factura = facturacion;
  var global_iva = 0;
  var global_total = 0;
  if (opcion_facturar == 0) {
    global_total = global_subtotal;
    //alert("sin iva"+global_total);
  }
  if (opcion_facturar == 1) {
    global_iva = (global_subtotal) * (0.16);
    global_total = global_subtotal + eval(global_iva);
  }
  $("#txt_iva").val(global_iva.toFixed(2));
  $("#txt_total").val(global_total.toFixed(2));
  var currentAbono = $("#txt_abono").val();
  var currentResta = (global_total.toFixed(2)) - currentAbono;
  //verifico que abono no sea mayor a total
  if (currentResta < 0) {
    alertify.error("Abono no puede ser mayor a total");
    $("#txt_abono").val(0);
    $("#txt_abono").select();
    $("#txt_resta").val($("#txt_total").val());
  } else {
    $("#txt_resta").val(currentResta.toFixed(2));
  }
}

function CapitaliseAllText(elemId) {
  var txt = $("#" + elemId).val();
  $("#" + elemId).val(txt.toUpperCase());
}
/*
*SECCION CANCELAR VENTA  ------------------------------------------------------------------------------------------------
*/
function cancelarVenta(folio, cliente, total, abonado) {
  let cadena = `<div class="row mt-1">
                    <div class="col-md-3 text-right">
                        <strong>Folio</strong>
                    </div>
                    <div class="col-md-9"> 
                    <span>${folio}</span>
                    <input type="hidden" id="idVentaCancelarHide" value="${folio}">                    
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-3 text-right">
                        <strong>Cliente</strong>
                    </div>
                    <div class="col-md-9"> 
                    <span>${cliente}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <strong>Total</strong>
                    </div>
                    <div class="col-md-9"> 
                    <span>$ ${total}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <strong>Abonado</strong>
                    </div>
                    <div class="col-md-9"> 
                    <span>$ ${formatMoney(abonado, 1, ".", ",")}</span>
                    <input type="hidden" id="importeDevolucionAbonado" value="${abonado}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-right">
                        <strong>Devolver</strong>
                    </div>
                    <div class="col-md-9"> 
                    <input type="number" id="importeDevolucion" placeholder="importe">
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-3 text-right">
                        <strong>Motivo</strong>
                    </div>
                    <div class="col-md-9"> 
                        <textarea name="motivo" id="motivoCancelaVenta" cols="30" rows="5"></textarea>
                    </div>
                </div> `;
  $('#bodyModalCancelarVenta').html(cadena);
  $('#cancelarVentaModal').modal('show');
}

//uso DOCUMENT porque son generado dinamicamente
$(document).on("keyup", "#importeDevolucion", function () {
  let imp = parseFloat($('#importeDevolucion').val());
  let abonado = parseFloat($('#importeDevolucionAbonado').val());
  if (imp > abonado) {
    $('#importeDevolucion').val('');
    alertify.error("Importe no puede ser mayor a total");
  }
});
$(document).on("click", "#importeDevolucion", function () {
  let imp = parseFloat($('#importeDevolucion').val());
  let abonado = parseFloat($('#importeDevolucionAbonado').val());
  if (imp > abonado) {
    $('#importeDevolucion').val('');
    alertify.error("Importe no puede ser mayor a total");
  }
});

$('#importeAbono').on('keyup', function () {
  const floatRegex = '[-+]?([0-9]*.[0-9]+|[0-9]+)';
  if (!$('#importeAbono').val().match(floatRegex)) {
    $(this).val('');
    alertify.error("Abono Debe ser Número");
    return
  }
  let abon = parseFloat($('#importeAbono').val());
  let testnt = parseFloat($('#saldoRestante').val());
  if (abon > testnt) {
    $('#importeAbono').val('');
    alertify.error("Importe no puede ser mayor a total");
  }

})


async function store_cancelacion() {
  let parametros = {
    'folio': $('#idVentaCancelarHide').val(),
    'motivo': $('#motivoCancelaVenta').val(),
    'importe': $('#importeDevolucion').val()
  }
  const respAsyncDetalles = await postData(parametros, url + '/ventas/cancelar');
  if (respAsyncDetalles.success) {
    const registrarComoPago = await postData(parametros, url + '/pagos/devolucion');
    // --------------------------------------------
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: "Venta Cancelada !",
      text: respAsyncDetalles.msg,
      icon: 'success',
      showCancelButton: false,
      confirmButtonText: 'Aceptar!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true,
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        location.reload();
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'Your imaginary file is safe :)',
          'error'
        )
      }
    })
    // --------------------------------------------
    $('#cancelarVentaModal').modal('hide')
  } else {
    alertify.error(respAsyncDetalles.msg);
    console.log(respAsyncDetalles);
  }
}//end store_cancelacion
/*
* SECCION VENTAS ------------------------------------------------------------------------------------------------
*/
function abonar(id_venta, saldo) {
  $('#tituloModalAbonar').text('Abonar Folio: ' + id_venta);
  $('#saldor_restante').text('$ ' + saldo);
  $('#abonarModal').modal('show');
  $('#idVentaHide').val(id_venta);
  $('#saldoRestante').val(saldo);

}
async function store_abono() {
  let idFolio = $('#idVentaHide').val();
  let parametros = {
    'metodo': $('#metodoPagoModal').val(),
    'importe': $('#importeAbono').val(),
    'folio': idFolio
  }

  const respAsyncDetalles = await postData(parametros, url + '/ventas/abonar');
  if (respAsyncDetalles.success) {
    // --------------------------------------------
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: "Abono Guardado !",
      text: respAsyncDetalles.msg,
      icon: 'success',
      showCancelButton: false,
      confirmButtonText: 'Aceptar!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true,
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        location.reload();
        //getDetallesNota(idFolio);
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'Your imaginary file is safe :)',
          'error'
        )
      }
    })
    // --------------------------------------------
    $('#importeAbono').val(0);
    $('#abonarModal').modal('hide')
  } else {
    alertify.error(respAsyncDetalles.msg);
  }
}




function cambiaaFacturado(folio) {
  Swal.fire({
    title: 'Marcar como Facturado',
    text: "FOLIO: " + folio,
    icon: 'question',
    allowOutsideClick: false,
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Guardar!'
  }).then((result) => {
    if (result.isConfirmed) {
      let parametros = {
        'folio': folio,
      }
      postStatus(parametros);
    }
  })
}


async function postStatus(parametros) {
  const respAsyncDetalles = await postData(parametros, url + '/pagos/updateFacturado');
  if (respAsyncDetalles.success) {
    location.reload();
  } else {
    alertify.error("Hubo un error, intente de nuevo.");
  }
}

// ------------ SEARCH VENTAS PENDIENTES--------------------------------------
$(document).on("keyup", "#folioPendiente", delay(function (e) {
  let f = $('#folioPendiente').val();
  searchVentas('folio', f, 'pendientes');
}, 500));
$(document).on("keyup", "#clientePendiente", delay(function (e) {
  let f = $('#clientePendiente').val();
  searchVentas('cliente', f, 'pendientes');
}, 500));
// ------------ SEARCH VENTAS PAGADOS--------------------------------------
$(document).on("keyup", "#folioPagados", delay(function (e) {
  let f = $('#folioPagados').val();
  searchVentas('folio', f, 'pagados');
}, 500));
$(document).on("keyup", "#clientePagados", delay(function (e) {
  let f = $('#clientePagados').val();
  searchVentas('cliente', f, 'pagados');
}, 500));
// ------------ SEARCH VENTAS CANCELADOS--------------------------------------
$(document).on("keyup", "#folioCancelados", delay(function (e) {
  let f = $('#folioCancelados').val();
  searchVentas('folio', f, 'cancelados');
}, 500));
$(document).on("keyup", "#clienteCancelados", delay(function (e) {
  let f = $('#clienteCancelados').val();
  searchVentas('cliente', f, 'cancelados');
}, 500));
// --------------------------------------------------------------------------
async function searchVentas(tipo, parametro, section) {
  let param = {
    'tipo': tipo,
    'param': parametro,
    'seccion': section
  }
  const respAsyncDetalles = await postData(param, url + '/ventas/search');
  if (respAsyncDetalles.success) {
    pintaVentasHtml(respAsyncDetalles.params, section);
    console.log(respAsyncDetalles);
  } else {
    alertify.error("No encontrado");
  }
}
function pintaVentasHtml(arrayDatos, seccion) {
  let cadena = "";
  arrayDatos.forEach(element => {
    let subt = element.total[0].importe;
    let iva = element.iva;
    let totalItem = parseFloat(subt) + parseFloat(iva);

    cadena += `<div class="row row-hover">
          <div class="col-md-2 text-right">
              ${element.folio}
          </div>
          <div class="col-md-5 text-capitalize">
          ${element.cliente}
          </div>
          <div class="col-md-1 text-right">
          ${formatMoney(totalItem, 1, ".", ",")}
          </div>
          <div class="col-md-1 text-right">
          ${formatMoney(element.abonos[0].importe, 1, ".", ",")}
          </div>
          <div class="col-md-1 text-right">
          ${formatMoney(element.resta, 1, ".", ",")}
          </div>
          <div class="col-md-1">
          ${element.fecha}
          </div>
          <div class="col-md-1 text-center">`;
    if (seccion == 'pendientes') {
      cadena += `<i class="fas fa-plus-circle incrementa" onclick="abonar(${element.folio},'${formatMoney(element.resta, 1, ".", ",")}');"></i> 
                      <i class="fas fa-ban decrementa ml-2" 
                          onclick="cancelarVenta(
                              '${element.folio}',
                              '${element.cliente}',
                              '${formatMoney(totalItem, 1, ".", ",")}  '                                                              
                          );">
                      </i> `
    }
    if (seccion == 'pagados') {
      cadena += `<i class="fas fa-ban decrementa ml-2" 
                          onclick="cancelarVenta(
                              '${element.folio}',
                              '${element.cliente}',
                              '${formatMoney(totalItem, 1, ".", ",")}  '                                                              
                          );">
                      </i> `
    }


    cadena += `</div>
      </div>
      <hr>`;
  });
  $('#pintaVentas').html(cadena);
}
// ------------/SEARCH VENTAS PENDIENTES--------------------------------------
//Search Pagos----------------------------------------------------------------
$(document).on("keyup", "#idFolio", delay(function (e) {
  let f = $('#idFolio').val();
  searchPagos('folio', f);
}, 500));
$(document).on("keyup", "#nombreProveedor", delay(function (e) {
  let f = $('#nombreProveedor').val();
  searchPagos('proveedor', f);
}, 500));

async function searchPagos(tipo, parametro) {
  let param = {
    'tipo': tipo,
    'param': parametro
  }
  const respAsyncDetalles = await postData(param, url + '/pagos/search');
  if (respAsyncDetalles.success) {
    pintaPagosHtml(respAsyncDetalles.params);
  } else {
    alertify.error("No encontrado");
  }
}

function pintaPagosHtml(arrayDatos) {
  let cadena = "";
  arrayDatos.forEach(element => {
    cadena += `<div class="row row-hover">
          <div class="col-md-2 text-right">
              ${element.folio}
          </div>
          <div class="col-md-5 text-capitalize">
          ${element.proveedor}
          </div>
          <div class="col-md-1">
          ${element.metodo}
          </div>
          <div class="col-md-1 text-center">`;
    if (element.facturado == '1') {
      cadena += `<i class="fas fa-toggle-on text-success"></i>`;
    } else {
      cadena += `<i class="fas fa-toggle-off text-danger decrementa" onclick="cambiaaFacturado('${element.folio}')"></i>`;
    }
    cadena += `</div>
          <div class="col-md-1 text-right">
            ${formatMoney(element.subtotal, 1, ".", ",")}
          </div>
          <div class="col-md-1 text-right">
            ${formatMoney(element.iva, 1, ".", ",")}
          </div>
          <div class="col-md-1 text-right">
            ${formatMoney(element.total, 1, ".", ",")}
          </div>
      </div>
      <hr>`;
  });
  $('#pintaPagos').html(cadena);
}
// /Search Pagos----------------------------------------------------------------

/**
 * 
 * CORTE
 */
$("#switchDeposito").on('change', function () {
  if ($(this).is(':checked')) {
    $('#btnEnviarDeposito').prop("disabled", false);

  } else {
    $('#btnEnviarDeposito').prop("disabled", true);
  }
});
$("#switchAceptarCorte").on('change', function () {
  if ($(this).is(':checked')) {
    $('#btnAceptarCorte').prop("disabled", false);
  } else {
    $('#btnAceptarCorte').prop("disabled", true);
  }
});
$('#btnDesglozarCorte').on('click', function () {
  $('#desglozarCorteDiv').css('display', 'block');
});

/**
 * ENVIAR DEPOSITO A CUENTA
 */
$('#btnEnviarDeposito').on('click', function () {
  let importe = $('#txt_deposito_a_cuenta').val();
  sendDeposito(importe);
});




async function sendDeposito(importe) {
  const respAsyncDetalles = await postData(importe, url + '/corte/store');
  if (respAsyncDetalles.success) {
    $('#txt_deposito_a_cuenta').removeClass("is-invalid");
    console.log(respAsyncDetalles);
    alertify.success(respAsyncDetalles.msg);
    $('#txt_caja').val("$ " + respAsyncDetalles.saldo_efectivo);
    $('#txt_banco').val("$ " + respAsyncDetalles.saldo_banco);
    $('#txt_deposito_a_cuenta').val("");

  } else {
    alertify.error(respAsyncDetalles.msg);
    $('#txt_deposito_a_cuenta').addClass("is-invalid");
  }
}

$('#btnAceptarCorte').on('click', function () {
  sendCorte();
});
async function sendCorte() {
  let algo = 'null';
  const respAsyncDetalles = await postData(algo, url + '/corte/desgloce');
  if (respAsyncDetalles.msg.success) {
    alertify.success('Reporte enviado!');
    //Habilitar para generar el PDF
    //generarPdf(respAsyncDetalles.msg.data, respAsyncDetalles.msg.name);
  } else {
    console.log(respAsyncDetalles);
    //alertify.error(respAsyncDetalles.msg);
  }
}
/**
 * DESGLOZAR POR FECHA
 */

$('#btnDesglozar').on('click', function () {
  let rango = {
    'desde': $('#desde').val(),
    'hasta': $('#hasta').val()
  }
  sendDesgloce(rango);
});

$(document).on("click", "#btnDetalles", function () {
  getDetallesNota();
});

async function getDetallesNota(folio) {
  let data = {
    'id': folio
  }
  const responseAwait = await postData(data, url + '/ventas/detalles');
  if (responseAwait.success) {
    console.log(responseAwait);
    generarPdf(responseAwait.cachar.data, responseAwait.cachar.name);

  } else {
    console.log(responseAwait);
  }
}
async function sendDesgloce(data) {
  const respAsyncDetalles = await postData(data, url + '/corte/desgloce_por_fecha');
  if (respAsyncDetalles.success) {
    console.log(respAsyncDetalles);
    pintaVentas(respAsyncDetalles.arrayventas);
    pintaImportes('importe_total_ventas', respAsyncDetalles.importe_total_ventas);
    pintaImportes('cobrado_en_efectivo', respAsyncDetalles.cobrado_en_efectivo);
    pintaImportes('cobrado_en_transferencia', respAsyncDetalles.cobrado_en_transferencia);
    pintaImportes('cobrado_en_tarjeta', respAsyncDetalles.cobrado_en_tarjeta);
    pintaImportes('cobrado_en_cheque', respAsyncDetalles.cobrado_en_cheque);

    pintaPagos(respAsyncDetalles.arraypagos);
    pintaImportes('total_pagos', respAsyncDetalles.total_pagos);

    pintadDepositos(respAsyncDetalles.arraydepositos);
    pintaImportes('total_depositos', respAsyncDetalles.total_depositos);

    pintaCajaCuenta('caja_efectivo', respAsyncDetalles.caja_efectivo);
    pintaCajaCuenta('cuenta_banco', respAsyncDetalles.cuenta_banco);

  } else {
    alertify.error('Fecha no válida');
  }
}

/**
 * PINTA INGRESOS ( VENTAS )
 */
function pintaVentas(ventasArray) {
  if (ventasArray != null) {
    let cadena = "";
    ventasArray.forEach(element => {
      cadena += `<div class="row row-hover">
                  <div class="col-md-6 text-right">
                      ${element.folio}
                  </div>
                  <div class="col-md-6 text-right">
                  ${element.abonos}
                  </div>
                </div>
                <hr>`;
    });
    $('#contentVentas').html(cadena);
  }
}

function pintaImportes(campo, importe) {
  let cadena = `<strong>${importe}</strong>`
  $('#' + campo).html(cadena);
}

function pintaCajaCuenta(campo, importe) {
  let cadena = `<h5>${importe}</h5>`
  $('#' + campo).html(cadena);
}

/**
 * PINTA PAGOS ( EGRESOS )
 */
function pintaPagos(pagosArray) {
  if (pagosArray != null) {
    let cadena = "";
    pagosArray.forEach(element => {
      cadena += `<div class="row row-hover">
                      <div class="col-md-6 text-right">
                          ${element.folio}
                      </div>
                      <div class="col-md-6 text-right">
                      ${element.total}
                      </div>
                    </div>
                    <hr>`;
    });
    $('#contentPagos').html(cadena);
  }

}

/**
* PINTA DEPOSITOS A CUENTA
*/
function pintadDepositos(depositosArray) {
  if (depositosArray != null) {
    let cadena = "";
    depositosArray.forEach(element => {
      cadena += `<div class="row row-hover">
                    <div class="col-md-6 text-right">
                        ${element.folio}
                    </div>
                    <div class="col-md-6 text-right">
                    ${element.total}
                    </div>
                  </div>
                  <hr>`;
    });
    $('#contentDepositos').html(cadena);
  }
}

/*
*GENERA PDF
 */
function generarPdf(datos, filename) {

  const imgData = 'data:image/jpeg;base64,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Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ew9cXuw9zsv5Uv72ILQgJ0+uL3Ye52X8qX97D1xe7D3Oy/lS/vYgtCAnT64vdh7nZfypf3sPXF7sPc7L+VL+9iC0ICdPri92Hudl/Kl/ex5pLtht10xOy7DknZnC46hCsUpecEgf8rEEI9ql/tnJ/wAob/WEBsaEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCEIQCPZpn7ZSn+fb/WEIQH/9k=';
  const opt = {
    margin: [50, 20, 20, 20],
    filename: filename + '.pdf',
    image: { type: 'jpeg', quality: 0.99 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'mm', format: 'letter', orientation: 'portrait' }
  };
  html2pdf().set(opt).from(datos).toPdf().get('pdf').then(function (pdf) {
    //pdf.addImage(imgData, "jpeg", cordenada-x, coordenada-y, ancho, alto);
    var totalPages = pdf.internal.getNumberOfPages();
    for (i = 1; i <= totalPages; i++) {
      pdf.setPage(i);
      pdf.addImage(imgData, "jpeg", 7, 10, 200, 30);
    }
    
  })
    .save();
}

























function formatMoney(number, decPlaces, decSep, thouSep) {
  decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSep = typeof decSep === "undefined" ? "." : decSep;
  thouSep = typeof thouSep === "undefined" ? "," : thouSep;
  var sign = number < 0 ? "-" : "";
  var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
  var j = (j = i.length) > 3 ? j % 3 : 0;

  return sign +
    (j ? i.substr(0, j) + thouSep : "") +
    i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
    (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}

//delay al dejar de esciribir para busqueda
function delay(callback, ms) {
  var timer = 0;
  return function () {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}

//});