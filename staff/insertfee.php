<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';

if (strlen($_SESSION['staffid']) == "") {
        header("Location: ../logout");
    } else {
    $now = time(); // Checking the time now when home page starts.
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location:../logout");
    }
    $error = '';
    $succ = '';
    /*$showModal = "true";
$showModalEr='true';
*Remember to Implement Welcome email to new User
*/
    if (isset($_POST["submitrecord"])) {

    if (empty($_POST["stud"])) {
        $error = "Enter Student ID";
        $showModalEr = 'true';
    } else {

        // ✅ Clean input
        $stud     = strtoupper(trim($_POST["stud"]));
        $amount   = trim($_POST["amount"]);
        $ref      = trim($_POST["ref"]);
        $pro      = strtoupper($_POST["programme"]);
        $purpose  = $_POST["purpose"];
        $session  = $_POST["session"];
        $level    = $_POST["level"];
        $semester = $_POST["semester"];
        $date     = $_POST["date"];
        $progam   = $_POST["progam"];
        $mode     = $_POST["mode"];
        $type     = $_POST["type"];

        // ✅ FILE VALIDATION
        if (!isset($_FILES["passport"]) || $_FILES["passport"]["error"] !== 0) {
            $error = "Choose image file";
            $showModalEr = 'true';
        } else {

            $allowed = ['jpg','jpeg'];
            $file_extension = strtolower(pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION));

            if (!in_array($file_extension, $allowed)) {
                $error = "Only JPG/JPEG allowed";
                $showModalEr = 'true';
            } elseif ($_FILES["passport"]["size"] > 500000) {
                $error = "Image too large";
                $showModalEr = 'true';
            } else {

                // ✅ Safe filename
                $fileBase = bin2hex(random_bytes(5)) . "." . $file_extension;
                $final_dir = "evidence/" . $fileBase;

                if (!move_uploaded_file($_FILES["passport"]["tmp_name"], $final_dir)) {
                    $error = "Upload failed";
                    $showModalEr = 'true';
                } else {

                    // ✅ CHECK DUPLICATE (SAFE)
                    $stmt = $con->prepare("SELECT id FROM studentpayment WHERE studentid=? AND paymentreference=? AND purpose=?");
                    $stmt->bind_param("sss", $stud, $ref, $purpose);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $error = "Student ID $stud and Receipt No $ref already exist";
                        $showModalEr = 'true';
                    } else {

                        // ✅ SIMPLIFIED LOGIC
                        $tuition = ['ND Tuition Fee', 'CERT. Tuition Fee', 'JCHEW Tuition Fee'];

                        $insertSem1 = false;
                        $insertSem2 = false;
                        $useSemester = $semester;

                        if ($purpose === 'Acceptance Fee') {
                            $level = '100';
                            $useSemester = '';
                        } elseif (in_array($purpose, $tuition)) {

                            if ($type === 'Part') {
                                if ($semester === 'First Semester') $insertSem1 = true;
                                if ($semester === 'Second Semester') $insertSem2 = true;
                            }

                            if ($type === 'Full') {
                                if ($level === '100') {
                                    $useSemester = '';
                                    $insertSem1 = true;
                                    $insertSem2 = true;
                                } else {
                                    $insertSem1 = true;
                                    $insertSem2 = true;
                                }
                            }
                        }

                        // ✅ INSERT PAYMENT (ONCE)
                        $stmt = $con->prepare("INSERT INTO studentpayment 
                        (studentid, section, level, semester, programme, purpose, amount, modes, paymentreference, paytime, status, apptype, proof)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?, ?)");

                        $stmt->bind_param(
                            "ssssssssssss",
                            $stud, $session, $level, $useSemester,
                            $pro, $purpose, $amount, $mode,
                            $ref, $date, $progam, $fileBase
                        );

                        $payment = $stmt->execute();

                        // ✅ INSERT SEMESTERS
                        if ($payment) {

                            if ($insertSem1) {
                                mysqli_query($con, "INSERT INTO studentregsem(studentid,session,semester,programme,status,level)
                                VALUES('$stud','$session','First Semester','$pro','','$level')");
                            }

                            if ($insertSem2) {
                                mysqli_query($con, "INSERT INTO studentregsem(studentid,session,semester,programme,status,level)
                                VALUES('$stud','$session','Second Semester','$pro','','$level')");
                            }

                            $succ = "Successfully submitted";
                            $showModal = 'true';

                        } else {
                            $error = "Error occurred while submitting";
                            $showModalEr = 'true';
                        }
                    }
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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname); ?></title>
        <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/main.css" media="screen">
        <link rel="stylesheet" href="css/animate.min.css" media="screen">
        <style type="text/css">
            .field-icon {
                float: right;
                margin-left: -35px;
                margin-top: -35px;
                position: relative;
                z-index: 2;
            }
        </style>
    </head>

    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php'); ?>
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                  }elseif ($paych['position']=="subadmin") {
                        include('includes/subbar.php');
                  }
                  elseif ($paych['position']=="accountant") {
                        include('includes/accbar.php');
                  }
                  elseif ($paych['position']=="admission") {
                        include('includes/admsec.php');
                  }elseif ($paych['position']=="bursar") {
                        include('includes/bursar.php');
                  }elseif ($paych['position']=="registrar") {
                        include('includes/admoff.php');
                  }else if ($paych['position']=="sys_analyst")
                  {
                    
                    include('includes/sysbar.php');

                    }elseif ($paych['position']=="admin_officer")
                    {
                        include('includes/admof.php');
                    }

                   else{
                  include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Insert Fees</h2>

                                </div>

                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->

                            <!-- /.row -->
                        </div>
                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">

                                        <div class="panel-body">
                                            <?php 
                                             $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                                          if ($paych['position']=="superadmin" OR $paych['position']=="accountant" OR $paych['position']=="bursar")
                                          { ?>
                                            <section class="section">
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Student ID:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="stud" class="form-control clid" required="required">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Amount:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="amount" class="form-control clid" required="required">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Course of Study:
                                                                </label>
                                                                
                                                                    <select class="form-control clid" name="programme" id="programme" required="required">
                                                                         <option selected="selected" disabled="disabled">Select Course of Study</option>
                                                                         <?php
                                                            $resz = mysqli_query($con, "SELECT * FROM program ORDER BY pname");
                                                            while ($row = mysqli_fetch_array($resz)) {

                                                                ?>
                                                                <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                                                            <?php

                                                            }
                                                          ?>  
                                                                    </select>
                                                                
                                                            </div>
                                                             <div class="form-group">
                                                        <label for="default">
                                                           Purpose of Payment:
                                                        </label>
                                                        <select class="form-control" name="purpose" id="purpose" required>
                                                        <option selected disabled>Select Payment Purpose</option>
                                                          <?php
                                                              
                                                            
                                                            $resz = mysqli_query($con, "SELECT * FROM feespope WHERE fees='1' ORDER BY feename");
                                                            while ($row = mysqli_fetch_array($resz)) {

                                                                ?>
                                                                <option value="<?php echo  $row["feename"]; ?>"><?php echo  $row["feename"]; ?></option>
                                                            <?php

                                                            }
                                                          ?>   
                                                        </select> 
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Payment Model:
                                                        </label>
                                                        <select class="form-control" name="type" id="type">
                                                        <option selected disabled>Please select Type</option>
                                                                <option value="Part">Partial Payment</option>
                                                                <option value="Full">Full Payment</option>
                                                              
                                                        </select> 
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Mode of Payment:
                                                        </label>
                                                        <select class="form-control" name="mode" id="mode">
                                                        <option selected disabled>Please select Mode</option>
                                                                <option value="Bank_Transfer">Bank Transfer/Deposit</option>
                                                                <option value="Other">Other Means</option>
                                                              
                                                        </select> 
                                                      </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="default" >
                                                                    Reciept No:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="ref" class="form-control clid" id="ref" required="required" placeholder="1001902">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                        <label for="default">
                                                           Programme:
                                                        </label>
                                                        <select class="form-control" name="progam" id="progam" required="required">
                                                        <option selected disabled>Select Programme</option>
                                                                <option value="ND">ND</option>
                                                                <option value="CERT">CERT</option>
                                                                <option value="JCHEW">JCHEW</option>

                                                        </select> 
                                                      </div>
                                                            <div class="form-group">
                                                        <label for="default">
                                                           Payment Level:
                                                        </label>
                                                        <select class="form-control" name="level" id="level" required="required">
                                                        <option selected disabled>Please Select Payment Level</option>
                                                                <option value="100">100 Level</option>
                                                                <option value="200">200 Level</option>
                                                                <option value="300">300 Level</option>
                                                              
                                                        </select> 
                                                      </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Session:
                                                                </label>
                                                                
                                                                    <select class="form-control clid" name="session" id="session" required="required">
                                                                <option selected disabled>Select Payment Session</option>
                                                                <?php
                                                                $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                                                while ($row = mysqli_fetch_array($res)) {

                                                                    ?>
                                                                    <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                                                              <?php

                                                                }
                                                              ?>
                                                            </select>
                                                            </div>
                                                            <div class="form-group">
                                                        <label for="default">
                                                           Payment Type:
                                                        </label>
                                                        <select class="form-control" name="semester" id="semester" required="required">
                                                        <option selected disabled>Please Select Semester</option>
                                                                <option value="First Semester">First Semester</option>
                                                                <option value="Second Semester">Second Semester</option>
                                                                <option value="Full Payment">Full Payment</option>
                                                              
                                                        </select> 
                                                      </div>
                                                             <div class="form-group">
                                                        <label for="default">
                                                           Paid Date:
                                                        </label>
                                                       
                                                            <input type="date" name="date" class="form-control"  required>
                                                        
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Upload Teller/Evidence of Payment:
                                                        </label>
                                                       
<input type="file" class="form-control" name="passport" required="required" accept=".jpeg,.jpg">
                                                                                                
                                                      </div>
                                                            
                                                            <div class="form-group">
                                                                <div class="">
                                                                    <button type="submit" name="submitrecord" id="submit" class="btn btn-primary form-control">Submit Data</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                            </section>
                                        <?php
                                            }else{
                                            
                                            ?>
                                            <section class="section">
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Firstname:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="firstname" class="form-control clid" required="required">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Lastname:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="lastname" class="form-control clid" required="required">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Position:
                                                                </label>
                                                                
                                                                    <select class="form-control clid" name="position">
                                                                         <option selected="selected" disabled="disabled">Please Select Privilege</option>
                                                                        <?php
                                                                            $res = mysqli_query($con, "SELECT * FROM privilege WHERE privileg!='superadmin'");
                                                                        while ($row = mysqli_fetch_array($res)) {

                                                                            ?>
                                                                            <option value="<?php echo  $row["privileg"];?>"><?php echo  $row["privileg"]; ?></option>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="default" >
                                                                    Password:
                                                                </label>
                                                                
                                                                    <input type="password" maxlength="38" name="password" class="form-control clid" id="password-field" required="required" pattern="(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{6,}" style="text-transform: capitalize;" placeholder="**********" maxlength="27" title="Your Password should contain atleat one uppercase and lowercase"><i toggle="#password-field" class="fa fa-fw field-icon fa-eye toggle-password"></i>
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Confirm Password:
                                                                </label>
                                                                
                                                                    <input type="password" name="passwordc" class="form-control clid" id="password-fieldc" required="required" pattern="(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).{6,}" style="text-transform: capitalize;" placeholder="**********" maxlength="38" title="Your Password should contain atleat one uppercase and lowercase">
                                                                    <i toggles="#password-fieldc" class="fa fa-fw field-icon fa-eye toggle-passwordc"></i>
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Staff Type:
                                                                </label>
                                                                
                                                                    <select class="form-control clid" name="staftype">
                                                                        <option selected="selected" disabled="disabled">Please Select Staff Type</option>
                                                                        <?php
                                                                        $res = mysqli_query($con, "SELECT * FROM stafftype ORDER BY stname");
                                                                        while ($row = mysqli_fetch_array($res)) {

                                                                            ?>
                                                                            <option value="<?php echo  $row["shrtcode"]; ?>"><?php echo  $row["stname"]; ?></option>
                                                                        <?php

                                                                        }
                                                                        ?>
                                                                    </select>
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="">
                                                                    <button type="submit" name="submitrecord" id="submit" class="btn btn-primary form-control">Submit Data</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                            </section>
                                            <?php
                                             }
                                            ?>

                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col-md-12 -->
                            </div>
                        </div>
                    </div>
                    <!-- /.content-container -->
                </div>
                <!-- /.content-wrapper -->
            </div>
            <!-- /.main-wrapper -->
            <div class="modal" id="viewModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Update Staff Status</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_details">

                        </div>

                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
            <div class="modal" id="viewModals">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Staff Bio Data</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailss">

                        </div>

                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
            <div class="modal" id="viewModalss">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Staff Bio Data</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailsss">

                        </div>

                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
            <div class="modal" id="viewModalst">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Update Staff Privilege</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailst">

                        </div>

                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($succ) ?></p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           
            <!-- ========== COMMON JS FILES ========== -->
            <?php
            include 'includes/buton.php';
            if (!empty($showModal)) {
                // CALL MODAL HERE
                echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
            }
            if (!empty($showModalEr)) {
                // CALL MODAL HERE
                echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
            }
            if (!empty($showModall)) {
                // CALL MODAL HERE
                echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModall").modal("show");
            });
        </script>';
            }
            ?>
            <script type="text/javascript">
                $(".toggle-password").click(function() {
                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var input = $($(this).attr("toggle"));
                    //calling on the attribute of the password
                    if (input.attr("type") == "password") {
                        //Changing to Text
                        input.attr("type", "text");
                    } else {
                        input.attr("type", "password");
                    }
                });

                $(".toggle-passwordc").click(function() {
                    $(this).toggleClass("fa-eye fa-eye-slash");
                    var input = $($(this).attr("toggles"));
                    //calling on the attribute of the password
                    if (input.attr("type") == "password") {
                        //Changing to Text
                        input.attr("type", "text");
                    } else {
                        input.attr("type", "password");
                    }
                });

              
                function selectstate()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fetch.php?facult=" + document.getElementById("state").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("lga").innerHTML = xmlhttp.responseText;

                }
            </script>
            <script type="text/javascript">
            if (window.history.replaceState){
                window.history.replaceState(null,null,window.location.href);
            }
        </script>
    </body>

    </html>
<?php }

?>
