<?php
$connection = TRUE;
include '../include/dbconfig.php';
$state=$_GET["state"];

if ($state!="")
 {
	$res=mysqli_query($con,"SELECT * FROM program WHERE did='$state' ORDER BY pname") OR die (mysqli_error($con));
	
	echo "<option selected='selected' disabled='disabled'>SELECT PROGRAMME</option>";
	while ($rows=mysqli_fetch_array($res))
	{
		echo '<option value="'.$rows['pid'].'">'.$rows['pname'].'</option>';
	}
}
?>