<?php
$connection = TRUE;
include '../include/dbconfig.php';
$facult=$_GET["facult"];
if ($facult !="")
 {
	if ($facult==12) {
		$fac="Transcript Within Nigeria";
	}
	if ($facult==13) {
		$fac="Transcript Outside Nigeria";
	}
	$res=mysqli_query($con,"SELECT * FROM feesrecord WHERE purpose='$fac'");
	
	while ($row=mysqli_fetch_array($res))
	{
		echo $row['amount'];
		
	}
}

?>