var tabla;
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
//Función que se ejecuta al inicio
function init(){

	mostrarform(false);
	listar();
	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	}) 

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

function limpiar() {
 
	$("#Nombres").val("");
	$("#Num_documento").val("");
	$("#Tipo_documento").val("CEDULA");
	$("#Tipo_documento").selectpicker('refresh');
	$("#Direccion").val("");
	$("#Telefono").val("");
	$("#Nombre_empresa").val("");
	$("#Idproveedor").val("");
}



//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{ 
		$("#listadoregistros").hide();
		//$(".info-box").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		//$(".info-box").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);

		$("#activeSensorLocal").attr("disabled", false);
		$("#fingerPrint").css("display", "none");
	
}


//Función Listar 
function listar()
{ 
	tabla=$('#tbllistado').DataTable(
	{   
		"responsive": true,
		"autoWidth": false, 
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"processing": true,//Activamos el procesamiento del datatables
		"ajax":
				{
					url: '../ajax/proveedor.php?op=listar',
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
            }
        },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	});
}


//Función para guardar o editar
function guardaryeditar(e) {
	e.preventDefault(); // No se activará la acción predeterminada del evento
  
	bootbox.confirm("¿Está Seguro de guardar al Proveedor?", function(result) {
	  if (result) {
		var formData = new FormData($("#formulario")[0]);
  
		$.ajax({
		  url: "../ajax/proveedor.php?op=guardaryeditar",
		  type: "POST",
		  data: formData,
		  contentType: false,
		  processData: false,
   
		  success: function(datos) {
			Toast.fire({icon: 'success',title: datos,});
			mostrarform(false);
			tabla.ajax.reload();
			limpiar();
		  }
		});
  
	  }
	});
  }

function mostrar(Idproveedor)
{
	$.post("../ajax/proveedor.php?op=mostrar",{Idproveedor : Idproveedor}, function(data, status)
	{   data = JSON.parse(data);		
		mostrarform(true);
	
		$("#Nombres").val(data.Nombres);
		$("#Num_documento").val(data.Num_documento);
		$("#Tipo_documento").val(data.Tipo_documento);
		$("#Tipo_documento").selectpicker('refresh');
		$("#Direccion").val(data.Direccion);
		$("#Telefono").val(data.Telefono);
		$("#Nombre_empresa").val(data.Nombre_empresa);
		$("#Idproveedor").val(data.Idproveedor);

 	})

}
//Función para desactivar registros
function desactivar(Idproveedor)
{
	bootbox.confirm("¿Está Seguro de desactivar el Proveedor?", function(result){
		if(result)
        {
        	$.post("../ajax/proveedor.php?op=desactivar", {Idproveedor : Idproveedor}, function(e){
			    Toast.fire({icon: 'error',title: e,});
	            tabla.ajax.reload();
        	});	
        }
	})
}
//Función para activar registros
function activar(Idproveedor)
{
	bootbox.confirm("¿Está Seguro de activar el Proveedor?", function(result){
		if(result)
        {
        	$.post("../ajax/proveedor.php?op=activar", {Idproveedor : Idproveedor}, function(e){
			    Toast.fire({icon: 'success',title: e,});
	            tabla.ajax.reload();
        	});	
        }
	})
}


init();
