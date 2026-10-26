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
$secc=$_SESSION['section'];
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
$pdf->Text(90, 36, strtoupper($orgname),'C');


$pdf->SetFont('Arial','B',12);
$pdf->Text(80, 42, 'PDE COMPLATE LIST OF ADMITTED STUDENT FOR '.$secc,'C'); 
$pdf->Ln(40);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetLeftMargin(15);
$pdf->Cell(8, 6, "#", 'LBTR', 0, 'L', 0);
$pdf->Cell(45, 6, "Application ID", 'LBTR', 0, 'L', 0);
$pdf->Cell(30, 6, "Surname", 'LBTR', 0, 'L', 0);
$pdf->Cell(30, 6, "First Name", 'LBTR', 0, 'L', 0);
$pdf->Cell(30, 6, "Other Name", 'LBTR', 0, 'L', 0);
$pdf->Cell(25, 6, "Phone No", 'LBTR', 0, 'L', 0);
$pdf->Cell(20, 6, "Sex", 'LBTR', 0, 'L', 0);
$pdf->Cell(60, 6, "Study Center", 'LBTR', 0, 'L', 0);
$pdf->Cell(25, 6, "Govt. Prog.", 'LBTR', 0, 'L', 0);
//$pdf->Cell(20, 6, "Year ", 'LBTR', 0, 'L', 0);
$pdf->Ln();
//$sql = "SELECT * FROM personaldata WHERE personaldata.acadsession='$secc' AND personaldata.status='active' ORDER BY personaldata.studentid AND personaldata.programme";
$sql = "SELECT * FROM studdata WHERE acadsession='$secc' AND apptype='PDE'";                            
  $query = mysqli_query($con,$sql);
  $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
  $cnt=1;
  
  foreach($results as $result)
  {
    stuname($con,$result['admno']);
    $govt=$result['govt'];
                                if ($govt=='EDO STATE UNIVERSAL BASIC EDUCATION BOARD (SUBEB)'){$gov='SUBEB';}
                                if ($govt=='EDO STATE SECONDARY EDUCATION BOARD (SSEB)'){$gov='SSEB';}
                                if ($govt==''){$gov='NONE';}
$pdf->SetFont('helvetica', 'B',10);
//$pdf->SetWidths(array(8,40,70,50,20));
$pdf->SetWidths(array(8,45,30,30,30,25,20,60,25));
$pdf->Row(array($cnt,$result['admno'],strtoupper($result['surname']),strtoupper($result['firstname']),strtoupper($result['lastname']),strtoupper($result['phone']),strtoupper($result['gender']),$_POST["harrds"],$gov));
$cnt=$cnt+1;
                             
  }
} catch (Exception $e) {
  echo $e;
}
$pdf->Output();


}


 function stuname($con,$maxt)
 {


    $sql =mysqli_query($con,"SELECT *  FROM  studdata WHERE admno='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $center = $data['center'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $_POST["harrds"]= $center;
        $_POST["mat"]= $data['admno'];
        $name =strtoupper($sur)." ".strtolower($first." ".$las);
        $_POST["harrd"]= $name;
        
        }

 }
 
?>