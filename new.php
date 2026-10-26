<?php
session_start();
$error='';
ob_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include 'include/dbconfig.php';

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];

$prog='Application Fee';

$length=5;
$string='';
$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$size = strlen($chars);
for ($i = 0; $i < $length; $i++) {
    $string .= $chars[rand(0, $size - 1)];
    $date=date("Ymd");
    $ned='EDOCOHEST'.$date.''.$string;
}

if (isset($_POST["registering"])) {
    // Original validation logic preserved
    if (empty($_POST['programm'])) {
        $error="Please Select Programme";
        $showModalEr='true';
    } elseif(empty($_POST['surname'])){
        $error="Surname is Required";
        $showModalEr='true';
    } elseif(empty($_POST['firstname'])){
        $error="Firstname is Required";
        $showModalEr='true';
    } elseif(empty($_POST['jambemail'])){
        $error="Email is Required";
        $showModalEr='true';
    } elseif(empty($_POST['jambphone'])){
        $error="Phone Number is Required";
        $showModalEr='true';
    } else {
        $pros=mysqli_escape_string($con,$_POST['programm']);
        $pd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pid='$pros'"));
        if ($pd['pname']=='') {
            $error='Programme Selected Not Available';
            $showModalEr='true';
        } else {
            $app=$pd['degree'];
            $proh=$pd['pname'];
            $mail=mysqli_escape_string($con,$_POST['jambemail']);
            $pnos=mysqli_escape_string($con,$_POST['jambphone']);
            $first=mysqli_escape_string($con,$_POST['firstname']);
            $other=mysqli_escape_string($con,$_POST['othername']);
            $last=mysqli_escape_string($con,$_POST['surname']);
            $paich= mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$ned' "));
            if ($paich=='') {
                $payref=mysqli_real_escape_string($con,$ned);
            } else {
                $neds=$ned+1;
                $payref=mysqli_real_escape_string($con,$neds);
            }
            $filtered_phone_number = filter_var($pnos, FILTER_SANITIZE_NUMBER_INT);
            $phone_to_check = str_replace("-", "", $filtered_phone_number);
            if (strlen($phone_to_check) < 11 || strlen($phone_to_check) > 14) {
                $error='Invalid Phone Number';
                $showModalEr='true';
            } else {
                $pno=$phone_to_check;
                $fname=$first." ".$last." ".$other; 
                $fullname=$fname;
                if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $error = "Invalid email Address";
                    $showModalEr='true';
                } else {
                    $feer=mysqli_fetch_assoc(mysqli_query($con,"SELECT amount FROM feesrecord WHERE purpose='$prog'"));
                    if ($feer=="") {
                        $error="It Seems ".ucwords($prog)." Amount Has Not Been Set Contact the Bursary";
                        $showModalEr='true';
                    } else {
                        $amount=$feer['amount']+$t;
                        $amt=$feer['amount'];
                        $que= mysqli_query($con,"SELECT * FROM applicant WHERE (email='$mail' OR phoneno='$pno') AND session='$ses' AND (apptype='ND' OR apptype='CERT' OR apptype='JCHEW' ) ");
                        if(mysqli_num_rows($que)>0){
                            $error="Your Email ".$mail. " Or Phone ".$pno." Details Is Already Exist";
                            $showModalEr='true';
                        } else {
                            $tday=date("Y-m-d");
                            $dated = date('Y-M-d'); 
                            $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
                            $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,remtime,apptype,status,firstname,lastname,phoneno,email) VALUES ('$mail', '$ses','$proh','$prog','$amt','failed','$payref','$tday','$newd','$app','Processing','$first','$last','$pno', '$mail') ");
                            $showModal='true';
                        }
                    }
                }
            }
        }
    }
}

