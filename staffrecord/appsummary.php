<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
include 'apc.php';

if (!isset($_SESSION['staffid']))
    header("Location:logout");
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
}

if (isset($_POST['approve'])) {
    $sel=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' "));
    if ($sel['status']=='submitted') {
        $up=mysqli_query($con,"UPDATE applicant SET status='Pending' WHERE applid='".$_SESSION['staffid']."'");
    }else{
        header('refresh:0;');
    }
    
}
if (isset($_POST['rapprove'])) {
    $sel=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' "));
     if ($sel['status']=='rejected') {
    $up=mysqli_query($con,"UPDATE applicant SET status='Pending' WHERE applid='".$_SESSION['staffid']."'");
   }else{
        header('refresh:0;');
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
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/receipt.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=Generator content="Microsoft Word 15 (filtered)">
<style type="text/css">
    
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
        line-height: 20px;
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
              <?php include('include/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">

                    <?php
                        $stc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' "));
                    if ($stc['status']=='submitted') {
                        include('include/leftbar.php');
                        ?>
                     <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                            <div>
                                    <h2 class="title">Application Review(Awaiting Submission)</h2>
                                
                            </div>  
            <div class="modalt" id="viewModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <form method="post">
                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_details">
                            <?php

        $sql = "SELECT * FROM applicant JOIN  personaldata ON personaldata.applid=applicant.applid WHERE applicant.applid='".$_SESSION['staffid']."' AND personaldata.applid='".$_SESSION['staffid']."'";
        $query = mysqli_query($con,$sql);
        $result = mysqli_fetch_all($query,MYSQLI_ASSOC);
            
            foreach($result as $row)
            {
                
                ?>
                <div class="row">
                <div class="col-md-3">
                    <img src="<?php echo $row['passport'];?>" class="img-thumbnail" style="width: 300px;height:200px;" />
                </div>
                <div class="col-md-9">
                    <table class="table">
                        <tr class="heading">
                <td>
                    Personal Data
                </td>
                
                <td>
                    
                </td>
            </tr>
                        <tr>
                            <th>Full-Name</th>
                            <td style="text-transform:capitalize;font-weight: bolder;"> <?php  echo htmlentities($row['surname']." ".$row['firstname']." ".$row['lastname'])?></td>
                        </tr>
                        <tr>
                            <th>Admission ID</th>
                            <td><?php echo htmlentities($row["applid"])?></td>
                        </tr>
                       
                        <tr>
                            <th>Email</th>
                            <td><a href="mailto:<?php echo $row['email']?>"><?php echo htmlentities($row["email"])?></a></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><a href="tel:<?php echo $row['phone']?>"><?php echo htmlentities($row["phone"])?></a></td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td><?php echo htmlentities($row["gender"])?></td>
                        </tr>
                        <tr>
                            <th>Marital</th>
                            <td><?php echo htmlentities($row["marital"])?></td>
                        </tr>
                        <tr>
                            <th>D-O-B</th>
                            <td><?php echo htmlentities($row["dob"])?></td>
                        </tr>
                        <tr>
                            <th>Nationality</th>
                            <td>
                                <?php 
                            echo htmlentities($row['nationality']);
                            ?></td>
                        </tr>
                        <?php if($row['nationality']=="Foreign"){ ?>
                            <tr>
                            <th>Country</th>
                            <td><?php echo htmlentities($row["state"])?></td>
                        </tr>
                           <?php 
                            }
                            else{
                               ?>
                               <tr>
                            <th>State</th>
                            <td><?php echo htmlentities($row["state"])?></td>
                        </tr>
                        <tr>
                            <th>L.G.A</th>
                            <td><?php echo htmlentities($row["lga"])?></td>
                        </tr>
                               <?php
                            }
                            ?>
                        
                        
                        <tr>
                            <th>Home Address</th>
                            <td><?php echo htmlentities($row["address"])?></td>
                        </tr>
                        
                        <tr class="heading">
                <td>
                    Jamb Information
                </td>
                
                <td>
                    
                </td>
            </tr>
 <tr>
                            <th>Jamb Registration No</th>
                            <td><?php echo htmlentities($row["jamb_no"])?></td>
                        </tr> <tr>
                            <th>Jamb Score</th>
                            <td><?php echo htmlentities($row["jamb_score"])?></td>
                        </tr> <td>
                    
                </td>              
                        <tr class="heading">
                <td>
                    Admission Information
                </td>
                
                <td>
                    
                </td>
            </tr>
                        <tr>
                            <th>Academic Session</th>
                            <td><?php echo htmlentities($row["session"])?></td>
                        </tr>
                        <tr>
                            <th>Faculty</th>
                            <td><?php
                               echo htmlentities($facu); 
                            
                            ?></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td><?php
                               echo htmlentities($def); 
                            
                            ?></td>
                        </tr>
                        <tr>
                            <th>Programme</th>
                            <td><?php
                               echo htmlentities($row['programme']); 
                            
                            ?></td>
                        </tr>
                        
                        
                    </table>
                
    <?php
    
        } 
        //loading Primary School Details Of Applicants
        $data=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM flscdetail WHERE applid='".$_SESSION['staffid']."'"));

    $schn = $data['schoolname'];
    $deg = $data['degree'];
    $yrs = $data['yearadmit'];
    $yag = $data['yeargrad'];
        ?> 
    <table class="table">
            <tr class="heading">
                <td>
                    Primary School Information
                </td>
                
                <td>
                   
                </td>
            </tr>
            <tr>
            <th>School Name</th>
            <td><?php echo htmlentities($schn)?></td>
            </tr>
            <tr>
                <th>Certificate</th>
                <td><?php echo htmlentities($deg)?></td>
            </tr>
            <tr>
                <th>From</th><th>To</th></tr>
                <tr><td><?php echo htmlentities($yrs)?></td>

                <td><?php echo htmlentities($yag)?></td>
            </tr>
        </table>  
        <table class="table">
            <tr class="heading">
                <td>
                    Secondary/ College School Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <th>First Sitting</th>
                        </tr>
                        <?php 
            /*Loading Secondary School Record*/
            $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE  applid='".$_SESSION['staffid']."' AND noofsit='1'"));
            if ($dat!="") {
                
            
        ?>
                    <tr>
            <th>School Name</th>
            <td><?php echo htmlentities($dat['school'])?></td>
            </tr>
            <tr>
                <th>Exam Body</th>
                <td><?php echo htmlentities($dat['exambody'])?></td>
            </tr>
            <tr>
                <th>Exam No</th>
                <td><?php echo htmlentities($dat['examno'])?></td>
            </tr>
            <tr>
                <th>Exam Year</th>
                <td><?php echo htmlentities($dat['examyear'])?></td>
            </tr>
            <tr>
                <th>From</th><th>To</th></tr>
                <tr><td><?php echo htmlentities($dat['yearadmit'])?></td>
                
                <td><?php echo htmlentities($dat['yeargrad'])?></td>
            </tr>

            <tr>
                <!-- <th>#</th> -->
                <th>Subject</th>
                <th>Grade</th>
            </tr>
            <?php 
                $sql = "SELECT * FROM olevelresultdetail WHERE applid='".$_SESSION['staffid']."' AND examno='".$dat['examno']."' ORDER BY subject";

                $query=mysqli_query($con,$sql);
                $results=mysqli_fetch_all($query,MYSQLI_ASSOC);
                $cnt=1;
                foreach($results as $result)
                {   ?>
            <tr>
                <!-- <td><?php echo htmlentities($cnt);?></td> -->
                <td><?php echo htmlentities($result['subject']);?></td>
                <td><?php echo htmlentities($result['grade']);?></td>
            </tr>
            <?php 
            $cnt=$cnt+1;
            }} ?>
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                            <th>Second Sitting</th>
                        </tr>
                        <?php 
            /*Loading Secondary School Record*/
            $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='".$_SESSION['staffid']."' AND noofsit='2'"));
            if ($dat!="") {
                
            
        ?>
                    <tr>
            <th>School Name</th>
            <td><?php echo htmlentities($dat['school'])?></td>
            </tr>
            <tr>
                <th>Exam Body</th>
                <td><?php echo htmlentities($dat['exambody'])?></td>
            </tr>
            <tr>
                <th>Exam No</th>
                <td><?php echo htmlentities($dat['examno'])?></td>
            </tr>
            <tr>
                <th>Exam Year</th>
                <td><?php echo htmlentities($dat['examyear'])?></td>
            </tr>
            <tr>
                <th>From</th><th>To</th></tr>
                <tr><td><?php echo htmlentities($dat['yearadmit'])?></td>
                
                <td><?php echo htmlentities($dat['yeargrad'])?></td>
            </tr>

            <tr>
                <!-- <th>#</th> -->
                <th>Subject</th>
                <th>Grade</th>
            </tr>
            <?php 
                $sql = "SELECT * FROM olevelresultdetail WHERE applid='".$_SESSION['staffid']."' AND examno='".$dat['examno']."' ORDER BY subject";

                $query=mysqli_query($con,$sql);
                $results=mysqli_fetch_all($query,MYSQLI_ASSOC);
                $cnt=1;
                foreach($results as $result)
                {   ?>
            <tr>
                <!-- <td><?php echo htmlentities($cnt);?></td> -->
                <td><?php echo htmlentities($result['subject']);?></td>
                <td><?php echo htmlentities($result['grade']);?></td>
            </tr>
            <?php 
            $cnt=$cnt+1;
            }} ?>
                    </table>
                </td>
            </tr>

         <table class="table">
            <!-- parentdetail -->
            <tr class="heading">
                <td>
                    Parent/Guardian Details
                </td>
                <td></td>
            </tr>
            <?php
            $dap=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='".$_SESSION['staffid']."'"));
            ?>
            <tr>
                <th>Name</th>
                <td><?php echo htmlentities($dap["title"])?> <?php echo htmlentities($dap['parentname']) ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlentities($dap["address"])?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo htmlentities($dap["phone"])?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlentities($dap["email"])?></td>
            </tr>
            <tr>
                <th>Relation</th>
                <td><?php echo htmlentities($dap["relat"])?></td>
            </tr>
            <tr>
                <th>Occupation</th>
                <td><?php echo htmlentities($dap["occupation"])?></td>
            </tr>
            
            <tr>
                <th>Religion</th>
                <td><?php echo htmlentities($dap["relig"])?></td>
            </tr>
            
        </table>  
       
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                         
                        <button class="btn btn-primary" type="submit" name="approve">Confirm Detail <i class="fa fa-check"></i></button>
                        
                    </form>
                                
                        </div>
                    </div>
                </div>
            </div> 
                        <?php
                    } 
                    elseif ($stc['status']=='Pending') { 
                        include('include/leftbar.php');
                        ?>
                   <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                            <div>
                                    <h2 class="title">Application Summary(Awaiting Data Confirmation)</h2>
                                
                            </div>
                            <div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="<?php echo htmlentities($logo) ;?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="width: 450px;margin: auto;">
            <h5><span style="font-size: 17px;font-weight: bold;"><?php echo htmlentities($orgname." ".$schadress) ;?></span><br> <span style="margin-left: 40px;font-size: 16.5px;font-weight: bold;">Application Information.</span></h5>
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
                    Application ID: <?php echo htmlentities($userid);?>
                </td>
                
                <td rowspan="4">
                    <img src="<?php echo "$passport "; ?>" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;">
                </td>
            </tr>
            <tr class="item">
                <td>
                    Full Name: <?php echo htmlentities($fullname); ?>
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
           
            <tr class="item">
                <td>
                    Academic Session: <?php echo htmlentities($set); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           
        </table>
        <button class="btn btn-success" style="margin:auto;width: 200px;float: center;" onclick="window.open('printdetail','_blank')"> <i class="fa fa-print"> Click to Print Out Document</i></button> 
                <?php }elseif($stc['status']=='Approved'){ 

                   
                        include('include/leftbar.php');
                        ?>
                       
                        <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                               <div>
                                    <h2 class="title">Application Summary(Data Confirmed)</h2>
                                
                            </div>
    <div class="invoice-box">
        <div style="width: 150px;margin: auto;">
            <img src="<?php echo htmlentities($logo) ;?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="width: 360px;margin: auto;">
            <h5><span style="font-size: 17px;font-weight: bold;"><?php echo htmlentities($orgname." ".$schadress) ;?></span><br> <span style="margin-left: 40px;font-size: 16.5px;font-weight: bold;">Application Information.</span></h5>
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
                    <img src="<?php echo "$passport "; ?>" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;">
                </td>
            </tr>
            <tr class="item">
                <td>
                    Full Name: <?php echo htmlentities($fullname); ?>
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
            
            <tr class="item">
                <td>
                    Academic Session: <?php echo htmlentities($set); ?>
                </td>
                
                <td>
                    
                </td>
            </tr>
           
        </table>
    
     <button class="btn btn-success" style="margin:auto;width: 200px;float: center;" onclick="window.open('printdetail','_blank')"> <i class="fa fa-print"> Click to Print Out Document</i></button> 
    </div>   <?php
                    
                     }else { 
                        include('include/leftbar.php');
                ?>
        <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                               <div>
                                    <h2 class="title">Application Summary(Data Rejected)</h2>
                                
                            </div>
                            <div class="invoice-box">
                                <span style="color:darkred">Dear: <?php echo htmlentities($fullname) ?> 
                                </span>
                                <div>
                                Reason for rejection: <?php
                                $rd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM rejectedam WHERE applid='".$_SESSION['staffid']."'"));
                                ?>
                                <span style="color:red;"><?php echo htmlentities($rd['reason']); ?></span>

                            </div>
                        </div>
                            <div class="modalt" id="viewModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <form method="post">
                        <!-- Modal body -->
                        <div class="modal-body" id="teacher_details">
                            <?php

        $sql = "SELECT * FROM applicant JOIN  personaldata ON personaldata.applid=applicant.applid WHERE applicant.applid='".$_SESSION['staffid']."' AND personaldata.applid='".$_SESSION['staffid']."'";
        $query = mysqli_query($con,$sql);
        $result = mysqli_fetch_all($query,MYSQLI_ASSOC);
            
            foreach($result as $row)
            {
                
                ?>
                <div class="row">
                <div class="col-md-3">
                    <img src="<?php echo $row['passport'];?>" class="img-thumbnail" style="width: 300px;height:200px;" />
                </div>
                <div class="col-md-9">
                    <table class="table">
                        <tr class="heading">
                <td>
                    Personal Data
                </td>
                
                <td>
                    
                </td>
            </tr>
                        <tr>
                            <th>Full-Name</th>
                            <td style="text-transform:capitalize;font-weight: bolder;"> <?php  echo htmlentities($row['surname']." ".$row['firstname']." ".$row['lastname'])?></td>
                        </tr>
                        <tr>
                            <th>Admission ID</th>
                            <td><?php echo htmlentities($row["applid"])?></td>
                        </tr>
                       
                        <tr>
                            <th>Email</th>
                            <td><a href="mailto:<?php echo $row['email']?>"><?php echo htmlentities($row["email"])?></a></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><a href="tel:<?php echo $row['phone']?>"><?php echo htmlentities($row["phone"])?></a></td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td><?php echo htmlentities($row["gender"])?></td>
                        </tr>
                        <tr>
                            <th>Marital</th>
                            <td><?php echo htmlentities($row["marital"])?></td>
                        </tr>
                        <tr>
                            <th>D-O-B</th>
                            <td><?php echo htmlentities($row["dob"])?></td>
                        </tr>
                        <tr>
                            <th>Nationality</th>
                            <td>
                                <?php 
                            echo htmlentities($row['nationality']);
                            ?></td>
                        </tr>
                        <?php if($row['nationality']=="Foreign"){ ?>
                            <tr>
                            <th>Country</th>
                            <td><?php echo htmlentities($row["state"])?></td>
                        </tr>
                           <?php 
                            }
                            else{
                               ?>
                               <tr>
                            <th>State</th>
                            <td><?php echo htmlentities($row["state"])?></td>
                        </tr>
                        <tr>
                            <th>L.G.A</th>
                            <td><?php echo htmlentities($row["lga"])?></td>
                        </tr>
                               <?php
                            }
                            ?>
                        
                        
                        <tr>
                            <th>Home Address</th>
                            <td><?php echo htmlentities($row["address"])?></td>
                        </tr>
                        <tr>
                            <th>Postal Address</th>
                            <td><?php echo htmlentities($row["caddress"])?></td>
                        </tr>
                        <tr>
                            <th>Home Town</th>
                            <td><?php echo htmlentities($row["town"])?></td>
                        </tr>
                        
                        <tr class="heading">
                <td>
                    Admission Information
                </td>
                
                <td>
                    
                </td>
            </tr>
                        <tr>
                            <th>Academic Session</th>
                            <td><?php echo htmlentities($row["session"])?></td>
                        </tr>
                        <tr>
                            <th>Faculty</th>
                            <td><?php
                               echo htmlentities($facu); 
                            
                            ?></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td><?php
                               echo htmlentities($def); 
                            
                            ?></td>
                        </tr>
                        <tr>
                            <th>Programme</th>
                            <td><?php
                               echo htmlentities($row['programme']); 
                            
                            ?></td>
                        </tr>
                        
                        
                    </table>
                
    <?php
    $stat=$row["entrysta"];
        } 
        //loading Primary School Details Of Applicants
        $data=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM flscdetail WHERE applid='".$_SESSION['staffid']."'"));

    $schn = $data['schoolname'];
    $deg = $data['degree'];
    $yrs = $data['yearadmit'];
    $yag = $data['yeargrad'];
        ?> 
    <table class="table">
            <tr class="heading">
                <td>
                    Primary School Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr>
            <th>School Name</th>
            <td><?php echo htmlentities($schn)?></td>
            </tr>
            <tr>
                <th>Certificate</th>
                <td><?php echo htmlentities($deg)?></td>
            </tr>
            <tr>
                <th>From</th><th>To</th></tr>
                <tr><td><?php echo htmlentities($yrs)?></td>

                <td><?php echo htmlentities($yag)?></td>
            </tr>
        </table>  
        <table class="table">
            <tr class="heading">
                <td>
                    Secondary/ College School Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <th>First Sitting</th>
                        </tr>
                        <?php 
            /*Loading Secondary School Record*/
            $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE  applid='".$_SESSION['staffid']."' AND noofsit='1'"));
            if ($dat!="") {
                
            
        ?>
                    <tr>
            <th>School Name</th>
            <td><?php echo htmlentities($dat['school'])?></td>
            </tr>
            <tr>
                <th>Exam Body</th>
                <td><?php echo htmlentities($dat['exambody'])?></td>
            </tr>
            <tr>
                <th>Exam No</th>
                <td><?php echo htmlentities($dat['examno'])?></td>
            </tr>
            <tr>
                <th>Exam Year</th>
                <td><?php echo htmlentities($dat['examyear'])?></td>
            </tr>
            <tr>
                <th>From</th><th>To</th></tr>
                <tr><td><?php echo htmlentities($dat['yearadmit'])?></td>
                
                <td><?php echo htmlentities($dat['yeargrad'])?></td>
            </tr>

            <tr>
                <!-- <th>#</th> -->
                <th>Subject</th>
                <th>Grade</th>
            </tr>
            <?php 
                $sql = "SELECT * FROM olevelresultdetail WHERE applid='".$_SESSION['staffid']."' AND examno='".$dat['examno']."' ORDER BY subject";

                $query=mysqli_query($con,$sql);
                $results=mysqli_fetch_all($query,MYSQLI_ASSOC);
                $cnt=1;
                foreach($results as $result)
                {   ?>
            <tr>
                <!-- <td><?php echo htmlentities($cnt);?></td> -->
                <td><?php echo htmlentities($result['subject']);?></td>
                <td><?php echo htmlentities($result['grade']);?></td>
            </tr>
            <?php 
            $cnt=$cnt+1;
            }} ?>
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                            <th>Second Sitting</th>
                        </tr>
                        <?php 
            /*Loading Secondary School Record*/
            $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='".$_SESSION['staffid']."' AND noofsit='2'"));
            if ($dat!="") {
                
            
        ?>
                    <tr>
            <th>School Name</th>
            <td><?php echo htmlentities($dat['school'])?></td>
            </tr>
            <tr>
                <th>Exam Body</th>
                <td><?php echo htmlentities($dat['exambody'])?></td>
            </tr>
            <tr>
                <th>Exam No</th>
                <td><?php echo htmlentities($dat['examno'])?></td>
            </tr>
            <tr>
                <th>Exam Year</th>
                <td><?php echo htmlentities($dat['examyear'])?></td>
            </tr>
            <tr>
                <th>From</th><th>To</th></tr>
                <tr><td><?php echo htmlentities($dat['yearadmit'])?></td>
                
                <td><?php echo htmlentities($dat['yeargrad'])?></td>
            </tr>

            <tr>
                <!-- <th>#</th> -->
                <th>Subject</th>
                <th>Grade</th>
            </tr>
            <?php 
                $sql = "SELECT * FROM olevelresultdetail WHERE applid='".$_SESSION['staffid']."' AND examno='".$dat['examno']."' ORDER BY subject";

                $query=mysqli_query($con,$sql);
                $results=mysqli_fetch_all($query,MYSQLI_ASSOC);
                $cnt=1;
                foreach($results as $result)
                {   ?>
            <tr>
                <!-- <td><?php echo htmlentities($cnt);?></td> -->
                <td><?php echo htmlentities($result['subject']);?></td>
                <td><?php echo htmlentities($result['grade']);?></td>
            </tr>
            <?php 
            $cnt=$cnt+1;
            }} ?>
                    </table>
                </td>
            </tr>
        </table>   
        
         <table class="table">
            <!-- parentdetail -->
            <tr class="heading">
                <td>
                    Parent/Guardian Details
                </td>
                <td></td>
            </tr>
            <?php
            $dap=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='".$_SESSION['staffid']."'"));
            ?>
            <tr>
                <th>Name</th>
                <td><?php echo htmlentities($dap["title"])?> <?php echo htmlentities($dap['parentname']) ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlentities($dap["address"])?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo htmlentities($dap["phone"])?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlentities($dap["email"])?></td>
            </tr>
            <tr>
                <th>Relation</th>
                <td><?php echo htmlentities($dap["relat"])?></td>
            </tr>
            <tr>
                <th>Occupation</th>
                <td><?php echo htmlentities($dap["occupation"])?></td>
            </tr>
            
            <tr>
                <th>Religion</th>
                <td><?php echo htmlentities($dap["relig"])?></td>
            </tr>
            
        </table>  
        
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                         
                        <button class="btn btn-primary" type="submit" name="rapprove">ReConfirm Detail <i class="fa fa-check"></i></button>
                        
                    </form>
                                
                        </div>
                    </div>
                </div>
            </div> 
                            </div>
                            
    <?php } ?>

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
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
