<?php 
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$secc=$_SESSION['section'];
$faclt=$_SESSION['prog'];
$a="$secc $faclt";
$resh = trim(str_ireplace(array( '\'', '"',',',';','<','>','/','-','_',''), '', $a));
$filename = $resh."_Admission_List.csv";
 header('Content-Type: text/csv; charset=utf-8');  
 header('Content-Disposition: attachment; filename="'.$filename.'"');  
 $output = fopen("php://output", "w");  
 fputcsv($output, array('Surname', 'First Name','Other Name','Sex','DOB','Phone No','Email','NIN','Application ID','Matric No','State','LGA','Programme','Academic Session'));  
 $query = "SELECT * FROM applicant JOIN personaldata ON personaldata.applid=applicant.applid WHERE personaldata.acadsession='$secc' AND personaldata.faculty='$faclt' AND personaldata.status='active' AND applicant.status='Approved' ORDER BY personaldata.programme";                            

 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result))  
 {  
      $line=array( strtoupper ($row['surname']), strtoupper ($row['firstname']), strtoupper ($row['lastname']),$row['gender'],$row['dob'],$row['phone'],$row['email'],$row['nin'],$row['applid'],$row['studentid'],$row['state'],$row['lga'],$row['programme'],$row['acadsession']);
      fputcsv($output, $line);  
 }  
 fclose($output);
?>