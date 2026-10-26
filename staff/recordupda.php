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
//for inserting Record
if (isset($_POST['submitcor'])) {
      if(empty($_POST['purposed'])){
		   $showModale='true';
		   $error='Please Select Updating Purpose';
	  }else{
        $username=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
        $purp=mysqli_real_escape_string($con,$_POST['purposed']);
          if ($purp=="student") {
              $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM studlogin where staffid ='".$username."'"));
              if ($telcheck>0) {
                $showModal='true';
                stuname($con,$username); 
              }else{
                 $showModale='true';
                $error='Student ID <b style=color:blue;>'.$username.'</b> Does not Exit Or Please Check Student ID Again <br> Or Ask The Student to Complete His/Her Bio Data in portal.edocoe.edu.ng Platform <br> Thank You';
                }
          }else{
            $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM stafflogin WHERE staffid ='".$username."'"));
              if ($telcheck>0) {
                    $showModalp='true';
                    staname($con,$username); 
              }else{
                 $showModale='true';
                $error='Staff ID <b style=color:blue;>'.$username.'</b> Does not Exit Or Please Check Staff ID Again <br> Or Ask The Staff to Complete His/Her Bio Data in portal.edocoe.edu.ng Platform <br> Thank You';
                }
          }
	  }
}

if (isset($_POST['submitcord'])) {
      
        $username=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
        
              $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM studlogin where staffid ='".$username."'"));
              if ($telcheck>0) {
                $showModald='true';
                stuname($con,$username); 
              }else{
                 $showModale='true';
                $error='Student ID <b style=color:blue;>'.$username.'</b> Does not Exit Or Please Check Student ID Again <br> Or Ask The Student to Complete His/Her Bio Data in portal.edocoe.edu.ng Platform <br> Thank You';
                }
          
    
}
/* fulclearance */
if (isset($_POST['fulclearance'])) {
$firstn=ucfirst(mysqli_real_escape_string($con,$_POST['frname']));
$lastn=ucfirst(mysqli_real_escape_string($con,$_POST['lname']));
//$unitme=trim(strtolower($firstn.".".$lastn."@student.uat.edu.ng"));
$unitme=trim(strtolower(mysqli_real_escape_string($con,$_POST['emname'])));
$studf=mysqli_real_escape_string($con,$_POST['studid']);
$acad=mysqli_real_escape_string($con,$_POST['acadsess']);
$stak=mysqli_real_escape_string($con,$_POST['statusd']);
$upd=mysqli_query($con,"UPDATE stafflogin SET firstname='$firstn',lastname='$lastn',acadsession='$acad',unemail='$unitme',staffid='$studf',status='$stak' WHERE staffid='".$_SESSION['stud']."' ");
$upds=mysqli_query($con,"UPDATE student SET uniemail='$unitme',status='$stak' WHERE studentid='".$_SESSION['stud']."' ");
 if ($upd) {

        $succ='Detail Successfully Updated';
        $showModall='true';

        } else {

        $error="Sorry Error Occur When Submitting";
        $showModale='true';
        }
}

if (isset($_POST['fulclearanced'])) {
$firstn=ucfirst(mysqli_real_escape_string($con,$_POST['frname']));
$lastn=ucfirst(mysqli_real_escape_string($con,$_POST['lname']));
$unitme=trim(strtolower(mysqli_real_escape_string($con,$_POST['emname'])));
$studf=mysqli_real_escape_string($con,$_POST['studid']);
$acad=mysqli_real_escape_string($con,$_POST['acadsess']);
$upd=mysqli_query($con,"UPDATE studlogin SET firstname='$firstn',lastname='$lastn',acadsession='$acad',staffid='$studf' WHERE staffid='".$_SESSION['stud']."' ");
$upds=mysqli_query($con,"UPDATE student SET email='$unitme' WHERE studentid='".$_SESSION['stud']."' ");
 if ($upd) {

        $succ='Detail Successfully Updated';
        $showModall='true';

        } else {

        $error="Sorry Error Occur When Submitting";
        $showModale='true';
        }
}

