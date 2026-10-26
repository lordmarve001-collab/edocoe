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
 fputcsv($output, array('SurName', 'FirstName','Othername','Phone No','Email', 'Student ID','Sex','Programme','Academic Session','Govt. Program','Study Center','State','LGA'));  
 $query = "SELECT * FROM studdata  WHERE acadsession='$secc'  AND apptype='PDE' ORDER BY status";

 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result))  
 {  
     
      $line=array(strtoupper($row['surname']),strtoupper($row['firstname']),strtoupper($row['lastname']),$row['phone'],strtoupper($row['email']),strtoupper($row['admno']),strtoupper($row['gender']),strtoupper($row['programme']),$row['session'],strtoupper($row['govt']),strtoupper($row['center']),strtoupper($row['state']),strtoupper($row['lga']));
      fputcsv($output, $line);  
 }  
 fclose($output);
?>