<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
$error='';
$succ='';
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session FROM resultsession WHERE description='Current Session'"));
$sems=$dat['semester'];
$ses=$dat['session'];

if (isset($_POST['submitclv'])) 
    {
        if (empty($_POST['staffid'])) {
            $error="Staff Detail Required";
            $showModal="true";
        }else{

            $staff=mysqli_escape_string($con,$_POST['staffid']);
            $role=mysqli_escape_string($con,$_POST['staffrol']);
            $sq=mysqli_query($con,"SELECT * FROM jupcordinator WHERE staffid='$staff'  AND acadsession='$ses'");
            if (mysqli_num_rows($sq)==1) {
            $error="Staff Detail already Exist";
            $showModal="true";
        }
        else{

            $insrt=mysqli_query($con,"INSERT INTO jupcordinator (staffid,acadsession,role,status) VALUES ('$staff','$ses','$role','active')");
            if ($insrt) {
                mysqli_commit($con);
            }
            }

        }
    }
        // For the Deleting of the subject
if (isset($_POST['deletsub'])) {
    $del=mysqli_query($con,"DELETE FROM jupcordinator WHERE staffid='".$_SESSION['staffds']."' AND acadsession='$ses' ");
    if ($del) {
         $showModalsucs='true';
         $succ="Detail Successfully Delected From This Staff";
      }else{
          $error= "Error Occured while Delete Information";
      $showModals='true';
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
        <link rel="stylesheet" type="text/css" href="css/select.css"/>
        <link rel="stylesheet" type="text/css" href="css/btable.css"/>
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
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

                  if ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                    
                    }elseif ($paych['position']=="subadmin")
                  {
                     include('includes/subbar.php');

                    
                    }else{
                        include('includes/leftbar.php');
                        }
                    ?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">PDE Cordinator Appointment Setup</h4>
                                
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

           if ($paych['position']=="superadmin" OR $paych['position']=="subadmin") {
         ?>    
                                                <section class="section">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Staff Member:</label>
                                                         <div class="col-sm-10">
                                                         <select name="staffid" class="form-control clid selectpicker" data-live-search="true" id="faculty" required="required">
                                                            <option selected disabled>Please select Staff</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM  people WHERE status='active' ORDER BY staffid");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["staffid"]; ?>"><?php echo  $row["staffid"]; ?> <?php echo htmlentities($row['title'].". ".$row['firstname']." ".$row['lastname']) ?></option>
                                                        <?php

                                                        }
                                                        ?>

                                                         </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Staff Role:</label>
                                                         <div class="col-sm-10">
                                                         <select name="staffrol" class="form-control clid" id="faculty" required="required">
                                                            <option selected disabled>Please select Staff Role</option>
                                                            <option value="cordinator">Cordinator</option>
                                                            <option value="accountant">Accountant</option>
                                                            <option value="officer">Admission Officer</option>
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
                                                            <th>Staff Role</th>
                                                            <th>Session</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <?php
                                                       
                              $sql = "SELECT * FROM jupcordinator JOIN people ON jupcordinator.staffid=people.staffid  ORDER BY jupcordinator.staffid";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            // if($query->rowCount() > 0)
                            // {
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['title'].". ".$result['firstname']. " ".$result['lastname']) ?></td>
                                <td><?php echo htmlentities($result['staffid']);?></td>
                                <td><?php echo htmlentities($result['role']);?></td>
                                <td><?php echo htmlentities($result['acadsession']);?></td>
                                <td>
                                    <button class="btn btn-danger userinfo" id="<?php echo $result['staffid'] ?>"  cvls="<?php echo $result['acadsession']?>"> <i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }//} ?> 
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
        <!-- /.main-wrapper -->
        <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
            include 'includes/butons.php';
        ?>

        
      <script type="text/javascript">
        $(document).ready(function() {
          //
            //$('.userinfo').click(function() {
            $('body').on('click','.userinfo',function() {
                var userid = $(this).attr('id');
                var clss = $(this).attr('cvls');
                // AJAX request
                $.ajax({
                    url: 'delegjp',
                    type: 'GET',
                    data: {
                        userid: userid,
                        clss: clss
                    },
                    success: function(response) {
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
