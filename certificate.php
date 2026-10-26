<?php
session_start();
$error='';
ob_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include 'include/dbconfig.php';

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];
//$key='pk_live_eb73a010958c2f95c86c47c5b5e8351a043e7eb2';
//$spk='SPL_xYzARhthVp';
   $key='pk_test_6d3ed6a3e94ed97b260c66c069851bed0b017cd1';
$length=5;
$string='';
$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $size = strlen($chars);
   for ($i = 0; $i < $length; $i++) {
       $string .= $chars[rand(0, $size - 1)];
       $date=date("Ymd");
       $ned='EDOCOE'.$date.''.$string;
  } 
if (isset($_POST["registering"])) {
    //$amts=mysqli_real_escape_string($con,$_POST["amount"]);
           $prog='Personal Transcript';
            $mail=mysqli_escape_string($con,$_POST['jambemail']);
            $pnos=mysqli_escape_string($con,$_POST['jambphone']);
            $first=mysqli_escape_string($con,$_POST['firstname']);
            $last=mysqli_escape_string($con,$_POST['surname']);
            $progs=mysqli_escape_string($con,$_POST['programm']);
            $dep=mysqli_escape_string($con,$_POST['department']);
            $year=mysqli_escape_string($con,$_POST['year']);
            $faculty=mysqli_real_escape_string($con,$_POST["faculty"]);

                            

     /*Payment Clearance Check*/
        $paich= mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ned' "));
         if ($paich=='') {
           $payref=mysqli_real_escape_string($con,$ned);
        }else{
            $neds=$ned+1;
            $payref=mysqli_real_escape_string($con,$neds);
        } 
    $resz = mysqli_query($con, "SELECT * FROM feesrecord WHERE purpose='$prog'");
    $row = mysqli_fetch_array($resz);
    if ($row=="") {
       $error="Amount not set for this payment";
       $showModaler='true';
    }else{
      $amtl=$row['amount'];
      $amount=$amtl+$namts;
        $tday=date("Y-m-d");
        $dated = date('Y-M-d'); 
        $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
        $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,remtime,status) 
        VALUES ('".$_SESSION['staffid']."', '$cads','$progr','$prog','$amtl','failed','$payref','$tday','$newd','Failed')");
                            $_SESSION['prog']=$prog;
                            $_SESSION['email']=$mail;                            
                            $_SESSION['phone']=$pnos;
                            $_SESSION['first']=$first;
                            $_SESSION['last']=$last;
                            $_SESSION['progs']=$progs;
                            $_SESSION['year']=$year;
                            $_SESSION['dep']=$dep;
                            $_SESSION['amt']=$amtl;
                             $_SESSION['faculty']=$faculty;

        $showModalp='true';
     }

}
if (isset($_POST["submitoff"])) {
    if (empty($_POST['copy'])) {
        $error="Please Select Official Type";
       $showModaler='true';
    }else{
$prog='Official Transcript';
$copy=mysqli_real_escape_string($con,$_POST["copy"]);
if ($copy==12) {
        $fac="Transcript Within Nigeria";
    }
    if ($copy==13) {
        $fac="Transcript Outside Nigeria";
    }
            $mail=mysqli_escape_string($con,$_POST['jambemail']);
            $pnos=mysqli_escape_string($con,$_POST['jambphone']);
            $first=mysqli_escape_string($con,$_POST['firstname']);
            $last=mysqli_escape_string($con,$_POST['surname']);
            $progs=mysqli_escape_string($con,$_POST['programm']);
            $dep=mysqli_escape_string($con,$_POST['department']);
            $year=mysqli_escape_string($con,$_POST['year']);
    $amonts=mysqli_real_escape_string($con,$_POST["amonts"]);
    $addresst=mysqli_real_escape_string($con,$_POST["addresst"]);
    $orgemail=mysqli_real_escape_string($con,$_POST["orgemail"]);
    $address=mysqli_real_escape_string($con,$_POST["address"]);
    $faculty=mysqli_real_escape_string($con,$_POST["faculty"]);
    /*Payment Clearance Check*/
    $paich= mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ned' "));
    if ($paich=='') {
      $payref=mysqli_real_escape_string($con,$ned);
    }else{
        $neds=$ned+1;
        $payref=mysqli_real_escape_string($con,$neds);
    }
    $resz = mysqli_query($con, "SELECT * FROM feesrecord WHERE purpose='$fac'");
    $row = mysqli_fetch_array($resz);
    if ($row=="") {
       $error="Amount not set for this payment";
       $showModaler='true';
    }else{
        $amtll=$row['amount'];
        $amount=$amtll+$namts;
        $tday=date("Y-m-d");
        $dated = date('Y-M-d'); 
        $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
        $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,remtime,status,firstname,lastname,email)
        VALUES ('".$_SESSION['staffid']."', '$cads','$progr','$fac','$amtll','failed','$payref','$tday','$newd','Failed','$addresst','$address','$orgemail')");
         $_SESSION['prog']=$prog;
                            $_SESSION['email']=$mail;                            
                            $_SESSION['phone']=$pnos;
                            $_SESSION['first']=$first;
                            $_SESSION['last']=$last;
                            $_SESSION['progs']=$progs;
                            $_SESSION['year']=$year;
                            $_SESSION['dep']=$dep;
                            $_SESSION['amt']=$amtll;
                            $_SESSION['addresst']=$addresst;
                            $_SESSION['address']=$address;
                            $_SESSION['orgemail']=$orgemail;
                            $_SESSION['copy']=$fac;
                            $_SESSION['faculty']=$faculty;

      $showModalof='true';
     }
    }
}
 /*End Here*/


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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://js.paystack.co/v1/inline.js"></script>
    <script type="text/javascript">
          function changes(objs){
    var selectBox = objs;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var retCustDetails = document.getElementById("retCustDetails");
    var tradeCustDetails = document.getElementById("tradeCustDetails");

    if(selected == 'Yes'){
        retCustDetails.style.display = "block";
        tradeCustDetails.style.display = "none";
    }else if (selected == 'No') {
         window.location.href='registration'
    }
    else{
        retCustDetails.style.display = "none";
        tradeCustDetails.style.display = "none";
    }
}
      </script>
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
                <div class="col-md-12" style="height:100vh;"> 
                    <div class="account-card-content">
                        <div class="panel-body">
                                                           
                <section>
                                                   <div class="col-md-6" style="height:100vh;"> 
                    <div class="account-card-content"> 
                    <form  action="" method="post">
                      
                    <label for="surname">Full Name</label>
                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter your FirstName"  value="" required>
                
                    <label for="firstname">Matric No</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter your LastName" onkeypress="return (event.keyCode != 32&&event.which!=32)" value="" required>
                  <label for="surname">Your Email</label>
                    <input type="email" class="form-control" name="jambemail" placeholder="Enter your Verified Email"  required>
                    <label for="firstname">Your Phone</label>
                    <input type="tel" class="form-control" name="jambphone" maxlength="13" onkeypress='return event.charCode >= 48 && event.charCode <= 57'  placeholder="Enter Phone Number" required>
                    <label for="faculty">School</label>
                    <select class="form-control" name="faculty" id="faculty">
                                        
                                          
                                    <option selected disabled>Please select School</option>
                                      <?php
                                       $resz = mysqli_query($con, "SELECT * FROM faculty WHERE prog='NCE' ORDER BY fname");
                                        while ($row = mysqli_fetch_array($resz)) {
                                            ?>
                                            <option value="<?php echo  $row["fname"]; ?>"><?php echo  $row["fname"]; ?></option>
                                        <?php
                                        }
                                      ?>   
                                    </select>
                    
                    <label for="department">Department</label>
                    <select class="form-control" name="department" id="department">
                                    <option selected disabled>Please select School</option>
                                      <?php
                                        $resz = mysqli_query($con, "SELECT * FROM program WHERE degree='NCE' ORDER BY pname");
                                        while ($row = mysqli_fetch_array($resz)) {
                                            ?>
                                            <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                                        <?php
                                        }
                                      ?>   
                                    </select>
                    
                     <label for="firstname">Year of Graduation</label>
                     <select name="year" class="form-control" id="yearpicker"></select>

    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"> 
    </script>
    <script type="text/javascript">
    let startYear = 1800;
    let endYear = new Date().getFullYear();
    for (i = endYear; i > startYear; i--)
    {
      $('#yearpicker').append($('<option />').val(i).html(i));
    }
    </script>
                    
                     <label>School Attended</label>
                                    <select class="form-control" name="programm" id="programme">
                                        
                                          
                                    <option selected disabled>Please select School</option>
                                      <?php
                                          
                                        
                                        $resz = mysqli_query($con, "SELECT * FROM oldschool ORDER BY schoolname");
                                        while ($row = mysqli_fetch_array($resz)) {

                                            ?>
                                            <option value="<?php echo  $row["schoolname"]; ?>"><?php echo  $row["schoolname"]; ?></option>
                                        <?php

                                        }
                                      ?>   
                                    </select>
                    <div style="margin-top:2%;">
                    <button class="btn btn-danger" type="submit" name="registering">Submit Request<i class="fa fa-refresh"></i>
                    </div>    
                                                        

                                        
                    </form>
                               <!--  <br>
                                <br> -->
                                
                </div>
                </div><br>
                <div class="col-md-6">
                    <div class="alert alert-info" style="font-size: 14px;font-weight: bolder;">
                        Personal Transcript Copy Application Portal
                        <br />
                                                   
                                                      <strong>APPLICATION PROCESS</strong><br>

                              <ol>
                            <li>
                                Fill All Feild Correctly (Required).
                            </li>
                            <li>
                               Please use a valid email address.
                            </li> <li>
                               We advice you to use Card Payment.
                            </li>
                             <li>
                                Any difficulty, reach us via email: info@edocoe.edu.ng
                            </li>
 <li>
                                For Support Call Exams and Record Officer on 08095536133 or 09051181195
                            </li>
