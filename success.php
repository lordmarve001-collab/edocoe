<?php
session_start();
$connection = TRUE;
include 'include/dbconfig.php';

if (empty($_GET['ref']) || empty($_GET['feedback'])) {
      header("location: index?");
  }

//header("Refresh:10;index");
$refe = $_GET['ref'];                        
$feedback = $_GET['feedback'];                        

$email=$_SESSION['email'];
$prog=$_SESSION['prog'];
$phone=$_SESSION['phone'];
$mat=$_SESSION['first'];
$progs=$_SESSION['progs'];
$dep=$_SESSION['dep'];
$year=$_SESSION['year'];
 $fullname=$_SESSION['last'];
 $amount=$_SESSION['amt'];

 $inst=$_SESSION['addresst'];
 $address=$_SESSION['address'];
 $orgemail=$_SESSION['orgemail'];
 $copy=$_SESSION['copy'];
 $faculty=$_SESSION['faculty'];
 $fac=$_SESSION['fac'];

$_SESSION['payre']=$refe;
$_SESSION['feedback']=$feedback;
 $gender=$_SESSION['gender'];
 $Leadership=$_SESSION['Leadership'];
 $Institution=$_SESSION['Institution'];
 $type=$_SESSION['type'];
 $reason=$_SESSION['reason'];
 $school=$_SESSION['school'];
 $lga=$_SESSION['lga'];
 $title=$_SESSION['title'];

