<?php
session_start();
// ob_start();
 set_time_limit(500);
ini_set('max_execution_time', '500');
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
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
                                    //Here is insert the accademic session detail into the database
    $admy=$ses;
$succ='';
$error='';
if (isset($_POST['approve'])) {

      if (isset($_POST['checks'])) {
        foreach ($_POST['checks'] as $checks) {
          
      $update=mysqli_query($con,"UPDATE applicant SET status='Cleared' WHERE applid='$checks' AND session='$ses'");
      if ($update) {
        
        $succ='Application Successfully Cleared. Awaiting Provost Admission';
          $showModalS='true';
          //appmail($checks,$orgname,$schadress,$logo,$con,$mail,$hostna,$passd,$usern);
         //sendsms($checks,$con,$orgname,$schadress);
      }else{
        $error='An Error Occured While Confirming Data Please Try Again!!!';
        $showModalEr='true';
      }
      
    }
     }
    
    }
    if (isset($_POST["disapproved"])) {
        $mess=$_POST['messag'];
        $upda=mysqli_query($con,"UPDATE applicant SET status= 'rejected' WHERE applid='".$_SESSION['appli']."'");
        $ins=mysqli_query($con,"INSERT INTO rejectedam (applid,reason,acadsession) VALUES ('".$_SESSION['appli']."','$mess','$ses')");
        if ($upda) {
            disapprovedmail($con,$_SESSION['appli'],$ses,$mess,$mail,$orgname,$hostna,$passd,$usern);
            studsms($_SESSION['appli'],$ses,$mess,$con,$orgname);
            $succ='Candidate has been notify of your decision via Email And Sms';
            $showModalS='true';
            }else{
                $error='An Error Occured While Rejecting Data Please Try Again!!!';
               $showModalEr='true'; 
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
      <style type="text/css">
          #blah{
                width:170.256px;
                height:159px;
                border:2px solid black;
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

                  if ($paych['position']=="registrar")
                  {
                     include('includes/admoff.php');

                  }elseif ($paych['position']=="admission") {
                        include('includes/admsec.php');
                  }elseif ($paych['position']=="superadmin") {
                        include('includes/supbar.php');
                  }elseif ($paych['position']=="subadmin") {
                        include('includes/subbar.php');
                  }elseif ($paych['position']=="admin_officer") {
                        include('includes/admof.php');
                  }
                   else{
                  include('includes/leftbar.php');
                        }
                    ?> 
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Bulk Admission Clearance For <?php echo $admy ?> Setup</h4>
                                
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

                  if ($paych['position']=="registrar" OR $paych['position']=="subadmin" OR $paych['position']=="admin_officer" OR $paych['position']=="superadmin")
                  {
                     ?>   
                                             <section>
                                                    <form action="" method="post">
                <table id="example2" class="table table-striped table-bordered">
                    <thead>
                        <?php 
                        $sql = "SELECT * FROM applicant JOIN personaldata ON personaldata.applid=applicant.applid WHERE (applicant.status='Pending' OR applicant.status='rejected') AND applicant.session='$admy' ORDER BY applicant.applid";
                            $query = mysqli_query($con,$sql);
                            $num_rows = mysqli_num_rows($query);
?>
                            
            <tr><td>Total:</td><td><span style="color: red"
                        ><strong><?php echo $num_rows;?> Applications</strong></span></td> </tr> 
                    <tr>
                        <th>#</th>
                        <th>Admission ID</th>
                        <th>Full Name</th>
                        <th>Programme</th>
                        <th>Session</th>
                        <th>Session</th>
                        <th>Apply Date</th>
                        <th>View</th>
                        <th> <input type="checkbox" name="check" id="check" title="Please click on the check box"> CheckAll</th>
                       
                    </tr>
                </thead>
                <tbody>
                   <?php
                              $sql = "SELECT * FROM applicant JOIN personaldata ON personaldata.applid=applicant.applid WHERE (applicant.status='Pending' OR applicant.status='rejected') AND applicant.session='$admy' ORDER BY applicant.applid";
                            

                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                           
                            foreach($results as $result)
                            {  
                $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$result['applid']."'"));
                   $date=$dat['date'];
                                    stuname($result['applid'],$con);
                             ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['applid']) ?></td>
                                <td><?php echo htmlentities($_POST["harrd"]);?></td>
                                <td><?php echo htmlentities($_POST['prog']);?></td>
                                <td><?php echo htmlentities($result['session']);?></td>
                                <td><?php echo htmlentities($_POST['stat']);?></td>
                                <td><?php echo $date ?></td>
                                <td> <span class="btn btn-primary userinfo" id="<?php echo $result['applid']?>"><i class="fa fa-eye"></i> </span> </td>
                                <td><input type="checkbox"   value="<?php echo $result["applid"]; ?>" name="checks[]" class="checkitem" id="checkitem"></td>
                                
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }//} ?> 
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            
                                                            <th colspan="8"><input type="submit" name="approve" style="float: right;" class="btn btn-primary fa fa-check"  value="Clear Students"> 
                                                            </th>
                                                           </form>
                                                        </tr>
                                                    </tfoot>
                                                   
                                                    </table>
                                                </section>
                                                <div class="modal" id="viewModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">APPLICANT DATA</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        
                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_details">

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                    <form method="post">
<table>
                                <tr>
                                    <th>
                       
                        <button name="disapproved" class="btn btn-danger sendButton" style="float: left;margin-top:15px;" >Reject Student <i class="fa fa-close"></i> </button>
                        <textarea id="mess" placeholder="Specify Reason for Rejecting Please" name="messag" style="resize: none;scroll-behavior: none;width: 550px;float: right;"></textarea>
                        
                    </form>
                     </th>
                     <th>
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                           
                        </th>
                         </tr>
                         </table>
                           
                        </div>
                    </div>
                </div>
            </div>
    
                <?php
                    }else{
                  ?>
                  <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                  <?php
                        }
                    ?> 
                                          <!-- End of Section -->
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
        <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please Select <?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
                
            <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalS" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($succ) ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
                            <p><?php echo htmlentities($error) ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Error Modal Box -->
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>There No Result Base on the Criterial Selected</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
   
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
        ?>
        <script type="text/javascript">
             $(document).ready(function(){
 $('#check').click(function(){
    if($(this).is(":checked"))
        $('.checkitem').prop('checked',true);
    else
    $('.checkitem').prop('checked',false);
});
});

