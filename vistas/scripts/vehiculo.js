var tabla;
var tabla1;
const Toast = Swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 3000
});
//Función que se ejecuta al inicio 
function init() {

	mostrarform(false);
	listar();
	updateInfoBoxes();
	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	//Cargamos los items al select cliente  
	$.post("../ajax/venta.php?op=selectCliente", function (r) {
		$("#Idcliente").html(r);
		$('#Idcliente').selectpicker('refresh');

		// Verifica si ya hay un cliente seleccionado al cargar la página
		var Idpersona = $('#Idcliente').val();
		if (Idpersona) {
			mostrarpersona(Idpersona);  // Carga los datos del cliente seleccionado por defecto
		}
	});

	// Detectamos el cambio en el select del cliente
	$('#Idcliente').on('change', function () {
		var Idpersona = $(this).val(); // Obtenemos el valor seleccionado
		if (Idpersona) {
			mostrarpersona(Idpersona);
		}
	});

	$('#mVehiculo').addClass("nav-item has-treeview menu-open");
	$('#aVehiculo').addClass("active");
	$('#lVehiculo').addClass("active");

	$("#Imagenmuestra").hide();
	bsCustomFileInput.init();
	$('#customColorContainer').hide();
	inicializarSelectorDeColor('.color-picker-container', '#selectedColor');
}

function inicializarSelectorDeColor(contenedorSelector, inputDestino) {
	var $opciones = $(contenedorSelector + ' .color-option');
	var $input = $(inputDestino);
	var $customColorContainer = $('#customColorContainer');
	var $customColorInput = $('#customColorInput');
	$opciones.on('click', function () {
		$opciones.removeClass('selected');
		$(this).addClass('selected');
		var color = $(this).data('color');
		if (color === 'Otro') {
			$customColorContainer.show();
			$customColorInput.focus();
			// Limpia el input personalizado al mostrarlo
			$customColorInput.val('');
			$input.val('');
		} else {
			$customColorContainer.hide();
			$input.val(color);
		}
	});

	// Actualiza el valor cuando el usuario escribe manualmente
	$customColorInput.on('input', function () {
		$input.val($(this).val());
	});
}




function mostrarpersona(Idpersona) {
	$.post("../ajax/cliente.php?op=mostrar", { Idpersona: Idpersona },
		function (data, status) {
			data = JSON.parse(data);

			$("#Nombres").val(data.Nombres);
			$("#Num_documento").val(data.Num_documento);
			$("#Tipo_documento").val(data.Tipo_documento);
			$("#Tipo_documento").selectpicker('refresh');
			$("#Telefono").val(data.Telefono);
			$("#Direccion").val(data.Direccion ? data.Direccion : "Datos nulos");


		})
}

function Nuevocliente() {
	var EstaMarcado = $('#NuevoCliente').is(':checked');

	if (EstaMarcado) {
		// Ocultamos el select y mostramos el input
		$('#selectContainer').hide();
		$('#inputContainer').show();
		$('#Idcliente').removeAttr('required');

		// Habilitar campos para edición
		$("#Nombres").prop('readonly', false);
		$("#Num_documento").prop('readonly', false);
		$("#Tipo_documento").prop('disabled', false); // es un <select>
		$("#Telefono").prop('readonly', false);
		$("#Direccion").prop('readonly', false);
		limpiarCliente();
	} else {
		// Ocultamos el input y mostramos el select
		$('#inputContainer').hide();
		$('#selectContainer').show();
		$('#Idcliente').attr('required', 'required');
		// Desactivar edición
		$("#Nombres").prop('readonly', true);
		$("#Num_documento").prop('readonly', true);
		$("#Tipo_documento").prop('disabled', true); // desactiva select
		$("#Telefono").prop('readonly', true);
		$("#Direccion").prop('readonly', true);
		// Recargamos los datos en el select
		$.post("../ajax/vehiculo.php?op=selectCliente", function (r) {
			$("#Idcliente").html(r);
			$('#Idcliente').selectpicker('refresh');

			// Verifica si ya hay un cliente seleccionado al cargar la página
			var Idpersona = $('#Idcliente').val();
			if (Idpersona) {
				mostrarpersona(Idpersona);
			}
		});
	}
}

function limpiarCliente() {
	$("#Nombres").val("");
	$("#Num_documento").val("");
	$("#Tipo_documento").val("CEDULA");
	$("#Tipo_documento").selectpicker('refresh');
	$("#Telefono").val("");
	$("#Direccion").val("");
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
	// Limpiar los campos del formulario  
	$("#Idvehiculo").val("");
	$("#Idcliente").val($("#Idcliente option:first").val());
	$("#Idcliente").selectpicker('refresh');
	$("#Tipo").val("Taxi");
	$('#Tipo').selectpicker('refresh');
	$("#Marca").val("");
	$("#Placa").val("");
	$("#Color").val("");
	$("#Observaciones").val("");
	$("#Imagen").val('');
	$('.custom-file-label[for="Imagen"]').text('Seleccione Archivo');
	$("#Imagenmuestra").attr("src", ""); // limpia src de vista previa
	$("#Imagenmuestra").hide(); // oculta la imagen
	$("#Imagenactual").val(""); // limpia el valor oculto

	// Desmarcar el checkbox "Nuevo Cliente"
	$("#NuevoCliente").prop("checked", false);
	$('#inputContainer').hide();
	$('#selectContainer').show();
	$(".color-option").removeClass("selected");
	$('#customColorContainer').hide();
	$("#customColorInput").val("");
	// Restablecer la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var year = now.getFullYear();
	var hours = ("0" + now.getHours()).slice(-2);
	var minutes = ("0" + now.getMinutes()).slice(-2);
	var seconds = ("0" + now.getSeconds()).slice(-2);
	var fechaHora = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
	$('#Fecha').val(fechaHora);
	limpiarCliente();
 	Nuevocliente();
}

