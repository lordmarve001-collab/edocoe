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


if (isset($_POST['verify'])) {
    //echo $_SESSION['staff'];
        $apidf=$_POST["did"];
        $ref=$_POST["ref"];
        $matr=$_POST["sid"];
$query = mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$apidf'");
$datag = mysqli_fetch_array($query);
$nt="0.01";
    if ($datag!="")
    {    
     $refe=$datag['paymentreference'];
     $apid=$datag['studentid'];
     $courseID=$datag['purpose'];
     $typ=$datag['paytype'];
     $levl=$datag['level'];
     $amount=$datag['amount'];
     $method=$datag['method'];
      /*Checking Paysplit Array to See If Verifying Payment Option belong to the Group */
     if ($apid>0)
        {
           $tranx = confirmPayment($ref);
     
             if ($tranx->responseMessage) {
                            if ('success' == $tranx->responseMessage) {
            $date = date('Y-M-d'); 
            $newd = date('Y-m-d', strtotime($date. ' + 15 days'));
            $mysg=mysqli_query($con,"UPDATE failpayment SET remtime='$newd' WHERE paymentreference='$refe'");
            $querr=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ref'"));
            //$repa=$querr['paymentreference'];
            if ($querr!='')
            {
               $error="Payment Datail Already Verified Or Kindly Visit ICT With Your Paymentreference ID: ".$apidf;
            $showModalEr='true';
            }else{
             if($courseID=='Tuition Fee')
                {
                                 if (getCourseAmount($courseID,$amount, $con)) {
                   
                    /*Retrieving From Failed Payment*/
                    $querb = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$refe' AND studentid='$matr'  "));
                    $admy=mysqli_escape_string($con,$querb['section']);
                    $staf=mysqli_escape_string($con,$querb['studentid']);
                    $progt = mysqli_escape_string($con,$querb['programme']);
                    $semst = mysqli_escape_string($con,$querb['semester']);
                    $level = mysqli_escape_string($con,$querb['level']);
                    $payt = mysqli_escape_string($con,$querb['paytype']);
                    $tday = mysqli_escape_string($con,$querb['paytime']);

                    $courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$courseID' "));
                    $tday=date("Y-m-d");
                    $amount = $courseDetails['amount'];
                                        $mode=$tranx->paymentMethod;
                    $payment = mysqli_query($con, "INSERT INTO studentpayment(studentid,section,level,semester,programme,purpose,amount,modes,paymentreference,paytime,paytype,status) VALUES ('$matr','$admy','$level','$semst','$progt','$courseID','$amount','$mode','$ref','$tday','$payt','$status') ");
                        if ($payment) {
                            mysqli_commit($con);
                            if ($payt=='Full') {
                                // code...
                            
                            studmailfg($matr,$admy,$level,$con,$mail);
                            studentemailf($ref,$orgname,$admy,$level,$schadress,$logo,$con,$mail);
                            coursinsertf($matr,$level,$admy,$payt,$con);
                            $showModalErd='True';
                            $succ="Payment Successfully Verified";
                        }else{
                            studmailf($matr,$semst,$admy,$level,$con,$mail);
                            //bursary($ref,$std,$semst,$cpy,$leveld,$con,$mail);
                            studentemailf($ref,$orgname,$admy,$level,$schadress,$logo,$con,$mail);
                            coursinsertpt($matr,$level,$admy,$payt,$semst,$con);
                            $showModalErd='True';
                            $succ="Payment Successfully Verified";
                        }
                            
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
                else{
                    if (getCourseAmount($courseID,$amount, $con)) {

                    /*Retrieving From Failed Payment*/
                    $querb = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$refe' AND purpose='$courseID' AND studentid='$matr'  "));
                    $admy=mysqli_escape_string($con,$querb['section']);
                    $staf=mysqli_escape_string($con,$querb['studentid']);
                    $progt = mysqli_escape_string($con,$querb['programme']);
                    $level = mysqli_escape_string($con,$querb['level']);
                    $tday= mysqli_escape_string($con,$querb['paytime']);

                    $courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$courseID' "));
                    //$tday=date("Y-m-d");
                    $amount = $courseDetails['amount'];
                                        $mode=$tranx->paymentMethod;
                    $payment = mysqli_query($con, "INSERT INTO studentpayment(studentid,section,level,programme,purpose,amount,modes,paymentreference,paytime,status) VALUES ('$staf','$admy','$level','$progt','$courseID','$amount','$mode','$ref','$tday','$status') ");
                        if ($payment) {
                            mysqli_commit($con);
                           studentemailv($ref,$orgname,$admy,$schadress,$logo,$con,$mail);
                           $showModalErd='True';
                            $succ="Payment Successfully Verified";
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
            $status = $tranx->responseMessage;
            
            $error=$status;
            $showModalEr='true';
            
            
        }

    }else{
        $error="Transaction Not Initiated Out";
        $showModalEr='true';
    }
        }
        else{
            $tranf = confirmPayment($ref);
     
             if ($tranf->responseMessage) {
                            if ('success' == $tranf->responseMessage) {
                     $status = $tranf->responseMessage;
                    $dateg = date('Y-M-d'); 
                    $newdg = date('Y-m-d', strtotime($dateg. ' + 15 days'));
                    $quts=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$refe'"));
                    if($quts['remtime']==''){
                     //echo "Let Update";
                     $mysg=mysqli_query($con,"UPDATE failpayment SET remtime='$newdg' WHERE paymentreference='$refe'");
                    }
                        $querr=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$refe'"));
            //$repa=$querr['paymentreference'];
                        if ($querr!='')
                        {
                           $error="Payment Datail Already Verified Or Kindly Visit ICT With Your Paymentreference ID: ".$apidf;
                        $showModalEr='true';
                        }else{
                            if (getCourseAmount($courseID,$amount, $con)) {

                    /*Retrieving From Failed Payment*/
                    $querb = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$refe' AND purpose='$courseID' AND studentid='$matr'  "));
                    $admy=mysqli_escape_string($con,$querb['section']);
                    $staf=mysqli_escape_string($con,$querb['studentid']);
                    $progt = mysqli_escape_string($con,$querb['programme']);
                    $tday = mysqli_escape_string($con,$querb['paytime']);

                    $courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$courseID' "));
                    //$tday=date("Y-m-d");
                    $amount = $courseDetails['amount'];
                                        $mode=$tranf->paymentMethod;
                    $payment = mysqli_query($con, "INSERT INTO studentpayment(studentid,section,programme,purpose,amount,modes,paymentreference,paytime,status) VALUES ('$staf','$admy','$progt','$courseID','$amount','$mode','$ref','$tday','$status') ");
                        if ($payment) {
                            mysqli_commit($con);
                           studentemail($ref,$orgname,$admy,$schadress,$logo,$con,$mail);
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
                    }else{
            $status = $tranf->responseMessage;
                        
                        $error='Payment Status: '.ucfirst($status);
                        $showModalEr='true';
                        } 
                    }else{
                        $error="Transaction Not Initiated Yet";
                        $showModalEr='true';
                    }
        }
    }else{
    $error="Your Payment Reference Entered ".$apidf." Doesn't Exist, Check and Try Again";
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
        <div style="width: 350px;margin: auto;">
            <h5><span style="font-size: 17px;"><?php echo htmlentities($orgname.",".$schadress) ;?>
       </span></h5>
        </div>
        <hr>
        <div>
        <span style='position:absolute;margin-left:
          500px;margin-top:3px;width:170px;height:170px'><img width=170 height=170
          src="../apply/<?php echo $passport ?>" alt="Photo "></span><b>Student Name: <?php echo htmlentities($fullname) ?></b></span>
          <br><br>
          <span class=MsoNormal><b>Matric No: <?php echo htmlentities($matr); ?></b></span>
          <br><br>
          <span class=MsoNormal><b>Email: <?php echo htmlentities($email) ?></b></span>

          <br><br><span class=MsoNormal><b>Faculty: <?php echo htmlentities($facu) ?> </b></span>
          <br><br><span class=MsoNormal><b>Department: <?php echo htmlentities($def) ?> </b></span>
          <br><br>
          <span class=MsoNormal><b>Programme: <?php echo htmlentities($pro) ?> </b></span>
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
            
            $sql = "SELECT * FROM failpayment  WHERE (studentid='$matr' OR studentid='$harr') ORDER BY id ";
                                               
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
            
           <button class="btn btn-primary staffprive" title="View Staff Record" id="<?php echo $result['studentid'] ?>" pos="<?php echo $result['paymentreference'] ?>">Verify<i class="fa fa-check-circle"></i></button>
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
            <div class="modal" id="viewModalst">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Verify Your Ppayment</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailst">

                        </div>

                        <!-- Modal footer -->

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
include 'include/buton.php';
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
        <script>
         $(document).ready(function() {

                    //$('.userinfof').click(function() {
                      $('body').on('click','.staffprive',function(){
                        var userid = $(this).attr('id');
                        var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'stafpriv',
                            type: 'GET',
                            data: {
                                userid: userid,
                                userip: userip
                            },
                            success: function(response) {
                                $('#teacher_detailst').html(response);
                                // Display Modal
                                $('#viewModalst').modal('show');
                            }
                        });
                    });
                });
            </script>
        </body>
<?php }
function confirmPayment($ref)
    { 
    
// Step 1: Get access token
$API_Key = "MK_PROD_DA332QTPV4";
$Secret_Key = "VAJJ7XKUUWGJL828WNK8RZSLJCLUEDU4";
$baseUrl = "https://api.monnify.com";

$ch = curl_init();

// Concatenate "ApiKey" + ":" +  "SecretKey", then Base 64 encode the string and prefix with the word "Basic". See in next line
$headers = array(
    'Content-Type:application/json',
    'Authorization: Basic '. base64_encode($API_Key . ":" . $Secret_Key) // <---
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL,"https://api.monnify.com/api/v1/auth/login");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($ch);

curl_close($ch);

$json = json_decode($output, true);
// print_r($json);

$accessToken = $json['responseBody']['accessToken'];
// Step 2: Verify transaction status
 $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.monnify.com/api/v2/transactions/" . urlencode($ref),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $accessToken",
            "Accept: application/json",
            "cache-control: no-cache"

        ),
    ));

$response = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($response, true);
    $responseBody = $res['responseBody'];
            $err = curl_error($curl);
    if($err){
            // there was an error contacting the Paystack API
          return json_decode('{"status": false, "message": "Could not Connect to Monnify. API Timeout. "}');
        }else{
            return json_decode($response);
        }
}

    function getCourseAmount($course, $con)
    {
        return mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$course' "))['amount'];
    }
/*For Inserting the Course Information*/
 function coursinsertpt($std,$leveld,$cpy,$payt,$semst,$con)
    {
        $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT programme FROM student WHERE studentid='$std'"));
        
             $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$std','$cpy','$semst','".$prd['programme']."' ,'','$leveld')");
    }
function coursinsert($add,$std,$leveld,$cpy,$sems,$con)
    {
        $date = date('Y-M-d'); 
        $newd = date('Y-m-d', strtotime($date. ' + 6 days'));
        $upda=mysqli_query($con,"UPDATE studentregsem SET extention='$newd' WHERE studentid='$std' AND semester='$sems' AND session='$cpy' AND level='$leveld' ");
    }
    function studentemailc($refe,$orgname,$sect,$sems,$lev,$schadress,$logo,$con,$mail)
    {
    
    $dt=date("D,d M Y ");
        $df=date("h:i:sa");
        $date= $dt." By ".$df;

        $data=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE paymentreference='$ref'"));
    
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
        $mail->setFrom('my@uat.edu.ng', 'Late Registration Payment');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'Late Registration Payment';
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
                  Semester: '.$sems.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
            $message .='<tr class="item">
                <td>
                  Academic Session: '.$sect.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
            $message .='<tr class="item">
                <td>
                  Level: '.$lev.'
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
function studmail($stud,$sm,$se,$lv,$con,$mail)
{
    
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
        $to = $rows['uniemail'];
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Late Registration Extension');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Late Registration Extension';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['lastname']. ', '. $rows['firstname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sm.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$lv.'</b></span>';
          $message .= '<br>';
          $message.='<p><strong style=text-align:justify;>Please Kindly Note: That Your Request For Extra Time for Course Enrolment have been Approved. Login to University Portal Now to Complete Your Course Registration Within 3 Days<br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}     

function coursinsertp($std,$leve,$secc,$sems,$con)
    {

        $date = date('Y-M-d'); 
        $newd = date('Y-m-d', strtotime($date. ' + 6 days'));
        $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT programme FROM student WHERE studentid='$std'"));
        $upda=mysqli_query($con,"INSERT INTO studentlateregsem (studentid,session,semester,programme,uploadst,regstatus,level) VALUES ('$std','$secc','$sems','".$prd['programme']."','Pending','','$leve')");
    }

 function studentemailp($refe,$orgname,$sect,$sems,$lev,$schadress,$logo,$con,$mail)
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
        $nmk=$dat["lastname"]." ".$dat["firstname"]." ".$dat["othername"];
       
       try {
        
        // $mail->SMTPDebug = 0;
        $mail->isSMTP();
        //$mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->Priority = 1;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->setFrom('my@uat.edu.ng', 'Previous Late Registration Payment');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'Previous Late Registration Payment';
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
                  Semester: '.$sems.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
            $message .='<tr class="item">
                <td>
                  Academic Session: '.$sect.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
            $message .='<tr class="item">
                <td>
                  Level: '.$lev.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
            
        $message .='</table>';
        $message .='<div class="info">
             Your Payment was successfull. Please Kindly Login to Complete Your Late Registration. 
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

function studmailp($stud,$sm,$se,$lv,$con,$mail)
{
    
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
        $to = $rows['uniemail'];
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->setFrom('my@uat.edu.ng', 'Late Previous Registration Extension');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Late Previous Registration Extension';
        $message = '<html><body>'; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['lastname']. ', '. $rows['firstname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sm.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$lv.'</b></span>';
          $message .= '<br>';
          $message.='<p><strong>Please Kindly Note: That Your Request For Extra Time for Course Enrolment have been Approved. Login to University Portal Now to Complete Your Course Registration Within 3 Days<br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            
}      
/*For School Fee Full Payment*/
function getCourseAmountf($course,$payt,$lev, $con)
    {
        return mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE level='$lev' AND paytype='$payt' AND purpose = '$course' "))['amount'];
    }

    
    function coursinsertf($std,$leveld,$cpy,$payt,$con)
    {
        $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT programme FROM student WHERE studentid='$std'"));
       
               $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$std','$cpy','First Semester','".$prd['programme']."' ,'','$leveld')");
            $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$std','$cpy','Second Semester','".$prd['programme']."','','$leveld')"); 
    }

function studmailf($stud,$sm,$se,$le,$con,$mail)
{
  $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
   
        $to = $rows['uniemail'];
        
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Course Enrollment Clearance');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Enrollment Clearance';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['lastname']. ', '. $rows['firstname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sm.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$le.'</b></span>';
          $message .= '<br>';
          $message.='<p><strong>Please Kindly Note: That You Have been Successfully Cleared for Course Enrollment for this '.$sm.' in '.$se.' Academic Session And Level '.$le.' Please Login to Please Login to University Portal https://my.uat.edu.ng to  Complete Your Course Registration <br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
}

function studmailfg($stud,$se,$le,$con,$mail)
{
   
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
    //die($maix);
        $to = $rows['uniemail'];
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Course Enrollment Clearance');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Enrollment Clearance';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['lastname']. ', '. $rows['firstname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$le.'</b></span>';
          $message .= '<br>';
          $message.='<p><strong style=text-align:justify>Please Kindly Note: That You Have been Successfully Cleared for Course Enrollment for Both First And Second Semesters  in '.$se.' Academic Session And Level '.$le.' Please Login to University Portal https://my.uat.edu.ng to Complete Your Course Registration <br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}
function studentemailf($refe,$orgname,$sect,$lev,$schadress,$logo,$con,$mail)
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
        
        //$mail->SMTPDebug = 0;
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
        $mail->setFrom('my@uat.edu.ng', 'School Fee Payment Receipt');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'School Fee Payment Receipt';
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
                  Pay Type: '.$pt.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
            $message .='<tr class="item">
                <td>
                  Academic Session: '.$sect.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
            $message .='<tr class="item">
                <td>
                  Level: '.$lev.'
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

    function studentemailv($refe,$orgname,$sect,$lev,$schadress,$logo,$con,$mail)
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
        
        //$mail->SMTPDebug = 0;
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
        $mail->setFrom('my@uat.edu.ng', 'Payment Receipt');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'Payment Receipt';
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
                  Academic Session: '.$sect.'
                </td>
                
                <td>
                    
                </td>
            </tr> ';
            
            $message .='<tr class="item">
                <td>
                  Level: '.$lev.'
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
        
        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE studentid='$appliD'"));
        $programm=$dat["programme"];
        $phone=$dat["phone"];
        $mal=$dat["email"];
        $nmk=$dat["surname"]." ".$dat["firstname"]." ".$dat["lastname"];
        try {
        
        //$mail->SMTPDebug = 0;
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
        $mail->setFrom('my@uat.edu.ng', ''.$tp.' Payment Receipt');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = $tp.' Payment Receipt';
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
                  Academic Session: '.$sect.'
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
