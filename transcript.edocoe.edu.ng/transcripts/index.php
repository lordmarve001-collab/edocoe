<?php
ob_start();
  session_start();
$connection = True;
include ("../include/dbconfig.php");
 

  $_SESSION['uatCsrfToken'] = md5(uniqid());
  $_SESSION['uatCsrfSalt'] = md5(uniqid());
  $error='';
  $succ='';


    if (isset($_POST['registering'])) {
        if(empty($_POST['applid'])){
            $error="Student ID is Required";
            $showModalEr='true';
        }
        else{
        $prog=trim(mysqli_escape_string($con,strtoupper($_POST['applid'])));
        $que= mysqli_fetch_array(mysqli_query($con,"SELECT * FROM student WHERE studentid='$prog'"));
       if ($que!="") {
        if ($que['status']=="active" OR $que['status']=="graduated") {
            $_SESSION['staffid']=$que['studentid'];
            $_SESSION['start'] = time();
            $_SESSION['expire'] = $_SESSION['start'] + (60*60*24);
            header("location: student");
        }
        else{
            $error="Student Data Currently ".$que['status'];
            $showModalEr='true';
        }
        }else{
            $error = "Check Your Student ID.";
            $showModalEr='true';
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo htmlentities($orgname) ;?></title>
    <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
</head>
<body>
    <?php include('includes/menubar.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line"></h4>

                </div>

            </div>
            
            <div class="row">
                <div class="col-md-6" style="height:100vh;">
                    <div class="account-card-content"> 
                    <form  action="" method="post">
                      
                    <label for="surname">First Name</label>
                    <input type="text" class="form-control" id="surname" name="firstname" placeholder="Enter your FirstName" onkeypress="return (event.keyCode != 32&&event.which!=32)" value="" required>
                
                    <label for="firstname">Last Name (Family Name)</label>
                    <input type="text" class="form-control" id="firstname" name="surname" placeholder="Enter your LastName" onkeypress="return (event.keyCode != 32&&event.which!=32)" value="" required>
                    <label for="firstname">Othername</label>
                    
                    <input type="text" class="form-control" id="firstname" name="surnameb" placeholder="Enter your Othername" onkeypress="return (event.keyCode != 32&&event.which!=32)" value="">
                    <label for="surname">Your Matriculation Noz</label>
                    <input type="text" class="form-control" name="matricno" placeholder="Enter your Matriculation No"  required>
                     <label>School Attended</label>
                                    <select class="form-control" name="programm" id="programme">
                                        
                                          
                                    <option selected disabled>Please select School</option>
                                      <?php
                                          
                                        
                                        $resz = mysqli_query($con, "SELECT * FROM program ORDER BY pname");
                                        while ($row = mysqli_fetch_array($resz)) {

                                            ?>
                                            <option value="<?php echo  $row["pid"]; ?>"><?php echo  $row["pname"]; ?></option>
                                        <?php

                                        }
                                      ?>   
                                    </select> 
                    <label for="surname">Your Email</label>
                    <input type="email" class="form-control" name="jambemail" placeholder="Enter your Verified Email"  required>
                    <label for="firstname">Your Phone</label>
                    <input type="tel" class="form-control" name="jambphone" maxlength="13" onkeypress='return event.charCode >= 48 && event.charCode <= 57'  placeholder="Enter Phone Number" required>
                    <div style="margin-top:2%;">
                    <button class="btn btn-danger"
                    type="submit" name="registering">Click to Submit <i class="fa fa-refresh"></i></button> <b style='color: red;float: right;'>Already have an account ? <a href="applogin" class="font-500 text-primary">Login here </a> </b>
                    </div>    
                                                        

                                        
                    </form>
                    <br>
                </div>
                </div>
                
                <div class="col-md-6">
                    <div class="alert alert-info" style="font-size: 14px;font-weight: bolder;">
                        Welcome to the <?php echo htmlentities($orgname) ?>. Transcript Portal
                        <br />
                         <strong> Please Note:</strong>
                        <ol>
                            
                            <li>
                                Enter your first, last and other name
                            </li>
                            <li>
                                Enter your Matriculation No.
                            </li>
                            <li>
                                Select the Institution you graduated from
                            </li>
                            <li>
                                Enter your valid phone and email.
                            </li>
                            <li>
                                Then click the submit.
                            </li>
                             <li>
                                Any difficulty reach us via Email: info@edocoe.edu.ng
                            </li>
                        </ol>
                       
                    </div>
                 </div>
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
            
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    
    <script src="js/jquery/jquery-2.2.4.js"></script>
    <script src="js/bootstrap/bootstrap.js"></script>
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
      
</script>
</body>
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
?>
</html>