/* End Here */
/* For Part Clearance Start partcleared*/
if (isset($_POST['partcleared'])) {
	$firstn=ucfirst(mysqli_real_escape_string($con,$_POST['frname']));
    $lastn=ucfirst(mysqli_real_escape_string($con,$_POST['lname']));
    $unitme=trim(strtolower(mysqli_real_escape_string($con,$_POST['emname'])));
    $studf=mysqli_real_escape_string($con,$_POST['studid']);
    $upd=mysqli_query($con,"UPDATE stafflogin SET firstname='$firstn',lastname='$lastn',staffid='$studf' WHERE staffid='".$_SESSION['stud']."' ");
    $upds=mysqli_query($con,"UPDATE people SET uniemail='$unitme' WHERE staffid='".$_SESSION['stud']."' ");
    if ($upd) {

        $succ='Detail Successfully Updated';
        $showModall='true';

        } else {

        $error="Sorry Error Occur When Submitting";
        $showModale='true';
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
                 <?php
					$paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="sys_analyst")
                  {
                    
                    include('includes/sysbar.php');

                    }else if ($paych['position']=="subadmin") {
                    
                    include('includes/subbar.php');
                    }elseif ($paych['position']=="subadministrator") {
                        include('includes/ictbar.php');
                  }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Details Updating Setup</h4>
                                
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
                     if ($paych['position']=="superadmin" ) {
                      ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" autocomplete="off" name="tellerid" required="required" placeholder="Enter Student ID OR StaffID">
                                                       </div>

                                                    </div>
													<div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Updating For:</label>
                                                         <div class="col-sm-10">
															<select class="form-control clid" name="purposed">
																<option selected="selected" disabled="disabled">Please Select Who Is Updating For</option>
																<option value="student">Student</option>
																<option value="staff">Staff</option>
															</select>
                                                       </div>

                                                    </div>
                                                   
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-success">Click to Update <i class="fa fa-refresh"></i> </button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
            <?php }elseif($paych['position']=="subadmin" OR $paych['position']=="sys_analyst" OR $paych['position']=="subadministrator"){?>
             <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" autocomplete="off" name="tellerid" required="required" placeholder="Enter Student ID">
                                                       </div>

                                                    </div>
                                                    
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcord" id="submit" class="btn btn-success">Click to Update <i class="fa fa-refresh"></i> </button>
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
        

            <div class="modal fade" id="checkModall" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($succ)?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
                            <p><?php echo $error ?>.</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Full Clearance Page Start -->
		    <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Data Updating
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" >×</span>
                            </button></h5>
                        </div>
						<form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
							<?php  
							$_SESSION['stud']=$username;

							?>
                           
						  
                            
                               
                                <div class="form-group">
                                <label>Firstname</label>
                                <input type="text" name="frname"  class="form-control" value="<?php echo htmlentities($_POST["harrd"]) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Lastname</label>
                                <input type="text" name="lname" class="form-control" value="<?php echo htmlentities($_POST["last"]) ?>" required>
                            
                               </div>
                            
                            
                               
                                <div class="form-group">
                                <label>Student College Email</label>
                                <input type="text" name="emname"  class="form-control" value="<?php echo htmlentities($_POST["prp"]) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Student ID</label>
                                <input type="text" name="studid" class="form-control" value="<?php echo htmlentities($_SESSION['stud']) ?>" required>
                            
                               </div>
                               <div class="form-group">
                                <label>Admitted Year</label>
                                <select class="form-control" name="acadsess">
                                    <option selected="selected" value="<?php echo htmlentities($_POST['las']) ?>"><?php echo htmlentities($_POST['las']) ?></option>
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
                               <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="statusd">
                                    <option selected="selected" value="<?php echo htmlentities($_POST["sta"]) ?>"><?php echo htmlentities($_POST["sta"]) ?></option>
                                    <?php
                                            $res = mysqli_query($con, "SELECT * FROM statuses WHERE status!='".$_POST["sta"]."' ORDER BY status");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["status"];?>"><?php echo  $row["status"]; ?></option>
                                        <?php
                                            }
                                    ?>
                                </select>
                            
                               </div>
                            </div>
                        
                        <div style="clear:both;"></div>
                        <div class="modal-footer">
                            
                                <button name="fulclearance" class="btn btn-success" style="float:left;">
                                   Click to Update <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- Full Clearance Page Start -->
            <div class="modal fade" id="checkModald" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Data Updating
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" >×</span>
                            </button></h5>
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <?php  
                            $_SESSION['stud']=$username;

                            ?>
                           
                          
                            
                               
                                <div class="form-group">
                                <label>Firstname</label>
                                <input type="text" name="frname"  class="form-control" value="<?php echo htmlentities($_POST["harrd"]) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Lastname</label>
                                <input type="text" name="lname" class="form-control" value="<?php echo htmlentities($_POST["last"]) ?>" required>
                            
                               </div>
                            
                            
                               
                                <div class="form-group">
                                <label>College Email</label>
                                <input type="text" name="emname"  class="form-control" value="<?php echo htmlentities($_POST["prp"]) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Student ID</label>
                                <input type="text" name="studid" class="form-control" value="<?php echo htmlentities($_SESSION['stud']) ?>" required>
                            
                               </div>
                               <div class="form-group">
                                <label>Admitted Year</label>
                                <select class="form-control" name="acadsess">
                                    <option selected="selected" value="<?php echo htmlentities($_POST['las']) ?>"><?php echo htmlentities($_POST['las']) ?></option>
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
                        
                        <div style="clear:both;"></div>
                        <div class="modal-footer">
                            
                                <button name="fulclearanced" class="btn btn-success" style="float:left;">
                                   Click to Update <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Full Clearance Page End -->
            <!-- Part Clearance Page Start-->
			 <div class="modal fade" id="checkModalp" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Staff Data Updating
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </h5>
                        </div>
						<form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
						<?php 
							$_SESSION['staff']=$username;
							//echo $_SESSION['stud'];
						?>
                           
                        <div class="form-group">
                                <label>Firstname</label>
                                <input type="text" name="frname"  class="form-control" value="<?php echo htmlentities($_POST["harrd"]) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Lastname</label>
                                <input type="text" name="lname" class="form-control" value="<?php echo htmlentities($_POST["photo"]) ?>" required>
                            
                               </div>
                                <div class="form-group">
                                <label>College Email</label>
                                <input type="text" name="emname"  class="form-control" value="<?php echo htmlentities($_POST["mak"]) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Staff ID</label>
                                <input type="text" name="studid" class="form-control" value="<?php echo htmlentities($_SESSION['staff']) ?>" required>
                            
                               </div>
                               
                            </div>
                        
                        <div style="clear:both;"></div>
                        <div class="modal-footer">
                            
                                <button name="partcleared" class="btn btn-success" style="float:left;">
                                   Click to Update <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Part Clearance Page End-->
            
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
        ?>
        
        <?php           
    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    }
    if(!empty($showModald)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModald").modal("show");
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
function stuname($con,$maxt)
 {
    
    $sql =mysqli_query($con,"SELECT  *  FROM  studlogin WHERE staffid='$maxt' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first=$data['firstname'];
        $prp=$data['unemail'];
        $las=$data['lastname'];
        $last=$data['acadsession'];
        $_POST["harrd"]= $first;
        $_POST["prp"]= $prp;
        $_POST["last"]= $las;
        $_POST["las"]= $last;
        $_POST["sta"]= $data['status'];
        }

 }
 
 function staname($con,$maxtt)
 {
    
    $sql =mysqli_query($con,"SELECT  *  FROM  stafflogin WHERE staffid='$maxtt' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first = $data['firstname'];
        $las=$data['lastname'];
        $mak=$data['unemail'];
        $_POST["harrd"]= $first;
        $_POST["photo"]= $las;
        $_POST["mak"]= $mak;
        }

 }

 ?>
