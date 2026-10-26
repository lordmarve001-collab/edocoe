<?php
include ("include/logis.php");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Edo State College Of Education</title>
    <link href="img/edo.png" rel="shortcut icon" type="image/x-icon" />
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style type="text/css">
        .field-icon {
              float: right;
              margin-left: -35px;
              margin-top: -30px;
              position: relative;
              z-index: 2;
            }
            .pull-right{float:right!important}
    </style>
</head>
<body>
    <?php include('include/menubar.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line"></h4>

                </div>

            </div>
             <span style="color:red;" ><?php// echo htmlentities($_SESSION['errmsg']); ?><?php //echo htmlentities($_SESSION['errmsg']="");?></span>
            
            <div class="row">
                <div class="col-md-6">
                <!-- <form name="admin" method="post">

                     <label>Enter Firstname : </label>
                        <input type="text" name="username" placeholder="firstname.lastname" class="form-control" required />
                        <hr />
                        <button type="submit" name="submit" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> &nbsp;Log Me In </button>&nbsp;
                    </form> -->
                    <div class="account-card-content"> 
                    
                    <form  action="" method="post">
                                 
                                        <label for="surname">Application ID</label>
                                        <input type="text" class="form-control" id="surname" name="applid" placeholder="Enter Your Application ID"  value="" required>
                                    
                                        <label for="firstname">Password</label>
                                        <input type="password"  class="form-control"  id="password-field" name="password" value="" required><i toggle="#password-field" class="fa fa-fw field-icon fa-eye toggle-password"></i>
                                        <div style="margin-top: 5%;" >
                                            <button class="btn btn-info"
                                                type="submit" name="login">Click to Login</button> <b style='color: red;float: right;'>Don't have an application ID ? <a href="index" class="font-500 text-primary"> Signup now </a> </b>
                                        </div>    
                                        <div> By registering you agree to the Edo State College of Education <a href="#">Terms of Use</a>
                                        </div>                               
                                </form>
                                <br>
                                

                </div>
                </div>
                
                <div class="col-md-6">
                    <div class="alert alert-info" style="font-size: 14px;font-weight: bolder;">
                        Welcome to Edo State College of Education, Edo State. Admission Application Portal
                        <br />
                         <strong> Please Note :</strong>
                        <ol>
                            
                            <li>
                                Create your Account by Completing this Form
                            </li>
                            <li>
                                Make Payment For Your Account to be Created
                            </li>
                            <li>
                                Your Username and Password Will be mailed to You as Your Payment Is Successful.
                            </li>
                            <li>
                                Make Sure You Provide A working Email and Phone Number
                            </li>
                             <li>
For Support Call Admission Officer on 08095536133 or 09051181195                            </li>
                        </ol>
                       
                    </div>
                                    </div>

            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('include/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
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
</html>
