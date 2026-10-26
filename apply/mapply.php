<?php
session_start();
$error='';
ob_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include '../include/dbconfig.php';

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];

$prog='Application Fee';
/*Start Here*/
$length=5;
$string='';
$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $size = strlen($chars);
   for ($i = 0; $i < $length; $i++) {
       $string .= $chars[rand(0, $size - 1)];
       $date=date("Ymd");
       $ned='EDOCOHEST'.$date.''.$string;
  } 
 /*End Here*/

if (isset($_POST["registering"])) {
       
        if (empty($_POST['programm'])) {
            $error="Please Select Programme";
        $showModalEr='true';
        }elseif(empty($_POST['surname'])){
            $error="Surname is Required";
            $showModalEr='true';
        }elseif(empty($_POST['firstname'])){
            $error="Firstname is Required";
            $showModalEr='true';
        }elseif(empty($_POST['jambemail'])){
            $error="Email is Required";
            $showModalEr='true';
        }elseif(empty($_POST['jambphone'])){
            $error="Phone Number is Required";
            $showModalEr='true';
        }
        else{
            $pros=mysqli_escape_string($con,$_POST['programm']);
            $pd=mysqli_fetch_assoc(mysqli_query($con,"SELECT pname FROM program WHERE pid='$pros'"));
            if ($pd['pname']=='') {
               $error='Programme Selected Not Available';
               $showModalEr='true';
            }else{
            $proh=$pd['pname'];
            $mail=mysqli_escape_string($con,$_POST['jambemail']);
            $pnos=mysqli_escape_string($con,$_POST['jambphone']);
            $first=mysqli_escape_string($con,$_POST['firstname']);
            $last=mysqli_escape_string($con,$_POST['surname']);
            $paich= mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ned' "));
            if ($paich=='') {
               $payref=mysqli_real_escape_string($con,$ned);
            }else{
                $neds=$ned+1;
                $payref=mysqli_real_escape_string($con,$neds);
            }
            $filtered_phone_number = filter_var($pnos, FILTER_SANITIZE_NUMBER_INT);
            $phone_to_check = str_replace("-", "", $filtered_phone_number);
            if (strlen($phone_to_check) < 11 || strlen($phone_to_check) > 14) {
                $error='Invalid Phone Number';
                $showModalEr='true';
             } else {
                $pno=$phone_to_check;
                $fname=$first." ".$last;
                $fullname=$fname;
                if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email Address";
                $showModalEr='true';
                }else{
           
                $feer=mysqli_fetch_assoc(mysqli_query($con,"SELECT amount FROM feesrecord WHERE purpose='$prog'"));
                if ($feer=="") {
                               $error="It Seems ".ucwords($prog)." Amount Has Not Been Set Contact the Bursary";
                            $showModalEr='true';
                           }else{
                
                $amount=$feer['amount']+$t;
                $amt=$feer['amount'];
                /*End Here*/
                
                        $que= mysqli_query($con,"SELECT * FROM applicant WHERE (email='$mail' OR phoneno='$pno') AND session='$ses' AND apptype='NCE' ");
                        if(mysqli_num_rows($que)>0){
                        $error="Your Email ".$mail. " Or Phone ".$pno." Details Is Already Exist";
                        $showModalEr='true';
                        }
                        else{
                            /*End Here*/
                        $tday=date("Y-m-d");
                        $dated = date('Y-M-d'); 
                        $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
                        $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,remtime,status,firstname,lastname,phoneno,email) VALUES ('$mail', '$ses','$proh','$prog','$amt','failed','$payref','$tday','$newd','Failed','$first','$last','$pno', '$mail') ");
                            $showModal='true';
                            }
                          }
                               
                    }   
                }
             }
        }
    } 
