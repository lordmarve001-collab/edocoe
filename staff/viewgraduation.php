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
        <link rel="stylesheet" href="css/print.css" media="print">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <style>
            @media print{
                .form-horizontal{
                 display:none;  
                }
                td{
                    text-transform:capitalize;
                    padding:15px;
                }
                .page1{
                        page-break-before: always !important; 
                      }
                .news{
                    font-size: 23px;
                    margin-left: 23px;
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
                   <?php //include('includes/hodbar.php');?>  
                    <!-- /.left-sidebar -->
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
                    elseif ($paych['position']=="academicaffair") {
                        include('includes/acdbar.php');
                    }
                    elseif ($paych['position']=="exams") {
                        include('includes/exambar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Graduation List Student</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                       <?php
                       $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                        if ($paych['position']=='hod') {
                        ?> 
                            <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
                                                
                                                <section class="section">
                                                    <form method="POST" class="form-horizontal">
                                                         
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
                                                                                                                 
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="query" class="btn btn-primary">View Student</button>
                        </div>
                    </div>                    
                </form>
                    <?php
                    if (isset($_POST['query'])) {
                        if(empty($_POST['section'])){
                            $showModalx='true';
                            $error='Please Select Session';
                        }
                        else{
                            //$ccode=mysqli_escape_string($con,$_POST['courseallo']);
                            $sect=mysqli_escape_string($con,$_POST['section']);
                            $sql=mysqli_query($con,"SELECT * FROM graduatelist WHERE  acadsession='$sect' AND hodapprove='1' ");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                            $_SESSION['section']=$sect;
                            try {
                            $tr=mysqli_query($con,"SELECT * FROM programs WHERE did='$deplt'");       
                            $res = mysqli_fetch_all($tr,MYSQLI_ASSOC);
                            foreach($res as $rt)
                            {
                                $prgs=$rt['pname'];

                                            $sqlcl="SELECT DISTINCT programme FROM graduatelist WHERE  acadsession='$sect' AND programme='$prgs' ORDER BY programme";
                                   
                                           $stsl = mysqli_query($con,$sqlcl);
                                                $counterl = mysqli_fetch_all($stsl,MYSQLI_ASSOC);
                                           $Acounterl=0;
                                           foreach($counterl as $rowal)
                                           {
                                             $Acounterl+=1;
                                             
                                             $Lev[$Acounterl]  =$rowal['programme'];
                                           }
                                            $tk=0;
                                            for($tk=1;$tk<=$Acounterl; $tk++)
                                            {
                                        ?>
                                        <div class="<?php echo $Lev[$tk]?'page1':''?>">
                                     <div class="logos" style="width: 180px;margin: auto;">
                                            <span><img src='<?php echo $logo ?>' id="images1"  style="width: 90px;height: 90px;"></span>
                                        </div>
                                        <div style="width:360px;margin: auto;">
                                            <span align="center" class="news" style="margin-top: 1%; font-size: 15px;font-weight: bolder;"><?php echo htmlentities($orgname)?>, <?php echo htmlentities($schadress)?> </span><br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="text-transform: uppercase;font-weight: bolder;margin-left:14px;">Graduation List</span>
                                        </div>
                                        <div>
                                                <p class=MsoNormal style='margin-left:90.35pt;'><span style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:106%;font-family:
                                                        "Times New Roman",serif;font-weight: bolder;'>Academic Session: <?php echo htmlentities($sect) ?> <span style='mso-tab-count:2'></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Programme: <?php echo htmlentities($Lev[$tk]) ?>
                                                        </p>
                                        </div>
                                        <table class='table cotable'  border="1" cellspacing="0" cellpadding="2">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student_ID</th>
                                                    <th>Name</th>
                                                    
                                                </tr>  
                                                <?php 
                                                $k=0;
                                                $sql="SELECT * FROM graduatelist WHERE acadsession='$sect'  AND programme='".$Lev[$tk]."' ORDER BY programme ASC";
             
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
                                                    <td><?php echo htmlentities( $row['studentid'] )?></td>
                                                    <td style="font-size: 18px;text-transform: capitalize;"><?php echo htmlentities( $_POST["harrd"])?></td>
                                                </tr>
                                               <?php }?>
                                               </table>
                                               <div>
                                            <!--Department Detail-->
                                            <div>
                                            
                                            <div><br><span><strong style="text-transform: capitalize;">Head Of Department: <?php echo htmlentities($fullname);?> </strong></span><br><br><span><strong>Hod Signature:_____________________________</strong></span><br></div>
                                            
                                        </div>
                                            
                                        </div>
                                            
                                     <?php
                                    }
                                 }//End of For Each
                            } catch (Exception $e) {
                                
                            }
                
                            ?>
                            <div style="margin:auto;width:50px;margin-top:5%;" class="button">
                                            <input type="button" value="Print-Detail" class="btn btn-primary" onclick="window.open('printgra','_blank')" style="float: left;font-size: 20px;display: non;">               
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
   </div>
                        <?php 
                        }elseif ($paych['position']=='academicaffair' OR $paych['position']=='superadmin' OR $paych['position']=='exams'){

                        ?>
                        <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
                                                
                                                <section class="section">
                                                    <form method="POST" class="form-horizontal">
                                                         
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
                                                                                                                 
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="querys" class="btn btn-primary">View Student</button>
                        </div>
                    </div>                    
                </form>
                    <?php
                    if (isset($_POST['querys'])) {
                        if(empty($_POST['section'])){
                            $showModalx='true';
                            $error='Please Select Session';
                        }
                        else{
                            
                            $sect=mysqli_escape_string($con,$_POST['section']);
                            $sql=mysqli_query($con,"SELECT * FROM graduatelist WHERE acadsession='$sect' AND hodapprove='1' AND deanapp='1' ");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                            $_SESSION['section']=$sect;
                            try {
                                 
                                            $sqlcl="SELECT DISTINCT programme FROM graduatelist WHERE  acadsession='$sect' AND deanapp='1' ORDER BY programme";
                                   
                                           $stsl = mysqli_query($con,$sqlcl);
                                                $counterl = mysqli_fetch_all($stsl,MYSQLI_ASSOC);
                                           $Acounterl=0;
                                           foreach($counterl as $rowal)
                                           {
                                             $Acounterl+=1;
                                             $Lev[$Acounterl]  =$rowal['programme'];
                                           }
                                            $tk=0;
                                            for($tk=1;$tk<=$Acounterl; $tk++)
                                            {
                                        ?>
                                        <div class="<?php echo $Lev[$tk]?'page1':''?>">
                                     <div class="logos" style="width: 180px;margin: auto;">
                                            <span><img src='<?php echo $logo ?>' id="images1"  style="width: 90px;height: 90px;"></span>
                                        </div>
                                        <div style="width:360px;margin: auto;">
                                            <span align="center" class="news" style="margin-top: 1%; font-size: 15px;font-weight: bolder;"><?php echo htmlentities($orgname)?>, <?php echo htmlentities($schadress)?> </span><br>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="text-transform: uppercase;font-weight: bolder;margin-left:14px;">Graduation List</span>
                                        </div>
                                        <div>
                                                <p class=MsoNormal style='margin-left:90.35pt;'><span style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:106%;font-family:
                                                        "Times New Roman",serif;font-weight: bolder;'>Academic Session: <?php echo htmlentities($sect) ?> <span style='mso-tab-count:2'></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Programme: <?php echo htmlentities($Lev[$tk]) ?>
                                                        </p>
                                        </div>
                                        <table class='table cotable'  border="1" cellspacing="0" cellpadding="2">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student_ID</th>
                                                    <th>Name</th>
                                                    
                                                </tr>  
                                                <?php 
                                                $k=0;
                                                $sql="SELECT * FROM graduatelist WHERE acadsession='$sect'  AND programme='".$Lev[$tk]."' ORDER BY programme ASC";
             
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
                                                    <td><?php echo htmlentities( $row['studentid'] )?></td>
                                                    <td style="font-size: 18px;text-transform: capitalize;"><?php echo htmlentities( $_POST["harrd"])?></td>
                                                </tr>
                                               <?php }?>
                                               </table>
                                               <div>
                                            <strong>
                                                <?php 
                                                    regd($con);

                                                    echo htmlentities(ucwords($_POST['regname']));
                                             ?> <br />
                                                <em>Registrar
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</em><br />
                                            <span class="style9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br />

                                              </strong>
                                            </div>
                                            
                                     <?php
                                    }
                                 
                            } catch (Exception $e) {
                                
                            }
                
                            ?>
                            <div style="margin:auto;width:50px;margin-top:5%;" class="button">
                                            <input type="button" value="Print-Detail" class="btn btn-primary" onclick="window.open('printgra','_blank')" style="float: left;font-size: 20px;display: non;">               
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
                                                   
                                                    <div class="panel-body">
                                                        <p style="font-size:17px;text-align: justify;">Dear:<b style="color:green;"> <?php echo htmlentities(ucwords($fullname)) ?></b> You Don't Have Access to this Page Kindly Contact the System Web Manager At The ICT Office </p>
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

// function to load the registrar Name
function regd($con){
    $dts=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people JOIN staffprive ON staffprive.staffid=people.staffid WHERE people.status='active' AND staffprive.position='registrar'"));
    if ($dts!='') {
        $fulna=$dts['title'].". ".$dts['firstname']." ".$dts['lastname'];
        $_POST['regname']=$fulna;
    }else{
        $_POST['regname']='No Name';
    }
}
 ?>
