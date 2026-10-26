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
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='exam processing'"));
$sems=$dat['semester'];
$ses=$dat['session'];
$ded=$dat['deadline'];




if (isset($_POST['complete'])) {
    $cmap=mysqli_escape_string($con,$_POST['ccode']);
    $rst=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM result WHERE status='Y' AND coursecode='$cmap' AND acadsession='$ses' AND semester='$sems' "));
    if ($rst=='') {
    $upd=mysqli_query($con,"UPDATE allocatecourse SET resultsatus='',subdate=NOW() WHERE semester='$sems' AND acadsession='$ses' AND coursecode='$cmap'");

    $up=mysqli_query($con,"UPDATE result SET status='' WHERE semester='$sems' AND acadsession='$ses' AND coursecode='$cmap' ");
    if ($up) {
        $showModal = "true";
        lectmail($_SESSION['staffid'],$deplt,$sems,$ses,$cmap,$con);
        staffsms($deplt,$sems,$ses,$cmap,$con);
    }
    else
    {
        $showModalEr='true';
    }
    }else{
        $error='This '.$cmap.' Result is already approved by the faculty for '.$sems.' '.$ses.' and it can not be reverse kindly ask the course domecile HOD to use current result edit request to effect the correction Thank You';
        $showModall='true';
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
        <link rel="stylesheet" href="css/print.css" media="print" >
        <link rel="stylesheet" href="css/animate.min.css" media="screen" >
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
                                    <h2 class="title">Result Dissapproved Setup</h2>
                                
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
                     if ($paych['position']=="dean") {
                       
                    
                     ?>                     
                                           <div>

                        <h5 style="font-size:17px;color:red;">
                            <ol>
                                
                                <li style="color:blue;">Current Session: <?php echo htmlentities($ses); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Semester: <?php echo htmlentities($sems);?></li>
                            </ol>
                        </h5>
                        </div>
                                            <div class="panel-body">
                                           
    
                                                </h3>
                                                <section class="section not">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label"> Course Code:</label>
                                                         <div class="col-sm-10">
                                                         <select name="coursecode" class="form-control clid" id="faculty" required="required">
                                                        <option selected disabled>Please select Course</option>
                                        
                                                        <?php
                                                        $res = mysqli_query($con, "SELECT * FROM course WHERE faculty='$faclt' ORDER BY coursecode ");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["coursecode"]; ?>"><?php echo  $row["coursecode"]; ?>  <?php echo  $row["title"]; ?></option>
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
                                                
                                                    if (isset($_POST['query'])) {
                                                        if (empty($_POST['coursecode'])) {
                                                            $showModall = "true";
                                                            $error='Please Select Course';
                                                            //exit();
                                                        }else{
                                                        $ccode=mysqli_escape_string($con,$_POST['coursecode']);
                                                        $sel=mysqli_query($con, "SELECT * FROM allocatecourse JOIN course ON allocatecourse.coursecode=course.coursecode WHERE allocatecourse.resultsatus='Approved' AND allocatecourse.semester='$sems' AND allocatecourse.acadsession='$ses' AND allocatecourse.coursecode='$ccode'");
                                                        if (mysqli_num_rows($sel)>0) {
                                                           
                                                        ?>
                                                        <table  id="example2" class="table table-striped table-bordered " cellspacing="0">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Course Code</th>
                                                            <th>Course Title</th>
                                                            <th>Academic Session</th>
                                                            <th>Semester</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <?php
                                            $sql = "SELECT DISTINCT allocatecourse.coursecode, course.title,allocatecourse.semester,allocatecourse.acadsession FROM allocatecourse JOIN course ON allocatecourse.coursecode=course.coursecode WHERE allocatecourse.coursecode='$ccode' AND allocatecourse.semester='$sems' AND allocatecourse.acadsession='$ses'";
                                            $query = mysqli_query($con,$sql);
                                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                            $cnt=1;
                                            // if($query->rowCount() > 0)
                                            // {
                                            foreach($results as $result)
                                            {   
                                            ?>
                                                <tbody>
                                            <tr class="table-row" id="table-row-<?php echo $result["coursecode"]; ?>">
                                             <td class="td"><?php echo htmlentities($cnt);?></td>
                                                <td class="td"><?php echo htmlentities($result['coursecode']) ?></td>
                                                <td class="td" style="text-transform: capitalize;"><?php echo htmlentities($result['title']);?></td>
                                                <td class="td"><?php echo htmlentities($result['acadsession']);?></td>
                                                <td ><?php echo $result["semester"]; ?></td>
                                                <td>
                                                    <button class="btn btn-primary" type="button"  data-toggle="modal" data-target="#myModal<?php echo $result['coursecode']?>"><i class="fa fa-undo"> Reverse Approval</i></button>
                                                </td>
                                                
                                            </tr>
                                            <?php 
                                                include 'rewar.php';
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
                     <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
                    ?>
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
        <!-- Error Modal Box -->
            <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Result Approval is Successfully Reverse</p>
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
function staffsms($deplt,$sems,$ses,$cmap,$con){

  $email = "okpehjoh@gmail.com";
  $password = "Testing123";
  $st=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM allocatecourse WHERE coursecode='$cmap' AND semester='$sems' AND acadsession='$ses' AND coursecord='Yes'"));
    $sid=$st['staffid'];
    $stf=mysqli_query($con,"SELECT * FROM people WHERE staffid='$sid'");
  /*Retrieving The Staff Record Start Here*/
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  //$diff->days;
  $message = "Dear: ".$stn.",\n";
  $message.= "Please be Notified that ".$cmap." Result For ".$sems. ",".$ses." Session Has been Reverse for reuploading Kindly Log in to reupload.\n";
  $message.= "Thank You";
  $sender_name = "UAT";
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

function lectmail($staffid,$dep,$sem,$ses,$cma,$con)
{
    hodmail($dep,$sem,$ses,$cma,$con);
    
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cma'"));
    $ct=$pr['title'];

    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$staffid' AND status='active' "));
    $to=$stm['uniemail'];
    $firt=$stm['firstname'];
    $last=$stm['lastname'];
    $tit=$stm['title'];
    require_once "phpmailer/class.phpmailer.php";
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'my@uat.edu.ng';                 // SMTP username
    $mail->Password = '12345@uat';                           // SMTP password

    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; 

    $mail->setFrom('my@uat.edu.ng', 'Result Reversal');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Department Course Resulted Reversal Notification';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear '.$tit.'. '.$last.' '.$firt.' </strong></p>';
    $message .= '<p><strong>Please be Notified that You Have Reversal Submitted For <br> <b style="color:blue;">Coursecode: '.$cma.' <br>Title: '.$ct.'</b><br> Semester: '.$sem.' <br>Session: '.$ses.' <br/> for reuploading by course lecturer.</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}

function hodmail($deps,$sems,$sess,$cmas,$con)
{
    
    require_once "phpmailer/class.phpmailer.php";    
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cmas'"));
    $ct=$pr['title'];
    $st=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM allocatecourse WHERE coursecode='$cmas' AND semester='$sems' AND acadsession='$sess' AND coursecord='Yes'"));
    $sid=$st['staffid'];
    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$sid' AND status='active' "));
    $to=$stm['uniemail'];
    $firt=$stm['firstname'];
    $last=$stm['lastname'];
    $tit=$stm['title'];
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'my@uat.edu.ng';                 // SMTP username
    $mail->Password = '12345@uat';                           // SMTP password

    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; 

    $mail->setFrom('my@uat.edu.ng', 'Submitted Result Reversal');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = 'Course Result Submitted Reversal Notification';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear '.$tit.'. '.$last.' '.$firt.' </strong></p>';
    $message .= '<p><strong>Please be Notified that <br><b style="color:blue;">Coursecode: '.$cmas.' <br> Title: '.$ct.'</b> Results For <br>Semester: '.$sems.'<br> Session '.$sess.'  <br/> Has been Reverse for your reuploading.</strong></p>';
    $message.='<p><strong>Kindly Log in at <a href="http://my.uat.edu.ng">Portal</a> to Complete the Process.</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}
?>
