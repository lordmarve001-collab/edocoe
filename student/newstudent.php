<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout"); 
    }
    else{
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
    /*Quering the student Record for Insertion*/
    $error='';
    require"phpmailer/class.phpmailer.php";
    $mail = new PHPMailer();
    
    $nw=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE studentid='".$_SESSION['staffid']."'"));
            //   $unim=$nw['unemail'];
              
            $applid=$nw['applid'];
            
    if (isset($_POST['submitspd'])) {
        $admid=mysqli_escape_string($con,$_POST['admino']);
        $appl=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$admid'"));
        if ($appl=='') {
           $error="Admission ID ".$admid." Not Valid Check and Try Again";
           $showModalEr='true';
        }else
        $applS=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE studentid='$harr' AND applid='$admid' "));
        if ($applS=='') {
           $error="Admission ID ".$admid." Do Not Match Your Matric No, Kindly Check and Try Again";
           $showModalEr='true';
        }else{
        $ld=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$admid'"));
        $lc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='$admid'"));
            
               $nw=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studlogin WHERE staffid='".$_SESSION['staffid']."'"));
            //   $unim=$nw['unemail'];
              
            $fac=$ld['faculty'];
            $dac=$ld['department'];
            //$disa=$ld['disablest'];
            $add = str_replace(array( '\'',"'",'"', ';', '<', '>' ), '', $ld['address']);
         $dup=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='".$_SESSION['staffid']."'"));
         if ($dup!='') {
           $error="Your Details Already Exist";
           $showModalEr='true';
           header("location:student");
           
        }else{
         $ins=mysqli_query($con,"INSERT INTO student(studentid,admissionid,firstname,lastname,othername,gender,maritalstatus,dob,phoneno,email,state,address,faculty,department,programme,acadsession,status,pname,pno,pemail,cprelationship,country,lga)VALUES('".$_SESSION['staffid']."','$admid','".$ld['firstname']."','".$ld['surname']."','".$ld['lastname']."','".$ld['gender']."','".$ld['marital']."','".$ld['dob']."','".$ld['phone']."','".$ld['email']."','".$ld['state']."','".$add."','$fac','$dac','".$ld['programme']."','".$nw['acadsession']."','active','".$lc['parentname']."','".$lc['phone']."','".$lc['email']."','".$lc['relat']."','".$ld['nationality']."','".$ld['lga']."')");
        if ($ins) {
            $showModal='true';
            sendstsms($con,$_SESSION['staffid']);
            sendstem($con,$_SESSION['staffid'],$mail,$hostna,$usern,$passd);
            header("refresh:2; student");
        }else{
            $error="Error Occured While Moving Data";
            $showModalEr='true';
        }
      }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
         <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/animate.min.css" media="screen" >
      <script type="text/javascript">
          function changes(objs){
    var selectBox = objs;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var retCustDetails = document.getElementById("retCustDetails");
    var tradeCustDetails = document.getElementById("tradeCustDetails");

    if(selected == 'Yes'){
        retCustDetails.style.display = "block";
        tradeCustDetails.style.display = "none";
    }else if (selected == 'No') {
         window.location.href='registration'
    }
    else{
        retCustDetails.style.display = "none";
        tradeCustDetails.style.display = "none";
    }
}
      </script>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
  <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php include('includes/newbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">New Student Data</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                        <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
                                                     <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                    
    <label>New Students Kindly Select <span style="color:green;">YES</span></label>
        <label>Old Students kindly Select <span style="color:red;">NO</span></label>
                                                     <div><br>
                                                         <label>Do You Have Admission Number?</label>
                                                    <select name="sponsor"  class="form-control clid" id="show" onchange="changes(this)" class="state">
                                                        <option disabled="disabled" selected="selected">Select Option</option>
                                                        <option value="Yes">YES</option>
                                                        <option value="No">NO</option>
                                                    </select>
                                                    </div>
                                                    </div>
                                                    <div id="retCustDetails" class="custDetails" style="display:none">
                                                        <div class="form-group">
                                                    <label for="default">Enter Admission Number:</label>
                                                     <div>
                                                        <input type="text" class="form-control"  autocomplete="on" value="<?php echo $applid?>"  placeholder="Enter Admission Number Here" name="admino" readonly required="required">
                                                     </div>
                                                    </div>
                                                     
                                                    </div>
                                                    <!-- For Foreign Country -->
                                                    <div style="display:none" id="tradeCustDetails" class="custDetails" >
                                                    </div>
 <!-- End Foreign -->

                    <!-- Sepreaaater -->
                                                    <div class="form-group">
                                                        <div >
                                                            <button type="submit" name="submitspd" id="submit" class="btn btn-primary form-control">Submit Data</button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-12 -->
                                </div>
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-thumbs-up"></i>Congratulation Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><i class="fa fa-thumbs-up"></i>Congratulation You Details is Successfully Submitted</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-success" type="button" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
       <script src="js/main.js"></script>

      
    </body>
</html>
<?php 
if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    }
    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    }


}
/*Send to Student Only*/
 function sendstsms($con,$numb){
    $url="https://portal.edocoe.edu.ng/parent/";
    $email = "okpehjohcybertech@gmail.com";
    $password = "Test123";
    $stu=array();
    $pho=mysqli_query($con,"SELECT * FROM student WHERE status='active' AND studentid='$numb'");
$results = mysqli_fetch_all($pho,MYSQLI_ASSOC);
foreach($results as $res)
{

$message= "Dear: ".$res['pname'].", parent of ".$res['firstname'];
$message .= " with Student ID: ".$res['studentid']." \n";
$message.= "know that EDOCOE has taken a giant stride.\n";
$message.= "You can now monitor your child from your home.\n";
$message.= "How to Login\n";
$message.= "Visit ".$url."\n";
$message.= "Enter your ward's Matric. No.\n";
$message.= "Login and see the Student Portal,\n";
$message.= "Check the Registered Courses,\n";
$message.= "Check the Registered Courses,\n";
$message.= "Check the result to see CGPA,\n";
$message.= "Check detailed results by session/semester; and 
Check School Fee Payment History.\n";
$message.= "EDOCOE is poised to produce graduates who are not found wanting in CHARACTER and LEARNING.
Be part of the character building process of your ward.\n";
$message.= "Signed:\n";
$message.= "Management, EDOCOE\n";
$sender_name = "EDOCOE";
$recipients = $res['pno'];
$forcednd ="1";
$data = array("email" => $email, "password" => $password,"message"=>$message, "sender_name"=>$sender_name,"recipients"=>$recipients,"forcednd"=>$forcednd);
  $data_string = json_encode($data);
  $ch = curl_init('https://app.multitexter.com/v2/app/sms');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
  $result = curl_exec($ch);
  $res_array = json_decode($result);
 }
}

function sendstem($con,$sect,$mail,$host,$usern,$passd){
    $stu=array();
    $url="https://portal.edocoe.edu.ng/parent/";
    $che=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$sect'"));
    if ($che['pemail']!='') {
   
    $pho=mysqli_query($con,"SELECT * FROM student WHERE status='active' AND studentid='$sect'");
$results = mysqli_fetch_all($pho,MYSQLI_ASSOC);
  foreach($results as $res)
  {
    try {
        //$mail->SMTPDebug = 1;
        $mail->isSMTP();
        $mail->Host =$host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $passd;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($usern, 'From EDOCOE To Parents');
        $mail->addAddress($res['pemail']);
        $mail->isHTML(true);
        $mail->Subject = 'From EDOCOE To Parents';
        $message= "Dear: ".$res['pname'].", parent/guardian of ".$res['firstname'];
        $message .= " with Student ID: ".$res['studentid']." \n";
        $message.= "know that Edo College of Education has taken a giant stride.\n";
        $message.= "You can now monitor your child from your home.\n";
        $message.= "How to Login\n";
        $message.= "Visit ".$url."\n";
        $message.= "Go to PARENT LOGIN\n";
        $message.= "Enter your ward's Matric. No.\n";
        $message.= "Login and see the Student Portal\n";
        $message.= "Check the Registered Courses\n";
        $message.= "Check the Registered Courses\n";
        $message.= "Check the result to see CGPA\n";
        $message.= "Check detailed results by session/semester; and 
        Check School Fee Payment History.\n";
        $message.= "EDOCOHEST is poised to produce graduates who are not found wanting in CHARACTER and LEARNING.
        Be part of the character building process of your ward.\n";
        $message.= "Signed:\n";
        $message.= "Management, EDOCEO\n";
        $mail->MsgHTML($message);
            $mail->send();
            $mail->SmtpClose();
            $mail->ClearAllRecipients();
            } catch (Exception $e) {
            echo $e;
  }
  }
 }
}

 ?>
