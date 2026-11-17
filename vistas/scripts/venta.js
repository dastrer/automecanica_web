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

		})
}

function Nuevocliente() {
	var EstaMarcado = $('#NuevoCliente').is(':checked');

	if (EstaMarcado) {
		// Ocultamos el select y mostramos el input
		$('#selectContainer').hide();
		$('#inputContainer').show();
		$('#Idcliente').removeAttr('required');
		$("#Nombres").prop('readonly', false);
		$("#Num_documento").prop('readonly', false);
		$("#Tipo_documento").prop('disabled', false); // es un <select>
		$("#Telefono").prop('readonly', false);
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
		// Recargamos los datos en el select
		$.post("../ajax/venta.php?op=selectCliente", function (r) {
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
}


function generarNumero(Tipo_comprobante) {
	$.ajax({
		url: '../ajax/venta.php?op=generarNumero',
		type: 'POST',
		data: { Tipo_comprobante: Tipo_comprobante },

		success: function (data) {
			var response = JSON.parse(data);
			var numero = parseInt(response.ultimo_numero_recibo);
			numero = numero + 1;
			$("#Num_comprobante").val(numero);
		}
	});
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
	$("#Idventa").val("");
	$("#Idcliente").val($("#Idcliente option:first").val());
	$("#Idcliente").selectpicker('refresh');
	$("#Tipo_comprobante_recibo").prop("checked", false);
	$("#Tipo_comprobante_ticket").prop("checked", false);
	$("#Num_comprobante").val("");
	$("#numero").html("");
	$("#tipopago_efectivo").prop("checked", false);
	$("#tipopago_deposito").prop("checked", false);
	$(".filas").remove();
	$("#Monto").val("");
	$("#Cambio").val("");
	$("#totales3").html("Bs/. 0.00");
	$("#Total").val("");

	// Desmarcar el checkbox "Nuevo Cliente"
	$("#NuevoCliente").prop("checked", false);
	$('#inputContainer').hide();
	$('#selectContainer').show();

	// Establecer la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + month + "-" + day;
	$('#Fecha').val(today);

	// Establecer la hora actual
	var hours = ("0" + now.getHours()).slice(-2);
	var minutes = ("0" + now.getMinutes()).slice(-2);
	var seconds = ("0" + now.getSeconds()).slice(-2);
	var currentTime = hours + ":" + minutes + ":" + seconds;
	$('#Hora').val(currentTime);
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
		listarArticulos();
		$("#infoCards").hide();
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles = 0;
		$("#btnAgregarArt").show();

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
		url: '../ajax/venta.php?op=listarconsulta',
		method: 'GET',
		dataType: 'json',

		success: function (data) {
			$('#total').text(data.total);
			$('#totalr').text(data.totalr);
			$('#totalp').text(data.totalp);
			$('#totala').text(data.totala);


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
				url: '../ajax/venta.php?op=listar',
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
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		});
}


//Función ListarArticulos
function listarArticulos() {
	tabla1 = $('#tblarticulos').DataTable(
		{
			"responsive": true,
			"autoWidth": false,
			"lengthMenu": [5, 10, 25, 50, 75],//mostramos el menú de registros a revisar
			"processing": true,//Activamos el procesamiento del datatables
			"ajax":
			{
				url: '../ajax/venta.php?op=listarArticulos',
				type: "get",
				dataType: "json",
				error: function (e) {
					console.log(e.responseText);
				}
			},
			"language": {
				"lengthMenu": "Mostrar _MENU_ registros",
				"zeroRecords": "No se encontraron resultados",
				"info": "Mostrando  _START_ a _END_ de _TOTAL_ registros",
				"infoEmpty": "Mostrando 0 a 0 de 0 registros",
				"infoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sSearch": "Buscar:",
				"paginate": {
					"first": "Primero", "last": "Último",
					"next": "Siguiente", "previous": "Anterior"
				},
			},
			"destroy": true,
			"pageLength": 5, // Paginación
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		});
}



// Función para guardar o editar
function guardaryeditar(e) {
	e.preventDefault(); // No se activará la acción predeterminada del evento

	bootbox.confirm("¿Está Seguro de registrar la Venta?", function (result) {
		if (result) {
			var formData = new FormData($("#formulario")[0]);
			// Verificar si el checkbox está marcado
			var isNuevoCliente = $("#NuevoCliente").is(":checked");
			// Cambiar el parámetro 'op' según el estado del checkbox
			var op = isNuevoCliente ? "guardaryeditarNuevoCliente" : "guardaryeditar";

			$.ajax({
				url: "../ajax/venta.php?op=" + op, // Cambiar dinámicamente el 'op'
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				success: function (response) {
					var data = JSON.parse(response);
					if (data.redirect) {
						window.open(
							data.redirect,
							'VentanaPDF',
							'width=800,height=600,scrollbars=yes,resizable=yes'
						);
					} else if (data.error) {
						Toast.fire({ icon: 'error', title: data.error });
					}
					mostrarform(false);
					tabla.ajax.reload();
				}
			});

			limpiar();
		}
	});
}



function mostrar(Idventa) {
	$.post("../ajax/venta.php?op=mostrar", { Idventa: Idventa }, function (data, status) {
		data = JSON.parse(data);
		mostrarform(true);

		$("#Idcliente").val(data.Idcliente);
		$("#Idcliente").selectpicker('refresh');
		$("#Nombres").val(data.Nombres);
		$("#Num_documento").val(data.Num_documento);
		$("#Tipo_documento").val(data.Tipo_documento);
		$("#Tipo_documento").selectpicker('refresh');
		$("#Telefono").val(data.Telefono);

		if (data.Tipo_comprobante === "Recibo") {
			$("#Tipo_comprobante_recibo").prop("checked", true);
			$("#Tipo_comprobante_ticket").prop("checked", false);
		} else if (data.Tipo_comprobante === "Ticket") {
			$("#Tipo_comprobante_ticket").prop("checked", true);
			$("#Tipo_comprobante_recibo").prop("checked", false);
		}
		if (data.Tipo === "Efectivo") {
			$("#tipopago_efectivo").prop("checked", true);
			$("#tipopago_deposito").prop("checked", false);
		} else if (data.Tipo === "Deposito") {
			$("#tipopago_deposito").prop("checked", true);
			$("#tipopago_efectivo").prop("checked", false);
		}
		$("#Fecha").val(data.Fecha);
		$("#Hora").val(data.Hora);
		$("#Monto").val(data.Monto);
		$("#Cambio").val(data.Cambio);
		$("#totales3").html("TOTAL Bs/. " + data.Total);
		$("#Total").val(data.Total);
		$("#Num_comprobante").val(data.Num_comprobante);
		$("#Idventa").val(data.Idventa);

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
	});

	$.post("../ajax/venta.php?op=listarDetalle&id=" + Idventa, function (r) {
		$("#detalles").html(r);
	});
}

//Función para anular registros
function anular(Idventa) {
	bootbox.confirm("¿Está Seguro de anular la Venta?", function (result) {
		if (result) {
			$.post("../ajax/venta.php?op=anular", { Idventa: Idventa }, function (e) {
				Toast.fire({ icon: 'error', title: e, });
				tabla.ajax.reload();
			});
		}
	})
}

function calcularCambio() {
	var total = parseFloat(document.getElementById("Total").value) || 0; // Total a pagar
	var efectivo = parseFloat(document.getElementById("Monto").value) || 0; // Efectivo ingresado

	if (efectivo < total) {
		document.getElementById("Monto").setAttribute("min", total);
		document.getElementById("Cambio").value = 0;
	} else {
		// Calcular el cambio
		var cambio = efectivo - total;
		var saldoRedondeado = cambio.toFixed(2);
		document.getElementById("Cambio").value = saldoRedondeado;
	}
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var cont = 1;
var detalles = 0;

function agregarDetalle(Idarticulo, Nombre, Cantidad, Unidadmedida, Precio_venta, Precio_compra) {
	var Cantidad_venta = 1;
	var descuento = 0;


	if (Idarticulo != "") {
		if ($("input[name='Idarticulo[]']").filter(function () {
			return $(this).val() == Idarticulo;
		}).length > 0) {
			Toast.fire({
				icon: 'error',
				title: "Este articulo ya fue agregado.",
			});
			return;
		}
		var subtotal = Cantidad_venta * Precio_venta;
		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(' + cont + ')"><i class="feather icon-trash-2"></button></td>' +
			'<td><input type="hidden" name="Idarticulo[]" value="' + Idarticulo + '">' + Nombre + '</td>' +
			'<td>' + Unidadmedida + '</td>' +
			'<td><input type="number" name="Cantidad_venta[]" id="Cantidad_venta ' + cont + '" value="' + Cantidad_venta + '" min="1"  max="' + Cantidad + '"  oninput="modificarSubototales()"></td>' +
			'<td>' + Precio_venta + '<input type="hidden" name="Precio_venta[]" id="Precio_venta' + cont + '" value="' + Precio_venta + '">' +
			'<input type="hidden" name="Precio_compra[]" id="Precio_compra' + cont + '" value="' + Precio_compra + '"></td>' +
			'<td><input type="number" name="Descuento[]" value="' + descuento + '"  oninput="modificarSubototales()" min="0" style="width: 80px;"></td>' +
			'<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
			'</tr>';
		cont++;
		detalles++;
		$('#detalles').append(fila);
		modificarSubototales();
	} else {
		alert("Error al ingresar el detalle, revisar los datos del artículo");
	}
}

function modificarSubototales() {
	var cant = document.getElementsByName("Cantidad_venta[]");
	var cost = document.getElementsByName("Precio_venta[]");
	var desc = document.getElementsByName("Descuento[]");
	var sub = document.getElementsByName("subtotal");


	for (var i = 0; i < cant.length; i++) {
		var inpC = parseFloat(cant[i].value) || 0;  // Asegura que sea un número
		var inpP = parseFloat(cost[i].value) || 0;  // Asegura que sea un número
		var inpD = parseFloat(desc[i].value) || 0;  // Asegura que sea un número
		var inpS = sub[i];

		inpS.value = ((inpC * inpP) - inpD).toFixed(2);
		document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
	}
	calcularTotales();
}

function calcularTotales() {
	var sub = document.getElementsByName("subtotal");
	var total = 0.0;

	for (var i = 0; i < sub.length; i++) {
		total += parseFloat(document.getElementsByName("subtotal")[i].value);
	}

	total = total.toFixed(2); // Redondear el total a 2 decimales

	$("#totales3").html("TOTAL Bs/." + total);
	$("#Total").val(total);
	evaluar();
	calcularCambio();
}
function evaluar() {
	if (detalles > 0) {
		$("#btnGuardar").show();
	}
	else {
		$("#btnGuardar").hide();
		cont = 0;
	}
}

function eliminarDetalle(indice) {
	$("#fila" + indice).remove();
	calcularTotales();
	detalles = detalles - 1;
	evaluar();
	calcularCambio();

}

function abrirPDF(url) {
	window.open(url, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
}
init();