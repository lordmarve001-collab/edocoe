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
 fputcsv($output, array('Surname', 'First Name','Other Name','Phone No','Email', 'Student ID','Programme','Gender','Govt. Programme','Academic Session'));  
 $query = "SELECT * FROM studdata  WHERE acadsession='$secc' AND apptype='PDE' ORDER BY surname";   
 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result)) 
 {  
     $govt=$row['govt'];
                                if ($govt=='EDO STATE UNIVERSAL BASIC EDUCATION BOARD (SUBEB)'){$gov='SUBEB';}
                                if ($govt=='EDO STATE SECONDARY EDUCATION BOARD (SSEB)'){$gov='SSEB';}
                                if ($govt==''){$gov='NONE';}
      $line=array( strtoupper ($row['surname']), strtoupper ($row['firstname']), strtoupper ($row['lastname']),$row['phone'],$row['email'],$row['admno'],$row['programme'],$row['gender'],$gov,$row['acadsession']);
      fputcsv($output, $line);  
 }  
 fclose($output);
?>