$key='MK_PROD_1Q6K9RH65A';   
//$key='pk_live_eb73a010958c2f95c86c47c5b5e8351a043e7eb2';
$spk='699979761851';
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
    <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>
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
                <div class="col-md-6" style="height:100vh;"> 
                    <div class="account-card-content"> 
                    <form  action="" method="post">
                      
                    <label for="surname">First Name</label>
                    <input type="text" class="form-control" id="surname" name="firstname" placeholder="Enter your FirstName" onkeypress="return (event.keyCode != 32&&event.which!=32)" value="" required>
                
                    <label for="firstname">Last Name (Family Name)</label>
                    <input type="text" class="form-control" id="firstname" name="surname" placeholder="Enter your LastName" onkeypress="return (event.keyCode != 32&&event.which!=32)" value="" required>
                     <label>Programme/Course to Study</label>
                                    <select class="form-control" name="programm" id="programme">
                                        
                                          
                                    <option selected disabled>Please select Programme</option>
                                      <?php
                                          
                                        
                                        $resz = mysqli_query($con, "SELECT * FROM program WHERE (degree='ND' OR degree='CERT' )ORDER BY pname");
                                        while ($row = mysqli_fetch_array($resz)) {

                                            ?>
                                            <option value="<?php echo  $row["pid"]; ?>"><?php echo  $row["pname"]; ?></option>
                                        <?php

                                        }
                                      ?>   
                                    </select> 
                    <label for="surname">Your Email</label>
                    <input type="email" class="form-control" name="jambemail" placeholder="Enter your Verified Email"  required>
                    <label for="firstname">Your Phone</label>
                    <input type="tel" class="form-control" name="jambphone" maxlength="13" onkeypress='return event.charCode >= 48 && event.charCode <= 57'  placeholder="Enter Phone Number" required>
                    <div style="margin-top:2%;">
                    <button class="btn btn-danger"
                    type="submit" name="registering">Click to Submit <i class="fa fa-refresh"></i></button> <b style='color: red;float: right;'>Already have an Application ID ? <a href="applogin" class="font-500 text-primary">Login here </a> </b>
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
                        NCE Application Portal <?php echo htmlentities($ses) ?> Session
                        <br />
                        <strong>ADMISSION REQUIREMENT</strong<strong> (Deadline: <?php 
                               $old_date = date($dead);
                               $old_date_timestamp = strtotime($old_date);
                               $_POST['termb'] = date("l jS, \\Of F  Y", $old_date_timestamp);

                               
                            ?></strong>                            <span style="color:red"><?php echo htmlentities($_POST['termb']);?></span>)<br>
        <ol>
                            <li>
                               Interested candidate must have sat for the 2023/2024 JAMB Examination.
                            </li>
                            <li>
Must have score 100 and above.                            
</li>
                            <li>
                                	Must have Credit passes in Five (5) subjects including English and Mathematics.
                            </li>
                            <li>
                                Candidate who did not chose the College can Do a Change of Institution to Edo State College of Education as first choice and upload SSCE result into the JAMB portal in any CBT Centre.
                            </li>
                              </ol>
                                                      <strong>APPLICATION PROCESS</strong><br>

                              <ol>
                            <li>
                                Please use a valid email address.
                            </li>
                            <li>
                                Debited but couldn't get the Application ID, Click <a href="payverification">Re-verify Payment</a>.
                            </li>
                             <li>
                                Any difficulty, reach us via email: admission@edocohest.edu.ng
                            </li>
 <li>
                                For Support Call Admission Officer on 07060441705 or 09051181195
                            </li>
