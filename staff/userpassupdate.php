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
$_SESSION['uatCsrfToken'] = md5(uniqid());
$_SESSION['uatCsrfSalt'] = md5(uniqid());
$error='';
$succ='';
$amount=1000;
$key='pk_test_0f3e609af65a8304fbb57ad385c4354dce8a4d16';
$spk='SPL_VESaobd7IL';
//for inserting Record
if (isset($_POST['submitcor'])) {
      if(empty($_POST['purposed'])){
		   $showModale='true';
		   $error='Please Select Who Uploading For';
	  }else{
        $username=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
        $purp=mysqli_real_escape_string($con,$_POST['purposed']);
          if ($purp=="student") {
              $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM student where studentid ='".$username."'"));
              if ($telcheck>0) {
                $showModal='true';
                stuname($con,$username); 
              }else{
                 $showModale='true';
                $error='Student ID '.$username.' Does not Exit Or Please Check Student ID Again  Or Ask The Student to Complete His/Her Bio Data in edocohest.com Platform  Thank You';
                }
          }else{
            $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM people WHERE staffid ='".$username."'"));
              if ($telcheck>0) {
                    $showModalp='true';
                    staname($con,$username); 
              }else{
                 $showModale='true';
                $error='Staff ID '.$username.' Does not Exit Or Please Check Staff ID Again  Or Ask The Staff to Complete His/Her Bio Data in edocohest.com Platform Thank You';
                }
          }
	  }
}

if (isset($_POST['submitcord'])) {
      if(empty($_POST['purposed'])){
           $showModale='true';
           $error='Please Select Who Uploading For';
      }else{
        $username=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
        $purp=mysqli_real_escape_string($con,$_POST['purposed']);
          if ($purp=="student") {
              $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM student where studentid ='".$username."'"));
              if ($telcheck>0) {
                $dtd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM passupload WHERE studentid='$username' "));
                if ($dtd=='') {
                 
                $showModalps='true';
                stuname($con,$username);
            }else{
                $_SESSION['studentid']=$username;
                header("location:studentpassportupdate");
            } 
              }else{
                 $showModale='true';
                $error='Student ID '.$username.' Does not Exit Or Please Check Student ID Again  Or Ask The Student to Complete His/Her Bio Data in edocohest.com Platform Thank You';
                }
          }else{
            $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM personaldata WHERE applid ='".$username."'"));
              if ($telcheck>0) {
                    $showModalp='true';
                    staname($con,$username); 
              }else{
                 $showModale='true';
                $error='Staff ID <b style=color:blue;>'.$username.'</b> Does not Exit Or Please Check Staff ID Again  Or Ask The Staff to Complete His/Her Bio Data in edocohest.com Platform Thank You';
                }
          }
      }
}
/* fulclearance */
if (isset($_POST['fulclearance'])) {
	    $filenamg =basename($_FILES['passport']['name']);
        $filetmp =$_FILES['passport']['tmp_name'];
        $filesize = $_FILES['passport']['size'];
        $extension = array("png","jpg","jpeg","PNG","JPG","JPEG");
        $file_extension = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
        // Validate file input to check if is not empty
        if (!file_exists($_FILES["passport"]["tmp_name"])) {
             $error="Choose image file to upload.";
             $showModalEr='true';
            
        }    // Validate file input to check if is with valid extension
        else if (!in_array($file_extension, $extension)) {
            $error="Upload valid images. Only PNG and JPEG are allowed.";
            $showModalEr='true';
        } else if (($_FILES["passport"]["size"] > 500000)) {
        $error="Image size exceeds 500KB.";
        $showModalEr='true';
        }   // Validate image file size
        else{
        $nwtourimg = explode(".", $filenamg);
        $fileExt = end($nwtourimg);
        $res = trim(str_ireplace( array( '\'', '"',
      ',' , ';', '<', '>','/','-','_','' ), '', $_SESSION['stud']));
        $fileBase = $res.".".$fileExt;
        // $dir = "photo/";
        // $final_dir= $dir . $fileBase;
        $dir = "../student/photo/";
        $dirs = "photo/";
        $final_dirs= $dir . $fileBase;
        $final_dir= $dirs. $fileBase;
        
        if(move_uploaded_file($filetmp, $final_dirs))
        {
           /*Update the Query*/
                $upd=mysqli_query($con,"UPDATE student SET image='$final_dir' WHERE studentid='".$_SESSION['stud']."'");
                if ($upd) {
           
                    $succ='Passport Successfully Updated';
                      $showModall='true';
                     
                    } else {
                
                $error="Sorry Error Occur When Submitting";
                $showModale='true';
                        }
                }
            }
}

