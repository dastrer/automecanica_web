<?php 

//incluye solo una vez el modelo llamado Categoria.php
require_once "../modelos/Mensaje.php";

//Idsolicitud Fecha Nombre Telefono Email Mensaje Idsucursal Estado
//creamos un objeto llamado categoria
$mensaje=new Mensaje();
$Idmensaje=isset($_POST["Idmensaje"])? limpiarCadena($_POST["Idmensaje"]):"";
$Fecha=isset($_POST["Fecha"])? limpiarCadena($_POST["Fecha"]):"";
$Nombre=isset($_POST["Nombre"])? limpiarCadena($_POST["Nombre"]):"";
$Telefono=isset($_POST["Telefono"])? limpiarCadena($_POST["Telefono"]):"";
$Email=isset($_POST["Email"])? limpiarCadena($_POST["Email"]):"";
$Detalle=isset($_POST["Detalle"])? limpiarCadena($_POST["Detalle"]):"";

//segun la paeticion (operacion) que realize mediante el metodo GEST
//devolvera un valor mediante la tecnologia AJAX, para eso utilizaremos SWITCH
switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($Idmensaje)) {
            $rspta = $mensaje->insertar($Fecha,$Nombre, $Telefono, $Email, $Detalle);
            echo $rspta ? "Mensaje enviado " : "Error al enviar el mensaje";
            if ($rspta) {
                exit();
            }
        } else {
            echo "Error Fatal";
        }
        break;
}



?>