<?php 
session_start();
$connection = TRUE;
include "../include/dbconfig.php";
include 'dataco.php';
$harr=$_SESSION["staffid"];
if (!isset($_SESSION['staffid']))
    header("Location: ../logout");

$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
if (isset($_POST['approved'])) {
   
    $date = date('Y-M-d'); 
    $userid=$_SESSION['id'];
    $sems=$_SESSION['ssid'];
    $upda=mysqli_query($con,"UPDATE transcriptre SET reqsatus= 'Approved',dateapp='$date' WHERE id='$userid' AND studentid='$sems' ");
    if ($upda) {
    approvedmail($con,$sems);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <style>
                    
        .time{
            color:red;
            margin-right: 34px;
            font-size: 13px;
        }
        .infon{
            color:green;
        }
        
</style>
</head>
<body  class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php');?> 
        <div class="content-wrapper">
                <div class="content-container">
                    <?php
                    
                   
                        include('includes/leftbar.php');
                        
                    ?>
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title">PENDING TRANSCRIPT REQUEST</h4>
                                </div>
                                
                            </div>
                            
                            <!-- /.row -->
                        </div>
                    <section class="section">
                            <div class="container-fluid">
                             <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                        <div class="panel-body">
                    <div>
                        <table id="example" class="table table-striped table-bordered " cellspacing="0">
                            <thead>
                                
                                <tr>
                                    <th>#</th>
                                    <th>Transcript Type</th>
                                    <th>Instution Email</th>
                                    <th>Status</th>
                                    
                                </tr>
                            </thead>
                            
                            <tbody>
                            <?php

                            $sql ="SELECT * FROM transcriptre WHERE  studentid='".$_SESSION['staffid']."' ";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            { 
                                
                           ?>
                           <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                            
                            <td><?php echo htmlentities($result['offtype']);?></td>
                            <td><?php echo htmlentities($result['orgemal']);?></td>
                            <td><?php echo htmlentities($result['reqsatus']);?></td>
                            
                            </tr>
                           <?php
                           $cnt=$cnt+1;
                            }
                             //}
                            ?>
                            </tbody>
                            
                            </table>

                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-8 col-md-offset-2 -->
                                </div>
                                <!-- /.row -->

                            <!-- /.container-fluid -->
                        </section>
                    </div>
                </div>
        </div>
    </div>
    <?php include 'includes/buton.php';
    ?>
</body>
</htm>
