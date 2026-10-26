<?php 
session_start();
$connection = TRUE;
include "../include/dbconfig.php";
include "dataco.php";
if (!isset($_SESSION['staffid']))
    header("Location: ../logout");
    $harr = $_SESSION['staffid'];

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];
$error='';
$succ='';
             //$key='pk_test_6d3ed6a3e94ed97b260c66c069851bed0b017cd1'; 
       //$key='pk_live_eb73a010958c2f95c86c47c5b5e8351a043e7eb2';
//$spk='SPL_zvNwJhkL8R';
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
    if (isset($_POST['makescpay'])) {
        if (empty($_POST['section'])) {
            $error="Please Select Session Paying For";
        $showModalEr='true';
        }elseif (empty($_POST['level'])){
            $error="Please Select Level Paying For";
        $showModalEr='true';
        }elseif(empty($_POST['purpose'])){
        $error="Please Select Fee Type";
        $showModalEr='true';
        }elseif(empty($_POST['paytype'])){
        $error="Please Select Fee Type";
        $showModalEr='true';
        }else{
        $section=mysqli_escape_string($con,$_POST['section']);
        $level=mysqli_escape_string($con,$_POST['level']);
        $prog=mysqli_escape_string($con,$_POST['purpose']);
        $payt=mysqli_escape_string($con,$_POST['paytype']);
        /*Querying the Payment Payment Reference*/
        $paich= mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ned' "));
        if ($paich=='') {
           $payref=mysqli_real_escape_string($con,$ned);
        }else{
            $neds=$ned+1;
            $payref=mysqli_real_escape_string($con,$neds);
        }
        
        /*Payment Clearance Check*/
        if ($payt=="Full") {
          /*Payment Check*/  
        $chk=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentregsem WHERE (studentid='".$_SESSION['staffid']."' OR studentid='$matr') AND session='$section' AND level='$level'"));
                   
        if ($chk=="") { 
            
           $feer=mysqli_fetch_assoc(mysqli_query($con,"SELECT amount FROM feesrecord WHERE purpose='$prog' AND  paytype='$payt' AND level='$level' "));
           if ($feer=="") {
            $error="It Seems School Fee Amount Have Not Been Set For This Level ".$level; 
            $showModalEr='true';
           }else{
            $per=$namts;
            $nam=$feer['amount'];
            $amt=$feer['amount'];
            $amount=$feer['amount']+$per;

            $que= mysqli_query($con,"SELECT * FROM studentpayment WHERE (studentid='".$_SESSION['staffid']."' OR studentid='$matr') AND level='$level' AND purpose='$prog' AND section='$section' ");
            if(mysqli_num_rows($que)>0){
            $error="You Have Make Payment For This Fee ".$prog;  
            $showModalEr='true';
            }
            else{
                $tday=date("Y-m-d");
        $dated = date('Y-M-d'); 
        $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
            $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,level,programme,purpose,amount,modes,paymentreference,paytime,remtime,paytype,status) 
            VALUES ('".$_SESSION['staffid']."', '$section','$level','$pro','$prog','$amt','Tuition','$payref','$tday','$newd','$payt','Attempt') ");
                $showModal='true';
               }
          } 
      //
            }else{
            $seml=$chk['semester'];
            $error="You have paid half school fee of ".$seml." for this ".$section." Academic Session kindly Use Payment by Part";
            $showModalEr='true';
            }
        }

        else{
        $semest=mysqli_escape_string($con,$_POST['semest']);
         $chk=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentregsem WHERE (studentid='".$_SESSION['staffid']."' OR studentid='$matr') AND session='$section' AND semester='$semest' AND level='$level'"));
         if ($chk=="") { 
        $feer=mysqli_fetch_assoc(mysqli_query($con,"SELECT amount FROM feesrecord WHERE purpose='$prog' AND  paytype='$payt' AND semester='$semest' AND level='$level' "));
         if ($feer=="") {
               $error="It Seems School Fee Amount Have Not Been Set For This Level ".$prog."  ".$payt."   ".$semest." ".$level."  ";
            $showModalEr='true';
           }else{
            $per=$namts;
            $nam=$feer['amount'];
            $amt=$feer['amount'];
            $amount=$feer['amount']+$per;
            $que= mysqli_query($con,"SELECT * FROM studentpayment WHERE (studentid='".$_SESSION['staffid']."' OR studentid='$matr') AND level='$level' AND purpose='$pro' AND section='$section' AND semester='$semest' ");
            if(mysqli_num_rows($que)>0){
            $error="You Have Make Payment For This ".$semest." And ".$level." Level ".$prog;
            $showModalEr='true';
            }else{
            $que=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(amount) As atm FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND level='$level' AND purpose='$prog' AND section='$section' "));
            $pad=$que['atm'];
            if($feer==$pad){
            $error="You Have Complted Your Fee For This Level ".$level;
            $showModalEr='true';
            }
            else{
                $tday=date("Y-m-d");
                $dated = date('Y-M-d'); 
                $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
                $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,level,semester, programme,purpose,amount,modes,paymentreference,paytime,remtime,paytype,status) 
                VALUES ('".$_SESSION['staffid']."', '$section','$level','$semest','$pro','$prog','$amt','Tuition','$payref','$tday','$newd','$payt','Attempt') ");
                $showModals='true';
               } 
           }
          }
          }else{
           $seml=$chk['semester'];
            $error="You have paid half school fee for this ".$seml." and ".$section." Academic Session choose the other semester for Payment";
            $showModalEr='true';
            }
        }
        }
    }
 /*paymentDescription: "EDOCOHEST TUITION FEE", 
			"incomeSplitConfig": [
        {
            "subAccountCode": "MFY_SUB_188826491174",  
            "splitAmount": 375000,
            "feeBearer": false
        }, 
        {
            "subAccountCode": "MFY_SUB_891671712178",
            "splitAmount": 2000,
            "feeBearer": true,
            "feePercentage": 100
        }
],*/

