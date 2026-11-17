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
	updateInfoBoxes();
	listar();
	$("#fecha_inicio").change(listar);
	$("#fecha_fin").change(listar);
	cargarDiagnostico();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	$("#formulario2").on("submit", function (e) {
		e.preventDefault();
		guardarPago(e);
	});

	$.post("../ajax/servicio.php?op=selectVehiculo", function (r) {
		$("#Idvehiculo").html(r);
		$('#Idvehiculo').selectpicker('refresh');

		var Idvehiculo = $('#Idvehiculo').val();
		if (Idvehiculo) {
			mostrardatos(Idvehiculo);
		}
	});

	// Detectamos el cambio en el select del vehiculo
	$('#Idvehiculo').on('change', function () {
		var Idvehiculo = $(this).val(); // Obtenemos el valor seleccionado
		if (Idvehiculo) {
			mostrardatos(Idvehiculo);
		}
	});

}

// Función limpiar
function limpiar() {
	$("#Idservicio").val("");

	$.post("../ajax/servicio.php?op=selectVehiculo", function (r) {
		$("#Idvehiculo").html(r);
		$('#Idvehiculo').selectpicker('refresh');

		var Idvehiculo = $('#Idvehiculo').val();
		if (Idvehiculo) {
			mostrardatos(Idvehiculo);
		}
	});
	// Restablecer el estado del switch y el select de cotización
	$('#customSwitch1').prop('checked', false); // Desmarcar el switch
	$('#Idcotizacion').prop('disabled', true)    // Deshabilitar el select
		.html('<option value="">Seleccione una cotización</option>') // Vaciar y poner opción por defecto
		.selectpicker('refresh');  // Refrescar selectpicker

	// Limpiar diagnóstico
	$("#detalles_diagnostico input[type='checkbox']").prop('checked', false);
	$("#detalles_diagnostico input[name='Observaciones[]']").val("").attr("placeholder", "Opcional");
	$("#Monto_servicio").val("0");
	// Limpiar venta
	$("#Monto_venta").val("0");
	$("#Descripcion").val("");
	$("#Numero").val("");
	$("#tipopago_efectivo").prop("checked", false);
	$("#tipopago_deposito").prop("checked", false);
	$("#TotalCD").html("Bs/. 0.00");
	$("#TotalC").val("");
	$("#Preciototal").val("");
	$("#totalCV").html("Bs/. 0.00");
	$(".filas").remove();
	$("#Monto").val("");
	$("#Cambio").val("");
	$("#Saldo").val("");
	$("#Acuenta").val("");
	$("#totales4").html("Bs/. 0.00");
	$("#Total").val("");

	// Restaurar estado inicial de sección de venta
	$('#Agregar_venta').prop('checked', false); // desmarcar checkbox

	// Colocar fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var year = now.getFullYear();
	var hours = ("0" + now.getHours()).slice(-2);
	var minutes = ("0" + now.getMinutes()).slice(-2);
	var seconds = ("0" + now.getSeconds()).slice(-2);
	var fechaHora = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
	$('#Fecha').val(fechaHora);
	// Colocar fecha y hora actual en formato compatible con datetime-local
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var year = now.getFullYear();
	var hours = ("0" + now.getHours()).slice(-2);
	var minutes = ("0" + now.getMinutes()).slice(-2);
	// Formato: 2025-07-29T10:30
	var fechaHora = year + "-" + month + "-" + day + "T" + hours + ":" + minutes;
	$("#Fecha_entrega").val(fechaHora);

}


function mostrardatos(Idvehiculo) {
	$.post("../ajax/servicio.php?op=mostrarVehiculo", { Idvehiculo: Idvehiculo },
		function (data, status) {
			data = JSON.parse(data);
			$("#Nombres").val(data.Nombres);
			$("#Marca").val(data.Marca);

		})
}

function updateInfoBoxes() {
	$.ajax({
		url: '../ajax/servicio.php?op=listarconsulta',
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			$('#repMes').text(data.repMes);
			$('#totalMes').text(data.totalMes);
		},
		error: function (xhr, status, error) {
			console.error('Error fetching data:', error);
		}
	});
}
// Actualizar cada 2 segundos (2000 milisegundos)
setInterval(updateInfoBoxes, 1000);

