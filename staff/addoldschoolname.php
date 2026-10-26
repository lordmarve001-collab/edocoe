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
        /*insname
instad*/
        $edonam=mysqli_escape_string($con,$_POST["insname"]);
        
                       $regist=mysqli_query($con,"INSERT INTO oldschool(schoolname) VALUES ('$edonam')");
                    
                if ($regist) {
				$succ='Successfully submited';
				  $showModal='true';
				  header("refresh:1;addoldschoolname"); 
				} else {
			
			$error="Sorry Error Occur When Submitting";
			$showModalEr='true';
					}
	   
				}

if (isset($_POST["submitupd"]))
 {
        /*insname
instad*/
        $firstname=mysqli_escape_string($con,$_POST["insname"]);
        $surname=mysqli_escape_string($con,$_POST["instad"]);
        $state=mysqli_escape_string($con,$_POST["state"]);
        $filename = $_FILES['passport']['name'];
        $filetmp =$_FILES['passport']['tmp_name'];
        $filesize = $_FILES['passport']['size'];
        $fileBase = basename($_FILES['passport']['name']);
        $dir = "photo/";
        $dirs = "../img/";
        $dirss = "img/";
        $final_dirs= $dirs.$fileBase;
        $final_dirss= $dirss.$fileBase;
        $final_dir= $dir.$fileBase;
        
                if(move_uploaded_file($filetmp, $final_dirs))
                {
                    
                        /*Update the Query*/
                        $upd=mysqli_query($con,"UPDATE schoolname SET schoolnames='$firstname',schadress='$surname',passport='$final_dirs',passports='$final_dirss' ");
                        if ($upd) {
                   
                            $succ='Successfully Updated';
                              $showModal='true';
                              header("refresh:1;staffportal");
                            } else {
                        
                        $error="Sorry Error Occur When Submitting";
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
        <title><?php echo "$orgname"; ?></title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <!-- <link rel="stylesheet" href="css/form.css" media="screen"> -->
         <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
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

                    
                    }else{
                        include('includes/leftbar.php');
                        }
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div >
                                    <h2 class="title">Old Institution Data Entry</h2>
                                
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
                    <label for="default" class="col-sm-2 control-label">Instituiton Name:</label>
                     <div class="col-sm-10">
                        <textarea placeholder="Enter Instituiton Details Here" name="insname" class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required"> </textarea>
                    </div>
                </div>
   
      <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="submitpd" id="submit" class="btn btn-primary form-control">Submit Data</button>
            </div>
        </div>   
    </form>
    </section>
        <section>
                                                    <table id="example" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Instituiton Name</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <?php
                              $sql = "SELECT * FROM oldschool ORDER BY schoolname";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['schoolname']) ?></td>
                                <td> 
									<button class='btn btn-primary editrecod'> <i class='fa fa-edit'></i> </button>
									<button class='btn btn-danger delrecod'><i class='fa fa-trash'></i> </button>
								</td>
                                
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }//} ?> 
                                                    </tbody>
                                                    </table>
                                                </section>    
       <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
                    ?>                                     </div>
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
            <!-- Error Modal Box -->
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
