<?php
    session_start();
   //ob_start();
   set_time_limit(600);
ini_set('max_execution_time', '600');
  

                    $connection = TRUE;
                    require '../../include/dbconfig.php';
                    require"../phpmailer/class.phpmailer.php";
                    $mail=new PHPMailer();
                    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);  
                    $email =  mysqli_real_escape_string($con, $_POST['email']);
                    $phone = mysqli_real_escape_string($con, $_POST['phone']);
                    $admy=mysqli_escape_string($con,$_POST['acads']);
                    $progt = mysqli_escape_string($con,$_POST['progs']);
                    $courseID = mysqli_escape_string($con,$_POST['prog']);
                    $ref=mysqli_real_escape_string($con, $_POST['ref']);
                    $first=mysqli_real_escape_string($con, $_POST['first']);
                    $last=mysqli_real_escape_string($con, $_POST['last']);
                    $prov=mysqli_real_escape_string($con, $_POST['prov']);
                    $name=$firstname;
        $check = mysqli_query($con, "SELECT paymentreference FROM studentpayment WHERE paymentreference ='$ref'");
             if (mysqli_num_rows($check) < 1) {
                        //server side verification of payment
                        $tranx = confirmPayment($ref);

                        if ($tranx->status) {
                            if ('success' == $tranx->data->status) {

                                if (getCourseAmount($courseID, $con) <= $tranx->data->amount) {
                                    
                                    $id =mysqli_fetch_assoc(mysqli_query($con, "SELECT count(applid) as userid FROM applicant WHERE session='$admy' AND apptype='NCE'"))['userid'];
                                    $id = $id + 1;
                                    $yrk=substr($admy,0,4);
                                    $yr=substr($yrk,-2);
                                    $userID = "EDOCOE/ADM/".$yr."/".str_pad($id, 4, '0', STR_PAD_LEFT);
                                    
                                    $courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$courseID' "));
                                    
                                    $amount = $courseDetails['amount'];
                                                                       
                                    $hashedPW = hash('sha256', $phone);
                                    $today = date("Y-m-d");
                                    $toda= date("Y-m-d");
                                    // Set autocommit to off
                                    mysqli_autocommit($con, FALSE);
                                    $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT pname FROM program WHERE pid='$prov'"));
                                    $pp=$prd['pname'];
                                   
                                    $register = mysqli_query($con, "INSERT INTO applicant (applid,phoneno,email,password,status,session,firstname,lastname,programme,apptype,applicationDate) VALUES ('$userID','$phone','$email','$hashedPW','submitted','$admy','$first','$last','$pp','NCE','$toda') ");
                                    if ($register) {
                                        
                                        $mode=$tranx->data->channel;
                                        
                                        $payment = mysqli_query($con, "INSERT INTO studentpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,status) VALUES ('$userID','$admy','$pp','$courseID','$amount','$mode','$ref','$today','Success') ");
                                        if ($payment) {
                                            mysqli_commit($con);
                                            studentemail($con,$mail,$ref,$email,$orgname,$schadress,$logo,$userID,$userID,$firstname,$pp,$phone,$hostna,$passd,$usern);
                                            studensms($phone,$userID,$firstname);
                                            mysqli_close($con);
                                            //echo 1;
                                        } else {
                                            mysqli_rollback($con);
                                            mysqli_close($con);
                                            echo "Could not Process Your admission, Kindly Contact Web Master. Do not repeat this Application ";
                                        }                                   

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

                
    function confirmPayment($ref)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transaction/verify/".rawurlencode($ref),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer sk_live_7ef729ba620b7af421f9f7861f6e99b9fdc14e2b",
            //"authorization: Bearer sk_test_b4a37f10a18df5910e7ce921b694b4fb47c75e25",
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


 function studensms($ph,$userid,$fna)
    {
  $url="https://portal.edocoe.edu.ng/apply/applogin";
  $email = "okpehjohcybertech@gmail.com";
  $password = "Test123";
  $message = "EDOCOE APPLICATION DETAILS \n";
  $message.= "Full Name: ".$fna."\n";
  $message.= "Application ID: ".$userid."\n";
  $message.= "Password: ".$ph."\n";
  $message.= "Click Here To Login: \n".$url;
  $sender_name = "EDOCOE";
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
        $mail->setFrom($user, 'EDOCOE Admission Processing');
        /*$con,$refe,$ema,$orgname,$schadress,$logo,$ids,$ap,$nam,$pg*/
        $mail->addAddress($ema);
        $mail->isHTML(true);
        $mail->Subject = 'EDOCOE Admission DETAILS';
        $message ='<html> 
        <head> 
        <title>Edo State College of Education</title> 
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
             Your Payment was successfull. Please Kindly Login to Complete Your Application. 
        </div>';
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