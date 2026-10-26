<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
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
    
    if (isset($_POST["submitpd"])) {
        $filenamg = basename($_FILES['passport']['name']);
        $filetmp = $_FILES['passport']['tmp_name'];
        $filesize = $_FILES['passport']['size'];
        $extension = array("png", "jpg", "jpeg", "PNG", "JPG", "JPEG");
        $file_extension = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
        
        if (!file_exists($_FILES["passport"]["tmp_name"])) {
            $error = "Choose image file to upload.";
            $showModalEr = true;
        } elseif (!in_array($file_extension, $extension)) {
            $error = "Upload valid images. Only PNG, JPG, and JPEG are allowed.";
            $showModalEr = true;
        } elseif ($_FILES["passport"]["size"] > 500000) {
            $error = "Image size exceeds 500KB.";
            $showModalEr = true;
        } else {
            $nwtourimg = explode(".", $filenamg);
            $fileExt = end($nwtourimg);
            $res = trim(str_ireplace(array('\'', '"', ',', ';', '<', '>', '/', '-', '_', ''), '', $_SESSION['staffid']));
            $fileBase = $res . "." . $fileExt;
            $dir = "photo/";
            $final_dir = $dir . $fileBase;
            
            if (move_uploaded_file($filetmp, $final_dir)) {
                $upd = mysqli_query($con, "UPDATE personaldata SET passport='$final_dir' WHERE applid='".$_SESSION['staffid']."'");
                if ($upd) {
                    $succ = 'Passport Successfully Updated';
                    $showModal = true;
                } else {
                    $error = "Sorry, error occurred when submitting";
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
    <title><?php echo "$orgname"; ?> - Update Passport</title>
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
        
        .photo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .current-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            background: #f1f5f9;
            margin-bottom: 1rem;
        }
        
        .photo-preview {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px dashed var(--primary);
            background: var(--primary-soft);
            margin-top: 1rem;
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
        
        .file-input-wrapper {
            position: relative;
            display: inline-block;
        }
        
        .file-input-wrapper input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .info-box {
            background: var(--primary-soft);
            border-radius: 16px;
            padding: 15px;
            margin-top: 1.5rem;
        }
        
        .info-box i { color: var(--primary); margin-right: 8px; }
        
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
        
        @media (max-width: 992px) {
            .sidebar-wrapper { flex: 100%; }
        }
        
        @media (max-width: 768px) {
            .main-wrapper { padding: 10px; }
            .card-body-modern { padding: 1rem; }
            .current-photo, .photo-preview { width: 150px; height: 150px; }
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
                    <h2><i class="fas fa-camera"></i> Update Passport Photograph</h2>
                </div>
                
                <div class="card-modern" data-aos="fade-up">
                    <div class="card-header-modern">
                        <h4><i class="fas fa-image"></i> Passport Update</h4>
                    </div>
                    <div class="card-body-modern">
                        <form method="post" enctype="multipart/form-data">
                            <div class="photo-container">
                                <div>
                                    <label class="form-label fw-bold mb-2">Current Passport</label>
                                    <div>
                                        <?php if ($passport != '' && file_exists($passport)) { ?>
                                            <img id="currentPhoto" src="<?php echo htmlentities($passport); ?>" class="current-photo" alt="Current Passport">
                                        <?php } else { ?>
                                            <img id="currentPhoto" src="photo/avater.png" class="current-photo" alt="No Photo">
                                        <?php } ?>
                                    </div>
                                </div>
                                
                                <div class="upload-area mt-4">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--primary);"></i>
                                    <p class="mt-2 mb-2">Click or drag to upload new passport photo</p>
                                    <div class="file-input-wrapper">
                                        <button type="button" class="btn-outline-modern" style="background: var(--primary-soft); border: 1px solid var(--primary); padding: 8px 20px; border-radius: 40px;">
                                            <i class="fas fa-folder-open me-2"></i> Choose File
                                        </button>
                                        <input type="file" name="passport" accept=".jpeg,.jpg,.png" onchange="previewImage(this);" required>
                                    </div>
                                    <p class="text-muted small mt-2">Allowed formats: JPG, JPEG, PNG. Max size: 500KB</p>
                                </div>
                                
                                <div id="previewContainer" style="display: none; margin-top: 1rem;">
                                    <label class="form-label fw-bold mb-2">New Passport Preview</label>
                                    <div>
                                        <img id="photoPreview" class="photo-preview" src="#" alt="Preview">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-box">
                                <i class="fas fa-info-circle"></i>
                                <strong>Guidelines:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Upload a clear, recent passport photograph</li>
                                    <li>Image must be in JPG, JPEG, or PNG format</li>
                                    <li>Maximum file size is 500KB</li>
                                    <li>Recommended dimensions: 300x300 pixels</li>
                                </ul>
                            </div>
                            
                            <div class="text-center mt-4">
                                <button type="submit" name="submitpd" class="btn-primary-modern">
                                    <i class="fas fa-upload me-2"></i> Update Passport
                                </button>
                            </div>
                        </form>
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
        
        function previewImage(input) {
            const previewContainer = document.getElementById('previewContainer');
            const preview = document.getElementById('photoPreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.style.display = 'none';
            }
        }
        
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