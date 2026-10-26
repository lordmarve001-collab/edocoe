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
//for inserting Record
if (isset($_POST['submitcor'])) {
      
        $teld=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
        
        $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT DISTINCT studentid FROM studentpayment  WHERE studentid='$teld' "));
        if ($telcheck>0) {
                  $showModalS='true';
                  $use=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='$teld' "));
                  $stuid=$use['studentid'];
                  $amt=$use['amount'];
                  $pop=$use['purpose'];
                  $ref=$use['paymentreference'];
                  $payt=$use['paytime'];
                  stuname($con,$stuid); 
                  $_SESSION["staff"]='$stuid';
        }else{
                 $showModale='true';
                   $error='Student ID '.$teld.' Does not Exit or Check and Try Again';
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

                   <?php
                //'subbursar' 'supbursar'

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

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Student Payment History View Setup</h4>
                                
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
                     if ($paych['position']=="accountant" OR $paych['position']=="supbursar") {
                       
                    
                     ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" name="tellerid" autocomplete="off" required="required" placeholder="Enter Student ID">
                                                       </div>

                                                    </div>
                                                   
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-success">Click to View <i class="fa fa-eye"></i> </button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                              <!-- End of Section -->
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
        <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please Select <?php echo htmlentities($error); ?> </p>
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
           <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalS" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Student Payment History</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                           <div style="float: none;width: 200px;margin: auto;"> <img src="<?php

                            echo htmlentities($_POST['maks']); ?>" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>

                            <table>
                                 <tr>
                                     <td>Full Name:</td>
                                     <td><b style="text-transform: capitalize;"><?php
                                     $_SESSION['stuid']=$stuid; echo htmlentities($_POST["harrd"]); ?></b></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>Matric No:</td>
                                     <td><?php echo htmlentities($stuid); ?></td>
                                 </tr>
                                 <tr>
                                     <td>Faculty:</td>
                                     <td><?php echo htmlentities($_POST["makst"]); ?></td>
                                 </tr>
                                 <tr>
                                     <td>Programme:</td>
                                     <td><?php echo htmlentities($_POST["prp"]); ?></td>
                                 </tr>
                            </table>
                            <table class="table table-bordered" style="width:400px;font-size: 11px;">
                <thead>
                <tr>
                <th>#</th>
                <th>Session</th>
                <th>Amount Paid</th>
                <th>Semester</th>
                <th>Purpose</th>
                <th>Reference</th>
                <th>Status</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $lf=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$stuid'"));
                    $maxt=$lf['admissionid'];
                    $ld=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$maxt'"));
                    $max=$ld['jambreg'];
             $sql="SELECT * FROM studentpayment WHERE (studentid='$max' OR studentid='$stuid' OR studentid='$maxt') ORDER BY level "; 
            //$sql = "SELECT * FROM studentpayment WHERE studentid='$stuid' ORDER BY level ";
                                               
                                                $query = mysqli_query($con,$sql);
                                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                                $cnt=1;
                                               
                                                foreach($results as $result)
                                                
                                                {   ?>
                                                <tr>
                                                 <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result['section']);?></td>
                                                    <td>&#8358;<?php echo htmlentities(number_format($result['amount'],2));?></td>
                                                    <?php
                                                        if ($result['semester']!="") {
                                                            ?>
                                                         <td><?php echo htmlentities($result['semester']);?>
                                                             
                                                         </td>   
                                                      <?php  }else{
                                                        
                                                        ?>
                                                       <td><?php echo 'Full Payment';?></td>
                                                        <?php
                                                      }
                                                     ?>
                                                    <td><?php echo htmlentities($result['purpose']);?></td>
                                                    <td><?php echo htmlentities($result['paymentreference']);?></td>
                                                    <td><?php echo htmlentities($result['status']);?></td>
                                                    
                                                </tr>
                                                <?php 

                                                $cnt=$cnt+1;
                                                 
                                                }
    $queryy="SELECT SUM(amount) AS sumunit FROM studentpayment WHERE (studentid='$max' OR studentid='$stuid' OR studentid='$maxt') ";
$resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
              $_POST['total']=$row['sumunit'];
                
            }

        ?>
                                                       
            <tr>
                <td colspan="2" style="text-align: right;text-transform: uppercase;font-weight: bolder;"> Total: </td>
                <td colspan="2" style="font-weight: bolder;text-align: left;">&#8358; <?php echo htmlentities(number_format($_POST['total'],2))?></td>
                <td></td>
                <td></td>
                <td></td>
               <td></td> 
                
            </tr>
        </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-info" onclick="window.open('printsum','_blank')"><i class="fa fa-print"></i> Print Receipt</button>
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
</html>
<?php }
function stuname($con,$maxt)
 {
    
    $sql =mysqli_query($con,"SELECT  *  FROM  student WHERE studentid='$maxt' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $datak=mysqli_fetch_assoc(mysqli_query($con,"SELECT  *  FROM  personaldata WHERE (applid='$maxt' OR studentid='$maxt') "));
    if ($datak=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first = $datak['firstname'];
        $sur = $datak['surname'];
        $las=$datak['lastname'];
        $prp=$datak['programme'];
        $maks="../apply/".$datak['passport'];
        $name =strtoupper($sur)." , ".$first." ".$las;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["prp"]= $prp;
        $facu=$datak['faculty'];
        $def=$datak['department'];
        $dep=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='$def'"));
        $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$facu'"));
        $_POST["makst"]= $fac['fname'];
        $_POST["det"]= $dep['dept'];
        }
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $prp=$data['programme'];
        $maks="../student/".$data['image'];
        $name =strtoupper($las)." , ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["prp"]= $prp;
        $facu=$data['faculty'];
        $def=$data['department'];
        $dep=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='$def'"));
        $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$facu'"));
        $_POST["makst"]= $fac['fname'];
        $_POST["det"]= $dep['dept'];
        }

 

 }
 function stunames($con,$max)
 {
    
    $sql =mysqli_query($con,"SELECT  *  FROM  personaldata WHERE applid='$max'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $prp=$data['programme'];
        if ($prp=='Jupeb') {
           $maks="../apply/applicant/".$data['passport'];
        }else{
            $maks="../postume/".$data['passport'];
        }
        
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["prp"]= $prp;
        }

 }
 ?>
