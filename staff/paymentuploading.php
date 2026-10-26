<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

if (!isset($_SESSION['staffid']))
    header("Location: ../logout");
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}

$error='';
$succ='';
if (isset($_POST['submitcd'])) 
  {
    if (empty($_POST['purpose'])) {
       $showModalEr='true';
      $error='Purpose';
    }elseif (empty($_POST['remark'])) {
      $showModalEr='true';
      $error='Level';
    }elseif (empty($_POST['grade'])) {
      $showModalEr='true';
      $error='Payment Type';
    }else{
      $Max=mysqli_escape_string($con,$_POST['purpose']);
     $prog=mysqli_escape_string($con,$_POST['program']);
     $Rm=mysqli_escape_string($con,$_POST['remark']);
      $Min=trim(mysqli_escape_string($con,$_POST['minscore']));
      $Gr=mysqli_escape_string($con,$_POST['grade']);
      
       //Checking to avoid Duplication
        $sel=mysqli_query($con,"SELECT * FROM feesrecord WHERE level='$Rm' AND purpose='$Max' AND paytype='$Gr'");
        
        if (mysqli_num_rows($sel) > 0)
         {
          $showModalr='true';
          $error='Datails Already Exist';
         }else
         {
           $dats= date("Y-m-d H:i:s a");
            $ins=mysqli_query($con,"INSERT INTO feesrecord (amount,level,purpose,programme,createdate,paytype) VALUES ('$Min','$Rm','$Max','$prog','$dats','$Gr')");
            if ($ins) {
                $succ="Details Successfully Submitted";
              $showModal='true';
            }else
            {
              $showModalr='true';
              $error='Error Occured While Submitting';
            }
    }
  }
}
/*For the Updating Query*/
if (isset($_POST['submitpr'])) {
    $new=trim(mysqli_real_escape_string($con,$_POST['newmount']));

$payed=mysqli_query($con,"UPDATE feesrecord SET amount='$new' WHERE level='".$_SESSION['userid']."'  AND purpose='".$_SESSION['pur']."' AND paytype='".$_SESSION['pty']."' ");
if ($payed) {
     $succ="Record Successfully Updated";
              $showModal='true';
}else{
     $showModalr='true';
     $error='Error Occured While Updating';
}
}
/*Delete Information*/
if (isset($_POST['updaterol'])) {
    $del=mysqli_query($con,"DELETE FROM feesrecord WHERE level='".$_SESSION['lvd']."' AND paytype='".$_SESSION['pty']."' ");
    if ($del) {
     $succ="Record Successfully Deleted";
              $showModal='true';
    }else{
         $showModalr='true';
         $error='Error Occured While Delecting';
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

                  if ($paych['position']=="accountant")
                  {
                    
                    include('includes/accbar.php');

                    }else if ($paych['position']=="supbursar") {
                    
                    include('includes/supbubar.php');
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
                                    <h2 class="title">School Fees Uploading</h2>
                                
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
                                        <div style="margin-bottom:15%;"> 
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                     if ($paych['position']=="accountant" OR $paych['position']=="supbursar") {
                       
                    
                     ?>       
                                       <section class="section newsect">
                                           <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Purpose:
                                                        </label>
                                                            <select name="purpose" class="form-control clid"required="required" id="department">
                                                            
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM degreetype ");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["degree_tution"]; ?>"><?php echo  $row["degree_tution"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select>
                                                      </div>
                                                      <div class="form-group">
                                                        <label for="default">
                                                          Level:
                                                        </label>
                                                        
                                                            <select class="form-control clid" name="remark">
                                                                <option selected disabled>Please select Level</option>
                                                                <?php
                                                            $ress = mysqli_query($con,"SELECT * FROM level ORDER BY levels");
                                                            while ($row=mysqli_fetch_array($ress))
                                                            {
                                                                ?>
                                                                <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                                                <?php

                                                            }
                                                             ?>
                                                            </select>
                                                        </div>
                                                      
                                                       <div class="form-group">
                                                        <label for="default">Programme:</label>
                                                        
                                                         <select name="program" class="form-control clid"required="required" id="department">
                                                            <option selected disabled>Please select Programme</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM program  ORDER BY pname");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        
                                                        </select>
                                                       
                                                    </div>
                                                  </div> 
                                              </div> 
                                              <div class="col-lg-6">
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                        <label for="default">
                                                           Amount:
                                                        </label>
                                                        
                                                            <input type="number" name="minscore" class="form-control clid" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required="required">
                                                        </div>
                                                      
                                                      <div class="form-group">
                                                        <label for="default">
                                                           Pay Type:
                                                        </label>
                                                       
                                                            <select class="form-control clid" name="grade">
                                                              <option selected="selected" disabled="disabled">Please Select Pay type</option>
                                                               <?php
                                                            $ress = mysqli_query($con,"SELECT * FROM paymenttype ORDER BY ptype");
                                                            while ($row=mysqli_fetch_array($ress))
                                                            {
                                                                ?>
                                                                <option value="<?php echo  $row["ptype"]; ?>"><?php echo  $row["ptype"]; ?></option>
                                                                <?php

                                                            }
                                                             ?>
                                                            </select>
                                                        </div>
                                                      
                                                     <div class="form-group">
                                                        <div>
                                                            <button type="submit" name="submitcd" class="btn btn-primary form-control">Submit Data</button>
                                                        </div>
                                                    </div>
                                                  </div> 
                                              </div> 
                                              
                                            </form>
                                        </section>
                                      </div>
                                      <h2></h2>  
                      <div style="margin-top: 24px;">
                          <table id="example" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Purpose</th>
                                  <th>Programme </th>
                                  <th>Amount</th>
                                  <th>Level</th>
                                  <th>Pay Type</th>
                                  <th>Action</th>
                                  
                                 
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM feesrecord WHERE (purpose='ND Tuition Fee' OR purpose='CERT Tuition Fee')ORDER BY  paytype ASC";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['purpose']) ?></td><td><?php echo htmlentities($result['programme']) ?></td>
                                <td>&#8358; <?php echo htmlentities(number_format($result['amount'],2));?></td>
                                <td><?php echo htmlentities($result['level']);?></td>
                                <td><?php echo htmlentities($result['paytype']);?></td>
                                
                                    <td>
                                    <button class="btn btn-primary userinfo" id="<?php echo $result['level']?>" lv="<?php echo $result['amount']?>" pur="<?php echo $result['purpose']?>" pty="<?php echo $result['paytype']?>" prg="<?php echo $result['programme']?>"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger userinfof" id="<?php echo $result['level']?>" lv="<?php echo $result['amount']?>" pur="<?php echo $result['purpose']?>" pty="<?php echo $result['paytype']?>" prg="<?php echo $result['programme']?>"> <i class="fa fa-trash"></i> </button>
                                </td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                    <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
                    ?>
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
        <!--  -->
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
                <div class="modal-dialog modal-dialog-scrollable" role="document">
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
            if(!empty($showModalEr)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModalEr").modal("show");
                });
            </script>';
            }
            if(!empty($showModalr)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModalr").modal("show");
                });
            </script>';
            }
            if(!empty($showModal)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModal").modal("show");
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
            var pty = $(this).attr('pty');
            var prg = $(this).attr('prg');
            // AJAX request
            $.ajax({
                    url: 'payedit',
                    type: 'GET',
                    data: {userid: userid,lvd:lvd,pur:pur,pty:pty,prg:prg},
                    success: function(response)
                    { 
                        $('#teacher_detail').html(response);
                        // Display Modal
                        $('#viewModals').modal('show');
                    }
                });
            });
       });
/*For Delete Query*/
    $(document).ready(function(){

            // $('.userinfo').click(function(){
            $('body').on('click','.userinfof',function(){
            var userid = $(this).attr('id');
            var lvd = $(this).attr('lv');
            var pur = $(this).attr('pur');
            var pty = $(this).attr('pty');
            var prg = $(this).attr('prg');
            // AJAX request
            $.ajax({
                    url: 'deleedit',
                    type: 'GET',
                    data: {userid: userid,lvd:lvd,pur:pur,pty:pty,prg:prg},
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