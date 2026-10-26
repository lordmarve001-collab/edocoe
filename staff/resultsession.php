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
require"phpmailer/class.phpmailer.php";
$mail = new PHPMailer();
$error='';
/*Data Submission*/
if (isset($_POST['submitcd'])) 
  {
/*
remark
semset
deadline
minscore
*/
    if (empty($_POST['remark'])) {
      $showModalEr='true';
      $error='Session';
    }else{
      $sect=mysqli_escape_string($con,$_POST['remark']);
      $sems=mysqli_escape_string($con,$_POST['semset']);
      $dead=mysqli_escape_string($con,$_POST['deadline']);
      $Min=mysqli_escape_string($con,$_POST['minscore']);

       //Checking to avoid Duplication
       $id = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(id) as userid FROM resultsession"))['userid'];
        $id = $id + 1;
        $sel=mysqli_query($con,"SELECT * FROM resultsession WHERE id='$id'");
        if (mysqli_num_rows($sel) > 0)
         {
          $id = $id + 1;
         }
         else
         {
            $ins=mysqli_query($con,"INSERT INTO resultsession (session,semester,deadline,id,description) VALUES ('$sect','$sems','$dead','$id','$Min')");
            if ($ins) {
              $showModal='true';
            }else
            {
              $showModalr='true';
              $error='Error Occured While Submitting';
            }
         }
    }
  }


if(isset($_POST['updateuse'])){
        $user_id = mysqli_escape_string($con, $_SESSION['did']);
        $lastname = mysqli_escape_string($con,$_POST['section']);
        $address = mysqli_escape_string($con,$_POST['semes']);
        $dead = mysqli_escape_string($con,$_POST['deadline']);
        $lcu=mysqli_query($con, "UPDATE resultsession SET  session='$lastname',semester='$address',deadline='$dead' WHERE id = '$user_id'") or die(mysqli_error());
        if ($lcu) {
            $showModals='true';
            // Examprocessing
            if ($user_id=='1') {
                staffsms($address,$lastname,$dead,$con);
                staffemail($address,$lastname,$dead,$con,$mail,$hostna,$usern,$passd);
            }
            // Course Allocation
            if ($user_id=='5') {
                staffsmc($address,$lastname,$dead,$con);
                staffemailc($address,$lastname,$dead,$con,$mail,$hostna,$usern,$passd);
            }
            //Student Course Registration
            if ($user_id=='3') {
                //studsms($address,$lastname,$dead,$con);
                studemail($address,$lastname,$dead,$con,$mail,$hostna,$usern,$passd);
                studhsms($address,$lastname,$dead,$con);
                studhemail($address,$lastname,$dead,$con,$mail,$hostna,$usern,$passd);
                studlsms($address,$lastname,$dead,$con);
                studlemail($address,$lastname,$dead,$con,$mail,$hostna,$usern,$passd);
            }

        }else{
            $error="Error Occured While Updating";
            $showModalr='true';
        }
        
    }

