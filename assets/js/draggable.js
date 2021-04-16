const baseurl = $('#baseurl').val();
$(".item").draggable({
    stop: function(event, ui) {
        // Show dropped position.
        var pos = $(this).position();        
        console.log(pos);

        let ruta = baseurl+'/actualiza/mesa_draggable';
        let id = this.id;
        let top = pos.top;
        let left = pos.left;
        mesa(id,top,left,ruta);

    }

});
// $(".item").click(function(){
//     console.log(this.id);
// });
$(".item" ).resizable({
    stop: function(event, ui) {
        // Show dropped position.
        let alto = ui.size.height;     
        let ancho = ui.size.width;
        console.log('id->'+this.id);
        console.log('height'+alto);
        console.log('width'+ancho);

        let ruta = baseurl+'/actualiza/mesa_resizable';
        let id = this.id;
        let width = ui.size.width;
        let height = ui.size.height;
        mesa(id,height,width,ruta);
    }
});


// function moverMesas(){    
//     let mesas = document.querySelectorAll('.mesa');
//     // let elemento;
//     // elemento = mesas.className;
//     // elemento = mesas.classList.add('item');
//     mesas.forEach(element => {
//         element.classList.add('item');
//         console.log(element);
//     });
//     console.log(mesas);
// }


/**
 * FUNCION MESAS
 */
async function mesa(id,top,left,url){
    const respuesta = await moverMesas(id,top,left,url);
    if(respuesta.success){
        console.log('guardado');
    }else{
        console.log('No guardado');
        console.log(url);
    }
    console.log(respuesta);
}
async function moverMesas(id,a,b,url){
    const response = $.ajax({
        type: 'ajax',
        method: 'post',
        url: url,
        data:{id:id,item1:a,item2:b}
    });
    const data = await response;
    return data;
}