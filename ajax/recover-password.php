<?php 
ob_start();
if (strlen(session_id()) < 1){
    session_start(); // Validamos si existe o no la sesión
}

if (!isset($_SESSION["nombre"])) {
    header("Location: ../vistas/login.html"); // Validamos el acceso solo a los usuarios logueados al sistema.
    exit();
}

require_once "../modelos/Usuario.php";

$usuario = new Usuario();
$Idusuario = $_SESSION["Idusuario"];
$clave = isset($_POST["nuevaClave"]) ? limpiarCadena($_POST["nuevaClave"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!empty($Idusuario)) {
            $clavehash = hash("SHA256", $clave);
            $rspta = $usuario->editarClave($clavehash, $Idusuario);

            // Mostrar el resultado de la actualización
            echo $rspta ? "Clave actualizada" : "Clave no se pudo actualizar"; 
            
            if ($rspta) {
                session_unset();
                session_destroy();
                exit();
            }
        } else {
            echo "Usuario no identificado";
        }
        break;
}



// Fin de las validaciones de acceso
ob_end_flush();
?>
