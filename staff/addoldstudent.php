<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();
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
$error='';
$succ='';

if (isset($_POST["submitrecord"])) {
        
        if (empty($_POST["programm"]))
         {
             $error="Password did not match";
             $showModalEr='true';
         }elseif (empty($_POST["faculty"])) {
           $error="Student Admitted Session Required";
           $showModalEr='true';
         }
            else{
           
            $firstname=strtolower(mysqli_escape_string($con,$_REQUEST["firstname"]));
            $lastname=strtolower(mysqli_escape_string($con,$_REQUEST["lastname"]));
            
            $admy=strtolower(mysqli_escape_string($con,$_REQUEST["faculty"]));
            $userID=strtoupper(mysqli_escape_string($con,$_REQUEST["matricno"]));
            $username=strtoupper(mysqli_escape_string($con,$userID));
            $proms=strtoupper(mysqli_escape_string($con,$_REQUEST["programm"]));
            $email=strtolower(mysqli_escape_string($con,$_REQUEST["emails"]));
            $pho=strtoupper(mysqli_escape_string($con,$_REQUEST["phones"]));
            $escapedPW = mysqli_real_escape_string($con,$_REQUEST["password"]);
            $fnam=$firstname." ".$lastname;
            $hashedPW = hash('sha256', $escapedPW);
                $sql="SELECT * FROM studlogin WHERE staffid='$userID' AND status='active'";
                $res=mysqli_query($con,$sql);
                if (mysqli_num_rows($res) > 0) {
                  $row = mysqli_fetch_assoc($res);
                  if($userID==$row['staffid']) {
                    $error="Student ID ".$userID." already exist";
                    $showModalEr='true';
                  }elseif($email==$row['email']){
                       $error="Student EMail ".$email." already exist";
                       $showModalEr='true';
                  }
                }
                else
                  {
                //inserting to the database
                $qu=mysqli_query($con,"INSERT INTO studlogin(staffid,status,firstname,lastname,password,username,position,programme,acadsession,phone,email) VALUES ('$userID','active','$firstname','$lastname','$hashedPW','$username','student','$proms','$admy','$pho','$email')");
                    if($qu>0){
                              $succ='Successfully submited';
                              $showModal='true';
                               appmail($userID,$escapedPW,$fnam,$email,$pho,$orgname,$schadress,$logo,$con,$mail,$hostna,$passd,$usern);
                               sendsms($userID,$pho,$fnam,$escapedPW,$con,$orgname,$schadress);
                            }
                            else{
                                $error="Error Occured!!";
                                $showModalEr='true';
                            }
                }
            }
         

}
  //for status Updated
    if (isset($_POST['submitst'])) {
        //$staf = $_POST['staffid'];
        $posc =mysqli_real_escape_string($con,$_POST['newprive']);

        $upd = mysqli_query($con, "UPDATE studlogin SET status='$posc' WHERE staffid='".$_SESSION['stud']."'");
        if ($upd > 0) {
            $succ = 'Successfully Updated';
            $showModal = 'true';
            //newuserst($username,$userID,$position);
        } else {
            $err = "Error Occured While Submiting!!";
            $showModalEr = 'true';
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
        <style type="text/css">
            .field-icon {
              float: right;
              margin-left: -35px;
              margin-top: -35px;
              position: relative;
              z-index: 2;
            }
        </style>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
  <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                  }elseif ($paych['position']=="subadmin") {
                        include('includes/subbar.php');
                  }else if ($paych['position']=="sys_analyst")
                  { 
                    
                    include('includes/sysbar.php');

                    }elseif ($paych['position']=="subadministrator") {
                        include('includes/ictbar.php');
                  }elseif ($paych['position']=="admission") {
                        include('includes/admsec.php');
                  }elseif ($paych['position']=="registrar") {
                        include('includes/admoff.php');
                  }
                   else{
                  include('includes/leftbar.php');
                        }
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Add Existing Student (s) Setup</h2>
                                
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
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                     if ($paych['position']=="superadmin" OR $paych['position']=="subadministrator" OR $paych['position']=="subadmin" OR $paych['position']=="sys_analyst") {
                      ?>       
                                        <section class="section">
                                           <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Firstname:
                                                        </label>
                                                       
                                                            <input type="text" maxlength="38" name="firstname" class="form-control clid"  required="required">
                                                        
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Lastname:
                                                        </label>
                                                        
                                                            <input type="text" maxlength="38" name="lastname" class="form-control clid"  required="required">
                                                        
                                                      </div>
                                                      
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Password:
                                                        </label>
                                                        
                                                            <input type="password" maxlength="38" name="password" class="form-control" id="password-field" required="required" pattern="(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{6,}" style="text-transform: capitalize;"  placeholder="**********" maxlength="27" title="Your Password should contain atleat one uppercase and lowercase"><i toggle="#password-field" class="fa fa-fw field-icon fa-eye toggle-password"></i>
                                                        
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Student Programme:
                                                        </label>
                                                        <select class="form-control" name="programm" id="programme">
                                                        <option selected disabled>Please select Programme</option>
                                                          <?php
                                                              
                                                            
                                                            $resz = mysqli_query($con, "SELECT * FROM program ORDER BY pname");
                                                            while ($row = mysqli_fetch_array($resz)) {

                                                                ?>
                                                                <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                                                            <?php

                                                            }
                                                          ?>   
                                                        </select> 
                                                      </div>
                                                  </div> 
                                              </div> 
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                        <label for="default">
                                                          Student Matric No:
                                                        </label>
                                                           <input type="text" class="form-control" name="matricno" required="required">                                
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                          SESSION ADMITTED:
                                                        </label>
                                                        
                                                            <select class="form-control clid" name="faculty">
                                                                <option selected disabled>Please select Admitted Session</option>
                                                                <?php
                                                                $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                                                while ($row = mysqli_fetch_array($res)) {

                                                                    ?>
                                                                    <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                                                              <?php

                                                                }
                                                              ?>
                                                            </select>
                                                       
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Student Email:
                                                        </label>
                                                       
                                                            <input type="email" name="emails" class="form-control" placeholder="Enter Student Email" required>
                                                           
                                                        
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Student Phone:
                                                        </label>
                                                       
                                                            <input type="tel" name="phones" class="form-control"  placeholder="Enter Student Phone Number" maxlength="12" required>
                                                        
                                                      </div>
                                                      <div class="form-group">
                                                
                                                        <div class="col-sm-offset-1 col-sm-8">
                                                            <button type="submit" name="submitrecord" id="submit"  class="btn btn-primary form-control ">Submit Data</button>
                                                        </div>
                                                </div>
                                                  </div> 
                                              </div> 

                                            </form>
        </section>
        <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
        ?>
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
        <!-- /.main-wrapper -->
         
        <!-- Error Modal Box -->
            <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
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
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($succ) ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-success" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
        if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
    if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    } 
    if(!empty($showModall)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModall").modal("show");
            });
        </script>';
    } 
       ?>
       <script type="text/javascript">
      $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input=$($(this).attr("toggle"));
        //calling on the attribute of the password
        if (input.attr("type")=="password") {
            //Changing to Text
            input.attr("type","text");
        }else{
            input.attr("type", "password");
        }
    });

      $(".toggle-passwordc").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input=$($(this).attr("toggles"));
        //calling on the attribute of the password
        if (input.attr("type")=="password") {
            //Changing to Text
            input.attr("type","text");
        }else{
            input.attr("type", "password");
        }
    });
 </script>
    </body>
