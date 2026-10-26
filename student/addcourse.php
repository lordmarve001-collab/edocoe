<?php 
session_start();
$connection = TRUE;
include "../include/dbconfig.php";
include"includes/genSubmit.php";

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UNIVERSITY OF AFRICA</title>
        <link href="../favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/toastr/toastr.min.css" media="screen" >
        <link rel="stylesheet" href="css/icheck/skins/line/blue.css" >
        <link rel="stylesheet" href="css/icheck/skins/line/red.css" >
        <link rel="stylesheet" href="css/icheck/skins/line/green.css" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script src="js/modernizr/modernizr.min.js"></script>
      
<style>
            
        .time{
    color:red;
    margin-right: 34px;
    font-size: 13px;
}
.infon{
    color:green;
}
select{
    text-transform: uppercase;
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
<?php include('includes/leftbar.php');?>   
<div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h3 class="title"><?php echo "STUDENT ADD/DROP COURSE SETUP" ?></h3>
                                </div>
                                
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    
                                </div>
                               
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section">
                            <div class="container-fluid">

                             

                              

                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5></h5>
                                                </div>
                                            </div>
          
  
                                            <div class="panel-body">

                                                <form method="post">
                                                    
                                                 
                                                    <div class="form-group has-success">
                                                        <label for="success" class="control-label">SESSION:</label>
                                                        <div class="">
                                                        <select name="Section" class="form-control" >
                                                            <option selected="selected" disabled="disabled">SELECT SESSION</option>
                                                                 <?php
                                        $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC LIMIT 5");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                                        <?php

                                        }
                                        ?>
                                                        </select></div>
                                                    </div>
                                                    <div class="form-group has-success">
                                                        
                                                        <label for="success" class="control-label">PROGRAMME:</label>
                                                        <div class="">
                                                           <select id="program" name="programme" class="form-control">
                                                            <option selected disabled>PLEASE SELECT PROGRAMME</option>
                                                             <?php
                                        $res = mysqli_query($con, "SELECT * FROM programs ORDER BY program");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["program"]; ?>"><?php echo  $row["program"]; ?></option>
                                        <?php

                                        }
                                        ?>

                                                        </select>
                                                          
                                                        </div>
                                                            
                                                    </div>
                                                    <div class="form-group has-success">
                                                        <label for="success" class="control-label">LEVEL:</label>
                                                        <div class="">
                                                            <select name="Levelx" class="form-control"  >
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
                                                            <button type="submit" name="addcoursereg" class="btn btn-success btn-label-right btn-label btn-label-right" ID="viewres" >Proceed</button>
                                                    </div>


                                                    
                                                </form>

                                              
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-8 col-md-offset-2 -->
                                </div>
                                <!-- /.row -->

                               
                               

                            </div>
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->

                    </div>
</body>

<?php 
include"includes/buton.php";?>
</html>