<?php
  	session_start();
   ob_start();
   set_time_limit(500);
ini_set('max_execution_time', '500');
    if (!isset($_SESSION['uatCsrfToken']) || empty($_SESSION['uatCsrfToken']) ) {
     header("location: ../index");       
    } else {
     if (!isset($_SESSION['uatCsrfSalt'], $_SESSION['uatCsrfSalt'])) {
         header("location: ../index");
        }else{

         if (!isset($_POST['salt'],$_POST['token'],$_POST['ref'])) {
             header("location: ../index");
         } else {

             if ($_SESSION['uatCsrfToken'] != $_POST['token'] || $_SESSION['uatCsrfSalt'] != $_POST['salt'] || $_POST['ref'] == "") {
                 header("location: ../index?error=Invalide Parameters");
             } else {

                    $connection = TRUE;
                    require '../../include/dbconfig.php';
                    require"../phpmailer/class.phpmailer.php";
                    $mail=new PHPMailer();
                    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
                    $staffid = mysqli_real_escape_string($con, $_POST['staffid']);  
                    $email =  mysqli_real_escape_string($con, $_POST['email']);
                    $phone = mysqli_real_escape_string($con, $_POST['phone']);
                    $proc=mysqli_escape_string($con,$_POST['proc']);
                    $first=mysqli_escape_string($con,$_POST['first']);
                    $last=mysqli_escape_string($con,$_POST['last']);
                    $acad=mysqli_escape_string($con,$_POST['acad']);
                    $name = $fullname;
                    $courseID = 'Acceptance Fee';
                    //echo $firstname;
                    $ref=mysqli_real_escape_string($con, $_POST['ref']);
                    $check = mysqli_query($con, "SELECT paymentreference FROM studentpayment WHERE paymentreference ='$ref'");
                    
                    if (mysqli_num_rows($check) < 1) {
                        //server side verification of payment
                        $tranx = confirmPayment($ref);

                        if ($tranx->status) {
                            if ('success' == $tranx->data->status) {

                                if (getCourseAmount($courseID, $con) <= $tranx->data->amount) {

                                    /* uncomment this line if we would use the server side  generation of UserId*/
                                    $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$staffid'"));
                                    $pp=$prd['programme'];
                                    $dep=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pname='$pp'"));
                                            $fac=strtoupper($dep['fid']);
                                            $dac=strtoupper($dep['did']);
                                            
                                    
                                    //comment this line if we will ues the server side generation of UserId
                                    $ref =  mysqli_real_escape_string($con, $_POST['ref']);

                                    $courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$courseID' "));
                                    //$course = $courseDetails['pname'];
                                    $amount = $courseDetails['amount'];
                                    
                                    //Here is insert the accademic session detail into the database
                                    $admy=$acad;
                                    // Set autocommit to off
                                    mysqli_autocommit($con, FALSE);

                                        $today = date("Y-m-d");
                                        $mode=$tranx->data->channel;
                                        
                                        $payment = mysqli_query($con, "INSERT INTO studentpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,status) VALUES ('$staffid','$admy','$pp','Acceptance Fee','$amount','$mode','$ref','$today','Success') ");
                                        if ($payment) {

                                           
                                               mysqli_commit($con);
                                            studentemail($ref,$staffid,$fullname,$mail,$email,$con,$hostna,$usern,$passd);
                                          
                                        } else {
                                            mysqli_rollback($con);
                                            mysqli_close($con);
                                            echo "Could not Process Your admission, Kindly Contact Web Master. Do not repeat this Application ";
                                        }                                   

                                } else {
                                    // log insuficient funds here
                                    $token = md5( uniqid() );
                                    $status = $tranx->data->status;
                                    $status = $tranx->data->message;
                                    $msg = "Amount paid not upto the amount for the course";
                                    $inst = mysqli_query($con, "INSERT INTO reg_pay_log (name, email, phone, reference, token, message) VALUES ('$name', '$email', '$phone', '$ref', '$token', '$msg') ");
                                    echo "Insuficient Payment Made";
                                }
                                
                            } else {

                                //was not sucess
                                $token = md5( uniqid() );
                                $status = $tranx->status;
                                $msg = $tranx->message;
                                $inst = mysqli_query($con, "INSERT INTO reg_pay_log (name, email, phone, reference, token, message) VALUES ('$name', '$email', '$phone', '$ref', '$token', '$msg') ");
                                echo "Payment not Successful";                                                              
                            }
                        } else {
                            
                            //was not sucess
                            $token = md5( uniqid() );
                            $status = $tranx->status;
                            $msg = $tranx->message;
                            $inst = mysqli_query($con, "INSERT INTO reg_pay_log (name, email, phone, reference, token, message) VALUES ('$name', '$email', '$phone', '$ref', '$token', '$msg') ");
                            echo "Payment not Successful";                              
                        }
                        
                    } else {
                        //if the reference already recorded
                        echo "Payment Already Recorded ";
                    }

                 }
                
            }

        } 
    }

    function confirmPayment($ref)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transaction/verify/".rawurlencode($ref),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => [
            "accept: application/json",
            		   // "authorization: Bearer sk_test_b4a37f10a18df5910e7ce921b694b4fb47c75e25",

           "authorization: Bearer sk_live_7ef729ba620b7af421f9f7861f6e99b9fdc14e2b",
            "cache-control: no-cache"
          ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

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
  

    function studentemail($refe,$usern,$pass,$mail,$maik,$con,$host,$user,$pasy)
    {
       
        $dt=date("D,d M Y ");
        $df=date("h:i:sa");
        $date= $dt." By ".$df;
        
        $data=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE paymentreference='$refe'"));
    
        $appliD=$data["studentid"];
        $amon=$data["amount"];
        $refe=$data["paymentreference"];
        $tp=$data["purpose"];
        
        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$appliD'"));
        $_POST["fname"]=$dat["firstname"];
        $_POST["sname"]=$dat["lastname"];
        $programm=$dat["programme"];
        $phone=$dat["phoneno"];
        $mal=$dat["email"];
        $Pass=$dat["password"];
        $nmk=$_POST["fname"]." ".$_POST["sname"];
   
    //$mail = new PHPMailer;
       try {
        
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $user;                 // SMTP username
        $mail->Password = $pasy;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        //$mail->Priority = 1;
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($user, 'Acceptance Clearance');
        $mail->addAddress($maik);
        $mail->isHTML(true);
        $mail->Subject = 'Acceptance Payment';
        $message ='<html> 
        <head> 
        <title> Edo State College Of Education</title> 
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
            <img src="../../img/edo.png" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="width: 300px;margin: auto;">
            <h5><span style="font-size: 17px;">Edo State College Of Education</span><br> <span style="margin-left: 30px;font-size: 14.5px;font-weight: bold;">Official Receipt of Payment.</span></h5>
        </div>';
        $message .=' <table cellpadding="0" cellspacing="0">';
        $message .='<tr class="item">
                <td>
                    Application ID: '.$appliD.'
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
                    Amount Paid: &#8358; '.$amon.'
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
             Your Payment was successfull. Please Kindly Login to Complete Your Application. 
        </div>';
        $message .='<div class="divFooter" style=text-align:center>Copyright © Edo State College Of Education;
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