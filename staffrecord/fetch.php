<?php
$connection = TRUE;
include '../include/dbconfig.php';
$facult=$_GET["facult"];
if ($facult !="")
 {
	$res=mysqli_query($con,"SELECT * FROM local WHERE state_ID='$facult' ORDER BY lga");
	echo "<option selected='selected' disabled='disabled'>SELECT LGA</option>";
	while ($row=mysqli_fetch_array($res))
	{
		echo '<option value="'.$row['lga'].'">'.$row['lga'].'</option>';
	}
}

?>