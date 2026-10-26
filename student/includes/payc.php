<?php
session_start();
$connection = TRUE;
require '../../include/dbconfig.php';
date_default_timezone_set("africa/lagos");
$fullname=mysqli_escape_string($con,$_POST['fullname']);
$email=mysqli_escape_string($con,$_POST['email']);
$phone=mysqli_escape_string($con,$_POST['phone']);
$cpy=mysqli_escape_string($con,$_POST['copy']);
$ref=mysqli_escape_string($con,$_POST['ref']);
$add=mysqli_escape_string($con,$_POST['mess']);
$leveld=mysqli_escape_string($con,$_POST['leveld']);
$std=mysqli_escape_string($con,$_POST['studentid']);

$check = mysqli_query($con, "SELECT paymentreference FROM studentpayment WHERE paymentreference = '$ref' ");
	    if (mysqli_num_rows($check) < 1) {
	    	$tranx = confirmPayment($ref);
	    	if ($tranx->status) {
		    	if ('success' == $tranx->data->status) {

		    		if (getCourseAmount($_POST['mess'], $con) <= $tranx->data->amount) {
		    			$tday=date("Y-m-d H:i:s A");
		    			//$amount = $tranx->data->amount;
		    			$courseDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose = '$add' "));
						$amount = $courseDetails['amount'];
		    			 $mode=$tranx->data->channel;
		    			$regist=mysqli_query($con,"INSERT INTO studentpayment(studentid,section,level,purpose,amount,modes,paymentreference,paytime,status) VALUES ('$std','$cpy','$leveld','$add','$amount','$mode','$ref','$tday','Success') ");
		    			mysqli_autocommit($con, FALSE);
		    			if ($regist) {
		    				//studmail($ref);
		    				//acadmic($ref);
                            coursinsert($add,$std,$leveld,$cpy,$con);
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
		    }else {
		    				
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
		  CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($ref),
			//CURLOPT_URL => "https://api.paystack.co/transaction/verify/:reference",
   		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_HTTPHEADER => [
		    "accept: application/json",
		    //"Content-Type': 'application/json",
		    //"authorization: Bearer sk_live_1cc92b8e0c6e958353555c5a58068f5a72df4bb0",
		    "authorization: Bearer sk_test_8a8ab8ae88b9122f918569234879600c605c64cf",
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

  	
    function coursinsert($add,$std,$leveld,$cpy,$con)
    {
        if ($add=='ICT PORTAL FEE') {
                $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT programme FROM student WHERE studentid='$std'"));
             $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$std','$cpy','First semester','".$prd['programme']."' ,'','$leveld')");
            $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$std','$cpy','Second semester','".$prd['programme']."','','$leveld')");
        }
    }
?>