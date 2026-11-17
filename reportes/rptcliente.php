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
  require('REPORTES.php');
  date_default_timezone_set('America/La_Paz');
//Inlcuímos a la clase PDF_MC_Table

  $pdf=new PDF_MC_Table();
  $pdf->AliasNbPages();
  $pdf->AddPage("P", "Letter");
  $pdf->SetAutoPageBreak(true, 20);
  $pdf->SetTopMargin(15);
  $pdf->SetLeftMargin(10);
  $pdf->SetRightMargin(10);
  $pdf->SetX(10); // Posición horizontal
  $pdf->SetFont('Arial','B',10); // Fuente negrita
  $pdf->SetFillColor(222, 20, 45);
  $pdf->SetTextColor(255,255,255); // Texto en color negro
  $pdf->Cell(195, 6, utf8_decode('LISTA DE CLIENTES'), 1, 1, 'C', 1); // Esta celda cubre todo el ancho (190 unidades)
  
  $pdf->SetX(10);
  $pdf->SetTextColor(0, 0, 0); // Texto en color negro
  $pdf->SetFillColor(205, 213, 192);
  $pdf->SetFont('Arial','B',10); // Reduzco el tamaño de la fuente 
  $pdf->Cell(10, 7, utf8_decode('N°'),1,0,'C',1);
  $pdf->Cell(60, 7, utf8_decode('NOMBRE'),1,0,'C',1);
  $pdf->Cell(45, 7, utf8_decode('DOCUMENTO'),1,0,'C',1);
  $pdf->Cell(45, 7, utf8_decode('NUMERO'),1,0,'C',1);
  $pdf->Cell(35, 7, utf8_decode('TELEFONO'),1,1,'C',1);


   
  
  require_once "../modelos/Cliente.php";
  $cliente = new Cliente();
  
  // Ejecutar la consulta
  $rspta = $cliente->listar();
//Table with rows and columns
  $pdf->SetWidths(array(10,60,45,45,35));
  $pdf->SetTextColor(0,0,0);
  
  // Generar filas dinámicamente con los datos de la consulta
  $index = 1; // Contador para el número de fila
  while($reg = $rspta->fetch_object()) {
    
       // Insertar los datos de cada fila
       $nombrecompleto = $reg->Nombres;
       $numero =$reg->Num_documento;
       $tipodocumento = $reg->Tipo_documento;
       $Telefono =$reg->Telefono;


      $pdf->SetFont('Arial','',10);
      $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C')); 
      $pdf->Row(array($index,utf8_decode($nombrecompleto),$tipodocumento,$numero,
      $Telefono));  
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