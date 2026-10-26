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

$error = '';
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
<h2 class="title">Payment Summary By Level (Session Based)</h2>
</div>

<div class="container-fluid">
<div class="panel">
<div class="panel-body">

<!-- ================= FORM ================= -->
<form method="POST" class="form-horizontal">
<div class="row">
<div class="col-md-6">
<label>Academic Session</label>
<select name="section" class="form-control" required>
<option disabled selected>Select Academic Session</option>
<?php
$res = mysqli_query($con, "SELECT section FROM sections ORDER BY section DESC");
while ($row = mysqli_fetch_assoc($res)) {
    echo "<option value='{$row['section']}'>{$row['section']}</option>";
}
?>
</select>
</div>

<div class="col-md-6">
<br>
<button type="submit" name="query" class="btn btn-primary btn-block">
    View Report
</button>
</div>
</div>
</form>

<?php
if (isset($_POST['query'])) {

    $section = mysqli_real_escape_string($con, $_POST['section']);

    $_SESSION['section'] = $section;
    $_SESSION['export_mode'] = 'level_section_summary';

    $sql = "
    SELECT 
        level,
        purpose,
        COUNT(DISTINCT studentid) AS total_students,
        SUM(CAST(amount AS UNSIGNED)) AS total_amount
    FROM studentpayment
    WHERE section = '$section'
      AND LOWER(status) = 'success'
    GROUP BY level, purpose
    ORDER BY level, purpose
    ";

    $res = mysqli_query($con, $sql);

    if (mysqli_num_rows($res) > 0) {

        $data = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $data[$row['level']][] = $row;
        }
?>

<hr>
<div align="center">
<img src="<?= $logo ?>" width="90"><br>
<strong><?= htmlentities($orgname) ?></strong><br>
PAYMENT SUMMARY BY LEVEL<br>
Session: <?= htmlentities($section) ?>
</div>

<?php
$grandTotal = 0;

foreach ([100,200,300] as $lvl) {
    if (!isset($data[$lvl])) continue;

    $levelTotal = 0;
?>
<div class="level-title">LEVEL <?= $lvl ?></div>

<table class="table table-bordered">
<tr>
    <th>#</th>
    <th>Fee Purpose</th>
    <th>Total Students</th>
    <th>Total Amount (₦)</th>
</tr>

<?php
$cnt = 1;
foreach ($data[$lvl] as $row) {
    $levelTotal += $row['total_amount'];
    $grandTotal += $row['total_amount'];
?>
<tr>
    <td><?= $cnt++ ?></td>
    <td><?= htmlentities($row['purpose']) ?></td>
    <td><?= $row['total_students'] ?></td>
    <td>₦ <?= number_format($row['total_amount'],2) ?></td>
</tr>
<?php } ?>

<tr style="font-weight:bold;">
    <td colspan="3" align="right">LEVEL TOTAL</td>
    <td>₦ <?= number_format($levelTotal,2) ?></td>
</tr>
</table>

<?php } ?>

<hr>
<table class="table table-bordered">
<tr style="font-weight:bold;">
    <td align="right">GRAND TOTAL</td>
    <td width="250">₦ <?= number_format($grandTotal,2) ?></td>
</tr>
</table>

<button class="btn btn-primary" onclick="window.open('printpdf','_blank')">Print PDF</button>
<button class="btn btn-success" onclick="window.open('section_summary_csv','_blank')">Export CSV</button>

<?php
    } else {
        echo "<p style='color:red;'>No records found</p>";
    }
}
?>

</div>
</div>
</div>

</div>
</div>
</div>
     <?php   include 'includes/buton.php';?>
</body>
</html>
<?php }?>