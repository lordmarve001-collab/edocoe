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
        if ($_POST['password'] != $_POST['passwordc']) {
                $error = "Password did not match";
                $showModalEr = 'true';
            } elseif (empty($_POST["position"])) {
            $error = "Position Required";
            $showModalEr = 'true';
           }else {
            // AssinData To Variable
            $firstname = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["firstname"])));
            $lastname = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["lastname"])));
            $position = strtolower(mysqli_escape_string($con, $_REQUEST["position"]));
            $escapedPW = mysqli_real_escape_string($con, $_REQUEST["password"]);
            $hashedPW = hash('sha256', $escapedPW);
            //$hashedPW = $escapedPW;
            $today=date("Y-m-d");
            $id = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(staffid) as userid FROM stafflogin"))['userid'];
            $id = $id + 9;
            $date = date('Y');
            $yr = substr($date, -2);
            //$userID ="IJBCOE/ST/" . str_pad($id, 2, '0', STR_PAD_LEFT);
            $userID = "EDOCOHEST/ST/" . $yr . "/" . str_pad($id, 2, '0', STR_PAD_LEFT);
            $sql = "SELECT * FROM stafflogin WHERE (staffid='$userID') AND status='active'";
            $res = mysqli_query($con, $sql);
            if (mysqli_num_rows($res) > 0) {
                $error='Staffid ID '.$userID.' And Email '.$unemal.' already Exist';
                $showModalEr='true';
            } else {
                    //inserting to the database
                    $qu = mysqli_query($con, "INSERT INTO stafflogin(staffid,status,firstname,lastname,password,username,position) VALUES ('$userID','active','$firstname','$lastname','$hashedPW','$userID','staff')");
                    $ins = mysqli_query($con, "INSERT INTO staffprive(staffid,position) VALUES ('$userID','$position') ");
                    if ($qu > 0) {
                        $succ = 'Successfully submited Your Username: '.$userID;
                        $showModal = 'true';
                    } else {
                        $error = "Error Occured While Submiting!!";
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
                  }elseif ($paych['position']=="subadministrator") {
                        include('includes/ictbar.php');
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
                                    <h2 class="title">Enter New Staff Details</h2>

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

                                          if ($paych['position']=="superadmin" OR $paych['position']=="subadministrator" OR $paych['position']=="subadmin")
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
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Position:
                                                                </label>
                                                                
                                                                    <select class="form-control clid" name="position">
                                                                         <option selected="selected" disabled="disabled">Please Select Privilege</option>
                                                                        <?php
                                                                            $res = mysqli_query($con, "SELECT * FROM privilege WHERE status='1'");
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
