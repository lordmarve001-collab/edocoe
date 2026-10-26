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
                  .WordSection1 td{
                    font-size: 10px;
                  }
                  .headerp{
                     position: fixed;
                  }
                  table { page-break-after:auto }
                  tr    { page-break-inside:avoid; page-break-after:auto }
                  td    { page-break-inside:avoid; page-break-after:auto }
                  .talk{
                    text-align: justify;
                  } 
                }
                .tals{
      width: 470px;margin: 0px;border: 1px solid;border-collapse: collapse;
    }
    .talss,.talk{
      border: 1px solid;
    }
    .talk{
      text-align: justify;
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
                     }elseif ($paych['position']=="academicaffair") {
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
                                    <h2 class="title">Department Summary Setup</h2>
                                
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
                  <button type="submit" name="query" id="submit" class="btn btn-primary">View Result</button>
                </div>
              </div>
            </form>
          </section>
          <!-- From Here Is For HOD Side --> 
          <?php 
            $error='';
            if (isset($_POST['query'])) {
              if (empty($_POST['level'])) {
                $error='level';
               $showModal = "true";
              }elseif (empty($_POST['section'])) {
               $showModal = "true";
               $error='session';
              }elseif (empty($_POST['semester'])) {
                $showModal = "true";
               $error='Semester';
              }elseif (empty($_POST['progrem'])) {
                $showModal = "true";
               $error='Programme';
              }

              else{
                $clvl=mysqli_escape_string($con,$_POST['level']);
                $secc=mysqli_escape_string($con,$_POST['section']);
                $sems=mysqli_escape_string($con,$_POST['semester']);
                $prgs=mysqli_escape_string($con,$_POST['progrem']);
                $_SESSION['level']=$clvl;
                $_SESSION['section']=$secc;
                $_SESSION['semester']=$sems;
                $_SESSION['program']=$prgs;
              $sql=mysqli_query($con,"SELECT * FROM result WHERE programme='$prgs' AND level='$clvl' AND acadsession='$secc' AND semester='$sems' ");
                $rows = mysqli_num_rows($sql);
                if($rows > 0)
               {
          ?>
          <div class=WordSection1>
<p class=MsoNormal align=center style='text-align:center'><span
style='mso-fareast-language:EN-GB;mso-no-proof:yes'><img width=100 height=96
src="<?php echo $logo ?>" v:shapes="Picture_x0020_1"></span></p>

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><?php echo htmlentities($orgname)?>, <?php echo htmlentities($schadress)?> </b></p>

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'>DEPARTMENTAL RESULT SUMMARY</b></p>

    
    <p class=MsoNormal><b style='mso-bidi-font-weight:
normal'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTMENT: <?php echo htmlentities($dt); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PROGRAMME: <?php echo htmlentities($prgs); ?></b></p>
<p class=MsoNormal align=center><b style='mso-bidi-font-weight:
normal;text-transform: uppercase;'>SESSION: <?php echo htmlentities($secc);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
SEMESTER: <?php echo htmlentities($sems); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='mso-bidi-font-weight:
normal'>LEVEL: <?php echo htmlentities($clvl) ?></b></p>
              <p class=MsoNormal></p>
              <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 align=center
               style='border-collapse:collapse;border:none;mso-border-alt:solid windowtext .5pt;
               mso-yfti-tbllook:1184;mso-table-lspace:9.0pt;mso-table-rspace:
               9.0pt;mso-table-anchor-vertical:paragraph;mso-table-anchor-horizontal:
               margin;mso-table-left:left;mso-table-top:12.45pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
               <?php
               /*implementation start Here*/
                try {
                       $sqlc = "SELECT DISTINCT result.coursecode,result.semester,result.level,course.unit FROM result JOIN course ON result.coursecode=course.coursecode
                  WHERE result.programme='$prgs' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$secc'  ORDER BY result.coursecode "; //

                       $sts = mysqli_query($con, $sqlc);
                       $counter = mysqli_fetch_all($sts, MYSQLI_ASSOC);
                       $Acounter = 0;
                       foreach ($counter as $rowa) {
                         $Acounter += 1;
                          $code[$Acounter]     = $rowa['coursecode'];
                         $SMSTER[$Acounter]  = $rowa['semester'];
                         // /$sta= $rowa['statux'];
                         $un[$Acounter]  = $rowa['unit'];
                         $Lev[$Acounter]  = $rowa['level'];
                         // echo " SES ".$ACA;


                  }
               ?>
  <div class="headerp">             
               <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
  <td width=17 valign=top style='width:15.05pt;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:
  margin;mso-element-top:12.45pt;mso-height-rule:exactly'><b style='mso-bidi-font-weight:
  normal'><span style='font-size:14.0pt;mso-bidi-font-size:11.0pt'>#<o:p></o:p></span></b></p>
  </td>
  <td width=18 valign=top style='width:46.1pt;border:solid windowtext 1.0pt;
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:
  margin;mso-element-top:12.45pt;mso-height-rule:exactly'><span class=SpellE><b
  style='mso-bidi-font-weight:normal'><span style='font-size:12.0pt;mso-bidi-font-size:
  11.0pt'>StudentID</span></b></span><b style='mso-bidi-font-weight:normal'><span
  style='font-size:12.0pt;mso-bidi-font-size:11.0pt'><o:p></o:p></span></b></p>
  </td>
  

  <td width=100 colspan="<?php echo  $Acounter;?>" valign=top style='width:100.95pt;border:solid windowtext 1.0pt;
  border-left:none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>

  <p class=MsoNormal align=center style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center;line-height:normal;mso-element:frame;mso-element-frame-hspace:
  9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
  mso-element-anchor-horizontal:margin;mso-element-top:12.45pt;mso-height-rule:
  exactly'><b style='mso-bidi-font-weight:normal'><span style='font-size:14.0pt;
  mso-bidi-font-size:11.0pt'>Courses<o:p></o:p></span></b></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1'>
  <td width=47 valign=top style='width:35.05pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:
  margin;mso-element-top:12.45pt;mso-height-rule:exactly'><o:p>&nbsp;</o:p></p>
  </td>
  <td width=38 valign=top style='width:46.1pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:
  margin;mso-element-top:12.45pt;mso-height-rule:exactly'><o:p>&nbsp;</o:p></p>
  </td>
  
  <?php
  
  $t = 0;
  for ($t = 1; $t <= $Acounter; $t++) {
    $k = 0;
    $count = 0;



  ?>
  <td width=77 valign=top style='width:37.55pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0px;text-align: center;'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal;font-size:9px;font-weight:bold;'><o:p>&nbsp;</o:p><?php  echo  $code[$t] ?><br/><?php  echo  $un[$t] ?><br/>
    M | G | P</p>
  </td>
  <?php
   }?>
 </tr>
</div>
 <?php
 $sqlv="SELECT DISTINCT studentid FROM result WHERE acadsession='$secc' AND semester='$sems' AND level='$clvl' AND programme='$prgs' ORDER BY studentid ASC";

              $_POST['totalunit']=0;
               $stv = mysqli_query($con,$sqlv);
                    $countv = mysqli_fetch_all($stv,MYSQLI_ASSOC);
                    $d=0;
           foreach($countv as $rowv)
           {
            $d+=1;
                stuname($rowv['studentid']);
 ?>
 <tr style='mso-yfti-irow:2;mso-yfti-lastrow:yes'>
  <td width=47 valign=top style='width:35.05pt;border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:
  margin;mso-element-top:10.45pt;mso-height-rule:exactly'><o:p>&nbsp;</o:p><?php echo $d; ?></p>
  </td>
  <td valign=top style='width:46.1pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:
  margin;mso-element-top:10.45pt;mso-height-rule:exactly;font-size:10px;font-weight:bold;'><?php echo $rowv['studentid'] ?></p>
  </td>
  
  <?php
  $counter = 0;
          
          $sqlc = "SELECT DISTINCT coursecode FROM result WHERE programme='$prgs' AND level='$clvl' AND semester='$sems' AND acadsession='$secc'  ORDER BY coursecode "; //

          $sts = mysqli_query($con, $sqlc);
          $counter = mysqli_fetch_all($sts, MYSQLI_ASSOC);
          $Acounterc = 0;
          foreach ($counter as $rowac) {
            $Acounterc += 1;
             $cour[$Acounterc]     = $rowac['coursecode'];

          }
  $tm = 0;
  for ($tm = 1; $tm <= $Acounterc; $tm++) {
   loadcorecourse($cour[$tm],$rowv['studentid'],$sems,$clvl,$secc,$prgs,$con)
  ?>

  <td width=77 valign=top style='width:57.55pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0px;'>
  <p class=MsoNormal style='margin-bottom:0in;margin-bottom:.0001pt;line-height:
  normal;font-size: 8px;text-align: center;font-weight:bold;'><?php echo $_POST['couif']; ?></p>
  </td>
               <?php }?>
               </tr>
               <?php
                          }
                      
                      } catch (Exception $e) {
                      
                  }

               ?>
             </table>
             <span>Legend: Not-App: Not Approved (Pending); 
Not-Up: Not Uploaded; FA: Failed due to Absence; Not-Sub: Not Submitted; ---: Not Registered</span>
             <br>
             <table class="tals">
               <tr>
                 <th class="talss">#</th>
                 <th class="talss">Code</th>
                 <th class="talss">Title</th>
                 <th class="talss">Units</th>
               </tr>
               <?php 
               $cnt = 0;
                $cds = "SELECT DISTINCT result.coursecode,result.semester,result.level,course.unit,course.title FROM result JOIN course ON result.coursecode=course.coursecode
                  WHERE result.programme='$prgs' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$secc'  ORDER BY result.coursecode ";
                   
                  $ct = mysqli_query($con, $cds);
                       $cnt = mysqli_fetch_all($ct, MYSQLI_ASSOC);
                       $Acounterv = 0;
                       $dt=0;
                       foreach ($cnt as $rowb) {
                         $Acounterv += 1;
                         
                          $codev[$Acounterv] = $rowb['coursecode'];
                         $Tt[$Acounterv]  = $rowb['title'];
                         $unv[$Acounterv]  = $rowb['unit'];
                    }
                    $kt=0;
                    
                    for ($kt=1; $kt <=$Acounterv ; $kt++) {
                     $dt+=1;
                      ?>
                        <tr>
                          <td class="talk"><?php echo htmlentities($dt) ?></td>
                          <td  class="talk"style='font-size: 12px;'><?php echo htmlentities($codev[$kt]); ?></td>
                          <td  class="talk"style='font-size: 11px;width: 300px;font-weight:bold;'><?php echo htmlentities($Tt[$kt]); ?></td>
                          <td class="talk" align="center"><?php echo htmlentities($unv[$kt]); ?></td>
                        </tr>
                      <?php
                    }
               ?>
             </table>
       <br>
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
      
      </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="float: right;">
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
<div style="margin:auto;width:450px;margin-top: 12%;" class="button">

<input type="button" class="btn btn-primary" value="Print On Page" onclick="window.print()" style="float: left;font-size: 20px;display: non;float: left;"> 
            <input type="button" class="btn btn-success" value="Print to Pdf" onclick="window.open('printdep','_blank')" style="float: left;font-size: 20px;display: non;float: right;">               
</div> 
          <?php
               }
              else{
                $showModale='true';

                }
              }
                }
            ?>        
          <!-- Ends Here Is For HOD Side -->        
          <?php
                }else{
          ?>
          <section class="section secb">
            <b style="color:red;">Sorry You Have Don't Access to this Page</b>
           </section>
          
            <?php 
             }
          ?>
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

      $sql =mysqli_query($con,"SELECT DISTINCT studentid,firstname,lastname  FROM  student WHERE studentid='$maxt'   ORDER BY studentid ASC");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        //$sur = $data['surname'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =$first." ".$las;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
}
/*Loading Grades*/
function GetGradeSystem($con)
  {
   
    $sqlper = "SELECT  * FROM gradetable";
    $result = mysqli_query($con, $sqlper);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //$rowks=mysqli_num_rows($result);
    foreach ($data as $row) {
      @$gradeCounter += 1;
      $_POST['Minscore'][$gradeCounter] =  $row["minscore"];
      $_POST['Maxscore'][$gradeCounter] =  $row["maxscore"];
      $_POST['Grade'][$gradeCounter]    =  $row["grade"];
      $_POST['point'][$gradeCounter]    =  $row["point"];
    }
    $_SESSION['gradeCounter'] = $gradeCounter;
  }

