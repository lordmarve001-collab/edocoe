<?php

$queryl=mysqli_query($con,"SELECT * FROM oleveldetail WHERE (applid='$admid' OR applid='".$_SESSION['staffid']."')");
$rowsl = mysqli_num_rows($queryl);
if ($rowsl == 0)
{
	header("Location: applicantoleve");
}

/*$queryy=mysqli_query($con,"SELECT * FROM flscdetail WHERE (applid='$admid' OR applid='".$_SESSION['staffid']."')");
$rowsy = mysqli_num_rows($queryy);
if ($rowsy == 0)
{
	header("Location: applicaprim");
}*/
//echo $_SESSION['staffid'];
$quert=mysqli_query($con,"SELECT image FROM student WHERE studentid='".$_SESSION['staffid']."'");
$rowt = mysqli_fetch_array($quert);
if ($rowt['image']=='')
{
	header("Location: passportupdates");
}

$querya=mysqli_query($con,"SELECT * FROM student WHERE studentid='".$_SESSION['staffid']."'");
$rows = mysqli_num_rows($querya);

if ($rows == 0)
{
	header("Location: newstudent");
}


/*Question page*/
?>