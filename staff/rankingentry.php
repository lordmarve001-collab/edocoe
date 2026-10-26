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

if (isset($_POST['submitclv'])) 
    {
        $staff=mysqli_escape_string($con,$_POST['rankid']);
        $ses=mysqli_escape_string($con,$_POST['unitid']);
        $sq=mysqli_query($con,"SELECT * FROM ranklist WHERE rankid='$staff'  OR rankname='$ses'");
            if (mysqli_num_rows($sq)==1) {
            $error="Detail already Exist For This";
            $showModal="true";
        }
        else{


            $insrt=mysqli_query($con,"INSERT INTO ranklist(rankid,rankname) VALUES ('$staff','$ses')");
            if ($insrt) {
                $showModalsucs='true';
                $succ="Detail Successfully Added";
            }else{
                $error="Error Occured While Inserting";
                $showModal="true";
            }
            }

    }
   if (isset($_POST['updateuse'])) {
       $newcla=mysqli_real_escape_string($con,$_POST['unitname']);
    //$newarm=mysqli_real_escape_string($con,$_POST['arms']);
    $sq=mysqli_query($con,"SELECT * FROM unitlist WHERE unitname='$newcla'");
            if (mysqli_num_rows($sq)==1) {
            $error=$newcla." Detail already Exist";
            $showModal="true";
        }
        else{
    $upd=mysqli_query($con,"INSERT INTO unitlist(unitid,unitname) VALUES ('".$_SESSION['staffds']."','$newcla')");
    if ($upd) {
         $showModalsucs='true';
         $succ="Detail Successfully Submitted";
      }else{
          $error= "Error Occured while submitting Information";
          $showModals='true';
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

                   if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
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
                                    <h4 class="title">New Ranking Setup</h4>
                                
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

           if ($paych['position']=="superadmin") {
            ?>
                                                <section class="section">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Rank ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="number" name="rankid" class="form-control"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Unit:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" name="unitid" placeholder="E.g ICT for ICT unit" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitclv" id="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                                <section>
                                                    <table id="example" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Rank ID</th>
                                                            <th>Unit Name</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <?php
                                                       
                              $sql = "SELECT * FROM ranklist";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            // if($query->rowCount() > 0)
                            // {
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['rankid']) ?></td>
                                <td><?php echo htmlentities($result['rankname']);?></td>
                                <td>
                                    
                                    <button class="btn btn-primary userinfof" id="<?php echo $result['rankid'] ?>"  cvls="<?php echo $result['rankname']?>">Add Units</i></button>
                                    <button class="btn btn-primary userinfov" id="<?php echo $result['rankid'] ?>"  cvls="<?php echo $result['rankname']?>"> <i class="fa fa-eye"></i></button>
                                </td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }//} ?> 
                                                    </tbody>
                                                    </table>
                                                </section>
               <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
                    ?>
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
          <div class="modal" id="viewModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" style="float:left;">Delete Comfirmation <i class="fa fa-trash"></i> </h4>
                        <button class="close" data-dismiss="modal" style="float:right">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" id="teacher_details">

                    </div>

                    <!-- Modal footer -->

                </div>
            </div>
        </div>
        <div class="modal" id="viewModals">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" style="float:left;">Add New Rank <i class="fa fa-plus"></i> </h4>
                        <button style="float:right;" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" id="teacher_detail">

                    </div>

                    <!-- Modal footer -->

                </div>
            </div>
        </div>

        <div class="modal" id="viewModalst">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" style="float:left;">View Unit Record</h4>
                        <button style="float:right;" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" id="teacher_detailt">

                    </div>

                    <!-- Modal footer -->

                </div>
            </div>
        </div>
        <!-- /.main-wrapper -->
        <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
            <div class="modal fade" id="checkModalsucc" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                            <?php echo htmlentities($succ, ENT_QUOTES, 'utf-8'); ?>
                            </p>
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
        ?>

        
      <script type="text/javascript">
        $(document).ready(function() {
          //
            //$('.userinfo').click(function() {
            $('body').on('click','.userinfo',function() {
                var userid = $(this).attr('id');
                var clss = $(this).attr('cvls');
                // AJAX request
                $.ajax({
                    url: 'delegst',
                    type: 'GET',
                    data: {
                        userid: userid,
                        clss: clss
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
          //
            //$('.userinfo').click(function() {
            $('body').on('click','.userinfof',function() {
                var userid = $(this).attr('id');
                var clss = $(this).attr('cvls');
                // AJAX request
                $.ajax({
                    url: 'addunit',
                    type: 'GET',
                    data: {
                        userid: userid,
                        clss: clss
                    },
                    success: function(response) {
                        $('#teacher_detail').html(response);
                        // Display Modal
                        $('#viewModals').modal('show');
                    }
                });
            });
        });

        $(document).ready(function() {
          //
            //$('.userinfo').click(function() {
            $('body').on('click','.userinfov',function() {
                var userid = $(this).attr('id');
                var clss = $(this).attr('cvls');
                // AJAX request
                $.ajax({
                    url: 'viewunit',
                    type: 'GET',
                    data: {
                        userid: userid,
                        clss: clss
                    },
                    success: function(response) {
                        $('#teacher_detailt').html(response);
                        // Display Modal
                        $('#viewModalst').modal('show');
                    }
                });
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
if(!empty($showModalsucs))
    {
    // CALL MODAL HERE
    echo '<script type="text/javascript">
       $(document).ready(function(){
           $("#checkModalsucc").modal("show");
       });
    </script>';
    }
 } ?>
