//OBTENER LA URL PREVIA
let prevUrl = document.referrer;
const baseUrl = document.getElementById('baseUrl').value;
/**
 * TABLE SQL ABONO_TYPE
 */
const EFECTIVO = 1;
const CHEQUE = 2;
const DEBITO = 3;
const TRASNFERENCIA = 4;

let parametros = {
    /*
    * BD TABLA ::: CLIENTES
    * ID=1 -> PUBLICO GENERAL
    */
    id_cliente:0,
    id_mesa:'',
    nombre_cliente:'Publico General',
    direccion_cliente:'',
    telefono_cliente:'',
    rfc_cliente:'',
    razon_social_cliente:'',
    correo_cliente:'',
    id_usuario:'',
    carrito:'',
    metodo_pago:'',
    importe_pago:0,
    importe_nota:'',
    pagos:'',
    netoPagado:''
}
//Array pagos:
//     'metodo' : '',
//     'importe':''
let pagos = [];     
//const alertas = new Alertas();
/**
 * OBTENER CARRITO DE COMPRAS LOCAL-STORAGE
 */
getCarrito();
getImporteCarrito();
getUsuario();
assignClientName();
/**
 * OBTIENE EL ID MESA
 */
parametros.id_mesa = $('#mesa').data('idmesa');
/**
 * OBTENER CLIENTE DE VIEW CLIENTES/INDEX
 */
function getCliente(params) {
    parametros.id_cliente = params.dataset.id;
    parametros.nombre_cliente = params.dataset.nombre; 
    parametros.id_mesa = params.dataset.mesa; 
    addLocalStorage(parametros.id_cliente, parametros.nombre_cliente);
    // window.location = prevUrl;
    window.location = baseUrl+"cobrar/payment/"+parametros.id_mesa;

}
/**
 * ASIGNA NOMBRE DEL CLIENTE LABEL 
 */
function assignClientName() {
    let cadena = "";
    let items = [];
    if (localStorage.getItem('cliente') === null) {
        items = [];
        cadena = `<i class="fas fa-user fa-lg text-success"></i> Agregar cliente`;
    } else {
        let info = localStorage.getItem('cliente');
        items = JSON.parse(info);
        parametros.id_cliente = items.id;
        parametros.nombre_cliente = items.nombre;
        cadena = `<i class="fas fa-user fa-lg text-success"></i> ${parametros.nombre_cliente}`;
    }    
    $('.lblCliente').html(cadena);
}

function addLocalStorage(idclient,nameclient) {
    let data = {
        id: idclient,
        nombre : nameclient
    }
    localStorage.setItem('cliente', JSON.stringify(data));
}



function getUsuario() {
    parametros.id_usuario = $('#userId').val();
}
/**
 * EVENTOS METODO DE PAGO
 */
function validarPagos(){
    if (parametros.importe_pago < parametros.importe_nota) {
        //venta a credito
        if ( parametros.id_cliente == 0 ) {
            //Alerta debe seleccionar cliente
            alertaSinClienteSeleccionado();
        } else {
            //cliente seleccionado
            //se guardara venta a credito?
            alertaPago();
        }        
    }else{
        console.log('con pago' + parametros.importe_pago);
        console.log(parametros);
        sendData();
    }
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
function deleteMetodo(elemento){
    let item = elemento.parentElement.parentElement;
    item.remove();
    getInputImporte();
}
function getInputImporte() {
    
    let totalImport = 0;
    const inputElemnt = document.querySelectorAll('.inputPago');
    let methodPag = '';
    pagos = [];    
    //RECORRO CADA INPUT PAGO
    inputElemnt.forEach(element => {
        totalImport += parseFloat(element.value); 
        methodPag = element.dataset.metodo;
        // pagos.push({
        //     metodo: methodPag,
        //     importe: element.value
        // });   
        // parametros.pagos = pagos;       
    });
    parametros.netoPagado = totalImport;
    //parametros.importe_pago = totalImport;
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
/**
 * /EVENTOS METODO DE PAGO
 */
function getCarrito() {
    parametros.carrito = getLocalStorage();        
}
function getImporteCarrito(){
    parametros.importe_nota = recorreCarrito(parametros.carrito);
    setLabelTotalImporte(parametros.importe_nota);
}
function setLabelTotalImporte(total){
    let dinero = formatMoney(total,1,".",",");
    let cadena = `<i class="fas fa-dollar-sign fa-5x text-success"> ${dinero}</i>`;
    $('#totalImporte').html(cadena);
}
function recorreCarrito(cart){
    let importeCart = 0;
    cart.forEach(element => {
        importeCart += (element.price);
    });
    return importeCart.toFixed(1);
}
function getLocalStorage() {
    let items = [];
    if (localStorage.getItem('shoppingCart') === null) {
        items = [];
    } else {
        let info = localStorage.getItem('shoppingCart');
        items = JSON.parse(info);
    }
    return items;
}

function deleteItemLocalStorage(key){
    localStorage.removeItem(key);
}

/**
 * FIN OBTENER CARRITO DE COMPRAS LOCAL-STORAGE
 */


/**
* ALERTA DE BORRADO?
* REDIRIGE A LA PAGINA USANDO DATA-URL 
*/
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

function alertaSinClienteSeleccionado() {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-warning ml-2',
            //cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Venta a Credito',
        text: "Debe Seleccionar un Cliente",
        icon: 'warning',
        showCancelButton: false,
        confirmButtonText: 'Volver',
        //cancelButtonText: 'No, Volver !',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            result.dismiss === Swal.DismissReason.cancel
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

async function postData(url,datos) {
    const response = $.ajax({
        url: url,
        data: { data: datos },
        method: 'post',
        async: true,
        dataType: 'json',
    });
    const data = await response;
    return data;
}

async function sendData() {
    const urlDetalles = baseurl+'/Ventas/registrar_venta';
    const respAsyncDetalles = await postData(urlDetalles,parametros);
    console.log(parametros);
    if (respAsyncDetalles.success) {
        console.log(respAsyncDetalles);
        // parametros.nombre_cliente = '';
        // parametros.carrito = '';
        ventaRealizada();  
    }else{
        console.log(respAsyncDetalles.error);
        let msg = '';
        respAsyncDetalles.error.forEach(element => {
            msg += element.msg+'<br>';
        });
        Swal.fire(
            'Error',
            msg,
            'error'
        )
    }
}

function ventaRealizada(){
    
    deleteItemLocalStorage('cliente');        
    deleteItemLocalStorage('shoppingCart'); 
    parametros = {
        id_cliente:'',
        nombre_cliente:'',
        direccion_cliente:'',
        telefono_cliente:'',
        rfc_cliente:'',
        razon_social_cliente:'',
        correo_cliente:'',
        id_usuario:'',
        carrito:'',
        metodo_pago:'',
        importe_pago:0,
        importe_nota:'',
        pagos:''
    }
    pagos = [];
    window.location = baseUrl;
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



    









    








