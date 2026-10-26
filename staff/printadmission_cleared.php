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
 fputcsv($output, array('Surname', 'First Name','Other Name','Phone No','Email', 'Gender','Disability','Nationality','State','LGA','NIN','Application ID','Programme','Academic Session','JAMB No','JAMB Score','Application Date'));  
 $query = "SELECT * FROM applicant JOIN personaldata ON personaldata.applid=applicant.applid WHERE personaldata.acadsession='$secc' AND personaldata.status='active' AND (applicant.status='Pending' OR applicant.status='Cleared') ORDER BY personaldata.programme";   
 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result))  
 {   
      $line=array( strtoupper ($row['surname']), strtoupper ($row['firstname']), strtoupper ($row['lastname']),$row['phone'],$row['email'],$row['gender'],$row['disability'],$row['nationality'],$row['state'],$row['lga'],$row['nin'],$row['applid'],$row['programme'],$row['acadsession'],$row['jamb_no'],$row['jamb_score'],$row['applicationDate']);
      fputcsv($output, $line);  
 }  
 fclose($output);
?>