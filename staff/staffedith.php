<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';

if (strlen($_SESSION['staffid']) == "") {
    header("Location: ../logout.php");
} else {
    $now = time();
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location:../logout.php");
    }
    
    $error = '';
    $succ = '';
    
    if (isset($_POST['submitpd'])) {
        $tiles = mysqli_escape_string($con, $_POST["title"]);
        $firstname = mysqli_escape_string($con, $_POST["firstname"]);
        $surname = mysqli_escape_string($con, $_POST["surname"]);
        $othername = mysqli_escape_string($con, $_POST["othername"]);
        $email = mysqli_escape_string($con, strtolower($_POST["email"]));
        $phone = mysqli_escape_string($con, $_POST["phone"]);
        $dob = mysqli_escape_string($con, $_POST["dobs"]);
        $staffid = mysqli_escape_string($con, $_SESSION["staffid"]);
        $address = mysqli_escape_string($con, $_POST["address"]);
        $sex = mysqli_escape_string($con, $_POST["sex"]);
        $dep = mysqli_escape_string($con, $_POST["dep"]);
        $marital = mysqli_escape_string($con, $_POST["marital"]);
        $country = mysqli_escape_string($con, $_POST["country"]);
        
        if ($country == "Nigeria") {
            $state = mysqli_escape_string($con, $_POST["state"]);
            $lga = mysqli_escape_string($con, $_POST["lga"]);
            $staname = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM state WHERE state_ID='$state' OR name='$state' "));
            $sate = $staname['name'];
            
            $recup = mysqli_query($con, "UPDATE people SET title='$tiles', firstname='$firstname', lastname='$surname', othername='$othername', gender='$sex', maritalstatus='$marital', dob='$dob', phoneno='$phone', uniemail='$email', state='$sate', address='$address', country='$country', lga='$lga' WHERE staffid='" . $_SESSION['staff'] . "' ");
            
            $stadz = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM department WHERE deptc='$dep'"));
            $deps = $stadz['deptc'];
            $fid = $stadz['fid'];
            
            mysqli_query($con, "UPDATE staffaculty SET deptm='$deps', faculty='$fid' WHERE staffid='" . $_SESSION['staff'] . "' ");
            mysqli_query($con, "UPDATE staffprive SET department='$deps' WHERE staffid='" . $_SESSION['staff'] . "' ");
            
            if ($recup) {
                $showModal = 'TRUE';
                $succ = "Details Successfully Updated";
            } else {
                $error = "Error Occurred While Updating";
                $showModalEr = 'true';
            }
        } else {
            $state = mysqli_escape_string($con, $_POST['country']);
            $recup = mysqli_query($con, "UPDATE people SET title='$tiles', firstname='$firstname', lastname='$surname', othername='$othername', gender='$sex', maritalstatus='$marital', dob='$dob', phoneno='$phone', uniemail='$email', address='$address', state='$state', country='Foreign' WHERE staffid='" . $_SESSION['staff'] . "' ");
            
            if ($recup) {
                $showModal = 'TRUE';
                $succ = "Details Successfully Updated";
            } else {
                $error = "Error Occurred While Updating";
                $showModalEr = 'true';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlentities($orgname); ?></title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <link rel="stylesheet" href="css/animate.min.css" media="screen">
    
    <style>
        :root {
            --primary-color: #2b5f5a;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
        }
        
        .page-title-div {
            background: linear-gradient(135deg, #2b5f5a 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            color: white;
        }
        
        .page-title-div .title {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .panel {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow: hidden;
        }
        
        .panel-body {
            padding: 25px;
        }
        
        .form-section {
            background: #f8f9fc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .form-section-title {
            border-left: 4px solid var(--primary-color);
            padding-left: 15px;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .form-control-custom {
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success-custom {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            border: none;
            color: #333;
            font-weight: 600;
        }
        
        .staff-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .staff-info-card h4 {
            margin: 0 0 10px 0;
        }
        
        .staff-info-card p {
            margin: 0;
            opacity: 0.9;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .required-field::after {
            content: "*";
            color: red;
            margin-left: 5px;
        }
        
        @media (max-width: 768px) {
            .form-group label {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        
        <div class="content-wrapper">
            <div class="content-container">
                <?php
                $paych = mysqli_fetch_assoc(mysqli_query($con, "SELECT position FROM staffprive WHERE staffid='" . $_SESSION['staffid'] . "'"));
                
                if ($paych['position'] == "sys_analyst") {
                    include('includes/sysbar.php');
                } else if ($paych['position'] == "superadmin") {
                    include('includes/supbar.php');
                } elseif ($paych['position'] == "subadministrator") {
                    include('includes/ictbar.php');
                } else if ($paych['position'] == "subadmin") {
                    include('includes/subbar.php');
                } else {
                    include('includes/leftbar.php');
                }
                ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-12">
                                <h4 class="title">
                                    <i class="fa fa-edit"></i> Edit Staff Data
                                </h4>
                                <p class="subtitle">Update staff personal and academic information</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <?php
                                        if ($paych['position'] == "superadmin" OR $paych['position'] == "subadministrator" OR $paych['position'] == "subadmin" OR $paych['position'] == "sys_analyst") {
                                        ?>
                                            <!-- Staff Verification Section -->
                                            <div class="form-section">
                                                <div class="form-section-title">
                                                    <i class="fa fa-search"></i> Verify Staff ID
                                                </div>
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data" id="verifyForm">
                                                    <div class="form-group">
                                                        <label for="staffid" class="col-sm-2 control-label required-field">Staff ID:</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                                                <input type="text" class="form-control form-control-custom" name="tellerid" id="staffid" autocomplete="off" required="required" placeholder="Enter Staff ID (e.g., STAFF001)">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <button type="submit" name="submitcor" id="verifyBtn" class="btn btn-primary-custom">
                                                                <i class="fa fa-check-circle"></i> Verify
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            
                                            <?php 
                                            if (isset($_POST['submitcor'])) {
                                                $teld = trim(mysqli_real_escape_string($con, strtoupper($_POST['tellerid'])));
                                                $telcheck = mysqli_num_rows(mysqli_query($con, "SELECT * FROM people WHERE staffid='$teld' "));
                                                
                                                if ($telcheck > 0) {
                                                    $stad = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM people WHERE staffid='$teld'"));
                                            ?>
                                                <!-- Staff Information Display -->
                                                <div class="staff-info-card">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <h4><i class="fa fa-user"></i> <?php echo htmlentities($stad['firstname'] . ' ' . $stad['lastname']); ?></h4>
                                                            <p><i class="fa fa-id-card"></i> Staff ID: <?php echo htmlentities($stad['staffid']); ?></p>
                                                            <p><i class="fa fa-envelope"></i> Email: <?php echo htmlentities($stad['uniemail']); ?></p>
                                                        </div>
                                                        <div class="col-md-4 text-right">
                                                            <i class="fa fa-check-circle" style="font-size: 48px; opacity: 0.5;"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Edit Form -->
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data" id="editForm">
                                                    <?php $_SESSION['staff'] = $teld; ?>
                                                    
                                                    <div class="row">
                                                        <!-- Left Column -->
                                                        <div class="col-lg-6">
                                                            <div class="form-section">
                                                                <div class="form-section-title">
                                                                    <i class="fa fa-user-circle"></i> Personal Information
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label required-field">Title:</label>
                                                                    <div class="col-sm-9">
                                                                        <select name="title" class="form-control form-control-custom">
                                                                            <option value="<?php echo htmlentities($stad['title']); ?>" selected><?php echo htmlentities($stad['title']); ?></option>
                                                                            <?php
                                                                            $res = mysqli_query($con, "SELECT * FROM titles ORDER BY title");
                                                                            while ($row = mysqli_fetch_array($res)) {
                                                                                echo '<option value="' . $row["title"] . '">' . $row["title"] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label required-field">Firstname:</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-custom" value="<?php echo htmlentities($stad['firstname']); ?>" name="firstname" required maxlength="50">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label required-field">Surname:</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-custom" value="<?php echo htmlentities($stad['lastname']); ?>" name="surname" required maxlength="50">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Othername:</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-custom" value="<?php echo htmlentities($stad['othername']); ?>" name="othername" maxlength="50">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label required-field">Email:</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="email" class="form-control form-control-custom" value="<?php echo htmlentities($stad['uniemail']); ?>" name="email" required readonly>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label required-field">Phone:</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="tel" class="form-control form-control-custom" value="<?php echo htmlentities($stad['phoneno']); ?>" name="phone" required maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Date of Birth:</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="date" class="form-control form-control-custom" value="<?php echo htmlentities($stad['dob']); ?>" name="dobs">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Right Column -->
                                                        <div class="col-lg-6">
                                                            <div class="form-section">
                                                                <div class="form-section-title">
                                                                    <i class="fa fa-info-circle"></i> Additional Information
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label required-field">Gender:</label>
                                                                    <div class="col-sm-9">
                                                                        <select name="sex" class="form-control form-control-custom" required>
                                                                            <option value="<?php echo htmlentities($stad['gender']); ?>" selected><?php echo htmlentities($stad['gender']); ?></option>
                                                                            <?php
                                                                            $res = mysqli_query($con, "SELECT * FROM genders ORDER BY gender");
                                                                            while ($row = mysqli_fetch_array($res)) {
                                                                                echo '<option value="' . $row["gender"] . '">' . $row["gender"] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label required-field">Marital Status:</label>
                                                                    <div class="col-sm-9">
                                                                        <select name="marital" class="form-control form-control-custom" required>
                                                                            <option value="<?php echo htmlentities($stad['maritalstatus']); ?>" selected><?php echo htmlentities($stad['maritalstatus']); ?></option>
                                                                            <?php
                                                                            $resz = mysqli_query($con, "SELECT * FROM maritals ORDER BY marital");
                                                                            while ($row = mysqli_fetch_array($resz)) {
                                                                                echo '<option value="' . $row["marital"] . '">' . $row["marital"] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <?php if ($stad['country'] == "Nigeria") { ?>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label">Nationality:</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control form-control-custom" name="country" value="<?php echo htmlentities($stad['country']); ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label">State:</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="state" class="form-control form-control-custom" id="state" onchange="loadLGA()">
                                                                                <option value="<?php echo htmlentities($stad['state']); ?>" selected><?php echo htmlentities($stad['state']); ?></option>
                                                                                <?php
                                                                                $resz = mysqli_query($con, "SELECT * FROM state ORDER BY name");
                                                                                while ($row = mysqli_fetch_array($resz)) {
                                                                                    echo '<option value="' . $row["state_ID"] . '">' . $row["name"] . '</option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label">LGA:</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="lga" class="form-control form-control-custom" id="lga">
                                                                                <option value="<?php echo htmlentities($stad['lga']); ?>" selected><?php echo htmlentities($stad['lga']); ?></option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label">Country:</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="country" class="form-control form-control-custom">
                                                                                <option value="<?php echo htmlentities($stad['country']); ?>" selected><?php echo htmlentities($stad['country']); ?></option>
                                                                                <?php
                                                                                $resz = mysqli_query($con, "SELECT * FROM countries ORDER BY country ASC");
                                                                                while ($row = mysqli_fetch_array($resz)) {
                                                                                    echo '<option value="' . $row["country"] . '">' . $row["country"] . '</option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Home Address:</label>
                                                                    <div class="col-sm-9">
                                                                        <textarea name="address" class="form-control form-control-custom" rows="3" required><?php echo htmlentities($stad['address']); ?></textarea>
                                                                    </div>
                                                                </div>
                                                                
                                                                <?php
                                                                $stada = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM staffrank WHERE staffid='$teld' AND officename='Academic' "));
                                                                if ($stada > 1) {
                                                                    $stads = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM staffaculty WHERE staffid='$teld'"));
                                                                    $dep = $stads['deptm'];
                                                                    $stadz = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM department WHERE deptc='$dep'"));
                                                                ?>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label">Department:</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="dep" class="form-control form-control-custom" id="dep">
                                                                                <option value="<?php echo htmlentities($stadz['deptc']); ?>" selected><?php echo htmlentities($stadz['dept']); ?></option>
                                                                                <?php
                                                                                $res = mysqli_query($con, "SELECT * FROM department ORDER BY dept");
                                                                                while ($row = mysqli_fetch_array($res)) {
                                                                                    echo '<option value="' . $row["deptc"] . '">' . $row["dept"] . '</option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group text-center">
                                                                <button type="submit" name="submitpd" class="btn btn-primary-custom btn-lg">
                                                                    <i class="fa fa-save"></i> Update Staff Data
                                                                </button>
                                                                <button type="reset" class="btn btn-default btn-lg">
                                                                    <i class="fa fa-refresh"></i> Reset
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php
                                            } else {
                                                $showModalEr = 'true';
                                                $error = 'Staff ID ' . $teld . ' does not exist. Please check and try again.';
                                            }
                                        }
                                        ?>
                                        <?php } else { ?>
                                            <div class="alert alert-danger text-center">
                                                <i class="fa fa-exclamation-triangle fa-2x"></i>
                                                <h4>Access Denied</h4>
                                                <p>Sorry Dear: <strong><?php echo htmlentities($fullname); ?></strong> You don't have access to this page.</p>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade" id="checkModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fa fa-check-circle"></i> Success!</h5>
                    <button class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-success"><?php echo htmlentities($succ); ?></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="checkModalEr" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa fa-exclamation-circle"></i> Error!</h5>
                    <button class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-danger"><?php echo htmlentities($error); ?></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
    
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    
    <script type="text/javascript">
        function loadLGA() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", "fetch.php?facult=" + document.getElementById("state").value, false);
            xmlhttp.send(null);
            document.getElementById("lga").innerHTML = xmlhttp.responseText;
        }
        
        $(document).ready(function() {
            // Form submission loading effect
            $('#verifyForm').on('submit', function() {
                $('#loadingOverlay').fadeIn();
            });
            
            $('#editForm').on('submit', function() {
                $('#loadingOverlay').fadeIn();
            });
            
            // Auto-hide success modal after 3 seconds
            $('#checkModal').on('shown.bs.modal', function() {
                setTimeout(function() {
                    $('#checkModal').modal('hide');
                    location.reload();
                }, 3000);
            });
            
            // Phone number validation
            $('input[name="phone"]').on('input', function() {
                var phone = $(this).val();
                if (phone.length > 11) {
                    $(this).val(phone.slice(0, 11));
                }
            });
            
            // Form validation before submit
            $('#editForm').on('submit', function(e) {
                var requiredFields = $(this).find('[required]');
                var isValid = true;
                
                requiredFields.each(function() {
                    if ($(this).val().trim() === '') {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields marked with *');
                }
            });
        });
    </script>
    
    <?php
    include 'includes/buton.php';
    
    if (!empty($showModal)) {
        echo '<script>$(document).ready(function(){$("#checkModal").modal("show");});</script>';
    }
    if (!empty($showModalEr)) {
        echo '<script>$(document).ready(function(){$("#checkModalEr").modal("show");});</script>';
    }
    ?>
</body>
</html>
<?php } ?>