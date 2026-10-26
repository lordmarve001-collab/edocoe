<?php
date_default_timezone_set("africa/lagos");
$harr = $_SESSION['staffid'];
$query = "SELECT * FROM stafflogin WHERE staffid='" .$_SESSION['staffid'] . "'";
$result = mysqli_fetch_assoc(mysqli_query($con, $query));
if ($result!='') {
	
	$firstnames = $result['firstname'];
	$lastnames = $result['lastname'];
	$_SESSION['staffid']=$result['staffid'];
	$has =$firstnames.".".$lastnames."@edocohest.edu.ng" ;
	//echo $_SESSION['staffid'];
}else{
	header("location:../logout");
}
$datst=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people WHERE staffid='".$_SESSION['staffid']."'"));
if ($datst!="") {
	// code...

$unmail=$datst['uniemail'];
$firstname = $datst['firstname'];
$surname = $datst['lastname'];
$othe = $datst['othername'];
$ltn = $datst['title'];
$sex = $datst['gender'];
$mart = $datst['maritalstatus'];
$statec = $datst['state'];
$lga = $datst['lga'];
$nal = $datst['country'];
if($datst['country']=="Foreign"){
$statec=$datst['state'];
}
else{
   //$statec=$datst['country']; 
}
	$email = $datst['uniemail'];
	$phone = $datst['phoneno'];
	if ($datst['image']=="") {
		$passport = "photo/avater.png";
	}else{
		$passport = $datst['image'];
	}
	$address = $datst['address'];
	$userid= $datst['staffid'];
	$dop= $datst['dob'];
	$fulname=$ltn.". ".$firstname.", ". $surname." ".$othe;
	$fullname=$ltn.". ".$surname." ". $firstname." ".$othe;
	$fullnames=$ltn.". ".$surname." ". $firstname." ".$othe;
}

	$daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT faculty,deptm,programme FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'"));
   if ($daft!="") {
    $faclt=$daft['faculty'];
    $deplt=$daft['deptm'];
	$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$faclt' "));
	$fac = $factn['fname'];
	$factns=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE did='$deplt' "));
	$pro = $factns['pname'];
	$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='$deplt' "));
	if ($depna!="") {
		$dt = $depna['dept'];
	}
	
	 }else{
	 	$deplt='';
	 }
?>