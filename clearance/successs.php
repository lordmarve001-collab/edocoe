<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

if (empty($_GET['ref']) || empty($_GET['feedback'])) {
      header("location: index?");
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
    header("location: index");
}
$refe=$_POST["refe"];
$_SESSION['refe']=$refe;
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE studentid='".$_POST["appliD"]."'"));
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
             Your Payment was successfull. 
        </div>
        <br>
        
        <div style="margin:auto;width: 200px;" class="button"><input type="button" value="Print-Detail" onclick="window.open('printrecept','_blank')" style="float: left;font-size: 20px;display: non;"></div> 
       <div class="divFooter" style="text-align:center;">Copyright &copy; <?php echo $orgname ?> <?php
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
<?php include 'includes/buton.php';?>
        </div>
        </body>