<?php
$connection=TRUE;
include '../include/dbconfig.php';

$sql=mysqli_query($con,"UPDATE course SET " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE  coursecode='".$_POST["codes"]."' AND title='".$_POST["title"]."' AND unit='".$_POST["unt"]."' ");
?>