<?php
$connection=TRUE;
include '../include/dbconfig.php';

$sql=mysqli_query($con,"UPDATE studdata SET " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE  admno='".$_POST["studentid"]."' AND acadsession='".$_POST["sect"]."' ");
?>