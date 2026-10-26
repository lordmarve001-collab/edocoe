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

            
      $update=mysqli_query($con,"UPDATE applicant SET status='Approved',applid='".$_SESSION['appli']."' WHERE applid='".$_SESSION['appli']."' AND acadsession='$ses'");
      if ($update) {
        $tud=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['appli']."' AND acadsession='$ses'"));
        $first=trim($tud['firstname']);
        $last=trim($tud['lastname']);
        $malk=mysqli_fetch_assoc(mysqli_query($con,"SELECT count(*) as unemail  FROM applicant WHERE firstname='$first' AND lastname='$last' "));
        
        $malb=$malk['unemail'];
        if (!$malb) {
            $username=strtolower(mysqli_escape_string($con,$first."".$malb.".".$last));
            $mk="";
            $uemail=strtolower(mysqli_escape_string($con,$username."".$mk));
            
        }else{
            $username=strtolower(mysqli_escape_string($con,$first.".".$last));
            $mk="";
            $uemail=strtolower(mysqli_escape_string($con,$username."".$mk));
            
        }
    
        $qu=mysqli_query($con,"UPDATE studdata SET email='$uemail' WHERE studentid='".$_SESSION['appli']."'");

          $succ='Email And Sms is Successfully Send To Applicants';
          $showModalS='true';
          
      }else{
        $error='An Error Occured While Confirming Data Please Try Again!!!';
        $showModalEr='true';
      }
    }
    if (isset($_POST["disapproved"])) {
        $mess=$_POST['messag'];
        $upda=mysqli_query($con,"UPDATE applicant SET status= 'rejected' WHERE applid='".$_SESSION['appli']."'");
        if ($upda) {
            
            $succ='Candidate has been notify of your decision via Email And Sms';
            $showModalS='true';
            }else{
                $error='An Error Occured While Rejecting Data Please Try Again!!!';
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
                         include('includes/abbar.php');
                    }elseif($paycr['role']=='accountant'){
                        include('includes/juaccbar.php');
                    }elseif($paycr['role']=='officer') {
                        include('includes/abbar.php');
                    }
                    ?>
                        <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title">ABA APPLICATION DATA FOR <?php echo $ses?> </h3>
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                        <?php 
                        $payck=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM abacordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));

                         if ($payck['role']=="cordinator"  OR $payck['role']=='officer') {

                        $dats= date("Y-m-d");
                        if ($dats>$dead) { ?>
                            <div class="container-fluid">                           
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                               <h5>
                                                    <ul>
                                                    <li style="color: blue;">Session: <strong style="color:red;"><?php echo htmlentities($ses) ?></strong> 
                                                    </li>
                                                    </ul>
                                                </h5>
                                                <p style="color:red">PDE Application Has Ended</p>
                           <section class="section">
                           <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                       <th>#</th>
                                        <th>Applicant id</th>
                                        <th>Applicant Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Acadsession</th>
                                        <th>Action</th> 
                                       
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            $sql = "SELECT * FROM applicant WHERE acadsession='$ses' AND status='Pending' ";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   
                                stuname($con,$result['applid']);
                                
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td class="td"><?php echo htmlentities($result['applid']) ?></td>
                                <td class="td"><?php echo htmlentities($_POST['harrd']) ?></td>
                                <td><a href="tel:<?php echo $result['phoneno']?>"><?php echo htmlentities($result['phoneno']);?></a></td>
                                <td><a href="mailto:<?php echo $result['email']?>"><?php echo htmlentities($result['email']);?></a></td>
                                <td><?php echo htmlentities($result['acadsession']);?></td>
                                <td>
                                  <button class="btn btn-primary userinfof" id="<?php echo $result['applid'] ?>">  <i class="fa fa-eye"></i></button>  
                                </td>
                            </tr>
                            <?php 
                            $cnt=$cnt+1;
                            } ?> 
                                </tbody>
                            </table>
                    </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        else{
                        ?>

    <div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                    <div>
                        <h5 style="font-size:17px;color:red;">
                            <ul>
                                
                                <?php
                                    $dats= date("Y-m-d");
                                    $date1 = new DateTime($dead);
                                    $now = new DateTime();
                                    $diff = $date1->diff($now);
                                ?>
                                <li>Days Remain For Application to End <?php echo htmlentities($diff->days) ?> Day </li>
                            </ul>
                        </h5>
                        </div> 
                    <div class="panel-body">                                                
                       
                        <section>
                           <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Applicant id</th>
                                        <th>Applicant Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Acadsession</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            
                            $sql = "SELECT * FROM applicant WHERE session='$ses' AND (status='Pending' OR status='rejected') AND apptype='ABA' ";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   
                                stuname($con,$result['applid']);
                                $_SESSION['applid']=$result['applid'];
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td class="td"><?php echo htmlentities($result['applid']) ?></td>
                                <td class="td"><?php echo htmlentities($_POST['harrd']) ?></td>
                                <td><a href="tel:<?php echo $result['phoneno']?>"><?php echo htmlentities($result['phoneno']);?></a></td>
                                <td><a href="mailto:<?php echo $result['email']?>"><?php echo htmlentities($result['email']);?></a></td>
                                <td><?php echo htmlentities($result['session']);?></td>
                                <td><?php echo htmlentities($result['status']);?></td>
                                <td>
                                  <button class="btn btn-primary userinfof" id="<?php echo $result['applid'] ?>">  <i class="fa fa-eye"></i></button>  
                                </td>
                            </tr>
                            <?php 
                            $cnt=$cnt+1;
                            } ?> 
                            </tbody>
                            </table>
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
                     }else{
                    ?><div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
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
            <div class="modal fade" id="checkModalsuc" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
         if(!empty($showModalsuc)) 
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
            url: 'jupdata',
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
<?php } 

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
 
?>
