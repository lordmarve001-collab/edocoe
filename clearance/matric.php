<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
if(strlen($_SESSION['staffid'])=="")
    {    
    header("Location: ../logout"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.

include 'dataco.php';
$docu=mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND (purpose='JCHEW Tuition Fee' OR purpose='ND Tuition Fee' OR purpose='CERT. Tuition Fee') AND level='100'");
		$dad = mysqli_num_rows($docu);
		if ($dad==0)
		{
			header("Location:admission");
		}else{
		
		$query = "SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."' ";
                             $result = mysqli_query($con,$query);
                             $data = mysqli_fetch_assoc($result);
							$userID = $data['studentid'];
							$dact=$data['department'];
							$adm=$data['acadsession'];

if ($userID =='')
{

    $querys = "SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND (purpose='JCHEW Tuition Fee' OR purpose='ND Tuition Fee' OR purpose='CERT. Tuition Fee') ";
                             $result = mysqli_query($con,$querys);
                             $datas = mysqli_fetch_assoc($result);
							$sem=$datas['semester'];
							$progt=$datas['programme'];
							$level=$datas['level'];
					if ($querys!=''){

	$querys = "SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."' ";
                             $results = mysqli_query($con,$querys);
                             $datas = mysqli_fetch_assoc($results);
							$pr=$datas['programme'];


		$mat = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM matrenge WHERE status= '1'"));
						$endmat = $mat['endmat'];
        $ids = $endmat + 1;
        $yrk=substr($adm,0,4);
        $yr=substr($yrk,-2);
        $tday=date('Y-m-d');
        $userIDs = $dact."".$yr."".str_pad($ids, 4, '0', STR_PAD_LEFT);
        $upd=mysqli_query($con,"UPDATE personaldata SET studentid='$userIDs' WHERE applid='".$_SESSION['staffid']."' ");
        $upds=mysqli_query($con,"UPDATE studlogin SET staffid='$userIDs', username='$userIDs' WHERE admid='".$_SESSION['staffid']."' ");
        $qu=mysqli_query($con,"UPDATE matrenge SET endmat='$ids' WHERE status='1' ");

                            if($sem!=''){
                                $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level)
                                VALUES('$userIDs','$admy','$sem','$pr' ,'','$level')");

                            }else{
                                
            $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$userIDs','$adm','First Semester','$pr' ,'','$level')");
            $inst=mysqli_query($con,"INSERT INTO studentregsem(studentid,session,semester,programme,status,level) VALUES('$userIDs','$adm','Second  Semester','$pr','','$level')");
                            }}}}


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
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/coms.css">
        <link rel="stylesheet" href="css/receipt.css">
    
    </head>
<body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
   <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
<?php include('includes/leftbar.php');?> 
<style>
    .ttable th,.ttable td{
    border: 2px solid black;
    padding: 5px 5px;
    font-size: 15px;
}
    
    @media print{
            .ttable{
                width:1000px;
                margin:auto;
                border: 1px solid black;

            }
td,th{
    border: 1px solid black;
    cellpadding:0px;
    cellspacing:0px;
    padding: 5px 5px;
    font-size: 15px;
}
.td{
    display: none;
}
        }
    </style>
    
    <div class="main-page">
        <div class="container-fluid">
            <div class="row page-title-div">
                <div>
                    <h3 class="title"><?php echo "STUDENT MATRIC NO SETUP"; ?></h3>

                </div>

                <!-- /.col-md-6 text-right -->
            </div>

            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

        <section class="section">
            <div class="container-fluid">



                <div class="row">
                    <div class="col-md-12">

                        <div class="panel">


                            <div class="panel-body p-20">
                                <div class="invoice-box">
                                    <div style="width: 200px;margin: auto;">
            <img src="<?php echo htmlentities($logo)?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <hr>
        <div>
        <span style='position:absolute;margin-left:
          500px;margin-top:3px;width:170px;height:170px'><img width=170 height=170
          src="../apply/<?php echo $passport ?>" alt=" "></span><b>Student Name: <?php echo htmlentities($fullname) ?></b></span>
          <br><br>
          <span class=MsoNormal><b>Reg No: <?php echo htmlentities($_SESSION['staffid']); ?></b></span>
          <br><br>
          <span class=MsoNormal><b>Email: <?php echo htmlentities($email) ?></b></span>

          <br><br><span class=MsoNormal><b>College Of: <?php echo htmlentities($facu) ?> </b></span>
          <br><br><span class=MsoNormal><b>Department: <?php echo htmlentities($def) ?> </b></span>
          <br><br>
          <span class=MsoNormal><b>Programme: <?php echo htmlentities($pro) ?> </b></span>
          <br>
      </div>
                            
                                    <div align="center">
                                        <h3>MY MATRICULATION NUMBER</h3>
                                    </div>
<br><br><br>
<?php 
$query = "SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."' ";
                             $result = mysqli_query($con,$query);
                             $data = mysqli_fetch_assoc($result);
							$userID = $data['studentid'];?>
<span class=MsoNormal><b>MATRIC NO: <?php echo htmlentities($userID); ?> </b></span>


<br><br><br><br>
        
        <button class="btn btn-danger" onclick="window.open('admission_letter','_blank')">Print Admission letter  <i class="fa fa-download"></i></button>
        </div>
                                <!--End of invoice-->
                                <!-- /.col-md-12 -->
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->


                </div>
                <!-- /.col-md-12 -->
            </div>
    </div>
    <!-- /.panel -->
    </div>
                                    <!-- /.col-md-6 -->

    </div>
                                <!-- /.row -->

    </div>
                            <!-- /.container-fluid -->
    </section>
                        <!-- /.section -->

    </div>
                    <!-- /.main-page -->
                    </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        </body>
<?php }?>
