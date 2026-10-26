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
$key='pk_live_eb73a010958c2f95c86c47c5b5e8351a043e7eb2';
$spk='SPL_CyZB32JXGQ';
   //$key='pk_test_6d3ed6a3e94ed97b260c66c069851bed0b017cd1';
$length=5;
$string='';
$chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $size = strlen($chars);
   for ($i = 0; $i < $length; $i++) {
       $string .= $chars[rand(0, $size - 1)];
       $date=date("Ymd");
       $ned='EDOCOE'.$date.''.$string;
  } 

if (isset($_POST["registerings"])) {
    //$amts=mysqli_real_escape_string($con,$_POST["amount"]);
           $prog='TSLS Program';
            $mail=mysqli_escape_string($con,$_POST['jambemail']);
            $pnos=mysqli_escape_string($con,$_POST['jambphone']);
            $Leadership=mysqli_escape_string($con,$_POST['Leadership']);
            $last=mysqli_escape_string($con,$_POST['surname']);
            $gender=mysqli_escape_string($con,$_POST['gender']);
            $school=mysqli_escape_string($con,$_POST['school']);
            $lga=mysqli_escape_string($con,$_POST['lga']);
            $type=mysqli_escape_string($con,$_POST['type']);
            $Institution=mysqli_escape_string($con,$_POST['Institution']);
            $reason=mysqli_real_escape_string($con,$_POST["reason"]);
            $title=mysqli_real_escape_string($con,$_POST["title"]);

    	                          $year='2023/2024';
                                    $id =mysqli_fetch_assoc(mysqli_query($con, "SELECT count(applid) as userid FROM tsls"))['userid'];
                                    $id = $id + 1;
                                    $yrk=substr($year,0,4);
                                    $yr=substr($yrk,-2);
                                    $mat = "EDOCOE/TSLS/24/".str_pad($id, 4, '0', STR_PAD_LEFT);
                                  $register=mysqli_query($con,"INSERT INTO tsls (title,applid,fullname, lga, phone, gender, email, school, types, reasons, inst, leader, date) 
                                  VALUES ('$title','$mat','$last', '$lga', '$pnos', '$gender', '$mail', '$school', '$type', '$reason', '$Institution', '$Leadership', now()) ");                                  
       $succ="Congratulations $last Your Registration is Sucessful";
       $succs="Your Registration ID: $mat";
        $showModalp='true';
     }


 /*End Here*/


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
                                                            <section>
                                                   <div class="col-md-6" style="height:200vh;"> 
                                                   <h3>Pre-Tertiary School Leadership Summit</h3>
                    <div class="account-card-content"> 
                    <form  action="" method="post">
                      <label for="lga">Title</label>
                  <select class="form-control" name="title" id="title" required>
                                    <option selected disabled>Select Title</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Dr.">Dr.</option>
                                            <option value="Prof.">Prof.</option>
                                            <option value="Engr.">Engr.</option>
                                    </select>   
                    <label for="surname">Full Name</label>
                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter your FirstName"  value="" required>
                <label for="faculty">Gender</label>
                    <select class="form-control" name="gender" id="gender">
                                    <option selected disabled>Please select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                    </select>
                    <label for="firstname">Name of School</label>
                    <input type="text" class="form-control" id="school" name="school" placeholder="Enter School Name" required>
                  <label for="phone">Your Phone</label>
                    <input type="tel" class="form-control" name="jambphone" maxlength="13"  placeholder="Enter Phone Number" required>
                    <label for="phone">Email</label>
                    <input type="email" class="form-control" name="jambemail"  placeholder="Enter Email" required>
                    
                  <label for="lga">LGA of Your School</label>
                  <select class="form-control" name="lga" id="lga" required>
                                    <option selected disabled>Select LGA of Your School</option>
                                            <option value="Akoko-Edo">Akoko-Edo</option>
                                            <option value="Egor">Egor</option>
                                            <option value="Esan Central">Esan Central</option>
                                            <option value="Esan North-East">Esan North-East</option>
                                            <option value="Esan South-East">Esan South-East</option>
                                            <option value="Esan West">Esan West</option>
                                            <option value="Etsako Central">Etsako Central</option>
                                            <option value="Etsako East">Etsako East</option>
                                            <option value="Etsako West">Etsako West</option>
                                            <option value="Igueben">Igueben</option>
                                            <option value="Ikpoba-Okha">Ikpoba-Okha</option>
                                            <option value="Oredo">Oredo</option>
                                            <option value="Orhionmwon">Orhionmwon</option>
                                            <option value="Ovia North-East">Ovia North-East</option>
                                            <option value="Ovia South-West">Ovia South-West</option>
                                            <option value="Owan East">Owan East</option>
                                            <option value="Owan West">Owan West</option>
                                            <option value="Uhunmwonde">Uhunmwonde</option>
                                         
                                    </select>                    
                    <label for="type">Categories of School</label> 
                    <select class="form-control" name="type" id="type" required>
                                    <option selected disabled>Select Type of School</option>
                                            <option value="Public">Public</option>
                                            <option value="Private">Private</option>
                                    </select>
                                     <label for="Leadership">Position</label>
                    <select class="form-control" name="Leadership" id="Leadership" required>
                                    <option selected disabled>Select Leadership Position</option>
                                            <option value="Proprietor">Proprietor</option>
                                            <option value="Principal">Principal</option>
                                            <option value="Teacher">Teacher</option>
                                            <option value="School Administrator">School Administrator</option>
               <option value="School Based Management Committee Leader">School Based Management Committee Leader</option>
                                            
                       <option value="Parent Council Leader">Parent Council Leader</option>
                                             
                                    </select>
                    <label for="firstname">Institution/organization</label>
                    <input type="text" class="form-control" name="Institution"  placeholder="Institution/organization Name" required>
                                        <label for="firstname">Reasons for interest in the summit (optional)</label>
                                 <textarea id="reason" class="form-control" name="reason" rows="4" cols="50"></textarea>

                    <div style="margin-top:2%;">
                    <button class="btn btn-danger" type="submit" name="registerings">Submit Request<i class="fa fa-refresh"></i>
                    </div>    
                                                        

                                        
                    </form>
                               <!--  <br>
                                <br> -->
                                
                </div>
                </div></ br>
<div class="col-md-6">
                    <div class="alert alert-info" style="font-size: 14px;font-weight: bolder;">
                        Pre-Tertiary School Leadership Summit
                        <br />Summit Fee: N10,000
                        <br /> Account Name: Edo State College of Education (IGR)<br>
Account Number: 1012892052<br>
Bank: Keystone Bank 
                        <img src="learn.jpg" alt="TSLS" width="500" height="600">

                    </div>
                 </div>
                        </section>

               
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
         <!-- Payment Option -->
     <div class="modal fade" id="checkModalp" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel"><i class="fa fa-credit-card"></i> Registration Successful
                            <button class="close" style="float:right;" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button></h5>
                        </div>
                        <div class="modal-body">
                           
                            <p><?php echo htmlentities($succ); ?></p>
                            <p><?php echo htmlentities($succs); ?></p>
                            <p>Registration Cost: N10,000<br>
                            Pay into The Following Account Deytails<br>Account Name: Edo State College of Education (IGR)<br>
Account Number: 1012892052<br>
Bank:Keystone Bank</p>

                        </div>
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left;">Close</button>
                       </div>
                    </div>
                </div>
            </div>  
    <!-- Payment Option -->

    
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
    
    if(!empty($showModalp)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalp").modal("show");
            });
        </script>';
            }
    if(!empty($showModalof)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalof").modal("show");
            });
        </script>';
            }
        ?>
     <script type="text/javascript">
