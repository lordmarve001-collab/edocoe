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
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();
        $error='';
        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT description,session,deadline FROM resultsession WHERE description='admission processing'"));
        $desc=$dat['description'];
        $ses=$dat['session'];
        $dead=$dat['deadline'];
        if (isset($_POST['approve'])) {

            
      $update=mysqli_query($con,"UPDATE studdata SET graduate='1', graduate_date='2025-01-03' WHERE admno='".$_SESSION['appli']."'");
      if ($update) {
        
        

          $succ='Email And Sms is Successfully Send To Applicants';
          $showModalS='true';
          appmail($_SESSION['appli'],$orgname,$schadress,$logo,$con,$mail,$hostna,$passd,$usern);
          

          sendsms($_SESSION['appli'],$con,$orgname,$schadress);
      }else{
        $error='An Error Occured While Confirming Data Please Try Again!!!';
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
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jupcordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));

                  if ($paych=="")
                  {
                    
                    include('includes/leftbar.php');
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h3 class="title">PDE DATA PRINT </h3>
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
                                            <p style="color:red;">Sorry You Don't Have Accessed To This Page Click <a href="staffportal" style="color:blue" >Here</a> To Go Back to Home Page.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }else {
                    $paycr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jupcordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));
                    if ($paycr['role']=='cordinator') {
                         include('includes/jupbar.php');
                    }elseif($paycr['role']=='accountant'){
                        include('includes/juaccbar.php');
                    }elseif($paycr['role']=='officer') {
                        include('includes/jupbar.php');
                    }
                    ?>
                        <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title">PDE STUDENT GRADUATION PAGE</h3>
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
     <?php 
        $payck=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jupcordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));

        if ($payck['role']=="cordinator"  OR $payck['role']=='officer') {
     ?>                   
    <div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">                                                
                           
                    <?php 
                        }else{
                    ?><div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                       // }
                    }
                    ?> 
                       
                <section>
                                            <section class="section not">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                       <div class="form-group">
                            <label for="default" class="col-sm-2">ACADEMIC SESSION:</label>
                             <div class="col-sm-10">
                             <select name="section" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Academic Session</option>
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
                                                                                                                 
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="query" class="btn btn-primary">View Student</button>
                        </div>
                    </div>                    
                    </form>
                </section>

                <?php 
                //if (isset($_POST['query'])) {
                        //if (empty($_POST['section'])) {
                         //$showModal = "true";
                         //$error="Please Select Session";
                        //}
                        
                        //else{
                        //$secc=mysqli_escape_string($con,$_POST['section']);
                        $secc='2023/2024';
                        $sql=mysqli_query($con,"SELECT * FROM studdata WHERE acadsession='$secc' AND apptype='PDE' ");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                            
                           ?>
                                                    <table id="example" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>PDE ID</th>
                                                            <th>Fullname</th>
                                                            <th>Session</th>
                                                            <th>Email</th>
                                                            <th>Phone</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                             <?php
                              $sql = "SELECT * FROM studdata  WHERE studdata.status='active' AND studdata.studentid !=''
                              AND studdata.acadsession='$secc' AND studdata.apptype='PDE' AND studdata.graduate='0' ORDER BY admno";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   
                                stuname($con,$result['admno']);
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['admno']) ?></td>
                                <td style="text-transform: capitalize;"><?php echo htmlentities($_POST['harrd'])?></td>
                                
                                <td><?php echo htmlentities($result['acadsession']);?></td>
                                <td><?php echo htmlentities($result['email']);?></td>
                                <td><?php echo htmlentities($result['phone']);?></td>
                                <td>Under-Graduate</td>
                                <td><button class="btn btn-primary userinfof" id="<?php echo $result['admno'] ?>"><i class="fa fa-eye"></i></button></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }//} ?> 
                            </tbody>
                            </table>
                            <?php 

                            }else{
                           $showModale='true';

                                  }
                             }
                        //}  
                            ?>
                        </section>
                        
                    </div>
            </div>
        </div>
        <!-- /.col-md-12 -->
    </div>
    </div>
</div>

                    <?php
                     }
                    ?>  
                    <!-- /.left-sidebar -->

 
<!-- /.content-container -->
</div>

<div class="modal" id="viewModals">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="float:left;">Appicant Data <i class="fa fa-users"></i> </h4>
                <button style="float:right;" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="teacher_detailss">

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                            <table>
                                <tr>
                                    <th>
                       
                      <form method="POST">  <button name="disapproved" type="submit" class="btn btn-danger sendButton" style="float: left;margin-top:15px;" >Reject Detail <i class="fa fa-close"></i> </button>
                        <textarea id="mess" placeholder="Specify Reason for Rejecting Please" name="messag" style="resize: none;scroll-behavior: none;width: 450px;float: right;"></textarea>
                        
                    </form>
                     </th>
                     <th>
                    <form method="POST">
                        <button class="btn btn-primary" type="submit" name="approve">Confirm Detail <i class="fa fa-check"></i></button>
                        
                    </form>
                        </th>
                         </tr>
                         </table>         
                        </div>
        </div>
    </div>
