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
if (isset($_POST['complete'])) {
    
    $upd=mysqli_query($con,"UPDATE result SET status='Approved' WHERE status='Y' AND semester='".$_SESSION['ssid']."' AND acadsession='".$_SESSION['sess']."' AND programme='".$_SESSION['prog']."'");

    $up=mysqli_query($con,"UPDATE resuappre SET status='Pending' WHERE semester='".$_SESSION['ssid']."' AND acadsession='".$_SESSION['sess']."' AND programme='".$_SESSION['prog']."' ");
    if ($upd) {
        $showModal = "true";
        hodmail($con,$_SESSION['staffid'],$deplt,$_SESSION['ssid'],$_SESSION['sess'],$_SESSION['prog']);
        staffsmc($con,$_SESSION['staffid'],$deplt,$_SESSION['ssid'],$_SESSION['sess'],$_SESSION['prog']);
        staffsmd($con,$_SESSION['staffid'],$deplt,$_SESSION['ssid'],$_SESSION['sess'],$_SESSION['prog']);
    }
    else
    {
        $showModalEr='true';
    }
}

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='exam processing'"));
$semst=$dat['semester'];
$sest=$dat['session'];
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
                  <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                    if ($paych['position']=="dean") {
                        
                    
                    include('includes/deanbar.php');
                     }
                     
                    else{
                   
                        include('includes/leftbar.php');
                        }
                    ?>
                    <!-- /.left-sidebar -->
                    
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Senate Summary Result(s) Approved  Setup</h4>
                                
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

                  if ($paych['position']=="dean") {

                     ?>
                      <table id="example" class="table table-striped table-bordered " cellspacing="0">
                            <thead>
                                
                                <tr>
                                    <th>#</th>
                                    <th>Programme</th>
                                    <th>Session</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th>Result View</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                            <?php
                            $res = mysqli_query($con, "SELECT * FROM programs WHERE fid='$faclt' ");
                            while ($row = mysqli_fetch_array($res)) {

                            $sql ="SELECT * FROM resuappre WHERE  resuappre.status='Approved' AND resuappre.acadsession='$sest' AND resuappre.semester='$semst' AND resuappre.programme='".$row['pname']."' ";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            { 

                           ?>
                           <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result['programme']) ?></td>
                            <td><?php echo htmlentities($result['acadsession']);?></td>
                            <td><?php echo htmlentities($result['semester']);?></td>
                            <td><?php echo htmlentities($result['status']);?></td>
                            <td>
                            <button type="button" class="userinfo btn btn-primary" 
                                data-program="<?php echo $result['programme'];?>" 
                                data-semester="<?php echo $result['semester'];?>" 
                                data-session="<?php echo $result['acadsession'];?>"
                                href="#detailsmodal" data-target="#detailsModal"><i class="fa fa-eye"></i></span>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="userinfof btn btn-danger" 
                                data-program="<?php echo $result['programme'];?>" 
                                data-semester="<?php echo $result['semester'];?>" 
                                data-session="<?php echo $result['acadsession'];?>"
                                href="#detailsmodal" data-target="#detailsModal">Reverse Approval</span>
                            </button> 
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
                    else{ ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                   
                    <?php    
                        }
                    ?> 
                    <div class="modal" id="viewModal">
                                      <!-- <div class="modal-dialog">-->
                                        <div class="modal-content"> 

                                          <!-- Modal Header -->
                                          <div class="modal-header">
                                            <h5 class="modal-title">RESULT INFORMATION</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          </div>

                                          <!-- Modal body -->
                                          <div class="modal-body" id="teacher_details">

                                          </div>
                                        <!-- </div>-->
                                      </div> 
                    </div>                             
                <!-- This Section is to Display the Faculty Results Base on the session And Semester -->
                
                <div id="myModal" class="modal fade">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content">
                        <div class="modal-header flex-column">
                                              
                            <h4 class="modal-title w-100">Are you sure?</h4>    
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body" id="teacher_detail">
                            
                        </div>
                     
                    </div>
                </div>
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
                            <p>Senate Result Approval is Successfully Reverse <i class="fa fa-check"></i></p>
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
                            <p><?php echo htmlentities($error) ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Message -->
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>There is No Result Base on the Criteria<br>
                                I'ts Seems Their Result Is Not Ready.
                            </p>
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
    if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
            });
        </script>';
    } 
       ?>
       <script>
