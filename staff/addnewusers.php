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
$succ='';
$secc=$_SESSION['section'];
    if (isset($_POST['submitst'])) {
        //$staf = $_POST['staffid'];
        $posc =mysqli_real_escape_string($con,$_POST['newprive']);
		if ($posc=='delete') {
            $del=mysqli_query($con,"DELETE FROM student WHERE studentid='".$_SESSION['studi']."'");
           $delt=mysqli_query($con,"DELETE FROM studlogin WHERE staffid='".$_SESSION['studi']."'");
           if ($delt) {
               $succ = 'Details Successfully Deleted';
               $showModal = 'true';
           }else{
            $err = "Error Occured While Deleting!!";
            $showModalEr = 'true';
           }
        }else{
        $upd = mysqli_query($con, "UPDATE studlogin SET status='$posc' WHERE studentid='".$_SESSION['studi']."'");
        if ($upd > 0) {
            $succ = 'Successfully Updated';
            $showModal = 'true';
            //newuserst($username,$userID,$position);
        } else {
            $err = "Error Occured While Submiting!!";
            $showModalEr = 'true';
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
            .field-icon {
              float: right;
              margin-left: -35px;
              margin-top: -35px;
              position: relative;
              z-index: 2;
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
                  }else if ($paych['position']=="sys_analyst")
                  {
                    
                    include('includes/sysbar.php');

                    }elseif ($paych['position']=="subadministrator") {
                        include('includes/ictbar.php');
                  }elseif ($paych['position']=="admission") {
                        include('includes/admsec.php');
                  }elseif ($paych['position']=="registrar") {
                        include('includes/admoff.php');
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
                                    <h2 class="title">Add New Student (s) Setup</h2>
                                
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
                                        <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                     if ($paych['position']=="superadmin" OR $paych['position']=="subadministrator" OR $paych['position']=="subadmin" OR $paych['position']=="sys_analyst") {
                      ?>   
                                            <div class="panel-body">
											<span class="pull-left"><a href="addoldstudent"  class="btn btn-primary"><span class="fa fa-users"></span> Add Student</a></span>
                                            <div style="height:50px;"></div>
                                                
                                        
        <section>
         <table id="example" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Firstname</th>
                    <th>Last Name</th>
                    <th>Studentid</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                  <?php
                  $sql = "SELECT * FROM studlogin WHERE status='active' AND acadsession='$secc' ORDER BY staffid";
                 
                $query = mysqli_query($con,$sql);
                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                $cnt=1;
               
                foreach($results as $result)
                {   ?>
                <tr>
                 <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($result['firstname']) ?></td>
                    <td><?php echo htmlentities($result['lastname']);?></td>
                    <td><?php echo htmlentities($result['staffid']);?></td>
                    <td><?php echo htmlentities($result['status']);?></td>
                    <td> 
					<button class="btn btn-success userinfof " title="View Student Record" id="<?php echo $result['staffid'] ?>" pos="<?php echo $result['position'] ?>"><i class="fa fa-eye"></i></button> 
					<button class="btn btn-primary staffprive" id="<?php echo $result['staffid'] ?>" pos="<?php echo $result['position'] ?>" title="Edit Staff status"><i class="fa fa-edit"></i></button></td>
                </tr>
                <?php 

                $cnt=$cnt+1;
                 
                } ?> 
                </tbody>
                </table>
            </section>
           <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
                    ?>                         </div>
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
		 <div class="modal" id="viewModals">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Student Bio Data</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailss">

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
                            <h4 class="modal-title">Update Student Privilege</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailst">

                        </div>

                        <!-- Modal footer -->

                    </div>
                </div>
            </div>
        <!-- Error Modal Box -->
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
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
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
                            <p><?php echo htmlentities($succ) ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-success" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
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
       ?>
      <script type="text/javascript">
      $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input=$($(this).attr("toggle"));
        //calling on the attribute of the password
        if (input.attr("type")=="password") {
            //Changing to Text
            input.attr("type","text");
        }else{
            input.attr("type", "password");
        }
    });

      $(".toggle-passwordc").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input=$($(this).attr("toggles"));
        //calling on the attribute of the password
        if (input.attr("type")=="password") {
            //Changing to Text
            input.attr("type","text");
        }else{
            input.attr("type", "password");
        }
    });
//  $('body').on('click','.userinfost',function()
      function selectstate()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "prog?facult=" + document.getElementById("faculty").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("depart").innerHTML = xmlhttp.responseText;

                }
				
				/* seperate Line */
                $(document).ready(function() {

                    //$('.userinfof').click(function() {
                      $('body').on('click','.userinfof',function(){
                        var userid = $(this).attr('id');
                        var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'viewstudatas',
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
				/* Statff Privilege */
				$(document).ready(function() {

                    //$('.userinfof').click(function() {
                      $('body').on('click','.staffprive',function(){
                        var userid = $(this).attr('id');
                        var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'stuprive',
                            type: 'GET',
                            data: {
                                userid: userid,
                                userip: userip
                            },
                            success: function(response) {
                                $('#teacher_detailst').html(response);
                                // Display Modal
                                $('#viewModalst').modal('show');
                            }
                        });
                    });
                });
    </script>
    </body>
</html>
<?php }
 ?>
