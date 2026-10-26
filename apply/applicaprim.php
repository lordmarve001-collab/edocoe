<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
include 'dataco.php';
$harr=$_SESSION["staffid"];

//include "process.php";
if(!isset($_SESSION['staffid']))
    {   
    header("Location:logout"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
}
$error='';
if (isset($_POST['submitfl'])) {
    if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
   }else{
if (empty($_POST['month']))
 {
    //Checking if the two droppeddown is empty
$error = "year admit cannot be empty.";
$showModalEr='true';
}elseif (empty($_POST['year'])) {
    $error = "Year graduate cannot be empty.";
    $showModalEr='true';
}else{
//tracking the posting Data
    $insti=$_POST['schoolname'];
    $yearadm=$_POST['month'];
    $yeargra=$_POST['year'];
    $degree=$_POST['certfic'];
    //Checking details Duplicate
    $sql=mysqli_query($con,"SELECT * FROM flscdetail WHERE applid='".$_SESSION['staffid']."'");
    if(mysqli_num_rows($sql)>0){
            $error="Detail Already Exist";
            $showModalEr='true';
    }

    else{
        if ($yearadm>=$yeargra) {

            $error="Graduating Year Must Be Greater Than Admit Year";
            $showModalEr='true';

        }else{
        $qu=mysqli_query($con,"INSERT INTO flscdetail(applid,schoolname,degree,yearadmit,yeargrad) VALUES ('".$_SESSION['staffid']."','$insti','$degree','$yearadm','$yeargra')") or die (mysqli_error($con));;
            if ($qu>0) {
                header("Location:appsummary");
            }else{
                $error="Error While Submiting";
                $showModalEr='true';
            }
        }
    }
    }
        
}
}
/*Update Query*/
if (isset($_POST['updateol'])) {
    if (empty($_POST['month']))
 {
    //Checking if the two droppeddown is empty
$error = "year admit cannot be empty.";
$showModalEr='true';
}elseif (empty($_POST['year'])) {
    $error = "Year graduate cannot be empty.";
    $showModalEr='true';
}else{
//tracking the posting Data
    $insti=$_POST['schoolname'];
    $yearadm=$_POST['month'];

    $yeargra=$_POST['year'];
    //echo $yeargra;
    $degree=$_POST['certfic'];
if ($yearadm>=$yeargra) {

            $error="Graduating Year Must Be Greater Than Admit Year";
            $showModalEr='true';

        }else{
        $qu=mysqli_query($con,"UPDATE flscdetail SET schoolname='$insti',degree='$degree',yearadmit='$yearadm',yeargrad='$yeargra' WHERE applid='".$_SESSION['staffid']."'");
            if ($qu>0) {
                header("Location:appsummary");
            }else{
                $error="Error While Updating";
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
      </style>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
  <?php include('include/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php include('include/leftbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Primary School Data</h2>
                                
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
                                                <?php $sed=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM flscdetail WHERE applid='".$_SESSION['staffid']."'"));
                                                if ($sed=="") {
                                                 ?>
                                                 <form class="form-horizontal" method="post" enctype="multipart/form-data">

<div class="form-group">
<label for="default" class="col-sm-2 control-label">School Name:</label>
 <div class="col-sm-10">
   <input type="text" class="form-control"  name="schoolname" required="required" maxlength="110">
 </div>
                                                    </div>
                                                    <div class="form-group">
<label for="default" class="col-sm-2 control-label">Year Entered:</label>
 <div class="col-sm-10">
 <!-- <select name="class" class="form-control clid"> -->

                                    <select name="month" class="form-control" required="required">
                                        <option selected="selected" disabled="disabled">Year Entered</option>
                                        <?php
                                        
                                        $currently_selected = date('Y');
                                        $earliest_year = 1960;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                     </div>
                     </div>
                                    <div class="form-group">
<label for="default" class="col-sm-2 control-label">Year Graduated:</label>
 <div class="col-sm-10"> 
<select name="year" class="form-control" required="required">
                                        <option selected="selected" disabled="disabled">Year Graduated</option>
                                        <?php
                                        $currently_selected = date('Y');
                                        $earliest_year = 1960;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                                        </div>
                                                    </div>

                            <div class="form-group">
                                <label for="date" class="col-sm-2 control-label">Qualification:</label>
                                <div class="col-sm-10">
                            <div  id="subject">
                    <input type="text" class="form-control"  autocomplete="off" name="certfic" readonly="readonly" value="First School Leaving Certificate">
                            </div>
                                </div>
                            </div>
                            





                                                    <!-- Sepreaaater -->
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitfl" id="submit" class="btn btn-primary">Submit Primary Details <i class="fa fa-save"></i></button>
                                                        </div>
                                                    </div>
                                                </form> 
                                             <?php }else{ ?>
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data">

<div class="form-group">
<label for="default" class="col-sm-2 control-label">School Name:</label>
 <div class="col-sm-10">
   <input type="text" class="form-control"  name="schoolname" value="<?php echo htmlentities($sed['schoolname']) ?>" required="required" maxlength="110">
 </div>
                                                    </div>
                                                    <div class="form-group">
<label for="default" class="col-sm-2 control-label">Year Entered:</label>
 <div class="col-sm-10">
 <!-- <select name="class" class="form-control clid"> -->

                                    <select name="month" class="form-control" required="required">
                                        <option selected="selected" value="<?php echo htmlentities($sed['yearadmit']) ?>"><?php echo htmlentities($sed['yearadmit']) ?></option>
                                        <?php
                                        
                                        $currently_selected = date('Y');
                                        $earliest_year = 1960;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                     </div>
                     </div>
                                    <div class="form-group">
                                    <label for="default" class="col-sm-2 control-label">Year Graduated:</label>
                                     <div class="col-sm-10"> 
                                    <select name="year" class="form-control" required="required">
                                        <option selected="selected" value="<?php echo htmlentities($sed['yeargrad']) ?>"><?php echo htmlentities($sed['yeargrad']) ?></option>
                                        <?php
                                        $currently_selected = date('Y');
                                        $earliest_year = 1960;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                                        </div>
                                                    </div>

                            <div class="form-group">
                                <label for="date" class="col-sm-2 control-label">Qualification:</label>
                                <div class="col-sm-10">
                            <div  id="subject">
                    <input type="text" class="form-control"  autocomplete="off" name="certfic" readonly="readonly" value="First School Leaving Certificate">
                            </div>
                                </div>
                            </div>
                            





                                                    <!-- Sepreaaater -->
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="updateol" id="submit" class="btn btn-primary">Update Primary Detail <i class="fa fa-refresh"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <?php } ?>
                                                

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
        </div>
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
        
       
    </body>
</html>
<?php
include 'include/buton.php';
if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    }
 } ?>
