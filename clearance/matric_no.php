<?php
require "pdf/fpdf.php";
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
if(!isset($_SESSION['staffid']))
    {   
    header("Location:logout"); 
    }
    else{ 
class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;
var $angle=0;
function Rotate($angle,$x=-1,$y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}
function _endpage()
{
    if($this->angle!=0)
    {
        $this->angle=0;
        $this->_out('Q');
    }
    parent::_endpage();
}
function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}
function SetWidth($w)
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
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'J';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
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
function RotatedText($x,$y,$txt,$angle)
{
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}
function RotatedImage($file,$x,$y,$w,$h,$angle)
{
    //Image rotated around its upper-left corner
    $this->Rotate($angle,$x,$y);
    $this->Image($file,$x,$y,$w,$h);
    $this->Rotate(0);
}
}

$pdf=new PDF_MC_Table();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetLeftMargin(23);
$pdf->SetTitle($orgname);
$pdf->Image($logo,80,6,35);
$pdf->SetFont('times','B',12);
//$pdf->Text(55, 27, strtoupper($orgname),'C'); 
//$pdf->Text(80, 33, strtoupper($schadress),'C'); 
$pdf->SetFont('times','B',12);
$pdf->Text(80,50, strtoupper('ADMISSION OFFICE'),'C');
$pdf->SetFont('times','B',8);
$pdf->Text(74,54, strtoupper('STUDENT MATRICULATION NUMBER'),'C');

$pdf->Ln(55);
$pdf->SetFont('helvetica','',11);
$pdf->Cell(45,6,"FULL NAME",1,0,'J');
$pdf->Cell(120,6,strtoupper($fullname), 1, 1);
$pdf->Cell(45,6,"PHONE",1,0,'J');
$pdf->Cell(120,6,$phone, 1, 1);
$pdf->Cell(45,6,"EMAIL",1,0,'J');
$pdf->Cell(120,6,strtoupper($email), 1, 1);
$pdf->Cell(45,6,"APPLICATION ID",1,0,'J');
$pdf->Cell(120,6,(strtoupper($_SESSION['staffid'])), 1, 1);
$pdf->Cell(45,6,"ADMISSION SESSION",1,0,'J');
$pdf->Cell(120,6,ucfirst($admy), 1, 1);
$pdf->Cell(45,6,"FACULTY",1,0,'J');
$pdf->Cell(120,6,strtoupper ($facu), 1, 1);
$pdf->Cell(45,6,"PROGRAMME",1,0,'J');
$pdf->Cell(120,6,strtoupper ($pro), 1, 1);

$pdf->Ln(25);
$pdf->SetFont('times','B');
$pdf->MultiCell(160,8,'MATRICULATION NO: '.$matr);
$pdf->Ln(25);
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(10,6,'ADMISSION OFFICER');
$pdf->Ln(12);
$pdf->SetFont('helvetica','',10);
$pdf->MultiCell(280,6,'Officer Name: .........................................................................','C');
$pdf->Ln(8);
$pdf->MultiCell(280,6,'Signature: .......................................... Date:............................','C');
$resh = trim(str_ireplace( array( '\'', '"',',',';','<','>','/','-','_',''), '', $_SESSION['staffid']));
$pdf->Output($resh.'_PrintOut.pdf',"I");
}
?>