<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
require "pdf/fpdf.php";
$appli=$_SESSION['appli'];
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: logout"); 
    }
    else{
            $progr='PDE. PROFESSIONAL DIPLOMA IN EDUCATION';
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studdata WHERE admno='$appli'"));
        $mak=$dat['passport'];
$_POST["fname"]=$dat["firstname"];
$_POST["sname"]=$dat["lastname"];
$_POST["surname"]=$dat["surname"];
$prog=$dat["programme"];
$_POST["phone"]=$dat["phoneno"];
$_POST["Email"]=$dat["email"];
$_POST["mak"]= $mak;
$ad=$dat["applid"];
$fullname=ucfirst($_POST["surname"].' '.$_POST["fname"].' '.$_POST["sname"]);
$yrk=substr($appli,10,18);
$pro=substr($prog,4,53);
$data=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pname='$prog'"));
$pid=$data["pid"];
$data1=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studdata WHERE admno='$appli'"));
$date1=$data1["graduate_date"];
$date = date("d F Y", strtotime($date1));
                        GetCumulative($con,$progr,$appli);

 $rmark='DISTINCTION';
				if($_POST['PGP']<4.4){$rmark='CREDIT';}
				if($_POST['PGP']<2.45){$rmark='MERIT';}
				if($_POST['PGP']<2.0){$rmark='PASS';}
				

class PDF extends FPDF 
{ 
function RotatedImage($file,$x,$y,$w,$h,$angle)
{
    //Image rotated around its upper-left corner
    $this->Rotate($angle,$x,$y);
    $this->Image($file,$x,$y,$w,$h);
    $this->Rotate(0);
}
function Footer() 
{ 

$this->SetY(-27); 
$this->SetFont('Arial','I',8); 

$this->Cell(0,10,'This certificate has been ©  © produced by thetutor',0,0,'R'); 
} 
} 

$pdf = new FPDF('L','pt',array(210, 300));

//Loading data 

$pdf->AddPage('P', 'A4');
$mid_x = $pdf->GetPageWidth() / 2;
$p=$_POST["mak"]; 

//  Print the edge of
$pdf->Image("college.png", 0, 0, 595); 
// Print the certificate logo  
// Print the title of the certificate 
$pdf->SetFont('times','B',80); 


$pdf->SetFont('Arial','B',24);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(25,195); 
$pdf->Text($mid_x - ($pdf->GetStringWidth($fullname) / 2), 360, strtoupper($fullname));


$pdf->SetFont('times','B',14); 
$pdf->SetXY(445,120);
$pdf->Cell(80,12,$yrk,0,'C',0); 

$pdf->SetFont('times','B','I',16); 
$pdf->SetXY(240,568);
$pdf->Cell(80,12,$rmark,0,'C',0);

$pdf->SetFont('times','I',14); 
$pdf->SetXY(276,685);
$pdf->Cell(80,12,$date,0,'C',0);

$pdf->Output(); 
}
function GetGradeSystem($con)
        {
     $gradeCounter=0;
     $sqlper = "SELECT  * FROM gradetable";
      $result = mysqli_query($con,$sqlper);
     $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
     //$rowks=mysqli_num_rows($result);
     foreach($data as $row){
        $gradeCounter+=1;
        $_POST['Minscore'][$gradeCounter] =  $row["minscore"];
        $_POST['Maxscore'][$gradeCounter] =  $row["maxscore"];
        $_POST['Grade'][$gradeCounter]    =  $row["grade"];
        $_POST['point'][$gradeCounter]    =  $row["point"];
       }
        $_SESSION['gradeCounter']=$gradeCounter;
//header("location:404");
 }
function GetCumulative($con,$prog,$std)
{
    $scoresCounterc=0;
$_POST['pointsc']=0;
$_POST['totalUnitc']=0;
  $harr= "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE studentid='$std' AND result.programme='$prog'AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
  foreach($resulter as $row){
     $scoresCounterc+=1;
     $_POST['Unitc'][$scoresCounterc]=$row['unit'];
     $_POST['Codec'][$scoresCounterc]=$row['coursecode'];
     $_POST['Scoresc'][$scoresCounterc]  =(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounterc']=$scoresCounterc; 

  //Compute CUMULATIVE
  GetGradeSystem($con);// Graing System
                        for($k=1; $k<=$_SESSION['scoresCounterc']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scoresc'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scoresc'][$k] >= $_POST['Minscore'][$j])
                                 {
                                    $_POST['Gc'][$k] = $_POST['Grade'][$j];
                                    $_POST['Pc'][$k] = $_POST['point'][$j];
                                    $_POST['pointsc']+=$_POST['point'][$j] * (int)$_POST['Unitc'][$k];
                                   
                                 }
                                $_POST['totalUnitc']+=(int)$_POST['Unitc'][$k];
                          }

                          
                          if($_POST['pointsc']!=0 && $_POST['totalUnitc']!=0)
                              {
                               $_POST['PGP']=number_format(($_POST['pointsc']/$_POST['totalUnitc']),2);//computer previous GP
                              } 
                              else if($_POST['pointsc']==0 && $_POST['totalUnitc']==0){
                                 $_POST['PGP']="0.00";
                              } 
    }
   
?> 