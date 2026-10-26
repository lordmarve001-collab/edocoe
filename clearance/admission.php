<?php
session_start();
$connection = TRUE;
require "pdf/fpdf.php";
include '../include/dbconfig.php';
//$harr=$_SESSION["staffid"];
$staffid=$_SESSION['staffid'];
$admy=$_SESSION['admy'];

//echo $prog;
include 'dataco.php';

  $_SESSION['uatCsrfToken'] = md5(uniqid());
  $_SESSION['uatCsrfSalt'] = md5(uniqid()); 
if(!isset($_SESSION['staffid']))
    {   
    header("Location: logout"); 
    }
    else{
$newfc=array();
$chp="SELECT * FROM labfefac";
            $stmtp = mysqli_query($con,$chp);
                $resulterp = mysqli_fetch_all($stmtp,MYSQLI_ASSOC);
           foreach($resulterp as $rww)
            {

                    $newfc[]=$rww['facid'];
            }

if (isset($_POST['approve'])) {
    $sel=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' "));
    if ($sel['uploadst']=='') {
        $up=mysqli_query($con,"UPDATE applicant SET uploadst='Pending' WHERE applid='".$_SESSION['staffid']."'");
    }else{
        header('refresh:0;');
    }
    
}
/* Matric No generator
$query = "SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."' ";
                             $result = mysqli_query($con,$query);
                             $data = mysqli_fetch_assoc($result);
							$userID = $data['studentid'];
							$dact=$data['department'];

if ($userID =='')
{

    $querys = "SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND (purpose='JCHEW Tuition Fee' OR purpose='ND Tuition Fee' OR purpose='CERT. Tuition Fee') ";
                             $result = mysqli_query($con,$querys);
                             $datas = mysqli_fetch_assoc($result);
							$sem=$datas['semester'];
							$progt=$datas['programme'];
							$level=$datas['level'];
					if ($querys>0){

		$mat = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM matrenge WHERE status= '1'"));
						$endmat = $mat['endmat'];
        $ids = $endmat + 1;
        $yrk=substr($admy,0,4);
        $yr=substr($yrk,-2);
        $tday=date('Y-m-d');
        $userIDs = $dact."".$yr."".str_pad($ids, 4, '0', STR_PAD_LEFT);
        $upd=mysqli_query($con,"UPDATE personaldata SET studentid='$userIDs' WHERE applid='".$_SESSION['staffid']."' ");
        $upds=mysqli_query($con,"UPDATE studlogin SET staffid='$userIDs', username='$userIDs' WHERE admid='".$_SESSION['staffid']."' ");
        $qu=mysqli_query($con,"UPDATE matrenge SET endmat='$ids' WHERE status='1' ");

                            if($sem!=''){
                                $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level)
                                VALUES('$userIDs','$admy','$sem','$progt' ,'','$level')");

                            }else{
                                
            $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$userIDs','$admy','First Semester','$progt' ,'','$level')");
            $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$userIDs','$admy','Second  Semester','$progt','','$level')");
                            }}}*/
                            
                            $query = "SELECT * FROM studlogin WHERE admid='".$_SESSION['staffid']."' ";
                             $result = mysqli_query($con,$query);
                             $data = mysqli_fetch_assoc($result);
							$userIDs = $data['staffid'];

if ($userIDs =='')
{
							
			$prd = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."' LIMIT 1"));
            $proc = $prd['programme'] ?? '';
            $pkl = $prd['phone'] ?? '';
            $pml = $prd['email'] ?? '';
            $fa = $prd['faculty'] ?? '';
            $de = $prd['department'] ?? '';
            $user = $prd['studentid'] ?? '';
            $last = $prd['surname'] ?? '';
            $first = $prd['firstname'] ?? '';

           $hashedPW = hash('sha256', $pkl);
           $tday = date('Y-m-d');

 $qu = mysqli_query($con, "INSERT INTO studlogin(staffid, status, admid, firstname, lastname, password, username, position, programme, acadsession, phone, email, dateinsert) 
                VALUES ('$user', 'active', '".$_SESSION['staffid']."', '$first', '$last', '$hashedPW', '$user', 'student', '$proc', '$admy', '$pkl', '$pml', '$tday')");
               
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
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/receipt.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=Generator content="Microsoft Word 15 (filtered)">
<style type="text/css">
    p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
    {margin-top:0cm;
    margin-right:0cm;
    margin-bottom:8.0pt;
    margin-left:36.0pt;
    line-height:107%;
    font-size:12.0pt;
    font-family:"Times New Roman",serif;}
p.MsoListParagraphCxSpFirst, li.MsoListParagraphCxSpFirst, div.MsoListParagraphCxSpFirst
    {margin-top:0cm;
    margin-right:0cm;
    margin-bottom:0cm;
    margin-left:36.0pt;
    margin-bottom:.0001pt;
    line-height:107%;
    font-size:12.0pt;
    font-family:"Times New Roman",serif;}
p.MsoListParagraphCxSpMiddle, li.MsoListParagraphCxSpMiddle, div.MsoListParagraphCxSpMiddle
    {margin-top:0cm;
    margin-right:0cm;
    margin-bottom:0cm;
    margin-left:36.0pt;
    margin-bottom:.0001pt;
    line-height:107%;
    font-size:12.0pt;
    font-family:"Times New Roman",serif;}
p.MsoListParagraphCxSpLast, li.MsoListParagraphCxSpLast, div.MsoListParagraphCxSpLast
    {margin-top:0cm;
    margin-right:0cm;
    margin-bottom:8.0pt;
    margin-left:36.0pt;
    line-height:107%;
    font-size:12.0pt;
    font-family:"Times New Roman",serif;}
    p.MsoNormal, li.MsoNormal, div.MsoNormal
    {margin-top:0cm;
    margin-right:0cm;
    margin-bottom:8.0pt;
    margin-left:0cm;
    line-height:107%;
    font-size:12.0pt;
    font-family:"Times New Roman",serif;}

    /*Printing Tag*/
    @media print {
        *{
    /*padding: 0;
    margin:0;*/
    /*background: transparent !important;
    color: #000 !important;
    box-shadow: none !important;
    text-shadow: none !important;*/
}
  div.divFooter {
    position: fixed;
    bottom: 0;
  }
  .button{
        display: none;
    }
.oat{
    page-break-before: always;
}
.invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        /*border: 1px solid #eee;*/
        /*box-shadow: 0 0 10px rgba(0, 0, 0, .15);*/
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .invoice-box .tables {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box .tables td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box .tables tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box .tables tr.top .tables td {
        padding-bottom: 20px;
    }
    
    .invoice-box .tables tr.top .tables td.title {
        font-size: 45px;
        line-height: 15px;
        color: #333;
    }
    
    .invoice-box .tables tr.information .tables td {
        padding-bottom: 40px;
    }
    
    .invoice-box .tables tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box .tables tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box .tables tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box .tables tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box .tables tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    .left-sidebar{
        display: none;
    }
    .top-navbar{
        display: none;
    }
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
                               <div>
                                     <h2 class="title">Clearance Page</h2>
                                </div>
                               
                        </div>
                       

                    </div>
                    <!-- /.main-page -->

                    <section class="section">
                            <div class="container-fluid">                          

                                <div class="row">
                                <div class="col-md-8 col-md-offset-2">    
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5></h5>
                                                </div>
                                            </div>
          
  
                                            <div class="panel-body">
                                            <?php $docut=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE purpose='Acceptance Fee' AND studentid='".$_SESSION['staffid']."' "));
                                                if ($docut=='') {
                                        
                                            ?>
                        
                            <div class="invoice-box">
                                <span style="color:darkred">Dear: <?php echo htmlentities($fullname) ?> you are advice to Pay Acceptance Fee before you can upload your clearance documents kindly click on this link <a href="paymentgrad" style="color: blue;"> make payment</a>

                                </span>
                                
                            </div>
                    <?php
                    }else{

						$docu=mysqli_query($con,"SELECT * FROM cleardoc WHERE applid='".$_SESSION['staffid']."' ");
                            $dad = mysqli_num_rows($docu);
                            if ($dad==0)
                            {
                                ?>
                                <div class="invoice-box">
                                <span style="color:darkred">Dear: <?php echo htmlentities($fullname) ?> you have not upload clearance documents  kindly click on this link <a href="applidocupl" style="color: blue;"> to upload</a>

                                </span>
                                
                            </div>
                                <?php
                            }else{

						$docu=mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' AND uploadst='Reset' ");
                            $dad = mysqli_num_rows($docu);
                            if ($dad==1)
                            {
                                ?>
                                <div class="invoice-box">
                                <span style="color:darkred">Dear: <?php echo htmlentities($fullname) ?> you have been ask to revisit the uploaded documents and make correction.  kindly click on this link <a href="applidocupl" style="color: blue;"> Click Me</a>

                                </span>
                                
                            </div>
                                <?php
                            }
                            
                            else{

                            /*submission of documents*/
                            $docc=mysqli_num_rows(mysqli_query($con,"SELECT * FROM cleardoc WHERE applid='".$_SESSION['staffid']."'"));
                                if($docc<6){
                                    ?>
                                     <div class="invoice-box">
                                <span style="color:darkred">Dear: <?php echo htmlentities($fullname) ?> you have not complete your upload clearance documents upload kindly click on this link <a href="applidocupl" style="color: blue;"> to upload</a>

                                </span>
                                
                            </div>
                                    <?php
                                }else{

                            $selp=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' "));
                            if ($selp['uploadst']=='') {
                                
                              ?>
                              <form method="POST" action="">
                                <div><h1>Clearance Documents</h1></div>
                              <table class="table table-striped table-bordered " cellspacing="0" width="100%">
                                                    <thead>
                                                       
                                                        <tr>
                                                           
                                                            <th>Document Type</th>
                                                            
                                                            <th>Image</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
                                                    <?php
                                                      $sql = "SELECT * FROM cleardoc WHERE applid='".$_SESSION['staffid']."' ORDER BY document";


                                                    $query = mysqli_query($con,$sql);
                                                    $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                                    $cnt=1;

                                                    foreach($results as $result)
                                                    {   ?>
                                                    <tr>
                                                        <?php if ($result['documtype']=='1') {
        ?>
    <td><?php echo htmlentities('JAMB Slip');?></td>
   <?php }elseif($result['documtype']=='2'){ ?>
   <td><?php echo htmlentities('Birth/Age Declaration');?></td>
   <?php }elseif($result['documtype']=='3'){ ?>
   <td><?php echo htmlentities('Olevel Result first sitting');?></td>
   <?php }elseif($result['documtype']=='4'){ ?>
   <td><?php echo htmlentities('Olevel Result Second sitting');?></td>
    <?php }elseif($result['documtype']=='5'){ ?>
   <td><?php echo htmlentities('Affidavit of Good Conduct');?></td>
    <?php }elseif($result['documtype']=='6'){ ?>
   <td><?php echo htmlentities('Guarantors Letter');?></td>
    <?php }elseif($result['documtype']=='8'){ ?>
   <td><?php echo htmlentities('JCHEW Licence');?></td>
    <?php }elseif($result['documtype']=='9'){ ?>
   <td><?php echo htmlentities('JCHEW Certificate');?></td>
                                                       <?php } if($result['dis']=='1'){ ?>
    
    <td>
        <img data-u='image' src="../apply/<?php echo htmlentities($result['document']);?>"  class='img-fluid'  style="width:300px;height:300px;"> </td><?php }else{?> 
    <td>
        <img data-u='image' src="<?php echo htmlentities($result['document']);?>"  class='img-fluid'  style="width:300px;height:300px;"> </td><?php }?> 
                                                        
                                                    </tr>
                                                    <?php 

                                                    $cnt=$cnt+1;
                                                    }?>               
                                                    
                                                    </tbody>
                                                </table> 
                                                <div class="modal-footer">
                         
                        <button class="btn btn-primary" type="submit" name="approve">Confirm Detail <i class="fa fa-check"></i></button>
                        </div>
                        </form>   

                              <?php
                          }elseif($selp['uploadst']=='Pending'){
                            ?>
                            <div class="invoice-box">
                                <span style="color:darkred">Dear: <?php echo htmlentities($fullname) ?> the documents you upload is currently under review. check back later for confirmation.

                                </span>
                                
                            </div>
                            <?php
                          }else{
                            /*Querying Tuition Fee Payment*/
                        $docut=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND (purpose='ND Tuition Fee' OR purpose='JCHEW Tuition Fee' OR purpose='CERT. Tuition Fee')AND programme='$pro' "));
                        if ($docut=='') {
                            ?>
                            <div class="invoice-box">
                                <table cellpadding="0" cellspacing="0" class="tables">
                    
           
            
            <tr class="heading">
                <td>
                    Personal Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Application ID: <?php echo htmlentities( $userid);?>
                </td>
                
                <td rowspan="4">
                    <img src="../apply/<?php echo "$passport "; ?>" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;">
                </td>
            </tr>
            <tr class="item">
                <td style="text-transform: capitalize;">
                    Full Name: <?php echo htmlentities($surname." ".$firstname." ".$lastname); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Phone: <?php echo htmlentities($phone); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Email: <span  style="text-transform: lowercase;"><?php echo htmlentities($email);?></span>
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    D-O-B: <?php echo htmlentities($dop); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Address: <?php echo htmlentities($address); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Marital: <?php echo htmlentities($mart); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Gender: <?php echo htmlentities($sex); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Nationality: <?php echo htmlentities($nal); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    State/Country: <?php echo htmlentities($state); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    L.G.A: <?php echo htmlentities($lga); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           <tr class="heading">
                <td>
                    Programme Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Faculty: <?php echo htmlentities($facu); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Department: <?php echo htmlentities($def); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Programme: <?php echo htmlentities($pro); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           
        </table>
        <div style="margin:auto;width: 200px;" class="button"><input type="button" value="Print-Details" onclick="window.open('printdetail','_blank')" style="float: left;font-size: 20px;display: non;"><br>
        </div>
       <!--<div style="margin:auto;width: 200px;" class="button"><input type="button" value="Print Admission Letter" onclick="window.open('admission_letter','_blank')" style="float: left;font-size: 20px;display: non;"><br>
        </div>-->
        </br>
                               <span style="color:darkred">Dear: <?php echo htmlentities($fullname) ?> you are advice to Pay Schools Fee before you can print out your documents kindly click on this link <a href="payfees" style="color: blue;"> make payment</a>

                                </span>
                                
                            </div>
                            <?php

                        }else{
                            if (in_array($data['faculty'],$newfc))
                            {
                                
                                $labc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='$matr' AND purpose='Laboratory Fee' "));
                                    if ($labc=='') 
                                        {
                                        ?>
                                        <div class="invoice-box">
                                            <span style="color:darkred">Dear: <?php echo htmlentities($fullname) ?> you are advice to Pay <b style="color:blue;">Laboratory Fee</b> before you can print out your documents kindly click on this link <a href="labfeepay" style="color: blue;"> make payment</a>

                                            </span>
                                            
                                        </div>
                                        <?php
                                        }else{
                                            ?>
                                            <div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="<?php echo htmlentities($logo) ;?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="width: 300px;margin: auto;">
            <h5><span style="font-size: 20px;"><?php echo htmlentities($orgname) ;?></span><br> <span style="margin-left: 60px;font-size: 16.5px;font-weight: bold;">Admission Letter.</span></h5>
        </div>
        <table cellpadding="0" cellspacing="0" class="tables">
                    
           
            
            <tr class="heading">
                <td>
                    Personal Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Application ID: <?php echo htmlentities( $userid);?>
                </td>
                
                <td rowspan="4">
                    <img src="../apply/<?php echo "$passport "; ?>" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;">
                </td>
            </tr>
            <tr class="item">
                <td style="text-transform: capitalize;">
                    Full Name: <?php echo htmlentities($surname." ".$firstname." ".$lastname); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Phone: <?php echo htmlentities($phone); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Email: <span  style="text-transform: lowercase;"><?php echo htmlentities($email);?></span>
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    D-O-B: <?php echo htmlentities($dop); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Address: <?php echo htmlentities($address); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Marital: <?php echo htmlentities($mart); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Gender: <?php echo htmlentities($sex); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Nationality: <?php echo htmlentities($nal); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    State/Country: <?php echo htmlentities($state); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    L.G.A: <?php echo htmlentities($lga); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           <tr class="heading">
                <td>
                    Programme Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Matric No: <?php echo htmlentities($matr); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Faculty: <?php echo htmlentities($facu); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Department: <?php echo htmlentities($def); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Programme: <?php echo htmlentities($pro); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           
        </table>
        <div style="margin:auto;width: 200px;" class="button"><input type="button" value="Print-Detail" onclick="window.open('printdetail','_blank')" style="float: left;font-size: 20px;display: non;">
        </div> 
    </div>
                                            <?php
                                        }
                            }else{
                                ?>
                                <div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="<?php echo htmlentities($logo) ;?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="width: 300px;margin: auto;">
            <h5><span style="font-size: 20px;"><?php echo htmlentities($orgname) ;?></span><br> <span style="margin-left: 60px;font-size: 16.5px;font-weight: bold;">Admission Letter.</span></h5>
        </div>
        <table cellpadding="0" cellspacing="0" class="tables">
                    
           
            
            <tr class="heading">
                <td>
                    Personal Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Application ID: <?php echo htmlentities( $userid);?>
                </td>
                
                <td rowspan="4">
                    <img src="../apply/<?php echo "$passport "; ?>" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;">
                </td>
            </tr>
            <tr class="item">
                <td style="text-transform: capitalize;">
                    Full Name: <?php echo htmlentities($surname." ".$firstname." ".$lastname); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Phone: <?php echo htmlentities($phone); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Email: <span  style="text-transform: lowercase;"><?php echo htmlentities($email);?></span>
                </td>
                
                <td>
                    
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    D-O-B: <?php echo htmlentities($dop); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Address: <?php echo htmlentities($address); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Marital: <?php echo htmlentities($mart); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Gender: <?php echo htmlentities($sex); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Nationality: <?php echo htmlentities($nal); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    State/Country: <?php echo htmlentities($state); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    L.G.A: <?php echo htmlentities($lga); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           <tr class="heading">
                <td>
                    Programme Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Matric No: <?php echo htmlentities($matr); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Faculty: <?php echo htmlentities($facu); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Department: <?php echo htmlentities($def); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr class="item">
                <td>
                    Programme: <?php echo htmlentities($pro); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           
        </table> 
        <!--<button class="btn btn-danger" onclick="window.open('admission_letter','_blank')">Print Admission letter  <i class="fa fa-download"></i></button>-->
        <button class="btn btn-primary" onclick="window.open('printdetail','_blank')">Print Detail <i class="fa fa-print"></i></button>
                        <button class="btn btn-success" onclick="window.open('matric_no','_blank')">Print Matric No  <i class="fa fa-download"></i></button>
    </div>
                                <?php
                              }
                             }
                            }
                          }
                        }
                    }}
                    ?>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
                                  
        <!-- /.main-wrapper -->

        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
        
    </body>
</html>
<?php 
} 
?>
