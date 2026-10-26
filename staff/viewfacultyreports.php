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
        .prog-title{
            font-weight:bold;
            font-size:16px;
            margin-top:30px;
            text-transform:uppercase;
        }
    </style>
</head>

<body class="top-navbar-fixed">
<div class="main-wrapper">

<?php include('includes/topbar.php'); ?>

<div class="content-wrapper">
<div class="content-container">

<?php
$paych = mysqli_fetch_assoc(mysqli_query(
    $con,
    "SELECT position FROM staffprive WHERE staffid='{$_SESSION['staffid']}'"
));

if ($paych['position'] == "accountant") {
    include('includes/accbar.php');
} elseif ($paych['position'] == "bursar") {
    include('includes/bursar.php');
} elseif ($paych['position'] == "superadmin") {
    include('includes/supbar.php');
} else {
    include('includes/leftbar.php');
}
?>

<div class="main-page">
<div class="container-fluid">
<h2 class="title">Multi-Programme Payment Summary By Session</h2>
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
    $_SESSION['export_mode'] = 'multi_programme_summary';

   $sql = "
SELECT 
    p.pname AS programme,
    sp.purpose,
    COUNT(DISTINCT sp.studentid) AS total_students,
    SUM(CAST(sp.amount AS UNSIGNED)) AS total_amount
FROM program p
LEFT JOIN studentpayment sp
    ON UPPER(TRIM(sp.programme)) = UPPER(TRIM(p.pname))
    AND sp.section = '$section'
    AND LOWER(sp.status) = 'success'
GROUP BY p.pname, sp.purpose
ORDER BY p.pname, sp.purpose
";


    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['programme']][] = $row;
        }
?>

<hr>

<div align="center">
<img src="<?= $logo ?>" width="90"><br>
<strong><?= htmlentities($orgname) ?></strong><br>
PAYMENT SUMMARY (ALL PROGRAMMES)<br>
Session: <?= htmlentities($section) ?>
</div>

<?php
$grandTotal = 0;
$progCount = 0;

foreach ($data as $programme => $rows) {
    $progCount++;
    $progTotal = 0;
?>

<div class="prog-title"><?= $progCount ?>. <?= htmlentities($programme) ?></div>

<table class="table table-bordered">
<tr>
    <th>#</th>
    <th>Fee Purpose</th>
    <th>Total Students</th>
    <th>Total Amount (₦)</th>
</tr>

<?php
$cnt = 1;
foreach ($rows as $row) {
    $progTotal += $row['total_amount'];
    $grandTotal += $row['total_amount'];
?>
<tr>
    <td><?= $cnt++ ?></td>
    <td><?= htmlentities($row['purpose']) ?></td>
    <td><?= $row['total_students'] ?></td>
    <td>₦ <?= number_format($row['total_amount'], 2) ?></td>
</tr>
<?php } ?>

<tr style="font-weight:bold;">
    <td colspan="3" align="right">PROGRAMME TOTAL</td>
    <td>₦ <?= number_format($progTotal, 2) ?></td>
</tr>

</table>

<?php } ?>

<hr>

<table class="table table-bordered">
<tr style="font-weight:bold;">
    <td align="right">GRAND TOTAL FOR SESSION</td>
    <td width="250">₦ <?= number_format($grandTotal, 2) ?></td>
</tr>
</table>

<button class="btn btn-primary" onclick="window.open('printpdf','_blank')">
    Print PDF
</button>
<button class="btn btn-success" onclick="window.open('section_summary_csv','_blank')">
    Export CSV
</button>

<?php
    } else {
        $showModals = true;
    }
}
?>

</div>
</div>
</div>

</div>
</div>
</div>

<?php include 'includes/buton.php'; ?>

<?php if (!empty($showModals)) { ?>
<script>
$(function(){ $('#checkModals').modal('show'); });
</script>
<?php } ?>

</body>
</html>
<?php }?>