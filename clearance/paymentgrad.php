<?php
session_start();
$connection = TRUE;
require "pdf/fpdf.php";
include '../include/dbconfig.php';

include 'dataco.php';

  $_SESSION['uatCsrfToken'] = md5(uniqid());
  $_SESSION['uatCsrfSalt'] = md5(uniqid());
if(!isset($_SESSION['staffid']))
    {   
    header("Location: logout"); 
    }
    else{
             //$key='pk_test_6d3ed6a3e94ed97b260c66c069851bed0b017cd1';
             
       //$key='pk_live_bce2ff7b1ca7356a2ec1d17957e5268ec67e2d3e';
       //$spk='SPL_BEcs1DXoCe';
        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];
$matg='Acceptance Fee';
$matr=$_SESSION['staffid'];

$admy=$ses;
$feed=mysqli_fetch_assoc(mysqli_query($con,"SELECT amount FROM feesrecord WHERE purpose='$matg'"));
if ($feed!='') {
$amtd=$feed['amount'];
}
/* 
Payment Refence Generation 
*/
$length=5;
$string='';
$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $size = strlen($chars);
   for ($i = 0; $i < $length; $i++) {
       $string .= $chars[rand(0, $size - 1)];
       $date=date("Ymd");
       $ned='EDOCOHEST'.$date.''.$string;
  } 
  /* End Here */
    if (isset($_POST['makescpay'])) {
        
        $section=mysqli_real_escape_string($con,$admy);
        $prog=mysqli_real_escape_string($con,$matg);
        /*Payment Clearance Check*/
        $paich= mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ned' "));
         if ($paich=='') {
           $payref=mysqli_real_escape_string($con,$ned);
        }else{
            $neds=$ned+1;
            $payref=mysqli_real_escape_string($con,$neds);
        } 
        $feer=mysqli_fetch_assoc(mysqli_query($con,"SELECT amount FROM feesrecord WHERE purpose='$prog'"));
        if ($feer!='') {
        
        $amount=$feer['amount']+$t;
        $amt=$feer['amount'];
        $que= mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND purpose='$prog' AND section='$admy'");
        if(mysqli_num_rows($que)>0){
        $error="You Have Make Payment For This ".$prog;
        $showModalEr='true';
        }
        else{
            $tday=date("Y-m-d");
            $dated = date('Y-M-d'); 
            $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
            $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,remtime,status) VALUES ('".$_SESSION['staffid']."', '$admy','$pro','$prog','$amt','failed','$payref','$tday','$newd','Failed')");
            $showModal='true';
        }
        }else{
           $error="Amount Have Not Been Set for this ".$prog."  purpose";
           $showModalEr='true'; 
        }
    }
    //$key='MK_PROD_1Q6K9RH65A';   
//$spk='699979761851';
	//"incomeSplitConfig": [
        //{
           // "subAccountCode": "MFY_SUB_152274049842",
            ////"splitAmount": 100,
           // "feeBearer": true
        //}
   // ],

//$key='MK_TEST_ZNF7A71RMX';   
//$spk='3996172859';

