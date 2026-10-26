<?php
session_start();
ob_start();
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
                  @page {size: landscape}
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

                  if ($paych['position']=="lecturer")
                  {
                    
                    include('includes/lectbar.php');

                    }else if ($paych['position']=="dean") {
                        
                    
                    include('includes/deanbar.php');
                     }
                     elseif ($paych['position']=="facofficer") {
                        include('includes/facbar.php');
                     }elseif ($paych['position']=="superadmin") {
                        include('includes/supbar.php');
                     }
                     elseif ($paych['position']=="academicaffair") {
                        include('includes/acdbar.php');
                     }
                    else{
                        include('includes/leftbar.php');
                        }
                    ?>
        <div class="main-page">

          <div class="container-fluid">
              <div class="row page-title-div">
                <div>
                  <h2 class="title">Senate Summary Setup</h2>
                                
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
          
                          <!-- Contain Table -->
          <?php
              $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                if ($paych['position']=="lecturer")
                { 
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
                      <?php

                        }
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
                          while ($row = mysqli_fetch_array($res)) {

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
                   <select name="program" class="form-control clid" required="required">
                    <option disabled="disabled" selected="selected">Select Programme</option>
                    <?php
                  $res = mysqli_query($con, "SELECT * FROM programs WHERE did='$deplt' ORDER BY pname");
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
                    <select name="level" class="form-control clid" id="faculty" required="required">
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
                                                                                                          
                                                     
                                    <!-- Sepreaaater -->
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" name="query" id="submit" class="btn btn-primary">View Result</button>
                </div>
              </div>
                                                           
                                                         
            </form>
        </section>


        <?php 
                          $error='';
                          $succ='';
                      if (isset($_POST['query'])) {
                        if (empty($_POST['section'])) {
                         $showModal = "true";
                         $error="Session";
                        }elseif (empty($_POST['semester'])) {
                          $showModal = "true";
                          $error="Semester";
                        }elseif (empty($_POST['program'])) {
                          $showModal = "true";
                          $error="Programme";
                        }elseif (empty($_POST['level'])) {
                          $showModal = "true";
                          $error="Level";
                        }
                        else{
                          $clvl=mysqli_escape_string($con,$_POST['level']);
                          $ses=mysqli_escape_string($con,$_POST['section']);
                          $sems=mysqli_escape_string($con,$_POST['semester']);
                          $prgs=mysqli_escape_string($con,$_POST['program']);

                          //Sessional Variables 
                          $_SESSION['level']=$clvl;
                          $_SESSION['section']=$ses;
                          $_SESSION['semester']=$sems;
                          $_SESSION['program']=$prgs;
                          /*An Implementation will be done here to check if the Dean Have Not Approved or received the Notification*/
                          $que=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM resuappre WHERE acadsession='$ses' AND semester='$sems' AND programme='$prgs'"));
                          if($que==''){
                            $showModals = "true";
                              $error="Result Request Not Made To Dean Yet";
                          }elseif ($que['status']=='Pending') {
                              $showModals = "true";
                              $error="Result Is Not Yet Approved By Dean Kindly Meet Dean For Approval Reminder";
                          }
                          else{

                          $sql=mysqli_query($con,"SELECT * FROM result WHERE programme='$prgs' AND level='$clvl' AND acadsession='$ses' AND semester='$sems' ");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {

                          ?>
                         <div class=WordSection1>  
                         <div class="logos" style="width: 150px;margin: auto;">
                                                
                      <span><img src='<?php echo $logo?>' id="images1"  style="width: 130px;height: 130px;"></span>
                      
                      
                    </div>      
     <div>   
    <h4 align="center"><?php echo htmlentities($orgname) ?>, <?php echo htmlentities($schadress) ?></h4>
    <h4 align="center">SENATE RESULT SUMMARY</h4></div>
    <div style="margin-left:93px;">
          
    <p class=MsoNormal><b style='mso-bidi-font-weight:
normal'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTMENT: <?php echo htmlentities($dt); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PROGRAMME: <?php echo htmlentities($prgs); ?></b></p>
<p class=MsoNormal align=center><b style='mso-bidi-font-weight:
normal;text-transform: uppercase;'>SESSION: <?php echo htmlentities($ses);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
SEMESTER: <?php echo htmlentities($sems); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='mso-bidi-font-weight:
normal'>LEVEL: <?php echo htmlentities($clvl) ?></b></p>
  </div>
 
<table class=MsoTable15Grid1Light border=1 cellspacing=0 cellpadding=0
 width=100 style='width:775.55pt;border-collapse:collapse;
 border:none;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;
 mso-border-themetint:102;mso-yfti-tbllook:1184;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>

 <?php 
  try{
    $grades_count = [];
 ?>
 <tr style='mso-yfti-irow:-1;mso-yfti-firstrow:yes;mso-yfti-lastfirstrow:yes'>
  <td width=36 valign=top style='width:27.2pt;border:solid #999999 1.0pt;
  mso-border-themecolor:text1;mso-border-themetint:102;border-bottom:solid #666666 1.5pt;
  mso-border-bottom-themecolor:text1;mso-border-bottom-themetint:153;
  mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;mso-border-bottom-alt:solid #666666 1.5pt;mso-border-bottom-themecolor:
  text1;mso-border-bottom-themetint:153;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-yfti-cnfc:5'><b><span style='font-size:14.0pt;mso-bidi-font-size:
  11.0pt'>#<o:p></o:p></span></b></p>
  </td>
  <td width=135 valign=top style='width:119.05pt;border-top:solid #999999 1.0pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;border-left:
  none;border-bottom:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-left-alt:solid #999999 .5pt;
  mso-border-left-themecolor:text1;mso-border-left-themetint:102;mso-border-alt:
  solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:102;
  mso-border-bottom-alt:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-yfti-cnfc:1'><b><span style='font-size:14.0pt;mso-bidi-font-size:
  11.0pt'>Student ID<o:p></o:p></span></b></p>
  </td>
  <td width=200 valign=top style='width:248.6pt;border-top:solid #999999 1.0pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;border-left:
  none;border-bottom:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-left-alt:solid #999999 .5pt;
  mso-border-left-themecolor:text1;mso-border-left-themetint:102;mso-border-alt:
  solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:102;
  mso-border-bottom-alt:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-yfti-cnfc:1'><b><span style='font-size:14.0pt;mso-bidi-font-size:
  11.0pt'>Student Name<o:p></o:p></span></b></p>
  </td>
  <td width=147 colspan=3 valign=top style='width:120.55pt;border-top:solid #999999 1.0pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;border-left:
  none;border-bottom:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-left-alt:solid #999999 .5pt;
  mso-border-left-themecolor:text1;mso-border-left-themetint:102;mso-border-alt:
  solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:102;
  mso-border-bottom-alt:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;mso-yfti-cnfc:1'><b><span
  style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>Previous<o:p></o:p></span></b></p>
  </td>
  <td width=135 colspan=3 valign=top style='width:101.4pt;border-top:solid #999999 1.0pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;border-left:
  none;border-bottom:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-left-alt:solid #999999 .5pt;
  mso-border-left-themecolor:text1;mso-border-left-themetint:102;mso-border-alt:
  solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:102;
  mso-border-bottom-alt:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;mso-yfti-cnfc:1'><b><span
  style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>Present<o:p></o:p></span></b></p>
  </td>
  <td width=146 colspan=3 valign=top style='width:109.35pt;border-top:solid #999999 1.0pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;border-left:
  none;border-bottom:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-left-alt:solid #999999 .5pt;
  mso-border-left-themecolor:text1;mso-border-left-themetint:102;mso-border-alt:
  solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:102;
  mso-border-bottom-alt:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;mso-yfti-cnfc:1'><b><span
  style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>Cumulative<o:p></o:p></span></b></p>
  </td>
  <td width=92 valign=top style='width:78.8pt;border-top:solid #999999 1.0pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;border-left:
  none;border-bottom:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-left-alt:solid #999999 .5pt;
  mso-border-left-themecolor:text1;mso-border-left-themetint:102;mso-border-alt:
  solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:102;
  mso-border-bottom-alt:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-yfti-cnfc:1'><b><span style='font-size:14.0pt;mso-bidi-font-size:
  11.0pt'>Standing<o:p></o:p></span></b></p>
  </td>
  <td width=92 valign=top style='width:78.8pt;border-top:solid #999999 1.0pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;border-left:
  none;border-bottom:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-left-alt:solid #999999 .5pt;
  mso-border-left-themecolor:text1;mso-border-left-themetint:102;mso-border-alt:
  solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:102;
  mso-border-bottom-alt:solid #666666 1.5pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:153;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-yfti-cnfc:1'><b><span style='font-size:14.0pt;mso-bidi-font-size:
  11.0pt'>Remarks<o:p></o:p></span></b></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:0'>
  <td width=36 valign=top style='width:27.2pt;border:solid #999999 1.0pt;
  mso-border-themecolor:text1;mso-border-themetint:102;border-top:none;
  mso-border-top-alt:solid #999999 .5pt;mso-border-top-themecolor:text1;
  mso-border-top-themetint:102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:
  text1;mso-border-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-yfti-cnfc:4'><b><o:p>&nbsp;</o:p></b></p>
  </td>
  <td width=135 valign=top style='width:101.05pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><o:p>&nbsp;</o:p></p>
  </td>
  <td width=131 valign=top style='width:98.6pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><o:p>&nbsp;</o:p></p>
  </td>
  <td width=39 valign=top style='width:29.15pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:10.5pt;
  font-family:"Arial",sans-serif;color:#494949;letter-spacing:.4pt;background:
  white'>TCP</span></span><o:p></o:p></b></p>
  </td>
  <td width=45 valign=top style='width:33.6pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:10.5pt;
  font-family:"Arial",sans-serif;color:#494949;letter-spacing:.4pt;background:
  white'>TNU</span></span><o:p></o:p></b></p>
  </td>
  <td width=64 valign=top style='width:47.8pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:10.5pt;
  font-family:"Arial",sans-serif;color:#494949;letter-spacing:.4pt;background:
  white'>CGPA</span></span><o:p></o:p></b></p>
  </td>
  <td width=44 valign=top style='width:33.0pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:10.5pt;
  font-family:"Arial",sans-serif;color:#494949;letter-spacing:.4pt;background:
  white'>TCP</span></span><o:p></o:p></b></p>
  </td>
  <td width=45 valign=top style='width:33.6pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:10.5pt;
  font-family:"Arial",sans-serif;color:#494949;letter-spacing:.4pt;background:
  white'>TNU</span></span><o:p></o:p></b></p>
  </td>
  <td width=46 valign=top style='width:34.8pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:10.5pt;
  font-family:"Arial",sans-serif;color:#494949;letter-spacing:.4pt;background:
  white'>GPA</span></span><o:p></o:p></b></p>
  </td>
  <td width=44 valign=top style='width:33.0pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:10.5pt;
  font-family:"Arial",sans-serif;color:#494949;letter-spacing:.4pt;background:
  white'>TCP</span></span><o:p></o:p></b></p>
  </td>
  <td width=45 valign=top style='width:33.6pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:10.5pt;
  font-family:"Arial",sans-serif;color:#494949;letter-spacing:.4pt;background:
  white'>TNU</span></span><o:p></o:p></b></p>
  </td>
  <td width=57 valign=top style='width:42.75pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><b style='mso-bidi-font-weight:normal'><span style='font-size:10.5pt;
  font-family:"Arial",sans-serif;color:#494949;letter-spacing:.4pt;background:
  white'>CGPA</span></span><o:p></o:p></b></p>
  </td>
  <td width=92  valign=top style='width:68.8pt;border-top:none;
  border-left:none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:
  text1;mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;
  mso-border-right-themecolor:text1;mso-border-right-themetint:102;mso-border-top-alt:
  solid #999999 .5pt;mso-border-top-themecolor:text1;mso-border-top-themetint:
  102;mso-border-left-alt:solid #999999 .5pt;mso-border-left-themecolor:text1;
  mso-border-left-themetint:102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:
  text1;mso-border-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><o:p>&nbsp;</o:p></p>
  </td>
  <td width=92  valign=top style='width:68.8pt;border-top:none;
  border-left:none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:
  text1;mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;
  mso-border-right-themecolor:text1;mso-border-right-themetint:102;mso-border-top-alt:
  solid #999999 .5pt;mso-border-top-themecolor:text1;mso-border-top-themetint:
  102;mso-border-left-alt:solid #999999 .5pt;mso-border-left-themecolor:text1;
  mso-border-left-themetint:102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:
  text1;mso-border-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><o:p>&nbsp;</o:p></p>
  </td>
 </tr>
 <?php
 $sqlv="SELECT DISTINCT studentid FROM result WHERE acadsession='$ses' AND semester='$sems' AND level='$clvl' AND programme='$prgs' ORDER BY studentid ASC";

        $_POST['totalunit']=0;
         $stv = mysqli_query($con,$sqlv);
            $countv = mysqli_fetch_all($stv,MYSQLI_ASSOC);
          $d=0;
          $Acct=0;
         foreach($countv as $rowv)
       {
        $d+=1;
        $Acct+=1;
        stuname($con,$rowv['studentid']);
 ?>
 <tr style='mso-yfti-irow:1;mso-yfti-lastrow:yes'>
  <td width=36 valign=top style='width:27.2pt;border:solid #999999 1.0pt;
  mso-border-themecolor:text1;mso-border-themetint:102;border-top:none;
  mso-border-top-alt:solid #999999 .5pt;mso-border-top-themecolor:text1;
  mso-border-top-themetint:102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:
  text1;mso-border-themetint:102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-yfti-cnfc:4'><b><o:p>&nbsp;</o:p><?php echo htmlentities($d); ?></b></p>
  </td>
  <td width=135 valign=top style='width:101.05pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><o:p>&nbsp;</o:p><?php echo htmlentities($rowv['studentid']); ?></p>
  </td>
  <td width=131 valign=top style='width:98.6pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><o:p>&nbsp;</o:p><?php echo htmlentities($_POST["harrd"])?></p>
  </td>
  <?php 
  //Loading Previous Result Computation
  ComputePreviousResults($con,$rowv['studentid'],$sems,$clvl,$ses,$prgs);
  ?>
  <td width=39 valign=top style='width:29.15pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php echo $_POST['StrCums']?></span></p>
  </td>

  <td width=45 valign=top style='width:33.6pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php echo $_POST['Strpos'] ?></span></p>
  </td>

  <td width=64 valign=top style='width:47.8pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php echo $_POST['CGPs']?></span></p>
  </td>
  <!-- For Present Computation  -->
  <?php
/*Thing to do Here
  1. Load All the Core Courses Code Here Before Sending to result TABLE
  2. Becare ful When Computing the result to avoid Commputational Errors
*/

//loading The Course Codes
$sqlty="SELECT * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='".$rowv['studentid']."' AND result.programme='$prgs' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$ses'  ORDER BY result.studentid";
//USing For Loop TO count the Courses
$_POST['totalunit']=0;
$_POST['tupt']=0;
$_POST['stupt']=0;
$_POST['mysk']='';
$stt = mysqli_query($con,$sqlty);
$countt = mysqli_fetch_all($stt,MYSQLI_ASSOC);
foreach($countt as $rowt)
{
//$k+=1;
$_POST['totalunit']+=$rowt['unit'];
/*Codes Using to Calculate De semester GPa

*/
GetGradeSystem($con);
$Scod=$rowt['ca'] + $rowt['exam'];
for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++) {
if ($Scod <= $_POST['Maxscore'][$i] && $Scod >= $_POST['Minscore'][$i]) {
$_POST['mysk']   =   $_POST['point'][$i];
$_POST['tupt'] += (int)$_POST['mysk']*(int)$rowt['unit'];
$_POST['stupt'] += (int)$_POST['mysk'];

//computing d Semester GPA

//unikx($_POST['totalPointx']);
}

}
}
$sgpa=number_format($_POST['tupt']/$_POST['totalunit'],2);
?>
  <td width=44 valign=top style='width:33.0pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php echo $_POST['tupt']; ?></span></p>
  </td>
  <td width=45 valign=top style='width:33.6pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php echo $_POST['totalunit']; ?></span></p>
  </td>
  <td width=46 valign=top style='width:34.8pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php echo htmlentities($sgpa); ?></span></p>
  </td>
  <?php ComputeThePreviousResults($con,$rowv['studentid'],$sems,$clvl,$ses,$prgs); ?>
  <td width=44 valign=top style='width:33.0pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php echo $_POST['StrCum']?></span></p>
  </td>
  <td width=45 valign=top style='width:33.6pt;border-top:none;border-left:none;
  border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php echo $_POST['Strpo'] ?></span></p>
  </td>
  <td width=57 valign=top style='width:42.75pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:10.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php echo $_POST['CGP']?></span></p>
  </td>
  <!-- Seperate Column -->
  <?php
   gradeclass($con,$_POST['CGP']);
?>
   
  <td width=57 valign=top style='width:78.75pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:8.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php
    echo htmlentities($_POST['myGracl']);
     $_POST['cote'][$Acct]=$_POST['myGracl'];
  ?></span></p>
  </td> 
  <?php
   Carryover($con,$rowv['studentid'],$sems,$clvl,$ses,$prgs);
   Outstand($con,$rowv['studentid'],$prgs);
   $newfc=array($_POST['HolderCO']);
    $newfd=array($_POST['cot']);
    $newf=array_merge($newfc,$newfd);
    $net=array_filter($newf);
    if (empty($net))
    {
?>
  <td width=57 valign=top style='width:78.75pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:8.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p><?php
    echo htmlentities("Passed");
  ?></span></p>
  </td>
<?php
        }else{

   ?>
   
  <td width=57 valign=top style='width:78.75pt;border-top:none;border-left:
  none;border-bottom:solid #999999 1.0pt;mso-border-bottom-themecolor:text1;
  mso-border-bottom-themetint:102;border-right:solid #999999 1.0pt;mso-border-right-themecolor:
  text1;mso-border-right-themetint:102;mso-border-top-alt:solid #999999 .5pt;
  mso-border-top-themecolor:text1;mso-border-top-themetint:102;mso-border-left-alt:
  solid #999999 .5pt;mso-border-left-themecolor:text1;mso-border-left-themetint:
  102;mso-border-alt:solid #999999 .5pt;mso-border-themecolor:text1;mso-border-themetint:
  102;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal'><span style='font-size:8.5pt;font-family:"Arial",sans-serif;
  color:#494949;letter-spacing:.4pt;background:white'><o:p>&nbsp;</o:p>RP: <?php
    foreach($net as $keys){
      echo htmlentities($keys);
    }
  ?></span></p>
  </td>
<?php } ?>
 </tr>
 <?php
}
   } catch (\Exception $e) {

   }
  ?>
</table>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>
<?php 
$ts=0;
   for($ts=1;$ts<=$Acct; $ts++)
     {
    
    if(isset($grades_count[$_POST['cote'][$ts]]) === false) { 
     $grades_count[$_POST['cote'][$ts]] = 0;                               
    
    }
    $grades_count[$_POST['cote'][$ts]]++;
    }

?>
<table style="width:150px;border: 1px solid black;">
  <tr style="border: 1px solid black;">
    <th colspan="2" style="text-align:center;">SUMMARY</th>
  </tr>
  <tr style="border: 1px solid black;">
    <th style="border: 1px solid black;">Standing</th>
    <th style="border: 1px solid black;">No</th>
  </tr>
  
  <?php 
  $cks=0;                 
     foreach ($grades_count as $grade=>$count) {
      $cks+=$count;
  ?>
  <tr style="border: 1px solid black;">
  <td style="border: 1px solid black;"><?php echo htmlentities($grade) ?></td>
  <td style="border: 1px solid black;"><?php echo htmlentities($count) ?></td>
</tr>
<?php }?>
  

  <tr style="border: 1px solid black;">
    <th style="border: 1px solid black;">Total</th>
    <th style="border: 1px solid black;"><?php echo htmlentities($cks); ?></th>
  </tr>
</table>  

                                                <!-- Separate Contain Senate -->
                                              
<div style="margin-left:25px;width: 1000px;">
<?php 
deanname($con,$prgs);
hodname($con,$prgs);
vcname($con);
 ?>   
     <div style="float: left;">
      <div style="float: left;">
        <b><strong>HEAD OF DEPARTMENT</strong></b><br>
        <span><strong>Name: <?php echo htmlentities($_POST['nameh']) ?></strong></span><br>
      
      <span><strong>Sign:__________________</span><br>
      
      <span><strong>Date:__________________</span>
      
      </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="float: right;">
        <b style="text-align: center;">DEAN</b><br>
        <span>Name: <?php echo htmlentities($_POST['names']) ?> </span><br>
        <span>Sign: _________________________</span><br>
        <span>Date:__________________________</span>
      </div>
    </div>
       <div style="float: right;">
        <b>VICE CHANCELLOR</b><br>
        <span>Name: <?php echo htmlentities($_POST['vname']) ?></span><br>
        <span>Sign:___________________</span><br>
        <span>Date:____________________</span>
      </div>
    </div>
            <br>
            <div style="margin:auto;width:50px;margin-top: 7%;" class="button">
                <input type="button" class="btn btn-success" value="Print-Detail" onclick="window.open('printsen','_blank')" style="float: left;font-size: 20px;display: non;">               
            </div>
          </div>
            </div>
                                                        <?php
                                                      }
                            else{
                                                        $showModale='true';

                            }
               }
            }
          }
          ?>                                 
                             
                                              
          <?php
                }else{ ?>
                  <b style="color: red;">Sorry You Don't Have Access to this Page</b>
           <?php     }
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
                            <p>Please Select <?php echo htmlentities($error); ?></p>
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
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
            <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalx" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Message Successfully Sent</p>
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
    
