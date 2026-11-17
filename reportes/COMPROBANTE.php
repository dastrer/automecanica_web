<?php
// ────── CONFIGURACIÓN INICIAL ──────
date_default_timezone_set('America/La_Paz');

// Compatibilidad PHP 8.2+ para utf8_decode (FPDF no admite UTF-8 por defecto)
if (!function_exists('utf8_decode')) {
    function utf8_decode($text) {
        return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
    }
}
require('../fpdf181/fpdf.php');

class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;

function SetWidths($w)
{  
	//Set the array of column widths
	$this->widths=$w;
}
 
function SetAligns($a)
{ 
	//Set the array of column alignments
	$this->aligns=$a;
}
function Row($data)
{
	// Calcula la altura de la fila
	$nb = 0;
	for($i = 0; $i < count($data); $i++) {
		$nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
	}
	$h = 5 * $nb;
	
	// Verifica si se necesita un salto de página
	$this->CheckPageBreak($h);
	
	// Guarda la posición actual
	$x = $this->GetX();
	$y = $this->GetY();
	
	// Dibuja un rectángulo blanco como fondo para la fila
	$this->SetFillColor(255, 255, 255); // Blanco
	$this->Rect($x, $y, array_sum($this->widths), $h, 'F');
	
	// Dibuja las celdas de la fila
	for($i = 0; $i < count($data); $i++)
	{
		$w = $this->widths[$i];
		$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		// Guarda la posición actual
		$x = $this->GetX();
		$y = $this->GetY();
		// Dibuja el borde
		$this->Rect($x, $y, $w, $h);
		// Imprime el texto
		$this->MultiCell($w, 5, $data[$i], 0, $a);
		// Coloca la posición a la derecha de la celda
		$this->SetXY($x + $w, $y);
	}
	// Pasa a la siguiente línea
	$this->Ln($h);
}

function CheckPageBreak($h)
{ 
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation,'Letter');
}
  


function Header()
{
    $this->Image('img/logo.png',10,5,40,30);
	$this->SetY(10);
	$this->SetX(55);
	$this->SetFont('Arial','B',16);
	$this->Cell(89, 8, '"TALLER MECANICO AUTONIC"',0,1);
	$this->SetY(16);
	$this->SetX(55);
	$this->SetFont('Arial','',10);
	$this->Cell(40, 8, utf8_decode('El Alto Av. Bolivia Entrada Villa Adela Calle Nro. 16'));
	$this->SetY(22);
	$this->SetX(55);
	$this->SetFont('Arial','',10);
	$this->Cell(40, 8, utf8_decode('Telefono:74095226'));
	$this->SetY(28);
	$this->SetX(55);
	$this->SetFont('Arial','',10);
	$this->Cell(40, 8, utf8_decode('EL ALTO - LA PAZ'));
	$this->Ln(8);

}

function Footer()
{
	 $this->SetFont('helvetica', 'B', 8);
		$this->SetY(-15);
		$this->Cell(95,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
		$this->Cell(95,5,date('d/m/Y | g:i:a') ,00,1,'R');
		$this->Line(10,340,200,340);
		$this->Cell(0,5,utf8_decode("TALLER MECANICO - AUTONIC"),0,0,"C");
		
}  
function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
}
?>
