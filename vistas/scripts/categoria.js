//variable total
var tabla; 
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
//Función que se ejecuta al inicio
//da referencia a otras funciones que vamos a implementar
function init(){
	listar();
	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	 
	}) 
  
}

//Función limpiar
//los objetos que vamos a implementar lo va a dejar en vacio
function limpiar()
{
	$("#Nombre").val("");
	$("#Descripcion").val("");
	$("#Idcategoria").val("");
}


//Función cancelarform para ocultar el formulario
function cancelarform()
{
	limpiar();

}

//Función Listar -> peticion AJAX al archivo categoria.php y le envia un
//valor a la variable OP que la variable categoria.php lo esta
//resivie//Función Listar 
function listar()
{ 
	tabla=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false,
		"lengthMenu": [ 3, 5, 10, 25, 50],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
					url: '../ajax/categoria.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"language": {
			"zeroRecords": "No se encontraron resultados",
			"lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando  _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
			"infoFiltered": "(filtrado de _MAX_ registros totales)",
			"sSearch": "Buscar:",
			"paginate": {
                "first": "Primero","last": "Último",
                "next": "Siguiente","previous": "Anterior"
            },
          
        },
		"bDestroy": true,
		"iDisplayLength": 3,//Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	});
}
//Función para guardar o editar
function guardaryeditar(e) {
    e.preventDefault();
    bootbox.confirm("¿Está Seguro de guardar la Categoria?", function(result) {
        if (result) {
            var formData = new FormData($("#formulario")[0]);
  
            $.ajax({
                url: "../ajax/categoria.php?op=guardaryeditar",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos) {
			        Toast.fire({icon: 'success',title: datos,});
                    tabla.ajax.reload();
                    limpiar();
                }
            });
        }
    });
}

function mostrar(Idcategoria)
{
	$.post("../ajax/categoria.php?op=mostrar",{Idcategoria : Idcategoria}, 
	function(data, status)
	{
		data = JSON.parse(data);		

		$("#Nombre").val(data.Nombre);
		$("#Descripcion").val(data.Descripcion);
 		$("#Idcategoria").val(data.Idcategoria);

 	})
}

//Función para desactivar registros
function desactivar(Idcategoria)
{
	bootbox.confirm("¿Está Seguro de desactivar la Categoria?", function(result){
		if(result)
        {
        	$.post("../ajax/categoria.php?op=desactivar", {Idcategoria : Idcategoria}, 
			function(e){
				Toast.fire({icon: 'error',title: e,});
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(Idcategoria)
{
	bootbox.confirm("¿Está Seguro de activar la Categoria?", function(result){
		if(result)
        {
        	$.post("../ajax/categoria.php?op=activar", {Idcategoria : Idcategoria},
			 function(e){
				Toast.fire({icon: 'success',title: e,});
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();