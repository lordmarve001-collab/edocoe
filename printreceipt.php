<?php
session_start();
$connection = TRUE;
include 'include/dbconfig.php';
require "pdf/fpdf.php";

$reft=$_SESSION['payre'];
$feedback=$_SESSION['feedback'];
$data=mysqli_fetch_assoc(mysqli_query($con, "SELECT studentid,amount, paymentreference,purpose FROM studentpayment WHERE paymentreference = '$reft'"));
if ($data !=null) {
$_POST["appliD"]=$data["studentid"];
$_POST["amon"]=$data["amount"];
$_POST["refe"]=$data["paymentreference"];
$_POST["pp"]=$data["purpose"];
}else{
    header("location: index");
}

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM transapp WHERE studentid='".$_POST["appliD"]."'"));
$fullname=$dat["fullname"];
$_POST["dep"]=$dat["dep"];
$_POST["phone"]=$dat["phone"];
$_POST["Email"]=$dat["email"];
$stu=$dat["studentid"];
$type=$dat["type"];
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
$reft=$_SESSION['payre'];
//echo $reft;
$pdf->SetTitle($orgname);
$pdf->Image($logos,85,5,$pdf->GetX()+15, $pdf->GetY()+15);
$pdf->SetFont('Arial','B',12);
$pdf->Text(60, 35, strtoupper($orgname),'C'); 
$pdf->Text(80, 40, strtoupper($schadress.', '.$stat.' State'),'C'); 
$pdf->SetFont('Arial','B',8);
$pdf->Text(77,45, strtoupper('Official Receipt of Payment'),'C');
date_default_timezone_set("africa/lagos");
$pdf->Text(138,50,"Created: ".date("D,d M Y ")."By ". date("h:i:sa"),'R');

$pdf->Ln(45);
$pdf->SetFont('times','B',16);
$pdf->Cell(45,10,"Personal Information",0,1,'J');
$pdf->SetFont('times','',14);
$pdf->Cell(120,10,"Full Name: ".ucfirst($fullname), 0, 1);
$pdf->Cell(120,10,"Phone: ".$_POST["phone"], 0, 1);
$pdf->Cell(120,10,"Email: ".$_POST["Email"], 0, 1);
$pdf->Cell(120,10,"Programme: ".$_POST["dep"], 0, 1);
$pdf->SetFont('times','B',16);
$pdf->Cell(120,10,"Payment Information", 0, 1);
$pdf->SetFont('times','',14);
$pdf->Cell(120,10,"Amount Paid: #".number_format($_POST["amon"]), 0, 1);
$pdf->Cell(120,10,"Payment Purpose: ".$_POST["pp"], 0, 1);
$pdf->Cell(120,10,"Reference: ".$reft, 0, 1);
$pdf->Cell(120,10,"Mat No: ".$stu, 0, 1);
$pdf->Cell(120,10,"Payment Status: ".$feedback, 0, 1);
$pdf->Output('Receipt_PrintOut.pdf',"I");
?>