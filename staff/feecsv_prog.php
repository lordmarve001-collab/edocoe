<?php 
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$secc=$_SESSION['section'];
$sec=$_SESSION['sections'];
$payp=$_SESSION['payp'];
$resh = trim(str_ireplace(array( '\'', '"',',',';','<','>','/','-','_',''), '', $secc));
$filename = $resh."_Fess_Report.csv";
 header('Content-Type: text/csv; charset=utf-8');  
 header('Content-Disposition: attachment; filename="'.$filename.'"');  
 $output = fopen("php://output", "w");  
 fputcsv($output, array('Student ID', 'Full Name','Programme','Purpose','Reference', 'Amount','Payment Date'));  
$query="SELECT * FROM studentpayment WHERE section='$sec' AND programme='$secc' AND purpose='$payp' ORDER BY studentid ASC";

 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result)) 

 {       
     stuname($con,$row['studentid']);
      $line=array($row['studentid'],$_POST["harrd"],$row['programme'],$row['purpose'],$row['paymentreference'],$row['amount'],$row['paytime']);

      fputcsv($output, $line); 
                                                gettotal($sec,$secc,$payp,$con);
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

  function gettotal($sec,$prg,$pps,$con){
    $sum=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(amount) As amount FROM studentpayment WHERE section='$sec' AND programme='$prg' AND purpose='$pps'"));
    $_POST['amount']=$sum['amount'];
 }
?>