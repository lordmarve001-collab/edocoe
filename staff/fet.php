<?php
$connection = TRUE;
include '../include/dbconfig.php';
$facult=$_GET["facult"];
if ($facult !="")
 {
	$res=mysqli_query($con,"SELECT * FROM department WHERE fid='$facult' ORDER BY dept");
	echo "<option selected='selected' disabled='disabled'>SELECT DEPARTMENT</option>";
	while ($row=mysqli_fetch_array($res))
	{
		echo '<option value="'.$row['deptc'].'">'.$row['dept'].'</option>';
	}
}

?>