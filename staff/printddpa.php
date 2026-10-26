<?php 
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$secc=$_SESSION['section'];
$resh = trim(str_ireplace(array( '\'', '"',',',';','<','>','/','-','_',''), '', $secc));
$filename = $resh."PDE_Complate_Admission_List.csv";
 header('Content-Type: text/csv; charset=utf-8');  
 header('Content-Disposition: attachment; filename="'.$filename.'"');  
 $output = fopen("php://output", "w");  
 fputcsv($output, array('Student ID','Surname', 'First Name','Other Name','Phone No','Sex', 'Programme','Govt. Programme','Academic Session'));  
 $query = "SELECT * FROM applicant WHERE session='$secc' AND (status='Approved' or status='submitted' or status='Pending') AND apptype='PDE'";   
 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result)) 
 {  
     $govt=$row['govt'];
    if ($govt=='EDO STATE UNIVERSAL BASIC EDUCATION BOARD (SUBEB)'){$gov='SUBEB';}
    if ($govt=='EDO STATE SECONDARY EDUCATION BOARD (SSEB)'){$gov='SSEB';}
    if ($govt==''){$gov='NONE';}

      $line=array( $row['applid'], strtoupper($row['lastname']), strtoupper ($row['firstname']), strtoupper ($row['othername']),$row['phoneno'],$row['sex'],$row['programme'],$gov,$row['session']);
      fputcsv($output, $line);  
 }  
 fclose($output);
?>