//Función mostrar formulario
function mostrarform(flag) {
	//limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#formulariomodal").hide();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		listarArticulos();

		$("#btnGuardar").show();
		$("#btnCancelar").show();
		detalles = 0;
		$("#btnAgregarArt").hide();
		$("#BtnCotizacion").show();

	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#formulariomodal").show();
		$("#btnagregar").show();
		Agregar_vent();

	}

}

function Agregar_vent() {
	var mostrar = $('#Agregar_venta').is(':checked');
	$('#btnAgregarArt').toggle(mostrar);
	$('#detalles').toggleClass('d-none', !mostrar);
	$("#btnGuardar").hide();
	if (!mostrar) {
		$(".filas").remove();
		detalles = 0;
		calcularTotales();
		evaluar();
		$("#btnGuardar").show();
	}
}


//Función cancelarform
function cancelarform() {
	limpiar();
	mostrarform(false);
}

//Función Listar 
function listar() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	tabla = $('#tbllistado').DataTable(
		{
			"responsive": true,
			"autoWidth": false,
			"lengthMenu": [5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
			"processing": true,//Activamos el procesamiento del datatables
			"ajax":
			{
				url: '../ajax/servicio.php?op=listar',
				data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
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
			"order": [[1, "desc"]]//Ordenar (columna,orden)
		});
}

function calcularCambio() {
	var total = parseFloat($("#Total").val()) || 0;
	var efectivo = parseFloat($("#Monto").val()) || 0;
	var acuenta = parseFloat($("#Acuenta").val()) || 0;
	if (acuenta > total) {
		$('#montoAdvertencia').show().text('El pago a cuenta no puede ser mayor al total.');
		$("#Acuenta").val(total.toFixed(2)); // Opcional: limitarlo al total
		$("#Saldo").val("0.00");
		return;
	}
	var referenciaPago = acuenta > 0 ? acuenta : total;

	if (efectivo < referenciaPago) {
		$("#Monto").attr("min", referenciaPago);
		$("#Cambio").val("0.00");
	    $('#montoAdvertencia').hide(); // ocultar alerta
	} else {
		var cambio = efectivo - referenciaPago;
		$("#Cambio").val(cambio.toFixed(2));
	}
	// Si acuenta es 0, no mostrar saldo (pago al contado)
	if (acuenta === 0) {
		$("#Saldo").val("0.00");
	} else {
		var saldo = total - acuenta;
		if (saldo < 0) saldo = 0;
		$("#Saldo").val(saldo.toFixed(2));
        $('#montoAdvertencia').hide(); // ocultar alerta
	}
}




// Función listar diagnóstico
function cargarDiagnostico() {
	$.ajax({
		url: "../ajax/servicio.php?op=listarDiagnostico",
		type: "GET",
		dataType: "json",
		success: function (response) {
			let datos = response.data;
			let tbody = $("#detalles_diagnostico tbody");
			tbody.empty();
			datos.forEach(function (item, index) {
				var esEditable = item.Nombre.toLowerCase() === "otros"; // compara ignorando mayúsculas
				var readonlyAttr = esEditable ? '' : 'readonly';

				var fila =
					'<tr id="fila_diag_' + index + '">' +
					'<td>' + (index + 1) +
					'<input type="hidden" name="Iditem_diagnostico[]" value="' + item.Iditem_diagnostico + '">' +
					'</td>' +
					'<td>' + item.Nombre + '</td>' +
					'<td class="text-center">' +
						'<div class="icheck-primary d-inline">' +
						'<input type="checkbox" class="check-b" id="checkB_' + index + '"   name="Estado[' + index + ']" value="B" onchange="soloUno(' + index + ', \'B\')">' +
						'<label for="checkB_' + index + '"></label>' +
						'</div>' +
					'</td>' +
					'<td class="text-center">' +
						'<div class="icheck-primary d-inline">' +
					    '<input type="checkbox" class="check-m"  id="checkM_' + index + '" name="Estado[' + index + ']" value="M" onchange="soloUno(' + index + ', \'M\'); calcularTotal();">' +
						'<label for="checkM_' + index + '"></label>' +
						'</div>' +
					'</td>' +
					'<td>' +
					'<input type="number" step="0.01" name="Precio[]" class="form-control precio_diagnostico" value="' + (item.Precio || 0) + '" ' + readonlyAttr + '>' +
					'</td>' +
					'<td>' +
					'<input type="text" name="Observaciones[]" class="form-control" placeholder="Opcional">' +
					'</td>' +
					'</tr>';

				tbody.append(fila);
			});


			calcularTotal(); // Calcula el total inicial
		},
		error: function (error) {
			console.log("Error al cargar diagnóstico:", error);
		}
	});
}

function soloUno(index, tipo) {
	if (tipo === 'B') {
		$("input[name='Estado[" + index + "]'][value='M']").prop('checked', false);
	} else if (tipo === 'M') {
		$("input[name='Estado[" + index + "]'][value='B']").prop('checked', false);
	}
	calcularTotal();
}


// Recalcular el total de precios solo si M está marcado
function calcularTotal() {
	let total = 0;

	$("#detalles_diagnostico tbody tr").each(function (index) {
		let checkM = $(this).find("input[name='Estado[" + index + "]'][value='M']");
		let precio = parseFloat($(this).find("input[name='Precio[]']").val()) || 0;

		if (checkM.prop("checked")) {
			total += precio;
		}
	});

	$("#TotalCD").text("Bs/. " + total.toFixed(2));
	$("#TotalC").val(total.toFixed(2));
	$("#Monto_servicio").val(total.toFixed(2));
	actualizarTotal();
}

// Validar que todas las filas tengan al menos un check B o M marcado
function validarDiagnosticoRequerido() {
	let valido = true;

	$("#detalles_diagnostico tbody tr").each(function (index) {
		let checkB = $(this).find("input[name='Estado[" + index + "]'][value='B']").prop('checked');
		let checkM = $(this).find("input[name='Estado[" + index + "]'][value='M']").prop('checked');

		if (!checkB && !checkM) {
			valido = false;
			$(this).addClass("table-danger");
		} else {
			$(this).removeClass("table-danger");
		}
	});

	if (!valido) {
		Toast.fire({ icon: 'error', title: "Debe marcar B o M en todos los ítems del diagnóstico." });
	}

	return valido;
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
				url: '../ajax/servicio.php?op=listarArticulos',
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



function guardaryeditar(e) {
	e.preventDefault(); // No se activará la acción predeterminada del evento
	// Validación de diagnóstico
	if (!validarDiagnosticoRequerido()) {
		// Si la validación falla, se cancela todo
		return;
	}
	bootbox.confirm("¿Está Seguro de registrar el Servicio?", function (result) {
		if (result) {
			var formData = new FormData($("#formulario")[0]);
			// Verificar si el checkbox está marcado
			var isAgregar_venta = $("#Agregar_venta").is(":checked");
			// Cambiar el parámetro 'op' según el estado del checkbox
			var op = isAgregar_venta ? "guardaryeditar2" : "guardaryeditar";

			$.ajax({
				url: "../ajax/servicio.php?op=" + op,
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






function mostrar(Idservicio) {
	$.post("../ajax/servicio.php?op=mostrar", { Idservicio: Idservicio }, function (data, status) {
		data = JSON.parse(data);
		mostrarform(true);

		// Mostrar todos los campos 
		$("#Idvehiculo").val(data.Idvehiculo);
		$("#Idvehiculo").selectpicker('refresh');
		mostrardatos(data.Idvehiculo);
		$("#Fecha").val(data.Fecha);
	    $("#Fecha_entrega").val(data.Fecha_entrega);
		$("#Descripcion").val(data.Descripcion);
		$("#Numero").val(data.Num_comprobante);
		$("#Idservicio").val(data.Idservicio);
		if (data.Metodo_pago === "Efectivo") {
			$("#tipopago_efectivo").prop("checked", true);
			$("#tipopago_deposito").prop("checked", false);
		} else if (data.Metodo_pago === "Deposito") {
			$("#tipopago_deposito").prop("checked", true);
			$("#tipopago_efectivo").prop("checked", false);
		}
		$("#Monto").val(data.Monto);
		$("#Cambio").val(data.Cambio);
		$("#Saldo").val(data.Saldo);
		$("#Acuenta").val(data.Acuenta);
		$("#totales4").html("TOTAL Bs/. " + data.Total);
		$("#Total").val(data.Total);

		$("#BtnCotizacion").hide();
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();

	});
	// Mostrar detalles del diagnóstico (si quieres también incluirlo)
	$.ajax({
		url: "../ajax/servicio.php?op=listarDiagnosticoServicio",
		type: "POST",
		data: { Idservicio: Idservicio },
		dataType: "json",
		success: function (response) {
			let tbody = $("#detalles_diagnostico tbody");
			tbody.empty();
			let total = 0;
			response.data.forEach(function (item, index) {
				var checkB = item.Estado === 'B' ? 'checked' : '';
				var checkM = item.Estado === 'M' ? 'checked' : '';
				var fila =
					'<tr id="fila_diag_' + index + '">' +
					'<td>' + (index + 1) +
					'<input type="hidden" name="Iditem_diagnostico[]" value="' + item.Iditem_diagnostico + '">' +
					'</td>' +
					'<td>' + item.Nombre + '</td>' +
					'<td class="text-center">' + 
						'<div class="icheck-primary d-inline">' +
						'<input type="checkbox" class="check-b"   id="checkB_' + index + '" name="Estado[' + index + ']" value="B" onchange="soloUno(' + index + ', \'B\')" ' + checkB + '>' +
						'<label for="checkB_' + index + '"></label>' +
						'</div>' +
					'</td>' +
					'<td class="text-center">' +
						'<div class="icheck-primary d-inline">' +
						'<input type="checkbox" class="check-m" id="checkM_' + index + '" name="Estado[' + index + ']" value="M" onchange="soloUno(' + index + ', \'M\'); calcularTotal();" ' + checkM + '>' +
						'<label for="checkM_' + index + '"></label>' +
						'</div>' +
					'</td>' +
					'<td>' +
					'<input type="number" step="0.01" name="Precio[]" class="form-control precio_diagnostico" value="' + item.Precio + '" readonly>' +
					'</td>' +
					'<td>' +
					'<input type="text" name="Observaciones[]" class="form-control" value="' + (item.Observaciones || '') + '">' +
					'</td>' +
					'</tr>';

				tbody.append(fila);
				if (item.Estado === 'M') {
					total += parseFloat(item.Precio) || 0;
				}
			});

			$("#TotalC").val(total.toFixed(2));
			$("#TotalCD").html("TOTAL Bs/. " + total);
			$("#Monto_servicio").val(total.toFixed(2));

		}
	});

	$.post("../ajax/servicio.php?op=listarDetalleVenta&id=" + Idservicio, function (r) {
		var contenedor = $('<div>').html(r);
		var filas = contenedor.find('.filas'); // Buscar filas de artículos
		if (filas.length > 0) {
			// Sí hay artículos de venta
			$('#Agregar_venta').prop('checked', true);
			$('#detalles').removeClass('d-none').html(r);
			// EXTRAER TOTAL desde el nuevo HTML inyectado
			var texto = contenedor.find("#totalCV").text();
			var valor = parseFloat(texto.replace("Bs/.", "").trim()) || 0;
			$("#Monto_venta").val(valor.toFixed(2));
		} else {
			// No hay venta
			$('#Agregar_venta').prop('checked', false);
			$('#detalles').addClass('d-none').empty();
			$("#Monto_venta").val("0");
		}
	});


}


//Función para anular registros
function anular(Idservicio) {
	bootbox.confirm("¿Está Seguro de anular la Servicio?", function (result) {
		if (result) {
			$.post("../ajax/servicio.php?op=anular", { Idservicio: Idservicio }, function (e) {
				Toast.fire({ icon: 'error', title: e, });
				tabla.ajax.reload();
			});
		}
	})
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
			'<td>' + Cantidad + '</td>' +
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

	$("#totalCV").html("Bs/. " + total);
	$("#Monto_venta").val(total);
	$("#Preciototal").val(total);
	evaluar();
	actualizarTotal();
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


function actualizarTotal() {
	var servicio = parseFloat($('#Monto_servicio').val()) || 0;
	var venta = parseFloat($('#Monto_venta').val()) || 0;

	var suma = servicio + venta;

	$('#totales4').html("TOTAL Bs/. " + suma.toFixed(2));
	$('#Total').val(suma.toFixed(2));
}

//PARA LA COTIZACIÓN
function toggleCotizacionSwitch() {
	var $cotizacionSelect = $('#Idcotizacion');
	var $switchCotizacion = $('#customSwitch1');

	if ($switchCotizacion.prop('checked')) {
		// Habilitar el select y cargar las cotizaciones
		$cotizacionSelect.prop('disabled', false);
		cargarCotizaciones();
	} else {
		// Deshabilitar el select y limpiar el contenido
		$cotizacionSelect.prop('disabled', true);
		$cotizacionSelect.html('<option value="">Seleccione una cotización</option>');
		$('.selectpicker').selectpicker('refresh');
		limpiar();
	}
}
// Asignar la función al evento 'change' del switch 
$('#customSwitch1').on('change', toggleCotizacionSwitch);


// Función para cargar las cotizaciones en el select
function cargarCotizaciones() {
	$.post("../ajax/servicio.php?op=selectCotizacion", function (r) {
		$('#Idcotizacion').html(r);
		$('.selectpicker').selectpicker('refresh');
	});
}
function mostrarCotizacion() {
	var Idcotizacion = $("#Idcotizacion").val();

	if (!Idcotizacion) {
		bootbox.alert("Por favor, selecciona una cotización.");
		return;
	}
	limpiarCotizacion();
	// Obtener datos generales de la cotización
	$.post("../ajax/servicio.php?op=mostrarCotizacion", { Idcotizacion: Idcotizacion }, function (data) {
		data = JSON.parse(data);

		// Llenar los campos del formulario con los datos obtenidos
		$("#Idvehiculo").val(data.Idvehiculo);
		$("#Idvehiculo").selectpicker('refresh');
		mostrardatos(data.Idvehiculo);
		$("#Descripcion").val(data.Descripcion);
		// Mostrar botones
		$("#btnCancelar").show();

	});

	// Mostrar detalles del diagnóstico (si quieres también incluirlo)
	$.ajax({
		url: "../ajax/cotizacion.php?op=listarDiagnosticoCotizacion",
		type: "POST",
		data: { Idcotizacion: Idcotizacion },
		dataType: "json",
		success: function (response) {
			let tbody = $("#detalles_diagnostico tbody");
			tbody.empty();
			let total = 0;
			response.data.forEach(function (item, index) {
				var checkB = item.Estado === 'B' ? 'checked' : '';
				var checkM = item.Estado === 'M' ? 'checked' : '';
				var fila =
					'<tr id="fila_diag_' + index + '">' +
					'<td>' + (index + 1) +
					'<input type="hidden" name="Iditem_diagnostico[]" value="' + item.Iditem_diagnostico + '">' +
					'</td>' +
					'<td>' + item.Nombre + '</td>' +
					'<td class="text-center">' + 
						'<div class="icheck-primary d-inline">' +
					    '<input type="checkbox" class="check-b" id="checkB_' + index + '" name="Estado[' + index + ']" value="B" onchange="soloUno(' + index + ', \'B\')" ' + checkB + '>' +
					    '<label for="checkB_' + index + '"></label>' +
						'</div>' +
					'</td>' +
					'<td class="text-center">' +
						'<div class="icheck-primary d-inline">' +
					    '<input type="checkbox" class="check-m"  id="checkM_' + index + '" name="Estado[' + index + ']" value="M" onchange="soloUno(' + index + ', \'M\'); calcularTotal();" ' + checkM + '>' +
						'<label for="checkM_' + index + '"></label>' +
						'</div>' +
					'</td>' +
					'<td>' +
					'<input type="number" step="0.01" name="Precio[]" class="form-control precio_diagnostico" value="' + item.Precio + '" readonly>' +
					'</td>' +
					'<td>' +
					'<input type="text" name="Observaciones[]" class="form-control" value="' + (item.Observaciones || '') + '">' +
					'</td>' +
					'</tr>';

				tbody.append(fila);
				if (item.Estado === 'M') {
					total += parseFloat(item.Precio) || 0;
				}
			});

			$("#TotalC").val(total.toFixed(2));
			$("#TotalCD").html("TOTAL Bs/. " + total);
			$("#Monto_servicio").val(total.toFixed(2));
			actualizarTotal();
		}
	});

	$.post("../ajax/servicio.php?op=listarDetalleCotizacion&id=" + Idcotizacion, function (r) {
		var contenedor = $('<div>').html(r); // Convertimos el HTML a un objeto jQuery
		var filas = contenedor.find('.filas'); // Buscar filas de artículos
		if (filas.length > 0) {
			// Sí hay artículos de venta
			$('#Agregar_venta').prop('checked', true);
			$('#detalles').removeClass('d-none').html(r); // Mostrar y llenar
			detalles = $(".filas").length;
			modificarSubototales();
			evaluar();
			// EXTRAER TOTAL desde el nuevo HTML inyectado
			var texto = contenedor.find("#totalCV").text(); // Obtener texto del total
			var valor = parseFloat(texto.replace("Bs/.", "").trim()) || 0;
			$("#Monto_venta").val(valor.toFixed(2));
		} else {
			// No hay venta
			$('#Agregar_venta').prop('checked', false);
			$("#Monto_venta").val("0");
			Agregar_vent();
		}
	});

}

function limpiarCotizacion() {
	// Limpia formulario principal
	$("#Idvehiculo").val('').selectpicker('refresh');
	$("#Descripcion").val('');
	$("#Monto_servicio").val("0");
	$("#Monto_venta").val("0");
	$("#TotalCD").html("Bs/. 0.00");
	$("#TotalC").val("");
	$("#totalCV").html("Bs/. 0.00");
	$("#Preciototal").val("");
	$(".filas").remove();
	// Limpia diagnóstico
	$("#detalles_diagnostico input[type='checkbox']").prop('checked', false);
	$("#detalles_diagnostico input[name='Observaciones[]']").val("").attr("placeholder", "Opcional");
}







//PARA EL MODAL DE PAGO SCRIPT APARTE
function modalPago(Idservicio2) {
	$.post("../ajax/pago.php?op=mostrarPago", { Idservicio2: Idservicio2 },
		function (data, status) {
			data = JSON.parse(data);
			// Establecer el valor del estado y manejar la lógica según el estado
			var Estado = data.Estado;
			// Actualizar los campos según el estado
			$("#Vehiculo").val(data.Placa + " - " + data.Tipo + " " + data.Marca);
			$("#Num_comprobante").val(data.Tipo_comprobante + " No: " + data.Num_comprobante);
			$("#DescripcionModal").val(data.Descripcion);
			$("#Estado_actual").html(data.Estado);
			$("#TotalServicio").val(data.Total);
			$("#Saldo_Actual").val(data.Saldo);
			$("#AnteriorAcuenta").val(data.Acuenta);
			$("#Fecha_entrega_modal").val(data.Fecha_entrega);
			$("#Idservicio2").val(data.Idservicio);
			// Establecer el valor en el h5
			// Aplicar la clase CSS según el estado
			if (Estado === 'Anulado') {
				$("#Estado_actual").removeClass().addClass("estado-anulado");
			} else if (Estado === 'Reparado') {
				$("#Estado_actual").removeClass().addClass("estado-realizado");
			} else if (Estado === 'En proceso') {
				$("#Estado_actual").removeClass().addClass("estado-proceso");
			} else {
				// Manejar cualquier otro estado aquí
			}

			$.post("../ajax/pago.php?op=listarDetallePago&id=" + Idservicio2, function (r) {
				$("#detallesM").html(r);
			});
			// Manejar la visibilidad de los botones según el estado y el saldo
			if (Estado === 'Anulado' || Estado === 'Reparado') {
				$("#btnGuardarModal").hide();
				$("#btnActualizarEstado").hide();
			} else if (Estado === 'En proceso' && parseFloat(data.Saldo) === 0) {
				$("#btnGuardarModal").hide();
				$("#btnActualizarEstado").show();
			} else {
				$("#btnGuardarModal").show();         // Se puede guardar pago
				$("#btnActualizarEstado").hide();     // No mostrar botón de actualización
			}

		}
	);
}

function cancelarModal() {
  $("#myModal3").modal("hide");

}
$('#myModal3').on('hidden.bs.modal', function () {
	limpiarModal();
});


function calcular() {
	var TotalServicio = parseFloat($("#TotalServicio").val()) || 0;
	var acuenta = parseFloat($("#AcuentaM").val()) || 0;
	var AnteriorSaldo = parseFloat($("#Saldo_Actual").val()) || 0;
	var AnteriorAcuenta = parseFloat($("#AnteriorAcuenta").val()) || 0;
	var saldoTotal;

	if (AnteriorAcuenta > 0) {
		if (acuenta > AnteriorSaldo) {
			Toast.fire({ icon: 'error', title: "El monto ingresado no puede ser mayor que el Saldo Pagado." });
			$("#AcuentaM").val(AnteriorSaldo);
			acuenta = AnteriorSaldo;
		}
		saldoTotal = AnteriorSaldo - acuenta;
	} else {
		if (acuenta > TotalServicio) {
			Toast.fire({ icon: 'error', title: "El monto ingresado no puede ser mayor al Total del Servicio." });
			$("#AcuentaM").val(TotalServicio);
			acuenta = TotalServicio;
		}
		saldoTotal = TotalServicio - acuenta;
	}

	var saldoRedondeado = saldoTotal.toFixed(2);
	$("#Nuevo_saldo").val(saldoRedondeado);

	calcularCambio2(); // Llamada directa a la función de cambio
}

function calcularCambio2() {
	var total = parseFloat($("#AcuentaM").val()) || 0;
	var efectivo = parseFloat($("#MontoM").val()) || 0;

	if (efectivo < total) {
		$("#MontoM").attr("min", total);
		$("#CambioM").val("0.00");
	} else {
		var cambio = efectivo - total;
		var redondeado = cambio.toFixed(2);
		$("#CambioM").val(redondeado);
	}
}

function actualizarEstadoReparado() {
	var id = $("#Idservicio2").val();
	Swal.fire({
		title: '¿Confirmar cambio de estado?',
		text: 'Se actualizará el estado a "Reparado".',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Sí, actualizar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.value) {
			$.post("../ajax/pago.php?op=actualizarEstadoReparado", { Idservicio2: id }, function (datos) {
				$("#myModal3").modal("hide");
				Toast.fire({ icon: 'success', title: datos });
				tabla.ajax.reload();
				limpiarModal();
			});
		}
	});
}

function limpiarModal() {
	$("#Vehiculo").val("");
	$("#Num_comprobante").val("");
	$("#DescripcionModal").val("");
	$("#Estado_actual").html("");
	$("#TotalServicio").val("");
	$("#Saldo_Actual").val("");
	$("#AnteriorAcuenta").val("");
	$("#AcuentaM").val("");
	$("#CambioM").val("");
	$("#MontoM").val("");
	$("#Nuevo_saldo").val("");
	$("#Idservicio2").val("");
	$("#tipopago_efectivo2").prop("checked", false);
	$("#tipopago_deposito2").prop("checked", false);
	$("#Idservicio2").val("");
	// Obtener la fecha actual
	// Colocar fecha y hora actual en formato compatible con datetime-local
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var year = now.getFullYear();
	var hours = ("0" + now.getHours()).slice(-2);
	var minutes = ("0" + now.getMinutes()).slice(-2);
	// Formato: 2025-07-29T10:30
	var fechaHora = year + "-" + month + "-" + day + "T" + hours + ":" + minutes;
	$("#Fecha_entrega_modal").val(fechaHora);
	$("#detallesM").html("");
}

function guardarPago(e) {
	e.preventDefault();
	Swal.fire({
		title: '¿Está seguro?',
		text: 'Está a punto de registrar un nuevo pago.',
		icon: 'question',
		showCancelButton: true,
		confirmButtonText: 'Sí, guardar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.value) {
			//	$("#btnGuardarModal").prop("disabled", true);
			var formData = new FormData($("#formulario2")[0]);

			$.ajax({
				url: "../ajax/pago.php?op=guardaryeditar",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				success: function (datos) {
					$("#myModal3").modal("hide");
					Toast.fire({ icon: 'success', title: datos });
					tabla.ajax.reload();
					limpiarModal();
				}
			});
		}
	});
}
function generarPDF() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();

	// Abre una nueva ventana con el PDF después de un breve retraso
	setTimeout(function () {
		window.open('../reportes/rptservicio.php?fecha_inicio=' + fecha_inicio + '&fecha_fin=' + fecha_fin, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
	}, 1);
}

function abrirPDF(url) {
	window.open(url, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
}
init();