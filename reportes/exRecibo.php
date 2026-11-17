<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['Servicio']==1)
{

  require('COMPROBANTE.php');
  require "../phpqrcode/qrlib.php"; 
  date_default_timezone_set('America/La_Paz');
//Inlcuímos a la clase PDF_MC_Table

  $pdf=new PDF_MC_Table();
  $pdf->AliasNbPages();
  $pdf->AddPage('P', 'Letter'); 
  require_once "../modelos/Servicio.php";
  $Servicio = new Servicio();
  $detalles1 = $Servicio->serviciocabecera($_GET["id"]);
  $regc = $detalles1->fetch_object(); 
//Inicio QR
$dir = 'Qr/';
	
//Si no existe la carpeta la creamos
if (!file_exists($dir))
{
      mkdir($dir);
}
      //Declaramos la ruta y nombre del archivo a generar
$filename = $dir.'QR.png';
      //Parametros de Condiguración

$tamaño = 10; //Tamaño de Pixel
$level = 'L'; //Precisión Baja
$framSize = 3; //Tamaño en blanco
$contenido = " 
  RECIBO No: $regc->Num_comprobante
  ===================================
  Fecha = $regc->Fecha
  Fecha entrega = $regc->Fecha_entrega
  Mecanico = $regc->Usuario
  Cliente= $regc->Nombres,
  Total= $regc->Total
"; //Texto
      //Enviamos los parametros a la Función para generar código QR 
QRcode::png($contenido, $filename, $level, $tamaño, $framSize); 
  //Fin QR

  $pdf->SetFont('Arial', 'B', 10);
  $pdf->SetFillColor(222, 20, 45);  
  $pdf->SetTextColor(255, 255, 255); 
  $pdf->Cell(195, 8, utf8_decode('DETALLE DE SERVICIO'), 1, 1, 'C', 1); // Esta celda cubre todo el ancho (190 unidades)
  $pdf->Ln(1);
  $pdf->SetX(10);
  $pdf->SetTextColor(0, 0, 0); 
  $pdf->SetFillColor(97, 222,72);  
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Cell(50, 5, utf8_decode($regc->Tipo_comprobante.' No:'), 0, 0, 'L');
  $pdf->SetFont('Arial', '', 11);
  $pdf->Cell(70, 5, utf8_decode($regc->Num_comprobante), 0, 0, 'L');

  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(10, 5, utf8_decode('Placa:'), 0, 0, 'R');
  $pdf->SetFont('Arial', '', 11);
  $pdf->Cell(40, 5, utf8_decode($regc->Placa), 0, 1, 'R');
  $pdf->Ln(2);

  $pdf->SetFont('Arial', 'B', 12);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Cell(50, 5, utf8_decode('Fecha entrega:'), 0, 0, 'L');
  $pdf->SetFont('Arial', '', 11);
  $pdf->Cell(70, 5, utf8_decode($regc->Fecha_entrega), 0, 0, 'L');

  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(10, 5, utf8_decode('Marca:'), 0, 0, 'R');
  $pdf->SetFont('Arial', '', 11);
  $pdf->Cell(40, 5, utf8_decode($regc->Marca), 0, 1,'R');
  $pdf->Ln(2);
  
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Cell(50, 5, utf8_decode('Señor(es):'), 0, 0, 'L');
  $pdf->SetFont('Arial', '', 11);
  $pdf->Cell(70, 5, utf8_decode($regc->Nombres ), 0, 0, 'L');

  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(10, 5, utf8_decode('Vehiculo:'), 0, 0, 'R');
  $pdf->SetFont('Arial', '', 11);
  $pdf->Cell(40, 5, utf8_decode($regc->Tipo), 0, 1,'R');
  $pdf->Ln(2);
  
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Cell(50, 5, utf8_decode('NIT/CI:'), 0, 0, 'L');
  $pdf->SetFont('Arial', '', 11);
  $pdf->Cell(70, 5, utf8_decode($regc->Num_documento . " " . $regc->Tipo_documento), 0, 0, 'L');
   
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(10, 5, utf8_decode('Color:'), 0, 0, 'R');
  $pdf->SetFont('Arial', '', 11);
  $pdf->Cell(40, 5, utf8_decode($regc->Color), 0, 1,'R');
  $pdf->Ln(2);

  $pdf->SetFont('Arial', 'B', 12);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Cell(35, 5, utf8_decode('DESCRIPCION:'), 0, 0, 'L');
  $pdf->SetFont('Arial', '', 11);
  $pdf->Cell(50, 5, utf8_decode($regc->Descripcion), 0, 1, 'L');



$pdf->Ln(2); // Espacio antes de la siguiente tabla
$pdf->SetFillColor(222, 20, 45);  
$pdf->SetTextColor(255, 255, 255); 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(195, 8, utf8_decode('LISTA DE DIAGNÓSTICOS / SERVICIOS'), 1, 1, 'C', 1); 
$pdf->SetX(10);
$pdf->SetFillColor(205, 213, 192);  
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0); 

