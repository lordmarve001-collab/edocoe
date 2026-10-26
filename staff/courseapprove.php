<?php 
session_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include "../include/dbconfig.php";
$harr = $_SESSION["staffid"];
include 'dataco.php';
set_time_limit(300);
ini_set('max_execution_time', '300');
require"phpmailer/class.phpmailer.php";
$mail = new PHPMailer();
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout"); 
    }
    else{
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='student course registration'"));
$sems=$dat['semester'];
$ses=$dat['session'];
$dead=$dat['deadline'];
//$date = date('Y-M-d'); 
$error='';
$newd = date('Y-m-d', strtotime($dead. ' + 3 days'));
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
if (isset($_POST['approved'])) {
    $student=$_POST['student'];
    $progna=$_POST['progname'];
    $leve=$_POST['leve'];
    $sess=$_POST['ses'];
    $sem=$_POST['sem'];
    /*Result Check*/
    $resk=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM result WHERE studentid='$student' AND semester='$sem' AND acadsession='$sess' AND level='$leve' AND programme='$progna' "));
    if ($resk=='') {
        
   
    //Selecting Course information from the studentregsemester table
    $load=mysqli_query($con,"SELECT * FROM studentcourseregistration WHERE studentid='$student' AND semester='$sem' AND session='$sess' AND level='$leve'");
    while ($rowl=mysqli_fetch_assoc($load)) {
        $code=$rowl['coursecode'];
        $sta=$rowl['custatus'];

        $insr=mysqli_query($con,"INSERT INTO result(studentid,acadsession,semester,coursecode,ca,exam,level,programme,custatus) VALUES ('$student','$sess','$sem','$code','0','0','$leve','$progna','$sta')");
        //$lms=mysqli_query($con,"INSERT INTO lms(acadsession,semester,idnumber,coursecode,type) VALUES ('$ses','$sems','$student','$code','student')");

    }
    $tod=date("Y-m-d");
    $upda=mysqli_query($con,"UPDATE studentregsem SET status= 'Approved' WHERE studentid='$student' AND semester='$sem' AND session='$sess' AND level='$leve'");
    if ($upda) {
        //$mail = new PHPMailer;                
        approvedmail($con,$student,$sem,$sess,$leve,$progna,$hostna,$passd,$usern,$mail);
        hodmail($con,$unmail,$student,$sem,$sess,$leve,$progna,$hostna,$passd,$usern,$mail);
        
    }
}else{
    $tod=date("Y-m-d");
    $upda=mysqli_query($con,"UPDATE studentregsem SET status= 'Approved' WHERE studentid='$student' AND semester='$sem' AND session='$sess' AND level='$leve'");
 }
 }
/*For Course Registration DisApproval
*Implementation to be Carried Out 
1. mailing of Disapproval information to Student email
2. With Reason for the Disapproval
*/

if (isset($_POST['disapproved'])) {
    $student=$_POST['student'];
    $progna=$_POST['progname'];
    $leve=$_POST['leve'];
    $sess=$_POST['ses'];
    $sem=$_POST['sem'];
    $mess=$_POST['messag'];
    //Disaaproving Algorithm
    $tod=date("Y-m-d H:i:s a");
    $upda=mysqli_query($con,"UPDATE studentregsem SET status= 'Disapproved' WHERE studentid='$student' AND semester='$sem' AND session='$sess' AND level='$leve'");
if ($upda) {
            disapprovedmail($con,$unmail,$student,$sem,$sess,$leve,$progna,$mess,$hostna,$passd,$usern,$mail);
            //studsms($student,$sems,$ses,$leve,$con,$hostna,$passd,$usern);
            }
}

                        
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
</head>
<body  class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php');?> 
        <div class="content-wrapper">
                <div class="content-container">
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="hod")
                  {
                    
                     include('includes/hodbar.php');

                    }else if ($paych['position']=="lecturer") {
                        
                   
                    include('includes/lectbar.php');
                     }elseif ($paych['position']=="dean") {
                        include('includes/deanbar.php');
                    }elseif ($paych['position']=="superadmin") {
                        include('includes/supbar.php');
                    }
                    else{
                   
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title">REGISTERED STUDENTS</h4>
                                </div>
                                
                            </div>
                            
                            <!-- /.row -->
                        </div>
<section class="section">
    <div class="container-fluid">
     <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                    <?php
                     //geting Current Date 
                    $dats= date("Y-m-d");


                    $paysc=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                    
                    if ($paysc['position']=='hod')
                     
                     {
                        if ($dats>$newd) {
                        
                        echo "<b style=color:red;>Course Registration Approval Portal Is Close</b>";
                      }else{ 
                        $date1 = new DateTime($newd);
                       $now = new DateTime();
                       $diff = $date1->diff($now);
                    ?><p style='font-weight: bolder;'>Semester Course Registration Approval <b style="color: red"> <?php echo htmlentities($ses); ?></b> Academic Session <b style="color: red"><?php echo htmlentities($sems) ?>,  Days Remaining for the Portal To Close: <?php echo $diff->days.' '; ?></b> </b></p>
                         <table id="example" class="table table-striped table-bordered " cellspacing="0">
                            <thead>
                                
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Session</th>
                                    <th>Semester</th>
                                    <th>Level</th>
                                    <th>Programme</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                            <?php
                            $query = ("SELECT * FROM staffprive WHERE staffid ='" . $_SESSION['staffid'] . "'");
$result = mysqli_query($con, $query);
while ($row = mysqli_fetch_assoc($result)) {
	
	$pgs=$row['department'];
}
                            
                           $tr=mysqli_query($con,"SELECT * FROM program WHERE did='$pgs'");
                                   
                            $res = mysqli_fetch_all($tr,MYSQLI_ASSOC);
                            foreach($res as $rt)
                            {
                                $prgs=$rt['pname'];

                                $sql ="SELECT * FROM studentregsem  WHERE  status='Pending' AND session='$ses' AND semester='$sems' AND programme='$prgs' ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            { 

                           ?>
                           <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result['studentid']) ?></td>
                            <td><?php echo htmlentities($result['session']);?></td>
                            <td><?php echo htmlentities($result['semester']);?></td>
                            <td><?php echo htmlentities($result['level']);?></td>
                            <td><?php echo htmlentities($result['programme']);?></td>
                            <td><?php echo htmlentities($result['status']);?></td>
                            <td>
                            <button class="userinfo btn btn-primary" 
                                studentid="<?php echo $result['studentid'];?>" 
                                semester="<?php echo $result['semester'];?>" 
                                sessions="<?php echo $result['session'];?>"
                                prog="<?php echo $result['programme'];?>"
                                level="<?php echo $result['level'];?>"
                                >View Courses</button> 
                            </td>
                            </tr>
                           <?php
                           $cnt=$cnt+1;
                            }
                             }
                            ?>
                            </tbody>
                            
                            </table>
                    <?php 
                    }
                }
                    elseif ($paysc['position']=='lecturer' OR $paysc['position']=='dean') 
                    { 
                       if ($dats>$dead) {
                        
                                                echo "<b style=color:red;>Course Registration Approval Portal Is Close</b>";
                      }else{ 
                        $lvd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM leveladv WHERE staffid='".$_SESSION['staffid']."' AND session='$ses' "));
                        
                        if ($lvd !="") {
                            $levf=$lvd['level'];
                        $smf=$lvd['semester'];
                        $sef=$lvd['session'];
                            $date1 = new DateTime($dead);
                                               $now = new DateTime();
                                               $diff = $date1->diff($now);
                            
                    ?><p style='font-weight: bolder;'>Semester Course Registration Approval <b style="color: red"> <?php echo htmlentities($ses); ?></b> Academic Session <b style="color: red"><?php echo htmlentities($sems) ?>,  Days Remaining for the Portal To Close: <?php echo $diff->days.' '; ?></b> </b></p>
                         <table id="example" class="table table-striped table-bordered " cellspacing="0">
                            <thead>
                                
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Session</th>
                                    <th>Semester</th>
                                    <th>Level</th>
                                    <th>Programme</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                                <tbody>
                                     <?php
                                    $tr=mysqli_query($con,"SELECT * FROM program WHERE did='$deplt'");
                                    //$query = mysqli_query($con,$sql);
                            $res = mysqli_fetch_all($tr,MYSQLI_ASSOC);
                            foreach($res as $rt)
                            {
                                $prgs=$rt['pname'];

                                $sql ="SELECT * FROM studentregsem  WHERE  status='Pending' AND session='$ses' AND level='$levf' AND semester='$sems' AND programme='$prgs' ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            { 

                           ?>
                           <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result['studentid']) ?></td>
                            <td><?php echo htmlentities($result['session']);?></td>
                            <td><?php echo htmlentities($result['semester']);?></td>
                            <td><?php echo htmlentities($result['level']);?></td>
                            <td><?php echo htmlentities($result['programme']);?></td>
                            <td><?php echo htmlentities($result['status']);?></td>
                            <td>
                            <button class="userinfo btn btn-primary" 
                                studentid="<?php echo $result['studentid'];?>" 
                                semester="<?php echo $result['semester'];?>" 
                                sessions="<?php echo $result['session'];?>"
                                prog="<?php echo $result['programme'];?>"
                                level="<?php echo $result['level'];?>"
                                >View Courses1</button> 
                            </td>
                            </tr>
                           <?php
                           $cnt=$cnt+1;
                            }
                             }
                            ?>
                                </tbody>
                            </table>
                    <?php
                       }
                       else{
                       ?>
                          <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Are Not A Level Advicer Thank You.</p>
                       <?php
                       }
                       }//Close Tag For the If Statement
                    } 
                    elseif ($paysc['position']=='superadmin') 
                    {
                       
                    ?>
                    <form method="POST" action="" class="form-horizontal">
                            <div class="form-group">
                            <label for="default" class="col-sm-2">Programme:</label>
                             <div class="col-sm-10">
                             <select name="programm" class="form-control clid" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Programme</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM program ORDER BY pname ");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" class="col-sm-2">Session:</label>
                             <div class="col-sm-10">
                             <select name="sectt" class="form-control clid" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Session</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" class="col-sm-2">Semester:</label>
                             <div class="col-sm-10">
                             <select name="semset" class="form-control clid" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Semester</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest ");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" class="col-sm-2">Level:</label>
                             <div class="col-sm-10">
                             <select name="levl" class="form-control clid" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Level</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM level ORDER BY levels ");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                        <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="queryt" class="btn btn-primary">View Student</button>
                        </div>
                    </div>    
                        </form>
                        
                   
                    <?php
                     if (isset($_POST['queryt'])) {
    if (empty($_POST['programm'])) {
        $showModal='true';
        $error="Please Select Programme";

    }elseif (empty($_POST['semset'])) {
        $showModal='true';
            $error="Please Select Semester";
    }elseif (empty($_POST['sectt'])) {
        $showModal='true';
            $error="Please Select Academic Session";
    }elseif (empty($_POST['levl'])) {
        $showModal='true';
            $error="Please Select Level";
    }else{

      $prgs=mysqli_escape_string($con,$_POST['programm']);
      $semset=mysqli_escape_string($con,$_POST['semset']);
      $levl  =mysqli_escape_string($con,$_POST['levl']);
      $sects  =mysqli_escape_string($con,$_POST['sectt']);

                        if ($levl!=='') { 
                        $date1 = new DateTime($newd);
                       $now = new DateTime();
                       $diff = $date1->diff($now);
                    ?><p style='font-weight: bolder;'>Semester Course Registration Approval <b style="color: red"> <?php echo htmlentities($ses); ?></b> Academic Session <b style="color: red"><?php echo htmlentities($sems) ?>,  Days Remaining for the Portal To Close: <?php echo $diff->days.' '; ?></b> </b></p>
                         <table id="example" class="table table-striped table-bordered " cellspacing="0">
                            <thead>
                                
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Session</th>
                                    <th>Semester</th>
                                    <th>Level</th>
                                    <th>Programme</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                            <?php
             $sql ="SELECT * FROM studentregsem  WHERE  (status='Pending' OR status='Disapproved') AND session='$sects' AND semester='$semset' AND programme='$prgs' ";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            { 

                           ?>
                           <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result['studentid']) ?></td>
                            <td><?php echo htmlentities($result['session']);?></td>
                            <td><?php echo htmlentities($result['semester']);?></td>
                            <td><?php echo htmlentities($result['level']);?></td>
                            <td><?php echo htmlentities($result['programme']);?></td>
                            <td><?php echo htmlentities($result['status']);?></td>
                            <td>
                            <button class="userinfo btn btn-primary" 
                                studentid="<?php echo $result['studentid'];?>" 
                                semester="<?php echo $result['semester'];?>" 
                                sessions="<?php echo $result['session'];?>"
                                prog="<?php echo $result['programme'];?>"
                                level="<?php echo $result['level'];?>"
                                >View Courses</button> 
                            </td>
                            </tr>
                           <?php
                           $cnt=$cnt+1;
                            }
                             
                            ?>
                            </tbody>
                            
                            </table>
                    <?php 
                    }
                }}
                        }else{
                       ?>
                          <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                       <?php
                       }
                      
                    ?>
                    
                    <div class="modal" id="viewModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                             <!-- Modal Header -->
                               <div class="modal-header">
                                    <h5 class="modal-title">STUDENT COURSE INFORMATION</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                              <!-- Modal body -->
                              <div class="modal-body" id="teacher_details">

                              </div>
                            </div>
                        </div>
                    </div>
                
                    </div>
                </div>
            </div>
     </div>
    </div>