if(isset($_POST['updateusea'])){
        $user_id = mysqli_escape_string($con, $_SESSION['did']);
        $lastname = mysqli_escape_string($con,$_POST['section']);
        $dead = mysqli_escape_string($con,$_POST['deadline']);
        //subject
        $lcu=mysqli_query($con, "UPDATE resultsession SET  session='$lastname',semester='n/a',deadline='$dead' WHERE id = '$user_id'") or die(mysqli_error());
        if ($lcu) {
            $showModals='true';
        }else{
            $error="Error Occured While Updating";
            $showModalr='true';
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

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                    }elseif ($paych['position']=="academicaffair") {
                        include('includes/acdbar.php');
                    }else if ($paych['position']=="registrar") {
                        include('includes/admoff.php');
                    }elseif ($paych['position']=="subadmin") {
                        include('includes/subbar.php');
                  }elseif ($paych['position']=="admin_officer")
                    {
                        include('includes/admof.php');
                    }
                    else{
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Academic Activities Setup</h2>
                                
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
                                                if ($paych['position']=="superadmin" OR $paych['position']=="subadmin") {
                                                   ?>
                                                   <section class="section newsect">
                                           <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Session:
                                                        </label>
                                                        
                                                            <select class="form-control clid" name="remark">
                                                                <option selected disabled>Please select Session</option>
                                                                <?php
                                                            $ress = mysqli_query($con,"SELECT * FROM sections ORDER BY section DESC");
                                                            while ($row=mysqli_fetch_array($ress))
                                                            {
                                                                ?>
                                                                <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                                                                <?php

                                                            }
                                                             ?>
                                                            </select>
                                                       
                                                      </div>
                                                      
                                                      <div class="form-group">
                                                        <label for="default" >
                                                          Semester:
                                                        </label>
                                                       <input type="text" class="form-control clid" name="semset">
                                                       
                                                      </div>
                                                  </div> 
                                              </div> 
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="default">
                                                           Deadline:
                                                        </label>
                                                        
                                                            <input type="date" max="12" min="0" name="deadline" class="form-control clid"  required="required">
                                                        
                                                      </div>
                                                       <div class="form-group">
                                                        <label for="default">
                                                           Description:
                                                        </label>
                                                        
                                                            <input type="text" max="100" min="0" name="minscore" class="form-control clid"  required="required">
                                                        
                                                      </div>
                                                      
                                                     <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcd" class="btn btn-primary">Submit Data</button>
                                                        </div>
                                                    </div>
                                                  </div> 
                                              </div> 
                                              
                                            </form>
                                        </section>
                                                   <div>
                          <table id="example" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Session</th>
                                  <th>Semester</th>
                                  <th>Deadline</th>
                                  <th>Description</th>                                
                                  <th>Action</th>                                
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM resultsession ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['session']) ?></td>
                                <td><?php echo htmlentities($result['semester']);?></td>
                                <td><?php echo htmlentities($result['deadline']);?></td>
                                <td><?php echo htmlentities($result['description']);?></td>
                                <td><button class="btn btn-primary userinfo" id="<?php echo $result['id'] ?>"><span class="fa fa-edit"></span></button></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div> 
                            <?php 
                                }elseif ($paych['position']=="academicaffair") {
                                                   ?>
                                                   <div>
                          <table id="example" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Session</th>
                                  <th>Semester</th>
                                  <th>Deadline</th>
                                  <th>Description</th>                                
                                  <th>Action</th>                                
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM resultsession ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['session']) ?></td>
                                <td><?php echo htmlentities($result['semester']);?></td>
                                <td><?php echo htmlentities($result['deadline']);?></td>
                                <td><?php echo htmlentities($result['description']);?></td>
                                <td><button class="btn btn-primary userinfo" id="<?php echo $result['id'] ?>"><span class="fa fa-edit"></span></button></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
                                                   <?php
                                                }elseif ($paych['position']=="registrar" OR $paych['position']=="admin_officer" OR $paych['position']=="superadmin" ) {
                                                    ?>
                                                    <div>
                          <table id="example" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Session</th>
                                  <th>Deadline</th>
                                  <th>Description</th>                                
                                  <th>Action</th>                                
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM resultsession WHERE description='admission processing' ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['session']) ?></td>
                               
                                <td><?php echo htmlentities($result['deadline']);?></td>
                                <td><?php echo htmlentities($result['description']);?></td>
                                <td><button class="btn btn-primary userinfof"id="<?php echo $result['id'] ?>"><span class="fa fa-edit"></span></button></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
                                                <?php
                                                }else{ ?>
                                                    <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                                           <?php     }
                                            ?>                      
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
        <div class="modal" id="viewModal">
  <div class="modal-dialog">
    <div class="modal-content" >

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Academic Activities Update
        <button type="button" class="close" data-dismiss="modal" style="float: right;">&times;</button></h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="teacher_details">

      </div>

      
    </div>
  </div>
</div> 
<div class="modal" id="viewModals">
  <div class="modal-dialog">
    <div class="modal-content" >

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Admission Academic Activity Update
        <button type="button" class="close" data-dismiss="modal" style="float: right;">&times;</button></h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="teacher_detail">

      </div>

      
    </div>
  </div>
