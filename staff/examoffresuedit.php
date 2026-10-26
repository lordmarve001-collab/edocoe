<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

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
                  <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                  }elseif ($paych['position']=="lecturer") {
                        include('includes/lectbar.php');
                  }
                   else{
                  include('includes/leftbar.php');
                        }
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Department Result (Senate Approved) Editing</h4>
                                
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

                                                      if ($paych['position']=="lecturer") {
                                                    /*Retrieving the Exam Session*/
                                                 $lvd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM examoff WHERE staffid='".$_SESSION['staffid']."'"));
                                                      if ($lvd !="") 
                                                      { ?>
                                                        <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                    
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Semester:</label>
                                                         <div class="col-sm-10">
                                                         <select name="semest" class="form-control clid"required="required">
                                                                  <option selected disabled>Please select Semester</option>
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
                                                        <label for="default" class="col-sm-2 control-label">Session:</label>
                                                         <div class="col-sm-10">
                                                         <select name="sect" class="form-control clid"required="required">
                                                            <option selected disabled>Please select Session</option>  
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
                                                        <label for="default" class="col-sm-2 control-label">Course:</label>
                                                         <div class="col-sm-10">
                                                         <select name="coursecode" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Course</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM course  WHERE department='$deplt' ORDER BY coursecode ");
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
                                                            <button type="submit" name="query" id="submit" class="btn btn-primary">View Result</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                                      <?php 
                                                      }else{
                                                        ?>
                                                        <p style="color:darkred;">You are not the exam officer Thank You</p>
                                                        <?php
                                                      }

                                                      }
                                                       else{
                                                        ?>
                                                        <p style="color: red;font-size: 15px;">Sorry Dear: <?php echo htmlentities($fullname)?> You Have Don't Access to this Page.</p>
                                                        <?php
                                                          }
                                                ?>
                           <?php
                        $error='';
                            if (isset($_POST['query'])) {
                                if (empty($_POST['coursecode'])) {
                                    $showModall = "true";
                                    $error='Please Select Course';
                                }elseif (empty($_POST['semest'])) {
                                   $showModall = "true";
                                    $error='Please Select Semester';
                                }elseif (empty($_POST['sect'])) {
                                    $showModall = "true";
                                    $error='Please Select Session';
                                } else{
                                $ccode=mysqli_escape_string($con,$_POST['coursecode']);
                                $ses=mysqli_escape_string($con,$_POST['sect']);
                                $sems=mysqli_escape_string($con,$_POST['semest']);

                                $sel=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM reupreqst  WHERE semester='$sems' AND acadsession='$ses' AND coursecode='$ccode'"));
                                if ($sel=='') {
                                    $showModall = "true";
                                    $error="The results for ".$ccode." of ".$ses." And ".$sems." have already been approved by the HoD and cannot be edited without the Dean's Consent";
                                } else if($sel['status']=='Pending'){
                                    $showModall = "true";
                                    $error='The request to edit '.$ccode.' of '.$sems.' in '.$ses.' has not yet been approved by the Dean';
                                }else{
                                 
                            $codi=mysqli_query($con,"SELECT * FROM result JOIN reupreq ON result.studentid=reupreq.studentid AND result.coursecode=reupreq.coursecode WHERE result.coursecode='$ccode' AND result.semester='$sems' AND result.acadsession='$ses' AND reupreq.status='Approved' AND result.status='Y' AND result.publish='1'");
                                if (mysqli_num_rows($codi)>0) {
                                    ?>
                            <form method="POST">
                                <div style="font-weight: bolder;color: red;">
                                    <h2>Please Note</h2>
                                    <ol>
                                        <li>The System Automatic Save</li>
                                        <li>When You Are Done Click Complete</li>
                                        <li>Adhere To Prompt Instruction</li>
                                        
                                        <li>Course Code: <b style="color: blue"><?php echo htmlentities($ccode) ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Academic Session: <b style="color: blue"><?php echo htmlentities($ses) ?></b> 
                                        </li>


                                        <li>Course Title: <b style='color: blue;'><?php 
                                            $cos=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$ccode'"));
                                        echo htmlentities($cos['title']) ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Semester: <b style="color: blue"><?php echo htmlentities($sems) ?></b> </li>
                                    </ol>
                                </div>
            <table  id="example2" class="table table-striped table-bordered " cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>CA</th>
                    <th>Exam</th>
                    
                </tr>
                </thead>
                <tbody>
                <?php
                    $sql = "SELECT * FROM result JOIN reupreq ON result.studentid=reupreq.studentid AND result.coursecode=reupreq.coursecode AND result.acadsession=reupreq.acadsession WHERE result.coursecode='$ccode' AND result.semester='$sems' AND result.acadsession='$ses' AND reupreq.status='Approved' AND result.status='Y' AND result.publish='1'";
                    $query = mysqli_query($con,$sql);
                    $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                    $cnt=1;
                    
                    foreach($results as $result)
                    {   
                         $resu=mysqli_fetch_assoc(mysqli_query($con,"SELECT cascore,examscore FROM curriculum WHERE coursecode='".$result['coursecode']."' AND programme='".$result['programme']."' "));
                            if ($resu=='') {
                                $caf=30;
                                $xaf=70;
                            }else{
                            $caf=$resu['cascore'];
                            $xaf=$resu['examscore'];
                            }
                        stuname($con,$result['studentid']);
                        ?>
                        
                    <tr class="table-row" id="table-row-<?php echo $result["studentid"]; ?>">
                     <td class="td"><?php echo htmlentities($cnt);?></td>
                        <td class="td"><?php echo htmlentities($result['studentid']) ?></td>
                        <td class="td" style="text-transform: capitalize;"><?php echo htmlentities(ucwords($_POST["harrd"]));?></td>
                        
                        <td contenteditable="true" class="ca allow_only_numbers" onBlur="saveInlineEdit(this,'ca','<?php 
                        echo $result["studentid"]; ?>','<?php 
                        echo $result["coursecode"]; ?>','<?php 
                        echo $result["acadsession"]; ?>','<?php 
                        echo $sems; ?>','<?php 
                        echo $result["level"]; ?>','<?php echo $caf; ?>')" 
                        onClick="highlightEdit(this);"><?php echo $result["ca"]; ?></td>
                        <td class="exam allow_only_numbers" contenteditable="true" onBlur="saveInlineEdit(this,'exam','<?php echo 
                        $result["studentid"]; ?>','<?php 
                        echo $result["coursecode"]; ?>','<?php 
                        echo $result["acadsession"]; ?>','<?php 
                        echo $sems; ?>','<?php 
                        echo $result["level"]; ?>','<?php echo $xaf; ?>')" onClick="highlightEdit(this);"><?php 
                        echo $result["exam"]; ?></td>
                        
                    </tr>
                   
                   <input type="hiddn" name="matricno[]" value="<?php echo $result["studentid"];?>" hidden>
                    <input type="hiddn" name="semester" value="<?php echo $sems;?>" hidden>
                
                     <input type="hiddn" name="section" value="<?php echo $result["acadsession"];?>" hidden>
                     <input type="hiddn" name="prd" value="<?php echo $result["coursecode"];?>" hidden>
                    <?php 

                    $cnt=$cnt+1;
                     
                    } ?>
                <!-- </form> -->
                   
                     </tbody>
                     </table>
                        <div id="myModal" class="modal fade">
                            <div class="modal-dialog modal-confirm">
                                <div class="modal-content">
                                    <div class="modal-header flex-column">
                                                          
                                        <h4 class="modal-title w-100">Are you sure?</h4>    
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Do you really want to Submit Results? This process cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">NO</button>
                                        
                                            <button name="complete" class="btn btn-primary">YES</button>
                                        
                                    </div>
                                 
                                </div>
                            </div>
                        </div>               

                        <div>
                        </form>                                                   
                           <button class="btn btn-success" type="button"  data-toggle="modal" data-target="#myModal" style="float: right;"><i class="fa fa-upload">Complete Updating</i></button> 
                       
                        </div>
                   
                            <?php
                    
                        }else{
                            $showModall='true';
                            $error='No Student result is available for editing for this  Course '.$ccode;
                        }
                        
                    }
                }
            }

            /*For Submiting*/
            if (isset($_POST['complete'])) {


                
                  $section=mysqli_escape_string($con,$_POST['section']);
                  $semester=mysqli_escape_string($con,$_POST['semester']);
                  $prd=mysqli_escape_string($con,$_POST['prd']);
                  //sending the array result
                  if (isset($_POST['matricno'])) {
                foreach ($_POST['matricno'] as $checks) {
                  
                  $update=mysqli_query($con,"UPDATE reupreq SET status= 'Y' WHERE studentid='$checks' AND acadsession='$section' AND semester='$semester' AND coursecode='$prd' ");
                  if ($update) {
                      $showModal='true';
                  }else{
                    $showModalEr='true';
                  }
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
        <!-- /.main-wrapper -->
        <!-- Error Modal Box -->
            <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Result is Successfully Updated</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>An Error Occured While Submitting</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModall" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
            <!-- Different Modal Box -->
            <div class="modal fade" id="myModalsk" role="dialog">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="float:left;color: red;">Warning Notice</h4>
                  <button type="button" class="close" data-dismiss="modal" style="float:right;">&times;</button>
                 
                </div>
                <div class="modal-body">
                  <p id="error" style="color:red"></p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
              
            </div>
          </div>
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
        ?>
        <script type="text/javascript">
           
          /*Start Here*/
        function saveInlineEdit(editableObj,column,id,codes,sesc,semes,level,cad)
         {
          $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
          if(column == "ca"){
            //alert(editableObj.innerHTML); 
                var score=$(editableObj).text();
                if(parseInt(score) > cad) // in case it is not string.
                {
                    $(document).ready(function(){
                 
                    $("#error").html("Ca Score Can't More than "+cad);
                      $('#myModalsk').modal("show");
                    
                  });
                   
                //alert("Ca Score Can't More than "+column);
                }

                else{
            var customer={
            editval : $(editableObj).text(),
            ccode:codes,
            column:column,
            studentid:id,
            sect:sesc,
            sems:semes,
            lvs:level
          }
           $.ajax({
            url: "edit",
            type: "POST",
            data:customer,
            success: function(data){
              $(editableObj).css("background","#FDFDFD");
              //alert(data);
            }
          });
          
      }
    }else if(column=="exam"){
        var score=$(editableObj).text();
        if(parseInt(score) > cad) // in case it is not string.
                {
                      $(document).ready(function(){
                 
                    $("#error").html("Exam Score Can't More than "+cad);
                      $('#myModalsk').modal("show");
                    
                  });
                }
                 else{
            var customer={
            editval : $(editableObj).text(),
            ccode:codes,
            column:column,
            studentid:id,
            sect:sesc,
            sems:semes,
            lvs:level
          }
                    $.ajax({
            url: "edit",
            type: "POST",
            data:customer,
            success: function(data){
              $(editableObj).css("background","#FDFDFD");
              //alert(data);
            }
          });
             }
            }
        }
        function highlightEdit(editableObj)
            {
          //     
            $(editableObj).css("background","#FFF");
            }

            
       </script>
       <script type="text/javascript">
        $(".allow_only_numbers").keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
              ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
              (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
</script>
<?php
        if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
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
       $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'   ORDER BY studentid ASC");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
}

?>
