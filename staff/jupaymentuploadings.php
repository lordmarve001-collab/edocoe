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
$error='';
$succ='';
if (isset($_POST['submitcd'])) 
  {
    if (empty($_POST['purpose'])) {
        $showModalr='true';
        $error='Please Select Purpose';
    }else{
      $Max=mysqli_escape_string($con,$_POST['purpose']);
      $Min=mysqli_escape_string($con,$_POST['minscore']);
    
       //Checking to avoid Duplication
        $sel=mysqli_query($con,"SELECT * FROM jupfeesrecord WHERE purpose='$Max'");
        if (mysqli_num_rows($sel) > 0)
         {
          $showModalr='true';
          $error='Datails '.$Max.' Already Exist';
         }else
         {
         
            $dats= date("Y-m-d");
            $ins=mysqli_query($con,"INSERT INTO jupfeesrecord (amount,purpose,createdate) VALUES ('$Min','$Max','$dats')");
            if ($ins) {
                $succ='Successfully Submitted';
              $showModal='true';
            }else
            {
              $showModalr='true';
              $error='Error Occured While Submitting';
            }
         }
     }
}
if (isset($_POST['updaterol'])) {
    $del=mysqli_query($con,"DELETE FROM jupfeesrecord WHERE purpose='".$_SESSION['pur']."' AND id='".$_SESSION['id']."' ");
    if ($del) {
     $succ="Record Successfully Deleted";
     $showModal='true';
    }else{
         $showModalr='true';
         $error='Error Occured While Delecting';
    }
}

/*For the Updating Query*/
if (isset($_POST['submitpr'])) {
$new=trim(mysqli_real_escape_string($con,$_POST['newmount']));
$payed=mysqli_query($con,"UPDATE jupfeesrecord SET amount='$new' WHERE purpose='".$_SESSION['pur']."' AND id='".$_SESSION['userid']."' ");
if ($payed) {
     $succ="Record Successfully Updated";
     $showModal='true';
}else{
     $showModalr='true';
     $error='Error Occured While Updating';
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
                                    <h3 class="title">JUPEB PAYMENT UPLOADING </h3>
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
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                      <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">
                                                           Purpose:
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <select name="purpose" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Payment</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM jupfeespope  ORDER BY feename");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["feename"]; ?>"><?php echo  $row["feename"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select>
                                                        </div>
                                                      </div>
                                                      
                                                  </div> 
                                              </div> 
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">
                                                           Amount:
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <input type="number" name="minscore" class="form-control clid" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required="required">
                                                        </div>
                                                      </div>
                                                     
                                                    
                                                  </div> 
                                              </div> 
                                               <div class="form-group">
                                                        <div class="col-sm-offset-2 ">
                                                            <button type="submit" name="submitcd" class="btn btn-primary">Submit Data</button>
                                                        </div>
                                                    </div>
                                            </form>
                                        </section>
                                        
                      <div>
                          <table id="example2" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Purpose</th>
                                  <th>Amount</th>
                                  <th>Action</th>                                 
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT *  FROM jupfeesrecord ORDER BY purpose ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['purpose']) ?></td>
                                <td>&#8358; <?php echo htmlentities(number_format($result['amount'],2));?></td>
                                
                                <td>
                                    <button class="btn btn-primary userinfo" id="<?php echo $result['id']?>" lv="<?php echo $result['amount']?>" pur="<?php echo $result['purpose']?>"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger userinfof" id="<?php echo $result['id']?>"pur="<?php echo $result['purpose']?>"> <i class="fa fa-trash"></i> </button>   
                                </td>
                                
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
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

 </div>
        <!-- /.main-wrapper -->
       <div class="modal" id="viewModals">
            <div class="modal-dialog">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Update Payment Data
                    <button type="button" class="close" data-dismiss="modal" style="float:right;">&times;</button></h4>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_detail">

                  </div>

      <!-- Modal footer -->
              

            </div>
          </div>
        </div>
        <div class="modal" id="viewModal">
            <div class="modal-dialog">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Delete Payment Data
                    <button type="button" class="close" data-dismiss="modal" style="float:right;">&times;</button></h4>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_details">

                  </div>

      <!-- Modal footer -->
              

            </div>
          </div>
        </div>
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
                            <p>Please Select <?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModalr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
    <script type="text/javascript">
            $(document).ready(function(){

            // $('.userinfo').click(function(){
            $('body').on('click','.userinfo',function(){
            var userid = $(this).attr('id');
            var lvd = $(this).attr('lv');
            var pur = $(this).attr('pur');
            $.ajax({
                    url: 'payedito',
                    type: 'POST',
                    data: {userid: userid,lvd:lvd,pur:pur},
                    success: function(response)
                    { 
                        $('#teacher_detail').html(response);
                        $('#viewModals').modal('show');
                    }
                });
            });
       });

            $(document).ready(function(){

            // $('.userinfo').click(function(){
            $('body').on('click','.userinfof',function(){
            var pur = $(this).attr('pur');
            var id = $(this).attr('id');
            
            // AJAX request
            $.ajax({
                    url: 'deleedits',
                    type: 'POST',
                    data: {pur:pur,id:id},
                    success: function(response)
                    { 
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
?>
