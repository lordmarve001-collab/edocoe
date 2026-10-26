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
$error='';

$sems=$_SESSION['sems'];
//$progr = $_SESSION['progr'];
$lv=$_SESSION['lv'];
$ses= $_SESSION['ses'];
$mater=$sems ." ".$ses;

$filename = $logo;
$name=$orgname.", ".$schadress;
$filepath = $filename;

$yr = substr($ses, -4);
$pname=$progr.'_'.$yr.'_'.$sems."_".$lv;

  $daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT fid FROM program WHERE pname='$progr' "));
  $faclt=$daft['fid'];
$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$faclt' "));
    $fac = $factn['fname'];      

?>
<?php
$pdf = new FPDF();
 try {
            stuname($con,$_SESSION['staffid']);
            $p=$_POST["mak"];
            //$p=$stp;
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
          //$pdf->Image($logo,140,50,$pdf->GetX()+40, $pdf->GetY());
          $pdf->Ln(60);
          $pdf->SetFont('helvetica', 'B', 10);
          $pdf->Cell(10, 6, "#", 'LBTR', 0, 'L', 0);
          $pdf->Cell(15, 6, " Code", 'LBTR', 0, 'L', 0);
          $pdf->Cell(130, 6, "Course Title", 'LBTR', 0, 'L', 0);
          $pdf->Cell(10, 6, "Unit", 'LBTR', 0, 'C', 0);
          $pdf->Cell(16, 6, "Status", 'LBTR', 0, 'L', 0);
          $pdf->Ln();
          
          $sr =1;
          //$que= mysqli_query($con,"SELECT DISTINCT course.title,course.unit,result.coursecode FROM course JOIN result ON result.coursecode=course.coursecode  WHERE result.studentid='$value' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses'  ORDER BY result.coursecode");
        $que= mysqli_query($con,"SELECT DISTINCT course.title,course.unit,result.coursecode,result.custatus FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='".$_SESSION['staffid']."' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses'  ORDER BY result.coursecode");                                  
             
            while ($rows= mysqli_fetch_array($que)): 
              $pdf->SetFont('helvetica', 'B', 8);
              $pdf->Cell(10, 6, $sr, 'LBTR', 0, 'L', 0);
              $pdf->Cell(15, 6, $rows['coursecode'], 'LBTR', 0, 'L', 0);
              $pdf->Cell(130, 6, $rows['title'], 'LBTR', 0, 'L', 0);
              $pdf->Cell(10, 6, $rows['unit'], 'LBTR', 0, 'C', 0);
              $pdf->Cell(16, 6, substr($rows["custatus"], 0, 1), 'LBTR', 0, 'C', 0);

              $pdf->Ln();
              
            $sr++;
          endwhile;
        GetUnit($_SESSION['staffid'],$lv,$sems,$ses);

         $pdf->Cell(155,6,'Total Credit Units', 'LBTR', 0, 'R', 0);
         $pdf->Cell(10, 6, $_POST['totalUnit'], 'LBTR', 0, 'C', 0);
         $pdf->Cell(16, 6, "", 'LBTR', 0, 'L', 0);
         $pdf->Ln();
        
          $dn="Dean's";
          $hn="HoD's";
          $pdf->ln();
          $pdf->SetFont('helvetica', 'B', 14);
          $pdf->Cell(3,6,'__________________________');
          $pdf->Cell(3,16,'Student Signature & Date',3,1); 
          $pdf->Cell(3,6,'_____________________________');
          $pdf->Cell(3,16,'Level Advicer Signature & Date',3,1);  
          $pdf->Cell(3,6,'__________________________');
          $pdf->Cell(3,16,$hn.' Signature & Date',3,1);
          $pdf->Cell(3,6,'__________________________');
          $pdf->Cell(3,16,$dn.' Signature & Date',3,1);

          
          $pdf->Output($yr.'_'.$lv.'_'.$sems.'_'.$progr.'_CourseRegistration_PrintOut.pdf',"I");
 } catch (Exception $e) {
     
 }
 
}
function stuname($con,$maxt)
{
  
      $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'   ORDER BY studentid ASC");
      //$sql =mysqli_query($con,"SELECT *  FROM  stafflogin2 WHERE staffid='$maxt'   ORDER BY staffid ASC");
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
        $_POST['emai']=$data['cemail'];
        $_POST['pro']=$data['programme'];
        //$name =$las.", ".$first;
        $name =$first.", ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["mak"]= $mak;
        }
}
function GetUnit($matt,$lv,$sems,$ses)
{
$connection = TRUE;
include('../include/dbconfig.php');
        $queryy="SELECT SUM(unit) AS sumunit FROM result  JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matt' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses' ORDER BY result.coursecode";
          $resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
                $_POST['totalUnit']=$row['sumunit'];       
            }
}
?>