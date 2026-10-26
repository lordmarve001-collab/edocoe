<?php
$harr = $_SESSION['staffid'];
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."'"));
	$ap = $dat['apptype'];
	
$data=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."'"));
	$firstname = $data['firstname'];
	$surname = $data['surname'];
	$lastname = $data['lastname'];
	$fullname=$firstname." ".$surname." ".$lastname;
	$sex = $data['gender'];
	$mart = $data['marital'];
	$state = $data['state'];
	$lga = $data['lga'];
	$nal = $data['nationality'];
	if($data['nationality']=="Foreign"){
$statec=$data['state'];
}
else{
   $statec=$data['nationality']; 
}
	//$email = $data['email'];
	$phone = $data['phone'];
	$passport = $data['passport'];
	$address = $data['address'];
	$userid= $data['applid'];
	$dop= $data['dob'];
	$matr= $data['studentid'];
	$admy=$data['acadsession'];
   $pro=$data['programme']; 

$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$data['faculty']."' "));
	$facu = $factn['fname'];
	$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='".$data['department']."'"));
	$def = $depna['dept'];
$rel=mysqli_fetch_assoc(mysqli_query($con,"SELECT relig FROM parentdetail WHERE applid='".$_SESSION['staffid']."'"));
	$reg = $rel['relig'];
$pa=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='".$_SESSION['staffid']."' "));
if($pa!=''){
$pfu=$pa['title'].'. '.$pa['parentname'];
$phn=$pa['phone'];
$pad=$pa['address'];
}
?>