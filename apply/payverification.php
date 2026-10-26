<?php
ob_start();
session_start();
$error='';
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include '../include/dbconfig.php';
//Login Session
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];
//
if (isset($_POST["registering"])) {
 $apid=trim(mysqli_escape_string($con,strtoupper($_POST["jambreg"])));
$query = mysqli_query($con,"SELECT * FROM failpayment WHERE studentid='$apid' AND section='$ses'");
//$query = mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='$apid' AND section='$ses'");
$data = mysqli_fetch_array($query);
$nt="0.01";
if ($data!="")
{    $_SESSION['staffid']=$data['studentid'];
     header("location:viewpayment");
}else{
    $error="Applicant Email Address. ".$apid." Doesn't Exist, Check and Try Again";
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
                <div class="col-md-12">
                    <h5 class="page-head-line"></h5>
                </div>
            </div>
            <div class="row">
                <?php $dats= date("Y-m-d");
            if ($dead>=$dats) { ?>
                <div class="col-md-6">
                    <div class="account-card-content"> 
                    <form  action="" method="post">
                                        <label for="surname">Application Email</label>
                                        <input type="text" class="form-control" id="jambregno" name="jambreg" placeholder="Enter your Application Email" required>
                                        
                                        <div style="margin-top: 5%;" >
                                            <button class="btn btn-info"
                                                type="submit" name="registering">Click to Validated <i class="fa fa-sign-out"></i></button>    <b style='color: red;float: right;'>Already have Application ID? <a href="applogin" class="font-500 text-primary">Login here  </a> </b>
                                        </div>    
                                                  
                                </form>
                                <div> By registering you agree to the <?php echo htmlentities($orgname) ?> <a href="#">Terms of Use</a>
                        </div>
                               <!--  <br>
                                <br> -->
                </div>
                </div>
                <br>
                <div class="col-md-6">
                    <div class="alert alert-info" style="font-size: 14px;font-weight: bolder;">
                        Welcome to the <?php echo $orgname.", ".$schadress ?> Application Payment Re-verification
                        <br />
                        <strong>APPLICATION SESSION: <?php echo htmlentities($ses) ?> </strong><br>
                         <strong> Please Note :</strong>
                        <ol>
                            <li>
                               Application Deadline <?php 
                               //$old_date = date($dead);
                               //$old_date_timestamp = strtotime($old_date);
                               //$_POST['termb'] = date("l jS, \\Of F  Y", $old_date_timestamp);

                               
                            ?>
                            <span style="color:red"><?php echo htmlentities($_POST['termb']);?></span>.
                            </li>
                            <li>
                                Complete the form and click the submit button to continue.
                            </li>
                            <li>
                                Please use a valid email address.
                            </li>
                            <li>
                                debited but couldn't get the Application ID, Click <a href="payverification">Re-verify Payment</a>.
                            </li>
                             <li>
                                Any difficulty, reach us via email: info@edocohest.edu.ng
                            </li>
 <li>
                                Call Admission Officer on  08164550281, 08163226994, or 08055283934
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
                               $date1 = new DateTime($dead);
                               $now = new DateTime();
                               $diff = $date1->diff($now);

                               
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
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-credit-card"></i> Payment Page</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table border="1" class="table">
                                <tr>
                                    <td> Dear : <strong style="text-transform: capitalize;"><?php echo htmlentities($fname) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Email: <strong><?php echo htmlentities($mail) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Phone: <strong><?php echo htmlentities($pno) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Payment For: <strong><?php echo htmlentities(strtoupper($prog)) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Programme: <strong><?php echo htmlentities(ucwords($proh)) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Academic Session: <strong><?php echo htmlentities($ses) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Amount Paying: <strong>&#8358;<?php echo htmlentities(number_format($amt,2)) ?></strong></td>
                                </tr>
                               
                                <tr>
                                    <td>Transaction Charge: <strong>&#8358;<?php echo htmlentities(number_format($namt,2)) ?></strong></td>
                                </tr>
                            </table>
                            <p>You are paying for <strong><?php echo htmlentities($prog) ?></strong> for provisional admission offered into <b><?php echo htmlentities(ucwords($proh)) ?></b> in <b style="color: red;"><?php echo $orgname." ,".$schadress ?></b>. To continue creating your account, please click "Make Payment" to proceed to the next page.</p>
                        </div>
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">Cancel</button>
                        
                            <button type="button" onclick="payWithPaystack()"  class="btn btn-success">Make Payment</button>
                       
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
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transaction/verify/".rawurlencode($ref),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer sk_test_3577364ce91e875e8066480fd86ac3e16373890d",
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
  function customer($email){
        $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/customer/".($email),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer sk_test_3577364ce91e875e8066480fd86ac3e16373890d",
      "Cache-Control: no-cache",
    ),
  ));
  
  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);
  $users =json_decode($response,true);
  /*if ($err) {
    return json_decode('{"status": false, "message": "Could not Connect to paystack. API Timeout. "}');
  } else {
    return json_decode($response);
  }*/
    }   
?>
</html>