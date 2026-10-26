<?php
$connection = TRUE;
include '../include/dbconfig.php';
$state=$_GET["state"];
//retrieving the course from course table
if ($state!="")
 {
	$res=mysqli_query($con,"SELECT * FROM programs WHERE did='$state' ORDER BY pname");//query to get the course
	//echo "<select name='program'>";
	echo "<option selected='selected' disabled='disabled'>SELECT PROGRAMME</option>";
	while ($rows=mysqli_fetch_array($res))
	{
		echo '<option value="'.$rows['pname'].'">'.$rows['pname'].'</option>';
	}
	//echo "</select>";
}
?>