<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

$appli= $_POST['id'];
$_SESSION['ids']=$appli;
?>
<p style="font-size: 16px;font-weight: bolder;">Are you sure, you want to remove this file?</p>