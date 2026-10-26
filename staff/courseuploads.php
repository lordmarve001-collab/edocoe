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
$factg=$_SESSION['faculty'];
$depng=$_SESSION['depart'];
if (isset($_POST['submitcd']))
    {
        
        $code=trim(mysqli_escape_string($con,strtoupper($_POST['coursecode'])));
        $cogr=preg_replace('/\s+/', '', $code);
        $courst=mysqli_escape_string($con,$_POST['title']);
        $ccuit=mysqli_escape_string($con,$_POST['cunit']);
        
        $sq=mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cogr'");
            if (mysqli_num_rows($sq)==1) {
            $error="Course Code ".$cogr." already Exist";
            $showModal='true';
        }else{
            $inser=mysqli_query($con,"INSERT INTO course(coursecode,title,unit,faculty,department) VALUES ('$cogr','$courst','$ccuit','$factg','$depng')");
            if ($inser) {
                mysqli_commit($con);
                //mysqli_close($con);
                header("location: courseuploads");
            }else{
                $showModal='TRUE';
                $error="Error Occured while Submitting";
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
                   <?php include('includes/hodbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Course Uploading Setup</h2>
                                
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
                                                        <label for="default" class="col-sm-2 control-label">Course Code:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" maxlength="8" name="coursecode" class="form-control clid" id="faculty" required="required">
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default"class="col-sm-2 control-label">Course Title:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" name="title" class="form-control clid"required="required" id="department">
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default"class="col-sm-2 control-label">Course Unit:</label>
                                                         <div class="col-sm-10">
                                                         <input type="number"  name="cunit" class="form-control clid" id="program" required="required">
                                                        
                                                        </div>
                                                    </div>
                                                    
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcd" id="submit" class="btn btn-primary">Submit Course Data</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                                <section>
                                                    <table id="example" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>course Code</th>
                                                            <th>course Title</th>
                                                            <th>course Unit</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <?php
                            /*$factg
                                $depng*/
                              $sql = "SELECT * FROM course  WHERE  faculty='$factg' AND department='$depng' ORDER BY coursecode";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['coursecode']) ?></td>
                                <td><?php echo htmlentities($result['title']);?></td>
                                <td><?php echo htmlentities($result['unit']);?></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }?> 
                                                    </tbody>
                                                    </table>
                                                </section>
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
        <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Course Successfully Inserted</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            include 'includes/buton.php';

        ?>

        
      
    </body>
</html>
<?php }

if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
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
    } ?>
