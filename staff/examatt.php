<?php 
require('pdf/fpdf.php');
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
class PDF extends FPDF
{
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
$ccode=$_SESSION['courseallo'];
/*For Admin And ICT SIDE*/
$semst=$_SESSION['semester'];
$sect =$_SESSION['section'];
 //$pdf=new FPDF('L');
 $pdf=new PDF();

$pdf->AliasNbPages();

$paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
  if ($paych['position']=='hod' OR $paych['position']=='lecturer' OR $paych['position']=='dean') {
    try {
  
 $sqlc="SELECT DISTINCT programme FROM result WHERE coursecode='$ccode' AND acadsession='$sect' AND semester='$sect'";
   
   $sts = mysqli_query($con,$sqlc);
        $counter = mysqli_fetch_all($sts,MYSQLI_ASSOC);
   $Acounter=0;
   foreach($counter as $rowa)
   {
     $Acounter+=1;
     $programe[$Acounter]  =$rowa['programme'];
     //$Lev[$Acounter]  =$rowa['level'];
   }
   $t=0;
        for($t=1;$t<=$Acounter; $t++)
    {
$pdf->AddPage();
$pdf->SetTitle($orgname);

$pdf->Image($logo,90,5,$pdf->GetX()+15, $pdf->GetY()+15);
$pdf->SetFont('Arial','B',12); 
$pdf->Text(65, 36, strtoupper($orgname.' , '.$schadress),'C');
 $pdf->SetFont('Arial','B',10);
$pdf->Text(73, 42, 'EXAMINATION ATTENDANCE SHEET','C'); 

$pps=mysqli_fetch_assoc(mysqli_query($con,"SELECT title,unit FROM course WHERE coursecode='$ccode'"));
$tit=$pps['title'];
$un=$pps['unit'];

;
$pdf->SetFont('Arial','B',8); 
$pdf->Text(15,47,strtoupper('SESSION: '.$sect.'    PROGRAMME: '.$programe[$t].'              SEMESTER: '.$semst),'C');
$pdf->Text(55,52,strtoupper('Course Code: '.$ccode.'               Course Unit: '.$un),'C');
$pdf->Text(15,58,strtoupper(' Course Title: '.$tit),'C');
$pdf->Ln(50);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(10, 6, "#", 'LBTR', 0, 'L', 0);
$pdf->Cell(40, 6, "StudentID", 'LBTR', 0, 'L', 0);
$pdf->Cell(70, 6, "Name", 'LBTR', 0, 'L', 0);
$pdf->Cell(20, 6, "Booklet No", 'LBTR', 0, 'C', 0);
$pdf->Cell(15, 6, "Sign-In", 'LBTR', 0, 'L', 0);
$pdf->Cell(17, 6, "Sign-Out", 'LBTR', 0, 'L', 0);
$pdf->Cell(20, 6, "Date", 'LBTR', 0, 'L', 0);
$pdf->Ln();
/*New For Loop Query*/
$sqlcl="SELECT DISTINCT level FROM result WHERE coursecode='$ccode' AND acadsession='$sect' AND programme='$programe[$t]' AND semester='$semst' ORDER BY level";
                                   
 $stsl = mysqli_query($con,$sqlcl);
      $counterl = mysqli_fetch_all($stsl,MYSQLI_ASSOC);
 $Acounterl=0;
 foreach($counterl as $rowal)
 {
   $Acounterl+=1;
   //$programe[$Acounterl]  =$rowa['programme'];
   $Lev[$Acounterl]  =$rowal['level'];
 }
  $tk=0;
    for($tk=1;$tk<=$Acounterl; $tk++)
    {
      $pdf->Cell(192, 6, "Level: ".$Lev[$tk], 'LBTR', 0, 'L', 0);
      $pdf->Ln();
      /*Foreach Loop to Display the Result*/
      $k=0;
      $sql="SELECT * FROM result WHERE acadsession='$sect' AND coursecode='$ccode' AND level='$Lev[$tk]' AND programme='$programe[$t]' AND semester='$semst' ORDER BY level ASC";

        $_POST['totalunit']=0;
         $st = mysqli_query($con,$sql);
          $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
     foreach($count as $row)
     {
      $k+=1;
        
       stuname($con,$row['studentid']);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(10, 6, $k, 'LBTR', 0, 'L', 0);
        $pdf->Cell(40, 6, $row['studentid'], 'LBTR', 0, 'L', 0);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(70, 6, $_POST["harrd"], 'LBTR', 0, 'L', 0);
        $pdf->Cell(20, 6, '', 'LBTR', 0, 'C', 0);
        $pdf->Cell(15, 6, '', 'LBTR', 0, 'L', 0);
        $pdf->Cell(17, 6, '', 'LBTR', 0, 'L', 0);
        $pdf->Cell(20, 6,'', 'LBTR', 0, 'C', 0);
        $pdf->Ln();
      }
      //Foreach Loop End Here
    }//For Loop End Here
    /*Loading Lecturers Information*/
   $pdf->Ln(6);
    $pdf->Cell(128,8,"Name of Invigilator:___________________________________",0,0);
    $pdf->Cell(50,10,'Signature/Date:_____________________',0,0);
    $pdf->Ln();
    $pdf->Cell(128,8,"Chief Invigilator Officer:_______________________________ ",0,0); 
    $pdf->Cell(50,10,'Signature/Date:_____________________',0,1);
    
     
  }
} catch (Exception $e) {
  echo $e;
}
  }else{
    /*For Admin And ICT SIDE*/
    $ccode=$_SESSION['courseallo'];
    $semst=$_SESSION['semester'];
    $sect =$_SESSION['section'];
    try {
  $counter=0;
 $sqlc="SELECT DISTINCT programme FROM result WHERE coursecode='$ccode' AND acadsession='$sect' AND semester='$semst'";
   
   $sts = mysqli_query($con,$sqlc);
        $counter = mysqli_fetch_all($sts,MYSQLI_ASSOC);
   $Acounter=0;
   foreach($counter as $rowa)
   {
     $Acounter+=1;
     $programe[$Acounter]  =$rowa['programme'];
     //$Lev[$Acounter]  =$rowa['level'];
   }
   $t=0;
        for($t=1;$t<=$Acounter; $t++)
    {
      $pdf->AddPage();

$pdf->SetTitle($orgname);
//$pdf->Image($logo,10,5,40);
$pdf->Image($logo,90,5,$pdf->GetX()+15, $pdf->GetY()+15);
$pdf->SetFont('Arial','B',12); 
$pdf->Text(65, 36, strtoupper($orgname.' , '.$schadress),'C');
 $pdf->SetFont('Arial','B',10);
$pdf->Text(73, 42, 'EXAMINATION ATTENDANCE SHEET','C'); 

$pps=mysqli_fetch_assoc(mysqli_query($con,"SELECT title,unit FROM course WHERE coursecode='$ccode'"));
$tit=$pps['title'];
$un=$pps['unit'];

;
$pdf->SetFont('Arial','B',8); 
$pdf->Text(15,47,strtoupper('SESSION: '.$sect.'    PROGRAMME: '.$programe[$t].'              SEMESTER: '.$semst),'C');
$pdf->Text(55,52,strtoupper('Course Code: '.$ccode.'               Course Unit: '.$un),'C');
$pdf->Text(15,58,strtoupper(' Course Title: '.$tit),'C');
$pdf->Ln(50);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(10, 6, "#", 'LBTR', 0, 'L', 0);
$pdf->Cell(40, 6, "StudentID", 'LBTR', 0, 'L', 0);
$pdf->Cell(70, 6, "Name", 'LBTR', 0, 'L', 0);
$pdf->Cell(20, 6, "Booklet No", 'LBTR', 0, 'C', 0);
$pdf->Cell(15, 6, "Sign-In", 'LBTR', 0, 'L', 0);
$pdf->Cell(17, 6, "Sign-Out", 'LBTR', 0, 'L', 0);
$pdf->Cell(20, 6, "Date", 'LBTR', 0, 'L', 0);
$pdf->Ln();
/*New For Loop Query*/
$sqlcl="SELECT DISTINCT level FROM result WHERE coursecode='$ccode' AND acadsession='$sect' AND programme='$programe[$t]' AND semester='$semst' ORDER BY level";
                                   
 $stsl = mysqli_query($con,$sqlcl);
      $counterl = mysqli_fetch_all($stsl,MYSQLI_ASSOC);
 $Acounterl=0;
 foreach($counterl as $rowal)
 {
   $Acounterl+=1;
   //$programe[$Acounterl]  =$rowa['programme'];
   $Lev[$Acounterl]  =$rowal['level'];
 }
  $tk=0;
    for($tk=1;$tk<=$Acounterl; $tk++)
    {
      $pdf->Cell(192, 6, "Level: ".$Lev[$tk], 'LBTR', 0, 'L', 0);
      $pdf->Ln();
      /*Foreach Loop to Display the Result*/
      $k=0;
      $sql="SELECT * FROM result WHERE acadsession='$sect' AND coursecode='$ccode' AND level='$Lev[$tk]' AND programme='$programe[$t]' AND semester='$semst' ORDER BY level ASC";

        $_POST['totalunit']=0;
         $st = mysqli_query($con,$sql);
          $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
     foreach($count as $row)
     {
      $k+=1;
        
       stuname($con,$row['studentid']);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(10, 6, $k, 'LBTR', 0, 'L', 0);
        $pdf->Cell(40, 6, $row['studentid'], 'LBTR', 0, 'L', 0);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(70, 6, $_POST["harrd"], 'LBTR', 0, 'L', 0);
        $pdf->Cell(20, 6, '', 'LBTR', 0, 'C', 0);
        $pdf->Cell(15, 6, '', 'LBTR', 0, 'L', 0);
        $pdf->Cell(17, 6, '', 'LBTR', 0, 'L', 0);
        $pdf->Cell(20, 6,'', 'LBTR', 0, 'C', 0);
        $pdf->Ln();
      }
      //Foreach Loop End Here
    }//For Loop End Here
    /*Loading Lecturers Information*/
    $pdf->Ln(6);
    $pdf->Cell(128,8,"Name of Invigilator:___________________________________",0,0);
    $pdf->Cell(50,10,'Signature/Date:_____________________',0,0);
    $pdf->Ln();
    $pdf->Cell(128,8,"Chief Invigilator Officer:_______________________________ ",0,0); 
    $pdf->Cell(50,10,'Signature/Date:_____________________',0,1);
    
     
  }
} catch (Exception $e) {
  echo $e;
}
  }
$pdf->Output();


}


 function stuname($con,$maxt)
 {
   
    $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =$las." ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }

 }

?>