/* End Here */
/* For Part Clearance Start partcleared*/
if (isset($_POST['partcleared'])) {
        //echo $_SESSION['staff'];
	    $filenamg =basename($_FILES['passports']['name']);
        $filetmps =$_FILES['passports']['tmp_name'];
        $filesize = $_FILES['passports']['size'];
        $extension = array("png","jpg","jpeg","PNG","JPG","JPEG");
        $file_extension = pathinfo($_FILES["passports"]["name"], PATHINFO_EXTENSION);
        // Validate file input to check if is not empty
        if (!file_exists($_FILES["passports"]["tmp_name"])) {
             $error="Choose image file to upload.";
             $showModalEr='true';
            
        }    // Validate file input to check if is with valid extension
        else if (!in_array($file_extension, $extension)) {
            $error="Upload valid images. Only PNG and JPEG are allowed.";
            $showModalEr='true';
        }
        /*else if (($_FILES["passport"]["size"] > 500000)) {
        $error="Image size exceeds 500KB.";
        $showModalEr='true';
        }    // Validate image file size
        else{*/
        $nwtourimg = explode(".", $filenamg);
        $fileExt = end($nwtourimg);
        $res = trim(str_ireplace( array( '\'', '"',
      ',' , ';', '<', '>','/','' ), '', $_SESSION['staff']));
        $fileBases = $res.".".$fileExt;
        
        $dirss = "../apply/photo/";
        $final_dirss= $dirss. $fileBases;
        
        $dir = "photo/";
        $final_dirs= $dir. $fileBases;
        
        
        if(move_uploaded_file($filetmps, $final_dirss))
        {
           /*Update the Query*/
    $upd=mysqli_query($con,"UPDATE personaldata SET passport='$final_dirs' WHERE applid='".$_SESSION['staff']."'");
                if ($upd) {
           
                    $succ='Passport Successfully Updated';
                      $showModall='true';
                     
                    } else {
                
                $error="Sorry Error Occur When Submitting";
                $showModale='true';
                        }
                }
            //}
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
        <link rel="stylesheet" href="css/animate.min.css" media="screen" >
        <script type="text/javascript" src="https://js.paystack.co/v1/inline.js"></script>
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
                 <?php
					$paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="subadmin")
                  {
                    
                    include('includes/subbar.php');

                    }else if ($paych['position']=="sys_analyst") {
                    
                    include('includes/sysbar.php');
                    }elseif ($paych['position']=="subadministrator") {
                        include('includes/ictbar.php');
                  }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Passport Updating Setup</h4>
                                
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
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" autocomplete="off" name="tellerid" required="required" placeholder="Enter Student ID OR StaffID">
                                                       </div>

                                                    </div>
													<div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Updating For:</label>
                                                         <div class="col-sm-10">
															<select class="form-control clid" name="purposed">
																<option selected="selected" disabled="disabled">Please Select Who Is Updating For</option>
																<option value="student">Student</option>
																<option value="staff">Staff</option>
															</select>
                                                       </div>

                                                    </div>
                                                   
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-success">Click to Update <i class="fa fa-refresh"></i> </button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>


