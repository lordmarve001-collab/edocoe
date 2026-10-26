<?php 
session_start();
$connection = TRUE;
include "../include/dbconfig.php";
include "dataco.php";
if (!isset($_SESSION['staffid']))
    header("Location: logout");
 
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$error='';
$succ='';
$key='pk_live_eb73a010958c2f95c86c47c5b5e8351a043e7eb2';
$spk='SPL_zvNwJhkL8R';
/*Start Here*/
$length=5;
$string='';
$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $size = strlen($chars);
   for ($i = 0; $i < $length; $i++) {
       $string .= $chars[rand(0, $size - 1)];
       $date=date("Ymd");
       $ned='EDOCOE'.$date.''.$string;
  } 
 /*End Here*/
    if (isset($_POST['makescpay'])) {
        if (empty($_POST['section'])) {
            $error="Please Select Session Paying For";
        $showModalEr='true';
        }elseif (empty($_POST['level'])){
            $error="Please Select Level Paying For";
        $showModalEr='true';
        }elseif(empty($_POST['programme'])){
        $error="Please Select Fee Type"; 
        $showModalEr='true';
        }else{
        $section=mysqli_real_escape_string($con,$_POST['section']);
        $level=mysqli_real_escape_string($con,$_POST['level']);
        $prog=mysqli_real_escape_string($con,$_POST['programme']);
        /*Payment Clearance Check*/
        /*Querying the Payment Payment Reference*/
        $paich= mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ned' "));
        if ($paich=='') {
           $payref=mysqli_real_escape_string($con,$ned);
        }else{
            $neds=$ned+1;
            $payref=mysqli_real_escape_string($con,$neds);
        }
        
        /*Payment Clearance Check*/
            
        $feer=mysqli_fetch_assoc(mysqli_query($con,"SELECT amount FROM feesrecord WHERE purpose='$prog'"));
        if ($feer!='') {
        
        $amount=$feer['amount']+$namt;
        $amt=$feer['amount'];
        $que= mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND level='$level' AND purpose='$prog' AND section='$section'");
        if(mysqli_num_rows($que)>0){
        $error="You Have Make Payment For This ".$prog;
        $showModalEr='true';
        }
        else{
            $tday=date("Y-m-d");
            $dated = date('Y-M-d'); 
            $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
            $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,level,programme,purpose,amount,modes,paymentreference,paytime,remtime,status) VALUES ('$matr', '$section','$level','$pro','$prog','$amt','failed','$payref','$tday','$newd','Failed') ");
            $showModal='true';
        }
        }else{
           $error="Amount Have Not Been Set for this ".$prog."  purpose";
           $showModalEr='true'; 
        }
        
    }
    }

    /*Trying Line If It Will Work End Here*/
$labfc=array();
$chp="SELECT * FROM labfefac";
            $stmtp = mysqli_query($con,$chp);
                $resulterp = mysqli_fetch_all($stmtp,MYSQLI_ASSOC);
           foreach($resulterp as $rww)
            {

                    $labfc[]=$rww['facid'];
            }
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
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script src="js/jquery/jquery-2.2.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="https://js.paystack.co/v1/inline.js"></script>
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
<?php include('includes/leftbar.php');?>   
<div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title"><?php echo "LABORATORY FEE PAYMENT SETUP" ?></h3>
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

                                               <?php 
                                                if (in_array($data['faculty'],$labfc))
                                                    {
                                                ?>
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
                                                            <option selected="selected" disabled="disabled">SELECT LEVEL</option>
                                                                 
                                            <option value="100">100</option>
                                       
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <label for="default" class="control-label">PAYMENT PURPOSE:</label>
                                                        <div class="">
                                                           <select name="programme"   class="form-control"> <option value="Laboratory Fee">Laboratory Fee</option>          
                                                        
                                                        </select>
                                                          
                                                        </div>
                                                            
                                                    </div>
                                                       
                                                     
                                                    <div class="form-group has-success">

                                                        <div class="">
                                                            <button type="submit" name="makescpay" class="btn btn-success btn-label-right btn-label btn-label-right">Click-to-Pay <i class="fa fa-gear"></i> </button>
                                                    </div>


                                                    
                                               </form>

                                              <?php }else{?>
                                            <p style="color:red;"> Sorry Dear: <b style="color:blue;font-weight: bolder;"><?php echo htmlentities($fullname) ?></b>You Can't Make Payment on this Page</p>
                                           <?php }?>
                                              
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
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-credit-card"></i>Payment Page
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
                                    <td>Academic Session: <?php echo htmlentities($section) ?></td>
                                </tr>
                                <tr>
                                    <td>Faculty: <?php echo htmlentities($facu) ?></td>
                                </tr>
                                <tr>
                                    <td>Programme: <?php echo htmlentities($pro) ?></td>
                                </tr>
                                <tr>
                                    <td>Level: <?php echo htmlentities($level) ?></td>
                                </tr>
                                <tr>
                                    <td>Purpose: <?php echo htmlentities($prog) ?></td>
                                </tr>
                                <tr>
                                    <td>Amount Paying: &#8358;<?php echo htmlentities(number_format($amt,2)) ?></td>
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
           
                            </div>
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->

                    </div>
                    <script type="text/javascript">
                        
                       
    function payWithPaystack() {
      
      var fullname = '<?php echo htmlentities($fullname); ?>';
      
      var amount = <?php echo htmlentities($amount)?>*100;
      var copy = '<?php echo htmlentities($section) ?>';
      var leveld = '<?php echo htmlentities($level) ?>';
      var mess = '<?php echo htmlentities($prog)?>';
      var email = '<?php echo $email ?>';
      var phone = '<?php echo htmlentities($phone) ?>';
      var prg = '<?php echo $pro; ?>';
      var fac = '<?php echo $facu; ?>';
      var studentid = "<?php echo htmlentities($matr) ?>";  
      var pbk='<?php echo htmlentities($key) ?>';
     var spl='<?php echo htmlentities($spk) ?>'; 
      var refs='<?php echo $payref; ?>';
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
              variable_name: "Academic_Session",
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
              display_name: "Level",
              variable_name: "level",
              value: leveld
            }
          ]
        },

        callback: function (response) {          
          var email = '<?php echo $email; ?>';
          var phone = '<?php echo $phone; ?>';
          var studentid = "<?php echo $matr; ?>";

          var xmlhttp = "";

          if (window.XMLHttpRequest) {
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

          xmlhttp.open("POST", "include/payc", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("fullname="+fullname + "&email=" + email + "&phone=" +phone+ "&copy=" + copy + "&ref=" + response.reference + "&mess=" + mess+ "&studentid=" + studentid+"&leveld="+leveld+"&prg="+prg);
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