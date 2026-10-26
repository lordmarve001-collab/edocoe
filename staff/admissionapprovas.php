<?php
session_start();
// ob_start();
 set_time_limit(500);
ini_set('max_execution_time', '500');
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();
if(!isset($_SESSION['staffid']))
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
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];//Here is insert the accademic session detail into the database
$admy=$ses;
$succ='';
$error='';
if (isset($_POST['reset'])) {
          $updat=mysqli_query($con,"UPDATE applicant SET uploadst='Reset' WHERE applid='".$_SESSION['appli']."'");
if ($updat) {
              $succ='Applicants Document have been reset';

}};
if (isset($_POST['approve'])) {
       $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$_SESSION['appli']."'"));
       $prgs=$prd['programme'];
       $chdup=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studlogin WHERE admid='".$_SESSION['appli']."' "));
       if ($chdup=='') {
           
       
      $update=mysqli_query($con,"UPDATE applicant SET uploadst='Approved' WHERE applid='".$_SESSION['appli']."'");
      if ($update) {
        $tud=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['appli']."' "));
        $first=trim($tud['firstname']);
        $last=trim($tud['lastname']);
        
        $prd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$_SESSION['appli']."'"));
        $proc=$prd['programme'];
        $pkl=$prd['phone'];
        $pml=$prd['email'];
        $dep=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pname='$proc'"));
                $fact=$dep['fid'];
                $dact=$dep['did'];
        
        $id = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(staffid) as userid FROM studlogin WHERE programme='$proc' AND acadsession='$admy'"))['userid'];
        $id = $id + 1;
        $yrk=substr($admy,0,4);
        $yr=substr($yrk,-2);
        $hashedPW = hash('sha256', $pkl);
        $tday=date('Y-m-d');
        //$userIDs = $fact."/".$dact."/".$yr."/".str_pad($id, 4, '0', STR_PAD_LEFT);
        //$upd=mysqli_query($con,"UPDATE personaldata SET studentid='$userIDs' WHERE applid='".$_SESSION['appli']."' ");
        $qu=mysqli_query($con,"INSERT INTO studlogin(status,admid,firstname,lastname,password,username,position,programme,acadsession,phone,email,dateinsert) VALUES ('active','".$_SESSION['appli']."','$first','$last','$hashedPW','$userIDs','student','$proc','$admy','$pkl','$pml','$tday')");

          $succ='Email And Sms is Successfully Sent To the Applicants';
          $showModalS='true';
          appmail($_SESSION['appli'],$orgname,$schadress,$logo,$con,$mail,$hostna,$usern,$passd);
          sendsms($_SESSION['appli'],$con,$orgname,$schadress);
      }else{
        $error='An Error Occured While Confirming Data Please Try Again!!!';
        $showModalEr='true';
      }
      }else{
        $error='Student With Admission ID '.$_SESSION['appli'].' Data Already Confirmed kindly Confirm Next Candidate';
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
        <link rel="stylesheet" href="css/animate.min.css" media="screen" >
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

                  if ($paych['position']=="registrar")
                  {
                     include('includes/admoff.php');

                  }elseif ($paych['position']=="admission") {
                        include('includes/admsec.php');
                  }elseif ($paych['position']=="superadmin") {
                        include('includes/supbar.php');
                  }elseif ($paych['position']=="subadministrator") {
                        include('includes/ictbar.php');
                  }elseif ($paych['position']=="subadmin") {
                        include('includes/subbar.php');
                  }elseif ($paych['position']=="admin_officer") {
                        include('includes/admof.php');
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
                                    <h4 class="title">Admission Document Confirmation For <?php echo $admy ?> Setup</h4>
                                
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

                  if ($paych['position']=="registrar" OR $paych['position']=="subadmin" OR $paych['position']=="subadministrator"  OR $paych['position']=="admin_officer" OR $paych['position']=="superadmin")
                  {
                     ?>   
                                             <section>
                
                <table id="example2" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Admission ID</th>
                        <th>Full Name</th>
                        <th>State of Origin</th>
                        <th>Programme</th>
                        <th>Session</th>
                        <th>Status</th>
                        <th>Action</th>
                       
                    </tr>
                </thead>
                <tbody>
                   <?php
                              $sql = "SELECT * FROM applicant JOIN personaldata ON personaldata.applid=applicant.applid WHERE (applicant.uploadst='Pending') AND applicant.session='$admy' AND (applicant.apptype='ND' OR applicant.apptype='CERT' OR applicant.apptype='JCHEW') ORDER BY applicant.applid";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                           
                            foreach($results as $result)
                            {  
                                    stuname($result['applid'],$con);
                             ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['applid']) ?></td>
                                <td><?php echo htmlentities($_POST["harrd"]);?></td>
                                <td><?php echo htmlentities($_POST["maks"]);?></td>
                                <td><?php echo htmlentities($_POST['prog']);?></td>
                                <td><?php echo htmlentities($result['session']);?></td>
                                <td><?php echo htmlentities($_POST['stat']);?></td>
                                <td> <button class="btn btn-primary userinfo" id="<?php echo $result['applid']?>"><i class="fa fa-eye"></i> </button> </td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                   
                                                    </table>
                                                </section>
                <div class="modal" id="viewModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">APPLICANT DATA</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        
                        <div class="modal-body" id="teacher_details">

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            
                     <th>
                         <form method="POST">
                        <button class="btn btn-danger" type="submit" name="reset">Reset Detail <i class="fa fa-check"></i></button>
                        
                    </form><br>
                    <form method="POST">
                        <button class="btn btn-primary" align="left" type="submit" name="approve">Confirm Detail <i class="fa fa-check"></i></button>
                        
                    </form>
                        </th>
                         </tr>
                         </table>         
                        </div>
                    </div>
                </div>
            </div>
                <?php
                    }else{
                  ?>
                  <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
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
        <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
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
                            <p><?php echo htmlentities($succ) ?></p>
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
                            <p><?php echo htmlentities($error) ?></p>
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
   
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
        ?>
        <script type="text/javascript">
             $(document).ready(function(){
 $('#check').click(function(){
    if($(this).is(":checked"))
        $('.checkitem').prop('checked',true);
    else
    $('.checkitem').prop('checked',false);
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
<script type="text/javascript">
    $(document).ready(function(){
    $('.sendButton').attr('disabled',true);

        $('#mess').keyup(function(){
        if($(this).val().length !=0){
        $('.sendButton').attr('disabled', false);
        }
        else
        {
        $('.sendButton').attr('disabled', true);        
        }
    });      
      
});
</script>
<script type="text/javascript">
    $(document).ready(function() {

                    
                     $('body').on('click','.userinfo',function() {
                        var userid = $(this).attr('id');
                        
                        $.ajax({
                            url: 'jambdatad',
                            type: 'POST',
                            data: {
                                userid: userid
                            },
                            success: function(response) {
                                $('#teacher_details').html(response);
                                // Display Modal
                                $('#viewModal').modal('show');
                            }
                        });
                    });
                });
</script>
    </body>
</html>
<?php } 
function stuname($maxt,$con)
{
      $sql =mysqli_query($con,"SELECT *  FROM  personaldata WHERE applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
         $_POST["maks"]="No State";
         $_POST['prog']="No Programme";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $maks=$data['state'];
        $_POST['prog']=$data['programme'];
        $name =$sur." ".$first." ".$las;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
        $dds=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$maxt'"));
    $_POST['stat']=$dds['uploadst'];
}

function appmail($stud,$orgname,$schadress,$logo,$con,$mail,$host,$pass,$user)
{     
    $url='https://portal.edocohest.edu.ng/clearance/';
        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$stud'"));
        $programm=$dat["programme"];
        $phone=$dat["phone"];
        $mal=$dat["email"];
        $nmk=$dat["surname"]." ".$dat["firstname"]." ".$dat["lastname"];
    //$mail = new PHPMailer;
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
        $mail->setFrom($user, 'Clearance Document Confirmation');
        /*$con,$refe,$ema,$orgname,$schadress,$logo,$ids,$ap,$nam,$pg*/
        $mail->addAddress($mal);
        $mail->isHTML(true);
        $mail->Subject = 'Clearance Document Confirmation';
        $message ='<html> 
        <head> 
        <title> Edo State College of Health Science Technology</title> 
        <style>
            .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color: #555;
        }
    
        .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
        }
    
        .invoice-box table td {
        padding: 5px;
        vertical-align: top;
        }
    
        .invoice-box table tr td:nth-child(2) {
        text-align: right;
        }
    
        .invoice-box table tr.top table td {
        padding-bottom: 20px;
        }
    
        .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 15px;
        color: #333;
        }
        
        .invoice-box table tr.information table td {
        padding-bottom: 40px;
        }
    
        .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        }
    
        .invoice-box table tr.details td {
        padding-bottom: 20px;
        }
    
        .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
        }
    
        .invoice-box table tr.item.last td {
        border-bottom: none;
        }
    
        .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
        }
    
        @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        }
        .rtl {
        direction: rtl;
        font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        }
    
         .rtl table {
        text-align: right;
        }
    
        .rtl table tr td:nth-child(2) {
        text-align: left;
        }

        </style>
        </head> 
        <body>';
    
        $message .='<div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="'.$logo.'" style="width:150px; height: 150px; max-width:150px;">

        </div>';
        $message .='<div> Congratulation Dear: '.$nmk.' with Admission ID '.$stud.' The clearance documents you provided has been verified  <br> kindly login to clearance portal '.$url.' to make payment for Tuition Fee.
        Your Matriculation No:'.$stu.' 
Thank you<br> Thank you</div>';
        $message .='<div class="info">
             Once Again Congratulation. 
        </div>';
        $message .='<div class="divFooter" style=text-align:center>Copyright © '.$orgname.','.$schadress.';
                     </div>';
        $mail->MsgHTML($message);
         
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
       } catch (Exception $e) {
           
       }
}
function sendsms($app,$con,$org,$sch){
    $sc=$org.' , '.$sch;
    $url='https://portal.edocohest.edu.ng/clearance/';
$query=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$app'");
$results = mysqli_fetch_all($query,MYSQLI_ASSOC);
foreach($results as $result)
{ 
   $_POST["fname"]=$result["firstname"];
   $_POST["sname"]=$result["surname"];
   $pro=$result["programme"];
   $phone=$result["phone"];
   $stu=$result["studentid"];
   $nmk=$_POST["sname"]." ".$_POST["fname"];
$email = "imarve.wiz@gmail.com"; 
$password = "Passw20ord"; 
$message= "Dear: ".$nmk."\n";
$message.= "The clearance documents you provided has been verified\n";
//$message .= "Matric No: ".$stu." \n";
$message.= $url." to pay for tuition fee. \n"; 
$sender_name = "EDOCOHEST";
$recipients=$result["phone"];
$forcednd = "1"; 
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
?>