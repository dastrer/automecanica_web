<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Usuario
{
	//Implementamos nuestro constructor 
	public function __construct()
	{
 
	}   
 	//Implementamos un método para insertar registros
	 public function insertar($Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono,$Direccion,
	 $Login,$Clavehash,$Email,$Fecha_creacion,$Cargo,$Imagen,$permisos)
 {
	 // Consulta de inserción en la tabla persona
	 $sql = "INSERT INTO persona(Tipopersona,Nombres,Num_documento,Tipo_documento,Telefono,Direccion,Estado)
			 VALUES ('$Tipopersona','$Nombres','$Num_documento','$Tipo_documento','$Telefono','$Direccion','1')";
	 $Idpersonanew = ejecutarConsulta_retornarID($sql);
 
	 if ($Idpersonanew) {
		 // Inserción en la tabla usuario
		 $sql2 = "INSERT INTO usuario(Login,Clave,Email,Fecha_creacion,Cargo,Imagen,Idpersona)
				  VALUES ('$Login','$Clavehash','$Email','$Fecha_creacion','$Cargo','$Imagen',$Idpersonanew)";
		 $Idusuarionew = ejecutarConsulta_retornarID($sql2);
 
		 $num_elementos = 0;
		 $sw = true;
 
		 while ($num_elementos < count($permisos)) {
			 $sql_detalle = "INSERT INTO usuario_permiso(Idusuario, Idpermiso) VALUES ('$Idusuarionew', '$permisos[$num_elementos]')";
			 ejecutarConsulta($sql_detalle) or $sw = false;
			 $num_elementos++;
		 }
 
		 return $sw;
	 }
 
	 return false;
 }
 
	
	public function editar($Idusuario,$Tipopersona,$Nombres,$Num_documento,$Tipo_documento,$Telefono,$Direccion,
	$Login,$Clavehash,$Email,$Fecha_creacion,$Cargo,$Imagen,$permisos)
	{
		// Actualización en la tabla persona
		$sql = "UPDATE persona SET Tipopersona='$Tipopersona',Nombres='$Nombres',Num_documento='$Num_documento',Tipo_documento='$Tipo_documento',Telefono='$Telefono',Direccion='$Direccion'
				WHERE Idpersona =  (SELECT Idpersona FROM usuario WHERE Idusuario = '$Idusuario')";
		
		$resultado1 = ejecutarConsulta($sql);
	
		if ($resultado1) {
			// Actualización en la tabla usuario
			$sql2 = "UPDATE usuario SET  Login='$Login',Clave='$Clavehash',Email='$Email',Fecha_creacion='$Fecha_creacion',Cargo='$Cargo',Imagen='$Imagen'
			WHERE  Idusuario= '$Idusuario'";
			ejecutarConsulta($sql2);
			
			//Eliminamos todos los permisos asignados para volverlos a registrar
			$sqldel="DELETE FROM usuario_permiso WHERE Idusuario='$Idusuario'";
		    ejecutarConsulta($sqldel);
 
			$num_elementos = 0;
			$sw = true;

			while ($num_elementos < count($permisos)) {
				$sql_detalle = "INSERT INTO usuario_permiso(Idusuario, Idpermiso) VALUES('$Idusuario', '$permisos[$num_elementos]')";
				ejecutarConsulta($sql_detalle) or $sw = false;
				$num_elementos = $num_elementos + 1;
			}

			return $sw;
	
		}
	
		return $resultado1;
	}

		//Implementamos un método para desactivar categorías
		public function desactivar($Idusuario)
		{
			$sql = "UPDATE persona
					SET Estado = '0'
					WHERE Idpersona = (SELECT Idpersona FROM usuario WHERE Idusuario = '$Idusuario')";
			return ejecutarConsulta($sql);
		}
		//Implementamos un método para activar categorías
		public function activar($Idusuario)
		{
			$sql = "UPDATE persona
					SET Estado = '1'
					WHERE Idpersona = (SELECT Idpersona FROM usuario WHERE Idusuario = '$Idusuario')"; 
			return ejecutarConsulta($sql);
		}


		  
		//Implementar un método para mostrar los datos de un registro a modificar
		public function mostrar($Idusuario)
		{
			$sql="SELECT p.*,u.Login,u.Clave,u.Email,u.Idusuario,u.Imagen,u.Fecha_creacion,u.Cargo 
			FROM usuario u 
			INNER JOIN persona p ON u.Idpersona = p.Idpersona WHERE u.Idusuario = '$Idusuario'";
			return ejecutarConsultaSimpleFila($sql);
		}

		//Implementar un método para listar los registros
		public function listar()
		{
			$sql = "SELECT p.*,u.Login,u.Clave,u.Email,u.Idusuario,u.Imagen,u.Fecha_creacion,u.Cargo
			 FROM persona p 
			INNER JOIN usuario u on p.Idpersona=u.Idpersona
			WHERE p.Tipopersona = 'Usuario'
			order by p.Idpersona desc"; 
			return ejecutarConsulta($sql); 
		}

	
	  //Implementar un método para listar los permisos marcados
			public function listarmarcados($Idusuario)
		{
			$sql = "SELECT up.Idpermiso,up.Idusuario
			FROM usuario_permiso up
			WHERE up.Idusuario = (
			SELECT u.Idusuario
			FROM usuario u
			INNER JOIN persona p ON u.Idpersona = p.Idpersona
			WHERE u.Idusuario = '$Idusuario')";
				
			return ejecutarConsulta($sql);
		}

	//Función para verificar el acceso al sistema
	public function verificar($login,$clavehash)
    {
    	$sql="SELECT u.Idusuario,u.Login,p.Nombres as nombre,u.Imagen as imagen FROM usuario u
        INNER JOIN persona  p on u.Idpersona=p.Idpersona     
		WHERE u.Login='$login' AND u.Clave='$clavehash' AND p.Estado='1'"; 
    	return ejecutarConsulta($sql);  
    }

		//Implementar un método optener la clave
		public function obtenerClaveActual($Idusuario)
		{
			$sql = "SELECT u.Clave  as Clave FROM usuario u JOIN persona p on u.Idpersona=p.Idpersona WHERE u.Idusuario= '$Idusuario'";
			$resultado = ejecutarConsultaSimpleFila($sql);
			return $resultado['Clave'];  
		}
		 
		public function recordar($Email,$Numero)
		{
			$sql="SELECT u.Idusuario,p.Idpersona,p.Nombres as nombre,u.Login,u.Clave,u.Imagen as imagen
        FROM usuario u 
        INNER JOIN persona p on u.Idpersona=p.Idpersona 
		WHERE u.Email='$Email' AND p.Num_documento='$Numero' AND p.Estado='1'"; 
			return ejecutarConsulta($sql);  
		}
	
		public function editarClave($clavehash, $Idusuario) {
			$sql = "UPDATE usuario SET  Clave='$clavehash' WHERE Idusuario = '$Idusuario'";
			return ejecutarConsulta($sql);
		}

		public function verificarDuplicidad($Num_documento)
		{
		   $sql="SELECT * FROM persona
		   WHERE   Num_documento='$Num_documento'";
		   return ejecutarConsulta($sql);  
		}
	

}

?>