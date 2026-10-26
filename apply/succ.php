<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

if (empty($_GET['ref']) || empty($_GET['feedback'])) {
      header("location: index?");
  }

//header("Refresh:10;index");
$refe = $_GET['ref'];                        

$data=mysqli_fetch_assoc(mysqli_query($con, "SELECT studentid,amount, paymentreference,purpose FROM studentpayment WHERE paymentreference = '$refe'"));
if ($data !=null) {
    $_POST["appliD"]=$data["studentid"];
$_POST["amon"]=$data["amount"];
$_POST["refe"]=$data["paymentreference"];
$_POST["pp"]=$data["purpose"];
}else{ 
    header("location: index");
}
$reft=$_POST["refe"];
$_SESSION['payre']=$reft;
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_POST["appliD"]."'"));
$_POST["fname"]=$dat["firstname"];
$_POST["sname"]=$dat["lastname"];
$_POST["othername"]=$dat["othername"];
$_POST["programm"]=$dat["programme"];
$_POST["phone"]=$dat["phoneno"];
$_POST["Email"]=$dat["email"];
$_POST["Pass"]=$dat["password"];

//$chanel=$tranx->data->channel;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlentities($orgname) ;?></title>
    <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="assets/css/receipt.css"/>
    <link href="assets/css/bootstrap.css" rel="stylesheet"/>
    <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link href="assets/css/style.css" rel="stylesheet" />
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <style type="text/css" media="print">
        -webkit-print-color-adjust:exact !important;
        color-adjust:exact !important;
        @media print{
    .btn{
        display:none;
    }
    footer{
        display:none;
    }
    }
    </style>

</head>
<body>
    <?php include('include/menubar.php');?>
    <div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="<?php echo htmlentities($logo) ;?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="max-width: 300px;margin: auto;">
            <h5><span style="font-size: 20px;"><?php echo htmlentities($orgname) ;?></span><br> <span style="margin-left: 35px;font-size: 16.5px;font-weight: bold;">Official Receipt of Payment.</span></h5>
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
                    Application ID: <?php echo htmlentities($_POST["appliD"]);?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Full Name: <?php echo htmlentities($_POST["fname"]);?>  <?php echo htmlentities( $_POST["snam"]); ?>
                    <?php echo htmlentities( $_POST["othername"]); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Phone: <?php echo htmlentities($_POST["phone"]) ; ?>
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
                    Amount Paid: &#8358; <?php echo htmlentities(number_format($_POST["amon"],2)) ?>
                </td>
                
                <td>
                    
                </td>
            </tr>    
            <tr class="item">
                <td>
                    Payment Purpose: <?php echo htmlentities($_POST["pp"]) ?>
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
                    Login Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Application ID: <?php echo htmlentities($_POST["appliD"]) ?>
                </td>
                
                <td>
                    
                </td>
            </tr>        
            <tr class="item">
                <td>
                  Password: <?php echo htmlentities($_POST["phone"]) ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            
           
        </table>
        <div class="info">
             Your Account Creation was successfully. kindly login your email account and follow the instructions sent to your email to complete your application.
        </div>
       <div class="butn">
            <button type="button" onclick="window.open('printreceipt','_blank')" class="btn btn-info"> <i class="fa fa-print"></i> Print</button>
            <a href="applogin" target="_blank"><button class="btn btn-success">Continue to Login Page</button></a>
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

