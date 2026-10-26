<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';
include 'apc.php';
require "../phpmailer/class.phpmailer.php";
$mail = new PHPMailer();

if (!isset($_SESSION['staffid']))
    header("Location:logout");
$now = time();
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
}

if (isset($_POST['approve'])) {
    $sel = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."'"));
    if ($sel['status'] == 'submitted') {
        $up = mysqli_query($con, "UPDATE applicant SET status='Pending' WHERE applid='".$_SESSION['staffid']."'");
        
        if ($up) {
            // Send SMS and Email notifications
            sendApplicationSubmittedSMS($_SESSION['staffid'], $con);
            sendApplicationSubmittedEmail($_SESSION['staffid'], $con, $mail, $orgname, $schadress, $logo, $hostna, $passd, $usern);
            
            echo '<script>window.location.href = "appsummary";</script>';
            exit();
        }
    }
}

if (isset($_POST['rapprove'])) {
    $sel = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."'"));
    if ($sel['status'] == 'rejected') {
        $up = mysqli_query($con, "UPDATE applicant SET status='Pending' WHERE applid='".$_SESSION['staffid']."'");
        echo '<script>window.location.href = "appsummary";</script>';
        exit();
    }
}

// Function to send SMS on application submission
function sendApplicationSubmittedSMS($studentid, $con) {
    $email = "imarve.wiz@gmail.com";
    $password = "Passw20ord";
    $login_url = "https://portal.edocohest.edu.ng/apply/applogin";
    
    // Get applicant details
    $applicant = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$studentid'"));
    $personal = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM personaldata WHERE applid='$studentid'"));
    
    $fullname = $applicant['lastname'] . " " . $applicant['firstname'];
    
    $message = "Dear " . $fullname . ",\n\n";
    $message .= "Your application has been successfully submitted to EDOCOHEST.\n\n";
    $message .= "App ID: " . $studentid . "\n";
    $message .= "Password: " . $applicant['phoneno'] . "\n\n";
    $message .= "Login: " . $login_url . "\n\n";
    $message .= "Thank you for choosing EDOCOHEST.";
    
    $sender_name = "EDOCOHEST";
    $recipients = $applicant['phoneno'];
    $forcednd = "1";
    
    $data = array(
        "email" => $email,
        "password" => $password,
        "message" => $message,
        "sender_name" => $sender_name,
        "recipients" => $recipients,
        "forcednd" => $forcednd
    );
    $data_string = json_encode($data);
    $ch = curl_init('https://app.multitexter.com/v2/app/sms');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
    $result = curl_exec($ch);
    curl_close($ch);
}

