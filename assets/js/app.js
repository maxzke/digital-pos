let parametros = {
  metodo_pago : "",
  netoPagado:0,
  pagos:"",
  importe_nota:0,
  importe_pago:0,
  id_cliente:0,
  nombre_cliente:""
}
const inputElemnt = document.querySelectorAll('.inputPago');
/*================================================
=            Subiendo Foto de Cliente            =
================================================*/
$(".nuevaFoto").change(function(){
    var imagen = this.files[0];
    console.log("imagen",imagen);
    if (imagen["type"] != "image/jpeg" && imagen["type"] != "image/png") {
      $(".nuevaFoto").val("");
      swal({
        title: "Error al subir la imagen",
        text: "La imagen debe estar en formato JPG ó PNG",
        type: "error",
        confirmationButtonText: "Cerrar"
      });
    }else{
      var datosImagen = new FileReader;
      datosImagen.readAsDataURL(imagen);
      $(datosImagen).on("load",function(event){
        var rutaImagen = event.target.result;
        $(".previsualizar").attr("src",rutaImagen);
      })
    }
  });
  /*=====  End of Subiendo Foto de Cliente  ======*/

  
/*
* CONTROLLER VENTAS / CREDITOS A CLIENTES
*/
function get_data_cliente(idx,nombreCliente){
  parametros.id_cliente = idx;
  parametros.nombre_cliente=nombreCliente
  response(idx);
  //console.log('click'+idx);
  $('#nombreCliente').html(nombreCliente);
  //console.log('click'+nombreCliente);
}
function get_ticket(id){
  get_tickey_by_id_venta(id);
}
async function response(id) {
    let bUrl = $('#url_hide').val();
	const url = bUrl+'ventas/creditos_cliente';
	const respAsyncDetalles = await postId(id,url);
	if (respAsyncDetalles.success) {
		console.log(respAsyncDetalles);
    saldoshtml(respAsyncDetalles.saldo,respAsyncDetalles.clientes);
    
	}
}
async function get_tickey_by_id_venta(id) {
  let bUrl = $('#url_hide').val();
const url = bUrl+'ventas/get_ticket_by_id_venta';
const respAsyncDetalles = await postId(id,url);
if (respAsyncDetalles.success) {
  console.log(respAsyncDetalles);
  ticket(respAsyncDetalles.total_ticket,respAsyncDetalles.ticket);
  abonoshtml(respAsyncDetalles.abonos);
}
}
// async function get_abonos_by_id_venta(Id){

// }

async function postId(id,url){
	const response = $.ajax({
		url: url,
		data:{id_cliente:id},
		method:'post',
		async:true,
		dataType: 'json',		
	});
	const data = await response;
	return data;
}
//saldos
function saldoshtml(saldo,ventas){
  let cadena = `<button type="button" class="btn btn-warning btn-block">
                    Saldo <span class="badge badge-light">$ ${saldo}</span>
                </button>
                <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#abonosModal">
                    Abonar
                </button>
                <ul class="list-group list-group-flush text-left mt-1">
                    <li class="list-group">`;
                        ventas.forEach(element => {
                          cadena += `<button  
                                    class="text-white list-group-item list-group-item-action btn bg-info text-capitalize"
                                    onclick="get_ticket('${element.id_venta}');">
                                    Ticket:  ${element.niceDate}
                                    </button>`
                        });
                            
        cadena +=`</li>                
                    </ul>`;
  $('#saldos').html(cadena); 
  //setLabelTotalImporte(saldo);           
  let dinero = formatMoney(saldo,1,".",",");
  let cadenasaldo = `<i class="fas fa-dollar-sign fa-5x text-success"> ${dinero}</i>`;
  $('#totalImporte').html(cadenasaldo);
  parametros.importe_nota = saldo;
}
//Abonos
function abonoshtml(ticket){
  let cadena = "";
  let ban = 0;
  ticket.forEach(element => {
    if(element.importe > 0) {   
      ban = 1;   
      cadena += `<tr>
                  <td>${element.fecha}</td>
                  <td>${element.importe}</td>
                  <td class="text-capitalize">${element.metodo}</td>
                </tr>`
    }
  });
  if(ban==1) {
    $('#abonos tbody').html(cadena);       
  }else{
    $('#abonos tbody').html("");       
  }       
}
function setLabelTotalImporte(total){
  let dinero = formatMoney(total,1,".",",");
  let cadena = `<i class="fas fa-dollar-sign fa-5x text-success"> ${dinero}</i>`;
  $('#totalImporte').html(cadena);
}
  