$(document).ready(function(){
    $('body').on('click','.userinfo',function() {
var ssid = $(this).attr('data-semester');
var sess = $(this).attr('data-session');
var prog = $(this).attr('data-program');

// AJAX request
$.ajax({
url: 'senateview',
type: 'POST',
data: {ssid:ssid,sess:sess,prog:prog},
success: function(response){ 
$('#teacher_details').html(response);

      // Display Modal
      $('#viewModal').modal('show');
}
});
});
}); 

$(document).ready(function(){
    $('body').on('click','.userinfof',function() {
var ssid = $(this).attr('data-semester');
var sess = $(this).attr('data-session');
var prog = $(this).attr('data-program');

// AJAX request
$.ajax({
url: 'senalerts',
type: 'POST',
data: {ssid:ssid,sess:sess,prog:prog},
success: function(response){ 
$('#teacher_detail').html(response);

      // Display Modal
      $('#myModal').modal('show');
}
});
});
}); 
</script>
    </body>
</html>
<?php } 
function hodmail($con,$staf,$dep,$sem,$ses,$cma)
{   
    person($con,$staf,$sem,$ses,$cma);
  
    require_once "phpmailer/class.phpmailer.php";
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$cma'"));
    $dpa=$pr['did'];
    $st=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffprive JOIN people ON staffprive.staffid=people.staffid WHERE staffprive.department='$dpa' AND staffprive.position='hod' AND people.status='active' "));
    if ($st!='') {
    $sid=$st['staffid'];
    $to=$st['uniemail'];
    $firt=$st['firstname'];
    $last=$st['lastname'];
    $tit=$st['title'];
    }
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'my@uat.edu.ng';                 // SMTP username
    $mail->Password = '12345@uat';                           // SMTP password

    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; 

    $mail->setFrom('my@uat.edu.ng', 'Senate Result Request DissApproval');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Departmental Senate Result Reversed';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear '.$tit.'. '.$firt.' '.$last.' </strong></p>';
    $message .= '<p><strong>Please be notify that, your Approved Senate Result for <br> Programme: '.$cma.' <br>Semester: '.$sem.'<br> Academic Session: '.$ses.'  <br/>has been reversed by your Dean.</strong></p>';
    $message.='<p><strong>Thank you.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}
/*Staff Sms And EMail On Course Allocation*/
function staffsmc($con,$staf,$dep,$sem,$ses,$cma){
  $email = "okpehjoh@gmail.com";
  $password = "Testing123";
 
  /*Retrieving The Staff Record Start Here*/
  
 $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$cma'"));
    $dpa=$pr['did'];
  $stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='hod' AND staffprive.department='$dpa' ");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  //$diff->days;
  $message = "Dear: ".$stn.",\n";
  $message.= "Please be notify that, your Approved Senate Result for ".$cma." ".$sem." ".$ses."\n";
  $message.= "has been reversed by your Dean\n";
  $message.= "Thank you\n";
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

/*Staff Sms And EMail On Course Allocation*/
function staffsmd($con,$staff,$dep,$sem,$ses,$cma){
  $email = "okpehjoh@gmail.com";
  $password = "Testing123";
 
  /*Retrieving The Staff Record Start Here*/
  

  $stf=mysqli_query($con,"SELECT * FROM people WHERE status='active' AND staffid='$staff'");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  //$diff->days;
  $message = "Dear: ".$stn.",\n";
  $message.= "Please be notify that, you have reversed Approved Senate Result  for ".$cma." ".$sem." ".$ses."\n";
  $message.= "Thank You\n";
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

function person($con,$sta,$semf,$sesf,$cmaf)
{
  
    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$sta'"));
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

    $mail->setFrom('my@uat.edu.ng', 'Senate Result Reversal');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Departmental Senate Result Reversal';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear '.$tit.'. '.$firt.' '.$last.' </strong></p>';
    $message .= '<p><strong>Please be notified that you have reversed <br> Programme: '.$cmaf.' <br>Results <br>Semester: '.$semf.'<br> Academic Session: '.$sesf.'.</strong></p>';
    $message.='<p><strong>Thank you.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}
?>
