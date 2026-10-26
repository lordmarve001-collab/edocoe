<?php 
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$ccodt=$_SESSION['date'];
$ccode=$_SESSION['dates'];
$resh = trim(str_ireplace(array( '\'', '"',',',';','<','>','/','-','_',''), '', $secc));
$filename = $resh."_Fess_Report.csv";
 header('Content-Type: text/csv; charset=utf-8');  
 header('Content-Disposition: attachment; filename="'.$filename.'"');  
 $output = fopen("php://output", "w");  
 fputcsv($output, array('Student ID', 'Full Name','Purpose','Reference', 'Amount','Payment Date'));  
 $query="SELECT * FROM studentpayment WHERE  paytime BETWEEN '$ccode' AND '$ccodt' AND (purpose!='SUG Fee' AND purpose!='SUG Fee Return')";
 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result)) 

 {       stuname($con,$row['studentid']);
      $line=array($row['studentid'],$_POST["harrd"],$row['purpose'],$row['paymentreference'],$row['amount'],$row['paytime']);

      fputcsv($output, $line); 
     gettotal($ccode,$ccodt,$con);
 }  
  fputcsv($output, array('Total Amount'));  
      $line=array(number_format($_POST['amount'],2));
            fputcsv($output, $line); 

 fclose($output);
 
  function stuname($con,$maxt)
 {


    $sql =mysqli_query($con,"SELECT *  FROM  applicant WHERE  applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $_POST["mat"]= $data['admno'];
        $name =strtoupper($sur)." ".strtolower($first." ".$las);
        $_POST["harrd"]= $name;
        
        }

 }
 function gettotal($prg,$le,$con){
    $sum=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(amount) As amount FROM studentpayment WHERE paytime BETWEEN '$prg' AND '$le'  AND (purpose!='SUG Fee' AND purpose!='SUG Fee Return')"));
    $_POST['amount']=$sum['amount'];
 }
?>