//SE DEFINE EL ESTILO Y DISEÑO DE LA ALERTA
class Alertas {

    alert_confirm_success(msg) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success ml-2',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire(
            msg.title,
            msg.text,
            msg.button
        )
    }
}//end class Alertas



/**
 * ALERTA DE BORRADO?
 * REDIRIGE A LA PAGINA USANDO DATA-URL 
 */
function confirmar_alert(e){

    let url = e.dataset.url;

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success ml-2',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Borrar registro?',
        text: "Se borrará del sistema",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, borrar!',
        cancelButtonText: 'No, cancelar!',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            // swalWithBootstrapButtons.fire(
            //     'Deleted!',
            //     'Your file has been deleted.',
            //     'success'
            // )
            window.location = url;
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

