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
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session FROM resultsession WHERE description='exam processing'"));
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
        <link rel="stylesheet" href="css/print.css" media="print">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <style>
            @media Print{
                .form-horizontal{
                 display:none;  
                }
                td{
                    text-transform: capitalize;
                }
            }
            @media screen{
                .lectna{
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
                                    <h4 class="title">Student Exam Attendance Printing Setup</h4>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
						<!--First Stage-->
                        <?php
                        $payt=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM allocatecourse WHERE acadsession='$ses' AND staffid='".$_SESSION['staffid']."'"));

                        if ($payt!='')
                        { 
                        ?>
                        <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           <?php 
                                           echo "<b style='font-size:17px;margin-left:16px;color:red;'>Academic Session: ".$ses." And Semester: ".$sems."</b>";
                                           ?>
                                            <div class="panel-body">
                                                
                                                <sesion class="sesion">
                                                    <form method="POST" class="form-horizontal">
                                                         <div class="form-group">
                            <label for="default" class="col-sm-2">Course Allocated:</label>
                             <div class="col-sm-10">
                             <select name="courseallo" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Course</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM allocatecourse JOIN course ON allocatecourse.coursecode=course.coursecode WHERE allocatecourse.staffid='".$_SESSION['staffid']."' AND allocatecourse.acadsession='$ses' AND allocatecourse.semester='$sems' ORDER BY allocatecourse.coursecode ");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["coursecode"]; ?>"><?php echo  $row["coursecode"]; ?> <?php echo htmlentities($row['title']) ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>                                                                                                              
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="query" class="btn btn-primary">View Student</button>
                        </div>
                    </div>                    
                </form>
                <?php
                    if (isset($_POST['query'])) {
                        if (empty($_POST['courseallo'])) {
                            $showModalx='true';
                            $error='Please Select Course';
                        }
                        else{
                            $ccode=mysqli_escape_string($con,$_POST['courseallo']);
                            $sql=mysqli_query($con,"SELECT * FROM allocatecourse WHERE coursecode='$ccode' AND acadsession='$ses' AND semester='$sems' AND  staffid='".$_SESSION['staffid']."'");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                            $sqls=mysqli_query($con,"SELECT * FROM result WHERE coursecode='$ccode' AND acadsession='$ses' AND semester='$sems'");
                          $rowst = mysqli_num_rows($sqls);
                          if ($rowst > 0) {
                              
                          
                            $_SESSION['courseallo']=$ccode;
                            try {
                                
                                 $sqlc="SELECT DISTINCT programme FROM result WHERE coursecode='$ccode' AND acadsession='$ses' AND semester='$sems' ";
                                   
                                   $sts = mysqli_query($con,$sqlc);
                                        $counter = mysqli_fetch_all($sts,MYSQLI_ASSOC);
                                   $Acounter=0;
                                   foreach($counter as $rowa)
                                   {
                                     $Acounter+=1;
                                     $programe[$Acounter]  =$rowa['programme'];
                                     
                                   }
                                   $t=0;
                                        for($t=1;$t<=$Acounter; $t++)
                                    {
                                     
                                     $count=0;?>
                                     <div class="<?php echo $programe[$t]?'page1':''?>">
                                     <div class="logos" style="width: 180px;margin: auto;">
                                            <span><img src='<?php echo $logo ?>' id="images1"  style="width: 90px;height: 90px;"></span>
                                        </div>
                                       <div style="width:400px;margin-left:106.35pt;margin: auto;">
                                            <span align="center" style="margin-top: 1%; font-size: 18px;font-weight: bolder;"><?php echo htmlentities($orgname)?>, <?php echo htmlentities($schadress)?></span><br>
                                            
                                        </div>
                                        <div style="width:350px;margin-left:106.35pt;margin: auto;">
                                            <span style="text-transform: capitalize;font-weight: bolder;"> EXAMINATION ATTENDANCE SHEET</span>
                                        </div>
                                            <div>
                                               
                                                <?php 
                                                    $pps=mysqli_fetch_assoc(mysqli_query($con,"SELECT title,unit FROM course WHERE coursecode='$ccode'"));
                                                    $tit=$pps['title'];
                                                    $un=$pps['unit'];

                                                    //Semester Selection
                                                    $qs=mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT semester FROM result WHERE coursecode='$ccode' "));
                                                    $mes=$qs['semester'];
                                                ?>
                                                <p class=MsoNormal style='margin-left:106.35pt;'><span style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:106%;font-family:
                                                        "Times New Roman",serif;font-weight: bolder;'>Session: <?php echo htmlentities($ses) ?> <span style='mso-tab-count:2'></span>&nbsp;&nbsp;&nbsp;Programme: <?php echo htmlentities($programe[$t]) ?>
                                                        <span style='mso-spacerun:yes'></span>&nbsp;&nbsp;&nbsp;Semester: <?php echo htmlentities($mes) ?> <o:p></o:p></span></p>

                                                        <p class=MsoNormal style='margin-left:106.35pt;'><b
                                                        style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;mso-bidi-font-size:
                                                        11.0pt;line-height:106%;font-family:"Times New Roman",serif'>Course</span></b><span
                                                        style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:106%;font-family:
                                                        "Times New Roman",serif'> <b style='mso-bidi-font-weight:normal'>Code</b>:
                                                        <?php echo htmlentities($ccode) ?><span style='mso-tab-count:3'></span>&nbsp;&nbsp;&nbsp;<b
                                                        style='mso-bidi-font-weight:normal'>Unit</b>: <?php echo htmlentities($un) ?> <span style='mso-tab-count:
                                                        2'></span>&nbsp;&nbsp;&nbsp;<span style='mso-spacerun:yes'></span><b
                                                        style='mso-bidi-font-weight:normal'>Course Title</b>: <?php echo htmlentities($tit) ?> <o:p></o:p></span></p>
                                            </div>
                                            <table class='table cotable'  border="1" cellspacing="0" cellpadding="5">
                                                
                                                <tr style="text-align:center;padding:5px;">
                                                    <th>#</th>
                                                    <th>StudentID</th>
                                                    <th>Name</th>
                                                    <th>Booklet No</th>
                                                    <th>Sign-In</th>
                                                    <th>Sign-Out</th>
                                                    <th>Date</th>
                                                    
                                                </tr>
                                            <?php 
                                            $sqlcl="SELECT DISTINCT level FROM result WHERE coursecode='$ccode' AND acadsession='$ses' AND semester='$sems' AND programme='$programe[$t]' ORDER BY level";
                                   
                                           $stsl = mysqli_query($con,$sqlcl);
                                                $counterl = mysqli_fetch_all($stsl,MYSQLI_ASSOC);
                                           $Acounterl=0;
                                           foreach($counterl as $rowal)
                                           {
                                             $Acounterl+=1;
                                             //$programe[$Acounterl]  =$rowa['programme'];
                                             $Lev[$Acounterl]  =$rowal['level'];
                                           }
                                            $tk=0;
                                            for($tk=1;$tk<=$Acounterl; $tk++)
                                            {
                                        ?>  
                                            
                                                <tr style="font-weight: bold;">
               
                                                   <th width="100">Level: <?php echo htmlentities($Lev[$tk]) ?> <span class="style14"></span></th>
                                                </tr>
                                                <?php 
                                                $k=0;
                                                $sql="SELECT * FROM result WHERE acadsession='$ses' AND coursecode='$ccode' AND level='$Lev[$tk]' AND programme='$programe[$t]' AND semester='$sems' ORDER BY level ASC";
             
                                                  $_POST['totalunit']=0;
                                                   $st = mysqli_query($con,$sql);
                                                    $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
                                               foreach($count as $row)
                                               {
                                                $k+=1;
                                                  
                                                 stuname($con,$row['studentid']);
                                                
                                                ?>
                                                <tr style="font-weight: bolder;">
                                                    <td style="width:10px;"><?php echo $k ?></td>
                                                    <td><?php echo $row['studentid'] ?></td>
                                                    <td><?php echo $_POST["harrd"]?></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    
                                                </tr>
                                               <?php }
                                            }?>
                                            </table>
                                            <br>
                                            <div>
                                            
                                                                                        
                                        </div>
                                            </div>
                                            
                                     <?php
                                    }
                                 
                            } catch (Exception $e) {
                                
                            }
                
                            ?>
                            <div style="margin:auto;width:50px;margin-top:5%;" class="button">
                                            <input type="button" value="Print-Detail" class="btn btn-primary" onclick="window.open('examatt','_blank')" style="float: left;font-size: 20px;display: non;">               
                            </div>
                            <?php 
                                    }else{
                                        $showModals='true';
                                    }  
                                }else{
                                            $showModals='true';
                                        }
                                    }
                                }
                            ?>
                        </sesion>
                        
                    </div>
                </div>
            </div>
            <!-- /.col-md-12 -->
        </div>
</div>
<!--next Query Should start From Here-->
<?php
    }
    else{
        ?>
        <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
         <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You Or Meet Your Head Of Department for Course Allocation To Enable You Print Exam Attendance</p>
         </div>
                                            
                                        </div>
                                            </div>
        <?php
    }

 ?>
</div>
<!-- /.content-container -->
</div>
<!-- /.content-wrapper -->
</div>
<div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Sorry There is no Result Base Your Criteria Selected</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModalx" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
       <?php
        include 'includes/buton.php';
        if(!empty($showModals)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModals").modal("show");
            });
        </script>';
    }
    if(!empty($showModalx)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalx").modal("show");
            });
        </script>';
    }
       ?>
    </body>
</html>


<?php }


 function stuname($con,$maxt)
 {
   
    $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =$las." ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }

 }
 ?>
