<?php
$connection = TRUE;
include '../include/dbconfig.php';
$facult=$_GET["facult"];
if ($facult !="")
 {
	$res=mysqli_query($con,"SELECT * FROM department WHERE fid='$facult' ORDER BY dept");
	//echo "<select id='statedd' name='deptment' onchange='change_state()'>";
	echo "<option selected='selected' disabled='disabled'>SELECT DEPARTMENT</option>";
	while ($row=mysqli_fetch_array($res))
	{
		echo '<option value="'.$row['deptid'].'">'.$row['dept'].'</option>';
		//echo '<option value="$row['depid']">'; echo $row["deptname"]; echo'</option>';
	}
	//echo "</select>";
}

?>