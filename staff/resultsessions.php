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
/*Reminder Session Start Here*/
 //Reminder for Result Uploading
  // $rup=mysqli_fetch_assoc(mysqli_query($con,"SELECT deadline,semester,session FROM resultsession WHERE description='exam processing' "));
  // $datef = new DateTime($rup['deadline']);
  // $nowf = new DateTime();
  // $difff = $datef->diff($nowf);
  // $dt=$difff->days;
  // if ($dt=='2') {
  //    staffsml($rup['semester'],$rup['session'],$rup['deadline'],$con);
  //    staffemaill($rup['semester'],$rup['session'],$rup['deadline'],$con);
  // }
/*Reminder Session End Here*/


$error='';

if(isset($_POST['updateuse'])){
        $user_id = mysqli_escape_string($con, $_SESSION['did']);
        $lastname = mysqli_escape_string($con,$_POST['section']);
        $address = mysqli_escape_string($con,$_POST['semes']);
        $dead = mysqli_escape_string($con,$_POST['deadline']);
        $lcu=mysqli_query($con, "UPDATE resultsession SET  session='$lastname',semester='$address',deadline='$dead' WHERE id = '$user_id'") or die(mysqli_error());
        if ($lcu) {
            $showModals='true';
            
        }else{
            $error="Error Occured While Updating";
            $showModalr='true';
        }
        
    }

if(isset($_POST['updateusea'])){
        $user_id = mysqli_escape_string($con, $_SESSION['did']);
        //die($_SESSION['id']);
        $lastname = mysqli_escape_string($con,$_POST['section']);
        $dead = mysqli_escape_string($con,$_POST['deadline']);
        //subject
        $lcu=mysqli_query($con, "UPDATE resultsession SET  session='$lastname',semester='n/a',deadline='$dead' WHERE id = '$user_id'") or die(mysqli_error());
        if ($lcu) {
            $showModals='true';
        }else{
            $error="Error Occured While Updating";
            $showModalr='true';
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

                  if ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                    }elseif ($paych['position']=="academicaffair") {
                        include('includes/acdbar.php');
                    }else if ($paych['position']=="registrar") {
                        include('includes/admoff.php');
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
                                <div>
                                    <h2 class="title">Academic Activities Setup</h2>
                                
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
                                                if ($paych['position']=="superadmin" OR $paych['position']=="academicaffair") {
                                                   ?>
                                                   <div>
                          <table id="example" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Session</th>
                                  <th>Semester</th>
                                  <th>Deadline</th>
                                  <th>Description</th>                                
                                  <th>Action</th>                                
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM resultsession ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['session']) ?></td>
                                <td><?php echo htmlentities($result['semester']);?></td>
                                <td><?php echo htmlentities($result['deadline']);?></td>
                                <td><?php echo htmlentities($result['description']);?></td>
                                <td><button class="btn btn-primary userinfo" id="<?php echo $result['id'] ?>"><span class="fa fa-edit"></span></button></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
                                                   <?php
                                                }elseif ($paych['position']=="registrar" OR $paych['position']=="admin_officer") {
                                                    ?>
                                                    <div>
                          <table id="example" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Session</th>
                                  <th>Deadline</th>
                                  <th>Description</th>                                
                                  <th>Action</th>                                
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM resultsession WHERE description='admission processing' ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['session']) ?></td>
                               
                                <td><?php echo htmlentities($result['deadline']);?></td>
                                <td><?php echo htmlentities($result['description']);?></td>
                                <td><button class="btn btn-primary userinfof"id="<?php echo $result['id'] ?>"><span class="fa fa-edit"></span></button></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
                                                <?php
                                                }else{
                                                    echo"<span style='color:red;'>Sorry You Don't Have Accessed to this Page</span>";
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
    <div class="modal-content" >

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Academic Activities Update
        <button type="button" class="close" data-dismiss="modal" style="float: right;">&times;</button></h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="teacher_details">

      </div>

      
    </div>
  </div>
</div> 
<div class="modal" id="viewModals">
  <div class="modal-dialog">
    <div class="modal-content" >

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Admission Academic Activity Update
        <button type="button" class="close" data-dismiss="modal" style="float: right;">&times;</button></h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="teacher_detail">

      </div>

      
    </div>
  </div>
</div> 
        <!-- Ends Here -->
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
                            <p>Please Select <?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModalr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
                            <p>Details Successfully Submitted</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Details Successfully Update</p>
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
            if(!empty($showModalEr)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModalEr").modal("show");
                });
            </script>';
            }
            if(!empty($showModalr)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModalr").modal("show");
                });
            </script>';
            }
            if(!empty($showModal)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModal").modal("show");
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
        ?> 
        <script type="text/javascript">
             $('body').on('click','.userinfo',function(){
            var userid = $(this).attr('id');
            // AJAX request
            $.ajax({
                    url: 'update_pro',
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

        $('body').on('click','.userinfof',function(){
            var userid = $(this).attr('id');
            // AJAX request
            $.ajax({
                    url: 'update_proa',
                    type: 'GET',
                    data: {userid: userid},
                    success: function(response)
                    { 
                        $('#teacher_detail').html(response);
                        // Display Modal
                        $('#viewModals').modal('show');
                    }
                });
            });
       
        </script>     
    </body>
</html>
<?php } 

?>
