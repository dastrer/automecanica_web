<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Cotizacion
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function insertar($Fecha, $Tipo_comprobante, $Descripcion, $Total, $Idusuario, $Idvehiculo, $Iditem_diagnostico, $Estado, $Precio, $Observaciones)
	{
		// 1. Obtener el último número de comprobante de cotización
		$sql_comprobante = "SELECT IFNULL(MAX(Num_comprobante), 0) + 1 AS nuevo_comprobante 
		FROM cotizacion 
		WHERE Tipo_comprobante = 'Proforma' 
		AND YEAR(Fecha) = YEAR(CURDATE())";
		$resultado = ejecutarConsultaSimpleFila($sql_comprobante); // Retorna una fila con el nuevo número
		$numComprobante = $resultado['nuevo_comprobante'];

		// 2. Insertar la nueva cotización con el número generado
		$sql = "INSERT INTO cotizacion (Fecha, Tipo_comprobante, Num_comprobante, Descripcion, Total, Idusuario, Idvehiculo, Estado)
            VALUES ('$Fecha', '$Tipo_comprobante', '$numComprobante', '$Descripcion', '$Total', '$Idusuario', '$Idvehiculo', 'Aceptado')";

		$Idcotizacionnew = ejecutarConsulta_retornarID($sql); // Obtener el ID de la nueva cotización

		$num_elementos = 0;
		$sw = true;

		// 3. Insertar cada producto asociado a la cotización Iditem_diagnostico	Idcotizacion	Estado	Precio	Observaciones
		while ($num_elementos < count($Iditem_diagnostico)) {
			$sql_detalle = "INSERT INTO item_diagnostico_cotizacion (Iditem_diagnostico ,Idcotizacion, Estado, Precio, Observaciones) 
                        VALUES ('$Iditem_diagnostico[$num_elementos]', '$Idcotizacionnew','$Estado[$num_elementos]', '$Precio[$num_elementos]', 
                                '$Observaciones[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos++;
		}

		return $sw ? $Idcotizacionnew : false;
	}

	public function insertar_articulo($Fecha, $Tipo_comprobante, $Descripcion, $Total, $Idusuario, $Idvehiculo, $Iditem_diagnostico, $Estado, $Precio, $Observaciones, $Precio_compra, $Precio_cotizacion, $Descuento, $Cantidad_cotizacion, $Idarticulo)
	{
		// 1. Obtener el último número de comprobante de cotización
		$sql_comprobante = "SELECT IFNULL(MAX(Num_comprobante), 0) + 1 AS nuevo_comprobante 
        FROM cotizacion 
        WHERE Tipo_comprobante = 'Cotizacion' 
        AND YEAR(Fecha) = YEAR(CURDATE())";
		$resultado = ejecutarConsultaSimpleFila($sql_comprobante);
		$numComprobante = $resultado['nuevo_comprobante'];

		// 2. Insertar cotización
		$sql = "INSERT INTO cotizacion (Fecha, Tipo_comprobante, Num_comprobante, Descripcion, Total, Idusuario, Idvehiculo, Estado)
            VALUES ('$Fecha', '$Tipo_comprobante', '$numComprobante', '$Descripcion', '$Total', '$Idusuario', '$Idvehiculo', 'Aceptado')";

		$Idcotizacionnew = ejecutarConsulta_retornarID($sql);

		$num_elementos = 0;
		$sw = true;

		// 3. Insertar ítems de diagnóstico
		while ($num_elementos < count($Iditem_diagnostico)) {
			$sql_detalle = "INSERT INTO item_diagnostico_cotizacion (Iditem_diagnostico, Idcotizacion, Estado, Precio, Observaciones) 
                        VALUES ('$Iditem_diagnostico[$num_elementos]', '$Idcotizacionnew', '$Estado[$num_elementos]', '$Precio[$num_elementos]', 
                                '$Observaciones[$num_elementos]')";
			if (!ejecutarConsulta($sql_detalle))
				$sw = false;
			$num_elementos++;
		}

		// 4. Insertar artículos asociados (no volver a poner $sw = true)
		$num_elementos = 0;
		while ($num_elementos < count($Idarticulo)) {
			$sql_detalle = "INSERT INTO cotizacion_articulo (Precio_compra, Precio_cotizacion, Descuento, Cantidad_cotizacion, Idcotizacion, Idarticulo) 
                        VALUES ('$Precio_compra[$num_elementos]', '$Precio_cotizacion[$num_elementos]', '$Descuento[$num_elementos]', 
                                '$Cantidad_cotizacion[$num_elementos]', '$Idcotizacionnew', '$Idarticulo[$num_elementos]')";
			if (!ejecutarConsulta($sql_detalle))
				$sw = false;
			$num_elementos++;
		}

		return $sw ? $Idcotizacionnew : false;
	}


	public function anular($Idcotizacion)
	{
		$sql = "UPDATE cotizacion SET Estado='Anulado' WHERE Idcotizacion='$Idcotizacion'";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Idcotizacion)
	{
		$sql = "SELECT  co.Idcotizacion,co.Fecha,v.Idvehiculo,co.Tipo_comprobante,p.Nombres,v.Marca,
                co.Num_comprobante,co.Descripcion,co.Total
				FROM cotizacion co 
                INNER JOIN vehiculo v ON co.Idvehiculo= v.Idvehiculo
                INNER JOIN persona p ON v.Idcliente= p.Idpersona
                WHERE co.Idcotizacion='$Idcotizacion'";
		return ejecutarConsultaSimpleFila($sql);
	}
	public function listarDiagnosticoCotizacion($Idcotizacion)
	{
		$sql = "SELECT 
                i.Iditem_diagnostico,
				i.Idcotizacion,
                d.Nombre,
                i.Estado,
                i.Precio,
                i.Observaciones
            FROM item_diagnostico_cotizacion i
            INNER JOIN item_diagnostico d ON i.Iditem_diagnostico = d.Iditem_diagnostico
            WHERE i.Idcotizacion = '$Idcotizacion'";

		return ejecutarConsulta($sql);
	}

	public function listarDetalle($Idcotizacion)
	{
		$sql = "SELECT ac.Idcotizacion,ac.Idarticulo,a.Nombre,a.Cantidad,ua.Unidadmedida,ac.Cantidad_cotizacion,ac.Precio_cotizacion,ac.Precio_compra,ac.Descuento,
		ROUND(ac.Cantidad_cotizacion*ac.Precio_cotizacion-ac.Descuento,2) as Subtotal
		FROM cotizacion_articulo ac
        INNER JOIN articulo a ON ac.Idarticulo=a.Idarticulo
        INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
		WHERE ac.Idcotizacion='$Idcotizacion'";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT  co.Idcotizacion, co.Fecha, co.Tipo_comprobante, co.Num_comprobante, co.Descripcion, co.Total, co.Estado,
  		v.Placa, v.Marca, v.Color, v.Tipo,
		p1.Nombres AS Cliente,
		p.Nombres AS Usuario
		FROM cotizacion co 
       INNER JOIN vehiculo v on co.Idvehiculo=v.Idvehiculo
       INNER JOIN persona p1 on v.Idcliente=p1.Idpersona
       INNER JOIN usuario u on co.Idusuario=u.Idusuario
       INNER JOIN persona p on u.Idpersona=p.Idpersona
        ORDER BY  co.Idcotizacion desc";
		return ejecutarConsulta($sql);
	}



	//Implementar un método para listar los registros y mostrar en el combobox
	public function selectCotizacion()
	{
		$sql = "SELECT  co.Idcotizacion,v.Idvehiculo,v.Placa,p.Nombres,v.Marca,
                co.Num_comprobante
				FROM cotizacion co 
                INNER JOIN vehiculo v ON co.Idvehiculo= v.Idvehiculo
                INNER JOIN persona p ON v.Idcliente= p.Idpersona
        WHERE co.Estado='Aceptado'";
		return ejecutarConsulta($sql);
	}

	public function coticabecera($Idcotizacion)
	{
		$sql = "SELECT co.*,v.Placa,v.Marca,v.Color,v.Tipo,p1.Nombres,p1.Num_documento,p1.Tipo_documento,p2.Nombres as Usuario
		FROM cotizacion co 
        JOIN vehiculo v on co.Idvehiculo=v.Idvehiculo
        JOIN persona p1 on v.Idcliente=p1.Idpersona
		JOIN usuario u ON u.Idusuario=co.Idusuario 
        JOIN persona p2 on u.Idpersona=p2.Idpersona
        WHERE co.Idcotizacion='$Idcotizacion'";
		return ejecutarConsulta($sql);
	}

		public function listarDiagnosticoPDF($Idcotizacion)
	{
		$sql = "SELECT 
                i.Iditem_diagnostico,
				i.Idcotizacion,
                d.Nombre,
                i.Estado,
                i.Precio,
                i.Observaciones
            FROM item_diagnostico_cotizacion i
            INNER JOIN item_diagnostico d ON i.Iditem_diagnostico = d.Iditem_diagnostico
            WHERE i.Idcotizacion = '$Idcotizacion'";

		return ejecutarConsulta($sql);
	}

	public function cotidetalle($Idcotizacion)
	{
		$sql = "SELECT ac.Idcotizacion,ac.Idarticulo,a.Codigo,a.Nombre,a.Cantidad,ua.Unidadmedida,ac.Cantidad_cotizacion,ac.Precio_cotizacion,ac.Descuento,
		ROUND(ac.Cantidad_cotizacion*ac.Precio_cotizacion-ac.Descuento,2) as Subtotal
		FROM cotizacion_articulo ac
        INNER JOIN articulo a ON ac.Idarticulo=a.Idarticulo
        INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
		WHERE ac.Idcotizacion='$Idcotizacion'";
		return ejecutarConsulta($sql);
	}

	public function obtenerIdClientePorPersona($Idpersona)
	{
		$sql = "SELECT Idcliente FROM cliente WHERE Idpersona = '$Idpersona'";
		$resultado = ejecutarConsultaSimpleFila($sql);
		return $resultado['Idcliente'];
	}


}

?>