</section>
                    </div>
                </div>
        </div>
    </div>
    <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
                
            <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalS" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Graduation List is Successfully Upload and Approved <i class="fa fa-thumb"></i> </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
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
                            <p>An Error Occured While Uploading Please Try Again!!!</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Error Modal Box -->
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
                            <p>There No Result Base on the Criterial Selected</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
</body>
<?php 
include"includes/buton.php";?>


<script type="text/javascript">
$(document).ready(function(){

//$('.userinfo').click(function(){
$('body').on('click','.userinfo',function(){
var applid = $(this).attr('studentid');
var ssid = $(this).attr('semester');
var sess = $(this).attr('sessions');
var prog = $(this).attr('prog');
var level = $(this).attr('level');

// AJAX request
$.ajax({
url: 'hodappro',
type: 'POST',
//type: 'GET',
data: {applid: applid,ssid:ssid,sess:sess,prog:prog,level:level},
success: function(response){ 
$('#teacher_details').html(response);

      // Display Modal
      $('#viewModal').modal('show');
}
});
});
}); 



</script>
<?php           
    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
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
</html>
<?php } 

/*Student Sms And EMail On Course Registration*/

//,$hostna,$passd,$usern
function approvedmail($con,$stud,$sm,$se,$lvc,$pp,$host,$pass,$user,$mail)
{
    
    //$rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$stud'"));

    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$stud'"));
    //die($maix);
        $to = $rows['email'];
         try {
        
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $user;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        //$mail->Priority = 1;
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;
        $mail->setFrom($user, 'Course Registration Approved');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Approved';
        //$content = 
        $message = '<html><body> 
        <style type="text/css">
        table{
            border: 1px solid black;
            cellspacing:0px;
            cellpadding:0px;
            width: 100%;
        }
        table td, table th{
            border: 1px solid black;
        }
        
        </style>'; 
          $message .= '<span>Student Name: <b style="text-transform:capitalize;">'.$rows['firstname']. ', '. $rows['lastname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stud.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$se.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sm.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Email: <b>'.$email.'</b></span>';
          $message .= '<br><span class=MsoNormal>Faculty: <b>'.$rows['fname'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$lvc.'</b></span>';
          $message .= '<p></p>';
          $message .= '<p></p>';
           //$message .= '<p align="centre"><strong> COURSE INFORMATION </strong></p>';
          $message .= '<table rules="all" border="1" cellpadding="5" cellspacing="0">
                    <tr>
                    <th>#</th>
                    <th style="width: 130px">Course Code</th>
                    <th>Course Title</th>
                    <th style="width: 120px;">Credit Unit</th>
                    <th style="width: 120px;">Status</th>
                    </tr>';
         $ques= mysqli_query($con,"SELECT * FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$stud' AND studentcourseregistration.level='$lvc' AND studentcourseregistration.semester='$sm' AND studentcourseregistration.session='$se' ORDER BY studentcourseregistration.coursecode");
         $sr =1;
          while ($row= mysqli_fetch_array($ques)){
            $message .= '<tr>';
            $message .='<td>' . $sr.'</td>';
            $message .= '<td>' . $row['coursecode'].'</td>';
            $message .= '<td>' . $row['title'].'</td>';
            $message .= '<td>' . $row['unit'].'</td>';
            $message .= '<td>' . substr($row["custatus"], 0,1).'</td>';
            $message.= '</tr>';
           $sr++;  
           } 
          $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$stud' AND studentcourseregistration.level='$lvc' AND studentcourseregistration.semester='$sm' AND studentcourseregistration.session='$se' ORDER BY studentcourseregistration.coursecode";
          $resultt= mysqli_query($con,$queryy);
                while($rowz = mysqli_fetch_array($resultt)){
                                      //$_POST['totalUnits']=$rowz['sumunit'];
                                      
            $message .= '<tr>';
            $message .= '<td colspan="3" align="right" font-weight="bolder">Total Credit Units </td>';
            $message .= '<td>' .$rowz['sumunit'].'</td>';
            $message.= '</tr>';
            }                           
            $message .= '</table>';
            $message .= '</body></html>';
            $message.='<p><strong>Please Kindly Note: That Your Course Registration Is Approved. Login to College Portal For Printing</strong></p>';
            $mail->MsgHTML($message);
            $mail->send();
            $mail->SmtpClose();
            $mail->ClearAllRecipients();
            } catch (Exception $e) {
            echo $e;
        }
            //return true;
}
function hodmail($con,$emak,$studt,$sesm,$ss,$lev,$prn,$host,$pass,$user,$mail)
{
    
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student JOIN faculty ON student.faculty=faculty.fid WHERE student.studentid='$studt'"));
        $to = $emak;
       
        try {
            
        
       $mail->isSMTP();
       $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; 
        $mail->SMTPKeepAlive = true;  
        //$mail->Priority = 1;                            // Enable SMTP authentication
        $mail->Username =$user;                 // SMTP username
        $mail->Password =$pass;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 

        $mail->setFrom($user, 'Course Registration Approved');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Approved';
        //$content = 
        $message = '<html><body> 
        <style type="text/css">
        table{
            border: 1px solid black;
            cellspacing:0px;
            cellpadding:0px;
            width: 100%;
        }
        table td, table th{
            border: 1px solid black;
        }
        
        </style>
        
    '; 
    
          
          $message .= '<span>Student Name: <b style="text-transform:capitalize;">'.$rows['firstname']. ', '. $rows['lastname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$studt.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$ss.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sesm.'</b></span>';
          $message .= '<br><span class=MsoNormal>Faculty: <b>'.$rows['fname'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$lev.'</b></span>';
          $message .= '<p></p>';
          $message .= '<p></p>';
           //$message .= '<p align="centre"><strong> COURSE INFORMATION </strong></p>' /*$studt,$sesm,$ss,$lev,$prn*/;
          $message .= '<table rules="all" border="1" cellpadding="5" cellspacing="0">
                    <tr>
                    <th>#</th>
                    <th style="width: 130px">Course Code</th>
                    <th>Course Title</th>
                    <th style="width: 120px;">Credit Unit</th>
                    <th style="width: 120px;">Status</th>
                    </tr>';
         $ques= mysqli_query($con,"SELECT * FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$studt' AND studentcourseregistration.level='$lev' AND studentcourseregistration.semester='$sesm' AND studentcourseregistration.session='$ss' ORDER BY studentcourseregistration.coursecode");
         $sr =1;
          while ($row= mysqli_fetch_array($ques)){
            $message .= '<tr>';
            $message .='<td>' . $sr.'</td>';
            $message .= '<td>' . $row['coursecode'].'</td>';
            $message .= '<td>' . $row['title'].'</td>';
            $message .= '<td>' . $row['unit'].'</td>';
            $message .= '<td>' . substr($row["custatus"], 0,1).'</td>';
            $message.= '</tr>';
           $sr++;  
           } 
          $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$studt' AND studentcourseregistration.level='$lev' AND studentcourseregistration.semester='$sesm' AND studentcourseregistration.session='$ss' ORDER BY studentcourseregistration.coursecode";
          $resultt= mysqli_query($con,$queryy);
                while($rowz = mysqli_fetch_array($resultt)){
                                      //$_POST['totalUnits']=$rowz['sumunit'];
                                      
            $message .= '<tr>';
            $message .= '<td colspan="3" align="right" font-weight="bolder">Total Credit Units </td>';
            $message .= '<td>' .$rowz['sumunit'].'</td>';
            $message.= '</tr>';
            }                           
            $message .= '</table>';
            $message .= '</body></html>';
            $message.='<p><strong>This Is to Notify You That You Have Approved the Student Course Registration</strong></p>';
            $mail->MsgHTML($message);
            $mail->send();
            $mail->SmtpClose();
            $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}

function hodmails($con,$emak,$studt,$sesm,$ss,$lev,$prn,$me,$host,$pass,$user,$mail)
{   
    
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student JOIN faculty ON student.faculty=faculty.fid WHERE student.studentid='$studt'"));
    //die($emak);
        $to = $emak;
        
        try{
        $mail->isSMTP();
        $mail->Host =$host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; 
        $mail->SMTPKeepAlive = true;  
        //$mail->Priority = 1;                            // Enable SMTP authentication
        $mail->Username =$user;                 // SMTP username
        $mail->Password =$pass;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 

        $mail->setFrom($user, 'Course Registration Disapproved');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Disapproved';
        //$content = 
        $message = '<html><body> 
        <style type="text/css">
        table{
            border: 1px solid black;
            cellspacing:0px;
            cellpadding:0px;
            width: 100%;
        }
        table td, table th{
            border: 1px solid black;
        }
        
        </style>
        
    '; 
    
          
          $message .= '<span>Student Name: <b style="text-transform:capitalize;">'.$rows['firstname']. ', '. $rows['lastname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$studt.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$ss.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sesm.'</b></span>';
          $message .= '<br><span class=MsoNormal>Faculty: <b>'.$rows['fname'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$lev.'</b></span>';
          $message .= '<p></p>';
          $message .= '<p></p>';
           
          $message .= '<table rules="all" border="1" cellpadding="5" cellspacing="0">
                    <tr>
                    <th>#</th>
                    <th style="width: 130px">Course Code</th>
                    <th>Course Title</th>
                    <th style="width: 120px;">Credit Unit</th>
                    <th style="width: 120px;">Status</th>
                    </tr>';
         $ques= mysqli_query($con,"SELECT * FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$studt' AND studentcourseregistration.level='$lev' AND studentcourseregistration.semester='$sesm' AND studentcourseregistration.session='$ss' ORDER BY studentcourseregistration.coursecode");
         $sr =1;
          while ($row= mysqli_fetch_array($ques)){
            $message .= '<tr>';
            $message .='<td>' . $sr.'</td>';
            $message .= '<td>' . $row['coursecode'].'</td>';
            $message .= '<td>' . $row['title'].'</td>';
            $message .= '<td>' . $row['unit'].'</td>';
            $message .= '<td>' . substr($row["custatus"], 0,1).'</td>';
            $message.= '</tr>';
           $sr++;  
           } 
          $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$studt' AND studentcourseregistration.level='$lev' AND studentcourseregistration.semester='$sesm' AND studentcourseregistration.session='$ss' ORDER BY studentcourseregistration.coursecode";
          $resultt= mysqli_query($con,$queryy);
                while($rowz = mysqli_fetch_array($resultt)){
                                      //$_POST['totalUnits']=$rowz['sumunit'];
                                      
            $message .= '<tr>';
            $message .= '<td colspan="3" align="right" font-weight="bolder">Total Credit Units </td>';
            $message .= '<td>' .$rowz['sumunit'].'</td>';
            $message.= '</tr>';
            }                           
            $message .= '</table>';
            $message .= '</body></html>';
            $message.='<p><strong>This Is to Notify You That You Have Disapproved the Student Course Registration for the Reason Below <br> <b style="color:red;">'.$me.'</b></strong></p>';
            $mail->MsgHTML($message);
            $mail->send();
            $mail->SmtpClose();
            $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}

function disapprovedmail($con,$mall,$stude,$sem,$sez,$levl,$progn,$mes,$host,$pass,$user,$mail)
{
    hodmails($con,$mall,$stude,$sem,$sez,$levl,$progn,$mes,$host,$pass,$user,$mail);

    
    
        $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student JOIN faculty ON student.faculty=faculty.fid WHERE student.studentid='$stude'"));
    //die($maix);
        $to = $rows['email'];
        try{
        $mail->isSMTP();
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; 
        $mail->SMTPKeepAlive = true;  
        //$mail->Priority = 1;                            // Enable SMTP authentication
        $mail->Username =$user;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 

        $mail->setFrom($user, 'Course Registration Disapproved');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Disapproved Notification';
        //$content = 
        $message = '<html><body> 
        <style type="text/css">
        table{
            border: 1px solid black;
            cellspacing:0px;
            cellpadding:0px;
            width: 100%;
        }
        table td, table th{
            border: 1px solid black;
        }
        
        </style>
        
    '; 
    //Getting the student information especially the full name
    
          
          $message .= '<span>Student Name: <b style="text-transform:capitalize;">'.$rows['firstname']. ', '. $rows['lastname'].' '.$rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stude.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$sez.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$sem.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Email: <b>'.$rows['uniemail'].'</b></span>';
          $message .= '<br><span class=MsoNormal>Faculty: <b>'.$rows['fname'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$levl.'</b></span>';
          $message .= '<p></p>';
          $message .= '<p></p>';
           //$message .= '<p align="centre"><strong> COURSE INFORMATION </strong></p>';
          /*
    $sem,$sez,$levl,$progn,*/
        $message .= '<table rules="all" border="1" cellpadding="5" cellspacing="0">
                    <tr>
                    <th>#</th>
                    <th style="width: 130px">Course Code</th>
                    <th>Course Title</th>
                    <th style="width: 120px;">Credit Unit</th>
                    <th style="width: 120px;">Status</th>
                    </tr>';
     $ques= mysqli_query($con,"SELECT * FROM studentcourseregistration INNER JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$stude' AND studentcourseregistration.level='$levl' AND studentcourseregistration.semester='$sem' AND studentcourseregistration.session='$sez' ORDER BY studentcourseregistration.coursecode");
      $sr =1;
      while ($row= mysqli_fetch_array($ques)){
        $message .= '<tr>';
        $message .='<td>' . $sr.'</td>';
        $message .= '<td>' . $row['coursecode'].'</td>';
        $message .= '<td>' . $row['title'].'</td>';
        $message .= '<td>' . $row['unit'].'</td>';
        $message .= '<td>' . substr($row["custatus"], 0,1).'</td>';
        $message.= '</tr>';
       $sr++;  
       } 
      $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration INNER JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$stude' AND studentcourseregistration.level='$levl' AND studentcourseregistration.semester='$sem' AND studentcourseregistration.session='$sez' ORDER BY studentcourseregistration.coursecode";
      $resultt= mysqli_query($con,$queryy);
            while($rowz = mysqli_fetch_array($resultt))
            {
                                  //$_POST['totalUnits']=$rowz['sumunit'];
                                  
                $message .= '<tr>';
                $message .= '<td colspan="3" align="right" font-weight="bolder">Total Credit Units </td>';
                $message .= '<td>' .$rowz['sumunit'].'</td>';
                $message.= '</tr>';
            }                           
    $message .= '</table>';
    $message .= '</body></html>';
    $message.='<p><strong>Please Kindly Note: That Your Course Registration Is Disapproved. For the Following Reason <br/> <b style="color:red;">'.$mes.'</b></strong></p>';
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAllRecipients();
        } catch (Exception $e) {
            
        }
        //return true;
    
}


?>