<li>
                            Application Fee is Two Thousand Naira Only (N2000) Non-Refundable excluding Service Charge Fee.
                            </li>
                            
                        </ol>
                       
                    </div>
                 </div>
                <?php }else{ ?>
                <div class="col-md-6">
                    <div class="account-card-content"> 
                        <p style="color:red;">Admission Application for new intake has not started or ended. </p>
                                       
                          <div style="margin-top: 5%;" >
                             <b style='color: red;'>Already applied? <a href="applogin" class="font-500 text-primary">Login here  </a> </b>
                        </div>
                </div>
                </div>

                <div class="col-md-6">
                    <div class="alert alert-info" style="font-size: 14px;font-weight: bolder;">
                        Welcome to the <?php echo $orgname.", ".$schadress ?> Application Portal
                        <br />
                        <strong>APPLICATION SESSION: <?php echo htmlentities($ses) ?> </strong><br>
                         <strong> Please Note :</strong>
                        <ol>
                            <li>
                               Application has ended <?php 
                               $date1 = new DateTime($dead);
                               $now = new DateTime();
                               $diff = $date1->diff($now);
                               $old_date_timestamp = strtotime($diff);
                               $_POST['termb'] = date("l jS, \\Of F  Y", $old_date_timestamp);
                               
                            ?>
                            <span style="color:red"><?php echo htmlentities($diff->days);?> Day's Ago</span>.
                            </li>
                             <li>
                                Any Difficulty Reach us via Email: admission@edocohest.edu.ng
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
                                <tr>
                                    <td>Total Amount: <strong>&#8358;<?php echo htmlentities(number_format($amount,2)) ?></strong></td>
                                </tr>
                            </table>
                            <p>You are paying for <strong><?php echo htmlentities($prog) ?></strong> for  Admission Application into <b><?php echo htmlentities(ucwords($proh)) ?></b> in <b style="color: red;"><?php echo $orgname?></b>. To continue creating your account, please click "Make Payment" to proceed to the next page.</p>
                        </div>
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">Cancel</button>
                        
                            <button type="button" onclick="payWithMonnify()"  class="btn btn-success">Make Payment</button>
                       
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
    <script type="text/javascript">
        function payWithMonnify() {

      var fullname = '<?php echo htmlentities($fullname); ?>'; 
      var first = '<?php echo htmlentities($first); ?>';
      var last = '<?php echo htmlentities($last); ?>';
      var amount = <?php echo htmlentities($amount)?>;
      var prog = '<?php echo htmlentities($prog)?>';
      var proh = '<?php echo $proh?>';
      var refs='<?php echo $payref; ?>';
      var prov = '<?php echo htmlentities($pros)?>';
      var acad = '<?php echo htmlentities($ses)?>';
      var email = '<?php echo htmlentities($mail)?>';
      var phone = '<?php echo htmlentities($pno) ?>';
      var pbk='<?php echo htmlentities($key) ?>';
      var spl='<?php echo htmlentities($spk) ?>';
      var fullname = fullname;  
      MonnifySDK.initialize({
          amount: amount,
          currency: "NGN",
          reference: refs,
          phoneNumber: phone,
          customerName: fullname,
          customerEmail: email,
          apiKey: pbk,
          contractCode: spl,
			paymentDescription: "EDOCOHEST ADMISSION PAYMENT",
			
			"incomeSplitConfig": [
        {
            "subAccountCode": "MFY_SUB_152274049842",
            "splitAmount": 100,
            "feeBearer": true
        }
    ],
          "metadata": {
            "name": fullname,
            "Session": acad,
            "Purpose": prog,
            "Programme": proh 
          },
          
          onLoadStart: () => {
            console.log("loading has started")
          },
          onLoadComplete: () => {
            console.log("SDK is UP")
          },
        
          onComplete: function (response) {
        var email = '<?php echo htmlentities($mail) ?>';
        var phone = '<?php echo htmlentities($pno)?>';
        var xmlhttp = "";

          if (window.XMLHttpRequest) {
              // code for modern browsers
              xmlhttp = new XMLHttpRequest();
          } else {
              // code for old IE browsers
              xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          }

          xmlhttp.onreadystatechange = function() {
           if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            
            window.location.href = "successs?feedback=success" + "&ref=" + response.transactionReference;
              
            }
           //alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");
          };
          
          xmlhttp.open("POST", "include/pay", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("firstname=" + fullname + "&email=" + email + "&phone=" + phone + "&progs=" + encodeURIComponent(proh) + "&prog=" + prog + "&ref=" + response.transactionReference+"&status=" + response.paymentStatus+"&method=" + response.paymentMethod+"&amount=" + response.amountPaid+"&acads="+acad+"&first="+first+"&last="+last+"&prov="+prov);
        
        },
        onClose: function(data) {
          console.log("Payment closed", data);
        }
           
      });

      handler.openIframe();
    }
    </script>
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
?>
</html>