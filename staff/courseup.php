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
if (isset($_POST["submitpd"]))
 {
   if (empty($_POST["faculty"])) {
        $showModalEr='true';
        $error="Please Select Faculty";
    }elseif (empty($_POST["depart"])) {
        $showModalEr='true';
        $error="Please Select Department";
    }elseif (empty($_POST["programme"])) {
        $showModalEr='true';
        $error="Please Select Programme";
    }
    else{
    
        $programs=mysqli_escape_string($con,$_POST["programme"]);
        $fact=mysqli_escape_string($con,$_POST["faculty"]);
        $depn=mysqli_escape_string($con,$_POST["depart"]);

        $_SESSION['programme'] = $programs;
        $_SESSION['faculty'] = $fact;
        $_SESSION['depart'] = $depn;
        header("location: courseuploads");
        
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
        <script type="text/javascript" src="js/state.js"></script>
      <script type="text/javascript" src="js/country.js"></script>
      <style type="text/css">
          #blah{
            width:170.256px;
            height:159px;
            border:2px solid black;
            }
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
                    }
                    else{
                    ?>
                    <?php
                        include('includes/leftbar.php');
                        }
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Course Data Entry</h2>
                                
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
        <label for="default" class="col-sm-2 control-label">Faculty:</label>
         <div class="col-sm-10">
         <select name="faculty" class="form-control clid" id="faculty" onchange="selectfaculty()"  required="required">
        <option selected disabled>Please select Faculty</option>

        <?php
        $res = mysqli_query($con, "SELECT * FROM faculty ORDER BY fname");
        while ($row = mysqli_fetch_array($res)) {

            ?>
            <option value="<?php echo  $row["fid"]; ?>"><?php echo  $row["fname"]; ?></option>
        <?php

        }
        ?>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label for="default" class="col-sm-2 control-label">Department:</label>
         <div class="col-sm-10">
         <select name="depart" class="form-control clid"required="required" id="department" onchange='selectdepart()'>
        <option selected disabled>Please select Department</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label for="default"  class="col-sm-2 control-label">Programme:</label>
         <div class="col-sm-10">
         <select name="programme" class="form-control clid" id="program" required="required">
        <option selected disabled>Please select Progamme</option>
        </select>
        </div>
    </div>
    
      <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="submitpd" id="submit" class="btn btn-primary form-control">Submit Data</button>
            </div>
        </div>   
    </form>
    </section>

          <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
                    ?>                                  </div>
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
        <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
        <?php include'includes/buton.php'; ?>            
        </script>
        <script type="text/javascript">
                
                function selectfaculty()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fet?facult=" + document.getElementById("faculty").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("department").innerHTML = xmlhttp.responseText;
                }

                function selectdepart()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "feck?state=" + document.getElementById("department").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("program").innerHTML = xmlhttp.responseText;
                }


  </script>
    </body>
</html>
<?php 
if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
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
 } ?>
