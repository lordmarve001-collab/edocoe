<?php 
session_start();
if (!isset($_SESSION['staffid']))
    header("Location: ../logout");
$connection = TRUE;
include "../include/dbconfig.php";
include 'dataco.php';
$data=mysqli_fetch_assoc(mysqli_query($con,"SELECT deadline,semester,session FROM resultsession WHERE description='student course registration'"));
$expires = $data['deadline'];
//echo $expires;
$sems=$data['semester'];
$ses=$data['session'];
$datat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid ='".$_SESSION['staffid']."'"));
$progr=$datat['programme'];
$std=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentregsem WHERE semester='$sems' AND session='$ses' AND programme='$progr' AND 
studentid='".$_SESSION['staffid']."' "));
            if ($std!="") {
            $lv=$std['level'];
            $prg=$std['programme'];
            $stat=$std['status'];
}
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
                                    <h3 class="title"><?php echo "STUDENT STUDY MATERIAL" ?></h3>
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
                                                            <th>Course</th>
                                                            <th>Material Title</th>
                                                            <th>Discription/Instruction</th>
                                                            <th>Material Type</th>
                                                             <th>Action</th>
                                                    </tr>
                                                </thead> 
                                            <tbody>
                                                <?php
	
										$que= mysqli_query($con,"SELECT DISTINCT course.title,course.unit,result.coursecode,result.custatus FROM result JOIN course ON
										result.coursecode=course.coursecode WHERE result.studentid='".$_SESSION['staffid']."' AND result.level='$lv' AND result.semester='$sems' 
										AND result.acadsession='$ses'  ORDER BY result.coursecode");
                                    
                                        while ($rows= mysqli_fetch_array($que)){
										
                                                  $sql = "SELECT * FROM study WHERE status='Active' AND  course='".$rows["coursecode"]."' ORDER BY date ";
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
                                                    <td><?php echo htmlentities(strtoupper($result['course']));?></td>
                                                    
                                                    <td><?php echo htmlentities(strtoupper($result['title']));?></td> 
													<td><?php echo htmlentities(strtoupper($result['instructions']));?></td><td><?php echo htmlentities($result['type']);?></td>
                                                   <td><button class="btn btn-success userinfof" title="View/Download Study Material" id="<?php echo $result['id'] ?>" pos="<?php echo $result['type'] ?>"><i class="fa fa-eye"></i></button>
                                                   <!--<button class="btn btn-primary userinfo" title="Reply Material" id="<?php echo $result['id'] ?>" pos="<?php echo $result['type'] ?>"><i class="fa fa-edit"></i></button>-->
													</td>
                                                <!-- Remember to continue from Here Tomorow Please -->
                                                
                                                </tr>
                                                <?php 

                                                $cnt=$cnt+1;
                                                 
                                                }}//} ?>
                                                                           
                                                                        
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
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Update Staff Status</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_details">

                        </div>

                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
					<div class="modal" id="viewModals">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Study Material Preview</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailss">

                        </div>

                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
</body>

<?php 
include"includes/buton.php";
?>

<script>
 $(document).ready(function() {

                    //$('.userinfo').click(function() {
                     $('body').on('click','.userinfo',function() {
                        var userid = $(this).attr('id');
                        var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'studyst',
                            type: 'GET',
                            data: {
                                userid: userid,
                                userip: userip
                            },
                            success: function(response) {
                                $('#teacher_details').html(response);
                                // Display Modal
                                $('#viewModal').modal('show');
                            }
                        });
                    });
                });
	$(document).ready(function() {

		$('body').on('click','.userinfof',function(){
                        var userid = $(this).attr('id');
                        var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'viewstudy',
                            type: 'GET',
                            data: {
                                userid: userid,
                                userip: userip
                            },
                            success: function(response) {
                                $('#teacher_detailss').html(response);
                                // Display Modal
                                $('#viewModals').modal('show');
                            }
                        });
                    });
                });    
</script>
</html>