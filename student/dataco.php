<?php
$harr= $_SESSION['staffid'];
$query = ("SELECT * FROM studlogin WHERE staffid='" . $_SESSION['staffid'] . "'");
$result = mysqli_query($con, $query);
while ($row = mysqli_fetch_assoc($result)) {
	$firstnames = $row['firstname'];
	$lastnames = $row['lastname'];
	$has=$row['email'];
	$pgs=$row['programme'];
		$pho=$row['phone'];
	$acad=$row['acadsession'];
}

$datst=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='".$_SESSION['staffid']."'"));
if ($datst!='') {
	

$progr=$datst['programme'];
$degree=$datst['degree'];
$admids= $datst['admissionid'];
$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$datst['faculty']."' "));
	$facu = $factn['fname'];
	//$fu= $factn['fst'];
	$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='".$datst['department']."' "));
$def = $depna['dept'];

$unmail=$datst['email'];
$firstname = $datst['firstname'];
$surname = $datst['lastname'];
$other= $datst['othername'];
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
	//$email = $datst['email'];	
	$cemail = $datst['cemail'];
	$phone = $datst['phoneno'];
	$passport = $datst['image'];
	$address = $datst['address'];
	$userid= $datst['studentid'];
	if($admids==''){
		$admid=$datst['studentid'];
	}else{
		$admid=$datst['admissionid'];
	}
	$pname= $datst['pname'];
	$pnos= $datst['pno'];
	$pnem= $datst['pemail'];
	$dop= $datst['dob'];
	$fulname=$surname.", ". $firstname." ".$other;
	$fullname=$surname." ". $firstname." ".$other;

}
?>
