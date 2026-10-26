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
        <title>UNIVERSITY OF AFRICA</title>
        
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <!-- <link rel="stylesheet" href="css/form.css" media="screen"> -->
        <link href="../favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/animate.min.css" media="screen" >
        
        <script type="text/javascript" src="js/state.js"></script>
      <script type="text/javascript" src="js/pic.js"></script>
      <script type="text/javascript" src="js/country.js"></script>
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
                   <?php //include('includes/hodbar.php');?>  
                    <!-- /.left-sidebar -->
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="hod")
                  {
                    $query=mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'");
                    $rows = mysqli_num_rows($query);
                    $data = mysqli_fetch_array($query);
                    if ($rows == 0)
                    {
                        header("Location: facultyrecord.php");
                    }
                    ?>
                    <?php include('includes/hodbar.php');

                    }else if ($paych['position']=="lecturer") {
                        
                        $query=mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'");
                    $rows = mysqli_num_rows($query);
                    $data = mysqli_fetch_array($query);
                    if ($rows == 0)
                    {
                        header("Location: facultyrecord.php");
                    }?>

                    <?php
                    include('includes/lectbar.php');
                     }//elseif ($paych['position']=="dean") {
                        
                    // }
                    else{
                    ?>
                    <?php
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Department Result Approving Setup</h2>
                                
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
                                                    <table class="table table-striped table-bordered tjks" border="1" width="100%" cellspacing=2 cellpadding=4>
                                                   <thead><tr>
                                                        <th>#</th>
                                                        <th>Session</th>
                                                        <th>Course Code</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead> 
                                            <tbody>
                                                <?php
                                                  $sql = "SELECT * FROM allocatecourse WHERE resultsatus='submitted' AND coursecord='Yes' AND  staffid='".$_SESSION['staffid']."' ORDER BY acadsession ";
                                                // $query = $con->prepare($sql);
                                                // $query->execute();
                                                // $results=$query->fetchAll(PDO::FETCH_OBJ);

                                                $query = mysqli_query($con,$sql);
                                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                                $cnt=1;
                                                // if($query->rowCount() > 0)
                                                // {
                                                foreach($results as $result)
                                                {   ?>
                                                <tr>
                                                 <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result['acadsession']);?></td>
                                                    <td><?php echo htmlentities($result['coursecode']);?></td>
                                                    <td><?php echo htmlentities($result['resultsatus']);?></td>
                                                   <td> <button type="button" class="userinfo btn btn-primary" 
                                                     
                                                    data-coursecode="<?php echo $result['coursecode'];?>" 
                                                    data-session="<?php echo $result['acadsession'];?>"
                                                    href="#detailsmodal" data-target="#detailsModal">View Result</button></td>
                                                <!-- Remember to continue from Here Tomorow Please -->
                                                
                                                </tr>
                                                <?php 

                                                $cnt=$cnt+1;
                                                 
                                                }//} ?>
                                                                           
                                                                        
                                                    </tbody>
                                                </table>
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
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
       <?php
        include 'includes/buton.php';
       ?>
       <script>
$(document).ready(function(){

$('.userinfo').click(function(){


var ssid = $(this).attr('data-coursecode');
var sess = $(this).attr('data-session');


// AJAX request
$.ajax({
url: '',
type: 'POST',
data: {ssid:ssid,sess:sess},
success: function(response){ 
// Add response in Modal body
//$('.modal-body').html(response);

// Display Modal
//$('#empModal').modal('show'); 
$('#teacher_details').html(response);

      // Display Modal
      $('#viewModal').modal('show');
}
});
});
}); 
</script>
    </body>
</html>


<?php } ?>
