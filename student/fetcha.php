<?php
$connection = TRUE;
include '../include/dbconfig.php';
$facult=$_GET["facult"];
if ($facult !="")
 {
	$res=mysqli_query($con,"SELECT * FROM feesrecord WHERE purpose='$facult'");
	
	while ($row=mysqli_fetch_array($res))
	{
		echo $row['amount'];
		
	}
}

?>