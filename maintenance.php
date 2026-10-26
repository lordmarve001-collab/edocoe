<?php 
$connection=TRUE;
include 'include/dbconfig.php';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Maintenance | <?php echo htmlentities($orgname) ;?></title>\
     <link href="<?php echo htmlentities($logos) ;?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0c7b46 0%, #4CAF50 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            line-height: 1.6;
        }

        .maintenance-container {
            max-width: 800px;
            width: 100%;
            text-align: center;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #fff;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .logo p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
        }

        .maintenance-icon {
            font-size: 5rem;
            color: #C8E6C9;
            margin: 20px 0;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }

        .message {
            font-size: 1.2rem;
            margin-bottom: 40px;
            color: rgba(255, 255, 255, 0.95);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #C8E6C9;
        }

        .countdown-container {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 15px;
            padding: 25px;
            margin: 40px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .countdown-title {
            font-size: 1.4rem;
            margin-bottom: 20px;
            color: #C8E6C9;
            font-weight: 600;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .countdown-item {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 20px 15px;
            min-width: 120px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .countdown-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.25);
        }

        .countdown-value {
            font-size: 3rem;
            font-weight: 700;
            display: block;
            color: #fff;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .countdown-label {
            font-size: 1rem;
            color: #E8F5E9;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
            font-weight: 500;
        }

        .progress-container {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            overflow: hidden;
            margin: 30px 0;
            height: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #A5D6A7, #66BB6A);
            border-radius: 10px;
            width: 65%;
            animation: progress-animation 3s ease-in-out infinite alternate;
            box-shadow: 0 0 10px rgba(166, 214, 167, 0.3);
        }

        @keyframes progress-animation {
            0% { width: 65%; }
            100% { width: 70%; }
        }

        .contact-info {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .contact-info h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #C8E6C9;
        }

        .contact-info p {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .contact-info a {
            color: #C8E6C9;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .contact-info a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .social-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .social-icon:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .leaf-decoration {
            font-size: 2rem;
            color: #A5D6A7;
            margin: 10px 0;
            opacity: 0.7;
        }

        .footer {
            margin-top: 40px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
        }

        .green-accent {
            color: #C8E6C9;
            font-weight: 600;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .maintenance-container {
                padding: 30px 20px;
            }
            
            h2 {
                font-size: 1.8rem;
            }
            
            .countdown-item {
                min-width: 90px;
                padding: 15px 10px;
            }
            
            .countdown-value {
                font-size: 2.2rem;
            }
            
            .maintenance-icon {
                font-size: 4rem;
            }
            
            .message {
                font-size: 1.1rem;
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .countdown {
                gap: 10px;
            }
            
            .countdown-item {
                min-width: 70px;
                padding: 10px 5px;
            }
            
            .countdown-value {
                font-size: 1.8rem;
            }
            
            .social-icon {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="logo">
            <h1><?php echo htmlentities($orgname) ;?></h1>
            <p>We're performing scheduled maintenance for a better experience</p>
        </div>

        <div class="leaf-decoration">
            <i class="fas fa-leaf"></i>
        </div>
        
        <div class="maintenance-icon">
            <i class="fas fa-tools"></i>
        </div>

        <h2>We're Undergoing Maintenance</h2>
        
        <div class="message">
            <p>We're currently performing essential updates and improvements to bring you an enhanced experience. We appreciate your patience and understanding during this process.</p>
        </div>

        <div class="leaf-decoration">
            <i class="fas fa-seedling"></i>
        </div>

        <div class="countdown-container">
            <div class="countdown-title">Expected Completion In:</div>
            <div class="countdown">
                <div class="countdown-item">
                    <span class="countdown-value" id="days">00</span>
                    <span class="countdown-label">Days</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value" id="hours">00</span>
                    <span class="countdown-label">Hours</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value" id="minutes">00</span>
                    <span class="countdown-label">Minutes</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value" id="seconds">00</span>
                    <span class="countdown-label">Seconds</span>
                </div>
            </div>
        </div>

        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>

        <div class="contact-info">
            <h3><i class="fas fa-life-ring"></i> Need Immediate Assistance?</h3>
            <p>Email us at: <a href="mailto:support@edocohest.edu.ng">support@edocohest.edu.ng</a></p>
            <p>Or call: <a href="tel:08164550281">08164550281 | 08088128133</a></p>
            <p style="margin-top: 15px; font-size: 0.95rem; color: rgba(255, 255, 255, 0.85);">
                <i class="fas fa-clock"></i> Our support team is available 24/7 during maintenance
            </p>
        </div>

        <div class="social-links">
            <a href="#" class="social-icon" title="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-icon" title="Twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-icon" title="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="social-icon" title="LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="#" class="social-icon" title="Status Updates">
                <i class="fas fa-broadcast-tower"></i>
            </a>
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo isset($siteName) ? htmlspecialchars($siteName) : 'Our Website'; ?>. All Rights Reserved.</p>
            <p>Maintenance started at: <span class="green-accent"><?php echo date('F j, Y, g:i a'); ?></span></p>
            <p style="margin-top: 10px; font-size: 0.85rem;">We're working hard to get back online as soon as possible</p>
        </div>
    </div>

    <script>
        // Set the countdown date (adjust this to your expected completion time)
        // Format: Month Day, Year HH:MM:SS
        const countdownDate = new Date("<?php echo date('F j, Y H:i:s', strtotime('+2 days')); ?>").getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = countdownDate - now;

            // Calculate days, hours, minutes, seconds
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Update display
            document.getElementById("days").textContent = days.toString().padStart(2, '0');
            document.getElementById("hours").textContent = hours.toString().padStart(2, '0');
            document.getElementById("minutes").textContent = minutes.toString().padStart(2, '0');
            document.getElementById("seconds").textContent = seconds.toString().padStart(2, '0');

            // Update progress bar based on time passed
            const totalTime = 2 * 24 * 60 * 60 * 1000; // 2 days in milliseconds
            const timePassed = totalTime - distance;
            const progressPercentage = Math.min(95, Math.max(5, (timePassed / totalTime) * 100));
            document.querySelector('.progress-bar').style.width = progressPercentage + '%';

            // If the countdown is over
            if (distance < 0) {
                clearInterval(countdownInterval);
                document.getElementById("days").textContent = "00";
                document.getElementById("hours").textContent = "00";
                document.getElementById("minutes").textContent = "00";
                document.getElementById("seconds").textContent = "00";
                document.querySelector('.progress-bar').style.width = '100%';
                document.querySelector('.progress-bar').style.background = 'linear-gradient(90deg, #A5D6A7, #2E7D32)';
                document.querySelector('.progress-bar').style.animation = 'none';
                
                // Update the message
                document.querySelector(".countdown-title").textContent = "Maintenance Complete!";
                document.querySelector(".message p").innerHTML = "Our maintenance is now complete. The website should be back online shortly. <span class='green-accent'>Thank you for your patience!</span>";
                
                // Change icon
                document.querySelector('.maintenance-icon i').className = 'fas fa-check-circle';
                document.querySelector('.maintenance-icon').style.color = '#A5D6A7';
            }
        }

        // Update countdown every second
        const countdownInterval = setInterval(updateCountdown, 1000);
        
        // Initial call to avoid delay
        updateCountdown();
    </script>
</body>
</html>