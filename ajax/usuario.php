<?php
ob_start(); 
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
require_once "../modelos/Usuario.php"; 
$usuario=new Usuario();  
 
//Idpersona Tipopersona Nombres Num_documento Tipo_documento Telefono Estado
//Idusuario Login Clave Email Direccion Fecha_creacion Cargo Imagen Idpersona Idsucursal
$Idusuario=isset($_POST["Idusuario"])? limpiarCadena($_POST["Idusuario"]):"";
$Tipopersona=isset($_POST["Tipopersona"])? limpiarCadena($_POST["Tipopersona"]):"";
$Nombres=isset($_POST["Nombres"])? limpiarCadena($_POST["Nombres"]):"";
$Num_documento=isset($_POST["Num_documento"])? limpiarCadena($_POST["Num_documento"]):"";
$Tipo_documento=isset($_POST["Tipo_documento"])? limpiarCadena($_POST["Tipo_documento"]):"";
$Telefono=isset($_POST["Telefono"])? limpiarCadena($_POST["Telefono"]):"";
$Fecha_ingreso=isset($_POST["Fecha_ingreso"])? limpiarCadena($_POST["Fecha_ingreso"]):"";
$Login=isset($_POST["Login"])? limpiarCadena($_POST["Login"]):"";
$Clave=isset($_POST["Clave"])? limpiarCadena($_POST["Clave"]):"";
$Email=isset($_POST["Email"])? limpiarCadena($_POST["Email"]):"";
$Direccion=isset($_POST["Direccion"])? limpiarCadena($_POST["Direccion"]):"";
$Fecha_creacion=isset($_POST["Fecha_creacion"])? limpiarCadena($_POST["Fecha_creacion"]):"";
$Cargo=isset($_POST["Cargo"])? limpiarCadena($_POST["Cargo"]):"";
$Imagen=isset($_POST["Imagen"])? limpiarCadena($_POST["Imagen"]):""; 
 
switch ($_GET["op"]){
	case 'guardaryeditar':
		// Verifica si se ha subido una nueva imagen
		if (!file_exists($_FILES['Imagen']['tmp_name']) || !is_uploaded_file($_FILES['Imagen']['tmp_name']))
		{
			$Imagen=$_POST["Imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["Imagen"]["name"]);
			if ($_FILES['Imagen']['type'] == "image/jpg" || $_FILES['Imagen']['type'] == "image/jpeg" || $_FILES['Imagen']['type'] == "image/png")
			{
				$Imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["Imagen"]["tmp_name"], "../files/usuarios/" . $Imagen); 
			}
		}
		 //Hash SHA256 en la contraseña
					if (!empty($Clave)) {
						$Clavehash = hash("SHA256", $Clave);
					} else {
						// Mantener la contraseña actual
						$Clavehash = $usuario->obtenerClaveActual($Idusuario);
					}
						 
		if (empty($Idusuario)) {
			$rspta=$usuario->verificarDuplicidad($Num_documento);
			if($rspta->num_rows > 0) {
				echo "Esta Cedula ya fue Registrada";
			} 
			else {   
			$rspta = $usuario->insertar($Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono,$Direccion,
			$Login,$Clavehash,$Email,$Fecha_creacion,$Cargo,$Imagen,$_POST['permiso']);
			echo $rspta ? "Usuario registrado" : "No se pudieron registrar todos los datos del usuario";
		    } 
	}
		else {
			$rspta = $usuario->editar($Idusuario,$Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono,$Direccion,
			$Login,$Clavehash,$Email,$Fecha_creacion,$Cargo,$Imagen,$_POST['permiso']);
			echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar"; 
		}
		break;
	

		case 'desactivar':
			if (!isset($_SESSION["nombre"]))
			{
			  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
			}
			else
			{
				//Validamos el acceso solo al usuario logueado y autorizado.
				if ($_SESSION['Acceso']==1)
				{
					$rspta=$usuario->desactivar($Idusuario);
					 echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
				//Fin de las validaciones de acceso
				}
				else
				{
				  require '../vistas/noacceso.php';
				}
			}		
		break;
	

		case 'activar':
			if (!isset($_SESSION["nombre"]))
			{
			  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
			}
			else
			{
				//Validamos el acceso solo al usuario logueado y autorizado.
				if ($_SESSION['Acceso']==1)
				{
					$rspta=$usuario->activar($Idusuario);
					 echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
				//Fin de las validaciones de acceso
				}
				else
				{
				  require '../vistas/noacceso.php';
				}
			}		
		break;

	case 'mostrar':
		$rspta=$usuario->mostrar($Idusuario);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;


	case 'listar':
		if (!isset($_SESSION["nombre"]))
		{
		  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
		}
		else
		{
			//Validamos el acceso solo al usuario logueado y autorizado.
			if ($_SESSION['Acceso']==1)
			{
				$rspta=$usuario->listar();
				//Vamos a declarar un array
				$data= Array();

				while ($reg = $rspta->fetch_object()) {
					$data[] = array(
						"0" => ($reg->Estado) ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idusuario . ')">
				     <i class="feather icon-edit-2"></i>
				     </button>' .
							' <button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->Idusuario . ')">
					 <i class="feather icon-x"></i>
					 </button>' :
							'<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->Idusuario . ')"><i class="feather icon-edit-2"></i></button>' .
							' <button class="btn btn-primary btn-sm" onclick="activar(' . $reg->Idusuario . ')"><i class="feather icon-check"></i></button>',
						"1" => $reg->Nombres,
						"2" => $reg->Num_documento . " " . $reg->Tipo_documento,
						"3" => $reg->Email,
						"4" =>'<a class="btn btn-sm btn-success" href="https://wa.me/+591' . $reg->Telefono . '" target="_blank" role="button">' .
							'<i class="fab fa-whatsapp"></i> ' . $reg->Telefono .'</a>',
						"5" => "<img src='../files/usuarios/" . $reg->Imagen . "' height='50px' width='50px'loading='lazy' alt='Usuario' >",
						"6" => ($reg->Estado) ? '<span class="badge badge-success">Activado</span>' :
							'<span class="badge badge-danger">Desactivado</span>'
					);
				}
		 $results = array(
			 "sEcho"=>1, //Información para el datatables
			 "iTotalRecords"=>count($data), //enviamos el total registros al datatable
			 "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
			 "aaData"=>$data);
		 echo json_encode($results);
		}
		else
		{
		  require 'noacceso.php';
		}
	}
