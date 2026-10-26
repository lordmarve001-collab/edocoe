<?php
$connection=TRUE;
include '../include/dbconfig.php';
//echo $_POST["unt"];
$sql=mysqli_query($con,"UPDATE graduatelist SET " . $_POST["column"]. " ='".$_POST["editval"]."' WHERE  studentid='".$_POST["codes"]."' AND programme='".$_POST["title"]."' AND acadsession='".$_POST['unt']."' ");
?>