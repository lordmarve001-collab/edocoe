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
$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fname='$fac' "));
        $fact = $factn['fid'];
        //echo $fact;
        $depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE dept='$dt' "));
        $depn = $depna['deptid'];


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
                   <?php include('includes/hodbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Active Department Staff Setup</h2>
                                
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
                                                
                                              
                                                <section>
                                                    <table id="example2" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Staffid</th>
                                                            <th>Full Name</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                            <?php
                              $sql = "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid WHERE  staffaculty.deptm='$depn' AND people.status='active' ORDER BY staffaculty.staffid";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['staffid']) ?></td>
                                <td><?php echo htmlentities($result['title'].'. '.$result['firstname'].' '.$result['lastname'].' '.$result['othername']);?></td>
                                <td><button class="btn btn-info userinfo" id="<?php echo htmlentities($result['staffid']) ?>"><i class="fa fa-eye"></i></button></td>
                                
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
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
            <div class="modal" id="viewModals">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Staff Bio Data</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailss">

                        </div>

                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
        ?>
        <script type="text/javascript">
            $(document).ready(function() {

                    //$('.userinfof').click(function() {
                      $('body').on('click','.userinfo',function(){
                        var userid = $(this).attr('id');
                        //var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'viewstafdata',
                            type: 'GET',
                            data: {
                                userid: userid,
                                //userip: userip
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
    </body>
</html>
<?php } ?>