<?php }elseif ($paych['position']=="subadmin" OR $paych['position']=="sys_analyst" OR $paych['position']=="subadministrator") {
    ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" autocomplete="off" name="tellerid" required="required" placeholder="Enter Student ID OR Admission ID">
                                                       </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Updating For:</label>
                                                         <div class="col-sm-10">
                                                            <select class="form-control clid" name="purposed">
                                                                <option selected="selected" disabled="disabled">Please Select Who Is Updating For</option>
                                                                <option value="student">Student</option>
                                                                <option value="staff">Admission Applicant</option>
                                                            </select>
                                                       </div>

                                                    </div>
                                                   
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcord" id="submit" class="btn btn-success">Click to Update <i class="fa fa-refresh"></i> </button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                    <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
            ?>
                                              <!-- End of Section -->
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
                            <p><?php echo htmlentities($succ)?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($error) ?>.</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Full Clearance Page Start -->
		    <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Passport Updating
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" >×</span>
                            </button></h5>
                        </div>
						<form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
							<?php  
							$_SESSION['stud']=$username;
                           
							$_SESSION['program']=$_POST["prp"];

							?>
                           <div style="margin:auto;width:200px;"> 
                           <img src="<?php
                           echo htmlentities($_POST['makss']); ?>" id="blah" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
						   <script>
                            function readURL(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();

                                    reader.onload = function(e) {
                                        $('#blah')
                                            .attr('src', e.target.result)

                                    };

                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
                            <table>
                                 <tr>
                                     <td>Student Fullname:</td>
                                     <td><b style="text-transform: capitalize;"><?php echo htmlentities($_POST["harrd"]); ?></b></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>Matric No:</td>
                                     <td><?php echo htmlentities($username); ?></td>
                                 </tr>
                                 <tr>
                                     <td>Programme:</td>
                                     <td><?php echo htmlentities($_POST["prp"]); ?></td>
                                 </tr>
                                 
                                
                                 <tr>
                                     <td>New Passport</td>
                                     <td>
                                         <input type="file" name="passport" class="form-control" required="required" accept=".jpeg,.jpg,.png" style="width:200px;" onchange="readURL(this);">
                                     </td>
                                 </tr>
                                 
                            </table>
                        </div>
                        <div class="modal-footer">
                            
                                <button name="fulclearance" type="submit" class="btn btn-success" style="float:left;">
                                   Click to Update <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Full Clearance Page End -->

           <!-- Student Passport Update with Payment Page Start -->
            <div class="modal fade" id="checkModalps" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Passport Updating
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" >×</span>
                            </button></h5>
                        </div>
                        
                        <div class="modal-body">
                            <?php  
                            $_SESSION['stud']=$username;
                           
                            $_SESSION['program']=$_POST["prp"];

                            ?>
                           <div style="margin:auto;width:200px;"> 
                           <img src="<?php
                           echo htmlentities($_POST['makss']); ?>" id="blah" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
                           <script>
                            function readURL(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();

                                    reader.onload = function(e) {
                                        $('#blah')
                                            .attr('src', e.target.result)

                                    };

                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
                            <table>
                                 <tr>
                                     <td>Student Fullname:</td>
                                     <td><b style="text-transform: capitalize;"><?php echo htmlentities($_POST["harrd"]); ?></b></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>Matric No:</td>
                                     <td><?php echo htmlentities($username); ?></td>
                                 </tr>
                                 <tr>
                                     <td>Programme:</td>
                                     <td><?php echo htmlentities($_POST["prp"]); ?></td>
                                 </tr>
                                 <tr>
                                    <td></td>
                                     <td style="font-size: 12px;">Student have to make payment <b>&#8358;<?php echo number_format('1000',2)?></b> before Updating</td>
                                 </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            
                                <button type="button" onclick="payWithPaystack()" class="btn btn-success" style="float:left;">
                                   Click to Pay <i class="fa fa-refresh"></i>
                                </button>
                           
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Full Clearance Page End -->
            <!-- Part Clearance Page Start-->
			 <div class="modal fade" id="checkModalp" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Applicant Image Updating
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </h5>
                        </div>
						<form method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
						<?php 
							$_SESSION['staff']=$username;
							//echo $_SESSION['stud'];
						?>
                           <div style="margin:auto;width:200px;"> 
                           <img src="<?php
                           echo htmlentities($_POST["photo"]); ?>" id="blahs" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
                           <script>
                            function readURLx(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();

                                    reader.onload = function(e) {
                                        $('#blahs')
                                            .attr('src', e.target.result)

                                    };

                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
                            <table>
                                 <tr>
                                     <td>Applicant Fullname:</td>
                                     <td><b style="text-transform: capitalize;"><?php echo htmlentities($_POST["harrd"]); ?></b></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>Admission ID:</td>
                                     <td><?php echo htmlentities($username); ?></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>New Passport</td>
                                     <td>
                                         <input type="file" name="passports" class="form-control" required="required" accept=".jpeg,.jpg,.png" style="width:200px;" onchange="readURLx(this);">
                                     </td>
                                 </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            
                                <button name="partcleared" type="submit" class="btn btn-success" style="float:left;">
                                   Click to Update <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Part Clearance Page End-->
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
        <script type="text/javascript">
        function payWithPaystack() {
      var amount = <?php echo htmlentities($amount)?>*100;
      var studi = '<?php echo htmlentities($username)?>';
      var pbk='<?php echo htmlentities($key) ?>';
      var spl='<?php echo htmlentities($spk) ?>';
      var email = '<?php echo htmlentities($unmail) ?>';     
      var ght = '<?php echo htmlentities('Passport Updating') ?>';     
      var stfd = '<?php echo htmlentities($_SESSION['staffid']) ?>';     

      var handler = PaystackPop.setup({
        key: pbk,
        split_code:spl,
        email: email,
        amount: amount,
        currency: "NGN",
         metadata: {
          custom_fields: [
            
            {
              display_name: "Purpose",
              variable_name: "full_name",
              value: ght
            }
          ]
        },
        callback: function (response) {          

        var csrfToken = '<?php echo $_SESSION['uatCsrfToken']; ?>';
        var csrfSalt = '<?php echo $_SESSION['uatCsrfSalt']; ?>';
        var email = '<?php echo htmlentities($unmail) ?>';
        var xmlhttp = "";

          if (window.XMLHttpRequest) {
              // code for modern browsers
              xmlhttp = new XMLHttpRequest();
          } else {
              // code for old IE browsers
              xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          }

          xmlhttp.onreadystatechange = function() {
           if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            
            window.location.href = "";
              
            }
            //alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");
          };
          

          xmlhttp.open("POST", "includes/pay", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("staff=" + stfd + "&studi=" + studi+ "&ref=" + response.reference+ "&token=" + csrfToken + "&salt=" + csrfSalt);
        }
      });

      handler.openIframe();
    }
        </script>
        <?php
            include 'includes/buton.php';
        ?>
        
        <?php           
    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    }
     if(!empty($showModalps)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalps").modal("show");
            });
        </script>';
    } 
    if(!empty($showModalp)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalp").modal("show");
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
    if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
            });
        </script>';
    }
    if(!empty($showModalS)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalS").modal("show");
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
?>
    </body>
</html>
<?php }
function stuname($con,$maxt)
 {
    
    $sql =mysqli_query($con,"SELECT  *  FROM  student WHERE studentid='$maxt' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $prp=$data['programme'];
        $mak=$data['studentid'];
        $_POST["makss"]="../student/".$data['image'];
        $name =$las.", ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["prp"]= $prp;
        }

 }
 
 function staname($con,$maxtt)
 {
    
    $sql =mysqli_query($con,"SELECT  *  FROM  personaldata WHERE applid='$maxtt' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $mak=$data['staffid'];
        $_POST["photo"]="../apply/".$data['passport'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["mak"]= $mak;
        }

 }

 ?>
