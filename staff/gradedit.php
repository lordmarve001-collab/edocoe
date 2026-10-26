<?php
$connection=TRUE;
include '../include/dbconfig.php';

$sql=mysqli_query($con,"UPDATE gradetable SET " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE  maxscore='".$_POST["maxscore"]."' AND minscore='".$_POST["minscore"]."' AND point='".$_POST["point"]."' AND grade='".$_POST["grade"]."' AND remark='".$_POST["remk"]."' ");
?>