$key='MK_PROD_DA332QTPV4';   
$spk='848892598133';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="Admission Application Portal - ND, CERT, JCHEW Programmes" />
    <meta name="author" content="" />
    <title><?php echo htmlentities($orgname); ?> - Admission Application</title>
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
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
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
        
        /* Hero Section */
        .admission-hero {
            background: linear-gradient(135deg, #1a2a2a 0%, #1e3a35 100%);
            padding: 2.5rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .admission-hero::before {
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
        
        .admission-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(43,95,90,0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .admission-badge {
            background: var(--primary);
            color: white;
            padding: 6px 18px;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .admission-title {
            font-weight: 800;
            font-size: 2rem;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .admission-subtitle {
            color: rgba(255,255,255,0.8);
            font-size: 0.95rem;
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
        
        /* Application Container */
        .application-wrapper {
            padding: 3rem 0;
        }
        
        .form-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease;
            border: none;
        }
        
        .form-card:hover {
            transform: translateY(-5px);
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
            color: #1a2a2a;
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
        
        .required-star {
            color: #dc2626;
            font-size: 0.8rem;
            margin-left: 4px;
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
        
        select.form-control-custom {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 20px;
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
            margin-top: 0.5rem;
        }
        
        .btn-submit:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(43,95,90,0.3);
            color: white;
        }
        
        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .login-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .reverify-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .reverify-link:hover {
            text-decoration: underline;
        }
        
        /* Info Panel */
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
        
        .requirement-list, .process-list {
            list-style: none;
            padding-left: 0;
        }
        
        .requirement-list li, .process-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.85rem;
            color: #334155;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .requirement-list li:last-child, .process-list li:last-child {
            border-bottom: none;
        }
        
        .requirement-list li i, .process-list li i {
            color: var(--primary);
            margin-top: 2px;
            min-width: 18px;
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
        
        /* Modal Styles */
        .modal-content-custom {
            border-radius: 28px;
            border: none;
            overflow: hidden;
        }
        
        .payment-table {
            background: #f8fafc;
            border-radius: 20px;
            overflow: hidden;
        }
        
        /* Footer */
        footer {
            background: #1a2421;
            color: #cfdfe9;
            padding: 1.5rem 0;
            margin-top: 2rem;
            border-top: 3px solid var(--primary);
        }
        
        footer a {
            color: #cfdfe9;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        footer a:hover {
            color: var(--primary) !important;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-card, .info-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Scrollbar */
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
        
        @media (max-width: 768px) {
            .form-body {
                padding: 1.5rem;
            }
            .admission-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<!-- Modern Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-modern sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap me-2"></i> <?php echo htmlentities($orgname); ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarModern">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarModern">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="applogin"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-headset me-1"></i>Support</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="admission-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="admission-badge">
                    <i class="fas fa-file-alt me-2"></i>Admission Application
                </div>
                <h1 class="admission-title">Begin Your Academic Journey</h1>
                <p class="admission-subtitle">Apply for ND, Certificate, and JCHEW programmes — your future starts here.</p>
                <?php if ($dead >= date("Y-m-d")) { ?>
                <div class="deadline-badge">
                    <i class="fas fa-calendar-alt" style="color: var(--primary);"></i>
                    <span style="color: white;">Application Deadline: <?php 
                        $old_date = date($dead);
                        $old_date_timestamp = strtotime($old_date);
                        echo date("l jS F, Y", $old_date_timestamp);
                    ?></span>
                </div>
                <?php } ?>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block" data-aos="fade-left">
                <i class="fas fa-chalkboard-user" style="font-size: 4rem; color: rgba(43,95,90,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<!-- Application Section -->
<div class="application-wrapper">
    <div class="container">
        <div class="row g-4">
            <?php $dats = date("Y-m-d");
            if ($dead >= $dats) { ?>
                <!-- Left Column - Application Form -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="form-card">
                        <div class="form-header">
                            <i class="fas fa-user-plus" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <h4>Application Form</h4>
                            <p class="mb-0" style="opacity: 0.8;"><?php echo htmlentities($ses); ?> Admission Session</p>
                        </div>
                        <div class="form-body">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-user"></i>First Name <span class="required-star">*</span></label>
                                    <input type="text" class="form-control-custom" name="firstname" placeholder="Enter your First Name" onkeypress="return (event.keyCode != 32&&event.which!=32)" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-user-tag"></i>Other Name</label>
                                    <input type="text" class="form-control-custom" name="othername" placeholder="Enter your Other Name">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-user-friends"></i>Last Name (Family Name) <span class="required-star">*</span></label>
                                    <input type="text" class="form-control-custom" name="surname" placeholder="Enter your Last Name" onkeypress="return (event.keyCode != 32&&event.which!=32)" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-graduation-cap"></i>Programme to Study <span class="required-star">*</span></label>
                                    <select class="form-control-custom" name="programm" required>
                                        <option selected disabled>Please select Programme</option>
                                        <?php
                                        $resz = mysqli_query($con, "SELECT * FROM program WHERE (degree='ND' OR degree='CERT' OR degree='JCHEW') AND status='1' ORDER BY pname");
                                        while ($row = mysqli_fetch_array($resz)) {
                                            ?>
                                            <option value="<?php echo $row["pid"]; ?>"><?php echo $row["name"]; ?></option>
                                        <?php
                                        }
                                        ?>   
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-envelope"></i>Email Address <span class="required-star">*</span></label>
                                    <input type="email" class="form-control-custom" name="jambemail" placeholder="Enter your verified email address" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label"><i class="fas fa-phone-alt"></i>Phone Number <span class="required-star">*</span></label>
                                    <input type="tel" class="form-control-custom" name="jambphone" maxlength="13" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Enter active phone number" required>
                                </div>
                                
                                <button type="submit" name="registering" class="btn-submit">
                                    <i class="fas fa-arrow-right me-2"></i>Proceed to Payment
                                </button>
                                
                                <div class="text-center mt-3">
                                    <span style="color: #64748b; font-size: 0.85rem;">Already have an Application ID?</span>
                                    <a href="applogin" class="login-link ms-1">Login here <i class="fas fa-arrow-right"></i></a>
                                </div>
                                
                                <div class="text-center mt-2">
                                    <span style="font-size: 0.75rem; color: #64748b;">
                                        <i class="fas fa-credit-card me-1"></i> Debited but couldn't get Application ID? 
                                        <a href="payverification" class="reverify-link">Re-verify Payment</a>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Information Panel -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="info-card">
                        <div class="info-header">
                            <h5><i class="fas fa-info-circle me-2"></i>Programme Information</h5>
                        </div>
                        <div class="info-body">
                            <div class="alert" style="background: #fef3c7; border-radius: 16px; padding: 10px; font-size: 0.8rem; margin-bottom: 1rem;">
                                <i class="fas fa-asterisk" style="color: #dc2626; font-size: 0.7rem;"></i>
                                <strong>Note:</strong> Fields marked with <span class="required-star">*</span> are required.
                            </div>
                            
                            <h6 class="fw-bold mb-2" style="color: var(--primary-dark);">Admission Requirements</h6>
                            <ul class="requirement-list">
                                <li><i class="fas fa-check-circle"></i> WAEC/NECO (O'Level) or NABTEB with at least five credits in English, Mathematics, Physics, Chemistry, and Biology in not more than two sittings.</li>
                                <li><i class="fas fa-check-circle"></i> Candidates with awaiting results can apply but must have results before clearance.</li>
                                <li><i class="fas fa-check-circle"></i> All applicants must have sat for JAMB 2025 (except Pharmacy Technician applicants).</li>
                                <li><i class="fas fa-check-circle"></i> JCHEW Conversion applicants must possess an active practicing JCHEW license along with the certificate.</li>
                            </ul>
                            
                            <div class="fee-box">
                                <div class="fee-amount">₦15,000</div>
                                <div style="font-size: 0.8rem; color: #64748b;">Application Fee (Non-Refundable)</div>
                                <small>Excluding service charge fee</small>
                            </div>
                            
                            <h6 class="fw-bold mb-2 mt-2" style="color: var(--primary-dark);">Application Process</h6>
                            <ul class="process-list">
                                <li><i class="fas fa-1-circle"></i> Fill the application form with valid details</li>
                                <li><i class="fas fa-2-circle"></i> Make payment of ₦15,000 (Non-refundable)</li>
                                <li><i class="fas fa-3-circle"></i> Receive Application ID via email upon successful payment</li>
                                <li><i class="fas fa-4-circle"></i> Complete your application profile</li>
                            </ul>
                            
                            <div class="mt-3 pt-2">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <span style="background: var(--primary-soft); padding: 6px 14px; border-radius: 40px; font-size: 0.75rem;">
                                        <i class="fas fa-phone-alt me-1" style="color: var(--primary);"></i> 08164550281
                                    </span>
                                    <span style="background: var(--primary-soft); padding: 6px 14px; border-radius: 40px; font-size: 0.75rem;">
                                        <i class="fas fa-envelope me-1" style="color: var(--primary);"></i> info@edocohest.edu.ng
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <!-- Application Closed State -->
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <div class="text-center p-5" style="background: white; border-radius: 32px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1);">
                        <i class="fas fa-calendar-times" style="font-size: 4rem; color: var(--primary); margin-bottom: 1rem;"></i>
                        <h4 style="color: var(--primary-dark);">Admission Application Closed</h4>
                        <p class="text-muted">Admission application for the <?php echo htmlentities($ses); ?> session has ended.</p>
                        <div class="mt-3">
                            <a href="applogin" class="btn-submit" style="display: inline-block; width: auto; padding: 10px 30px; text-decoration: none;">
                                <i class="fas fa-sign-in-alt me-2"></i>Login for Existing Applicants
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small">
                    <i class="fas fa-copyright me-1"></i> <span id="currentYear"></span> - 
                    <a href="#"><?php echo htmlentities($orgname . ', ' . $schadress); ?></a>
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small">
                    <i class="fas fa-graduation-cap me-1" style="color: var(--primary);"></i> Excellence in Education
                    <span class="mx-2">|</span>
                    <i class="fas fa-shield-alt me-1"></i> Secure Payment
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Error Modal -->
<div class="modal fade" id="checkModalEr" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 28px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; border: none;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Error Message</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-times-circle" style="font-size: 3rem; color: #dc2626; margin-bottom: 1rem;"></i>
                <p><?php echo htmlentities($error); ?></p>
            </div>
            <div class="modal-footer justify-content-center" style="border: none;">
                <button class="btn btn-danger" data-bs-dismiss="modal" style="border-radius: 40px; padding: 8px 30px;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="checkModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 28px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a2a2a, #1e3a35); color: white; border: none;">
                <h5 class="modal-title"><i class="fas fa-credit-card me-2"></i>Payment Summary</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div style="background: #f8fafc; border-radius: 20px; overflow: hidden;">
                    <table class="table table-borderless mb-0">
                        <tr><td style="width: 40%;"><strong>Full Name:</strong></td><td><?php echo htmlentities($fname); ?></td></tr>
                        <tr><td><strong>Email:</strong></td><td><?php echo htmlentities($mail); ?></td></tr>
                        <tr><td><strong>Phone:</strong></td><td><?php echo htmlentities($pno); ?></td></tr>
                        <tr><td><strong>Programme:</strong></td><td><?php echo htmlentities(ucwords($proh)); ?></td></tr>
                        <tr><td><strong>Session:</strong></td><td><?php echo htmlentities($ses); ?></td></tr>
                        <tr style="background: var(--primary-soft);"><td><strong>Application Fee:</strong></td><td><strong>₦<?php echo htmlentities(number_format($amt,2)); ?></strong></td></tr>
                    </table>
                </div>
                <p class="mt-3 text-center small text-muted">Click "Make Payment" to proceed with your application.</p>
            </div>
            <div class="modal-footer justify-content-center gap-3" style="border: none; padding-bottom: 1.5rem;">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" style="border-radius: 40px; padding: 8px 25px;">Cancel</button>
                <button type="button" onclick="payWithMonnify()" class="btn btn-success" style="border-radius: 40px; padding: 8px 30px; background: linear-gradient(95deg, var(--primary), var(--primary-dark)); border: none;">Make Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>
<script>
    AOS.init({ duration: 800, once: true, offset: 20 });
    document.getElementById('currentYear').innerText = new Date().getFullYear();
    
    function payWithMonnify() {
        var fullname = '<?php echo htmlentities($fullname); ?>'; 
        var first = '<?php echo htmlentities($first); ?>';
        var other = '<?php echo htmlentities($other); ?>';
        var last = '<?php echo htmlentities($last); ?>';
        var amount = <?php echo htmlentities($amount); ?>;
        var prog = '<?php echo htmlentities($prog); ?>';
        var proh = '<?php echo $proh; ?>';
        var refs = '<?php echo $payref; ?>';
        var prov = '<?php echo htmlentities($pros); ?>';
        var acad = '<?php echo htmlentities($ses); ?>';
        var email = '<?php echo htmlentities($mail); ?>';
        var phone = '<?php echo htmlentities($pno); ?>';
        var pbk = '<?php echo htmlentities($key); ?>';
        var spl = '<?php echo htmlentities($spk); ?>';
        
        MonnifySDK.initialize({
            amount: amount,
            currency: "NGN",
            reference: refs,
            customerName: fullname,
            customerEmail: email,
            customerMobileNumber: phone,
            paymentSession: acad,
            apiKey: pbk,
            contractCode: spl,
            paymentDescription: "ADMISSION APPLICATION PAYMENT",
            incomeSplitConfig: [
                {
                    subAccountCode: "MFY_SUB_891671712178",
                    splitAmount: 500,
                    feeBearer: true
                }
            ],
            metadata: {
                name: fullname,
                Session: acad,
                Purpose: prog,
                Programme: proh
            },
            onLoadStart: () => { console.log("loading has started"); },
            onLoadComplete: () => { console.log("SDK is UP"); },
            onComplete: function(response) {
                var email = '<?php echo htmlentities($mail); ?>';
                var phone = '<?php echo htmlentities($pno); ?>';
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        window.location.href = "successs?feedback=success&ref=" + response.transactionReference;
                    }
                };
                xmlhttp.open("POST", "include/pay", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("firstname=" + fullname + "&email=" + email + "&phone=" + phone + "&progs=" + encodeURIComponent(proh) + "&prog=" + prog + "&ref=" + response.transactionReference + "&status=" + response.paymentStatus + "&method=" + response.paymentMethod + "&amount=" + response.amountPaid + "&acads=" + acad + "&first=" + first + "&last=" + last + "&other=" + other + "&prov=" + prov);
                alert('Payment completed. Please wait while your transaction is being finalized!');
            },
            onClose: function(data) {
                console.log("Payment closed", data);
            }
        });
    }
</script>

<?php
if(!empty($showModal)) {
    echo '<script>document.addEventListener("DOMContentLoaded", function() { new bootstrap.Modal(document.getElementById("checkModal")).show(); });</script>';
}
if(!empty($showModalEr)) {
    echo '<script>document.addEventListener("DOMContentLoaded", function() { new bootstrap.Modal(document.getElementById("checkModalEr")).show(); });</script>';
}
?>

</body>
</html>