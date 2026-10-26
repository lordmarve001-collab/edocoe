<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout.php"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout.php");
}

include "includes/getSubmit.php";
        //echo $depn; 

        //submiting the course informations
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/animate.min.css" media="screen" >
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php //include('includes/deanbar.php');?>  
                    <!-- /.left-sidebar -->
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                     if ($paych['position']=="dean") {
                       ?>

                    <?php
                    include('includes/deanbar.php');
                     }elseif ($paych['position']=="facofficer") {
                        include('includes/facbar.php');
                     }
                    else{
                    ?>
                    <?php
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title">Faculty Student(s) Department View Setup</h4>
                                
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
                                                
                                              <section class="section not">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                       <div class="form-group">
                            <label for="default" class="col-sm-2">Programme:</label>
                             <div class="col-sm-10">
                             <select name="section" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Programme</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM programs WHERE fid='$faclt' ORDER BY pname");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                                                                                                                 
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="query" class="btn btn-primary">View Student</button>
                        </div>
                    </div>                    
                    </form>
                </section>
                <?php
                    if (isset($_POST['query'])) {
                        if (empty($_POST['section'])) {
                         $showModal = "true";
                         $error="Programme";
                        }
                        else{
                        $secc=mysqli_escape_string($con,$_POST['section']);
                        $sql=mysqli_query($con,"SELECT * FROM student JOIN department ON student.department=department.deptid  WHERE student.faculty='$faclt' AND student.programme='$secc'");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                ?>
                <section>
                                                    <table id="example2" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Student ID</th>
                                                            <th>Fullname</th>
                                                            <th>Department</th>
                                                            <th>Programme</th>
                                                            <th>Year Admitted</th>
                                                            <th>Current Status</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <?php
                              $sql = "SELECT * FROM student JOIN department ON student.department=department.deptid  WHERE student.faculty='$faclt' AND student.programme='$secc' ORDER BY student.studentid";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            // if($query->rowCount() > 0)
                            // {
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['studentid']) ?></td>
                                <td style="text-transform: capitalize;"><?php echo htmlentities($result["firstname"]." ".$result['lastname']);?></td>
                                <td><?php echo htmlentities($result['dept']);?></td>
                                <td><?php echo htmlentities($result['programme']);?></td>
                                <td><?php echo htmlentities($result['acadsession']);?></td>
                                <td><?php echo htmlentities($result['status']);?></td>
                                <td><button class="btn btn-success userinfo" id="<?php echo $result['studentid']?>" type="button"  title="View Student Data"><i class="fa fa-eye"></i></button></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }//} ?> 
                            </tbody>
                            </table>
                        </section>
                        <?php
                            }
                            else{
                           $showModale='true';

                            }
                            //}
                        }
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Student Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_details">

                  </div>

      <!-- Modal footer -->
              <div class="modal-footer">
                
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div> 

        <!-- /.main-wrapper -->
        <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please Select <?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>There No Result Base on the Criterial Selected</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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
            <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalx" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Message Successfully Sent</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Student Details -->
            <div class="modal" id="viewModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Student Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_details">

                  </div>

      <!-- Modal footer -->
              <div class="modal-footer">
                
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div> 
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';

    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
    if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
            });
        </script>';
    }
    if(!empty($showModals)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModals").modal("show");
            });
        </script>';
    }
    if(!empty($showModalx)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalx").modal("show");
            });
        </script>';
    }
        ?>
    <script type="text/javascript">
        $(document).ready(function(){

            //$('.userinfo').click(function(){
           $('body').on('click','.userinfo',function(){
            var userid = $(this).attr('id');
            // AJAX request
            $.ajax({
                    url: 'viewstudata',
                    type: 'GET',
                    data: {userid: userid},
                    success: function(response)
                    { 
                        $('#teacher_details').html(response);
                        // Display Modal
                        $('#viewModal').modal('show');
                    }
                });
            });
        });   
    </script>
    </body>
</html>
<?php } ?>