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
if (isset($_POST['proceeds'])) {
    if (empty($_POST['semesters'])) {
        $error="Please Select Semester";
        $showModal='true';
    }elseif (empty($_POST['levels'])){
        $error="Please Select Level";

        $showModal='true';
    }
	elseif (empty($_POST['program'])) {
        $error="Please Select Programme";
        $showModal='true';
    }else{
        $semest=mysqli_escape_string($con,$_POST['semesters']);
        $levls=mysqli_escape_string($con,$_POST['levels']);
        $progra=mysqli_escape_string($con,$_POST['program']);

        $_SESSION['levels']=$levls;
        $_SESSION['semest']=$semest;
        $_SESSION['progra']=$progra;
        header("location:setuppage");
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
      <style type="text/css">
          #blah{
    width:170.256px;
    height:159px;
    border:2px solid black;
}
.td{
     cursor: not-allowed;
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
                   <!-- <?php //include('includes/hodbar.php');?>   -->
                    <!-- /.left-sidebar -->
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="hod")
                  {
                   include('includes/hodbar.php');
                    }elseif ($paych['position']=="subadmin") {
                        include('includes/subbar.php');
                  }elseif ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                  }else if ($paych['position']=="lecturer") {
                       ?>

                    <?php
                    include('includes/lectbar.php');
                     }//elseif ($paych['position']=="dean") {
                        
                    // }
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
                                    <h2 class="title">Semester Curriculum Setup</h2>
                                
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
                                                
                                                <section class="section">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                                   
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Semester:</label>
                                                         <div class="col-sm-10">
                                                         <select name="semesters" class="form-control clid" id="faculty" required="required">
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
                                                        <label for="default" class="col-sm-2 control-label">Level:</label>
                                                         <div class="col-sm-10">
                                                         <select name="levels" class="form-control clid" id="faculty" required="required">
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
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Programme:</label>
                                                         <div class="col-sm-10">
                                                         <?php
                                                         if ($paych['position']=="hod")
                  {?>
                                                         <select name="program" class="form-control clid"  required="required">
                                                        <option selected disabled>Please select Programme</option>
                                        
                                                        <?php
                                                        
                                                        $rest = mysqli_query($con, "SELECT * FROM program WHERE did='$deplt' ORDER BY pname");
                                                        while ($rowt = mysqli_fetch_array($rest)) {

                                                            ?>
                                                            <option value="<?php echo  $rowt["pname"]; ?>"><?php echo  $rowt["pname"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        <?php }
                                                        else
                                                        {
                                                        ?><select name="program" class="form-control clid"  required="required">
                                                        <option selected disabled>Please select Programme</option>
                                        
                                                        <?php
                                                        
                                                        $rest = mysqli_query($con, "SELECT * FROM program ORDER BY pname");
                                                        while ($rowt = mysqli_fetch_array($rest)) {

                                                            ?>
                                                            <option value="<?php echo  $rowt["pname"]; ?>"><?php echo  $rowt["pname"]; ?></option>
                                                        <?php

                                                        }}
                                                        ?>
                                                        </select>
                                                        </div>
                                                    </div>
                                                     
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="proceeds" id="submit" class="btn btn-primary">Proceed</button>
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
                            <p><?php echo htmlentities($error); ?></p>
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
        if(!empty($showModal)) {
        
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    }
       ?>
    </body>
</html>
<?php } ?>