$tday = date("Y-m-d");
if ($feedback=='success'){
    
    
    
    	if($prog=='TSLS Program'){
    	                          $year='2023/2024';
                                    $id =mysqli_fetch_assoc(mysqli_query($con, "SELECT count(applid) as userid FROM tsls"))['userid'];
                                    $id = $id + 1;
                                    $yrk=substr($year,0,4);
                                    $yr=substr($yrk,-2);
                                    $mat = "EDOCOE/TSLS/24/".str_pad($id, 4, '0', STR_PAD_LEFT);
                                  $register=mysqli_query($con,"INSERT INTO tsls (title,applid,fullname, lga, phone, gender, email, school, types, reasons, inst, leader, date) 
                                  VALUES ('$title','$mat','$fullname', '$lga', '$phone', '$gender', '$email', '$school', '$type', '$reason', '$Institution', '$Leadership', now()) ");
                                 
                                  $payment=mysqli_query($con,"INSERT INTO studentpayment(studentid,section,programme,purpose,amount,modes,paymentreference,paytime,status,apptype)
		    			          VALUES ('$mat','$year','Pre-Tertiary School Leadership Summit','TSLS Program','$amount','online','$refe','$tday','success','TSLS') ");
    	}
    if($prog=='Personal Transcript'){
$register=mysqli_query($con,"INSERT INTO transapp (fullname, studentid, email, phone, fac, dep, school, status, year, type, date)
		    			         VALUES ('$fullname', '$mat', '$email', '$phone', '$faculty','$dep', '$progs','Submitted', '$year','$prog', now()) ");

    $payment=mysqli_query($con,"INSERT INTO studentpayment(studentid,section,programme,purpose,amount,modes,paymentreference,paytime,status,apptype)
		    			                  VALUES ('$mat','$year','$dep','$prog','$amount','online','$refe','$tday','success','Transcript') ");
}
elseif ($prog=='Official Transcript'){
$register=mysqli_query($con,"INSERT INTO transapp (fullname, studentid, email, phone, fac, dep, school, status, year,type,inst,orgemail,address,date)
		  VALUES ('$fullname', '$mat', '$email', '$phone','$faculty', '$dep', '$progs','Submitted', '$year','$copy','$inst','$orgemail','$address', now()) ");

    $payment=mysqli_query($con,"INSERT INTO studentpayment(studentid,section,programme,purpose,amount,modes,paymentreference,paytime,status,apptype)
		    			                  VALUES ('$mat','$year','$dep','$prog','$amount','online','$refe','$tday','success','$copy') ");
}elseif ($prog=='Certificate'){
$register=mysqli_query($con,"INSERT INTO transapp (fullname, studentid, email, phone, fac, dep, school, status, year, type, date)
		    			         VALUES ('$fullname', '$mat', '$email', '$phone', '$faculty','$dep', '$progs','Submitted', '$year','$prog', now()) ");

    $payment=mysqli_query($con,"INSERT INTO studentpayment(studentid,section,programme,purpose,amount,modes,paymentreference,paytime,status,apptype)
		    			                  VALUES ('$mat','$year','$dep','$prog','$amount','online','$refe','$tday','success','Transcript') ");
}
}
//$chanel=$tranx->data->channel;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlentities($orgname) ;?></title>
    <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="assets/css/receipt.css"/>
    <link href="assets/css/bootstrap.css" rel="stylesheet"/>
    <script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link href="assets/css/style.css" rel="stylesheet" />
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <style type="text/css" media="print">
        -webkit-print-color-adjust:exact !important;
        color-adjust:exact !important;
        @media print{
    .btn{
        display:none;
    } 
    footer{
        display:none;
    }
    }
    </style>

</head>
<body>
    <?php include('include/menubar.php');?>
    <div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="<?php echo htmlentities($logo) ;?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="max-width: 300px;margin: auto;">
            <h5><span style="font-size: 20px;"><?php echo htmlentities($orgname) ;?></span><br> <span style="margin-left: 35px;font-size: 16.5px;font-weight: bold;"><?php echo $prog?> Official Receipt.</span></h5>
        </div>
         <div class="row">
                <div class="col-12">
                 <div class="form-floating" align="center">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                               
                            </td>
                            
                            <td>
                                <!-- Invoice #: 123<br> -->
                                Created: <?php
                                    date_default_timezone_set("africa/lagos");
                                 echo date("D,d M Y ")."By ". date("h:i:sa")?><br>
                                <!-- Due: February 1, 2015 -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>
                    Personal Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Matri No/Applicant ID: <?php echo ($mat);?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Full Name: <?php echo htmlentities($fullname);?> 
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Phone: <?php echo ($phone) ; ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Email: <?php echo htmlentities($email);?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Purpose: <?php echo htmlentities($prog); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           
            <tr class="heading">
                <td>
                    Payment Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Amount Paid: &#8358; <?php echo htmlentities(number_format($amount,2)) ?>
                </td>
                
                <td>
                    
                </td>
            </tr>    
            <tr class="item">
                <td>
                    Payment Purpose: <?php echo htmlentities($prog) ?>
                </td>
                
                <td>
                    
                </td>
            </tr>     
            <tr class="item">
                <td>
                  Reference: <?php echo htmlentities($refe) ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                  Payment Status: <?php echo htmlentities($feedback) ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
             
        </table>
        <?php if ($feedback=='success'){ ?>
        <div class="info">
             Your <?php echo $prog ?> Application is successfull. The College will get in touch with you shortly. 
        </div>
        <?php } else{?>
        <div class="info">
             Sorry, Your Application is not successfull. 
        </div><?php }?>
       <div class="butn">
           <?php 
           if ($prog='TSLS Program') {?>
            <button type="button" onclick="window.open('printreceipts','_blank')" class="btn btn-info"> <i class="fa fa-print"></i> Print Receipt</button>
        <?php  } else {?>
                    <button type="button" onclick="window.open('printreceipt','_blank')" class="btn btn-info"> <i class="fa fa-print"></i> Print</button>
<?php }?>
        </div>
        </div>
        </div>
        </div>
       
        
        <br>
        <br>
        <!-- <input id="a" value="change me" /> --> 
        <div class="watermark" id="a" data-watermark="uat"></div>
       <div class="divFooter">Copyright &copy; <?php echo $orgname ?> <?php
                                    date_default_timezone_set("africa/lagos");
                                 echo date("Y-m-d h:i:sa")?></div>
    </div>
</body>


<?php include('include/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
</html>