//Función mostrar formulario
function mostrarform(flag) {
	//limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled", false);
		$("#btnagregar").hide();
		$("#infoCards").hide();


	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#infoCards").show();
		$("#btnagregar").show();
		Nuevocliente();
	}

}

//Función cancelarform
function cancelarform() {
	limpiar();
	mostrarform(false);
}
function updateInfoBoxes() {
	$.ajax({
		url: '../ajax/vehiculo.php?op=listarconsulta',
		method: 'GET',
		dataType: 'json',

		success: function (data) {
			$('#total').text(data.total);
			$('#totala').text(data.totala);
			$('#totali').text(data.totali);
			$('#totalp').text(data.totalp);


		},
		error: function (xhr, status, error) {
			console.error('Error fetching data:', error);
		}
	});
}
// Actualizar cada 2 segundos (2000 milisegundos)
setInterval(updateInfoBoxes, 1000);

//Función Listar 
function listar() {
	tabla = $('#tbllistado').DataTable(
		{
			"responsive": true,
			"autoWidth": false,
			"lengthMenu": [5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
			"processing": true,//Activamos el procesamiento del datatables
			"ajax":
			{
				url: '../ajax/vehiculo.php?op=listar',
				type: "get",
				dataType: "json",
				error: function (e) {
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
					"first": "Primero", "last": "Último",
					"next": "Siguiente", "previous": "Anterior"
				}
			},
			"destroy": true,
			"pageLength": 5, // Paginación
			"order": [[0, "asc"]]//Ordenar (columna,orden)
		});
}



// Función para guardar o editar
function guardaryeditar(e) {
	e.preventDefault(); // No se activará la acción predeterminada del evento

	bootbox.confirm("¿Está Seguro de registrar el Vehiculo?", function (result) {
		if (result) {
			var formData = new FormData($("#formulario")[0]);
			// Verificar si el checkbox está marcado
			var isNuevoCliente = $("#NuevoCliente").is(":checked");
			// Cambiar el parámetro 'op' según el estado del checkbox
			var op = isNuevoCliente ? "guardaryeditarNuevoCliente" : "guardaryeditar";

			$.ajax({
				url: "../ajax/vehiculo.php?op=" + op, // Cambiar dinámicamente el 'op'
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				success: function (datos) {
					Toast.fire({ icon: 'success', title: datos, });
					mostrarform(false);
					tabla.ajax.reload();
				}
			});

			limpiar();
		}
	});
}



function mostrar(Idvehiculo) {
	$.post("../ajax/vehiculo.php?op=mostrar", { Idvehiculo: Idvehiculo }, function (data, status) {
		data = JSON.parse(data);
		mostrarform(true);

		$("#Idcliente").val(data.Idcliente);
		$("#Idcliente").selectpicker('refresh');
		$("#Nombres").val(data.Nombres);
		$("#Num_documento").val(data.Num_documento);
		$("#Tipo_documento").val(data.Tipo_documento);
		$("#Tipo_documento").selectpicker('refresh');
		$("#Telefono").val(data.Telefono);
		$("#Direccion").val(data.Direccion);
		$("#Fecha").val(data.Fecha);
		$("#Marca").val(data.Marca);
		$("#Placa").val(data.Placa);
		$("#Observaciones").val(data.Observaciones);
		$("#Tipo").val(data.Tipo);
		$("#Tipo").selectpicker('refresh');
		$("#Imagenmuestra").show();
		$("#Imagenmuestra").attr("src", "../files/vehiculo/" + data.Imagen);
		$("#Imagenactual").val(data.Imagen);
		$("#Idvehiculo").val(data.Idvehiculo);
		// Mostrar color seleccionado
		$("#selectedColor").val(data.Color);
		// Eliminar selección anterior
		$(".color-option").removeClass("selected");
		// Buscar color y seleccionarlo si existe en la lista
		var $colorMatch = $(".color-option").filter(function () {
			return $(this).data("color") === data.Color;
		});
		if ($colorMatch.length) {
			$colorMatch.addClass("selected");
			$("#customColorContainer").hide(); // Oculta input manual
			$("#customColorInput").val('');
		} else {
			$("#customColorContainer").show(); // Muestra input manual
			$("#customColorInput").val(data.Color);
		}
	});
}



//Función para desactivar registros
function desactivar(Idvehiculo) {
	bootbox.confirm("¿Está Seguro de desactivar el vehiculo?", function (result) {
		if (result) {
			$.post("../ajax/vehiculo.php?op=desactivar", { Idvehiculo: Idvehiculo },
				function (e) {
					Toast.fire({ icon: 'error', title: e, });
					tabla.ajax.reload();
				});
		}
	})
}

//Función para activar registros
function activar(Idvehiculo) {
	bootbox.confirm("¿Está Seguro de activar el vehiculo?", function (result) {
		if (result) {
			$.post("../ajax/vehiculo.php?op=activar", { Idvehiculo: Idvehiculo },
				function (e) {
					Toast.fire({ icon: 'success', title: e, });
					tabla.ajax.reload();
				});
		}
	})
}


init();