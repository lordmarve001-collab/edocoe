<?php
session_start();
$error='';
ob_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include 'include/dbconfig.php';

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];

$prog='Application Fee';
/*Start Here*/
$length=5;
$string='';

 /*End Here*/

if (isset($_POST["registering"])) {
        
        if (empty($_POST["amount"]))
         {
             $error="Please enter amount";
             $showModalEr='true';
         }
            else{
                             
            $staffid=strtoupper(mysqli_escape_string($con,$_REQUEST["staffid"]));
            $firstname=strtolower(mysqli_escape_string($con,$_REQUEST["firstname"]));
            $rank=strtoupper(mysqli_escape_string($con,$_REQUEST["rank"]));
            $dep=strtoupper(mysqli_escape_string($con,$_REQUEST["dep"]));
            $phone=strtoupper(mysqli_escape_string($con,$_REQUEST["phone"]));
            $padv=strtoupper(mysqli_escape_string($con,$_REQUEST["padvance"]));
            $dpre=(mysqli_escape_string($con,$_REQUEST["dprevious"]));
            $bank=(mysqli_escape_string($con,$_REQUEST["bank"]));
            $branch=(mysqli_escape_string($con,$_REQUEST["branch"]));
            $acc=(mysqli_escape_string($con,$_REQUEST["acc"]));
            $amount=(mysqli_escape_string($con,$_REQUEST["amount"]));
            $purpose=(mysqli_escape_string($con,$_REQUEST["purpose"]));

        
$payment = mysqli_query($con, "INSERT INTO requestform (staffid, fullname, department, rank, rdate,padv, bank, branch, accno, phone, purpose, amount, status,date) 
VALUES ('$staffid', '$firstname', '$dep', '$rank', '$dpre', '$padv', '$bank', '$branch', '$acc', '$phone', '$purpose', '$amount', 'Pending',now()) ");



                    if($payment>0){
                              $succ=''.$firstname.' your ADVANCE REQUEST FORM has been Submitted Successfully, The Bursary Department will contact you soon.';
                              $showModal='true';
                              
                            }
                            else{
                                $error="Error Occured!!";
                                $showModalEr='true';
                            }
            }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo htmlentities($orgname) ;?></title>
    <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://js.paystack.co/v1/inline.js"></script>
    <script type="text/javascript">
          function changes(objs){
    var selectBox = objs;
    var selected = selectBox.options[selectBox.selectedIndex].value;
    var retCustDetails = document.getElementById("retCustDetails");
    var tradeCustDetails = document.getElementById("tradeCustDetails");

    if(selected == 'Yes'){
        retCustDetails.style.display = "block";
        tradeCustDetails.style.display = "none";
    }else if (selected == 'No') {
         window.location.href='registration'
    }
    else{
        retCustDetails.style.display = "none";
        tradeCustDetails.style.display = "none";
    }
}
      </script>
    <style type="text/css">
        .field-icon {
              float: right;
              margin-left: -35px;
              margin-top: -30px;
              position: relative;
              z-index: 2;
            }
            .pull-right{float:right!important}
    </style>
</head>
<body>
    <?php include('include/menubar.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="page-head-line"></h5>
                </div>
            </div>
            <div class="row">
                
           
            
                <div class="col-md-12" style="height:100vh;"> 
                    <div class="account-card-content">
                        <div class="panel-body">
                                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                       <div class="form-group">
                            <label for="default" class="col-sm-2">NAME OF OFFICER:</label>
                             <div class="col-sm-10">
                             <select name="section" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Your Name</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM staffdata ORDER BY surname");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["sta"]; ?>"><?php echo  $row["surname"]; ?> <?php echo  $row["firstname"]; ?> <?php echo  $row["othername"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                                                                                                       
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="query" class="btn btn-primary">Continue</button>
                        </div>
                    </div>                    
                    </form>
                </section>
                
                <?php
                    if (isset($_POST['query'])) {
                        if (empty($_POST['section'])) {
                         $showModal = "true";
                         $error="Please Select Your Name";
                        }
                        else{
                        $secc=mysqli_escape_string($con,$_POST['section']);
                        $sql=mysqli_query($con,"SELECT * FROM staffdata WHERE sta='$secc'");
                          $rows = mysqli_num_rows($sql);
                          $rows = mysqli_fetch_assoc($sql);
                          if($rows > 0)
                          {
                            $_SESSION['section']=$secc;
                ?>
                <section>
                                                    <div class="account-card-content"> 
                    <form  action="" method="post">
                      <label for="firstname">Staff ID</label>
                    <input type="text" class="form-control" id="staffid" name="staffid" readonly   value="<?php echo htmlentities($rows['sta']);?>" >
                    
                    <label for="surname">Staff Full Name</label>
                    <input type="text" class="form-control" id="surname" name="firstname" readonly   value="<?php echo htmlentities($rows['surname']);?> <?php echo htmlentities($rows['firstname']);?> <?php echo htmlentities($rows['othername']);?>" required>
                
                    <label for="firstname">Rank</label>
                    <input type="text" class="form-control" id="firstname" name="rank" readonly   value="<?php echo htmlentities($rows['rank']);?>" >
                    <label for="firstname">Department</label>
                    <input type="text" class="form-control" id="firstname" name="dep" readonly   value="<?php echo htmlentities($rows['department']);?>" >
                    <label for="firstname">Phone No</label>
                    <input type="text" class="form-control" id="firstname" name="phone" readonly   value="<?php echo htmlentities($rows['phone']);?>" >
                    
                    <b>ADVANCE REQUEST</b><br>
                    
                     
                    <label for="surname">Previous Advance Taken: <i>(if yes)</i></label>
                    <input type="text" class="form-control" name="padvance" placeholder="Enter previous advance taken"  >
                    <label for="firstname">Date <i>(previous advance was taken)</i></label>
                    <input type="date" class="form-control" name="dprevious" >
                    <label for="firstname">Bank</label>
                    <input type="text" class="form-control" name="bank" >
                    <label for="firstname">Branch</label>
                    <input type="text" class="form-control" name="branch" >
                    <label for="firstname">Account No</label>
                    <input type="text" class="form-control" name="acc" >
                    <label for="firstname">Amount</label>
                    <input type="text" class="form-control" name="amount" >                    
                    <label for="firstname">Purpose of This Advance Being Required</label>
                    <textarea  class="form-control" name="purpose"></textarea>
                    <div style="margin-top:2%;">
                        <div> UNDERTAKING : I undertake to give a full account of the advance given me within 48 hours on completion of expenditure. Failure to do so, the college is authorized to deduct the amount of the advance from my salary.
                        </div>
                    <button class="btn btn-danger"
                    type="submit" name="registering">Submit <i class="fa fa-refresh"></i></button> <b style='color: red;float: right;'> </b>
                    </div>    
                                                        

                                        
                    </form>
                        
                               <!--  <br>
                                <br> -->
                </div>
                           
                        </section>
                        <?php
                            }
                            else{
                           $showModale='true';

                            }
                            //}
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
        <!-- /.main-wrapper -->
        <div class="modal" id="viewModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Student Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_details">

                  </div>

      <!-- Modal footer -->
              <div class="modal-footer">
                
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div> 

        <!-- /.main-wrapper -->
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
                            <p><?php echo htmlentities($succ); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
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
                            <p>There No Result Base on the Criterial Selected</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
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
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalx" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Message Successfully Sent</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Student Details -->
            <div class="modal" id="viewModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Student Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_details">

                  </div>

      <!-- Modal footer -->
              <div class="modal-footer">
                
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div> 
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'include/buton.php';

    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
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
    <script type="text/javascript">
        $(document).ready(function(){

            //$('.userinfo').click(function(){
           $('body').on('click','.userinfo',function(){
            var userid = $(this).attr('id');
            // AJAX request
            $.ajax({
                    url: 'viewstudata',
                    type: 'GET',
                    data: {userid: userid},
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