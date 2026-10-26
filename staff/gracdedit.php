<?php
$connection=TRUE;
include '../include/dbconfig.php';

$sql=mysqli_query($con,"UPDATE gradclass SET " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE  maxscorep='".$_POST["maxscore"]."' AND minscorep='".$_POST["minscore"]."' AND gracla='".$_POST["grad"]."' AND remark='".$_POST["remk"]."' ");
?>