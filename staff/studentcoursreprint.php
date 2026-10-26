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
//$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session FROM resultsession WHERE description='Current Session'"));
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
                $clvl=mysqli_escape_string($con,$_POST['level']);
                $secc=mysqli_escape_string($con,$_POST['section']);
                $sems=mysqli_escape_string($con,$_POST['semester']);
                $prgs=mysqli_escape_string($con,$_POST['progrem']);
                $daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT fid FROM program WHERE pname='$prgs' "));
                $faclt=$daft['fid'];
              $factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$faclt' "));
                  $fac = $factn['fname'];    
              $sql=mysqli_query($con,"SELECT * FROM studentregsem JOIN result ON studentregsem.studentid=result.studentid AND studentregsem.semester=result.semester AND studentregsem.session=result.acadsession AND studentregsem.programme=result.programme AND studentregsem.level=result.level WHERE studentregsem.programme='$prgs' AND studentregsem.level='$clvl' AND studentregsem.status='Approved' AND studentregsem.session='$secc' AND studentregsem.semester='$sems'");
                $rows = mysqli_num_rows($sql);
                if($rows > 0)
               {
                $_SESSION['prgs'] = $prgs;
                $_SESSION['clvl'] = $clvl;
                $_SESSION['sect'] = $secc;
                $_SESSION['sems'] = $sems;
                header("Location:printcourse");
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
        
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/print.css" media="print" >
        <style type="text/css">
                @media print{
                  @page {
                    size: portrait;
                    @bottom-right { 
                    content: counter(page) " of " counter(page); 
                    }
                  }
                  
                  .WordSection1 td{
                    font-size: 13px;
                  }
                
                .cotable{
                        width: 700px;
                        margin:auto;
                    }

            .cotable td, .cotable th{
                border: 1px solid black;
            } 
             
          }
            @media screen{
            .lines,.list{
              display: none;
            }
            }
            .notf{
                overflow-y: scroll;
                height: auto;
                width: auto;
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
                   <?php //include('includes/hodbar.php');?>  
                    <!-- /.left-sidebar -->
                  <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="hod")
                  {
                    
                     include('includes/hodbar.php');

                    }else if ($paych['position']=="dean") {
                        
                    
                    include('includes/deanbar.php');
                     }
                     elseif ($paych['position']=="facofficer") {
                        include('includes/facbar.php');
                     }
                     elseif ($paych['position']=="superadmin") {
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
                                    <h4 class="title">Student Course Registration Printout Setup</h4>
                                
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
                    if ($paych['position']=="hod")
                  { ?>
          <section class="section secb">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Session:</label>
                <div class="col-sm-10">
                  <select name="section" class="form-control clid" id="faculty" required="required">
                    <option disabled="disabled" selected="selected">Select Session</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                        while ($row = mysqli_fetch_array($res)) {
                    ?>
                     <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Semester:</label>
                <div class="col-sm-10">
                  <select name="semester" class="form-control clid" id="faculty" required="required">
                    <option disabled="disabled" selected="selected">Select Semester</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest");
                        while ($row = mysqli_fetch_array($res)) 
                        {
                    ?>
                      <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
                      <?php
                        }
                      ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Programme:</label>
                <div class="col-sm-10">
                  <select name="progrem" class="form-control clid" required="required">
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
                <label for="default" class="col-sm-2 control-label">Level:</label>
                <div class="col-sm-10">
                  <select name="level" class="form-control clid" required="required">
                    <option selected disabled>Please select Level</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM level  ORDER BY levels");
                        while ($row = mysqli_fetch_array($res)) {
                    ?>
                      <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                    <?php
                        }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" name="query" id="submit" class="btn btn-primary">View Record</button>
                </div>
              </div>
            </form>
          </section>
        <?php }elseif($paych['position']=="superadmin"){

          ?>
           <section class="section secb">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Session:</label>
                <div class="col-sm-10">
                  <select name="section" class="form-control clid" id="faculty" required="required">
                    <option disabled="disabled" selected="selected">Select Session</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                        while ($row = mysqli_fetch_array($res)) {
                    ?>
                     <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Semester:</label>
                <div class="col-sm-10">
                  <select name="semester" class="form-control clid" id="faculty" required="required">
                    <option disabled="disabled" selected="selected">Select Semester</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest");
                        while ($row = mysqli_fetch_array($res)) 
                        {
                    ?>
                      <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
                      <?php
                        }
                      ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Programme:</label>
                <div class="col-sm-10">
                  <select name="progrem" class="form-control clid" required="required">
                    <option disabled="disabled" selected="selected">Select Programme</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM program  ORDER BY pname");
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
                <label for="default" class="col-sm-2 control-label">Level:</label>
                <div class="col-sm-10">
                  <select name="level" class="form-control clid" required="required">
                    <option selected disabled>Please select Level</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM level  ORDER BY levels");
                        while ($row = mysqli_fetch_array($res)) {
                    ?>
                      <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                    <?php
                        }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" name="query" id="submit" class="btn btn-primary">View Record</button>
                </div>
              </div>
            </form>
          </section>
          <?php 
        } else{  ?>
            <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
          <?php  
            }
        ?>
          <!-- From Here Is For HOD Side --> 
            
         
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
                            <p>Please Select <?php echo htmlentities($error) ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>There No Result Base on the Criterial Selected</p>
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
    }
 ?>
<?php
/*Fucntion to load Names of Student*/
function stuname($maxt)
{
  $connection = TRUE;
  include '../include/dbconfig.php'; //Get Database accesss

      $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'   ORDER BY studentid ASC");
      //$sql =mysqli_query($con,"SELECT *  FROM  stafflogin2 WHERE staffid='$maxt'   ORDER BY staffid ASC");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
         $_POST["mak"]="photo/avater.png";
         $_POST["maks"]="Nil";
         $_POST['emai']="Nil";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $mak=$data['image'];
        $maks=$data['studentid'];
        $_POST['emai']=$data['uniemail'];
        $_POST['pro']=$data['programme'];
        $name =$first.", ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["mak"]= $mak;
        }
}
function GetUnit($matt,$lv,$sems,$ses)
{
$connection = TRUE;
include('../include/dbconfig.php');
        $queryy="SELECT SUM(unit) AS sumunit FROM result  JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matt' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses' ORDER BY result.coursecode";
          $resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
                $_POST['totalUnit']=$row['sumunit'];       
            }
}
?>