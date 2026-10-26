<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$staffid = $_SESSION["staffid"];
include 'dataco.php';

if (strlen($_SESSION['staffid']) == "") {   
    header("Location: logout"); 
    exit();
} else {
    $now = time();
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location: logout");
        exit();
    }
    
    $error = '';
    $succ = '';
    $showModalEr = false;
    $showModal = false;
    
    // Get payment status
    $payment = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid='$staffid' AND purpose='Application Fee' ORDER BY id DESC LIMIT 1"));
    $current_status = $payment['status'] ?? 'Pending';
    $current_evidence = $payment['payment_evidence'] ?? '';
    $payment_id = $payment['id'] ?? 0;
    $reject_reason = '';
    
    // Get rejection reason if any
    if ($current_status == 'Rejected') {
        $rejection = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM payment_rejections WHERE payment_id='$payment_id' ORDER BY id DESC LIMIT 1"));
        $reject_reason = $rejection['reason'] ?? '';
    }
    
    // Create payment_evidence directory if not exists
    $upload_dir = '../applys/payment_evidence/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    if (isset($_POST["submit_evidence"])) {
        if ($current_status != 'Rejected') {
            $error = "Your payment is not in rejected status. You cannot update evidence at this time.";
            $showModalEr = true;
        } elseif (empty($_FILES['payment_evidence']['name'])) {
            $error = "Please select a file to upload as payment evidence.";
            $showModalEr = true;
        } else {
            $filenamg = basename($_FILES['payment_evidence']['name']);
            $filetmp = $_FILES['payment_evidence']['tmp_name'];
            $extension = array("png", "jpg", "jpeg", "PNG", "JPG", "JPEG", "pdf", "PDF");
            $file_extension = pathinfo($filenamg, PATHINFO_EXTENSION);
            
            if (!in_array($file_extension, $extension)) {
                $error = "Upload valid file. Only PNG, JPG, JPEG, PDF are allowed.";
                $showModalEr = true;
            } elseif ($_FILES["payment_evidence"]["size"] > 2000000) {
                $error = "File size exceeds 2MB.";
                $showModalEr = true;
            } elseif ($_FILES["payment_evidence"]["error"] !== UPLOAD_ERR_OK) {
                $error = "File upload error. Please try again.";
                $showModalEr = true;
            } else {
                // Generate unique filename
                $timestamp = date("Ymd_His");
                $safe_staffid = str_replace('/', '_', $staffid);
                $payment_filename = $safe_staffid . "_" . $timestamp . "." . $file_extension;
                $payment_path = "payment_evidence/" . $payment_filename;
                $full_path = $upload_dir . $payment_filename;
                
                if (move_uploaded_file($filetmp, $full_path)) {
                    chmod($full_path, 0644);
                    
                    // Update payment record with new evidence and reset status
                    $update = mysqli_query($con, "UPDATE studentpayment SET payment_evidence='$payment_path', status='Pending Verification', paytime=NOW() WHERE id='$payment_id'");
                    
                    if ($update) {
                        $succ = "Payment evidence has been updated successfully. Your payment is now pending verification.";
                        $showModal = true;
                        // Refresh payment data
                        $payment = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid='$staffid' AND purpose='Application Fee' ORDER BY id DESC LIMIT 1"));
                        $current_status = $payment['status'] ?? 'Pending';
                        $current_evidence = $payment['payment_evidence'] ?? '';
                    } else {
                        $error = "Error updating payment record. Please try again.";
                        $showModalEr = true;
                    }
                } else {
                    $error = "Error uploading file. Please check folder permissions and try again.";
                    $showModalEr = true;
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title><?php echo "$orgname"; ?> - Update Payment Evidence</title>
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
            padding: 5px 15px;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-success { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        
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
        
        .evidence-container {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 16px;
            margin: 15px 0;
        }
        
        .current-evidence {
            max-width: 100%;
            max-height: 250px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 10px;
        }
        
        .upload-area {
            border: 2px dashed #e2e8f0;
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .upload-area:hover {
            border-color: var(--primary);
            background: var(--primary-soft);
        }
        
        .upload-area i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .file-info {
            font-size: 0.8rem;
            margin-top: 10px;
        }
        
        .info-box {
            background: var(--primary-soft);
            border-radius: 16px;
            padding: 15px;
            margin-top: 1.5rem;
        }
        
        .info-box i { color: var(--primary); margin-right: 8px; }
        
        .rejection-box {
            background: #fee2e2;
            border-left: 4px solid var(--danger);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .rejection-box i { color: var(--danger); margin-right: 8px; }
        
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
        
        .btn-disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }
        
        @media (max-width: 992px) {
            .sidebar-wrapper { flex: 100%; }
        }
        
        @media (max-width: 768px) {
            .main-wrapper { padding: 10px; }
            .card-body-modern { padding: 1rem; }
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
                <div class="page-title">
                    <h2><i class="fas fa-receipt"></i> Update Payment Evidence</h2>
                </div>
                
                <div class="card-modern" data-aos="fade-up">
                    <div class="card-header-modern">
                        <h4><i class="fas fa-credit-card"></i> Payment Status: 
                            <?php
                            if ($current_status == 'Pending Verification') {
                                echo '<span class="status-badge status-pending"><i class="fas fa-clock me-1"></i> Pending Verification</span>';
                            } elseif ($current_status == 'Success') {
                                echo '<span class="status-badge status-success"><i class="fas fa-check-circle me-1"></i> Approved</span>';
                            } elseif ($current_status == 'Rejected') {
                                echo '<span class="status-badge status-rejected"><i class="fas fa-times-circle me-1"></i> Rejected</span>';
                            } else {
                                echo '<span class="status-badge status-pending">' . htmlspecialchars($current_status) . '</span>';
                            }
                            ?>
                        </h4>
                    </div>
                    <div class="card-body-modern">
                        
                        <?php if ($current_status == 'Rejected'): ?>
                            <!-- Rejection Notice -->
                            <div class="rejection-box">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Payment Rejected!</strong>
                                <?php if (!empty($reject_reason)): ?>
                                    <p class="mb-0 mt-2"><strong>Reason:</strong> <?php echo nl2br(htmlspecialchars($reject_reason)); ?></p>
                                <?php else: ?>
                                    <p class="mb-0 mt-2">Your payment evidence was rejected. Please upload a valid payment teller/receipt.</p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Current Evidence Display -->
                            <?php if (!empty($current_evidence) && file_exists('../applys/' . $current_evidence)): ?>
                            <div class="evidence-container">
                                <label class="form-label fw-bold mb-2">Previously Uploaded Evidence (Rejected)</label>
                                <div>
                                    <?php
                                    $evidence_ext = pathinfo($current_evidence, PATHINFO_EXTENSION);
                                    if (in_array(strtolower($evidence_ext), ['jpg', 'jpeg', 'png'])): ?>
                                        <img src="../applys/<?php echo htmlspecialchars($current_evidence); ?>" class="current-evidence" alt="Payment Evidence">
                                    <?php else: ?>
                                        <a href="../applys/<?php echo htmlspecialchars($current_evidence); ?>" target="_blank" class="btn btn-outline-primary">
                                            <i class="fa fa-file-pdf-o"></i> View PDF Evidence
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Upload New Evidence Form -->
                            <form method="post" enctype="multipart/form-data">
                                <div class="upload-area" onclick="document.getElementById('payment_evidence').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p class="mb-2"><strong>Click to upload new payment evidence</strong></p>
                                    <p class="text-muted small">Supported formats: JPG, PNG, PDF (Max 2MB)</p>
                                    <input type="file" id="payment_evidence" name="payment_evidence" accept=".jpg,.jpeg,.png,.pdf" style="display: none;" required>
                                </div>
                                <div id="file-name" class="file-info text-center"></div>
                                
                                <div class="info-box">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Guidelines for Payment Evidence:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Upload a clear, readable image of your bank teller or payment receipt</li>
                                        <li>Ensure the teller shows the College account details (Account Name: Edo State College of Health Sciences and Technology, Account No: 2319008390, Bank: Zenith Bank)</li>
                                        <li>Image must be in JPG, JPEG, PNG, or PDF format</li>
                                        <li>Maximum file size is 2MB</li>
                                        <li>Make sure the amount paid (₦15,000) is clearly visible</li>
                                    </ul>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <button type="submit" name="submit_evidence" class="btn-primary-modern">
                                        <i class="fas fa-upload me-2"></i> Submit New Evidence
                                    </button>
                                </div>
                            </form>
                            
                        <?php elseif ($current_status == 'Success'): ?>
                            <!-- Payment Already Approved -->
                            <div class="evidence-container">
                                <i class="fas fa-check-circle" style="font-size: 3rem; color: var(--success);"></i>
                                <h5 class="mt-3" style="color: var(--success);">Payment Already Verified!</h5>
                                <p>Your payment has been successfully verified and approved.</p>
                                <?php if (!empty($current_evidence) && file_exists('../applys/' . $current_evidence)): ?>
                                    <div class="mt-3">
                                        <label class="form-label fw-bold">Your Payment Evidence</label>
                                        <div>
                                            <?php
                                            $evidence_ext = pathinfo($current_evidence, PATHINFO_EXTENSION);
                                            if (in_array(strtolower($evidence_ext), ['jpg', 'jpeg', 'png'])): ?>
                                                <img src="../applys/<?php echo htmlspecialchars($current_evidence); ?>" class="current-evidence" alt="Payment Evidence">
                                            <?php else: ?>
                                                <a href="../applys/<?php echo htmlspecialchars($current_evidence); ?>" target="_blank" class="btn btn-outline-primary">
                                                    <i class="fa fa-file-pdf-o"></i> View PDF Evidence
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                        <?php elseif ($current_status == 'Pending Verification'): ?>
                            <!-- Payment Pending -->
                            <div class="evidence-container">
                                <i class="fas fa-clock" style="font-size: 3rem; color: var(--warning);"></i>
                                <h5 class="mt-3" style="color: var(--warning);">Payment Pending Verification</h5>
                                <p>Your payment evidence has been submitted and is awaiting verification by the finance team.</p>
                                <p>Please check back later for updates on your payment status.</p>
                                
                                <?php if (!empty($current_evidence) && file_exists('../applys/' . $current_evidence)): ?>
                                    <div class="mt-3">
                                        <label class="form-label fw-bold">Your Submitted Evidence</label>
                                        <div>
                                            <?php
                                            $evidence_ext = pathinfo($current_evidence, PATHINFO_EXTENSION);
                                            if (in_array(strtolower($evidence_ext), ['jpg', 'jpeg', 'png'])): ?>
                                                <img src="../applys/<?php echo htmlspecialchars($current_evidence); ?>" class="current-evidence" alt="Payment Evidence">
                                            <?php else: ?>
                                                <a href="../applys/<?php echo htmlspecialchars($current_evidence); ?>" target="_blank" class="btn btn-outline-primary">
                                                    <i class="fa fa-file-pdf-o"></i> View PDF Evidence
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                        <?php else: ?>
                            <!-- No Payment Record -->
                            <div class="evidence-container">
                                <i class="fas fa-exclamation-circle" style="font-size: 3rem; color: var(--danger);"></i>
                                <h5 class="mt-3" style="color: var(--danger);">No Payment Record Found</h5>
                                <p>You have not made any payment yet. Please make payment and upload your evidence.</p>
                                <a href="index.php" class="btn-primary-modern mt-3" style="display: inline-block; text-decoration: none;">
                                    <i class="fas fa-arrow-left me-2"></i> Go to Application
                                </a>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <footer>
            <p class="mb-0 small">&copy; <span id="currentYear"></span> - <a href="#"><?php echo htmlentities($orgname . ', ' . $schadress); ?></a></p>
        </footer>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Error</h5>
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
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--success), #059669); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Success</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <i class="fas fa-check-circle" style="font-size: 2.5rem; color: var(--success); margin-bottom: 0.5rem;"></i>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        AOS.init({ duration: 600, once: true, offset: 20 });
        document.getElementById('currentYear').innerText = new Date().getFullYear();
        
        document.getElementById('payment_evidence')?.addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : '';
            var fileSize = e.target.files[0] ? (e.target.files[0].size / 1024).toFixed(2) + ' KB' : '';
            
            if (fileName) {
                document.getElementById('file-name').innerHTML = '<i class="fas fa-check-circle text-success"></i> Selected: ' + fileName + ' (' + fileSize + ')';
            } else {
                document.getElementById('file-name').innerHTML = '';
            }
        });
        
        <?php if ($showModalEr) { ?>
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        <?php } ?>
        <?php if ($showModal) { ?>
        new bootstrap.Modal(document.getElementById('successModal')).show();
        <?php } ?>
    </script>
</body>
</html>
<?php } ?>