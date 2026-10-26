<?php 
session_start();
$connection = TRUE;
include "../include/dbconfig.php";
include 'dataco.php';
$harr=$_SESSION["staffid"];
if (!isset($_SESSION['staffid']))
    header("Location: ../logout");
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='exam processing'"));
$sems=$dat['semester'];
$ses=$dat['session'];
$dead=$dat['deadline'];
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
if (isset($_POST['approved'])) {
   $student=mysqli_escape_string($con,$_POST['student']);
    $date = date('Y-M-d'); 
    $newd = date('Y-m-d', strtotime($date. ' + 3 days')); 
    $upda=mysqli_query($con,"UPDATE lateresup SET status= 'Approved',timeframe='$newd',respdate=NOW() WHERE staffid='$student' AND semester='$sems' AND acadsession='$ses' ");
    if ($upda) {
    approvedmail($con,$student,$sems,$ses);
    smshod($con,$student,$sems,$ses);
    personals($_SESSION['staffid'],$sems,$ses,$con);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <style>
                    
        .time{
            color:red;
            margin-right: 34px;
            font-size: 13px;
        }
        .infon{
            color:green;
        }
        
</style>
</head>
<body  class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php');?> 
        <div class="content-wrapper">
                <div class="content-container">
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="hod")
                  {
                    
                     include('includes/hodbar.php');

                    }else if ($paych['position']=="lecturer") {
                        
                   
                    include('includes/lectbar.php');
                     }elseif ($paych['position']=="dean") {
                        include('includes/deanbar.php');
                    }elseif ($paych['position']=="superadmin") {
                        include('includes/supbar.php');
                    }
                    else{
                   
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title">STAFF EXTRA TIME REQUEST</h4>
                                </div>
                                
                            </div>
                            
                            <!-- /.row -->
                        </div>
                    <section class="section">
                            <div class="container-fluid">
                             <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                        <div class="panel-body">
                    <div>
                        <table id="example" class="table table-striped table-bordered " cellspacing="0">
                            <thead>
                                
                                <tr>
                                    <th>#</th>
                                    <th>staff_ID</th>
                                    <th>Name</th>
                                    <th>Session</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                            <?php

                            $sql ="SELECT * FROM lateresup JOIN people ON people.staffid=lateresup.staffid WHERE  lateresup.status='Pending' AND lateresup.acadsession='$ses' AND lateresup.semester='$sems' AND lateresup.facu='$faclt' ";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            { 

                           ?>
                           <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result['staffid']) ?></td>
                            <td><span style="text-transform:capitalize;"><?php echo htmlentities($result['title'].". ".$result['firstname']." ".$result['lastname']);?></span></td>
                            <td><?php echo htmlentities($result['acadsession']);?></td>
                            <td><?php echo htmlentities($result['semester']);?></td>
                            <td><?php echo htmlentities($result['status']);?></td>
                            <td>
                            <button type="button" class="userinfo btn btn-primary" 
                                data-studentid="<?php echo $result['staffid'];?>" 
                                data-semester="<?php echo $result['semester'];?>" 
                                data-session="<?php echo $result['acadsession'];?>"
                                href="#detailsmodal" data-target="#detailsModal">View Request</span></button> 
                            </td>
                            </tr>
                           <?php
                           $cnt=$cnt+1;
                            }
                             //}
                            ?>
                            </tbody>
                            
                            </table>

                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-8 col-md-offset-2 -->
                                </div>
                                <!-- /.row -->

                               <div class="modal" id="viewModal">
                                      <div class="modal-dialog">
                                        <div class="modal-content">

                                          <!-- Modal Header -->
                                          <div class="modal-header">
                                            <h5 class="modal-title">STAFF REQUEST INFORMATION</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <!-- Modal body -->
                                          <div class="modal-body" id="teacher_details">

                                          </div>
                                        </div>
                                      </div>
                                </div> 
                            <!-- /.container-fluid -->
                        </section>
                    </div>
                </div>
        </div>
    </div>
</body>
<?php 
include"includes/buton.php";?>


<script>
$(document).ready(function(){
    $('body').on('click','.userinfo',function() {
var applid = $(this).attr('data-studentid');
var ssid = $(this).attr('data-semester');
var sess = $(this).attr('data-session');
var prog = $(this).attr('data-prog');
var level = $(this).attr('data-level');

// AJAX request
$.ajax({
url: 'extratime',
type: 'POST',
data: {applid: applid,ssid:ssid,sess:sess,prog:prog,level:level},
success: function(response){ 
$('#teacher_details').html(response);

      // Display Modal
      $('#viewModal').modal('show');
}
});
});
}); 
</script>
</html>
<?php 
//Emailing Section
function approvedmail($con,$stud,$sm,$se)
{
    
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people  WHERE staffid='$stud'"));
    //die($maix);
        $to = $rows['uniemail'];
        require_once "phpmailer/class.phpmailer.php";
        $mail = new PHPMailer;
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Extra Time Result Uploading Request Approval');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Extra Time Request Approval';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['title'].'. '.$rows['firstname']. ', '. $rows['lastname'].'</b></span>';
          $message .= '<br>';
          $message .= '<br>';
          $message.='<p><strong>Please Kindly Note: That Your Request For Extra Time to Approve '.$sm.' '.$se.' academic session have been Approved. Login to University Portal to Approve your Result Within 3 Days<br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}
function smshod($con,$student,$sems,$ses)
{
/*Sms Authentication*/
$email = "okpehjoh@gmail.com";
$password = "Testing123";

$stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people WHERE staffid='$student'"));
    $to=$stm['phoneno'];
    $fun=$stm['firstname'];
    $message = "Dear: ".$fun.",\n";
  $message.= "Please Kindly Note: That Your Request For Extra Time to Approve ".$sm." ".$se." academic session have been Approved. Login to University Portal to Approve your Result Within 3 Days \n";
  $message.= "Thank You";
  $sender_name = "UAT";
  $recipients = $to;
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


function personals($staf,$sems,$ses,$con)
{ 
  
  //deanmail($pds,$secs,$semc);
  require_once "phpmailer/class.phpmailer.php";
  
  //Loading Lecturer Information
  $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$staf'"));
    $to=$stm['uniemail'];
   //echo $to;
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                // Enable SMTP authentication
    $mail->Username = 'my@uat.edu.ng';                 // SMTP username
    $mail->Password = '12345@uat';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; 
    $mail->setFrom('my@uat.edu.ng', 'Extra Time Request Approval');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Extra Time Request Approval';
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear '.$fun.' </strong></p>';
    $message .= '<p><strong>Your Have  Approved The Request for Extra Time Request  for <br/> Semester: '.$sems.'<br> Academic Session: '.$ses.'</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}
?>