<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

// Check if user is logged in
if (strlen($_SESSION['staffid']) == "") {   
    header("Location: logout"); 
    exit();
}
else{
$staffid = $_SESSION['staffid'];
include 'dataco.php';
$error = '';
$showModalEr = false;
$showModal = false;
$succ = '';

// Handle document removal - FIXED
if (isset($_POST['removedd'])) {
    $id = mysqli_real_escape_string($con, $_POST['ids']); // Get from POST instead of session
    $delete_query = mysqli_query($con, "DELETE FROM cleardoc WHERE id='$id' AND applid='$staffid'");
    if ($delete_query) {
        $succ = "Document Successfully Removed";
        $showModal = true;
        // Refresh the page to show updated list
        echo '<script>setTimeout(function(){ window.location.href = "ndupload"; }, 2000);</script>';
    } else {
        $error = "Error removing document. Please try again.";
        $showModalEr = true;
    }
}

// Handle completion button
if (isset($_POST['jchew'])) {
    $qu = mysqli_query($con, "UPDATE applicant SET cert='1' WHERE applid='$staffid'");
    if ($qu) {
        header("Location: appsummary");
        exit();
    } else {
        $error = "Error While Updating";
        $showModalEr = true;
    }
}

// Handle document upload
if (isset($_POST['savees'])) {
    if (empty($_POST['proty'])) {
        $error = "Select Document Type";
        $showModalEr = true;
    } else {
        $prodt = mysqli_real_escape_string($con, $_POST["proty"]);
        $id = $prodt;
        $filenamg = basename($_FILES['passport']['name']);
        $filetmp = $_FILES['passport']['tmp_name'];
        $filesize = $_FILES['passport']['size'];
        // UPDATED: Added PDF to allowed extensions
        $extension = array("png", "jpg", "jpeg", "PNG", "JPG", "JPEG", "pdf", "PDF");
        $file_extension = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
        
        if (!file_exists($_FILES["passport"]["tmp_name"])) {
            $error = "Choose file to upload.";
            $showModalEr = true;
        } elseif (!in_array($file_extension, $extension)) {
            $error = "Upload valid files. Only PNG, JPG, JPEG, and PDF are allowed.";
            $showModalEr = true;
        } elseif ($_FILES["passport"]["size"] > 4000000) { // UPDATED: Changed to 4MB (4000000 bytes)
            $error = "File size exceeds 4MB.";
            $showModalEr = true;
        } else {
            $nwtourimg = explode(".", $filenamg);
            $fileExt = end($nwtourimg);
            $res = trim(str_ireplace(array('\'', '"', ',', ';', '<', '>', '/', ''), '', $staffid));
            $fileBase = $res . "_" . $id . "." . $fileExt;
            $dir = "photo/";
            $final_dir = $dir . $fileBase;
            
            // Document type names
            $doc_names = [
                '1' => 'JAMB Slip',
                '2' => 'Birth/Age Declaration',
                '3' => 'Olevel Result First Sitting',
                '4' => 'Olevel Result Second Sitting'
            ];
            $igd = $doc_names[$id] ?? 'Document';
            
            // Check if document already exists
            $check_sql = mysqli_query($con, "SELECT * FROM cleardoc WHERE applid='$staffid' AND documtype='$id'");
            if (mysqli_num_rows($check_sql) == 1) {
                $error = "Document '$igd' Already Exists";
                $showModalEr = true;
            } else {
                if (move_uploaded_file($filetmp, $final_dir)) {
                    $insert_sql = mysqli_query($con, "INSERT INTO cleardoc (applid, document, documtype, dis, prog) VALUES ('$staffid', '$final_dir', '$id', '1', 'ND')");
                    if ($insert_sql) {
                        // Refresh page to show new document
                        echo '<script>window.location.href = "ndupload";</script>';
                        exit();
                    } else {
                        $error = "Error uploading document. Please try again.";
                        $showModalEr = true;
                    }
                } else {
                    $error = "Failed to upload file. Please check directory permissions.";
                    $showModalEr = true;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title><?php echo htmlentities($orgname); ?> - ND Documents</title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            --gray-bg: #f8fafc;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
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
            transition: 0.2s;
            border-radius: 40px;
        }
        
        .navbar-modern .nav-link:hover {
            background: rgba(43,95,90,0.2);
            transform: translateY(-2px);
            color: var(--primary) !important;
        }
        
        /* Main Wrapper */
        .main-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .content-container {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }
        
        .sidebar-wrapper {
            flex: 1;
            min-width: 260px;
        }
        
        .main-content {
            flex: 3;
            min-width: 300px;
        }
        
        /* Page Title */
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
        
        .page-title h2 i {
            color: var(--primary);
            margin-right: 10px;
        }
        
        /* Cards */
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
        
        .card-header-modern h4 i {
            margin-right: 8px;
            color: var(--primary);
        }
        
        .card-body-modern {
            padding: 1.5rem;
        }
        
        /* Document Table */
        .doc-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .doc-table th {
            background: var(--primary-soft);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 0.85rem;
        }
        
        .doc-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        
        .doc-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .doc-image-large {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* PDF Icon Styling */
        .pdf-icon {
            font-size: 48px;
            color: #dc2626;
        }
        
        .pdf-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }
        
        /* Buttons */
        .btn-primary-modern {
            background: linear-gradient(95deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 8px 20px;
            font-weight: 600;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-primary-modern:hover {
            background: linear-gradient(95deg, var(--primary-dark), #143a36);
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-outline-modern {
            background: transparent;
            border: 1.5px solid var(--primary);
            padding: 8px 20px;
            font-weight: 600;
            border-radius: 40px;
            color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .btn-outline-modern:hover {
            background: var(--primary-soft);
            transform: translateY(-2px);
            color: var(--primary-dark);
        }
        
        .btn-danger-modern {
            background: #dc2626;
            border: none;
            padding: 6px 12px;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-danger-modern:hover {
            background: #b91c1c;
            transform: translateY(-2px);
        }
        
        .btn-info-modern {
            background: #3b82f6;
            border: none;
            padding: 6px 12px;
            border-radius: 40px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-info-modern:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
        
        /* Image Preview */
        .image-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 2px solid var(--primary);
            border-radius: 12px;
            margin: 10px auto;
            display: block;
        }
        
        /* Modal Styles */
        .modal-content-custom {
            border-radius: 24px;
            border: none;
            overflow: hidden;
        }
        
        .modal-header-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 15px 20px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar-wrapper {
                flex: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .main-wrapper {
                padding: 10px;
            }
            .card-body-modern {
                padding: 1rem;
                overflow-x: auto;
            }
            .doc-table {
                min-width: 500px;
            }
            .doc-image-large {
                width: 120px;
                height: 90px;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card-modern {
            animation: fadeInUp 0.5s ease-out;
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
        
        /* Document Grid for View Only */
        .doc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .doc-card {
            background: var(--gray-bg);
            border-radius: 16px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .doc-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .doc-title {
            font-weight: 600;
            color: var(--primary-dark);
            margin: 10px 0;
            font-size: 0.9rem;
        }
        
        footer {
            background: #1a2421;
            color: #cfdfe9;
            padding: 1rem 0;
            margin-top: 2rem;
            border-radius: 20px;
            text-align: center;
        }
        
        footer a {
            color: #cfdfe9;
            text-decoration: none;
        }
        
        footer a:hover {
            color: var(--primary) !important;
        }
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
                    <h2><i class="fas fa-file-alt"></i> National Diploma Support Documents</h2>
                </div>
                
                <?php
                $stanam_submitted = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$staffid' AND status='submitted'"));
                $stanam_pending = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$staffid' AND (status='Pending' OR status='Approved')"));
                ?>
                
                <!-- Submitted Status - Can Upload/Edit Documents -->
                <?php if ($stanam_submitted > 0) { ?>
                <div class="card-modern" data-aos="fade-up">
                    <div class="card-header-modern">
                        <h4><i class="fas fa-upload"></i> Upload Required Documents</h4>
                    </div>
                    <div class="card-body-modern">
                        <div class="table-responsive">
                            <table class="doc-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document Type</th>
                                        <th>File</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM cleardoc WHERE applid='$staffid' ORDER BY documtype ASC";
                                    $query = mysqli_query($con, $sql);
                                    $cnt = 1;
                                    $doc_types = [
                                        '1' => 'JAMB Slip',
                                        '2' => 'Birth/Age Declaration',
                                        '3' => 'Olevel Result First Sitting',
                                        '4' => 'Olevel Result Second Sitting'
                                    ];
                                    
                                    while ($result = mysqli_fetch_assoc($query)) {
                                        if ($result['dis'] == '1') {
                                            $file_ext = pathinfo($result['document'], PATHINFO_EXTENSION);
                                            $is_pdf = in_array(strtolower($file_ext), ['pdf']);
                                    ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><strong><?php echo htmlentities($doc_types[$result['documtype']] ?? 'Document'); ?></strong></td>
                                        <td>
                                            <?php if ($is_pdf): ?>
                                                <div class="pdf-container">
                                                    <i class="fas fa-file-pdf pdf-icon"></i>
                                                    <small class="text-muted">PDF Document</small>
                                                </div>
                                            <?php else: ?>
                                                <img src="<?php echo htmlentities($result['document']); ?>" class="doc-image" alt="Document">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn-info-modern view-doc" data-doc="<?php echo htmlentities($result['document']); ?>" data-type="<?php echo htmlentities($doc_types[$result['documtype']] ?? 'Document'); ?>" data-ext="<?php echo $file_ext; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-danger-modern delete-doc" data-id="<?php echo $result['id']; ?>" data-type="<?php echo htmlentities($doc_types[$result['documtype']] ?? 'Document'); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } } 
                                    if ($cnt == 1) { ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 30px;">
                                            <i class="fas fa-folder-open" style="font-size: 2rem; color: #cbd5e1;"></i>
                                            <p class="mt-2 text-muted">No documents uploaded yet. Click "Add New Document" to upload.</p>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <button type="button" class="btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                                <i class="fas fa-plus me-2"></i> Add New Document
                            </button>
                            
                            <?php
                            $doc_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM cleardoc WHERE applid='$staffid' AND dis='1'"));
                            if ($doc_count >= 2) { ?>
                            <form method="post" class="d-inline-block ms-2">
                                <button type="submit" name="jchew" class="btn-outline-modern">
                                    <i class="fas fa-check-circle me-2"></i> Complete & Continue
                                </button>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
                <!-- Pending/Approved Status - View Only Documents -->
                <?php if ($stanam_pending > 0) { ?>
                <div class="card-modern" data-aos="fade-up">
                    <div class="card-header-modern">
                        <h4><i class="fas fa-folder-open"></i> Uploaded Documents</h4>
                    </div>
                    <div class="card-body-modern">
                        <div class="doc-grid">
                            <?php
                            $sql = "SELECT * FROM cleardoc WHERE applid='$staffid' ORDER BY documtype ASC";
                            $query = mysqli_query($con, $sql);
                            $has_docs = false;
                            
                            while ($result = mysqli_fetch_assoc($query)) {
                                $has_docs = true;
                                $doc_names = [
                                    '1' => 'JAMB Slip',
                                    '2' => 'Birth/Age Declaration',
                                    '3' => 'Olevel Result First Sitting',
                                    '4' => 'Olevel Result Second Sitting'
                                ];
                                $file_ext = pathinfo($result['document'], PATHINFO_EXTENSION);
                                $is_pdf = in_array(strtolower($file_ext), ['pdf']);
                            ?>
                            <div class="doc-card">
                                <?php if ($is_pdf): ?>
                                    <div class="pdf-container">
                                        <i class="fas fa-file-pdf pdf-icon"></i>
                                    </div>
                                <?php else: ?>
                                    <img src="<?php echo htmlentities($result['document']); ?>" class="doc-image-large" alt="Document">
                                <?php endif; ?>
                                <div class="doc-title"><?php echo htmlentities($doc_names[$result['documtype']] ?? 'Document'); ?></div>
                                <button class="btn-info-modern view-doc-sm mt-2" data-doc="<?php echo htmlentities($result['document']); ?>" data-type="<?php echo htmlentities($doc_names[$result['documtype']] ?? 'Document'); ?>" data-ext="<?php echo $file_ext; ?>">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>
                            </div>
                            <?php } 
                            if (!$has_docs) { ?>
                            <div class="text-center" style="grid-column: 1/-1; padding: 40px;">
                                <i class="fas fa-folder-open" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p class="mt-3 text-muted">No documents uploaded yet.</p>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <?php if ($has_docs) { ?>
                        <div class="text-center mt-4">
                            <button class="btn-primary-modern" disabled>
                                <i class="fas fa-check-circle me-2"></i> Documents Submitted
                            </button>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        
        <footer>
            <p class="mb-0 small">&copy; <span id="currentYear"></span> - <a href="#"><?php echo htmlentities($orgname . ', ' . $schadress); ?></a></p>
        </footer>
    </div>
    
    <!-- Add Document Modal -->
    <div class="modal fade" id="addDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #1a2a2a, #1e3a35); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Add New Document</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold mb-2"><i class="fas fa-tag me-1" style="color: var(--primary);"></i> Document Type</label>
                            <select name="proty" class="form-control-custom" required style="width:100%; padding: 10px; border-radius: 12px; border: 2px solid #e2e8f0;">
                                <option selected disabled>Please Select Document Type</option>
                                <option value="1">JAMB Slip</option>
                                <option value="2">Birth/Age Declaration</option>
                                <option value="3">Olevel Result First Sitting</option>
                                <option value="4">Olevel Result Second Sitting (if applicable)</option>
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold mb-2"><i class="fas fa-image me-1" style="color: var(--primary);"></i> Document File (JPG, JPEG, PNG, PDF only)</label>
                            <input type="file" name="passport" accept=".jpg,.jpeg,.png,.pdf" class="form-control-custom" onchange="previewFile(this);" required style="padding: 8px;">
                        </div>
                        
                        <div class="text-center" id="previewContainer">
                            <img id="imagePreview" class="image-preview" src="img/document.png" alt="Document Preview" style="display: none;">
                            <div id="pdfPreview" style="display: none;">
                                <i class="fas fa-file-pdf" style="font-size: 80px; color: #dc2626;"></i>
                                <p class="mt-2 text-muted">PDF Document Selected</p>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 small" style="background: var(--primary-soft); border-radius: 12px;">
                            <i class="fas fa-info-circle me-1"></i> Maximum file size: 4MB. Allowed formats: JPG, JPEG, PNG, PDF
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center gap-3" style="border: none; padding-bottom: 1.5rem;">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" style="border-radius: 40px; padding: 8px 25px;">Cancel</button>
                        <button type="submit" name="savees" class="btn btn-success" style="border-radius: 40px; padding: 8px 30px; background: linear-gradient(95deg, var(--primary), var(--primary-dark)); border: none;">Upload Document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- View Document Modal -->
    <div class="modal fade" id="viewDocModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Document Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <h6 id="docType" class="mb-3" style="color: var(--primary-dark);"></h6>
                    <div id="docPreviewContainer"></div>
                    <div class="mt-3">
                        <a href="#" id="downloadLink" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-download me-2"></i> Download / Open Full Document
                        </a>
                    </div>
                </div>
                <div class="modal-footer justify-content-center" style="border: none; padding-bottom: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 40px; padding: 8px 30px;">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Document Modal - FIXED -->
    <div class="modal fade" id="deleteDocModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-trash-alt me-2"></i>Delete Document</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body text-center p-4">
                        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #f59e0b; margin-bottom: 1rem;"></i>
                        <p>Are you sure you want to delete <strong id="deleteDocName"></strong>?</p>
                        <p class="text-muted small">This action cannot be undone.</p>
                        <input type="hidden" name="ids" id="deleteDocId">
                    </div>
                    <div class="modal-footer justify-content-center gap-3" style="border: none; padding-bottom: 1.5rem;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 40px; padding: 8px 25px;">Cancel</button>
                        <button type="submit" name="removedd" class="btn btn-danger" style="border-radius: 40px; padding: 8px 30px;">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Error Message</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
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
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px;">
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Success</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #10b981; margin-bottom: 0.5rem;"></i>
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
        
        function previewFile(input) {
            var file = input.files[0];
            var fileExt = file.name.split('.').pop().toLowerCase();
            
            if (fileExt === 'pdf') {
                document.getElementById('imagePreview').style.display = 'none';
                document.getElementById('pdfPreview').style.display = 'block';
            } else if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                document.getElementById('pdfPreview').style.display = 'none';
                var reader = new FileReader();
                reader.onload = function(e) {
                    var imgPreview = document.getElementById('imagePreview');
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagePreview').style.display = 'none';
                document.getElementById('pdfPreview').style.display = 'none';
            }
        }
        
        // View document
        $('.view-doc, .view-doc-sm').click(function() {
            var docSrc = $(this).data('doc');
            var docType = $(this).data('type');
            var fileExt = $(this).data('ext');
            
            $('#docType').text(docType);
            $('#downloadLink').attr('href', docSrc);
            
            var previewContainer = $('#docPreviewContainer');
            previewContainer.empty();
            
            if (fileExt === 'pdf') {
                previewContainer.html('<iframe src="' + docSrc + '" style="width:100%; height:500px;" frameborder="0"></iframe>');
            } else {
                previewContainer.html('<img src="' + docSrc + '" style="max-width:100%; max-height:400px; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.15);">');
            }
            
            $('#viewDocModal').modal('show');
        });
        
        // Delete document - FIXED: using click event properly
        $(document).on('click', '.delete-doc', function() {
            var docId = $(this).data('id');
            var docType = $(this).data('type');
            $('#deleteDocId').val(docId);
            $('#deleteDocName').text(docType);
            $('#deleteDocModal').modal('show');
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