<?php 
require('pdf/fpdf.php');
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
 if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout"); 
    }
    else{ 
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
 //$pdf=new FPDF('L');
 $pdf=new FPDF();
//Sessional Variable
$ccodt=$_SESSION['date'];
$ccode=$_SESSION['dates'];
$payp=$_SESSION['payp'];
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
    // Ensure $data is an array
    if (!is_array($data)) {
        $data = [$data];
    }

    $nb = 0;
    for ($i = 0; $i < count($data); $i++)
        $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));

    $h = 5 * $nb;
    $this->CheckPageBreak($h);

    for ($i = 0; $i < count($data); $i++) {
        $w = $this->widths[$i];
        $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'J';
        $x = $this->GetX();
        $y = $this->GetY();
        $this->Rect($x, $y, $w, $h);
        $this->MultiCell($w, 5, $data[$i], 0, $a);
        $this->SetXY($x + $w, $y);
    }

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

function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    //$this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

}
try {
$pdf=new PDF_MC_Table('L');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
//$pdf->Image($logo,10,5,40);
$pdf->Image($logo,130,5,$pdf->GetX()+15, $pdf->GetY()+15);
$pdf->SetFont('Arial','B',16); 
$pdf->Text(90, 36, strtoupper($orgname),'C' );


$pdf->SetFont('Arial','B',12);
$pdf->Text(100, 42, ''.$payp.' FEES REPORT BETWEEN '.$ccode.' AND '.$ccodt,' '); 
$pdf->Ln(40);
$pdf->SetFont('helvetica', 'B', 15);
$pdf->SetLeftMargin(15);
$pdf->Cell(8, 6, "#", 'LBTR', 0, 'L', 0);
$pdf->Cell(45, 6, "Student ID", 'LBTR', 0, 'L', 0);
$pdf->Cell(70, 6, "Full Name", 'LBTR', 0, 'L', 0);
$pdf->Cell(60, 6, "Reference", 'LBTR', 0, 'L', 0);
$pdf->Cell(30, 6, "Session", 'LBTR', 0, 'L', 0);
$pdf->Cell(20, 6, "Amount", 'LBTR', 0, 'L', 0);
$pdf->Cell(40, 6, "Payment Date", 'LBTR', 0, 'L', 0);
$pdf->row(20, 1, "Total ", 'LBTR', 0, 'L', 0);
$pdf->Ln();

//$sql = "SELECT * FROM personaldata WHERE personaldata.acadsession='$secc' AND personaldata.status='active' ORDER BY personaldata.studentid AND personaldata.programme";

$sql="SELECT * FROM studentpayment WHERE  paytime BETWEEN '$ccode' AND '$ccodt'  AND purpose='$payp' ORDER BY studentid ASC";
  $query = mysqli_query($con,$sql);
$_POST['totalunit']=0;
  $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
  $cnt=1;
  
  foreach($results as $result)
  {
    stuname($con,$result['studentid']);
$pdf->SetFont('helvetica', 'B',10);
//$pdf->SetWidths(array(8,40,70,50,20));
$pdf->SetWidths(array(8,45,70,60,30,20,40));
$pdf->Row(array($cnt,$result['studentid'],ucwords (strtoupper($_POST["harrd"])),$result['paymentreference'],$result['section'],$result['amount'],$result['paytime']));
$cnt=$cnt+1;
                             
  }
                                                  gettotal($ccode,$ccodt,$payp,$con);
$total='Total';
  $pdf->SetWidths(array(50));
$pdf->Row(array($total, (number_format($_POST['amount'],2))));

} catch (Exception $e) {
  echo $e;
}
$pdf->Output();


}


 function stuname($con,$maxt)
 {
        $sql =mysqli_query($con,"SELECT *  FROM  applicant WHERE applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
     
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $name =$sur." ".$las." ".$first;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
               if ($data=="") {
    $sqlt =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $datat = mysqli_fetch_assoc($sqlt);
      if ($datat=="") {
        $_POST["harrd"]=" No Namess";
      }else{
        $first = $datat['firstname'];
        $sur = $datat['othername'];
        $las=$datat['lastname'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
        }
    }
    

 }
  function gettotal($prg,$le,$payp,$con){
    $sum=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(amount) As amount FROM studentpayment WHERE paytime BETWEEN '$prg' AND '$le'  AND purpose='$payp'"));
    $_POST['amount']=$sum['amount'];
 };

?>