<?php
ob_start();
session_start();
$error='';
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include '../include/dbconfig.php';
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();
//Login Session
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];
$student=$_SESSION['staffid'];

if (isset($_POST['verify'])) {
    //echo $_SESSION['staff'];
        $apidf=mysqli_escape_string($con,$_POST["did"]);
        $ref=mysqli_escape_string($con,$_POST["ref"]);
$query = mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$apidf' AND section='$ses'");
$datag = mysqli_fetch_array($query);
$nt="0.01";
if ($datag!="")
{    
     $refe=$datag['paymentreference'];
     $apid=$datag['studentid'];
     $courseID=$datag['purpose'];
     $amount=$datag['amount'];
     $method=$datag['method'];
     
     $tranx = confirmPayment($ref);
     
             if ($tranx->responseMessage) {
                            if ('success' == $tranx->responseMessage) {
     
       $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT paymentreference FROM studentpayment WHERE paymentreference='$ref'"));
            if ($pr=="")
            {
             
                 if (getCourseAmount($courseID,$amount, $con)) {
                  /*Retrieving From Failed Payment*/
                  $querb = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM failpayment WHERE paymentreference='$apidf' AND section='$ses'"));
                  /*Data Retrieving Stage*/
                     
                    $email =  mysqli_real_escape_string($con, $querb['email']);
                    $phone = mysqli_real_escape_string($con, $querb['phoneno']);
                    $admy=mysqli_escape_string($con,$querb['section']);
                    $staf=mysqli_escape_string($con,$querb['studentid']);
                    $progt = mysqli_escape_string($con,$querb['programme']);
                    $degree=mysqli_real_escape_string($con, $querb['apptype']);

                    $first=mysqli_real_escape_string($con,$querb['firstname']);
                    $last=mysqli_real_escape_string($con, $querb['lastname']);
                    $firstname=$first." ".$last;
                    //$prov=mysqli_real_escape_string($con, $querb['prov']);                
                  /*Data Retrieving End*/                
                    $id =mysqli_fetch_assoc(mysqli_query($con, "SELECT count(applid) as userid FROM applicant WHERE session='$admy' AND apptype='$degree'"))['userid'];
                                    $id = $id + 1;
                                    $yrk=substr($admy,0,4);
                                    $yr=substr($yrk,-2);
                                    $userID = "EDOCOHEST/".$degree."/".$yr."/".str_pad($id, 4, '0', STR_PAD_LEFT);
                                    
                    $courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$courseID' "));
                    
                    $amount = $courseDetails['amount'];
                                                       
                    $hashedPW = hash('sha256', $phone);
                    $today = date("Y-m-d");
                    $toda= date("Y-m-d");
                    // Set autocommit to off
                    mysqli_autocommit($con, FALSE);
                   

                    $register = mysqli_query($con, "INSERT INTO applicant (applid,phoneno,email,password,status,session,firstname,lastname,othername,programme,apptype,applicationDate) VALUES ('$userID','$phone','$email','$hashedPW','submitted','$admy','$first','$last','$other','$progt','$degree','$toda') ");
                    
                    
                    if ($register) {
                        
                                        $mode=$tranx->paymentMethod;
                        
                        $payment = mysqli_query($con, "INSERT INTO studentpayment (studentid, section,programme,purpose,amount,modes,paymentreference,paytime,status,apptype) VALUES ('$userID','$admy','$progt','$courseID','$amount','$mode','$ref','$today','success','$degree') "); 
                        
                        $recup=mysqli_query($con,"UPDATE failpayment SET status='Verified' WHERE paymentreference='$refe' ");
                        
                        if ($payment) {
                            mysqli_commit($con);
                            studentemail($con,$mail,$ref,$email,$orgname,$schadress,$logo,$userID,$staf,$firstname,$pp,$phone,$hostna,$passd,$usern);
                            studensms($phone,$userID,$firstname,$staf);
                            //mysqli_close($con);
                            //echo 1;
                        } else {
                            mysqli_rollback($con);
                            //mysqli_close($con);
                            echo "Could not Process Your admission, Kindly Contact Web Master. Do not repeat this Application "; 
                        }                                   

                    } 
                }

              $showModal='true';
              header("Refresh:4;success?feedback=success&ref=".$ref);
            }else{
                $quer = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ref' AND section='$ses'"));
            $stu =  mysqli_real_escape_string($con, $quer['studentid']);
            
            $error="Already have Application ID, Your app ID: ".$stu." AND Password: ".$datag['phoneno']." ";
            $error.="Kindly Copy them Out to Complete your Application";
            $showModalEr='true';
            }
        }else{
            $status = $tranx->responseMessage;
        }

    }else{
        $error="Transaction Not Initiated Yet '.$ref$apidf.'";
        $showModalEr='true';
    }

}else{
    $error="Your Payment Reference Entered ".$apidf." Doesn't Exist, Check and Try Again";
    $showModalEr='true';
  
 }
 
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo htmlentities($orgname) ;?></title>
    <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style type="text/css">
        .field-icon {
              float: right;
              margin-left: -35px;
              margin-top: -30px;
              position: relative;
              z-index: 2;
            }
            .pull-right{float:right!important}
    </style>
</head>
<body>
    <?php include('include/menubar.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="page-head-line"></h5>
                </div>
            </div>
            <div class="row">
                <?php $dats= date("Y-m-d");
            if ($dead>=$dats) { ?>
                <div class="panel-body">
                    <div class="account-card-content"> 
                    <table id="example2" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Applicant Email</th>
                            <th>Full Name</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>
                            <th>Transaction Reference</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $sql = "SELECT * FROM failpayment WHERE studentid='".$_SESSION['staffid']."'ORDER BY paymentreference";
                                
                                $queryn=mysqli_query($con,$sql);
                                $results=mysqli_fetch_all($queryn,MYSQLI_ASSOC);
                                $cnt=1;
                                
                                foreach($results as $result)
                                {   ?>
                        <tr>
                            <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result['studentid']);?></td>
                            <td><?php echo htmlentities($result['lastname']." ".$result['firstname']);?></td>
                            <td><?php echo htmlentities($result['status']);?></td><td><?php echo htmlentities($result['paytime']);?></td>
                            <td>
<form class="form-horizontal" method="post" enctype="multipart/form-data">
    
        <input type="text" class="form-control" id="ref" name="ref" placeholder="MNFY|60|20250810112123|216811"  required>
                
<input type="hidden" name="did" class="form-control" value="<?php echo htmlentities($result['paymentreference']) ?>" required>
  

                                                                </td>
                                                                <td>
                                <button type="submit" name="verify" class="btn btn-primary">Verify <i class="fa fa-check-circle"></i> </button>
                                </form>
                            </td>
                        </tr>
                    <?php 
                        $cnt=$cnt+1;
                       
                        }
                    ?>
                    </tbody>
                    </table>
                </div>
                </div>
                <br>
                <div class="col-md-6">
                    <div class="alert alert-info" style="font-size: 15px;font-weight: bolder;">
                        Payment Validation Portal (Admission Only)
                        <br />
                        <strong>How to validate your payment</strong><br>
                         <strong> Please Note :</strong>
                        <ol>
                            <li>
                              Enter Transaction Referance Number send to your email eg: MNFY|60|20250810112123|216811
                            </li>
                            <li>
                                Click Verify button to get payment status.
                            </li>
                            
                            <li>
                                Any difficulty, reach us via Email: info@edocohest.edu.ng
                            </li>

                            
                        </ol>
                       
                    </div>
                 </div>
                <?php }else{ ?>
                <div class="col-md-6">
                    <div class="account-card-content"> 
                        <p style="color:red;">Admission acceptance fee for new intake has not started. </p>
                                       
                          <div style="margin-top: 5%;" >
                             <b style='color: red;'>Already have an e-PIN? <a href="applogin" class="font-500 text-primary">Login here  </a> </b>
                        </div>
                </div>
                </div>

                <div class="col-md-6">
                    <div class="alert alert-info" style="font-size: 14px;font-weight: bolder;">
                        Welcome to the <?php echo $orgname.", ".$schadress ?> Acceptance Portal
                        <br />
                        <strong>APPLICATION SESSION: <?php echo htmlentities($ses) ?> </strong><br>
                         <strong> Please Note :</strong>
                        <ol>
                            <li>
                               Application has ended <?php 
                               //$date1 = new DateTime($dead);
                               //$now = new DateTime();
                               //$diff = $date1->diff($now);

                               
                            ?>
                            <span style="color:red"><?php echo htmlentities($diff->days);?> Day's Ago</span>.
                            </li>
                            <li>
                                debited but couldn't get the e-Pin, <a href="payverification">Re-verify Payment</a>.
                            </li>
                             <li>
                                Any Difficulty Reach us via Email: my@uat.edu.ng
                            </li>
                            </ol>
                       
                    </div>
                 </div>
            <?php } ?>
            
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
            
            <!-- Email Resending Stages -->
                <!-- Email Resending Stages -->
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
            <!-- Email Resending Stages -->
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('include/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
</body>
<?php
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
    if(!empty($showModalEri)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalErl").modal("show");
            });
        </script>';
    }

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
  function getCourseAmount($course,$amount, $con)
    {
        return mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$course' "))['amount'];
    }


 function studensms($ph,$userid,$fna,$sta){
  $url="https://edocohest.com/apply/applogin";
  $email = "imarve.wiz@gmail.com";
  $password = "Passw20ord";
  $message = "EDOCOHEST APPLICATION DETAILS \n";
  $message.= "Full Name: ".$fna."\n";
  $message.= "Application ID: ".$userid."\n";
  $message.= "Password: ".$ph."\n";
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
  //print_r($res_array); 
}

