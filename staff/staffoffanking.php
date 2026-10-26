<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout.php"); 
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
/*Query to Submit Record*/
if (isset($_POST['submitfd'])) {
    if (empty($_POST['faculty'])) {
        $error='Please Select Office Unit';
        $showModal='TRUE';
    }elseif (empty($_POST['depart'])) {
        $error='Please Select Rank';
        $showModal='TRUE';
    }else{
    
    $rankid=mysqli_escape_string($con,$_POST['faculty']);
    $rankma=mysqli_escape_string($con,$_POST['depart']);
    $pd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM ranklist WHERE rankid='$rankid'"));
        if ($pd['rankid']=='') {
           $error='Unit Selected Not Available';
           $showModalEr='true';
        }else{
            $offc=$pd['rankname'];
     $ins=mysqli_query($con,"INSERT INTO staffrank (staffid,rankname,insrdate,officename) VALUES ('".$_SESSION['staffid']."','$rankma',NOW(),'$offc')");
        if ($ins) {
            header("location: staffportal");
        }else{
            $error='Error Occured While Submitting';
            $showModal='TRUE';
        }
    }
  }
}

if (isset($_POST['submitfda'])) {
   if (empty($_POST['depart'])) {
        $error='Please Select Rank';
        $showModal='TRUE';
    }else{
   
    $rankma=mysqli_escape_string($con,$_POST['depart']);
    $pd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM unitlist WHERE unitname='$rankma'"));
        if ($pd['unitname']=='') {
           $error='Designation Selected Not Available';
           $showModalEr='true';
        }else{
            $offc=$pd['unitname'];
     $ins=mysqli_query($con,"INSERT INTO staffrank (staffid,rankname,insrdate,officename) VALUES ('".$_SESSION['staffid']."','$offc',NOW(),'Academic')");
        if ($ins) {
            header("location: staffportal");
        }else{
            $error='Error Occured While Submitting';
            $showModal='TRUE';
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
         <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
                   <?php include('includes/leftbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h3 class="title">Office Designation Data</h3>
                                
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
                                $paych=mysqli_num_rows(mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'"));
                                    if ($paych>0) {
                                        
                                    
                                    ?>
                                    <section class="section">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                                    <div class="form-group">
                                                        <label for="default"class="col-sm-2 control-label">Designation:</label>
                                                         <div class="col-sm-10">
                                                         <select name="depart" id="department" class="form-control clid"required="required">
                                                        <option selected disabled>Please select Designation</option>
                                                        <?php
                                                        $res = mysqli_query($con, "SELECT * FROM unitlist ORDER BY unitname");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["unitname"]; ?>"><?php echo  $row["unitname"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        </select>
                                                        </div>
                                                    </div>
                                                                                                                       
                                                     
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitfda" id="submit" class="btn btn-primary">Submit Data</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                <?php }

                                    else{ ?>
                                        <section class="section">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Office Unit:</label>
                                                         <div class="col-sm-10">
                                                         <select name="faculty" class="form-control clid" id="faculty" onchange="selectfaculty()"  required="required">
                                                        <option selected disabled>Please select Office</option>
                                        
                                                        <?php
                                                        $res = mysqli_query($con, "SELECT * FROM ranklist ORDER BY rankname");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["rankid"]; ?>"><?php echo  $row["rankname"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default"class="col-sm-2 control-label">Rank:</label>
                                                         <div class="col-sm-10">
                                                         <select name="depart" id="department" class="form-control clid"required="required">
                                                        <option selected disabled>Please select Rank</option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                                                                                       
                                                     
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitfd" id="submit" class="btn btn-primary">Submit Data</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                   <?php }
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
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModalsucc" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
       <script src="js/main.js"></script>
        <script type="text/javascript">
                
                function selectfaculty()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "units.php?facult=" + document.getElementById("faculty").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("department").innerHTML = xmlhttp.responseText;
                }


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