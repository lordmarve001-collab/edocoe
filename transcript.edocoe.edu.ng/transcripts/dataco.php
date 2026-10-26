<?php
date_default_timezone_set("africa/lagos");

$datst=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='".$_SESSION['staffid']."'"));
if ($datst!="") {
$progr=$datst['programme'];
$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$datst['faculty']."' "));
$facu = $factn['fname'];
$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='".$datst['department']."'"));
$def = $depna['dept'];
$unmail=$datst['uniemail'];
$firstname = $datst['firstname'];
$surname = $datst['lastname'];
$other= $datst['othername'];
$sex = $datst['gender'];
$mart = $datst['maritalstatus'];
$statec = $datst['state'];
$lga = $datst['lga'];
$sta = $datst['status'];
$nal = $datst['country'];
	if($datst['country']=="Foreign"){
$statec=$datst['state'];
}
else{
   //$statec=$datst['country']; 
}
	$email = $datst['uniemail'];
	$phone = $datst['phoneno'];
	$passport = $datst['image'];
	$address = $datst['address'];
	$userid= $datst['studentid'];
	$admid= $datst['admissionid'];
	//die($admid);
	$pname= $datst['pname'];
	$pnos= $datst['pno'];
	$pnem= $datst['pemail'];
	$dop= $datst['dob'];
	$cads= $datst['acadsession'];
	$fulname=ucwords($firstname.", ". $surname." ".$other);
	$fullname=strtoupper($surname)." , ".ucwords($firstname." ".$other);
}
	
?>
