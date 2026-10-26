<?php 
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$ccodt=$_SESSION['date'];
$ccode=$_SESSION['dates'];
$payp=$_SESSION['payp'];
$resh = trim(str_ireplace(array( '\'', '"',',',';','<','>','/','-','_',''), '', $secc));
$filename = $resh."_Fess_Report.csv";
 header('Content-Type: text/csv; charset=utf-8');  
 header('Content-Disposition: attachment; filename="'.$filename.'"');  
 $output = fopen("php://output", "w");  
 fputcsv($output, array('Student ID', 'Full Name','Purpose','Reference', 'Session', 'Payment Date','Amount','Govt. Program','State','LGA','Sex'));  
$query="SELECT * FROM studentpayment WHERE  paytime BETWEEN '$ccode' AND '$ccodt'  AND purpose='$payp' ORDER BY studentid ASC";

 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result)) 

 {       stuname($con,$row['studentid']);
      $line=array($row['studentid'],$_POST["harrd"],$row['purpose'],$row['paymentreference'],$row['section'],$row['paytime'],$row['amount'],$_POST["govt"],$_POST["state"],$_POST["lga"],$_POST["gender"]);

      fputcsv($output, $line); 
                       gettotal($ccode,$ccodt,$payp,$con);
 }  
  fputcsv($output, array('Total Amount'));  
      $line=array($_POST['amount']);
            fputcsv($output, $line); 

 fclose($output);
 
  function stuname($con,$maxt)
 {
        $sql =mysqli_query($con,"SELECT *  FROM  applicant WHERE applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
     
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
         $govt=$data['govt'];
        $gender=$data['gender'];
        $_POST["govt"]= $govt;
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
        $govt=$datat['govt'];
        $state=$datat['state'];
        $lga=$datat['lga'];
        $gender=$datat['gender'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
         $_POST["govt"]= $govt;
        $_POST["state"]= $state;
        $_POST["lga"]= $lga;
        $_POST["gender"]= $gender;
        }
    }
     $sqlt =mysqli_query($con,"SELECT *  FROM  studdata WHERE admno='$maxt'");
      $datat = mysqli_fetch_assoc($sqlt);
     
       
        $govt=$datat['govt'];
        $state=$datat['state'];
        $lga=$datat['lga'];
        $gender=$datat['gender'];
         $_POST["govt"]= $govt;
        $_POST["state"]= $state;
        $_POST["lga"]= $lga;
        $_POST["gender"]= $gender;
        

 }
 
 function gettotal($prg,$le,$pps,$con){
    $sum=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(amount) As amount FROM studentpayment WHERE paytime BETWEEN '$prg' AND '$le' AND purpose='$pps'"));
    $_POST['amount']=$sum['amount'];
 }
?>