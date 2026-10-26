<?php
session_start();
$connection = TRUE;
include('../include/dbconfig.php');
include 'dataco.php';
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location:logout"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
}
$key='pk_live_756a3d7216e88d067546c3bd7bde2af24b95e198';
$spk='SPL_szCEw40f8L';
$error='';
/*Start Here*/
$length=5;
$string='';
$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $size = strlen($chars);
   for ($i = 0; $i < $length; $i++) {
       $string .= $chars[rand(0, $size - 1)];
       $date=date("Ymd");
       $ned='UAT'.$date.''.$string;
  } 
if (isset($_POST["submitper"])) {
    //$amts=mysqli_real_escape_string($con,$_POST["amount"]);
    $prog='Personal Transcript';
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
      $amount=$amtl+$namt;
        $tday=date("Y-m-d");
        $dated = date('Y-M-d'); 
        $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
        $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,remtime,status) VALUES ('".$_SESSION['staffid']."', '$cads','$progr','$prog','$amtl','failed','$payref','$tday','$newd','Failed')");
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
    $amonts=mysqli_real_escape_string($con,$_POST["amonts"]);
    $addresst=mysqli_real_escape_string($con,$_POST["addresst"]);
    $orgemail=mysqli_real_escape_string($con,$_POST["orgemail"]);
    $address=mysqli_real_escape_string($con,$_POST["address"]);
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
        $amount=$amtll+$namt;
        $tday=date("Y-m-d");
        $dated = date('Y-M-d'); 
        $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
        $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,remtime,status,firstname,lastname,email) VALUES ('".$_SESSION['staffid']."', '$cads','$progr','$fac','$amtll','failed','$payref','$tday','$newd','Failed','$addresst','$address','$orgemail')");
        
      $showModalof='true';
     }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script src="js/jquery/jquery-2.2.4.min.js" type="text/javascript"></script>
         <script type="text/javascript" src="https://js.paystack.co/v1/inline.js"></script>
        <script type="text/javascript">
            function change(obj) {

    var selectBox = obj;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var retCustDetails = document.getElementById("retCustDetails");
    var tradeCustDetails = document.getElementById("tradeCustDetails");

    if(selected == '01'){
        retCustDetails.style.display = "block";
        tradeCustDetails.style.display = "none";
    }else if (selected == '02') {
         tradeCustDetails.style.display = "block";
       retCustDetails.style.display = "none";
    }
    else{
        retCustDetails.style.display = "none";
        tradeCustDetails.style.display = "none";
    }
}

        </script>
        <style type="text/css">
          #blah{
                width:170.256px;
                height:159px;
                border:2px solid black;
            }
      </style>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
  <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php include('includes/leftbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Transcript Request Form</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                        <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
<form class="form-horizontal" method="post" enctype="multipart/form-data">  

            <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Full Name:</label>
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="fullname" value="<?php  echo htmlentities($fullname) ?>" autocomplete="off" name="surname" style=text-transform:capitalize; required="required" readonly="readonly" maxlength="117">
                     </div>
            </div>
            <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Type:</label>
                    <div class="col-sm-10">
                        <select class="form-control clid" id="show" onchange="change(this)" name="copy">
                            <option selected="selected" disabled="disabled">Please Select Copy Type</option>
                            <option value="01">Personal Copy</option>
                            <option value="02">Official Copy</option>
                        </select>
                    </div>
            </div>
     </form>
    <div id="retCustDetails" class="custDetails" style="display:none"> 
    <form class="form-horizontal" method="POST">
    <?php
    $resz = mysqli_query($con, "SELECT * FROM feesrecord WHERE purpose='Personal Transcript'");
    $row = mysqli_fetch_array($resz);
    if ($row=="") {
       $amtl='Amount Not Set';
    }else{
      $amtl=$row['amount'];
     }
    ?>
    <div class="form-group">
    <label for="default" class="col-sm-2 control-label">Amount Paying:</label>
     <div class="col-sm-10">
     <input type="text" name="amount" value="<?php echo htmlspecialchars(number_format($amtl,2))?>" class="form-control" readonly>
        </div>
    </div>
    <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="submitper" class="btn btn-primary">Submit Request</button>
                </div>
            </div>
    </form>
    <!--  -->
    </div>
    <!-- For Foreign Country -->
    <div style="display:none" id="tradeCustDetails" class="custDetails" >
        <form class="form-horizontal" method="post"> 
    <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Official Type:</label>
                    <div class="col-sm-10">
                        <select class="form-control clid" id="copy" name="copy" onchange="selamount()">
                            <option selected="selected" disabled="disabled">Please Select Official Type</option>
                            <option value="12">Within Nigeria</option>
                            <option value="13">Outside Nigeria</option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Amount Paying:</label>
                     <div class="col-sm-10">
                        <input type="text" class="form-control" id="amout" autocomplete="off" name="amonts" style=text-transform:capitalize; required="required" readonly="readonly" maxlength="117">
                     </div>
            </div>
            <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Name of Institution/Organisation:</label>
                     <div class="col-sm-10">
                       <div  id="subject">
                                   <textarea name="addresst" id="messs" class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required" placeholder="Name of Institution Or Organisation Transcript should Sent to"></textarea>
                        </div> 
                     </div>
            </div>
            <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Email Address:</label>
                     <div class="col-sm-10">
                        <input type="email" class="form-control"  autocomplete="off" name="orgemail" required="required" maxlength="117">
                     </div>
            </div>
           
<!-- Seperate Line -->
            <div class="form-group">
                <label for="date" class="col-sm-2 control-label">Address to send Transcript:</label>
                    <div class="col-sm-10">
                        <div  id="subject">
                                   <textarea name="address" id="mess" class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required" placeholder="The Address the Transcript should be sent to or drop"></textarea>
                        </div>
                    </div>
            </div> 
<!-- Sepreaaater -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="submitoff" class="btn btn-primary sendButton">Submit Request</button>
                </div>
            </div>
    </div>
    </form>
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
                           
                            <table border="1">
                                <tr>
                                    <td>Full Name: <strong style="text-transform: capitalize;"><?php echo htmlentities($fullname) ?></strong></td>
                                </tr>
                                
                                <tr>
                                    <td>Faculty: <?php echo htmlentities($facu) ?></td>
                                </tr>
                                <tr>
                                    <td>Programme: <?php echo htmlentities($progr) ?></td>
                                </tr>
                                <tr>
                                    <td>Purpose: <?php echo htmlentities($prog) ?></td>
                                </tr>
                                <tr>
                                    <td>Amount Paying: &#8358;<?php echo htmlentities(number_format($amtl,2)) ?></td>
                                </tr>
                                <tr>
                                    <td>Transaction Charge: &#8358;<?php echo htmlentities(number_format($namt,2)) ?></td>
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
                           
                            <table border="1">
                                <tr>
                                    <td>Full Name: <strong style="text-transform: capitalize;"><?php echo htmlentities($fullname) ?></strong></td>
                                </tr>
                                
                                <tr>
                                    <td>Faculty: <?php echo htmlentities($facu) ?></td>
                                </tr>
                                <tr>
                                    <td>Programme: <?php echo htmlentities($progr) ?></td>
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
                                    <td>Transaction Charge: &#8358;<?php echo htmlentities(number_format($namt,2)) ?></td>
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
    <!-- Payment Option -->

                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-12 -->
                                </div>
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
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
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please Select Copy</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalx" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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
            <!-- /.content-wrapper -->
        </div>
       <?php include 'includes/buton.php';
        if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
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
        if(!empty($showModaler)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
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
       <script>
            function selamount()
                {
                    var xmlhttps = new XMLHttpRequest();
                    xmlhttps.open("GET", "fetcha?facult=" + document.getElementById("copy").value, false);
                    xmlhttps.send(null);
                     //alert(xmlhttps.responseText);
                   document.getElementById("amout").value = xmlhttps.responseText;

                }
       </script>
       <script>
function payWithPaystacks() {
      
      var fullname = '<?php echo htmlentities($fullname); ?>';
      var amount = <?php echo htmlentities($amount)?>*100;
      var mess = '<?php echo htmlentities($prog)?>';
      var email = '<?php echo $email ?>';
      var phone = '<?php echo htmlentities($phone) ?>';
      var prg = '<?php echo $progr; ?>';
      var fac = '<?php echo $facu; ?>';
      var refs='<?php echo $payref; ?>';
      var studentid = "<?php echo htmlentities($_SESSION['staffid']) ?>";  
      var pbk='<?php echo htmlentities($key) ?>';
      var spl='<?php echo htmlentities($spk) ?>'; 
      var fullName = fullname;    

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
              value: fullName,
            },
            {
              display_name: "Studentid",
              variable_name: "Student ID",
              value: studentid
            },
            {
              display_name: "Purpose",
              variable_name: "Purpose",
              value: mess
            },
            {
              display_name: "Faculty",
              variable_name: "Faculty",
              value: fac
            },
            {
              display_name: "Programme",
              variable_name: "Programme",
              value: prg
            }
          ]
        },

        callback: function (response) {          
          var email = '<?php echo $email; ?>';
          var phone = '<?php echo $phone; ?>';
          var studentid = "<?php echo $_SESSION['staffid']; ?>";

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
            
             window.location.href = "personaltranscript?feedback=success" + "&ref=" + response.reference;
              }
            alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");

          };

          xmlhttp.open("POST", "includes/pays", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("fullname="+fullname + "&email=" + email + "&phone=" +phone+ "&ref=" + response.reference + "&mess=" + mess+ "&studentid=" + studentid+"&prg="+prg);
        }
      });

      handler.openIframe();
    }