<li>
                            Personal Transcript Fee is Twenty Two Thousand Naira Only (N22000) Non-Refundable excluding Service Charge Fee.
                            </li>
                            
                        </ol>
                       
                    </div>
                 </div>
                           
                        </section>
                        
                      
                    </div>
                </div>
            </div>
                                    <!-- /.col-md-12 -->
                                </div>
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
         <!-- Payment Option -->
     <div class="modal fade" id="checkModalp" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-credit-card"></i>Personal Transcript Payment Page
                            <button class="close" style="float:right;" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button></h5>
                        </div>
                        <div class="modal-body">
                           
                            <table border="1" class="table">
                                <tr>
                                    <td>Full Name: <strong style="text-transform: capitalize;"><?php echo htmlentities($last) ?></strong></td>
                                </tr>
                                
                                <tr>
                                    <td>Department: <?php echo htmlentities($dep) ?></td>
                                </tr>
                                <tr>
                                    <td>School Attanded: <?php echo htmlentities($progs) ?></td>
                                </tr>
                                <tr>
                                    <td>Purpose: <?php echo htmlentities($prog) ?></td>
                                </tr>
                                <tr>
                                    <td>Amount Paying: &#8358;<?php echo htmlentities(number_format($amtl,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Transaction Charge: &#8358;<?php echo htmlentities(number_format($namts,2)) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">NO</button>
                        
                            <button type="button" onclick="payWithPaystacks()"  class="btn btn-success">Click to Make Payment</button>
                       
                       </div>
                    </div>
                </div>
            </div>  
    <!-- Payment Option -->

    <!-- Payment Option -->
     <div class="modal fade" id="checkModalof" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-credit-card"></i>Official Transcript Payment Page
                            <button class="close" style="float:right;" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button></h5>
                        </div>
                        <div class="modal-body">
                           
                            <table border="1" class="table">
                                <tr>
                                    <td>Full Name: <strong style="text-transform: capitalize;"><?php echo htmlentities($last) ?></strong></td>
                                </tr>
                                
                                <tr>
                                    <td>Department: <?php echo htmlentities($dep) ?></td>
                                </tr>
                                <tr>
                                    <td>School Attanded: <?php echo htmlentities($progs) ?></td>
                                </tr>
                                <tr>
                                    <td>Transcript Type: <?php echo htmlentities($prog) ?></td>
                                </tr>
                                <tr>
                                    <td>Official Type: <?php echo htmlentities($fac) ?></td>
                                </tr>
                                <tr>
                                    <td>Amount Paying: &#8358;<?php echo htmlentities(number_format($amtll,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Transaction Charge: &#8358;<?php echo htmlentities(number_format($namts,2)) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">NO</button>
                        
                            <button type="button" onclick="payWithPaystack()"  class="btn btn-success">Click to Make Payment</button>
                       
                       </div>
                    </div>
                </div>
            </div>  

        <!-- /.main-wrapper -->
        <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
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
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>There No Result Base on the Criterial Selected</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
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
            <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalx" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Message Successfully Sent</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Student Details -->
            <div class="modal" id="viewModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Student Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_details">

                  </div>

      <!-- Modal footer -->
              <div class="modal-footer">
                
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div> 
        <!-- ========== COMMON JS FILES ========== -->
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
    if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
            });
        </script>';
    }
    if(!empty($showModals)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModals").modal("show");
            });
        </script>';
    }
    if(!empty($showModalx)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalx").modal("show");
            });
        </script>';
    }
    
    if(!empty($showModalp)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalp").modal("show");
            });
        </script>';
            }
    if(!empty($showModalof)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalof").modal("show");
            });
        </script>';
            }
        ?>
     <script type="text/javascript">
