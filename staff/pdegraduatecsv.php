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
 fputcsv($output, array('Student ID', 'Full Name','Email','Programme','Session'));  
 $query = "SELECT * FROM studdata  INNER JOIN applicant ON studdata.admno=applicant.applid WHERE studdata.status='active' AND applicant.status='Approved'
                              AND studdata.acadsession='$secc' AND studdata.apptype='PDE' AND studdata.graduate='1' ORDER BY admno";
 $result = mysqli_query($con, $query);  
 while($row = mysqli_fetch_assoc($result))  
 {      stuname($con,$result['applid']);
      $line=array($row['applid'],($_POST["harrd"]),$row['email'],$row['programme'],$row['session']);
      fputcsv($output, $line);  
 }  
 fclose($output);
function stuname($con,$maxt)
 {


    $sql =mysqli_query($con,"SELECT *  FROM  studdata WHERE admno='$maxt'");
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
 
?>