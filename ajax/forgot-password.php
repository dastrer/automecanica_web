<?php
session_start(); 
require_once "../modelos/Usuario.php";

$usuario=new Usuario();

 

$Email=isset($_POST["Email"])? limpiarCadena($_POST["Email"]):"";
$Numero=isset($_POST["Numero"])? limpiarCadena($_POST["Numero"]):"";


switch ($_GET["op"]){


	case 'verificar':
		$Numero=$_POST['Numero'];
        $Email=$_POST['Email'];

		$rspta=$usuario->recordar($Email,$Numero);

		$fetch=$rspta->fetch_object();
 
		if (isset($fetch))
	    {
	        //Declaramos las variables de sesión

	        $_SESSION['Idusuario']=$fetch->Idusuario;
			$_SESSION['nombre']=$fetch->nombre;


	    }
	    echo json_encode($fetch);
	break;

	
}
?>