/*Fucntion to load Names of Student*/
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
        //$name =$first." ".$las." ".$sur;
        $name =strtoupper($las).", ".$first;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
}
/*Loading Grades*/
function GetGradeSystem($con)
  {
   $gradeCounter= 0;
    $sqlper = "SELECT  * FROM gradetable";
    $result = mysqli_query($con, $sqlper);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //$rowks=mysqli_num_rows($result);
    foreach ($data as $row) {
      $gradeCounter += 1;
      $_POST['Minscore'][$gradeCounter] =  $row["minscore"];
      $_POST['Maxscore'][$gradeCounter] =  $row["maxscore"];
      $_POST['Grade'][$gradeCounter]    =  $row["grade"];
      $_POST['point'][$gradeCounter]    =  $row["point"];
    }
    $_SESSION['gradeCounter'] = $gradeCounter;
  }

/*Cummulative Computation*/
function ReadingCurrentSemesterResult($con,$mat,$se1,$lv1,$as1,$pr1)
  {
   
    //Variable Declaration
    $Acode=0;
 $TPointds = 0;
 $TotalUnitds=0;
         $_POST['TotalUnitds'] = 0;
         $_POST['Pth'] = 0;
         $_POST['cue'] = 0;
         $_POST['stupts'] = 0;
         $_POST['mysz']=0;
 
         $harr = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$mat' AND result.acadsession='$as1' AND result.semester='$se1' AND result.level='$lv1' AND result.programme='$pr1' ORDER BY result.studentid";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
          foreach($resulter as $rowk)
          {
            $Acode+=1;
            $UnitGPCS[$Acode] = $rowk['unit'];
            $TotalUnitds += $rowk['unit'];
            $Totaleds =$rowk['ca'] + $rowk['exam'];

            GetGradeSystem($con);
      for ($k = 1; $k<= $_SESSION['gradeCounter']; $k++){
                                if ($Totaleds <= $_POST['Maxscore'][$k] && $Totaleds >= $_POST['Minscore'][$k])
                                 {
                                    
                                    $_POST['mysz']   =   $_POST['point'][$k];
                                    $TPointds+=(int)$_POST['mysz'] * (int)$rowk['unit'];
                                    $_POST['pointd']=$TPointds;
                                    $_POST['stupts'] += (int)$_POST['mysz'];

                                 }


                          }
        }
        //echo "Current Units ".$TotalUnitds;
        $_POST['TotalUnitds']=$TotalUnitds;
        $_POST['cue']=$_POST['stupts'];
        $_POST['Pth']=$_POST['pointd'];

  }
  function ComputeThePreviousResults($con,$st,$se11,$lv11,$as11,$pr11)
     {

       
      
       //Declaring all the Variables
         $TPointer = 0; $GP = 0; $TotalUniter = 0; $Totaler = 0;
         $Cum = 0; $GP_Cum = 0; $TotalUCum = 0; $TotalPointCum = 0; $MyGP = 0; $Counter = 0;
         $TPointd = 0;
         $TPointdsk=0;
         $TotalUnitd = 0;
         $_POST['totalunit']=0;
         $_POST['stuptc'] = 0;
         $_POST['Pthc'] = 0;
         $_POST['mysc'] = 0;
         $_POST['pointsk']=0;
       try {

        //Trying It Here
              $harr= "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$st' AND result.acadsession<'$as11' AND result.programme='$pr11'  ORDER BY result.studentid";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
      foreach($resulter as $rowz){

        $_POST['totalunit']+=$rowz['unit'];
        $Scodc=$rowz['ca'] + $rowz['exam'];
            GetGradeSystem($con);
        for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                                if ($Scodc <= $_POST['Maxscore'][$j] && $Scodc >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['mysc']=$_POST['point'][$j];
                                    $TPointdsk +=(int)$_POST['mysc'] * (int)$rowz['unit'];
                                    $_POST['pointsk']=$TPointdsk;
                                    $_POST['stuptc'] += (int)$_POST['mysc'];
                                 }
                          }
          }

        $_POST['Pthc']=$_POST['pointsk'];

        //Ends Here
         $Counter_P = 0;
                 if ($se11 =="First Semester")
                 {

                    ReadingCurrentSemesterResult($con,$st,$se11,$lv11,$as11,$pr11);
                    //echo $_POST['TotalUnitds'];
                    $_POST['Strpo']= ($_POST['totalunit']+  $_POST['TotalUnitds']);
                    $_POST['StrCE']= ($_POST['stuptc']+$_POST['cue']);
                    $_POST['StrCum']=($_POST['Pthc']+$_POST['Pth']);
                    
                    $_POST['CGP']=number_format((($_POST['Pthc']+$_POST['Pth'])/($_POST['totalunit']+  $_POST['TotalUnitds'])),2);//compute cumulative

                 }
                 else if ($se11 == "Second Semester" ) //
                 {
                    
                    ReadingCurrentSemesterResult($con,$st,$se11,$lv11,$as11,$pr11);
                    //echo "<br>";
                    GetSecondResult($con,$st,$se11,$lv11,$as11,$pr11);
                    $_POST['Strpo']=($_POST['totalunit'] + $_POST['TotalUnitds'] + $_POST['Unitsd']);
                    
                    $_POST['StrCE']= ($_POST['stuptc']+$_POST['cues']+$_POST['cue']);
                    $_POST['StrCum']=($_POST['Pthc']+$_POST['pointsd']+$_POST['Pth']);
                    $_POST['CGP']=number_format((($_POST['Pthc']+$_POST['pointsd']+$_POST['Pth'])/($_POST['totalunit'] + $_POST['TotalUnitds'] + $_POST['Unitsd'])),2);
                    
                 }

       } catch (Exception $e) {

       }


    }

    function GetSecondResult($con,$matric,$semet,$lv11,$sectin,$program)
    {
        
        //declaring variable
        $scoresCounters=0;
        $_POST['Unitsd']=0;
        $_POST['cues']=0;
        $_POST['stupty']=0;
        $TPointdsky=0;
        $_POST['mys']=0;
        //checking semester
        $semet="First Semester";

        $mysqlercs = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matric' AND result.acadsession='$sectin' AND result.semester='$semet' AND result.programme='$program'  ORDER BY result.studentid";
   $resultfy = mysqli_query($con,$mysqlercs);
        $resulters = mysqli_fetch_all($resultfy,MYSQLI_ASSOC);
  foreach($resulters as $row){
    $scoresCounters+=1;

     $_POST['Unitsd']+=$row['unit'];
     $Totaledx  =(int)$row['exam'] + (int)$row['ca'];
      GetGradeSystem($con);
      for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                                if ($Totaledx <= $_POST['Maxscore'][$j] && $Totaledx >= $_POST['Minscore'][$j])
                                 {
                                   
                                    $_POST['mys']=$_POST['point'][$j];
                                    $TPointdsky +=(int)$_POST['mys'] * (int)$row['unit'];
                                    $_POST['pointsd']=$TPointdsky;
                                    $_POST['stupty'] += (int)$_POST['mys'];

                                 }


                          }



    }
    $_POST['cues']=$_POST['stupty'];
}
//Carry over Section
function CheckCOSV($con,$codet,$materd,$prom,$level,$sek)
   {
      
        $MyData="";
         try
         {
            
              $ch="SELECT coursecode, MAX(ca+exam) FROM result WHERE   coursecode='$codet' AND level<='$level' AND studentid='$materd' AND programme='$prom' AND status='Y' ORDER BY acadsession DESC";
              $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$prom'"));
              $type = $data['passmark'];
                        $stmt = mysqli_query($con,$ch);
                        $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
                   foreach($resulter as $rw)
              {

              if ($rw['MAX(ca+exam)'] < $type)
              {
                 $MyData .=$rw['coursecode']. ",";

              }
                        }
             $_POST['MyData']=$MyData;
             //$_SESSION['MyData']=$MyData;
         }
              catch(PDOException $e)
           {
          echo $e->getMessage();
         }

     }



     function Carryover($con,$Matric,$sem,$level,$sectin,$prom)
     {
      
      $MyData="";
        $HolderCO='';
       $_POST['coufc']='';
        $COS='';
        $_POST['MyData']='';
        //echo $codet;
         try
         {
            
              $ch="SELECT *,coursecode, MAX(ca+exam) FROM result WHERE  acadsession<='$sectin' AND level<='$level' AND studentid='$Matric' AND programme='$prom' AND status='Y' GROUP BY coursecode ORDER BY coursecode";
              $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$prom'"));
              if ($data!='') {
                $type = $data['passmark'];  
                }
                        $stmt = mysqli_query($con,$ch);
                        $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
                   foreach($resulter as $rw)
              {

              if ($rw['MAX(ca+exam)'] < $type)
              {  
                
                 $MyData .=$rw['coursecode']. ",";
                  
              }
                        }
             $_POST['MyData']=$MyData;
             
             $COS.=$_POST['MyData'];
         }
              catch(PDOException $e)
           {
          echo $e->getMessage();
         }

         if ($_POST['MyData'] == "")
             {
                 $HolderCO .="";
             }
             else
             {
                 $HolderCO .="" .$COS;
             }
             $_POST['HolderCO'] = $HolderCO;
     }

