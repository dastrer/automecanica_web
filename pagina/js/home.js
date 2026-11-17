const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
function init() {
    $("#formularioWeb").on("submit", function (e) {
        guardaryeditar(e);
    })
    $('#Inicio').addClass("active");
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

function guardaryeditar(e) {
	e.preventDefault();
	Swal.fire({
		title: '¿Está seguro?',
		text: 'Está a punto de enviar el mensaje?.',
		icon: 'question',
		showCancelButton: true,
		confirmButtonText: 'Sí, guardar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.value) {
			var formData = new FormData($("#formularioWeb")[0]);
			$.ajax({
				url: "../ajax/contacto.php?op=guardaryeditar",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				success: function (datos) {
					Toast.fire({ icon: 'success', title: datos });
					limpiar();
				}
			});
		}
	});
}
function limpiar() {
	$("#Idmensaje").val("");
	$("#Nombre").val("");
	$("#Telefono").val("");
	$("#Email").val("");
	$("#Detalle").val("");
	// Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + month + "-" + day;
	$("#Fecha").val(today);

}
init();
