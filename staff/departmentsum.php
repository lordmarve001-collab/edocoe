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
        <title><?php echo htmlentities($orgname) ;?> - Department Result Summary</title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/print.css" media="print" >
      <style type="text/css">
                @media print{
                  @page {size: landscape}
                  .WordSection1 td{
                    font-size: 8px !important;
                  }
                  .headerp{
                     position: fixed;
                  }
                  table { page-break-after:auto }
                  tr    { page-break-inside:avoid; page-break-after:auto }
                  td    { page-break-inside:avoid; page-break-after:auto }
                  .talk{
                    text-align: justify;
                  }
                  .no-print {
                    display: none !important;
                  }
                  .btn-print-actions {
                    display: none !important;
                  }
                }
                .tals{
                    width: 470px;
                    margin: 0px;
                    border: 1px solid;
                    border-collapse: collapse;
                }
                .talss, .talk{
                    border: 1px solid;
                }
                .talk{
                    text-align: justify;
                }
                
                /* Enhanced Styles */
                .result-summary-header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                
                .result-summary-header img {
                    display: block;
                    margin: 0 auto;
                }
                
                .stats-card {
                    background: #f8f9fa;
                    border-radius: 8px;
                    padding: 10px;
                    margin: 5px;
                    text-align: center;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                
                .stats-card h4 {
                    margin: 0;
                    color: #333;
                    font-size: 14px;
                }
                
                .stats-card .stat-number {
                    font-size: 24px;
                    font-weight: bold;
                    color: #007bff;
                }
                
                .btn-custom {
                    padding: 8px 20px;
                    border-radius: 5px;
                    font-weight: bold;
                    transition: all 0.3s ease;
                    margin: 5px;
                }
                
                .btn-custom:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                }
                
                .loading-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.7);
                    z-index: 9999;
                    display: none;
                    justify-content: center;
                    align-items: center;
                }
                
                .loading-spinner {
                    background: white;
                    padding: 20px;
                    border-radius: 10px;
                    text-align: center;
                }
                
                .spinner-border {
                    width: 3rem;
                    height: 3rem;
                }
                
                .legend-box {
                    background: #f8f9fa;
                    border-left: 4px solid #007bff;
                    padding: 10px;
                    margin: 15px 0;
                    font-size: 12px;
                }
                
                .legend-item {
                    display: inline-block;
                    margin: 5px 15px 5px 0;
                }
                
                .legend-color {
                    width: 20px;
                    height: 20px;
                    display: inline-block;
                    border-radius: 3px;
                    margin-right: 5px;
                    vertical-align: middle;
                }
                
                .table-responsive-custom {
                    overflow-x: auto;
                    margin: 20px 0;
                }
                
                .grade-cell {
                    text-align: center;
                    padding: 5px !important;
                    font-size: 9px;
                }
                
                .grade-pass {
                    background-color: #d4edda;
                    color: #155724;
                }
                
                .grade-fail {
                    background-color: #f8d7da;
                    color: #721c24;
                }
                
                .grade-pending {
                    background-color: #fff3cd;
                    color: #856404;
                }
                
                .btn-group-actions {
                    display: flex;
                    justify-content: center;
                    gap: 10px;
                    margin-top: 20px;
                    flex-wrap: wrap;
                }
                
                .btn-group-actions .btn {
                    min-width: 150px;
                }
                
                .search-box {
                    margin: 20px 0;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 5px;
                }
                
                .signature-section {
                    margin-top: 40px;
                    page-break-inside: avoid;
                }
                
                .signature-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-end;
                    margin-top: 30px;
                    flex-wrap: wrap;
                }
                
                .signature-box {
                    text-align: center;
                    flex: 1;
                    min-width: 200px;
                    margin: 10px;
                }
                
                .signature-line {
                    border-top: 1px solid #000;
                    margin-top: 30px;
                    padding-top: 10px;
                }
                
                .filter-bar {
                    background: white;
                    padding: 15px;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    margin-bottom: 20px;
                }
                
                .cgpa-cell {
                    font-weight: bold;
                    text-align: center;
                    background-color: #e8f4f8;
                    font-size: 11px;
                }
                
                .cgpa-excellent {
                    background-color: #28a745;
                    color: white;
                }
                
                .cgpa-good {
                    background-color: #17a2b8;
                    color: white;
                }
                
                .cgpa-average {
                    background-color: #ffc107;
                    color: #856404;
                }
                
                .cgpa-poor {
                    background-color: #dc3545;
                    color: white;
                }
                
                /* Reduced font size for result table */
                .MsoTableGrid td, .MsoTableGrid th {
                    font-size: 9px;
                    padding: 4px;
                }
                
                .MsoTableGrid td small, .MsoTableGrid th small {
                    font-size: 7px;
                }
                
                /* Course code header with M|G|P below */
                .course-header {
                    text-align: center;
                    font-weight: bold;
                }
                
                .course-code {
                    font-size: 10px;
                    font-weight: bold;
                    display: block;
                }
                
                .course-units {
                    font-size: 8px;
                    display: block;
                }
                
                .course-mgp {
                    font-size: 7px;
                    display: block;
                    margin-top: 3px;
                }
                
                /* Student name and ID */
                .student-info {
                    font-size: 9px;
                }
                
                .student-id {
                    font-weight: bold;
                    font-size: 10px;
                }
      </style>
    </head>
    <body class="top-navbar-fixed">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p>Processing result data...</p>
            </div>
        </div>
        
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php');?> 
            
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                    /*if ($paych['position']=="hod") {
                        include('includes/hodbar.php');
                    } else if ($paych['position']=="dean") {
                        include('includes/deanbar.php');
                    } elseif ($paych['position']=="facofficer") {
                        include('includes/facbar.php');
                    } elseif ($paych['position']=="superadmin") {
                        include('includes/supbar.php');
                    } elseif ($paych['position']=="academicaffair") {
                        include('includes/acdbar.php');
                    } elseif ($paych['position']=="dvc_acad") {
                        include('includes/dvcbar.php');
                    } elseif ($paych['position']=="vc") {
                        include('includes/vcbar.php');
                    } elseif ($paych['position']=="exams") {
                        include('includes/exambar.php');
                    } else {
                        include('includes/leftbar.php');
                    }*/
                    $paycr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM examoff WHERE staffid='".$_SESSION['staffid']."' AND acadsession='$ses' "));
                    if ($paycr['deptm']=$pro) {
                        
                         include('includes/exambar.php');
                    }
                    ?>
                    
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title"><i class="fa fa-bar-chart"></i> Department Result Summary Setup</h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            
                                            <?php
                                            if ($paych['position']=="hod" OR $paych['position']=='lecturer') { 
                                            ?>
                                            <section class="section secb">
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data" id="resultForm">
                                                    <div class="filter-bar">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Session:</label>
                                                                    <select name="section" class="form-control" required>
                                                                        <option value="">Select Session</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["section"]; ?>"><?php echo $row["section"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Semester:</label>
                                                                    <select name="semester" class="form-control" required>
                                                                        <option value="">Select Semester</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["semest"]; ?>"><?php echo $row["semest"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Programme:</label>
                                                                    <select name="progrem" class="form-control" required>
                                                                        <option value="">Select Programme</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM program WHERE did='$deplt' ORDER BY pname");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["pname"]; ?>"><?php echo $row["pname"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Level:</label>
                                                                    <select name="level" class="form-control" required>
                                                                        <option value="">Select Level</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM level ORDER BY levels");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["levels"]; ?>"><?php echo $row["levels"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <button type="submit" name="query" class="btn btn-primary btn-custom">
                                                                    <i class="fa fa-search"></i> View Result
                                                                </button>
                                                                <button type="reset" class="btn btn-default btn-custom">
                                                                    <i class="fa fa-refresh"></i> Reset
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </section>
                                            <?php
                                            } elseif($paych['position']=="facofficer" OR $paych['position']=='dean') {
                                            ?>
                                            <section class="section secb">
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data" id="resultForm">
                                                    <div class="filter-bar">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Session:</label>
                                                                    <select name="section" class="form-control" required>
                                                                        <option value="">Select Session</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["section"]; ?>"><?php echo $row["section"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Semester:</label>
                                                                    <select name="semester" class="form-control" required>
                                                                        <option value="">Select Semester</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["semest"]; ?>"><?php echo $row["semest"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Programme:</label>
                                                                    <select name="progrem" class="form-control" required>
                                                                        <option value="">Select Programme</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM program WHERE fid='$faclt' ORDER BY pname");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["pname"]; ?>"><?php echo $row["pname"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Level:</label>
                                                                    <select name="level" class="form-control" required>
                                                                        <option value="">Select Level</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM level ORDER BY levels");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["levels"]; ?>"><?php echo $row["levels"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <button type="submit" name="queryt" class="btn btn-primary btn-custom">
                                                                    <i class="fa fa-search"></i> View Result
                                                                </button>
                                                                <button type="reset" class="btn btn-default btn-custom">
                                                                    <i class="fa fa-refresh"></i> Reset
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </section>
                                            <?php
                                            } elseif($paych['position']=="superadmin" OR $paych['position']=='academicaffair' OR $paych['position']=='exams') { 
                                            ?>
                                            <section class="section secb">
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data" id="resultForm">
                                                    <div class="filter-bar">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Session:</label>
                                                                    <select name="section" class="form-control" required>
                                                                        <option value="">Select Session</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["section"]; ?>"><?php echo $row["section"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Semester:</label>
                                                                    <select name="semester" class="form-control" required>
                                                                        <option value="">Select Semester</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["semest"]; ?>"><?php echo $row["semest"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Programme:</label>
                                                                    <select name="progrem" class="form-control" required>
                                                                        <option value="">Select Programme</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM programs ORDER BY pname");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["pname"]; ?>"><?php echo $row["pname"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Level:</label>
                                                                    <select name="level" class="form-control" required>
                                                                        <option value="">Select Level</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM level ORDER BY levels");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["levels"]; ?>"><?php echo $row["levels"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <button type="submit" name="queryt" class="btn btn-primary btn-custom">
                                                                    <i class="fa fa-search"></i> View Result
                                                                </button>
                                                                <button type="reset" class="btn btn-default btn-custom">
                                                                    <i class="fa fa-refresh"></i> Reset
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </section>
                                            <?php 
                                            } elseif($paych['position']=='dvc_acad' OR $paych['position']=='vc') { 
                                            ?>
                                            <section class="section secb">
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data" id="resultForm">
                                                    <div class="filter-bar">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Session:</label>
                                                                    <select name="section" class="form-control" required>
                                                                        <option value="">Select Session</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["section"]; ?>"><?php echo $row["section"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Semester:</label>
                                                                    <select name="semester" class="form-control" required>
                                                                        <option value="">Select Semester</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["semest"]; ?>"><?php echo $row["semest"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Programme:</label>
                                                                    <select name="progrem" class="form-control" required>
                                                                        <option value="">Select Programme</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM programs ORDER BY pname");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["pname"]; ?>"><?php echo $row["pname"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Level:</label>
                                                                    <select name="level" class="form-control" required>
                                                                        <option value="">Select Level</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM level ORDER BY levels");
                                                                        while ($row = mysqli_fetch_array($res)) {
                                                                        ?>
                                                                        <option value="<?php echo $row["levels"]; ?>"><?php echo $row["levels"]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <button type="submit" name="query" class="btn btn-primary btn-custom">
                                                                    <i class="fa fa-search"></i> View Result
                                                                </button>
                                                                <button type="reset" class="btn btn-default btn-custom">
                                                                    <i class="fa fa-refresh"></i> Reset
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </section>
                                            <?php } else { ?>
                                            <div class="alert alert-danger">
                                                <i class="fa fa-exclamation-triangle"></i> 
                                                Sorry Dear: <b><?php echo htmlentities($fullname); ?></b> You Don't Have Access to this Page. Thank You.
                                            </div>
                                            <?php } ?>
                                            
                                            <!-- Result Display Section -->
                                            <?php 
                                            $error='';
                                            if (isset($_POST['queryt'])) {
                                                if (empty($_POST['level'])) {
                                                    $error='level';
                                                    $showModal = "true";
                                                } elseif (empty($_POST['section'])) {
                                                    $showModal = "true";
                                                    $error='session';
                                                } elseif (empty($_POST['semester'])) {
                                                    $showModal = "true";
                                                    $error='Semester';
                                                } elseif (empty($_POST['progrem'])) {
                                                    $showModal = "true";
                                                    $error='Programme';
                                                } else {
                                                    $clvl=mysqli_escape_string($con,$_POST['level']);
                                                    $secc=mysqli_escape_string($con,$_POST['section']);
                                                    $sems=mysqli_escape_string($con,$_POST['semester']);
                                                    $prgs=mysqli_escape_string($con,$_POST['progrem']);
                                                    $_SESSION['level']=$clvl;
                                                    $_SESSION['section']=$secc;
                                                    $_SESSION['semester']=$sems;
                                                    $_SESSION['program']=$prgs;
                                                    
                                                    $sql=mysqli_query($con,"SELECT * FROM result WHERE programme='$prgs' AND level='$clvl' AND acadsession='$secc' AND semester='$sems' AND status='Y'");
                                                    $rows = mysqli_num_rows($sql);
                                                    if($rows > 0) { 
                                            ?>
                                            <div class="WordSection1">
                                                <!-- Header Section - Centered -->
                                                <div class="result-summary-header">
                                                    <img src="<?php echo $logo; ?>" width="80" height="80" alt="Logo">
                                                    <h3><?php echo htmlentities($orgname); ?></h3>
                                                    <p><?php echo htmlentities($schadress); ?></p>
                                                    <h4>DEPARTMENTAL RESULT SUMMARY</h4>
                                                </div>
                                                
                                                <?php 
                                                $daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pname='$prgs'"));
                                                $deplt=$daft['did'];
                                                $depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='$deplt'"));
                                                $dt = $depna['dept'];
                                                ?>
                                                
                                                <!-- Department Info -->
                                                <div class="stats-card" style="background: #e9ecef;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <strong>DEPARTMENT:</strong> <?php echo htmlentities($dt); ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>PROGRAMME:</strong> <?php echo htmlentities($prgs); ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-4">
                                                            <strong>SESSION:</strong> <?php echo htmlentities($secc); ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>SEMESTER:</strong> <?php echo htmlentities($sems); ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>LEVEL:</strong> <?php echo htmlentities($clvl); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Result Table -->
                                                <div class="table-responsive-custom">
                                                    <table class="MsoTableGrid" border="1" cellspacing="0" cellpadding="0" align="center" style='border-collapse:collapse;width:100%;'>
                                                        <?php 
                                                        try {
                                                            $sqlc = "SELECT DISTINCT result.coursecode,result.semester,result.level,course_allocation.unit FROM result JOIN course_allocation ON result.coursecode=course_allocation.coursecode
                                                                    WHERE result.programme='$prgs' AND course_allocation.programme='$prgs' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$secc' ORDER BY result.coursecode";
                                                            $sts = mysqli_query($con, $sqlc);
                                                            $counter = mysqli_fetch_all($sts, MYSQLI_ASSOC);
                                                            $Acounter = 0;
                                                            foreach ($counter as $rowa) {
                                                                $Acounter+=1;
                                                                $code[$Acounter]=$rowa['coursecode'];
                                                                $SMSTER[$Acounter]=$rowa['semester'];
                                                                $un[$Acounter]=$rowa['unit'];
                                                                $Lev[$Acounter]=$rowa['level'];
                                                            }
                                                        ?>
                                                        <thead>
                                                            <tr style="background: #667eea; color: white;">
                                                                <th width="5%">#</th>
                                                                <th width="15%">Student ID</th>
                                                                <?php for ($t = 1; $t <= $Acounter; $t++) { ?>
                                                                <th width="8%" class="course-header">
                                                                    <span class="course-code"><?php echo $code[$t]; ?></span>
                                                                    <span class="course-units">(<?php echo $un[$t]; ?> units)</span>
                                                                    <span class="course-mgp">M | G | P</span>
                                                                </th>
                                                                <?php } ?>
                                                              </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $sqlv="SELECT DISTINCT studentid FROM result WHERE acadsession='$secc' AND semester='$sems' AND level='$clvl' AND programme='$prgs' ORDER BY studentid ASC";
                                                            $stv = mysqli_query($con,$sqlv);
                                                            $countv = mysqli_fetch_all($stv,MYSQLI_ASSOC);
                                                            $d=0;
                                                            foreach($countv as $rowv) {
                                                                $d+=1;
                                                                stuname($rowv['studentid'],$con);
                                                            ?>
                                                              <tr>
                                                                  <td><?php echo $d; ?></td>
                                                                  <td class="student-info">
                                                                      <div class="student-id"><?php echo $rowv['studentid']; ?></div>
                                                                      <small><?php echo $_POST["harrd"]; ?></small>
                                                                  </td>
                                                                <?php
                                                                $sqlct = "SELECT DISTINCT coursecode FROM result WHERE programme='$prgs' AND level='$clvl' AND semester='$sems' AND acadsession='$secc' ORDER BY coursecode";
                                                                $sts=mysqli_query($con, $sqlct);
                                                                $counterx=mysqli_fetch_all($sts, MYSQLI_ASSOC);
                                                                $Acounterc=0;
                                                                foreach ($counterx as $rowac) {
                                                                    $Acounterc+=1;
                                                                    $cour[$Acounterc]= $rowac['coursecode'];
                                                                }
                                                                for ($tm = 1; $tm <= $Acounterc; $tm++) {
                                                                    loadcorecourse($cour[$tm],$rowv['studentid'],$sems,$clvl,$secc,$prgs,$con);
                                                                    $gradeClass = '';
                                                                    if(strpos($_POST['couif'], 'FA') !== false || strpos($_POST['couif'], 'Not-') !== false || strpos($_POST['couif'], '---') !== false) {
                                                                        $gradeClass = 'grade-fail';
                                                                    } elseif(strpos($_POST['couif'], 'Approved') !== false) {
                                                                        $gradeClass = 'grade-pass';
                                                                    }
                                                                ?>
                                                                <td class="grade-cell <?php echo $gradeClass; ?>">
                                                                    <?php echo $_POST['couif']; ?>
                                                                  </td>
                                                                <?php } ?>
                                                              </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                        <?php } catch (Exception $e) {
                                                            echo '<tr><td colspan="100" class="text-danger">Error: ' . $e->getMessage() . '</td></tr>';
                                                        } ?>
                                                      </table>
                                                </div>
                                                
                                                <!-- Legend -->
                                                <div class="legend-box">
                                                    <strong>Legend:</strong>
                                                    <div class="legend-item"><span class="legend-color" style="background: #28a745;"></span> Approved</div>
                                                    <div class="legend-item"><span class="legend-color" style="background: #ffc107;"></span> Pending</div>
                                                    <div class="legend-item"><span class="legend-color" style="background: #dc3545;"></span> Failed/Not Registered</div>
                                                    <div class="legend-item"><span class="legend-color" style="background: #17a2b8;"></span> Not Uploaded</div>
                                                    <div class="legend-item">FA - Failed due to Absence</div>
                                                    <div class="legend-item">--- - Not Registered</div>
                                                </div>
                                                
                                                <!-- Course List Table -->
                                                <div class="table-responsive-custom">
                                                    <table class="tals" style="width: 100%;">
                                                        <thead>
                                                            <tr style="background: #667eea; color: white;">
                                                                <th class="talss">#</th>
                                                                <th class="talss">Code</th>
                                                                <th class="talss">Title</th>
                                                                <th class="talss">Units</th>
                                                              </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $cds = "SELECT DISTINCT r.coursecode,c.title,r.semester,r.level,ca.unit FROM result r JOIN course c ON r.coursecode = c.coursecode JOIN course_allocation ca 
                                                                    ON r.coursecode = ca.coursecode AND ca.programme='$prgs' WHERE r.programme = '$prgs' AND r.level = '$clvl' AND r.semester = '$sems' AND r.acadsession = '$secc' ORDER BY r.coursecode";
                                                            $ct = mysqli_query($con, $cds);
                                                            $cnt = mysqli_fetch_all($ct, MYSQLI_ASSOC);
                                                            $Acounterv = 0;
                                                            $dt=0;
                                                            foreach ($cnt as $rowb) {
                                                                $Acounterv += 1;
                                                                $codev[$Acounterv] = $rowb['coursecode'];
                                                                $Tt[$Acounterv]  = $rowb['title'];
                                                                $unv[$Acounterv]  = $rowb['unit'];
                                                            }
                                                            for ($kt=1; $kt <=$Acounterv ; $kt++) {
                                                                $dt+=1;
                                                            ?>
                                                              <tr>
                                                                <td class="talk"><?php echo htmlentities($dt); ?></td>
                                                                <td class="talk"><?php echo htmlentities($codev[$kt]); ?></td>
                                                                <td class="talk"><?php echo htmlentities($Tt[$kt]); ?></td>
                                                                <td class="talk" align="center"><?php echo htmlentities($unv[$kt]); ?></td>
                                                              </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                      </table>
                                                </div>
                                                
                                                <!-- Signature Section - In a Row -->
                                                <div class="signature-section">
                                                    <?php 
                                                    deanname($con,$prgs);
                                                    hodname($con,$prgs);
                                                    vcname($con);
                                                    ?>     
                                                    <div class="signature-row">
                                                        <div class="signature-box">
                                                            <strong>HEAD OF DEPARTMENT</strong><br>
                                                            Name: <?php echo htmlentities($_POST['nameh']); ?><br>
                                                            <div class="signature-line">Sign: __________________</div>
                                                            Date: __________________
                                                        </div>
                                                        <div class="signature-box">
                                                            <strong>DEAN</strong><br>
                                                            Name: <?php echo htmlentities($_POST['names']); ?><br>
                                                            <div class="signature-line">Sign: __________________</div>
                                                            Date: __________________
                                                        </div>
                                                        <div class="signature-box">
                                                            <strong>PROVOST</strong><br>
                                                            Name: <?php echo htmlentities($_POST['vname']); ?><br>
                                                            <div class="signature-line">Sign: __________________</div>
                                                            Date: __________________
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Action Buttons -->
                                                <div class="btn-group-actions no-print">
                                                    <button class="btn btn-primary" onclick="window.print()">
                                                        <i class="fa fa-print"></i> Print
                                                    </button>
                                                    <button class="btn btn-info" onclick="window.open('depfull','_blank')">
                                                        <i class="fa fa-expand"></i> Full Screen
                                                    </button>
                                                    <button class="btn btn-success" onclick="printToPDF()">
                                                        <i class="fa fa-file-pdf-o"></i> Save as PDF
                                                    </button>
                                                    <button class="btn btn-default" onclick="exportToExcel()">
                                                        <i class="fa fa-file-excel-o"></i> Export to Excel
                                                    </button>
                                                </div>
                                            </div>
                                            <?php 
                                            } else {
                                                $showModale='true';
                                            }
                                        }
                                    }
                                    ?>
                                    
                                    <!-- For Superadmin/Dean with CGPA -->
                                    <?php 
                                    $error='';
                                    if (isset($_POST['query'])) {
                                        if (empty($_POST['level'])) {
                                            $error='level';
                                            $showModal = "true";
                                        } elseif (empty($_POST['section'])) {
                                            $showModal = "true";
                                            $error='session';
                                        } elseif (empty($_POST['semester'])) {
                                            $showModal = "true";
                                            $error='Semester';
                                        } elseif (empty($_POST['progrem'])) {
                                            $showModal = "true";
                                            $error='Programme';
                                        } else {
                                            $clvl=mysqli_escape_string($con,$_POST['level']);
                                            $secc=mysqli_escape_string($con,$_POST['section']);
                                            $sems=mysqli_escape_string($con,$_POST['semester']);
                                            $prgs=mysqli_escape_string($con,$_POST['progrem']);
                                            $_SESSION['level']=$clvl;
                                            $_SESSION['section']=$secc;
                                            $_SESSION['semester']=$sems;
                                            $_SESSION['program']=$prgs;
                                            
                                            $sql=mysqli_query($con,"SELECT * FROM result WHERE programme='$prgs' AND level='$clvl' AND acadsession='$secc' AND semester='$sems'");
                                            $rows = mysqli_num_rows($sql);
                                            if($rows > 0) { 
                                    ?>
                                            <div class="WordSection1">
                                                <!-- Header Section - Centered -->
                                                <div class="result-summary-header">
                                                    <img src="<?php echo $logo; ?>" width="80" height="80" alt="Logo">
                                                    <h3><?php echo htmlentities($orgname); ?></h3>
                                                    <p><?php echo htmlentities($schadress); ?></p>
                                                    <h4>DEPARTMENTAL RESULT SUMMARY</h4>
                                                </div>
                                                
                                                <?php 
                                                $daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pname='$prgs'"));
                                                $deplt=$daft['did'];
                                                $depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='$deplt'"));
                                                $dt = $depna['dept'];
                                                ?>
                                                
                                                <div class="stats-card" style="background: #e9ecef;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <strong>DEPARTMENT:</strong> <?php echo htmlentities($dt); ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>PROGRAMME:</strong> <?php echo htmlentities($prgs); ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-4">
                                                            <strong>SESSION:</strong> <?php echo htmlentities($secc); ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>SEMESTER:</strong> <?php echo htmlentities($sems); ?>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>LEVEL:</strong> <?php echo htmlentities($clvl); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="table-responsive-custom">
                                                    <table class="MsoTableGrid" border="1" cellspacing="0" cellpadding="0" align="center" style='border-collapse:collapse;width:100%;'>
                                                        <?php 
                                                        try {
                                                            $sqlc = "SELECT DISTINCT result.coursecode,result.semester,result.level,course_allocation.unit FROM result JOIN course_allocation ON result.coursecode=course_allocation.coursecode
                                                                    WHERE result.programme='$prgs' AND course_allocation.programme='$prgs' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$secc' ORDER BY result.coursecode";
                                                            $sts = mysqli_query($con, $sqlc);
                                                            $counter = mysqli_fetch_all($sts, MYSQLI_ASSOC);
                                                            $Acounter = 0;
                                                            foreach ($counter as $rowa) {
                                                                $Acounter+=1;
                                                                $code[$Acounter]=$rowa['coursecode'];
                                                                $SMSTER[$Acounter]=$rowa['semester'];
                                                                $un[$Acounter]=$rowa['unit'];
                                                                $Lev[$Acounter]=$rowa['level'];
                                                            }
                                                        ?>
                                                        <thead>
                                                            <tr style="background: #667eea; color: white;">
                                                                <th width="5%">#</th>
                                                                <th width="12%">Student ID</th>
                                                                <?php for ($t = 1; $t <= $Acounter; $t++) { ?>
                                                                <th width="7%" class="course-header">
                                                                    <span class="course-code"><?php echo $code[$t]; ?></span>
                                                                    <span class="course-units">(<?php echo $un[$t]; ?>u)</span>
                                                                </th>
                                                                <?php } ?>
                                                                <th width="8%">CGPA</th>
                                                              <tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $sqlv="SELECT DISTINCT studentid FROM result WHERE acadsession='$secc' AND semester='$sems' AND level='$clvl' AND programme='$prgs' ORDER BY studentid ASC";
                                                            $stv = mysqli_query($con,$sqlv);
                                                            $countv = mysqli_fetch_all($stv,MYSQLI_ASSOC);
                                                            $d=0;
                                                            foreach($countv as $rowv) {
                                                                $d+=1;
                                                                stuname($rowv['studentid'],$con);
                                                                
                                                                // Calculate CGPA for this student
                                                                $studentCGPA = calculateStudentCGPA($con, $rowv['studentid'], $sems, $clvl, $secc, $prgs);
                                                                
                                                                // Determine CGPA class for styling
                                                                $cgpaClass = '';
                                                                if ($studentCGPA >= 3.5) {
                                                                    $cgpaClass = 'cgpa-excellent';
                                                                } elseif ($studentCGPA >= 3.0) {
                                                                    $cgpaClass = 'cgpa-good';
                                                                } elseif ($studentCGPA >= 2.0) {
                                                                    $cgpaClass = 'cgpa-average';
                                                                } elseif ($studentCGPA > 0) {
                                                                    $cgpaClass = 'cgpa-poor';
                                                                }
                                                            ?>
                                                              <tr>
                                                                  <td><?php echo $d; ?></td>
                                                                  <td class="student-info">
                                                                      <div class="student-id"><?php echo $rowv['studentid']; ?></div>
                                                                      <small><?php echo $_POST["harrd"]; ?></small>
                                                                  </td>
                                                                <?php
                                                                $sqlct = "SELECT DISTINCT coursecode FROM result WHERE programme='$prgs' AND level='$clvl' AND semester='$sems' AND acadsession='$secc' ORDER BY coursecode";
                                                                $sts=mysqli_query($con, $sqlct);
                                                                $counterx=mysqli_fetch_all($sts, MYSQLI_ASSOC);
                                                                $Acounterc=0;
                                                                foreach ($counterx as $rowac) {
                                                                    $Acounterc+=1;
                                                                    $cour[$Acounterc]= $rowac['coursecode'];
                                                                }
                                                                for ($tm = 1; $tm <= $Acounterc; $tm++) {
                                                                    loadcorecourse($cour[$tm],$rowv['studentid'],$sems,$clvl,$secc,$prgs,$con);
                                                                ?>
                                                                <td class="grade-cell">
                                                                    <?php echo $_POST['couif']; ?>
                                                                  </td>
                                                                <?php } ?>
                                                                <td class="cgpa-cell <?php echo $cgpaClass; ?>">
                                                                    <strong><?php echo number_format($studentCGPA, 2); ?></strong>
                                                                </td>
                                                              </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                        <?php } catch (Exception $e) {
                                                            echo '<tr><td colspan="100">Error: ' . $e->getMessage() . '</td></tr>';
                                                        } ?>
                                                      </table>
                                                </div>
                                                
                                                <div class="legend-box">
                                                    <strong>Legend:</strong>
                                                    <div class="legend-item"><span class="legend-color" style="background: #28a745;"></span> Approved</div>
                                                    <div class="legend-item"><span class="legend-color" style="background: #ffc107;"></span> Pending</div>
                                                    <div class="legend-item"><span class="legend-color" style="background: #dc3545;"></span> Failed</div>
                                                    <div class="legend-item">FA - Failed due to Absence</div>
                                                    <div class="legend-item">--- - Not Registered</div>
                                                    <div class="legend-item"><span class="legend-color" style="background: #28a745;"></span> CGPA ≥ 3.5 - Excellent</div>
                                                    <div class="legend-item"><span class="legend-color" style="background: #17a2b8;"></span> CGPA 3.0 - 3.49 - Good</div>
                                                    <div class="legend-item"><span class="legend-color" style="background: #ffc107;"></span> CGPA 2.0 - 2.99 - Average</div>
                                                    <div class="legend-item"><span class="legend-color" style="background: #dc3545;"></span> CGPA {'<'} 2.0 - Poor</div>
                                                </div>
                                                
                                                <!-- Signature Section - In a Row -->
                                                <div class="signature-section">
                                                    <?php 
                                                    deanname($con,$prgs);
                                                    hodname($con,$prgs);
                                                    vcname($con);
                                                    ?>     
                                                    <div class="signature-row">
                                                        <div class="signature-box">
                                                            <strong>DEPARTMENT OFFICER</strong><br>
                                                            Name: <?php echo htmlentities($_POST['nameh']); ?><br>
                                                            <div class="signature-line">Sign: __________________</div>
                                                            Date: __________________
                                                        </div>
                                                        <div class="signature-box">
                                                            <strong>DEAN</strong><br>
                                                            Name: <?php echo htmlentities($_POST['names']); ?><br>
                                                            <div class="signature-line">Sign: __________________</div>
                                                            Date: __________________
                                                        </div>
                                                        <div class="signature-box">
                                                            <strong>PROVOST</strong><br>
                                                            Name: <?php echo htmlentities($_POST['vname']); ?><br>
                                                            <div class="signature-line">Sign: __________________</div>
                                                            Date: __________________
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="btn-group-actions no-print">
                                                    <button class="btn btn-primary" onclick="window.print()">
                                                        <i class="fa fa-print"></i> Print
                                                    </button>
                                                    <button class="btn btn-info" onclick="window.open('depfull','_blank')">
                                                        <i class="fa fa-expand"></i> Full Screen
                                                    </button>
                                                    <button class="btn btn-success" onclick="printToPDF()">
                                                            <i class="fa fa-file-pdf-o"></i> Save as PDF
                                                        </button>
                                                </div>
                                            </div>
                                            <?php 
                                            } else {
                                                $showModale='true';
                                            }
                                        }
                                    }
                                    ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modals -->
        <div class="modal fade" id="checkModal" role="dialog" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Error Message</h5>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Please Select <?php echo htmlentities($error); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="checkModale" role="dialog" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Error Message</h5>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>There is No Result based on the Criteria Selected</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        
        <script>
            $(document).ready(function() {
                // Auto-hide alerts after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 5000);
                
                // Show loading on form submit
                $('#resultForm').on('submit', function() {
                    $('#loadingOverlay').css('display', 'flex');
                });
            });
            
            function printToPDF() {
                window.print();
            }
            
            function exportToExcel() {
                var table = document.querySelector('.MsoTableGrid');
                var html = table.outerHTML;
                var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
                var link = document.createElement('a');
                link.download = 'result_summary.xls';
                link.href = url;
                link.click();
            }
        </script>
        
        <?php
        include 'includes/buton.php';
        ?>
    </body>
</html>

<?php }
if(!empty($showModal)) {
    echo '<script type="text/javascript">
        $(document).ready(function(){
            $("#checkModal").modal("show");
        });
    </script>';
} 
if(!empty($showModale)) {
    echo '<script type="text/javascript">
        $(document).ready(function(){
            $("#checkModale").modal("show");
        });
    </script>';
}
?>

<?php
// Functions
function stuname($maxt,$con) {
    $sql = mysqli_query($con, "SELECT * FROM student WHERE studentid='$maxt'");
    $data = mysqli_fetch_assoc($sql);
    if ($data=="") {
        $_POST["harrd"] = "No Name";
    } else {
        $first = $data['firstname'];
        $las = $data['lastname'];
        $name = $first . " " . $las;
        $_POST["harrd"] = $name;
    }
}

function GetGradeSystem($con) {
    $sqlper = "SELECT * FROM gradetable ORDER BY maxscore DESC";
    $result = mysqli_query($con, $sqlper);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $gradeCounter = 0;
    foreach ($data as $row) {
        $gradeCounter++;
        $_POST['Minscore'][$gradeCounter] = $row["minscore"];
        $_POST['Maxscore'][$gradeCounter] = $row["maxscore"];
        $_POST['Grade'][$gradeCounter] = $row["grade"];
        $_POST['point'][$gradeCounter] = $row["point"];
    }
    $_SESSION['gradeCounter'] = $gradeCounter;
}

// Function to properly calculate CGPA for a student
function calculateStudentCGPA($con, $studentId, $semester, $level, $session, $programme) {
    // Get all registered courses for this student in the specified semester
    $query = "SELECT r.coursecode, r.ca, r.exam, r.status, ca.unit 
              FROM result r 
              JOIN course_allocation ca ON r.coursecode = ca.coursecode AND ca.programme = '$programme'
              WHERE r.studentid = '$studentId' 
              AND r.semester = '$semester' 
              AND r.level = '$level' 
              AND r.acadsession = '$session'
              AND r.programme = '$programme'";
    
    $result = mysqli_query($con, $query);
    $totalPoints = 0;
    $totalUnits = 0;
    
    if (!$result) {
        return 0.00;
    }
    
    GetGradeSystem($con);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $totalScore = (int)$row['ca'] + (int)$row['exam'];
        $unit = (int)$row['unit'];
        $status = trim($row['status']);
        
        // Only calculate for approved results
        if (($status == 'Y' || $status == 'Approved') && $totalScore > 0) {
            // Find the grade point for this score
            for ($j = 1; $j <= $_SESSION['gradeCounter']; $j++) {
                if ($totalScore <= $_POST['Maxscore'][$j] && $totalScore >= $_POST['Minscore'][$j]) {
                    $gradePoint = $_POST['point'][$j];
                    $totalPoints += $gradePoint * $unit;
                    $totalUnits += $unit;
                    break;
                }
            }
        }
    }
    
    if ($totalUnits > 0) {
        return $totalPoints / $totalUnits;
    }
    
    return 0.00;
}

function GetCumulative($ct, $mat, $sem, $le, $se, $pr, $con) {
    // This function is kept for compatibility but CGPA is now calculated separately
    $scoresCounterc=0;
    $_POST['pointsc']=0;
    $_POST['totalUnitc']=0;
    
$harr = "SELECT r.coursecode, r.ca, r.exam, r.status, ca.unit FROM result r JOIN course_allocation ca ON r.coursecode = ca.coursecode AND ca.programme = '$pr'
              WHERE r.studentid = '$mat' AND r.semester = '$sem' AND r.level = '$le' AND r.acadsession = '$se' AND r.programme = '$pr' AND r.coursecode='$ct'";
            
    $resultfz = mysqli_query($con,$harr);
    $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
    foreach($resulter as $row){
        $scoresCounterc+=1;
        $_POST['Unitc'][$scoresCounterc]=$row['unit'];
        $_POST['Codec'][$scoresCounterc]=$row['coursecode'];
        $_POST['Scoresc'][$scoresCounterc] = (int)$row['exam'] + (int)$row['ca']; 
    }
    $_SESSION['scoresCounterc']=$scoresCounterc; 
    
    GetGradeSystem($con);
    for($k=1; $k<=$_SESSION['scoresCounterc']; $k++) {
        for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++) {
            if ($_POST['Scoresc'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scoresc'][$k] >= $_POST['Minscore'][$j]) {
                $_POST['Gc'][$k] = $_POST['Grade'][$j];
                $_POST['Pc'][$k] = $_POST['point'][$j];
                $_POST['pointsc'] += $_POST['point'][$j] * (int)$_POST['Unitc'][$k];
            }
            $_POST['totalUnitc'] += (int)$_POST['Unitc'][$k];
        }
    }
    
    if($_POST['pointsc']!=0 && $_POST['totalUnitc']!=0) {
        $_POST['PGP'] = number_format(($_POST['pointsc']/$_POST['totalUnitc']),2);
    } else if($_POST['pointsc']==0 && $_POST['totalUnitc']==0) {
        $_POST['PGP'] = "0.00";
    }
}

function loadcorecourse($ct, $mat, $sem, $le, $se, $pr, $con) {
    $_POST['pt']=0;
    
    $rowr = mysqli_fetch_assoc(mysqli_query($con, "SELECT r.coursecode, r.ca, r.exam, r.status, ca.unit FROM result r JOIN course_allocation ca ON r.coursecode = ca.coursecode AND ca.programme = '$pr'
              WHERE r.studentid = '$mat' AND r.semester = '$sem' AND r.level = '$le' AND r.acadsession = '$se' AND r.programme = '$pr' AND r.coursecode='$ct'"));
   
    
    
    if ($rowr!='') {
        $Totaledk = ((int)$rowr["ca"] + (int)$rowr["exam"]);
        $coud = $rowr['coursecode'];
        $std = preg_replace('/\s+/', '',$rowr['status']);
        $unt = $rowr['unit'];
        
        if (($std=='Y' OR $std=='Approved') AND $Totaledk!='0') {
            GetGradeSystem($con);
            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++) {
                if ($Totaledk <= $_POST['Maxscore'][$j] && $Totaledk >= $_POST['Minscore'][$j]) {
                    $_POST['pt'] = $_POST['point'][$j]*$unt;
                    $_POST['G'] = $_POST['Grade'][$j];
                }
            }
            $_POST['couif'] = $Totaledk." | ".$_POST['G']." | ".$_POST['pt'];
        } elseif ($std=='Pending' AND $Totaledk=='0') {
            $_POST['couif'] = 'Not-Approved';
        } elseif ($Totaledk=='0' AND $std!='') {
            $_POST['couif'] = 'FA';
        } elseif ($std=='' AND $Totaledk=='0') {
            $_POST['couif'] = 'Not-Upload';
        } elseif ($std=='' AND $Totaledk!='0') {
            $_POST['couif'] = 'Not-Submitted';
        } elseif ($std=='Pending') {
            $_POST['couif'] = 'Not-Approved';
        }
    } elseif ($rowr=="") {
        $_POST['couif'] = '---';
    }
}

function deanname($con,$prgs){
    $fac = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM program WHERE pname='$prgs'"));  
    $stf = mysqli_query($con, "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='dean' AND staffaculty.faculty='".$fac['fid']."'");
    $res = mysqli_fetch_assoc($stf);
    if ($res!='') {
        $stn = ucwords($res['title'].". ".strtoupper($res['lastname'])." ".$res['firstname']);
        $_POST['names'] = $stn;
    } else {
        $_POST['names'] = "No Name";
    }
}

function vcname($con){
    $dts = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM people JOIN staffprive ON staffprive.staffid=people.staffid WHERE people.status='active' AND staffprive.position='provost'"));
    if ($dts!='') {
        $fulna = $dts['title'].". ".strtoupper($dts['lastname'])." ".$dts['firstname'];
        $_POST['vname'] = $fulna;
    } else {
        $_POST['vname'] = 'No Name';
    }
}

function hodname($con,$prgs){
    $fac = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM program WHERE pname='$prgs'"));  
    $stf = mysqli_query($con, "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='hod' AND staffaculty.deptm='".$fac['did']."'");
    $res = mysqli_fetch_assoc($stf);   
    if ($res!='') {
        $stn = ucwords($res['title'].". ".strtoupper($res['lastname'])." ".$res['firstname']);
        $_POST['nameh'] = $stn;
    } else {
        $_POST['nameh'] = 'No Name';
    }
}
?>