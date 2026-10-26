<?php
session_start();

$connection = TRUE;
include('../include/dbconfig.php');
  
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
include 'dataco.php';
if (empty($_GET['ref'])) {
      header("location: student");
  }
  $refe = $_GET['ref'];
$data=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE paymentreference = '$refe'"));
if ($data !='') {
    $_POST["appliD"]=$data["studentid"];
$_POST["amon"]=$data["amount"];
$_POST["refe"]=$data["paymentreference"];
$_POST["tp"]=$data["purpose"];
$_POST["add"]=$data["section"];
$pat=$data["paytype"];
}else{
    header("location: student");
}
$_SESSION['refe']=$refe;

        ?>
        <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/receiptp.css" media="print">
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <style type="text/css" media="print">
        -webkit-print-color-adjust:exact !important;
        color-adjust:exact !important;
    </style>
    </head>
<body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
   <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
<?php include('includes/leftbar.php');?> 

    
    <div class="main-page">
        <div class="container-fluid">
            <div class="row page-title-div">
                <div>
                    <h3 class="title"><?php echo "TRANSACTION PAYMENT RECEIPT"; ?></h3>

                </div>

                <!-- /.col-md-6 text-right -->
            </div>

            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

        <section class="section">
            <div class="container-fluid">



                <div class="row">
                    <div class="col-md-12">

                        <div class="panel">


                            <div class="panel-body p-20">
                              <!-- Begining of invoice -->
                              <div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="<?php echo htmlentities($logo)?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="max-width: 300px;margin: auto;">
            <h5><span style="font-size: 20px;"><?php echo htmlentities($orgname)?></span> 
            </h5>
            <h5><span style="margin-left:25px;font-size: 16.5px;font-weight: bold;">Official Receipt of Payment.</span></h5>
        </div>
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                              
                            </td>
                            
                            <td>
                                <!-- Invoice #: 123<br> -->
                                Printed: <?php
                                    date_default_timezone_set("africa/lagos");
                                 echo date("D,d M Y ")."By ". date("h:i:sa")?><br>
                                <!-- Due: February 1, 2015 -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>
                    Personal Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Student ID: <?php echo htmlentities($userid);?>
                </td>
                
                <td rowspan="4">
                    <img src="<?php echo htmlentities($passport); ?>" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;">
                </td>
            </tr>
            <tr class="item">
                <td>
                    Full Name: <strong style='text-transform: capitalize;'><?php echo htmlentities($fullname); ?></strong>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Phone: <?php echo htmlentities($phone) ; ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Email: <?php echo htmlentities($email);?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Programme: <?php echo htmlentities($pro); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           
            <tr class="heading">
                <td>
                    Payment Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Amount Paid: &#8358; <?php echo htmlentities(number_format($_POST["amon"],2)) ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                  Reference: <?php echo htmlentities($_POST["refe"]) ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                  Purpose: <?php echo htmlentities($_POST["tp"]);?>
                </td>
                
                <td>
                    
                </td>
            </tr> 
            
            <tr class="item">
                <td>
                    Academic Session: <?php echo htmlentities($_POST["add"]) ; ?>
                </td>
                <td>
                    
                </td>
            </tr>
            
            
           
        </table>
        <div class="info">
             Your Payment was successfull. 
        </div>
        <br>
        <!-- <input id="a" value="change me" /> --> 
        <div class="watermark" id="a" data-watermark="uat"><!--  UNIVERSITY OF AFRICA, TORU-ORUA Official receip for Payment  --></div>
        <div style="margin:auto;width: 200px;" class="button"><input type="button" value="Print-Detail" onclick="window.open('printrecept','_blank')" style="float: left;font-size: 20px;display: non;"></div> 
       <div class="divFooter">Copyright &copy;<?php echo htmlentities($orgname) ?> <?php
                                    date_default_timezone_set("africa/lagos");
                                 echo date("Y-m-d h:i:sa")?></div>
    </div>
                                <!--End of invoice-->
                                <!-- /.col-md-12 -->
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->


                </div>
                <!-- /.col-md-12 -->
            </div>
    </div>
    <!-- /.panel -->
    </div>
                                    <!-- /.col-md-6 -->

    </div>
                                <!-- /.row -->

    </div>
                            <!-- /.container-fluid -->
    </section>
                        <!-- /.section -->

    </div>
                    <!-- /.main-page -->
                    </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>        </div>
        </body>
        
<?php include 'includes/buton.php'; }?>
