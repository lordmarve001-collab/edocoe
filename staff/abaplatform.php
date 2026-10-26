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

$error='';
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT description,session,deadline FROM resultsession WHERE description='Current Session'"));
$desc=$dat['description'];
$ses=$dat['session'];
$dead=$dat['deadline'];
if (isset($_POST["submitrecord"])) {
/*
firstname
lastname
email
phoneno
*/
    $firstname=strtolower(mysqli_escape_string($con,$_REQUEST["firstname"]));
    $lastname=strtolower(mysqli_escape_string($con,$_REQUEST["lastname"]));
    $admy=strtolower(mysqli_escape_string($con,$_REQUEST["email"]));
    $phon=strtoupper(mysqli_escape_string($con,$_REQUEST["phoneno"]));
    $hashedPW = hash('sha256', $phon);
    $id = mysqli_fetch_assoc(mysqli_query($con, "SELECT count(applid) as userid FROM applicant WHERE session='$ses' AND programme='ABA'"))['userid'];
    $id = $id + 1;
    $date=date('Y');
    $yr=substr($date,-2);
    $today = date("Y-m-d");
    $userID = "EDOCOE/ABA/".$yr."/".str_pad($id, 4, '0', STR_PAD_LEFT);
    $register = mysqli_query($con, "INSERT INTO applicant (applid,phoneno,email,password,status,admissionstatus,session,firstname,lastname,programme,applicationDate) VALUES ('$userID','$phone','$email','$hashedPW','Approved','complete','$ses','$firstname','$lastname','$desc','$today') ");
    $payf=mysqli_fetch_assoc(mysqli_query($con, "SELECT amount FROM feesrecord WHERE purpose='$desc' "));
    $amount=$payf['amount'];
    $payment = mysqli_query($con, "INSERT INTO jupebpayment(studentid,section,purpose,amount,modes,paymentreference,paytime,status,staffname) VALUES ('$userID','$ses','$desc','$amount','staff','$ref','$today','Success','$fullname') ");
    if($payment) {
    studentemail($con,$ref,$phone,$orgname,$schadress,$logo);
    studensms($phone,$userID);
    } 
    else 
    {
    mysqli_rollback($con);
    mysqli_close($con);
    echo "Could not Process Your admission, Kindly Contact Web Master. Do not repeat this Application ";
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
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM abacordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));

                  if ($paych=="")
                  {
                    
                    include('includes/leftbar.php');
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h3 class="title">ABA APPLICATION DATA FOR <?php echo $ses?> </h3>
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
                    $paycr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM abacordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));
                    if ($paycr['role']=='cordinator') {
                        $qts=mysqli_query($con,"SELECT * FROM jupsig WHERE staffid='".$_SESSION['staffid']."'");
                        $row = mysqli_num_rows($qts);
                        
                        if ($row == 0) 
                        {
                            header("Location:abacordsign");
                        }
                         include('includes/abbar.php');
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
                                    <h3 class="title">ABA PLATFORM </h3>
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                        <?php 
                         $payck=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM abacordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));

                         if ($payck['role']=="cordinator"  OR $payck['role']=='officer' OR $payck['role']=='accountant') {

                        ?>

    <div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">                                                
                       <div class="col-sm-3">
                                                 
                                                    <img id="Image1" src="<?php echo $passport;?>" alt="Photo" style="height:152px;width:152px;">
                                                    <p class="text-center">      
                                                    <strong><span id="Label1"></span></strong>&nbsp;<strong><span id="Label16"></span></strong></p>
                                                
                                            </div>
                                            <div class="col-md-5">
                                               <table class="nav-justified">
                                                     
                                                        <tbody><tr>
                                                          <td>Staffid ID<strong>:
                                                               <span><?php echo htmlentities($_SESSION['staffid']); ?></span>
                                                              </strong></td>
                                                      </tr>
                                                      <tr>
                                                          <td>Name <strong>:
                                                              <span><?php echo htmlentities($fullnames); ?></span>
                                                              </strong></td>
                                                      </tr>
                                                      <tr>
                                                          <td>Sex<strong>:
                                                              <span><?php echo htmlentities($sex); ?></span>
                                                              </strong></td>
                                                      </tr>
                                        
                                                  </tbody>
                                              </table>
                                            </div>
                                            <div id="divb" class="col-md-4">

                                              <table class="nav-justified">
                                                  
                                                  <tbody>
                                                     
                                                      <tr>
                                                          <td>Email<strong>:
                                                              <span><?php echo htmlentities($unmail); ?></span></strong></td>
                                                      </tr>
                                                      <tr>
                                                        <td>Role: <strong style=text-transform:capitalize;><?php echo htmlentities($payck['role']) ?></strong></td>
                                                         </tr>
                                                        
                                                        
                                                      
                                               </tbody>
                                          </table>   
               
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
       
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
        ?>
      
    </body>
</html>
<?php 
} 

?>
