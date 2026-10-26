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
// Retrieving Session
$data=mysqli_fetch_assoc(mysqli_query($con,"SELECT deadline,semester,session FROM resultsession WHERE description='student course registration'"));
$expires = $data['deadline'];
//echo $expires;
$sems=$data['semester'];
$ses=$data['session'];
$error='';
$succ='';
//for inserting Record

/**/
if (isset($_POST['submitcor'])) {
      if(empty($_POST['purposed'])){
           $showModale='true';
           $error='Please Select Clearance Purpose';
      }else{
        $username=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
        //echo $username;
        $purp=mysqli_real_escape_string($con,$_POST['purposed']);
        
        $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM student where studentid ='".$username."'"));
        if ($telcheck>0) {
            if($purp=="fulclrance"){
                $showModal='true'; 
                stuname($con,$username); 
            }elseif($purp=="patclrance")
            {
                $showModalp='true';
                stuname($con,$username);
            }elseif($purp=="latereg"){
                $showModalEr='true';
                stuname($con,$username);
            }elseif($purp=="clateregpr"){
                $showModalErsc='true';
                stuname($con,$username);
            }elseif($purp=="lateregpr"){
                $showModalErs='true';
                stuname($con,$username);
            }else{
                $showModale='true';
                $error='Purpose Selected Does not Exit';
            }
              
                  
        }else{
                 $showModale='true';
                $error='Student ID <b style=color:blue;>'.$username.'</b> Does not Exit Or Please Check Student ID Again <br> Or Ask The Student to Complete His/Her Bio Data in my.uat.edu.ng Platform <br> Thank You';
        }
      }
}
/* fulclearance */
if (isset($_POST['fulclearance'])) {
    if (empty($_POST['sessionx'])) {
        $showModale = "true";
        $error='Please Select Session';
    }elseif (empty($_POST['level'])) {
        $showModale = "true";
        $error='Please Select Level';
    }
    else{
    $stds=trim(mysqli_real_escape_string($con,strtoupper($_SESSION['stud'])));
      
        $prd=mysqli_escape_string($con,$_SESSION['program']);
        $secc=mysqli_escape_string($con,$_POST['sessionx']);
        $leve=mysqli_escape_string($con,$_POST['level']);
        //Checking to avoid Duplicate Data
            $sq=mysqli_query($con,"SELECT * FROM studentregsem WHERE studentid='$stds' AND session='$secc' AND level='$leve' AND semester='$sems' ");
            if (mysqli_num_rows($sq)==1) {
                    $showModale='true';
                    $error='Student Detail Already Exist For This <b style=color:red;>'.$sems.'</b> AND <b style=color:red;>'.$secc.'</b> Academic Session  AND Level <b style=color:red;>'.$leve.'</b>';
                }
                else
                {
               $tod=date("Y-m-d H:i:sa"); 
            $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$stds','$secc','First Semester','$prd','','$leve')");
            $instt=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$stds','$secc','Second Semester','$prd','','$leve')");
            $tod=date("Y-m-d H:i:sa"); 
            //$tns=mysqli_query($con,"INSERT INTO paytrack(staffid,purpose,datecleared,staffname,studentid,programme) VALUES('".$_SESSION['staffid']."','Full Clearance','$tod','$fullname','$stds','$prd')");
                if ($inst) {
                  //  approvefdmail($stds,$secc,$leve,$con);
                    $showModall='true';
                    $succ="Student Successfully Cleared Course Registration";
                }
                else{
                $showModale='true';
                $error='Error Occured while Submitting Please Try Again';
                }
            //}
        }
  }
}

