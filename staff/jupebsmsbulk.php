<?php
session_start();
set_time_limit(22500);
ini_set('max_execution_time', '22500');
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

$error='';
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();

if (isset($_POST['approves'])) {
    if (empty($_POST['checks'])) {
           $error='No Student Where checked';
            $showModale='true'; 
    }else{
        $studentid=$_POST['checks'];
		$pass='Edo23coe';
        foreach ($studentid as $check) {
            sendsms($check,$con,$orgname,$schadress,$pass);
            appmail($check,$orgname,$schadress,$logo,$con,$mail,$hostna,$passd,$usern,$pass);
            
           $showModalS='true';
        
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
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }elseif($paych['position']=='subadmin'){
                        include('includes/subbar.php');
                    }elseif($paych['position']=='sys_analyst'){
                        include('includes/sysbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    
                    
                        <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title">BULK SMS TO SEND</h3>
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
                     if ($paych['position']=="superadmin" OR $paych['position']=='subadmin' OR $paych['position']=='sys_analyst') {
                       
                    
                     ?>
                    <div class="panel-body"> 
                    <section>
                        <form action="" method="post">
                           <table id="example2" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Matric No</th>
                                        <th>Student Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Acadsession</th>
                                        <th> <input type="checkbox" name="check" id="check" title="Please click on the check box"> CheckAll</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            
                            $sql = "SELECT * FROM studlogin WHERE  status='active' ";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   
                                stuname($con,$result['staffid']);
                                $_SESSION['applid']=$result['staffid'];
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td class="td"><?php echo htmlentities($result['staffid']) ?></td>
                                <td class="td"><?php echo htmlentities($_POST['harrd']) ?></td>
                                <td><a href="tel:<?php echo $result['phone']?>"><?php echo htmlentities($result['phone']);?></a></td>
                                <td><a href="mailto:<?php echo $result['email']?>"><?php echo htmlentities($result['email']);?></a></td>
                                <td><?php echo htmlentities($result['acadsession']);?></td>
                                <td><input type="checkbox"   value="<?php echo $result["staffid"]; ?>" name="checks[]" class="checkitem" id="checkitem"></td>
                            </tr>
                            <?php 
                            $cnt=$cnt+1;
                            } ?> 
                            </tbody>
                             <tfoot>
                                <tr>
                                    <th colspan="7"><button class="btn btn-success sendButton" type="submit" name="approves" style="float: right;"><i class="fa fa-upload">Click to Send</i></button> 
                                    </th>
                                   </form>
                                </tr>
                            </tfoot>
                            </table>
                    </section>
               
                    <?php
                  
                    }else{
                    ?><div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        //}
                      }
                    ?>  
                    <!-- /.left-sidebar -->

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
        <div class="modal" id="viewModals">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="float:left;">Jupeb Student Data <i class="fa fa-users"></i> </h4>
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
                       
                        <button name="disapproved" class="btn btn-danger sendButton" style="float: left;margin-top:15px;" >Reject Detail <i class="fa fa-close"></i> </button>
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
    </body>
</html>
<?php 
} 
function stuname($con,$maxt)
{
 
      $sql =mysqli_query($con,"SELECT *  FROM  studlogin WHERE staffid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $las=$data['lastname'];
        //$maks=$data['studentid'];
        $maks=$data['programme'];
        $name =strtoupper($las)." ".$first;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
}


//sendsms($con,$check);https://portal.edocoe.edu.ng/
function appmail($stud,$orgname,$schadress,$logo,$con,$mail,$hostna,$passd,$usern,$pas)
{     
    $url='https://portal.edocoe.edu.ng/login';
        
        
    //$mail = new PHPMailer;
       try {
        
        $mail->isSMTP();
       
        $mail->Host = $hostna;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
       $mail->Username =$usern;                 // SMTP username
        $mail->Password = $passd;                           // SMTP password
        $mail->SMTPKeepAlive = true;
        $mail->SetLanguage("en");
        $mail->SMTPAutoTLS = true;
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        //$mail->Port = 587; 
        $mail->Port = 465; 
        $mail->setFrom($usern, 'EDOCOE LOGIN DETAIL');
        //$mail->addAddress($mal);
        $sts=mysqli_query($con,"SELECT * FROM studlogin WHERE staffid='$stud'");
          while ($result=mysqli_fetch_array($sts)){
        $mail->addAddress($result['email']);
        $_POST["fname"]=$result["firstname"];
        $_POST["sname"]=$result["lastname"];
        $programm=$result["programme"];
        $phone=$result["phone"];
        $mal=$result["email"];
        $nmk=$_POST["fname"]." ".$_POST["sname"];     
        $mail->isHTML(true);
        $mail->Subject = 'EDOCOE LOGIN DETAIL';
        $message ='<html> 
        <head> 
        <title> University Of Africa</title> 
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

        </div>
        <div style="width: 300px;margin: auto;">
            <h5><span style="font-size: 18px;">'.$orgname.'</span> , <span style="font-size: 18px;"></span><br> <span style="margin-left: 25px;font-size: 15.5px;font-weight: bold;">Login Information.</span></h5>
        </div>';
        $message .=' <table cellpadding="0" cellspacing="0">';
        $message .='<tr class="item">
                <td>
                    Full Name: <strong style="text-transform: capitalize;">'.$nmk.'</strong>
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                    Phone: '.$phone.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='<tr class="item">
                <td>
                    Email: '.$mal.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
            $message.='<tr class="heading">
                <td>
                    Login Information
                </td>
                
                <td>
                    
                </td>
            </tr>';
            $message.='<tr class="item">
                <td>
                    Username: '.$stud.'
                </td>
                
                <td>
                    
                </td>
            </tr>    ';
            $message.='<tr class="item">
                <td>
                 Login Password: '.$pas.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
			$message.='<tr class="item">
                <td>
                 Login Link: '.$url.'
                </td>
                
                <td>
                    
                </td>
            </tr>';
        $message .='</table>';
        $message .='<div class="info">
             Please Kindly Login to Complete Your Application. 
        </div>';
        $message .='<div class="divFooter" style=text-align:center>Copyright © Edocoe;
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
function sendsms($app,$con,$org,$sch,$pass){
    $sc=$org.' , '.$sch;
$url="https://portal.edocoe.edu.ng/login";
$result=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studlogin WHERE staffid='$app'"));
 
   $_POST["fname"]=$result["firstname"];
   $_POST["sname"]=$result["lastname"];
   $phone=$result["phone"];
   $nmk=$_POST["sname"]." ".$_POST["fname"];

  $email = "okpehjohcybertech@gmail.com";
  $password = "Test123";
  $message = "EDOCOE LOGIN DETAILS \n";
  $message.= "Dear ".$nmk." \n";
  $message.= "Username: ".$app."\n";
  $message.= "Password: ".$pass."\n";
  $message.= "Click Here To Login: \n".$url;
  $sender_name = "EDOCOE";
  $recipients = $phone;
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

?>
