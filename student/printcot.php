<?php
require "pdf/fpdf.php";
session_start();

$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout.php"); 
    }
    else{ 
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout.php");
}
$sems=$_SESSION['sems'];
$lv=$_SESSION['lv'];
$ses= $_SESSION['ses'];
$mater=$sems ." ".$ses;

$filename = $logo;
$name=$orgname.", ".$schadress;
$filepath = $filename;
$yr = substr($ses, -4);
$pname=$progr.'_'.$yr.'_'.$sems."_".$lv;

//if ($fac=="") {
  $daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT fid FROM program WHERE pname='$progr' "));
  $faclt=$daft['fid'];
$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$faclt' "));
    $fac = $factn['fname'];      
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

          stuname($con,$_SESSION['staffid']);
          $stp='../student/';
          $p=$stp.$_POST["mak"];
          $pdf->AddPage();
          $pdf->SetFont('Arial','B',22); 
          $pdf->SetTitle($orgname);
          $pdf->Image($logo,10,5,40);
          $pdf->Text(50, 18, $orgname.' , '.$schadress,'L'); 
          $pdf->SetFont('Arial','B',16);
          //$pdf->Cell(40,10,$value);
          $pdf->Text(60,26,'Student Course Registration Print-Out');
          $pdf->Text(50,34,$mater.' Academic Session');
          $pdf->Ln(25);
          $pdf->Cell(0,16,'_________________________________________________________');
          $pdf->Ln(15);
          $pdf->SetFont('Arial','B',12);
          $pdf->Text(10,52,'Student Name : '.ucwords($_POST["harrd"]));
          $pdf->Text(10,62,'Student ID : '.$_POST["maks"]);
          $pdf->SetFont('Arial','B',12);
          $pdf->Text(10,70,'Email : '.$_POST['emai']);
          $pdf->SetFont('Arial','B',12);
          $pdf->Text(10,80,'Faculty : '.$fac);
          $pdf->Text(10,90,'Programme: '.$progr);
          $pdf->Text(10,100,'Level: '.$lv);
          $pdf->Image($p,140,50,$pdf->GetX()+40, $pdf->GetY());
          $pdf->Ln(60);
          $pdf->SetFont('Arial', '', 14);
          $width_cell=array(10,18,126,10,15);
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell($width_cell[0],10,'#',1,0); // First header column 
          $pdf->Cell($width_cell[1],10,'Code',1,0); // Second header column
          $pdf->Cell($width_cell[2],10,'Course Title',1,0); // Third header column 
          $pdf->Cell($width_cell[3],10,'Unit',1,0); // Fourth header column
          $pdf->Cell($width_cell[4],10,'Status',1,1); // Fourth header column
          $pdf->SetFont('helvetica', '', 11);
          $pdf->SetWidths(array(10,18,126,10,15));
          $sql="SELECT DISTINCT course.title,course.unit,result.coursecode,result.custatus FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='".$_SESSION['staffid']."' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses'  ORDER BY result.coursecode";  
          $query = mysqli_query($con, $sql);
          $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
          $cnt = 1;

          foreach ($results as $result)
          { 
          $pdf->Row(array($cnt,$result['coursecode'],$result['title'],$result['unit'],substr($result["custatus"], 0, 1)));
          $cnt = $cnt + 1;
          }
         GetUnit($con,$_SESSION['staffid'],$lv,$sems,$ses);

         $pdf->Cell(154,6,'Total Credit Units', 'LBTR', 0, 'R', 0);
         $pdf->Cell(10, 6, $_POST['totalUnit'], 'LBTR', 0, 'C', 0);
         $pdf->Cell(15, 6, "", 'LBTR', 0, 'L', 0);
         $pdf->Ln();
         $dn="Dean's";
         $hn="HoD's";
         $pdf->ln();
         $pdf->SetFont('helvetica', 'B', 14);
         $pdf->Cell(3,6,'__________________________',0,0);
         $pdf->Cell(3,16,'Student Signature & Date',3,1); 
         
         $pdf->Cell(3,6,'__________________________');
         $pdf->Cell(3,16,$hn.' Signature & Date',3,1);
         $pdf->Cell(3,6,'__________________________');
         $pdf->Cell(3,16,$dn.' Signature & Date',3,1);
         $pdf->Output($yr.'_'.$lv.'_'.$sems.'_'.$progr.'_CourseRegistration_PrintOut.pdf',"I");
}
function stuname($con,$maxt)
{
  
      $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
         $_POST["mak"]="photo/avater.png";
         $_POST["maks"]="Nil";
         $_POST['emai']="Nil";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $mak=$data['image'];
        $maks=$data['studentid'];
        $_POST['emai']=$data['uniemail'];
        $_POST['pro']=$data['programme'];
        //$name =$las.", ".$first;
        $name =$las.", ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["mak"]= $mak;
        }
}
function GetUnit($con,$matt,$lv,$sems,$ses)
{

        $queryy="SELECT SUM(unit) AS sumunit FROM result  JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matt' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses' ORDER BY result.coursecode";
          $resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
                $_POST['totalUnit']=$row['sumunit'];       
            }
}
?>