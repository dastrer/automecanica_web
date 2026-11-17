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
if ($_SESSION['Venta']==1)
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
  $pdf->Cell(260, 6, utf8_decode('LISTA DE VEHICULOS'), 1, 1, 'C', 1); // Esta celda cubre todo el ancho (190 unidades)
  
  $pdf->SetX(10);
  $pdf->SetTextColor(0, 0, 0); // Texto en color negro
  $pdf->SetFillColor(205, 213, 192);
  $pdf->SetFont('Arial','B',10); // Reduzco el tamaño de la fuente
  $pdf->Cell(10,  7, utf8_decode('N°'),1,0,'C',1);
  $pdf->Cell(30,  7, utf8_decode('FECHA'),1,0,'C',1);
  $pdf->Cell(40,  7, utf8_decode('CLIENTE'),1,0,'C',1);
  $pdf->Cell(35,  7, utf8_decode('DOCUMENTO'),1,0,'C',1);
  $pdf->Cell(35,  7, utf8_decode('MARCA'),1,0,'C',1);
  $pdf->Cell(35,  7, utf8_decode('PLACA'),1,0,'C',1);
  $pdf->Cell(30,  7, utf8_decode('COLOR'),1,0,'C',1);
  $pdf->SetFont('Arial','B',7); 
  $pdf->Cell(25,  7, utf8_decode('TIPO DE VEHICULO'),1,0,'C',1);
  $pdf->SetFont('Arial','B',10); // Fuente negrita
  $pdf->Cell(20,  7, utf8_decode('ESTADO'),1,1,'C',1);

   
  
  require_once "../modelos/Vehiculo.php";
  $vehiculo = new Vehiculo();
  
  // Ejecutar la consulta
  $rspta = $vehiculo->listar();
//Table with rows and columns
  $pdf->SetWidths(array(10,30,40,35,35,35,30,25,20,20));
  $pdf->SetTextColor(0,0,0);
  
  // Generar filas dinámicamente con los datos de la consulta
  $index = 1; // Contador para el número de fila
  while($reg = $rspta->fetch_object()) { 
    
       // Insertar los datos de cada fila
      $Fecha = $reg->Fecha;
      $Cliente = $reg->Nombres;
      $Documento = $reg->Tipo_documento . " " . $reg->Num_documento;
      $Marca = $reg->Marca;
      $Placa =$reg->Placa;
      $Color =$reg->Color;
      $Tipo =$reg->Tipo;
      $Estado = ($reg->Estado == 1) ? "Activo" : "Inactivo";
      $pdf->SetFont('Arial','',10);
      $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C')); 

      $pdf->Row(array($index,$Fecha,utf8_decode($Cliente),utf8_decode($Documento),
      utf8_decode($Marca),utf8_decode($Placa),utf8_decode($Color),utf8_decode($Tipo),$Estado));  
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