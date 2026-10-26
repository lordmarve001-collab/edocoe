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
            /*.notf{
                overflow-y: scroll;
                height: 500px;
                width: 800px;
            }*/
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

                    }else if ($paych['position']=="dean") {
                        
                    
                    include('includes/deanbar.php');
                     }
                     elseif ($paych['position']=="facofficer") {
                        include('includes/facbar.php');
                     }elseif ($paych['position']=="superadmin") {
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
                                    <h3 class="title">Individual Exam Permit Print Setup</h3>
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
          $payc=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
            if ($payc['position']=="hod")
                  { 
          ?>
            <section class="section secb">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">StudentID:</label>
                <div class="col-sm-10">
                  <input type="text" name="studentid" required="required" autocomplete="off" placeholder="Enter StudentID Here" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Session:</label>
                <div class="col-sm-10">
                  <select name="section" class="form-control clid" id="faculty" required="required">
                    <option disabled="disabled" selected="selected">Select Session</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC LIMIT 5");
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
          <?php }elseif($payc['position']=="dean" OR $payc['position']=="facofficer"){ ?>
            <section class="section secb">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">StudentID:</label>
                <div class="col-sm-10">
                  <input type="text" name="studentid" required="required" autocomplete="off" placeholder="Enter StudentID Here" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Session:</label>
                <div class="col-sm-10">
                  <select name="section" class="form-control clid" id="faculty" required="required">
                    <option disabled="disabled" selected="selected">Select Session</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC LIMIT 5");
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
                      $res = mysqli_query($con, "SELECT * FROM programs WHERE fid='$faclt' ORDER BY pname");
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
             }elseif($payc['position']=="superadmin"){
              ?>
              <section class="section secb">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">StudentID:</label>
                <div class="col-sm-10">
                  <input type="text" name="studentid" required="required" autocomplete="off" placeholder="Enter StudentID Here" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="default" class="col-sm-2 control-label">Session:</label>
                <div class="col-sm-10">
                  <select name="section" class="form-control clid" id="faculty" required="required">
                    <option disabled="disabled" selected="selected">Select Session</option>
                    <?php
                      $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC LIMIT 5");
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
                      $res = mysqli_query($con, "SELECT * FROM programs ORDER BY pname");
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
             }else{
              ?>
              <p style="font-size:17px;text-align: justify;">Dear:<b style="color:green;"> <?php echo htmlentities(ucwords($fullname)) ?></b> You Don't Have Access to this Page.
              </p>
              <?php
             }
           ?>
          
          <!-- From Here Is For HOD Side --> 
             <?php
                if (isset($_POST['query'])) {
                  if (empty($_POST['progrem'])) {
                    $showModal='true';
                    $error='Programme';
                  }elseif (empty($_POST['level'])) {
                    $showModal='true';
                    $error='Level';
                  }else{
                $stud=mysqli_escape_string($con,$_POST['studentid']);
                $clvl=mysqli_escape_string($con,$_POST['level']);
                $secc=mysqli_escape_string($con,$_POST['section']);
                $sems=mysqli_escape_string($con,$_POST['semester']);
                $prgs=mysqli_escape_string($con,$_POST['progrem']);

                $_SESSION['studentid']=$stud;
                $_SESSION['level']=$clvl;
                $_SESSION['section']=$secc;
                $_SESSION['semester']=$sems;
                $_SESSION['progrem']=$prgs;

                $mater=$sems ." ".$secc;
              $sql=mysqli_query($con,"SELECT * FROM studentregsem JOIN result ON studentregsem.studentid=result.studentid AND studentregsem.semester=result.semester AND studentregsem.session=result.acadsession AND studentregsem.programme=result.programme AND studentregsem.level=result.level WHERE studentregsem.programme='$prgs' AND studentregsem.level='$clvl' AND studentregsem.status='Approved' AND studentregsem.session='$secc' AND studentregsem.semester='$sems' AND studentregsem.studentid='$stud' ");
                $rows = mysqli_num_rows($sql);
                if($rows > 0)
               {
             ?> 
             
                                    <br>
             <section class="section">
                    <div class="container-fluid">
                            
                            <div class="row breadcrumb-div">
                                <div style="width: 700px; margin: auto;">
                                  <div class="notf">
                                  <?php
                                    try {
                                      $counter=0;

                                      $sqlc="SELECT * FROM studentregsem WHERE programme='$prgs' AND level='$clvl' AND status='Approved' AND session='$secc' AND semester='$sems' AND studentid='$stud' ORDER BY studentid";
                                      $sts = mysqli_query($con,$sqlc);
                                      $counter = mysqli_fetch_all($sts,MYSQLI_ASSOC);
                                      $Acounter=0;
                                      $d=0;
                                     foreach($counter as $rowa)
                                     {
                                       $Acounter+=1;
                                       $ACA[$Acounter]     =$rowa['session'];
                                     $MATR[$Acounter]  =$rowa['studentid'];
                                     $Lev[$Acounter]  =$rowa['level'];
                                    
                                     }
     
                                      $t=0;
                                    for($t=1;$t<=$Acounter; $t++)
                                     {
                                      $d+=1;
                                     $sqlv="SELECT DISTINCT studentid FROM studentregsem WHERE programme='$prgs' AND level='$clvl' AND status='Approved' AND session='$secc' AND semester='$sems' AND studentid='".$MATR[$t]."' ORDER BY studentid";

                                        $_POST['totalunit']=0;
                                         $stv = mysqli_query($con,$sqlv);
                                              $countv = mysqli_fetch_all($stv,MYSQLI_ASSOC);
                                              
                                     foreach($countv as $rowv)
                                     {
                                      
                                          stuname($rowv['studentid']);
                                      ?>
                                      <div class="<?php echo $MATR[$t]?'page':''?>">
                                      <table cellpadding=0 cellspacing=0  class="datas">
                      
                                      <tr>
                                       
                                        <td rowspan=2 align=left valign=top><img width=130 height=130
                                        src="<?php echo $logo ?>" alt="Institution Photo"></td>
                                       </tr>

                                      <tr>
                      
                                      <td style="font-weight: bolder;text-align: center;">
                                      <span style="font-size: 20.5px;text-align: center;width: 150px;"><?php echo htmlentities($orgname.' , '.$schadress) ?> &#13;&#10;</span>
                                      <br>
                                      <span style="font-size: 15.5px;text-align: center;width: 150px;">Exam Permit &#13;&#10;</span>
                                      <br>
                                      <span style="font-size: 15.5px;text-align: center;width: 150px;"> <?php echo htmlentities($mater)?> Academic Session &#13;&#10;</span>
                                      
                                      </td>
                                    </tr>

                                   </table>
                                      <p class="lines" style="width: 700px;">_______________________________________________________________________________________</p>
                                      
                                      <table style="width: 700px;">
                                        <tr>
                                          <td>Student Name: <b style='text-transform: uppercase;'><?php echo htmlentities($_POST["harrd"]) ?></td>
                                          <td rowspan="4">
                                            <img width=150 height=150
                                      src="../student/<?php echo  $_POST["mak"] ?>" alt=" " style="float:right;width:150px; height: 150px; max-width:150px;border: 2px solid white;">
                                          </td>
                                        </tr>
                                        <tr>
                                        <td>
                                          <span class=MsoNormal>StudentID: <b><?php echo htmlentities($_POST["maks"]); ?></b></span></td>
                                          <td></td>
                                      </tr>
                                       <tr>
                                        <td>
                                          <span class=MsoNormal>Email: <b><?php echo htmlentities($_POST['emai']) ?></b></span></td><td></td>
                                           </tr>
                                       <tr><td>
                                        <span class=MsoNormal>Faculty: <b><?php echo htmlentities($fac) ?> </b></span></td>
                                        <td></td>
                                      </tr>
                                       <tr><td>
                                        <span class=MsoNormal>Programme: <b><?php echo htmlentities($prgs) ?></b></span></td>
                                        <td></td>
                                      
                                    </tr>
                                       <tr>
                                        <td>
                                        <span class=MsoNormal>Level: <b><?php echo htmlentities($clvl); ?></b></span> </td>
                                        <td></td>
                                       </tr>
                                     </table>
                                      <table class="cotable" border=1 cellspacing=0 cellpadding=5>
                                        <tr>
                                            <th>#</th>
                                            <th style="width: 130px">Course Code</th>
                                            <th>Course Title</th>
                                            
                                        </tr>
                                        <?php 
                                        $que= mysqli_query($con,"SELECT * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='".$MATR[$t]."' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$secc'  ORDER BY result.coursecode");
                                         $sr =1;
                                        while ($rows= mysqli_fetch_array($que)): ?>
                                        <tr>
                                            <td><?php echo $sr;?></td>
                                            <td><?php echo $rows["coursecode"];?></td>
                                            <td><?php echo $rows["title"];?></td>
                                            
                                        </tr>
                                        <?php 
                                        $sr++;
                                      endwhile;
                                    
                                    ?>
                
            
                                       </table>
                                       <p class=MsoNormal>&nbsp;</p>
                                         <div class="signt">
                                        <!-- <p class=MsoNorma>&nbsp;</p> -->
                                        <p class='MsoNorma'>
                                        <b>This is to Certify that <i style="text-transform: capitalize;"><?php echo htmlentities($_POST['harrd']) ?></i> is duly registered for the courses listed above. Kindly admit.</b></p>

                                        <p class='MsoNorma'>__________________________<br>
                                        <b>Hod’s Signature &amp; Date</b></p>
                                        </div>
                                        <div class="pageFooter"> </div>
                                        </div>
                                      <?php
                                     } 
                                   }
                                    } catch (Exception $e) {
                                      
                                    }
                                  ?>

                                  <?php 
                                    try {
                                      ?>
                                      <div class="list">
                                         <table cellpadding=0 cellspacing=0  class="datas">
                      
                                      <tr>
                                       
                                        <td rowspan=2 align=left valign=top><img width=130 height=130
                                        src="<?php echo $logo ?>" alt="Institution Photo"></td>
                                       </tr>

                                      <tr>
                      
                                      <td style="font-weight: bolder;text-align: center;">
                                      <span style="font-size: 20.5px;text-align: center;width: 150px;"><?php echo htmlentities($orgname.' , '.$schadress); ?> &#13;&#10;</span>
                                      <br>
                                      <span style="font-size: 15.5px;text-align: center;width: 150px;">Exam Permit &#13;&#10;</span>
                                      <br>
                                      <span style="font-size: 15.5px;text-align: center;width: 150px;"> <?php echo htmlentities($mater)?> Academic Session &#13;&#10;</span>
                                      
                                      <br>                                    
                                        <span style="font-size: 15.5px;text-align: center;width: 150px;">PROGRAMME <?php echo htmlentities($prgs)?> &#13;&#10;</span>
                                      <br>
                                      <span style="font-size: 15.5px;text-align: center;width: 150px;">LEVEL <?php echo htmlentities($clvl)?>&#13;&#10;</span>
                                       
                                      </td>
                                    </tr>
                                   </table>

                                   <table class="cotable" border=1 cellspacing=0 cellpadding=2>
                                     <tr>
                                       <th>#</th>
                                       <th>Student ID</th>
                                       <th>Student Name</th>
                                       <th>Signature</th>
                                       <th>Date</th>
                                     </tr>
                                     <?php 
                                            $sqlf="SELECT DISTINCT studentid FROM studentregsem WHERE session='$ses' AND semester='$sems' AND level='$clvl' AND programme='$prgs' AND status='Approved' AND studentid='$stud' ORDER BY studentid ASC";
                                            
                                           $stvx = mysqli_query($con,$sqlf);
                                              $countv = mysqli_fetch_all($stvx,MYSQLI_ASSOC);
                                            $dd=0;
                                           foreach($countv as $rowy)
                                         {
                                          $dd+=1;
                                          stuname($rowy['studentid']);
                                       ?>
                                     <tr>
                                       <td><?php echo htmlentities($dd)?></td>
                                       <td><?php echo htmlentities($rowy['studentid']); ?></td>
                                       <td><strong><?php echo htmlentities(strtoupper($_POST["harrd"])); ?></strong></td>
                                       <td></td>
                                       <td></td>
                                     </tr>
                                     <?php
                                    }?>
                                   </table>
                                 </div>
                                      <?php
                                    
                                    } catch (Exception $e) {
                                      
                                    }
                                  ?>
                                </div>
                             <div style="margin:auto;width:50px;" class="button">
                                        <input type="button" class="btn btn-success" value="Print-Detail" onclick="window.open('printie','_blank')" style="float: left;font-size: 20px;display: non;">               
                                    </div> 
                                  
                               </div>  
                            </div>
                    </div>
              </section>
             <?php 
              }else{
                $showModale='true';
              }
            }
              }?> 
              
         
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
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
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
                <div class="modal-dialog modal-dialog-scrollable" role="document">
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

     
      $sql =mysqli_query($con,"SELECT *  FROM  applicant WHERE applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
     
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $mak=$data['image'];
        $maks=$data['applid'];
        //$maks=$data['studentid'];
        $_POST['emai']=$data['email'];
        $_POST['pro']=$data['programme'];
        $name =$las." ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["mak"]= $mak;
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