$(document).ready(function(){
    $('.sendButton').attr('disabled',true);

        $('#mess').keyup(function(){
        if($(this).val().length !=0){
        $('.sendButton').attr('disabled', false);
        }
        else
        {
        $('.sendButton').attr('disabled', true);        
        }
    });      
      
});

        </script>
        <?php           
    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
    if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
            });
        </script>';
    }
    if(!empty($showModalS)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalS").modal("show");
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
?>
<script type="text/javascript">
    $(document).ready(function() {

                    //$('.userinfo').click(function() {
                     $('body').on('click','.userinfo',function() {
                        var userid = $(this).attr('id');
                        
                        $.ajax({
                            url: 'jambdata',
                            type: 'POST',
                            data: {
                                userid: userid
                            },
                            success: function(response) {
                                $('#teacher_details').html(response);
                                // Display Modal
                                $('#viewModal').modal('show');
                            }
                        });
                    });
                });
</script>
    </body>
</html>
<?php } 
function stuname($maxt,$con)
{
      $sql =mysqli_query($con,"SELECT *  FROM  personaldata WHERE applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
         $_POST["maks"]="Nil";
         $_POST["prog"]="Nil";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $maks=$data['state'];
        $_POST['prog']=$data['programme'];
        $name =$sur." ".$first." ".$las;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
        $dds=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$maxt'"));
    $_POST['stat']=$dds['status'];
}
function appmail($stud,$orgname,$schadress,$logo,$con,$mail,$host,$pass,$user)
{     
    $url='https://edocohest.com/clearance/';
        
        
    //$mail = new PHPMailer;
       try {
        
        $mail->isSMTP();
        
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
       $mail->Username =$user;                 // SMTP username
        $mail->Password =$pass;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->SetLanguage("en");
        $mail->SMTPAutoTLS = true;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        //$mail->Port = 465; 
        $mail->setFrom($user, 'Application Data Confirmation');
        //$mail->addAddress($mal);
        $sts=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$stud'");
          while ($result=mysqli_fetch_array($sts)){
        $mail->addAddress($result['email']);
        $_POST["fname"]=$result["firstname"];
        $_POST["sname"]=$result["surname"];
        $programm=$result["programme"];
        $phone=$result["phone"];
        $nmk=$_POST["fname"]." ".$_POST["sname"];     
        $mail->isHTML(true);
        $mail->Subject = 'Application Data Confirmation';
        $message ='<html> 
        <head> 
        <title> University Of Africa</title> 
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
        $message .='<div> Congratulation Dear: '.$nmk.' with Admission ID '.$stud.' The data you provided has been verified  <br> kindly login to clearance portal '.$url.' to continue your admission process.
Thank you<br> Thank you</div>';
        $message .='<div class="info">
             Once Again Congratulation. 
        </div>';
        $message .='<div class="divFooter" style=text-align:center>Copyright © '.$orgname.','.$schadress.';
                     </div>';
        $mail->MsgHTML($message);
         }
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
       } catch (Exception $e) {
           
       }
}
function sendsms($stud,$con,$org,$sch){
    $sc=$org.' , '.$sch;
$url="https://edocohest.com/clearance/";
$query=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$stud'");
$results = mysqli_fetch_all($query,MYSQLI_ASSOC);
foreach($results as $result)
{ 
   $_POST["fname"]=$result["surname"];
   $_POST["sname"]=$result["firstname"];
   $pro=$result["programme"];
   $phone=$result["phone"];
   $nmk=$_POST["fname"]." ".$_POST["sname"];
$email = "imarve.wiz@gmail.com"; 
$password = "Passw20ord"; 
  $message= "Dear: ".$nmk." with\n";
$message .= "Admission ID: ".$app." \n";
$message.= "The data you provide has been confirmed kindly Login to\n";
$message.= $url." to continue your admission processing. \n"; 
$sender_name = "EDOCOHEST";
$recipients=$result["phone"];
$forcednd = "1"; 
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
function studsms($stud,$ses,$mess,$con,$orgname){
  $email = "imarve.wiz@gmail.com";
  $password = "Passw20ord";
  
  /*Retrieving The Staff Record Start Here*/
  $stf=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$stud' ");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  $message = "Dear: ".$stn.",\n";
  $message.= "Your detail provided to ".$orgname." for admission consideration in ".$ses." was rejected\n ";
  $message.= "Reason: ".$mess." \n";
  $message.= "Thank You.";
  $sender_name = "EDOCOHEST";
  $recipients = $res['phone'];
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
//disapprovedmail($con,$_SESSION['appli'],$ses,$mess,$mail);
function disapprovedmail($con,$stude,$sez,$mes,$mail,$orgname,$host,$pass,$user)
{
   $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$stude'"));
    //die($maix);
        $to = $rows['email'];
        $fnat=strtoupper($rows['surname']).' '.$rows['firstname'].' '.$rows['lastname'];
       try {
        
        $mail->isSMTP();
        
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
       $mail->Username =$user;                 // SMTP username
        $mail->Password =$pass;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->SetLanguage("en");
        $mail->SMTPAutoTLS = true;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom($user, 'Admission Rejection Notification');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Admission Rejection Notification';
        $message='<p><strong>Dear: '.$fnat.' </strong></p>';
        $message.='<p><strong>Your detail provided to '.$orgname.' for admission consideration for '.$sez.' Academic Session was rejected for the following reason. <br/> <b style="color:red;">'.$mes.'</b></strong></p>';
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAllRecipients();
        } catch (Exception $e) {
            
        }
        //return true;
    }
?>