$pdf->Cell(10, 8, utf8_decode('N°'),1,0,'C',1);
$pdf->Cell(55, 8, utf8_decode('NOMBRE DEL SERVICIO'),1,0,'C',1);
$pdf->Cell(20, 8, utf8_decode('ESTADO'),1,0,'C',1);
$pdf->Cell(40, 8, utf8_decode('PRECIO'),1,0,'C',1);
$pdf->Cell(70, 8, utf8_decode('OBSERVACIONES'),1,1,'C',1);

// Supongamos que usas este método para obtener los diagnósticos:
$diagnosticos = $Servicio->listarDiagnosticoPDF($_GET["id"]);

$pdf->SetWidths(array(10, 55, 20, 40, 70));
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial','',10);
$pdf->SetAligns(array('C', 'L', 'C', 'R', 'L'));

$contador = 1;
$total_diagnostico = 0;

while ($reg = $diagnosticos->fetch_object()) {
    $estado = ($reg->Estado === 'M') ? 'MAL' : 'BIEN';
    $precio = number_format($reg->Precio, 2, '.', '');
    $observacion = $reg->Observaciones ?? '';

    if ($reg->Estado === 'M') {
        $total_diagnostico += (float)$reg->Precio;
    }

    $pdf->Row(array(
        $contador++,
        utf8_decode($reg->Nombre),
        $estado,
        "Bs/. " . $precio,
        utf8_decode($observacion)
    ));
}

// Mostrar total diagnóstico 
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(155, 8, 'TOTAL DIAGNOSTICO', 1, 0, 'R', 1);
$pdf->Cell(40, 8, 'Bs/. ' . number_format($total_diagnostico, 2, '.', ''), 1, 1, 'R', 1);


//DETALLE DE VENTA
$detalles2 = $Servicio->serviciodetalle($_GET["id"]);
$rspta = $detalles2;

if ($rspta->num_rows > 0) { // Solo si hay artículos

  $pdf->SetFont('Arial', 'B', 10);
  $pdf->SetFillColor(222, 20, 45);  
  $pdf->SetTextColor(255, 255, 255); 
  $pdf->Cell(195, 8, utf8_decode('DETALLE DE ARTICULOS'), 1, 1, 'C', 1); 

  $pdf->SetX(10);
  $pdf->SetFillColor(205, 213, 192);  
  $pdf->SetFont('Arial','B',10);
  $pdf->SetTextColor(0, 0, 0); 

  $pdf->Cell(40, 8, utf8_decode('CODIGO'),1,0,'C',1);
  $pdf->Cell(20, 8, utf8_decode('CANTIDAD'),1,0,'C',1);
  $pdf->Cell(55, 8, utf8_decode('DETALLES'),1,0,'C',1);
  $pdf->Cell(25, 8, utf8_decode('P.U.'),1,0,'C',1);
  $pdf->Cell(25, 8, utf8_decode('DSCTO'),1,0,'C',1);
  $pdf->Cell(30, 8, utf8_decode('SUBTOTAL'),1,1,'C',1);

  $pdf->SetWidths(array(40,20,55,25,25,30));
  $pdf->SetTextColor(0,0,0);

  $Total_subtotal = 0;

  while($reg = $rspta->fetch_object()) {
      $Codigo = $reg->Codigo; 
      $Cantidad = $reg->Cantidad_venta;
      $Detalle = $reg->Nombre;
      $Costo = $reg->Precio_venta;
      $Descuento = $reg->Descuento;
      $Subtotal = $reg->Subtotal;
      $Total_subtotal += round($Subtotal, 2);

      $pdf->SetFont('Arial','',10);
      $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C')); 
      $pdf->Row(array(
        utf8_decode($Codigo),
        utf8_decode($Cantidad),
        $Detalle,
        $Costo,
        $Descuento,
        $Subtotal
      ));  
  }

  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell(155, 8, 'TOTAL VENTA', 1, 0, 'R', 1);
  $pdf->Cell(40, 8, 'Bs/. ' . number_format($Total_subtotal, 2, '.', ''), 1, 1, 'R', 1);
}

  //DETALLE DE PAGOS
