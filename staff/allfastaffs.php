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
                   
                    <!-- /.left-sidebar -->
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                     if ($paych['position']=="dean") {
                       
                    include('includes/deanbar.php');
                     }elseif ($paych['position']=="facofficer") {
                        include('includes/facbar.php');
                     }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Faculty Staff(s) View Setup</h2>
                                
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
                     if ($paych['position']=="dean" OR $paych['position']=="facofficer") {
                    ?>                        
                                                <section>
                                                    <table id="example" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Student ID</th>
                                                            <th>Fullname</th>
                                                            <th>Department</th>
                                                            <th>Current Status</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                            <?php
                              $sql = "SELECT * FROM people JOIN staffaculty ON people.staffid=staffaculty.staffid  WHERE staffaculty.faculty='$faclt' AND people.status='active' ORDER BY people.staffid";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                           
                            foreach($results as $result)
                            { 
                                ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['staffid']) ?></td>
                                <td style="text-transform: capitalize;"><?php echo htmlentities($result['title'].". ".$result["firstname"]." ".$result['lastname']);?></td>
                                <?php  if($result['deptm']!="") {
                                   $dpn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department  WHERE deptc='".$result['deptm']."' "));
                                   ?>
                                <td><?php echo htmlentities($dpn['dept']);?></td>
                            <?php }else{ ?>
                                <td></td>
                                <?php }?>
                                <td><?php echo htmlentities($result['status']);?></td>
                                <td><button class="btn btn-success userinfo" id="<?php echo $result['staffid']?>" type="button"  title="View Staff Data"><i class="fa fa-eye"></i></button></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                             <button class="btn btn-primary" onclick="window.open('printdae','_blank')">Print Detail <i class="fa fa-print"></i></button>
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
        <!-- /.main-wrapper -->
         <div class="modal" id="viewModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Staff Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_details">

                  </div>

      <!-- Modal footer -->
              <div class="modal-footer">
                
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div> 
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
        ?>
        <script type="text/javascript">
        $(document).ready(function(){

            // $('.userinfo').click(function(){
            $('body').on('click','.userinfo',function(){
            var userid = $(this).attr('id');
            // AJAX request
            $.ajax({
                    url: 'viewstafdata',
                    type: 'GET',
                    data: {userid: userid},
                    success: function(response)
                    { 
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