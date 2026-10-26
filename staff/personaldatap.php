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
$succ=''; 
if (isset($_POST['submitpdu'])) {
    
        $firstname=mysqli_escape_string($con,$_POST["firstname"]);
        $surname=mysqli_escape_string($con,$_POST["surname"]);
        $email=mysqli_escape_string($con,strtolower($_POST["email"]));
        $phone=mysqli_escape_string($con,$_POST["phone"]);
        $prg=mysqli_escape_string($con,$_POST['programme']);
        $pd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$prg'"));
         if ($pd=='') {
           $error="Programme Selected Not Available Programme";
           $showModalEr='true';
        }else{
            $prohk=$pd['pname'];
            //$prg=mysqli_escape_string($con,$_POST['programme']);
        
        $recup=mysqli_query($con,"UPDATE applicant SET firstname='$firstname',lastname='$surname',phoneno='$phone',email='$email' WHERE applid='".$_SESSION['staff']."' ");
        /*Updating Jambapplicant Table*/
        $recups=mysqli_query($con,"UPDATE jambappli SET programme='$prohk',firstname='$firstname',lastname='$surname',phone='$phone',email='$email' WHERE applid='".$_SESSION['staff']."'");
            if ($recup) {
                $showModal='TRUE';
                $succ="Applicant Details Successfully Updated";
            }else{
                $error="Error Occured While Updating";
                $showModalEr='true';
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
        <!-- <script type="text/javascript" src="js/country.js"></script> -->
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

                   <?php
                //'subbursar' 'supbursar'

                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="sys_analyst")
                  {
                    
                    include('includes/sysbar.php');

                    }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }else if ($paych['position']=="subadmin") {
                    
                    include('includes/subbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title">Edit Applicant Data Setup</h4>
                                
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
 if ($paych['position']=="superadmin" OR $paych['position']=="subadmin" OR $paych['position']=="sys_analyst") {
  ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Application-ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" name="tellerid" autocomplete="off" required="required" placeholder="Enter Admission ID">
                                                       </div>

                                                    </div>
                                                   
                                                    
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-success">Click to Verified <i class="fa fa-check-circle"></i> </button>
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
            ?>
                                                    <?php 
                                                    if (isset($_POST['submitcor'])) {
      
                                                    $teld=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
                                                    
                                                    $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$teld' "));
                                                    if ($telcheck>0) {
                                                    $stad=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$teld'"));
                                                    $dpfd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jambappli WHERE applid='$teld' "));

                                                    ?>
                                                    <section>
            
                                                    <fieldset>
                                                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                        <?php
                                                            $_SESSION['staff']=$teld;
                                                        ?>
                                                        <div class="col-lg-6">
                                                            <div class="col-lg-12">
                                                            
                                                                <div class="form-group">
                                                                    <label for="default">Firstname:</label>
                                                                    <input type="text" class="form-control"  value="<?php  echo htmlentities($stad['firstname']) ?>" autocomplete="off" name="firstname"  maxlength="17">
                                                                    
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="date">Surname:</label>
                                                                    
                                                                    <input type="text"  class="form-control"  value="<?php echo htmlentities($stad['lastname']) ?>" autocomplete="off" name="surname"  maxlength="17">
                                                                   
                                                                </div>
                                                                 
                                                                <div class="form-group">
                                                                    <label for="date">Email:</label>
                                                                    <input type="email"  class="form-control" autocomplete="off" value="<?php echo htmlentities($stad['email']) ?>" name="email"  maxlength="137">
                                                   
                                                                   
                                                                </div>
                                                                

                                                       </div>
                                                        </div>
                                            <div class="col-lg-6">
                                                <div class="col-lg-12">
                                                                                                                        
                                                     <div class="form-group">
                                                                    <label for="date">Phone:</label>
                                                                     <input type="tel"  class="form-control"  value="<?php echo htmlentities($stad['phoneno']) ?>"   name="phone" autocomplete="off"  maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                    
                                                                  
                                                                </div>
                                                                
                                                        
    <div class="form-group">
        <label for="default">Programme</label>
         
         <select name="programme" class="form-control clid" id="program">
        <option selected="selected" value="<?php echo htmlentities($dpfd['programme'])?>"><?php echo htmlentities($dpfd['programme'])?></option>
        <?php
                                          
            
            $resz = mysqli_query($con, "SELECT * FROM programs WHERE pname!='".$dpfd['programme']."' ORDER BY pname");
            while ($row = mysqli_fetch_array($resz)) {

                ?>
                <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
            <?php

            }
          ?> 
        </select>
      
    </div>
     
                                            
                                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitpdu" id="submit" class="btn btn-primary form-control">Update Data <i class="fa fa-refresh"></i></button>
                                                        </div>
                                                    </div>
                                                            </div>
                                                        </div>
                                                         
                                                    </form>
                                                </fieldset>
                                                </section>
                                                    <?php
                                                          
                                                    }else{
                                                             $showModalEr='true';
                                                               $error='Student ID '.$teld.' Does not Exit or Check and Try Again';
                                                    }
                                                    } ?>
                                                   
                                              <!-- End of Section -->
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
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($succ); ?> </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
                            <p><?php echo $error ?>.</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           
        <!-- ========== COMMON JS FILES ========== -->
        <script type="text/javascript">
                function selectstate()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fetch.php?facult=" + document.getElementById("state").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("lga").innerHTML = xmlhttp.responseText;

                }
                function selectfaculty()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fet.php?facult=" + document.getElementById("faculty").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("department").innerHTML = xmlhttp.responseText;
                }

                function selectdepart()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "feck.php?state=" + document.getElementById("department").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("program").innerHTML = xmlhttp.responseText;
                }
  </script>
        <?php
            include 'includes/buton.php';
                   
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
<?php }

 ?>
