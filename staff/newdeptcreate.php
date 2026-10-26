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
if (isset($_POST['submitcd'])) 
  {
    if (empty($_POST['facid'])) {
       $showModalEr='true';
       $error='Faculty';
      }else{
      $fid=mysqli_escape_string($con,$_POST['facid']);
      $fna=mysqli_escape_string($con,$_POST['pname']);
      $dept=trim(mysqli_escape_string($con,$_POST['depts']));
       //Checking to avoid Duplication
        $sel=mysqli_query($con,"SELECT * FROM department WHERE deptid='$dept'");
        if (mysqli_num_rows($sel) > 0)
         {
          $showModalr='true';
          $error='Department of '.$fna.' Already Exist';
         }else
         {
            $ins=mysqli_query($con,"INSERT INTO department (deptid,dept,deptc,fid) VALUES ('$fid','$fna','$dept','$fid')");
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
                                <div class="col-md-6">
                                    <h2 class="title">Department Creation Setup</h2>
                                
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
                                                
                                       <section class="section newsect">
                                           <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                              
                                                      <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">
                                                           School ID:
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <select name="facid" class="form-control">
                                                              <option disabled="disabled" selected="selected">Please Select School</option>
                                                              <?php
                                                              $res = mysqli_query($con, "SELECT * FROM faculty ORDER BY fname");
                                                              while ($row = mysqli_fetch_array($res)) {

                                                                  ?>
                                                                  <option value="<?php echo  $row["fid"]; ?>"><?php echo  $row["fname"]; ?></option>
                                                              <?php

                                                              }
                                                              ?>
                                                            </select>
                                                        </div>
                                                      </div>
                                                      
                                                      <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">
                                                           Department Name:
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <input type="text"  name="pname" class="form-control clid" required="required">
                                                        </div>
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">
                                                           Depart short code:
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <input type="text"  name="depts" class="form-control clid" placeholder="e.g BED for Business Education" required="required">
                                                        </div>
                                                      </div>
                                              
                                                     <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcd" class="btn btn-primary">Submit Data</button>
                                                        </div>
                                                    </div>
                                                  
                                            </form>
                                        </section>
                                        
                      <div>
                          <table id="example" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                 
                                  <th>Department Name</th>
                                  <th>Department Short Code</th>
                                  <th>School Code</th>
                                  <th>Action</th>
                                  
                                 
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM department ORDER BY dept";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['dept']);?></td>
                                <td><?php echo htmlentities($result['deptc']);?></td>
                                <td><?php echo htmlentities($result['deptid']);?></td>
                                <td>
                                    <button class="btn btn-success"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
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
                <div class="modal-dialog modal-dialog-scrollable" role="document">
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
           url: "gradedit.php",
             //url: "gradesystem.php",
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