// Function to send Email on application submission
function sendApplicationSubmittedEmail($studentid, $con, $mail, $orgname, $schadress, $logo, $host, $pass, $user) {
    try {
        // Get applicant details
        $applicant = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$studentid'"));
        $personal = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM personaldata WHERE applid='$studentid'"));
        $programme = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM program WHERE pname='{$applicant['programme']}'"));
        
        $fullname = $applicant['lastname'] . " " . $applicant['firstname'] . " " . $applicant['othername'];
        $login_url = "https://portal.edocohest.edu.ng/apply/applogin";
        
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom($user, 'EDOCOHEST Application Portal');
        $mail->addAddress($applicant['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Application Submission Confirmation - EDOCOHEST';
        
        $message = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Application Submission Confirmation</title>
            <meta charset="UTF-8">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background: #f9f9f9;
                    border-radius: 10px;
                }
                .header {
                    background: linear-gradient(135deg, #1a2a2a, #1e3a35);
                    color: white;
                    padding: 20px;
                    text-align: center;
                    border-radius: 10px 10px 0 0;
                }
                .content {
                    background: white;
                    padding: 25px;
                    border-radius: 0 0 10px 10px;
                }
                .credentials {
                    background: #e8f3f1;
                    padding: 15px;
                    border-radius: 8px;
                    margin: 15px 0;
                    border-left: 4px solid #2b5f5a;
                }
                .status-badge {
                    display: inline-block;
                    background: #fef3c7;
                    color: #92400e;
                    padding: 5px 15px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: bold;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                    margin-top: 20px;
                    padding-top: 15px;
                    border-top: 1px solid #eee;
                }
                .button {
                    display: inline-block;
                    background: #2b5f5a;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 15px;
                }
                .info-box {
                    background: #f0fdf4;
                    padding: 12px;
                    border-radius: 8px;
                    margin: 15px 0;
                    border-left: 4px solid #10b981;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Application Submission Confirmation</h2>
                </div>
                <div class="content">
                    <p>Dear <strong>' . htmlspecialchars($fullname) . '</strong>,</p>
                    
                    <p>We are pleased to inform you that your application to <strong>Edo State College of Health Sciences and Technology (EDOCOHEST)</strong> has been successfully submitted.</p>
                    
                    <div class="credentials">
                        <h4 style="margin-top: 0; color: #1e4541;">Your Login Credentials</h4>
                        <p><strong>Application ID:</strong> ' . htmlspecialchars($studentid) . '</p>
                        <p><strong>Password:</strong> ' . htmlspecialchars($applicant['phoneno']) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($applicant['email']) . '</p>
                    </div>
                    
                    <div class="info-box">
                        <p><strong>📋 Application Summary:</strong></p>
                        <p><strong>Programme:</strong> ' . htmlspecialchars($applicant['programme']) . '</p>
                        <p><strong>Application Type:</strong> ' . htmlspecialchars($applicant['apptype']) . '</p>
                        <p><strong>Submission Date:</strong> ' . date("F j, Y, g:i a") . '</p>
                    </div>
                    
                    <p><strong>Next Steps:</strong></p>
                    <ol>
                        <li>Your application will be reviewed by the admissions committee</li>
                        <li>You will be notified via SMS and Email once a decision is made</li>
                        <li>Check your application status regularly by logging into the portal</li>
                        <li>Keep your Application ID and Password safe for future reference</li>
                    </ol>
                    
                    <div style="text-align: center;">
                        <a href="' . $login_url . '" class="button">Login to Portal</a>
                    </div>
                    
                    <p style="margin-top: 20px;">For any inquiries, please contact the Admissions Office:</p>
                    <p>📞 Phone: 08164550281 - 08163226994 - 07017865994<br> 
                    📧 Email: support@edocohest.edu.ng</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ' . htmlspecialchars($orgname) . '. All rights reserved.</p>
                    <p>' . htmlspecialchars($schadress) . '</p>
                    <p><small>This is an automated message, please do not reply to this email.</small></p>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAllRecipients();
    } catch (Exception $e) {
        // Log error if needed
        error_log("Email sending failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title><?php echo htmlentities($orgname); ?> - Application Summary</title>
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
            --danger: #ef4444;
            --info: #3b82f6;
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
        
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-badge.submitted { background: #fef3c7; color: #92400e; }
        .status-badge.pending { background: #dbeafe; color: #1e40af; }
        .status-badge.approved { background: #d1fae5; color: #065f46; }
        .status-badge.rejected { background: #fee2e2; color: #991b1b; }
        
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
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .info-item {
            background: var(--primary-soft);
            border-radius: 16px;
            padding: 1rem;
            transition: all 0.3s ease;
        }
        
        .info-item:hover { transform: translateX(5px); }
        
        .info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            font-weight: 700;
            font-size: 0.9rem;
            color: #1a2a2a;
            word-break: break-word;
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
        
        .subject-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .subject-table th {
            background: var(--primary-soft);
            padding: 10px 12px;
            text-align: left;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .subject-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.8rem;
        }
        
        .btn-primary-modern {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 12px 28px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-primary-modern:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: translateY(-2px);
        }
        
        .btn-success-modern {
            background: linear-gradient(95deg, var(--success), #059669);
            border: none;
            padding: 12px 28px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
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
        
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
            background: #f1f5f9;
        }
        
        @media (max-width: 992px) {
            .sidebar-wrapper { flex: 100%; }
            .info-grid { grid-template-columns: 1fr; }
        }
        
        @media (max-width: 768px) {
            .main-wrapper { padding: 10px; }
            .card-body-modern { padding: 1rem; }
            .profile-image { width: 100px; height: 100px; }
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
        
        @media print {
            .navbar-modern, .sidebar-wrapper, .action-buttons, footer, .btn-print, .btn-primary-modern, .btn-success-modern {
                display: none !important;
            }
            .main-wrapper { padding: 0; }
            .content-container { display: block; }
            .main-content { margin-left: 0; }
            .card-modern { box-shadow: none; border: 1px solid #e2e8f0; }
        }
        
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
                <?php
                $stc = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."'"));
                $status = $stc['status'];
                ?>
                
                <div class="page-title">
                    <h2>
                        <i class="fas fa-clipboard-list"></i> Application Summary
                        <?php
                        if ($status == 'submitted') echo '<span class="status-badge submitted ms-3"><i class="fas fa-clock me-1"></i> Pending Review</span>';
                        elseif ($status == 'Pending') echo '<span class="status-badge pending ms-3"><i class="fas fa-spinner me-1"></i> Under Review</span>';
                        elseif ($status == 'Approved') echo '<span class="status-badge approved ms-3"><i class="fas fa-check-circle me-1"></i> Approved</span>';
                        elseif ($status == 'rejected') echo '<span class="status-badge rejected ms-3"><i class="fas fa-times-circle me-1"></i> Rejected</span>';
                        ?>
                    </h2>
                </div>
                
                <?php if ($status == 'submitted') { ?>
                    <!-- Review and Submit Mode -->
                    <div class="card-modern" data-aos="fade-up">
                        <div class="card-header-modern">
                            <h4><i class="fas fa-eye"></i> Review Your Application</h4>
                        </div>
                        <div class="card-body-modern">
                            <?php
                            $sql = "SELECT * FROM applicant JOIN personaldata ON personaldata.applid=applicant.applid WHERE applicant.applid='".$_SESSION['staffid']."' AND personaldata.applid='".$_SESSION['staffid']."'";
                            $query = mysqli_query($con, $sql);
                            $result = mysqli_fetch_assoc($query);
                            ?>
                            
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    <img src="<?php echo $result['passport']; ?>" class="profile-image" alt="Passport">
                                </div>
                                <div class="col-md-9">
                                    <div class="info-grid">
                                        <div class="info-item"><div class="info-label">Application ID</div><div class="info-value"><?php echo htmlentities($result["applid"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Full Name</div><div class="info-value"><?php echo htmlentities($result['surname']." ".$result['firstname']." ".$result['lastname']); ?></div></div>
                                        <div class="info-item"><div class="info-label">Email</div><div class="info-value"><?php echo htmlentities($result["email"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Phone</div><div class="info-value"><?php echo htmlentities($result["phone"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Gender</div><div class="info-value"><?php echo htmlentities($result["gender"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Marital Status</div><div class="info-value"><?php echo htmlentities($result["marital"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Date of Birth</div><div class="info-value"><?php echo htmlentities($result["dob"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Nationality</div><div class="info-value"><?php echo htmlentities($result['nationality']); ?></div></div>
                                        <?php if($result['nationality'] == "Foreign") { ?>
                                            <div class="info-item"><div class="info-label">Country</div><div class="info-value"><?php echo htmlentities($result["state"]); ?></div></div>
                                        <?php } else { ?>
                                            <div class="info-item"><div class="info-label">State</div><div class="info-value"><?php echo htmlentities($result["state"]); ?></div></div>
                                            <div class="info-item"><div class="info-label">LGA</div><div class="info-value"><?php echo htmlentities($result["lga"]); ?></div></div>
                                        <?php } ?>
                                        <div class="info-item"><div class="info-label">Home Address</div><div class="info-value"><?php echo htmlentities($result["address"]); ?></div></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <div class="section-title"><i class="fas fa-graduation-cap me-2"></i>Programme Information</div>
                                <div class="info-grid mt-2">
                                    <div class="info-item"><div class="info-label">Academic Session</div><div class="info-value"><?php echo htmlentities($result["session"]); ?></div></div>
                                    <div class="info-item"><div class="info-label">Faculty</div><div class="info-value"><?php echo htmlentities($facu); ?></div></div>
                                    <div class="info-item"><div class="info-label">Department</div><div class="info-value"><?php echo htmlentities($def); ?></div></div>
                                    <div class="info-item"><div class="info-label">Programme</div><div class="info-value"><?php echo htmlentities($result['programme']); ?></div></div>
                                </div>
                            </div>
                            
                            <!-- O-Level Results -->
                            <div class="mt-4">
                                <div class="section-title"><i class="fas fa-book me-2"></i>O-Level Results</div>
                                <?php
                                $first_sitting = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM oleveldetail WHERE applid='".$_SESSION['staffid']."' AND noofsit='1'"));
                                if ($first_sitting) { ?>
                                    <div class="mt-3"><strong>First Sitting</strong></div>
                                    <table class="subject-table mt-2">
                                        <thead><tr><th>Subject</th><th>Grade</th></tr></thead>
                                        <tbody>
                                        <?php
                                        $results = mysqli_query($con, "SELECT * FROM olevelresultdetail WHERE applid='".$_SESSION['staffid']."' AND examno='".$first_sitting['examno']."' ORDER BY subject");
                                        while ($row = mysqli_fetch_assoc($results)) {
                                            echo "<tr><td>".htmlentities($row['subject'])."</td><td>".htmlentities($row['grade'])."</td></tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                                
                                <?php
                                $second_sitting = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM oleveldetail WHERE applid='".$_SESSION['staffid']."' AND noofsit='2'"));
                                if ($second_sitting) { ?>
                                    <div class="mt-3"><strong>Second Sitting</strong></div>
                                    <table class="subject-table mt-2">
                                        <thead><tr><th>Subject</th><th>Grade</th></tr></thead>
                                        <tbody>
                                        <?php
                                        $results = mysqli_query($con, "SELECT * FROM olevelresultdetail WHERE applid='".$_SESSION['staffid']."' AND examno='".$second_sitting['examno']."' ORDER BY subject");
                                        while ($row = mysqli_fetch_assoc($results)) {
                                            echo "<tr><td>".htmlentities($row['subject'])."</td><td>".htmlentities($row['grade'])."</td></tr>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </div>
                            
                            <!-- Parent Information -->
                            <div class="mt-4">
                                <div class="section-title"><i class="fas fa-users me-2"></i>Parent/Guardian Information</div>
                                <?php
                                $parent = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM parentdetail WHERE applid='".$_SESSION['staffid']."'"));
                                if ($parent) { ?>
                                    <div class="info-grid mt-2">
                                        <div class="info-item"><div class="info-label">Parent Name</div><div class="info-value"><?php echo htmlentities($parent["title"] . " " . $parent['parentname']); ?></div></div>
                                        <div class="info-item"><div class="info-label">Address</div><div class="info-value"><?php echo htmlentities($parent["address"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Phone</div><div class="info-value"><?php echo htmlentities($parent["phone"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Email</div><div class="info-value"><?php echo htmlentities($parent["email"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Relationship</div><div class="info-value"><?php echo htmlentities($parent["relat"]); ?></div></div>
                                        <div class="info-item"><div class="info-label">Occupation</div><div class="info-value"><?php echo htmlentities($parent["occupation"]); ?></div></div>
                                    </div>
                                <?php } ?>
                            </div>
                            
                            <div class="mt-4">
                                <div class="section-title"><i class="fas fa-receipt me-2"></i>Payment Evidence</div>
                                <?php
                                $payment_evidence = mysqli_fetch_assoc(mysqli_query($con, "SELECT payment_evidence FROM applicant WHERE applid='".$_SESSION['staffid']."'"));
                                if ($payment_evidence && !empty($payment_evidence['payment_evidence'])) { ?>
                                    <div class="text-center mt-2">
                                        <a href="<?php echo $payment_evidence['payment_evidence']; ?>" target="_blank" class="btn btn-outline-primary">
                                            <i class="fas fa-eye me-2"></i> View Payment Evidence
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                            
                            <div class="action-buttons text-center mt-4">
                                <form method="post" onsubmit="return confirm('Are you sure you want to submit your application? You cannot make changes after submission.');">
                                    <button type="submit" name="approve" class="btn-primary-modern">
                                        <i class="fas fa-check-circle me-2"></i> Submit Application
                                    </button>
                                </form>
                                <p class="text-muted small mt-3">
                                    <i class="fas fa-info-circle"></i> By submitting, you confirm that all information provided is accurate and complete.
                                    You will receive a confirmation SMS and Email after submission.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                <?php } elseif ($status == 'Pending') { ?>
                    <!-- Application Submitted - Awaiting Confirmation -->
                    <div class="card-modern" data-aos="fade-up">
                        <div class="card-header-modern">
                            <h4><i class="fas fa-hourglass-half"></i> Application Submitted</h4>
                        </div>
                        <div class="card-body-modern">
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle" style="font-size: 3rem; color: var(--success); margin-bottom: 1rem;"></i>
                                <h4 style="color: var(--success);">Application Successfully Submitted!</h4>
                                <p>Your application has been received and is currently under review.</p>
                                <p>You will receive a notification once a decision is made on your application.</p>
                                <div class="alert alert-info mt-3" style="background: var(--primary-soft); border: none; border-radius: 16px;">
                                    <i class="fas fa-envelope me-2"></i> A confirmation email and SMS have been sent to your registered email and phone number.
                                </div>
                                <hr>
                                <button onclick="window.open('printdetail','_blank')" class="btn-print">
                                    <i class="fas fa-print me-2"></i> Print Application Details
                                </button>
                            </div>
                        </div>
                    </div>
                    
                <?php } elseif ($status == 'Approved') { ?>
                    <!-- Admission Confirmed -->
                    <div class="card-modern" data-aos="fade-up">
                        <div class="card-header-modern">
                            <h4><i class="fas fa-trophy"></i> Admission Confirmed</h4>
                        </div>
                        <div class="card-body-modern">
                           <div class="text-center py-4">
                                <i class="fas fa-trophy" style="font-size: 3rem; color: var(--success); margin-bottom: 1rem;"></i>
                                <h4 style="color: var(--success);">Congratulations! Admission Confirmed</h4>
                                <p>You have been offered provisional admission. Please proceed to accept your offer.</p>
                                <hr>
                                <div class="d-flex justify-content-center gap-3 flex-wrap">
                                    <button onclick="window.open('admission_letter','_blank')" class="btn-print">
                                        <i class="fas fa-print me-2"></i> Print Admission Letter
                                    </button>
                                    <button onclick="window.open('https://portal.edocohest.edu.ng/clearance/', '_blank')" class="btn-primary-modern">
                                        <i class="fas fa-check-circle me-2"></i> Accept Admission
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php } elseif ($status == 'rejected') { ?>
                    <!-- Application Rejected -->
                    <div class="card-modern" data-aos="fade-up">
                        <div class="card-header-modern">
                            <h4><i class="fas fa-times-circle"></i> Application Status</h4>
                        </div>
                        <div class="card-body-modern">
                            <div class="text-center py-4">
                                <i class="fas fa-times-circle" style="font-size: 3rem; color: var(--danger); margin-bottom: 1rem;"></i>
                                <h4 style="color: var(--danger);">Application Not Successful</h4>
                                <?php
                                $rejection = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM rejectedam WHERE applid='".$_SESSION['staffid']."'"));
                                if ($rejection) { ?>
                                    <p><strong>Reason:</strong> <?php echo htmlentities($rejection['reason']); ?></p>
                                <?php } ?>
                                <hr>
                                <form method="post">
                                    <button type="submit" name="rapprove" class="btn-primary-modern">
                                        <i class="fas fa-sync-alt me-2"></i> Submit Re-application
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        
        <footer>
            <p class="mb-0 small">&copy; <span id="currentYear"></span> - <a href="#"><?php echo htmlentities($orgname . ', ' . $schadress); ?></a></p>
        </footer>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 600, once: true, offset: 20 });
        document.getElementById('currentYear').innerText = new Date().getFullYear();
    </script>
</body>
</html>