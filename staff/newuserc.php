<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();
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

    if (isset($_POST["submitrecord"])) {
       
             $tod=date("Y-m-d");   
            // AssinData To Variable
            $firstname = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["firstname"])));
            $lastname = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["lastname"])));
            $phone = trim(strtolower(mysqli_escape_string($con, $_REQUEST['phonen'])));
            $emal = trim(strtolower(mysqli_escape_string($con, $_REQUEST['emails'])));
            $escapedPW = mysqli_real_escape_string($con, $_REQUEST["phonen"]);
            $hashedPW = hash('sha256', $escapedPW);
            $today=date("Y-m-d");
            $id = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(cafeid) as userid FROM cafeapp"))['userid'];
            $id = $id + 1;
            $date = date('Y');
            $yr = substr($date, -2);
            $userID = "UAT/CB/" . $yr . "/" . str_pad($id, 3, '0', STR_PAD_LEFT);
            $sql = "SELECT * FROM cafeapp WHERE (cafeid='$userID' OR email='$emal' OR phoneno='$phone') AND status='Approved'";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) > 0) {
                $error='Cafe ID '.$userID.' Or Email '.$emal.' already Exist';
                $showModalEr='true';
            } else {
                    //inserting to the database
                    $qu = mysqli_query($con, "INSERT INTO cafeapp(cafeid,status,phoneno,email,password,firstname,lastname,applicationDate) VALUES ('$userID','Approved','$phone','$emal','$hashedPW','$firstname','$lastname','$tod')");
                   
                    if ($qu > 0) {
                        $succ = 'Account Successfully Created';
                        $showModal = 'true';
                        //studentemail($con,$phone,$orgname,$schadress,$logo,$emal,$userID,$mail);
                        //studensms($phone,$userID);
                    } else {
                        $error = "Error Occured While Creating!!"; 
                        $showModalEr = 'true';
                    }
                }
            //}
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
                  }elseif ($paych['position']=="admission") {
                        include('includes/admsec.php');
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
                                <div >
                                    <h2 class="title">Enter New Cafe Details</h2>

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

                                          if ($paych['position']=="superadmin")
                                          { ?>
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
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Phone:
                                                                </label>
                                                                
                                                                    <input type="tel" maxlength="38" name="phonen" class="form-control clid" required="required">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Company Email:
                                                                </label>
                                                                
                                                                    <input type="email" name="emails" class="form-control clid" required="required">
                                                                    
                                                                
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
                                            <span>Sorry You don't have access to this page</span>
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

            </script>
            <script type="text/javascript">
            if (window.history.replaceState){
                window.history.replaceState(null,null,window.location.href);
            }
        </script>
    </body>

    </html>
<?php 
}
?>
