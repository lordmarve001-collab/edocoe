<?php 
require_once('fpdf.php');
//$fromat = array(3,5);
$pdf = new FPDF('p','in', [4.1,2.9]);
$pdf->SetTopMargin(50);
$pdf->Addpage();
$pdf->SetTitle("invoice");
$pdf->SetCreator("maqbool solutons");
$pdf->SetAuthor("my name");
$pdf->SetSubject("report");


$pdf->SetFont('Arial', 'B', '16');
$pdf->SetTextColor(155,14,9);// rgb
$pdf->SetDrawColor(155,14,9);
$pdf->SetfillColor(15,140,95);
$pdf->Cell(60,10, 'hello word');
$pdf->Cell(60,10,'powered by fpdf', 1, 0,'c',true);
$pdf->Cell(60,10,'powered by fpdf', 1, 2,'c');
$pdf->Cell(60,10,'powered by fpdf', 1, 1,'c');
//$pdf->Image("images/coat.jpg", 10,20,10,35);
$pdf->MultiCell(94,10,"skldjfsldfsfjsdkfsjdlfjsdflkjsdflksjflksjdflskjfslkjfdslkfdjslkfdjslkfjslkfjslkfjsflkjsflkjsflksjflksjfslkjfslkjslkf",1,"L",false);
$pdf->Output("I", "invice.pdf");
 ?>