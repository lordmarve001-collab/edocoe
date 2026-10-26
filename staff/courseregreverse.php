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
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='student course registration'"));
$sems=$dat['semester'];
$ses=$dat['session'];
$ded=$dat['deadline'];

//die($ses);


if (isset($_POST['complete'])) { 
    
   
    $cmap=mysqli_escape_string($con,$_SESSION['student']);
    $leve=mysqli_escape_string($con, $_SESSION['level']);
    $sems=mysqli_escape_string($con, $_SESSION['semester']);
    $ses=mysqli_escape_string($con, $_SESSION['session']);
    //$ext = date('Y-m-d', strtotime('+3 days'));
    $upd=mysqli_query($con,"DELETE FROM result  WHERE semester='$sems' AND acadsession='$ses' AND level='$leve' AND studentid='$cmap' ");
    //$lms=mysqli_query($con,"DELETE FROM lms  WHERE semester='$sems' AND acadsession='$ses' AND idnumber='$cmap' ");
           
    $up=mysqli_query($con,"UPDATE studentregsem SET status='Disapproved' WHERE studentid='$cmap' AND session='$ses' AND  semester='$sems' AND level='$leve' ");
    if ($up) {
        $showModal = "true";
        		    				studentemail($std,$matno,$ref,$orgname,$cpy,$leveld,$schadress,$logo,$con,$mail,$hostna,$usern,$passd);

        studmail($cmap,$leve,$ses,$sems,$con,$hostna,$usern,$passd);
        studsms($cmap,$sems,$ses,$leve,$con);
    }
    else
    {
        $showModalEr='true';
    }
}


//die($alrt2);
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
        <link rel="stylesheet" href="css/print.css" media="print" >
      <style type="text/css">
          #blah{
    width:170.256px;
    height:159px;
    border:2px solid black;
        }
        .td{
             cursor: not-allowed;
        }
        @media print{
            .dataTables_filter{
                display: none;
            }
            .dataTables_info{
                display: none;
            }
            .tab{
                border: 1px solid black;
                margin-top: 20px;
                width:700px;
            }
            .th{border: 1px solid black;}
            .td{
                border: 1px solid black;
                padding: 5px;
                margin: 5px;
                text-align: justify;
            }
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
                   <?php //include('includes/hodbar.php');?>  
                    <!-- /.left-sidebar -->
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="dean") {
                       include 'includes/deanbar.php'; 
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Course Registration Reversal Setup</h2>
                                
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
                                                $paysc=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                    
                                            if ($paysc['position']=='dean')
                                            { 
                                                $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='student course registration'"));
                                                    $semt=$dat['semester'];
                                                    $set=$dat['session'];
                                                    $dead=$dat['deadline'];
                                                    $dats= date("Y-m-d");
                                                    if ($dats>$dead) { ?>
                                                        <b style=color:red;>Course Registration Has Ended For <b style="color:blue;"><?php echo htmlentities($semt." And ".$set) ?> Academic Session</b>, No Room For Course Registration Reversar</b>
                                                <?php  }else{ 
                                                ?>
                                                <section class="section not">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                      <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" name="studentid" required="required" placeholder="Enter Student Id Or Matric No">
                                                       </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Semester:</label>
                                                         <div class="col-sm-10">
                                                         <select name="semester" class="form-control clid"required="required">
                                                            
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
                                                        <label for="default" class="col-sm-2 control-label">Session:</label>
                                                         <div class="col-sm-10">
                                                         <select name="sessionx" class="form-control clid"required="required">
                                                            
<?php
                                $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                              <?php

                                }
                              ?>                                                        </select>
                                                       </div>

                                                    </div><div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Programme:</label>
                                                         <div class="col-sm-10">
                                                         <select name="program" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Programme</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM program  WHERE fid='$faclt' ORDER BY pname");
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
                                                        <label for="default" class="col-sm-2 control-label">Level:</label>
                                                         <div class="col-sm-10">
                                                         <select name="level" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Level</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM level  ORDER BY levels");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select>
                                                       </div>

                                                    </div>
                                                                                                                 
                                                     
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="query" class="btn btn-primary">View Record</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                            <?php
                                        }
                                }
                       else{
                       ?>
                          <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Are Not A Level Advicer Thank You.</p>
                       <?php
                       }
                                                
                                                $error='';
                                                    if (isset($_POST['query'])) {
                                                        if (empty($_POST['semester'])) {
                                                        $showModal = "true";
                                                        $error='Semester';
                                                    }elseif (empty($_POST['sessionx'])) {
                                                        $showModal = "true";
                                                        $error='Session';
                                                    }
                                                    elseif (empty($_POST['program'])) {
                                                        $showModal = "true";
                                                        $error='Programme';
                                                    }elseif (empty($_POST['level'])) {
                                                        $showModal = "true";
                                                        $error='Level';
                                                    }else{
                                                        $stds=trim(mysqli_escape_string($con,strtoupper($_POST['studentid'])));
                                                        $prd=mysqli_escape_string($con,$_POST['program']);
                                                        $secc=mysqli_escape_string($con,$_POST['sessionx']);
                                                        $sems=mysqli_escape_string($con,$_POST['semester']);
                                                        $leve=mysqli_escape_string($con,$_POST['level']);

                                                        $sel=mysqli_query($con, "SELECT * FROM studentregsem WHERE semester='$sems' AND session='$secc' AND level='$leve' AND studentid='$stds' AND programme='$prd' ");
                                                        if (mysqli_num_rows($sel)>0) {
                                                            // code...
                                                        //}
                                                        ?>
                                                        <table  id="example2" class="table table-striped table-bordered " cellspacing="0">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>StudentID</th>
                                                            <th>Name</th>
                                                            <th>Session</th>
                                                            <th>Semester</th>
                                                            <th>Level</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <?php
                                            $sql = "SELECT DISTINCT studentregsem.session,studentregsem.semester,studentregsem.level,studentregsem.studentid,student.firstname,student.lastname,student.othername  FROM studentregsem JOIN student ON studentregsem.studentid=student.studentid WHERE studentregsem.semester='$sems' AND studentregsem.session='$secc' AND studentregsem.level='$leve' AND studentregsem.studentid='$stds' AND studentregsem.programme='$prd'";
                                            $query = mysqli_query($con,$sql);
                                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                            $cnt=1;
                                            
                                            foreach($results as $result)
                                            {   
                                            ?>
                                                <tbody>
                                            <tr class="table-row" id="table-row-<?php echo  htmlentities($result["studentid"]); ?>">
                                             <td class="td"><?php echo htmlentities($cnt);?></td>
                                                <td class="td"><?php echo htmlentities($result['studentid']) ?></td>
                                                <td class="td" style="text-transform:capitalize;"><?php echo htmlentities($result['firstname'].' '.$result['lastname'].' '.$result['othername']);?></td>
                                                <td><?php echo  htmlentities($result["session"]); ?></td>
                                                <td><?php echo  htmlentities($result["semester"]); ?></td>
                                                <td><?php echo  htmlentities($result["level"]); ?></td>
                                                <div id="myModal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header flex-column">
                                  
                <h4 class="modal-title w-100">Are you sure?</h4>    
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p>Do you really want to Reverse This Course Registration Approval? This process cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">NO</button>
                <form method="POST">
                    <?php $_SESSION['student']=$result['studentid']; ?>
                    <?php $_SESSION['level']=$result['level']; ?>
                    <?php $_SESSION['semester']=$result['semester']; ?>
                    <?php $_SESSION['session']=$result['session']; ?>
                    <button name="complete" class="btn btn-success">YES</button>
                </form>
            </div>
         
        </div>
    </div>
</div>     
                                                <td>
                                                    <button class="btn btn-primary" type="button"  data-toggle="modal" data-target="#myModal" ><i class="fa fa-undo"> Reverse Approval</i></button>
                                                </td>
                                                
                                            </tr>
                                            <?php 
                                                //include 'cowar.php';
                                            $cnt=$cnt+1;
                                             
                                            }//} ?>
                                                       
                                                    
                                             </tbody>
                                                        </table>
                                                <?php
                                                    }else{
                                                        $showModall = "true";
                                                        $error='There is No Result Base On the Criteria';
                                                    }
                                                }
                                            }
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
                            <p>Course Registration Approval is Successfully Reverse</p>
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
                            <p>An Error Occured While Submitting</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
			<!-- Error Modal Box -->
            <div class="modal fade" id="checkModall" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
    </body>
