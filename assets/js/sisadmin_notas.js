//$(document).ready(function() {
              
                //contador lleva la cuenta de cuantos articulos se han agregado a la nota
                var hay_articulo=0;
                var contador=1;
                var subtotal_articuls = 0;
                var subtotal_articuls2 = 0;
                var cotizacion=0;
                var facturacion = 0;
                let opcion_facturar = 0;
                let opcion_cotizar = 0;
                

                 //uso DOCUMENT porque el div lo estoy cargando en LOAD dentro de otro div de lo contrario usaria
                //$('#cobrarNota').click(function() {                 
                $(document).on("click", "#cobrarNota", function() {

                  var confirmaNota = validaCamposVAcios();
                  var Ffolio=$("#txt_folio").val();
                  
                  if (cotizacion==1 && confirmaNota==1) {
                    
                    //alert("cotizacion");
                    cotizacionPdfNotaPost();                    
                    window.open('bar_progresspdf.php?folio='+Ffolio+'&bit=2','_blank','');                  
                    return;

                  };


                  if(confirmaNota==1 && cotizacion==0){
                    alertify.confirm("Guardar Venta", function (e) {
                        if (e) {
                            // user clicked "ok"
                            //se guarda la nota                    
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

                
                $(".chkCotizacion").on( 'change', function() {
                  if( $(this).is(':checked') ) {
                    opcion_cotizar=1;                  
                  }else{
                    opcion_cotizar=0;                   
                  }

                });

                $(".select_facturar").on( 'change', function() {
                  if( $(this).is(':checked') ) {
                    opcion_facturar=1;  
                    alertify.error("facturacion 1");                  
                  }else{
                    opcion_facturar=0;  
                    alertify.error("facturacion 0");                  
                  }
                  calculaCuentas();

                });

                

                

                  function validaCamposVAcios(){
                    var procedeNota=1;
                    if ($('#txt_cliente').val().trim() === '') {                      
                      procedeNota=0;
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
                      if ($('#txtcantidad'+i).val()==0) {
                            procedeNota=0;
                            $('#txtcantidad'+i).focus();
                            $('#txtcantidad'+i).select();
                            alertify.error("Cantidad");
                            return;
                      }
                      if ($('#txtdescripcion'+i).val()==0) {
                            procedeNota=0;
                            $('#txtdescripcion'+i).focus();
                            alertify.error("Descripcion");
                            return;
                      }
                      if ($('#txtpunit'+i).val()==0) {
                            procedeNota=0;
                            $('#txtpunit'+i).focus();
                            $('#txtpunit'+i).select();
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
async function postData(arraydatos,url){
  const response = $.ajax({
    url: url,
    data:{params:arraydatos},
    method:'post',
    async:true,
    dataType: 'json',		
  });
  const data = await response;
  return data;
}                 
async function cobrarNotaPost(){
  //cuenta elementos de la clase
  //Si no hay no se manda el POST                      
  var elementos = $('.total_hidden');
  var size = elementos.length;
  hay_articulo = size; 
  var AbonoNota=parseFloat($("#txt_abono").val());   
  let datosNota = {
    'subtotal': $("#txt_subtotal").val(), 
    'currentIva':$("#txt_iva").val(),
    'total': $("#txt_total").val(), 
    'abono': $("#txt_abono").val(), 
    'metodo_pago': $("#metodoPago").val(),
    'cliente': $("#txt_cliente").val(), 
    'direccion': $("#txt_direccion").val(), 
    'telefono': $("#txt_telefono").val(), 
    'empresa': $("#txt_empresa").val(), 
    'facturar': opcion_facturar,
    'cotizar' : opcion_cotizar,
    'user': $("#txt_idUser").val()
  }                 
  //Arreglo de Objetos
  var cart=[];
  for(var i=1;i<=contador;i++){
    var cant=$('#'+"txtcantidad"+i).val();
    var deskrip=$('#'+"txtdescripcion"+i).val();
    var punit=$('#'+"txtpunit"+i).val();
    var tot=$('#'+"txttotal"+i).val(); 
    //Arreglo con datos de articulos
    var arregloArticulos={};
    if (cant) {
      //Si existe "cant" se hace ya que segeneran varios txtDinamicos pero solo se guardan los que estan llenos
      arregloArticulos.cantidad=cant;
      arregloArticulos.producto= deskrip;
      arregloArticulos.precio= punit;
      arregloArticulos.importe= tot;  
      //Meto arreglos al Arreglo de objetos
      cart.push(arregloArticulos);                           
    }                                                                               
  }
  console.log(datosNota); 
  console.log(cart);
  parametros = {
    'datos': datosNota,
    'carrito': cart
  }
  const respAsyncDetalles = await postData(parametros,'https://digital-pos.digitalestudio.com.mx/index.php/pos/store');
  if (respAsyncDetalles.success) {
    alertify.success("Venta Guardar !");
  }else{
    alertify.error("Hubo un error, intente de nuevo.");
  }
                 
}

                   function cotizacionPdfNotaPost(){
                      //cuenta elementos de la clase
                      //Si no hay no se manda el POST                      
                      var elementos = $('.total_hidden');
                      var size = elementos.length;
                      hay_articulo = size; 
                      var AbonoNota=parseFloat($("#txt_abono").val());
                          var foliop=$("#txt_folio").val();
                         // $.post("controllers/guarda_notaventa.php", {currentSubtotal: $("#txt_subtotal").val(), currentIva:$("#txt_iva").val(),total: $("#txt_total").val(), abono: $("#txt_abono").val(), cliente: $("#txt_cliente").val(), direccion: $("#txt_direccion").val(), telefono: $("#txt_telefono").val(), folio: foliop, facturar: $("#select_facturar").val()});                            

                          if (AbonoNota>0){
                            //$.post("controllers/guarda_abonosnota.php",{folioAbono: foliop, importeAbono: $("#txt_abono").val()});
                            //alert("se abono");
                          }
                          //Arreglo de Objetos
                          var arregloArticulosPost=[];

                          for(var i=1;i<=contador;i++){
                              var cant=$('#'+"txtcantidad"+i).val();
                              var deskrip=$('#'+"txtdescripcion"+i).val();
                              var punit=$('#'+"txtpunit"+i).val();
                              var tot=$('#'+"txttotal"+i).val(); 
                              //Arreglo con datos de articulos
                              var arregloArticulos={};
                              if (cant) {
                                //Si existe "cant" se hace ya que segeneran varios txtDinamicos pero solo se guardan los que estan llenos
                               // $.post("controllers/guarda_articulosnota.php", {cantidad: cant, p_descrip: deskrip, p_unit: punit, p_total: tot, folio_v: foliop});                      
                                
                                arregloArticulos.cantidad=cant;
                                arregloArticulos.p_descrip= deskrip;
                                arregloArticulos.p_unit= punit;
                                arregloArticulos.p_total= tot;  
                                //Meto arreglos al Arreglo de objetos
                                arregloArticulosPost.push(arregloArticulos); 
                                                           
                              }                                                                               
                          }

                            
                              //Genera el PDF de la cotizacion
                              $.post("pdf/generador.php", {
                                BitTipo:'0',
                                currentSubtotal: $("#txt_subtotal").val(), 
                                currentIva:$("#txt_iva").val(),
                                total: $("#txt_total").val(), 
                                abono: $("#txt_abono").val(), 
                                cliente: $("#txt_cliente").val(), 
                                direccion: $("#txt_direccion").val(), 
                                telefono: $("#txt_telefono").val(), 
                                folio: foliop, 
                                facturar: $("#select_facturar").val(),                                 
                                articulosArray:arregloArticulosPost});                  
                  }

                  
                
                $("#add").click(function() {
                  contador=contador+1;
                  var intId = $("#buildyourform div").length + 1;
                  //var fieldWrapper = $("<div class=\"fieldwrapper\" id=\"field" + intId + "\"/>");                  
                                                                   
                  var fieldtable1 = $("<div class='row mt-1'>");                                                                  
                  var fcantidad = $("<div class='col-md-1'><input type=\"text\" autocomplete=\"off\" size=\"5\" value=\"0\" class=\"form-control form-control-sm fieldname\" id=\"txtcantidad" + contador + "\" name=\""+contador+"\" required/></div>");
                  var fdescripcion = $("<div class='col-md-7'><input type=\"text\" size='55' class=\"form-control form-control-sm fieldname text-capitalize\" autocomplete=\"off\" id=\"txtdescripcion" + contador + "\" name=\"txt_descripcion" + contador + "\" required/></div>");
                  var fpreciounitario = $("<div class='col-md-1'><input type=\"text\" size=\"5\" value=\"0\" autocomplete=\"off\" class=\"form-control form-control-sm fieldname\" id=\"txtpunit" + contador + "\" name=\"" + contador + "\" required/></div>");
                  var fTotal = $("<div class='col-md-1'><input type=\"text\" class=\"form-control form-control-sm fieldname\" size=\"5\" value=\"0\" id=\"txttotal" + contador + "\" disabled/></div>");                  
                  var fDescuento = $("<div class='col-md-1'><input type=\"text\" class=\"form-control form-control-sm fieldname\" size=\"5\" value=\"0\" id=\"txtdescuento" + contador + "\" name=\""+contador+"\"/></div>");                  
                  var removeButton = $("<div class='col-md-1'><i class=\"fas fa-times text-danger\"></i></div></div>");                                                            
                  var fTotalhidden = $("<div class='col-md-1'><input type=\"hidden\" value=\"0\" class=\"total_hidden\" id=\"txttotal_hidden" + contador + "\" /></div>");                  
                      removeButton.click(function() {
                              $(this).parent().remove();
                                        //contador=contador-1;
                                        //si se remueve un item se actualiza el Subtotal
                                       //cuenta elementos de la clase
                                          subtotal_articuls2=0;
                                          var elementos = $('.total_hidden');
                                          var size = elementos.length;
                                          hay_articulo = size;
                                          var arrayID = [];                      
                                          $.each( elementos, function(i, val){
                                            var v_hidden = parseFloat($(this).val());
                                            subtotal_articuls2=subtotal_articuls2+v_hidden;
                                              arrayID.push( $(val).parent().attr('id') );
                                              
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

                //selecciono el texto del input text clickeado                
                $(document).on("click","input[type='text']", function() {
                  //$(this).select();
                  $(this).select();                  
                });               
                 //uso DOCUMENT porque son generado dinamicamente
                  $(document).on("keyup", ".fieldname", function() {                       
                      subtotal_articuls =0;                   
                      subtotal_articuls2 =0;                   
                       var p_id = $(this).attr("name"); 
                       var v1 = $('#'+"txtcantidad"+p_id).val();
                       var v2 = $('#'+"txtpunit"+p_id).val();
                       var v3 = $('#'+"txtdescuento"+p_id).val();
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

                       if(v1 != '' && v2 != ''){
                          var precioBruto=cantidad_product*precio_unitario;
                          var cantDescuento = (descuento_producto*precioBruto)/100;
                          $('#'+"txttotal"+p_id).val(precioBruto-cantDescuento);
                          $('#'+"txttotal_hidden"+p_id).val(precioBruto-cantDescuento);
                          //$('#'+"txttotal_hidden"+p_id).val(cantidad_product*precio_unitario);

                          for (var i = 1; i <= contador; i++) {
                            var v3 = parseFloat($('#'+"txttotal"+i).val());
                            subtotal_articuls = subtotal_articuls + v3;;  
                          };
                          //cuenta elementos de la clase
                          var elementos = $('.total_hidden');
                          var size = elementos.length;
                          var arrayID = [];
                           
                          $.each( elementos, function(i, val){
                            var v_hidden = parseFloat($(this).val());
                            subtotal_articuls2=subtotal_articuls2+v_hidden;
                              arrayID.push( $(val).parent().attr('id') );
                              
                          });//Fin cuenta elementos de la clase
                         // $("#txt_subtotal").val(subtotal_articuls);
                          $("#txt_subtotal").val(subtotal_articuls2.toFixed(2));
                        }                    
                    calculaCuentas();
                    //CapitaliseAllText($(this).attr('id'));
                });//Fin KEYUP

                
                $("#select_facturar").change(function(){

                    calculaCuentas();
                });

                $("#txt_abono").keyup(function(){

                  var validaAbono =$("#txt_abono").val();                       
                  var floatRegex = '[-+]?([0-9]*.[0-9]+|[0-9]+)';
                  if (!validaAbono.match(floatRegex)) {
                      //alert("Bien");
                      $(this).val(0);
                      alertify.error("Ingrese un Abono válido");
                      return                          
                    }                                      
                  calculaCuentas();
                });
                function calculaCuentas(){
                  
                  var global_subtotal = parseFloat($("#txt_subtotal").val());
                  var global_factura = facturacion;                    
                  var global_iva=0;
                  var global_total=0;
                  if(opcion_facturar==0){
                    global_total = global_subtotal;
                    //alert("sin iva"+global_total);
                  }
                  if(opcion_facturar==1){
                    global_iva=(global_subtotal)*(0.16);
                    global_total =global_subtotal+eval(global_iva);
                  }
                  $("#txt_iva").val(global_iva.toFixed(2));
                  $("#txt_total").val(global_total.toFixed(2));
                  var currentAbono = $("#txt_abono").val();
                  var currentResta =(global_total.toFixed(2))-currentAbono;
                  //verifico que abono no sea mayor a total
                  if (currentResta<0) {
                    alertify.error("Abono no puede ser mayor a total");
                    $("#txt_abono").val(0);
                    $("#txt_abono").select();
                    $("#txt_resta").val($("#txt_total").val());
                    
                  }else{
                    $("#txt_resta").val(currentResta.toFixed(2));
                  }
                  
                } 

                function CapitaliseAllText(elemId) {
                   var txt = $("#" + elemId).val();
                   $("#" + elemId).val(txt.toUpperCase());
                }

  /*
  * SECCION VENTAS
  */              
  function abonar(id_venta){
    $('#tituloModalAbonar').text('Abonar Folio: '+id_venta);
    $('#abonarModal').modal('show');
    $('#idVentaHide').val(id_venta);
  }
  async function store_abono(){
    let parametros = {
      'metodo': $('#metodoPago').val(),
      'importe': $('#importeAbono').val(),
      'folio': $('#idVentaHide').val()
    }
    
    const respAsyncDetalles = await postData(parametros,'https://digital-pos.digitalestudio.com.mx/index.php/ventas/abonar');
    if (respAsyncDetalles.success) {
      alertify.success(respAsyncDetalles.msg);
      $('#importeAbono').val(0);
      $('#abonarModal').modal('hide')
    }else{
      alertify.error(respAsyncDetalles.msg);
    }
  }

  $('#importeAbono').on('keyup',function(){
    const floatRegex = '[-+]?([0-9]*.[0-9]+|[0-9]+)';                            
      if (!$('#importeAbono').val().match(floatRegex)) {
        $(this).val(0);
          alertify.error("Abono Debe ser Número");
        return                           
      }
  })

//});