const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
// Inicialización
function init() {
    $("#actualizar").on("submit", function(e) {
        guardaryeditar(e);
    });

    // Validación de contraseñas en tiempo real
    $("#confirmarClave").on("input", function() {
        validarClaves();
    });

    $("#nuevaClave").on("input", function() {
        validarClaves();
    });
}

// Función para limpiar los campos del formulario
function limpiar() {
    $("#nuevaClave").val("");
    $("#confirmarClave").val("");
}

// Validación de contraseñas
function validarClaves() {
    var nuevaClave = $("#nuevaClave").val();
    var confirmarClave = $("#confirmarClave").val();

    if (nuevaClave !== confirmarClave) {
        $("#confirmarClave").addClass("is-invalid");
        $("#submitBtn").attr("disabled", "disabled");
        
    } else {
        $("#confirmarClave").removeClass("is-invalid");
        $("#submitBtn").removeAttr("disabled");
    }
}

// Función para guardar y editar la contraseña
function guardaryeditar(e) {
    e.preventDefault(); // Prevenir comportamiento por defecto del formulario

    bootbox.confirm("¿Está seguro de actualizar la clave?", function(result) {
        if (result) {
            var formData = new FormData($("#actualizar")[0]);

            $.ajax({
                url: "../ajax/recover-password.php?op=guardaryeditar",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos) {
                    Toast.fire({
                        icon: 'success',
                        title: datos
                    });
                    setTimeout(function() {
                        window.location.href = "../vistas/login.html";
                    }, 2000); //
                    limpiar();
                }
            });
        }
    });
}


function validarClaveRecup(input, minLength, maxLength) {
    var val = input.value;
    var inputLength = val.length;

    if (inputLength < minLength) {
        input.setCustomValidity(`La contraseña debe tener al menos ${minLength} caracteres.`);
        return;
    }
    if (inputLength > maxLength) {
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
    if (!/[!@#$%^&*(),.?":{}|<>]/.test(val)) {
        input.setCustomValidity("La contraseña debe contener al menos un carácter especial.");
        return;
    }

    input.setCustomValidity(""); // Si pasa todas las validaciones, no hay error
}


    init();