</div> 
        <!-- Ends Here -->
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
                            <p>Please Select <?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModalr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
                            <p>Details Successfully Submitted</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Details Successfully Update</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
            if(!empty($showModalr)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModalr").modal("show");
                });
            </script>';
            }
            if(!empty($showModal)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModal").modal("show");
                });
            </script>';
            }
            if(!empty($showModals)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModals").modal("show");
                });
            </script>';
            }
        ?> 
        <script type="text/javascript">
             $('body').on('click','.userinfo',function(){
            var userid = $(this).attr('id');
            // AJAX request
            $.ajax({
                    url: 'update_pro',
                    type: 'GET',
                    data: {userid: userid},
                    success: function(response)
                    { 
                        $('#teacher_details').html(response);
                        // Display Modal
                        $('#viewModal').modal('show');
                    }
                });
            });

        $('body').on('click','.userinfof',function(){
            var userid = $(this).attr('id');
            // AJAX request
            $.ajax({
                    url: 'update_proa',
                    type: 'GET',
                    data: {userid: userid},
                    success: function(response)
                    { 
                        $('#teacher_detail').html(response);
                        // Display Modal
                        $('#viewModals').modal('show');
                    }
                });
            });
       
        </script>     
    </body>
</html>
<?php } 
/*Staff Sms And EMail On Exam Processing
$address,$lastname,$dead,$con
*/
function staffsms($sem,$secc,$dea,$con){

  $email = "okpehjohcybertech@gmail.com";
  $password = "Test123";
  $date1 = new DateTime($dea);
  $now = new DateTime();
  $diff = $date1->diff($now);
  /*Retrieving The Staff Record Start Here*/
  $stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid WHERE people.status='active' ");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  //$diff->days;
  $message = "Dear: ".$stn.",\n";
  $message.= "Result uploading for ".$sem."  ".$secc." has started. All results must be uploaded and approved before the deadline of ".$dea."\n";
  $message.= "Thank You";
  $sender_name = "EDOCOE";
  $recipients = $res['phoneno'];
  $forcednd ="1";

  $data = array("email" => $email, "password" => $password,"message"=>$message, "sender_name"=>$sender_name,"recipients"=>$recipients,"forcednd"=>$forcednd);
  $data_string = json_encode($data);
  $ch = curl_init('https://app.multitexter.com/v2/app/sms');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
  $result = curl_exec($ch);
  $res_array = json_decode($result);
}
}
function staffemail($sem,$secc,$dea,$con,$mail,$hostna,$usern,$passd)
  {
       $date1 = new DateTime($dea);
       $now = new DateTime();
       $diff = $date1->diff($now);
        
        $stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid WHERE people.status='active' ");
          $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
          foreach($results as $res)
        {
        $stn=ucwords($res['firstname']);
        try {
        //$mail->SMTPDebug = 1;
        $mail->isSMTP();
        $mail->Host =$hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($usern, 'Result Uploading Notification');
        $mail->addAddress($res['uniemail']);
        $mail->isHTML(true);
        $mail->Subject = 'Result Uploading Notification';
        $message="Dear: ".$stn.",\n";
        $message.= "Result uploading for ".$sem."  ".$secc." has started. All results must be uploaded and approved before the deadline of ".$dea."\n";
       $message.= "Thank You";
        $mail->MsgHTML($message);
            $mail->send();
            $mail->SmtpClose();
            $mail->ClearAllRecipients();
            } catch (Exception $e) {
            //echo $e;
  }
 }
}
/*Staff Sms And EMail On Course Allocation*/
function staffsmc($sem,$secc,$dea,$con){
  $email = "okpehjohcybertech@gmail.com";
  $password = "Test123";
  $date1 = new DateTime($dea);
  $now = new DateTime();
  $diff = $date1->diff($now);
  /*Retrieving The Staff Record Start Here*/
  $stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='hod' ");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  $message = "Dear: ".$stn.",\n";
  $message.= "Course allocation for ".$sem."  ".$secc."  has been enabled. Please complete all course allocation before the deadline ".$dea."\n";
  $message.= "Thank You";
  $sender_name = "EDOCOE";
  $recipients = $res['phoneno'];
  $forcednd ="1";

  $data = array("email" => $email, "password" => $password,"message"=>$message, "sender_name"=>$sender_name,"recipients"=>$recipients,"forcednd"=>$forcednd);
  $data_string = json_encode($data);
  $ch = curl_init('https://app.multitexter.com/v2/app/sms');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
  $result = curl_exec($ch);
  $res_array = json_decode($result);
}
}
function staffemailc($sem,$secc,$dea,$con,$mail,$hostna,$usern,$passd){
     $date1 = new DateTime($dea);
       $now = new DateTime();
       $diff = $date1->diff($now);
       
        $stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='hod' ");
          $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
          foreach($results as $res)
        {
        $stn=ucwords($res['firstname']);
        try {
        //$mail->SMTPDebug = 1;
        $mail->isSMTP();
        $mail->Host =$hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($usern, 'Course Allocation Notification');
        $mail->addAddress($res['uniemail']);
        $mail->isHTML(true);
        $mail->Subject = 'Course Allocation Notification';
        $message = "Dear: ".$stn.",\n";
        $message.= "Course allocation for ".$sem."  ".$secc."  has been enabled. Please complete all course allocation before the deadline ".$dea."\n";
        $message.= "Thank You";
        $mail->MsgHTML($message);
            $mail->send();
            $mail->SmtpClose();
            $mail->ClearAllRecipients();
            } catch (Exception $e) {
            //echo $e;
  }
 }
}
/*Student Sms And EMail On Course Registration*/
function studsms($sem,$secc,$dea,$con){
  $email = "okpehjohcybertech@gmail.com";
  $password = "Test123";
  $date1 = new DateTime($dea);
  $now = new DateTime();
  $diff = $date1->diff($now);
  /*Retrieving The Staff Record Start Here*/
  $stf=mysqli_query($con,"SELECT * FROM student WHERE status='active' ");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  //$diff->days;
  $message = "Dear: ".$stn.",\n";
  $message.= "Course registration for ".$sem."  ".$secc." Session has started. Deadline is ".$dea."\n";
  $message.= "after which late fees apply";
  $sender_name = "EDOCOE";
  $recipients = $res['phoneno'];
  $forcednd ="1";

  $data = array("email" => $email, "password" => $password,"message"=>$message, "sender_name"=>$sender_name,"recipients"=>$recipients,"forcednd"=>$forcednd);
  $data_string = json_encode($data);
  $ch = curl_init('https://app.multitexter.com/v2/app/sms');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
  $result = curl_exec($ch);
  $res_array = json_decode($result);
}
}
function studemail($sem,$secc,$dea,$con,$mail,$hostna,$usern,$passd){
$date1 = new DateTime($dea);
       $now = new DateTime();
       $diff = $date1->diff($now);
       
        $stf=mysqli_query($con,"SELECT * FROM student WHERE status='active'");
          $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
          foreach($results as $res)
        {
        $stn=ucwords($res['firstname']);
        try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host =$hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($usern, 'Course Registration Notification');
        $mail->addAddress($res['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Notification';
        $message = "Dear: ".$stn.",\n";
        $message.= "Course registration for ".$sem."  ".$secc." Session has started. Deadline is ".$dea."\n";
        $message.= "after which late fees apply\n";
        $message.="Thank You";
        $mail->MsgHTML($message);
            $mail->send();
            $mail->SmtpClose();
            $mail->ClearAllRecipients();
            } catch (Exception $e) {
            //echo $e;
  }
 }
}
//First Quartal End Here
/*Staff Emails to Hod and Level Advicers*/
/*Student Sms And EMail On Course Registration*/
function studhsms($sem,$secc,$dea,$con){
  $email = "okpehjohcybertech@gmail.com";
  $password = "Test123";
  $date1 = new DateTime($dea);
  $now = new DateTime();
  $diff = $date1->diff($now);
  /*Retrieving The Staff Record Start Here*/
  $stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='hod'");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  //$diff->days;
  $message = "Dear: ".$stn.",\n";
  $message.= "Course registration for ".$sem."  ".$secc." Session has started. Deadline is ".$dea."\n";
  $message.= "after which late fees apply";
  $sender_name = "EDOCOE";
  $recipients = $res['phoneno'];
  $forcednd ="1";

  $data = array("email" => $email, "password" => $password,"message"=>$message, "sender_name"=>$sender_name,"recipients"=>$recipients,"forcednd"=>$forcednd);
  $data_string = json_encode($data);
  $ch = curl_init('https://app.multitexter.com/v2/app/sms');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
  $result = curl_exec($ch);
  $res_array = json_decode($result);
}
}
function studhemail($sem,$secc,$dea,$con,$mail,$hostna,$usern,$passd){
$date1 = new DateTime($dea);
       $now = new DateTime();
       $diff = $date1->diff($now);
        
        $stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='hod'");
          $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
          foreach($results as $res)
        {
        $stn=ucwords($res['title'].". ".$res['firstname']);
        try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host =$hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($usern, 'Course Registration Notification');
        $mail->addAddress($res['uniemail']);
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Notification';
        $message = "Dear: ".$stn.",\n";
        $message.= "Course registration for ".$sem."  ".$secc." Session has started. Deadline is ".$dea."\n";
        $message.= "after which late fees apply to the student \n";
        $message.= "<b>KINDLY MAKE SURE ALL REGISTRATION ARE APPROVED WITHIN THIS PERIOD</b>\n";
        $message.="Thank You";
        $mail->MsgHTML($message);
            $mail->send();
            $mail->SmtpClose();
            $mail->ClearAllRecipients();
            } catch (Exception $e) {
            //echo $e;
  }
 }
}
/*Student Level Advicer for Coure*/

