<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';

if (!isset($_SESSION['staffid'])) {   
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
    $showModalEr = false;
    
    // Parent Insertion
    if (isset($_POST['submitpar'])) {
        if (empty($_POST['ptitle'])) {
            $error = "Select Parent Title";
            $showModalEr = true;
        } elseif (empty($_POST['famirelat'])) {
            $error = "Select Family Relation";
            $showModalEr = true;
        } elseif (empty($_POST['fullname'])) {
            $error = "Parent/Guardian Name Required";
            $showModalEr = true;
        } elseif (empty($_POST['address'])) {
            $error = "Address Required";
            $showModalEr = true;
        } elseif (empty($_POST['phone'])) {
            $error = "Phone Number Required";
            $showModalEr = true;
        } else {
            $titl = mysqli_real_escape_string($con, $_POST['ptitle']);
            $pfull = mysqli_real_escape_string($con, $_POST['fullname']);
            $padd = mysqli_real_escape_string($con, $_POST['address']);
            $pho = mysqli_real_escape_string($con, $_POST['phone']);
            $emai = mysqli_real_escape_string($con, $_POST['email']); 
            $famre = mysqli_real_escape_string($con, $_POST['famirelat']);
            $occ = mysqli_real_escape_string($con, $_POST['ocupation']);
            $pregi = mysqli_real_escape_string($con, $_POST['religh']);
            
            $check_sql = mysqli_query($con, "SELECT * FROM parentdetail WHERE applid='$harr'");
            if (mysqli_num_rows($check_sql) > 0) {
                $error = "Parent Detail Already Exist";
                $showModalEr = true;
            } else {
                $ins = mysqli_query($con, "INSERT INTO parentdetail (applid, title, parentname, address, phone, email, relat, occupation, relig) VALUES ('$harr','$titl','$pfull','$padd','$pho','$emai','$famre','$occ','$pregi')");
                if ($ins) {
                    echo '<script>window.location.href = "appsummary";</script>';
                    exit();
                } else {
                    $error = "Error While Submitting";
                    $showModalEr = true;
                }
            }
        }
    }
    
    // Parent Update
    if (isset($_POST['submitpup'])) {
        $titl = mysqli_real_escape_string($con, $_POST['ptitle']);
        $pfull = mysqli_real_escape_string($con, $_POST['fullname']);
        $padd = mysqli_real_escape_string($con, $_POST['address']);
        $pho = mysqli_real_escape_string($con, $_POST['phone']);
        $emai = mysqli_real_escape_string($con, $_POST['email']); 
        $famre = mysqli_real_escape_string($con, $_POST['famirelat']);
        $occ = mysqli_real_escape_string($con, $_POST['ocupation']);
        $pregi = mysqli_real_escape_string($con, $_POST['religh']);
        
        $upd = mysqli_query($con, "UPDATE parentdetail SET title='$titl', parentname='$pfull', address='$padd', phone='$pho', email='$emai', relat='$famre', occupation='$occ', relig='$pregi' WHERE applid='$harr'");
        if ($upd) {
            echo '<script>window.location.href = "appsummary";</script>';
            exit();
        } else {
            $error = "Error While Updating";
            $showModalEr = true;
        }
    }
    
    $parent_data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM parentdetail WHERE applid='$harr'"));
    $applicant_status = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$harr'"));
    $status = $applicant_status['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title><?php echo htmlentities($orgname); ?> - Parent/Guardian Information</title>
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
        
        .page-title h4 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--secondary-dark);
        }
        
        .page-title h4 i { color: var(--primary); margin-right: 10px; }
        
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
        
        .form-group { margin-bottom: 1.25rem; }
        
        .form-label {
            font-weight: 600;
            color: #1a2a2a;
            margin-bottom: 0.4rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .form-label i { color: var(--primary); }
        .required-star { color: #dc2626; font-size: 0.7rem; }
        
        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(43,95,90,0.1);
        }
        
        .form-control-custom:read-only,
        .form-control-custom[readonly] {
            background: #f1f5f9;
            cursor: not-allowed;
        }
        
        select.form-control-custom {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px;
        }
        
        textarea.form-control-custom {
            resize: vertical;
            min-height: 80px;
        }
        
        .btn-primary-modern {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-primary-modern:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: translateY(-2px);
        }
        
        .btn-danger-modern {
            background: linear-gradient(95deg, #dc2626, #b91c1c);
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-outline-modern {
            background: transparent;
            border: 1.5px solid var(--primary);
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 40px;
            color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .info-box {
            background: var(--primary-soft);
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 15px;
            font-size: 0.8rem;
            color: var(--primary-dark);
        }
        
        .info-box i { margin-right: 8px; }
        
        .two-columns { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        
        @media (max-width: 992px) {
            .sidebar-wrapper { flex: 100%; }
            .two-columns { grid-template-columns: 1fr; gap: 0; }
        }
        
        @media (max-width: 768px) {
            .main-wrapper { padding: 10px; }
            .card-body-modern { padding: 1rem; }
            .action-buttons { justify-content: center; }
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
                    <h4><i class="fas fa-users"></i> Parent / Guardian Information</h4>
                </div>
                
                <div class="card-modern" data-aos="fade-up">
                    <div class="card-header-modern">
                        <h4><i class="fas fa-user-friends"></i> Parent/Guardian Details</h4>
                    </div>
                    <div class="card-body-modern">
                        <?php if (empty($parent_data)) { ?>
                            <!-- New Registration Form -->
                            <form method="post">
                                <div class="two-columns">
                                    <div>
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-user-tie"></i> Title <span class="required-star">*</span></label>
                                            <select name="ptitle" class="form-control-custom" required>
                                                <option value="">Select Title</option>
                                                <?php
                                                $titles = mysqli_query($con, "SELECT * FROM titles ORDER BY title");
                                                while ($row = mysqli_fetch_array($titles)) {
                                                    echo "<option value='{$row['title']}'>{$row['title']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-user"></i> Full Name <span class="required-star">*</span></label>
                                            <input type="text" class="form-control-custom" name="fullname" maxlength="59" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-phone-alt"></i> Phone Number <span class="required-star">*</span></label>
                                            <input type="tel" class="form-control-custom" name="phone" maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-handshake"></i> Relationship <span class="required-star">*</span></label>
                                            <select name="famirelat" class="form-control-custom" required>
                                                <option value="">Select Relationship</option>
                                                <?php
                                                $relations = mysqli_query($con, "SELECT * FROM relat ORDER BY rela");
                                                while ($row = mysqli_fetch_array($relations)) {
                                                    echo "<option value='{$row['rela']}'>{$row['rela']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-briefcase"></i> Occupation</label>
                                            <input type="text" class="form-control-custom" name="ocupation" maxlength="57">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Address <span class="required-star">*</span></label>
                                            <textarea class="form-control-custom" name="address" required></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                                            <input type="email" class="form-control-custom" name="email" maxlength="137">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-pray"></i> Religion</label>
                                            <select name="religh" class="form-control-custom">
                                                <option value="">Select Religion</option>
                                                <?php
                                                $religions = mysqli_query($con, "SELECT * FROM relig ORDER BY reli");
                                                while ($row = mysqli_fetch_array($religions)) {
                                                    echo "<option value='{$row['reli']}'>{$row['reli']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="action-buttons">
                                    <button type="submit" name="submitpar" class="btn-danger-modern">
                                        <i class="fas fa-save me-2"></i> Save Parent Information
                                    </button>
                                </div>
                            </form>
                            
                        <?php } elseif ($status == 'submitted') { ?>
                            <!-- Editable Mode -->
                            <div class="info-box">
                                <i class="fas fa-edit"></i> You can edit your parent/guardian information below
                            </div>
                            <form method="post">
                                <div class="two-columns">
                                    <div>
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-user-tie"></i> Title</label>
                                            <select name="ptitle" class="form-control-custom" required>
                                                <option value="<?php echo htmlentities($parent_data['title']); ?>"><?php echo htmlentities($parent_data['title']); ?></option>
                                                <?php
                                                $titles = mysqli_query($con, "SELECT * FROM titles ORDER BY title");
                                                while ($row = mysqli_fetch_array($titles)) {
                                                    echo "<option value='{$row['title']}'>{$row['title']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
                                            <input type="text" class="form-control-custom" name="fullname" value="<?php echo htmlentities($parent_data['parentname']); ?>" maxlength="59" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-phone-alt"></i> Phone Number</label>
                                            <input type="tel" class="form-control-custom" name="phone" value="<?php echo htmlentities($parent_data['phone']); ?>" maxlength="11" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-handshake"></i> Relationship</label>
                                            <select name="famirelat" class="form-control-custom" required>
                                                <option value="<?php echo htmlentities($parent_data['relat']); ?>"><?php echo htmlentities($parent_data['relat']); ?></option>
                                                <?php
                                                $relations = mysqli_query($con, "SELECT * FROM relat ORDER BY rela");
                                                while ($row = mysqli_fetch_array($relations)) {
                                                    echo "<option value='{$row['rela']}'>{$row['rela']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-briefcase"></i> Occupation</label>
                                            <input type="text" class="form-control-custom" name="ocupation" value="<?php echo htmlentities($parent_data['occupation']); ?>" maxlength="57">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Address</label>
                                            <textarea class="form-control-custom" name="address" required><?php echo htmlentities($parent_data['address']); ?></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                                            <input type="email" class="form-control-custom" name="email" value="<?php echo htmlentities($parent_data['email']); ?>" maxlength="137">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label"><i class="fas fa-pray"></i> Religion</label>
                                            <select name="religh" class="form-control-custom">
                                                <option value="<?php echo htmlentities($parent_data['relig']); ?>"><?php echo htmlentities($parent_data['relig']); ?></option>
                                                <?php
                                                $religions = mysqli_query($con, "SELECT * FROM relig ORDER BY reli");
                                                while ($row = mysqli_fetch_array($religions)) {
                                                    echo "<option value='{$row['reli']}'>{$row['reli']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="action-buttons">
                                    <button type="submit" name="submitpup" class="btn-primary-modern">
                                        <i class="fas fa-save me-2"></i> Update Information
                                    </button>
                                </div>
                            </form>
                            
                        <?php } elseif ($status == 'Pending' || $status == 'Approved') { ?>
                            <!-- View Only Mode -->
                            <div class="info-box">
                                <i class="fas fa-lock"></i> Parent/Guardian information is locked for review
                            </div>
                            <div class="two-columns">
                                <div>
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-user-tie"></i> Title</label>
                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($parent_data['title']); ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($parent_data['parentname']); ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-phone-alt"></i> Phone Number</label>
                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($parent_data['phone']); ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-handshake"></i> Relationship</label>
                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($parent_data['relat']); ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-briefcase"></i> Occupation</label>
                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($parent_data['occupation']); ?>" readonly>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> Address</label>
                                        <textarea class="form-control-custom" readonly><?php echo htmlentities($parent_data['address']); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($parent_data['email']); ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label"><i class="fas fa-pray"></i> Religion</label>
                                        <input type="text" class="form-control-custom" value="<?php echo htmlentities($parent_data['relig']); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="action-buttons">
                                <button type="button" class="btn-outline-modern" disabled>
                                    <i class="fas fa-check-circle me-2"></i> Information Submitted
                                </button>
                            </div>
                        <?php } ?>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        AOS.init({ duration: 600, once: true, offset: 20 });
        document.getElementById('currentYear').innerText = new Date().getFullYear();
        <?php if ($showModalEr) { ?>
        new bootstrap.Modal(document.getElementById('errorModal')).show();
        <?php } ?>
    </script>
</body>
</html>
<?php } ?>