$key='MK_PROD_DA332QTPV4';   
$spk='848892598133';
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
        <link rel="stylesheet" href="css/receipt.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
        <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
              <?php include('includes/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">

                    <?php include('includes/leftbar.php');?>  

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                               <div>
                                     <h2 class="title">Acceptance Page</h2>
                                </div>
                                <!-- Checking For Acceptance Pay -->
            
                        </div>
                       

                    </div>
                    <!-- /.main-page -->

                    <section class="section">
                            <div class="container-fluid">                          

                                <div class="row">
                                <div class="col-md-8 col-md-offset-2">    
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5></h5>
                                                </div>
                                            </div>
          
  
                                            <div class="panel-body">
                                                                                              
                                                  <form method="POST">
                                                    <div class="form-group">
                                                        
                                                        <label for="default" class="control-label">Your Name:</label>
                                                        <div class="">
                                                           <select name="fullname"   class="form-control">           
                                                        <option value="102030"><?php echo  htmlentities($fullname); ?></option>
                                                        </select>
                                                          
                                                        </div>
                                                            
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <label for="default" class="control-label">Payment Purpose:</label>
                                                        <div class="">
                                                           <select name="programme"   class="form-control">           
                                                        <option value="102030"><?php echo  htmlentities($matg); ?></option>
                                                        </select>
                                                          
                                                        </div>
                                                            
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <label for="default" class="control-label">Amount Paying (&#8358;):</label>
                                                        <div class="">
                                                           <select name="programme"   class="form-control">           
                                                        <option value="60706"><?php echo  htmlentities(number_format($amtd,2)); ?></option>
                                                        </select>
                                                          
                                                        </div>
                                                            
                                                    </div>
                                                    <div class="form-group has-success">

                                                        <div class="">
                                                            <button type="submit" name="makescpay" class="btn btn-success btn-label-right btn-label btn-label-right">Click-to-Pay <i class="fa fa-gear"></i> </button>
                                                    </div>
                                                  </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
                                    <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
                               
            <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-credit-card"></i><?php echo htmlentities($prog) ?> Payment Page
                            <button class="close" style="float:right;" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button></h5>
                        </div>
                        <div class="modal-body">
                           
                            <table border="1">
                                <tr>
                                    <td>Full Name: <strong style="text-transform: capitalize;"><?php echo htmlentities($fullname) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Email: <strong><?php echo htmlentities($email) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Faculty: <strong><?php echo htmlentities($facu) ?></strong></td>
                                </tr>
                                
                                <tr>
                                    <td>Programme: <strong><?php echo htmlentities($pro) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Academic Session: <?php echo htmlentities($section) ?></td>
                                </tr>
                                <tr>
                                    <td>Faculty: <?php echo htmlentities($facu) ?></td>
                                </tr>
                                <tr>
                                    <td>Programme: <?php echo htmlentities($pro) ?></td>
                                </tr>
                                
                                <tr>
                                    <td>Purpose: <?php echo htmlentities($prog) ?></td>
                                </tr>
                                <tr>
                                    <td>Amount Paying: &#8358;<?php echo htmlentities(number_format($amt,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Transaction Charge: &#8358;<?php echo htmlentities(number_format($t,2)) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">NO</button>
                        
                            <button type="button" onclick="payWithMonnify()"  class="btn btn-success">Click to Make Payment</button>
                       
                       </div>
                    </div>
                </div>
            </div>  
        </div>
        <!-- /.main-wrapper -->
        <script type="text/javascript" language="javascript">
 function payWithMonnify() {

      var fullname = '<?php echo htmlentities($fullname); ?>'; 
      var last = '<?php echo htmlentities($last); ?>';
      var amount = <?php echo htmlentities($amount)?>;
      var prog = '<?php echo htmlentities($prog)?>';
      var proh = '<?php echo htmlentities($ses)?>';
      var refs='<?php echo $payref; ?>';
      var prov = '<?php echo htmlentities($level)?>';
      var acad = '<?php echo htmlentities($payt)?>';
      var email = '<?php echo htmlentities($email)?>';
      var phone = '<?php echo htmlentities($pno) ?>';
      var studentid = "<?php echo htmlentities($matr) ?>";
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
			paymentDescription: "EDOCOHEST ACCEPTANCE FEE",

    "incomeSplitConfig": [
        {
            "subAccountCode": "MFY_SUB_600745458479",
            "splitAmount": 30000,
            "feeBearer": false
        },
        {
            "subAccountCode": "MFY_SUB_891671712178",
            "splitAmount": 500,
            "feeBearer": true,
            "feePercentage": 100
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
          var email = '<?php echo $email; ?>';
          var phone = '<?php echo $phone; ?>';
          var studentid = "<?php echo $matr; ?>";
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
            
            window.location.href = "success?feedback=success" + "&ref=" + response.transactionReference;
              
            }
           //alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");
          };
          
          xmlhttp.open("POST", "include/payp", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("firstname=" + fullname + "&email=" + email + "&phone=" + phone+ "&copy=" + proh+"&payty="+acad + "&progs=" + encodeURIComponent(proh)+"&method=" + response.paymentMethod +"&status=" + response.paymentStatus + "&mess=" + prog + "&ref=" + response.transactionReference+"&amount=" + response.amountPaid +"&studentid=" + studentid);
        
        },
        onClose: function(data) {
          console.log("Payment closed", data);
        }
           
      });

      handler.openIframe();
    }
    </script>
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
        
    </body>
</html>
<?php 
if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
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
if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    }
} 
?>