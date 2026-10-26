<?php
session_start();
$error='';
ob_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include '../include/dbconfig.php';

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admissions processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];

$prog='Application Fee';
/*Start Here*/
$length=5;
$string='';
$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $size = strlen($chars);
   for ($i = 0; $i < $length; $i++) {
       $string .= $chars[rand(0, $size - 1)];
       $date=date("Ymd");
       $ned='EDOCOHEST'.$date.''.$string;
  } 
 /*End Here*/

// Check if admission is closed based on deadline
$current_date = date("Y-m-d");
$is_admission_closed = ($current_date > $dead);

if (isset($_POST["registering"])) {
        // If admission is closed, prevent registration
        if ($is_admission_closed) {
            $error="Admission for this Programme is currently CLOSED. Application deadline was " . date("jS F, Y", strtotime($dead)) . ".";
            $showModalEr='true';
        }elseif (empty($_POST['programm'])) {
            $error="Please Select Programme";
        $showModalEr='true';
        }elseif(empty($_POST['surname'])){
            $error="Surname is Required";
            $showModalEr='true';
        }elseif(empty($_POST['firstname'])){
            $error="Firstname is Required";
            $showModalEr='true';
        }elseif(empty($_POST['jambemail'])){
            $error="Email is Required";
            $showModalEr='true';
        }elseif(empty($_POST['jambphone'])){
            $error="Phone Number is Required";
            $showModalEr='true';
        }
        else{
            $pros=mysqli_escape_string($con,$_POST['programm']);
            $pd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pid='$pros'"));
            if ($pd['pname']=='') {
               $error='Programme Selected Not Available';
               $showModalEr='true';
            }else{
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
            }else{
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
                }else{
           
                $feer=mysqli_fetch_assoc(mysqli_query($con,"SELECT amount FROM feesrecord WHERE purpose='$prog'"));
                if ($feer=="") {
                               $error="It Seems ".ucwords($prog)." Amount Has Not Been Set Contact the Bursary";
                            $showModalEr='true';
                           }else{
                
                $amount=$feer['amount']+$t;
                $amt=$feer['amount'];
                /*End Here*/
                
                        $que= mysqli_query($con,"SELECT * FROM applicant WHERE (email='$mail' OR phoneno='$pno') AND session='$ses' AND (apptype='ND' OR apptype='CERT' OR apptype='JCHEW' ) ");
                        if(mysqli_num_rows($que)>0){
                        $error="Your Email ".$mail. " Or Phone ".$pno." Details Is Already Exist";
                        $showModalEr='true';
                        }
                        else{
                            /*End Here*/
                        $tday=date("Y-m-d");
                        $dated = date('Y-M-d'); 
                        $newd = date('Y-m-d', strtotime($dated. ' + 15 days'));
                        $inst = mysqli_query($con, "INSERT INTO failpayment (studentid,section,programme,purpose,amount,modes,paymentreference,paytime,remtime,apptype,status,firstname,lastname,phoneno,email) 
                        VALUES ('$mail', '$ses','$proh','$prog','$amt','failed','$payref','$tday','$newd','$app','Processing','$first','$last','$pno', '$mail') ");
                            $showModal='true';
                            }
                          }
                               
                    }   
                }
             }
        }
    } 
