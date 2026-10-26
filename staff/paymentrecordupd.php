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
                    }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Student School Fee Payment Updating</h4>
                                
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
                     if ($paych['position']=="superadmin") {
                       
                    
                     ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Payment Reference:</label>
                                                         <div class="col-sm-10">
                                                        
                                                         <input type="text" id="studentid" class="form-control clid" name="studentid" required="required" placeholder="Payment Reference Or Teller ID">
                                                       </div>

                                                    </div> 
                                                   
                                                    
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-primary">Submit Data</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                              <!-- End of Section -->

                    <?php 
                    if (isset($_POST['submitcor'])) {
                        $teld=trim(mysqli_real_escape_string($con,strtoupper($_POST['studentid'])));
                         $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$teld' "));
                        if ($telcheck>0) {
                        $stad=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE paymentreference='$teld'"));
                    ?>
                    <section>
            
                    <fieldset>
                        <legend align='center'>Payment Record Updating</legend>
                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                        <?php
                            $_SESSION['staff']=$teld;
                        ?>
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Student ID</label>
                                    <input type="text" name="studentid" value="<?php echo htmlspecialchars($stad['studentid'])?>" class="form-control clid">
                                </div>
                                <div class="form-group">
                                    <label for="default">Level:</label>
                                     
                                     <select name="level" class="form-control clid">
                                        <option selected value="<?php echo htmlspecialchars($stad['level']) ?>"><?php echo htmlspecialchars($stad['level']) ?></option>
                                        <?php
                                        $res = mysqli_query($con, "SELECT * FROM level WHERE levels!='".$stad['section']."'  ORDER BY levels");
                                    while ($row = mysqli_fetch_array($res)) {

                                        ?>
                                        <option value="<?php echo  $row["levels"]; ?>"><?php echo  $row["levels"]; ?></option>
                                    <?php

                                    }
                                    ?>
                                    
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="default">Semester:</label>
                                     
                                     <select name="semester" class="form-control clid">
                                        <option selected value="<?php echo htmlspecialchars($stad['semester']) ?>"><?php echo htmlspecialchars($stad['semester']) ?></option>
                                        <?php
                                        $res = mysqli_query($con, "SELECT * FROM semester WHERE semest!='".$stad['section']."'  ORDER BY semest");
                                    while ($row = mysqli_fetch_array($res)) {

                                        ?>
                                        <option value="<?php echo  $row["semest"]; ?>"><?php echo  $row["semest"]; ?></option>
                                    <?php

                                    }
                                    ?>
                                    
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Amount (&#8358;)</label>
                                    <input type="text" name="amount" value="<?php echo htmlspecialchars($stad['amount'])?>" class="form-control clid">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-lg-12"> 
                                <div class="form-group">
                                    <label>Session</label>
                                    <select name="paysec" class="form-control">
                                        <option selected value="<?php echo htmlspecialchars($stad['section']) ?>"><?php echo htmlspecialchars($stad['section']) ?></option>
                                        <?php
                                            $res = mysqli_query($con, "SELECT * FROM sections WHERE section!='".$stad['section']."' ORDER BY section DESC");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                                        <?php

                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                <label for="default">Programme:</label>
                                
                                 <select name="program" class="form-control clid">
                                    <option selected value="<?php echo htmlspecialchars($stad['programme']) ?>"><?php echo htmlspecialchars($stad['programme']) ?></option>
                                    <?php
                                    $res = mysqli_query($con, "SELECT * FROM programs WHERE pname!='".$stad['programme']."'  ORDER BY pname");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["pname"]; ?>"><?php echo  $row["pname"]; ?></option>
                                <?php

                                }
                                ?>
                                
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="default">Payment Purpose:</label>
                                
                                 <select name="paypou" class="form-control clid">
                                    <option selected value="<?php echo htmlspecialchars($stad['purpose']) ?>"><?php echo htmlspecialchars($stad['purpose']) ?></option>
                                    <?php
                                    $res = mysqli_query($con, "SELECT DISTINCT purpose FROM feesrecord WHERE purpose!='".$stad['purpose']."'  ORDER BY purpose");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["purpose"]; ?>"><?php echo  $row["purpose"]; ?></option>
                                <?php

                                }
                                ?>
                                
                                </select>
                            </div>
                            <div class="form-group">
                                <label></label>
                                <button class="btn btn-success" style="margin-top:7%" type="submit">Click to Update <i class="fa fa-refresh"></i></button>
                            </div>
                            </div>
                        </div> 
                    </form>
                    </fieldset>
                    </section>
                   <?php
                   }else{
                       $showModal='true';
                       $error='Student ID Or Reference ID '.$teld.' Does not Exit or Check and Try Again';
                    }
                            }//for Payment Check 
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
                            <p><?php echo htmlentities($error); ?> </p>
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
                            <p><?php echo $error ?> Detail Already Exist.</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalS" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Detail is Successfully Submmitted</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
             <!-- Error Modal Box -->
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
                            <p>An Error Occured While Submitting Please Try Again!!!</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
              <div class="modal fade" id="checka" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body" id="trying">
                            <p>Student Matriculation No Or ID Does not Exist</p>
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
        ?>
        
        <?php           
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
<?php 

}  ?>