function studlsms($sem,$secc,$dea,$con){
  $email = "okpehjohcybertech@gmail.com";
  $password = "Test123";
  $date1 = new DateTime($dea);
  $now = new DateTime();
  $diff = $date1->diff($now);
  /*Retrieving The Staff Record Start Here*/
  $stf=mysqli_query($con,"SELECT * FROM leveladv JOIN people ON leveladv.staffid=people.staffid  WHERE people.status='active' AND leveladv.session='$secc' ");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  //$diff->days;
  $message = "Dear: ".$stn.",\n";
  $message.= "Course registration for ".$sem."  ".$secc." Session has started. Deadline is ".$dea."\n";
  $message.= "after which late fees apply";
  $sender_name = "EDOCOE";
  $recipients = $res['phoneno'];
  $forcednd ="1";

  $data = array("email" => $email, "password" => $password,"message"=>$message, "sender_name"=>$sender_name,"recipients"=>$recipients,"forcednd"=>$forcednd);
  $data_string = json_encode($data);
  $ch = curl_init('https://app.multitexter.com/v2/app/sms');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_string)));
  $result = curl_exec($ch);
  $res_array = json_decode($result);
}
}
function studlemail($sem,$secc,$dea,$con,$mail,$hostna,$usern,$passd){
$date1 = new DateTime($dea);
       $now = new DateTime();
       $diff = $date1->diff($now);
      
        $stf=mysqli_query($con,"SELECT * FROM leveladv JOIN people ON leveladv.staffid=people.staffid  WHERE people.status='active' AND leveladv.session='$secc' ");
          $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
          foreach($results as $res)
        {
        $stn=ucwords($res['title'].". ".$res['firstname']);
        try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host =$hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usern;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; 
        $mail->setFrom($usern, 'Course Registration Notification');
        $mail->addAddress($res['uniemail']);
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Notification';
        $message = "Dear: ".$stn.",\n";
        $message.= "Course registration for ".$sem."  ".$secc." Session has started. Deadline is ".$dea."\n";
        $message.= "after which late fees apply to the student \n";
        $message.= "<b>KINDLY MAKE SURE ALL REGISTRATION ARE APPROVED WITHIN THIS PERIOD</b>\n";
        $message.="Thank You";
        $mail->MsgHTML($message);
            $mail->send();
            $mail->SmtpClose();
            $mail->ClearAllRecipients();
            } catch (Exception $e) {
            //echo $e;
  }
 }
}
?>
