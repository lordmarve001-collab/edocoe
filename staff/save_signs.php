<?php 
$connection = TRUE;
include '../include/dbconfig.php';
$result = array();
$imagedata = base64_decode($_POST['img_data']);
$staffid=$_POST['p'];
//$filename = md5(date("dmYhisA"));
 $res = trim(str_ireplace( array( '\'', '"',
      ',' , ';', '<', '>','/','-','_','' ), '', $staffid));
$filename = $res;
//Location to where you want to created sign image
$file_name = 'photo/'.$filename.'.png';
$sql="INSERT INTO jupsig(staffid,image,insrdate) VALUES('$staffid','$file_name',NOW())";
    mysqli_query($con,$sql);
file_put_contents($file_name,$imagedata);
$result['status'] = 1;
$result['file_name'] = $file_name;
echo json_encode($result);
?>