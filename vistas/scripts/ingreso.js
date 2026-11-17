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

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});
	//Cargamos los items al select proveedor
	$.post("../ajax/ingreso.php?op=selectProveedor", function (r) {
		$("#Idproveedor").html(r);
		$('#Idproveedor').selectpicker('refresh');
	});
}



// Función limpiar
function limpiar() {
	$("#Idingreso").val("");
	$("#Tipocomprobante").val("Boleta");
	$('#Tipocomprobante').selectpicker('refresh');
	$("#Idproveedor").val($("#Idproveedor option:first").val()).selectpicker('refresh');
	$("#Numero").val("");
	$("#Preciototal").val("");
	$(".filas").remove();
	$("#total").html("Bs/. 0.00");
	// Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + month + "-" + day;
	$("#Fecha").val(today);
	// Marcamos el primer tipo_documento
}


//Función mostrar formulario
function mostrarform(flag) {
	//limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#btnRedirigir").hide();
		listarArticulos();

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles = 0;
		$("#btnAgregarArt").show();
	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#btnRedirigir").show();

	}

}


//Función cancelarform
function cancelarform() {
	limpiar();
	mostrarform(false);
}

//Función Listar
//resivie//Función Listar 
function listar() {
	tabla = $('#tbllistado').DataTable(
		{
			"responsive": true,
			"autoWidth": false,
			"lengthMenu": [5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
			"processing": true,//Activamos el procesamiento del datatables
			"ajax":
			{
				url: '../ajax/ingreso.php?op=listar',
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
			"bDestroy": true,
			"iDisplayLength": 5,//Paginación
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		});

}

//Función ListarArticulos
function listarArticulos() {
	var Idsucursal = $("#Idsucursal").val();
	tabla1 = $('#tblarticulos').DataTable(
		{
			"responsive": true,
			"autoWidth": false,
			"lengthMenu": [5, 10, 25, 50, 75],//mostramos el menú de registros a revisar
			"processing": true,//Activamos el procesamiento del datatables
			"ajax":
			{
				url: '../ajax/ingreso.php?op=listarArticulos',
				data: { Idsucursal: Idsucursal },
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
//Función para guardar o editar

function guardaryeditar(e) {
	e.preventDefault(); // No se activará la acción predeterminada del evento

	bootbox.confirm("¿Está Seguro de guardar el ingreso?", function (result) {
		if (result) {
			var formData = new FormData($("#formulario")[0]);

			$.ajax({
				url: "../ajax/ingreso.php?op=guardaryeditar",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,

				success: function (datos) {
					Toast.fire({ icon: 'success', title: datos, });
					mostrarform(false);
					tabla.ajax.reload();
					limpiar();
				}
			});

		}
	});
}

function mostrar(Idingreso) {
	$.post("../ajax/ingreso.php?op=mostrar", { Idingreso: Idingreso }, function (data, status) {
		data = JSON.parse(data);
		mostrarform(true);


		$("#Fecha").val(data.Fecha);
		$("#Idproveedor").val(data.Idproveedor);
		$('#Idproveedor').selectpicker('refresh');
		$("#Numero").val(data.Numero);
		$("#Tipocomprobante").val(data.Tipocomprobante),
			$('#Tipocomprobante').selectpicker('refresh');
		$("#Idingreso").val(data.Idingreso);

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
	});

	$.post("../ajax/ingreso.php?op=listarDetalle&id=" + Idingreso, function (r) {
		$("#detalles").html(r);
	});
}

//Función para anular registros
function anular(Idingreso) {
	bootbox.confirm("¿Está Seguro de anular el Ingreso ?", function (result) {
		if (result) {
			$.post("../ajax/ingreso.php?op=anular", { Idingreso: Idingreso }, function (e) {
				Toast.fire({ icon: 'error', title: e, });
				tabla.ajax.reload();
			});
		}
	})
}


var cont = 1;
var detalles = 0;
function agregarDetalle(Idarticulo, Nombre, Precio_venta) {
	var Cantidad = 1;
	var Precio_compra = 1;

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
		var subtotal = Cantidad * Precio_compra;
		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarDetalle(' + cont + ')"><i class="feather icon-trash-2"></button></td>' +
			'<td><input type="hidden" name="Idarticulo[]" value="' + Idarticulo + '">' + Nombre + '</td>' +
			'<td><input type="number"class="form-control text-center" name="Cantidad_ingreso[]" id="Cantidad_ingreso' + cont + '" value="' + Cantidad + '" min="1"  oninput="modificarSubototales(' + cont + ')" ></td>' +
			'<td><input type="number"class="form-control text-center" name="Precio_compra[]" id="Precio_compra' + cont + '" value="' + Precio_compra + '"  min="1" max="' + Precio_venta + '" step="0.01" oninput="modificarSubototales(' + cont + ')"></td>' +
			'<td><input type="number"class="form-control text-center" name="Precio_venta[]" value="' + Precio_venta + '" min="1" step="0.01"></td>' +
			'<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
			'</tr>';
		cont++;
		detalles = detalles + 1;
		$('#detalles').append(fila);
		modificarSubototales();

	}
	else {
		alert("Error al ingresar el detalle, revisar los datos del producto");
	}

}

function modificarSubototales() {
	var cant = document.getElementsByName("Cantidad_ingreso[]");
	var prec = document.getElementsByName("Precio_compra[]");
	var sub = document.getElementsByName("subtotal");

	for (var i = 0; i < cant.length; i++) {
		var inpC = cant[i];
		var inpP = prec[i];
		var inpS = sub[i];

		inpS.value = (inpC.value * inpP.value).toFixed(2); // Redondear el subtotal a 2 decimales
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

	$("#total").html("Bs/. " + total);
	$("#Preciototal").val(total);
	evaluar();
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
function abrirPDF(url) {
	window.open(url, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
}
init();