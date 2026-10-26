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
        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session FROM resultsession WHERE description='Course allocation'"));
        $sems=$dat['semester'];
        $ses=$dat['session'];

        //Submit QUery to inserter Record
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
        <link rel="stylesheet" href="css/main.css" media="screen">
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
                                    <h3 class="title">Allocated Course(s) Setup</h3>
                                
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
                            $payt=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM allocatecourse WHERE acadsession='$ses' AND staffid='".$_SESSION['staffid']."'"));

                        if ($payt!='')
                        { 
                            ?>
                                                <section class="section">
                                                    <table id="example2" class="table table-striped table-bordered">
                                                    
                                                        
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Course Code</th>
                                                            <th>Course Title</th>
                                                            <th>Semester</th>
                                                            <th>Session</th>
                                                        </tr>
                                                   
                                                   
                                                       <?php
                              $sql = "SELECT allocatecourse.coursecode,allocatecourse.semester,allocatecourse.acadsession,course.title FROM allocatecourse JOIN course ON allocatecourse.coursecode=course.coursecode  WHERE  allocatecourse.staffid='".$_SESSION['staffid']."' AND allocatecourse.semester='$sems' AND allocatecourse.acadsession='$ses' ORDER BY allocatecourse.staffid";
                              
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['coursecode']) ?> </td>
                                
                                <td>
                                    <?php echo htmlentities($result['title']) ?>
                                    
                                </td>
                                <td> 
                                    <?php echo htmlentities($result['semester']) ?>
                                </td>
                                <td> 
                                    <?php echo htmlentities($result['acadsession']) ?>
                                </td>
                                <!-- <td> 
                                    
                                </td> -->
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             //include 'locate.php';
                            }//} ?> 
                                                    
                                                    </table>
                                                </section>
                            <?php 
                            }else{ ?>
            
                    <p style="font-size:17px;text-align: justify;">Dear:<b style="color:green;"> <?php echo htmlentities(ucwords($fullname)) ?></b> You Don't Have Access to this Page Kindly Contact the System Web Manager At The ICT Office Or Meet Your Head Of Department for Course Allocation</p>
                                   
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
        <?php
            include 'includes/buton.php';
        ?>
         </body>
</html>
<?php } ?>
