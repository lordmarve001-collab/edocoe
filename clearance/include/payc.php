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
$leveld=mysqli_escape_string($con,$_POST['leveld']);
$std=mysqli_escape_string($con,$_POST['studentid']);
//echo $std;
$check = mysqli_query($con, "SELECT paymentreference FROM studentpayment WHERE paymentreference = '$ref' ");
	    if (mysqli_num_rows($check) < 1) {
	    	$tranx = confirmPayment($ref);
	    	if ($tranx->status) {
		    	if ('success' == $tranx->data->status) {

		    		if (getCourseAmount($_POST['mess'], $con) <= $tranx->data->amount) {
		    			$tday=date("Y-m-d");
		    			$courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose = '$add' "));
						$amount = $courseDetails['amount'];
		    			 $mode=$tranx->data->channel;
                         $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT programme FROM personaldata WHERE studentid='$std'"));
                         $prgs=$prd['programme'];
		    			$regist=mysqli_query($con,"INSERT INTO studentpayment(studentid,section,level,programme,purpose,amount,modes,paymentreference,paytime,status) VALUES ('$std','$cpy','$leveld','$prgs','$add','$amount','$mode','$ref','$tday','Success') ");
		    			mysqli_autocommit($con, FALSE);
		    			if ($regist) {
		    				studentemail($ref,$orgname,$cpy,$leveld,$schadress,$logo,$con,$mail,$hostna,$usern,$passd);
							mysqli_commit($con);							
						}else{
							mysqli_rollback($con);
							mysqli_close($con);
							echo "Could not Process Your admission, Kindly Contact Web Master. Do not repeat this Application ";
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
  		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($ref),
   		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_HTTPHEADER => [
		    "accept: application/json",
		    //"authorization: Bearer sk_test_b4a37f10a18df5910e7ce921b694b4fb47c75e25",
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
  		return mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose = '$course' "))['amount'];
  	}
function studentemail($refe,$orgname,$sect,$lev,$schadress,$logo,$con,$mail,$hostna,$usern,$passd)
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
        $mail->Host = $hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $passd;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        //$mail->Priority = 1;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($usern, 'Lab Fee Payment Receipt');
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'Lab Fee Payment Receipt';
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
?>