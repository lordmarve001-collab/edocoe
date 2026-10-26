<?php
session_start(); // Starting Session
//error_reporting(0);
//error_reporting(1);
$connection=TRUE;
include("dbconfig.php");
$error=''; // Variable To Store Error Message
$empty='';
$no_acc='';
$hey='';
	if (isset($_POST['submit'])) {
		# code...
		#$username=mysqli_real_escape_string( strtolower(trim($con,$_REQUEST['username'])));
		$username=strtolower($_REQUEST["username"]);

     $escapedPW = mysqli_real_escape_string($con,$_REQUEST['password']);

     //save this user and pass as cookie if remeber checked start
 if (isset($_REQUEST['remember']))
   $escapedRemember = mysqli_real_escape_string($con,$_REQUEST['remember']);

 $cookie_time = 60 * 60 * 24 * 30; // 30 days
 //$cookie_time = 60 * 24 * 30; // 30 days
 //$cookie_time =0*0*0*0; // 30 days
  $cookie_time_Onset=$cookie_time+ time();
  if (isset($escapedRemember)) {
    /*
     * Set Cookie from here for one hour
     * */
    setcookie("username", $username, $cookie_time_Onset);
    setcookie("password", $escapedPW, $cookie_time_Onset);

  } else {

      $cookie_time_fromOffset=time()-$cookie_time;
	setcookie("username", '',$cookie_time_fromOffset );
    setcookie("password", '', $cookie_time_fromOffset);

  }
  

$hashedPW = hash('sha256',$escapedPW);
//$hashedPW = $escapedPW;
//die($escapedPW);

  $hashedPW = hash('sha256', $escapedPW);
  $query = "SELECT position,status,username,staffid FROM stafflogin WHERE username='$username' AND password='$hashedPW' UNION SELECT position,status,username,staffid FROM studlogin WHERE username='$username' AND password='$hashedPW'";

                             $result = mysqli_query($con,$query);
  $data = mysqli_fetch_assoc($result);
							$type = $data['position'];
							$user = $data['username'];
							$status=$data['status'];


if ($status =="active")
{

$_SESSION['staffid']=$data['staffid'];
$_SESSION['start'] = time();
$_SESSION['expire'] = $_SESSION['start'] + (60*60*24);
    if($type=='student')
        {
            header("location: student/student");
        }
        elseif ($type=='staff')
        {
           header("location: staff/staffportal");
        }
    else
    {
      
       $error= "Your Account Have No Directory.";
        $showModals='true';
    }

}
elseif($status=="graduated" AND $type=="student"){
	$_SESSION['staffid']=$data['staffid'];
$_SESSION['start'] = time();
$_SESSION['expire'] = $_SESSION['start'] + (60*60*24);

         header("location:students/student");
}
elseif ($status=="disable") {
     $error = "Your Account Is Not Active.";
     $showModals='true';
    
 }
 else
{
$error = "Check Username or Password.";
//$_SESSION['errmsg'] = "Check Username or Password.";
//header("Refresh:3;");
$showModals='true';
}

mysqli_close($con); // Closing Connection
}

?>
