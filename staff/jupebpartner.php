<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();
if (strlen($_SESSION['staffid']) == "") {
        header("Location: ../logout");
    } else {
    $now = time(); // Checking the time now when home page starts.
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location:../logout");
    }
    $error = '';
    $succ = '';
    /*$showModal = "true";
$showModalEr='true';
*Remember to Implement Welcome email to new User
*/
    if (isset($_POST["submitrecord"]))
     {
        
                $firstname = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["firstname"])));
                $lastname = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["lastname"])));
                $unemal = trim(strtolower(mysqli_escape_string($con,$_REQUEST["emails"] )));
                $phoned = strtolower(mysqli_escape_string($con, $_REQUEST["phonel"]));

                $hashedPW = hash('sha256', $phoned);
                $today=date("Y-m-d");
                $sql = "SELECT * FROM partapp WHERE (email='$unemal' OR phoneno='$phoned') AND status='active'";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) > 0) {
                $error='Staff Email '.$unemal.' already Exist';
                $showModalEr='true';
            } else {
                $id = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(partid) as userid FROM partapp"))['userid'];
                $id = $id + 1;
                $date = date('Y');
                $yr = substr($date, -2);
                $userID = "UATJP". $yr . "" . str_pad($id, 2, '0', STR_PAD_LEFT);
                $qu = mysqli_query($con, "INSERT INTO partapp(partid,phoneno,email,password,status,firstname,lastname,applicationDate) VALUES ('$userID','$phoned','$unemal','$hashedPW','active','$firstname','$lastname','$today')");
                   
                    if ($qu > 0) {
                        studentemail($con,$userID,$phoned,$orgname,$schadress,$logo,$mail,$host,$usern,$pass);
                        $name=$firstname.' '.$lastname;
                        studensms($phoned,$userID,$name);
                        $succ = 'Successfully Submited';
                        $showModal = 'true';
                    } else {
                        $error = "Error Occured While Submiting!!";
                        $showModalEr = 'true';
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
        <title><?php echo htmlentities($orgname); ?></title>
        <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/main.css" media="screen">
        <link rel="stylesheet" href="css/animate.min.css" media="screen">
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
            <?php include('includes/topbar.php'); ?>
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                  }elseif ($paych['position']=="subadmin") {
                        include('includes/subbar.php');
                  }
                  else if ($paych['position']=="sys_analyst")
                  {
                    
                    include('includes/sysbar.php');

                    }

                   else{
                  include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Enter New Jupeb Details</h2>

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

                                          if ($paych['position']=="superadmin" OR $paych['position']=="subadmin" OR $paych['position']=="sys_analyst" )
                                          { ?>
                                            <section class="section">
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Firstname:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="firstname" class="form-control clid" required="required" placeholder="Enter Partner Firstname">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Lastname:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="lastname" class="form-control clid" placeholder="Enter Partner Lastname" required="required">
                                                                
                                                            </div>
															
                                                          
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="default" >
                                                                    Email:
                                                                </label>
                                                                
                                                                    <input type="email" maxlength="38" name="emails" class="form-control clid" required="required" placeholder="Enter Partner Email">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Phone No:
                                                                </label>
                                                                
                                                                    <input type="tel" name="phonel" class="form-control clid" maxlength="14" placeholder="Enter Partner Phone" required>
                                                                
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <div class="">
                                                                    <button type="submit" name="submitrecord" id="submit" class="btn btn-primary form-control">Submit Data</button>
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
            if (!empty($showModal)) {
                // CALL MODAL HERE
                echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
            }
            if (!empty($showModalEr)) {
                // CALL MODAL HERE
                echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
            }
            if (!empty($showModall)) {
                // CALL MODAL HERE
                echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModall").modal("show");
            });
        </script>';
            }
            ?>
    </body>

    </html>
<?php 
}
function studensms($ph,$userid,$name)
  {
  $url="https://my.uat.edu.ng/partnerj/";
  $email = "okpehjoh@gmail.com";
  $password = "Testing123";
  $message = "YOUR UAT JUPEB PARTNER LOGIN DETAILS \n";
  $message.= "Username: ".$userid."\n";
  $message.= "Password: ".$ph."\n";
  $message.= "Click Here To Login: \n".$url;
  $sender_name = "JupebUAT";
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
  //print_r($res_array); 
}
function studentemail($con,$appliD,$Pass,$orgname,$schadress,$logo,$mail,$host,$usern,$pass)
    {
    $url='https://my.uat.edu.ng/partnerj/';
        
    
    $dt=date("D,d M Y ");
        $df=date("h:i:sa");
        $date= $dt." By ".$df;

        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM partapp WHERE partid='$appliD'"));
        $_POST["fname"]=$dat["firstname"];
        $_POST["sname"]=$dat["lastname"];
        $phone=$dat["phoneno"];
        $mal=$dat["email"];
        $nmk=$_POST["fname"]." ".$_POST["sname"];
        
       try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        //$mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->Host =$host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->Priority = 1;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->setFrom($usern, ' UAT Jupeb Partner Login Detail');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'UAT Jupeb Partner Login Detail';
        $message ='<html> 
        <head> 
        <title>University Of Africa</title> 
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
            <img src="../'.$logo.'" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="width: 300px;margin: auto;">
            <h5><span style="font-size: 18px;">'.$orgname.'</span> , <span style="font-size: 18px;">'.$schadress.'</span><br> <span style="margin-left: 25px;font-size: 15.5px;font-weight: bold;">UAT Partner Login.</span></h5>
        </div>';
        $message .=' <table cellpadding="0" cellspacing="0">';
        $message .='<tr class="item">
                <td>
                    Partner ID: '.$appliD.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                    Full Name: <strong style="text-transform: capitalize;">'.$nmk.'</strong>
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                    Phone: '.$phone.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                    Email: '.$mal.'
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
            $message.='<tr class="item">
                <td>
                    Login ID: '.$appliD.'
                </td>
                
                <td>
                    
                </td>
            </tr>    ';
            $message.='<tr class="item">
                <td>
                 Login Password: '.$pass.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
            $message.='<tr class="item">
                <td>
                 URL: '.$url.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='</table>';
        $message .='<div class="info">
             Please Kindly Login to Complete Your Application. 
        </div>';
        $message .='<div class="divFooter" style=text-align:center>Copyright © University Of Africa;
                     </div>';
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
       } catch (Exception $e) {
           
       }
    }     
?>