function loadcorecourse($ct,$mat,$sem,$le,$se,$pr,$con)
{
 
    $_POST['pt']=0;
  
$rowr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$mat' AND result.level='$le' AND result.programme='$pr' AND result.acadsession='$se' AND result.semester='$sem' AND result.coursecode='$ct' ORDER BY result.studentid ASC"));
if ($rowr!='') {
    $Totaledk=((int)$rowr["ca"]+(int)$rowr["exam"]);
    $coud=$rowr['coursecode'];
    $std=preg_replace('/\s+/', '',$rowr['status']);
    $unt=$rowr['unit'];
    //if ($std!="") {
    //if ($std=="Y" OR $std=="Approved") {
  if (($std=='Y' OR $std=='Approved') AND $Totaledk!='0') {
  GetGradeSystem($con);
    for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                              if ($Totaledk <= $_POST['Maxscore'][$j] && $Totaledk >= $_POST['Minscore'][$j])
                               {
                                      $_POST['pt']=$_POST['point'][$j]*$unt;
                                      $_POST['G']= $_POST['Grade'][$j];
                               }


                  }
                    
                    $_POST['couif']=$Totaledk." | ".$_POST['G']." | ".$_POST['pt'];
   }elseif ($std=='Pending' AND $Totaledk=='0') {
    
    $_POST['couif']='Not-App';
   }
   elseif ($Totaledk=='0' AND $std!='') {
    $_POST['couif']='F A';
   }elseif ($std=='' AND $Totaledk=='0') {
     $_POST['couif']='Not-Upload';
   }elseif ($std=='' AND $Totaledk!='0') {
     $_POST['couif']='Not-Submitted';
   }
   elseif ($std=='Pending') {
    
    $_POST['couif']='Not-Approved';
   }
   }elseif ($rowr=='') {
  $_POST['couif']='---';
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
?>