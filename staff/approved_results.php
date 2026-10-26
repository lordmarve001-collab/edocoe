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
    
    // Get filter parameters
    $selectedSession = isset($_GET['session']) ? mysqli_real_escape_string($con, $_GET['session']) : '';
    $selectedSemester = isset($_GET['semester']) ? mysqli_real_escape_string($con, $_GET['semester']) : '';
    $selectedProgramme = isset($_GET['programme']) ? mysqli_real_escape_string($con, $_GET['programme']) : '';
    $selectedLevel = isset($_GET['level']) ? mysqli_real_escape_string($con, $_GET['level']) : '';
    
    
function hodmails($con, $unmail, $ccode, $ses, $host, $pass, $user, $mail, $reason) {
    $to = $unmail ?: 'default@email.com';
    
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
                .reason-box { 
                    background: #fff3cd; 
                    border-left: 4px solid #ffc107; 
                    padding: 15px; 
                    margin: 15px 0; 
                    border-radius: 5px;
                }
                .reason-title { font-weight: bold; margin-bottom: 10px; color: #856404; }
                .reason-text { color: #333; line-height: 1.5; }
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
                    
                    <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                        <tr><th style="background: #f2f2f2; padding: 8px; text-align: left;">Course Code</th><td style="padding: 8px;">' . htmlspecialchars($ccode) . '</td></tr>
                        <tr><th style="background: #f2f2f2; padding: 8px; text-align: left;">Session</th><td style="padding: 8px;">' . htmlspecialchars($ses) . '</td></tr>
                    ?</table>
                    
                    <!-- Reason for Disapproval -->
                    <div class="reason-box">
                        <div class="reason-title"><i class="fa fa-info-circle"></i> Reason for Disapproval:</div>
                        <div class="reason-text">' . nl2br(htmlspecialchars($reason)) . '</div>
                    </div>
                    
                    <p><strong>Action Required:</strong> Please review and edit the scores based on the feedback above, then resubmit for approval.</p>
                    
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
        error_log("Email sending failed: " . $e->getMessage());
    }
}
    
    // Handle disapprove action
if(isset($_POST['disapproved'])) {
    $ccode = mysqli_real_escape_string($con, $_POST['ccode']);
    $ses = mysqli_real_escape_string($con, $_POST['ses']);
    $disapproveReason = isset($_POST['disapprove_reason']) ? mysqli_real_escape_string($con, $_POST['disapprove_reason']) : 'No specific reason provided.';
    
    $upda = mysqli_query($con, "UPDATE allocatecourse SET resultsatus='' WHERE coursecord='YES' AND acadsession='$ses' AND coursecode = '$ccode'");
    $updates = mysqli_query($con, "UPDATE result SET status='' WHERE acadsession='$ses' AND coursecode = '$ccode'");
    
    if($upda) {
        $succ = 'Result has been disapproved. Course Master can now edit scores.';
        
        // Get staff email for notification
        $pps = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM allocatecourse WHERE coursecord='Yes' AND coursecode = '$ccode'"));
        $stuid = $pps['staffid'];
        $pp = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM people WHERE staffid ='$stuid'"));
        $unmail = $pp['uniemail'];
        
        // Email configuration variables
        $host='portal.edocohest.edu.ng';
        $pass='Passw20ord.';
        $user='notice@portal.edocohest.edu.ng'; 
        
        hodmails($con, $unmail, $ccode, $ses, $host, $pass, $user, $mail, $disapproveReason);
        
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> ' . $succ . '
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>';
        
        // Refresh the page content after disapproval
        echo '<script>setTimeout(function(){ location.reload(); }, 2000);</script>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> Error disapproving result. Please try again.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlentities($orgname); ?> - Approved Departmental Results</title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <link rel="stylesheet" href="css/print.css" media="print">
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        /* Stats Cards */
        .stats-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: var(--dark-color);
        }
        
        .stat-label {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
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
            font-size: 13px;
        }
        
        .data-table thead th {
            background: linear-gradient(135deg, var(--dark-color) 0%, #34495e 100%);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        .data-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s ease;
            cursor: pointer;
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
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .badge-approved {
            background: #d4edda;
            color: #155724;
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
        
        /* Result Details */
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
        
        .grade-pass {
            color: #28a745;
            font-weight: bold;
        }
        
        .grade-fail {
            color: #dc3545;
            font-weight: bold;
        }
        
        /* Print Styles */
        @media print {
            .no-print, .filter-section, .stats-dashboard, .action-buttons, .modal {
                display: none !important;
            }
            .table-container {
                box-shadow: none;
                padding: 0;
            }
            .data-table thead th {
                background: #2c3e50;
                color: black;
            }
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0,0,0,0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 0.8s ease infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .stats-dashboard {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            .data-table {
                font-size: 11px;
            }
        }
        
        /* Course Code Link */
        .course-link {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }
        
        .course-link:hover {
            text-decoration: underline;
        }
        
        /* View Button */
        .btn-view-details {
            background: var(--info-color);
            color: white;
            padding: 5px 12px;
            border-radius: 5px;
            border: none;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-view-details:hover {
            background: #138496;
            transform: scale(1.05);
        }
        
        .btn-print {
            background: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .btn-print:hover {
            background: #2980b9;
        }
        
        /* Summary Section */
        .summary-section {
            background: #e8f4f8;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <!-- TOP NAVBAR -->
        <?php include('includes/topbar.php'); ?> 
        
        <div class="content-wrapper">
            <div class="content-container">
                <!-- LEFT SIDEBAR -->
                <?php
                $paycr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM examoff WHERE staffid='".$_SESSION['staffid']."' AND acadsession='$ses' "));
                    if ($paycr['deptm']=$pro) {
                        
                         include('includes/exambar.php');
                    }
                ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-8">
                                <h2 class="title">
                                    <i class="fa fa-check-circle-o"></i> Approved Departmental Results
                                </h2>
                                <p class="subtitle">View and manage all approved course results</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <button class="btn-print no-print" onclick="window.print()">
                                    <i class="fa fa-print"></i> Print Report
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php 
                         $paycr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM examoff WHERE staffid='".$_SESSION['staffid']."' AND acadsession='$ses' "));
                    if ($paycr['deptm']=$pro) {

                        ?>
                    <div class="container-fluid">
                        <!-- Filter Section -->
                        <div class="filter-section no-print">
                            <div class="filter-title">
                                <i class="fa fa-filter"></i> Filter Results
                            </div>
                            <form method="GET" action="" class="form-horizontal">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Session:</label>
                                            <select name="session" class="form-control" onchange="this.form.submit()">
                                                <option value="">All Sessions</option>
                                                <?php
                                                $sessions = mysqli_query($con, "SELECT DISTINCT acadsession FROM allocatecourse WHERE resultsatus='Approved' ORDER BY acadsession DESC");
                                                while($s = mysqli_fetch_assoc($sessions)) {
                                                    $selected = ($selectedSession == $s['acadsession']) ? 'selected' : '';
                                                    echo "<option value='{$s['acadsession']}' $selected>{$s['acadsession']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Semester:</label>
                                            <select name="semester" class="form-control" onchange="this.form.submit()">
                                                <option value="">All Semesters</option>
                                                <option value="First Semester" <?php echo ($selectedSemester == 'First Semester') ? 'selected' : ''; ?>>First Semester</option>
                                                <option value="Second Semester" <?php echo ($selectedSemester == 'Second Semester') ? 'selected' : ''; ?>>Second Semester</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Programme:</label>
                                            <select name="programme" class="form-control" onchange="this.form.submit()">
                                                <option value="">All Programmes</option>
                                                <?php
                                                $programmes = mysqli_query($con, "SELECT DISTINCT coursecode FROM allocatecourse WHERE resultsatus='Approved' ORDER BY coursecode");
                                                while($p = mysqli_fetch_assoc($programmes)) {
                                                    $selected = ($selectedProgramme == $p['coursecode']) ? 'selected' : '';
                                                    echo "<option value='{$p['coursecode']}' $selected>{$p['coursecode']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Level:</label>
                                            <select name="level" class="form-control" onchange="this.form.submit()">
                                                <option value="">All Levels</option>
                                                <?php
                                                for($i = 100; $i <= 500; $i+=100) {
                                                    $selected = ($selectedLevel == $i) ? 'selected' : '';
                                                    echo "<option value='$i' $selected>Level $i</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <a href="approved_results.php" class="btn btn-default">
                                            <i class="fa fa-refresh"></i> Clear Filters
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <?php
                        // Build query for approved results
                        $whereConditions = ["ac.resultsatus = 'Approved'"];
                        if(!empty($selectedSession)) {
                            $whereConditions[] = "ac.acadsession = '$selectedSession'";
                        }
                        if(!empty($selectedSemester)) {
                            $whereConditions[] = "ac.semester = '$selectedSemester'";
                        }
                        if(!empty($selectedProgramme)) {
                            $whereConditions[] = "ac.programme = '$selectedProgramme'";
                        }
                        if(!empty($selectedLevel)) {
                            $whereConditions[] = "ac.level = '$selectedLevel'";
                        }
                        
                        $whereClause = implode(" AND ", $whereConditions);
                        
                        $query = "SELECT ac.*, p.firstname, p.lastname, p.title,
                                  COUNT(DISTINCT r.studentid) as student_count,
                                  AVG(CASE WHEN r.status = 'Y' THEN (r.ca + r.exam) ELSE NULL END) as avg_score
                                  FROM allocatecourse ac
                                  JOIN people p ON ac.staffid = p.staffid
                                  LEFT JOIN result r ON ac.coursecode = r.coursecode AND ac.acadsession = r.acadsession
                                  WHERE $whereClause
                                  GROUP BY ac.coursecode, ac.acadsession
                                  ORDER BY ac.acadsession DESC, ac.coursecode ASC";
                        
                        $result = mysqli_query($con, $query);
                        $approvedCourses = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        $totalCourses = count($approvedCourses);
                        
                        // Calculate statistics
                        $totalStudents = 0;
                        $totalPassed = 0;
                        $totalFailed = 0;
                        
                        foreach($approvedCourses as $course) {
                            $statsQuery = "SELECT COUNT(*) as total, 
                                          SUM(CASE WHEN status = 'Y' AND (ca + exam) >= 40 THEN 1 ELSE 0 END) as passed
                                          FROM result 
                                          WHERE coursecode = '{$course['coursecode']}' 
                                          AND acadsession = '{$course['acadsession']}'";
                            $statsResult = mysqli_query($con, $statsQuery);
                            $stats = mysqli_fetch_assoc($statsResult);
                            $totalStudents += $stats['total'];
                            $totalPassed += $stats['passed'];
                        }
                        $totalFailed = $totalStudents - $totalPassed;
                        $successRate = $totalStudents > 0 ? round(($totalPassed / $totalStudents) * 100, 1) : 0;
                        ?>
                        
                        <!-- Statistics Dashboard -->
                        <div class="stats-dashboard no-print">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fa fa-book"></i></div>
                                <div class="stat-number"><?php echo $totalCourses; ?></div>
                                <div class="stat-label">Approved Courses</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fa fa-users"></i></div>
                                <div class="stat-number"><?php echo $totalStudents; ?></div>
                                <div class="stat-label">Total Students</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fa fa-check-circle"></i></div>
                                <div class="stat-number"><?php echo $totalPassed; ?></div>
                                <div class="stat-label">Students Passed</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fa fa-percent"></i></div>
                                <div class="stat-number"><?php echo $successRate; ?>%</div>
                                <div class="stat-label">Success Rate</div>
                            </div>
                        </div>
                        
                        <!-- Results Table -->
                        <div class="table-container">
                            <div class="search-box no-print" style="margin-bottom: 15px;">
                                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search by course code, session, or lecturer...">
                            </div>
                            
                            <div class="table-responsive">
                                <table class="data-table" id="resultsTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="15%">Session</th>
                                            <th width="15%">Course Code</th>
                                            <th width="20%">Course Title</th>
                                            <th width="20%">Lecturer</th>
                                            <th width="10%">Semester</th>
                                            <th width="10%">Level</th>
                                            <th width="10%">Students</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sn = 0;
                                        foreach($approvedCourses as $course) {
                                            $sn++;
                                            
                                            // Get course title
                                            $titleQuery = mysqli_query($con, "SELECT title FROM course WHERE coursecode = '{$course['coursecode']}'");
                                            $titleData = mysqli_fetch_assoc($titleQuery);
                                            $courseTitle = $titleData ? $titleData['title'] : 'N/A';
                                        ?>
                                        <tr onclick="viewCourseDetails('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')" style="cursor: pointer;">
                                            <td><?php echo $sn; ?></td>
                                            <td><strong><?php echo htmlspecialchars($course['acadsession']); ?></strong></td>
                                            <td>
                                                <a href="#" class="course-link" onclick="event.stopPropagation(); viewCourseDetails('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')">
                                                    <?php echo htmlspecialchars($course['coursecode']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars(substr($courseTitle, 0, 50)) . (strlen($courseTitle) > 50 ? '...' : ''); ?></td>
                                            <td><?php echo htmlspecialchars($course['title'] . ' ' . $course['firstname'] . ' ' . $course['lastname']); ?></td>
                                            <td><?php echo htmlspecialchars($course['semester']); ?></td>
                                            <td><?php echo htmlspecialchars($course['level']); ?></td>
                                            <td class="text-center"><?php echo $course['student_count']; ?></td>
                                            <td class="no-print">
                                                <button class="btn-view-details" onclick="event.stopPropagation(); viewCourseDetails('<?php echo $course['coursecode']; ?>', '<?php echo $course['acadsession']; ?>')">
                                                    <i class="fa fa-eye"></i> View Details
                                                </button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php if($totalCourses == 0): ?>
                                        <tr>
                                            <td colspan="9">
                                                <div class="text-center" style="padding: 50px;">
                                                    <i class="fa fa-inbox" style="font-size: 48px; color: #ccc;"></i>
                                                    <h4>No Approved Results Found</h4>
                                                    <p>No approved results match your filter criteria.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
    
    <!-- Course Details Modal -->
    <div class="modal fade" id="courseModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-file-text-o"></i> Course Result Details
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="courseDetails">
                    <div class="text-center">
                        <div class="loading-spinner"></div>
                        <p class="mt-3">Loading course details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="printModalContent()">
                        <i class="fa fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ========== COMMON JS FILES ========== -->
    <?php include 'includes/buton.php'; ?>
    
    <script>
        // Search functionality
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#resultsTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
        
        function viewCourseDetails(courseCode, session) {
            $('#courseDetails').html(`
                <div class="text-center">
                    <div class="loading-spinner"></div>
                    <p class="mt-3">Loading details for course: ${courseCode}...</p>
                </div>
            `);
            
            $.ajax({
                url: 'get_course_details.php',
                type: 'POST',
                data: { coursecode: courseCode, session: session },
                success: function(response) {
                    $('#courseDetails').html(response);
                    $('#courseModal').modal('show');
                },
                error: function() {
                    $('#courseDetails').html(`
                        <div class="alert alert-danger text-center">
                            <i class="fa fa-exclamation-circle"></i>
                            <p>Error loading course details. Please try again.</p>
                        </div>
                    `);
                    $('#courseModal').modal('show');
                }
            });
        }
        
        function printModalContent() {
            var content = $('#courseDetails').html();
            var printWindow = window.open('', '_blank', 'width=900,height=600');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Course Result Details</title>
                    <link rel="stylesheet" href="css/bootstrap.min.css">
                    <style>
                        body { padding: 20px; font-family: Arial, sans-serif; }
                        @media print {
                            body { margin: 0; padding: 15px; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    ${content}
                    <div class="text-center no-print" style="margin-top: 20px;">
                        <button onclick="window.print()" class="btn btn-primary">Print</button>
                        <button onclick="window.close()" class="btn btn-secondary">Close</button>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
</body>
</html>

<?php 


} ?>