/* End Here */
/* For Part Clearance Start partcleared*/
if (isset($_POST['partcleared'])) {
    if (empty($_POST['sessionx'])) {
        $showModale = "true";
        $error='Please Select Session';
    }elseif (empty($_POST['semster'])) {
        $showModale = "true";
        $error='Please Select Semester';
    }elseif (empty($_POST['level'])) {
        $showModale = "true";
        $error='Please Select Level';
    }
    else{
    $stds=trim(mysqli_real_escape_string($con,strtoupper($_SESSION['stud'])));
      
        $prd=mysqli_escape_string($con,$_SESSION['program']);
        $sems=mysqli_escape_string($con,$_POST['semster']);
        $secc=mysqli_escape_string($con,$_POST['sessionx']);
        $leve=mysqli_escape_string($con,$_POST['level']);


            //Checking to avoid Duplicate Data
            $sq=mysqli_query($con,"SELECT * FROM studentregsem WHERE studentid='$stds' AND session='$secc' AND level='$leve' AND semester='$sems' ");
            if (mysqli_num_rows($sq)==1) {
                    $showModale='true';
                    $error='Student Detail Already Exist For This <b style=color:red;>'.$sems.'</b> AND <b style=color:red;>'.$secc.'</b> Academic Session  AND Level <b style=color:red;>'.$leve.'</b>';
                }
                else
                {
                $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$stds','$secc','$sems','$prd','','$leve')");
                $tod=date("Y-m-d H:i:sa"); 
            //$tns=mysqli_query($con,"INSERT INTO paytrack(staffid,purpose,datecleared,staffname,studentid,programme) VALUES('".$_SESSION['staffid']."','Part Clearance','$tod','$fullname','$stds','$prd')");
                if ($inst) {
                    //approvedpmail($stds,$sems,$secc,$leve,$con);
                    $showModall='true';
                    $succ="Student Successfully Cleared Course Registration";
                }
                else{
                $showModale='true';
                $error='Error Occured while Submitting Please Try Again';
                }
            //}
        }
  }
}
/* For Part Clearance End */
/* For Late Registration Clearance End */
if (isset($_POST['exten'])) {
    $std=trim(mysqli_real_escape_string($con,strtoupper($_SESSION['stud'])));
    $query ="SELECT * FROM student WHERE studentid='".$std."' ";
        $result =mysqli_query($con, $query);
    $rows=mysqli_fetch_assoc($result);
    if ($rows=="") {
         $showModale='true';
         $error='Student ID '.$std.' Does not Exit or Check and Try Again';
    }else{
    $stuid=$rows['studentid'];
    $date = date('Y-M-d'); 
    $newd = date('Y-m-d', strtotime($date. ' + 4 days'));
    //die($stuid);
    $conf=mysqli_num_rows(mysqli_query($con,"SELECT * FROM studentregsem WHERE studentid='$stuid' AND semester='$sems' AND session='$ses' "));
    if ($conf>0) {
        
    $upda=mysqli_query($con,"UPDATE studentregsem SET extention='$newd' WHERE studentid='$stuid' AND semester='$sems' AND session='$ses' ");
    $tod=date("Y-m-d H:i:sa"); 
    //$tns=mysqli_query($con,"INSERT INTO paytrack(staffid,purpose,datecleared,staffname,studentid,programme) VALUES('".$_SESSION['staffid']."','Extension Clearance','$tod','$fullname','$std','$prd')");
    if ($upda) {
            //approvedmail($stuid,$sems,$ses,$con);
            $showModall='true';
            $succ='Student Course Registration Time Extension is  Successfully';
             }
        }else{
            $showModale='true';
            $error='Student Details '.$stuid.' Was Not Found On The Student Clearance Table';
        }
    }
}
/* For Previous Late Registration Clearance Clearance End */
if (isset($_POST['extenpr'])) {
    if (empty($_POST['sessionx'])) {
        $showModale = "true";
        $error='Please Select Session';
    }elseif (empty($_POST['semster'])) {
        $showModale = "true";
        $error='Please Select Semester';
    }elseif (empty($_POST['level'])) {
        $showModale = "true";
        $error='Please Select Level';
    }
    else{
    $std=trim(mysqli_real_escape_string($con,strtoupper($_SESSION['stud'])));
    $prd=mysqli_escape_string($con,$_SESSION['program']);
    $semsc=mysqli_escape_string($con,$_POST['semster']);
    $secc=mysqli_escape_string($con,$_POST['sessionx']);
    $leve=mysqli_escape_string($con,$_POST['level']);
    $query ="SELECT * FROM student WHERE studentid='".$std."' ";
        $result =mysqli_query($con, $query);
    $rows=mysqli_fetch_assoc($result);
    
    if ($rows=="") {
         $showModale='true';
         $error='Student ID '.$std.' Does not Exit or Check and Try Again';
    }else{
    $stuid=$rows['studentid'];
    $date = date('Y-M-d'); 
    $newd = date('Y-m-d', strtotime($date. ' + 4 days'));
    //die($stuid);
    $conf=mysqli_num_rows(mysqli_query($con,"SELECT * FROM studentregsem WHERE studentid='$stuid' AND semester='$semsc' AND session='$secc' AND level='$leve' "));
    if ($conf>0) {
        
    $upda=mysqli_query($con,"UPDATE studentregsem SET extention='$newd' WHERE studentid='$stuid' AND semester='$semsc' AND session='$secc' AND level='$leve' ");
    //try to prevent resubmitting
    $slt=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentlateregsem WHERE studentid='$stuid' AND semester='$semsc' AND session='$secc' AND level='$leve' "));
    if ($slt=="") {
       $inst=mysqli_query($con,"INSERT INTO studentlateregsem (studentid,session,semester,programme,uploadst,regstatus,level) VALUES ('$stuid','$secc','$semsc','$prd','Pending','','$leve')");
       $tod=date("Y-m-d H:i:sa"); 
            //$tns=mysqli_query($con,"INSERT INTO paytrack(staffid,purpose,datecleared,staffname,studentid,programme) VALUES('".$_SESSION['staffid']."','Previous Late Clearance','$tod','$fullname','$std','$prd')");
    }
   
    if ($upda) {
            //approvedmails($stuid,$semsc,$secc,$leve,$con);
            $showModall='true';
            $succ='Student Course Registration Time Extension is  Successfully';
             }
        }else{
            $showModale='true';
            $error='Student Details '.$stuid.' Was Not Found On The Student Clearance Table';
        }
    }
 }
}
/* For Previous Course Registration Clearance Clearance End */
if (isset($_POST['clickclead'])) {
    if (empty($_POST['sessionx'])) {
        $showModale = "true";
        $error='Please Select Session';
    }elseif (empty($_POST['semster'])) {
        $showModale = "true";
        $error='Please Select Semester';
    }elseif (empty($_POST['level'])) {
        $showModale = "true";
        $error='Please Select Level';
    }
    else{
    $std=trim(mysqli_real_escape_string($con,strtoupper($_SESSION['stud'])));
    $prd=mysqli_escape_string($con,$_SESSION['program']);
    $semsc=mysqli_escape_string($con,$_POST['semster']);
    $secc=mysqli_escape_string($con,$_POST['sessionx']);
    $leve=mysqli_escape_string($con,$_POST['level']);
    $query ="SELECT * FROM student WHERE studentid='".$std."' ";
        $result =mysqli_query($con, $query);
    $rows=mysqli_fetch_assoc($result);
    //Checking to avoid Duplicate Data
    $sq=mysqli_query($con,"SELECT * FROM studentregsem WHERE studentid='$std' AND session='$secc' AND level='$leve' AND semester='$semsc' ");
    if (mysqli_num_rows($sq)==1) {
            $showModale='true';
            $error='Student Detail Already Exist For This <b style=color:red;>'.$semsc.'</b> AND <b style=color:red;>'.$secc.'</b> Academic Session and <b style=color:red;>'.$leve.' </b>Level';
        }
        else
        {
    if ($rows=="") {
         $showModale='true';
         $error='Student ID '.$std.' Does not Exit or Check and Try Again';
    }else{
    $stuid=$rows['studentid'];
    
       $inst=mysqli_query($con,"INSERT INTO studentregsem (studentid,session,semester,programme,status,level) VALUES ('$stuid','$secc','$semsc','$prd','','$leve')");
   $tod=date("Y-m-d H:i:sa"); 
           // $tns=mysqli_query($con,"INSERT INTO paytrack(staffid,purpose,datecleared,staffname,studentid,programme) VALUES('".$_SESSION['staffid']."','Previous Registration Clearance','$tod','$fullname','$std','$prd')");
    if ($inst) {
            //approvedmailsp($stuid,$semsc,$secc,$leve,$con);
            $showModall='true';
            $succ='Student Course Registration is Successfully Cleared';
             }else{
            $showModale='true';
            $error='Sorry Error Occured while Clearing the Student';
        }
    }
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

                  if ($paych['position']=="accountant")
                  {
                    
                    include('includes/accbar.php');

                    }else if ($paych['position']=="bursar") {
                    
                    include('includes/supbubar.php');
                    }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }else if ($paych['position']=="subadmin") {
                    
                    include('includes/subbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title">Registration Clearance Setup</h4>
                                
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

                                                if ($paych['position']=="accountant" OR $paych['position']=="supbursar")
                                                {
                                                ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" autocomplete="off" name="tellerid" required="required" placeholder="Enter Student ID">
                                                       </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Clearnace Purpose:</label>
                                                         <div class="col-sm-10">
                                                            <select class="form-control clid" name="purposed">
                                                                <option selected="selected" disabled="disabled">Please Select Clearance Purpose</option>
                                                                <option value="fulclrance">Full Clearance</option>
                                                                <option value="patclrance">Part Clearance</option>
                                                                <option value="latereg">Late Registration Clearance</option>
                                                                <option value="clateregpr">Previous Registration Clearance</option>
                                                                <option value="lateregpr">Previous Late Registration Clearance</option>
                                                            </select>
                                                       </div>

                                                    </div>
                                                   
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-success">Click to Verify <i class="fa fa-check-circle"></i> </button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                                
                                                <?php
                                                }else if ($paych['position']=="superadmin"  OR $paych['position']=="subadmin") {
                                                
                                                ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" autocomplete="off" name="tellerid" required="required" placeholder="Enter Student ID">
                                                       </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Clearnace Purpose:</label>
                                                         <div class="col-sm-10">
                                                            <select class="form-control clid" name="purposed">
                                                                <option selected="selected" disabled="disabled">Please Select Clearance Purpose</option>
                                                                <option value="fulclrance">Full Clearance</option>
                                                                <option value="patclrance">Part Clearance</option>
                                                                <option value="latereg">Late Registration Clearance</option>
                                                                <option value="clateregpr">Previous Registration Clearance</option>
                                                                <option value="lateregpr">Previous Late Registration Clearance</option>
                                                            </select>
                                                       </div>

                                                    </div>
                                                   
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-success">Click to Verified <i class="fa fa-check-circle"></i> </button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                                <?php                                                
                                                }
                                                else{
                                                
                                               ?>
                                               <p style="color:darkred;font-size: 16px;font-weight: bold;text-align: justify;">Sorry Dear: <b style="color:blue;"><?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page.</p>
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
                            <p><?php echo $error ?>.</p>
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
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Full Course Registration Clearance</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="POST">
                        <div class="modal-body">
                            <?php  
                            $_SESSION['stud']=$username;
                            //echo $_SESSION['stud'];
                            $_SESSION['program']=$_POST["prp"];
                            ?>
                           <div style="margin:auto;width:200px;"> <img src="<?php
                           echo htmlentities($_POST['maks']); ?>" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
                           
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
                                     <td>Session:</td>
                                     <td>
                                                         <select name="sessionx" class="form-control clid"required="required">
                                                            <option selected="selected" disabled="disabled">Select Academic Session</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select>
                                </td>
                                 </tr>
                                
                                 <tr>
                                     <td>Level:</td>
                                     <td><select name="level" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Level</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM level  ORDER BY levels");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select></td>
                                 </tr>
                                 
                            </table>
                        </div>
                        <div class="modal-footer">
                            
                                <button name="fulclearance" class="btn btn-success" style="float:left;">
                                   Click to Cleared<i class="fa fa-upload"></i>
                                </button>
                            </form>
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
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Part Course Registration Clearance</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="POST">
                        <div class="modal-body">
                        <?php 
                            $_SESSION['stud']=$username;
                            //echo $_SESSION['stud'];
                           $_SESSION['program']=$_POST["prp"];
                        ?>
                           <div style="margin:auto;width:200px;"> <img src="<?php 
                           
                           echo htmlentities($_POST['maks']); ?>" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
                           
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
                                     <td>Session:</td>
                                     <td>
                                         <select name="sessionx" class="form-control clid"required="required">
                                            <option selected="selected" disabled="disabled">Select Academic Session</option>
                                            <?php
                                            $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                                        <?php

                                        }
                                        ?>
                                        
                                        </select>
</td>
                                 </tr>
                                 <tr>
                                     <td>Semester:</td>
                                     <td><select name="semster" class="form-control clid"required="required">
                                                            <option selected="selected" disabled="disabled">Select Semester</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest ASC");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select></td>
                                 </tr>
                                 <tr>
                                     <td>Level:</td>
                                     <td><select name="level" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Level</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM level  ORDER BY levels");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select></td>
                                 </tr>
                                 
                            </table>
                        </div>
                        <div class="modal-footer">
                            
                                <button name="partcleared" class="btn btn-success" style="float:left;">
                                   Click to Cleared<i class="fa fa-upload"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Part Clearance Page End-->
            <!--Late Registration Clearance Start Here-->
            <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Late Registration Clearance</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <?php 
                            $_SESSION['stud']=$username;
                            //echo $_SESSION['stud'];
                           $_SESSION['program']=$_POST["prp"]
                        ?>
                           <div style="margin:auto;width:200px;"> <img src="<?php
                            ;
                           echo htmlentities($_POST['maks']); ?>" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
                            <table>
                                 <tr>
                                     <td>Full Name:</td>
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
                                     <td>Current Semester:</td>
                                     <td><?php echo htmlentities($sems); ?></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>Current Session:</td>
                                     <td><strong><?php echo htmlentities($ses); ?></strong></td>
                                 </tr>
                                 
                            </table>
                        </div>
                        <div class="modal-footer">
                            <form method="POST">
                                
                                <button name="exten" class="btn btn-success" style="float:left;">
                                    Extend Time <i class="fa fa-clock-o"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div> 
            <!--Previous Late Registration Clearance Start Here-->
            <div class="modal fade" id="checkModalErs" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Previous Late Registration Clearance</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="POST">
                        <div class="modal-body">
                        <?php 
                            $_SESSION['stud']=$username;
                            //echo $_SESSION['stud'];
                           $_SESSION['program']=$_POST["prp"]
                        ?>
                           <div style="margin:auto;width:200px;"> <img src="<?php
                            ;
                           echo htmlentities($_POST['maks']); ?>" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
                            <table>
                                 <tr>
                                     <td>Full Name:</td>
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
                                     <td>Session:</td>
                                     <td>
                                                         <select name="sessionx" class="form-control clid"required="required">
                                                            <option selected="selected" disabled="disabled">Select Academic Session</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select>
</td>
                                 </tr>
                                 <tr>
                                     <td>Semester:</td>
                                     <td><select name="semster" class="form-control clid"required="required">
                                                            <option selected="selected" disabled="disabled">Select Semester</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest ASC");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select></td>
                                 </tr>
                                 <tr>
                                     <td>Level:</td>
                                     <td><select name="level" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Level</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM level  ORDER BY levels");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select></td>
                                 </tr>
                                 
                            </table>
                        </div>
                        <div class="modal-footer">
                            
                                
                                <button name="extenpr" class="btn btn-success" style="float:left;">
                                    Extend Time <i class="fa fa-clock-o"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
       <!-- New Section -->
        <div class="modal fade" id="checkModalErsc" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Previous Registration Clearance</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="POST">
                        <div class="modal-body">
                        <?php 
                            $_SESSION['stud']=$username;
                            //echo $_SESSION['stud'];
                           $_SESSION['program']=$_POST["prp"]
                        ?>
                           <div style="margin:auto;width:200px;"> <img src="<?php
                            ;
                           echo htmlentities($_POST['maks']); ?>" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
                            <table>
                                 <tr>
                                     <td>Full Name:</td>
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
                                     <td>Session:</td>
                                     <td>
                                                         <select name="sessionx" class="form-control clid"required="required">
                                                            <option selected="selected" disabled="disabled">Select Academic Session</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select>
</td>
                                 </tr>
                                 <tr>
                                     <td>Semester:</td>
                                     <td><select name="semster" class="form-control clid"required="required">
                                                            <option selected="selected" disabled="disabled">Select Semester</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest ASC");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select></td>
                                 </tr>
                                 <tr>
                                     <td>Level:</td>
                                     <td><select name="level" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Level</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM level  ORDER BY levels");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select></td>
                                 </tr>
                                 
                            </table>
                        </div>
                        <div class="modal-footer">
                            
                                
                                <button name="clickclead" class="btn btn-success" style="float:left;">
                                    Click to Cleared <i class="fa fa-refresh"></i>
                                </button>
                            </form>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ========== COMMON JS FILES ========== -->
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
    if(!empty($showModalErs)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalErs").modal("show");
            });
        </script>';
    } 
    if(!empty($showModalErsc)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalErsc").modal("show");
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
        $maks="../student/".$data['image'];
        $name =strtoupper($las).", ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["mak"]= $mak;
        $_POST["prp"]= $prp;
        }

 }
function approvedmail($stud,$sm,$se,$con)
{
    
    //$rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$stud'"));
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
    //die($maix);
        $to = $rows['email'];
        require_once "phpmailer/class.phpmailer.php";
        $mail = new PHPMailer;
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Late Registration Approval');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Late Registration Approval';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['lastname']. ', '. $rows['firstname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sm.'</b></span>';
          $message .= '<br>';
          $message.='<p><strong>Please Kindly Note: That Your Request For Extra Time for Course Enrolment have been Approved. Login to University Portal Now to Complete Your Course Registration Within 3 Days<br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}

function approvedmails($stud,$sm,$se,$lv,$con)
{
    
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
        $to = $rows['uniemail'];
        require_once "phpmailer/class.phpmailer.php";
        $mail = new PHPMailer;
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Late Previous Registration Extension');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Late Previous Registration Extension';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['lastname']. ', '. $rows['firstname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sm.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$lv.'</b></span>';
          $message .= '<br>';
          $message.='<p><strong>Please Kindly Note: That Your Request For Extra Time for Course Enrolment have been Approved. Login to University Portal Now to Complete Your Course Registration Within 3 Days<br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}
function approvedmailsp($stud,$sm,$se,$lv,$con)
{
    
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
    //die($maix);
        $to = $rows['uniemail'];
        require_once "phpmailer/class.phpmailer.php";
        $mail = new PHPMailer;
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Course Enrollment Clearance');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Enrollment Clearance';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['lastname']. ', '. $rows['firstname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sm.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$lv.'</b></span>';
          $message .= '<br>';
          $message.='<p><strong>Please Kindly Note: That You Have been Successfully Cleared for Course Enrollment for this '.$sm.' in '.$se.' Academic Session And Level '.$lv.' Please Login to Please Login to University Portal https://my.uat.edu.ng to  Complete Your Course Registration <br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}
function approvefdmail($stud,$se,$le,$con)
{
   
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
    //die($maix);
        $to = $rows['uniemail'];
        require_once "phpmailer/class.phpmailer.php";
        $mail = new PHPMailer;
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Course Enrollment Clearance');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Enrollment Clearance';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['lastname']. ', '. $rows['firstname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$le.'</b></span>';
          $message .= '<br>';
          $message.='<p><strong>Please Kindly Note: That You Have been Successfully Cleared for Course Enrollment for Both First And Second Semesters  in '.$se.' Academic Session And Level '.$le.' Please Login to University Portal https://my.uat.edu.ng to Complete Your Course Registration <br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}

function approvedpmail($stud,$sm,$se,$le,$con)
{
   
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student  WHERE studentid='$stud'"));
   
        $to = $rows['uniemail'];
        require_once "phpmailer/class.phpmailer.php";
        $mail = new PHPMailer;
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Course Enrollment Clearance');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Enrollment Clearance';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['lastname']. ', '. $rows['firstname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sm.'</b></span>';
          $message .= '<br>';
          
          $message .= '<span class=MsoNormal>Level: <b>'.$le.'</b></span>';
          $message .= '<br>';
          $message.='<p><strong>Please Kindly Note: That You Have been Successfully Cleared for Course Enrollment for this '.$sm.' in '.$se.' Academic Session And Level '.$le.' Please Login to Please Login to University Portal https://my.uat.edu.ng to  Complete Your Course Registration <br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}
 ?>
