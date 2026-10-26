<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
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
if (isset($_POST['uquery'])) {
    //$_SESSION['section']=$secc;
    $escapedPW = mysqli_real_escape_string($con,$_POST['password']);
    $hashedPW = hash('sha256', $escapedPW);
    $upd=mysqli_query($con,"UPDATE juplicant SET password='$hashedPW' WHERE applid='".$_SESSION['section']."'");
    if($upd){
            $showModal='true';
            $succ='password successfully Reset';
        }else{
             $showModalEr='true';
            $error="Sorry Error Occured";
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
    <style type="text/css">
      #blah{
            width:170.256px;
            height:159px;
            border:2px solid black;
            }
            .td{
                cursor: not-allowed;
            }
        strong{
            color: blue;
        }
        .field-icon {
              float: right;
              margin-left: -35px;
              margin-top: -25px;
              position: relative;
              z-index: 2;
            }
            .pull-right{float:right!important}
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

                  if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }elseif($paych['position']=='subadmin'){
                        include('includes/subbar.php');
                    }elseif($paych['position']=='sys_analyst'){
                        include('includes/sysbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    
                    
                        <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title">JUPEB DATA ENTRY </h3>
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
                     if ($paych['position']=="superadmin" OR $paych['position']=='subadmin' OR $paych['position']=='sys_analyst') {
                       
                    
                     ?>
                    <div class="panel-body">                                                
                          <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                            <label for="default" class="col-sm-2">Jupeb ID:</label>
                             <div class="col-sm-10">
                             <input name="studentid" placeholder="Enter Student Jupeb ID" class="form-control clid" required="required">
                            </div>
                        </div>
                                                                                                                 
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="query" class="btn btn-primary">Query Data</button>
                        </div>
                    </div>                    
                    </form>
                    <?php 
                        if (isset($_POST['query'])) {
        
                        $secc=trim(mysqli_escape_string($con,$_POST['studentid']));
                        $sql=mysqli_query($con,"SELECT * FROM juplicant WHERE applid='$secc' ");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          { 
                            $red=mysqli_fetch_assoc($sql);

                            
                            ?>
                               <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                <?php $_SESSION['section']=$secc;  ?>
                                <fieldset>
                            <div class="form-group">
                            <label for="default" class="col-sm-2">Jupeb ID:</label>
                             <div class="col-sm-10">
                             <input name="studentid" value="<?php echo htmlentities($secc) ?>" placeholder="Enter Student Jupeb ID" class="form-control clid" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" class="col-sm-2">Student Name:</label>
                             <div class="col-sm-10">
                             <input name="studentidn" placeholder="Enter Student Jupeb ID" class="form-control clid" value="<?php echo htmlentities($red['lastname'].' '.$red['firstname']) ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" class="col-sm-2">New Password:</label>
                             <div class="col-sm-10">
                            <input type="password" maxlength="38" name="password" class="form-control clid" id="password-field" required="required" style="text-transform: capitalize;" placeholder="**********" maxlength="27" title="Your Password should contain atleat one uppercase and lowercase"><i toggle="#password-field" class="fa fa-fw field-icon fa-eye toggle-password"></i>
                            </div>
                        </div>
                                                                                                                 
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="uquery" class="btn btn-primary">Update Password</button>
                        </div>
                    </div>   
                    </fieldset>                 
                    </form> 
                            <?php
                            }
                            else{
                           $error='There Is No result base on the Criteria Selected';
                           $showModal='true';

                            }
                        }
                    ?> 
                    
               
                    <?php
                  
                    }else{
                    ?><div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        //}
                      }
                    ?>  
                    <!-- /.left-sidebar -->

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
</script>
    </body>
</html>
<?php 
} 
?>