$pdf->SetFillColor(   232,232,232);  
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0, 0, 0); 

$pdf->Cell(10, 6, utf8_decode('N°'),1,0,'C',1);
$pdf->Cell(35, 6, utf8_decode('FECHA'),1,0,'C',1);
$pdf->Cell(50, 6, utf8_decode('ACUENTA'),1,0,'C',1);
$pdf->Cell(50, 6, utf8_decode('SALDO'),1,0,'C',1);
$pdf->Cell(50, 6, utf8_decode('METODO DE PAGO'),1,1,'C',1);

// Supongamos que usas este método para obtener los diagnósticos:
$pagos = $Servicio->listarPago($_GET["id"]);

$pdf->SetWidths(array(10, 35, 50, 50, 50));
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial','',10);
$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C'));

$contador = 1;

while ($reg = $pagos->fetch_object()) {

    $FechaPago = $reg->FechaPago;
    $Acuenta = $reg->Acuenta;
    $Saldo = $reg->Saldo;
    $MetodoPago = $reg->Metodo_pago;

    $pdf->Row(array(
        $contador++,
        $FechaPago,
        $Acuenta,
        $Saldo,
        utf8_decode($MetodoPago)
    ));
}
  // Convertir total en letras
  require_once "Letras.php";
  $V = new CifrasEnLetras(); 
  $titulo = utf8_decode('IMPORTE TOTAL EN LETRAS');
  $con_letra = strtoupper($V->convertirEurosEnLetras($regc->Total));
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFillColor(230, 230, 230); // Fondo opcional
  // Guardar posición
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  // Dibujar la celda vacía con borde y fondo
  $pdf->Rect($x, $y, 145, 10); // 2 líneas de 5 de alto
  $pdf->SetXY($x, $y);
  // Línea 1 - título en negrita
  $pdf->SetFont('Arial', 'B', 8);
  $pdf->Cell(145, 5, $titulo, 0, 2, 'L'); // 2 = salto de línea dentro de celda
  // Línea 2 - texto en letras
  $pdf->SetFont('Arial', '', 8);
  $pdf->Cell(145, 5, $con_letra, 0, 1, 'L');
  // TOTAL a la derecha
  $pdf->SetXY($x + 145, $y);
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell(50, 10, 'TOTAL: ' . number_format($regc->Total, 2, '.', ''), 1, 0, 'C', true);

  $pdf->Ln(0.5);
  $imagenPath = $filename;
  // Ajustar las posiciones X e Y según sea necesario
  $posicionX = 175; // Cambia este valor a la posición X deseada
  $posicionY = 3; // Cambia este valor a la posición Y deseada
  $pdf->Image($imagenPath, $posicionX, $posicionY, 30, 0, 'PNG');

  // Mostrar el documento PDF
  $pdf->Output('I', 'RECIBO_AUTONIC_' . $regc->Num_comprobante . '.pdf');
  ?>
  <?php
  }
  else
  {
    echo 'No tiene permiso para visualizar el reporte';
  }
  
  }
  ob_end_flush();
  ?>