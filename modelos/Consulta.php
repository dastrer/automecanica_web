<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consultas
{
	//Implementamos nuestro constructor
	public function __construct() 
	{
 
	}
 
    public function total_venta_anio(){
		$sql="SELECT SUM(v.Total) AS total_venta_anio
        FROM venta v
        WHERE YEAR(v.Fecha) = YEAR(CURDATE()) AND v.Estado = 'Aceptado'";
		return ejecutarConsulta($sql); 
	}


    public function ventahoy(){
		$sql="SELECT IFNULL(SUM(v.Total), 0) AS ventahoy
		FROM venta v 
		WHERE DATE(v.Fecha) = CURDATE()  AND v.Estado = 'Aceptado'";
		return ejecutarConsulta($sql); 
	}

    public function ingresohoy(){
		$sql="SELECT IFNULL(SUM(i.Preciototal), 0) AS ingresohoy
		FROM ingreso i 
		WHERE DATE(i.Fecha) = CURDATE()  AND i.Estado = 'Aceptado'";
		return ejecutarConsulta($sql); 
	}
    public function cambiohoy(){
		$sql="SELECT IFNULL(SUM(v.Cambio), 0) AS cambiohoy
		FROM venta v 
		WHERE DATE(v.Fecha) = CURDATE()  AND v.Estado = 'Aceptado'";
		return ejecutarConsulta($sql); 

	}    public function efectivohoy(){
		$sql="SELECT IFNULL(SUM(v.Monto), 0) AS montohoy
		FROM venta v 
		WHERE DATE(v.Fecha) = CURDATE()  AND v.Estado = 'Aceptado'";
		return ejecutarConsulta($sql); 
	}
    

    public function total_ingreso()
    {
        $sql="SELECT 
        DATE_FORMAT(i.Fecha, '%M %Y') AS fecha, 
        ROUND(SUM(i.Preciototal), 2) AS total_ingreso
        FROM ingreso i
        WHERE i.Estado = 'Aceptado'
        GROUP BY YEAR(i.Fecha), MONTH(i.Fecha)
        ORDER BY YEAR(i.Fecha) DESC, MONTH(i.Fecha) DESC
        LIMIT 10";
        return ejecutarConsulta($sql);
    }


    public function total_venta()
    {
        $sql="SELECT 
        DATE_FORMAT(v.Fecha, '%M %Y') AS fecha, 
        ROUND(SUM(v.Total), 2) AS total_venta
        FROM venta v
        WHERE v.Estado = 'Aceptado' 
        GROUP BY YEAR(v.Fecha), MONTH(v.Fecha)
        ORDER BY YEAR(v.Fecha) DESC, MONTH(v.Fecha) DESC
        LIMIT 10";
        return ejecutarConsulta($sql);
    }

        public function total_servicio()
    {
        $sql="SELECT 
        DATE_FORMAT(s.Fecha, '%M %Y') AS fecha, 
        ROUND(SUM(s.Total), 2) AS total_servicio
        FROM servicio s
        WHERE s.Estado = 'Reparado' 
        GROUP BY YEAR(s.Fecha), MONTH(s.Fecha)
        ORDER BY YEAR(s.Fecha) DESC, MONTH(s.Fecha) DESC
        LIMIT 10";
        return ejecutarConsulta($sql);
    }



    public function articulosPorCategoria()
    {
        $sql = "SELECT c.Nombre AS categoria, COUNT(a.Idarticulo) AS cantidad
            FROM categoria c
            LEFT JOIN articulo a ON c.Idcategoria = a.Idcategoria
            GROUP BY c.Nombre
            ORDER BY cantidad DESC
            LIMIT 3";
        return ejecutarConsulta($sql);
    }
    
        public function usuarioPagina()
    {
        $sql = "SELECT  IFNULL(COUNT(u.Idusuario), 0)AS cantidad
            FROM usuario u
            INNER JOIN persona p on u.Idpersona=p.Idpersona WHERE p.Estado = '1' AND u.Cargo != 'ADMINISTRADOR'";
        return ejecutarConsulta($sql);
    }

            public function clientePagina()
    {
        $sql = "SELECT IFNULL(COUNT(p.Idpersona), 0)AS cantidad
            FROM persona p  WHERE p.Estado = '1' AND p.Tipopersona = 'Cliente'"; 
        return ejecutarConsulta($sql);
    }

                public function servicioPagina()
    {
        $sql = "SELECT  IFNULL(COUNT(s.Idservicio), 0) AS cantidad
            FROM servicio s  WHERE s.Estado = 'Reparado' "; 
        return ejecutarConsulta($sql);
    }



}



?>