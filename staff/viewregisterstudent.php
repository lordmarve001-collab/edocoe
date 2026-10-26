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
    header("Location:../logout.php");
}
$error='';
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='exam processing'"));
$sems=$dat['semester'];
$ses=$dat['session'];
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
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/main.css" media="screen" >
      
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
  <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                  
                    <!-- /.left-sidebar -->
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="hod")
                  {
                    
                     include('includes/hodbar.php');

                    }else if ($paych['position']=="lecturer") {
                        
                    
                    include('includes/lectbar.php');
                     }elseif ($paych['position']=="dean") {
                       include 'includes/deanbar.php'; 
                    }elseif ($paych['position']=="dvc_acad") {
                       include 'includes/dvcbar.php'; 
                    }elseif ($paych['position']=="superadmin") {
                       include 'includes/supbar.php'; 
                    }elseif ($paych['position']=="facofficer") {
                       include 'includes/facbar.php'; 
                    }elseif ($paych['position']=="academicaffair") {
                       include 'includes/acdbar.php'; 
                    }elseif ($paych['position']=="accountant") {
                       include 'includes/accbar.php'; 
                    }elseif ($paych['position']=="subadmin") {
                      include('includes/subbar.php'); 
                    }elseif ($paych['position']=="sys_analyst") {
                      include('includes/sysbar.php'); 
                    }elseif ($paych['position']=="subbursar") {
                      include('includes/sububar.php'); 
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Registered Student Setup</h2>
                                
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
                     if ($paych['position']=="dean" OR $paych['position']=="lecturer" OR $paych['position']=="hod") {
                        ?>                                      
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Allocated Course:</label>
                                                         <div class="col-sm-10">
                                                         <select name="coursecode" class="form-control clid" id="faculty" required="required">
                                                        <option selected disabled>Please select Course</option>
                                        
                                                        <?php
                                                            $res = mysqli_query($con, "SELECT DISTINCT allocatecourse.coursecode,course.title FROM allocatecourse  JOIN course ON allocatecourse.coursecode=course.coursecode WHERE allocatecourse.staffid='".$_SESSION['staffid']."' ORDER BY allocatecourse.coursecode");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["coursecode"]; ?>"><?php echo  $row["coursecode"]; ?> <?php echo  $row["title"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Session:</label>
                                                         <div class="col-sm-10">
                                                         <select name="acadses" class="form-control clid" id="facultys" required="required">
                                                        <option selected disabled>Please select Session</option>
                                        
                                                        <?php
                                                            $res = mysqli_query($con, "SELECT DISTINCT  acadsession FROM allocatecourse WHERE staffid='".$_SESSION['staffid']."' ORDER BY acadsession");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["acadsession"]; ?>"><?php echo  $row["acadsession"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        </select>
                                                        </div>
                                                    </div>
                                                                                                                 
                                                     
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            
                                                            <input type="submit" name="submitfd" class="btn btn-primary" value="View Registered Student">
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                <?php 
                
                }else{
                 ?>
                 <p style="font-size:17px;text-align: justify;">Dear:<b style="color:green;"> <?php echo htmlentities(ucwords($fullname)) ?></b> You Don't Have Access to this Page Kindly Contact the System Web Manager At The ICT Office Or Meet Your Head Of Department for Course Allocation</p>
                 <?php
                }
                                                    if (isset($_POST['submitfd'])) {
                                                        if (empty($_POST['coursecode'])) {
                                                            $error="Please Select coursecode";
                                                            $showModalEr='true';
                                                            //exit();
                                                        }else if (empty($_POST['acadses'])) {
                                                            $error='Please Select Academic Session';
                                                            $showModalEr = "true";
                                                        }else{
                                                        $ccode=mysqli_escape_string($con,$_POST['coursecode']);
                                                        $acadse=mysqli_escape_string($con,$_POST['acadses']);

                                                    $qst=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM result WHERE coursecode='$ccode' AND acadsession='$acadse'"));
                                                    if ($qst!="") {
                                                        $_SESSION['coursecode']=$ccode;
                                                        $_SESSION['acadses']=$acadse;
                                                    ?>

                                                    <!-- Session to hold course information -->
                                                    <div>
                                                             <div class="brek">
                                         <div class="logos" style="width: 150px;margin: auto;">
                                            
                                            <span><img src='<?php echo $logo?>' id="images1"  style="width: 130px;height: 130px;"></span>
                                            
                                            
                                        </div>
                                        <div align="center"><span align="center" style="margin-top: 4%; font-size: 20px;font-weight: bolder;"><?php echo htmlentities($orgname);?>, <?php echo htmlentities($schadress);?></span>
                                        </div>
                                            <div>
                                               <div align="center"><h3 style="text-transform: uppercase;"> COURSE REGISTERED STUDENT'S</h3></div>
                                                <?php 
                                                    $pps=mysqli_fetch_assoc(mysqli_query($con,"SELECT title,unit FROM course WHERE coursecode='$ccode'"));
                                                    $tit=$pps['title'];
                                                    $un=$pps['unit'];

                                                    //Semester Selection
                                                    $qs=mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT semester FROM result WHERE coursecode='$ccode' "));
                                                    $mes=$qs['semester'];

                                                ?>
                                            </div>
                        <div>
                                    <strong>
                                    <p style="text-align: center;" >COURSE TITLE: <strong style="text-transform: uppercase;"><?php echo htmlentities($tit) ?></strong></p>
                                    </strong>
                                    <div style="width: 480px;margin: auto;">
                                    <strong>
                                    <p style="float:left;" >COURSE CODE:
                                     <strong style="text-transform: capitalize;"><?php echo $ccode ?></strong></p>
                                    </strong>
                                    <strong>
                                    <p style="float: right;" >COURSE UNIT:
                                     <strong style="text-transform: capitalize;"><?php echo htmlentities($un) ?></strong></p>
                                    </strong>
                                    </div>
                                    
                                    <br />
                                    <table style="margin: auto;width: 500px;">
                                      <tr>
                                        <td width="307"><strong>SESSION:&nbsp; 
                                        <?php echo htmlentities($acadse) ?></strong></td>
                                        <td width="515"><strong>SEMESTER:&nbsp;
                                            <?php echo htmlentities($mes) ?></strong></td>
                                        </tr>
                                    </table>
                        </div>
                    </div>
                                                    </div>
                                    <table class="table table-striped table-bordered cotable" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>CA</th>
                                            <th>Exam</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <?php
                                            $sql = "SELECT * FROM result WHERE coursecode='$ccode' AND acadsession='$acadse' ORDER BY studentid ";

                                            $query = mysqli_query($con,$sql);
                                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                            $cnt=1;
                                            
                                            foreach($results as $result)
                                            {   
                                                stuname($con,$result['studentid']);
                                                ?>
                                            <tr>
                                             <td class="td"><?php echo htmlentities($cnt);?></td>
                                                <td class="td"><?php echo htmlentities($result['studentid']) ?></td>
                                                <td class="td" style="text-transform: capitalize;"><?php echo htmlentities($_POST["harrd"]) ?></td>
                                                
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                 
                                            </td>
                                            </tr>
                                            <?php 

                                            $cnt=$cnt+1;
                                             
                                            }//} ?>
                                                       
                                                    
                                </tbody>
                                                        </table>
                                <div style="margin: auto;" class="button">
                                    <button type="button"  class="btn btn-primary"  onclick="window.open('printregstu','_blank')">Print-Detail <i class="fa fa-print"></i> </button>              
                                
                                    <button type="button"  class="btn btn-success" onclick="window.open('resultcsv','_blank')">Export To CSV <i class="fa fa-download"></i></button>               
                                </div>
                                
                                                    <?php
                                                }else{
                                                        $error="There Is No Result Base on the Criteria Selected";
                                                        $showModalEr='true';
                                                    }
                                                    
                                                    }

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
		<!-- Error Modal Box -->
            <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
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
            <div class="modal fade" id="checkModall" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please Select Session</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
       <?php
        include 'includes/buton.php';
		if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
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
       ?>
       
    </body>
</html>
<?php }
function stuname($con,$maxt)
 {
        $sql =mysqli_query($con,"SELECT *  FROM  applicant WHERE applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
     
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $name =$sur." ".$las." ".$first;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
               if ($data=="") {
    $sqlt =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $datat = mysqli_fetch_assoc($sqlt);
      if ($datat=="") {
        $_POST["harrd"]=" No Namess";
      }else{
        $first = $datat['firstname'];
        $sur = $datat['othername'];
        $las=$datat['lastname'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
        }
    }
    

 }

 ?>
