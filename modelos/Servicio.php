<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Servicio
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function insertar($Fecha,$Fecha_entrega, $Tipo_comprobante, $Descripcion, $Total, $Idusuario, $Idvehiculo,$Monto,$Cambio,$Acuenta,$Saldo,$Metodo_pago,$Iditem_diagnostico, $Estado, $Precio, $Observaciones)
	{
		// 1. Obtener el último número de comprobante de cotización
		$sql_comprobante = "SELECT IFNULL(MAX(Num_comprobante), 0) + 1 AS nuevo_comprobante 
		FROM servicio 
		WHERE Tipo_comprobante = 'Recibo' 
		AND YEAR(Fecha) = YEAR(CURDATE())";
		$resultado = ejecutarConsultaSimpleFila($sql_comprobante); // Retorna una fila con el nuevo número
		$numComprobante = $resultado['nuevo_comprobante'];

		// 2. Insertar servicio con el número generado
		$sql = "INSERT INTO servicio(Fecha,Fecha_entrega, Tipo_comprobante, Num_comprobante, Descripcion, Total, Idusuario, Idvehiculo, Estado)
            VALUES ('$Fecha','$Fecha_entrega', '$Tipo_comprobante', '$numComprobante', '$Descripcion', '$Total', '$Idusuario', '$Idvehiculo', 'En proceso')";
		$Idservicionew = ejecutarConsulta_retornarID($sql); 

		// 3. Insertar pago asociado al sercicio
		 $sql_pago = "INSERT INTO pago_servicio (Idservicio,Fecha,Monto,Cambio,Acuenta,Saldo,Metodo_pago)
		 VALUES ($Idservicionew,'$Fecha','$Monto','$Cambio','$Acuenta','$Saldo','$Metodo_pago')";
	    ejecutarConsulta($sql_pago);
	
		$num_elementos = 0;
		$sw = true;

		// 4. Insertar cada producto asociado a la servicio 
		while ($num_elementos < count($Iditem_diagnostico)) {
			$sql_detalle = "INSERT INTO item_diagnostico_servicio (Iditem_diagnostico ,Idservicio, Estado, Precio, Observaciones) 
                        VALUES ('$Iditem_diagnostico[$num_elementos]', '$Idservicionew','$Estado[$num_elementos]', '$Precio[$num_elementos]', 
                                '$Observaciones[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos++;
		}

		return $sw ? $Idservicionew : false;
	}

	public function insertar_articulo($Fecha,$Fecha_entrega, $Tipo_comprobante, $Descripcion, $Total, $Idusuario, $Idvehiculo, $Monto,$Cambio,$Acuenta,$Saldo,$Metodo_pago, $Iditem_diagnostico, $Estado, $Precio, $Observaciones, $Precio_compra, $Precio_venta, $Descuento, $Cantidad_venta, $Idarticulo)
	{
			// 1. Obtener el último número de comprobante de cotización
		$sql_comprobante = "SELECT IFNULL(MAX(Num_comprobante), 0) + 1 AS nuevo_comprobante 
		FROM servicio 
		WHERE Tipo_comprobante = 'Recibo' 
		AND YEAR(Fecha) = YEAR(CURDATE())";
		$resultado = ejecutarConsultaSimpleFila($sql_comprobante); // Retorna una fila con el nuevo número
		$numComprobante = $resultado['nuevo_comprobante'];

		// 2. Insertar la nueva cotización con el número generado
		$sql = "INSERT INTO servicio(Fecha,Fecha_entrega, Tipo_comprobante, Num_comprobante, Descripcion, Total, Idusuario, Idvehiculo, Estado)
            VALUES ('$Fecha','$Fecha_entrega', '$Tipo_comprobante', '$numComprobante', '$Descripcion', '$Total', '$Idusuario', '$Idvehiculo', 'En proceso')";

		$Idservicionew = ejecutarConsulta_retornarID($sql); // Obtener el ID de la nueva cotización

		// 3. Insertar pago asociado al sercicio
		 $sql_pago = "INSERT INTO pago_servicio (Idservicio,Fecha,Monto,Cambio,Acuenta,Saldo,Metodo_pago)
		 VALUES ($Idservicionew,'$Fecha','$Monto','$Cambio','$Acuenta','$Saldo','$Metodo_pago')";
	    ejecutarConsulta($sql_pago);

		$num_elementos = 0;
		$sw = true;

		// 4. Insertar cada producto asociado a la servicio 
		while ($num_elementos < count($Iditem_diagnostico)) {
			$sql_detalle = "INSERT INTO item_diagnostico_servicio (Iditem_diagnostico ,Idservicio, Estado, Precio, Observaciones) 
                        VALUES ('$Iditem_diagnostico[$num_elementos]', '$Idservicionew','$Estado[$num_elementos]', '$Precio[$num_elementos]', 
                                '$Observaciones[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos++;
		}

		// 4. Insertar artículos asociados (no volver a poner $sw = true)
		$num_elementos = 0;
		while ($num_elementos < count($Idarticulo)) {
			$sql_detalle = "INSERT INTO servicio_articulo (Precio_compra, Precio_venta, Descuento, Cantidad_venta, Idservicio, Idarticulo) 
                        VALUES ('$Precio_compra[$num_elementos]', '$Precio_venta[$num_elementos]', '$Descuento[$num_elementos]', 
                                '$Cantidad_venta[$num_elementos]', '$Idservicionew', '$Idarticulo[$num_elementos]')";
			if (!ejecutarConsulta($sql_detalle))
				$sw = false;
			$num_elementos++;
		}

		return $sw ? $Idservicionew : false;
	}


public function anular($Idservicio)
{
    // Consultar si hay artículos de venta asociados al servicio
    $consultaVenta = "SELECT sa.Idarticulo, sa.Cantidad_venta AS Cantidad_venta
                      FROM servicio_articulo sa
                      WHERE sa.Idservicio = '$Idservicio'";
    $resultados = ejecutarConsulta($consultaVenta);

    $datosServicio = array();
    while ($row = $resultados->fetch_assoc()) {
        $datosServicio[] = $row;
    }

    // Anular el servicio, sin importar si tiene o no artículos
    $sqlAnularVenta = "UPDATE servicio SET Estado='Anulado' WHERE Idservicio='$Idservicio'";
    ejecutarConsulta($sqlAnularVenta);

    // Si hay artículos, restaurar el stock
    if (!empty($datosServicio)) {
        foreach ($datosServicio as $venta) {
            $Idarticulo = $venta['Idarticulo'];
            $cantidad = $venta['Cantidad_venta'];

            $sqlRestaurarStock = "UPDATE articulo 
                                  SET Cantidad = Cantidad + $cantidad 
                                  WHERE Idarticulo = '$Idarticulo'";
            ejecutarConsulta($sqlRestaurarStock);
        }
    }

    return true;
}



	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($Idservicio)
	{
		$sql = "SELECT  s.Idservicio,s.Fecha,s.Fecha_entrega,v.Idvehiculo,s.Tipo_comprobante,p.Nombres,s.Num_comprobante,
			s.Descripcion,s.Total,pgt.Monto,pgt.Cambio,pgt.Acuenta,pgt.Saldo,pgt.Metodo_pago
			FROM servicio s 
			INNER JOIN vehiculo v ON s.Idvehiculo = v.Idvehiculo
			INNER JOIN persona p ON v.Idcliente = p.Idpersona
			INNER JOIN (
			SELECT *
			FROM pago_servicio
			WHERE Idpago IN (
				SELECT MIN(Idpago)
				FROM pago_servicio
				GROUP BY Idservicio
			)
		) pgt ON s.Idservicio = pgt.Idservicio
			WHERE s.Idservicio = '$Idservicio'";
		return ejecutarConsultaSimpleFila($sql);
	}
	public function listarDiagnosticoServicio($Idservicio)
	{
		$sql = "SELECT 
                i.Iditem_diagnostico,
				i.Idservicio,
                d.Nombre,
                i.Estado,
                i.Precio,
                i.Observaciones
            FROM item_diagnostico_servicio i
            INNER JOIN item_diagnostico d ON i.Iditem_diagnostico = d.Iditem_diagnostico
            WHERE i.Idservicio = '$Idservicio'";
		return ejecutarConsulta($sql);
	}

	public function listarDetalle($Idservicio)
	{
		$sql = "SELECT sa.Idservicio,sa.Idarticulo,a.Nombre,a.Cantidad,ua.Unidadmedida,sa.Cantidad_venta,sa.Precio_venta,sa.Descuento,
		ROUND(sa.Cantidad_venta*sa.Precio_venta-sa.Descuento,2) as Subtotal
		FROM servicio_articulo sa
        INNER JOIN articulo a ON sa.Idarticulo=a.Idarticulo
        INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
		WHERE sa.Idservicio='$Idservicio'";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para listar los registros
	public function listar($fecha_inicio,$fecha_fin)
	{
		$sql = "SELECT  s.Idservicio, s.Fecha,s.Fecha_entrega, s.Tipo_comprobante, s.Num_comprobante, s.Descripcion, s.Total, s.Estado,
  		v.Placa, v.Marca, v.Color, v.Tipo,
		p1.Nombres AS Cliente,
		p.Nombres AS Usuario,
        pgt.Acuenta AS Acuenta,
        pgt.Saldo AS SaldoPago,
        pgt.Metodo_pago
		FROM servicio s 
       INNER JOIN vehiculo v on s.Idvehiculo=v.Idvehiculo
       INNER JOIN persona p1 on v.Idcliente=p1.Idpersona
       INNER JOIN usuario u on s.Idusuario=u.Idusuario
       INNER JOIN persona p on u.Idpersona=p.Idpersona
	   INNER JOIN (
			SELECT *
			FROM pago_servicio
			WHERE Idpago IN (
				SELECT MAX(Idpago)
				FROM pago_servicio
				GROUP BY Idservicio
			)
		) pgt ON s.Idservicio = pgt.Idservicio
		WHERE  DATE(s.Fecha)>='$fecha_inicio' AND DATE(s.Fecha)<='$fecha_fin'
        ORDER BY  s.Idservicio desc";
		return ejecutarConsulta($sql);
	}



	//Implementar un método para listar los registros y mostrar en el combobox
	public function selectCotizacion()
	{
		$sql = "SELECT co.Idcotizacion,p1.Nombre,p1.Apellidos,co.Tipo_comprobante,co.Num_comprobante
		FROM cotizacion co JOIN cliente c ON co.Idcliente=c.Idcliente
        JOIN persona p1 on c.Idpersona=p1.Idpersona
        WHERE co.Estado='Aceptado'";
		return ejecutarConsulta($sql);
	}

	public function serviciocabecera($Idservicio)
	{
		$sql = "SELECT s.*,v.Placa,v.Marca,v.Color,v.Tipo,p1.Nombres,p1.Num_documento,p1.Tipo_documento,p2.Nombres as Usuario
		FROM servicio s 
        JOIN vehiculo v on s.Idvehiculo=v.Idvehiculo
        JOIN persona p1 on v.Idcliente=p1.Idpersona
		JOIN usuario u ON u.Idusuario=s.Idusuario 
        JOIN persona p2 on u.Idpersona=p2.Idpersona
        WHERE s.Idservicio='$Idservicio'";
		return ejecutarConsulta($sql);
	}

		public function listarDiagnosticoPDF($Idservicio)
	{
		$sql = "SELECT 
                i.Iditem_diagnostico,
				i.Idservicio,
                d.Nombre,
                i.Estado,
                i.Precio,
                i.Observaciones
            FROM item_diagnostico_servicio i
            INNER JOIN item_diagnostico d ON i.Iditem_diagnostico = d.Iditem_diagnostico
            WHERE i.Idservicio = '$Idservicio'";

		return ejecutarConsulta($sql);
	}

	public function serviciodetalle($Idservicio)
	{
		$sql = "SELECT sa.Idservicio,sa.Idarticulo,a.Codigo,a.Nombre,a.Cantidad,ua.Unidadmedida,sa.Cantidad_venta,sa.Precio_venta,sa.Descuento,
		ROUND(sa.Cantidad_venta*sa.Precio_venta-sa.Descuento,2) as Subtotal
		FROM servicio_articulo sa
        INNER JOIN articulo a ON sa.Idarticulo=a.Idarticulo
        INNER JOIN unidadmedida ua ON a.Idunidadmedida = ua.Idunidadmedida
		WHERE sa.Idservicio='$Idservicio'";
		return ejecutarConsulta($sql);
	}

		public function listarPago($Idservicio)
	{
		$sql="SELECT  ps.Fecha as FechaPago,ps.Saldo,ps.Acuenta,ps.Metodo_pago
				FROM servicio s
                LEFT JOIN pago_servicio ps ON s.Idservicio=ps.Idservicio
                WHERE ps.Idservicio='$Idservicio'";
		return ejecutarConsulta($sql);
	}


	public function actualizarEstadoReparado($Idservicio) 
{
    $sql = "UPDATE servicio SET Estado = 'Reparado' WHERE Idservicio = '$Idservicio'";
    return ejecutarConsulta($sql);
}


		// DATOS ESTADISCOS
		public function servicio_mes()
	{
		$sql = "SELECT COUNT(*) AS servicio_mes
				FROM servicio
				WHERE MONTH(Fecha) = MONTH(CURDATE()) 
				AND YEAR(Fecha) = YEAR(CURDATE()) AND Estado = 'En proceso'";
		return ejecutarConsulta($sql);
	}

			public function completado_mes()
	{
		$sql = "SELECT COUNT(*) AS completado_mes
				FROM servicio
				WHERE MONTH(Fecha) = MONTH(CURDATE()) 
				AND YEAR(Fecha) = YEAR(CURDATE()) AND Estado = 'Reparado'";
		return ejecutarConsulta($sql);
	}

			 public function selectServicio()
	{
		$sql = "SELECT s.Idservicio,s.Tipo_comprobante,s.Num_comprobante,v.placa as Vehiculo
				FROM servicio s
				INNER JOIN vehiculo v ON s.Idvehiculo = v.Idvehiculo";
		return ejecutarConsulta($sql);
	}

}

?>