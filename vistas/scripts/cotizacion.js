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
	cargarDiagnostico();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	$.post("../ajax/cotizacion.php?op=selectVehiculo", function (r) {
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
	$("#Idcotizacion").val("");

	$.post("../ajax/cotizacion.php?op=selectVehiculo", function (r) {
		$("#Idvehiculo").html(r);
		$('#Idvehiculo').selectpicker('refresh');
		var Idvehiculo = $('#Idvehiculo').val();
		if (Idvehiculo) {
			mostrardatos(Idvehiculo);
		}
	});
	// Limpiar diagnóstico
	$("#detalles_diagnostico input[type='checkbox']").prop('checked', false);
	$("#detalles_diagnostico input[name='Observaciones[]']").val("").attr("placeholder", "Opcional");
	$("#Monto_servicio").val("0");

	// Limpiar venta
	$("#Monto_venta").val("0");
	$("#Descripcion").val("");
	$("#Numero").val("");
	$("#TotalCD").html("Bs/. 0.00");
	$("#TotalC").val("");
	$("#Preciototal").val("");
	$("#totalCV").html("Bs/. 0.00");
	$(".filas").remove();
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

}


function mostrardatos(Idvehiculo) {
	$.post("../ajax/cotizacion.php?op=mostrarVehiculo", { Idvehiculo: Idvehiculo },
		function (data, status) {
			data = JSON.parse(data);
			$("#Nombres").val(data.Nombres);
			$("#Marca").val(data.Marca);

		})
}

//Función mostrar formulario
function mostrarform(flag) {
	//limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		listarArticulos();

		$("#btnGuardar").show();
		$("#btnCancelar").show();
		detalles = 0;
		$("#btnAgregarArt").hide();
	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
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
	tabla = $('#tbllistado').DataTable(
		{
			"responsive": true,
			"autoWidth": false,
			"lengthMenu": [5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
			"processing": true,//Activamos el procesamiento del datatables
			"ajax":
			{
				url: '../ajax/cotizacion.php?op=listar',
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


// Función listar diagnóstico
function cargarDiagnostico() {
	$.ajax({
		url: "../ajax/cotizacion.php?op=listarDiagnostico",
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
						'<input type="checkbox" class="check-b"  id="checkB_' + index + '" name="Estado[' + index + ']" value="B" onchange="soloUno(' + index + ', \'B\')">' +
						'<label for="checkB_' + index + '"></label>' +
						'</div>' +
					'</td>' +
					'<td class="text-center">' +
						'<div class="icheck-primary d-inline">' +
						'<input type="checkbox" class="check-m" id="checkM_' + index + '" name="Estado[' + index + ']" value="M" onchange="soloUno(' + index + ', \'M\'); calcularTotal();">' +
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
				url: '../ajax/cotizacion.php?op=listarArticulos',
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
	bootbox.confirm("¿Está Seguro de registrar la Cotizacion?", function (result) {
		if (result) {
			var formData = new FormData($("#formulario")[0]);
			// Verificar si el checkbox está marcado
			var isAgregar_venta = $("#Agregar_venta").is(":checked");
			// Cambiar el parámetro 'op' según el estado del checkbox
			var op = isAgregar_venta ? "guardaryeditar2" : "guardaryeditar";

			$.ajax({
				url: "../ajax/cotizacion.php?op=" + op,
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




function mostrar(Idcotizacion) {
	$.post("../ajax/cotizacion.php?op=mostrar", { Idcotizacion: Idcotizacion }, function (data, status) {
		data = JSON.parse(data);
		mostrarform(true);

		// Mostrar todos los campos 
		$("#Idvehiculo").val(data.Idvehiculo);
		$("#Idvehiculo").selectpicker('refresh');
		mostrardatos(data.Idvehiculo);
		$("#Fecha").val(data.Fecha);
		$("#Descripcion").val(data.Descripcion);
		$("#Numero").val(data.Num_comprobante);
		$("#Idcotizacion").val(data.Idcotizacion);
		$("#totales4").html("TOTAL Bs/. " + data.Total);
		$("#Total").val(data.Total);


		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();

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
						'<input type="checkbox" class="check-b"  id="checkB_' + index + '" name="Estado[' + index + ']" value="B" onchange="soloUno(' + index + ', \'B\')" ' + checkB + '>' +
						'<label for="checkB_' + index + '"></label>' +
						'</div>' +
					'</td>' +
					'<td class="text-center">' +
						'<div class="icheck-primary d-inline">' +
						'<input type="checkbox" class="check-m" id="checkM_' + index + '"  name="Estado[' + index + ']" value="M" onchange="soloUno(' + index + ', \'M\'); calcularTotal();" ' + checkM + '>' +
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

	$.post("../ajax/cotizacion.php?op=listarDetalleVenta&id=" + Idcotizacion, function (r) {
		var contenedor = $('<div>').html(r);
		var filas = contenedor.find('.filas'); // Buscar filas de artículos
		if (filas.length > 0) {
			// Sí hay artículos de venta
			$('#Agregar_venta').prop('checked', true);
			$('#detalles').removeClass('d-none').html(r);
			// EXTRAER TOTAL desde el nuevo HTML inyectado
			var texto = contenedor.find("#totalCV").text(); // Obtener texto del total
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
function anular(Idcotizacion) {
	bootbox.confirm("¿Está Seguro de anular la Cotizacion?", function (result) {
		if (result) {
			$.post("../ajax/cotizacion.php?op=anular", { Idcotizacion: Idcotizacion }, function (e) {
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
	var Cantidad_cotizacion = 1;
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
		var subtotal = Cantidad_cotizacion * Precio_venta;
		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(' + cont + ')"><i class="feather icon-trash-2"></button></td>' +
			'<td><input type="hidden" name="Idarticulo[]" value="' + Idarticulo + '">' + Nombre + '</td>' +
			'<td>' + Cantidad + '</td>' +
			'<td>' + Unidadmedida + '</td>' +
			'<td><input type="number" name="Cantidad_cotizacion[]" id="Cantidad_cotizacion ' + cont + '" value="' + Cantidad_cotizacion + '" min="1"  max="' + Cantidad + '"  oninput="modificarSubototales()"></td>' +
			'<td>' + Precio_venta + '<input type="hidden" name="Precio_cotizacion[]" id="Precio_cotizacion' + cont + '" value="' + Precio_venta + '">' +
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
	var cant = document.getElementsByName("Cantidad_cotizacion[]");
	var cost = document.getElementsByName("Precio_cotizacion[]");
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
}


function actualizarTotal() {
	var servicio = parseFloat($('#Monto_servicio').val()) || 0;
	var venta = parseFloat($('#Monto_venta').val()) || 0;

	var suma = servicio + venta;

	$('#totales4').html("TOTAL Bs/. " + suma.toFixed(2));
	$('#Total').val(suma.toFixed(2));
}

function abrirPDF(url) {
	window.open(url, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
}
init();