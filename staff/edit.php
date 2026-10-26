<?php
$connection=TRUE;
include '../include/dbconfig.php';

$sql=mysqli_query($con,"UPDATE result SET " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE  studentid='".$_POST["studentid"]."' AND coursecode='".$_POST["ccode"]."' AND acadsession='".$_POST["sect"]."' AND semester='".$_POST["sems"]."' AND level='".$_POST["lvs"]."' ");
?>