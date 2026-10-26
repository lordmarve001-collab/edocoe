<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
if(!isset($_SESSION['staffid']))
    {   
    header("Location:logout"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
}
//Parent Insertion
if (isset($_POST['submitpar'])) {
    //Checking 
    if (empty($_POST['ptitle'])) {
    $error="Select Parent Title";
    $showModalEr='true';
    }elseif (empty($_POST['famirelat'])) {
        $error="Select Family Relation";
        $showModalEr='true';
    }elseif (empty($_POST['religh'])) {
        $error="Select Religion You Belong";
        $showModalEr='true';
    }else{
        //Positing Record
        $titl=mysqli_escape_string($con,$_POST['ptitle']);
        $pfull=mysqli_escape_string($con,$_POST['fullname']);
        $padd=mysqli_escape_string($con,$_POST['address']);
        $pho=mysqli_escape_string($con,$_POST['phone']);
        $emai=mysqli_escape_string($con,$_POST['email']); 
        $famre=mysqli_escape_string($con,$_POST['famirelat']);
        $occ=mysqli_escape_string($con,$_POST['ocupation']);
        $pregi=mysqli_escape_string($con,$_POST['religh']);       
    $sql=mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='$harr'");
    if(mysqli_num_rows($sql)>0)
    {
            $error="Parent Detail Already Exist";
            $showModalEr='true';
    }else{

        $ins=mysqli_query($con,"INSERT INTO parentdetail(applid,title,parentname,address,phone,email,relat,occupation,relig) VALUES ('$harr','$titl','$pfull','$padd','$pho','$emai','$famre','$occ','$pregi')");
            
            if ($ins>0) {
            
            header("Refresh:0;  appsummary");
            }else{
                $error="Error While Submiting";
                $showModalEr='true';
            }
        }

    }
}
if (isset($_POST['submitpup'])) {
        $titl=mysqli_escape_string($con,$_POST['ptitle']);
        $pfull=mysqli_escape_string($con,$_POST['fullname']);
        $padd=mysqli_escape_string($con,$_POST['address']);
        $pho=mysqli_escape_string($con,$_POST['phone']);
        $emai=mysqli_escape_string($con,$_POST['email']); 
        $famre=$_POST['famirelat'];
        $occ=mysqli_escape_string($con,$_POST['ocupation']);
        $pregi=$_POST['religh'];
        //die($pregi);
       // $upd=mysqli_query($con,"UPDATE parentdetail SET title='$titl',parentname'$pfull',address='$padd',phone='$pho',email='$emai',relat='$famre',occupation='$occ',relig='$pregi' WHERE applid='$harr'"); 
        $upd=mysqli_query($con,"UPDATE parentdetail SET title='$titl',parentname='$pfull',address='$padd',phone='$pho',email='$emai',relat='$famre',occupation='$occ',relig='$pregi' WHERE applid='$harr'"); 
        if ($upd) {
            
            header("Refresh:0;  appsummary");
            }else{
                $error="Error While Updating";
                $showModalEr='true';
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
  <?php include('include/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php include('include/leftbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">PARENT/ GUARDIAN INFORMATION</h4>
                                
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
                                    $sqlc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='$harr'"));
                                    if ($sqlc=="") {
                                       
                                 ?>   
                                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Title: </label>
                                                
                                                 <select name="ptitle" class="form-control clid" id="classid" required="required">
                                                    <option selected disabled>Please select Title</option>
                                        
                                                    <?php
                                                    $res = mysqli_query($con, "SELECT * FROM titles ORDER BY title");
                                                    while ($row = mysqli_fetch_array($res)) {

                                                        ?>
                                                        <option value="<?php echo  $row["title"]; ?>"><?php echo  $row["title"]; ?></option>
                                                    <?php

                                                    }
                                                    ?>
                                                 </select>
                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="default"style="font-size: 12px;">Name of Parent/Guardian:</label>
                                             <div>
                                                <input type="text" class="form-control" autocomplete="off" name="fullname" required="required"  maxlength="59">
                                             </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Phone:</label>
                                                <div>
                                                    <input type="tel"  class="form-control"  name="phone" autocomplete="off" required="required" maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                        <label>Relationship</label>
                                                         <div>
                                                         <select name="famirelat" class="form-control clid" id="classid" required="required">
                                                        <option selected disabled>Please select Relationship</option>
                                                                                                
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM relat ORDER BY rela");
                                                            while ($row = mysqli_fetch_array($res)) {

                                                                ?>
                                                                <option value="<?php echo  $row["rela"]; ?>"><?php echo  $row["rela"]; ?></option>
                                                            <?php

                                                            }
                                                            ?>
                                                         </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="date">Occupation:</label>
                                                        <div>
                                                    <input type="text"  class="form-control" name="ocupation"  maxlength="57"/>
                                                        </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="col-lg-12">
                                             <div class="form-group">
                                                <label>Address:</label>
                                                <div>
                                               <textarea name="address" autocomplete="on"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required"></textarea>
                                                </div>
                                            </div>   
                                            <div class="form-group">
                                                        <label>Email:</label>
                                                        <div>
                                                    
                                                       <input type="email"  class="form-control" autocomplete="off" name="email"  maxlength="137">
                                                    
                                                        </div>
                                            </div>
                                            <div class="form-group">
                                            <label>Religion</label>
                                             <div>
                                             <select name="religh" class="form-control clid" id="classid" required="required">
                                            <option selected disabled>Please select Religion</option>
                                                                                    
                                                <?php
                                                $res = mysqli_query($con, "SELECT * FROM relig ORDER BY reli");
                                                while ($row = mysqli_fetch_array($res)) {

                                                    ?>
                                                    <option value="<?php echo  $row["reli"]; ?>"><?php echo  $row["reli"]; ?></option>
                                                <?php

                                                }
                                                ?>
                                             </select>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                                <div>
                                                    <button type="submit"  name="submitpar" id="submit" class="btn btn-primary form-control">Submit Data Before Continuing</button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        
                                    </form>
                                <?php }else{ ?>
                                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Title: </label>
                                                
                                                 <select name="ptitle" class="form-control clid" id="classid" required="required">
                                                    <option selected value="<?php echo htmlentities($sqlc['title'])?>"><?php echo htmlentities($sqlc['title'])?></option>
                                        
                                                    <?php
                                                    $res = mysqli_query($con, "SELECT * FROM titles ORDER BY title");
                                                    while ($row = mysqli_fetch_array($res)) {

                                                        ?>
                                                        <option value="<?php echo  $row["title"]; ?>"><?php echo  $row["title"]; ?></option>
                                                    <?php

                                                    }
                                                    ?>
                                                 </select>
                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="default"style="font-size: 12px;">Name of Parent/Guardian:</label>
                                             <div>
                                                <input type="text" class="form-control" autocomplete="off" name="fullname" required="required" value="<?php echo htmlentities($sqlc['parentname'])?>"  maxlength="159">
                                             </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Phone:</label>
                                                <div>
                                                    <input type="tel"  class="form-control"  name="phone" autocomplete="off" value="<?php echo htmlentities($sqlc['phone'])?>" required="required" maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                        <label>Relationship</label>
                                                         <div>
                                                         <select name="famirelat" class="form-control clid" id="classid" required="required">
                                                        <option selected='selected' value="<?php echo htmlentities($sqlc['relat'])?>"><?php echo htmlentities($sqlc['relat'])?></option>
                                                                                                
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM relat ORDER BY rela");
                                                            while ($row = mysqli_fetch_array($res)) {

                                                                ?>
                                                                <option value="<?php echo  $row["rela"]; ?>"><?php echo  $row["rela"]; ?></option>
                                                            <?php

                                                            }
                                                            ?>
                                                         </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="date">Occupation:</label>
                                                        <div>
                                                    <input type="text" value="<?php echo htmlentities($sqlc['occupation'])?>" class="form-control" name="ocupation"  maxlength="157"/>
                                                        </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="col-lg-12">
                                             <div class="form-group">
                                                <label>Address:</label>
                                                <div>
                                               <textarea name="address" autocomplete="on"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required"><?php echo htmlentities($sqlc['address'])?></textarea>
                                                </div>
                                            </div>   
                                            <div class="form-group">
                                                        <label>Email:</label>
                                                        <div>
                                                    
                                                       <input type="email"  class="form-control" autocomplete="off" name="email" value="<?php echo htmlentities($sqlc['email'])?>"  maxlength="137">
                                                    
                                                        </div>
                                            </div>
                                            <div class="form-group">
                                            <label>Religion</label>
                                             <div>
                                             <select name="religh" class="form-control clid" id="classid" required="required">
                                            <option selected='selected' value="<?php echo htmlentities($sqlc['relig'])?>"><?php echo htmlentities($sqlc['relig'])?></option>
                                                                                    
                                                <?php
                                                $res = mysqli_query($con, "SELECT * FROM relig ORDER BY reli");
                                                while ($row = mysqli_fetch_array($res)) {

                                                    ?>
                                                    <option value="<?php echo  $row["reli"]; ?>"><?php echo  $row["reli"]; ?></option>
                                                <?php

                                                }
                                                ?>
                                             </select>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                                <div>
                                                    <button type="submit"  name="submitpup" id="submit" class="btn btn-primary form-control">Click to Update Data</button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        
                                    </form>

                                <?php }?>
                                </div><a href="applicparent"><button class="btn btn-primary">Complete and Next Page</button></a>
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
        <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
       <script src="js/main.js"></script>
    </body>
</html>
<?php 
if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    }
} ?>