function payWithPaystacks() {
     var fullname = '<?php echo htmlentities($last); ?>';
      var amount = <?php echo htmlentities($amount)?>*100;
      var prog = '<?php echo htmlentities($prog)?>';
      var email = '<?php echo $mail ?>';
      var phone = '<?php echo htmlentities($phone) ?>';
      var prg = '<?php echo $dep; ?>';
      var fac = '<?php echo $progs; ?>';
      var year = '<?php echo $year; ?>';
      var refs='<?php echo $payref; ?>';
      var studentid = "<?php echo $first; ?>";  
      var pbk='<?php echo htmlentities($key) ?>';
      var spl='<?php echo htmlentities($spk) ?>'; 
      var fullname = fullname;  
      
      var handler = PaystackPop.setup({
        key: pbk,
        split_code:spl,
        email: email,
        ref: refs,
        amount: amount,
        currency: "NGN",
        
         metadata: {
          custom_fields: [
            {  
              display_name: "Mobile Number",
              variable_name: "mobile_number",
              value: phone,
            },
            {
              display_name: "Full Name",
              variable_name: "full_name",
              value: fullname,
            },
            {
              display_name: "Academic Session",
              variable_name: "Academic Session",
              value: fac
            },
            {
              display_name: "Programme",
              variable_name: "Programme",
              value: prg
            },
            {
              display_name: "Payment Purpose",
              variable_name: "Payment Purpose",
              value: prog
            }
          ]
        },
        callback: function (response) {          

        var email = '<?php echo htmlentities($email) ?>';
        var phone = '<?php echo htmlentities($phone)?>';
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
             
            window.location.href = "success?feedback=success" + "&ref=" + response.reference;
              
            }
           //alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");
          };
          
          xmlhttp.open("POST", "include/transcriptpay", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("fullname="+fullname + "&email=" + email + "&phone=" +phone+ "&ref=" + response.reference + "&ref=" + response.reference+ "&prog=" + prog+ "&studentid=" + studentid+"&prg="+prg+"&year="+year+"&fac="+fac);}
      });

      handler.openIframe();
    }
    </script>
        <script type="text/javascript">