</div>
            <!-- /.content-wrapper -->
 </div>
        <!-- /.main-wrapper -->
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
                            <p>Please Select Programme</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="modal fade" id="checkModalh" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p style='color:red;'>
                                <?php 
                                    echo htmlentities($error);
                                ?>
                            </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Success Message -->
            <div class="modal fade" id="checkModalsucc" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                            Details Is Successfully Submitted
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

        if(!empty($showModalEr)) 
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
        } 
         
        if(!empty($showModals)) 
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalh").modal("show");
            });
        </script>';
        }
         if(!empty($showModalS)) 
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalsuc").modal("show");
            });
        </script>';
        } 
        ?>
      
    </body>

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
$(document).ready(function() {

    //$('.userinfof').click(function() {
  $('body').on('click','.userinfof',function() {
        var userid = $(this).attr('id');
        
        $.ajax({
            url: 'graduatedata',
            type: 'POST',
            data: {
                userid: userid
            },
            success: function(response) {
                $('#teacher_detailss').html(response);
                // Display Modal
                $('#viewModals').modal('show');
            }
        });
    });
});

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
</html>
<?php //} 

function stuname($con,$maxt)
 {
    
    $sql =mysqli_query($con,"SELECT *  FROM  studdata WHERE admno='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur=$data['surname'];
        $las=$data['lastname'];
        $name =$sur." ".$first." ".$las;
        $_POST["harrd"]= $name;
        }

 }
 function appmail($stud,$orgname,$schadress,$logo,$con,$mail,$host,$pass,$user)

{     
    $url='https://portal.edocoe.edu.ng/pde/applogin';
        
        
    //$mail = new PHPMailer;
       try {
        
          $mail->isSMTP();
        
        $mail->Host = $host;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
       $mail->Username =$user;                 // SMTP username
        $mail->Password =$pass;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->SetLanguage("en");
        $mail->SMTPAutoTLS = true;
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        //$mail->Port = 587; 
        $mail->Port = 465;  
        $mail->setFrom('edocoe.edu.ng', 'EdoCoe PDE Graduation Confirmation');
        //$mail->addAddress($mal);
        $sts=mysqli_query($con,"SELECT * FROM studdata WHERE studentid='$stud'");
          while ($result=mysqli_fetch_array($sts)){
        $mail->addAddress($result['email']);
        $_POST["fname"]=$result["firstname"];
        $_POST["sname"]=$result["lastname"];
        $programm=$result["programme"];
        $phone=$result["phone"];
        $nmk=$_POST["fname"]." ".$_POST["sname"];     
        $mail->isHTML(true);
        $mail->Subject = 'PDE Graduation Confirmation';
        $message ='<html> 
        <head> 
        <title> Edo State College of Edocation, Edo State</title> 
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
        $message .='<div> Congratulation Dear: '.$nmk.' with PDE ID '.$stud.' Your Graduation Status has been Comfirm as Graduation  <br> kindly login to PDE portal '.$url.'.
<br> To pay for Final Clearance Fees</div>';
        $message .='<div class="info">
             Once Again Congratulation. 
        </div>';
        $message .='<div class="divFooter" style=text-align:center>Copyright © '.$orgname.','.$schadress.';
                     </div>';
        $mail->MsgHTML($message);
         }
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
       } catch (Exception $e) {
           
       }
}
function sendsms($app,$con,$org,$sch){
    $sc=$org.' , '.$sch;
$url="https://portal.edocoe.edu.ng/aba/applogin";
$query=mysqli_query($con,"SELECT * FROM studdata WHERE admno='$app'");
$results = mysqli_fetch_all($query,MYSQLI_ASSOC);
foreach($results as $result)
{ 
   $_POST["fname"]=$result["firstname"];
   $_POST["sname"]=$result["lastname"];
   $pro=$result["programme"];
   $phone=$result["phone"];
   $nmk=$_POST["sname"]." ".$_POST["fname"];
$email = "okpehjoh@gmail.com"; 
$password = "Testing123"; 
$message= "Dear: ".$nmk." with\n";
$message .= "ABA ID: ".$app." \n";
$message.= "The data you provide has been confirmed kindly Login to\n";
$message.= $url." to Login your jupeb platform. \n"; 
$sender_name = "ABAEDOCOE";
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

//studsms($_SESSION['appli'],$ses,$mess,$con);
function studsms($stud,$ses,$mess,$con,$orgname){
  $email = "okpehjoh@gmail.com";
  $password = "Testing123";
  
  /*Retrieving The Staff Record Start Here*/
  $stf=mysqli_query($con,"SELECT * FROM studdata WHERE admno='$stud' ");
  $results = mysqli_fetch_all($stf,MYSQLI_ASSOC);
  foreach($results as $res)
{
  $stn=ucfirst($res['firstname']);
  $message = "Dear: ".$stn.",\n";
  $message.= "Your detail provided to ".$orgname." for ABA admission consideration in ".$ses." was rejected\n ";
  $message.= "Reason: ".$mess." \n";
  $message.= "Thank You.";
  $sender_name = "ABAEDOCOE";
  $recipients = $res['phone'];
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
//disapprovedmail($con,$_SESSION['appli'],$ses,$mess,$mail);
function disapprovedmail($con,$stude,$sez,$mes,$mail,$orgname)
{
   $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studdata WHERE admno='$stude'"));
    //die($maix);
        $to = $rows['email'];
        $fnat=strtoupper($rows['surname']).' '.$rows['firstname'].' '.$rows['lastname'];
        try{
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('edocoe.edu.ng', 'ABA Admission Rejection Notification');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'ABA Admission Rejection Notification';
        $message='<p><strong>Dear: '.$fnat.' </strong></p>';
        $message.='<p><strong>Your detail provided to '.$orgname.' for ABA admission consideration for '.$sez.' Academic Session was rejected for the following reason. <br/> <b style="color:red;">'.$mes.'</b></strong></p>';
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAllRecipients();
        } catch (Exception $e) {
            
        }
    }
?>