</html>
<?php } 
/*Student Sms And EMail On Course Registration*/
function studsms($stud,$sems,$ses,$lev,$con){
  $email = "okpehjoh@gmail.com";
  $password = "Testing123";
  
  /*Retrieving The Staff Record Start Here*/
  $stf=mysqli_query($con,"SELECT * FROM student WHERE status='active' AND studentid='$stud' ");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  //$diff->days;
  $message = "Dear: ".$stn.",\n";
  $message.= "Your Course registration for ".$sems."  ".$ses."  Session ".$lev." Level has been Reversed ";
  $message.= "Kindly Login to Your Portal to Complete Your Registration.\n";
  $message.= "Thank You.";
  $sender_name = "EDOCOHEST";
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

function studmail($cmap,$leve,$ses,$sems,$con,$hostna,$usern,$passd)
{
    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT email,firstname,lastname FROM student WHERE studentid='$cmap'"));
    $to=$stm['email'];
    $firt=$stm['lastname'];
    $last=$stm['firstname'];
    require_once "phpmailer/class.phpmailer.php";
    
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
       try {
        
       $mail->isSMTP();
        
        $mail->Host = $hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
       $mail->Username =$usern;                 // SMTP username
        $mail->Password =$passd;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->SetLanguage("en");
        $mail->SMTPAutoTLS = true;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        $mail->setFrom($usern, 'Course Registration Reversar');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Reversar Notification';
    
    
    
    
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear '.$firt.' '.$last.' </strong></p>';
    $message .= '<p><strong>Please be Notified that Your Approved Course Registration for <br> <b style="color:blue;">'.$sems.' '.$ses.'</b> '.$leve.' Level <br/> has been Reversed kindly Login to Complete Your Registration Thank You.</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
       }catch (Exception $e) {
            
        }
}

?>