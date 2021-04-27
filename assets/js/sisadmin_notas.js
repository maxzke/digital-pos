//$(document).ready(function() {
              
                //contador lleva la cuenta de cuantos articulos se han agregado a la nota
                var hay_articulo=0;
                var contador=1;
                var subtotal_articuls = 0;
                var subtotal_articuls2 = 0;
                var cotizacion=0;
                var facturacion = 0;
                let opcion_facturar = 0;
                //en secciona pagos, todo es con iva incluido
                const opcion_facturar_pago = 1;
                let opcion_pago_facturado = 0;
                let opcion_cotizar = 0;
                const url = $('#url_base').val();
                

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
                            $('#cobrarNota').prop( "disabled", true );  
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

                $(document).on("click", "#guardarPago", function() {

                  var confirmaNota = validaCamposVAcios();
                  var Ffolio=$("#txt_folio").val();

                  if(confirmaNota==1 && cotizacion==0){
                    alertify.confirm("Guardar Venta", function (e) {
                        if (e) {
                            // user clicked "ok"
                            //se guarda la nota   
                            $('#guardarPago').prop( "disabled", true );  
                            $('#spinner_loading').css('display', 'inherit');  
                                           
                            guardarPagoPost();

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
                    //alertify.error("facturacion 1");                  
                  }else{
                    opcion_facturar=0;  
                    //alertify.error("facturacion 0");                  
                  }
                  calculaCuentas();
                });

                $("#customSwitchPagos").on( 'change', function() {
                  if( $(this).is(':checked') ) {
                    opcion_pago_facturado=1;  
                    //alertify.error("facturacion 1");                  
                  }else{
                    opcion_pago_facturado=0;  
                    //alertify.error("facturacion 0");                  
                  }
                  //calculaCuentas();
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
    'iva':$("#txt_iva").val(),
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
  // console.log(datosNota); 
  // console.log(cart);
  parametros = {
    'datos': datosNota,
    'carrito': cart
  }
  const respAsyncDetalles = await postData(parametros,url+'/pos/store');
  if (respAsyncDetalles.success) {
    alertify.success("Venta Guardar !");
    $('#cobrarNota').prop( "disabled", false );
    $('#spinner_loading').css('display', 'none'); 
    $('#formVenta')[0].reset();    
  }else{
    alertify.error("Hubo un error, intente de nuevo.");
    $('#cobrarNota').prop( "disabled", false );
    $('#spinner_loading').css('display', 'none');
  }
                 
}

async function guardarPagoPost(){
  //cuenta elementos de la clase
  //Si no hay no se manda el POST                      
  var elementos = $('.total_hidden');
  var size = elementos.length;
  hay_articulo = size; 
  var AbonoNota=parseFloat($("#txt_abono").val());   
  let datosNota = {
    'subtotal': $("#txt_subtotal").val(), 
    'iva':$("#txt_iva").val(),
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
  // console.log(datosNota); 
  // console.log(cart);
  parametros = {
    'datos': datosNota,
    'carrito': cart
  }
  const respAsyncDetalles = await postData(parametros,url+'/pagos/store');
  if (respAsyncDetalles.success) {
    console.log(respAsyncDetalles);
    alertify.success("Pago success !");
    $('#guardarPago').prop( "disabled", false );
    $('#spinner_loading').css('display', 'none'); 
    $('#formPago')[0].reset();    
  }else{
    alertify.error("Hubo un error, intente de nuevo.");
    $('#guardarPago').prop( "disabled", false );
    $('#spinner_loading').css('display', 'none');
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
                  var fcantidad = $("<div class='col-md-1'><input type=\"number\" onclick=\"this.select()\" autocomplete=\"off\" size=\"5\" value=\"0\" class=\"form-control form-control-sm fieldname\" id=\"txtcantidad" + contador + "\" name=\""+contador+"\" required/></div>");
                  var fdescripcion = $("<div class='col-md-7'><input type=\"text\" size='55' class=\"form-control form-control-sm fieldname text-capitalize\" autocomplete=\"off\" id=\"txtdescripcion" + contador + "\" name=\"txt_descripcion" + contador + "\" required/></div>");
                  var fpreciounitario = $("<div class='col-md-1'><input type=\"number\" size=\"5\" onclick=\"this.select()\" value=\"0\" autocomplete=\"off\" class=\"form-control form-control-sm fieldname\" id=\"txtpunit" + contador + "\" name=\"" + contador + "\" required/></div>");
                  var fTotal = $("<div class='col-md-1'><input type=\"text\" class=\"form-control form-control-sm fieldname\" size=\"5\" value=\"0\" id=\"txttotal" + contador + "\" disabled/></div>");                  
                  var fDescuento = $("<div class='col-md-1'><input type=\"number\" onclick=\"this.select()\" class=\"form-control form-control-sm fieldname\" size=\"5\" value=\"0\" id=\"txtdescuento" + contador + "\" name=\""+contador+"\"/></div>");                  
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

                $("#addPago").click(function() {
                  contador=contador+1;
                  var intId = $("#buildyourform div").length + 1;
                  //var fieldWrapper = $("<div class=\"fieldwrapper\" id=\"field" + intId + "\"/>");                  
                                                                   
                  var fieldtable1 = $("<div class='row mt-1'>");                                                                  
                  var fcantidad = $("<div class='col-md-1 offset-1'><input type=\"number\" onclick=\"this.select()\" autocomplete=\"off\" size=\"5\" value=\"0\" class=\"form-control form-control-sm fieldnamePago\" id=\"txtcantidad" + contador + "\" name=\""+contador+"\" required/></div>");
                  var fdescripcion = $("<div class='col-md-7'><input type=\"text\" size='55' class=\"form-control form-control-sm fieldnamePago text-capitalize\" autocomplete=\"off\" id=\"txtdescripcion" + contador + "\" name=\"txt_descripcion" + contador + "\" required/></div>");
                  var fpreciounitario = $("<div class='col-md-1'><input type=\"number\" size=\"5\" onclick=\"this.select()\" value=\"0\" autocomplete=\"off\" class=\"form-control form-control-sm fieldnamePago\" id=\"txtpunit" + contador + "\" name=\"" + contador + "\" required/></div>");
                  var fTotal = $("<div class='col-md-1'><input type=\"text\" class=\"form-control form-control-sm fieldnamePago\" size=\"5\" value=\"0\" id=\"txttotal" + contador + "\" disabled/></div>");                  
                  //var fDescuento = $("<div class='col-md-1'><input type=\"number\" onclick=\"this.select()\" class=\"form-control form-control-sm fieldnamePago\" size=\"5\" value=\"0\" id=\"txtdescuento" + contador + "\" name=\""+contador+"\"/></div>");                  
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
                  //fieldtable1.append(fDescuento);                  
                  fieldtable1.append(removeButton);
                  fieldtable1.append(fTotalhidden);                  
                  $("#buildyourform").append(fieldtable1);
                });

                //selecciono el texto del input text clickeado                
                $(document).on("click","input[type='text']", function() {
                  //$(this).select();                  
                });   

                function calculaCuentasPagos(){                  
                  var global_subtotal = parseFloat($("#txt_subtotal").val());
                  var global_factura = facturacion;                    
                  var global_iva=0;
                  var global_total=0;
                  if(opcion_facturar_pago==0){
                    global_total = global_subtotal;
                    //alert("sin iva"+global_total);
                  }
                  //siempre incluye iva por tanto opcion_facturar_pago==1
                  if(opcion_facturar_pago==1){
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
                
                //uso DOCUMENT porque son generado dinamicamente
                $(document).on("keyup", ".fieldnamePago", function() {                       
                    subtotal_articuls =0;                   
                    subtotal_articuls2 =0;                   
                      var p_id = $(this).attr("name"); 
                      var v1 = $('#'+"txtcantidad"+p_id).val();
                      var v2 = $('#'+"txtpunit"+p_id).val();
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
                      if(v1 != '' && v2 != ''){
                        var precioBruto=cantidad_product*precio_unitario;
                        //var cantDescuento = (descuento_producto*precioBruto)/100;
                        $('#'+"txttotal"+p_id).val(precioBruto);
                        $('#'+"txttotal_hidden"+p_id).val(precioBruto);
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
                  calculaCuentasPagos();
                });//Fin KEYUP


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
                        $("#txt_subtotal").val(subtotal_articuls2.toFixed(2));
                      }                    
                  calculaCuentas();
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
  *SECCION CANCELAR VENTA  ------------------------------------------------------------------------------------------------
  */
  function cancelarVenta(folio,cliente,total){
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
                        <strong>Motivo</strong>
                    </div>
                    <div class="col-md-9"> 
                        <textarea name="motivo" id="motivoCancelaVenta" cols="30" rows="5"></textarea>
                    </div>
                </div> `;
    $('#bodyModalCancelarVenta').html(cadena);
    $('#cancelarVentaModal').modal('show');
  }
  async function store_cancelacion(){
    let parametros = {
      'folio':$('#idVentaCancelarHide').val(),
      'motivo':$('#motivoCancelaVenta').val()
    } 
    const respAsyncDetalles = await postData(parametros,url+'/ventas/cancelar');
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
    }else{
      alertify.error('Error al cancelar');
      console.log(respAsyncDetalles);
    }
  }//end store_cancelacion
  /*
  * SECCION VENTAS ------------------------------------------------------------------------------------------------
  */              
  function abonar(id_venta,saldo){
    $('#tituloModalAbonar').text('Abonar Folio: '+id_venta);
    $('#saldor_restante').text('$ '+saldo);
    $('#abonarModal').modal('show');
    $('#idVentaHide').val(id_venta);
  }
  async function store_abono(){
    let parametros = {
      'metodo': $('#metodoPagoModal').val(),
      'importe': $('#importeAbono').val(),
      'folio': $('#idVentaHide').val()
    }
    
    const respAsyncDetalles = await postData(parametros,url+'/ventas/abonar');
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

  
  function cambiaaFacturado(folio){
    Swal.fire({
      title: 'Marcar como Facturado',
      text: "FOLIO: "+folio,
      icon: 'question',
      allowOutsideClick:false,
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


  async function postStatus(parametros){
    const respAsyncDetalles = await postData(parametros,url+'/pagos/updateFacturado');
    if (respAsyncDetalles.success) {
      location.reload();  
    }else{
      alertify.error("Hubo un error, intente de nuevo.");          
    } 
  }

  // ------------ SEARCH VENTAS PENDIENTES--------------------------------------
  $(document).on("keyup", "#folioPendiente", delay(function (e) {
      let f = $('#folioPendiente').val();
      searchVentas('folio',f,'pendientes');
    }, 500));
  $(document).on("keyup", "#clientePendiente", delay(function (e) {
    let f = $('#clientePendiente').val();
    searchVentas('cliente',f,'pendientes');
  },500)); 
  // ------------ SEARCH VENTAS PAGADOS--------------------------------------
  $(document).on("keyup", "#folioPagados", delay(function (e) {
      let f = $('#folioPagados').val();
      searchVentas('folio',f,'pagados');
    }, 500));
  $(document).on("keyup", "#clientePagados", delay(function (e) {
    let f = $('#clientePagados').val();
    searchVentas('cliente',f,'pagados');
  },500)); 
  // ------------ SEARCH VENTAS CANCELADOS--------------------------------------
  $(document).on("keyup", "#folioCancelados", delay(function (e) {
      let f = $('#folioCancelados').val();
      searchVentas('folio',f,'cancelados');
    }, 500));
  $(document).on("keyup", "#clienteCancelados", delay(function (e) {
    let f = $('#clienteCancelados').val();
    searchVentas('cliente',f,'cancelados');
  },500)); 
  // --------------------------------------------------------------------------
  async function searchVentas(tipo,parametro,section){
    let param = {
      'tipo':tipo,
      'param':parametro,
      'seccion':section
    }
    const respAsyncDetalles = await postData(param,url+'/ventas/search');
    if (respAsyncDetalles.success) {
      pintaVentasHtml(respAsyncDetalles.params,section);
      console.log(respAsyncDetalles);
    }else{
      alertify.error("No encontrado");          
    }
  }
  function pintaVentasHtml(arrayDatos,seccion){
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
          ${formatMoney(totalItem,1,".",",")}
          </div>
          <div class="col-md-1 text-right">
          ${formatMoney(element.abonos[0].importe,1,".",",")}
          </div>
          <div class="col-md-1 text-right">
          ${ formatMoney(element.resta,1,".",",")}
          </div>
          <div class="col-md-1">
          ${element.fecha}
          </div>
          <div class="col-md-1 text-center">`;
          if (seccion == 'pendientes') {
            cadena += `<i class="fas fa-plus-circle incrementa" onclick="abonar(${element.folio},'${formatMoney(element.resta,1,".",",")}');"></i> 
                      <i class="fas fa-ban decrementa ml-2" 
                          onclick="cancelarVenta(
                              '${element.folio}',
                              '${element.cliente}',
                              '${formatMoney(totalItem,1,".",",")}  '                                                              
                          );">
                      </i> `
          }
          if (seccion == 'pagados') {
            cadena += `<i class="fas fa-ban decrementa ml-2" 
                          onclick="cancelarVenta(
                              '${element.folio}',
                              '${element.cliente}',
                              '${formatMoney(totalItem,1,".",",")}  '                                                              
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
    searchPagos('folio',f);
  },500));  
  $(document).on("keyup", "#nombreProveedor", delay(function (e) {
    let f = $('#nombreProveedor').val();
    searchPagos('proveedor',f);
  },500)); 
                       
  async function searchPagos(tipo,parametro){
    let param = {
      'tipo':tipo,
      'param':parametro
    }
    const respAsyncDetalles = await postData(param,url+'/pagos/search');
    if (respAsyncDetalles.success) {
      pintaPagosHtml(respAsyncDetalles.params);
    }else{
      alertify.error("No encontrado");          
    }
  }

  function pintaPagosHtml(arrayDatos){
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
              if (element.facturado == '1'){
                  cadena += `<i class="fas fa-toggle-on text-success"></i>`;
              }else{
                cadena += `<i class="fas fa-toggle-off text-danger decrementa" onclick="cambiaaFacturado('${element.folio}')"></i>`;
              }
          cadena +=`</div>
          <div class="col-md-1 text-right">
            ${ formatMoney(element.subtotal,1,".",",") }
          </div>
          <div class="col-md-1 text-right">
            ${ formatMoney(element.iva,1,".",",") }
          </div>
          <div class="col-md-1 text-right">
            ${ formatMoney(element.total,1,".",",") }
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
 $("#switchDeposito").on( 'change', function() {
  if( $(this).is(':checked') ) {
    $('#btnEnviarDeposito').prop( "disabled", false);   
                   
  }else{
    $('#btnEnviarDeposito').prop( "disabled", true);               
  }
});
$("#switchAceptarCorte").on( 'change', function() {
  if( $(this).is(':checked') ) {
    $('#btnAceptarCorte').prop( "disabled", false);                  
  }else{
    $('#btnAceptarCorte').prop( "disabled", true);               
  }
});
$('#btnDesglozarCorte').on('click',function(){   
  $('#desglozarCorteDiv').css('display', 'block');
});

/**
 * ENVIAR DEPOSITO A CUENTA
 */
 $('#btnEnviarDeposito').on('click',function(){ 
   let importe = $('#txt_deposito_a_cuenta').val();
   sendDeposito(importe);
  });

  async function sendDeposito(importe){    
    const respAsyncDetalles = await postData(importe,url+'/corte/store');
    if (respAsyncDetalles.success) {
      $('#txt_deposito_a_cuenta').removeClass("is-invalid");   
      console.log(respAsyncDetalles);
      alertify.success(respAsyncDetalles.msg);     
    }else{
      alertify.error(respAsyncDetalles.msg);   
      $('#txt_deposito_a_cuenta').addClass("is-invalid");       
    }
  }
/**
 * DESGLOZAR POR FECHA
 */
  
  $('#btnDesglozar').on('click',function(){ 
    let rango = {
      'desde': $('#desde').val(),
      'hasta':$('#hasta').val()
    }
    sendDesgloce(rango);
   });
 
   async function sendDesgloce(data){    
     const respAsyncDetalles = await postData(data,url+'/corte/desgloce_por_fecha');
     if (respAsyncDetalles.success) {   
       console.log(respAsyncDetalles);
       //alertify.success(msg);     
     }else{
       alertify.error('x');      
     }
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
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}

//});