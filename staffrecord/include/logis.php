<?php
ob_start();
session_start();
$error='';
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include '../include/dbconfig.php';
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];

if (isset($_POST["login"])) {
 $apid=trim(mysqli_escape_string($con,strtoupper($_POST["applid"])));
 $pass=mysqli_escape_string($con,$_POST["password"]);
 $hashedPW = hash('sha256', $pass);
 $query = mysqli_query($con,"SELECT * FROM applicant WHERE applid='$apid' AND password='$hashedPW' AND session='$ses' AND apptype='NCE'");
 $rows = mysqli_num_rows($query);
$data = mysqli_fetch_array($query);
if ($data!='') {

$_SESSION['staffid']=$data['applid'];
$_SESSION['start'] = time();
$_SESSION['expire'] = $_SESSION['start'] + (60*60*24);
header("location:appsummary");
 }else{
  $error='Check e-PIN ID '.$apid.' Or Password '.$pass;
  $showModal='true';
 }
}
?>