</html>
<?php
 }
/*
* appmail($userID,$escapedPW,$fnam,$email,$ph,$orgname,$schadress,$logo,$con,$mail);
  sendsms($userID,$pho,$fnam,$escapedPW,$con,$orgname,$schadress);
*/
function appmail($stud,$pass,$fuln,$ema,$ph,$orgname,$schadress,$logo,$con,$mail,$host,$passp,$user)
{     
    $url='https://edocohest.com';
        
        
    //$mail = new PHPMailer;
       try {
        
        $mail->isSMTP();
        
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
       $mail->Username = $user;                 // SMTP username
        $mail->Password =$passp;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->SetLanguage("en");
        $mail->SMTPAutoTLS = true;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        //$mail->Port = 465; 
        $mail->setFrom('portal@edocohest.edu.ng', 'EDOCOHEST LOGIN DETAILS');
       
        $mail->addAddress($ema);     
        $mail->isHTML(true);
        $mail->Subject = 'EDOCOHEST LOGIN DETAILS';
        $message ='<html> 
        <head> 
        <title>Edo State College of Health Science and Technology</title> 
        <style>
            .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color: #555;
        }
    
        .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
        }
    
        .invoice-box table td {
        padding: 5px;
        vertical-align: top;
        }
    
        .invoice-box table tr td:nth-child(2) {
        text-align: right;
        }
    
        .invoice-box table tr.top table td {
        padding-bottom: 20px;
        }
    
        .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 15px;
        color: #333;
        }
        
        .invoice-box table tr.information table td {
        padding-bottom: 40px;
        }
    
        .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        }
    
        .invoice-box table tr.details td {
        padding-bottom: 20px;
        }
    
        .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
        }
    
        .invoice-box table tr.item.last td {
        border-bottom: none;
        }
    
        .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
        }
    
        @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        }
        .rtl {
        direction: rtl;
        font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        }
    
         .rtl table {
        text-align: right;
        }
    
        .rtl table tr td:nth-child(2) {
        text-align: left;
        }

        </style>
        </head> 
        <body>';
    
        $message .='<div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="'.$logo.'" style="width:150px; height: 150px; max-width:150px;">

        </div>';
        
        $message .='<tr class="item">
                <td>
                    Full Name: <strong style="text-transform: capitalize;">'.$fuln.'</strong>
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                    Phone: '.$ph.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
            
        $message .='<tr class="item">
                <td>
                    Email: '.$ema.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
       
            $message.='<tr class="heading">
                <td>
                    Login Information
                </td>
                
                <td>
                    
                </td>
            </tr>';
            /*$stud,$pass,$fuln,$ema,$ph,*/
            $message.='<tr class="item">
                <td>
                   Username: '.$stud.'
                </td>
                
                <td>
                    
                </td>
            </tr>    ';
            $message.='<tr class="item">
                <td>
                   Password : '.$pass.'
                </td>
                
                <td>
                    
                </td>
            </tr>    ';
        $message .='</table>';
        $message .='<div class="divFooter" style=text-align:center>Copyright © '.$orgname.','.$schadress.';
                     </div>';
        $mail->MsgHTML($message);
         
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
       } catch (Exception $e) {
           
       }
   }

/*sendsms($userID,$pho,$fnam,$escapedPW,$con,$orgname,$schadress);*/
function sendsms($app,$ph,$fna,$pass,$con,$org,$sch){
  $sc=$org.' , '.$sch;
  $url="https://edocohest.com/login";
  $email = "imarve.wiz@gmail.com";
  $password = "Passw20ord";
  $message = "EDOCOHEST Portal LOGIN DETAILS \n";
  $message.= "Full Name: ".$fna."\n";
  $message.= "Username: ".$app."\n";
  $message.= "Password: ".$pass."\n";
  $message.= "Use this to complete your data\n";
  $message.= "Click Here To Login: \n".$url;
  $sender_name = "EDOCOHEST";
  $recipients = $ph;
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

?>
