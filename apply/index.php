<?php
session_start();
$error = '';
$success = '';
ob_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include '../include/dbconfig.php';

$dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses = $dat['session'];
$dead = $dat['deadline'];

$prog = 'Application Fee';

// Check if admission is closed based on deadline
$current_date = date("Y-m-d");
$is_admission_closed = ($current_date > $dead);

// Generate unique Application ID for new applicant
function generateApplicationID($con, $ses, $degree) {
    $result = mysqli_query($con, "SELECT COUNT(applid) as userid FROM applicant WHERE session='$ses' AND apptype='$degree'");
    $row = mysqli_fetch_assoc($result);
    $id = $row['userid'] + 1;
    $yrk = substr($ses, 0, 4);
    $yr = substr($yrk, -2);
    return "EDOCOHEST/" . $degree . "/" . $yr . "/" . str_pad($id, 4, '0', STR_PAD_LEFT);
}

// Create payment_evidence directory at script start
$upload_dir = __DIR__ . '/payment_evidence/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_POST["registering"])) {
    if ($is_admission_closed) {
        $error = "Admission for this Programme is currently CLOSED. Application deadline was " . date("jS F, Y", strtotime($dead)) . ".";
        $showModalEr = 'true';
    } elseif (empty($_POST['programm'])) {
        $error = "Please Select Programme";
        $showModalEr = 'true';
    } elseif (empty($_POST['surname'])) {
        $error = "Surname is Required";
        $showModalEr = 'true';
    } elseif (empty($_POST['firstname'])) {
        $error = "Firstname is Required";
        $showModalEr = 'true';
    } elseif (empty($_POST['jambemail'])) {
        $error = "Email is Required";
        $showModalEr = 'true';
    } elseif (empty($_POST['jambphone'])) {
        $error = "Phone Number is Required";
        $showModalEr = 'true';
    } elseif (empty($_FILES['payment_evidence']['name'])) {
        $error = "Please upload evidence of payment (Bank Teller/Receipt)";
        $showModalEr = 'true';
    } else {
        $pros = mysqli_real_escape_string($con, $_POST['programm']);
        $pd = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM program WHERE pid='$pros'"));
        if ($pd['pname'] == '') {
            $error = 'Programme Selected Not Available';
            $showModalEr = 'true';
        } else {
            $app = $pd['degree'];
            $proh = $pd['pname'];
            $mail = mysqli_real_escape_string($con, $_POST['jambemail']);
            $pnos = mysqli_real_escape_string($con, $_POST['jambphone']);
            $first = mysqli_real_escape_string($con, $_POST['firstname']);
            $other = mysqli_real_escape_string($con, $_POST['othername']);
            $last = mysqli_real_escape_string($con, $_POST['surname']);
            
            $filtered_phone_number = filter_var($pnos, FILTER_SANITIZE_NUMBER_INT);
            $phone_to_check = str_replace("-", "", $filtered_phone_number);
            if (strlen($phone_to_check) < 11 || strlen($phone_to_check) > 14) {
                $error = 'Invalid Phone Number';
                $showModalEr = 'true';
            } else {
                $pno = $phone_to_check;
                $fname = $first . " " . $last . " " . $other;
                $fullname = $fname;
                
                if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $error = "Invalid email Address";
                    $showModalEr = 'true';
                } else {
                    $feer = mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$prog'"));
                    if ($feer == "") {
                        $error = "It Seems " . ucwords($prog) . " Amount Has Not Been Set Contact the Bursary";
                        $showModalEr = 'true';
                    } else {
                        $amount = $feer['amount'];
                        
                        // Check if applicant already exists
                        $que = mysqli_query($con, "SELECT * FROM applicant WHERE (email='$mail' OR phoneno='$pno') AND session='$ses' AND (apptype='ND' OR apptype='CERT' OR apptype='JCHEW')");
                        if (mysqli_num_rows($que) > 0) {
                            $error = "Your Email " . $mail . " Or Phone " . $pno . " Details Is Already Exist";
                            $showModalEr = 'true';
                        } else {
                            // Generate Application ID
                            $userID = generateApplicationID($con, $ses, $app);
                            $hashedPW = hash('sha256', $pno);
                            $today = date("Y-m-d");
                            
                            // Handle Payment Evidence Upload
                            $filenamg = basename($_FILES['payment_evidence']['name']);
                            $filetmp = $_FILES['payment_evidence']['tmp_name'];
                            $extension = array("png", "jpg", "jpeg", "PNG", "JPG", "JPEG", "pdf", "PDF");
                            $file_extension = pathinfo($filenamg, PATHINFO_EXTENSION);
                            
                            // Validate file extension
                            if (!in_array($file_extension, $extension)) {
                                $error = "Upload valid file. Only PNG, JPG, JPEG, PDF are allowed.";
                                $showModalEr = 'true';
                            } elseif ($_FILES["payment_evidence"]["size"] > 4000000) {
                                $error = "File size exceeds 4MB.";
                                $showModalEr = 'true';
                            } elseif ($_FILES["payment_evidence"]["error"] !== UPLOAD_ERR_OK) {
                                $error = "File upload error code: " . $_FILES["payment_evidence"]["error"];
                                $showModalEr = 'true';
                            } else {
                                // FIXED: Create a safe filename without slashes
                                // Replace slashes with underscores to avoid subdirectory creation
                                $safe_userID = str_replace('/', '_', $userID);
                                $timestamp = date("Ymd_His");
                                $payment_filename = $safe_userID . "_" . $timestamp . "." . $file_extension;
                                $payment_path = "payment_evidence/" . $payment_filename;
                                $full_path = $upload_dir . $payment_filename;
                                
                                // Move uploaded file
                                if (move_uploaded_file($filetmp, $full_path)) {
                                    // Set proper permissions
                                    chmod($full_path, 0644);
                                    
                                    // Start transaction
                                    mysqli_autocommit($con, FALSE);
                                    
                                    // Insert into applicant table
                                    $inst = mysqli_query($con, "INSERT INTO applicant (applid, phoneno, email, password, status, session, firstname, lastname, othername, programme, apptype, applicationDate, payment_evidence) 
                                    VALUES ('$userID', '$pno', '$mail', '$hashedPW', 'submitted', '$ses', '$first', '$last', '$other', '$proh', '$app', '$today', '$payment_path')");
                                    
                                    if ($inst) {
                                        // Insert into studentpayment table
                                        $payment_ref = "PAY_" . $safe_userID . "_" . date("YmdHis");
                                        $payment_ins = mysqli_query($con, "INSERT INTO studentpayment (studentid, section, level, programme, purpose, amount, modes, paymentreference, paytime, status, apptype, payment_evidence) 
                                        VALUES ('$userID', '$ses', '100', '$proh', '$prog', '$amount', 'Bank Transfer', '$payment_ref', '$today', 'Pending Verification', '$app', '$payment_path')");
                                        
                                        if ($payment_ins) {
                                            mysqli_commit($con);
                                            $_SESSION['staffid'] = $userID;
                                            $_SESSION['expire'] = time() + (30 * 60);
                                            // After successful insertion, before header redirect
$_SESSION['new_registration'] = true;
$_SESSION['staffid'] = $userID;
$_SESSION['expire'] = time() + (30 * 60);
header("Location: personaldata.php");
exit();
                                        } else {
                                            mysqli_rollback($con);
                                            $error = "Error processing payment record: " . mysqli_error($con);
                                            $showModalEr = 'true';
                                        }
                                    } else {
                                        mysqli_rollback($con);
                                        $error = "Error creating applicant record: " . mysqli_error($con);
                                        $showModalEr = 'true';
                                    }
                                } else {
                                    $error = "Error uploading payment evidence. Please try again.";
                                    $showModalEr = 'true';
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

$key = 'MK_PROD_DA332QTPV4';
$spk = '848892598133';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="ND/CERT/JCHEW Programme - Admission Application Form" />
    <meta name="author" content="" />
    <title>Admission Application - EDOCOHEST</title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
    <!-- Google Fonts + Modern Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    
    <style>
        :root {
            --primary: #2b5f5a;
            --primary-dark: #1e4541;
            --primary-light: #4a7f7a;
            --primary-soft: #e8f3f1;
            --secondary-dark: #1e2a2a;
            --secondary-light: #2c3e2f;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
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
        
        .navbar-modern {
            background: linear-gradient(135deg, #1e2a2a 0%, #2c3e2f 100%);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            padding: 0.8rem 0;
            border-bottom: 3px solid var(--primary);
        }
        
        .navbar-modern .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
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
        
        .hero-section {
            background: linear-gradient(135deg, #1e2a2a 0%, #2c3e2f 100%);
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(43,95,90,0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .hero-title {
            font-weight: 800;
            font-size: 2.2rem;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .hero-subtitle {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }
        
        .deadline-badge {
            background: rgba(255,255,255,0.15);
            border-radius: 40px;
            padding: 8px 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(10px);
            margin-top: 1rem;
        }
        
        .bank-details-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .bank-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 1.25rem;
            color: white;
            text-align: center;
        }
        
        .bank-header h4 {
            font-weight: 700;
            margin: 0;
        }
        
        .bank-body {
            padding: 1.5rem;
        }
        
        .bank-detail-item {
            background: var(--primary-soft);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .bank-detail-item i {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .bank-detail-item .label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
        }
        
        .bank-detail-item .value {
            font-weight: 700;
            font-size: 1rem;
            color: var(--secondary-dark);
        }
        
        .form-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary-light) 100%);
            padding: 1.5rem;
            text-align: center;
            color: white;
        }
        
        .form-header h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .form-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #1e2a2a;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-label i {
            color: var(--primary);
            font-size: 0.9rem;
        }
        
        .form-control-custom {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            font-family: 'Inter', sans-serif;
        }
        
        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(43,95,90,0.1);
        }
        
        .btn-submit {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 14px 24px;
            font-weight: 700;
            border-radius: 40px;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-submit:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(43,95,90,0.3);
            color: white;
        }
        
        .btn-submit:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .info-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            height: 100%;
        }
        
        .info-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 1.25rem;
            color: white;
        }
        
        .info-header h5 {
            font-weight: 700;
            margin: 0;
        }
        
        .info-body {
            padding: 1.5rem;
        }
        
        .requirement-list {
            list-style: none;
            padding-left: 0;
        }
        
        .requirement-list li {
            padding: 0.6rem 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: #334155;
        }
        
        .requirement-list li i {
            color: var(--primary);
            width: 20px;
        }
        
        .fee-box {
            background: linear-gradient(135deg, var(--primary-soft), #fff);
            border-radius: 20px;
            padding: 1rem;
            text-align: center;
            margin: 1rem 0;
            border: 1px solid rgba(43,95,90,0.2);
        }
        
        .fee-amount {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .upload-area {
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .upload-area:hover {
            border-color: var(--primary);
            background: var(--primary-soft);
        }
        
        .upload-area i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .closure-alert {
            background: linear-gradient(95deg, #7f1a1a, #991b1b);
            color: white;
            padding: 1rem 0;
            border-bottom: 3px solid #fbbf24;
        }
        
        footer {
            background: #1a2421;
            color: #cfdfe9;
            padding: 1.5rem 0;
            margin-top: 2rem;
            border-top: 3px solid var(--primary);
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-card, .info-card, .bank-details-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @media (max-width: 768px) {
            .form-body { padding: 1.5rem; }
            .hero-title { font-size: 1.5rem; }
        }
        
        .file-info {
            font-size: 0.8rem;
            margin-top: 5px;
        }
        
        .file-info i {
            margin-right: 3px;
        }
        
        .text-success {
            color: #10b981 !important;
        }
        
        .text-danger {
            color: #ef4444 !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-modern sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="../<?php echo $logos?>" style="height: 60px;"> <?php echo htmlentities($orgname); ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarModern">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarModern">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="applogin"><i class="fas fa-sign-in-alt me-1"></i>Continue Application</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <h1 class="hero-title">Edo State College of Health Sciences and Technology</h1>
                <p class="hero-subtitle">Start your Health career and contribute to the community with our accredited programme.</p>
                <div class="deadline-badge">
                    <i class="fas fa-calendar-alt" style="color: var(--primary);"></i>
                    <span style="color: white;"><!--Application Deadline:--> <?php /*echo date("jS F, Y", strtotime($dead)); */?></span>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block" data-aos="fade-left">
                <i class="fas fa-chalkboard-user" style="font-size: 4rem; color: rgba(43,95,90,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<?php if ($is_admission_closed): ?>
<div class="closure-alert">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <i class="fas fa-ban me-2" style="font-size: 1.5rem;"></i>
                <strong>ADMISSION CLOSED!</strong> The application portal is now <strong>CLOSED</strong>. 
                Deadline was <strong><?php echo date("jS F, Y", strtotime($dead)); ?></strong>.
            </div>
            <a href="applogin" class="btn btn-light btn-sm">Continue Application</a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Bank Details Column -->
        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
            <div class="bank-details-card">
                <div class="bank-header">
                    <h4><i class="fas fa-university me-2"></i>Payment Instructions</h4>
                </div>
                <div class="bank-body">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i> Please proceed to any Fidelity Bank branch nationwide to make payment of the application fee.
                    </div>
                    
                    <div class="bank-detail-item">
                        <i class="fas fa-building"></i>
                        <div class="label">Account Name</div>
                        <div class="value">Edo State College of Health Sciences and Technology</div>
                    </div>
                    
                    <div class="bank-detail-item">
                        <i class="fas fa-credit-card"></i>
                        <div class="label">Account Number</div>
                        <div class="value">5030151624</div>
                    </div>
                    
                    <div class="bank-detail-item">
                        <i class="fas fa-bank"></i>
                        <div class="label">Bank</div>
                        <div class="value">Fidelity Bank</div>
                    </div>
                    
                    <div class="fee-box">
                        <div class="fee-amount">₦20,500</div>
                        <div style="font-size: 0.8rem; color: #64748b;">Application Fee (Non-Refundable)</div>
                    </div>
                    
                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Important Notes:</h6>
                        <ul class="small text-muted">
                            <li><i class="fas fa-check-circle text-success me-1"></i> Name of Applicant should be written on teller or bank transfer narrative</li>
                            <li><i class="fas fa-check-circle text-success me-1"></i> Keep your payment teller/receipt for upload</li>
                            <li><i class="fas fa-check-circle text-success me-1"></i> Upload clear, readable image of payment evidence</li>
                            <li><i class="fas fa-check-circle text-success me-1"></i> Supported formats: JPG, PNG, PDF (Max 4MB)</li>
                            <li><i class="fas fa-check-circle text-success me-1"></i> Ensure the teller shows the College account details</li>
                        </ul>
                    </div>
                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Support:</h6>
                        <ul class="small text-muted">
                            <li><i class="fas fa-check-circle text-success me-1"></i> Call: 08164550281 - 08163226994 - 07017865994</li>
                            <li><i class="fas fa-check-circle text-success me-1"></i> Email: ict@edocohest.edu.ng</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Application Form Column -->
        <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
            <div class="form-card">
                <div class="form-header">
                    <i class="fas fa-user-plus" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <h4>Admission Application Form</h4>
                    <p class="mb-0" style="opacity: 0.8;"><?php echo htmlentities($ses); ?> Admission Session</p>
                </div>
                <div class="form-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-user"></i>First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control-custom" name="firstname" placeholder="Enter your First Name" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-user-friends"></i>Surname <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control-custom" name="surname" placeholder="Enter your Surname" value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user-tag"></i>Other Name (Optional)</label>
                            <input type="text" class="form-control-custom" name="othername" placeholder="Enter your Other Name" value="<?php echo isset($_POST['othername']) ? htmlspecialchars($_POST['othername']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-graduation-cap"></i>Programme to Study <span class="text-danger">*</span></label>
                            <select class="form-control-custom" name="programm" required>
                                <option selected disabled>Please select Programme</option>
                                <?php
                                $resz = mysqli_query($con, "SELECT * FROM program WHERE (degree='ND' OR degree='CERT' OR degree='JCHEW') AND statuss='1' ORDER BY pname");
                                while ($row = mysqli_fetch_array($resz)) {
                                    $selected = (isset($_POST['programm']) && $_POST['programm'] == $row["pid"]) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $row["pid"]; ?>" <?php echo $selected; ?>><?php echo $row["name"]; ?></option>
                                <?php
                                }
                                ?>   
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-envelope"></i>Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control-custom" name="jambemail" placeholder="Enter your verified email address" value="<?php echo isset($_POST['jambemail']) ? htmlspecialchars($_POST['jambemail']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-phone-alt"></i>Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control-custom" name="jambphone" maxlength="13" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Enter active phone number" value="<?php echo isset($_POST['jambphone']) ? htmlspecialchars($_POST['jambphone']) : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-receipt"></i>Evidence of Payment (Bank Teller/Receipt) <span class="text-danger">*</span></label>
                            <div class="upload-area" onclick="document.getElementById('payment_evidence').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p class="mb-0">Click to upload payment evidence</p>
                                <small class="text-muted">Supported: JPG, PNG, PDF (Max 4MB)</small>
                                <input type="file" id="payment_evidence" name="payment_evidence" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" required>
                            </div>
                            <div id="file-name" class="file-info"></div>
                        </div>
                        
                        <button type="submit" name="registering" class="btn-submit" <?php echo $is_admission_closed ? 'disabled' : ''; ?>>
                            <i class="fas fa-arrow-right me-2"></i>Continue Application
                        </button>
                        
                        <div class="text-center mt-3">
                            <span style="color: #64748b; font-size: 0.85rem;">Already have an Application ID?</span>
                            <a href="applogin" class="login-link ms-1">Login here <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small">&copy; <span id="currentYear"></span> - Edo State College of Health Sciences and Technology</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small"><i class="fas fa-shield-alt me-1" style="color: var(--primary);"></i> Secure Application Portal</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true, offset: 20 });
    document.getElementById('currentYear').innerText = new Date().getFullYear();
    
    document.getElementById('payment_evidence').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : '';
        var fileSize = e.target.files[0] ? (e.target.files[0].size / 1024).toFixed(2) + ' KB' : '';
        
        if (fileName) {
            document.getElementById('file-name').innerHTML = '<i class="fas fa-check-circle text-success"></i> Selected: ' + fileName + ' (' + fileSize + ')';
        } else {
            document.getElementById('file-name').innerHTML = '';
        }
    });
</script>
</body>
</html>