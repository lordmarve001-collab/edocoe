<?php 
session_start();
//error_reporting(0);
if (!isset($_SESSION['staffid']))
    header("Location: ../logout");
$connection = TRUE;
include "../include/dbconfig.php";
include 'dataco.php';
//include"includes/genSubmit.php";
$harr = $_SESSION['staffid'];
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout.php");
}

if (isset($_POST["submitresult"])) {
    if (empty($_POST["section"])) {
        $error="Please Select Session";
        $showModalEr='true';
    }elseif (empty($_POST["semest"])) {
        $error="Please Select Semester";
        $showModalEr='true';
    }elseif (empty($_POST["level"])) {
        $error="Please Select Level";
        $showModalEr='true';
    }else{
    
    
    $sectin= mysqli_real_escape_string($con,$_POST["section"]);
    $semet= mysqli_real_escape_string($con,$_POST["semest"]);
    $level= mysqli_real_escape_string($con,$_POST["level"]);
        
$rowx =mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM `studentregsem` WHERE `studentid`='".$_SESSION['staffid']."' AND `session`='$sectin' AND `level`='$level ' AND `programme`='$progr' "));
    
if ($rowx=="") {
        $error="You have Not Payed School Fee for " .$sectin;
    $showModalEr='true';
}
elseif ($rowx!="" AND $semet=='First Semester') {
    $sql=mysqli_query($con,"SELECT * FROM result WHERE studentid='".$_SESSION['staffid']."' AND acadsession='$sectin' AND semester='$semet' AND level='$level' AND programme='$progr' AND status='Y'  AND publish='1'");
$rows = mysqli_num_rows($sql);
        if($rows > 0){
        $_SESSION['section'] = $sectin;
        $_SESSION['semest'] = $semet;
        $_SESSION['level'] = $level;
        header("location: myresult");
    }else{
        $error= "There is no result base on the criterial!!";
        $showModalEr='true';
    }
        }
        elseif ($rowx!="" AND $semet=='Second Semester'){
        
                $sql=mysqli_query($con,"SELECT * FROM result WHERE studentid='".$_SESSION['staffid']."' AND acadsession='$sectin' AND semester='$semet' AND level='$level' AND programme='$progr' AND status='Y'  AND publish='1'");
        $rows = mysqli_num_rows($sql);
                if($rows > 0){
                $_SESSION['section'] = $sectin;
                $_SESSION['semest'] = $semet;
                $_SESSION['level'] = $level;
                header("location: myresult");
            }else{
                $error= "There is no result base on the criterial!!";
                $showModalEr='true';
            }
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
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        
         <style>
        .errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
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
 <!-- /.left-sidebar -->

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title">STUDENT RESULT CHECKING SETUP</h3>
                                </div>
                                
                            </div>
                            
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section">
                            <div class="container-fluid">

                             

                              

                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
  
                                            <div class="panel-body">

                                                <form method="post">
                                                    <div class="form-group has-success">
                                                        <label for="success" class="control-label">CGPA: <?php
                                                             GetCumulative($con,$progr,$_SESSION['staffid']);
                                                             
                                                         echo htmlentities($_POST['PGP']); ?></label>
                                                        
                                                    </div> 
                                                    <div class="form-group has-success">
                                                        <label for="success" class="control-label">SECTION</label>
                                                        <div class="">
                                                            <select name="section" class="form-control" required="required" id="success">
                                                                <option selected="selected" disabled="disabled">SELECT SESSION</option>
                                                                 <?php
                                                                $res = mysqli_query($con, "SELECT  DISTINCT acadsession FROM result WHERE studentid='".$_SESSION['staffid']."' ORDER BY acadsession DESC");
                                                                while ($row = mysqli_fetch_array($res)) {

                                                                    ?>
                                                                    <option value="<?php echo  $row["acadsession"]; ?>"><?php echo  $row["acadsession"]; ?></option>
                                                                <?php

                                                                }
                                                                ?>
                                                            </select>
                                                          
                                                        </div>
                                                    </div>
                                                     <div class="form-group has-success">
                                                        <label for="success" class="control-label">SEMESTER:</label>
                                                        <div class="">
                                                           <select name="semest" class="form-control"  >
                                                            <option selected="selected" disabled="disabled">SELECT SEMESTER</option>
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
                                                     <div class="form-group has-success">
                                                        <label for="success" class="control-label">LEVEL:</label>
                                                        <div class="">
                                                            <select  name="level" class="form-control">
                                                                <option selected="selected" disabled="disabled">SELECT LEVEL</option>
                                                                 <?php
                                        $res = mysqli_query($con, "SELECT * FROM level ORDER BY levels");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                        <?php

                                        }
                                        ?>
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group has-success">

                                                        <div class="">
                                                           
                                                           <button name="submitresult" class="btn btn-success btn-label-right btn-label btn-label-right">View Result <i class="fa fa-eye"></i></button>
                                                    </div>


                                                    
                                                </form>

                                              
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-8 col-md-offset-2 -->
                                </div>
                                <!-- /.row -->

                               
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
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

                            </div>
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->

                    </div>
                    <!-- /.main-page -->

                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->

        </div>
       
    </body>
</html>
<?php 
include"includes/buton.php";
include 'ipcap.php';
if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    }
//$_POST['PGP']="";
function GetGradeSystem($con)
        {
     $gradeCounter=0;
     $sqlper = "SELECT  * FROM gradetable";
      $result = mysqli_query($con,$sqlper);
     $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
     //$rowks=mysqli_num_rows($result);
     foreach($data as $row){
        $gradeCounter+=1;
        $_POST['Minscore'][$gradeCounter] =  $row["minscore"];
        $_POST['Maxscore'][$gradeCounter] =  $row["maxscore"];
        $_POST['Grade'][$gradeCounter]    =  $row["grade"];
        $_POST['point'][$gradeCounter]    =  $row["point"];
       }
        $_SESSION['gradeCounter']=$gradeCounter;
//header("location:404");
 }

function GetCumulative($con,$prog,$std)
{
    $scoresCounterc=0;
$_POST['pointsc']=0;
$_POST['totalUnitc']=0;
  $harr= "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE studentid='$std' AND result.programme='$prog' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
  foreach($resulter as $row){
     $scoresCounterc+=1;
     $_POST['Unitc'][$scoresCounterc]=$row['unit'];
     $_POST['Codec'][$scoresCounterc]=$row['coursecode'];
     $_POST['Scoresc'][$scoresCounterc]  =(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounterc']=$scoresCounterc; 

  //Compute CUMULATIVE
  GetGradeSystem($con);// Graing System
                        for($k=1; $k<=$_SESSION['scoresCounterc']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scoresc'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scoresc'][$k] >= $_POST['Minscore'][$j])
                                 {
                                    $_POST['Gc'][$k] = $_POST['Grade'][$j];
                                    $_POST['Pc'][$k] = $_POST['point'][$j];
                                    $_POST['pointsc']+=$_POST['point'][$j] * (int)$_POST['Unitc'][$k];
                                   
                                 }
                                $_POST['totalUnitc']+=(int)$_POST['Unitc'][$k];
                          }

                          
                          if($_POST['pointsc']!=0 && $_POST['totalUnitc']!=0)
                              {
                               $_POST['PGP']=number_format(($_POST['pointsc']/$_POST['totalUnitc']),2);//computer previous GP
                              } 
                              else if($_POST['pointsc']==0 && $_POST['totalUnitc']==0){
                                 $_POST['PGP']="0.00";
                              } 
    }
 ?>