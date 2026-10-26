<?php
session_start();
$connection = TRUE;
require '../../include/dbconfig.php';
require"../phpmailer/class.phpmailer.php";
$mail = new PHPMailer();
date_default_timezone_set("africa/lagos");
$fullname=mysqli_escape_string($con,$_POST['fullname']);
$email=mysqli_escape_string($con,$_POST['email']);
$phone=mysqli_escape_string($con,$_POST['phone']);
$cpy=mysqli_escape_string($con,$_POST['copy']);
$ref=mysqli_escape_string($con,$_POST['ref']);
$add=mysqli_escape_string($con,$_POST['mess']);
$leveld=mysqli_escape_string($con,$_POST['progs']);
$level=mysqli_escape_string($con,$_POST['leveld']);
$std=mysqli_escape_string($con,$_POST['studentid']);
$payt=mysqli_escape_string($con,$_POST['payty']);
$semst=mysqli_escape_string($con,$_POST['semst']);
$statu=mysqli_real_escape_string($con, $_POST['status']);

$amount=mysqli_real_escape_string($con, $_POST['amount']);
$method=mysqli_real_escape_string($con, $_POST['method']);

$check = mysqli_query($con, "SELECT paymentreference FROM studentpayment WHERE paymentreference = '$ref' ");
	    if (mysqli_num_rows($check) < 1) {
	        
	    	 $tranx = confirmPayment($ref);
                        if ($tranx->responseMessage) {
                            if ('success' == $tranx->responseMessage) {

               if ('PAID' == $statu) {
		    			$tday=date("Y-m-d");
		    			/*For the Programme*/
                         $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$std'"));
                         $pp=$prd['programme'];
		    			$courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose = '$add'"));
                                        $mode=$method;
		    			 
                         
		    			 $regist=mysqli_query($con,"INSERT INTO studentpayment(studentid,section,level,semester,programme,purpose,amount,modes,paymentreference,paytime,paytype,status) 
		    			 VALUES ('$std','$cpy','$level','$semst','$pp','$add','$amount','$mode','$ref','$tday','$payt','Success') ");
		    			 		    			mysqli_autocommit($con, FALSE);
		    			if ($regist) {
		    				studmail($std,$semst,$cpy,$level,$con,$mail,$hostna,$usern,$passd);
		    				studentemail($std,$ref,$orgname,$cpy,$semst,$level,$schadress,$logo,$con,$mail,$hostna,$usern,$passd);
                            coursinsert($add,$std,$level,$cpy,$pp,$payt,$semst,$con);
                           
							mysqli_commit($con);
						}else{
							mysqli_rollback($con);
							mysqli_close($con);
							echo "Could not Process Your Payment, Kindly Contact Web Master. Do not repeat this Payment ";
						}
					
		    		}else {
					// log insuficient funds here
					$token = md5( uniqid() );
					$status = $tranx->data->status;
					$status = $tranx->data->message;
					$msg = "Amount paid not upto the amount for the course";
					$inst = mysqli_query($con, "INSERT INTO reg_pay_log (name, email, phone, reference, token, message) VALUES ('$fullname', '$email', '$phone', '$ref', '$token', '$msg') ");
					echo "Insuficient Payment Made";
			    				}
		    	} else {

				//was not sucess
				$token = md5( uniqid() );
				$status = $tranx->status;
				$msg = $tranx->message;
				$inst = mysqli_query($con, "INSERT INTO reg_pay_log (name, email, phone, reference, token, message) VALUES ('$fullname', '$email', '$phone', '$ref', '$token', '$msg') ");
				echo "Payment not Successful";			    							    				
			    			}
		    }else {
		    				
	    					//was not sucess
	    					$token = md5( uniqid() );
	    					$status = $tranx->status;
	    					$msg = $tranx->message;
	    					$inst = mysqli_query($con, "INSERT INTO reg_pay_log (name, email, phone, reference, token, message) VALUES ('$fullname', '$email', '$phone', '$ref', '$token', '$msg') ");
	    					echo "Payment not Successful";			    				
		    			}
	    } else {
						//if the reference already recorded
					  	echo "Payment Already Recorded ";
					}
function confirmPayment($ref)
  	{ 
    
// Step 1: Get access token
// Step 1: Get access token

$API_Key = "MK_PROD_DA332QTPV4";
$Secret_Key = "VAJJ7XKUUWGJL828WNK8RZSLJCLUEDU4";
$baseUrl = "https://api.monnify.com";
//$API_Key = "MK_TEST_ZNF7A71RMX";
//$Secret_Key = "MCJHAXH0GEWT9NDJ9ZZNS9GZADNRBAH3";
//$baseUrl = "https://sandbox.monnify.com";

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
          return json_decode('{"status": false, "message": "Could not Connect to paystack. API Timeout. "}'); 
        }else{
            return json_decode($response);
        }
}
  	
  	//function getCourseAmount($course,$payt,$lev,$amount, $con)
  	//{
  		//return mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE level='$lev' AND paytype='$payt' AND purpose = '$course' "))['amount'];
  	//}

  	
    function coursinsert($add,$std,$level,$cpy,$pp,$payt,$semst,$con)
    {
    	
               $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$std','$cpy','$semst','$pp' ,'','$level')");
    }

function studmail($stud,$sm,$se,$le,$con,$mail,$hostna,$usern,$passd)
{
   
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
    //die($maix);
        $to = $rows['email'];
        try {
     $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = $hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $passd;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        //$mail->Priority = 1;
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($usern, 'Course Enrollment Clearance');
        /*$con,$refe,$ema,$orgname,$schadress,$logo,$ids,$ap,$nam,$pg*/
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Enrollment Clearance';
        
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['surname']. ', '. $rows['firstname'].' '.$rows['lastname'].'</b></span>';
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
          $message.='<p><strong>Please Kindly Note: That you have been successfully cleared for Course Enrollment for this '.$sm.' in '.$se.' Academic Session And Level '.$le.' <br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}
function studentemail($stu,$refe,$orgname,$sect,$sems,$lev,$schadress,$logo,$con,$mail,$hostna,$usern,$passd)
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
        
        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$stu'"));
        $programm=$dat["programme"];
        $phone=$dat["phoneno"];
        $mal=$dat["email"];
        $nmk=$dat["surname"]." ".$dat["firstname"]." ".$dat["lastname"];
        try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        //$mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->Host = $hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $passd;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        //$mail->Priority = 1;
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($usern, 'Tuition Fee Payment Receipt');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'Tuition Fee Payment Receipt';
        $message ='<html> 
        <head> 
        <title>Edo State College of Health Science Technology</title> 
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
                  Semester: '.$sems.'
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
        $message .='<div class="divFooter" style=text-align:center>Copyright © Edo State College of Health Science Technology;
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