<?php
$connection = TRUE;
include '../include/dbconfig.php';
$facult=$_GET["facult"];
if ($facult !="")
 {
	$res=mysqli_query($con,"SELECT * FROM unitlist WHERE unitid='$facult' ORDER BY unitname");
	echo "<option selected='selected' disabled='disabled'>Select Unit</option>";
	while ($row=mysqli_fetch_array($res))
	{
		echo '<option value="'.$row['unitname'].'">'.$row['unitname'].'</option>';
	}
}

?>