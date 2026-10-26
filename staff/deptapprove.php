<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
require "phpmailer/class.phpmailer.php";
$mail = new PHPMailer();

if(strlen($_SESSION['staffid'])=="") {   
    header("Location: ../logout.php"); 
} else {
    $now = time();
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location:../logout.php");
    }
    
    $factn = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM faculty WHERE fname='$fac' "));
    $fact = $factn['fid'];
    $depna = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM department WHERE dept='$dt' "));
    $depn = $depna['deptid'];
    $error = '';
    $dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT semester,session FROM resultsession WHERE description='exam processing'"));
    $sems = $dat['semester'];
    $ses = $dat['session'];

    if (isset($_POST['approve'])) {
        $ccode = $_POST['ccode'];
        $ses = $_POST['ses'];
        $tod = date("Y-m-d H:i:s a");
        $update = mysqli_query($con, "UPDATE allocatecourse SET resultsatus='Approved', appdate='$tod' WHERE coursecord='YES' AND acadsession='$ses' AND coursecode = '$ccode' ");
        if ($update) {
            $updates = mysqli_query($con, "UPDATE result SET status='Y' WHERE acadsession='$ses' AND coursecode = '$ccode' ");
            $succ = 'Result Approval Successful';
            $showModalS = 'true';
        } else {
            $error = 'An Error Occurred While Confirming Data. Please Try Again!!!';
            $showModalEr = 'true';
        }
    }
    
    if (isset($_POST['disapproved'])) {
        $ccode = $_POST['ccode'];
        $ses = $_POST['ses'];
        $upda = mysqli_query($con, "UPDATE allocatecourse SET resultsatus='' WHERE coursecord='YES' AND acadsession='$ses' AND coursecode = '$ccode' ");
        if ($upda) {
            $succ = 'Result has been disapproved. Course Master can now edit scores.';
            $showModalS = 'true';
            $pps = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM allocatecourse WHERE coursecord='Yes' AND coursecode = '$ccode'"));
            $code = $pps['coursecode'];
            $stuid = $pps['staffid'];
            $pp = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM people WHERE staffid ='$stuid'"));
            $unmail = $pp['uniemail'];
            hodmails($con, $unmail, $ccode, $ses, $host, $pass, $user, $mail);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlentities($orgname); ?> - Result Approval Dashboard</title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/receipt.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <link rel="stylesheet" href="css/print.css" media="print">
    <style type="text/css">
        /* Modern Dashboard Styling */
        :root {
            --primary-color: #3498db;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Dashboard Stats Cards */
        .stats-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: var(--dark-color);
        }
        
        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .stat-card-primary .stat-icon { color: var(--primary-color); }
        .stat-card-success .stat-icon { color: var(--success-color); }
        .stat-card-warning .stat-icon { color: var(--warning-color); }
        .stat-card-info .stat-icon { color: var(--info-color); }
        
        /* Table Styling */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        .data-table thead th {
            background: linear-gradient(135deg, var(--dark-color) 0%, #34495e 100%);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        .data-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s ease;
        }
        
        .data-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .data-table td {
            padding: 12px;
            vertical-align: middle;
        }
        
        /* Badge Styling */
        .badge-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-submitted {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-pending {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Button Styling */
        .btn-action {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 2px;
        }
        
        .btn-view {
            background: var(--info-color);
            color: white;
        }
        
        .btn-view:hover {
            background: #138496;
            transform: scale(1.05);
        }
        
        .btn-approve {
            background: var(--success-color);
            color: white;
        }
        
        .btn-approve:hover {
            background: #1e7e34;
            transform: scale(1.05);
        }
        
        .btn-disapprove {
            background: var(--danger-color);
            color: white;
        }
        
        .btn-disapprove:hover {
            background: #c82333;
            transform: scale(1.05);
        }
        
        /* Modal Styling */
        .modal-content {
            border-radius: 10px;
            border: none;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--dark-color) 0%, #34495e 100%);
            color: white;
            border-radius: 10px 10px 0 0;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .close {
            color: white;
            opacity: 0.8;
        }
        
        .close:hover {
            color: white;
            opacity: 1;
        }
        
        /* Result Details Table */
        .result-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .result-details-table th,
        .result-details-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        
        .result-details-table th {
            background: var(--light-color);
            font-weight: 600;
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0,0,0,0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 0.8s ease infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
        }
        
        .empty-state i {
            font-size: 60px;
            margin-bottom: 15px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .stats-dashboard {
                grid-template-columns: repeat(2, 1fr);
            }
            .data-table {
                font-size: 12px;
            }
            .btn-action {
                padding: 4px 8px;
                font-size: 11px;
            }
        }
        
        @media (max-width: 480px) {
            .stats-dashboard {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease;
        }
        
        /* Search Box */
        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        
        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        /* Action Buttons Container */
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        /* Footer Buttons */
        .modal-footer-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 15px;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php'); ?> 
        
        <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">
                <!-- ========== LEFT SIDEBAR ========== -->
                <?php
                $paycr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM examoff WHERE staffid='".$_SESSION['staffid']."' AND acadsession='$ses' "));
                    if ($paycr['deptm']=$pro) {
                        
                         include('includes/exambar.php');
                    }
                ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-12">
                                <h2 class="title">
                                    <i class="fa fa-check-circle"></i> Department Result Approval Dashboard
                                </h2>
                                <p class="subtitle">Review and approve submitted results from course lecturers</p>
                            </div>
                        </div>
                    </div>
                     <?php 
                         $paycr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM examoff WHERE staffid='".$_SESSION['staffid']."' AND acadsession='$ses' "));
                    if ($paycr['deptm']=$pro) {

                        ?>
                    <div class="container-fluid">
                        <?php
                        // Get statistics for dashboard
                        $totalSubmitted = 0;
                        $totalApproved = 0;
                        $totalPending = 0;
                        
                        $staffQuery = "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid WHERE staffaculty.deptm='$depn' AND people.status='active' ORDER BY staffaculty.staffid";
                        $staffResult = mysqli_query($con, $staffQuery);
                        $staffMembers = mysqli_fetch_all($staffResult, MYSQLI_ASSOC);
                        
                        foreach($staffMembers as $staff) {
                            $staffid = $staff['staffid'];
                            $courseQuery = "SELECT * FROM allocatecourse WHERE resultsatus='submitted' AND coursecord='Yes' AND staffid='$staffid' ORDER BY acadsession";
                            $courseResult = mysqli_query($con, $courseQuery);
                            $submittedCount = mysqli_num_rows($courseResult);
                            $totalSubmitted += $submittedCount;
                        }
                        ?>
                        
                        <!-- Statistics Dashboard -->
                        <div class="stats-dashboard">
                            <div class="stat-card stat-card-primary">
                                <div class="stat-icon"><i class="fa fa-book"></i></div>
                                <div class="stat-number"><?php echo $totalSubmitted; ?></div>
                                <div class="stat-label">Pending Approval</div>
                            </div>
                            <div class="stat-card stat-card-success">
                                <div class="stat-icon"><i class="fa fa-check-circle"></i></div>
                                <div class="stat-number"><?php echo $totalApproved; ?></div>
                                <div class="stat-label">Approved Results</div>
                            </div>
                            <div class="stat-card stat-card-warning">
                                <div class="stat-icon"><i class="fa fa-clock-o"></i></div>
                                <div class="stat-number"><?php echo $totalPending; ?></div>
                                <div class="stat-label">In Progress</div>
                            </div>
                            <div class="stat-card stat-card-info">
                                <div class="stat-icon"><i class="fa fa-users"></i></div>
                                <div class="stat-number"><?php echo count($staffMembers); ?></div>
                                <div class="stat-label">Active Lecturers</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-container">
                                    <div class="search-box">
                                        <input type="text" id="searchInput" class="search-input" placeholder="🔍 Search by course code, session, or lecturer...">
                                        <button class="btn btn-primary" onclick="clearSearch()">Clear</button>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="data-table" id="resultTable">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="15%">Session</th>
                                                    <th width="15%">Course Code</th>
                                                    <th width="15%">Lecturer</th>
                                                    <th width="15%">Status</th>
                                                    <th width="20%">Submitted Date</th>
                                                    <th width="15%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cnt = 0;
                                                foreach($staffMembers as $staff) {
                                                    $staffid = $staff['staffid'];
                                                    $lecturerName = $staff['firstname'] . ' ' . $staff['lastname'];
                                                    $sql = "SELECT * FROM allocatecourse WHERE resultsatus='submitted' AND coursecord='Yes' AND staffid='$staffid' ORDER BY acadsession DESC";
                                                    $query = mysqli_query($con, $sql);
                                                    $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                    
                                                    foreach($results as $result) {
                                                        $cnt++;
                                                ?>
                                                <tr class="fade-in">
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td>
                                                        <strong><?php echo htmlentities($result['acadsession']); ?></strong>
                                                        <br><small class="text-muted">Semester: <?php echo htmlentities($result['semester']); ?></small>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlentities($result['coursecode']); ?></strong>
                                                    </td>
                                                    <td><?php echo htmlentities($lecturerName); ?></td>
                                                    <td>
                                                        <span class="badge-status badge-submitted">
                                                            <i class="fa fa-clock-o"></i> <?php echo htmlentities($result['resultsatus']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small><?php echo isset($result['submitted_date']) ? date('d M Y, h:i A', strtotime($result['submitted_date'])) : 'Not specified'; ?></small>
                                                    </td>
                                                    <td>
                                                       
                                                           
<button class="btn-action btn-view" onclick="debugAndView('<?php echo $result['coursecode']; ?>', '<?php echo $result['acadsession']; ?>')">
    <i class="fa fa-eye"></i> View
</button>                                                    </td>
                                                </tr>
                                                <?php 
                                                    }
                                                }
                                                if($cnt == 0) {
                                                ?>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="empty-state">
                                                            <i class="fa fa-inbox"></i>
                                                            <h4>No Pending Results</h4>
                                                            <p>All submitted results have been processed. Check back later for new submissions.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    
                    }else{
                    ?><div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        //}
                      }
                    
                    ?>  
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="viewModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Result Details</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        
                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_details">

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                    <form method="post">
<table>
                                <tr>
                                    
                         </tr>
                         </table>
                           
                        </div>
                    </div>
                </div>
            </div>
    <!-- View Result Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-file-text-o"></i> Result Details
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="teacher_details">
                    <div class="text-center">
                        <div class="loading-spinner"></div>
                        <p>Loading result details...</p>
                    </div>
                </div>
                <div class="modal-footer-buttons">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade" id="checkModalS" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h5 class="modal-title">
                        <i class="fa fa-check-circle"></i> Success
                    </h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-check-circle" style="font-size: 60px; color: #28a745;"></i>
                        <p style="margin-top: 15px; font-size: 16px;">Successful</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" data-dismiss="modal">
                        <i class="fa fa-check"></i> OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="checkModalEr" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                    <h5 class="modal-title">
                        <i class="fa fa-exclamation-triangle"></i> Error
                    </h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-exclamation-circle" style="font-size: 60px; color: #dc3545;"></i>
                        <p style="margin-top: 15px; font-size: 16px;"><?php echo htmlentities($error); ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ========== COMMON JS FILES ========== -->
    <?php include 'includes/buton.php'; ?>
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-1.11.1.js"></script>
<script src="js/bootstrap.js"></script>
<script>
        $(document).ready(function() {
            <?php if(!empty($showModalS)) { ?>
                $('#checkModalS').modal('show');
            <?php } ?>
            <?php if(!empty($showModalEr)) { ?>
                $('#checkModalEr').modal('show');
            <?php } ?>
        });
        
        // Search functionality
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#resultTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
        
        function clearSearch() {
            $('#searchInput').val('');
            $('#resultTable tbody tr').show();
        }
        
        // Auto-refresh every 30 seconds (optional)
        // setInterval(function() {
        //     location.reload();
        // }, 30000);
    </script>
   
    <script>
    $(document).ready(function() {
        <?php if(!empty($showModalS)) { ?>
            $('#checkModalS').modal('show');
        <?php } ?>
        <?php if(!empty($showModalEr)) { ?>
            $('#checkModalEr').modal('show');
        <?php } ?>
    });
    
    function viewResult(courseCode, session) {
        // Show loading state
        $('#teacher_details').html(`
            <div class="text-center" style="padding: 40px;">
                <div class="loading-spinner" style="width: 40px; height: 40px; border-width: 4px;"></div>
                <p style="margin-top: 15px;">Loading result details for course: <strong>` + courseCode + `</strong>...</p>
            </div>
        `);
        
        // Make AJAX request to the correct path
        $.ajax({
            url: 'resultap',  // Added .php extension
            type: 'POST',
            data: { 
                ssid: courseCode, 
                sess: session,
                ajax: true 
            },
            timeout: 30000, // 30 second timeout
            success: function(response) {
                if(response.trim() === '') {
                    $('#teacher_details').html(`
                        <div class="text-center text-danger" style="padding: 40px;">
                            <i class="fa fa-exclamation-circle" style="font-size: 48px;"></i>
                            <p style="margin-top: 15px;">Empty response received. Please check if the result data exists.</p>
                        </div>
                    `);
                } else {
                    $('#teacher_details').html(response);
                }
                $('#viewModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.error("Response Text:", xhr.responseText);
                $('#teacher_details').html(`
                    <div class="text-center text-danger" style="padding: 40px;">
                        <i class="fa fa-exclamation-circle" style="font-size: 48px;"></i>
                        <p style="margin-top: 15px;"><strong>Error loading result details!</strong></p>
                        <p>Status: ` + status + `<br>Error: ` + error + `</p>
                        <p>Please ensure the resultap.php file exists in the correct directory.</p>
                        <button class="btn btn-primary mt-3" onclick="checkFile()">Check File</button>
                    </div>
                `);
                $('#viewModal').modal('show');
            }
        });
    }
    
    function checkFile() {
        $.ajax({
            url: 'resultap',
            type: 'HEAD',
            success: function() {
                alert('resultap.php file exists and is accessible.');
            },
            error: function() {
                alert('resultap.php file NOT found! Please create the file in the correct directory.');
            }
        });
    }
    
    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#resultTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    function clearSearch() {
        $('#searchInput').val('');
        $('#resultTable tbody tr').show();
    }
    function debugAndView(courseCode, session) {
    console.log("View button clicked");
    console.log("Course Code:", courseCode);
    console.log("Session:", session);
    
    // Test if AJAX is working
    $.ajax({
        url: 'resultap.php',
        type: 'POST',
        data: { test: true },
        success: function() {
            console.log("resultap.php is accessible");
            viewResult(courseCode, session);
        },
        error: function(xhr, status, error) {
            console.error("resultap.php NOT accessible:", error);
            alert("Error: resultap.php file not found or not accessible.\n\nPlease ensure the file exists in: " + window.location.pathname);
        }
    });
}
</script>
</body>
</html>

<?php 
}

function hodmails($con, $unmail, $ccode, $ses, $host, $pass, $user, $mail) {
    $rows = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM allocatecourse WHERE allocatecourse.coursecode='$ccode'"));
    $to = $unmail ?: 'imarve.wiz@gmail.com';
    
    try {
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->SMTPKeepAlive = true;
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom($user, 'Result Disapproval Notification');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Result Disapproval Notification - ' . $ccode;
        
        $message = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { padding: 20px; }
                .header { background: #dc3545; color: white; padding: 15px; text-align: center; }
                .content { padding: 20px; }
                .footer { background: #f8f9fa; padding: 10px; text-align: center; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background: #f2f2f2; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h3>Result Disapproval Notification</h3>
                </div>
                <div class="content">
                    <p>Dear Course Lecturer,</p>
                    <p>This is to notify you that the results for the following course have been <strong style="color: #dc3545;">DISAPPROVED</strong> by the Head of Department.</p>
                    
                    <table>
                        <tr><th>Course Code</th><td>' . htmlspecialchars($ccode) . '</td></tr>
                        <tr><th>Session</th><td>' . htmlspecialchars($ses) . '</td></tr>
                    </table>
                    
                    <p><strong>Action Required:</strong> Please review and edit the scores as necessary, then resubmit for approval.</p>
                    
                    <p>Thank you for your cooperation.</p>
                    
                    <hr>
                    <p><small>This is an automated message from the Result Management System. Please do not reply to this email.</small></p>
                </div>
                <div class="footer">
                    &copy; ' . date('Y') . ' Result Management System
                </div>
            </div>
        </body>
        </html>';
        
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAllRecipients();
    } catch (Exception $e) {
        // Log error silently
        error_log("Email sending failed: " . $e->getMessage());
    }
}
?>