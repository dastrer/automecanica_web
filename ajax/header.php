<?php

switch ($_GET["op"]) {
	case 'listarnotificacionArticulo':
		// Ejecutar la función que devuelve los productos con stock bajo
		require_once "..\modelos\Articulo.php";
		$articulo = new Articulo();
		$result = $articulo->alerta();

		$notifications = [];
		$count = 0;

		// Iterar sobre los resultados y construir el array de notificaciones
		while ($row = $result->fetch_assoc()) {
			$notifications[] = [
				'nombre' => $row['Nombre'],
				'stock' => $row['Cantidad'],
			];
			$count++;
		}

		// Configurar el encabezado de la respuesta como JSON
		header('Content-Type: application/json');

		// Enviar los datos como respuesta JSON
		echo json_encode([
			'count' => $count,
			'notifications' => $notifications
		]);
		break;
	case 'listarnotificacion':
		require_once "../modelos/Mensaje.php";
		$mensaje = new Mensaje();
		// Ejecutar la función que devuelve las notificaciones
		$result = $mensaje->listarnotificacion();

		$notifications = [];
		$count = 0;

		// Iterar sobre los resultados y construir el array de notificaciones
		while ($row = $result->fetch_assoc()) {
			$notifications[] = [
				'message' => 'Solicitud de: ' . $row['Nombre'], // Ajusta según tus campos
				'time' => 'Fecha: ' . $row['Fecha'] // Puedes ajustar esto para mostrar la hora correcta
			];
			$count++;
		}

		// Configurar el encabezado de la respuesta como JSON
		header('Content-Type: application/json');

		// Enviar los datos como respuesta JSON
		echo json_encode([
			'count' => $count,
			'notifications' => $notifications
		]);
		break;

}
?>