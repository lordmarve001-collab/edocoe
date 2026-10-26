<?php 
session_start();
//error_reporting(0);
if (!isset($_SESSION['staffid']))
    header("Location: ../logout");
$connection = TRUE;
include "../include/dbconfig.php";
include 'dataco.php';
//include"includes/genSubmit.php";
$harr = $_SESSION['staffid'];
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}

if (isset($_POST["submitresult"])) {
    if (empty($_POST["programe"])) {
        $error="Please Select Programme";
        $showModalEr='true';
    }elseif (empty($_POST["section"])) {
        $error="Please Select Session";
        $showModalEr='true';
    }elseif (empty($_POST["semest"])) {
        $error="Please Select Semester";
        $showModalEr='true';
    }elseif (empty($_POST["level"])) {
        $error="Please Select Level";
        $showModalEr='true';
    }else{
    
    $studid=trim(mysqli_real_escape_string($con,strtoupper($_POST["studentid"])));
    $sectin= mysqli_real_escape_string($con,$_POST["section"]);
    $semet= mysqli_real_escape_string($con,$_POST["semest"]);
    $level= mysqli_real_escape_string($con,$_POST["level"]);
    $progr= mysqli_real_escape_string($con,$_POST["programe"]);
        
$rowx =mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM `studentregsem` WHERE `studentid`='$studid' AND `session`='$sectin' AND `level`='$level ' AND `programme`='$progr' "));
    
if ($rowx=="") {
        $error="student have Not Payed School Fee for " .$sectin;
    $showModalEr='true';
}
elseif ($rowx!="" AND $semet=='First Semester') {
    $sql=mysqli_query($con,"SELECT * FROM result WHERE studentid='$studid' AND acadsession='$sectin' AND semester='$semet' AND level='$level' AND programme='$progr' AND status='Y'  AND publish='1'");
$rows = mysqli_num_rows($sql);
        if($rows > 0){
        $_SESSION['studid'] = $studid;
        $_SESSION['section'] = $sectin;
        $_SESSION['semest'] = $semet;
        $_SESSION['level'] = $level;
        $_SESSION['programe'] = $progr;
        header("location: myresult");
    }else{
        $error= "There is no result base on the criterial!!";
        $showModalEr='true';
    }
        }
        elseif ($rowx!="" AND $semet=='Second Semester'){
        
                $sql=mysqli_query($con,"SELECT * FROM result WHERE studentid='$studid' AND acadsession='$sectin' AND semester='$semet' AND level='$level' AND programme='$progr' AND status='Y'  AND publish='1'");
        $rows = mysqli_num_rows($sql);
                if($rows > 0){
                $_SESSION['studid'] = $studid;
                $_SESSION['section'] = $sectin;
                $_SESSION['semest'] = $semet;
                $_SESSION['level'] = $level;
                $_SESSION['programe'] = $progr;
                header("location: myresult");
            }else{
                $error= "There is no result base on the criterial!!";
                $showModalEr='true';
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
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        
         <style>
        .errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
        </style>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php');?>   
          <!-----End Top bar>-->
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

<!-- ========== LEFT SIDEBAR ========== -->
                <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="subadmin")
                  {
                    
                    include('includes/subbar.php');

                    }elseif ($paych['position']=="subadministrator") {
                        include('includes/ictbar.php');
                  }else if ($paych['position']=="lecturer") {
                    
                    include('includes/lectbar.php');
                    }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
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
                                    <h3 class="title">STUDENT RESULT CHECKING SETUP</h3>
                                </div>
                                
                            </div>
                            
                        </div>
                        <!-- /.container-fluid -->
<?php
    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

    if ($paych['position']=="superadmin" OR $paych['position']=="subadmin" OR $paych['position']=="subadministrator")
    {
    ?>
                        <section class="section">
                            <div class="container-fluid">

                             

                              

                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
  
                                            <div class="panel-body">

                                                <form method="post">
                                                    <div class="form-group has-success">
                                                       <label for="success" class="control-label">STUDENT ID</label>
                                                       <input type="text" name="studentid" class="form-control" required="required" placeholder="Enter Student Matric Or ID Here" autocomplete="off">
                                                    </div> 
                                                    <div class="form-group has-success">
                                                        <label for="success" class="control-label">PROGRAMME</label>
                                                        <div class="">
                                                            <select name="programe" class="form-control" required="required" id="success">
                                                                <option selected disabled>Please select Programme</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM program  ORDER BY pname");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                            </select>
                                                          
                                                        </div>
                                                    </div> 
                                                    <div class="form-group has-success">
                                                        <label for="success" class="control-label">SECTION</label>
                                                        <div class="">
                                                            <select name="section" class="form-control" required="required" id="success">
                                                                <option selected="selected" disabled="disabled">SELECT SESSION</option>
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
                                                     <div class="form-group has-success">
                                                        <label for="success" class="control-label">SEMESTER:</label>
                                                        <div class="">
                                                           <select name="semest" class="form-control"  >
                                                            <option selected="selected" disabled="disabled">SELECT SEMESTER</option>
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
                                                     <div class="form-group has-success">
                                                        <label for="success" class="control-label">LEVEL:</label>
                                                        <div class="">
                                                            <select  name="level" class="form-control">
                                                                <option selected="selected" disabled="disabled">SELECT LEVEL</option>
                                                                 <?php
                                        $res = mysqli_query($con, "SELECT * FROM level ORDER BY levels");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                        <?php

                                        }
                                        ?>
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-success">

                                                        <div class="">
                                                           
                                                           <button name="submitresult" class="btn btn-success btn-label-right btn-label btn-label-right">View Result <i class="fa fa-eye"></i></button>
                                                    </div>


                                                    
                                                </form>

                                              
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-8 col-md-offset-2 -->
                                </div>
                                <!-- /.row -->
<?php                                                
    }
    else{
    
   ?>
   <p style="color:darkred;font-size: 16px;font-weight: bold;text-align: justify;">Sorry Dear: <b style="color:blue;"><?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page.</p>
   <?php
    }
    ?> 
                               
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

                            </div>
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->

                    </div>
                    <!-- /.main-page -->

                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->

        </div>
       
    </body>
</html>
<?php 
include"includes/buton.php";
include 'ipcap.php';
if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    }
 ?>