//Computation For the previous Record
  function ComputePreviousResults($con,$st,$se11,$lv11,$as11,$pr11)
     {

       // echo $st;
       
       //echo "<br>";
       //Declaring all the Variables
         $TPointer = 0; $GP = 0; $TotalUniter = 0; $Totaler = 0;
         $Cum = 0; $GP_Cum = 0; $TotalUCum = 0; $TotalPointCum = 0; $MyGP = 0; $Counter = 0;
         $TPointd = 0;
         $TPointdsk=0;
         $TotalUnitd = 0;
         $_POST['totalunit']=0;
         $_POST['stuptc'] = 0;
         $_POST['Pthc'] = 0;
         $_POST['mysc'] = 0;
         $_POST['pointsk']=0;
       try {

        //Trying It Here
              $harr= "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$st' AND result.acadsession<'$as11' AND result.programme='$pr11'  ORDER BY result.studentid";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
      foreach($resulter as $rowz){

        $_POST['totalunit']+=$rowz['unit'];
        $Scodc=$rowz['ca'] + $rowz['exam'];
            GetGradeSystem($con);
        for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                                if ($Scodc <= $_POST['Maxscore'][$j] && $Scodc >= $_POST['Minscore'][$j])
                                 {
                                   
                                    $_POST['mysc']=$_POST['point'][$j];
                                    $TPointdsk +=(int)$_POST['mysc'] * (int)$rowz['unit'];
                                    $_POST['pointsk']=$TPointdsk;
                                    $_POST['stuptc'] += (int)$_POST['mysc'];
                                 }
                          }

          }

        $_POST['Pthc']=$_POST['pointsk'];

        //Ends Here
         $Counter_P = 0;
                 if ($se11 =="First Semester")
                 {

                    
                    if($_POST['Pthc']!=0 && $_POST['totalunit']!=0)
                      {
                    $_POST['Strpos']= ($_POST['totalunit']);
                    $_POST['StrCEs']= ($_POST['stuptc']);
                    $_POST['StrCums']=($_POST['Pthc']);
                    
                    $_POST['CGPs']=number_format(($_POST['Pthc']/$_POST['totalunit']),2);//compute cumulative
                    }
                    else if($_POST['Pthc']==0 && $_POST['totalunit']==0)
                    {
                      $_POST['Strpos']="0.00";
                      $_POST['StrCEs']="0.00";
                      $_POST['StrCums']="0.00";
                      $_POST['CGPs']="0.00";
                    }               
                 }
                 else if ($se11 == "Second Semester" ) //
                 {
                  
                  GetScores($con,$se11,$lv11,$as11,$pr11,$st);
                   GetSecResult($con,$st,$se11,$lv11,$as11,$pr11);
                   
                     if ($_POST['cues']!=0 && $_POST['Unitsd']!=0 ) {
                     
                   
                    $_POST['Strpos']=($_POST['totalunit']  + $_POST['Unitsd']);
                    $_POST['StrCums']=($_POST['Pthc']+$_POST['pointsd']);
                    $_POST['CGPs']=number_format((($_POST['Pthc']+$_POST['pointsd'])/($_POST['totalunit'] + $_POST['Unitsd'])),2);
                    }elseif($_POST['cues']==0 && $_POST['Unitsd']==0 ){
                      $lvt=mysqli_fetch_assoc(mysqli_query($con,"SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$st' AND result.acadsession<'$as11' AND result.programme='$pr11'  ORDER BY result.studentid"));
                      if ($lvt=="") {
                        $_POST['Strpos']="0.00";
                        $_POST['StrCums']="0.00";
                       $_POST['CGPs']="0.00";
                      }else{
                    $_POST['Strpos']=($_POST['totalunit'] + $_POST['Unitsd']);
                    $_POST['StrCums']=($_POST['Pthc']+$_POST['pointsd']);
                    $_POST['CGPs']=number_format((($_POST['Pthc']+$_POST['pointsd'])/($_POST['totalunit'] + $_POST['Unitsd'])),2);
                      }//Closing else Statement
                    }
                    
                 }

       } catch (Exception $e) {

       }


    }

    function GetSecResult($con,$matric,$semet,$lv11,$sectin,$program)
    {
        
        //declaring variable
        $scoresCounters=0;
        $_POST['Unitsd']=0;
        $_POST['cues']=0;
        $_POST['stupty']=0;
        $TPointdsky=0;
        $_POST['mys']=0;
        $_POST['pointsd']=0;
        //checking semester
        $semet="First Semester";

        $mysqlercs = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matric' AND result.acadsession='$sectin' AND result.semester='$semet' AND result.programme='$program'  ORDER BY result.studentid";

   $resultfy = mysqli_query($con,$mysqlercs);
        $resulters = mysqli_fetch_all($resultfy,MYSQLI_ASSOC);
  foreach($resulters as $row){
    $scoresCounters+=1;

     $_POST['Unitsd']+=$row['unit'];
     $Totaledx  =(int)$row['exam'] + (int)$row['ca'];
      GetGradeSystem($con);
      for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                                if ($Totaledx <= $_POST['Maxscore'][$j] && $Totaledx >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['mys']=$_POST['point'][$j];
                                    $TPointdsky +=(int)$_POST['mys'] * (int)$row['unit'];
                                    $_POST['pointsd']=$TPointdsky;
                                    $_POST['stupty'] += (int)$_POST['mys'];

                                 }


                          }



    }
    $_POST['cues']=$_POST['stupty'];
}
function GetScores($con,$semc,$les,$sesc,$prob,$hars)
{
    $scoresCounter=0;
    $_POST['points']=0;
    $_POST['totalUnit']=0;
    
    //$_SESSION['scoresCounter']=@$_POST['scoresCounter'];
    $mysqler = "SELECT  * FROM result INNER JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$hars' AND result.acadsession='$sesc' AND result.semester='$semc' AND result.level='$les' AND result.programme='$prob' ORDER BY result.coursecode";
    $resultf = mysqli_query($con,$mysqler);
     $dataf = mysqli_fetch_all($resultf,MYSQLI_ASSOC);
     foreach($dataf as $row){
     $scoresCounter+=1;
     $_POST['Units'][$scoresCounter]=$row['unit'];
     $_POST['Code'][$scoresCounter]=$row['coursecode'];
     $_POST['Title'][$scoresCounter]=$row['title'];
     $_POST['Scores'][$scoresCounter]=(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounter']=$scoresCounter;
  //compute GP
  GetGradeSystem($con); //Get the grade
   for($k=1; $k<=$_SESSION['scoresCounter']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scores'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scores'][$k] >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['G'][$k] = $_POST['Grade'][$j];
                                    $_POST['P'][$k] = $_POST['point'][$j]*$_POST['Units'][$k];
                                    $_POST['points']+=(int)$_POST['point'][$j] * (int)$_POST['Units'][$k];
                                    
                                 }
                                $_POST['totalUnit']+=$_POST['Units'][$k];
                          }
                           

}

