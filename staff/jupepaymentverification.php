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
//for inserting Record
if (isset($_POST['submitcor'])) {
      
        $teld=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
        
        $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM jupebpayment WHERE paymentreference='$teld' "));
        if ($telcheck>0) {
                  $showModalS='true';
                  $use=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jupebpayment WHERE paymentreference='$teld' "));
                  $stuid=$use['studentid'];
                  $amt=$use['amount'];
                  $pop=$use['purpose'];
                  $ref=$use['paymentreference'];
                  $payt=$use['paytime'];
                  stunames($con,$stuid);
                  
        }else{
                 $showModalEr='true';
                   $error='Teller ID '.$teld.' Does not Exit or Check and Try Again';
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
                                    <h3 class="title">JUPEB APPLICATION DATA FOR <?php echo $ses?> </h3>
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
                                    <h3 class="title">JUPEB STUDENT PAYMENT VERIFICATION </h3>
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                        <?php 
                        $payck=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jupcordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));

                         if ($payck['role']=="accountant") {
                            ?>
        <div class="container-fluid">                           
           <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">
                        <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Teller ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" name="tellerid" autocomplete="off" required="required" placeholder="Enter Payment Teller Id Or Reference Id">
                                                       </div>

                                                    </div>
                                                   
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-success">Click to Verified <i class="fa fa-check-circle"></i> </button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                    </div>
                </div>
            </div>
        </div>
                            <?php
                            }else{
                    ?>
        <div class="container-fluid">                           
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
<!-- Success Modal Box -->
            <div class="modal fade" id="checkModalS" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Payment Confirmation</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                           <div style="float: none;width: 200px;margin: auto;"> <img src="<?php echo htmlentities($_POST['maks']); ?>" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
                            <table>
                                 <tr>
                                     <td>Full Name:</td>
                                     <td><b style="text-transform: capitalize;"><?php echo htmlentities($_POST["harrd"]); ?></b></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>Jupeb No:</td>
                                     <td><?php echo htmlentities($stuid); ?></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>Payment Purpose:</td>
                                     <td><?php echo htmlentities($pop); ?></td>
                                 </tr>
                                 
                                 <?php if ($use['section']!="") {
                                     ?>
                                     <tr>
                                     <td>Session:</td>
                                     <td><?php echo htmlentities($use['section']); ?></td>
                                 </tr>
                               <?php
                                 } 
                                 ?>
                                 
                                 <?php if ($use['paytype']!="") {
                                     ?>
                                <tr>
                                     <td>Pay Type:</td>
                                     <td><?php echo htmlentities($use['paytype']); ?></td>
                                 </tr>
                               <?php
                                 } 
                                 ?>
                                 <tr>
                                     <td>Amount Paid:</td>
                                     <td>&#8358; <?php echo htmlentities(number_format($amt,2)); ?></td>
                                 </tr><tr>
                                     <td>Payment Reference:</td>
                                     <td><?php echo htmlentities($ref); ?></td>
                                 </tr><tr>
                                     <td>Payment Time:</td>
                                     <td><?php echo htmlentities($payt); ?></td>
                                 </tr>
                                 <?php if ($use['modes']=='staff') { ?>
                                     <tr>
                                     <td>Payment Mode:</td>
                                     <td><?php echo htmlentities($use['modes']); ?></td>
                                     <tr>
                                     <td>Payment Mode:</td>
                                     <td><?php echo htmlentities($use['staffname']); ?></td>
                                 </tr>
                                 <?php }else { ?>
                                 <tr>
                                     <td>Payment Mode:</td>
                                     <td><?php echo htmlentities($use['modes']); ?></td>
                                 </tr>
                             <?php } ?>
                            </table>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.content-wrapper -->
 </div>
        <!-- /.main-wrapper -->
            <!-- Error Message -->
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
    if(!empty($showModalp)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalp").modal("show");
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
      
    </body>
    <script type="text/javascript">

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


</script>
</html>
<?php } 

 function stunames($con,$max)
 {
    
   
    $sql =mysqli_query($con,"SELECT  *  FROM  studdata WHERE  studentid='$max'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $prp=$data['programme'];
        $maks="../jupeb/".$data['passport'];
        $name =$sur." ".$las." ".$first;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["prp"]= $prp;
        
    }

 }
?>
