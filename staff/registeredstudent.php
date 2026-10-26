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
$error='';

if (isset($_POST['query'])) {
                  if (empty($_POST['progrem'])) {
                    $showModal='true';
                    $error='Programme';
                  }elseif (empty($_POST['level'])) {
                    $showModal='true';
                    $error='Level';
                  }elseif (empty($_POST['semester'])) {
                    $showModal='true';
                    $error='Semester';
                  }elseif (empty($_POST['section'])) {
                    $showModal='true';
                    $error='Section';
                  }else{
                $stud=mysqli_escape_string($con,$_POST['studentid']);
                $clvl=mysqli_escape_string($con,$_POST['level']);
                $secc=mysqli_escape_string($con,$_POST['section']);
                $sems=mysqli_escape_string($con,$_POST['semester']);
                $prgs=mysqli_escape_string($con,$_POST['progrem']);
                $daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT fid FROM program WHERE pname='$prgs' "));
                $faclt=$daft['fid'];
              $factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$faclt' "));
                  $fac = $factn['fname'];    
              
              $sql=mysqli_query($con,"SELECT * FROM studentregsem JOIN result ON studentregsem.studentid=result.studentid AND studentregsem.semester=result.semester AND studentregsem.session=result.acadsession AND studentregsem.programme=result.programme AND studentregsem.level=result.level WHERE studentregsem.studentid='$stud' AND studentregsem.programme='$prgs' AND studentregsem.level='$clvl' AND studentregsem.status='Approved' AND studentregsem.session='$secc' AND studentregsem.semester='$sems'");
                $rows = mysqli_num_rows($sql);
                if($rows > 0)
               {
                $_SESSION['stud'] = $stud;
                $_SESSION['prgs'] = $prgs;
                $_SESSION['clvl'] = $clvl;
                $_SESSION['sect'] = $secc;
                $_SESSION['sems'] = $sems;
                header("Location:printco");
                }else{
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
        <link rel="stylesheet" href="css/print.css" media="print">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <style>
            @media print{
                .form-horizontal,
                .button,
                .btn,
                .modal,
                .top-navbar-fixed .navbar,
                .left-sidebar,
                .page-title-div,
                .panel-heading,
                .btn-view,
                .no-print {
                    display: none !important;
                }
                .main-page {
                    margin: 0 !important;
                    padding: 0 !important;
                }
                .content-wrapper {
                    margin: 0 !important;
                    padding: 0 !important;
                }
                .cotable {
                    width: 100% !important;
                    border-collapse: collapse !important;
                }
                td, th {
                    padding: 8px !important;
                    border: 1px solid #000 !important;
                }
                body {
                    background: white !important;
                }
                .status-approved, .status-pending, .status-not-registered {
                    print-color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                }
            }
            
            /* Status Badge Styles */
            .status-approved {
                background-color: #28a745;
                color: white;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: bold;
                display: inline-block;
                text-align: center;
                min-width: 100px;
            }
            
            .status-pending {
                background-color: #ffc107;
                color: #856404;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: bold;
                display: inline-block;
                text-align: center;
                min-width: 100px;
            }
            
            .status-not-registered {
                background-color: #dc3545;
                color: white;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: bold;
                display: inline-block;
                text-align: center;
                min-width: 100px;
            }
            
            /* View Button Style */
            .btn-view {
                background-color: #17a2b8;
                color: white;
                padding: 5px 15px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
                font-weight: bold;
                transition: all 0.3s ease;
            }
            
            .btn-view:hover {
                background-color: #138496;
                transform: scale(1.05);
            }
            
            .btn-view:disabled {
                opacity: 0.5;
                cursor: not-allowed;
                transform: none;
            }
            
            /* Course List Styling */
            .course-list {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                max-width: 300px;
            }
            
            .course-badge {
                background-color: #e9ecef;
                color: #495057;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 500;
                white-space: nowrap;
                border-left: 3px solid #007bff;
            }
            
            .course-badge:hover {
                background-color: #dee2e6;
                transform: translateY(-1px);
            }
            
            /* Table Styling Enhancements */
            .cotable {
                width: 100%;
                border-collapse: collapse;
            }
            
            .cotable th {
                background-color: #f8f9fa;
                padding: 10px;
                text-align: left;
                font-weight: bold;
                border: 1px solid #ddd;
            }
            
            .cotable td {
                padding: 10px;
                vertical-align: middle;
                border: 1px solid #ddd;
            }
            
            .cotable tr:hover {
                background-color: #f5f5f5;
            }
            
            /* Level Header Styling */
            .level-header {
                background-color: #e3f2fd;
            }
            
            /* Print Button Styling */
            .btn-print {
                background-color: #6c757d;
                color: white;
                padding: 8px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                font-weight: bold;
                transition: all 0.3s ease;
                margin: 10px;
            }
            
            .btn-print:hover {
                background-color: #5a6268;
                transform: scale(1.05);
            }
            
            /* Print Area */
            .print-area {
                margin-top: 20px;
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

                  if ($paych['position']=="hod")
                  {
                    
                    include('includes/hodbar.php');

                    }else if ($paych['position']=="lecturer") {
                    include('includes/lectbar.php');
                     }
                     elseif ($paych['position']=="dean") {
                        include('includes/deanbar.php');
                    }elseif ($paych['position']=="superadmin") {
                        include('includes/supbar.php');
                    }
                    elseif ($paych['position']=="dvc_acad") {
                        include('includes/dvcbar.php');
                    }elseif ($paych['position']=="vc") {
                        include('includes/vcbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Registered Student</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                        <?php
                        $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                        if ($paych['position']=='hod') {
                            // code...
                        ?>
                        <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
                                                
                                                <section class="section">
                                                    <form method="POST" class="form-horizontal no-print">
                                                         <div class="form-group">
                            <label for="default" class="col-sm-2">Programme:</label>
                             <div class="col-sm-10">
                             <select name="courseallo" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Programme</option>
                              
                                <?php
                                $res = mysqli_query($con, "SELECT * FROM program WHERE did='$deplt' ORDER BY pname");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                                <?php

                                }
                                ?>
                            </select>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" class="col-sm-2">Session:</label>
                             <div class="col-sm-10">
                             <select name="section" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Session</option>
                              <?php
                                $res = mysqli_query($con, "SELECT *  FROM sections ORDER BY section DESC");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" class="col-sm-2">Semester:</label>
                             <div class="col-sm-10">
                             <select name="semester" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Semester</option>
                              <?php
                                $res = mysqli_query($con, "SELECT *  FROM semester ORDER BY semest ASC");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
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
                <div id="printArea" class="print-area">
                <?php
                    if (isset($_POST['query'])) {
                        if (empty($_POST['courseallo'])) {
                            $showModalx='true';
                            $error='Please Select Course';
                        }elseif(empty($_POST['section'])){
                            $showModalx='true';
                            $error='Please Select Session';
                        }elseif(empty($_POST['semester'])){
                            $showModalx='true';
                            $error='Please Select Semester';
                        }
                        else{
                            $ccode=mysqli_escape_string($con,$_POST['courseallo']);
                            $sect=mysqli_escape_string($con,$_POST['section']);
                            $sems=mysqli_escape_string($con,$_POST['semester']);
                            $sql=mysqli_query($con,"SELECT * FROM studentregsem WHERE semester='$sems' AND programme='$ccode' AND session='$sect'");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                            try {
                                 
                                      $_SESSION['courseallo'] = $ccode;
                                      $_SESSION['section'] = $sect;
                                      $_SESSION['semester'] = $sems;
                                      
                                            $sqlcl="SELECT DISTINCT level FROM studentregsem WHERE semester='$sems' AND session='$sect' AND programme='$ccode' ORDER BY level";
                                   
                                           $stsl = mysqli_query($con,$sqlcl);
                                                $counterl = mysqli_fetch_all($stsl,MYSQLI_ASSOC);
                                           $Acounterl=0;
                                           foreach($counterl as $rowal)
                                           {
                                             $Acounterl+=1;
                                             //$std[$Acounterl]  =$rowal['studentid'];
                                             $Lev[$Acounterl]  =$rowal['level'];
                                           }
                                            $tk=0;
                                            for($tk=1;$tk<=$Acounterl; $tk++)
                                            {
                                        ?>
                                        <div class="print-content">
                                     <div class="logos" style="width: 180px;margin: auto;">
                                            <span><img src='<?php echo $logo ?>' id="images1"  style="width: 90px;height: 90px;"></span>
                                        </div>
                                        <div style="width:350px;margin: auto;">
                                            <span align="center" style="margin-top: 1%; font-size: 15px;font-weight: bolder;"><?php echo htmlentities($orgname)?>, <?php echo htmlentities($schadress)?> </span><br>
                                            <span style="text-transform: uppercase;font-weight: bolder;margin-left:14px;">Course Registration Details</span>
                                        </div>
                                        <div>
                                                <p class=MsoNormal style='margin-left:90.35pt;'><span style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:106%;font-family:
                                                        "Times New Roman",serif;font-weight: bolder;'>Session: <?php echo htmlentities($sect) ?> <span style='mso-tab-count:2'></span>&nbsp;&nbsp;&nbsp;Programme: <?php echo htmlentities($ccode) ?>
                                                        <span style='mso-spacerun:yes'></span>&nbsp;&nbsp;&nbsp;Semester: <?php echo htmlentities($sems) ?> <o:p></o:p></span></p>
                                        </div>
                                        <table class='table cotable'  border="1" cellspacing="0" cellpadding="2">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student_ID</th>
                                                    <th>Name</th>
                                                    <th>Courses</th>
                                                    <th>Total Unit</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>  
                                                <tr class="level-header">
                                                   <th colspan="7">Level: <?php echo htmlentities($Lev[$tk]) ?> <span class="style14"></span></th>
                                                </tr>
                                                
                                                <?php 
                                                $k=0;
                                                $sql="SELECT * FROM studentregsem WHERE session='$sect' AND level='$Lev[$tk]' AND programme='$ccode' AND semester='$sems' ORDER BY level ASC";
             
                                                  $_POST['totalunit']=0;
                                                   $st = mysqli_query($con,$sql);
                                                    $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
                                               foreach($count as $row)
                                               {
                                                $k+=1;
                                                  
                                                 stuname($con,$row['studentid']);
                                                   GetUnit($con,$row['studentid'],$sems,$sect,$ccode); 
                                                ?>
                                                <tr style="font-weight: bolder;">
                                                    <td style="width:10px;"><?php echo $k ?></td>
                                                    <td><?php echo htmlentities( $row['studentid'] )?></td>
                                                    <td><?php echo htmlentities( $_POST["harrd"])?></td>
                                                    <td>
                                                        <div class="course-list">
                                                        <?php
                            $sqlc = "SELECT scr.coursecode,ca.unit FROM studentcourseregistration scr JOIN course c 
    ON scr.coursecode = c.coursecode JOIN course_allocation ca ON scr.coursecode = ca.coursecode AND ca.programme='$ccode' WHERE scr.studentid = '".$row['studentid']."'
  AND scr.semester = '$sems' AND scr.session = '$sect' ORDER BY scr.coursecode";
                                                              
                                                               $sts = mysqli_query($con, $sqlc);
                                                               $counter = mysqli_fetch_all($sts, MYSQLI_ASSOC);
                                                               $Acounter = 0;
                                                               foreach ($counter as $rowa) {
                                                                 $Acounter += 1;
                                                                  $code[$Acounter]     = $rowa['coursecode'];
                                                                 $SMSTER[$Acounter]  = $rowa['semester'];
                                                                 $un[$Acounter]= $rowa['unit'];
                                                          }
                                                      $t = 0;
                                                      for ($t = 1; $t <= $Acounter; $t++) {
                                                        
                                                        $count = 0;
                                                        //$tou+=$un;
                                                      ?>
                                                    <span class="course-badge"><?php  echo  $code[$t];?> (<?php  echo  $un[$t];?>)</span>

                                                    <?php   } if($Acounter == 0){ echo "<span style='color:#999;'>No courses registered</span>"; } ?>
                                                        </div>
                                                     </td>

                                                       <?php if ($_POST['totalUnit']=="") {   ?>
                                                         <td><?php echo $_POST['totalunit']; ?></td>   
                                                    <?php   }else{ ?>
                                                        <td><?php echo $_POST['totalUnit']; ?></td>
                                                       <?php
                                                        }
                                                       if ($row['status']==''){ ?>
                                                        <td><span class="status-not-registered">Not Registered</span></td>
                                                    <?php } elseif($row['status'] == 'Approved'){ ?>
                                                        <td><span class="status-approved">Approved</span></td>
                                                    <?php } elseif($row['status'] == 'Pending'){ ?>
                                                        <td><span class="status-pending">Pending</span></td>
                                                    <?php } else{ ?>
                                                        <td><?php echo htmlentities($row['status']) ?></td>
                                                    <?php } ?>
                                                    <td class="no-print">
                                                        <?php if ($row['status'] == 'Approved') { ?>
                                                        <form methoed="post"  method="post" enctype="multipart/form-data" target="_blank">

                                                            <input type="hidden" name="studentid" value="<?php echo $row['studentid']?>">
                                                            <input type="hidden" name="progrem" value="<?php echo $ccode?>">
                                                            <input type="hidden" name="section" value="<?php echo $sect ?>">
                                                            <input type="hidden" name="semester" value="<?php echo $sems ?>">
                                                            <input type="hidden" name="level" value="<?php echo $Lev[$tk] ?>">
                                                            <button type="submit" name="query" id="submit" class="btn-view">
                                                        View</button>
                                                                </form>
                                                        <?php } else { ?>
                                                            <button class="btn-view" disabled style="opacity:0.5; cursor:not-allowed;" title="View only available for Approved status">View</button>
                                                        <?php } ?>
                                                         </td>
                                                 </tr>
                                               <?php }?>
                                                </table>
                                                <br>
                                            <div>
                                            <!--Department Detail-->
                                            <div>
                                            
                                            <div><br><span><strong style="text-transform: capitalize;">Head Of Department: <?php echo htmlentities($fullnames);?> </strong></span><br><br><span><strong>Hod Signature:_____________________________</strong></span><br></div>
                                            
                                        </div>
                                            <!--Try to upgrade to Load the hod name out  hodname($row['coursetitle']);-->
                                            
                                        </div><br>
                                    </div>
                                            <?php }
                               
                            } catch (Exception $e) {
                                
                            }
                
                            ?>
                            <div style="margin:auto;width:150px;margin-top:5%;text-align:center;" class="button no-print">
                                            <button type="button" class="btn-print" onclick="printPage()">
                                                <i class="fa fa-print"></i> Print Details
                                            </button>               
                            </div>
                            <?php    }else{
                                            $showModals='true';
                                        }
                                    }
                                }
                            ?>
                        </section>
                        
                    </div>
                </div>
            </div>
            <!-- /.col-md-12 -->
        </div>
</div>
<?php
}elseif($paych['position']=="superadmin" OR $paych['position']=="dvc_acad" OR $paych['position']=="vc") {
    
    ?>
         <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
                                                
                                                <section class="section">
                                                    <form method="POST" class="form-horizontal no-print">
                                                         <div class="form-group">
                            <label for="default" class="col-sm-2">Programme:</label>
                             <div class="col-sm-10">
                             <select name="courseallo" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Programme</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM  programs ORDER BY pname ");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["pname"]; ?>"><?php echo htmlentities($row['pname']) ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" class="col-sm-2">Session:</label>
                             <div class="col-sm-10">
                             <select name="section" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Session</option>
                              <?php
                                $res = mysqli_query($con, "SELECT *  FROM sections ORDER BY section DESC ");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="default" class="col-sm-2">Semester:</label>
                             <div class="col-sm-10">
                             <select name="semester" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Semester</option>
                              <?php
                                $res = mysqli_query($con, "SELECT *  FROM semester ORDER BY semest ASC");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
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
                        }elseif(empty($_POST['section'])){
                            $showModalx='true';
                            $error='Please Select Session';
                        }elseif(empty($_POST['semester'])){
                            $showModalx='true';
                            $error='Please Select Semester';
                        }
                        else{
                            $ccode=mysqli_escape_string($con,$_POST['courseallo']);
                            $sect=mysqli_escape_string($con,$_POST['section']);
                            $sems=mysqli_escape_string($con,$_POST['semester']);

                            $sql=mysqli_query($con,"SELECT * FROM studentregsem WHERE semester='$sems' AND  programme='$ccode' AND session='$sect'");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                            try {
                                      $_SESSION['courseallo'] = $ccode;
                                      $_SESSION['section'] = $sect;
                                      $_SESSION['semester'] = $sems;
                                            $sqlcl="SELECT DISTINCT level FROM studentregsem WHERE  semester='$sems' AND session='$sect' AND programme='$ccode' ORDER BY level";
                                   
                                           $stsl = mysqli_query($con,$sqlcl);
                                                $counterl = mysqli_fetch_all($stsl,MYSQLI_ASSOC);
                                           $Acounterl=0;
                                           foreach($counterl as $rowal)
                                           {
                                             $Acounterl+=1;
                                             
                                             $Lev[$Acounterl]  =$rowal['level'];
                                           }
                                            $tk=0;
                                            for($tk=1;$tk<=$Acounterl; $tk++)
                                            {
                                        ?>
                                        <div class="print-content">
                                     <div class="logos" style="width: 180px;margin: auto;">
                                            <span><img src='<?php echo $logo ?>' id="images1"  style="width: 90px;height: 90px;"></span>
                                        </div>
                                        <div style="width:350px;margin: auto;">
                                            <span align="center" style="margin-top: 1%; font-size: 15px;font-weight: bolder;"><?php echo htmlentities($orgname)?>, <?php echo htmlentities($schadress)?> </span><br>
                                            <span style="text-transform: uppercase;font-weight: bolder;margin-left:14px;">Course Registration Details</span>
                                        </div>
                                        <div>
                                                <p class=MsoNormal style='margin-left:90.35pt;'><span style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:106%;font-family:
                                                        "Times New Roman",serif;font-weight: bolder;'>Session: <?php echo htmlentities($sect) ?> <span style='mso-tab-count:2'></span>&nbsp;&nbsp;&nbsp;Programme: <?php echo htmlentities($ccode) ?>
                                                        <span style='mso-spacerun:yes'></span>&nbsp;&nbsp;&nbsp;Semester: <?php echo htmlentities($sems) ?> <o:p></o:p></span></p>
                                        </div>
                                        <table class='table cotable'  border="1" cellspacing="0" cellpadding="2">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student ID</th>
                                                    <th>Name</th>
                                                    <th>Courses</th>
                                                    <th>Total Unit</th>
                                                    <th>status</th>
                                                    <th>Action</th>
                                                </tr>  
                                                <tr class="level-header">
                                                   <th colspan="7">Level: <?php echo htmlentities($Lev[$tk]) ?> <span class="style14"></span></th>
                                                </tr>
                                                
                                                <?php 
                                                $k=0;
                                                $sql="SELECT * FROM studentregsem WHERE session='$sect' AND level='$Lev[$tk]' AND programme='$ccode' AND semester='$sems' ORDER BY level ASC";
             
                                                  $_POST['totalunit']=0;
                                                   $st = mysqli_query($con,$sql);
                                                    $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
                                               foreach($count as $row)
                                               {
                                                $k+=1;
                                                  
                                                 stuname($con,$row['studentid']);
                                                   GetUnit($con,$row['studentid'],$sems,$sect,$ccode); 
                                                ?>
                                                <tr style="font-weight: bolder;">
                                                    <td style="width:10px;"><?php echo $k ?></td>
                                                    <td><?php echo htmlentities( $row['studentid'] )?></td>
                                                    <td><?php echo htmlentities( $_POST["harrd"])?></td>
                                                    <td>
                                                        <div class="course-list">
                                                        <?php
                                                   $sqlc = "SELECT scr.coursecode,ca.unit FROM studentcourseregistration scr JOIN course c 
    ON scr.coursecode = c.coursecode JOIN course_allocation ca ON scr.coursecode = ca.coursecode AND ca.programme='$ccode' WHERE scr.studentid = '".$row['studentid']."'
  AND scr.semester = '$sems' AND scr.session = '$sect' ORDER BY scr.coursecode";
  $sts = mysqli_query($con, $sqlc);
                                                               $counter = mysqli_fetch_all($sts, MYSQLI_ASSOC);
                                                               $Acounter = 0;
                                                               foreach ($counter as $rowa) {
                                                                 $Acounter += 1;
                                                                  $code[$Acounter]     = $rowa['coursecode'];
                                                                 $SMSTER[$Acounter]  = $rowa['semester'];
                                                                 $un[$Acounter]= $rowa['unit'];
                                                          }
                                                      $t = 0;
                                                      $_POST['totalunit']=0;
                                                      for ($t = 1; $t <= $Acounter; $t++) {
                                                        
                                                        $count = 0;
                                                        //$tou+=$un;
                                                      ?>
                                                    <span class="course-badge"><?php  echo  $code[$t];?> (<?php  echo  $un[$t];?>)</span>

                                                    <?php   } if($Acounter == 0){ echo "<span style='color:#999;'>No courses registered</span>"; } ?>
                                                        </div>
                                                     </td>
                                                       <?php if ($_POST['totalUnit']=="") {   ?>
                                                         <td><?php echo $_POST['totalunit']; ?></td>   
                                                    <?php   }else{ ?>
                                                        <td><?php echo $_POST['totalUnit']; ?></td>
                                                       <?php
                                                        }
                                                       if ($row['status']==''){ ?>
                                                        <td><span class="status-not-registered">Not Registered</span></td>
                                                    <?php } elseif($row['status'] == 'Approved'){ ?>
                                                        <td><span class="status-approved">Approved</span></td>
                                                    <?php } elseif($row['status'] == 'Pending'){ ?>
                                                        <td><span class="status-pending">Pending</span></td>
                                                    <?php } else{ ?>
                                                        <td><?php echo htmlentities($row['status']) ?></td>
                                                    <?php } ?>
                                                    <td class="no-print">
                                                         <?php if ($row['status'] == 'Approved') { ?>
                                                         <form methoed="post"  method="post" enctype="multipart/form-data" target="_blank">

                                                            <input type="hidden" name="studentid" value="<?php echo $row['studentid']?>">
                                                            <input type="hidden" name="progrem" value="<?php echo $ccode?>">
                                                            <input type="hidden" name="section" value="<?php echo $sect ?>">
                                                            <input type="hidden" name="semester" value="<?php echo $sems ?>">
                                                            <input type="hidden" name="level" value="<?php echo $Lev[$tk] ?>">
                                                            <button type="submit" name="query" id="submit" class="btn-view">
                                                        View</button>
                                                                </form>
                                                        <?php } else { ?>
                                                            <button class="btn-view" disabled style="opacity:0.5; cursor:not-allowed;" title="View only available for Approved status">View</button>
                                                        <?php } ?>
                                                         </td>

                                               <?php }?>
                                                </table>
                                                <br>
                                            <div>
                                            <!--Department Detail-->
                                            <div>
                                            <!--Load out Staffs Name taking Course-->
                                            
                                            <div><br><span><strong style="text-transform: capitalize;">Head Of Department:_________________________</strong></span><br><br><span><strong>Hod Signature:_____________________________</strong></span><br></div>
                                            
                                        </div>
                    
                                            
                                        </div>
                                            <!--Try to upgrade to Load the hod name out  hodname($row['coursetitle']);-->
                                            
                                        </div>
                                            
                                            
                                     <?php
                                    }
                                 ?>

                                 <?php
                            } catch (Exception $e) {
                                
                            }
                
                            ?>
                            </div>
                            <!--  -->
                            <div style="margin:auto;width:150px;text-align:center;" class="button no-print">
                                            <button type="button" class="btn-print" onclick="printPage()">
                                                <i class="fa fa-print"></i> Print Details
                                            </button>               
                            </div>
                        </div>
                            <?php    }else{
                                            $showModals='true';
                                        }
                                    }
                                }
                            ?>
                        </section>
                        
                    </div>
                </div>
            </div>
            <!-- /.col-md-12 -->
        </div>
</div>
    <?php
}else{
                                    ?>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-body panels">
                                        <div class="panel-body">
                                            <p style="font-size:17px;text-align: justify;">Dear:<b style="color:green;"> <?php echo htmlentities(ucwords($fullname)) ?></b> You Don't Have Access to this Page.
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
       
       <script type="text/javascript">
        function printPage() {
            // Store the original title
            var originalTitle = document.title;
            
            // Get the print area content
            var printContents = document.getElementById('printArea').innerHTML;
            
            // Create a popup window for printing
            var printWindow = window.open('', '_blank', 'width=1200,height=800,toolbar=yes,scrollbars=yes,resizable=yes');
            
            // Write the print content to the new window
            printWindow.document.write('<html>');
            printWindow.document.write('<head>');
            printWindow.document.write('<title><?php echo htmlentities($orgname); ?> - Course Registration Details</title>');
            
            // Copy all styles to the print window
            var styles = document.querySelectorAll('style, link[rel="stylesheet"]');
            styles.forEach(function(style) {
                if (style.tagName === 'STYLE') {
                    printWindow.document.write(style.outerHTML);
                } else if (style.tagName === 'LINK') {
                    printWindow.document.write('<link href="' + style.href + '" rel="stylesheet">');
                }
            });
            
            printWindow.document.write('</head>');
            printWindow.document.write('<body onload="window.print(); window.close();">');
            printWindow.document.write('<div class="print-wrapper">');
            printWindow.document.write(printContents);
            printWindow.document.write('</div>');
            printWindow.document.write('</body>');
            printWindow.document.write('</html>');
            
            printWindow.document.close();
        }
       </script>
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


function GetUnit($con,$mat,$sems,$ses,$pr)
{

$queryy = "SELECT SUM(ca.unit) AS sumunit FROM studentcourseregistration scr JOIN course_allocation ca ON scr.coursecode = ca.coursecode AND ca.programme='$pr'
        WHERE scr.studentid='$mat' AND scr.semester='$sems' AND scr.session='$ses' ORDER BY scr.coursecode";

$resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
              $_POST['totalUnit']=$row['sumunit'];
                
            }
}
 ?>