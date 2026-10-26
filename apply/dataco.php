<?php
$harr = $_SESSION['staffid'];
$query = ("SELECT * FROM applicant WHERE applid='" . $_SESSION['staffid'] . "'");
$result = mysqli_query($con, $query);
while ($row = mysqli_fetch_assoc($result)) {
	$admy=$row['session'];
	$firstname = $row['firstname'];
	$lastname = $row['lastname'];
	$other = $row['othername'];
	$staffid = $row['applid'];
	$has = $row['email'];
	$phf = $row['phoneno'];
	$pgs=$row['programme'];
}

$data=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."'"));
if ($data!='') {
	
	$firstnames = $data['firstname'];
	$surnames = $data['surname'];
	$lastnames = $data['lastname'];
	$fullname=$surnames." ".$firstnames." ".$lastnames;
	$sex = $data['gender'];
	$mart = $data['marital'];
	$state = $data['state'];
	$lga = $data['lga'];
	$nal = $data['nationality'];
	$set = $data['acadsession'];
	if($data['nationality']=="Foreign"){
$statec=$data['state'];
}
else{
   $statec=$data['nationality']; 
}
	//$email = $data['email'];
	$phone = $data['phone'];
	$nin = $data['nin'];
	$passport = $data['passport'];
	$address = $data['address'];
	$userid= $data['applid'];
	$dop= $data['dob'];
	$pro= $data['programme'];
//Retrieviing the Degree Info
$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$data['faculty']."' "));
	$facu = $factn['fname'];
	$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='".$data['department']."'"));
	$def = $depna['dept'];


}


?>