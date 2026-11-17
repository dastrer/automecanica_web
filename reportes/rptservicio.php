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
  require('REPORTESL.php'); 
  date_default_timezone_set('America/La_Paz');


//Inlcuímos a la clase PDF_MC_Table
  $pdf=new PDF_MC_Table();
  $pdf->AliasNbPages();
  $pdf->AddPage("L", "Letter");
  $pdf->SetAutoPageBreak(true, 20);
  $pdf->SetTopMargin(15);
  $pdf->SetLeftMargin(10);
  $pdf->SetRightMargin(10);
  $pdf->SetX(10); // Posición horizontal
  $pdf->SetFont('Arial','B',10); // Fuente negrita
  $pdf->SetFillColor(222, 20, 45);
  $pdf->SetTextColor(255,255,255); // Texto en color negro
  $pdf->Cell(260, 6, utf8_decode('LISTA DE SERVICIO DE REPARACION'), 1, 1, 'C', 1); // Esta celda cubre todo el ancho (190 unidades)
  
  $pdf->SetX(10);
  $pdf->SetTextColor(0, 0, 0); // Texto en color negro
  $pdf->SetFillColor(205, 213, 192);
  $pdf->SetFont('Arial','B',10); // Reduzco el tamaño de la fuente
  $pdf->Cell(10,  7, utf8_decode('N°'),1,0,'C',1);
  $pdf->Cell(30,  7, utf8_decode('FECHA'),1,0,'C',1);
  $pdf->Cell(40,  7, utf8_decode('COMPROBANTE'),1,0,'C',1);
  $pdf->Cell(40,  7, utf8_decode('VEHICULO'),1,0,'C',1);
  $pdf->Cell(55,  7, utf8_decode('CLIENTE'),1,0,'C',1);
  $pdf->Cell(35,  7, utf8_decode('USUARIO'),1,0,'C',1);
  $pdf->Cell(30,  7, utf8_decode('ESTADO'),1,0,'C',1);
  $pdf->Cell(20,  7, utf8_decode('TOTAL'),1,1,'C',1);

   
  
  require_once "../modelos/Servicio.php";
  $servicio = new Servicio();
  
  // Ejecutar la consulta
  $rspta = $servicio->listar($_REQUEST["fecha_inicio"], $_REQUEST["fecha_fin"]);
//Table with rows and columns
  $pdf->SetWidths(array(10,30,40,40,55,35,30,20));
  $pdf->SetTextColor(0,0,0);
 
  // Generar filas dinámicamente con los datos de la consulta
  $index = 1; // Contador para el número de fila
  while($reg = $rspta->fetch_object()) { 
    
       // Insertar los datos de cada fila
      $Fecha = $reg->Fecha;
      $Documento = $reg->Tipo_comprobante . " " . $reg->Num_comprobante;
      $Cliente = $reg->Cliente;
      $Vehiculo =$reg->Placa . " " . $reg->Marca;
      $Usuario =$reg->Usuario;
      if ($reg->Estado == "En proceso") {
        $Estado = "En proceso";
    } elseif ($reg->Estado == "Anulado") {
        $Estado = "Anulado";
    } elseif ($reg->Estado == "Reparado") {
        $Estado = "Reparado";
    } else {
        $Estado = "Desconocido";
    }
      $Total =$reg->Total;
      $pdf->SetFont('Arial','',10);
      $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C')); 

      $pdf->Row(array($index,$Fecha,utf8_decode($Documento),utf8_decode($Vehiculo),
      utf8_decode($Cliente),utf8_decode($Usuario),utf8_decode($Estado),$Total));  
      $index++; // Incrementar el contador de filas
  }
  $pdf->Ln(0.5);
  
  // Mostrar el documento PDF
  $pdf->Output();
  
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