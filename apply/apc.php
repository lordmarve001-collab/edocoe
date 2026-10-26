<?php
$harr = $_SESSION['staffid'];


//For Foreign Student
/*$queryt=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."'");
while($row = mysqli_fetch_array($queryt)){
	$row = $row['nationality'];
switch ($row) {
	case 'Foreign':
		$queryy=mysqli_query($con,"SELECT * FROM Foreignd WHERE applid='".$_SESSION['staffid']."'");
		$rows = mysqli_num_rows($queryy);
		//$data = mysqli_fetch_array($query);
		if ($rows == 0)
		{
			header("Location: foreigndetail");
		}
	break;
}
}*/


$shj=mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='".$_SESSION['staffid']."' ");
		$rt = mysqli_num_rows($shj);
		$dat = mysqli_fetch_array($shj);
		if ($rt == 0)
		{
			header("Location:applicparent");
		}


$query = mysqli_query($con, "SELECT * FROM oleveldetail WHERE applid='" . $_SESSION['staffid'] . "'");
$rows = mysqli_num_rows($query);
$data = mysqli_fetch_array($query);
if ($rows == 0) {
	header("Location:applicantoleve");
} else {
	$query = mysqli_query($con, "SELECT * FROM olevelresultdetail WHERE applid='" . $_SESSION['staffid'] . "'");
	$rows = mysqli_num_rows($query);
	//$data = mysqli_fetch_array($query);
	if ($rows == 0) {
		header("Location: applicantoleve");
	}
}
//Primary School Data
//$queryf=mysqli_query($con,"SELECT * FROM flscdetail WHERE applid='".$_SESSION['staffid']."' ");
		//$rows = mysqli_num_rows($queryf);
		//$data = mysqli_fetch_array($queryf);
		//if ($rows == 0)
		//{
		//	header("Location: applicaprim");
		//}
//Personal Data Page 
$queryz=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."' AND cert='0' AND programme='JCHEW. Conversion Program into Community Health Extension Worker' ");
		$rows = mysqli_num_rows($queryz);
		$dataz = mysqli_fetch_array($queryz);

		//$data = mysqli_fetch_array($query);
		if ($dataz > 0)
		{
			header("Location: jchewupload");
		}
//
$quer=mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' AND cert='0' AND apptype='ND' ");
		$rows = mysqli_num_rows($queryz);
		$dat= mysqli_fetch_array($quer);

		//$data = mysqli_fetch_array($query);
		if ($dat > 0)
		{
			header("Location: ndupload");
		}
//
$quer=mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' AND cert='0' AND apptype='CERT' ");
		$rows = mysqli_num_rows($queryz);
		$dat= mysqli_fetch_array($quer);

		//$data = mysqli_fetch_array($query);
		if ($dat > 0)
		{
			header("Location: certupload");
		}

//

		$querys=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."' ");
		$rows = mysqli_num_rows($querys);
		//$data = mysqli_fetch_array($query);
		
		if ($rows == 0)
		{
			header("Location: personaldata");
		}

?>