function ticket(total,carrito){  
  let output = "";
  carrito.forEach(element => {
    output += `<tr>
                  <th scope="row">`+ element.producto +`</th>
                  <td>` + element.cantidad + `</td>
                  <td>` + element.precio + `</td>
                  <td>` + element.importe + `</td>
                </tr>`;
  });  
  output += `<tr>
              <th scope="col"></th>
              <th scope="col"></th>
              <th scope="col"></th>
              <th scope="col">${total[0].importe}</th>                        
              </tr>`;

  $('#carticket tbody').html(output);
  //$('#carticket tfoot').html(foter);
  //
}



function validarPagos(){
    if (parametros.importe_pago<=0) {
        //alertaPago();
        console.log('sin abono');
    }else{
        console.log('con pago' + parametros.importe_pago);
        console.log(parametros);
        sendData();        
    }
}
async function sendData() {
    let bUrl = $('#url_hide').val();
    const urlDetalles = bUrl+'ventas/recibe_pago_venta_a_credito';
    const respAsyncDetalles = await postId(parametros,urlDetalles);
    if (respAsyncDetalles.success) {
        console.log(respAsyncDetalles);
        response(parametros.id_cliente);
        $('#nombreCliente').html(parametros.nombre_cliente);
        inputElemnt.forEach(element => {
            element.value = 0;
        });
    }else{
      console.log(respAsyncDetalles);
    }
}
function alertaPago() {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success ml-2',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Sin Abono',
        text: "Desea Continuar ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, Validar !',
        cancelButtonText: 'No, Volver !',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            pagos.push({
                metodo: 1,
                importe: 0
            });   
            parametros.importe_pago = 0;
            parametros.pagos = pagos;
            parametros.netoPagado = 0;
            sendData();
            // swalWithBootstrapButtons.fire(
            //     'Guardado!',
            //     'Your file has been deleted.',
            //     'success'
            // )
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            // swalWithBootstrapButtons.fire(
            //     'Cancelled',
            //     'Your imaginary file is safe :)',
            //     'error'
            // )
        }
    })

}
function setMetodoPago(metodo,id) {
  const abonos_table = document.querySelector('#tableMetodo tbody');
  parametros.metodo_pago = id;    
  abonos_table.innerHTML = `<tr class="table-active">
      <td>
      <input 
          class="form-control inputPago"
          type="text"
          onkeyup="getInputImporte()"
          onclick="this.select()"
          value="0"
          data-metodo="${parametros.metodo_pago}">
      </td>
      <td class="text-capitalize tdImporte">0</td>
      <td class="text-capitalize">${metodo}</td>
      <td>
          <a href="#" onclick="deleteMetodo(this);">
          <i class="fas fa-times-circle fa-2x text-danger"></i>
          </a>
      </td>
  </tr>`;
  insertarCambio(0);
  getInputImporte();
}

