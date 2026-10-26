<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

if(strlen($_SESSION['staffid'])=="") {   
    header("Location: ../logout"); 
} else {
    $now = time();
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location:../logout");
    }
    
    // Create a unique key for the result table to enable ON DUPLICATE KEY UPDATE
    $alter_query = "ALTER TABLE result ADD UNIQUE KEY IF NOT EXISTS unique_result (studentid, coursecode, acadsession, semester)";
    mysqli_query($con, $alter_query);
    
    $error='';
    $success='';
    
    // Get current result session
    $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='exam processing'"));
    $sems=$dat['semester'];
    $ses=$dat['session'];
    $ded=$dat['deadline'];
    
    // Handle student search
    if (isset($_POST['search_student'])) {
        $studentid = mysqli_escape_string($con, trim($_POST['studentid']));
        
        // Check in both student and applicant tables
        $student_check = mysqli_query($con, "SELECT * FROM student WHERE studentid='$studentid' AND status='active'");
        
        if (mysqli_num_rows($student_check) > 0) {
            $_SESSION['search_student'] = $studentid;
            $_SESSION['student_data'] = mysqli_fetch_assoc($student_check);
            $_SESSION['student_source'] = 'student';
        } else {
            // Check applicant table if not found in student table
            $applicant_check = mysqli_query($con, "SELECT * FROM studdata WHERE studentid='$studentid'");
            if (mysqli_num_rows($applicant_check) > 0) {
                $_SESSION['search_student'] = $studentid;
                $_SESSION['student_data'] = mysqli_fetch_assoc($applicant_check);
                $_SESSION['student_source'] = 'studdata';
            } else {
                $error = "Student ID not found in either student or applicant records";
            }
        }
    }
    
    // Handle individual result submission (bulk save) - Submit Selected Results
    if (isset($_POST['submit_selected'])) {
        $studentid = $_SESSION['search_student'];
        $courses = $_POST['courses'];
        $selected_courses = isset($_POST['selected_courses']) ? $_POST['selected_courses'] : array();
        
        if (empty($selected_courses)) {
            $error = "Please select at least one course to submit.";
        } else {
            $success_count = 0;
            $error_count = 0;
            
            foreach ($selected_courses as $coursecode) {
                if (isset($courses[$coursecode])) {
                    $ca = $courses[$coursecode]['ca'] !== '' ? $courses[$coursecode]['ca'] : NULL;
                    $exam = $courses[$coursecode]['exam'] !== '' ? $courses[$coursecode]['exam'] : NULL;
                    
                    // Get student's programme and level from appropriate table
                   
                        $student_info = mysqli_fetch_assoc(mysqli_query($con, "SELECT programme, level FROM studentregsem WHERE studentid='$studentid' AND session='$ses' AND semester='$sems' "));

                    
                    $programme = $student_info['programme'];
                    $level = $student_info['level'];
                    
                    // Check if result already exists
                    $check = mysqli_query($con, "SELECT * FROM result WHERE studentid='$studentid' AND coursecode='$coursecode' AND acadsession='$ses' AND semester='$sems'");
                    
                    if (mysqli_num_rows($check) > 0) {
                        // Update existing result with status='Y'
                        $update = "UPDATE result SET ca='$ca', exam='$exam', status='Y' 
                                  WHERE studentid='$studentid' AND coursecode='$coursecode'  
                                  AND acadsession='$ses' AND semester='$sems'";
                        if (mysqli_query($con, $update)) {
                            $success_count++;
                        } else {
                            $error_count++;
                        }
                    } else {
                        // Insert new result with status='Y'
                        $insert = "INSERT INTO result (studentid, coursecode, acadsession, semester, level, programme, ca, exam, status) 
                                  VALUES ('$studentid', '$coursecode', '$ses', '$sems', '$level', '$programme', '$ca', '$exam', 'Y')";
                        if (mysqli_query($con, $insert)) {
                            $success_count++;
                        } else {
                            $error_count++;
                        }
                    }
                }
            }
            
            if ($error_count == 0) {
                $success = "$success_count selected courses submitted and marked as COMPLETED for Student: $studentid";
            } else {
                $error = "$success_count courses updated successfully, $error_count courses failed";
            }
        }
    }
    
    // Handle Submit and Approve Selected Results
    if (isset($_POST['approve_selected'])) {
        $studentid = $_SESSION['search_student'];
        $courses = $_POST['courses'];
        $selected_courses = isset($_POST['selected_courses']) ? $_POST['selected_courses'] : array();
        
        if (empty($selected_courses)) {
            $error = "Please select at least one course to approve.";
        } else {
            $success_count = 0;
            $error_count = 0;
            
            foreach ($selected_courses as $coursecode) {
                if (isset($courses[$coursecode])) {
                    $ca = $courses[$coursecode]['ca'] !== '' ? $courses[$coursecode]['ca'] : NULL;
                    $exam = $courses[$coursecode]['exam'] !== '' ? $courses[$coursecode]['exam'] : NULL;
                    
                    // Get student's programme and level from appropriate table
                     $student_info = mysqli_fetch_assoc(mysqli_query($con, "SELECT programme, level FROM studentregsem WHERE studentid='$studentid' AND session='$ses' AND semester='$sems' "));

                    
                    $programme = $student_info['programme'];
                    $level = $student_info['level'];
                    
                    // Check if result already exists
                    $check = mysqli_query($con, "SELECT * FROM result WHERE studentid='$studentid' AND coursecode='$coursecode' AND acadsession='$ses' AND semester='$sems'");
                    
                    if (mysqli_num_rows($check) > 0) {
                        // Update existing result with status='Y' AND publish='1'
                        $update = "UPDATE result SET ca='$ca', exam='$exam', status='Y', publish='1' 
                                  WHERE studentid='$studentid' AND coursecode='$coursecode' 
                                  AND acadsession='$ses' AND semester='$sems'";
                        if (mysqli_query($con, $update)) {
                            $success_count++;
                        } else {
                            $error_count++;
                        }
                    } else {
                        // Insert new result with status='Y' AND publish='1'
                        $insert = "INSERT INTO result (studentid, coursecode, acadsession, semester, level, programme, ca, exam, status, publish) 
                                  VALUES ('$studentid', '$coursecode', '$ses', '$sems', '$level', '$programme', '$ca', '$exam', 'Y', '1')";
                        if (mysqli_query($con, $insert)) {
                            $success_count++;
                        } else {
                            $error_count++;
                        }
                    }
                }
            }
            
            if ($error_count == 0) {
                $success = "$success_count selected courses APPROVED and PUBLISHED for Student: $studentid";
            } else {
                $error = "$success_count courses updated successfully, $error_count courses failed";
            }
        }
    }
    
    // Clear search
    if (isset($_POST['clear_search'])) {
        unset($_SESSION['search_student']);
        unset($_SESSION['student_data']);
        unset($_SESSION['student_source']);
    }
    
    // Function to get student name
    function getStudentName($con, $studentid, $source) {
        if ($source == 'student') {
            $sql = mysqli_query($con, "SELECT * FROM student WHERE studentid='$studentid'");
            $data = mysqli_fetch_assoc($sql);
            if ($data) {
                $first = $data['firstname'];
                $sur = $data['othername'];
                $las = $data['lastname'];
                return $sur . " " . $las . " " . $first;
            }
        } else {
            $sql = mysqli_query($con, "SELECT * FROM studdata WHERE studentid='$studentid'");
            $data = mysqli_fetch_assoc($sql);
            if ($data) {
                $first = $data['firstname'];
                $sur = $data['surname'];
                $las = $data['lastname'];
                return $sur . " " . $las . " " . $first;
            }
        }
        return "No Name Found";
    }
    
    // Function to get max scores from curriculum
    function getMaxScores($con, $coursecode, $programme) {
        $curr = mysqli_fetch_assoc(mysqli_query($con, "SELECT cascore, examscore FROM curriculum 
                                                      WHERE coursecode='$coursecode' 
                                                      AND programme='$programme'"));
        if ($curr == '') {
            return array('ca' => 30, 'exam' => 70);
        } else {
            return array('ca' => $curr['cascore'], 'exam' => $curr['examscore']);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlentities($orgname);?> - Individual Result Upload</title>
    <link href="<?php echo htmlentities($logo);?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <style>
        .student-info-card {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .course-row {
            background: white;
            border: 1px solid #dee2e6;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .course-row:hover {
            background: #f0f7ff;
            border-color: #007bff;
        }
        .course-row.selected {
            background: #e8f0fe;
            border-color: #007bff;
            border-left: 4px solid #007bff;
        }
        .course-code-badge {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin-right: 10px;
        }
        .score-input {
            width: 80px;
            display: inline-block;
            margin: 0 5px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .score-input.saving {
            background-color: #fff3cd;
            border-color: #ffc107;
        }
        .score-input.saved {
            background-color: #d4edda;
            border-color: #28a745;
        }
        .score-input.error {
            background-color: #f8d7da;
            border-color: #dc3545;
        }
        .deadline-warning {
            color: #dc3545;
            font-weight: bold;
            padding: 10px;
            background: #f8d7da;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .days-remaining {
            color: #28a745;
            font-weight: bold;
        }
        .student-source-badge {
            background: #17a2b8;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 10px;
        }
        .auto-save-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 14px;
            z-index: 9999;
            display: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .save-status {
            font-size: 11px;
            margin-left: 5px;
            color: #28a745;
        }
        .btn-group-custom {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .btn-group-custom button {
            min-width: 180px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-pending {
            background: #6c757d;
            color: white;
        }
        .status-completed {
            background: #28a745;
            color: white;
        }
        .status-published {
            background: #17a2b8;
            color: white;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .checkbox-cell {
            width: 40px;
            text-align: center;
            vertical-align: middle;
        }
        .course-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .select-all-container {
            background: #e9ecef;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .select-all-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .selection-summary {
            margin-left: auto;
            font-size: 14px;
            font-weight: normal;
        }
        .course-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .badge-info {
            background: #17a2b8;
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php');?>
        
        <div class="content-wrapper">
            <div class="content-container">
                <!-- Sidebar -->
                <?php
                $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                if ($paych['position']=="hod") {
                    include('includes/hodbar.php');
                } else if ($paych['position']=="lecturer") {
                    include('includes/lectbar.php');
                } elseif ($paych['position']=="dean") {
                    include('includes/deanbar.php');
                } elseif ($paych['position']=="superadmin") {
                    include('includes/supbar.php');
                } else {
                    include('includes/leftbar.php');
                }
                ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div>
                                <h2 class="title">Individual Result Upload (Select Courses to Submit/Approve)</h2>
                            </div>
                        </div>
                        
                        <!-- Deadline Information -->
                        <?php
                        $dats = date("Y-m-d");
                        if ($dats > $ded) {
                            echo "<div class='deadline-warning'><i class='fa fa-warning'></i> Result Uploading Portal Is Closed (Deadline was: " . date('d-m-Y', strtotime($ded)) . ")</div>";
                        } else {
                            $date1 = new DateTime($ded);
                            $now = new DateTime();
                            $diff = $date1->diff($now);
                        ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Academic Session: <?php echo htmlentities($ses); ?> | Semester: <?php echo htmlentities($sems); ?></strong><br>
                            Days Remaining for Upload: <span class="days-remaining"><?php echo $diff->days; ?> days</span> (Deadline: <?php echo date('d-m-Y', strtotime($ded)); ?>)
                        </div>
                        <?php } ?>
                        
                        <!-- Student Search Form -->
                        <?php if (!isset($_SESSION['search_student'])) { ?>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title"><i class="fa fa-search"></i> Search Student</h4>
                            </div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Student ID/Matric No:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="studentid" class="form-control" 
                                                   placeholder="Enter Student ID or Matriculation Number" required 
                                                   value="<?php echo isset($_POST['studentid']) ? htmlentities($_POST['studentid']) : ''; ?>">
                                            <small class="text-muted">Enter the student's ID number (checks both student and applicant tables)</small>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search_student" class="btn btn-primary btn-block">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <!-- Student Info and Course List -->
                        <?php if (isset($_SESSION['search_student']) && isset($_SESSION['student_data'])) { 
                            $studentid = $_SESSION['search_student'];
                            $student = $_SESSION['student_data'];
                            $source = $_SESSION['student_source'];
                            $student_name = getStudentName($con, $studentid, $source);
                            
                            // Get registered courses for this student
                            $reg_courses = mysqli_query($con, "SELECT rc.*, c.title, c.unit, c.department 
                                                               FROM studentcourseregistration rc 
                                                               JOIN course c ON rc.coursecode = c.coursecode 
                                                               WHERE rc.studentid='$studentid' 
                                                               AND rc.session='$ses' 
                                                               AND rc.semester='$sems'
                                                               ORDER BY rc.coursecode");
                            
                            // Get existing results for pre-fill and status checking
                            $existing_results = array();
                            $res_query = mysqli_query($con, "SELECT coursecode, ca, exam, status, publish FROM result 
                                                            WHERE studentid='$studentid' 
                                                            AND acadsession='$ses' 
                                                            AND semester='$sems'");
                            while ($row = mysqli_fetch_assoc($res_query)) {
                                $existing_results[$row['coursecode']] = $row;
                            }
                            
                            // Calculate statistics
                            $total_courses = mysqli_num_rows($reg_courses);
                            $completed_courses = 0;
                            $published_courses = 0;
                            $pending_courses = 0;
                            
                            $course_list = array();
                            while ($course = mysqli_fetch_assoc($reg_courses)) {
                                $course_list[] = $course;
                                if (isset($existing_results[$course['coursecode']])) {
                                    if ($existing_results[$course['coursecode']]['status'] == 'Y') {
                                        $completed_courses++;
                                    }
                                    if ($existing_results[$course['coursecode']]['publish'] == '1') {
                                        $published_courses++;
                                    }
                                }
                            }
                            $pending_courses = $total_courses - max($completed_courses, $published_courses);
                            
                            // Reset the result set pointer for display
                            $reg_courses = $course_list;
                        ?>
                        
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="panel-title">
                                            <i class="fa fa-upload"></i> Upload Results for Student
                                            <span class="student-source-badge">Source: <?php echo $source; ?></span>
                                        </h4>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <form method="post" style="display: inline;">
                                            <button type="submit" name="clear_search" class="btn btn-warning">
                                                <i class="fa fa-times"></i> Clear Search
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel-body">
                                <!-- Student Information Card -->
                                <div class="student-info-card">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong><i class="fa fa-id-card"></i> Student ID:</strong> 
                                            <span class="text-primary"><?php echo htmlentities($studentid); ?></span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong><i class="fa fa-user"></i> Name:</strong> 
                                            <?php echo htmlentities($student_name); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <strong><i class="fa fa-level-up"></i> Level:</strong> 
                                            <?php echo isset($student['level']) ? htmlentities($student['level']) : 'N/A'; ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong><i class="fa fa-book"></i> Programme:</strong> 
                                            <?php echo isset($student['programme']) ? htmlentities($student['programme']) : 'N/A'; ?>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-12">
                                            <div class="progress" style="height: 20px;">
                                                <?php 
                                                $progress_percent = ($total_courses > 0) ? ($completed_courses / $total_courses) * 100 : 0;
                                                ?>
                                                <div class="progress-bar progress-bar-success" role="progressbar" 
                                                     style="width: <?php echo $progress_percent; ?>%;" 
                                                     aria-valuenow="<?php echo $progress_percent; ?>" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    <?php echo $completed_courses; ?>/<?php echo $total_courses; ?> Courses Completed
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-md-4">
                                                    <span class="status-badge status-completed">Completed: <?php echo $completed_courses; ?></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <span class="status-badge status-published">Published: <?php echo $published_courses; ?></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <span class="status-badge status-pending">Pending: <?php echo $pending_courses; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if ($total_courses > 0) { ?>
                                
                                <form method="post" id="resultForm">
                                    <!-- Select All Container -->
                                    <div class="select-all-container">
                                        <input type="checkbox" id="selectAllCheckbox" class="select-all-checkbox">
                                        <label for="selectAllCheckbox" style="margin: 0; cursor: pointer;">
                                            <strong>Select All Courses</strong>
                                        </label>
                                        <span class="selection-summary" id="selectionSummary">
                                            <span id="selectedCount">0</span> of <span id="totalCount"><?php echo $total_courses; ?></span> courses selected
                                        </span>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-success">
                                                <i class="fa fa-info-circle"></i> 
                                                <strong>Auto-Save Enabled:</strong> Scores are saved automatically when you click out of the field or press Enter.
                                                <span id="autoSaveStatus" class="save-status"><i class="fa fa-check-circle"></i> Ready</span>
                                            </div>
                                            
                                            <?php if ($published_courses > 0): ?>
                                            <div class="warning-box">
                                                <i class="fa fa-exclamation-triangle"></i> 
                                                <strong><?php echo $published_courses; ?> course(s) have already been published.</strong> Editing will require re-approval.
                                            </div>
                                            <?php endif; ?>
                                            
                                            <!-- Course List Header -->
                                            <div class="row" style="font-weight: bold; margin-bottom: 10px; padding: 0 15px; background: #e9ecef; border-radius: 4px; line-height: 40px;">
                                                <div class="col-md-1 text-center">Select</div>
                                                <div class="col-md-2">Course Code</div>
                                                <div class="col-md-3">Course Title</div>
                                                <div class="col-md-1 text-center">Unit</div>
                                                <div class="col-md-2 text-center">CA Score (Max)</div>
                                                <div class="col-md-2 text-center">Exam Score (Max)</div>
                                                <div class="col-md-1 text-center">Status</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php 
                                    $counter = 0;
                                    foreach ($reg_courses as $course) { 
                                        $counter++;
                                        $coursecode = $course['coursecode'];
                                        $existing_ca = isset($existing_results[$coursecode]) ? $existing_results[$coursecode]['ca'] : '';
                                        $existing_exam = isset($existing_results[$coursecode]) ? $existing_results[$coursecode]['exam'] : '';
                                        $course_status = isset($existing_results[$coursecode]) ? $existing_results[$coursecode]['status'] : '';
                                        $course_publish = isset($existing_results[$coursecode]) ? $existing_results[$coursecode]['publish'] : '';
                                        
                                        // Get max scores
                                        $max_scores = getMaxScores($con, $coursecode, $student['programme']);
                                        $max_ca = $max_scores['ca'];
                                        $max_exam = $max_scores['exam'];
                                        
                                        $status_text = 'Pending';
                                        $status_class = 'status-pending';
                                        if ($course_publish == '1') {
                                            $status_text = 'Published';
                                            $status_class = 'status-published';
                                        } elseif ($course_status == 'Y') {
                                            $status_text = 'Completed';
                                            $status_class = 'status-completed';
                                        }
                                        
                                        $is_selected = ($course_status != 'Y' && $course_publish != '1') || ($course_publish == '1' && $course_status == 'Y');
                                    ?>
                                    
                                    <div class="course-row" id="course-<?php echo $counter; ?>" data-course="<?php echo $coursecode; ?>">
                                        <div class="row">
                                            <div class="col-md-1 text-center">
                                                <input type="checkbox" 
                                                       name="selected_courses[]" 
                                                       value="<?php echo $coursecode; ?>" 
                                                       class="course-checkbox" 
                                                       id="course_<?php echo $coursecode; ?>"
                                                       data-course="<?php echo $coursecode; ?>"
                                                       <?php echo $is_selected ? 'checked' : ''; ?>
                                                       onclick="updateSelectionCount()">
                                            </div>
                                            <div class="col-md-2">
                                                <span class="course-code-badge"><?php echo htmlentities($coursecode); ?></span>
                                            </div>
                                            <div class="col-md-3">
                                                <?php echo htmlentities($course['title']); ?>
                                                <input type="hidden" name="courses[<?php echo $coursecode; ?>][coursecode]" 
                                                       value="<?php echo $coursecode; ?>">
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <span class="badge" style="background: #28a745;"><?php echo htmlentities($course['unit']); ?></span>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <input type="number" 
                                                       name="courses[<?php echo $coursecode; ?>][ca]" 
                                                       class="form-control score-input ca-input auto-save" 
                                                       id="ca-<?php echo $coursecode; ?>"
                                                       data-course="<?php echo $coursecode; ?>"
                                                       data-field="ca"
                                                       data-max="<?php echo $max_ca; ?>"
                                                       data-student="<?php echo $studentid; ?>"
                                                       data-session="<?php echo $ses; ?>"
                                                       data-semester="<?php echo $sems; ?>"
                                                       value="<?php echo $existing_ca; ?>" 
                                                       min="0" 
                                                       max="<?php echo $max_ca; ?>"
                                                       placeholder="CA"
                                                       onblur="saveInlineEdit(this)"
                                                       onkeypress="handleKeyPress(event, this)"
                                                       style="display: inline-block; width: 70px;">
                                                <small class="text-muted">/<?php echo $max_ca; ?></small>
                                                <span class="save-status" id="status-ca-<?php echo $coursecode; ?>"></span>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <input type="number" 
                                                       name="courses[<?php echo $coursecode; ?>][exam]" 
                                                       class="form-control score-input exam-input auto-save" 
                                                       id="exam-<?php echo $coursecode; ?>"
                                                       data-course="<?php echo $coursecode; ?>"
                                                       data-field="exam"
                                                       data-max="<?php echo $max_exam; ?>"
                                                       data-student="<?php echo $studentid; ?>"
                                                       data-session="<?php echo $ses; ?>"
                                                       data-semester="<?php echo $sems; ?>"
                                                       value="<?php echo $existing_exam; ?>" 
                                                       min="0" 
                                                       max="<?php echo $max_exam; ?>"
                                                       placeholder="Exam"
                                                       onblur="saveInlineEdit(this)"
                                                       onkeypress="handleKeyPress(event, this)"
                                                       style="display: inline-block; width: 70px;">
                                                <small class="text-muted">/<?php echo $max_exam; ?></small>
                                                <span class="save-status" id="status-exam-<?php echo $coursecode; ?>"></span>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <span class="status-badge <?php echo $status_class; ?>" id="saved-label-<?php echo $coursecode; ?>">
                                                    <?php echo $status_text; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php } ?>
                                    
                                    <div class="btn-group-custom">
                                        <button type="submit" name="submit_selected" class="btn btn-success btn-lg" 
                                                onclick="return validateSelection('submit')">
                                            <i class="fa fa-save"></i> Submit Selected (<span id="submitCount">0</span>)
                                        </button>
                                        
                                        <button type="submit" name="approve_selected" class="btn btn-primary btn-lg" 
                                                onclick="return validateSelection('approve')">
                                            <i class="fa fa-check-double"></i> Approve Selected (<span id="approveCount">0</span>)
                                        </button>
                                    </div>
                                    
                                    <div class="text-center" style="margin-top: 15px;">
                                        <button type="button" class="btn btn-info" onclick="window.print()">
                                            <i class="fa fa-print"></i> Print Preview
                                        </button>
                                    </div>
                                </form>
                                
                                <?php } else { ?>
                                <div class="alert alert-warning">
                                    <i class="fa fa-warning"></i> 
                                    <strong>No Courses Registered!</strong><br>
                                    This student has no courses registered for <?php echo htmlentities($ses); ?> session, <?php echo htmlentities($sems); ?> semester.
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <!-- Real-time Summary Panel -->
                        <?php if ($total_courses > 0) { ?>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4 class="panel-title"><i class="fa fa-chart-line"></i> Score Summary</h4>
                            </div>
                            <div class="panel-body" id="scoreSummary">
                                <div class="row">
                                    <div class="col-md-12" id="totalDisplay">
                                        <!-- Populated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Auto-save indicator -->
    <div class="auto-save-indicator" id="autoSaveIndicator">
        <i class="fa fa-spinner fa-spin"></i> Saving...
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #28a745; color: white;">
                    <h5 class="modal-title"><i class="fa fa-check-circle"></i> Success</h5>
                    <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                </div>
                <div class="modal-body">
                    <p><?php echo htmlentities($success); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dc3545; color: white;">
                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Error</h5>
                    <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                </div>
                <div class="modal-body">
                    <p><?php echo htmlentities($error); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Warning Modal (for validation errors) -->
    <div class="modal fade" id="warningModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #ffc107; color: black;">
                    <h5 class="modal-title"><i class="fa fa-warning"></i> Validation Error</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="warningMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/buton.php'; ?>
    
    <!-- JavaScript -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
    // Global variables
    let savingNow = false;
    let saveQueue = [];
    let totalCourses = <?php echo $total_courses; ?>;
    
    // Update selection count
    function updateSelectionCount() {
        let selectedCount = $('.course-checkbox:checked').length;
        $('#selectedCount').text(selectedCount);
        $('#submitCount').text(selectedCount);
        $('#approveCount').text(selectedCount);
        
        // Update select all checkbox state
        if (selectedCount === totalCourses) {
            $('#selectAllCheckbox').prop('checked', true);
            $('#selectAllCheckbox').prop('indeterminate', false);
        } else if (selectedCount === 0) {
            $('#selectAllCheckbox').prop('checked', false);
            $('#selectAllCheckbox').prop('indeterminate', false);
        } else {
            $('#selectAllCheckbox').prop('indeterminate', true);
        }
        
        // Highlight selected rows
        $('.course-row').removeClass('selected');
        $('.course-checkbox:checked').each(function() {
            $(this).closest('.course-row').addClass('selected');
        });
    }
    
    // Select/Deselect all
    $('#selectAllCheckbox').on('change', function() {
        let isChecked = $(this).prop('checked');
        $('.course-checkbox').prop('checked', isChecked);
        updateSelectionCount();
    });
    
    // Validate selection before submission
    function validateSelection(action) {
        let selectedCount = $('.course-checkbox:checked').length;
        
        if (selectedCount === 0) {
            showWarning('Please select at least one course to ' + action + '.');
            return false;
        }
        
        let confirmMessage = '';
        if (action === 'submit') {
            confirmMessage = 'Submit selected ' + selectedCount + ' course(s) for this student? This will mark them as COMPLETED.';
        } else {
            confirmMessage = 'Approve and publish selected ' + selectedCount + ' course(s) for this student? This will make them visible to students.';
        }
        
        return confirm(confirmMessage);
    }
    
    // Auto-save function with queue system
    function saveInlineEdit(element) {
        // Prevent multiple rapid saves
        if (savingNow) {
            saveQueue.push(element);
            return;
        }
        
        savingNow = true;
        
        let courseCode = $(element).data('course');
        let field = $(element).data('field');
        let studentId = $(element).data('student');
        let session = $(element).data('session');
        let semester = $(element).data('semester');
        let maxScore = $(element).data('max');
        let value = $(element).val();
        
        // Validate score
        if (value !== '') {
            let numValue = parseInt(value);
            if (isNaN(numValue)) {
                showWarning(field.toUpperCase() + " score must be a number");
                $(element).val('');
                savingNow = false;
                processNextSave();
                return;
            }
            
            if (numValue > maxScore) {
                showWarning(field.toUpperCase() + " score cannot exceed " + maxScore);
                $(element).val('');
                savingNow = false;
                processNextSave();
                return;
            }
            
            if (numValue < 0) {
                showWarning(field.toUpperCase() + " score cannot be negative");
                $(element).val('');
                savingNow = false;
                processNextSave();
                return;
            }
        }
        
        // Show saving indicator
        $(element).removeClass('saved error').addClass('saving');
        $('#status-' + field + '-' + courseCode).html('<i class="fa fa-spinner fa-spin" style="color: #ffc107;"></i>');
        
        // Prepare data for AJAX
        let formData = {
            studentid: studentId,
            coursecode: courseCode,
            column: field,
            value: value,
            acadsession: session,
            semester: semester
        };
        
        $.ajax({
            url: 'ajax_save_result.php',
            type: 'POST',
            data: formData,
            timeout: 10000,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $(element).removeClass('saving');
                response = response.trim();
                
                if (response === 'success') {
                    $(element).addClass('saved');
                    $('#status-' + field + '-' + courseCode).html('<i class="fa fa-check-circle" style="color: #28a745;"></i>');
                    $('#saved-label-' + courseCode).removeClass('status-pending status-completed status-published').addClass('status-pending').text('Draft');
                    
                    setTimeout(function() {
                        $(element).removeClass('saved');
                    }, 2000);
                } else {
                    $(element).addClass('error');
                    $('#status-' + field + '-' + courseCode).html('<i class="fa fa-exclamation-circle" style="color: #dc3545;"></i>');
                    
                    let errorMsg = response;
                    if (response.includes('ERROR:')) {
                        errorMsg = response.replace('ERROR:', '').trim();
                    } else if (response.length > 50) {
                        errorMsg = "Server error occurred";
                    }
                    
                    showWarning("Save failed: " + errorMsg);
                    
                    setTimeout(function() {
                        $(element).removeClass('error');
                    }, 3000);
                }
                
                updateSummary();
                savingNow = false;
                processNextSave();
            },
            error: function(xhr, status, error) {
                $(element).removeClass('saving');
                $(element).addClass('error');
                $('#status-' + field + '-' + courseCode).html('<i class="fa fa-exclamation-circle" style="color: #dc3545;"></i>');
                
                let errorMsg = "Connection error";
                if (status === 'timeout') {
                    errorMsg = "Request timeout - please try again";
                } else if (error) {
                    errorMsg = error;
                }
                
                showWarning("Save failed: " + errorMsg);
                
                setTimeout(function() {
                    $(element).removeClass('error');
                }, 3000);
                
                savingNow = false;
                processNextSave();
            }
        });
    }
    
    function processNextSave() {
        if (saveQueue.length > 0) {
            let nextElement = saveQueue.shift();
            setTimeout(function() {
                saveInlineEdit(nextElement);
            }, 100);
        }
    }
    
    function handleKeyPress(event, element) {
        if (event.keyCode === 13) {
            event.preventDefault();
            $(element).blur();
        }
    }
    
    function showWarning(message) {
        $('#warningMessage').text(message);
        $('#warningModal').modal('show');
    }
    
    function updateSummary() {
        let coursesWithScores = 0;
        let totalCA = 0;
        let totalExam = 0;
        
        $('.ca-input').each(function() {
            let val = $(this).val();
            if (val && val !== '') {
                coursesWithScores++;
                totalCA += parseInt(val) || 0;
            }
        });
        
        $('.exam-input').each(function() {
            let val = $(this).val();
            if (val && val !== '') {
                totalExam += parseInt(val) || 0;
            }
        });
        
        let summary = `
            <div class="alert alert-success">
                <strong>Summary:</strong><br>
                <div class="row">
                    <div class="col-md-3">Courses with scores: <span class="badge">${coursesWithScores}</span></div>
                    <div class="col-md-3">Total CA: <span class="badge">${totalCA}</span></div>
                    <div class="col-md-3">Total Exam: <span class="badge">${totalExam}</span></div>
                    <div class="col-md-3">Total Score: <span class="badge">${totalCA + totalExam}</span></div>
                </div>
            </div>
        `;
        
        $('#totalDisplay').html(summary);
    }
    
    let saveTimeout;
    function debouncedSave(element) {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(function() {
            saveInlineEdit(element);
        }, 800);
    }
    
    $(document).ready(function() {
        updateSelectionCount();
        updateSummary();
        
        <?php if (!empty($success)) { ?>
            $('#successModal').modal('show');
        <?php } ?>
        
        <?php if (!empty($error)) { ?>
            $('#errorModal').modal('show');
        <?php } ?>
        
        $('.auto-save').on('input', function() {
            debouncedSave(this);
        });
        
        $('.auto-save').on('blur', function() {
            clearTimeout(saveTimeout);
            saveInlineEdit(this);
        });
        
        $('.auto-save').on('focus', function() {
            $(this).data('last-value', $(this).val());
        });
        
        // Auto-select courses that haven't been submitted yet
        $('.course-checkbox').each(function() {
            let courseCode = $(this).val();
            let statusLabel = $('#saved-label-' + courseCode).text();
            if (statusLabel === 'Completed' || statusLabel === 'Published') {
                $(this).prop('checked', false);
            } else {
                $(this).prop('checked', true);
            }
        });
        updateSelectionCount();
    });
    </script>
</body>
</html>

<?php 
} // End of else for session check
?>