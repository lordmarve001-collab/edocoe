<?php
$harr = $_SESSION['staffid'];

$docc=mysqli_num_rows(mysqli_query($con,"SELECT * FROM cleardoc WHERE applid='".$_SESSION['staffid']."'"));
if($docc!=3){
	header("Location:applidocupl");
}
$docu=mysqli_query($con,"SELECT * FROM cleardoc WHERE applid='".$_SESSION['staffid']."' ");
		$dad = mysqli_num_rows($docu);
		if ($dad==0)
		{
			header("Location:applidocupl");
		}
?>