function getInputImporte() {
  let totalImport = 0;
  console.log('keypress');
  let input = document.querySelectorAll('.inputPago');
  let methodPag = '';
  let pagos = [];    
  //RECORRO CADA INPUT PAGO
  input.forEach(element => {
      totalImport += parseFloat(element.value); 
      methodPag = element.dataset.metodo;
  });
  parametros.netoPagado = totalImport;
  parametros.importe_pago = totalImport;
  insertarCambio(totalImport);
  pagos.push({
      metodo: methodPag,
      importe: parametros.importe_pago
  });   
  parametros.pagos = pagos;
}
function insertarCambio(importe) {
  const lblCambio = document.querySelector('#lblCambio');
  const tdImporte = document.querySelector('.tdImporte');
  if (importe > parseFloat(parametros.importe_nota)) {
      let cambio = importe - parseFloat(parametros.importe_nota);
      lblCambio.innerHTML = `<span class="h1 text-warning">Cambio: $ ${cambio.toFixed(1)}</span>`;
      tdImporte.innerHTML = parametros.importe_nota;
      parametros.importe_pago = parametros.importe_nota;
  }else{
      lblCambio.innerHTML = `<span class="h3"></span>`;
      tdImporte.innerHTML = importe;
      parametros.importe_pago = importe;
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


  /**
  * TABLAS
   */
   
$('#content-clientes').DataTable(
	{
		//     'dom'  : 'Bfrtip',
		//     buttons: [
		//   {
		//       extend: 'print',
		//         text: 'Imprimir'          
		//   },
		//   {
		//     extend: 'pdf',
		//     text: 'PDF'
		//   },
		//   {
		//     extend: 'excel',
		//     text: 'Excel'
		//   }

		// ],     
		"scrollY": 250,
		"responsive": true,
		'paging': false,
		'lengthChange': false,
		'searching': true,
		'ordering': false,
		'info': false,
		'autoWidth': true,
		// 'columns': [
		//             { "width": "10%" },
		//             { "width": "30%" },
		//             { "width": "10%" },
		//             { "width": "10%" },
		//             { "width": "10%" },
		//             { "width": "10%" },
		//             { "width": "10%" }
		//           ],
		/*Cambiando a espanol el lenguaje*/
		'language': {

			"sProcessing": "Procesando...",
			"sLengthMenu": "Mostrar _MENU_ registros",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "Ningun cliente",
			"sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando del 0 al 0 de un total de 0 registros",
			"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix": "",
			"sSearch": "Buscar:",
			"sUrl": "",
			"sInfoThousands": ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst": "Primero",
				"sLast": "Último",
				"sNext": "Siguiente",
				"sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
});
$('#carticket').DataTable(
	{
		//     'dom'  : 'Bfrtip',
		//     buttons: [
		//   {
		//       extend: 'print',
		//         text: 'Imprimir'          
		//   },
		//   {
		//     extend: 'pdf',
		//     text: 'PDF'
		//   },
		//   {
		//     extend: 'excel',
		//     text: 'Excel'
		//   }

		// ],     
		"scrollY": 250,
		"responsive": true,
		'paging': false,
		'lengthChange': false,
		'searching': false,
		'ordering': false,
		'info': false,
		'autoWidth': true,
		// 'columns': [
		//             { "width": "10%" },
		//             { "width": "30%" },
		//             { "width": "10%" },
		//             { "width": "10%" },
		//             { "width": "10%" },
		//             { "width": "10%" },
		//             { "width": "10%" }
		//           ],
		/*Cambiando a espanol el lenguaje*/
		'language': {

			"sProcessing": "Procesando...",
			"sLengthMenu": "Mostrar _MENU_ registros",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "Cargar ticket",
			"sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando del 0 al 0 de un total de 0 registros",
			"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix": "",
			"sSearch": "Buscar:",
			"sUrl": "",
			"sInfoThousands": ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst": "Primero",
				"sLast": "Último",
				"sNext": "Siguiente",
				"sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
});

$('#abonos').DataTable(
	{
		//     'dom'  : 'Bfrtip',
		//     buttons: [
		//   {
		//       extend: 'print',
		//         text: 'Imprimir'          
		//   },
		//   {
		//     extend: 'pdf',
		//     text: 'PDF'
		//   },
		//   {
		//     extend: 'excel',
		//     text: 'Excel'
		//   }

		// ],     
		"scrollY": 250,
		"responsive": true,
		'paging': false,
		'lengthChange': false,
		'searching': false,
		'ordering': false,
		'info': false,
		'autoWidth': true,
		// 'columns': [
		//             { "width": "10%" },
		//             { "width": "30%" },
		//             { "width": "10%" },
		//             { "width": "10%" },
		//             { "width": "10%" },
		//             { "width": "10%" },
		//             { "width": "10%" }
		//           ],
		/*Cambiando a espanol el lenguaje*/
		'language': {

			"sProcessing": "Procesando...",
			"sLengthMenu": "Mostrar _MENU_ registros",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "Ningun abono",
			"sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando del 0 al 0 de un total de 0 registros",
			"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix": "",
			"sSearch": "Buscar:",
			"sUrl": "",
			"sInfoThousands": ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst": "Primero",
				"sLast": "Último",
				"sNext": "Siguiente",
				"sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
});