/*Querying Outstanding Courses*/

function gradeclass($con,$CGP){
  /* 
  *Computing the graduating class of the Student From Graduating class table
  */
  $gradeCounterx=0;
  $sqlperx = "SELECT  * FROM gradclass";
  $resultx = mysqli_query($con, $sqlperx);
  $datax = mysqli_fetch_all($resultx, MYSQLI_ASSOC);
  //$rowks=mysqli_num_rows($result);
  foreach ($datax as $row) {
    $gradeCounterx += 1;
    $_POST['Minscorep'][$gradeCounterx] =  $row["minscorep"];
    $_POST['Maxscorep'][$gradeCounterx] =  $row["maxscorep"];
    $_POST['Graclas'][$gradeCounterx]    =  $row["remark"];
  }
  $_SESSION['gradeCounterx'] = $gradeCounterx;

  for ($i = 1; $i <= $_SESSION['gradeCounterx']; $i++) {
    //GetCumulative();
    if ($CGP <= $_POST['Maxscorep'][$i] && $CGP >= $_POST['Minscorep'][$i]) {
      
      $_POST['myGracl']   =   $_POST['Graclas'][$i];
      $_SESSION['myGracl']   =   $_POST['Graclas'][$i];
    }
  }
}

function deanname($con,$prgs){
 $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$prgs'"));  
$stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='dean' AND staffaculty.faculty='".$fac['fid']."' ");
         $res=mysqli_fetch_assoc($stf);
    if ($res!='') {
     
    
        $stn=ucwords($res['title'].". ".strtoupper($res['lastname'])." ".$res['firstname']);
        $_POST['names']=$stn;
      }else{
        $_POST['names']="No Name";
      }

}
function vcname($con){
$dts=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people JOIN staffprive ON staffprive.staffid=people.staffid WHERE people.status='active' AND staffprive.position='vc'"));
    if ($dts!='') {
        $fulna=$dts['title'].". ".strtoupper($dts['lastname'])." ".$dts['firstname'];
        $_POST['vname']=$fulna;
    }else{
        $_POST['vname']='No Name';
    }
    
}

function hodname($con,$prgs){
 $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$prgs'"));  
$stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='hod' AND staffaculty.deptm='".$fac['did']."' ");
        $res=mysqli_fetch_assoc($stf);   
        if ($res!='') {
        $stn=ucwords($res['title'].". ".strtoupper($res['lastname'])." ".$res['firstname']);
        $_POST['nameh']=$stn;
        }else{
          $_POST['nameh']='No Name';
        }

}

function  Outstand($con,$sdt,$program){
  $_POST['cot']='';
  $_POST['cott']='';
  
     $ques= mysqli_query($con,"SELECT DISTINCT coursecode,studentid,custatus FROM studelecour WHERE coursecode NOT IN (SELECT DISTINCT coursecode FROM result WHERE studentid='$sdt' AND programme='$program') AND studentid='$sdt'");
            $resu= mysqli_fetch_all($ques,MYSQLI_ASSOC);
            foreach($resu as $rwt)
              {
                $_POST['cot'].=$rwt['coursecode'].',';
                $_POST['cott'].=$rwt['coursecode'].',';
              }   
}   
?>