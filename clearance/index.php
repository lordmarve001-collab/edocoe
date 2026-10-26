<?php
ob_start();
session_start();
$connection = True;
include ("../include/dbconfig.php");

$_SESSION['uatCsrfToken'] = md5(uniqid());
$_SESSION['uatCsrfSalt'] = md5(uniqid());
$error = '';
$succ = '';
$prog = ''; // Initialize $prog variable at the top

$dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses = $dat['session'];
$dead = $dat['deadline'];
$admy = $ses;

if (isset($_POST['Proceed'])) {
    $_SESSION['staffid'] = $_POST['appd'];
    $_SESSION['admy'] = $admy;
    header("location:admission");
}

if (isset($_POST['registering'])) {
    if (empty($_POST['applid'])) {
        $error = "Application ID is Required";
        $showModalEr = 'true';
    } else {
        $prog = mysqli_escape_string($con, $_POST['applid']);
        
       $que = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$prog' AND session='$admy' AND status='Approved'"));

if ($que) {
    if ($que['apptype'] == "ND" || $que['apptype'] == "CERT" || $que['apptype'] == "JCHEW") {
        $showModal = 'true';
        $fullnam = $que['firstname'] . " " . $que['lastname'] . " " . $que['othername'];
    } elseif ($que['apptype'] == "PRE-NCE") {
        $showModal = 'true';
        $fullnam = $que['firstname'] . " " . $que['lastname'];
    }
} else {
    $error = "Invalid Application ID or Not Yet Approved";
    $showModalEr = 'true';
}
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" />
    <meta name="description" content="Admission Clearance Portal - Check and Accept Your Admission" />
    <meta name="author" content="" />
    <title><?php echo htmlentities($orgname); ?> | Admission Clearance Portal</title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2b5f5a;
            --primary-dark: #1e4541;
            --primary-light: #4a7c77;
            --primary-soft: #e0eeec;
            --primary-bg-light: #f0f6f5;
            --secondary: #2c5f5a;
            --accent: #f5a65b;
            --accent-dark: #e08e3a;
            --gray-bg: #f8fafc;
            --card-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
            --card-hover-shadow: 0 25px 40px -12px rgba(43, 95, 90, 0.25);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
            min-height: 100vh;
        }
        
        /* Modern Navbar */
        .navbar-modern-custom {
            background: linear-gradient(135deg, #1a2525 0%, #1e332f 100%);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 0;
            border-bottom: 3px solid var(--primary);
        }
        
        .navbar-modern-custom .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .navbar-modern-custom .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: 0.3s;
            border-radius: 30px;
        }
        
        .navbar-modern-custom .nav-link:hover {
            background: rgba(43, 95, 90, 0.25);
            color: var(--primary) !important;
            transform: translateY(-2px);
        }
        
        .navbar-modern-custom .nav-link.active {
            background: rgba(43, 95, 90, 0.3);
            color: var(--primary) !important;
        }
        
        /* Content Wrapper */
        .content-wrapper {
            padding: 2rem 0 3rem 0;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(120deg, #f0f6f5 0%, #e4efed 100%);
            padding: 2rem 0;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
        }
        
        .hero-badge {
            background: var(--primary-soft);
            color: var(--primary);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e4541, var(--primary-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin: 1rem 0;
        }
        
        /* Modern Card */
        .modern-card {
            background: white;
            border-radius: 28px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
        }
        
        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 1.2rem 1.5rem;
            color: white;
        }
        
        .card-header-custom h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        /* Form Inputs */
        .form-input-modern {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            transition: all 0.3s;
            background: white;
        }
        
        .form-input-modern:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(43, 95, 90, 0.1);
            outline: none;
        }
        
        .form-label-modern {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        /* Buttons */
        .btn-modern-primary {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 40px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 1rem;
        }
        
        .btn-modern-primary:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a37);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(43, 95, 90, 0.3);
            color: white;
        }
        
        .btn-modern-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 40px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-modern-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Info Alert */
        .info-alert {
            background: linear-gradient(135deg, var(--primary-bg-light) 0%, #e8f0ee 100%);
            border: none;
            border-left: 5px solid var(--primary);
            border-radius: 24px;
            padding: 1.5rem;
        }
        
        .info-alert ol {
            margin-top: 0.8rem;
            margin-bottom: 0;
        }
        
        .info-alert li {
            margin-bottom: 0.6rem;
            font-weight: 500;
        }
        
        /* Steps Indicator */
        .steps-wrapper {
            background: white;
            border-radius: 20px;
            padding: 1.2rem;
            margin-bottom: 1rem;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem;
            border-bottom: 1px solid #eef2f6;
        }
        
        .step-number {
            width: 32px;
            height: 32px;
            background: var(--primary-soft);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .step-text {
            font-size: 0.85rem;
            color: #4a5568;
            font-weight: 500;
        }
        
        /* Modal Styles */
        .modal-modern .modal-content {
            border-radius: 28px;
            border: none;
            overflow: hidden;
        }
        
        .modal-modern .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 1.2rem 1.5rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.8rem;
            }
            .content-wrapper {
                padding: 1rem 0 2rem;
            }
            .btn-modern-primary {
                width: 100%;
            }
        }
        
        /* Badge */
        .badge-session {
            background: var(--primary-soft);
            color: var(--primary-dark);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        
        /* Dashboard card icon */
        .icon-circle {
            width: 50px;
            height: 50px;
            background: var(--primary-soft);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }
        
        /* Success alert */
        .alert-success-custom {
            background: #e8f5e9;
            border-left: 4px solid var(--primary);
            color: #2e7d32;
        }
        
        /* Loading spinner */
        .loading-spinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .loading-spinner.active {
            display: flex;
        }
        
        .spinner-border-custom {
            width: 3rem;
            height: 3rem;
            border: 0.25rem solid var(--primary-soft);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<!-- Loading Spinner -->
<div class="loading-spinner" id="loadingSpinner">
    <div class="spinner-border-custom"></div>
</div>

<!-- Modern Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-modern-custom sticky-top">
    <div class="container">
       <a class="navbar-brand" href="#">
            <img src="../<?php echo $logos?>" style="height: 60px;"> <?php echo htmlentities($orgname); ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-expanded="false">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../apply/"><i class="fas fa-file-alt me-1"></i>Admission</a></li>
                <li class="nav-item"><a class="nav-link" href="../login"><i class="fas fa-user-graduate me-1"></i>Student Portal</a></li>
                <li class="nav-item"><a class="nav-link active" href="#"><i class="fas fa-check-circle me-1"></i>Clearance</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <span class="hero-badge">
                    <i class="fas fa-calendar-alt me-2"></i><?php echo htmlentities($ses); ?> Admission Session
                </span>
                <h1 class="hero-title">Admission Clearance Portal</h1>
                <p class="text-muted">Check your admission status, accept offer, and complete clearance process online.</p>
                <div class="mt-3">
                    <span class="badge-session"><i class="fas fa-hourglass-half me-1"></i> Deadline: <?php echo htmlentities($dead); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-wrapper">
    <div class="container">
        <div class="row g-4">
            <!-- Left Column - Check Admission Form -->
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                <div class="modern-card">
                    <div class="card-header-custom">
                        <h4><i class="fas fa-search me-2"></i>Check Admission Status</h4>
                    </div>
                    <div class="p-4">
                        <form action="" method="post" id="checkAdmissionForm">
                            <div class="mb-4">
                                <label class="form-label-modern" for="applid">
                                    <i class="fas fa-id-card me-1" style="color: var(--primary);"></i> Application ID
                                </label>
                                <input type="text" class="form-control form-input-modern" id="applid" name="applid" 
                                       placeholder="e.g., APP/2024/1234" required autocomplete="off">
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle"></i> Enter your application ID exactly as received
                                </small>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-block">
                                <button class="btn btn-modern-primary" type="submit" name="registering" id="checkBtn">
                                    <i class="fas fa-arrow-right me-2"></i>Check Admission Status
                                </button>
                            </div>
                        </form>
                        
                        <!-- Quick help -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-question-circle" style="color: var(--primary);"></i>
                                <small class="text-muted">Lost your Application ID? Contact admissions office for assistance.</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Info Card -->
                <div class="modern-card mt-4">
                    <div class="p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-circle">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Important Dates</h5>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Admission Processing</span>
                            <span class="fw-semibold"><?php echo htmlentities($ses); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>Acceptance Deadline</span>
                            <span class="fw-semibold" style="color: var(--primary);"><?php echo htmlentities($dead); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span>Clearance Portal Status</span>
                            <span class="badge" style="background: var(--primary); color: white;">Active</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Instructions -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="info-alert">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-circle">
                            <i class="fas fa-bell fa-lg"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Welcome to <?php echo htmlentities($orgname); ?> Clearance Portal</h5>
                    </div>
                    <p class="mb-2"><strong>Please follow these steps carefully:</strong></p>
                    <ol class="ps-3">
                        <li><i class="fas fa-check-circle me-2" style="color: var(--primary); font-size: 0.8rem;"></i> Enter your application ID to check admission status</li>
                        <li><i class="fas fa-check-circle me-2" style="color: var(--primary); font-size: 0.8rem;"></i> If "Congratulations" appears, click Proceed to accept admission</li>
                        <li><i class="fas fa-check-circle me-2" style="color: var(--primary); font-size: 0.8rem;"></i> Click "Make Payment" to accept admission and pay clearance fee</li>
                        <li><i class="fas fa-check-circle me-2" style="color: var(--primary); font-size: 0.8rem;"></i> Upload required documents after successful payment</li>
                        <li><i class="fas fa-check-circle me-2" style="color: var(--primary); font-size: 0.8rem;"></i> Print your official admission letter</li>
                        <li><i class="fas fa-check-circle me-2" style="color: var(--primary); font-size: 0.8rem;"></i> Pay tuition fee and get your Matriculation Number</li>
                    </ol>
                    <hr class="my-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-shield-alt" style="color: var(--primary);"></i>
                        <small class="text-muted">This is an official portal. All transactions are secure and encrypted.</small>
                    </div>
                </div>
                
                <!-- Steps Visual -->
                <div class="steps-wrapper mt-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-chart-line me-2" style="color: var(--primary);"></i>Clearance Process Flow</h6>
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-text">Check Admission Status</div>
                        <i class="fas fa-check-circle ms-auto" style="color: var(--primary);"></i>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-text">Accept Offer & Pay Clearance Fee</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-text">Upload Required Documents</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-text">Print Admission Letter</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">5</div>
                        <div class="step-text">Pay Tuition & Get Matric Number</div>
                    </div>
                </div>
                
                <!-- Contact Support -->
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-envelope me-1" style="color: var(--primary);"></i> Need help? 
                        <a href="#" style="color: var(--primary); text-decoration: none;">Contact Admissions Support</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade modal-modern" id="checkModalEr" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Error Message</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <i class="fas fa-times-circle fa-3x mb-3" style="color: #dc3545;"></i>
                    <p class="mb-0"><?php echo htmlentities($error); ?></p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn btn-modern-primary" type="button" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success / Congratulation Modal -->
<div class="modal fade modal-modern" id="checkModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark));">
                <h5 class="modal-title"><i class="fas fa-trophy me-2"></i>Congratulations!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <i class="fas fa-graduation-cap fa-4x mb-3" style="color: var(--primary);"></i>
                    <p class="mb-2">Dear <strong class="text-capitalize" style="color: var(--primary);"><?php echo isset($fullnam) ? htmlentities($fullnam) : ''; ?></strong></p>
                    <p>You have been offered a <strong>Provisional Admission</strong> into</p>
                    <div class="alert alert-success-custom py-2 rounded-4">
                        <strong><?php echo htmlentities($orgname); ?></strong>
                    </div>
                    <p class="mt-3">Click <strong>Proceed</strong> to accept your admission and upload the required documents.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-3">
                <button type="button" class="btn btn-modern-outline" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form method="POST" id="proceedForm">
                    <input type="hidden" name="appd" value="<?php echo isset($prog) ? htmlentities($prog) : ''; ?>">
                    <button type="submit" name="Proceed" class="btn btn-modern-primary">
                        <i class="fas fa-arrow-right me-2"></i>Proceed to Acceptance
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../include/footer.php'); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 20
    });
    
    // Show loading spinner on form submit
    document.getElementById('checkAdmissionForm')?.addEventListener('submit', function() {
        document.getElementById('loadingSpinner').classList.add('active');
    });
    
    // Hide loading spinner when page loads
    window.addEventListener('load', function() {
        document.getElementById('loadingSpinner').classList.remove('active');
    });
    
    // Form validation
    document.querySelector('#checkAdmissionForm')?.addEventListener('submit', function(e) {
        const applid = document.getElementById('applid').value.trim();
        if (!applid) {
            e.preventDefault();
            alert('Please enter your Application ID');
            document.getElementById('loadingSpinner').classList.remove('active');
            return false;
        }
    });
</script>

<?php
// Modal display logic - fixed to ensure modal shows properly
if (!empty($showModal)) {
   echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    var myModal = new bootstrap.Modal(document.getElementById("checkModal"), {
        backdrop: "static",
        keyboard: false
    });
    myModal.show();
});
</script>';
}
if (!empty($showModalEr)) {
   echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    var myModal = new bootstrap.Modal(document.getElementById("checkModalEr"), {
        backdrop: "static",
        keyboard: false
    });
    myModal.show();
});
</script>';
}
?>

</body>
</html>