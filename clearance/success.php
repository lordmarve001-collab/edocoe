<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

if (empty($_GET['ref']) || empty($_GET['feedback'])) {
      header("location: admission?");
  }

//header("Refresh:10;index");
$refe = $_GET['ref'];                        
//$reft=$accp['paymentreference'];

$data=mysqli_fetch_assoc(mysqli_query($con, "SELECT studentid,amount, paymentreference,purpose FROM studentpayment WHERE paymentreference = '$refe'"));
if ($data !=null) {
    $_POST["appliD"]=$data["studentid"];
$_POST["amon"]=$data["amount"];
$_POST["refe"]=$data["paymentreference"];
$_POST["pp"]=$data["purpose"];
}else{
    header("location: admission");
}
$refe=$_POST["refe"];
$_SESSION['refe']=$refe;
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE (applid='".$_POST["appliD"]."')"));
$_POST["fname"]=$dat["firstname"];
$_POST["sname"]=$dat["lastname"];
$_POST["programm"]=$dat["programme"];
$_POST["phone"]=$dat["phone"];
$_POST["Email"]=$dat["email"];
$ad=$dat["applid"];

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
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/coms.css">
        <link rel="stylesheet" href="css/receipt.css">
    
    </head>
<body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
   <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
<?php include('includes/leftbar.php');?> 
    <div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="<?php echo htmlentities($logo) ;?>" style="width:150px; height: 150px; max-width:150px;">
        </div>
        <div style="max-width: 300px;margin: auto;">
            <h5><br> <span style="margin-left: 35px;font-size: 16.5px;font-weight: bold;">Official Receipt of Payment.</span></h5>
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
                                Created: <?php
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
                    Full Name: <?php echo htmlentities($_POST["fname"]);?>  <?php echo htmlentities( $_POST["sname"]); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Email: <?php echo htmlentities($_POST["Email"]);?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Programme: <?php echo htmlentities($_POST["programm"]); ?>
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
                    Amount Paid: <strong>&#8358;<?php echo htmlentities(number_format($_POST["amon"],2)) ?></strong>
                </td>
                
                <td>
                    
                </td>
            </tr>    
            <tr class="item">
                <td>
                    Payment Purpose: <?php echo htmlentities(strtoupper($_POST["pp"])) ?>
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
            <tr class="heading">
                <td>
                    Payment Information
                </td>
                
                <td>
                    
                </td>
            </tr>
                   
        </table>
        <div class="info">
             Your Acceptance Fee Payment is successfull. kindly upload your Document to complete clearance process. 
        </div>
        <div class="butn">
            <button type="button" onclick="window.open('printrecept','_blank')" class="btn btn-info"> <i class="fa fa-print"></i> Print</button>
            <button type="button" onclick="window.open('admission_letter','_blank')" class="btn btn-danger"> <i class="fa fa-print"></i> Print Admission Letter</button>
            <a href="admission"><button class="btn btn-success">Continue to Clearance</button></a>
        </div>
        <br>
        <br>
        <!-- <input id="a" value="change me" /> --> 
        <div class="watermark" id="a" data-watermark="uat"></div>
       <div class="divFooter">Copyright &copy; <?php echo $orgname ?> <?php
                                    date_default_timezone_set("africa/lagos");
                                 echo date("Y-m-d h:i:sa")?></div>
    </div>
</body>
<?php include('include/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
</html>