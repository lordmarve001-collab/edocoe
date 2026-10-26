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
		
		
		if (isset($_POST["submited"])) {
        
        if (empty($_POST["coursecode"]))
         {
             $error="Please Select a Course";
             $showModalEr='true';
         }
            else{
                             
            $code=strtolower(mysqli_escape_string($con,$_REQUEST["coursecode"]));
            $title=strtolower(mysqli_escape_string($con,$_REQUEST["title"]));
            $inst=strtolower(mysqli_escape_string($con,$_REQUEST["instruction"]));
            $pope=strtoupper(mysqli_escape_string($con,$_REQUEST["pope"]));
            $filenamg =basename($_FILES['passport']['name']);
                    $filetmp =$_FILES['passport']['tmp_name'];
                    $filesize = $_FILES['passport']['size'];
                    $fileinfo = @getimagesize($_FILES["passport"]["tmp_name"]);
                    $extension = array("mp3","mp4","doc","docx","jpg","jpeg","JPG","JPEG","pdf","png");
                     // Get image file extension
                    $file_extension = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
                    // Validate file input to check if is not empty
                    if (!file_exists($_FILES["passport"]["tmp_name"])) {
                         $error="Choose image file to upload.";
                         $showModalEr='true';
                        
                    }    // Validate file input to check if is with valid extension
                    else if (!in_array($file_extension, $extension)) {
                    $error="Upload Only Docx, doc, mp3, mp4, pdf,png Materials.";
                    $showModalEr='true';
                    }    // Validate image file size
                    else{
                        $nwtourimg = explode(".", $filenamg);
                        $fileExt = end($nwtourimg);
                        //unlink($filetmp);
						$length=5;
						$string='';
$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $size = strlen($chars);
   for ($i = 0; $i < $length; $i++) {
       $string .= $chars[rand(0, $size - 1)];
       $date=date("Ymd");
       $ned=$title.''.$date.''.$string;
  }
						
						////
                        $fileBase = $ned.".".$fileExt;
                        $dir = "study/";
                        $final_dir= $dir . $fileBase;
                        if (($filesize > 80000000)) {
                                $error="file too large, filesize should be 75mb";
                                //header("Refresh:2;");
                                $showModalEr='true';
                                //return false;
                        }
                    
				else{
					                                    if(move_uploaded_file($filetmp, $final_dir))


                //inserting to the database
     $qu = mysqli_query($con, "INSERT INTO study (staffid,course,title,instructions,type,material,status,date)
     VALUES ('$harr','$code','$title','$inst','$pope','$final_dir','Active',now()) ");
     
                           if($qu>0){
                              $succ='Study Material Successfully Uploaded for '.$code.'';
                              $showModal='true';
                              
                            }
                            else{
                                $error="Error Occured!!";
                                $showModalEr='true';
                            }
                }
					}
         
					}
}
		if (isset($_POST['submitst'])) {
			$new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM study WHERE id='".$_SESSION['studi']."'"));
                    $frst=$new['material'];        //$staf = $_POST['staffid'];
        $posc =mysqli_real_escape_string($con,$_POST['newprive']);
		if ($posc=='delete') {
            $del=mysqli_query($con,"DELETE FROM study WHERE id='".$_SESSION['studi']."'");
			 
			$file_to_delete = $frst;
        unlink($file_to_delete);

           if ($del) {
               $succ = 'Details Successfully Deleted';
               $showModal = 'true';
           }else{
            $err = "Error Occured While Deleting!!";
            $showModalEr = 'true';
           }}else{
        $upd = mysqli_query($con, "UPDATE study SET status='$posc' WHERE id='".$_SESSION['studi']."'");
        if ($upd > 0) {
            $succ = 'Successfully Updated';
            $showModal = 'true';
            //newuserst($username,$userID,$position);
        } else {
            $err = "Error Occured While Submiting!!";
            $showModalEr = 'true';
            }
        }}
		
		
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
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
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

                  if ($paych['position']=="hod")
                  {
                    
                     include('includes/hodbar.php');

                    }else if ($paych['position']=="lecturer") {
                        
                    
                    include('includes/lectbar.php');
                     }elseif ($paych['position']=="dean") {
                       include 'includes/deanbar.php'; 
                    }elseif ($paych['position']=="dvc_acad") {
                       include 'includes/dvcbar.php'; 
                    }elseif ($paych['position']=="superadmin") {
                       include 'includes/supbar.php'; 
                    }elseif ($paych['position']=="facofficer") {
                       include 'includes/facbar.php'; 
                    }elseif ($paych['position']=="academicaffair") {
                       include 'includes/acdbar.php'; 
                    }elseif ($paych['position']=="accountant") {
                       include 'includes/accbar.php'; 
                    }elseif ($paych['position']=="subadmin") {
                      include('includes/subbar.php'); 
                    }elseif ($paych['position']=="sys_analyst") {
                      include('includes/sysbar.php'); 
                    }elseif ($paych['position']=="pde coordinator") {
                      include('includes/jupbar.php'); 
                    }elseif ($paych['position']=="subbursar") {
                      include('includes/sububar.php'); 
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Pre-Tertiary School Leadership Summit</h2>
                                
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
                        $payt=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM allocatecourse WHERE acadsession='$ses' AND staffid='".$_SESSION['staffid']."'"));

                        if ($payt!='')
                        { 
                        ?>                                      
                                               
												 <section>
                                                <table id="example" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Full Name</th>
                                                            <th>Application ID</th>
                                                            <th>LGA</th>
                                                            <th>Phone</th>
                                                            <th>Gender</th>
                                                             <th>Email</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        
                                                        $sql = "SELECT  * FROM tsls  ORDER BY date";

                                                        $query = mysqli_query($con, $sql);
                                                        $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                        $cnt = 1;
                                                        
                                                        foreach ($results as $result) {   ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities(strtoupper($result['fullname'])) ?></td>
                                                                <td><?php echo htmlentities(strtoupper($result['applid'])); ?></td>
                                                                <td><?php echo htmlentities(strtoupper($result['lga'])); ?></td>
                                                                <td><?php echo htmlentities(strtoupper($result['phone'])); ?></td>
                                                                <td><?php echo htmlentities($result['gender']); ?></td>
                                                                 <td><?php echo htmlentities($result['email']); ?></td>

																
                                                            </tr>
                                                            <?php

                                                            $cnt = $cnt + 1;
                                                            
                                                        } }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </section>
                
   
                                    <!-- /.col-md-12 -->
                                </div>
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
		<!-- Error Modal Box -->
            <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
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

 
             <div class="modal" id="viewModalst">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                    <h4 class="modal-title" style="float:left;color: red;">Warning Notice</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_detailst">

                        </div>

                        <!-- Modal footer -->

                    </div>
                </div>
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
			<!-- Error Modal Box -->
            <div class="modal fade" id="checkModall" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($succ); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-success" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /.main-wrapper -->
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
	if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModall").modal("show");
            });
        </script>';
    } 
		           
		
       ?>
        <script type="text/javascript">
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

                    //$('.userinfof').click(function() {
                      $('body').on('click','.staffprive',function(){
                        var userid = $(this).attr('id');
                        var userip = $(this).attr('pos');
                        // AJAX request
                        $.ajax({
                            url: 'studelete',
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
function stuname($con,$maxt)
 {
    
    $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }

 }

 ?>
