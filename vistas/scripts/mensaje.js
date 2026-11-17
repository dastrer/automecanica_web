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
}

//Función mostrar formulario
function mostrarform(flag)
{
	//limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").hide();
	}
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
					url: '../ajax/mensaje.php?op=listar',
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
	    "order": [[ 1, "DESC" ]]//Ordenar (columna,orden)
	});
}
//Función para desactivar registros
function desactivar(Idmensaje)
{
    bootbox.confirm("Marcar como Respondido?", function(result){
        if(result)
        {
            $.post("../ajax/mensaje.php?op=desactivar", {Idmensaje : Idmensaje},
            function(e){
			    Toast.fire({icon: 'success',title: e,});
                tabla.ajax.reload();
            });
        }
    })
}

//funcion para activar registros 
function activar(Idmensaje)
{
    bootbox.confirm("Poner en Pendiente?", 
    function(result)
    {
        if(result)
        {
            $.post("../ajax/mensaje.php?op=activar",
            {
                Idmensaje:Idmensaje
            },
            function(e)
            {
			    Toast.fire({icon: 'success',title: e,});
                tabla.ajax.reload();
            }
            );
        }
    })
}

//funcion para enviar mensaje
//Recibe el número de teléfono como parámetro 
function mensaje(Telefono) {
    bootbox.confirm("¿Está seguro de enviar el mensaje?", function(result) {
        if (result) {
            var Mensaje = "Hola Gracias por Solicitar Nuestro Servicio, Como Podemos Ayudarte? Tambien puedes Ubicarnos en https://maps.app.goo.gl/oqi5ajQKqwMwZWVV9";
            var url = 'https://wa.me/+591' + Telefono + '?text=' + encodeURIComponent(Mensaje);
            window.open(url);
            Toast.fire({
                icon: 'success',
                title: "Mensaje enviado"
            });
        }
    });
}


init();