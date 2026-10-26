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
if (isset($_POST['submitcd'])) {
      if(empty($_POST['matricno'])){
		   $showModale='true';
		   $error='Please Enter Student ID';
	  }else{
        $username=trim(mysqli_real_escape_string($con,strtoupper($_POST['matricno'])));
        $purp=mysqli_real_escape_string($con,$_POST['purposed']);
         $upd=mysqli_query($con,"UPDATE studdata SET govt='$purp' WHERE admno='$username' ");
 if ($upd) {

        $succ='Detail Successfully Updated';
        $showModall='true';

        } else {

        $error="Sorry Error Occur When Submitting";
        $showModale='true';
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
                                    <h2 class="title">Update Government Programme</h2>
                                
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
                                       <section class="section newsect">
                                           <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                              
                                                      
                                                      <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">
                                                           Student ID:
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <input type="text"  name="matricno" class="form-control clid" placeholder="Enter Student Id" required="required">
                                                        </div>
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Select Programme:</label>
                                                         <div class="col-sm-10">
															<select class="form-control clid" name="purposed">
																<option selected="selected" disabled="disabled">Select Programme</option>
																<option value="EDO STATE UNIVERSAL BASIC EDUCATION BOARD (SUBEB)">EDO STATE UNIVERSAL BASIC EDUCATION BOARD (SUBEB)</option>
																<option value="EDO STATE SECONDARY EDUCATION BOARD (SSEB)">EDO STATE SECONDARY EDUCATION BOARD (SSEB)</option>
															</select>
                                                       </div>

                                                    </div>
                                                 
                                              
                                                     <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcd" class="btn btn-primary">Update Record</button>
                                                        </div>
                                                    </div>
                                                  
                                            </form>
                                        </section>
                                        <div>
                                             <?php
                                                            }else{
                                                            ?>
                                                                <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                                                            <?php
                                                                }
                                                ?> 
                                        </div>
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
                                <label>New Password</label>
                                    <input type="password" name="newspass" id="password-field" placeholder="Enter New Password Here" class="form-control" value="" required>
                                    <i toggle="#password-field" class="fa fa-fw field-icon fa-eye toggle-password"></i>
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
            </div>
            
            
            
            <div class="modal fade" id="checkModala" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">ABA/PDE Student Data Updating
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" >×</span>
                            </button></h5>
                        </div>
						<form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
							<?php  
							$_SESSION['stud']=$username;
							$sql =mysqli_query($con,"SELECT  *  FROM  applicant WHERE applid='$username' ");
      $data = mysqli_fetch_assoc($sql);
        $first=$data['firstname'];
        $prp=$data['email'];
        $las=$data['lastname'];
        $last=$data['acadsession'];
       
    
							?>
                           <div class="form-group">
                                <label>Firstname</label>
                                <input type="text" name="frname"  class="form-control" value="<?php echo $first ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label>Lastname</label>
                                <input type="text" name="lname" class="form-control" value="<?php echo $las ?>" readonly required>
                            
                               </div>
                            
                            <div class="form-group">
                                <label>Student ID</label>
                                <input type="text" name="studid" class="form-control" value="<?php echo htmlentities($_SESSION['stud']) ?>" readonly required>
                            
                               </div>
                               
                               <div class="form-group">
                                <label>New Password</label>
                                    <input type="text" name="newspass" id="password-field" placeholder="Enter New Password Here" class="form-control" value="" required>
                                    <i toggle="#password-field" class="fa fa-fw field-icon fa-eye toggle-password"></i>
                               </div>
                            </div>
                        
                        <div style="clear:both;"></div>
                        <div class="modal-footer">
                            
                                <button name="fulclearances" class="btn btn-success" style="float:left;">
                                   Click to Update <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            
            
            
            
            
            
            
            
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
                                <label>Staff ID</label>
                                <input type="text" name="studid" class="form-control" value="<?php echo htmlentities($_SESSION['staff']) ?>" required>
                            
                               </div>

                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="newspass" id="password-field" placeholder="Enter New Password Here" class="form-control" value="" required>
                                <i toggle="#password-field" class="fa fa-fw field-icon fa-eye toggle-password"></i>
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
        <!-- ========== COMMON JS FILES ========== -->
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
    if(!empty($showModala)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModala").modal("show");
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
<script type="text/javascript">
      $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input=$($(this).attr("toggle"));
        //calling on the attribute of the password
        if (input.attr("type")=="password") {
            //Changing to Text
            input.attr("type","text");
        }else{
            input.attr("type", "password");
        }
    });
</script>
        
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
        $prp=$data['email'];
        $las=$data['lastname'];
        $last=$data['acadsession'];
        $_POST["harrd"]= $first;
        $_POST["prp"]= $prp;
        $_POST["last"]= $las;
        $_POST["las"]= $last;
        $_POST["sta"]= $data['status'];
        }

 }
 
 function stunam($con,$max)
 {
    
    $sql =mysqli_query($con,"SELECT  *  FROM  applicant WHERE applid='$max' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POSTs["harrd"]=" No Name";
        $_POSTs["prp"]= "";
      }else{
        $first=$data['firstname'];
        $prp=$data['email'];
        $las=$data['lastname'];
        $last=$data['acadsession'];
        $_POSTs["harrd"]= $first;
        $_POSTs["prp"]= $prp;
        $_POSTs["last"]= $las;
        $_POSTs["las"]= $last;
        $_POSTs["sta"]= $data['status'];
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
        $_POST["harrd"]= $first;
        $_POST["photo"]= $las;
        }

 }


?>
