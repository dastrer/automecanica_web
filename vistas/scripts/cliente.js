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
function limpiar() {
 


	$("#Nombres").val("");
	$("#Num_documento").val("");
	$("#Tipo_documento").val("CEDULA");
	$("#Tipo_documento").selectpicker('refresh');
	$("#Telefono").val("");
	$("#Direccion").val("");
	$("#Idpersona").val("");
}


//Función cancelarform para ocultar el formulario
function cancelarform()
{
	limpiar();

}

function validarLongitud(input, minLength, maxLength) {
    var inputLength = input.value.length;
    
    if (inputLength < minLength) {
        input.setCustomValidity(`El número debe tener al menos ${minLength} dígitos.`);
    } else if (inputLength > maxLength) {
        input.setCustomValidity(`El número no puede tener más de ${maxLength} dígitos.`);
    } else {
        input.setCustomValidity("");
    }
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
					url: '../ajax/cliente.php?op=listar',
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
    bootbox.confirm("¿Está Seguro de guardar al Cliente?", function(result) {
        if (result) {
            var formData = new FormData($("#formulario")[0]);
  
            $.ajax({
                url: "../ajax/cliente.php?op=guardaryeditar",
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

function mostrar(Idpersona)
{
	$.post("../ajax/cliente.php?op=mostrar",{Idpersona : Idpersona}, 
	function(data, status)
	{
		data = JSON.parse(data);		

		 $("#Nombres").val(data.Nombres);
		 $("#Num_documento").val(data.Num_documento);
		 $("#Tipo_documento").val(data.Tipo_documento);
		 $("#Tipo_documento").selectpicker('refresh');
		 $("#Telefono").val(data.Telefono);
	     $("#Direccion").val(data.Direccion);
		 $("#Idpersona").val(data.Idpersona);

 	})
}

//Función para desactivar registros
function desactivar(Idpersona)
{
	bootbox.confirm("¿Está Seguro de desactivar al Cliente?", function(result){
		if(result)
        {
        	$.post("../ajax/cliente.php?op=desactivar", {Idpersona : Idpersona}, 
			function(e){
				Toast.fire({icon: 'error',title: e,});
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(Idpersona)
{
	bootbox.confirm("¿Está Seguro de activar al Cliente?", function(result){
		if(result)
        {
        	$.post("../ajax/cliente.php?op=activar", {Idpersona : Idpersona},
			 function(e){
				Toast.fire({icon: 'success',title: e,});
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();