function payWithPaystack() {
     var fullname = '<?php echo htmlentities($last); ?>';
      var amount = <?php echo htmlentities($amount)?>*100;
      var prog = '<?php echo htmlentities($prog)?>';
      var email = '<?php echo $mail ?>';
      var phone = '<?php echo htmlentities($phone) ?>';
      var prg = '<?php echo $dep; ?>';
      var fac = '<?php echo $progs; ?>';
      var year = '<?php echo $year; ?>';
      var refs='<?php echo $payref; ?>';
      var studentid = "<?php echo $first; ?>"; 
      var copy = '<?php echo htmlentities($orgemail) ?>';
      var leveld = '<?php echo htmlentities($addresst) ?>';      
      var adres = '<?php echo htmlentities($address)?>';

      var pbk='<?php echo htmlentities($key) ?>';
      var spl='<?php echo htmlentities($spk) ?>'; 
      var fullname = fullname;  
      
      var handler = PaystackPop.setup({
        key: pbk,
        split_code:spl,
        email: email,
        ref: refs,
        amount: amount,
        currency: "NGN",
        
         metadata: {
          custom_fields: [
            {  
              display_name: "Mobile Number",
              variable_name: "mobile_number",
              value: phone,
            },
            {
              display_name: "Full Name",
              variable_name: "full_name",
              value: fullname,
            },
            {
              display_name: "Academic Session",
              variable_name: "Academic Session",
              value: fac
            },
            {
              display_name: "Programme",
              variable_name: "Programme",
              value: prg
            },
            {
              display_name: "Payment Purpose",
              variable_name: "Payment Purpose",
              value: prog
            }
          ]
        },
        callback: function (response) {          

        var email = '<?php echo htmlentities($email) ?>';
        var phone = '<?php echo htmlentities($phone)?>';
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
             
            window.location.href = "success?feedback=success" + "&ref=" + response.reference;
              
            }
           //alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");
          };
          
          xmlhttp.open("POST", "include/transcriptpay", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("fullname="+fullname + "&email=" + email + "&phone=" +phone+ "&ref=" + response.reference + "&ref=" + response.reference+ "&prog=" + prog+ "&studentid=" + studentid+"&prg="+prg+"&year="+year+"&fac="+fac);}
      });

      handler.openIframe();
    }
  
  </script>
    </body>
</html>