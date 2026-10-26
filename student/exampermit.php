<?php 
session_start();
$harr=$_SESSION["staffid"];
$connection = TRUE;
// Enxternal Files
include "../include/dbconfig.php";
include"includes/genSubmit.php";
include'dataco.php';
// Curent Session Loading Out

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session FROM resultsession WHERE description='Current Session'"));
$sems=$dat['semester'];
$ses=$dat['session'];

$std=mysqli_fetch_assoc(mysqli_query($con,"SELECT level,programme FROM studentregsem WHERE semester='$sems' AND session='$ses' AND studentid='".$_SESSION['staffid']."' "));
$lv=$std['level'];
$prg=$std['programme'];

$mater=$sems ." ".$ses;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen">
        <link rel="stylesheet" href="css/print.css" media="print">
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script src="js/modernizr/modernizr.min.js"></script>
        <style type="text/css">
        .cotable{
                width: 600px;
                margin:auto;
            }

            .cotable td, .cotable th{
                border: 1px solid black;
            }
            @media screen{
            .lines{
              display: none;
            }
            }
    </style>
</head>
<body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('includes/topbar.php');?>   
          <!-----End Top bar>-->
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

<!-- ========== LEFT SIDEBAR ========== -->
<?php include('includes/leftbar.php');?>   
<div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title"><?php echo "STUDENT EXAM PERMIT PRINT SETUP" ?></h4>
                                </div>
                                
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    
                                </div>
                               
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section sections">
                            <div class="container-fluid">

                             

                              

                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5></h5>
                                                </div>
                                            </div>
          
  
                                            <div class="panel-body">

                                                <form method="post">
                                                    
                                                 <div class="form-group has-success">
                                                        <label for="success" class="control-label">SESSION:</label>
                                                        <div class="">
                                                        <select name="section" class="form-control">
                                                            
                                                              <option value="<?php echo htmlentities($ses) ?>"><?php echo htmlentities($ses) ?></option>   
                                                        </select>
                                                    </div>
                                                    </div>
                                                 
                                                      <div class="form-group has-success">
                                                        <label for="success" class="control-label">SEMESTER:</label>
                                                        <div class="">
                                                           <select name="semest" class="form-control">
                                                            <option value="<?php echo htmlentities($sems) ?>"><?php echo htmlentities($sems) ?></option>

                                                        </select>
                                                          <span class="help-block" style="color: red;font-size: 13px;"> <?php echo "$emptys"; ?></span>
                                                        </div>
                                                    </div>
                                                     <div class="form-group has-success">
                                                        
                                                        <label for="success" class="control-label">PROGRAMME:</label>
                                                        <div class="">
                                                           <select name="programme"   class="form-control">
                                                            <?php 
                                                            $res = mysqli_query($con, "SELECT * FROM student WHERE studentid='".$_SESSION['staffid']."'");
                                                            while ($row = mysqli_fetch_array($res)) {

                                                                ?>
                                                                <option value="<?php echo  $row["programme"]; ?>"><?php echo  $row["programme"]; ?></option>
                                                            <?php

                                                            }
                                                            ?>
                                                            

                                                            </select>
                                                          
                                                        </div>
                                                            
                                                    </div>
                                                    <div class="form-group has-success">
                                                        <label for="success" class="control-label">LEVEL:</label>
                                                        <div class="">
                                                            <select name="level" class="form-control"  >
                                                            <option value="<?php echo htmlentities($lv);?>"><?php echo htmlentities($lv); ?></option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                    
                                                       
                                                     
                                                    <div class="form-group has-success">

                                                        <div class="">
                                                            <button type="submit" name="examper" class="btn btn-success btn-label-right btn-label btn-label-right" >Proceed</button>
                                                    </div>


                                                    
                                                </form>

                                              
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-8 col-md-offset-2 -->
                                </div>
                                <!-- /.row -->

                               
                               

                            </div>
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->
                        <?php 
                        if(isset($_POST['examper']))
                        {
                        $prd=mysqli_escape_string($con,$_POST['programme']);
                        $secc=mysqli_escape_string($con,$_POST['section']);
                        $leve=mysqli_escape_string($con,$_POST['level']);
                        $semsx=mysqli_escape_string($con,$_POST['semest']);

                        $sql=mysqli_query($con,"SELECT * FROM studentregsem JOIN result ON studentregsem.studentid=result.studentid AND studentregsem.semester=result.semester AND studentregsem.session=result.acadsession AND studentregsem.programme=result.programme AND studentregsem.level=result.level WHERE studentregsem.programme='$prd' AND studentregsem.level='$leve' AND studentregsem.status='Approved' AND studentregsem.session='$secc' AND studentregsem.semester='$semsx' AND studentregsem.studentid='".$_SESSION['staffid']."' ");
                        $rows = mysqli_num_rows($sql);
                        if($rows > 0){
                        ?>
                        <section class="section">
                    <div class="container-fluid">
                            
                            <div class="row breadcrumb-div">
                                <div style="width: 800px; margin: auto;">
                                    <table cellpadding=0 cellspacing=0  class="datas" style="width:1000px">
                      
                    <tr>
                     
                      <td rowspan=2 align=left valign=top><img width=130 height=130 
                      src="<?php echo $logo?>" alt="Institution Photo"></td>
                     </tr>

                    <tr>
                      
                      <td style="font-weight: bolder;text-align: center;">
                          <span style="font-size: 20.5px;text-align: center;width: 150px;"><?php echo htmlentities($orgname) ?> &#13;&#10;</span>
                          <br>
                          <span style="font-size: 15.5px;text-align: center;width: 150px;">Exam Permit &#13;&#10;</span>
                          <br>
                          <span style="font-size: 15.5px;text-align: center;width: 150px;"> <?php echo htmlentities($mater)?> Academic Session &#13;&#10;</span>
                          <br>
                          <span style="font-size: 15.5px;text-align: center;width: 150px;"> www.edocoe.edu.ng &#13;&#10;</span>
                      </td>
                     </tr>

                    </table>
                    <p class="lines" style="width: 800px;">_______________________________________________________________________________________</p>
                    <span><span style='position:absolute;margin-left:
                    530px;margin-top:3px;width:150px;height:150px'><img width=150 height=150
                    src="<?php echo $passport ?>" alt=" "></span>Student Name: <b style='text-transform: uppercase;'><?php echo htmlentities($fulname) ?></b></span>
                    <br>
                    <span class=MsoNormal>Matric No: <b><?php echo htmlentities($_SESSION['staffid']); ?></b></span>
                    <br>
                    <span class=MsoNormal>Email: <b><?php echo htmlentities($email) ?></b></span>

                    <br><span class=MsoNormal>Faculty: <b><?php echo htmlentities($facu) ?> </b></span>
                    <br>
                    <span class=MsoNormal>Programme: <b><?php echo htmlentities($prd) ?></b></span>
                    <br>
                    <span class=MsoNormal>Level: <b><?php echo htmlentities($leve); ?></b></span>
                    <!-- Ends Here -->
                    <p class=MsoNormal>&nbsp;</p>
                    <p class=MsoNormal>&nbsp;</p>
                    <table class="cotable" border=1 cellspacing=0 cellpadding=5 style="width:800px" >
                        <tr>
                            <th>#</th>
                            <th style="width: 130px">Course Code</th>
                            <th>Course Title</th>
                            <th>Supervsor Signature</th>

                        </tr>
                        <?php 
                        $que= mysqli_query($con,"SELECT * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='".$_SESSION['staffid']."' AND result.level='$leve' AND result.semester='$semsx' AND result.acadsession='$secc'  ORDER BY result.coursecode");
                    
                $sr =1;
                        while ($rows= mysqli_fetch_array($que)): ?>
                        <tr>
                            <td><?php echo $sr;?></td>
                            <td><?php echo $rows["coursecode"];?></td>
                            <td><?php echo $rows["title"];?></td>
                            <td></td>

                        </tr>
                        <?php 
                        $sr++;
                    endwhile;
                    
                    ?>
                
            
                    </table>
                    <p class=MsoNormal>&nbsp;</p>

                        <!-- <p class=MsoNormal>&nbsp;</p>

                        <p class=MsoNormal>&nbsp;</p>
                         -->
                         <div class="signt">
                        <!-- <p class=MsoNorma>&nbsp;</p> -->
                        <p class='MsoNorma'>
                        <b>This is to Certify that <i style="text-transform: capitalize;"><?php echo htmlentities($fulname) ?></i> is duly registered for the courses listed above. Kindly admit.</b></p>

                        <p class='MsoNorma'>__________________________<br>
                        <b>HoD’s Signature &amp; Date</b></p>
                        </div>
                    <div style="margin:auto;width:50px;" class="button">
                                        <input type="button" class="btn btn-success" value="Print-Detail" onclick="window.print()" style="float: left;font-size: 20px;display: non;">               
                                    </div> 
                                </div>
                               
                            </div>
                            <!-- /.row -->
                        </div>
                    </section>
                <?php 
                    }else{
                          $showModalEr='true';
                        }
                    }?>
                    </div>
                    <!-- Error Modal Box -->
            <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>There Is No Result Base On The Criteria!!! </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
</body>

<?php include 'includes/buton.php'; 

if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    } 
?>
</html>