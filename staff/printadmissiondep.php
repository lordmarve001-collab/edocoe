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
        <link rel="stylesheet" href="css/animate.min.css" media="screen">
        <style>
            @media Print{
                .form-horizontal{
                 display:none;  
                }
                td{
                    text-transform:capitalize;
                    padding:15px;
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
                  <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="registrar")
                  {
                     include('includes/admoff.php');

                  }elseif ($paych['position']=="admission") {
                        include('includes/admsec.php');
                  }
                   else{
                  include('includes/leftbar.php');
                        }
                    ?> 
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Admission Printing List By Department</h2>
                                
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
                                                
                                                <section class="section">
                                                    <form method="POST" class="form-horizontal">
                                                         <div class="form-group">
                            <label for="default" class="col-sm-2">Department:</label>
                             <div class="col-sm-10">
                             <select name="courseallo" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Department</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM department ORDER BY dept");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["dept"]; ?>"><?php echo htmlentities($row['dept']) ?></option>
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
                        }
                        else{
                            $ccode=mysqli_escape_string($con,$_POST['courseallo']);
                            $sect=mysqli_escape_string($con,$_POST['section']);
                            $sql=mysqli_query($con,"SELECT * FROM applicant WHERE programme='$ccode' AND session='$sect'");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                            try {
                                $counter=0;
                                 $sqlc="SELECT DISTINCT programme FROM applicant WHERE programme='$ccode' AND session='$sect'";
                                   
                                   $sts = mysqli_query($con,$sqlc);
                                        $counter = mysqli_fetch_all($sts,MYSQLI_ASSOC);
                                   $Acounter=0;
                                   foreach($counter as $rowa)
                                   {
                                     $Acounter+=1;
                                     $programe[$Acounter]  =$rowa['programme'];
                                     //$Lev[$Acounter]  =$rowa['level'];
                                   }
                                   $t=0;
                                        for($t=1;$t<=$Acounter; $t++)
                                    {
                                     
                                     $count=0;?>
                                     <div class="<?php echo $programe[$t]?'page1':''?>">
                                     <div class="logos" style="width: 180px;margin: auto;">
                                            <span><img src='<?php echo $logo ?>' id="images1"  style="width: 90px;height: 90px;"></span>
                                        </div>
                                        <div style="width:350px;margin: auto;">
                                            <span align="center" style="margin-top: 1%; font-size: 18px;font-weight: bolder;"><?php echo htmlentities($orgname) ?></span>
                                            <span style="text-align: center;font-size: 12px;font-weight: bolder;margin-left: 12px;"></span><br>
                                            <!-- <span style="text-align: center;font-size: 12px;font-weight: bolder;margin-left: 46px;">Bayelsa State</span><br><br> -->
                                            <span style="text-transform: uppercase;font-weight: bolder;text-align: center;margin-left: 34px;"> ADMISSION LIST SHEET</span>
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
                                                <p class=MsoNormal style=''><span style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:106%;font-family:
                                                        "Times New Roman",serif;font-weight: bolder;margin-left:200px;'>Academic Session: <?php echo htmlentities($sect) ?> <span style='mso-tab-count:2'></span>&nbsp;&nbsp;&nbsp;Programme: <?php echo htmlentities($programe[$t]) ?>
                                                        </p>
                                            </div>
                                            <table class='table cotable'  border="1" cellspacing="0" cellpadding="5">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Application ID</th>
                                                    <th>Full Name</th>
                                                </tr>
                                            <?php 
                                            $sqlcl="SELECT DISTINCT level FROM result WHERE coursecode='$ccode' AND acadsession='$sect' AND programme='$programe[$t]' ORDER BY level";
                                   
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
                                                $sql="SELECT * FROM result WHERE acadsession='$sect' AND coursecode='$ccode' AND level='$Lev[$tk]' AND programme='$programe[$t]' ORDER BY level ASC";
             
                                                  $_POST['totalunit']=0;
                                                   $st = mysqli_query($con,$sql);
                                                    $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
                                               foreach($count as $row)
                                               {
                                                $k+=1;
                                                  
                                                 stuname($row['studentid']);
                                                
                                                ?>
                                                <tr style="font-weight: bolder;">
                                                    <td style="width:10px;"><?php echo $k ?></td>
                                                    <td><?php echo $row['studentid'] ?></td>
                                                    <td><?php echo $_POST["harrd"]?></td>
                                                    <td><?php echo $row['ca'] ?></td>
                                                    <td><?php echo $row['exam']  ?></td>
                                                    <td><?php echo $row['exam'] + $row['ca'] ?></td>
                                                    <td><?php echo GetGrade($row['ca'] + $row['exam']) ?></td>
                                                </tr>
                                               <?php }
                                            }?>
                                            </table>
                                            <br>
                                            <div>
                                            <!--Department Detail-->
                                            <div>
                                            <!--Load out Staffs Name taking Course-->
                                            <?php 
                                                $sqlx="SELECT * FROM allocatecourse WHERE coursecode='$ccode' AND acadsession='$sect'";
                                                $res = mysqli_query($con,$sqlx);
                                                 $datax = mysqli_fetch_all($res,MYSQLI_ASSOC);
                                                 foreach($datax as $row){
                                                    @$gradeCounter+=1;
                                                    $_POST['Minscore'][$gradeCounter] =  $row["staffid"];

                                                    lecturnames($_POST['Minscore'][$gradeCounter]);

                                                 }
                                                 
                                            ?>
                                            <div><br><span><strong style="text-transform: capitalize;">Head Of Department:_________________________ </strong></span><br><br><span><strong>Hod Signature:_____________________</strong></span><br></div>
                                            
                                        </div>
                                            <!--Try to upgrade to Load the hod name out  hodname($row['coursetitle']);-->
                                            
                                        </div>
                                            </div>
                                            
                                     <?php
                                    }
                                 
                            } catch (Exception $e) {
                                
                            }
                
                            ?>
                            <div style="margin:auto;width:50px;margin-top:5%;" class="button">
                                            <input type="button" value="Print-Detail" class="btn btn-primary" onclick="window.print()" style="float: left;font-size: 20px;display: non;">               
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
<!-- /.content-container -->
</div>
<!-- /.content-wrapper -->
</div>
<div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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
$_POST['totalPoint']=0;
  function GetGrade($scores)
  {
     GetGradeSystem();
                          for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++)
                            {
                                if ($scores <= $_POST['Maxscore'][$i] && $scores >= $_POST['Minscore'][$i])
                                {
                                  $_POST['myGrade']   =   $_POST['Grade'][$i];
                                                   
                                }
                            }
                        
                return   $_POST['myGrade'];
  }
  function Getrem($scoress)
  {
     GetGradeSystem();
                          for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++)
                            {
                                if ($scoress <= $_POST['Maxscore'][$i] && $scoress >= $_POST['Minscore'][$i])
                                {
                                  $_POST['myGrades']   =   $_POST['Remark'][$i];
                                                   
                                }
                            }
                        
                return   $_POST['myGrades'];
  }
 function GetPoints($score)
    {
    $_POST['totalPoint'] = 0;
        GetGradeSystem();
        
        for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++) {
            if ($score <= $_POST['Maxscore'][$i] && $score >= $_POST['Minscore'][$i]) {
                $_POST['myPoint']   =   $_POST['point'][$i];
                $_POST['totalPoint'] += $_POST['myPoint'];
            }
        }

        return   $_POST['myPoint'];
    }  

  
 function GetGradeSystem()
        {
     $connection = TRUE;
     include '../include/dbconfig.php';//Get Database accesss
     $sqlper = "SELECT  * FROM gradetable";
      $result = mysqli_query($con,$sqlper);
     $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
     //$rowks=mysqli_num_rows($result);
     foreach($data as $row){
        @$gradeCounter+=1;
        $_POST['Minscore'][$gradeCounter] =  $row["minscore"];
        $_POST['Maxscore'][$gradeCounter] =  $row["maxscore"];
        $_POST['Grade'][$gradeCounter]    =  $row["grade"];
        $_POST['point'][$gradeCounter]    =  $row["point"];
        $_POST['Remark'][$gradeCounter]    =  $row["remark"];
       }
         $_SESSION['gradeCounter']=$gradeCounter;

 }


 function stuname($maxt)
 {
    $connection = TRUE;
    include '../include/dbconfig.php';//Get Database accesss

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
 function lecturnames($staff)

 {
    $connection = TRUE;
    include '../include/dbconfig.php';
    $dfg=mysqli_query($con,"SELECT * FROM people WHERE staffid='$staff' ORDER BY staffid");
    $_POST['staNName']=0;
               while($row=mysqli_fetch_assoc($dfg))
             {
                $_POST['staNName'] += 1;
                           $_SESSION['stadt'][$_POST['staNName']] = $row["firstname"];
                           //$_SESSION['stadtc'][$_POST['staNName']] = $row["surname"];
                           $_SESSION['stadtz'][$_POST['staNName']] = $row["lastname"];
                           $_SESSION['stadtt'][$_POST['staNName']] = $row["title"];

                           $rig=$_SESSION['stadtt'][$_POST['staNName']] ." ". $_SESSION['stadt'][$_POST['staNName']] ." ".$_SESSION['stadtz'][$_POST['staNName']] ;
                           echo '<div><br><span><strong style="text-transform: capitalize;">'."Course Lecturer: "."$rig".'</strong></span><br><br>'." <span><strong>Lecturer Signature:_____________________</strong></span><br></div><br>";
             }
             $_SESSION['staNName']= $_POST['staNName'];
 }
 ?>
