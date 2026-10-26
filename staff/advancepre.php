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
    if (isset($_POST['submitpr'])) {
            $staf = $_POST['staffid'];
            $posc = $_POST['postc'];
            $posi = $_POST['newprive'];
            
                    $upd = mysqli_query($con, "UPDATE requestform SET status='$posi', adate=now() WHERE staffid='".$_SESSION['staffis']."'");
                    if ($upd > 0) {
                        $succ = 'Successfully Updated';
                        $showModal = 'true';
                        //newuserst($username,$userID,$position);
                    } else {
                        $err = "Error Occured While Submiting!!";
                        $showModalEr = 'true';
                    }
    }
    //for status Updated
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

                    <!-- ========== LEFT SIDEBAR ========== -->
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="sys_analyst")
                  {
                    
                    include('includes/sysbar.php');

                    }else if ($paych['position']=="subadmin") {
                    
                    include('includes/subbar.php');
                    } elseif ($paych['position']=="accountant")
                  {
                     include('includes/accbar.php');

                  }elseif ($paych['position']=="bursar") {
                        include('includes/supbubar.php');
                  }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Staff Advance Request Form Setup</h2>

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

                  if ($paych['position']=="accountant" OR $paych['position']=="bursar" OR $paych['position']=="superadmin"){
                       
                    
                     ?>
                                            <span class="pull-left"><a href="advancepre"  class="btn btn-primary"><span class="fa fa-users"></span> View Previous Advance Form Request</a></span>
                                            <div style="height:50px;"></div>    
                                            <section>
                                                <table id="example" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Staff ID</th>
                                                            <th>Full Name</th>
                                                            <th>Rank</th>
                                                            <th>Amount</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                            <th>Action</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        
                                                        $sql = "SELECT  * FROM requestform WHERE status!='Pending' ORDER BY date";

                                                        $query = mysqli_query($con, $sql);
                                                        $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                        $cnt = 1;
                                                        
                                                        foreach ($results as $result) {   ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($result['staffid']) ?></td>
                                                                <td><?php echo htmlentities($result['fullname']); ?></td>
                                                                <td><?php echo htmlentities($result['rank']); ?></td>
                                                                <td>₦ <?php echo htmlentities($result['amount']); ?></td>
                                                                <td><?php echo htmlentities($result['adate']); ?></td>
                                                                <td><?php echo htmlentities($result['status']); ?></td>
                                                                <td>
                                                                    
                                                                    
                                                                    <button class="btn btn-primary staffprive" title="View Staff Record" id="<?php echo $result['staffid'] ?>" pos="<?php echo $result['position'] ?>"><i class="fa fa-edit"></i></button>
                                                                    
                                                                    <button class="btn btn-success userinfof" title="View Staff Record" id="<?php echo $result['staffid'] ?>" pos="<?php echo $result['position'] ?>"><i class="fa fa-eye"></i></button>
                                                                    
                                                            </tr>
                                                            <?php

                                                            $cnt = $cnt + 1;
                                                            
                                                        } 
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </section>
                    <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
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
                            <h4 class="modal-title">Staff Advance Previous Record</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailss">

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
                            <h4 class="modal-title">Update Advance Form</h4>
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

                $(document).ready(function() {

                    //$('.userinfo').click(function() {
                     $('body').on('click','.userinfo',function() {
                        var userid = $(this).attr('id');
                        var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'staffst',
                            type: 'GET',
                            data: {
                                userid: userid,
                                userip: userip
                            },
                            success: function(response) {
                                $('#teacher_details').html(response);
                                // Display Modal
                                $('#viewModal').modal('show');
                            }
                        });
                    });
                });
                /* seperate Line */
                $(document).ready(function() {

                    //$('.userinfof').click(function() {
                      $('body').on('click','.userinfof',function(){
                        var userid = $(this).attr('id');
                        var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'adview',
                            type: 'GET',
                            data: {
                                userid: userid,
                                userip: userip
                            },
                            success: function(response) {
                                $('#teacher_detailss').html(response);
                                // Display Modal
                                $('#viewModals').modal('show');
                            }
                        });
                    });
                });
                /* Statff Privilege */
                $(document).ready(function() {

                    //$('.userinfof').click(function() {
                      $('body').on('click','.staffprive',function(){
                        var userid = $(this).attr('id');
                        var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'adv',
                            type: 'GET',
                            data: {
                                userid: userid,
                                userip: userip
                            },
                            success: function(response) {
                                $('#teacher_detailst').html(response);
                                // Display Modal
                                $('#viewModalst').modal('show');
                            }
                        });
                    });
                });
            </script>
    </body>

    </html>
<?php 
 }

?>