</script>
<script>
   
   function payWithPaystack() {
      
      var fullname = '<?php echo htmlentities($fullname); ?>';
      var amount = <?php echo htmlentities($amount)?>*100;
      var refs='<?php echo $payref; ?>';
      var copy = '<?php echo htmlentities($orgemail) ?>';
      var leveld = '<?php echo htmlentities($addresst) ?>';
      var mess = '<?php echo htmlentities($prog)?>';
      var semes = '<?php echo htmlentities($fac)?>';
      var adres = '<?php echo htmlentities($address)?>';
      var email = '<?php echo $email ?>';
      var phone = '<?php echo htmlentities($phone) ?>';
      var prg = '<?php echo $progr; ?>';
      var fac = '<?php echo $facu; ?>';
      var studentid = "<?php echo htmlentities($_SESSION['staffid']) ?>";  
      var pbk='<?php echo htmlentities($key) ?>';
      var spl='<?php echo htmlentities($spk) ?>'; 
      var fullName = fullname;    

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
              value: fullName,
            },
            {
              display_name: "Studentid",
              variable_name: "Studentid",
              value: studentid
            },
            {
              display_name: "Academic Session",
              variable_name: "Academic Session",
              value: copy
            },
            {
              display_name: "Purpose",
              variable_name: "Purpose",
              value: mess
            },
            {
              display_name: "Faculty",
              variable_name: "Faculty",
              value: fac
            },
            {
              display_name: "Programme",
              variable_name: "Programme",
              value: prg
            },
            {
              display_name: "Transcript Type",
              variable_name: " Transcript Type",
              value: mess
            }
            ,
            {
              display_name: "Official Type",
              variable_name: " Official Type",
              value: semes
            }
          ]
        },

        callback: function (response) {          
          var email = '<?php echo $email; ?>';
          var phone = '<?php echo $phone; ?>';
          var studentid = "<?php echo $_SESSION['staffid']; ?>";

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
            
             window.location.href = "transrequest";
              }
            //alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");

          };

          xmlhttp.open("POST", "includes/pay", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("fullname="+fullname + "&email=" + email + "&phone=" +phone+ "&copy=" + copy + "&ref=" + response.reference + "&mess=" + mess+ "&studentid=" + studentid+"&leveld="+leveld+"&semes="+semes+"&prg="+prg+"&adres="+adres);
        }
      });

      handler.openIframe();
    }

    
  </script>
  <script type="text/javascript">
                                    
$(document).ready(function(){
    $('.sendButton').attr('disabled',true);

        $('#mess').keyup(function(){
        if($(this).val().length !=0){
        $('.sendButton').attr('disabled', false);
        }
        else
        {
        $('.sendButton').attr('disabled', true);        
        }
    });      
      
});
</script>
    </body>
</html>
<?php 

} ?>
