<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';
require "phpmailer/class.phpmailer.php";
$mail = new PHPMailer();

if(strlen($_SESSION['staffid'])=="") {   
    header("Location: ../logout.php"); 
    exit();
} else {
    $now = time();
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location:../logout.php");
        exit();
    }
    
    $factn = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM faculty WHERE fname='$fac' "));
    $fact = $factn['fid'];
    $depna = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM department WHERE dept='$dt' "));
    $depn = $depna['deptid'];
    $error = '';
    $succ = '';
    $dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT semester,session FROM resultsession WHERE description='exam processing'"));
    $sems = $dat['semester'];
    $ses = $dat['session'];

    // Handle AJAX request for viewing results
    if (isset($_POST['ajax_view_result'])) {
        $ccode = mysqli_real_escape_string($con, $_POST['ccode']);
        $session = mysqli_real_escape_string($con, $_POST['session']);
        
        $query = "SELECT r.*, s.firstname, s.lastname, s.othername, s.studentid as reg_no,
                  c.title as course_title, c.unit
                  FROM result r
                  LEFT JOIN student s ON r.studentid = s.studentid
                  LEFT JOIN course c ON r.coursecode = c.coursecode
                  WHERE r.coursecode = '$ccode' AND r.acadsession = '$session'
                  ORDER BY s.lastname ASC";
        
        $result = mysqli_query($con, $query);
        $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        if (count($students) > 0) {
            $courseInfo = $students[0];
            ?>
            <div class="result-view-container">
                <div class="course-info" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <h5 style="margin-bottom: 10px;">Course Information</h5>
                    <table style="width: 100%;">
                        <tr><td width="30%"><strong>Course Code:</strong></td><td><?php echo htmlentities($ccode); ?></td></tr>
                        <tr><td><strong>Course Title:</strong></td><td><?php echo htmlentities($courseInfo['course_title'] ?? 'N/A'); ?></td></tr>
                        <tr><td><strong>Academic Session:</strong></td><td><?php echo htmlentities($session); ?></td></tr>
                        <tr><td><strong>Semester:</strong></td><td><?php echo htmlentities($sems); ?></td></tr>
                        <tr><td><strong>Course Unit:</strong></td><td><?php echo htmlentities($courseInfo['unit'] ?? 'N/A'); ?></td></tr>
                    </table>
                </div>
                
                <h5>Student Results</h5>
                <div class="table-responsive">
                    <table class="result-details-table">
                        <thead>
                            <tr>
                                <th>S/N</th><th>Matric Number</th><th>Student Name</th>
                                <th>CA Score</th><th>Exam Score</th><th>Total Score</th><th>Grade</th><th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sn = 1;
                            foreach($students as $student) {
                                $totalScore = (int)$student['ca'] + (int)$student['exam'];
                                $grade = getGrade($con, $totalScore);
                                $status = ($totalScore >= 40) ? 'Pass' : 'Fail';
                                ?>
                                <tr>
                                    <td><?php echo $sn++; ?></td>
                                    <td><?php echo htmlentities($student['reg_no']); ?></td>
                                    <td><?php echo htmlentities(ucwords($student['lastname'] . ' ' . $student['firstname'] . ' ' . $student['othername'])); ?></td>
                                    <td><?php echo htmlentities($student['ca']); ?></td>
                                    <td><?php echo htmlentities($student['exam']); ?></td>
                                    <td><strong><?php echo $totalScore; ?></strong></td>
                                    <td><span class="badge-status <?php echo ($grade == 'F') ? 'badge-danger' : 'badge-success'; ?>"><?php echo $grade; ?></span></td>
                                    <td><span class="badge-status <?php echo ($status == 'Pass') ? 'badge-approved' : 'badge-pending'; ?>"><?php echo $status; ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            echo '<div class="alert alert-danger">No results found for this course.</div>';
        }
        exit();
    }

    // Handle Approve
    if (isset($_POST['approve'])) {
        $ccode = mysqli_real_escape_string($con, $_POST['ccode']);
        $session = mysqli_real_escape_string($con, $_POST['ses']);
        $tod = date("Y-m-d H:i:s");
        $update = mysqli_query($con, "UPDATE allocatecourse SET resultsatus='Approved', appdate='$tod' WHERE coursecord='YES' AND acadsession='$session' AND coursecode = '$ccode'");
        if ($update) {
            $updates = mysqli_query($con, "UPDATE result SET status='Y' WHERE acadsession='$session' AND coursecode = '$ccode'");
            $succ = 'Result Approval Successful';
            $showModalS = true;
        } else {
            $error = 'An Error Occurred While Confirming Data. Please Try Again!!!';
            $showModalEr = true;
        }
    }
    
    // Handle Disapprove
    if (isset($_POST['disapproved'])) {
        $ccode = mysqli_real_escape_string($con, $_POST['ccode']);
        $session = mysqli_real_escape_string($con, $_POST['ses']);
        $upda = mysqli_query($con, "UPDATE allocatecourse SET resultsatus='' WHERE coursecord='YES' AND acadsession='$session' AND coursecode = '$ccode'");
        if ($upda) {
            $succ = 'Result has been disapproved. Course Master can now edit scores.';
            $showModalS = true;
            $pps = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM allocatecourse WHERE coursecord='Yes' AND coursecode = '$ccode' AND acadsession='$session'"));
            $stuid = $pps['staffid'];
            $pp = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM people WHERE staffid ='$stuid'"));
            $unmail = $pp['uniemail'];
            hodmails($con, $unmail, $ccode, $session, $host, $pass, $user, $mail);
        }
    }

    // Handle Revert to Pending (for approved courses)
    if (isset($_POST['revert'])) {
        $ccode = mysqli_real_escape_string($con, $_POST['ccode']);
        $session = mysqli_real_escape_string($con, $_POST['ses']);
        $upda = mysqli_query($con, "UPDATE allocatecourse SET resultsatus='submitted' WHERE coursecord='YES' AND acadsession='$session' AND coursecode = '$ccode'");
        if ($upda) {
            $updateresult = mysqli_query($con, "UPDATE result SET status='Pending' WHERE acadsession='$session' AND coursecode = '$ccode'");
            $succ = 'Result has been reverted to pending status.';
            $showModalS = true;
        } else {
            $error = 'Error reverting result status';
            $showModalEr = true;
        }
    }

    function getGrade($con, $score) {
        $query = "SELECT grade FROM gradetable WHERE minscore <= $score AND maxscore >= $score LIMIT 1";
        $result = mysqli_query($con, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['grade'];
        }
        return 'F';
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
    <link rel="stylesheet" href="css/print.css" media="screen">
    <style type="text/css">
        :root {
            --primary-color: #3498db;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }
        
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Tabs Styles */
        .tabs-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .tabs-header {
            display: flex;
            border-bottom: 2px solid #e9ecef;
            background: #f8f9fa;
        }
        
        .tab-btn {
            flex: 1;
            padding: 15px 20px;
            background: transparent;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
            position: relative;
        }
        
        .tab-btn:hover {
            background: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
        }
        
        .tab-btn.active {
            color: var(--primary-color);
        }
        
        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-color);
        }
        
        .tab-btn i {
            margin-right: 8px;
        }
        
        .tab-count {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 8px;
        }
        
        .tab-count.pending { background: #ffc107; color: #856404; }
        .tab-count.approved { background: #28a745; color: white; }
        .tab-count.all { background: var(--primary-color); color: white; }
        
        .tab-pane {
            display: none;
            padding: 20px;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
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
            transition: transform 0.3s ease;
            text-align: center;
        }
        
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon { font-size: 40px; margin-bottom: 10px; }
        .stat-number { font-size: 32px; font-weight: bold; color: var(--dark-color); }
        .stat-label { font-size: 14px; color: #7f8c8d; margin-top: 5px; }
        .stat-card-primary .stat-icon { color: var(--primary-color); }
        .stat-card-success .stat-icon { color: var(--success-color); }
        .stat-card-warning .stat-icon { color: var(--warning-color); }
        .stat-card-info .stat-icon { color: var(--info-color); }
        
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
        
        .data-table tbody tr:hover { background: #f8f9fa; }
        .data-table td { padding: 12px; vertical-align: middle; }
        
        .badge-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-submitted { background: #fff3cd; color: #856404; }
        .badge-approved { background: #d4edda; color: #155724; }
        .badge-pending { background: #cce5ff; color: #004085; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-success { background: #d4edda; color: #155724; }
        
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
        
        .btn-view { background: var(--info-color); color: white; }
        .btn-view:hover { background: #138496; transform: scale(1.05); }
        .btn-approve { background: var(--success-color); color: white; }
        .btn-approve:hover { background: #1e7e34; transform: scale(1.05); }
        .btn-disapprove { background: var(--danger-color); color: white; }
        .btn-disapprove:hover { background: #c82333; transform: scale(1.05); }
        .btn-revert { background: var(--warning-color); color: #856404; }
        .btn-revert:hover { background: #e0a800; color: white; }
        
        .modal-content { border-radius: 10px; border: none; }
        .modal-header {
            background: linear-gradient(135deg, var(--dark-color) 0%, #34495e 100%);
            color: white;
            border-radius: 10px 10px 0 0;
        }
        
        .result-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .result-details-table th, .result-details-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        
        .result-details-table th { background: var(--light-color); font-weight: 600; }
        
        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0,0,0,0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 0.8s ease infinite;
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
        }
        
        .empty-state i { font-size: 60px; margin-bottom: 15px; }
        
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
        
        .action-buttons { display: flex; gap: 5px; flex-wrap: wrap; }
        .filter-section {
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .filter-select {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .stats-dashboard { grid-template-columns: repeat(2, 1fr); }
            .data-table { font-size: 12px; }
            .tab-btn { font-size: 12px; padding: 10px; }
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?> 
        
        <div class="content-wrapper">
            <div class="content-container">
                <?php
                    $paych = mysqli_fetch_assoc(mysqli_query($con, "SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                    if ($paych['position'] == "superadmin") {
                        include('includes/supbar.php');
                    } elseif ($paych['position'] == "subadmin") {
                        include('includes/subbar.php');
                    } else {
                        include('includes/leftbar.php');
                    }
                ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-12">
                                <h2 class="title"><i class="fa fa-check-circle"></i> Result Approval Dashboard</h2>
                                <p class="subtitle">Review, approve, and manage course results</p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($paych['position'] == "superadmin" OR $paych['position'] == "subadmin") { ?>
                    <div class="container-fluid">
                        <?php
                        // Get counts for tabs
                        $pendingCountQuery = "SELECT COUNT(DISTINCT CONCAT(coursecode, acadsession)) as count 
                                              FROM allocatecourse 
                                              WHERE resultsatus = 'submitted' AND coursecord = 'YES'";
                        $pendingCountResult = mysqli_fetch_assoc(mysqli_query($con, $pendingCountQuery));
                        $totalPending = $pendingCountResult['count'];
                        
                        $approvedCountQuery = "SELECT COUNT(DISTINCT CONCAT(coursecode, acadsession)) as count 
                                               FROM allocatecourse 
                                               WHERE resultsatus = 'Approved' AND coursecord = 'YES'";
                        $approvedCountResult = mysqli_fetch_assoc(mysqli_query($con, $approvedCountQuery));
                        $totalApproved = $approvedCountResult['count'];
                        
                        $allCountQuery = "SELECT COUNT(DISTINCT CONCAT(coursecode, acadsession)) as count 
                                         FROM allocatecourse 
                                         WHERE coursecord = 'YES'";
                        $allCountResult = mysqli_fetch_assoc(mysqli_query($con, $allCountQuery));
                        $totalAll = $allCountResult['count'];
                        ?>
                        
                        <!-- Statistics Dashboard -->
                        <div class="stats-dashboard">
                            <div class="stat-card stat-card-warning">
                                <div class="stat-icon"><i class="fa fa-clock-o"></i></div>
                                <div class="stat-number"><?php echo $totalPending; ?></div>
                                <div class="stat-label">Pending Approval</div>
                            </div>
                            <div class="stat-card stat-card-success">
                                <div class="stat-icon"><i class="fa fa-check-circle"></i></div>
                                <div class="stat-number"><?php echo $totalApproved; ?></div>
                                <div class="stat-label">Approved Results</div>
                            </div>
                            <div class="stat-card stat-card-primary">
                                <div class="stat-icon"><i class="fa fa-book"></i></div>
                                <div class="stat-number"><?php echo $totalAll; ?></div>
                                <div class="stat-label">Total Courses</div>
                            </div>
                        </div>
                        
                        <!-- Tabs Section -->
                        <div class="tabs-container">
                            <div class="tabs-header">
                                <button class="tab-btn active" onclick="switchTab('pending')">
                                    <i class="fa fa-clock-o"></i> Pending Approval
                                    <span class="tab-count pending"><?php echo $totalPending; ?></span>
                                </button>
                                <button class="tab-btn" onclick="switchTab('approved')">
                                    <i class="fa fa-check-circle"></i> Approved Results
                                    <span class="tab-count approved"><?php echo $totalApproved; ?></span>
                                </button>
                                <button class="tab-btn" onclick="switchTab('all')">
                                    <i class="fa fa-list"></i> All Results
                                    <span class="tab-count all"><?php echo $totalAll; ?></span>
                                </button>
                            </div>
                            
                            <!-- Pending Tab -->
                            <div id="pendingTab" class="tab-pane active">
                                <div class="filter-section">
                                    <select id="pendingSessionFilter" class="filter-select">
                                        <option value="">All Sessions</option>
                                        <?php
                                        $sessionsQuery = "SELECT DISTINCT acadsession FROM allocatecourse WHERE coursecord='YES' ORDER BY acadsession DESC";
                                        $sessionsResult = mysqli_query($con, $sessionsQuery);
                                        while($sessionRow = mysqli_fetch_assoc($sessionsResult)) {
                                            echo '<option value="' . htmlentities($sessionRow['acadsession']) . '">' . htmlentities($sessionRow['acadsession']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="search-box">
                                    <input type="text" id="searchPending" class="search-input" placeholder="🔍 Search by course code, course title, or lecturer...">
                                    <button class="btn btn-primary" onclick="clearSearch('pending')">Clear</button>
                                </div>
                                <div class="table-container">
                                    <table class="data-table" id="pendingTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th><th width="12%">Session</th><th width="15%">Course Code</th>
                                                <th width="25%">Course Title</th><th width="15%">Lecturer</th>
                                                <th width="8%">Unit</th><th width="10%">Submitted Date</th><th width="15%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $pendingQuery = "SELECT DISTINCT ac.coursecode, ac.acadsession, ac.semester, ac.resultsatus, ac.revisedate, ac.staffid, c.title as course_title, c.unit as course_unit
                                                            FROM allocatecourse ac LEFT JOIN course c ON ac.coursecode = c.coursecode
                                                            WHERE ac.resultsatus = 'submitted' AND ac.coursecord = 'YES'
                                                            ORDER BY ac.revisedate DESC, ac.acadsession DESC";
                                            $pendingResult = mysqli_query($con, $pendingQuery);
                                            $pendingCourses = mysqli_fetch_all($pendingResult, MYSQLI_ASSOC);
                                            
                                            if(count($pendingCourses) > 0) {
                                                $cnt = 1;
                                                foreach($pendingCourses as $course) {
                                                    $lecturer = mysqli_fetch_assoc(mysqli_query($con, "SELECT firstname, lastname, title FROM people WHERE staffid = '{$course['staffid']}'"));
                                                    $lecturerName = ($lecturer ? trim($lecturer['title'] . ' ' . $lecturer['firstname'] . ' ' . $lecturer['lastname']) : 'Unknown');
                                                    $submittedDate = !empty($course['revisedate']) ? date('d M Y, h:i A', strtotime($course['revisedate'])) : 'Not specified';
                                            ?>
                                            <tr data-session="<?php echo htmlentities($course['acadsession']); ?>">
                                                <td><?php echo $cnt++; ?></td>
                                                <td><strong><?php echo htmlentities($course['acadsession']); ?></strong><br><small>Sem: <?php echo htmlentities($course['semester']); ?></small></td>
                                                <td><strong><?php echo htmlentities($course['coursecode']); ?></strong></td>
                                                <td><?php echo htmlentities($course['course_title'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlentities($lecturerName); ?></td>
                                                <td><?php echo htmlentities($course['course_unit'] ?? 'N/A'); ?></td>
                                                <td><small><?php echo $submittedDate; ?></small></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn-action btn-view" onclick="viewResult('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')"><i class="fa fa-eye"></i> View</button>
                                                        <button class="btn-action btn-approve" onclick="confirmApprove('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')"><i class="fa fa-check"></i> Approve</button>
                                                        <button class="btn-action btn-disapprove" onclick="confirmDisapprove('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')"><i class="fa fa-times"></i> Disapprove</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } } else { ?>
                                            <tr><td colspan="8"><div class="empty-state"><i class="fa fa-inbox"></i><h4>No Pending Results</h4></div></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Approved Tab -->
                            <div id="approvedTab" class="tab-pane">
                                <div class="filter-section">
                                    <select id="approvedSessionFilter" class="filter-select">
                                        <option value="">All Sessions</option>
                                        <?php
                                        $sessionsResult = mysqli_query($con, $sessionsQuery);
                                        mysqli_data_seek($sessionsResult, 0);
                                        while($sessionRow = mysqli_fetch_assoc($sessionsResult)) {
                                            echo '<option value="' . htmlentities($sessionRow['acadsession']) . '">' . htmlentities($sessionRow['acadsession']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="search-box">
                                    <input type="text" id="searchApproved" class="search-input" placeholder="🔍 Search by course code, course title, or lecturer...">
                                    <button class="btn btn-primary" onclick="clearSearch('approved')">Clear</button>
                                </div>
                                <div class="table-container">
                                    <table class="data-table" id="approvedTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th><th width="12%">Session</th><th width="15%">Course Code</th>
                                                <th width="25%">Course Title</th><th width="15%">Lecturer</th>
                                                <th width="8%">Unit</th><th width="10%">Approved Date</th><th width="15%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $approvedQuery = "SELECT DISTINCT ac.coursecode, ac.acadsession, ac.semester, ac.resultsatus, ac.appdate, ac.staffid, c.title as course_title, c.unit as course_unit
                                                            FROM allocatecourse ac LEFT JOIN course c ON ac.coursecode = c.coursecode
                                                            WHERE ac.resultsatus = 'Approved' AND ac.coursecord = 'YES'
                                                            ORDER BY ac.appdate DESC, ac.acadsession DESC";
                                            $approvedResult = mysqli_query($con, $approvedQuery);
                                            $approvedCourses = mysqli_fetch_all($approvedResult, MYSQLI_ASSOC);
                                            
                                            if(count($approvedCourses) > 0) {
                                                $cnt = 1;
                                                foreach($approvedCourses as $course) {
                                                    $lecturer = mysqli_fetch_assoc(mysqli_query($con, "SELECT firstname, lastname, title FROM people WHERE staffid = '{$course['staffid']}'"));
                                                    $lecturerName = ($lecturer ? trim($lecturer['title'] . ' ' . $lecturer['firstname'] . ' ' . $lecturer['lastname']) : 'Unknown');
                                                    $approvedDate = !empty($course['appdate']) ? date('d M Y, h:i A', strtotime($course['appdate'])) : 'Not specified';
                                            ?>
                                            <tr data-session="<?php echo htmlentities($course['acadsession']); ?>">
                                                <td><?php echo $cnt++; ?></td>
                                                <td><strong><?php echo htmlentities($course['acadsession']); ?></strong><br><small>Sem: <?php echo htmlentities($course['semester']); ?></small></td>
                                                <td><strong><?php echo htmlentities($course['coursecode']); ?></strong></td>
                                                <td><?php echo htmlentities($course['course_title'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlentities($lecturerName); ?></td>
                                                <td><?php echo htmlentities($course['course_unit'] ?? 'N/A'); ?></td>
                                                <td><small><?php echo $approvedDate; ?></small></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn-action btn-view" onclick="viewResult('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')"><i class="fa fa-eye"></i> View</button>
                                                        <button class="btn-action btn-revert" onclick="confirmRevert('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')"><i class="fa fa-undo"></i> Revert</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } } else { ?>
                                            <tr><td colspan="8"><div class="empty-state"><i class="fa fa-check-circle"></i><h4>No Approved Results</h4></div></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- All Results Tab -->
                            <div id="allTab" class="tab-pane">
                                <div class="filter-section">
                                    <select id="allSessionFilter" class="filter-select">
                                        <option value="">All Sessions</option>
                                        <?php
                                        $sessionsResult = mysqli_query($con, $sessionsQuery);
                                        mysqli_data_seek($sessionsResult, 0);
                                        while($sessionRow = mysqli_fetch_assoc($sessionsResult)) {
                                            echo '<option value="' . htmlentities($sessionRow['acadsession']) . '">' . htmlentities($sessionRow['acadsession']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <select id="allStatusFilter" class="filter-select">
                                        <option value="">All Status</option>
                                        <option value="submitted">Pending Approval</option>
                                        <option value="Approved">Approved</option>
                                        <option value="">In Progress</option>
                                    </select>
                                </div>
                                <div class="search-box">
                                    <input type="text" id="searchAll" class="search-input" placeholder="🔍 Search by course code, course title, or lecturer...">
                                    <button class="btn btn-primary" onclick="clearSearch('all')">Clear</button>
                                </div>
                                <div class="table-container">
                                    <table class="data-table" id="allTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th><th width="12%">Session</th><th width="15%">Course Code</th>
                                                <th width="25%">Course Title</th><th width="15%">Lecturer</th>
                                                <th width="10%">Status</th><th width="8%">Unit</th>
                                                <th width="10%">Date</th><th width="15%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $allQuery = "SELECT DISTINCT ac.coursecode, ac.acadsession, ac.semester, ac.resultsatus, ac.revisedate, ac.appdate, ac.staffid, c.title as course_title, c.unit as course_unit
                                                        FROM allocatecourse ac LEFT JOIN course c ON ac.coursecode = c.coursecode
                                                        WHERE ac.coursecord = 'YES'
                                                        ORDER BY ac.acadsession DESC, ac.coursecode ASC";
                                            $allResult = mysqli_query($con, $allQuery);
                                            $allCourses = mysqli_fetch_all($allResult, MYSQLI_ASSOC);
                                            
                                            if(count($allCourses) > 0) {
                                                $cnt = 1;
                                                foreach($allCourses as $course) {
                                                    $lecturer = mysqli_fetch_assoc(mysqli_query($con, "SELECT firstname, lastname, title FROM people WHERE staffid = '{$course['staffid']}'"));
                                                    $lecturerName = ($lecturer ? trim($lecturer['title'] . ' ' . $lecturer['firstname'] . ' ' . $lecturer['lastname']) : 'Unknown');
                                                    
                                                    $statusClass = 'badge-submitted';
                                                    $statusText = ucfirst($course['resultsatus'] ?: 'Pending');
                                                    if($course['resultsatus'] == 'Approved') {
                                                        $statusClass = 'badge-approved';
                                                    } elseif(empty($course['resultsatus'])) {
                                                        $statusClass = 'badge-pending';
                                                    }
                                                    
                                                    $displayDate = !empty($course['appdate']) ? date('d M Y', strtotime($course['appdate'])) : 
                                                                  (!empty($course['revisedate']) ? date('d M Y', strtotime($course['revisedate'])) : 'N/A');
                                            ?>
                                            <tr data-session="<?php echo htmlentities($course['acadsession']); ?>" data-status="<?php echo htmlentities($course['resultsatus']); ?>">
                                                <td><?php echo $cnt++; ?></td>
                                                <td><strong><?php echo htmlentities($course['acadsession']); ?></strong><br><small>Sem: <?php echo htmlentities($course['semester']); ?></small></td>
                                                <td><strong><?php echo htmlentities($course['coursecode']); ?></strong></td>
                                                <td><?php echo htmlentities($course['course_title'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlentities($lecturerName); ?></td>
                                                <td><span class="badge-status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                                                <td><?php echo htmlentities($course['course_unit'] ?? 'N/A'); ?></td>
                                                <td><small><?php echo $displayDate; ?></small></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn-action btn-view" onclick="viewResult('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')"><i class="fa fa-eye"></i> View</button>
                                                        <?php if($course['resultsatus'] != 'Approved') { ?>
                                                            <button class="btn-action btn-approve" onclick="confirmApprove('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')"><i class="fa fa-check"></i> Approve</button>
                                                            <button class="btn-action btn-disapprove" onclick="confirmDisapprove('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')"><i class="fa fa-times"></i> Disapprove</button>
                                                        <?php } else { ?>
                                                            <button class="btn-action btn-revert" onclick="confirmRevert('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')"><i class="fa fa-undo"></i> Revert</button>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } } else { ?>
                                            <tr><td colspan="9"><div class="empty-state"><i class="fa fa-inbox"></i><h4>No Results Found</h4></div></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php } else { ?>
                    <div class="container-fluid">                           
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">    
                                    <div class="panel-body">
                                        <p style="color:red;">Sorry Dear: <b style="color:blue;"><?php echo htmlentities($fullname); ?></b> You Don't Have Access to this Page. Thank You.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Result Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-file-text-o"></i> Result Details</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="teacher_details">
                    <div class="text-center"><div class="loading-spinner"></div><p>Loading result details...</p></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h5 class="modal-title"><i class="fa fa-check-circle"></i> Confirm Approval</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <p>Are you sure you want to approve this result?</p>
                        <p><strong>Course Code:</strong> <span id="approveCourseCode"></span></p>
                        <p><strong>Session:</strong> <span id="approveSession"></span></p>
                        <p class="text-success"><small>Once approved, the result will be published and cannot be edited by the lecturer.</small></p>
                        <input type="hidden" name="ccode" id="approveCcode">
                        <input type="hidden" name="ses" id="approveSes">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="approve" class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Disapprove Modal -->
    <div class="modal fade" id="disapproveModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Confirm Disapproval</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <p>Are you sure you want to <strong class="text-danger">DISAPPROVE</strong> this result?</p>
                        <p>This will allow the lecturer to edit the scores.</p>
                        <p><strong>Course Code:</strong> <span id="disapproveCourseCode"></span></p>
                        <p><strong>Session:</strong> <span id="disapproveSession"></span></p>
                        <input type="hidden" name="ccode" id="disapproveCcode">
                        <input type="hidden" name="ses" id="disapproveSes">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="disapproved" class="btn btn-danger">Disapprove</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Revert Modal -->
    <div class="modal fade" id="revertModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                    <h5 class="modal-title"><i class="fa fa-undo"></i> Confirm Revert</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <p>Are you sure you want to revert this result to <strong class="text-warning">PENDING</strong> status?</p>
                        <p>This will allow the lecturer to edit the scores again.</p>
                        <p><strong>Course Code:</strong> <span id="revertCourseCode"></span></p>
                        <p><strong>Session:</strong> <span id="revertSession"></span></p>
                        <input type="hidden" name="ccode" id="revertCcode">
                        <input type="hidden" name="ses" id="revertSes">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="revert" class="btn btn-warning">Revert to Pending</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade" id="checkModalS" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h5 class="modal-title"><i class="fa fa-check-circle"></i> Success</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-check-circle" style="font-size: 60px; color: #28a745;"></i>
                        <p style="margin-top: 15px;"><?php echo $succ; ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" data-dismiss="modal" onclick="location.reload()">OK</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="checkModalEr" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Error</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-exclamation-circle" style="font-size: 60px; color: #dc3545;"></i>
                        <p style="margin-top: 15px;"><?php echo htmlentities($error); ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/buton.php'; ?>
    
    <script>
    function switchTab(tab) {
        // Hide all tabs
        document.getElementById('pendingTab').classList.remove('active');
        document.getElementById('approvedTab').classList.remove('active');
        document.getElementById('allTab').classList.remove('active');
        
        // Remove active class from all buttons
        let btns = document.querySelectorAll('.tab-btn');
        btns.forEach(btn => btn.classList.remove('active'));
        
        // Show selected tab
        if(tab === 'pending') {
            document.getElementById('pendingTab').classList.add('active');
            btns[0].classList.add('active');
        } else if(tab === 'approved') {
            document.getElementById('approvedTab').classList.add('active');
            btns[1].classList.add('active');
        } else {
            document.getElementById('allTab').classList.add('active');
            btns[2].classList.add('active');
        }
    }
    
    $(document).ready(function() {
        <?php if(!empty($showModalS)) { ?>
            $('#checkModalS').modal('show');
        <?php } ?>
        <?php if(!empty($showModalEr)) { ?>
            $('#checkModalEr').modal('show');
        <?php } ?>
        
        // Search and filter for Pending tab
        $('#searchPending, #pendingSessionFilter').on('keyup change', function() {
            var search = $('#searchPending').val().toLowerCase();
            var session = $('#pendingSessionFilter').val().toLowerCase();
            $('#pendingTable tbody tr').each(function() {
                var text = $(this).text().toLowerCase();
                var rowSession = $(this).data('session') + '';
                var show = (search === '' || text.indexOf(search) > -1) && (session === '' || rowSession.toLowerCase().indexOf(session) > -1);
                $(this).toggle(show);
            });
        });
        
        // Search and filter for Approved tab
        $('#searchApproved, #approvedSessionFilter').on('keyup change', function() {
            var search = $('#searchApproved').val().toLowerCase();
            var session = $('#approvedSessionFilter').val().toLowerCase();
            $('#approvedTable tbody tr').each(function() {
                var text = $(this).text().toLowerCase();
                var rowSession = $(this).data('session') + '';
                var show = (search === '' || text.indexOf(search) > -1) && (session === '' || rowSession.toLowerCase().indexOf(session) > -1);
                $(this).toggle(show);
            });
        });
        
        // Search and filter for All tab
        $('#searchAll, #allSessionFilter, #allStatusFilter').on('keyup change', function() {
            var search = $('#searchAll').val().toLowerCase();
            var session = $('#allSessionFilter').val().toLowerCase();
            var status = $('#allStatusFilter').val().toLowerCase();
            $('#allTable tbody tr').each(function() {
                var text = $(this).text().toLowerCase();
                var rowSession = $(this).data('session') + '';
                var rowStatus = $(this).data('status') + '';
                var showSession = session === '' || rowSession.toLowerCase().indexOf(session) > -1;
                var showStatus = status === '' || rowStatus.toLowerCase().indexOf(status) > -1;
                var showSearch = search === '' || text.indexOf(search) > -1;
                $(this).toggle(showSession && showStatus && showSearch);
            });
        });
    });
    
    function clearSearch(tab) {
        if(tab === 'pending') {
            $('#searchPending').val('');
            $('#pendingSessionFilter').val('');
            $('#pendingTable tbody tr').show();
        } else if(tab === 'approved') {
            $('#searchApproved').val('');
            $('#approvedSessionFilter').val('');
            $('#approvedTable tbody tr').show();
        } else {
            $('#searchAll').val('');
            $('#allSessionFilter').val('');
            $('#allStatusFilter').val('');
            $('#allTable tbody tr').show();
        }
    }
    
    function viewResult(courseCode, session) {
        $('#teacher_details').html(`
            <div class="text-center" style="padding: 40px;">
                <div class="loading-spinner" style="width: 40px; height: 40px; border-width: 4px;"></div>
                <p style="margin-top: 15px;">Loading result details for course: <strong>` + courseCode + `</strong>...</p>
            </div>
        `);
        
        $.ajax({
            url: 'resultap.php',
            type: 'POST',
            data: { 
                ssid: courseCode, 
                sess: session,
                ajax: true 
            },
            timeout: 30000,
            success: function(response) {
                if(response.trim() === '') {
                    $('#teacher_details').html(`
                        <div class="text-center text-danger" style="padding: 40px;">
                            <i class="fa fa-exclamation-circle" style="font-size: 48px;"></i>
                            <p style="margin-top: 15px;">No result data found for this course.</p>
                        </div>
                    `);
                } else {
                    $('#teacher_details').html(response);
                }
                $('#viewModal').modal('show');
            },
            error: function(xhr, status, error) {
                $('#teacher_details').html(`
                    <div class="text-center text-danger" style="padding: 40px;">
                        <i class="fa fa-exclamation-circle" style="font-size: 48px;"></i>
                        <p style="margin-top: 15px;"><strong>Error loading result details!</strong></p>
                        <p>Status: ` + status + `<br>Error: ` + error + `</p>
                    </div>
                `);
                $('#viewModal').modal('show');
            }
        });
    }
    
    function confirmApprove(courseCode, session) {
        $('#approveCourseCode').text(courseCode);
        $('#approveSession').text(session);
        $('#approveCcode').val(courseCode);
        $('#approveSes').val(session);
        $('#approveModal').modal('show');
    }
    
    function confirmDisapprove(courseCode, session) {
        $('#disapproveCourseCode').text(courseCode);
        $('#disapproveSession').text(session);
        $('#disapproveCcode').val(courseCode);
        $('#disapproveSes').val(session);
        $('#disapproveModal').modal('show');
    }
    
    function confirmRevert(courseCode, session) {
        $('#revertCourseCode').text(courseCode);
        $('#revertSession').text(session);
        $('#revertCcode').val(courseCode);
        $('#revertSes').val(session);
        $('#revertModal').modal('show');
    }
    </script>
</body>
</html>

<?php 
}

function hodmails($con, $unmail, $ccode, $ses, $host, $pass, $user, $mail) {
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
        <head><style>body{font-family:Arial;} .header{background:#dc3545;color:white;padding:15px;text-align:center;}</style></head>
        <body>
            <div class="header"><h3>Result Disapproval Notification</h3></div>
            <p>Dear Course Lecturer,</p>
            <p>Results for course <strong>' . htmlspecialchars($ccode) . '</strong> for session <strong>' . htmlspecialchars($ses) . '</strong> have been <strong style="color:#dc3545;">DISAPPROVED</strong>.</p>
            <p><strong>Action Required:</strong> Please review and edit the scores as necessary, then resubmit.</p>
            <hr><p><small>Automated message - Do not reply</small></p>
        </body>
        </html>';
        
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAllRecipients();
    } catch (Exception $e) {
        error_log("Email failed: " . $e->getMessage());
    }
}
?>