<?php
$connection=TRUE;
include '../include/dbconfig.php';
//$dat=date("Y-m-d h:i:sa");
$sql=mysqli_query($con,"UPDATE passmark SET " . $_POST["column"] . " = '".$_POST["editval"]."',updatedate=NOW() WHERE  programme='".$_POST["programme"]."'");
?>