<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

if (empty($_GET['ref']) || empty($_GET['feedback'])) {
      header("location: index?");
}

$refe = $_GET['ref'];                        

$data = mysqli_fetch_assoc(mysqli_query($con, "SELECT studentid, amount, paymentreference, purpose FROM studentpayment WHERE paymentreference = '$refe'"));
if ($data != null) {
    $_POST["appliD"] = $data["studentid"];
    $_POST["amon"] = $data["amount"];
    $_POST["refe"] = $data["paymentreference"];
    $_POST["pp"] = $data["purpose"];
} else { 
    header("location: index");
}

$reft = $_POST["refe"];
$_SESSION['payre'] = $reft;
$dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='" . $_POST["appliD"] . "'"));
$_POST["fname"] = $dat["firstname"];
$_POST["sname"] = $dat["lastname"];
$_POST["programm"] = $dat["programme"];
$_POST["othername"] = $dat["othername"];
$_POST["phone"] = $dat["phoneno"];
$_POST["Email"] = $dat["email"];
$_POST["Pass"] = $dat["password"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="description" content="Payment Success - Official Receipt" />
    <title><?php echo htmlentities($orgname); ?> - Payment Successful</title>
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
            --success-soft: #d1fae5;
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
        
        /* Success Hero */
        .success-hero {
            background: linear-gradient(135deg, var(--success), #059669);
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .success-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        .success-icon i {
            font-size: 3rem;
            color: white;
        }
        
        .success-title {
            font-weight: 800;
            font-size: 1.8rem;
            color: white;
            text-align: center;
        }
        
        .success-subtitle {
            color: rgba(255,255,255,0.9);
            text-align: center;
            font-size: 1rem;
        }
        
        /* Receipt Container */
        .receipt-wrapper {
            padding: 3rem 0;
        }
        
        .receipt-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, var(--secondary-dark), var(--secondary-light));
            padding: 1.5rem;
            text-align: center;
            color: white;
            border-bottom: 4px solid var(--primary);
        }
        
        .receipt-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .receipt-logo img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        
        .receipt-title {
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 2px;
        }
        
        .receipt-badge {
            display: inline-block;
            background: var(--success);
            padding: 5px 15px;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        
        .receipt-body {
            padding: 2rem;
        }
        
        /* Info Sections */
        .info-section {
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--secondary-dark);
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .info-item {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1rem;
            transition: all 0.3s ease;
        }
        
        .info-item:hover {
            background: var(--primary-soft);
            transform: translateX(5px);
        }
        
        .info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            font-weight: 700;
            font-size: 1rem;
            color: #1e2a2a;
            word-break: break-word;
        }
        
        .amount-box {
            background: linear-gradient(135deg, var(--primary-soft), #fff);
            border: 2px solid var(--primary);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            margin: 1rem 0;
        }
        
        .amount-label {
            font-size: 0.8rem;
            color: #64748b;
            letter-spacing: 1px;
        }
        
        .amount-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .login-credentials {
            background: var(--success-soft);
            border-radius: 20px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--success);
        }
        
        .credential-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px dashed rgba(16,185,129,0.3);
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .credential-row:last-child {
            border-bottom: none;
        }
        
        .credential-label {
            font-weight: 600;
            color: var(--secondary-dark);
        }
        
        .credential-value {
            font-family: monospace;
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-dark);
            background: white;
            padding: 4px 12px;
            border-radius: 8px;
        }
        
        .info-message {
            background: #eff6ff;
            border-radius: 16px;
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 1.5rem 0;
        }
        
        .info-message i {
            color: var(--primary);
            font-size: 1.2rem;
            margin-top: 2px;
        }
        
        .info-message p {
            margin: 0;
            font-size: 0.85rem;
            color: #1e40af;
            line-height: 1.4;
        }
        
        /* Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin: 1rem 0;
        }
        
        .btn-print {
            background: linear-gradient(95deg, #334155, #1e293b);
            border: none;
            padding: 12px 28px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-print:hover {
            background: linear-gradient(95deg, #1e293b, #0f172a);
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-login {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 12px 28px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-login:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: translateY(-2px);
            color: white;
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
        
        /* Print Styles */
        @media print {
            .navbar-modern,
            .success-hero,
            .action-buttons,
            footer,
            .btn,
            button {
                display: none !important;
            }
            
            .receipt-wrapper {
                padding: 0;
                margin: 0;
            }
            
            .receipt-card {
                box-shadow: none;
                border: 1px solid #e2e8f0;
            }
            
            body {
                background: white;
                padding: 20px;
            }
            
            .info-item {
                background: #f8fafc;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .amount-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .login-credentials {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .receipt-body {
                padding: 1.5rem;
            }
            .amount-value {
                font-size: 1.5rem;
            }
            .credential-row {
                flex-direction: column;
                align-items: flex-start;
            }
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
        
        .receipt-card {
            animation: fadeInUp 0.6s ease-out;
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
    </style>
</head>
<body>

<!-- Modern Navigation Bar -->
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
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="applogin"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Success Hero Section -->
<section class="success-hero">
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="success-title">Payment Successful!</h1>
        <p class="success-subtitle">Your application payment has been processed successfully</p>
    </div>
</section>

<!-- Receipt Section -->
<div class="receipt-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="receipt-card">
                    <div class="receipt-header">
                        <div class="receipt-logo">
                            <img src="<?php echo htmlentities($logo); ?>" alt="Logo">
                        </div>
                        <div class="receipt-title"><?php echo htmlentities($orgname); ?></div>
                        <div class="receipt-badge">
                            <i class="fas fa-receipt me-1"></i> OFFICIAL RECEIPT
                        </div>
                    </div>
                    
                    <div class="receipt-body">
                        <!-- Transaction Info -->
                        <div class="info-section">
                            <div class="section-title">
                                <i class="fas fa-info-circle me-2"></i>Transaction Details
                            </div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Transaction Reference</div>
                                    <div class="info-value"><?php echo htmlentities($_POST["refe"]); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Payment Date & Time</div>
                                    <div class="info-value">
                                        <?php
                                        date_default_timezone_set("africa/lagos");
                                        echo date("l, d F Y") . "<br>" . date("h:i:s A");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Personal Information -->
                        <div class="info-section">
                            <div class="section-title">
                                <i class="fas fa-user-circle me-2"></i>Personal Information
                            </div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Application ID</div>
                                    <div class="info-value"><?php echo htmlentities($_POST["appliD"]); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value"><?php echo htmlentities($_POST["fname"]); ?> <?php echo htmlentities($_POST["sname"]); ?> <?php echo htmlentities($_POST["othername"]); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Phone Number</div>
                                    <div class="info-value"><?php echo htmlentities($_POST["phone"]); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value"><?php echo htmlentities($_POST["Email"]); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Programme</div>
                                    <div class="info-value"><?php echo htmlentities($_POST["programm"]); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Information -->
                        <div class="amount-box">
                            <div class="amount-label">TOTAL AMOUNT PAID</div>
                            <div class="amount-value">₦ <?php echo htmlentities(number_format($_POST["amon"], 2)); ?></div>
                            <div class="amount-label mt-2">Payment Purpose: <?php echo htmlentities($_POST["pp"]); ?></div>
                        </div>
                        
                        <!-- Login Credentials -->
                        <div class="info-section">
                            <div class="section-title">
                                <i class="fas fa-key me-2"></i>Login Credentials
                            </div>
                            <div class="login-credentials">
                                <div class="credential-row">
                                    <span class="credential-label"><i class="fas fa-id-card me-2"></i>Application ID / Username:</span>
                                    <span class="credential-value"><?php echo htmlentities($_POST["appliD"]); ?></span>
                                </div>
                                <div class="credential-row">
                                    <span class="credential-label"><i class="fas fa-lock me-2"></i>Password:</span>
                                    <span class="credential-value"><?php echo htmlentities($_POST["phone"]); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Important Message -->
                        <div class="info-message">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Important:</strong> Your account has been created successfully! 
                                Please check your email inbox (including spam/junk folder) for further instructions 
                                to complete your application. You can also login using the credentials above.
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="button" onclick="window.print();" class="btn-print">
                                <i class="fas fa-print me-2"></i>Print Receipt
                            </button>
                            <a href="applogin" class="btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Continue to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
                    <a href="#"><?php echo htmlentities($orgname); ?></a>
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small">
                    <i class="fas fa-graduation-cap me-1" style="color: var(--primary);"></i> Excellence in Education
                    <span class="mx-2">|</span>
                    <i class="fas fa-shield-alt me-1"></i> Secure Payment Confirmed
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

<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>