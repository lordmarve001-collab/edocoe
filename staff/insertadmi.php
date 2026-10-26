<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();
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
$succ='';

if (isset($_POST["submitrecord"])) {
        
        if (empty($_POST["programm"]))
         {
             $error="Password did not match";
             $showModalEr='true';
         }elseif (empty($_POST["faculty"])) {
           $error="Student Admitted Session Required";
           $showModalEr='true';
         }
            else{
                             
            $firstname=strtolower(mysqli_escape_string($con,$_REQUEST["firstname"]));
            $lastname=strtolower(mysqli_escape_string($con,$_REQUEST["lastname"]));
            
            $admy=strtolower(mysqli_escape_string($con,$_REQUEST["faculty"]));
            $amount=strtoupper(mysqli_escape_string($con,$_REQUEST["amount"]));
            $proms=strtoupper(mysqli_escape_string($con,$_REQUEST["programm"]));
            $email=(mysqli_escape_string($con,$_REQUEST["emails"]));
            $pho=strtoupper(mysqli_escape_string($con,$_REQUEST["phones"]));
            $ref=strtoupper(mysqli_escape_string($con,$_REQUEST["ref"]));
            $pop=(mysqli_escape_string($con,$_REQUEST["pope"]));
            $mode=(mysqli_escape_string($con,$_REQUEST["mode"]));
            $fnam=$firstname." ".$lastname;
            $hashedPW = hash('sha256', $pho);
            
             $feers=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM batch WHERE status='active'"));
                        $batch=$feers['batch'];

            $feer=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pname='$proms'"));
            $degree=$feer['degree'];
                 $id =mysqli_fetch_assoc(mysqli_query($con, "SELECT count(applid) as userid FROM applicant WHERE session='$admy'AND apptype='$degree'"))['userid'];
                                    $id = $id + 1;
                                    $yrk=substr($admy,0,4);
                                    $yr=substr($yrk,-2);
                                    $userID = "EDOCOHEST/".$degree."/".$yr."/".str_pad($id, 4, '0', STR_PAD_LEFT);

                                    $toda= date("Y-m-d");
           
                $sql="SELECT * FROM applicant WHERE applid='$userID'";
                $res=mysqli_query($con,$sql);
                if (mysqli_num_rows($res) > 0) {
                  $row = mysqli_fetch_assoc($res);
                  if($userID==$row['staffid']) {
                    $error="Student ID ".$userID." already exist";
                    $showModalEr='true';
                  }
                }
                else
                  {

                //inserting to the database
     $qu = mysqli_query($con, "INSERT INTO applicant (applid,phoneno,email,password,status,session,firstname,lastname,programme,apptype,applicationDate)
     VALUES ('$userID','$pho','$email','$hashedPW','submitted','$admy','$firstname','$lastname','$proms','$degree','$toda') ");
     
        $payment = mysqli_query($con, "INSERT INTO studentpayment (studentid, section,programme,purpose,amount,modes,paymentreference,paytime,status)
        VALUES ('$userID','$admy','$proms','$pop','$amount','$mode','$ref','$toda','Success') ");
        $nar="$fulname initiated admission process with Payment details: $ref and Applicant Name: $firstname  $lastname";
        $ip = $_SERVER['REMOTE_ADDR'];
$ser = PHP_OS_FAMILY === 'Windows' ? getenv('USERNAME') : getenv('USER'); 

$inst = mysqli_query($con, "INSERT INTO activity_log (activite, staffid, naration,ip,system_user,date) VALUES ('Admission Insertion', '".$_SESSION['staffid']."', '$nar','$ip','$ser', now()) ");


                    if($qu>0){
                              $succ='Successfully submited ';
                              $suc='Application Id '.$userID.' ';
                              $su='Password: '.$pho.' ';
                              $showModal='true';
                              
                            }
                            else{
                                $error="Error Occured!!";
                                $showModalEr='true';
                            }
                }
            }
         

}
  //for status Updated
    if (isset($_POST['submitst'])) {
        //$staf = $_POST['staffid'];
        $posc =mysqli_real_escape_string($con,$_POST['newprive']);

        $upd = mysqli_query($con, "UPDATE stafflogin SET status='$posc' WHERE staffid='".$_SESSION['stud']."'");
        if ($upd > 0) {
            $succ = 'Successfully Updated';
            $showModal = 'true';
            //newuserst($username,$userID,$position);
        } else {
            $err = "Error Occured While Submiting!!";
            $showModalEr = 'true';
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
        <link rel="stylesheet" href="css/animate.min.css" media="screen">
        <style type="text/css">
            .field-icon {
              float: right;
              margin-left: -35px;
              margin-top: -35px;
              position: relative;
              z-index: 2;
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

                  }elseif ($paych['position']=="subadmin") {
                        include('includes/subbar.php');
                  }else if ($paych['position']=="sys_analyst")
                  {
                    
                    include('includes/sysbar.php');

                    }elseif ($paych['position']=="admission") {
                        include('includes/admsec.php');
                  }elseif ($paych['position']=="registrar") {
                        include('includes/admoff.php');
                  }
                   else{
                  include('includes/leftbar.php');
                        }
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Add Existing Student (s) Setup</h2>
                                
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
                     if ($paych['position']=="superadmin" OR $paych['position']=="subadmin" OR $paych['position']=="sys_analyst") {
                      ?>       
                                        <section class="section">
                                           <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Firstname:
                                                        </label>
                                                       
                                                            <input type="text" maxlength="38" name="firstname" class="form-control clid"  required="required">
                                                        
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Lastname:
                                                        </label>
                                                        
                                                            <input type="text" maxlength="38" name="lastname" class="form-control clid"  required="required">
                                                        
                                                      </div>
                                                      
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Student Programme:
                                                        </label>
                                                        <select class="form-control" name="programm" id="programme">
                                                        <option selected disabled>Please select Programme</option>
                                                          <?php
                                                              
                                                            
                                                            $resz = mysqli_query($con, "SELECT * FROM program WHERE statuss='1' ORDER BY pname");
                                                            while ($row = mysqli_fetch_array($resz)) {

                                                                ?>
                                                                <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                                                            <?php

                                                            }
                                                          ?>   
                                                        </select> 
                                                      </div> 
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Purpose of Payment:
                                                        </label>
                                                        <select class="form-control" name="pope" id="programme">
                                                        <option selected disabled>Please select Purpose</option>
                                                                <option value="ND Application">ND Application</option>
                                                                <option value="CERT Application">Certificate Application</option>
                                                                <option value="JCHEW Application">JCHEW Application</option>
                                                              
                                                        </select> 
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Mode of Payment:
                                                        </label>
                                                        <select class="form-control" name="mode" id="programme">
                                                        <option selected disabled>Please select Mode</option>
                                                                <option value="Bank_Transfer">Bank Transfer</option><option value="Bank_Deposit">Bank Deposit</option>
                                                                <option value="Bank Draft">Bank Draft</option>
                                                                <option value="Other">Other Means</option>
                                                              
                                                        </select> 
                                                      </div>
                                                  </div> 
                                              </div> 
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                        <label for="default">
                                                          Amount Paid:
                                                        </label>
                                                           <input type="text" class="form-control" name="amount" required="required">                                
                                                      </div> 
                                                      <div class="form-group">
                                                        <label for="default">
                                                          Payment Referance No:
                                                        </label>
                                                           <input type="text" class="form-control" name="ref" required="required">                                
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                          SESSION ADMITTED:
                                                        </label>
                                                        
                                                            <select class="form-control clid" name="faculty">
                                                                <option selected disabled>Please select Admitted Session</option>
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
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Student Email:
                                                        </label>
                                                       
                                                            <input type="email" name="emails" class="form-control" placeholder="Enter Student Email" required>
                                                           
                                                        
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Student Phone:
                                                        </label>
                                                       
                                                            <input type="tel" name="phones" class="form-control"  placeholder="Enter Student Phone Number" maxlength="12" required>
                                                        
                                                      </div>
                                                      <div class="form-group">
                                                
                                                        <div class="col-sm-offset-1 col-sm-8">
                                                            <button type="submit" name="submitrecord" id="submit"  class="btn btn-primary form-control ">Submit Data</button>
                                                        </div>
                                                </div>
                                                  </div> 
                                              </div> 

                                            </form>
        </section>
        <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
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
            <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
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
                            <p><?php echo htmlentities($succ) ?></p>
                            <p><?php echo htmlentities($suc) ?></p>
                            <p><?php echo htmlentities($su) ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-success" type="button" data-dismiss="modal">Close</button>
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
      $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input=$($(this).attr("toggle"));
        //calling on the attribute of the password
        if (input.attr("type")=="password") {
            //Changing to Text
            input.attr("type","text");
        }else{
            input.attr("type", "password");
        }
    });

      $(".toggle-passwordc").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input=$($(this).attr("toggles"));
        //calling on the attribute of the password
        if (input.attr("type")=="password") {
            //Changing to Text
            input.attr("type","text");
        }else{
            input.attr("type", "password");
        }
    });
 </script>
    </body>
</html>
<?php
 }

?>
