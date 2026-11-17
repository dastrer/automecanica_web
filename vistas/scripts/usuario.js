var tabla;
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
	})

	$("#Imagenmuestra").hide();
	//Mostramos los permisos
	$.post("../ajax/usuario.php?op=permisos&id=", function (r) {
		$("#permisos").html(r);
	});
	bsCustomFileInput.init();
}

  function togglePassword() {
    var $input = $("#Clave");
    var $icon = $("#iconoClave");

    if ($input.attr("type") === "password") {
      $input.attr("type", "text");
      $icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
      $input.attr("type", "password");
      $icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
  }


function limpiar() {
	$("#Nombres").val("");
	$("#Num_documento").val("");
	$("#Tipo_documento").val("CEDULA");
	$("#Tipo_documento").selectpicker('refresh');
	$("#Cargo").val("ADMINISTRADOR");
	$("#Cargo").selectpicker('refresh');
	$("#Direccion").val("");
	$("#Email").val("");
	$("#Telefono").val("");
	$("#Idsucursal").val($("#Idsucursal option:first").val()).selectpicker('refresh');

	// Restablecer la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var year = now.getFullYear();
	var hours = ("0" + now.getHours()).slice(-2);
	var minutes = ("0" + now.getMinutes()).slice(-2);
	var seconds = ("0" + now.getSeconds()).slice(-2);
	var fechaHora = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
	$("#Fecha_creacion").val(fechaHora);
	$("#Imagen").val(''); 
	$('.custom-file-label[for="Imagen"]').text('Seleccione Archivo');
	$("#Imagenmuestra").attr("src", "");
	$("#Imagenactual").val("");
	$("#Imagenmuestra").hide();
	$("#Login").val("");
	$("#Clave").val("");
	$("#Clave").attr("required", "required");
	$("#Clave")[0].setCustomValidity("");
	$("#Idusuario").val("");
}



//Función mostrar formulario
function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled", false);
		$("#btnagregar").hide();

	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();

	}
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
				url: '../ajax/usuario.php?op=listar',
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
				},
			},
			"bDestroy": true,
			"iDisplayLength": 5,//Paginación
			"order": [[0, "asc"]]//Ordenar (columna,orden)
		});
}

//Función para guardar o editar
function guardaryeditar(e) {
	e.preventDefault(); // No se activará la acción predeterminada del evento
	bootbox.confirm("¿Está Seguro de guardar el Usuario?", function (result) {
		if (result) {
			var formData = new FormData($("#formulario")[0]);

			$.ajax({
				url: "../ajax/usuario.php?op=guardaryeditar",
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

function mostrar(Idusuario) {
	$.post("../ajax/usuario.php?op=mostrar", { Idusuario: Idusuario }, function (data, status) {
		data = JSON.parse(data);
		mostrarform(true);
		$("#Nombres").val(data.Nombres);
		$("#Num_documento").val(data.Num_documento);
		$("#Tipo_documento").val(data.Tipo_documento);
		$("#Tipo_documento").selectpicker('refresh');
		$("#Direccion").val(data.Direccion);
		$("#Telefono").val(data.Telefono);
		$("#Email").val(data.Email);
		$("#Cargo").val(data.Cargo);
		$("#Cargo").selectpicker('refresh');
		$("#Fecha_creacion").val(data.Fecha_creacion);
		$("#Idsucursal").val(data.Idsucursal);
		$("#Idsucursal").selectpicker('refresh');
		$("#Login").val(data.Login);
		$("#Clave").val("");
		$("#Clave").removeAttr("required");
		$("#Clave")[0].setCustomValidity(""); 
		$("#Imagenmuestra").show();
		$("#Imagenmuestra").attr("src", "../files/usuarios/" + data.Imagen);
		$("#Imagenactual").val(data.Imagen);
		$("#Idusuario").val(data.Idusuario);



	});
	$.post("../ajax/usuario.php?op=permisos&id=" + Idusuario, function (r) {
		$("#permisos").html(r);
	});
}
//Función para desactivar registros
function desactivar(Idusuario) {
	bootbox.confirm("¿Está Seguro de desactivar el usuario?", function (result) {
		if (result) {
			$.post("../ajax/usuario.php?op=desactivar", { Idusuario: Idusuario }, function (e) {
				Toast.fire({ icon: 'error', title: e, });
				tabla.ajax.reload();
			});
		}
	})
}
//Función para activar registros
function activar(Idusuario) {
	bootbox.confirm("¿Está Seguro de activar el Usuario?", function (result) {
		if (result) {
			$.post("../ajax/usuario.php?op=activar", { Idusuario: Idusuario }, function (e) {
				Toast.fire({ icon: 'success', title: e, });
				tabla.ajax.reload();
			});
		}
	})
}

function validarClave(input, minLength, maxLength) {
    var val = input.value;

    // Solo valida si el campo es requerido o si el usuario escribe algo
    if (val === "" && !input.required) {
        input.setCustomValidity("");
        return;
    }

    if (val.length < minLength) {
        input.setCustomValidity(`La contraseña debe tener al menos ${minLength} caracteres.`);
        return;
    }
    if (val.length > maxLength) {
        input.setCustomValidity(`La contraseña no puede tener más de ${maxLength} caracteres.`);
        return;
    }
    if (!/[A-Z]/.test(val)) {
        input.setCustomValidity("La contraseña debe contener al menos una letra mayúscula.");
        return;
    }
    if (!/[a-z]/.test(val)) {
        input.setCustomValidity("La contraseña debe contener al menos una letra minúscula.");
        return;
    }
    if (!/[0-9]/.test(val)) {
        input.setCustomValidity("La contraseña debe contener al menos un número.");
        return;
    }
    if (!/[!@#$%^&*(),.?\":{}|<>]/.test(val)) {
        input.setCustomValidity("La contraseña debe contener al menos un carácter especial.");
        return;
    }

    input.setCustomValidity(""); // Todo bien
}



init();
