<?php
session_start();
$connection = TRUE;
include('../include/dbconfig.php');
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location:logout"); 
    }
    else{
       
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();
$error='';
$succ='';

include 'dataco.php';
if (isset($_GET["del"])) {
$apidf=trim(mysqli_escape_string($con,$_GET["del"]));
$query = mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$apidf' AND studentid='".$_SESSION['staffid']."'");
$datag = mysqli_fetch_array($query);
$nt="0.01";
    if ($datag!="")
    {    
     $ref=$datag['paymentreference'];
     $apid=$datag['studentid'];
     $courseID=$datag['purpose'];
     $typ=$datag['paytype'];
     $levl=$datag['level'];
      /*Checking Paysplit Array to See If Verifying Payment Option belong to the Group */
    
        $tranx = confirmPayment($ref);
     if ($tranx->status) {
        if ('success' == $tranx->data->status) {
            $status = $tranx->data->status;
            $msg = $tranx->message;
            $amt=$tranx->data->amount*$nt;
            $mailt=$tranx->data->customer->email;
            $date = date('Y-M-d'); 
            $newd = date('Y-m-d', strtotime($date. ' + 15 days'));
            $mysg=mysqli_query($con,"UPDATE failpayment SET remtime='$newd' WHERE paymentreference='$ref'");
            $querr=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ref'"));
            //$repa=$querr['paymentreference'];
            if ($querr!='')
            {
               $error="Payment Datail Already Verified Or Kindly Visit ICT With Your Paymentreference ID: ".$apidf;
            $showModalEr='true';
            }else{
                if($courseID=='Personal Transcript')
                {
                    /* Retrieving Personal Transcript Payment Record From the Failed Transaction Table */
                    if (getCourseAmount($courseID, $con) <= $tranx->data->amount) {
                        $querb = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$ref' AND purpose='$courseID' AND studentid='".$_SESSION['staffid']."'  "));
                        $admy=mysqli_escape_string($con,$querb['section']);
                        $staf=mysqli_escape_string($con,$querb['studentid']);
                        $progt = mysqli_escape_string($con,$querb['programme']);
                        $tday = mysqli_escape_string($con,$querb['paytime']);
                       
                        $courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$courseID' "));
                    $amount = $courseDetails['amount'];
                    $mode=$tranx->data->channel;
                    $payment = mysqli_query($con, "INSERT INTO studentpayment(studentid,section,programme,purpose,amount,modes,paymentreference,paytime,status) VALUES ('".$_SESSION['staffid']."','$admy','$progt','$courseID','$amount','$mode','$ref','$tday','$status') ");
                        if ($payment) {
                            mysqli_commit($con);
                           studentemailp($ref,$orgname,$schadress,$logo,$con,$mail);
                           $showModalErd='true';
                           $succ="Payment Verification is Success";
                        } else {
                            mysqli_rollback($con);
                            //mysqli_close($con);
                            $showModalEr='true';
                            $error="Could not Process Your admission, Kindly Contact Web Master. Do not repeat this Application ";
                            }
                    }else{
                        $error="Amount Send is Less Than Expected Amount";
                        $showModalEr='true';
                        } 
                }else{
                    /* Retrieving Official Transcript Request Copy From the Ins */
                    if (getCourseAmount($courseID, $con) <= $tranx->data->amount) {
                        $querb = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$ref' AND purpose='$courseID' AND studentid='".$_SESSION['staffid']."'  "));
                        $admy=mysqli_escape_string($con,$querb['section']);
                        $staf=mysqli_escape_string($con,$querb['studentid']);
                        $progt = mysqli_escape_string($con,$querb['programme']);
                        $tday = mysqli_escape_string($con,$querb['paytime']);
                        $addins = mysqli_escape_string($con,$querb['firstname']);
                        $addrs = mysqli_escape_string($con,$querb['lastname']);
                        $emasd = mysqli_escape_string($con,$querb['email']);
                        $courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$courseID' "));
                    $amount = $courseDetails['amount'];
                    $mode=$tranx->data->channel;
                    $payment = mysqli_query($con, "INSERT INTO studentpayment(studentid,programme,purpose,amount,modes,paymentreference,paytime,status) VALUES ('".$_SESSION['staffid']."','$progt','$courseID','$amount','$mode','$ref','$tday','$status') ");
                        if ($payment) {
                            mysqli_commit($con);
                            studentemail($ref,$orgname,$courseID,$schadress,$logo,$con,$mail);
                            studmail($std,$addins,$addrs,$con,$mail);
                            coursinsert($con,$std,$courseID,$addins,$addrs,$emasd);
                           $showModalErd='true';
                           $succ="Payment Verification is Success";
                           
                        } else {
                            mysqli_rollback($con);
                            //mysqli_close($con);
                            $showModalEr='true';
                            $error="Could not Process Your admission, Kindly Contact Web Master. Do not repeat this Application ";
                            }
                    }else{
                        $error="Amount Send is Less Than Expected Amount";
                        $showModalEr='true';
                        }
                }
                
            }
           
        }else{
            $status=$tranx->data->status;
            
            $error='Payment Status: '.ucfirst($status);
            $showModalEr='true';
            
            
        }

    }else{
        $error="Transaction Not Initiated Out";
        $showModalEr='true';
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
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <link rel="stylesheet" href="css/coms.css">
    <link rel="stylesheet" href="css/receipt.css">
    
    </head>
<body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
   <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
<?php include('includes/leftbar.php');?> 
<style>
    .ttable th,.ttable td{
    border: 2px solid black;
    padding: 5px 5px;
    font-size: 15px;
}
    
    @media print{
            .ttable{
                width:1000px;
                margin:auto;
                border: 1px solid black;

            }
td,th{
    border: 1px solid black;
    cellpadding:0px;
    cellspacing:0px;
    padding: 5px 5px;
    font-size: 15px;
}
.td{
    display: none;
}
        }
    </style>
    
    <div class="main-page">
        <div class="container-fluid">
            <div class="row page-title-div">
                <div>
                    <h3 class="title"><?php echo "STUDENT FAILED PAYMENTS HISTORY SETUP"; ?></h3>

                </div>

                <!-- /.col-md-6 text-right -->
            </div>

            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

        <section class="section">
            <div class="container-fluid">



                <div class="row">
                    <div class="col-md-12">

                        <div class="panel">


                            <div class="panel-body p-20">
                                <div class="invoice-box">
                                    <div style="width: 200px;margin: auto;">
            <img src="<?php echo htmlentities($logo)?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="width: 300px;margin: auto;">
            <h5><span style="font-size: 17px;"><?php echo htmlentities($orgname.",".$schadress) ;?>
       </span></h5>
        </div>
        <hr>
        <div>
        <span style='position:absolute;margin-left:
          500px;margin-top:3px;width:170px;height:170px'><img width=170 height=170
          src="../student/<?php echo $passport ?>" alt="Photo "></span><b>Student Name: <?php echo htmlentities($fullname) ?></b></span>
          <br><br>
          <span class=MsoNormal><b>Matric No: <?php echo htmlentities($_SESSION['staffid']); ?></b></span>
          <br><br>
          <span class=MsoNormal><b>Email: <?php echo htmlentities($unmail) ?></b></span>

          <br><br><span class=MsoNormal><b>Faculty: <?php echo htmlentities($facu) ?> </b></span>
          <br><br><span class=MsoNormal><b>Department: <?php echo htmlentities($def) ?> </b></span>
          <br><br>
          <span class=MsoNormal><b>Programme: <?php echo htmlentities($progr) ?> </b></span>
          <br>
      </div>
                            
                                    <div align="center">
                                        <h3>YOUR FAILED
                                           FEES
                                            PAYMENT DETAILS</h3>
                                    </div>

        <table border="1" cellpadding="0" class="ttable" cellspacing="0" width="800">
            <tr class="heading">
                <th>#</th>
                <th>Session</th>
                <th>Amount Paid</th>
                <th>Level</th>
                <th>Semester</th>
                <th>Purpose</th>
                <th>Reference</th>
                <th>Date</th>
                <th>Status</th>
                <th class="td" colspan="2">Action</th>
            </tr>
            <?php
            
            $sql = "SELECT * FROM failpayment WHERE (purpose='Transcript Outside Nigeria' OR purpose='Personal Transcript' OR purpose='Transcript Within Nigeria') AND studentid='".$_SESSION['staffid']."'";
                                               
            $query = mysqli_query($con,$sql);
            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
            $cnt=1;
                                               
            foreach($results as $result)
            {   ?>
            <tr>
         <td><?php echo htmlentities($cnt);?></td>
            <td><?php echo htmlentities($result['section']);?></td>
            <td>&#8358;<?php echo htmlentities(number_format($result['amount'],2));?></td>
            <td><?php echo htmlentities($result['level']);?></td>
            <?php
                if ($result['semester']!="") {
                    ?>
                 <td><?php echo htmlentities($result['semester']);?>
                     
                 </td>   
              <?php  
                }else{
                
                ?>
               <td><?php echo 'Full';?></td>
                <?php
              }
             ?>
            <td><?php echo htmlentities($result['purpose']);?></td>
            <td><?php echo htmlentities($result['paymentreference']);?></td>
            <td><?php echo htmlentities($result['paytime']);?></td>
            <td><?php echo htmlentities($result['status']);?></td>
            
            <td>
            <a href="payverification?del=<?php echo $result['paymentreference']?>">
                            <button class="btn btn-primary">Verify <i class="fa fa-check-circle"></i> </button>
            </a>
           
        </td>
           
        <!-- Remember to continue from Here Tomorow Please -->
        
        </tr>
        <?php 

        $cnt=$cnt+1;
         
        }

        ?>
               
            
        </table>
                              
        </div>
                                <!--End of invoice-->
                                <!-- /.col-md-12 -->
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->


                </div>
                <!-- /.col-md-12 -->
            </div>
    </div>
    <!-- /.panel -->
    </div>
                                    <!-- /.col-md-6 -->
 <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-credit-card"></i>Successfull Verification Page</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                           <p>Your Payment Was <?php echo htmlentities($status) ?></p>
                        </div>
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                       
                       </div>
                    </div>
                </div>
            </div> 
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

            <div class="modal fade" id="checkModalErd" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($succ); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
                   
    </div>
                                <!-- /.row -->

    </div>
                            <!-- /.container-fluid -->
    </section>
                        <!-- /.section -->

    </div>
                    <!-- /.main-page -->
                    </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        
<?php 
include'includes/buton.php';
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
    if(!empty($showModalErd)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalErd").modal("show");
            });
        </script>';
    }
?>
        </div>
        </body>
<?php }
function confirmPayment($ref)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.paystack.co/transaction/verify/".rawurlencode($ref),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "authorization: Bearer sk_live_8a686c7fa2904c72622135d207891ab8b092a62b",
        "cache-control: no-cache"
      ],
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    //echo $response;
    if($err){
        // there was an error contacting the Paystack API
      return json_decode('{"status": false, "message": "Could not Connect to paystack. API Timeout. "}');
    }else{
        return json_decode($response);
    }
    
    

}

    function getCourseAmount($course, $con)
    {
        return mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$course' "))['amount'];
    }
    /* coursinsert($con,$std,$courseID,$addins,$addrs,$emasd); */
    function coursinsert($con,$std,$payt,$instn,$adres,$emal)
    {
        $tod=date("Y-m-d");
     $add='Official Transcript';   
    $inst=mysqli_query($con,"INSERT INTO transcriptre(studentid,transtype,offtype,orgnam,orgemal,orgadd,reqsatus,datecreate) VALUES('$std','$add','$payt','$instn','$emal','$adres','Pending','$tod')");
    }
    /* studentemailp($ref,$orgname,$schadress,$logo,$con,$mail); */
