<?php
session_start(); // Starting Session

$connection=TRUE;
include("../include/dbconfig.php");
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
  

$saltQuery = "SELECT salt FROM stafflogin WHERE username = '$username' UNION SELECT salt FROM stafflogin1 WHERE username='$username' ";
  //$saltQuery= "SELECT salt FROM register WHERE username ='$username' ";
$result = mysqli_query($con,$saltQuery);
$row = mysqli_fetch_assoc($result);
$salt = $row['salt'];

$saltedPW =  $escapedPW . $salt;
//die($saltedPW);
$hashedPW = hash('sha256', $saltedPW);
//$hashedPW = $escapedPW;
//die($escapedPW);

  $query = mysqli_query($con,"SELECT * FROM stafflogin WHERE username='$username' AND password='$hashedPW' UNION SELECT * FROM stafflogin1 WHERE username='$username' AND password ='$hashedPW'"); 
 /*$query .= mysqli_query($con,"SELECT * FROM register WHERE username = '$username'AND password = '$hashedPW' ");*/

                            $rows = mysqli_num_rows($query);
							$data = mysqli_fetch_array($query);
							$type = $data['position'];
							$user = $data['username'];
							$status=$data['status'];


if ($status =="active")
 //if($rows=="1")
{
//$_SESSION['email'] = $data['email'];
$_SESSION['staffid']=$data['staffid'];
$_SESSION['start'] = time();
$_SESSION['expire'] = $_SESSION['start'] + (60*60*24);
    if($type=='student')
        {
             $_SESSION['login_user'] = $staffid;
            // echo "<script>location.assign('./rootSU/super_user.php')     </script> ";

            header("location: studentportal/studentdashboard");
        }
        elseif ($type=='staff')
        {
            $_SESSION['login_user']=$staffid;
            //$_SESSION['staffid']=$harr;
            // Initializing Session
            //echo "<script>location.assign('./admini/admin.php')</script> ";
           header("location: staffportal/staffportal");
        }
        elseif ($type=='administrator')
        {
            $_SESSION['login_user']=$staffid;
            
           header("location: administrator/staffportal");
        }
        elseif ($type=='bursary')
        {
            $_SESSION['login_user']=$staffid;
            
           header("location: bursaryoffice/staffportal");
        }
    else
    {
        //$hey = "Your Account Is Not Active.";
       //$_SESSION['errmsg']= "Your Account Have No Directory.";
       $error= "Your Account Have No Directory.";
        $showModals='true';

        // header("Refresh:3;");
    }

}
elseif($status=="graduated" AND $type=="student"){
	$_SESSION['staffid']=$data['staffid'];
$_SESSION['start'] = time();
$_SESSION['expire'] = $_SESSION['start'] + (60*60*24);
	$_SESSION['login_user']=$staffid; // Initializing Session
           // echo "<script>location.assign('./user/home.php')</script> ";

            header("location:studentportal/studentdashboard");
}
elseif ($status=="disable") {
     //$_SESSION['errmsg'] = "Your Account Is Not Active.";
     $error = "Your Account Is Not Active.";
     $showModals='true';
     //header("Refresh:3;");
 }
 else
{
$error = "Check Username or Password.";
//$_SESSION['errmsg'] = "Check Username or Password.";
//header("Refresh:3;");
$showModals='true';
}

mysqli_close($con); // Closing Connection
//}
}
//session_destroy();
//session_unset();

// destroy the session
//session_destroy();
?>
