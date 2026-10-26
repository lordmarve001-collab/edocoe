<?php
require "pdf/fpdf.php";
session_start();

$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
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


$filename = $logo;
$name=$orgname.", ".$schadress;
$filepath = $filename;
$pname=$firstname.'_Payment_Summary';
    
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
}
 $pdf=new PDF_MC_Table();
$stu=$_SESSION['staff'];
          stuname($con,$stu);
          $p=$_POST["mak"];
          $pdf->AddPage();
          $pdf->SetFont('Arial','B',22); 
          $pdf->SetTitle($orgname);
          /*$pdf->Image($logo,10,5,40);*/
          $pdf->Image($logo,98,5,$pdf->GetX()+15, $pdf->GetY()+15);
          $pdf->Text(50, 38, $orgname,'L'); 
          $pdf->SetFont('Arial','B',16);
          $pdf->Ln(25);
          $pdf->Cell(0,16,'____________________________________________________________');
          $pdf->Ln(15);
          $pdf->SetFont('Arial','B',12);
          $pdf->Text(10,52,'Student Name : '.ucwords($_POST["harrd"]));
          $pdf->Text(10,62,'Student ID : '.$_POST["maks"]);
          $pdf->SetFont('Arial','B',12);
          $pdf->Text(10,70,'Email : '.$_POST['emai']);
          $pdf->SetFont('Arial','B',12);
          $pdf->Text(10,80,'College Of : '.$facu);
          $pdf->Text(10,90,'Department: '.$def);
          $pdf->Text(10,100,'Programme: '.$progr);
          $pdf->Image($p,155,51,$pdf->GetX()+38, $pdf->GetY());
          $pdf->Ln(65);
          $pdf->Text(60,108,'YOUR FEES PAYMENT DETAILS');
          $pdf->SetFont('Arial', '', 14);
          $width_cell=array(10,20,26,20,20,22,37,21,17);
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell($width_cell[0],10,'#',1,0); // First header column 
          $pdf->Cell($width_cell[1],10,'Session',1,0); // Second header column
          $pdf->Cell($width_cell[2],10,'Amount Paid',1,0); // Third header column 
          $pdf->Cell($width_cell[3],10,'Level',1,0); // Fourth header column
          $pdf->Cell($width_cell[4],10,'Semester',1,0); // Fourth header column
          $pdf->Cell($width_cell[5],10,'Purpose',1,0); // Fourth header column
          $pdf->Cell($width_cell[6],10,'Reference',1,0); // Fourth header column
          $pdf->Cell($width_cell[7],10,'Date',1,0); // Fourth header column
          $pdf->Cell($width_cell[8],10,'Status',1,1); // Fourth header column
          $pdf->SetFont('helvetica', '', 10);
          $pdf->SetWidths(array(10,20,26,20,20,22,37,21,17));
         
          $sql="SELECT * FROM studentpayment WHERE (studentid='".$_SESSION['staffid']."' OR studentid='$admids') ORDER BY level ";  
          $query = mysqli_query($con, $sql);
          $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
          $cnt = 1;
         
          foreach ($results as $result)
          { 
          $pdf->Row(array($cnt,$result['section'],'#'.number_format($result['amount'],2),$result['level'],$result["semester"],$result['purpose'],$result['paymentreference'],$result['paytime'],$result['status']));
          $cnt = $cnt + 1;
          }
         $queryy="SELECT SUM(amount) AS sumunit FROM studentpayment WHERE  (studentid='".$_SESSION['staffid']."' OR studentid='$admids') ";
        $resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
              $_POST['total']=$row['sumunit'];
                
            }
         $pdf->Cell(30,6,'Total:', 'LBTR', 0, 'L', 0);
         $pdf->Cell(46, 6,number_format($_POST['total'],2) , 'LBTR', 0, 'L', 0);
         $pdf->Cell(20, 6, "", 'LBTR', 0, 'L', 0);
         $pdf->Cell(22, 6, "", 'LBTR', 0, 'L', 0);
         $pdf->Cell(37, 6, "", 'LBTR', 0, 'L', 0);
         $pdf->Cell(21, 6, "", 'LBTR', 0, 'L', 0);
         $pdf->Cell(17, 6, "", 'LBTR', 0, 'L', 0);
          $pdf->Ln();
        $pdf->Ln(5);
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(10,6,'BURSARY');
$pdf->Ln(5);
$pdf->SetFont('helvetica','',10);
$pdf->MultiCell(280,6,'Officer Name: .........................................................................','C');
$pdf->Ln(3);
$pdf->MultiCell(280,6,'Signature: .......................................... Date:............................','C');
         $pdf->Output($pname,"I");
}
function stuname($con,$maxt)
{
  $sql =mysqli_query($con,"SELECT  *  FROM  student WHERE studentid='$maxt' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $datak=mysqli_fetch_assoc(mysqli_query($con,"SELECT  *  FROM  personaldata WHERE (applid='$maxt' OR studentid='$maxt') "));
    if ($datak=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first = $datak['firstname'];
        $sur = $datak['surname'];
        $las=$datak['lastname'];
        $prp=$datak['programme'];
        $maks="../apply/".$datak['passport'];
        $name =strtoupper($sur)." , ".$first." ".$las;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["prp"]= $prp;
        $facu=$datak['faculty'];
        $def=$datak['department'];
        $dep=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='$def'"));
        $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$facu'"));
        $_POST["makst"]= $fac['fname'];
        $_POST["det"]= $dep['dept'];
        }
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $prp=$data['programme'];
        $maks="../student/".$data['image'];
        $name =strtoupper($las)." , ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["prp"]= $prp;
        $facu=$data['faculty'];
        $def=$data['department'];
        $dep=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='$def'"));
        $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$facu'"));
        $_POST["makst"]= $fac['fname'];
        $_POST["det"]= $dep['dept'];
        }

 
}
function GetUnit($matt,$lv,$sems,$ses,$con)
{

        $queryy="SELECT SUM(unit) AS sumunit FROM result  JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matt' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses' ORDER BY result.coursecode";
          $resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
                $_POST['totalUnit']=$row['sumunit'];       
            }
}
?>