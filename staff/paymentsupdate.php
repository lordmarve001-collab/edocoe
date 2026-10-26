<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

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
$ses=$_SESSION['section'];

/*Updating Query*/
$error='';
$succ='';
if (isset($_POST['submitpr'])) {
   
   $nsec=mysqli_escape_string($con,$_POST['sections']);
   $ppg=mysqli_escape_string($con,$_POST['programm']);
   $puy=mysqli_escape_string($con,$_POST['purpso']);
   $newsd=mysqli_escape_string($con,$_POST['studentids']);
   $pacd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE section='".$_SESSION['pur']."' AND studentid='".$_SESSION['userid']."' AND id='".$_SESSION['pty']."' "));
  
    if ($pacd['semester']!="" OR $pacd['level']!='') {
        $nlev=mysqli_escape_string($con,$_POST['levelc']); 
        //$nsemt=mysqli_escape_string($con,$_POST['semester']);
        if ($pacd['semester']=="") {
            $nsem='';
        }else{
            $nsem=mysqli_escape_string($con,$_POST['semester']);
        }
        $upd=mysqli_query($con,"UPDATE studentpayment SET level='$nlev',semester='$nsem',section='$nsec',programme='$ppg',purpose='$puy',studentid='$newsd'  WHERE studentid='".$_SESSION['userid']."' AND section='".$_SESSION['pur']."' AND id='".$_SESSION['pty']."'");
          if ($upd) {
            $showModall='true';
              $succ='Student Data Successfully Updated';
          }else{
            $showModale='true';
            $error='Purpose Selected Does not Exit';
          }
        
    }else{
        $upd=mysqli_query($con,"UPDATE studentpayment SET section='$nsec',purpose='$puy',studentid='$newsd',programme='$ppg' WHERE studentid='".$_SESSION['userid']."' AND section='".$_SESSION['pur']."' AND id='".$_SESSION['pty']."'");
          if ($upd) {
            $showModall='true';
              $succ='Student Data Successfully Updated';
          }else{
            $showModale='true';
            $error='Purpose Selected Does not Exit';
          }
    }
   
}

/*Deleteing Query*/
if (isset($_POST['updaterol'])) {
    $mys=mysqli_query($con,"DELETE FROM studentpayment WHERE studentid='".$_SESSION['userid']."' AND section='".$_SESSION['sess']."' AND id='".$_SESSION['sems']."'");
    if ($mys) {
    $showModall='true';
      $succ='Student Data Successfully Deleted';
  }else{
    $showModale='true';
    $error='Error Occured While Deleting';
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

                    }else if ($paych['position']=="bursar") {
                    
                    include('includes/supbubar.php');
                    }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
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
                                    <h2 class="title">Student Payment Record</h2>
                                
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

    if ($paych['position']=="superadmin")
    {
    ?> 
    
                
                <section>
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>StudentID</th>
                        <th>Student Name</th>
                        <th>Programme</th>
                        <th>Semester</th>
                        <th>Level</th>
                        <th>Mode</th>
                        <th>Purpose</th>
                        <th>Reference</th>
                        <th>Action</th>
                       
                    </tr>
                </thead>
                <tbody>
                <?php
                  $sql = "SELECT * FROM studentpayment WHERE section='$ses' ORDER BY studentid";
                $query = mysqli_query($con,$sql);
                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                $cnt=1;
                
                foreach($results as $result)
                {  
                     stuname($con,$result['studentid']); 
                    ?>
                <tr>
                 <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($result['studentid']) ?></td>
                    <td><?php echo htmlentities($_POST["harrd"]) ?></td>
                    <td><?php echo htmlentities($result['programme']);?></td>
                    <td><?php echo htmlentities($result['semester']);?></td>
                    <td><?php echo htmlentities($result['level']);?></td>
                    <td ><?php echo htmlentities($result['modes']);?></td>
                    <td><?php echo htmlentities($result['purpose']);?></td>
                    <td><?php echo htmlentities($result['paymentreference']);?></td>
                    <td>
                        <button class="btn btn-primary userinfo" id="<?php echo $result['studentid']?>" pur="<?php echo $result['section']?>" pty="<?php echo $result['id']?>"><i class="fa fa-edit"></i> </button>
                        <button class="btn btn-danger userinfof" id="<?php echo $result['studentid']?>" pur="<?php echo $result['section']?>" pty="<?php echo $result['id']?>"><i class="fa fa-trash"></i> </button>
                    </td>
                    
                </tr>
                <?php 

                $cnt=$cnt+1;
                 
                } ?> 
                                        </tbody>
                                        </table>
                                    </section>

                <?php

                       
                    }                                              
                    /*Closing of If Statement*/
                    else{
                    
                   ?>
                   <p style="color:darkred;font-size: 16px;font-weight: bold;text-align: justify;">Sorry Dear: <b style="color:blue;"><?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page.</p>
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
                <div class="modal" id="viewModal">
            <div class="modal-dialog">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Student Payment Data Update
                    <button type="button" class="close" data-dismiss="modal" style="float:right;">&times;</button></h4>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_detail">

                  </div>

      <!-- Modal footer -->
              

            </div>
          </div>
        </div>
        <div class="modal" id="viewModals">
            <div class="modal-dialog">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Deleting Student Cleared Data
                    <button type="button" class="close" data-dismiss="modal" style="float:right;">&times;</button></h4>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_details">

                  </div>

      <!-- Modal footer -->
              

            </div>
          </div>
        </div>
                <!-- Separeed -->
                <div class="modal fade" id="checkModall" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($succ)?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
                            <p><?php echo $error ?>.</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';


            if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
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
            /*For Delete Query*/
    $(document).ready(function(){

            // $('.userinfo').click(function(){
            $('body').on('click','.userinfo',function(){
            var userid = $(this).attr('id');
            var pur = $(this).attr('pur');
            var pty = $(this).attr('pty');
            /*id
pur
pty*/
            // AJAX request
            $.ajax({
                    url: 'ppledit',
                    type: 'POST',
                    data: {userid:userid,pur:pur,pty:pty},
                    success: function(response)
                    { 
                        $('#teacher_detail').html(response);
                        // Display Modal
                        $('#viewModal').modal('show');
                    }
                });
            });
       });
/*Function for Delecting*/
    $(document).ready(function(){

            // $('.userinfo').click(function(){
            $('body').on('click','.userinfof',function(){
            var userid = $(this).attr('id');
            var pur = $(this).attr('pur');
            var pty = $(this).attr('pty');
            
            $.ajax({
                    url: 'pledit',
                    type: 'POST',
                    data: {userid:userid,pur:pur,pty:pty},
                    
                    success: function(response)
                    { 
                        $('#teacher_details').html(response);
                        // Display Modal
                        $('#viewModals').modal('show');
                    }
                });
            });
       });
    
        </script>
    </body>
</html>
<?php } 
function stuname($con,$maxt)
 {

    $ld=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jambappli WHERE jambreg='$maxt'"));
    if ($ld!="") {
        $max=$ld['applid'];
    }else{
         $max=$maxt;
    }
    

   $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
       $sqld =mysqli_query($con,"SELECT *  FROM  personaldata WHERE (studentid='$maxt' OR applid='$max' )");
      $data = mysqli_fetch_assoc($sqld);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =strtoupper($sur).", ".$first." ".$las;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =strtoupper($las).", ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }

 }
?>
