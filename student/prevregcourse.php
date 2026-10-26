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
if (isset($_POST['submitcor'])) {
    //cheking if
    if (empty($_POST['semester'])) {
        $showModal='true';
        $error='Semester';
    }elseif (empty($_POST['sessionx'])) {
        $showModal='true';
        $error='Session';
    }elseif (empty($_POST['level'])) {
        $showModal='true';
        $error='Level';
    }
    else{
        
        $sems=mysqli_escape_string($con,$_POST['semester']);
        $ses=mysqli_escape_string($con,$_POST['sessionx']);
        $lv=mysqli_escape_string($con,$_POST['level']);

        $chek=mysqli_num_rows(mysqli_query($con,"SELECT * FROM studentlateregsem WHERE  semester='$sems' AND session='$ses' AND programme='$progr' AND level='$lv'  AND studentid='".$_SESSION['staffid']."' "));
        if ($chek>0) {
            $_SESSION['semester']=$sems;
            $_SESSION['sessionx']=$ses;
            $_SESSION['level']=$lv;
            header('location:precoursereg');
        }else{
            $showModale='true';
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
                    
                  include('includes/leftbar.php');
                       
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Student Previous Course Registration</h4>
                                
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
                                                
                                                     <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                    
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Semester:</label>
                                                         <div class="col-sm-10">
                                                         <select name="semester" class="form-control clid"required="required">
                                                            <option selected disabled>Please select Semester</option>
                                                            <?php
                                                              $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest");
                                                              while ($row = mysqli_fetch_array($res)) {

                                                                  ?>
                                                                  <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
                                                              <?php

                                                              }
                                                            ?>
                                                        
                                                        </select>
                                                       </div>

                                                    </div>
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Session:</label>
                                                         <div class="col-sm-10">
                                                         <select name="sessionx" class="form-control clid"required="required">
                                                            <option selected disabled>Please select Session</option>
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

                                                    </div>


                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Level:</label>
                                                         <div class="col-sm-10">
                                                         <select name="level" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Level</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM level  ORDER BY levels");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select>
                                                       </div>

                                                    </div>
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-primary">View Course</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                                     
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
                            <p>Please Select <?php echo htmlentities($error); ?> </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
               
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>1. It's Seem you have not payed for Previous Late Registration Click <a href="prevpay" style="color:blue">Here</a> to Make Payment. </p>
                            <p>2. Visit Bursary with your Bank Teller for prove Previous Late Registration Payment For Clearance. </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalS" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Result is Successfully Publish</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
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
                            <p>An Error Occured While Publishishing Results Please Try Again!!!</p>
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
             $(document).ready(function(){
 $('#check').click(function(){
    if($(this).is(":checked"))
        $('.checkitem').prop('checked',true);
    else
    $('.checkitem').prop('checked',false);
});
});
        </script>
        <?php           
    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
    if(!empty($showModalp)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalp").modal("show");
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
    if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
            });
        </script>';
    }
    if(!empty($showModalS)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalS").modal("show");
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
?>
    </body>
</html>
<?php } ?>
