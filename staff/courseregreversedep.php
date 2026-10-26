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
    $cmap=mysqli_escape_string($con,$_POST['ccode']);
    $leve=mysqli_escape_string($con,$_POST['level']);
    $ext = date('Y-m-d', strtotime('+3 days'));
    //$upd=mysqli_query($con,"UPDATE allocatecourse SET resultsatus='',subdate=NOW() WHERE semester='$sems' AND acadsession='$ses' AND coursecode='$cmap'");

    $up=mysqli_query($con,"UPDATE studentregsem SET status='Disapproved',extention='$ext' WHERE semester='$sems' AND session='$ses' AND level='$leve' AND studentid='$cmap' ");
    if ($up) {
        $showModal = "true";
        //lectmail($_SESSION['staffid'],$deplt,$sems,$ses,$cmap);
    }
    else
    {
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

                  if ($paych['position']=="hod")
                  {
                    
                    ?>
                    <?php include('includes/hodbar.php');

                    }else if ($paych['position']=="lecturer") {
                        
                    
                    include('includes/lectbar.php');
                     }elseif ($paych['position']=="dean") {
                       include 'includes/deanbar.php'; 
                    }
                    else{
                    ?>
                    <?php
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h3 class="title">Department Course Registration Reversal</h3>
                                
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
                                                <h3 style="color: red;">
                                            <?php 
                                                /*$date1 = new DateTime($ded);
                                                $now = new DateTime();
                                                $diff = $date1->diff($now);*/ 
                                                //echo $diff->days.' Days Remaining'; 
                                            //Remember to implement Algorithm to check time
                                            ?>
    
                                                </h3>
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
                                                            <option selected disabled>Please select Semester</option>
                                                            <?php
                                                              $res = mysqli_query($con, "SELECT * FROM semester ORDER BY semest");
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
                                                            <option selected disabled>Please select Session</option>
                                                            <?php
                                                            /*$res = mysqli_query($con, "SELECT * FROM resultsession WHERE description='exam processing'");
                                                        while ($row = mysqli_fetch_array($res)) {*/

                                                            ?>
                                                            <option value="<?php echo  htmlentities($ses) ?>"><?php echo  htmlentities($ses) ?></option>
                                                        <?php

                                                        // }
                                                        ?>
                                                        
                                                        </select>
                                                       </div>

                                                    </div><div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Programme:</label>
                                                         <div class="col-sm-10">
                                                         <select name="program" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Programme</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM programs  WHERE fid='$faclt' ORDER BY pname");
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
                                                        $stds=mysqli_escape_string($con,$_POST['studentid']);
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
                                            $sql = "SELECT DISTINCT studentregsem.session,studentregsem.semester,studentregsem.level,studentregsem.studentid,student.firstname,student.lastname  FROM studentregsem JOIN student ON studentregsem.studentid=student.studentid WHERE studentregsem.semester='$sems' AND studentregsem.session='$secc' AND studentregsem.level='$leve' AND studentregsem.studentid='$stds' AND studentregsem.programme='$prd'";
                                            $query = mysqli_query($con,$sql);
                                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                            $cnt=1;
                                            // if($query->rowCount() > 0)
                                            // {
                                            foreach($results as $result)
                                            {   
                                            ?>
                                                <tbody>
                                            <tr class="table-row" id="table-row-<?php echo  htmlentities($result["studentid"]); ?>">
                                             <td class="td"><?php echo htmlentities($cnt);?></td>
                                                <td class="td"><?php echo htmlentities($result['studentid']) ?></td>
                                                <td class="td" style="text-transform:capitalize;"><?php echo htmlentities($result['firstname'].' '.$result['lastname']);?></td>
                                                <td><?php echo  htmlentities($result["session"]); ?></td>
                                                <td><?php echo  htmlentities($result["semester"]); ?></td>
                                                <td><?php echo  htmlentities($result["level"]); ?></td>
                                                <td>
                                                    <button class="btn btn-primary" type="button"  data-toggle="modal" data-target="#myModal<?php echo $result['studentid']?>" data-target="#Modal<?php echo htmlentities($result['level'])?>" ><i class="fa fa-undo"> Reverse Approval</i></button>
                                                </td>
                                                
                                            </tr>
                                            <?php 
                                                include 'cowar.php';
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
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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
function lectmail($staffid,$dep,$sem,$ses,$cma)
{
    hodmail($dep,$sem,$ses,$cma);
    $connection=TRUE;
    include('../include/dbconfig.php');
    
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cma'"));
    $ct=$pr['title'];

    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$staffid'"));
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

    $mail->setFrom('my@uat.edu.ng', 'Result Submitted');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Department Course Result Submit Notification';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear '.$tit.' '.$firt.' '.$last.' </strong></p>';
    $message .= '<p><strong>Please be Notified that You Have Submitted <br> <b style="color:blue;">Coursecode: '.$cma.' <br>Title: '.$ct.'</b><br> Results For <br> Semester: '.$sem.' <br>Session: '.$ses.' <br/ for your Hod review and Approval on the University Portal.</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}
function hodmail($deps,$sems,$sess,$cmas)
{
    
    require_once "phpmailer/class.phpmailer.php";
    $connection=TRUE;
    include('../include/dbconfig.php');
    
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cmas'"));
    $ct=$pr['title'];
    $st=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffprive WHERE department='$deps' AND position='hod'"));
    $sid=$st['staffid'];
    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$sid'"));
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

    $mail->setFrom('my@uat.edu.ng', 'Result Submitted');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Department Course Result Submit Notification';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong>HOD</strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear '.$tit.' '.$firt.' '.$last.' </strong></p>';
    $message .= '<p><strong>Please be Notified that <br><b style="color:blue;">Coursecode: '.$cmas.' <br> Title: '.$ct.'</b> Results For <br>Semester: '.$sems.'<br> Session '.$sess.'  <br/>Have been submitted for your review and Approval on the University Portal.</strong></p>';
    $message.='<p><strong>Kindly Log in at <a href="portal.uat.edu.ng">Portal</a> to Complete the Process.</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}
?>
