<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
$error='';

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
        <link rel="stylesheet" href="css/print.css" media="print">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/animate.min.css" media="screen">
        <style>
            @media Print{
                .form-horizontal{
                 display:none;  
                }
                td{
                    text-transform:capitalize;
                    padding:15px;
                }
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
                  <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="accountant")
                  {
                     include('includes/accbar.php');

                  }elseif ($paych['position']=="bursar") {
                        include('includes/bursar.php');
                  }elseif ($paych['position']=="superadmin") {
                        include('includes/supbar.php');
                  }
                   else{
                  include('includes/leftbar.php');
                        }
                    ?> 
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
<h3>Payment Summary By Date Range</h3>
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
                                            <section class="section">
<form method="POST">
    <div class="form-group">
    <input type="date" class="form-control clid" name="from" required>
    </div>
        <div class="form-group">
    <input type="date" class="form-control clid" name="to" required>
   </div>
   <button name="query" class="btn btn-primary">View Report</button>
</form>
            </section>

<?php
if (isset($_POST['query'])) {

    $from = mysqli_real_escape_string($con, $_POST['from']);
    $to   = mysqli_real_escape_string($con, $_POST['to']);

    $_SESSION['from'] = $from;
    $_SESSION['to']   = $to;
    $_SESSION['export_mode'] = 'date_range_summary';

    /* ================= SUMMARY BY FEES ================= */
    $fees = mysqli_query($con,"
        SELECT purpose,
               COUNT(*) AS payments,
               SUM(amount) AS total
        FROM studentpayment
        WHERE paytime BETWEEN '$from' AND '$to'
          AND LOWER(status)='success'
        GROUP BY purpose
    ");

    /* ================= SUMMARY BY PROGRAMME ================= */
    $programmes = mysqli_query($con,"
        SELECT programme,
               COUNT(*) AS payments,
               SUM(amount) AS total
        FROM studentpayment
        WHERE paytime BETWEEN '$from' AND '$to'
          AND LOWER(status)='success'
        GROUP BY programme
    ");

    /* ================= SUMMARY BY LEVEL ================= */
    $levels = mysqli_query($con,"
        SELECT level,
               COUNT(*) AS payments,
               SUM(amount) AS total
        FROM studentpayment
        WHERE paytime BETWEEN '$from' AND '$to'
          AND LOWER(status)='success'
        GROUP BY level
    ");
?>

<hr>
<div align="center">
<img src="<?= $logo ?>" width="90"><br>
<strong><?= htmlentities($orgname) ?></strong><br>
PAYMENT SUMMARY BY DATE RANGE<br>
Date Between : <?= htmlentities($from) ?> to <?= htmlentities($to) ?>
</div>
<h4>Summary By Fees</h4>
<table class="table table-bordered">
<tr><th>#</th><th>Fee</th><th>Payments</th><th>Total (₦)</th></tr>
<?php $i=1; $gt=0; while($r=mysqli_fetch_assoc($fees)){ $gt+=$r['total']; ?>
<tr>
<td><?= $i++ ?></td>
<td><?= $r['purpose'] ?></td>
<td><?= $r['payments'] ?></td>
<td><?= number_format($r['total'],2) ?></td>
</tr>
<?php } ?>
<tr><th colspan="3">GRAND TOTAL</th><th><?= number_format($gt,2) ?></th></tr>
</table>

<h4>Summary By Programme</h4>
<table class="table table-bordered">
<tr><th>#</th><th>Programme</th><th>Payments</th><th>Total (₦)</th></tr>
<?php $i=1; while($r=mysqli_fetch_assoc($programmes)){ ?>
<tr>
<td><?= $i++ ?></td>
<td><?= $r['programme'] ?></td>
<td><?= $r['payments'] ?></td>
<td><?= number_format($r['total'],2) ?></td>
</tr>
<?php } ?>
</table>

<h4>Summary By Level</h4>
<table class="table table-bordered">
<tr><th>#</th><th>Level</th><th>Payments</th><th>Total (₦)</th></tr>
<?php $i=1; while($r=mysqli_fetch_assoc($levels)){ ?>
<tr>
<td><?= $i++ ?></td>
<td><?= $r['level'] ?></td>
<td><?= $r['payments'] ?></td>
<td><?= number_format($r['total'],2) ?></td>
</tr>
<?php } ?>
</table>

<a href="printpdf" target="_blank" class="btn btn-primary">Print PDF</a>
<a href="section_summary_csv" target="_blank" class="btn btn-success">Export CSV</a>

<?php } ?>
 </div>
                </div>
            </div>
            <!-- /.col-md-12 -->
        </div>
</div>
</div>
<?php include 'includes/buton.php';?>
</body>
</html>
<?php }?>
 