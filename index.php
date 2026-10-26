<?php 
$connection=TRUE;
include 'include/dbconfig.php';
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM abaresultsession WHERE description='admission processing'"));
$sess=$dat['session'];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title><?php echo htmlentities($orgname) ;?></title>
     <link href="<?php echo htmlentities($logos) ;?>" rel="shortcut icon" type="image/x-icon" />
    <meta name="description" content="Student Portal - Edo State College of Education" />
    <title><?php echo htmlentities($orgname); ?> - Student Portal</title>
    <link href="<?php echo htmlentities($logos); ?>" rel="shortcut icon" type="image/x-icon" />
    
    <!-- Google Fonts + Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2b5f5a;
            --primary-dark: #1e4541;
            --primary-light: #4a7f7a;
            --primary-soft: #e8f3f1;
            --secondary-dark: #1a2a2a;
            --secondary-light: #2c3e2f;
            --accent-gold: #d4a843;
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
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        
        /* Modern Navbar */
        .navbar-modern {
            background: linear-gradient(135deg, #1a2a2a 0%, #1e3a35 100%);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            padding: 0.8rem 0;
            transition: all 0.3s ease;
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
        .hero-section {
            background: linear-gradient(120deg, #e8f3f1 0%, #dcece8 100%);
            padding: 3rem 0 2.5rem;
            border-bottom: 1px solid rgba(43,95,90,0.15);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(43,95,90,0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .hero-title {
            font-weight: 800;
            font-size: 2.6rem;
            background: linear-gradient(135deg, #1e3a35, var(--primary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }
        
        /* Card Design */
        .portal-card {
            background: white;
            border-radius: 28px;
            border: none;
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 12px 28px -8px rgba(0, 32, 64, 0.08);
            height: 100%;
            overflow: hidden;
            position: relative;
        }
        
        .portal-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            transform: scaleX(0);
            transition: transform 0.3s ease;
            transform-origin: left;
        }
        
        .portal-card:hover::after {
            transform: scaleX(1);
        }
        
        .portal-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 28px 40px -12px rgba(43,95,90,0.25);
        }
        
        .card-header-icon {
            width: 56px;
            height: 56px;
            background: var(--primary-soft);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .card-header-icon i {
            font-size: 1.8rem;
            color: var(--primary);
        }
        
        .card-title-modern {
            font-weight: 700;
            font-size: 1.45rem;
            margin-bottom: 0.75rem;
            color: #1a2a2a;
        }
        
        /* Buttons */
        .btn-modern {
            border-radius: 40px;
            padding: 0.5rem 1.3rem;
            font-weight: 600;
            transition: 0.2s;
            margin: 0.2rem 0.2rem;
            border: none;
            font-size: 0.85rem;
        }
        
        .btn-primary-modern {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 10px rgba(43,95,90,0.2);
        }
        
        .btn-primary-modern:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: scale(1.02);
            color: white;
        }
        
        .btn-outline-modern {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            color: #2c3e50;
        }
        
        .btn-outline-modern:hover {
            background: var(--primary-soft);
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .session-badge {
            background: var(--primary-soft);
            color: var(--primary-dark);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 30px;
            display: inline-block;
            margin-bottom: 12px;
        }
        
        /* Footer */
        footer {
            background: #1a2421;
            color: #cfdfe9;
            border-radius: 30px 30px 0 0;
            padding: 2rem 0;
            margin-top: 3rem;
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
        
        /* Grid spacing */
        .card-grid-gap {
            row-gap: 2rem;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.9rem;
            }
            .card-title-modern {
                font-size: 1.25rem;
            }
            .btn-modern {
                padding: 0.4rem 1rem;
                font-size: 0.75rem;
            }
        }
        
        .btn-modern:active {
            transform: scale(0.97);
        }
        
        /* Custom scrollbar */
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
        
        /* Animation */
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
        
        .portal-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }
    </style>
</head>
<body>

<?php
// Original PHP logic preserved
$connection = TRUE;
include 'include/dbconfig.php';
$dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses = $dat['session'];
$dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT semester,session,deadline FROM abaresultsession WHERE description='admission processing'"));
$sess = $dat['session'];

// Fallback values if not defined
if (!isset($orgname)) $orgname = "Edo State College of Education";
if (!isset($schadress)) $schadress = "Benin City, Nigeria";
if (!isset($logos)) $logos = "#";
?>

<!-- Modern Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-modern sticky-top">
    <div class="container">
        <a class="navbar-brand" href="https://edocohest.edu.ng">
            <img src="<?php echo $logos?>" style="height: 60px;"> <?php echo htmlentities($orgname); ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarModern" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarModern">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="apply/"><i class="fas fa-file-alt me-1"></i>Admissions</a></li>
                <li class="nav-item"><a class="nav-link" href="login"><i class="fas fa-user-graduate me-1"></i>Student Login</a></li>
                <li class="nav-item"><a class="nav-link" href="transcript"><i class="fas fa-scroll me-1"></i>Transcript</a></li>
                <li class="nav-item"><a class="nav-link" href="#hostel/"><i class="fas fa-bed me-1"></i>Hostel</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container py-3 py-md-4">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <div class="d-inline-flex align-items-center gap-2 bg-white px-3 py-2 rounded-pill shadow-sm mb-4">
                    <i class="fas fa-chalkboard-user" style="color: var(--primary);"></i>
                    <span style="font-size: 0.8rem; font-weight: 600; color: #1a2a2a;">Official Student Portal</span>
                </div>
                <h1 class="hero-title">Welcome to the Student Portal</h1>
                <p class="lead text-secondary mt-3">Seamless access to admissions, academic records, and campus services â€” all in one place.</p>
                <div class="mt-4">
                    <a href="https://www.edocohest.edu.ng/" class="btn btn-modern btn-primary-modern me-2"><i class="fas fa-arrow-left"></i> Back to College Website</a>
                    <a href="login" class="btn btn-modern btn-outline-modern"><i class="fas fa-key"></i> Portal Login</a>
                                        <a href="apply/" class="btn btn-modern btn-primary-modern me-2"><i class="fas fa-arrow-right"></i> Apply Now</a>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Cards -->
<div class="container my-5">
    <div class="row g-4 card-grid-gap">
        
        <!-- NCE Application Card -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
            <div class="portal-card p-4 d-flex flex-column h-100">
                <div class="card-header-icon"><i class="fas fa-scroll"></i></div>
                <div><span class="session-badge"><i class="far fa-calendar-alt me-1"></i> <?php echo htmlentities($ses); ?> Session</span></div>
                <h3 class="card-title-modern">Admission Application</h3>
                <p class="text-secondary mb-3">Admission application ongoing. Start your journey with EDOCOHEST.</p>
                <div class="mt-auto d-flex flex-wrap gap-2">
                    <a class="btn btn-modern btn-primary-modern" href="apply/"><i class="fas fa-pen-alt"></i> Apply Now</a>
                    <a class="btn btn-modern btn-outline-modern" href="apply/applogin"><i class="fas fa-sign-in-alt"></i> Already have App No</a>
                </div>
            </div>
        </div>
        
        <!-- Acceptance / Clearance Card -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="portal-card p-4 d-flex flex-column h-100">
                <div class="card-header-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <div><span class="session-badge"><i class="far fa-calendar-alt me-1"></i> <?php echo htmlentities($ses); ?> Session</span></div>
                <h3 class="card-title-modern">Acceptance / Clearance</h3>
                <p class="text-secondary mb-3">Accept your admission offer and pay clearance fees to secure your spot.</p>
                <div class="mt-auto">
                    <a class="btn btn-modern btn-primary-modern w-100" href="clearance/"><i class="fas fa-check-circle"></i> Click to Accept &raquo;</a>
                </div>
            </div>
        </div>
        
        <!-- Student Login Card -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
            <div class="portal-card p-4 d-flex flex-column h-100">
                <div class="card-header-icon"><i class="fas fa-id-card-alt"></i></div>
                <h3 class="card-title-modern">Student Login</h3>
                <p class="text-secondary mb-3">Returning students access portal using Matric Number and password.</p>
                <div class="mt-auto">
                    <a class="btn btn-modern btn-primary-modern w-100" href="login"><i class="fas fa-lock-open"></i> Access Dashboard</a>
                </div>
            </div>
        </div>
        
        <!-- Academic Transcript Card -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="portal-card p-4 d-flex flex-column h-100">
                <div class="card-header-icon"><i class="fas fa-file-alt"></i></div>
                <h3 class="card-title-modern">Academic Transcript</h3>
                <p class="text-secondary mb-3">Apply for your academic transcript and get it delivered within 72 hours.</p>
                <div class="mt-auto">
                    <a class="btn btn-modern btn-primary-modern w-100" href="transcript"><i class="fas fa-download"></i> Apply Now</a>
                </div>
            </div>
        </div>
        
        <!-- Hostel Application Card -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
            <div class="portal-card p-4 d-flex flex-column h-100">
                <div class="card-header-icon"><i class="fas fa-hotel"></i></div>
                <h3 class="card-title-modern">Hostel Accommodation</h3>
                <p class="text-secondary mb-3">Apply for on-campus hostel accommodation. Limited slots available.</p>
                <div class="mt-auto">
                    <a class="btn btn-modern btn-primary-modern w-100" href="#hostel/"><i class="fas fa-bed"></i> Apply Now</a>
                </div>
            </div>
        </div>
        
        <!-- Payment Validation Card -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="portal-card p-4 d-flex flex-column h-100">
                <div class="card-header-icon"><i class="fas fa-credit-card"></i></div>
                <h3 class="card-title-modern">Payment Verification</h3>
                <p class="text-secondary mb-3">Account debited but transaction not showing? Verify your payment here.</p>
                <div class="mt-auto">
                    <a class="btn btn-modern btn-primary-modern w-100" href="payment_verification"><i class="fas fa-shield-alt"></i> Verify Now</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Disclaimer Notice -->
    <div class="row mt-5" data-aos="fade-up" data-aos-delay="350">
        <div class="col-12">
            <div class="alert border-0 rounded-4 shadow-sm d-flex align-items-start gap-3 p-4" style="background: linear-gradient(135deg, #fff9eb, #fff5e0); border-left: 5px solid var(--accent-gold);">
                <i class="fas fa-exclamation-triangle fa-2x" style="color: var(--accent-gold);"></i>
              <!--  <div>
                    <strong class="fs-6" style="color: #8b6914;">Official Payment Notice</strong>
                    <p class="mb-0 mt-1">All school fees and charges must be made exclusively through the student portal. Do NOT pay into any personal account. Management will not be liable for unauthorized payments. <a href="#" style="color: var(--primary); font-weight: 500;">Read full policy â†’</a></p>
                </div>-->
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="container body-content">
    <hr />
    <footer>
        <p>&copy; <span id="demo"></span> - <a href="https://edocohest.edu.ng"><?php echo htmlentities($orgname . ', ' . $schadress); ?></a></p>
    </footer>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 20
    });
    
    document.getElementById('demo').innerText = new Date().getFullYear();
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target && this.getAttribute('href') !== '#') {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
</body>
</html>