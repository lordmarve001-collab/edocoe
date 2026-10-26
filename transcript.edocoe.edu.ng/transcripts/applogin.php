<?php
include ("includes/love.php");
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
    <?php include('includes/menubar.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line"></h4>

                </div>

            </div>
             
            <div class="row">
                <div class="col-md-6">
                
                    <div class="account-card-content"> 
                    
                    <form  action="" method="post">
                                 
                                        <label for="surname">Matriculation No</label>
                                        <input type="text" class="form-control" id="surname" name="applid" placeholder="Enter Your Matriculation No"  value="" required>
                                        <div style="margin-top: 5%;" >
                                            <button class="btn btn-info"
                                                type="submit" name="login">Click to Login</button> <b style='color: red;float: right;'>Don't have an account ? <a href="index" class="font-500 text-primary"> Signup now </a> </b>
                                        </div>    
                                                                      
                                </form>
                                <br>
                                

                </div>
                </div>
                 <div class="col-md-6">
                    <div class="alert alert-info" style="font-size: 14px;font-weight: bolder;">
                        Welcome to the <?php echo htmlentities($orgname) ;?>. Old Transcript Portal
                        <br />
                         <strong> Please Note :</strong>
                        <ol>
                            
                            <li>
                                Enter the Matriculation you provided.
                            </li>
                            
                            
                        </ol>
                       
                    </div>
                                    </div>

            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
   
    <script src="js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="js/bootstrap.js"></script>
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
