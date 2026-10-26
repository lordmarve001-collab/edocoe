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
// $date1 = new DateTime("2021-04-30");
// $now = new DateTime();

// $diff = $date1->diff($now);

// echo 'Remaining Days:'. $diff->days ;
$error='';
if (isset($_POST['submitcd'])) 
  {
    if (empty($_POST['remark'])) {
      $showModalEr='true';
      $error='Remark';
    }elseif (empty($_POST['grade'])) {
      $showModalEr='true';
      $error='Grade';
    }else{
      $Max=mysqli_escape_string($con,$_POST['maxscore']);
      $Pt=mysqli_escape_string($con,$_POST['point']);
     $Rm=mysqli_escape_string($con,$_POST['remark']);
      $Min=mysqli_escape_string($con,$_POST['minscore']);
      $Gr=mysqli_escape_string($con,$_POST['grade']);
      if($Min>$Max) {
        $showModalr='true';
        $error='MinScore '.$Min.' Can not Greater Than MaxScore '.$Max;
      }else
      {
       //Checking to avoid Duplication
        $sel=mysqli_query($con,"SELECT * FROM gradetable WHERE maxscore='$Max' AND minscore='$Min'");
        if (mysqli_num_rows($sel) > 0)
         {
          $showModalr='true';
          $error='Datails Already Exist';
         }else
         {
            $ins=mysqli_query($con,"INSERT INTO gradetable (maxscore,minscore,point,grade,remark) VALUES ('$Max','$Min','$Pt','$Gr','$Rm')");
            if ($ins) {
              $showModal='true';
            }else
            {
              $showModalr='true';
              $error='Error Occured While Submitting';
            }
         }
    }
  }
}
// if (isset($_POST['$(editableObj)'])) {
//   $sql=mysqli_query($con,"UPDATE gradetable SET " . $_POST["column"] . " = '".$_POST["editval"]."' WHERE  maxscore='".$_POST["maxscore"]."' AND minscore='".$_POST["minscore"]."' AND point='".$_POST["point"]."' AND grade='".$_POST["grade"]."' AND remark='".$_POST["remk"]."' ");
// }
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
        <link rel="stylesheet" href="css/animate.min.css" media="screen" >
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

                    }elseif ($paych['position']=="subadmin") {
                        include('includes/subbar.php');
                  }elseif ($paych['position']=="academicaffair") {
                        include('includes/acdbar.php');
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
                                    <h2 class="title">Grading System Setup</h2>
                                
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

                  if ($paych['position']=="superadmin" OR $paych['position']=="academicaffair" OR $paych['position']=="subadmin")
                  {  
                    ?>                     
                                        
                      <div>
                          <table id="example2" class="table table-striped table-bordered"> 
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Maxscore</th>
                                  <th>Minscore</th>
                                  <th>Point</th>
                                  <th>Grade</th>
                                  <th>Remark</th>
                                 
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM gradetable ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td contenteditable="true" class="maxscore allow_only_numbers" onBlur="saveInlineEdit(this,'maxscore','<?php 
                                                echo $result["maxscore"]; ?>','<?php 
                                                echo $result["minscore"]; ?>','<?php 
                                                echo $result["point"]; ?>','<?php 
                                                echo $result["grade"]; ?>','<?php 
                                                echo $result["remark"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['maxscore']) ?></td>
                                <td contenteditable="true" class="minscore allow_only_numbers" onBlur="saveInlineEdit(this,'minscore','<?php 
                                                echo $result["maxscore"]; ?>','<?php 
                                                echo $result["minscore"]; ?>','<?php 
                                                echo $result["point"]; ?>','<?php 
                                                echo $result["grade"]; ?>','<?php 
                                                echo $result["remark"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['minscore']);?></td>
                                <td contenteditable="true" class="point allow_only_numbers" onBlur="saveInlineEdit(this,'point','<?php 
                                                echo $result["maxscore"]; ?>','<?php 
                                                echo $result["minscore"]; ?>','<?php 
                                                echo $result["point"]; ?>','<?php 
                                                echo $result["grade"]; ?>','<?php 
                                                echo $result["remark"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['point']);?></td>
                                <td contenteditable="true" class="grade" onBlur="saveInlineEdit(this,'grade','<?php 
                                                echo $result["maxscore"]; ?>','<?php 
                                                echo $result["minscore"]; ?>','<?php 
                                                echo $result["point"]; ?>','<?php 
                                                echo $result["grade"]; ?>','<?php 
                                                echo $result["remark"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['grade']);?></td>
                                <td contenteditable="true" class="remark" onBlur="saveInlineEdit(this,'remark','<?php 
                                                echo $result["maxscore"]; ?>','<?php 
                                                echo $result["minscore"]; ?>','<?php 
                                                echo $result["point"]; ?>','<?php 
                                                echo $result["grade"]; ?>','<?php 
                                                echo $result["remark"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['remark']);?></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                <?php
                 }
                else{   ?>
                    <p style="color:darkred;font-size: 16px;font-weight: bold;text-align: justify;">Sorry Dear: <b style="color:blue;"><?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page.</p>
                       
                <?php  }
                
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
                            <p>Please Select <?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModalr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
            <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Details Successfully Submitted</p>
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
            if(!empty($showModalEr)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModalEr").modal("show");
                });
            </script>';
            }
            if(!empty($showModalr)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModalr").modal("show");
                });
            </script>';
            }
            if(!empty($showModal)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModal").modal("show");
                });
            </script>';
            }
        ?>

        <script type="text/javascript">
          function saveInlineEdit(editableObj,column,maxi,mini,pint,grad,rem)
         {
          $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
          
           $.ajax({
           url: "gradedit",
            type: "POST",
            data:'column='+column+'&editval='+$(editableObj).text()+'&maxscore='+maxi+'&minscore='+mini+'&point='+pint+'&grade='+grad+'&remk='+rem,
            success: function(data){
              $(editableObj).css("background","#FDFDFD");
              //alert(data);
            }
          });
          
            }

          function highlightEdit(editableObj)
            {
          //     
            $(editableObj).css("background","#FFF");
            }

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
      
    </body>
</html>
<?php } ?>
