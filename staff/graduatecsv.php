<?php 
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$secc=$_SESSION['section'];
$resh = trim(str_ireplace(array( '\'', '"',',',';','<','>','/','-','_',''), '', $secc));
$filename = $resh."_Admission_List.csv";
 header('Content-Type: text/csv; charset=utf-8');  
 header('Content-Disposition: attachment; filename="'.$filename.'"');  
 $output = fopen("php://output", "w");  
 fputcsv($output, array('FirstName', 'Surname','Phone No','Email', 'Student ID','Programme','Academic Session'));  
 $query = "SELECT * FROM applicant  WHERE session='$secc' AND apptype='ABA' and status='Approved' ORDER BY programme";  
 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result))  
 {  
      $line=array($row['firstname'],$row['lastname'],$row['phoneno'],$row['email'],$row['applid'],$row['programme'],$row['session']);
      fputcsv($output, $line);  
 }  
 fclose($output);
?>