<?php
include("include/logis.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" />
    <meta name="description" content="Application Login Portal - Check your application status" />
    <meta name="author" content="" />
    <title><?php echo htmlentities($orgname); ?> - Application Login</title>
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
        
        /* Hero Section */
        .login-hero {
            background: linear-gradient(135deg, #1a2a2a 0%, #1e3a35 100%);
            padding: 2.5rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .login-hero::before {
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
        
        .login-hero::after {
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
        
        .login-badge {
            background: var(--primary);
            color: white;
            padding: 6px 18px;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .login-title {
            font-weight: 800;
            font-size: 2rem;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }
        
        /* Login Container */
        .login-wrapper {
            padding: 3rem 0;
        }
        
        .login-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease;
            border: none;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary-light) 100%);
            padding: 1.5rem;
            text-align: center;
            color: white;
        }
        
        .login-header h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
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
        }
        
        .input-group-custom {
            position: relative;
        }
        
        .input-group-custom input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            font-family: 'Inter', sans-serif;
        }
        
        .input-group-custom input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(43,95,90,0.1);
        }
        
        .input-group-custom .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            transition: color 0.2s;
            background: white;
            padding-left: 8px;
        }
        
        .input-group-custom .toggle-password:hover {
            color: var(--primary);
        }
        
        .btn-login {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 12px 24px;
            font-weight: 700;
            border-radius: 40px;
            width: 100%;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-login:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(43,95,90,0.3);
            color: white;
        }
        
        .signup-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: color 0.2s;
        }
        
        .signup-link:hover {
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
        
        .info-body ol {
            margin-top: 0.75rem;
            padding-left: 1.2rem;
        }
        
        .info-body li {
            margin: 0.75rem 0;
            color: #334155;
            line-height: 1.4;
            font-size: 0.85rem;
        }
        
        .info-body li i {
            color: var(--primary);
            margin-right: 8px;
        }
        
        .support-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .support-btn {
            background: var(--primary-soft);
            padding: 6px 14px;
            border-radius: 40px;
            font-size: 0.7rem;
            color: var(--primary-dark);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .support-btn i {
            color: var(--primary);
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
        
        .login-card, .info-card {
            animation: fadeInUp 0.6s ease-out;
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
        
        @media (max-width: 768px) {
            .login-body {
                padding: 1.5rem;
            }
            .login-title {
                font-size: 1.6rem;
            }
            .info-card {
                margin-top: 1.5rem;
            }
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
                <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="index"><i class="fas fa-file-alt me-1"></i>Apply Now</a></li>
                <li class="nav-item"><a class="nav-link" href="../clearance/"><i class="fas fa-headset me-1"></i>Acceptance</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="login-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="login-badge">
                    <i class="fas fa-lock me-2"></i>Secure Portal Access
                </div>
                <h1 class="login-title">Application Login</h1>
                <p class="login-subtitle">Access your application dashboard to check status, complete your profile, and view updates.</p>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block" data-aos="fade-left">
                <i class="fas fa-file-alt" style="font-size: 4rem; color: rgba(43,95,90,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<!-- Login Section -->
<div class="login-wrapper">
    <div class="container">
        <div class="row g-4">
            <!-- Left Column - Login Form -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-sign-in-alt" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                        <h4>Login to Portal</h4>
                        <p class="mb-0" style="opacity: 0.8;">Enter your credentials to continue</p>
                    </div>
                    <div class="login-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-id-card me-2"></i>Application ID
                                </label>
                                <div class="input-group-custom">
                                    <input type="text" name="applid" placeholder="Enter your Application ID" required />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="input-group-custom">
                                    <input type="password" id="password-field" name="password" placeholder="Enter your password" required />
                                    <i class="fa fa-fw toggle-password fa-eye" id="togglePassword"></i>
                                </div>
                            </div>
                            
                            <button type="submit" name="login" class="btn-login">
                                <i class="fas fa-arrow-right-to-bracket me-2"></i>Click to Login
                            </button>
                            
                            <div class="text-center mt-3">
                                <span style="color: #64748b; font-size: 0.85rem;">Don't have an Application ID?</span>
                                <a href="index" class="signup-link ms-1">Signup now <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Information Panel -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="info-card">
                    <div class="info-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Welcome to <?php echo htmlentities($orgname); ?></h5>
                    </div>
                    <div class="info-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-university" style="font-size: 2rem; color: var(--primary); margin-right: 12px;"></i>
                            <strong style="color: var(--primary-dark);">Admission Application Portal</strong>
                        </div>
                        <hr>
                        <strong>Please Note:</strong>
                        <ol>
                            <li><i class="fas fa-user-plus"></i> Create your account by completing the application form</li>
                            <li><i class="fas fa-envelope"></i> Use your username and password mailed to you after successful application payment</li>
                            <li><i class="fas fa-headset"></i> For support: Call/Whatsapp - 08164550281, 08163226994</li>
                        </ol>
                        
                        <div class="support-buttons">
                            <span class="support-btn"><i class="fas fa-phone-alt"></i> 08164550281</span>
                            <span class="support-btn"><i class="fas fa-phone-alt"></i> 08163226994</span>
                        </div>
                        
                        <div class="mt-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <span class="badge" style="background: var(--primary-soft); color: var(--primary-dark); padding: 8px 16px;">
                                    <i class="fas fa-clock me-1"></i> 24/7 Portal Access
                                </span>
                                <span class="badge" style="background: var(--primary-soft); color: var(--primary-dark); padding: 8px 16px;">
                                    <i class="fas fa-shield-alt me-1"></i> Secure SSL Encrypted
                                </span>
                            </div>
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
                    <a href="#"><?php echo htmlentities($orgname . ', ' . $schadress); ?></a>
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small">
                    <i class="fas fa-graduation-cap me-1" style="color: var(--primary);"></i> Excellence in Health Education
                    <span class="mx-2">|</span>
                    <i class="fas fa-shield-alt me-1"></i> Secure Login
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
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password-field');
    
    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
</script>

</body>
</html>