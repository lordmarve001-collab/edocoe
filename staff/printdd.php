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
 fputcsv($output, array('Surname', 'First Name','Other Name','Phone No','Email', 'Student ID','Programme','Academic Session'));  
 $query = "SELECT * FROM studdata  WHERE acadsession='$secc' AND apptype='ABA' ORDER BY surname";   
 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result))  
 {  
      $line=array( strtoupper ($row['surname']), strtoupper ($row['firstname']), strtoupper ($row['lastname']),$row['phone'],$row['email'],$row['admno'],$row['programme'],$row['acadsession']);
      fputcsv($output, $line);  
 }  
 fclose($output);
?>