function studentemailp($refe,$orgname,$schadress,$logo,$con,$mail)
    {
    
    $dt=date("D,d M Y ");
        $df=date("h:i:sa");
        $date= $dt." By ".$df;

        $data=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE paymentreference='$refe'"));
    
        $appliD=$data["studentid"];
        $amon=$data["amount"];
        $refe=$data["paymentreference"];
        $tp=$data["purpose"];
        

        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$appliD'"));
        $programm=$dat["programme"];
        $phone=$dat["phoneno"];
        $mal=$dat["uniemail"];
        $nmk=$dat["lastname"].", ".$dat["firstname"]." ".$dat["othername"];
       try {
        $mail->isSMTP();
        //$mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password
        $mail->SMTPKeepAlive = true;
        //$mail->Priority = 1;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->setFrom('my@uat.edu.ng', 'Personal Transcript Payment Receipt');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'Personal Transcript Payment Receipt';
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
            <h5><span style="font-size: 18px;">'.$orgname.'</span> , <span style="font-size: 18px;">'.$schadress.'</span><br> <span style="margin-left: 25px;font-size: 15.5px;font-weight: bold;">Official Receipt of Payment.</span></h5>
        </div>';
        $message .=' <table cellpadding="0" cellspacing="0">';
        $message .='<tr class="item">
                <td>
                   Student ID: '.$appliD.'
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
        $message .='<tr class="item">
                <td>
                    Programme: '.$programm.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="heading">
                <td>
                    Payment Information
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .=' <tr class="item">
                <td>
                    Amount Paid: &#8358; '.number_format($amon,2).'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                  Reference ID: '.$refe.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                  Purpose: '.$tp.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
        $message .='</table>';
        $message .='<div class="info">
             Your Payment was successfull. 
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
    /* $emasd */
    function studmail($stud,$add,$payt,$con,$mail)
    {
    
    
        $to = 'academic.affairs@uat.edu.ng';
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Transcript Request Notification');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Transcript Request Notification';
        //$content = 
        $message = '<html><body> 
        '; 
          
          $message.='<p><strong style=text-align:justify;>Please Kindly Note: That there is a Pending Transcript Request that need your action<br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}       
function studentemail($refe,$orgname,$sect,$schadress,$logo,$con,$mail)
    {
    
    $dt=date("D,d M Y ");
        $df=date("h:i:sa");
        $date= $dt." By ".$df;

        $data=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE paymentreference='$refe'"));
    
        $appliD=$data["studentid"];
        $amon=$data["amount"];
        $refe=$data["paymentreference"];
        $tp=$data["purpose"];
        $pt=$data["paytype"];
        
        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$appliD'"));
        $programm=$dat["programme"];
        $phone=$dat["phoneno"];
        $mal=$dat["uniemail"];
        $nmk=$dat["lastname"]." ".$dat["firstname"]." ".$dat["othername"];
        try {
        $mail->isSMTP();
        //$mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password
        $mail->SMTPKeepAlive = true;
        //$mail->Priority = 1;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->setFrom('my@uat.edu.ng', 'Transcript Payment Receipt');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'Transcript Payment Receipt';
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
            <h5><span style="font-size: 18px;">'.$orgname.'</span> , <span style="font-size: 18px;">'.$schadress.'</span><br> <span style="margin-left: 25px;font-size: 15.5px;font-weight: bold;">Official Receipt of Payment.</span></h5>
        </div>';
        $message .=' <table cellpadding="0" cellspacing="0">';
        $message .='<tr class="item">
                <td>
                   Student ID: '.$appliD.'
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
        $message .='<tr class="item">
                <td>
                    Programme: '.$programm.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="heading">
                <td>
                    Payment Information
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .=' <tr class="item">
                <td>
                    Amount Paid: &#8358; '.number_format($amon,2).'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                  Reference ID: '.$refe.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                  Purpose: '.$tp.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
            $message .='<tr class="item">
                <td>
                  Transcript Type: '.$sect.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
        $message .='</table>';
        $message .='<div class="info">
             Your Payment was successfull. 
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
