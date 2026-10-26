<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout.php"); 
    }
    else{
        $myCommand="SELECT * FROM faculty WHERE fid='".$datst['faculty']."'";
$sql = mysqli_query($con,$myCommand) or die (mysqli_error($con));
while($row=mysqli_fetch_array($sql)){
    $school=$row['fname'];
}
            
                if($school=='School of Technical Education'){$vol='Vol.01';}
                if($school=='School of Sciences'){$vol='Vol.03';}
                if($school=='Vocational and Technical Education'){$vol='Vol02';}
                if($school=='School of Business Education'){$vol='Vol02';}
        ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/receipt.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen">
        <link rel="stylesheet" href="css/print.css" media="print">
        <style type="text/css">
        .style1 {
            color: #000066;
            font-weight: bold;
        }
        .style2 {color: #000000}
        .style3 {color: #000066}
        .style4 {color: #000000; font-weight: bold; }
        .style5 { color:#06C}
        .style6 {
            font-size: 24px;
            color: #003366;text-align: justify;
        }
        .style7 {font-size: 18px;
            text-align: justify;
        }
        .style8 {font-size: 18px; color:#C00
        }
        body {
            background-image: url("logi1.png");
            background-repeat: no-repeat;
                 background-size: 520px 572px;
            background-position: center;
            
        }


    </style>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
              <?php include('includes/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">

                    <?php include('includes/leftbar.php');?>  

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                               <table width="785" border="0" align="center" >
  <tr>
    <td colspan="2"><img src="main1.png" width="865" height="118"></td>
  </tr>
  <tr>
    <td colspan="2"><h3 align="left" class="style1 style2">REGISTRAR: 
        <span class="style3">DR. ADESI AKPOEBI, Phd (IBADAN)</span></h3>
      <p align="left" class="style1 style2">&nbsp;</p>
      <p align="left" class="style2">OUR REF:<?php echo $vol?>/<?php echo $sect?>/NCE<?php //echo $cou_ref?><?php //echo $loca?><?php //echo $ra?></p>
      <p align="left" class="style4">NAME: <span class="style5"><?php echo mb_strtoupper($fullname) ?> </span></p>      
      <h2 align="center" class="style1">OFFER OF PROVISIONAL ADMISSION </h2>    </td>
  </tr>
  <tr>
    <td width="1122"><p class="style7"> I am pleased to inform you that you have been offered Provisional Admission to Edo State College of Education, Edo State, Nigeria to pursue a NIGERIA CERTIFICATE IN EDUCATION in <span class="style5"><?php echo mb_strtoupper  ($progr) ?></span>, </p>
      <p class="style7">School of <span class="style5"><?php echo mb_strtoupper  ($school) ?></span>, Department of <span class="style5"><?php echo mb_strtoupper  ($def) ?> </span>in the <span class="style8"><?php echo mb_strtoupper  ($sect) ?></span> Academic Session.</p>
      <p class="style7">This offer is conditional upon the confirmation of your qualification(s) as listed by you inthe application form </p>
      <p class="style7">During registration, you will be required to produce the original copies of your certificates or any other acceptable evidence of the qualification which this is based.</p>
      <p class="style7">If at any time after admission, it is discovered that you do not possess the requisite qualification at the time you got this admission, you will be required to withdraw from the College.You should complete the attached Acceptance Form in triplicate and indicate whether you accept or reject the offer. Two copies of the form should be forwarded to Registrar's Office, while the third copy should be retained by you and should be tendered on demand. </p>
      <p class="style7">Your offer of admission shall lapse if you failed to accept it within three weeks from the commencement of the session. You will be required to present at the time of Registration a letter of attestation from the Head of the last school or college you attended or from persons of reputable standing in the society who should vouch for your good conduct. (Note: testimonial is not enough)</p>
      <p class="style7"> Before commencement of Registration, you will be required to undergo medical examination which should be conducted in a Government Hospital. You are required to pay the necessary school fees after the clearance within two (2) weeks from the beginning of the academic session.</p>
      <p class="style7">Other instructions about facilities at the College would be obtained from the Registrar's Office. </p>
      <p>&nbsp;</p>
      <p class="style7"><strong>Accept our hearty congratulations</strong>    </p>
    <table width="802" height="119" border="0">
      <tr>
        <td width="665"><img src="sing.jpg" width="186" height="91"></td>
        <td width="122"><img src="seal.png" width="114" height="103"></td>
      </tr>
    </table>    
    <h1 class="style6">Registrar</h1></td>
    <td width="84"><div align="right"><img src="REGIS.png" width="74" height="744"></div></td>
  </tr>
</table>
<div>
    <input type="button" class="button" name="print" onclick="window.print()" value="Print-Detail">
</div>
                            </div>
                            <!-- /.row -->
                     
                        </div>
                       

                    </div>
                    <!-- /.main-page -->

                    
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->

        </div>
        <!-- /.main-wrapper -->

        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
        </script>
    </body>
</html>
<?php } ?>
