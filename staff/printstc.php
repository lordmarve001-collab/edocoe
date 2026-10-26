<?php 
session_start();
require('pdf/fpdf.php');
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
/*Session Variable*/
$matrics=$_SESSION['studentid'];
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
    $h=4*$nb;
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
$filename = $logo;
$name=$orgname.", ".$schadress;
$filepath = $filename;
 $pdf=new PDF_MC_Table();
 $pdf->AddPage();
$pdf->SetTitle($orgname);
student($matrics,$con);
$pdf->SetTitle($orgname);
$pdf->SetFont('Arial','B',12);
$pdf->Image($logo,90,5,20);
$pdf->Text(70, 30, $orgname.' , '.$schadress,'C'); 
$pdf->Text(75, 37, ucwords('Student Registered Courses'),'C'); 
$pdf->SetFont('Arial','B',12);
$pdf->Text(10,45,'Matric Number : '.strtoupper($matrics));
$pdf->Text(10,50,'Student Name  : '.ucwords($_POST["harrd"]));
$pdf->Text(10,55,'Programme      : '.$_POST["pro"]);
$pdf->Text(10,60,'Faculty             : '.$_POST["maks"]);
$pdf->Text(10,65,'Department      : '.$_POST["det"]);
$stp='../student/';
$p=$stp.$_POST["mak"];
$pdf->Image($p,152,40,$pdf->GetX()+30, $pdf->GetY()+20);
$pdf->Ln(60);
$pdf->Ln(1);
/*Start Here*/
try {
	$sqlc="SELECT DISTINCT acadsession,semester,level FROM result
     WHERE studentid='$matrics'";
       
   $sts = mysqli_query($con,$sqlc);
   $counter = mysqli_fetch_all($sts,MYSQLI_ASSOC);
   $Acounter=0;
   foreach($counter as $rowa)
   {
	 $Acounter+=1;
	 $ACA[$Acounter]     =$rowa['acadsession'];
	 $SMSTER[$Acounter]  =$rowa['semester'];
	 $Lev[$Acounter]  =$rowa['level'];
   
   } 
   $t=0;
 for($t=1;$t<=$Acounter; $t++)
 {
 $k=0;

	  $pdf->SetFont('helvetica', 'B', 9);
	  $pdf->Cell(31, 6, "Session:".$ACA[$t], 'LBTR', 0, 'L', 0);
	  $pdf->Cell(108, 6, "Level: ".$Lev[$t], 'LBTR', 0, 'L', 0);
	$pdf->Cell(45, 6, "Semester: ".$SMSTER[$t], 'LBTR', 0, 'L', 0);
	$pdf->Ln();
    $pdf->Cell(13, 6, "#", 'LBTR', 0, 'L', 0);
    $pdf->Cell(18, 6, "Code", 'LBTR', 0, 'L', 0);
    $pdf->Cell(108, 6, "Course Title", 'LBTR', 0, 'L', 0);
    $pdf->Cell(22, 6, "Units", 'LBTR', 0, 'C', 0);
    $pdf->Cell(23, 6, "Status", 'LBTR', 0, 'L', 0);
    $pdf->Ln();

      $sql="SELECT * FROM result  JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matrics' AND result.semester='$SMSTER[$t]' AND result.acadsession='$ACA[$t]' AND  result.level='$Lev[$t]' ORDER BY result.coursecode";
             
              $_POST['totalunit']=0;
              $_POST['tupt']=0;
              $_POST['stupt']=0;
               $st = mysqli_query($con,$sql);
                $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
           foreach($count as $row)
           {
            $k+=1;
              $_POST['totalunit']+=$row['unit'];
         $pdf->SetFont('helvetica', '', 10);
         $pdf->SetWidths(array(13,18,108,22,23));
         $pdf->Row(array($k,$row['coursecode'],$row['title'],$row['unit'],substr($row["custatus"], 0, 1)));
          //$k = $k + 1;

     }
     $pdf->SetFont('helvetica', 'B', 10);
      $pdf->Cell(139, 6, "Units Registered: ",'LBTR', 0, 'R', 0);
      $pdf->Cell(22, 6,$_POST['totalunit'], 'LBTR', 0, 'L', 0);
      $pdf->Cell(23, 6,'', 'LBTR', 0, 'L', 0);
      
      $pdf->Ln();
  }
} catch (Exception $e) {
	echo $e;
}
$pdf->Output();
}//Ending File
function student($mat,$con){
$sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$mat'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["maks"]="";
        $_POST["det"]="";
        $_POST["mak"]="photo/avater.png";
        $_POST["pro"]="";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $facu=$data['faculty'];
        $def=$data['department'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $progr=$data['programme'];
        $name =$las." , ".$first." ".$sur;
        $mak=$data['image'];
        
        $dep=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='$def'"));
        $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$facu'"));
        $_POST["harrd"]= $name;
        $_POST["maks"]= $fac['fname'];
        $_POST["det"]= $dep['dept'];
        $_POST["pro"]= $progr;
        $_POST["mak"]= $mak;
        }
}
?>