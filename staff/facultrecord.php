<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
include "includes/getSubmit.php";
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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UNIVERSITY OF AFRICA</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <!-- <link rel="stylesheet" href="css/form.css" media="screen"> -->
        <link href="../favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/animate.min.css" media="screen" >
        <script type="text/javascript" src="js/state.js"></script>
      <script type="text/javascript" src="js/pic.js"></script>
      <script type="text/javascript" src="js/country.js"></script>
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
                   <?php include('includes/leftbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Faculty Data</h2>
                                
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
                                                        <label for="default"class="col-sm-2 control-label">Department:</label>
                                                         <div class="col-sm-10">
                                                         <select name="depart" id="department" class="form-control clid"required="required">
                                                        <option selected disabled>Please select Department</option>
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
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
       <script src="js/main.js"></script>

        
        <script>
            
        </script>
        <script type="text/javascript">
                
                function selectfaculty()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fet.php?facult=" + document.getElementById("faculty").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("department").innerHTML = xmlhttp.responseText;
                }


  </script>
    </body>
</html>
<?php } ?>
