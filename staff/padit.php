<?php
$connection=TRUE;
include '../include/dbconfig.php';

$sql=mysqli_query($con,"UPDATE registcheck SET " . $_POST["column"] . " = '".$_POST["editval"]."',updatedate=NOW() WHERE  programme='".$_POST["programme"]."' AND level='".$_POST["lvs"]."' ");
?>