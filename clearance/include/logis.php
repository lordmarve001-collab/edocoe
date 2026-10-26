<?php
ob_start();
session_start();
$error='';
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include '../include/dbconfig.php';
//Login Session

if (isset($_POST["login"])) {
 /*
  *Here we are to check the payment record 
  *if Dey Talley with the registration Record
  *login the Applicant In
 */
 $apid=mysqli_escape_string($con,$_POST["applid"]);
 $pass=mysqli_escape_string($con,$_POST["password"]);
 //$query = mysqli_query($con,"SELECT * FROM register WHERE userid='$apid' AND password='$pass'");
//Remember to Implement Algorithm that will check Payment Record Table of Applicant
 $query = mysqli_query($con,"SELECT * FROM applicant  INNER JOIN studentpayment  ON applicant.applid=studentpayment.studentid WHERE applicant.applid='$apid' AND applicant.password='$pass'");
 

              $rows = mysqli_num_rows($query);
              $data = mysqli_fetch_array($query);
              // $type = $data['position'];
              // $user = $data['username'];
              $status=$data['status'];
//echo $status;

//if ($status =="complete")
 if($rows=="1")
{
$_SESSION['staffid']=$data['applid'];
$_SESSION['start'] = time();
$_SESSION['expire'] = $_SESSION['start'] + (60*60*24);
header("location:applicant/appsummary");
}else{
  $error= "Check Username or Password.";
  echo "<script>alert('$error')</script> ";
//header("Refresh:3;");
 }
 
}
?>