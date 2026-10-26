<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

// Check if user is logged in
if (strlen($_SESSION['staffid']) == "") {   
    header("Location: logout"); 
    exit();
}

$staffid = $_SESSION['staffid'];
include 'dataco.php';
$error = '';
$showModalEr = false;
$showModal = false;
$succ = '';

// Check if this is a new registration (show welcome modal)
$is_new_registration = isset($_SESSION['new_registration']) && $_SESSION['new_registration'] === true;
if ($is_new_registration) {
    // Clear the flag so it doesn't show again on refresh
    unset($_SESSION['new_registration']);
}

// Check session expiration
$now = time();
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location: logout");
    exit();
}
else{
// Get programme details
$pgk = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM program WHERE pname='$pgs'"));
$cat = $pgk['fid'];
$dedg = $pgk['did'];

$factm = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM faculty WHERE fid='$cat'"));
$facur = $factm['fname'];

$depnm = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM department WHERE deptc='$dedg'"));
$defr = $depnm['dept'];

// Get phone number for password display
$applicant_info = mysqli_fetch_assoc(mysqli_query($con, "SELECT phoneno, email FROM applicant WHERE applid='$staffid'"));
$applicant_phone = $applicant_info ? $applicant_info['phoneno'] : '';

/**
 * Handle New Personal Data Submission
 */
if (isset($_POST["submitpd"])) {
    // Validation
    $required_fields = [
        'day' => 'Day Required',
        'month' => 'Month Required',
        'year' => 'Year Required',
        'sex' => 'Gender Required',
        'marital' => 'Marital Status Required',
        'country' => 'Nationality Required'
    ];
    
    foreach ($required_fields as $field => $message) {
        if (empty($_POST[$field])) {
            $error = $message;
            $showModalEr = true;
            break;
        }
    }
    
    if (empty($error)) {
        // Sanitize inputs
        $firstname = mysqli_real_escape_string($con, $_POST["firstname"]);
        $surname = mysqli_real_escape_string($con, $_POST["surname"]);
        $nin = mysqli_real_escape_string($con, $_POST["nin"]);
        $lastname = mysqli_real_escape_string($con, $_POST["lastname"]);
        $email = mysqli_real_escape_string($con, strtolower($_POST["email"]));
        $phone = mysqli_real_escape_string($con, $phf);
        $day = mysqli_real_escape_string($con, $_POST["day"]);
        $year = mysqli_real_escape_string($con, $_POST["year"]);
        $jamb_score = mysqli_real_escape_string($con, $_POST["jamb_score"]);
        $jamb_no = mysqli_real_escape_string($con, $_POST["jamb_no"]);
        $month = mysqli_real_escape_string($con, $_POST["month"]);
        $dob = $year . "-" . $month . "-" . $day;
        $address = mysqli_real_escape_string($con, $_POST["address"]);
        $town = mysqli_real_escape_string($con, $_POST["town"]);
        $sex = mysqli_real_escape_string($con, $_POST["sex"]);
        $disability = mysqli_real_escape_string($con, $_POST["disability"]);
        $fact = mysqli_real_escape_string($con, $cat);
        $depn = mysqli_real_escape_string($con, $dedg);
        $marital = mysqli_real_escape_string($con, $_POST["marital"]);
        $country = mysqli_real_escape_string($con, $_POST["country"]);
        $programs = $pgs;
        
        // Handle file upload
        $filenamg = basename($_FILES['passport']['name']);
        $filetmp = $_FILES['passport']['tmp_name'];
        $filesize = $_FILES['passport']['size'];
        // UPDATED: Added PNG and PDF to allowed extensions
        $extension = array("png", "jpg", "jpeg", "PNG", "JPG", "JPEG");
        $file_extension = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
        
        // Validate file
        if (!file_exists($_FILES["passport"]["tmp_name"])) {
            $error = "Choose image file to upload.";
            $showModalEr = true;
        } elseif (!in_array($file_extension, $extension)) {
            $error = "Upload valid images. Only PNG, JPG, and JPEG are allowed.";
            $showModalEr = true;
        } elseif ($_FILES["passport"]["size"] > 5000000) { // UPDATED: Changed from 4MB to 5MB
            $error = "Image size exceeds 5MB.";
            $showModalEr = true;
        } else {
            // Generate unique filename
            $nwtourimg = explode(".", $filenamg);
            $fileExt = end($nwtourimg);
            $res = trim(str_ireplace(array('\'', '"', ',', ';', '<', '>', '/', '-', '_', ''), '', $staffid));
            $fileBase = $res . "." . $fileExt;
            $dir = "photo/";
            $final_dir = $dir . $fileBase;
            
            // Process based on nationality
            if ($country == "Nigeria") {
                if (empty($_POST["state"])) {
                    $error = "Please select state";
                    $showModalEr = true;
                } elseif (empty($_POST["lga"])) {
                    $error = "Please select LGA";
                    $showModalEr = true;
                } elseif ($filesize > 5000000) { // UPDATED: Changed from 4MB to 5MB
                    $error = "File too large, filesize should be 5MB max";
                    $showModalEr = true;
                } else {
                    // Check if already registered
                    $check_sql = mysqli_query($con, "SELECT * FROM personaldata WHERE applid='$staffid'");
                    if (mysqli_num_rows($check_sql) == 1) {
                        $error = "You have already registered";
                        $showModalEr = true;
                    } else {
                        // Check for duplicate email/phone
                        $duplicate_sql = mysqli_query($con, "SELECT * FROM personaldata WHERE (email='$email' OR phone='$phone') AND acadsession='$admy'");
                        if (mysqli_num_rows($duplicate_sql) > 0) {
                            $row = mysqli_fetch_assoc($duplicate_sql);
                            if ($email == $row['email']) {
                                $error = "Email already exists";
                            } elseif ($phone == $row['phone']) {
                                $error = "Phone number already exists";
                            }
                            $showModalEr = true;
                        } else {
                            $state = mysqli_real_escape_string($con, $_POST["state"]);
                            $lga = mysqli_real_escape_string($con, $_POST["lga"]);
                            $staname = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM state WHERE state_ID='$state'"));
                            $sate = $staname['name'];
                            
                            if (move_uploaded_file($filetmp, $final_dir)) {
                                $insert_sql = "INSERT INTO personaldata (
                                    firstname, surname, lastname, applid, email, phone, dob, address, gender, nin, 
                                    marital, nationality, state, lga, town, acadsession, faculty, department, programme, 
                                    passport, jamb_score, jamb_no, status, disability, date
                                ) VALUES (
                                    '$firstname','$surname','$lastname','$staffid','$email','$phone','$dob','$address',
                                    '$sex','$nin','$marital','$country','$sate','$lga','$town','$admy','$fact','$depn',
                                    '$programs','$final_dir','$jamb_score','$jamb_no','active','$disability',NOW()
                                )";
                                
                                if (mysqli_query($con, $insert_sql)) {
                                    header("Location: appsummary");
                                    exit();
                                } else {
                                    $error = "Sorry, an error occurred when submitting";
                                    $showModalEr = true;
                                }
                            }
                        }
                    }
                }
            } elseif ($country == "Foreign") {
                if (empty($_POST['states'])) {
                    $error = "Please select Country";
                    $showModalEr = true;
                } elseif ($filesize > 5000000) { // UPDATED: Changed from 4MB to 5MB
                    $error = "File too large, filesize should be 5MB max";
                    $showModalEr = true;
                } else {
                    $check_sql = mysqli_query($con, "SELECT * FROM personaldata WHERE applid='$staffid'");
                    if (mysqli_num_rows($check_sql) == 1) {
                        $error = "You have already registered";
                        $showModalEr = true;
                    } else {
                        $duplicate_sql = mysqli_query($con, "SELECT * FROM personaldata WHERE (email='$email' OR phone='$phone') AND acadsession='$admy'");
                        if (mysqli_num_rows($duplicate_sql) > 0) {
                            $row = mysqli_fetch_assoc($duplicate_sql);
                            if ($email == $row['email']) {
                                $error = "Email already exists";
                            } elseif ($phone == $row['phone']) {
                                $error = "Phone number already exists";
                            }
                            $showModalEr = true;
                        } else {
                            $state = mysqli_real_escape_string($con, $_POST['states']);
                            
                            if (move_uploaded_file($filetmp, $final_dir)) {
                                $insert_sql = "INSERT INTO personaldata (
                                    firstname, surname, lastname, applid, email, phone, dob, address, gender, nin, 
                                    marital, nationality, state, town, acadsession, faculty, department, programme, 
                                    passport, status, disability, date
                                ) VALUES (
                                    '$firstname','$surname','$lastname','$staffid','$email','$phone','$dob','$address',
                                    '$sex','$nin','$marital','$country','$state','$town','$admy','$fact','$depn',
                                    '$programs','$final_dir','active','$disability',NOW()
                                )";
                                
                                if (mysqli_query($con, $insert_sql)) {
                                    header("Location: appsummary");
                                    exit();
                                } else {
                                    $error = "Sorry, an error occurred when submitting";
                                    $showModalEr = true;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

/**
 * Handle Personal Data Update
 */
if (isset($_POST['submitpdu'])) {
    // Sanitize inputs
    $firstname = mysqli_real_escape_string($con, $_POST["firstname"]);
    $surname = mysqli_real_escape_string($con, $_POST["surname"]);
    $nin = mysqli_real_escape_string($con, $_POST["nin"]);
    $othername = mysqli_real_escape_string($con, $_POST["othername"]);
    $email = mysqli_real_escape_string($con, strtolower($_POST["email"]));
    $phone = mysqli_real_escape_string($con, $_POST["phone"]);
    $dob = mysqli_real_escape_string($con, $_POST["dobs"]);
    $address = mysqli_real_escape_string($con, $_POST["address"]);
    $sex = mysqli_real_escape_string($con, $_POST["sex"]);
    $disability = mysqli_real_escape_string($con, $_POST["disability"]);
    $marital = mysqli_real_escape_string($con, $_POST["marital"]);
    $country = mysqli_real_escape_string($con, $_POST["country"]);
    $tod = date('Y-m-d');
    
    // Get faculty, department, programme from form or keep existing
    $fac = mysqli_real_escape_string($con, $_POST['faculty'] ?? $cat);
    $det = mysqli_real_escape_string($con, $_POST['depart'] ?? $dedg);
    $prg = mysqli_real_escape_string($con, $_POST['programme'] ?? $pgs);
    
    $pd = mysqli_fetch_assoc(mysqli_query($con, "SELECT pname FROM program WHERE pid='$prg'"));
    $prohk = ($pd && $pd['pname'] != '') ? $pd['pname'] : $prg;
    
    if ($country == "Nigeria") {
        $state = mysqli_real_escape_string($con, $_POST["state"]);
        $lga = mysqli_real_escape_string($con, $_POST["lga"]);
        $staname = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM state WHERE state_ID='$state' OR name='$state'"));
        $sate = $staname['name'] ?? $state;
        
        $update_sql = "UPDATE personaldata SET 
            faculty='$fac', department='$det', programme='$prohk',
            firstname='$firstname', surname='$surname', lastname='$othername',
            gender='$sex', nin='$nin', marital='$marital', dob='$dob',
            phone='$phone', email='$email', state='$sate', address='$address',
            nationality='$country', lga='$lga', disability='$disability', date='$tod'
            WHERE applid='$staffid'";
        
        if (mysqli_query($con, $update_sql)) {
            $showModal = true;
            $succ = "Details Successfully Updated";
        } else {
            $error = "Error Occurred While Updating: " . mysqli_error($con);
            $showModalEr = true;
        }
    } else {
        $state = mysqli_real_escape_string($con, $_POST['country']);
        
        $update_sql = "UPDATE personaldata SET 
            faculty='$fac', department='$det', programme='$prohk',
            firstname='$firstname', surname='$surname', lastname='$othername',
            gender='$sex', nin='$nin', marital='$marital', dob='$dob',
            phone='$phone', email='$email', address='$address',
            state='$state', country='Foreign', disability='$disability', date='$tod'
            WHERE applid='$staffid'";
        
        if (mysqli_query($con, $update_sql)) {
            $showModal = true;
            $succ = "Details Successfully Updated";
        } else {
            $error = "Error Occurred While Updating: " . mysqli_error($con);
            $showModalEr = true;
        }
    }
}

// Fetch existing personal data
$stad = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM personaldata WHERE applid='$staffid'"));
$stanam_status = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$staffid' AND (status='Pending' OR status='Approved')"));
$is_submitted = ($stanam_status > 0);
$has_data = ($stad != null);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title><?php echo htmlentities($orgname); ?> - Personal Data</title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2b5f5a;
            --primary-dark: #1e4541;
            --primary-light: #4a7f7a;
            --primary-soft: #e8f3f1;
            --secondary-dark: #1a2a2a;
            --secondary-light: #2c3e2f;
            --gray-bg: #f8fafc;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #eef2f7 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Modern Navbar */
        .navbar-modern {
            background: linear-gradient(135deg, #1a2a2a 0%, #1e3a35 100%);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            padding: 0.8rem 0;
            border-bottom: 3px solid var(--primary);
        }
        
        .navbar-modern .navbar-brand {
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.3px;
            color: white !important;
        }
        
        .navbar-modern .navbar-brand i {
            color: var(--primary);
        }
        
        .navbar-modern .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 0.25rem;
            transition: 0.2s;
            border-radius: 40px;
        }
        
        .navbar-modern .nav-link:hover {
            background: rgba(43,95,90,0.2);
            transform: translateY(-2px);
            color: var(--primary) !important;
        }
        
        /* App ID Badge in Navbar */
        .appid-badge {
            background: rgba(43,95,90,0.3);
            border-radius: 40px;
            padding: 5px 15px;
            margin-left: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .appid-badge i {
            margin-right: 5px;
            color: var(--primary);
        }
        
        /* Sidebar Modern */
        .sidebar-modern {
            background: white;
            border-radius: 0 24px 24px 0;
            box-shadow: 2px 0 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .sidebar-modern .side-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-modern .side-nav li {
            margin: 5px 0;
        }
        
        .sidebar-modern .side-nav li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 0 10px;
        }
        
        .sidebar-modern .side-nav li a:hover {
            background: var(--primary-soft);
            color: var(--primary);
            transform: translateX(5px);
        }
        
        .sidebar-modern .side-nav li a i {
            width: 24px;
            color: var(--primary);
        }
        
        /* Main Content */
        .main-page {
            padding: 20px;
        }
        
        .page-title-div {
            margin-bottom: 20px;
        }
        
        .page-title-div h2 {
            font-weight: 700;
            color: var(--secondary-dark);
            font-size: 1.5rem;
        }
        
        /* Form Card */
        .form-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 35px -10px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 25px;
        }
        
        .form-header {
            background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary-light) 100%);
            padding: 1rem 1.5rem;
            color: white;
        }
        
        .form-header h4 {
            font-weight: 600;
            margin: 0;
            font-size: 1.1rem;
        }
        
        .form-header h4 i {
            margin-right: 8px;
        }
        
        .form-body {
            padding: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #1a2a2a;
            margin-bottom: 0.4rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .form-label i {
            color: var(--primary);
            font-size: 0.85rem;
        }
        
        .required-star {
            color: #dc2626;
            font-size: 0.7rem;
            margin-left: 2px;
        }
        
        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: white;
            font-family: 'Inter', sans-serif;
        }
        
        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(43,95,90,0.1);
        }
        
        .form-control-custom:read-only,
        .form-control-custom[readonly] {
            background: #f1f5f9;
            cursor: not-allowed;
        }
        
        select.form-control-custom {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px;
        }
        
        textarea.form-control-custom {
            resize: vertical;
            min-height: 80px;
        }
        
        .btn-submit {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 40px;
            width: 100%;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-submit:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(43,95,90,0.3);
            color: white;
        }
        
        /* Image Upload */
        .image-upload-container {
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .profile-preview {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
            background: #f1f5f9;
            margin-bottom: 1rem;
        }
        
        /* Date Select Row */
        .date-select-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .date-select-row select {
            flex: 1;
            min-width: 90px;
        }
        
        /* Info Display */
        .info-display {
            background: var(--primary-soft);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .info-display p {
            margin: 0;
            font-size: 0.85rem;
        }
        
        /* Welcome Modal Styles */
        .welcome-modal-content {
            border-radius: 24px;
            border: none;
            overflow: hidden;
        }
        
        .welcome-modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 1.5rem;
            text-align: center;
        }
        
        .welcome-modal-header i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        
        .credentials-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid var(--primary);
        }
        
        .credentials-box p {
            margin: 0.5rem 0;
            font-family: monospace;
            font-size: 1rem;
        }
        
        .credentials-box .label {
            font-weight: 600;
            color: var(--primary-dark);
            font-family: 'Inter', sans-serif;
        }
        
        .copy-btn {
            background: var(--primary-soft);
            border: none;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            color: var(--primary);
            margin-left: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .copy-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        .warning-text {
            background: #fef3c7;
            border-radius: 12px;
            padding: 0.8rem;
            font-size: 0.8rem;
            color: #92400e;
            margin-top: 1rem;
        }
        
        /* Footer */
        footer {
            background: #1a2421;
            color: #cfdfe9;
            padding: 1rem 0;
            margin-top: 2rem;
            border-top: 3px solid var(--primary);
        }
        
        footer a {
            color: #cfdfe9;
            text-decoration: none;
        }
        
        footer a:hover {
            color: var(--primary) !important;
        }
        
        /* Modal */
        .modal-content-custom {
            border-radius: 24px;
            border: none;
            overflow: hidden;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-body {
                padding: 1rem;
            }
            .date-select-row {
                flex-direction: column;
            }
            .date-select-row select {
                width: 100%;
            }
            .profile-preview {
                width: 120px;
                height: 120px;
            }
            .appid-badge {
                display: none;
            }
        }
        
        /* Two column layout */
        .two-columns {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        @media (max-width: 992px) {
            .two-columns {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-card {
            animation: fadeInUp 0.5s ease-out;
        }
        
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #e2e8f0;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        
        /* Print styles for modal */
        @media print {
            body * {
                visibility: hidden;
            }
            #welcomeModal, #welcomeModal * {
                visibility: visible;
            }
            #welcomeModal {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
            }
            .modal-footer, .btn-close, .copy-btn {
                display: none !important;
            }
            .modal-dialog {
                margin: 0;
                width: 100%;
                max-width: 100%;
            }
            .modal-content {
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <?php include('include/topbar.php'); ?>
        
        <div class="content-wrapper">
            <div class="content-container">
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-12">
                                <h2><i class="fas fa-user-edit me-2" style="color: var(--primary);"></i>Personal Data</h2>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <?php include('include/leftbar.php'); ?>
                            </div>
                            
                            <div class="col-md-9">
                                <?php
                                $stad = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."'"));
                                if ($stad == '') {
                                ?>
                                <!-- New Registration Form -->
                                <div class="form-card" data-aos="fade-up">
                                    <div class="form-header">
                                        <h4><i class="fas fa-user-plus"></i> Complete Your Personal Data</h4>
                                    </div>
                                    <div class="form-body">
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="two-columns">
                                                <!-- Left Column -->
                                                <div>
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-user"></i>First Name <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control-custom" name="firstname" value="<?php echo $firstname; ?>" maxlength="17" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-user-friends"></i>Surname <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control-custom" name="surname" value="<?php echo $lastname; ?>" maxlength="17" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-user-tag"></i>Other Name</label>
                                                        <input type="text" class="form-control-custom" name="lastname" value="<?php echo $other; ?>" maxlength="17">
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-id-card"></i>Application ID</label>
                                                        <input type="text" class="form-control-custom" name="staffid" value="<?php echo $staffid; ?>" readonly>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-envelope"></i>Email</label>
                                                        <input type="email" class="form-control-custom" name="email" value="<?php echo $has; ?>" readonly>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-phone-alt"></i>Phone Number</label>
                                                        <input type="tel" class="form-control-custom" name="phone" value="<?php echo $phf; ?>" readonly>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-map-marker-alt"></i>Home Address <span class="required-star">*</span></label>
                                                        <textarea class="form-control-custom" name="address" required></textarea>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-id-card"></i>NIN <span class="required-star">*</span></label>
                                                        <input type="tel" class="form-control-custom" name="nin" maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Enter NIN" required>
                                                    </div>
                                                </div>
                                                
                                                <!-- Right Column -->
                                                <div>
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-calendar-alt"></i>Date of Birth <span class="required-star">*</span></label>
                                                        <div class="date-select-row">
                                                            <select name="day" class="form-control-custom" required>
                                                                <option value="">Day</option>
                                                                <?php for($i=1;$i<=31;$i++) echo "<option value='".str_pad($i,2,'0',STR_PAD_LEFT)."'>".str_pad($i,2,'0',STR_PAD_LEFT)."</option>"; ?>
                                                            </select>
                                                            <select name="month" class="form-control-custom" required>
                                                                <option value="">Month</option>
                                                                <option value="01">January</option><option value="02">February</option><option value="03">March</option>
                                                                <option value="04">April</option><option value="05">May</option><option value="06">June</option>
                                                                <option value="07">July</option><option value="08">August</option><option value="09">September</option>
                                                                <option value="10">October</option><option value="11">November</option><option value="12">December</option>
                                                            </select>
                                                            <select name="year" class="form-control-custom" required>
                                                                <option value="">Year</option>
                                                                <?php for($year=1960;$year<=date('Y')-10;$year++) echo "<option value='$year'>$year</option>"; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-venus-mars"></i>Gender <span class="required-star">*</span></label>
                                                        <select name="sex" class="form-control-custom" required>
                                                            <option value="">Select Gender</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM genders ORDER BY gender");
                                                            while($row = mysqli_fetch_array($res)) echo "<option value='".$row["gender"]."'>".$row["gender"]."</option>";
                                                            ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-heart"></i>Marital Status <span class="required-star">*</span></label>
                                                        <select name="marital" class="form-control-custom" required>
                                                            <option value="">Select Marital Status</option>
                                                            <?php
                                                            $resz = mysqli_query($con, "SELECT * FROM maritals ORDER BY marital");
                                                            while($row = mysqli_fetch_array($resz)) echo "<option value='".$row["marital"]."'>".$row["marital"]."</option>";
                                                            ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-globe"></i>Disability <span class="required-star">*</span></label>
                                                        <select name="disability" class="form-control-custom" required>
                                                            <option value="">Select Disability</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM disability ORDER BY id");
                                                            while($row = mysqli_fetch_array($res)) echo "<option value='".$row["status"]."'>".$row["status"]."</option>";
                                                            ?>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="form-label"><i class="fas fa-flag"></i>Nationality <span class="required-star">*</span></label>
                                                        <select name="country" class="form-control-custom" id="nationality" onchange="toggleCountryFields()" required>
                                                            <option value="">Select Nationality</option>
                                                            <option value="Nigeria">Nigeria</option>
                                                            <option value="Foreign">Foreign</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div id="nigeriaFields" style="display:none;">
                                                        <div class="form-group">
                                                            <label class="form-label"><i class="fas fa-map-marker-alt"></i>State</label>
                                                            <select name="state" class="form-control-custom" id="state" onchange="loadLGA()">
                                                                <option value="">Select State</option>
                                                                <?php
                                                                $resz = mysqli_query($con, "SELECT * FROM state ORDER BY name");
                                                                while($row = mysqli_fetch_array($resz)) echo "<option value='".$row["state_ID"]."'>".$row["name"]."</option>";
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label"><i class="fas fa-city"></i>LGA</label>
                                                            <select name="lga" class="form-control-custom" id="lga">
                                                                <option value="">Select LGA</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div id="foreignFields" style="display:none;">
                                                        <div class="form-group">
                                                            <label class="form-label"><i class="fas fa-globe"></i>Country</label>
                                                            <select name="states" class="form-control-custom">
                                                                <option value="">Select Country</option>
                                                                <?php
                                                                $resz = mysqli_query($con, "SELECT * FROM countries ORDER BY country ASC");
                                                                while($row = mysqli_fetch_array($resz)) echo "<option value='".$row["country"]."'>".$row["country"]."</option>";
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php
                                                    $stanam = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$staffid' AND (apptype='ND')"));
                                                    if($stanam > 0) {
                                                    ?>
                                                    <div class="info-display">
                                                        <p><strong><i class="fas fa-graduation-cap"></i> JAMB Information</strong></p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">JAMB Registration Number <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control-custom" name="jamb_no" maxlength="17">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">JAMB Score <span class="required-star">*</span></label>
                                                        <input type="text" class="form-control-custom" name="jamb_score" maxlength="17">
                                                    </div>
                                                    <?php } ?>
                                                    
                                                    <div class="info-display">
                                                        <p><strong><i class="fas fa-university"></i> Programme Information</strong></p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">School</label>
                                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($facur); ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Department</label>
                                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($defr); ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Programme</label>
                                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($pgs); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="image-upload-container">
                                                <small class="text-muted">UPLOAD PASSPORT: UPLOAD RED OR WHITE BACKGROUND PASSPORT</small>
                                                <img id="blah" class="profile-preview" src="img/avater.png" alt="Passport Photo">
                                                <input type="file" class="form-control-custom" name="passport" accept=".jpeg,.jpg,.png" onchange="readURL(this);" required style="max-width: 250px; margin: 0 auto;">
                                                <small class="text-muted d-block mt-1">Allowed formats: JPG, JPEG, PNG (Max 5MB)</small>
                                            </div>
                                            
                                            <button type="submit" name="submitpd" class="btn-submit">
                                                <i class="fas fa-save me-2"></i> Submit Data
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <?php } else { 
                                    $stanam = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$staffid' AND (status='Pending' OR status='Approved')"));
                                    if($stanam > 0) {
                                ?>
                                <!-- View Only Form -->
                                <div class="form-card" data-aos="fade-up">
                                    <div class="form-header">
                                        <h4><i class="fas fa-eye"></i> Personal Data (View Only)</h4>
                                    </div>
                                    <div class="form-body">
                                        <div class="two-columns">
                                            <div>
                                                <div class="form-group"><label class="form-label">First Name</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['firstname']); ?>" readonly></div>
                                                <div class="form-group"><label class="form-label">Surname</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['surname']); ?>" readonly></div>
                                                <div class="form-group"><label class="form-label">Other Name</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['lastname']); ?>" readonly></div>
                                                <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control-custom" value="<?php echo htmlentities($stad['email']); ?>" readonly></div>
                                                <div class="form-group"><label class="form-label">Phone</label><input type="tel" class="form-control-custom" value="<?php echo htmlentities($stad['phone']); ?>" readonly></div>
                                                <div class="form-group"><label class="form-label">NIN</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['nin']); ?>" readonly></div>
                                            </div>
                                            <div>
                                                <div class="form-group"><label class="form-label">Date of Birth</label><input type="date" class="form-control-custom" value="<?php echo htmlentities($stad['dob']); ?>" readonly></div>
                                                <div class="form-group"><label class="form-label">Gender</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['gender']); ?>" readonly></div>
                                                <div class="form-group"><label class="form-label">Marital Status</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['marital']); ?>" readonly></div>
                                                <div class="form-group"><label class="form-label">Disability</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['disability']); ?>" readonly></div>
                                                <div class="form-group"><label class="form-label">Nationality</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['nationality']); ?>" readonly></div>
                                                <?php if($stad['nationality'] == "Nigeria") { ?>
                                                    <div class="form-group"><label class="form-label">State</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['state']); ?>" readonly></div>
                                                    <div class="form-group"><label class="form-label">LGA</label><input type="text" class="form-control-custom" value="<?php echo htmlentities($stad['lga']); ?>" readonly></div>
                                                <?php } ?>
                                                <div class="form-group"><label class="form-label">Address</label><textarea class="form-control-custom" readonly><?php echo htmlentities($stad['address']); ?></textarea></div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button type="button" class="btn-submit" style="width: auto; padding: 10px 30px;" disabled>
                                                <i class="fas fa-check-circle me-2"></i> Data Submitted
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <!-- Editable Form for Submitted Status -->
                                <div class="form-card" data-aos="fade-up">
                                    <div class="form-header">
                                        <h4><i class="fas fa-edit"></i> Edit Personal Data</h4>
                                    </div>
                                    <div class="form-body">
                                        <form method="post">
                                            <div class="two-columns">
                                                <div>
                                                    <div class="form-group"><label class="form-label">First Name</label><input type="text" class="form-control-custom" name="firstname" value="<?php echo htmlentities($stad['firstname']); ?>"></div>
                                                    <div class="form-group"><label class="form-label">Surname</label><input type="text" class="form-control-custom" name="surname" value="<?php echo htmlentities($stad['surname']); ?>"></div>
                                                    <div class="form-group"><label class="form-label">Other Name</label><input type="text" class="form-control-custom" name="othername" value="<?php echo htmlentities($stad['lastname']); ?>"></div>
                                                    <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control-custom" name="email" value="<?php echo htmlentities($stad['email']); ?>"></div>
                                                    <div class="form-group"><label class="form-label">Phone</label><input type="tel" class="form-control-custom" name="phone" value="<?php echo htmlentities($stad['phone']); ?>"></div>
                                                    <div class="form-group"><label class="form-label">Date of Birth</label><input type="date" class="form-control-custom" name="dobs" value="<?php echo htmlentities($stad['dob']); ?>"></div>
                                                </div>
                                                <div>
                                                    <div class="form-group"><label class="form-label">Gender</label><select name="sex" class="form-control-custom"><?php $res = mysqli_query($con,"SELECT * FROM genders"); while($row=mysqli_fetch_array($res)) echo "<option value='".$row["gender"]."' ".($stad['gender']==$row["gender"]?"selected":"").">".$row["gender"]."</option>"; ?></select></div>
                                                    <div class="form-group"><label class="form-label">Marital Status</label><select name="marital" class="form-control-custom"><?php $resz = mysqli_query($con,"SELECT * FROM maritals"); while($row=mysqli_fetch_array($resz)) echo "<option value='".$row["marital"]."' ".($stad['marital']==$row["marital"]?"selected":"").">".$row["marital"]."</option>"; ?></select></div>
                                                    <div class="form-group"><label class="form-label">Disability</label><select name="disability" class="form-control-custom"><?php $res = mysqli_query($con,"SELECT * FROM disability"); while($row=mysqli_fetch_array($res)) echo "<option value='".$row["status"]."' ".($stad['disability']==$row["status"]?"selected":"").">".$row["status"]."</option>"; ?></select></div>
                                                    <div class="form-group"><label class="form-label">Home Address</label><textarea class="form-control-custom" name="address"><?php echo htmlentities($stad['address']); ?></textarea></div>
                                                </div>
                                            </div>
                                            <button type="submit" name="submitpdu" class="btn-submit"><i class="fas fa-save me-2"></i> Update Data</button>
                                        </form>
                                    </div>
                                </div>
                                <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <footer>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0 small">&copy; <span id="currentYear"></span> - <a href="#"><?php echo htmlentities($orgname . ', ' . $schadress); ?></a></p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0 small"><i class="fas fa-shield-alt me-1" style="color: var(--primary);"></i> Secure Portal</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Welcome Modal - Shows Application ID and Password -->
    <div class="modal fade" id="welcomeModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content welcome-modal-content">
                <div class="welcome-modal-header">
                    <i class="fas fa-clipboard-list"></i>
                    <h3 class="mt-2">Welcome to EDOCOHEST</h3>
                    <p class="mb-0">Your Application Has Been Created Successfully!</p>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> Your account has been created. Please save your credentials below.
                    </div>
                    
                    <div class="credentials-box">
                        <p><span class="label"><i class="fas fa-id-card me-1"></i> Application ID:</span> 
                            <strong style="font-size: 1.1rem; color: var(--primary);"><?php echo $staffid; ?></strong>
                            <button class="copy-btn" onclick="copyToClipboard('<?php echo $staffid; ?>')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </p>
                        <p><span class="label"><i class="fas fa-key me-1"></i> Password:</span> 
                            <strong style="font-size: 1.1rem; color: var(--primary);"><?php echo $applicant_phone; ?></strong>
                            <button class="copy-btn" onclick="copyToClipboard('<?php echo $applicant_phone; ?>')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </p>
                    </div>
                    
                    <div class="warning-text">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important Information:</strong>
                        <ul class="mt-2 mb-0" style="margin-left: 1rem;">
                            <li>Your Application ID and Password are required to log in and continue your application.</li>
                            <li>Password is your <strong>Phone Number</strong> used during registration.</li>
                            <li>Please save these details in a safe place. They cannot be recovered easily if lost.</li>
                            <li>You will need these credentials to complete your application and check your admission status.</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: center; border: none; padding-bottom: 1.5rem; gap: 10px;">
                    <button type="button" class="btn btn-outline-secondary" onclick="printModalDetails()" style="border-radius: 40px; padding: 8px 25px;">
                        <i class="fas fa-print me-2"></i> Print Details
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="border-radius: 40px; padding: 8px 30px; background: linear-gradient(95deg, var(--primary), var(--primary-dark)); border: none;">
                        <i class="fas fa-check me-2"></i> I Have Saved My Credentials
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="checkModalEr" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Error Message</h5>
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
    
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Success</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #10b981; margin-bottom: 0.5rem;"></i>
                    <p><?php echo htmlentities($succ); ?></p>
                </div>
                <div class="modal-footer justify-content-center" style="border: none;">
                    <button class="btn btn-success" data-bs-dismiss="modal" style="border-radius: 40px; padding: 6px 24px;">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../clearance/js/jquery-2.2.4.min.js"></script>
    <script>
        AOS.init({ duration: 600, once: true, offset: 20 });
        document.getElementById('currentYear').innerText = new Date().getFullYear();
        
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) { $('#blah').attr('src', e.target.result); };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function toggleCountryFields() {
            var nationality = document.getElementById('nationality').value;
            document.getElementById('nigeriaFields').style.display = nationality === 'Nigeria' ? 'block' : 'none';
            document.getElementById('foreignFields').style.display = nationality === 'Foreign' ? 'block' : 'none';
        }
        
        function loadLGA() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", "fetch?facult=" + document.getElementById("state").value, false);
            xmlhttp.send(null);
            document.getElementById("lga").innerHTML = xmlhttp.responseText;
        }
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show temporary feedback
                var btn = event.target.closest('.copy-btn');
                var originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(function() {
                    btn.innerHTML = originalHtml;
                }, 2000);
            });
        }
        
        function printModalDetails() {
            // Open a new window with the modal content for printing
            var printWindow = window.open('', '_blank', 'width=800,height=600');
            var modalContent = document.getElementById('welcomeModal').querySelector('.modal-content').cloneNode(true);
            
            // Remove buttons from the cloned content
            var buttons = modalContent.querySelectorAll('.btn, .copy-btn, .modal-footer');
            buttons.forEach(function(btn) { btn.remove(); });
            
            // Add print-specific styles
            printWindow.document.write('<!DOCTYPE html>');
            printWindow.document.write('<html><head>');
            printWindow.document.write('<title>EDOCOHEST - Application Credentials</title>');
            printWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">');
            printWindow.document.write(`
                <style>
                    body {
                        font-family: 'Inter', sans-serif;
                        padding: 40px;
                        max-width: 600px;
                        margin: 0 auto;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 30px;
                    }
                    .credentials-box {
                        background: #f8fafc;
                        border-radius: 12px;
                        padding: 20px;
                        margin: 20px 0;
                        border-left: 4px solid #2b5f5a;
                    }
                    .label {
                        font-weight: 600;
                        color: #1e4541;
                    }
                    .warning-text {
                        background: #fef3c7;
                        border-radius: 12px;
                        padding: 15px;
                        font-size: 0.8rem;
                        color: #92400e;
                        margin-top: 20px;
                    }
                    .footer {
                        text-align: center;
                        margin-top: 30px;
                        font-size: 0.7rem;
                        color: #64748b;
                    }
                    .logo {
                        max-width: 80px;
                        margin-bottom: 10px;
                    }
                </style>
            `);
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div class="header">');
            printWindow.document.write('<h2>Edo State College of Health Sciences and Technology</h2>');
            printWindow.document.write('<p>Application Credentials</p>');
            printWindow.document.write('</div>');
            printWindow.document.write(modalContent.innerHTML);
            printWindow.document.write('<div class="footer">');
            printWindow.document.write('<p>This is a computer-generated document. Please keep it safe.</p>');
            printWindow.document.write('<p>Generated on: ' + new Date().toLocaleString() + '</p>');
            printWindow.document.write('</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            // Wait for content to load then print
            printWindow.onload = function() {
                printWindow.print();
            };
        }
        
        // Show welcome modal if this is a new registration
        <?php if ($is_new_registration && $stad == ''): ?>
        document.addEventListener("DOMContentLoaded", function() {
            new bootstrap.Modal(document.getElementById("welcomeModal")).show();
        });
        <?php endif; ?>
        
        <?php if ($showModal): ?>
        document.addEventListener("DOMContentLoaded", function() {
            new bootstrap.Modal(document.getElementById("successModal")).show();
        });
        <?php endif; ?>
    </script>
</body>
</html>
<?php }
if(!empty($showModalEr)) {
    echo '<script>document.addEventListener("DOMContentLoaded", function() { new bootstrap.Modal(document.getElementById("checkModalEr")).show(); });</script>';
}
?>