function studentemail($con,$mail,$refe,$ema,$orgname,$schadress,$logo,$ids,$ap,$nam,$pg,$phs,$host,$pass,$user)
    {
    
    $dt=date("D,d M Y ");
        $df=date("h:i:sa");
        $date= $dt." By ".$df;

        $data=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE paymentreference='$refe'"));
    
        $appliD=$data["studentid"];
        $amon=$data["amount"];
        $refe=$data["paymentreference"];
        $tp=$data["purpose"];
        
        
       try {
        
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $user;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        //$mail->Priority = 1;
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($user, 'EDOCOHEST Admission Processing');
        /*$con,$refe,$ema,$orgname,$schadress,$logo,$ids,$ap,$nam,$pg*/
        $mail->addAddress($ema);
        $mail->isHTML(true);
        $mail->Subject = 'EDOCOHEST Admission DETAILS';
          $url="https://edocohest.com/apply/applogin";
        $message ='<html> 
        <head> 
        <title>Edo State College of Health Sciense Technology</title> 
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
        <div style="width: 350px;margin: auto;">
            <h5><span style="font-size: 18px;">'.$orgname.'</span> , <span style="font-size: 18px;">'.$schadress.'</span><br> <span style="margin-left: 25px;font-size: 15.5px;font-weight: bold;">Official Receipt of Payment.</span></h5>
        </div>';
        
        $message .=' <table cellpadding="0" cellspacing="0">';
        $message .='<tr class="item">
                <td>
                    Full Name: <strong style="text-transform: capitalize;">'.$nam.'</strong>
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                    Phone: '.$phs.'
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
        $message .='<tr class="item">
                <td>
                    Programme: '.$pg.'
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
                    Amount Paid: &#8358;'.number_format($amon,2).'
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
            $message.='<tr class="heading">
                <td>
                    Epin Information
                </td>
                
                <td>
                    
                </td>
            </tr>';
            /*$ids,$ap,$nam,$pg*/
            $message.='<tr class="item">
                <td>
                   Admission ID: '.$ids.'
                </td>
                
                <td>
                    
                </td>
            </tr>    ';
            $message.='<tr class="item">
                <td>
                   Password : '.$phs.'
                </td>
                
                <td>
                    
                </td>
            </tr>    ';
        $message .='</table>';
        $message .='<div class="info">
             Your Payment was successfull.
        </div>';
          $message.= "Please Kindly Login to Complete Your Application: \n".$url;

        $message .='<div class="divFooter" style=text-align:center>Copyright © '.$orgname.' ,'.$schadress.';
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
</html>