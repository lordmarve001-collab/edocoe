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
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
                                    //Here is insert the accademic session detail into the database
    $admy=$ses;
/*
*Remember to Implement Welcome email to new User
*/

 /*For Updating*/
if (isset($_POST['submitpr'])) {
$firstname=mysqli_escape_string($con,$_POST['firstname']);
$lastname=mysqli_escape_string($con,$_POST['lastname']);
$jamre=mysqli_escape_string($con,$_POST['jamreg']);
$pro=mysqli_escape_string($con,$_POST['programe']);
     $upd=mysqli_query($con,"UPDATE applicant SET firstname='$firstname',lastname='$lastname',applid='$jamre',status='$pro' WHERE applid='".$_SESSION['userid']."' "); 
      if($upd){
          $succ='Data Successfully Updated';
          $showModal='true';
        }
        else{
            $error="Error Occured While Updating!!";
            $showModalEr='true';
        } 
   }
 /*For Deleting*/
if (isset($_POST['updaterol'])) {
     $del=mysqli_query($con,"DELETE FROM applicant WHERE applid='".$_SESSION['staffds']."'");
     if($del){
          $succ='Successfully Deleted';
          $showModal='true';
        }
        else{
            $error="Error Occured While Removing!!";
            $showModalEr='true';
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
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title"><?php echo htmlentities($admy) ?> Applicant(s) </h2>
                                
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
                     if ($paych['position']=="superadmin" OR $paych['position']=="subadmin" OR $paych['position']=="admin_officer" OR $paych['position']=="sys_analyst") {
                       
                    
                     ?>      
                                       
        <section>
         <table id="example" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Firstname</th>
                    <th>Last Name</th>
                    <th>Applid</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Session</th>
                    <th>Action</th>
                   
                </tr>
            </thead>
            <tbody>
                  <?php
                  $sql = "SELECT * FROM applicant WHERE session='$admy' ORDER BY applid";
                  
                $query = mysqli_query($con,$sql);
                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                $cnt=1;
                
                foreach($results as $result)
                {   ?>
                <tr>
                 <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($result['firstname']) ?></td>
                    <td><?php echo htmlentities($result['lastname']);?></td>
                    <td><?php echo htmlentities($result['applid']);?></td>
                    <td><?php echo htmlentities($result['phoneno']);?></td>
                    <td><?php echo htmlentities($result['email']);?></td>
                    <td><?php echo htmlentities($result['session']);?></td>
                    <td>
                        <button class="btn btn-success userinfo" id="<?php echo htmlentities($result['applid']) ?>" title="View Student Record"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-primary userinfor" id="<?php echo htmlentities($result['applid']) ?>" title="View Student Data"><i class="fa fa-eye"></i></button> 
                    </td>
                </tr>
                <?php 

                $cnt=$cnt+1;
                  
                } ?> 
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
<div class="modal" id="viewModal">
  <div class="modal-dialog">
    <div class="modal-content" >

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Candidate Data </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="teacher_details">

      </div>

      
    </div>
  </div>
</div> 
<div class="modal" id="viewModals">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Detail Removal</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_detail">

                  </div>

      <!-- Modal footer -->
              

            </div>
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
                <div class="modal-dialog modal-dialog-scrollable" role="document">
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
        if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
    if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    } 
    if(!empty($showModall)) {
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
        var input=$($(this).attr("toggle"));
        //calling on the attribute of the password
        if (input.attr("type")=="password") {
            //Changing to Text
            input.attr("type","text");
        }else{
            input.attr("type", "password");
        }
    });

      $(".toggle-passwordc").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input=$($(this).attr("toggles"));
        //calling on the attribute of the password
        if (input.attr("type")=="password") {
            //Changing to Text
            input.attr("type","text");
        }else{
            input.attr("type", "password");
        }
    });

   $(document).ready(function(){

            // $('.userinfo').click(function(){
            $('body').on('click','.userinfo',function(){
            var userid = $(this).attr('id');
            // AJAX request
            $.ajax({
                    url: 'appledit',
                    type: 'POST',
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

    $(document).ready(function(){

            $('body').on('click','.userinfor',function(){
            var userid = $(this).attr('id');

            $.ajax({
                    url: 'applide',
                    type: 'POST',
                    data: {userid: userid},
                    success: function(response)
                    { 
                        $('#teacher_detail').html(response);
                        // Display Modal
                        $('#viewModals').modal('show');
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
