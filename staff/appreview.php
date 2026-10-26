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
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='exam processing'"));
$sems=$dat['semester'];
$ses=$dat['session'];
$dead=$dat['deadline'];


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
            .td{
                 cursor: not-allowed;
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

                      if ($paych['position']=="hod")
                      {
                        
                         include('includes/hodbar.php');

                      }
                     else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Department Result(s) Approved Setup</h2>
                                
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
                      $paychv=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                      if ($paychv['position']=="hod")
                      {
                        
                     ?>
                    
                        <section class="section">
                          <table class="table table-striped table-bordered tjks" border="1" width="100%" cellspacing=2 cellpadding=4>
                                                   <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Session</th>
                                                        <th>Course Code</th>
                                                        <th>Semester</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead> 
                                            <tbody>
                                                <?php
                                                  
                                                $sql = "SELECT DISTINCT allocatecourse.acadsession,allocatecourse.coursecode,allocatecourse.resultsatus,allocatecourse.semester FROM allocatecourse JOIN course ON allocatecourse.coursecode=course.coursecode WHERE allocatecourse.resultsatus='Approved' AND allocatecourse.acadsession='$ses' AND allocatecourse.semester='$sems' AND course.department='$deplt' ORDER BY allocatecourse.coursecode ";
                                                $query = mysqli_query($con,$sql);
                                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                                $cnt=1;
                                                
                                                foreach($results as $result)
                                                {   ?>
                                                <tr>
                                                 <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result['acadsession']);?></td>
                                                    <td><?php echo htmlentities($result['coursecode']);?></td>
                                                    <td><?php echo htmlentities($result['semester']);?></td>
                                                    <td><?php echo htmlentities($result['resultsatus']);?></td>

                                                   <td> <button type="button" class="userinfo btn btn-primary" 
                                                     
                                                    data-coursecode="<?php echo $result['coursecode'];?>"
                                                    data-semester="<?php echo $result['semester'];?>"
                                                    data-session="<?php echo $result['acadsession'];?>"
                                                    href="#detailsmodal" data-target="#detailsModal">View Result</button></td>
                                                <!-- Remember to continue from Here Tomorow Please -->
                                                
                                                </tr>
                                                <?php 

                                                $cnt=$cnt+1;
                                                 
                                                }
                                            ?>
                                                                           
                                                                        
                                                    </tbody>
                                                </table>
                                    </section>
                       <?php
                        }//Close Tag to else statement if Result Uploading Have not Ended 
                        
                      else{
                    ?>
                    <p>Sorry Dear: <?php echo htmlentities($fullname) ?> You Don't Have Access To This Page </p>
                    <?php   
                     }
                    ?>

<div class="modal" id="viewModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">RESULT INFORMATION</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="teacher_details">

            </div>
        </div>
    </div>
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
        <!-- Error Modal Box -->
          
            
        <!-- ========== COMMON JS FILES ========== -->
       <?php
        include 'includes/buton.php';
       ?>
       <script>
$(document).ready(function(){

//$('.userinfo').click(function(){
$('body').on('click','.userinfo',function(){

var ssid = $(this).attr('data-coursecode');
var sess = $(this).attr('data-session');
var sems = $(this).attr('data-semester');


// AJAX request
$.ajax({
url: 'apprint',
type: 'POST',
data: {ssid:ssid,sess:sess,sems:sems},
success: function(response){ 
//$('#empModal').modal('show'); 
$('#teacher_details').html(response);

      // Display Modal
      $('#viewModal').modal('show');
}
});
});
}); 
/*Textbox Disabbling*/
$(document).ready(function(){
    $('.sendButton').attr('disabled',true);

        $('#mess').keyup(function(){
        if($(this).val().length !=0){
        $('.sendButton').attr('disabled', false);
        }
        else
        {
        $('.sendButton').attr('disabled', true);        
        }
    });      
      
});

</script>

    </body>
</html>


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
}
?>