//$key='MK_PROD_DA332QTPV4';   
//$spk='848892598133';
//$key='MK_PROD_1Q6K9RH65A';   
//$spk='699979761851';
$key='MK_TEST_ZNF7A71RMX';   
$spk='3996172859';
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
    <script type="text/javascript" src="https://sdk.monnify.com/plugin/monnify.js"></script>
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
        
        /* Modern Navbar */
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
        
        /* Hero Section */
        .nce-hero {
            background: linear-gradient(135deg, #1e2a2a 0%, #2c3e2f 100%);
            padding: 3rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .nce-hero::before {
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
        
        .nce-hero::after {
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
        
        .nce-badge {
            background: var(--primary);
            color: white;
            padding: 6px 18px;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .nce-title {
            font-weight: 800;
            font-size: 2.2rem;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .nce-subtitle {
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
            transition: color 0.2s;
        }
        
        .login-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .terms-text {
            font-size: 0.7rem;
            color: #64748b;
            margin-top: 1rem;
            text-align: center;
        }
        
        .terms-text a {
            color: var(--primary);
            text-decoration: none;
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
        
        .process-list {
            list-style: none;
            padding-left: 0;
        }
        
        .process-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.85rem;
        }
        
        .step-number {
            width: 24px;
            height: 24px;
            background: var(--primary-soft);
            color: var(--primary);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            flex-shrink: 0;
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
        
        /* Closure Alert Banner */
        .closure-alert {
            background: linear-gradient(95deg, #7f1a1a, #991b1b);
            color: white;
            padding: 1rem 0;
            border-bottom: 3px solid #fbbf24;
        }
        
        .closure-alert .alert-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .closure-alert .alert-icon {
            font-size: 1.8rem;
            margin-right: 15px;
        }
        
        .closure-alert .alert-message {
            flex: 1;
            font-weight: 500;
        }
        
        .closure-alert .alert-message strong {
            color: #fbbf24;
        }
        
        .closure-alert .btn-closure {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.4);
            color: white;
            border-radius: 40px;
            padding: 6px 18px;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .closure-alert .btn-closure:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        /* Disabled form overlay effect when admission closed */
        .form-card.closed {
            position: relative;
        }
        
        .form-card.closed .form-body {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }
        
        .form-card.closed .form-body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.6);
            border-radius: 0 0 32px 32px;
            pointer-events: none;
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
            .nce-title {
                font-size: 1.5rem;
            }
            .closure-alert .alert-content {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<!-- Modern Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-modern sticky-top">
    <div class="container">
        <a class="navbar-brand" href="https://edocohest.edu.ng">
            <i class="fas fa-graduation-cap me-2"></i> Admission Application Portal
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
<section class="nce-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="nce-badge">
                    <i class="fas fa-graduation-cap me-2"></i>ND/CERT. and JCHEW Application
                </div>
                <h1 class="nce-title">EDOCOHEST</h1>
                <p class="nce-subtitle">Start your Health career and contribute to the community with our accredited programme.</p>
                <div class="deadline-badge">
                    <i class="fas fa-calendar-alt" style="color: var(--primary);"></i>
                    <span style="color: white;">Application Deadline: <?php echo date("jS F, Y", strtotime($dead)); ?></span>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block" data-aos="fade-left">
                <i class="fas fa-chalkboard-user" style="font-size: 4rem; color: rgba(43,95,90,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<!-- ADMISSION CLOSURE NOTICE - Displayed when current date > deadline -->
<?php if ($is_admission_closed): ?>
<div class="closure-alert" data-aos="fade-down">
    <div class="container">
        <div class="alert-content">
            <div style="display: flex; align-items: center;">
                <i class="fas fa-ban alert-icon"></i>
                <div class="alert-message">
                    <strong>ADMISSION CLOSED!</strong> The application portal for EDOCOHEST Programme is now <strong>CLOSED</strong>. 
                    Deadline was <strong><?php echo date("jS F, Y", strtotime($dead)); ?></strong>. 
                    No further applications will be accepted for the <?php echo htmlentities($ses); ?> session.
                </div>
            </div>
            <a href="applogin" class="btn-closure">
                <i class="fas fa-sign-in-alt me-1"></i> Continue Application
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Application Section -->
<div class="application-wrapper">
    <div class="container">
        <div class="row g-4">
            <!-- Left Column - Application Form -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="form-card <?php echo $is_admission_closed ? 'closed' : ''; ?>">
                    <div class="form-header">
                        <i class="fas fa-user-plus" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                        <h4>Admission Application Form</h4>
                        <p class="mb-0" style="opacity: 0.8;"><?php echo htmlentities($ses); ?> Admission Session</p>
                    </div>
                    <div class="form-body">
                        <?php if ($is_admission_closed): ?>
                            <div class="alert alert-danger mb-4" style="border-radius: 20px; border-left: 4px solid #b91c1c;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Admission Closed</strong> - Applications are no longer being accepted for the <?php echo htmlentities($ses); ?> session.
                            </div>
                        <?php endif; ?>
                        
                        <form action="" method="post">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-user"></i>First Name</label>
                                <input type="text" class="form-control-custom" name="firstname" placeholder="Enter your First Name" <?php echo $is_admission_closed ? 'disabled' : ''; ?> required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-user-friends"></i>Last Name (Family Name)</label>
                                <input type="text" class="form-control-custom" name="surname" placeholder="Enter your Last Name" <?php echo $is_admission_closed ? 'disabled' : ''; ?> required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-user-tag"></i>Other Name (Optional)</label>
                                <input type="text" class="form-control-custom" name="othername" placeholder="Enter your Other Name" <?php echo $is_admission_closed ? 'disabled' : ''; ?>>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-graduation-cap"></i>Programme to Study</label>
                                <select class="form-control-custom" name="programm" <?php echo $is_admission_closed ? 'disabled' : ''; ?> required>
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
                                <label class="form-label"><i class="fas fa-envelope"></i>Email Address</label>
                                <input type="email" class="form-control-custom" name="jambemail" placeholder="Enter your verified email address" <?php echo $is_admission_closed ? 'disabled' : ''; ?> required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-phone-alt"></i>Phone Number</label>
                                <input type="tel" class="form-control-custom" name="jambphone" maxlength="13" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Enter active phone number" <?php echo $is_admission_closed ? 'disabled' : ''; ?> required>
                            </div>
                            
                            <button type="submit" name="registering" class="btn-submit" <?php echo $is_admission_closed ? 'disabled' : ''; ?>>
                                <i class="fas fa-arrow-right me-2"></i>Proceed to Payment
                            </button>
                            
                            <div class="text-center mt-3">
                                <span style="color: #64748b; font-size: 0.85rem;">Already have an Application ID?</span>
                                <a href="applogin" class="login-link ms-1">Login here <i class="fas fa-arrow-right"></i></a>
                            </div>
                            
                            <div class="terms-text">
                                By registering you agree to the Edo State College of Health Sciences and Technology 
                                <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>
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
                        <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">Admission Requirements</h6>
                        <ul class="requirement-list">
                            <li><i class="fas fa-check-circle"></i> Minimum of 5 O'Level credits including English and Mathematics</li>
                            <li><i class="fas fa-check-circle"></i> WAEC, NECO, or NABTEB results (not more than 2 sittings)</li>
                            <li><i class="fas fa-check-circle"></i> JAMB UTME with appropriate subject combination</li>
                            <li><i class="fas fa-check-circle"></i> Age: 16 years and above</li>
                            <li><i class="fas fa-check-circle"></i> Birth certificate or age declaration</li>
                        </ul>
                        
                        <div class="fee-box">
                            <div class="fee-amount">₦15,000</div>
                            <div style="font-size: 0.8rem; color: #64748b;">Application Fee (Non-Refundable)</div>
                            <small>Excluding service charge fee</small>
                        </div>
                        
                        <h6 class="fw-bold mb-3 mt-3" style="color: var(--primary-dark);">Application Process</h6>
                        <ul class="process-list">
                            <li><span class="step-number">1</span> Fill the application form with valid details</li>
                            <li><span class="step-number">2</span> Make payment of ₦15,000 (Non-refundable)</li>
                            <li><span class="step-number">3</span> Receive Application ID via email upon successful payment</li>
                            <li><span class="step-number">4</span> Complete your application profile</li>
                        </ul>
                        
                        <div class="mt-3 pt-2">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <span style="background: var(--primary-soft); padding: 6px 14px; border-radius: 40px; font-size: 0.75rem;">
                                    <i class="fas fa-phone-alt me-1" style="color: var(--primary);"></i> Support: 08095536133
                                </span>
                                <span style="background: var(--primary-soft); padding: 6px 14px; border-radius: 40px; font-size: 0.75rem;">
                                    <i class="fas fa-envelope me-1" style="color: var(--primary);"></i> portal@edocoe.edu.ng
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i> Secure SSL Encrypted Payment
                            </small>
                        </div>
                    </div>
                </div>
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
        <div class="modal-content" style="border-radius: 24px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e2a2a, #2c3e2f); color: white; border: none;">
                <h5 class="modal-title"><i class="fas fa-credit-card me-2"></i>Payment Page</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div style="background: #f8fafc; border-radius: 20px; overflow: hidden;">
                    <table class="table table-borderless mb-0">
                        <tr><td style="width: 40%;"><strong>Dear:</strong></td><td><strong><?php echo htmlentities($fname); ?></strong></td></tr>
                        <tr><td><strong>Email:</strong></td><td><?php echo htmlentities($mail); ?></td></tr>
                        <tr><td><strong>Phone:</strong></td><td><?php echo htmlentities($pno); ?></td></tr>
                        <tr><td><strong>Payment For:</strong></td><td><?php echo htmlentities(strtoupper($prog)); ?></td></tr>
                        <tr><td><strong>Programme:</strong></td><td><?php echo htmlentities(ucwords($proh)); ?></td></tr>
                        <tr><td><strong>Academic Session:</strong></td><td><?php echo htmlentities($ses); ?></td></tr>
                        <tr style="background: var(--primary-soft);"><td><strong>Amount Paying:</strong></td><td><strong>₦<?php echo htmlentities(number_format($amt,2)); ?></strong></td></tr>
                        <tr style="background: var(--primary-soft);"><td><strong>Total Amount:</strong></td><td><strong>₦<?php echo htmlentities(number_format($amount,2)); ?></strong></td></tr>
                    </table>
                </div>
                <p class="mt-3 text-center small text-muted">You are paying for <strong><?php echo htmlentities($prog); ?></strong> for Admission Application into <strong><?php echo htmlentities(ucwords($proh)); ?></strong>. Click "Make Payment" to proceed.</p>
            </div>
            <div class="modal-footer justify-content-center gap-3" style="border: none; padding-bottom: 1.5rem;">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" style="border-radius: 40px; padding: 8px 25px;">Cancel</button>
                <button type="button" onclick="payWithMonnify()" class="btn btn-success" style="border-radius: 40px; padding: 8px 30px; background: linear-gradient(95deg, var(--primary), var(--primary-dark)); border: none;">Make Payment</button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small">&copy; <span id="currentYear"></span> - <a href="https://edocohest.edu.ng"><?php echo htmlentities($orgname . ', ' . $schadress); ?></a></p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small">
                    <i class="fas fa-shield-alt me-1" style="color: var(--primary);"></i> Secure Payment
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 20
    });
    
    document.getElementById('currentYear').innerText = new Date().getFullYear();
</script>

<script type="text/javascript">
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
            paymentDescription: "EDOCOHEST ADMISSION PAYMENT",
            /*incomeSplitConfig: [
                {
                    subAccountCode: "MFY_SUB_891671712178",
                    splitAmount: 500,
                    feeBearer: true
                }
            ],*/
            metadata: {
                name: fullname,
                Session: acad,
                Purpose: prog,
                Programme: proh
            },
            onLoadStart: () => {
                console.log("loading has started");
            },
            onLoadComplete: () => {
                console.log("SDK is UP");
            },
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

<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
<?php
    if(!empty($showModal)) {
        echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                new bootstrap.Modal(document.getElementById("checkModal")).show();
            });
        </script>';
    } 
    if(!empty($showModalEr)) {
        echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                new bootstrap.Modal(document.getElementById("checkModalEr")).show();
            });
        </script>';
    }
    if(!empty($showModalEri)) {
        echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                new bootstrap.Modal(document.getElementById("checkModalErl")).show();
            });
        </script>';
    }
?>
</html>