function payWithPaystacks() {
     var fullname = '<?php echo htmlentities($last); ?>';
      var amount = <?php echo htmlentities($amount)?>*100;
      var prog = '<?php echo htmlentities($prog)?>';
      var email = '<?php echo $mail ?>';
      var phone = '<?php echo htmlentities($phone) ?>';
      var school = '<?php echo $school; ?>';
      var lga = '<?php echo $lga; ?>';
      var reason = '<?php echo $reason; ?>';
      var type = '<?php echo $type; ?>';
      var gender = '<?php echo $gender; ?>';
      var Institution = '<?php echo $Institution; ?>';
      var Leadership = '<?php echo $Leadership; ?>';
      var refs='<?php echo $payref; ?>';
      var pbk='<?php echo htmlentities($key) ?>';
      var spl='<?php echo htmlentities($spk) ?>'; 
      var fullname = fullname;  
      
      var handler = PaystackPop.setup({
        key: pbk,
        split_code:spl,
        email: email,
        ref: refs,
        amount: amount,
        currency: "NGN",
        
         metadata: {
          custom_fields: [
            {  
              display_name: "Mobile Number",
              variable_name: "mobile_number",
              value: phone,
            },
            {
              display_name: "Full Name",
              variable_name: "full_name",
              value: fullname,
            },
            {
              display_name: "School",
              variable_name: "School",
              value: school
            },
            {
              display_name: "lga",
              variable_name: "lga",
              value: lga
            },
            {
              display_name: "Payment Purpose",
              variable_name: "Payment Purpose",
              value: prog
            }
          ]
        },
        callback: function (response) {          

        var email = '<?php echo htmlentities($email) ?>';
        var phone = '<?php echo htmlentities($phone)?>';
        var xmlhttp = "";

          if (window.XMLHttpRequest) {
              // code for modern browsers
              xmlhttp = new XMLHttpRequest();
          } else {
              // code for old IE browsers
              xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          }

          xmlhttp.onreadystatechange = function() {
           if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {  
             
            window.location.href = "success?feedback=success" + "&ref=" + response.reference;
              
            }
           //alert(this.responseText + ".Pls Contact the Web master with your payment reference ID: ("+response.reference+")");
          };
          
          xmlhttp.open("POST", "include/progpay", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send("fullname="+fullname + "&email=" + email + "&phone=" +phone+ "&ref=" + response.reference + "&prog=" + prog+ "&lga="+lga+"&school="+school+"&type="+type+"&reason="+reason+"&Institution="+Institution+"&Leadership="+Leadership);}
      });

      handler.openIframe();
    }
    </script>
        
    </body>
</html>