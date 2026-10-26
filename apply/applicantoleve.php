<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';

if (!isset($_SESSION['staffid'])) {   
    header("Location: logout"); 
    exit();
} else {
    $now = time();
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location: logout");
        exit();
    }else {
    $error = '';
    $succ = '';
    $showModalEr = false;
    $upload_success = false;
    
    // Helper function to check if 5 credits are achieved
// Helper function to check if 5 credits are achieved (including Awaiting)
function checkFiveCredits($con, $staffid) {
    $passed_subjects = array();
    
    // Get First Sitting results
    $first_sitting = mysqli_fetch_assoc(mysqli_query($con, "SELECT examno FROM oleveldetail WHERE applid='$staffid' AND noofsit='1'"));
    if ($first_sitting) {
        $first_results = mysqli_query($con, "SELECT subject, grade FROM olevelresultdetail WHERE applid='$staffid' AND examno='{$first_sitting['examno']}'");
        while ($row = mysqli_fetch_assoc($first_results)) {
            $grade = strtoupper(trim($row['grade']));
            // Allow A1-C6 AND also 'AWAITING' as valid credits
            if (in_array($grade, ['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'AWAITING'])) {
                $passed_subjects[] = $row['subject'];
            }
        }
    }
    
    // Get Second Sitting results if exists
    $second_sitting = mysqli_fetch_assoc(mysqli_query($con, "SELECT examno FROM oleveldetail WHERE applid='$staffid' AND noofsit='2'"));
    if ($second_sitting) {
        $second_results = mysqli_query($con, "SELECT subject, grade FROM olevelresultdetail WHERE applid='$staffid' AND examno='{$second_sitting['examno']}'");
        while ($row = mysqli_fetch_assoc($second_results)) {
            $grade = strtoupper(trim($row['grade']));
            // Allow A1-C6 AND also 'AWAITING' as valid credits
            if (in_array($grade, ['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'AWAITING'])) {
                if (!in_array($row['subject'], $passed_subjects)) {
                    $passed_subjects[] = $row['subject'];
                }
            }
        }
    }
    
    // Check if Maths, English, and 3 other subjects are passed
    $has_maths = in_array('General Mathematics', $passed_subjects);
    $has_english = in_array('English Language', $passed_subjects);
    
    return ($has_maths && $has_english && count($passed_subjects) >= 5);
}
// First Sitting Submit
if (isset($_POST["submitol"])) {
    if (empty($_POST["exambody"])) {
        $error = "Exam Body Required";
        $showModalEr = true;
    } elseif (empty($_POST["month"])) {
        $error = "Month Required";
        $showModalEr = true;
    } elseif (empty($_POST["year"])) {
        $error = "Year Required";
        $showModalEr = true;
    } elseif (empty($_POST["grade1"])) {
        $error = "Grade For Mathematics Required";
        $showModalEr = true;
    } elseif (empty($_POST['grade2'])) {
        $error = "Grade For English Language Required";
        $showModalEr = true;
    } elseif (empty($_POST['grade3'])) {
        $error = "Grade For Biology Required";
        $showModalEr = true;
    } elseif (empty($_POST['grade4'])) {
        $error = "Grade For Chemistry Required";
        $showModalEr = true;
    } elseif (empty($_POST['grade'])) {
        $error = "Grade For Physics Required";
        $showModalEr = true;
    } else {
        $exambodp = mysqli_real_escape_string($con, $_POST["exambody"]);
        $examno = trim(mysqli_real_escape_string($con, strtoupper($_POST["examno"] ?? $_SESSION['staffid'])));
        $year = mysqli_real_escape_string($con, $_POST["year"]);
        $month = mysqli_real_escape_string($con, $_POST["month"]);
        $examyear = mysqli_real_escape_string($con, $month . "-" . $year);
        $school = mysqli_real_escape_string($con, $_POST["school"]);
        $eyearadm = mysqli_real_escape_string($con, $_POST['exmonth']);
        $eyeargra = mysqli_real_escape_string($con, $_POST['exyear']);
        
        // Subject and Grade mappings - FIXED variable names
        $subjects = array();
        $grades = array();
        
        // Subject 1: Mathematics (addcourse1, grade1)
        $subjects[] = mysqli_real_escape_string($con, $_POST["addcourse1"]); // "General Mathematics" (readonly)
        $grades[] = mysqli_real_escape_string($con, $_POST["grade1"]);
        
        // Subject 2: English Language (addcourse2, grade2)
        $subjects[] = mysqli_real_escape_string($con, $_POST["addcourse2"]); // "English Language" (readonly)
        $grades[] = mysqli_real_escape_string($con, $_POST["grade2"]);
        
        // Subject 3: Biology (addcourse3, grade3)
        $subjects[] = mysqli_real_escape_string($con, $_POST["addcourse3"]); // "Biology" (readonly)
        $grades[] = mysqli_real_escape_string($con, $_POST["grade3"]);
        
        // Subject 4: Chemistry (addcourse4, grade4)
        $subjects[] = mysqli_real_escape_string($con, $_POST["addcourse4"]); // "Chemistry" (readonly)
        $grades[] = mysqli_real_escape_string($con, $_POST["grade4"]);
        
        // Subject 5: Physics (addcourse, grade)
        $subjects[] = mysqli_real_escape_string($con, $_POST["addcourse"]); // "Physics" (readonly)
        $grades[] = mysqli_real_escape_string($con, $_POST["grade"]);
        
        $pdp = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM exambodys WHERE exambodyid='$exambodp'"));
        if (empty($pdp['exambodyid'])) {
            $error = 'Exam Body Selected Not Available';
            $showModalEr = true;
        } else {
            $exambody = $pdp['exambodyid'];
            $check_sql = mysqli_query($con, "SELECT * FROM oleveldetail WHERE applid='{$_SESSION['staffid']}' AND noofsit='1'");
            if (mysqli_num_rows($check_sql) > 0) {
                $error = "First Sitting Details Already Exists. Use Update Option.";
                $showModalEr = true;
            } elseif ($eyearadm >= $eyeargra) {
                $error = "Graduating Year Must Be Greater Than Admit Year";
                $showModalEr = true;
            } else {
                // Insert into oleveldetail
                $qu = mysqli_query($con, "INSERT INTO oleveldetail (applid, exambody, examno, examyear, yearadmit, yeargrad, school, noofsit) VALUES ('{$_SESSION['staffid']}', '$exambody', '$examno', '$examyear', '$eyearadm', '$eyeargra', '$school', '1')");
                
                // Get the last inserted ID if needed, but we use examno as reference
                if ($qu) {
                    // Insert subjects and grades
                    $insert_success = true;
                    foreach ($subjects as $index => $subject) {
                        if (!empty($subject) && !empty($grades[$index])) {
                            $result_query = mysqli_query($con, "INSERT INTO olevelresultdetail (applid, examno, subject, grade) VALUES ('{$_SESSION['staffid']}', '$examno', '{$subjects[$index]}', '{$grades[$index]}')");
                            if (!$result_query) {
                                $insert_success = false;
                                $error = "Error inserting subject: " . mysqli_error($con);
                                $showModalEr = true;
                                break;
                            }
                        }
                    }
                    
                    if ($insert_success) {
                        $upload_success = true;
                        echo '<script>window.location.href = "applicantoleve?success=1";</script>';
                        exit();
                    }
                } else {
                    $error = "Error Occurred While Submitting Main Record";
                    $showModalEr = true;
                }
            }
        }
    }
}
    
    // Second Sitting Submit
// Second Sitting Submit
if (isset($_POST["submitoll"])) {
    if (empty($_POST["exambody"])) {
        $error = "Exam Body Required";
        $showModalEr = true;
    } elseif (empty($_POST["month"])) {
        $error = "Month Required";
        $showModalEr = true;
    } elseif (empty($_POST["year"])) {
        $error = "Year Required";
        $showModalEr = true;
    } elseif (empty($_POST["grade1"])) {
        $error = "Grade For Subject 1 Required";
        $showModalEr = true;
    } elseif (empty($_POST['addcourse1'])) {
        $error = "Subject 1 Required";
        $showModalEr = true;
    } else {
        $exambodp = mysqli_real_escape_string($con, $_POST["exambody"]);
        $examno = trim(mysqli_real_escape_string($con, strtoupper($_POST["examno"] ?? $_SESSION['staffid'])));
        $year = mysqli_real_escape_string($con, $_POST["year"]);
        $month = mysqli_real_escape_string($con, $_POST["month"]);
        $examyear = mysqli_real_escape_string($con, $month . "-" . $year);
        $school = mysqli_real_escape_string($con, $_POST["school"]);
        $eyearadm = mysqli_real_escape_string($con, $_POST['exmonth']);
        $eyeargra = mysqli_real_escape_string($con, $_POST['exyear']);
        
        // Collect subjects and grades for second sitting
        $subjects = array();
        $grades = array();
        
        for ($i = 1; $i <= 5; $i++) {
            $subject_field = "addcourse" . ($i == 5 ? "" : $i);
            $grade_field = "grade" . ($i == 5 ? "" : $i);
            
            if ($i == 5) {
                $subject_field = "addcourse";
                $grade_field = "grade";
            }
            
            if (!empty($_POST[$subject_field]) && !empty($_POST[$grade_field])) {
                $subjects[] = mysqli_real_escape_string($con, $_POST[$subject_field]);
                $grades[] = mysqli_real_escape_string($con, $_POST[$grade_field]);
            }
        }
        
        $pdp = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM exambodys WHERE exambodyid='$exambodp'"));
        if (empty($pdp['exambodyid'])) {
            $error = 'Exam Body Selected Not Available';
            $showModalEr = true;
        } else {
            $exambody = $pdp['exambodyid'];
            $check_sql = mysqli_query($con, "SELECT * FROM oleveldetail WHERE applid='{$_SESSION['staffid']}' AND noofsit='2'");
            if (mysqli_num_rows($check_sql) > 0) {
                $error = "Second Sitting Details Already Exists. Use Update Option.";
                $showModalEr = true;
            } elseif ($eyearadm >= $eyeargra) {
                $error = "Graduating Year Must Be Greater Than Admit Year";
                $showModalEr = true;
            } else {
                $qu = mysqli_query($con, "INSERT INTO oleveldetail (applid, exambody, examno, examyear, yearadmit, yeargrad, school, noofsit) VALUES ('{$_SESSION['staffid']}', '$exambody', '$examno', '$examyear', '$eyearadm', '$eyeargra', '$school', '2')");
                
                if ($qu) {
                    $insert_success = true;
                    foreach ($subjects as $index => $subject) {
                        if (!empty($subject) && !empty($grades[$index])) {
                            $result_query = mysqli_query($con, "INSERT INTO olevelresultdetail (applid, examno, subject, grade) VALUES ('{$_SESSION['staffid']}', '$examno', '{$subjects[$index]}', '{$grades[$index]}')");
                            if (!$result_query) {
                                $insert_success = false;
                                $error = "Error inserting subject: " . mysqli_error($con);
                                $showModalEr = true;
                                break;
                            }
                        }
                    }
                    
                    if ($insert_success) {
                        $upload_success = true;
                        echo '<script>window.location.href = "applicantoleve?success=2";</script>';
                        exit();
                    }
                } else {
                    $error = "Error Occurred While Submitting";
                    $showModalEr = true;
                }
            }
        }
    }
}    
   // Update First Sitting
if (isset($_POST['submitup'])) {
    $exambodp = mysqli_real_escape_string($con, $_POST["exambody"]);
    $examno = trim(mysqli_real_escape_string($con, strtoupper($_POST["examno"])));
    $examyear = mysqli_real_escape_string($con, $_POST["examyea"]);
    $school = mysqli_real_escape_string($con, $_POST["school"]);
    $eyearadm = mysqli_real_escape_string($con, $_POST['exmonth']);
    $eyeargra = mysqli_real_escape_string($con, $_POST['exyear']);
    $subje = $_POST['addcourse'];
    $grad = $_POST['grade'];
    $dis = $_POST['did'];
    
    // Update main details
    $upd = mysqli_query($con, "UPDATE oleveldetail SET exambody='$exambodp', examno='$examno', examyear='$examyear', yearadmit='$eyearadm', yeargrad='$eyeargra', school='$school' WHERE applid='{$_SESSION['staffid']}' AND noofsit='1'");
    
    // Update or insert subject-grade records
    foreach ($subje as $checks => $value) {
        if (!empty($subje[$checks]) && !empty($grad[$checks])) {
            // Check if this is an existing record or new
            if (strpos($dis[$checks], 'new_') === false && is_numeric($dis[$checks])) {
                // Update existing record
                mysqli_query($con, "UPDATE olevelresultdetail SET examno='$examno', subject='{$subje[$checks]}', grade='{$grad[$checks]}' WHERE applid='{$_SESSION['staffid']}' AND id='{$dis[$checks]}'");
            } else {
                // Insert new record
                mysqli_query($con, "INSERT INTO olevelresultdetail (applid, examno, subject, grade) VALUES ('{$_SESSION['staffid']}', '$examno', '{$subje[$checks]}', '{$grad[$checks]}')");
            }
        }
    }
    
    echo '<script>window.location.href = "applicantoleve?updated=1";</script>';
    exit();
}
    
    // Update Second Sitting
// Update Second Sitting
if (isset($_POST['submitupp'])) {
    $exambodp = mysqli_real_escape_string($con, $_POST["exambody"]);
    $examno = trim(mysqli_real_escape_string($con, strtoupper($_POST["examno"])));
    $examyear = mysqli_real_escape_string($con, $_POST["examyea"]);
    $school = mysqli_real_escape_string($con, $_POST["school"]);
    $eyearadm = mysqli_real_escape_string($con, $_POST['exmonth']);
    $eyeargra = mysqli_real_escape_string($con, $_POST['exyear']);
    $subje = $_POST['addcourse'];
    $grad = $_POST['grade'];
    $dis = $_POST['did'];
    
    // Update main details
    $upd = mysqli_query($con, "UPDATE oleveldetail SET exambody='$exambodp', examno='$examno', examyear='$examyear', yearadmit='$eyearadm', yeargrad='$eyeargra', school='$school' WHERE applid='{$_SESSION['staffid']}' AND noofsit='2'");
    
    // Update or insert subject-grade records
    foreach ($subje as $check => $value) {
        if (!empty($subje[$check]) && !empty($grad[$check])) {
            if (strpos($dis[$check], 'new_') === false && is_numeric($dis[$check])) {
                mysqli_query($con, "UPDATE olevelresultdetail SET examno='$examno', subject='{$subje[$check]}', grade='{$grad[$check]}' WHERE applid='{$_SESSION['staffid']}' AND id='{$dis[$check]}'");
            } else {
                mysqli_query($con, "INSERT INTO olevelresultdetail (applid, examno, subject, grade) VALUES ('{$_SESSION['staffid']}', '$examno', '{$subje[$check]}', '{$grad[$check]}')");
            }
        }
    }
    
    echo '<script>window.location.href = "applicantoleve?updated=2";</script>';
    exit();
}    
    // Check if user has 5 credits
    $has_five_credits = checkFiveCredits($con, $staffid);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title><?php echo htmlentities($orgname); ?> - O-Level Data</title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2b5f5a;
            --primary-dark: #1e4541;
            --primary-light: #4a7f7a;
            --primary-soft: #e8f3f1;
            --secondary-dark: #1a2a2a;
            --success: #10b981;
            --warning: #f59e0b;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #eef2f7 100%);
            min-height: 100vh;
        }
        
        .navbar-modern {
            background: linear-gradient(135deg, #1a2a2a 0%, #1e3a35 100%);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            padding: 0.8rem 0;
            border-bottom: 3px solid var(--primary);
        }
        
        .navbar-modern .navbar-brand {
            font-weight: 800;
            font-size: 1.3rem;
            color: white !important;
        }
        
        .navbar-modern .navbar-brand i { color: var(--primary); }
        
        .main-wrapper { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .content-container { display: flex; gap: 25px; flex-wrap: wrap; }
        .sidebar-wrapper { flex: 1; min-width: 260px; }
        .main-content { flex: 3; min-width: 300px; }
        
        .page-title {
            background: white;
            border-radius: 20px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .page-title h2 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--secondary-dark);
        }
        
        .page-title h2 i { color: var(--primary); margin-right: 10px; }
        .page-title small { font-size: 0.75rem; color: #64748b; display: block; margin-top: 5px; }
        
        .card-modern {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 35px -10px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 25px;
        }
        
        .card-header-modern {
            background: linear-gradient(135deg, #1a2a2a 0%, #1e3a35 100%);
            padding: 1rem 1.5rem;
            color: white;
        }
        
        .card-header-modern h4 {
            font-weight: 600;
            margin: 0;
            font-size: 1rem;
        }
        
        .card-header-modern h4 i { margin-right: 8px; color: var(--primary); }
        
        .card-body-modern { padding: 1.5rem; }
        
        .form-group { margin-bottom: 1rem; }
        
        .form-label {
            font-weight: 600;
            color: #1a2a2a;
            margin-bottom: 0.4rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .form-label i { color: var(--primary); }
        .required-star { color: #dc2626; font-size: 0.7rem; }
        
        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(43,95,90,0.1);
        }
        
        .subject-grade-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        
        .subject-select { flex: 2; min-width: 180px; }
        .grade-select { flex: 1; min-width: 120px; }
        
        .subject-grade-display {
            background: var(--primary-soft);
            border-radius: 12px;
            padding: 8px 12px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-primary-modern {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-primary-modern:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: translateY(-2px);
        }
        
        .btn-danger-modern {
            background: linear-gradient(95deg, #dc2626, #b91c1c);
            border: none;
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-success-modern {
            background: linear-gradient(95deg, var(--success), #059669);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16,185,129,0.3);
        }
        
        .btn-outline-modern {
            background: transparent;
            border: 1.5px solid var(--primary);
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 40px;
            color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .btn-outline-modern:hover {
            background: var(--primary-soft);
            transform: translateY(-2px);
        }
        
        .section-badge {
            display: inline-block;
            background: var(--primary-soft);
            color: var(--primary-dark);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .info-box {
            background: var(--primary-soft);
            border-radius: 12px;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 0.8rem;
            color: var(--primary-dark);
        }
        
        .success-box {
            background: #d1fae5;
            border-left: 4px solid var(--success);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid var(--warning);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .credit-check {
            background: white;
            border-radius: 16px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .credit-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .credit-badge.success { background: #d1fae5; color: #065f46; }
        .credit-badge.warning { background: #fef3c7; color: #92400e; }
        
        @media (max-width: 992px) {
            .sidebar-wrapper { flex: 100%; }
        }
        
        @media (max-width: 768px) {
            .main-wrapper { padding: 10px; }
            .card-body-modern { padding: 1rem; }
        }
        
        footer {
            background: #1a2421;
            color: #cfdfe9;
            padding: 1rem 0;
            margin-top: 2rem;
            border-radius: 20px;
            text-align: center;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card-modern { animation: fadeInUp 0.5s ease-out; }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #e2e8f0; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }
    </style>
</head>
<body>
    <?php include('include/topbar.php'); ?>
    
    <div class="main-wrapper">
        <div class="content-container">
            <div class="sidebar-wrapper">
                <?php include('include/leftbar.php'); ?>
            </div>
            
            <div class="main-content">
                <div class="page-title">
                    <h2><i class="fas fa-graduation-cap"></i> O-Level / Secondary School Data</h2>
                    <small><i class="fas fa-info-circle me-1"></i> Minimum of 5 credits required including Mathematics and English</small>
                </div>
                
                <!-- Credit Check Status -->
                <div class="credit-check">
                    <?php if ($has_five_credits): ?>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.2rem;"></i>
                                <strong style="color: var(--success);">✓ Credit Requirement Met!</strong>
                                <span class="ms-2 text-muted small">You have 5 credits including Mathematics and English.</span>
                            </div>
                            <span class="credit-badge success"><i class="fas fa-check me-1"></i> Eligible</span>
                        </div>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <i class="fas fa-exclamation-triangle" style="color: var(--warning); font-size: 1.2rem;"></i>
                                <strong style="color: #92400e;">Credit Requirement Not Yet Met</strong>
                                <span class="ms-2 text-muted small">You need 5 credits including Mathematics and English.</span>
                            </div>
                            <span class="credit-badge warning"><i class="fas fa-clock me-1"></i> Pending</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="row g-4">
                    <!-- First Sitting Column -->
                    <div class="col-lg-6">
                        <div class="card-modern" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-header-modern">
                                <h4><i class="fas fa-star-of-life"></i> First Sitting</h4>
                            </div>
                            <div class="card-body-modern">
                                <?php
                                $first_sitting = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM oleveldetail WHERE applid='{$_SESSION['staffid']}' AND noofsit='1'"));
                                $is_submitted = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$staffid' AND status='submitted'"));
                                $is_pending = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$staffid' AND (status='Pending' OR status='Approved')"));
                                
                                if ($first_sitting && $is_pending) {
                                    // View only mode
                                    $exam_body = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM exambodys WHERE exambodyid='{$first_sitting['exambody']}'"));
                                ?>
                                    <div class="info-box"><i class="fas fa-lock me-2"></i> This information is locked for review</div>
                                    
                                    <div class="form-group"><label class="form-label">Exam Body</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($exam_body['exambody'] ?? ''); ?>" readonly></div>
                                    <div class="form-group"><label class="form-label">Exam Number</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($first_sitting['examno']); ?>" readonly></div>
                                    <div class="form-group"><label class="form-label">Exam Year</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($first_sitting['examyear']); ?>" readonly></div>
                                    <div class="form-group"><label class="form-label">Year Entered</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($first_sitting['yearadmit']); ?>" readonly></div>
                                    <div class="form-group"><label class="form-label">Year Graduated</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($first_sitting['yeargrad']); ?>" readonly></div>
                                    <div class="form-group"><label class="form-label">School Name</label><textarea class="form-control-custom" readonly><?php echo htmlentities($first_sitting['school']); ?></textarea></div>
                                    
                                    <div class="section-badge">Subjects & Grades</div>
                                    <?php
                                    $results = mysqli_query($con, "SELECT * FROM olevelresultdetail WHERE examno='{$first_sitting['examno']}' AND applid='{$_SESSION['staffid']}' ORDER BY id");
                                    while ($result = mysqli_fetch_assoc($results)) {
                                    ?>
                                        <div class="subject-grade-display">
                                            <span><strong><?php echo htmlentities($result['subject']); ?></strong></span>
                                            <span class="badge" style="background: var(--primary-soft); color: var(--primary-dark);"><?php echo htmlentities($result['grade']); ?></span>
                                        </div>
                                    <?php } ?>
                                    
                                <?php } elseif ($first_sitting && $is_submitted) { ?>
                                    <!-- Editable mode for submitted status -->
                                    <form method="post">
                                        <?php
                                        $exam_body = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM exambodys WHERE exambodyid='{$first_sitting['exambody']}'"));
                                        ?>
                                        <div class="form-group"><label class="form-label">Exam Body</label>
                                            <select name="exambody" class="form-control-custom" required>
                                                <option value="<?php echo htmlentities($exam_body['exambodyid']); ?>"><?php echo htmlentities($exam_body['exambody']); ?></option>
                                                <?php $bodies = mysqli_query($con, "SELECT * FROM exambodys ORDER BY exambody");
                                                while ($row = mysqli_fetch_array($bodies)) echo "<option value='{$row['exambodyid']}'>{$row['exambody']}</option>"; ?>
                                            </select>
                                        </div>
                                        <div class="form-group"><label class="form-label">Exam Number</label><input type="text" class="form-control-custom" name="examno" value="<?php echo htmlentities($first_sitting['examno']); ?>" required></div>
                                        <div class="form-group"><label class="form-label">Exam Year</label><input type="text" class="form-control-custom" name="examyea" value="<?php echo htmlentities($first_sitting['examyear']); ?>" required></div>
                                        <div class="form-group"><label class="form-label">Year Entered</label><select name="exmonth" class="form-control-custom" required><?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'".($first_sitting['yearadmit']==$y?" selected":"").">$y</option>"; ?></select></div>
                                        <div class="form-group"><label class="form-label">Year Graduated</label><select name="exyear" class="form-control-custom" required><?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'".($first_sitting['yeargrad']==$y?" selected":"").">$y</option>"; ?></select></div>
                                        <div class="form-group"><label class="form-label">School Name</label><textarea name="school" class="form-control-custom" required><?php echo htmlentities($first_sitting['school']); ?></textarea></div>
                                        
                                        <!-- Subjects and Grades - Properly Populated -->
<div class="section-badge">Subjects & Grades</div>
<?php 
$results = mysqli_query($con, "SELECT * FROM olevelresultdetail WHERE examno='{$first_sitting['examno']}' AND applid='{$_SESSION['staffid']}' ORDER BY id");
$result_count = mysqli_num_rows($results);

if ($result_count > 0) {
    while ($result = mysqli_fetch_assoc($results)) { 
        ?>
        <div class="subject-grade-row">
            <select name="addcourse[]" class="form-control-custom subject-select" required>
                <option value="<?php echo htmlentities($result['subject']); ?>"><?php echo htmlentities($result['subject']); ?></option>
                <?php 
                $subjects = mysqli_query($con, "SELECT * FROM waecsubject ORDER BY subject");
                while ($row = mysqli_fetch_array($subjects)) {
                    $selected = ($row['subject'] == $result['subject']) ? 'selected' : '';
                    echo "<option value='{$row['subject']}' $selected>{$row['subject']}</option>";
                }
                ?>
            </select>
            <select name="grade[]" class="form-control-custom grade-select" required>
                <option value="<?php echo htmlentities($result['grade']); ?>"><?php echo htmlentities($result['grade']); ?></option>
                <?php 
                $grades = mysqli_query($con, "SELECT * FROM grades ORDER BY grade");
                while ($row = mysqli_fetch_array($grades)) {
                    $selected = ($row['grade'] == $result['grade']) ? 'selected' : '';
                    echo "<option value='{$row['grade']}' $selected>{$row['grade']}</option>";
                }
                ?>
            </select>
            <input type="hidden" name="did[]" value="<?php echo htmlentities($result['id']); ?>">
        </div>
    <?php 
    }
} else {
    // If no results found, show empty rows
    for ($i = 1; $i <= 5; $i++) { ?>
        <div class="subject-grade-row">
            <select name="addcourse[]" class="form-control-custom subject-select" required>
                <option value="">Select Subject</option>
                <?php 
                $subjects = mysqli_query($con, "SELECT * FROM waecsubject ORDER BY subject");
                while ($row = mysqli_fetch_array($subjects)) {
                    echo "<option value='{$row['subject']}'>{$row['subject']}</option>";
                }
                ?>
            </select>
            <select name="grade[]" class="form-control-custom grade-select" required>
                <option value="">Select Grade</option>
                <?php 
                $grades = mysqli_query($con, "SELECT * FROM grades ORDER BY grade");
                while ($row = mysqli_fetch_array($grades)) {
                    echo "<option value='{$row['grade']}'>{$row['grade']}</option>";
                }
                ?>
            </select>
            <input type="hidden" name="did[]" value="new_<?php echo $i; ?>">
        </div>
    <?php }
} ?>
                                        
                                        <div class="mt-4"><button type="submit" name="submitup" class="btn-primary-modern w-100"><i class="fas fa-save me-2"></i> Update First Sitting</button></div>
                                    </form>
                                    
                                <?php } else { ?>
                                    <!-- New registration form -->
                                    <form method="post">
                                        <div class="form-group"><label class="form-label">Exam Body <span class="required-star">*</span></label>
                                            <select name="exambody" class="form-control-custom" required><option value="">Select Exam Body</option>
                                                <?php $bodies = mysqli_query($con, "SELECT * FROM exambodys ORDER BY exambody");
                                                while ($row = mysqli_fetch_array($bodies)) echo "<option value='{$row['exambodyid']}'>{$row['exambody']}</option>"; ?>
                                            </select>
                                        </div>
                                        <div class="form-group"><label class="form-label">Exam Number</label><input type="text" class="form-control-custom" name="examno" placeholder="Enter Exam Number"></div>
                                        
                                        <div class="form-group"><label class="form-label">Exam Year <span class="required-star">*</span></label>
                                            <div class="row g-2"><div class="col-6"><select name="month" class="form-control-custom" required><option value="">Month</option>
                                                <?php $months = mysqli_query($con, "SELECT * FROM examyear ORDER BY year");
                                                while ($row = mysqli_fetch_array($months)) echo "<option value='{$row['year']}'>{$row['year']}</option>"; ?>
                                            </select></div><div class="col-6"><select name="year" class="form-control-custom" required><option value="">Year</option>
                                                <?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'>$y</option>"; ?>
                                            </select></div></div>
                                        </div>
                                        
                                        <div class="form-group"><label class="form-label">Year Entered <span class="required-star">*</span></label>
                                            <select name="exmonth" class="form-control-custom" required><option value="">Select Year</option>
                                                <?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'>$y</option>"; ?>
                                            </select>
                                        </div>
                                        <div class="form-group"><label class="form-label">Year Graduated <span class="required-star">*</span></label>
                                            <select name="exyear" class="form-control-custom" required><option value="">Select Year</option>
                                                <?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'>$y</option>"; ?>
                                            </select>
                                        </div>
                                        <div class="form-group"><label class="form-label">School Name <span class="required-star">*</span></label><textarea name="school" class="form-control-custom" required placeholder="Enter School Name"></textarea></div>
                                        
                                        <div class="section-badge">Core Subjects</div>
                                        <div class="subject-grade-row"><input type="text" class="form-control-custom subject-select" name="addcourse1"  value="General Mathematics" readonly><select name="grade1" class="form-control-custom grade-select" required><option value="">Select Grade</option><?php $grades = mysqli_query($con,"SELECT * FROM grades"); while($row=mysqli_fetch_array($grades)) echo "<option value='{$row['grade']}'>{$row['grade']}</option>"; ?></select></div>
                                        <div class="subject-grade-row"><input type="text" class="form-control-custom subject-select" name="addcourse2"  value="English Language" readonly><select name="grade2" class="form-control-custom grade-select" required><option value="">Select Grade</option><?php $grades = mysqli_query($con,"SELECT * FROM grades"); while($row=mysqli_fetch_array($grades)) echo "<option value='{$row['grade']}'>{$row['grade']}</option>"; ?></select></div>
                                        <div class="subject-grade-row"><input type="text" class="form-control-custom subject-select" name="addcourse3"  value="Biology" readonly><select name="grade3" class="form-control-custom grade-select" required><option value="">Select Grade</option><?php $grades = mysqli_query($con,"SELECT * FROM grades"); while($row=mysqli_fetch_array($grades)) echo "<option value='{$row['grade']}'>{$row['grade']}</option>"; ?></select></div>
                                        <div class="subject-grade-row"><input type="text" class="form-control-custom subject-select" name="addcourse4"  value="Chemistry" readonly><select name="grade4" class="form-control-custom grade-select" required><option value="">Select Grade</option><?php $grades = mysqli_query($con,"SELECT * FROM grades"); while($row=mysqli_fetch_array($grades)) echo "<option value='{$row['grade']}'>{$row['grade']}</option>"; ?></select></div>
                                        <div class="subject-grade-row"><input type="text" class="form-control-custom subject-select" name="addcourse"  value="Physics" readonly><select name="grade" class="form-control-custom grade-select" required><option value="">Select Grade</option><?php $grades = mysqli_query($con,"SELECT * FROM grades"); while($row=mysqli_fetch_array($grades)) echo "<option value='{$row['grade']}'>{$row['grade']}</option>"; ?></select></div>
                                        
                                        <div class="mt-4"><button type="submit" name="submitol" class="btn-danger-modern w-100"><i class="fas fa-save me-2"></i> Save First Sitting</button></div>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Second Sitting Column -->
                    <div class="col-lg-6">
                        <div class="card-modern" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header-modern">
                                <h4><i class="fas fa-layer-group"></i> Second Sitting (Optional)</h4>
                            </div>
                            <div class="card-body-modern">
                                <?php
                                $second_sitting = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM oleveldetail WHERE applid='{$_SESSION['staffid']}' AND noofsit='2'"));
                                
                                if ($second_sitting && $is_pending) {
                                    $exam_body2 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM exambodys WHERE exambodyid='{$second_sitting['exambody']}'"));
                                ?>
                                    <div class="info-box"><i class="fas fa-lock me-2"></i> Second sitting information is locked</div>
                                    <div class="form-group"><label class="form-label">Exam Body</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($exam_body2['exambody'] ?? ''); ?>" readonly></div>
                                    <div class="form-group"><label class="form-label">Exam Number</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($second_sitting['examno']); ?>" readonly></div>
                                    <div class="form-group"><label class="form-label">Exam Year</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($second_sitting['examyear']); ?>" readonly></div>
                                    
                                    <div class="section-badge">Subjects & Grades</div>
                                    <?php
                                    $results2 = mysqli_query($con, "SELECT * FROM olevelresultdetail WHERE examno='{$second_sitting['examno']}' AND applid='{$_SESSION['staffid']}' ORDER BY id");
                                    while ($result = mysqli_fetch_assoc($results2)) {
                                    ?>
                                        <div class="subject-grade-display">
                                            <span><strong><?php echo htmlentities($result['subject']); ?></strong></span>
                                            <span class="badge" style="background: var(--primary-soft); color: var(--primary-dark);"><?php echo htmlentities($result['grade']); ?></span>
                                        </div>
                                    <?php } ?>
                                    
                                <?php } elseif ($second_sitting && $is_submitted) { ?>
                                    <form method="post">
                                        <?php $exam_body2 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM exambodys WHERE exambodyid='{$second_sitting['exambody']}'")); ?>
                                        <div class="form-group"><label class="form-label">Exam Body</label><select name="exambody" class="form-control-custom" required><option value="<?php echo htmlentities($exam_body2['exambodyid']); ?>"><?php echo htmlentities($exam_body2['exambody']); ?></option><?php $bodies = mysqli_query($con,"SELECT * FROM exambodys"); while($row=mysqli_fetch_array($bodies)) echo "<option value='{$row['exambodyid']}'>{$row['exambody']}</option>"; ?></select></div>
                                        <div class="form-group"><label class="form-label">Exam Number</label><input type="text" class="form-control-custom" name="examno" value="<?php echo htmlentities($second_sitting['examno']); ?>" required></div>
                                        <div class="form-group"><label class="form-label">Exam Year</label><input type="text" class="form-control-custom" name="examyea" value="<?php echo htmlentities($second_sitting['examyear']); ?>" required></div>
                                        <div class="form-group"><label class="form-label">Year Entered</label><select name="exmonth" class="form-control-custom" required><?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'".($second_sitting['yearadmit']==$y?" selected":"").">$y</option>"; ?></select></div>
                                        <div class="form-group"><label class="form-label">Year Graduated</label><select name="exyear" class="form-control-custom" required><?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'".($second_sitting['yeargrad']==$y?" selected":"").">$y</option>"; ?></select></div>
                                        <div class="form-group"><label class="form-label">School Name</label><textarea name="school" class="form-control-custom" required><?php echo htmlentities($second_sitting['school']); ?></textarea></div>
                                        
                                        <!-- Subjects and Grades - Properly Populated -->
<div class="section-badge">Subjects & Grades</div>
<?php 
$results2 = mysqli_query($con, "SELECT * FROM olevelresultdetail WHERE examno='{$second_sitting['examno']}' AND applid='{$_SESSION['staffid']}' ORDER BY id");
$result_count2 = mysqli_num_rows($results2);

if ($result_count2 > 0) {
    while ($result = mysqli_fetch_assoc($results2)) { 
        ?>
        <div class="subject-grade-row">
            <select name="addcourse[]" class="form-control-custom subject-select" required>
                <option value="<?php echo htmlentities($result['subject']); ?>"><?php echo htmlentities($result['subject']); ?></option>
                <?php 
                $subjects = mysqli_query($con, "SELECT * FROM waecsubject ORDER BY subject");
                while ($row = mysqli_fetch_array($subjects)) {
                    $selected = ($row['subject'] == $result['subject']) ? 'selected' : '';
                    echo "<option value='{$row['subject']}' $selected>{$row['subject']}</option>";
                }
                ?>
            </select>
            <select name="grade[]" class="form-control-custom grade-select" required>
                <option value="<?php echo htmlentities($result['grade']); ?>"><?php echo htmlentities($result['grade']); ?></option>
                <?php 
                $grades = mysqli_query($con, "SELECT * FROM grades ORDER BY grade");
                while ($row = mysqli_fetch_array($grades)) {
                    $selected = ($row['grade'] == $result['grade']) ? 'selected' : '';
                    echo "<option value='{$row['grade']}' $selected>{$row['grade']}</option>";
                }
                ?>
            </select>
            <input type="hidden" name="did[]" value="<?php echo htmlentities($result['id']); ?>">
        </div>
    <?php 
    }
} else {
    // If no results found, show empty rows
    for ($i = 1; $i <= 5; $i++) { ?>
        <div class="subject-grade-row">
            <select name="addcourse[]" class="form-control-custom subject-select" required>
                <option value="">Select Subject</option>
                <?php 
                $subjects = mysqli_query($con, "SELECT * FROM waecsubject ORDER BY subject");
                while ($row = mysqli_fetch_array($subjects)) {
                    echo "<option value='{$row['subject']}'>{$row['subject']}</option>";
                }
                ?>
            </select>
            <select name="grade[]" class="form-control-custom grade-select" required>
                <option value="">Select Grade</option>
                <?php 
                $grades = mysqli_query($con, "SELECT * FROM grades ORDER BY grade");
                while ($row = mysqli_fetch_array($grades)) {
                    echo "<option value='{$row['grade']}'>{$row['grade']}</option>";
                }
                ?>
            </select>
            <input type="hidden" name="did[]" value="new_<?php echo $i; ?>">
        </div>
    <?php }
} ?>
                                        <div class="mt-4"><button type="submit" name="submitupp" class="btn-primary-modern w-100"><i class="fas fa-save me-2"></i> Update Second Sitting</button></div>
                                    </form>
                                    
                                <?php } else { ?>
                                    <form method="post">
                                        <div class="form-group"><label class="form-label">Exam Body</label><select name="exambody" class="form-control-custom" required><option value="">Select Exam Body</option><?php $bodies = mysqli_query($con,"SELECT * FROM exambodys"); while($row=mysqli_fetch_array($bodies)) echo "<option value='{$row['exambodyid']}'>{$row['exambody']}</option>"; ?></select></div>
                                        <div class="form-group"><label class="form-label">Exam Number</label><input type="text" class="form-control-custom" name="examno" placeholder="Enter Exam Number"></div>
                                        <div class="form-group"><label class="form-label">Exam Year</label><div class="row g-2"><div class="col-6"><select name="month" class="form-control-custom" required><option value="">Month</option><?php $months = mysqli_query($con,"SELECT * FROM examyear"); while($row=mysqli_fetch_array($months)) echo "<option value='{$row['year']}'>{$row['year']}</option>"; ?></select></div><div class="col-6"><select name="year" class="form-control-custom" required><option value="">Year</option><?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'>$y</option>"; ?></select></div></div></div>
                                        <div class="form-group"><label class="form-label">Year Entered</label><select name="exmonth" class="form-control-custom" required><option value="">Select Year</option><?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'>$y</option>"; ?></select></div>
                                        <div class="form-group"><label class="form-label">Year Graduated</label><select name="exyear" class="form-control-custom" required><option value="">Select Year</option><?php for($y=date('Y'); $y>=1990; $y--) echo "<option value='$y'>$y</option>"; ?></select></div>
                                        <div class="form-group"><label class="form-label">School Name</label><textarea name="school" class="form-control-custom" placeholder="Enter School Name"></textarea></div>
                                        
                                        <div class="section-badge">Subjects & Grades</div>
                                        <?php for($i=1; $i<=5; $i++) { ?>
                                            <div class="subject-grade-row">
                                                <select name="addcourse<?php echo $i; ?>" class="form-control-custom subject-select" required><option value="">Select Subject</option><?php $subs = mysqli_query($con,"SELECT * FROM waecsubjects ORDER BY subject"); while($s=mysqli_fetch_array($subs)) echo "<option value='{$s['subject']}'>{$s['subject']}</option>"; ?></select>
                                                <select name="grade<?php echo $i; ?>" class="form-control-custom grade-select" required><option value="">Select Grade</option><?php $grades = mysqli_query($con,"SELECT * FROM grades"); while($g=mysqli_fetch_array($grades)) echo "<option value='{$g['grade']}'>{$g['grade']}</option>"; ?></select>
                                            </div>
                                        <?php } ?>
                                        
                                        <div class="mt-4"><button type="submit" name="submitoll" class="btn-outline-modern w-100"><i class="fas fa-save me-2"></i> Save Second Sitting</button></div>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Continue Button - Only shows when 5 credits are achieved -->
                <?php if ($has_five_credits): ?>
                    <div class="success-box text-center">
                        <i class="fas fa-check-circle" style="font-size: 2rem; color: var(--success); margin-bottom: 10px;"></i>
                        <h5 style="color: var(--success);">Congratulations! You have met the credit requirement.</h5>
                        <p class="mb-3">You have successfully uploaded the required 5 O-Level credits including Mathematics and English.</p>
                        <a href="appsummary" class="btn-success-modern">
                            <i class="fas fa-arrow-right me-2"></i> Continue to Application Summary
                        </a>
                    </div>
                <?php else: ?>
                    <div class="warning-box">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> The "Continue" button will appear once you have uploaded 5 credits including Mathematics and English. 
                        You can combine results from First and Second Sitting to achieve this requirement.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <footer>
            <p class="mb-0 small">&copy; <span id="currentYear"></span> - <a href="#"><?php echo htmlentities($orgname . ', ' . $schadress); ?></a></p>
        </footer>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Error</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <i class="fas fa-times-circle" style="font-size: 2.5rem; color: #dc2626; margin-bottom: 0.5rem;"></i>
                    <p><?php echo htmlentities($error); ?></p>
                </div>
                <div class="modal-footer justify-content-center" style="border: none;">
                    <button class="btn btn-danger" data-bs-dismiss="modal" style="border-radius: 40px; padding: 6px 24px;">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        AOS.init({ duration: 600, once: true, offset: 20 });
        document.getElementById('currentYear').innerText = new Date().getFullYear();
        <?php if ($showModalEr) { ?>
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        <?php } ?>
    </script>
</body>
</html>
<?php } ?>