break;

	case 'permisos':
		//Obtenemos todos los permisos de la tabla permisos
		require_once "../modelos/Permiso.php";
		$permiso = new Permiso();
		$rspta = $permiso->listar();
 
		//Obtener los permisos asignados al usuario
		$id=$_GET['id'];
		$marcados = $usuario->listarmarcados($id);
		//Declaramos el array para almacenar todos los permisos marcados
		$valores=array();

		//Almacenar los permisos asignados al usuario en el array
		while ($per = $marcados->fetch_object())
			{
				array_push($valores, $per->Idpermiso);
			}

		//Mostramos la lista de permisos en la vista y si están o no marcados
		while ($reg = $rspta->fetch_object()) {
		$sw = in_array($reg->Idpermiso, $valores) ? 'checked' : '';
		echo '<li>
				<div class="icheck-primary d-inline-block" style="margin-top: 10px;">
					<input type="checkbox" ' . $sw . ' name="permiso[]" id="permiso' . $reg->Idpermiso . '" value="' . $reg->Idpermiso . '">
					<label for="permiso' . $reg->Idpermiso . '">' . $reg->Nombre . '</label>
				</div>
			</li>';
		}

	break;
	
	case 'verificar':
		$logina = limpiarCadena($_POST['logina']);
        $clavea = limpiarCadena($_POST['clavea']);

	    //Hash SHA256 en la contraseña
		$clavehash= hash("SHA256", $clavea);//

		$rspta=$usuario->verificar($logina, $clavehash);

		$fetch=$rspta->fetch_object();

		if (isset($fetch))
	    {
	        //Declaramos las variables de sesión

	        $_SESSION['Idusuario']=$fetch->Idusuario;
	        $_SESSION['nombre']=$fetch->nombre;
	        $_SESSION['imagen']=$fetch->imagen;
	        $_SESSION['login']=$fetch->Login;
	        //Obtenemos los permisos del usuario
	    	$marcados = $usuario->listarmarcados($fetch->Idusuario);

	    	//Declaramos el array para almacenar todos los permisos marcados
			$valores=array();
 
			//Almacenamos los permisos marcados en el array
			while ($per = $marcados->fetch_object())
				{
					array_push($valores, $per->Idpermiso);
				}

		//Determinamos los accesos del usuario
		in_array(1,$valores)?$_SESSION['Escritorio']=1:$_SESSION['Escritorio']=0;
		in_array(2,$valores)?$_SESSION['Almacen']=1:$_SESSION['Almacen']=0;
		in_array(3,$valores)?$_SESSION['Compra']=1:$_SESSION['Compra']=0;
		in_array(4,$valores)?$_SESSION['Vehículos']=1:$_SESSION['Vehículos']=0;
		in_array(5,$valores)?$_SESSION['Servicio']=1:$_SESSION['Servicio']=0;
		in_array(6,$valores)?$_SESSION['Venta']=1:$_SESSION['Venta']=0;
		in_array(7,$valores)?$_SESSION['Acceso']=1:$_SESSION['Acceso']=0;	
		in_array(8,$valores)?$_SESSION['ConsultaS']=1:$_SESSION['ConsultaS']=0;	
		in_array(9,$valores)?$_SESSION['ConsultaC']=1:$_SESSION['ConsultaC']=0;		
		in_array(10,$valores)?$_SESSION['ConsultaV']=1:$_SESSION['ConsultaV']=0;	

	    }
	    echo json_encode($fetch);
	break;



	case 'salir':
		//Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../index.php");

	break;

	}
ob_end_flush();
?>