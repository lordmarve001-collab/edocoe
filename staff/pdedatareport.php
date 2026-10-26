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
        <link rel="stylesheet" href="css/print.css" media="print">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/animate.min.css" media="screen">
        <style>
            @media Print{
                .form-horizontal{
                 display:none;  
                }
                td{
                    text-transform:capitalize;
                    padding:15px;
                }
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
                                <div>
                                    <h2 class="title"> Payment Day's Range Report Printing</h2>
                                
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
                    <section class="section">
                        <form method="POST" class="form-horizontal">
                            <div class="col-lg-6">
                               <div class="col-lg-12">
                                 <div class="form-group">
                            <label for="default" class="col-sm-2 control-label">Day:</label>
                             <div class="col-sm-10">
                             <input type="date" class="form-control clid" name="studentid" required="required" placeholder="Enter Student Id Or Matric No">
                           </div>

                        </div>  
                               </div>
                           </div>
                           <div class="col-lg-6">
                               <div class="col-lg-12">
                                   <div class="form-group">
                            <label for="default" class="col-sm-2 control-label">To:</label>
                             <div class="col-sm-10">
                             <input type="date" class="form-control clid" name="studentidt" required="required" placeholder="Enter Student Id Or Matric No">
                           </div>

                        </div>
                        <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="query" class="btn btn-primary form-control">View Report</button>
                        </div>
                    </div> 
                               </div>
                           </div>                     
    <!-- Sepreaaater -->
                                             
                </form>
            </section>
            <?php
                    if (isset($_POST['query'])) {
                        
                            $ccode=mysqli_escape_string($con,$_POST['studentid']);
                            $ccodt=mysqli_escape_string($con,$_POST['studentidt']);
                            
                            $sql=mysqli_query($con,"SELECT * FROM studentpayment WHERE paytime BETWEEN '$ccode' AND '$ccodt' AND (purpose!='SUG Fee' AND purpose!='SUG Fee Return')");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                        try {
                            $_SESSION['dates']=$ccode;
                            $_SESSION['date']=$ccodt;
                            ?>
                            <div class="">
                                <div class="logos" style="width: 180px;margin: auto;">
                                            <span><img src='<?php echo $logo ?>' id="images1"  style="width: 90px;height: 90px;"></span>
                                        </div>
                                        <div style="width:300px;margin: auto;">
                                            <span align="center" style="margin-top: 1%; font-size: 18px;font-weight: bolder;"><?php echo htmlentities($orgname)?>, <?php echo htmlentities($schadress)?> </span><br>
                                            <span style="text-transform: uppercase;font-weight: bolder;margin-left:34px;">PAYMENT SUMMARY</span>
                                        </div>
                                    <p class=MsoNormal style='margin-left:10.35pt;'><span style='font-size:12.0pt;mso-bidi-font-size:11.0pt;line-height:106%;font-family:
                                                        "Times New Roman",serif;font-weight: bolder;'><span style='mso-tab-count:2'></span>&nbsp;&nbsp;&nbsp;From: <?php echo htmlentities($ccode) ?> To : <?php echo htmlentities($ccodt) ?>
                            </div>
                            <table class='table cotable'  border="1" cellspacing="0" cellpadding="5">
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Purpose</th>
                                    <th>Reference</th>
                                    <th>Amount (&#8358;)</th>
                                    <th>Pay Mode</th>
                                    <th>Pay Type</th>
                                </tr>
                                <?php 
                                                $k=0;
                                                $sql="SELECT * FROM studentpayment WHERE  paytime BETWEEN '$ccode' AND '$ccodt' AND (purpose='PDE Application' OR purpose='PDE Acceptance') ORDER BY purpose ASC";
             
                                                  $_POST['totalunit']=0;
                                                   $st = mysqli_query($con,$sql);
                                                    $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
                                               foreach($count as $row)
                                               {
                                                $k+=1;
                                                 
                                                 stuname($con,$row['studentid']);
                                                 
                                                
                                                ?>
                                                <tr style="font-weight: bolder;">
                                                    <td style="width:10px;"><?php echo $k ?></td>
                                                    <td><?php echo $row['studentid'] ?></td>
                                                    <!-- <td><?php echo $_POST["harrd"]?></td> -->
                                                    <td><?php echo $row['purpose'] ?></td>
                                                    <td><?php echo $row['paymentreference'] ?></td>
                                                    <td>&#8358; <?php echo number_format($row['amount'],2)  ?></td>
                                                    <td><?php echo $row['modes']?></td>
                                                    <?php if ($row['paytype']=="") { ?>
                                                       <td><?php echo htmlentities('Full') ?>
                                                   <?php }else{ ?>
                                                    <td><?php echo $row['paytype'] ?></td>
                                                <?php } ?>
                                                </tr>
                                            <?php } 
                                                gettotal($ccode,$ccodt,$con);
                                            ?>
                                            <tr>

                                                <td colspan="5"><b style="float:right;font-weight: bolder;">Total Amount (&#8358;)</b></td>
                                                <td colspan="2">&#8358;<?php echo htmlentities(number_format($_POST['amount'],2)) ?></td>
                                                
                                                <!-- <td></td> -->
                                            </tr>
                            </table>
                            <?php   
                            } catch (Exception $e) {
                                echo $e;
                            }
                        ?>
                        <button class="btn btn-primary" onclick="window.open('pdefeep','_blank')">Print Detail <i class="fa fa-print"></i></button>
                        <?php }else{
                                            $showModals='true';
                                        }
                                    
                                ?>
                 <?php
                     
                     }else{
                    ?><div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">
                    <?php
                        }
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
<div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Sorry There is no Result Base Your Criteria Selected</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModalx" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
       <?php
        include 'includes/buton.php';
        if(!empty($showModals)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModals").modal("show");
            });
        </script>';
    }
    if(!empty($showModalx)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalx").modal("show");
            });
        </script>';
    }
       ?>
    </body>
</html>


<?php }
 function stuname($con,$maxt)
 {
    $ld=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jambappli WHERE jambreg='$maxt'"));
    if ($ld!='') {
        $maxt=$ld['applid'];
        $sql =mysqli_query($con,"SELECT *  FROM  personaldata WHERE (applid='$maxt' OR studentid='$maxt')");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $name =$sur." ".$las." ".$first;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
        }
    }else{
    $sqlt =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $datat = mysqli_fetch_assoc($sqlt);
      if ($datat=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $datat['firstname'];
        $sur = $datat['othername'];
        $las=$datat['lastname'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
        }
    }
    

 }
 function gettotal($prg,$le,$con){
    $sum=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(amount) As amount FROM studentpayment WHERE paytime BETWEEN '$prg' AND '$le'  AND (purpose='PDE Application' OR purpose='PDE Acceptance')"));
    $_POST['amount']=$sum['amount'];
 }
 ?>
