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
<div class="container">
<h2>Payment Summary By Session</h2>

<form method="post" class="row">
<div class="col-md-6">
    <label>Academic Session</label>
    <select name="section" class="form-control" required>
        <option disabled selected>Select Session</option>
        <?php
        $rs = mysqli_query($con,"SELECT section FROM sections ORDER BY section DESC");
        while($r=mysqli_fetch_assoc($rs)){
            $sel = (!empty($_SESSION['section']) && $_SESSION['section']==$r['section'])?'selected':'';
            echo "<option $sel>{$r['section']}</option>";
        }
        ?>
    </select>
</div>
<div class="col-md-6"><br>
    <button name="query" class="btn btn-primary btn-block">View Report</button>
</div>
</form>

<?php
if (isset($_POST['query'])) {

$section = mysqli_real_escape_string($con,$_POST['section']);
$_SESSION['section'] = $section;
$_SESSION['export_mode'] = 'section_summary';

/* =======================
   1. FEE SUMMARY
======================= */
$fee_sql = "
SELECT 
    f.feename fee,
    COUNT(DISTINCT sp.studentid) total_students,
    SUM(CAST(sp.amount AS UNSIGNED)) total_amount
FROM feespope f
LEFT JOIN studentpayment sp
 ON TRIM(LOWER(sp.purpose))=TRIM(LOWER(f.feename))
 AND sp.section='$section'
 AND LOWER(sp.status)='success'
GROUP BY f.feename
ORDER BY f.feename";
$fee_rs = mysqli_query($con,$fee_sql);

/* =======================
   2. PROGRAMME SUMMARY
======================= */
$prog_sql = "
SELECT 
 TRIM(UPPER(programme)) programme,
 purpose,
 COUNT(DISTINCT studentid) total_students,
 SUM(CAST(amount AS UNSIGNED)) total_amount
FROM studentpayment
WHERE section='$section' AND LOWER(status)='success'
GROUP BY programme,purpose
ORDER BY programme,purpose";
$prog_rs = mysqli_query($con,$prog_sql);

$programmes=[];
while($r=mysqli_fetch_assoc($prog_rs)){
    $programmes[$r['programme']][]=$r;
}

/* =======================
   3. LEVEL SUMMARY
======================= */
$level_sql = "
SELECT 
 level,
 COUNT(DISTINCT studentid) total_students,
 SUM(CAST(amount AS UNSIGNED)) total_amount
FROM studentpayment
WHERE section='$section' AND LOWER(status)='success'
GROUP BY level
ORDER BY level";
$level_rs = mysqli_query($con,$level_sql);
?>

<hr>
<h3>Fee Summary (<?= htmlentities($section) ?>)</h3>
<table class="table table-bordered">
<tr><th>#</th><th>Fee</th><th>Payments</th><th>Amount (₦)</th></tr>
<?php $i=1;$gt=0; while($f=mysqli_fetch_assoc($fee_rs)){ $gt+=$f['total_amount']; ?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlentities($f['fee']) ?></td>
<td><?= $f['total_students'] ?></td>
<td><?= number_format($f['total_amount'],2) ?></td>
</tr>
<?php } ?>
<tr style="font-weight:bold">
<td colspan="3" align="right">GRAND TOTAL</td>
<td><?= number_format($gt,2) ?></td>
</tr>
</table>

<h3>Programme Summary</h3>
<?php $p=1; foreach($programmes as $prog=>$rows){ $pt=0; ?>
<h4><?= $p++ ?>. <?= htmlentities($prog) ?></h4>
<table class="table table-bordered">
<tr><th>#</th><th>Purpose</th><th>Payments</th><th>Amount (₦)</th></tr>
<?php $i=1; foreach($rows as $r){ $pt+=$r['total_amount']; ?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlentities($r['purpose']) ?></td>
<td><?= $r['total_students'] ?></td>
<td><?= number_format($r['total_amount'],2) ?></td>
</tr>
<?php } ?>
<tr style="font-weight:bold">
<td colspan="3" align="right">PROGRAMME TOTAL</td>
<td><?= number_format($pt,2) ?></td>
</tr>
</table>
<?php } ?>

<h3>Level Summary</h3>
<table class="table table-bordered">
<tr><th>#</th><th>Level</th><th>Payments</th><th>Amount (₦)</th></tr>
<?php $i=1;$lt=0; while($l=mysqli_fetch_assoc($level_rs)){ $lt+=$l['total_amount']; ?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlentities($l['level']) ?></td>
<td><?= $l['total_students'] ?></td>
<td><?= number_format($l['total_amount'],2) ?></td>
</tr>
<?php } ?>
<tr style="font-weight:bold">
<td colspan="3" align="right">LEVEL TOTAL</td>
<td><?= number_format($lt,2) ?></td>
</tr>
</table>

<a class="btn btn-primary" target="_blank" href="printpdf">Print PDF</a>
<a class="btn btn-success" target="_blank" href="section_summary_csv">Export CSV</a>

<?php } ?>
</div>
</body>
</html>


<?php }
        include 'includes/buton.php';
 function stuname($con,$maxt)
 {
        $sql =mysqli_query($con,"SELECT *  FROM  applicant WHERE applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
     
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $name =$sur." ".$las." ".$first;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
               if ($data=="") {
    $sqlt =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $datat = mysqli_fetch_assoc($sqlt);
      if ($datat=="") {
        $_POST["harrd"]=" No Namess";
      }else{
        $first = $datat['firstname'];
        $sur = $datat['othername'];
        $las=$datat['lastname'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
        }
    }
    

 }
 
 function gettotal($prg,$pps,$con){
    $sum=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(amount) As amount FROM studentpayment WHERE section='$prg' AND purpose='$pps'"));
    $_POST['amount']=$sum['amount'];
 }
 ?>
