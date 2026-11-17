<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Pago
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}


	//PAGO SERVICO

public function insertar($Idservicio, $Fecha, $Monto, $Cambio, $Acuenta, $Saldo, $Metodo_pago)
{
    // Insertar el nuevo pago
	 $sql_pago = "INSERT INTO pago_servicio (Idservicio,Fecha,Monto,Cambio,Acuenta,Saldo,Metodo_pago)
		 VALUES ($Idservicio,'$Fecha','$Monto','$Cambio','$Acuenta','$Saldo','$Metodo_pago')";
	    ejecutarConsulta($sql_pago);

    // Obtener el TOTAL del servicio desde la tabla 'servicio'
    $sqlTotal = "SELECT Total FROM servicio WHERE Idservicio = '$Idservicio'";
    $resultadoTotal = ejecutarConsulta($sqlTotal);
    $rowTotal = $resultadoTotal->fetch_assoc();
    $Total = $rowTotal['Total'];

    // Sumar todo lo que ya se ha pagado con Acuenta
    $sqlSaldo = "SELECT SUM(Acuenta) AS totalAcuenta FROM pago_servicio WHERE Idservicio = '$Idservicio'";
    $resultadoSaldo = ejecutarConsulta($sqlSaldo);
    $rowSaldo = $resultadoSaldo->fetch_assoc();
    $totalAcuentaPagado = $rowSaldo['totalAcuenta'] ;

    // Calcular el saldo restante
    $saldoRestante = $Total - $totalAcuentaPagado;

    // Determinar el nuevo estado del servicio
        if ($saldoRestante <= 0) {
            $nuevoEstado = 'Reparado'; 
        } else {
            $nuevoEstado = 'En proceso';
        }
    // Actualizar estado del servicio
    $sqlActualizar = "UPDATE servicio SET Estado = '$nuevoEstado' WHERE Idservicio = '$Idservicio'";
    return ejecutarConsulta($sqlActualizar);
}

		public function mostrarPago($Idservicio)
	{
		$sql="SELECT  s.Idservicio,s.Fecha,s.Fecha_entrega,v.Idvehiculo,s.Tipo_comprobante,s.Num_comprobante,s.Descripcion,s.Estado,
			s.Descripcion,s.Total,pgt.Acuenta,pgt.Saldo,pgt.Metodo_pago,v.Placa,v.Marca,v.Tipo
			FROM servicio s 
			INNER JOIN vehiculo v ON s.Idvehiculo = v.Idvehiculo
			INNER JOIN (
				SELECT *
				FROM pago_servicio
				WHERE Idpago IN (
					SELECT MAX(Idpago)
					FROM pago_servicio
					GROUP BY Idservicio
				)
			) pgt ON s.Idservicio = pgt.Idservicio
			WHERE s.Idservicio = '$Idservicio'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetallePago($Idservicio)
	{
		$sql="SELECT  ps.Fecha as FechaPago,ps.Saldo,ps.Acuenta,ps.Monto,ps.Cambio
				FROM servicio s
                LEFT JOIN pago_servicio ps ON s.Idservicio=ps.Idservicio
                WHERE ps.Idservicio='$Idservicio'";
		return ejecutarConsulta($sql);
	}

}

?>