//$key='MK_TEST_ZNF7A71RMX';   
//$spk='3996172859';

$key='MK_PROD_DA332QTPV4';   
$spk='848892598133'; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen"> 
        <script src="js/jquery/jquery-2.2.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>
        <script type="text/javascript">
            
    function change(obj) {

    var selectBox = obj;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var retCustDetails = document.getElementById("retCustDetails");
    if(selected == 'Part'){
        retCustDetails.style.display = "block";
    }
    else{
        retCustDetails.style.display = "none";
        tradeCustDetails.style.display = "none";
    }
}
        </script>
        <style>
        :root {
            --primary: #2b5f5a;
            --primary-dark: #1e4541;
            --primary-light: #4a7c77;
            --primary-soft: #e8f3f1;
            --sidebar-width: 280px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            overflow-x: hidden;
        }
        
        /* Main Wrapper */
        .main-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Content Wrapper */
        .content-wrapper {
            display: flex;
            flex: 1;
        }
        
        /* Content Container */
        .content-container {
            flex: 1;
            padding: 20px 30px;
            transition: all 0.3s ease;
        }
        
        /* Page Header */
        .page-header-modern {
            background: linear-gradient(135deg, #1a2525 0%, #1e332f 100%);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            color: white;
        }
        
        .page-header-modern h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .page-header-modern p {
            margin: 8px 0 0;
            opacity: 0.85;
            font-size: 0.9rem;
        }
        
        /* Payment Card */
        .payment-card {
            background: white;
            border-radius: 24px;
            border: none;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }
        
        .payment-card-header {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            padding: 1.2rem 1.5rem;
            color: white;
        }
        
        .payment-card-header h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .payment-card-body {
            padding: 1.8rem;
        }
        
        /* Form Elements */
        .form-group-modern {
            margin-bottom: 1.5rem;
        }
        
        .form-label-modern {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.85rem;
        }
        
        .form-label-modern i {
            color: var(--primary);
            width: 20px;
        }
        
        .form-control-modern {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s;
            width: 100%;
            background: white;
        }
        
        .form-control-modern:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(43, 95, 90, 0.1);
            outline: none;
        }
        
        select.form-control-modern {
            cursor: pointer;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%232b5f5a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>');
            background-repeat: no-repeat;
            background-position: right 1rem center;
        }
        
        /* Payment Button */
        .btn-payment {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 40px;
            padding: 12px 28px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-payment:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(43, 95, 90, 0.3);
            color: white;
        }
        
        /* Info Alert */
        .info-alert-modern {
            background: var(--primary-soft);
            border-left: 4px solid var(--primary);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        /* Modal Styles */
        .modal-modern .modal-content {
            border-radius: 24px;
            border: none;
            overflow: hidden;
        }
        
        .modal-modern .modal-header {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 1rem 1.5rem;
        }
        
        .modal-modern .modal-header .btn-close {
            background: white;
            border-radius: 50%;
            opacity: 1;
        }
        
        .payment-details-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .payment-details-table tr {
            border-bottom: 1px solid #eef2f6;
        }
        
        .payment-details-table td {
            padding: 12px 8px;
        }
        
        .payment-details-table td:first-child {
            font-weight: 600;
            color: #4a5568;
            width: 40%;
        }
        
        .payment-details-table td:last-child {
            color: #1e2a3a;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .content-container {
                padding: 70px 20px 20px 20px;
            }
        }
        
        @media (max-width: 768px) {
            .page-header-modern {
                padding: 1rem 1.5rem;
            }
            
            .page-header-modern h3 {
                font-size: 1.2rem;
            }
            
            .payment-card-body {
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php');?>   
          <!-----End Top bar>-->
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

<!-- ========== LEFT SIDEBAR ========== -->
<div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title"><?php echo "STUDENT FEES PAYMENT SETUP" ?></h3>
                                </div>
                                
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    
                                </div>
                               
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

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
                                                        <label for="default" class="control-label">SESSION:</label>
                                                        <select name="section" class="form-control" >
                                                            <option value="<?php echo htmlentities($ses);?>"><?php echo htmlentities($ses);?></option>
                                                        </select>
                                                	</div>
                                                   
                                                    
                    
                                                    <div class="form-group">
                                                        <label for="default" class="control-label">LEVEL:</label>
                                                        <div class="">
                                                            <select name="level" class="form-control"  >
                                                                 <option value="100">100</option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <label for="default" class="control-label">Fee Pursose:</label>
                                                        <div class="">
                                                           <select name="purpose" class="form-control"><
                                                       <
                                                        <?php
                                                                $ress = mysqli_query($con, "SELECT * FROM degreetype WHERE degree='$ap'");
                                                                while ($rows = mysqli_fetch_array($ress)) {
                        
                                                                    ?>
                                                        <option value="<?php echo  $rows["degree_tution"]; ?>"><?php echo  $rows["degree_tution"]; ?></option>
                                                        <?php

                                                         }
                                                        ?>
                                                    </select>
                                                          
                                                        </div>
                                                            
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <label for="default" class="control-label">Pay Type:</label>
                                                        <div class="">
                                                           <select name="paytype" id="show" onchange="change(this)" class="form-control"><option selected="selected" disabled="disabled">Please Select Fee Type</option>
                                                        <?php
                                                            $res = mysqli_query($con, "SELECT DISTINCT ptype FROM paymenttype ORDER BY ptype");
                                                            while ($row = mysqli_fetch_array($res)) {

                                                        ?>
                                                        <option value="<?php echo  $row["ptype"]; ?>"><?php echo  $row["ptype"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                    </select>
                                                          
                                                        </div>
                                                            
                                                    </div>
                                                     <div id="retCustDetails" class="custDetails" style="display:none">
                                                    <div class="form-group">
                                                        
                                                        <label for="default" class="control-label">Semester:</label>
                                                        <div class="">
                                                           <select name="semest" class="form-control"><option selected="selected" disabled="disabled">Please Select Semester</option>
                                                        <?php
                                                            $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest");
                                                            while ($row = mysqli_fetch_array($res)) {

                                                        ?>
                                                        <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                    </select>
                                                          
                                                        </div>
                                                            
                                                    </div>
                                                       
                                                     </div>
                                                    <div class="form-group has-success">

                                                        <div class="">
                                                            <button type="submit" name="makescpay" class="btn btn-success btn-label-right btn-label btn-label-right">Click-to-Pay <i class="fa fa-gear"></i></button>
                                                    </div>


                                                    
                                               </form>

                                              
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-8 col-md-offset-2 -->
                                </div>
                                <!-- /.row -->
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
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-credit-card"></i>Full Payment Page
                            <button class="close" style="float:right;" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button></h5>
                        </div>
                        <div class="modal-body">
                           <form method="POST">  
                            <table border="1">
                                <tr>
                                    <td>Full Name: <strong style="text-transform: capitalize;"><?php echo htmlentities($fullname) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Academic Session: <?php echo htmlentities($section) ?></td>
                                </tr>
                                <tr>
                                    <td>Purpose: <?php echo htmlentities($prog) ?></td>
                                </tr>
                                <tr>
                                    <td>Faculty: <?php echo htmlentities($facu) ?></td>
                                </tr>
                                <tr>
                                    <td>Programme: <?php echo htmlentities($pro) ?></td>
                                </tr>
                                <tr>
                                    <td>Amount Paying: &#8358; <?php echo htmlentities(number_format($nam,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Transaction Charge: &#8358; <?php echo htmlentities(number_format($per,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Total Amount: &#8358; <?php echo htmlentities(number_format($amount,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Payment Type: <?php echo htmlentities($payt) ?></td>
                                </tr>
                                <tr>
                                    <td>Level: <?php echo htmlentities($level) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">NO</button>
                                                      
                            <button type="button" onclick="payWithMonnify()"  class="btn btn-success">Click to Make Payment</button>
                           </form>
                       </div>
                    </div>
                </div>
            </div>  
            <div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-credit-card"></i>Part Payment Page
                            <button class="close" style="float:right;" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button></h5>
                        </div>
                        <div class="modal-body">
                         <form method="POST">  
                            <table border="1">
                                <tr>
                                    <td>Full Name: <strong style="text-transform: capitalize;"><?php echo htmlentities($fullname) ?></strong></td>
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
                                    <td>Amount Paying: &#8358; <?php echo htmlentities(number_format($nam,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Transaction Charge: &#8358; <?php echo htmlentities(number_format($per,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Total Amount: &#8358; <?php echo htmlentities(number_format($amount,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Semester: <?php echo htmlentities($semest) ?></td>
                                </tr>       
                                
                                <tr>
                                    <td>Payment Type: <?php echo htmlentities($payt) ?></td>
                                </tr>
                                <tr>
                                    <td>Level: <?php echo htmlentities($level) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">NO</button>
                            
                            <button type="button" onclick="payWithMonnifys()"  class="btn btn-success">Click to Make Payment</button>
                           </form>
                       </div>
                    </div>
                </div>
            </div>  
            <!-- For Course Enrollement -->       
                    

                            </div>
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->

                    </div>
    <script type="text/javascript" language="javascript">
                        
                       
    function payWithMonnify() {
      
      var fullname = '<?php echo htmlentities($fullname); ?>';
      var amount = <?php echo htmlentities($amount)?>;
      var amt = <?php echo htmlentities($nam)?>;
      var copy = '<?php echo htmlentities($section) ?>';
      var leveld = '<?php echo htmlentities($level) ?>';
      var mess = '<?php echo htmlentities($prog)?>';
      var payt = '<?php echo htmlentities($payt)?>';
      var email = '<?php echo htmlentities($email) ?>';
      var phone = '<?php echo htmlentities($phone) ?>';
      var studentid = "<?php echo htmlentities($_SESSION['staffid']) ?>"; 
      var prg = '<?php echo $pro; ?>';
      var fac = '<?php echo $facu; ?>';
      var pbk='<?php echo htmlentities($key) ?>';
      var spl='<?php echo htmlentities($spk) ?>'; 
      var refs='<?php echo $payref; ?>';
      var fullName = fullname;    
MonnifySDK.initialize({
          amount: amount,
          currency: "NGN",
          reference: refs,
          phoneNumber: phone,
          customerName: fullname,
          customerEmail: email,
          apiKey: pbk,
          contractCode: spl,
		paymentDescription: "EDOCOHEST YEAR TUITION FEE", 
		"incomeSplitConfig": [
        {
            "subAccountCode": "MFY_SUB_600745458479",  
            "splitAmount": amt,
            "feeBearer": false
        }, 
        {
            "subAccountCode": "MFY_SUB_891671712178",
            "splitAmount": 2000,
            "feeBearer": true,
            "feePercentage": 100
        }
],
          onLoadStart: () => {
            console.log("loading has started")
          },
          onLoadComplete: () => {
            console.log("SDK is UP")
          },
        
           onComplete: function (response) {
                          
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
            
            window.location.href = "success_page?feedback=success" + "&ref=" + response.transactionReference;
              
            }
           //alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");
          };

          xmlhttp.open("POST", "include/payps", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("fullname="+fullname + "&email=" + email + "&phone=" +phone+ "&copy=" + copy +"&method=" + response.paymentMethod +"&status=" + response.paymentStatus+ "&ref=" + response.transactionReference +"&amount=" + response.amountPaid+"&amt=" + amt + "&mess=" + mess+ "&studentid=" + studentid+"&leveld="+leveld+"&payty="+payt);
      alert('Payment completed. Please wait while your transaction is being finalized!'); // Display the alert
},
        onClose: function(data) {
          console.log("Payment closed", data);
        }
           
      });

      handler.openIframe();
    }
</script>
<script type="text/javascript">
    function payWithMonnifys() {
      var fullname = '<?php echo htmlentities($fullname); ?>';
      var amount = <?php echo htmlentities($amount)?>;
      var amt = <?php echo htmlentities($nam)?>;
      var copy = '<?php echo htmlentities($section) ?>';
      var leveld = '<?php echo htmlentities($level) ?>';
      var mess = '<?php echo htmlentities($prog)?>';
      var payt = '<?php echo htmlentities($payt)?>';
      var email = '<?php echo htmlentities($email)?>';
      var phone = '<?php echo htmlentities($phone)?>';
      var semst = '<?php echo htmlentities($semest) ?>';
      var studentid = "<?php echo htmlentities($_SESSION['staffid']) ?>"; 
      var prg = '<?php echo $pro; ?>';
      var fac = '<?php echo $facu; ?>';
      var pbk='<?php echo htmlentities($key) ?>';
      var spl='<?php echo htmlentities($spk) ?>'; 
      var refs='<?php echo $payref; ?>';
      var fullName = fullname; 
      MonnifySDK.initialize({
          amount: amount,
          currency: "NGN",
          reference: refs,
          phoneNumber: phone,
          customerName: fullname,
          customerEmail: email,
          apiKey: pbk,
          contractCode: spl,
		paymentDescription: "EDOCOHEST PAER-PAYMENT YEAR ONE TUITION FEE",
			"incomeSplitConfig": [
        {
            "subAccountCode": "MFY_SUB_600745458479",  
            "splitAmount": amt,
            "feeBearer": false
        }, 
        {
            "subAccountCode": "MFY_SUB_891671712178",
            "splitAmount": 2000,
            "feeBearer": true,
            "feePercentage": 100
        }
],
          onLoadStart: () => {
            console.log("loading has started")
          },
          onLoadComplete: () => {
            console.log("SDK is UP")
          },
        
           onComplete: function (response) {
                          
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
            
            window.location.href = "success_page?feedback=success" + "&ref=" + response.transactionReference;
              
            }
           //alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");
          };


          xmlhttp.open("POST", "include/pays", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("fullname="+fullname + "&email=" + email + "&phone=" +phone+ "&copy=" + copy +"&method=" + response.paymentMethod +"&status=" + response.paymentStatus + "&ref=" + response.transactionReference +"&amount=" + response.amountPaid+"&amt=" + amt + "&mess=" + mess+ "&studentid=" + studentid+"&leveld="+leveld+"&payty="+payt+"&semst="+semst);
        alert('Payment completed. Please wait while your transaction is being finalized!'); // Display the alert
},
        onClose: function(data) {
          console.log("Payment closed", data);
        }
           
      });

      handler.openIframe();
    }
    
</script>
</body>
<?php 
include"include/buton.php";
if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
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
?>
</html>