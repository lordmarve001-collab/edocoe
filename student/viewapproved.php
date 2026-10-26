<?php 
session_start();
if (!isset($_SESSION['staffid']))
    header("Location: ../logout");
$connection = TRUE;
include "../include/dbconfig.php";
include 'dataco.php';
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
        <link rel="stylesheet" type="text/css" href="css/print.css" media="print">
        <link rel="stylesheet" href="css/main.css" media="screen" >
      
<style>
            
        .time{
    color:red;
    margin-right: 34px;
    font-size: 13px;
}
.infon{
    color:green;
}
select{
    text-transform: uppercase;
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
                                <div>
                                    <h3 class="title"><?php echo "STUDENT APPROVED COURSES SETUP" ?></h3>
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

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <table class="table table-striped table-bordered tjks" border="1" width="100%" cellspacing=2 cellpadding=4>
                                                   <thead><tr>
                                                        <th>#</th>
                                                        <th>Session</th>
                                                        <th>Semester</th>
                                                        <th>Level</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead> 
                                            <tbody>
                                                <?php
                                                  $sql = "SELECT * FROM studentregsem WHERE status='Approved' AND  studentid='".$_SESSION['staffid']."' ORDER BY level ";
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
                                                    <td><?php echo htmlentities($result['session']);?></td>
                                                    <td><?php echo htmlentities($result['semester']);?></td>
                                                    <td><?php echo htmlentities($result['level']);?></td>
                                                    <td><?php echo htmlentities($result['status']);?></td>
                                                   <td> <button type="button" class="userinfo btn btn-primary" 
                                                    data-studentid="<?php echo $result['studentid'];?>" 
                                                    data-semester="<?php echo $result['semester'];?>" 
                                                    data-session="<?php echo $result['session'];?>"
                                                    data-prog="<?php echo $result['programme'];?>"
                                                    data-level="<?php echo $result['level'];?>"
                                                    >View Course</button></td>
                                                <!-- Remember to continue from Here Tomorow Please -->
                                                
                                                </tr>
                                                <?php 

                                                $cnt=$cnt+1;
                                                 
                                                }//} ?>
                                                                           
                                                                        
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-8 col-md-offset-2 -->
                                </div>
                                <!-- /.row -->

                            
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->

                    </div>
                    <div class="modal" id="viewModal">
                                      <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                          <!-- Modal Header -->
                                          <div class="modal-header">
                                            <h5 class="modal-title">COURSE INFORMATION</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <!-- Modal body -->
                                          <div class="modal-body" id="teacher_details">

                                          </div>
                                        </div>
                                      </div>
                                </div>
</body>

<?php 
include"includes/buton.php";
?>

<script>
$(document).ready(function(){

//$('.userinfo').click(function()

    //$('.userinfo').click(function(){

 $('body').on('click','.userinfo',function(){

var applid = $(this).attr('data-studentid');
var ssid = $(this).attr('data-semester');
var sess = $(this).attr('data-session');
var prog = $(this).attr('data-prog');
var level = $(this).attr('data-level');

// AJAX request
$.ajax({
url: 'apprview',
type: 'POST',
data: {applid: applid,ssid:ssid,sess:sess,prog:prog,level:level},
success: function(response){ 
 
$('#teacher_details').html(response);

      // Display Modal
      $('#viewModal').modal('show');
}
});
});
}); 
</script>
</html>