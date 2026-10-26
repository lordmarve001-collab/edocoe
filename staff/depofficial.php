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
/* $factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fname='$fac' "));
        $fact = $factn['fid'];
        //echo $fact;
        $depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE dept='$dt' "));
        $depn = $depna['deptid']; */

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session FROM resultsession WHERE description='Current Session'"));
$sems=$dat['semester'];
$ses=$dat['session'];

include "includes/getSubmit.php";

if (isset($_POST['submitclv'])) {
    // $succ="We Are Here";
    // $showModalsucs='true';
    $stu=mysqli_real_escape_string($con,$_POST['staffid']);
    $level=mysqli_real_escape_string($con,$_POST['level']);
    //$newarm=mysqli_real_escape_string($con,$_POST['arms']);
    $upd=mysqli_query($con,"UPDATE staffrank SET rankname='$level' WHERE staffid='$stu' ");
    $upds=mysqli_query($con,"UPDATE staffprive SET position='$level' WHERE staffid='$stu'");
    if ($upd) {
         $showModalsucs='true';
         $succ="Role Successfully Updated for This Staff";
      }else{
          $error= "Error Occured while Updating Information";
      $showModals='true';
      }
  }


        // For the Deleting of the subject
if (isset($_POST['deletsub'])) {
    //$sub=mysqli_real_escape_string($con,$_POST['delt']);
    //echo $_SESSION['claa'];
    $del=mysqli_query($con,"DELETE FROM leveladv WHERE staffid='".$_SESSION['staffds']."' AND level='".$_SESSION['claa']."' AND session='$ses'");
    if ($del) {
         $showModalsucs='true';
         $succ="Detail Successfully Delected From This Staff";
      }else{
          $error= "Error Occured while Delete Information";
      $showModals='true';
      }
  }
  // for updating
  if (isset($_POST['updateuse'])) {
    // $succ="We Are Here";
    // $showModalsucs='true';
    $newcla=mysqli_real_escape_string($con,$_POST['semes']);
    //$newarm=mysqli_real_escape_string($con,$_POST['arms']);
    $upd=mysqli_query($con,"UPDATE leveladv SET level='$newcla' WHERE staffid='".$_SESSION['staffds']."' AND level='".$_SESSION['sub']."' AND session='$ses' ");
    if ($upd) {
         $showModalsucs='true';
         $succ="Detail Successfully Updated for This Staff";
      }else{
          $error= "Error Occured while Updating Information";
      $showModals='true';
      }
  }
  
  // NEW: Delete HOD record
  if (isset($_POST['delete_hod'])) {
      $staffid = mysqli_real_escape_string($con, $_POST['staffid']);
      $position = mysqli_real_escape_string($con, $_POST['position']);
      
      // Delete from staffprive where staffid and position = 'hod'
      $del_query = mysqli_query($con, "DELETE FROM staffprive WHERE staffid='$staffid' AND position='hod'");
      
      if ($del_query) {
          $showModalsucs = 'true';
          $succ = "Department Officer Successfully Removed";
      } else {
          $error = "Error Occurred while Deleting Record";
          $showModals = 'true';
      }
  }
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
      <style type="text/css">
          #blah{
                width:170.256px;
                height:159px;
                border:2px solid black;
                }
      </style>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
  <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                     if ($paych['position']=="dean") {
                       
                    include('includes/deanbar.php');
                     }elseif ($paych['position']=="facofficer") {
                        include('includes/facbar.php');
                     }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?> 
                   
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Department Official Setup</h2>
                                
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

                                                  if ($paych['position']=="dean")
                                                  {  
                                                ?> 
                                                <section class="section">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Staff Member:</label>
                                                         <div class="col-sm-10">
                                                         <select name="staffid" class="form-control clid" id="faculty" required="required">
                                                            <option selected disabled>Please select Staff</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid  WHERE  staffaculty.faculty='$faclt'AND people.status='active' ORDER BY staffaculty.staffid");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["staffid"]; ?>"><?php echo htmlentities($row['title']." ".$row['firstname']." ".$row['lastname']." ".$row['othername']) ?> <?php echo  $row["deptm"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>

                                                         </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default"class="col-sm-2 control-label">Role:</label>
                                                         <div class="col-sm-10">
                                                         <select name="level" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Role</option>
                                                            
                                                            <option value="hod">Department Officer</option>
                                                        
                                                        
                                                        </select>
                                                        </div>
                                                    </div>
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitclv" id="submit" class="btn btn-primary">Assigned</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                                <section>
                                                    <table id="example" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Staff Name</th>
                                                            <th>StaffID</th>
                                                            <th>Department</th>
                                                            <th>Role</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <?php
        $res = mysqli_query($con, "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid  
        WHERE  staffaculty.faculty='$faclt'AND people.status='active' ORDER BY staffaculty.staffid");
                          
                              $sql = "SELECT * FROM staffprive JOIN people ON staffprive.staffid=people.staffid JOIN staffaculty ON staffprive.staffid=staffaculty.staffid 
                              WHERE staffaculty.faculty='$faclt' AND staffprive.position='hod' ORDER BY staffprive.staffid";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            // if($query->rowCount() > 0)
                            // {
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['title']." ".$result['firstname']. " ".$result['lastname']." ".$result['othername']) ?></td>
                                <td><?php echo htmlentities($result['staffid']);?></td>
                                <?php $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='".$result['deptm']."'"));
                                      $sems=$dat['dept'];?>
                                <td><?php echo htmlentities($sems);?></td>
                                <td>Department Officer</td>
                                <td>
                                    <button class="btn btn-primary userinfof" id="<?php echo $result['staffid'] ?>" pos="<?php echo $sems ?>"> <i class="fa fa-edit"></i></button>
                                    <!-- NEW DELETE BUTTON with confirmation warning -->
                                    <button class="btn btn-danger delete-hod-btn" data-staffid="<?php echo $result['staffid'] ?>" data-staffname="<?php echo htmlentities($result['title']." ".$result['firstname']. " ".$result['lastname']." ".$result['othername']) ?>" data-dept="<?php echo htmlentities($sems) ?>"> <i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }//} ?> 
                                                    </tbody>
                                                    </table>
                                                </section>
                            <?php 
                                }else{ ?>
                                <p style='color:red;'>Sorry You Don't Have Access To This Page.</p> 
                                    
                            <?php   }
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
          <div class="modal" id="viewModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" style="float:left;">Delete Comfirmation <i class="fa fa-trash"></i> </h4>
                        <button class="close" data-dismiss="modal" style="float:right">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" id="teacher_details">

                    </div>

                    <!-- Modal footer -->

                </div>
            </div>
        </div>
        <div class="modal" id="viewModals">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" style="float:left;">Update Comfirmation <i class="fa fa-refresh"></i> </h4>
                        <button style="float:right;" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" id="teacher_detailss">

                    </div>

                    <!-- Modal footer -->

                </div>
            </div>
        </div>
        
        <!-- NEW MODAL FOR DELETE CONFIRMATION (Bootstrap) -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #d9534f; color: white;">
                        <h5 class="modal-title" id="deleteConfirmModalLabel"><i class="fa fa-exclamation-triangle"></i> Confirm Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to remove <strong id="deleteStaffName"></strong> as Department Officer for <strong id="deleteDeptName"></strong>?</p>
                        <p class="text-danger">This action cannot be undone!</p>
                        <form id="deleteHodForm" method="post" action="">
                            <input type="hidden" name="staffid" id="deleteStaffId" value="">
                            <input type="hidden" name="position" value="hod">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_hod" class="btn btn-danger">Yes, Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- /.main-wrapper -->
        <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
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
            <div class="modal fade" id="checkModalsucc" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                            <?php echo htmlentities($succ, ENT_QUOTES, 'utf-8'); ?>
                            </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
        ?>

        
      <script type="text/javascript">
        $(document).ready(function() {
          //
            //$('.userinfo').click(function() {
            $('body').on('click','.userinfo',function() {
                var userid = $(this).attr('id');
                var userip = $(this).attr('pos');
                //var clss = $(this).attr('cvls');
                // AJAX request
                $.ajax({
                    url: 'deletecla',
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
        $(document).ready(function() {

            //$('.userinfof').click(function() {
          $('body').on('click','.userinfof',function() {
                var userid = $(this).attr('id');
                var userip = $(this).attr('pos');
                //var clss = $(this).attr('cvls');
                // AJAX request
                $.ajax({
                    url: 'updatsclass',
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
        
        // NEW: Delete confirmation modal trigger
        $(document).ready(function() {
            $('body').on('click', '.delete-hod-btn', function() {
                var staffid = $(this).data('staffid');
                var staffname = $(this).data('staffname');
                var deptname = $(this).data('dept');
                
                $('#deleteStaffName').text(staffname);
                $('#deleteDeptName').text(deptname);
                $('#deleteStaffId').val(staffid);
                
                $('#deleteConfirmModal').modal('show');
            });
        });
        </script>
    </body>
</html>
<?php
if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
if(!empty($showModalsucs))
    {
    // CALL MODAL HERE
    echo '<script type="text/javascript">
       $(document).ready(function(){
           $("#checkModalsucc").modal("show");
       });
    </script>';
    }
 } ?>