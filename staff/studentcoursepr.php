<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
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
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/animate.min.css" media="screen">
        <link rel="stylesheet" href="css/print.css" media="print">
        <style type="text/css">  
          #blah{
            width:170.256px;
            height:159px;
            border:2px solid black;
        }
        .td{
             cursor: not-allowed;
             font-weight: bolder;
        }
        /*.ds{
            border:1px solid black;
        }*/
        @media screen{
            logos,logoz{
                display: none;
            }
            .fac{
                display: none;
            }
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
                   <?php //include('includes/acdbar.php');?>
                    <!-- /.left-sidebar -->
					<?php
						$paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
						if ($paych['position']=="academicaffair") 
						{
							 include('includes/acdbar.php');
						}elseif ($paych['position']=="subadministrator") {
                        include('includes/ictbar.php');
                  }elseif ($paych['position']=="superadmin") 
						{
							 include('includes/supbar.php');
						}elseif ($paych['position']=="sys_analyst")
                         {
                    
                        include('includes/sysbar.php');

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
                                <div>
                                    <h2 class="title"> Full Student Courses Registered</h2>
                                
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
                     if ($paych['position']=="superadmin" OR $paych['position']=="subadmin" OR $paych['position']=="sys_analyst" OR $paych['position']=="subadministrator") {
                       
                    
                     ?>         
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student Matric No:</label>
                                                         <div class="col-sm-10">
                                                         <input  name="studentid" class="form-control clid" required="required" autocomplete="off">
                                                        
                                                        <span style="color: red;">
                                                       
                                                   </span>
                                                        </div>

                                                    </div>
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-primary">Submit Data</button>
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
                                                    <!-- Session to hold course information -->
                                                <?php
                                                //Here is the Implementation level of the trancript Generating Stage
                                                    if (isset($_POST['submitcor'])) {

                                                        $matrics=mysqli_escape_string($con,$_POST['studentid']);
                                                        $_SESSION['studentid']=$matrics;
                                                        $sql=mysqli_query($con,"SELECT * FROM result WHERE studentid='$matrics' AND status='Y'  AND publish='1'");
                                                        $rows = mysqli_num_rows($sql);
                                                        if($rows > 0){
                                                ?>
                                                    <div>
                                                             <div class="brek">
                                         <div class="logos" style="width: 150px;margin: auto;">
                                            
                                            <span><img src='<?php echo $logo?>' id="images1"  style="width: 130px;height: 130px;"></span>
                                            
                                            
                                        </div>
                                            <div align="center"><span align="center" style="margin-top: 4%; font-size: 20px;font-weight: bolder;"><?php echo htmlentities($orgname)?>, <?php echo htmlentities($schadress)?></span>
                                                <br>
                                                <p style="font-weight: bolder;">Student Registered Courses</p>
                                            </div>
                                            <?php 
                                                student($matrics,$con);
                                             ?>
                                            <div>
                                                
                                                <strong>Matric Number : <?php echo htmlentities($matrics); ?></strong><br>
                                                <strong style="text-transform: capitalize;">Student's Name : <?php echo htmlentities($_POST["harrd"]); ?></strong><br>
                                               
                                                <strong>Programme : <?php echo htmlentities($_POST["pro"])?><br> <?php echo htmlentities("Faculty: ".$_POST["maks"])?> <br> <?php echo htmlentities("Department: ".$_POST["det"])?> </strong>
                                                
                                            </div>
                                            <br>
                                            <!-- Result Details Information -->
                                              <table width="754" style="text-transform: capitalize;" class="table table-striped table-bordered cotabl" cellspacing="0">
                                               <?php 
                                              try
                                               {
                                                  
                                                   $counter=0;
                                                     $sqlc="SELECT DISTINCT acadsession,semester,level FROM result
                                                     WHERE studentid='$matrics'";
                                                       
                                                       $sts = mysqli_query($con,$sqlc);
                                                            $counter = mysqli_fetch_all($sts,MYSQLI_ASSOC);
                                                       $Acounter=0;
                                                   foreach($counter as $rowa)
                                                   {
                                                     $Acounter+=1;
                                                     $ACA[$Acounter]     =$rowa['acadsession'];
                                                     $SMSTER[$Acounter]  =$rowa['semester'];
                                                     $Lev[$Acounter]  =$rowa['level'];
                                                   
                                                   }
                                                  
                                                   $t=0;
                                                for($t=1;$t<=$Acounter; $t++)
                                                 {
                                                 $k=0;
                                                 
                                                 ?>
      
         <tr style="font-weight: bold;">
               <td width="20" colspan="2">Session: <span class="style14">   <?php echo  $ACA[$t] ?> </span></td>
               <td width="80">Level: <span class="style14">   <?php echo  $Lev[$t] ?> </span></td>
               <td width="80" colspan="4">Semester: <span class="style14">   <?php echo  $SMSTER[$t] ?> </span></td>
               
         </tr>
         
         <tr style="text-align:left;">
            <th>#</th>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Units</th>
            <th>Status</th>
            </tr>
     <?php
         $sql="SELECT * FROM result  JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matrics' AND result.semester='$SMSTER[$t]' AND result.acadsession='$ACA[$t]' ORDER BY result.coursecode";
             
              $_POST['totalunit']=0;
              $_POST['tupt']=0;
              $_POST['stupt']=0;
               $st = mysqli_query($con,$sql);
                $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
           foreach($count as $row)
           {
            $k+=1;
              $_POST['totalunit']+=$row['unit'];
            
            ?>
            <tr>
                <!--<td></td>-->
            <td width="50"><?php echo $k ?></td>
            <td width="50" ><?php echo $row['coursecode'] ?></td>
            <td width="200"><?php echo $row['title'] ?></td>
            <td  width="30"><?php echo $row['unit'] ?></td>             
            <td width="30"><?php echo substr($row["custatus"], 0, 1) ?></td>           
          </tr>
          <?php
               
          }
            
          ?>
           <tr>
                
                <td colspan="3"><strong style="float: right;">Unit Registered</strong></td>
                <td><strong><?php echo $_POST['totalunit']; ?></strong></td>
                <td></td>
            </tr>     
       
          <?php
       }

     
      }
    catch(PDOException $e)
        {
          echo $e->getMessage();
        }
    ?>
</table>

  </form>

                                            <div style="margin: auto;" class="button">
                                    <input type="button"  class="btn btn-success" value="Print-Detail" onclick="window.open('printstc','_blank')">               
                                </div>
                                        </div>
                                                <?php
                                                 }
                                                 else{
                                                  $error= "There is no result base on the criterial!!";
                                                  $showerro='true';
                                                }}
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
        <!-- ========== COMMON JS FILES ========== -->
       <?php
        include 'includes/buton.php';
        if(!empty($showerro)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    }
       ?>
       
    </body>
</html>

<?php } 
 
function student($mat,$con){
$sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$mat'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["maks"]="";
        $_POST["det"]="";
        $_POST["pro"]="";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $facu=$data['faculty'];
        $def=$data['department'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $progr=$data['programme'];
        $name =$las." , ".$first." ".$sur;
        $dep=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='$def'"));
        $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$facu'"));
        $_POST["harrd"]= $name;
        $_POST["maks"]= $fac['fname'];
        $_POST["det"]= $dep['dept'];
        $_POST["pro"]= $progr;
        }
}   
?>
