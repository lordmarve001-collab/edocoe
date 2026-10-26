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
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM abaresultsession WHERE description='exam processing'"));
$sems=$dat['semester'];
$bat=$dat['batch'];
$ses=$dat['session'];
$ded=$dat['deadline'];

if (isset($_POST['complete'])) {
    $cmap=mysqli_escape_string($con,$_POST['ccode']);
    $code=mysqli_escape_string($con,$_POST['code']);
                $upd=mysqli_query($con,"INSERT INTO abaresult (session,batch,status,date) VALUES ('$cmap','$code','Submitted',NOW())"); 

    if ($upd) {
        $showModal = "true";
        if ($deplt=='') {
           lectmailn($con,$_SESSION['staffid'],$sems,$ses,$cmap);
        }else{
        lectmail($con,$_SESSION['staffid'],$deplt,$sems,$ses,$cmap);
        }
    }
    else
    {
        $showModalEr='true';
    }
}
/**/

// $newfc=array();
// $chp="SELECT * FROM bordeline";
//             $stmtp = mysqli_query($con,$chp);
//                 $resulterp = mysqli_fetch_all($stmtp,MYSQLI_ASSOC);
//            foreach($resulterp as $rww)
//             {

//                     $newfc[]=$rww['score'];
//             }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname);?></title>
        <link href="<?php echo htmlentities($logo);?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/print.css" media="print" >
      <style type="text/css">
          #blah{
    width:170.256px;
    height:159px;
    border:2px solid black;
        }
        .td{
             cursor: not-allowed;
        }
        @media print{
            .dataTables_filter{
                display: none;
            }
            .dataTables_info{
                display: none;
            }
            .tab{
                border: 1px solid black;
                margin-top: 20px;
                width:700px;
            }
            .th{border: 1px solid black;}
            .td{
                border: 1px solid black;
                padding: 5px;
                margin: 5px;
                text-align: justify;
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
                    <!-- /.left-sidebar -->
                    <?php
                    $paycr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM abacordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));
                    if ($paycr['role']=='cordinator') {
                         include('includes/abbar.php');
                    }elseif($paycr['role']=='accountant'){
                        include('includes/juaccbar.php');
                    }elseif($paycr['role']=='officer') {
                        include('includes/abbar.php');
                    }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div >
                                    <h2 class="title">Student Result Uploading Setup</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                        <?php
                           
                        $payt=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffprive WHERE position='aba coordinator' AND staffid='".$_SESSION['staffid']."'"));
                        if ($payt!='')
                        {
                            ?>
                        <section class="section">
                        <div class="container-fluid">
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           <div class="panel-body panels">
                                            <?php 
                                               //geting Current Date 
                                            $dats= date("Y-m-d");
                                            //echo $ded;
                                            if ($dats>$ded) {
                                                echo "<b style=color:red;>Result Uploading Portal Is Close</b>";
                                            }else{
                                                $date1 = new DateTime($ded);
                                               $now = new DateTime();
                                               $diff = $date1->diff($now);
                                            ?>
                                            <p style='font-weight: bolder;'>Result Uploading <b style="color: red"> <?php echo htmlentities($ses); ?></b> Academic Session <b style="color: red"><?php echo htmlentities($bat) ?>, Batch  Days Remaining for the Portal To Close: <?php echo $diff->days.' '; ?></b></p>
                                </div>
                                            <div class="panel-body">
                                                <section class="section not">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                    <div class="form-group">
                            <label for="default" class="col-sm-2 control-labe">Academic Session:</label>
                             <div class="col-sm-10">
                             <select name="section" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Academic Session</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div> <div class="form-group">
                            <label for="default" class="col-sm-2 control-labe">Admission Batch:</label>
                             <div class="col-sm-10">
                             <select name="batch" class="form-control clid"  id="batch" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Admission Batch</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM batch ORDER BY batch ASC");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["batch"]; ?>"><?php echo  $row["name"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                                                                                                                 
                                                     
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="query" class="btn btn-primary">View Student</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                                <?php
                                                $error='';
                                                    if (isset($_POST['query'])) {
                                                        if (empty($_POST['section'])) {
                                                            $showModall = "true";
                                                            $error='Please Select Academic Session';
                                                            //exit();
                                                        }else
                                                        if (empty($_POST['batch'])) {
                                                            $showModall = "true";
                                                            $error='Please Admission Batch';
                                                            //exit();
                                                        }else{
                                                        $ccode=mysqli_escape_string($con,$_POST['section']);
                                                        $code=mysqli_escape_string($con,$_POST['batch']);
                                                        $sel=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM abaresult WHERE session='$ccode' AND batch='$code'"));
                                                        $status=$sel['status'];
                                                        if ($sel['status']=='Submitted') {
                                                             $showModall = "true";
                                                            $error='Result Already Submitted, Awaiting Approver';
                                                            //exit();
                                                        }else
                                                         if ($sel['status']=='Approved') {
                                                             $showModall = "true";
                                                            $error='Result Already Approved';
                                                            //exit();
                                                        }else
                                                        $sel=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studdata WHERE acadsession='$ccode' AND batch='$code' AND apptype='ABA'"));
                                                        if ($sel['graduate']=='0') {

                                                            $codi=mysqli_query($con,"SELECT * FROM studdata WHERE acadsession='$ccode' AND batch='$code' ");
                                                            if (mysqli_num_rows($codi)>0) {
                                                                
                                                            
                                                        ?>
                                                    <form method="post">
                                                        <div style="font-weight: bolder;color: red;">
                                                            <h2>Please Note</h2>
                                                            <ol>
                                                                <li>The System Automatic Save</li>
                                                                <li>When You Are Done Click Complete</li>
                                                                <li>Adhere To Prompt Instruction</li>
                                                                <?php   if($code=='1'){$m='First Batch';}
                                                                        if($code=='2'){$m='Second Batch';}
                                                                        if($code=='3'){$m='Third Batch';}
                                                                        if($code=='4'){$m='Fourth Batch';}
                                                                        if($code=='5'){$m='Fifth Batch';}
                                                                        if($code=='6'){$m='Sixth Batch';}
                                                                        if($code=='7'){$m='Seventh Batch';}
?>
                                                                <li>Academic Session: <b style="color: blue"><?php echo htmlentities($ccode) ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Admission Batch: <b style="color: blue"><?php echo $m?></b> 
                                                                </li> </ol>
                                                        </div>
                                    <table  id="example2" class="table table-striped table-bordered " cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>CA</th>
                                            <th>Exam</th>
                                            
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $newf=array();
                                            $sql = "SELECT * FROM studdata WHERE studdata.acadsession='$ccode' AND batch='$code' AND studdata.apptype='ABA'";
                                            $query = mysqli_query($con,$sql);
                                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                            $cnt=1;
                                            
                                            foreach($results as $result)
                                            {   
                                                 $caf=30;
                                                $xaf=70;
                                                stuname($con,$result['admno']);
                                                ?>
                                                
                                            <tr class="table-row" id="table-row-<?php echo $result["admno"]; ?>">
                                             <td class="td"><?php echo htmlentities($cnt);?></td>
                                                <td class="td"><?php echo htmlentities($result['admno']) ?></td>
                                                <td class="td" style="text-transform: capitalize;"><?php echo htmlentities(ucwords($_POST["harrd"]));?></td>
                                               
                                                <td contenteditable="true" class="ca allow_only_numbers" onBlur="saveInlineEdit(this,'ca','<?php 
                                                echo $result["admno"]; ?>','<?php 
                                                echo $result["acadsession"]; ?>','<?php 
                                                echo $caf; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo $result["ca"]; ?></td>
                                                <td class="exam allow_only_numbers" contenteditable="true" onBlur="saveInlineEdit(this,'exam','<?php echo 
                                                $result["admno"]; ?>','<?php 
                                                echo $result["acadsession"]; ?>','<?php 
                                                echo $xaf; ?>')"  onClick="highlightEdit(this);" id="text-field"><?php 
                                                echo $result["exam"]; ?></td>
                                                
                                            </tr>
                                            <?php 
                                           
                                            $cnt=$cnt+1;
                                             
                                            } ?>
                                                       
                                                    
                                             </tbody>
                                             </table>
                                                <div>
                                                                                                   
                                                   <button class="btn btn-success" type="button"  data-toggle="modal" data-target="#myModal<?php echo $result['coursecode']?>" style="float: right;"><i class="fa fa-upload">Complete</i></button> 
                                               
                                                </div>
                                            </form>
                                                    <?php
                                                    include 'abawarning.php';
                                                }else{
                                                    $showModall='true';
                                                    $error='No Student Register For this Course '.$ccode.' For This '.$ses.' Academic Session';
                                                }
                                                }else{
                                                    $sect=$ses;
                                                    $_SESSION['section']=$ccode;
                                                    $_SESSION['batch']=$code;
                                                    $_SESSION['status']=$status;
                                                    ?>
                                                    
                                                             <div class="brek">
                                         <div class="logos" style="width: 150px;margin: auto;">
                                            <span><img src='<?php echo $logo ?>' id="images1"  style="width: 130px;height: 130px;"></span>
                                        </div>
                                        <div><h3 align="center" style="font-size: 20px;font-weight: bolder;"><?php echo htmlentities($orgname) ?>, <?php echo htmlentities($schadress) ?></h3>
                                        
                                                <?php 
                                            $sel=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM abaresult WHERE session='$ccode' AND batch='$code'"));
                                                    $status=$sel['status'];

                                                    //Semester Selection
                                                    $qs=mysqli_fetch_assoc(mysqli_query($con,"SELECT DISTINCT semester FROM result WHERE coursecode='$ccode' "));
                                                    $mes=$qs['semester'];
                                                ?>
                                            
                                        <h6 align="center"style="text-transform: uppercase;"> Submitted Result Sheet</h6>
                                    <strong>
                                    <p style="text-align: center;" >COURSE TITLE: <strong style="text-transform: uppercase;">ABA. WEST AFRICAN - APPLIED BEHAVIOR ANALYSIS CERTIFICATE</strong></p>
                                    </strong>
                                    <div style="width: 480px;margin: auto;">
                                    </div>
                                            <table style="margin: auto;width: 800px;">
                                      <tr>
                                        <td width="327"><strong>SESSION:&nbsp; 
                                        <?php echo htmlentities($ses) ?></strong></td>
                                        <td width="415"><strong>BATCH:&nbsp;
                                            <?php  if($code=='1'){$m='First Batch';}
                                                                        if($code=='2'){$m='Second Batch';}
                                                                        if($code=='3'){$m='Third Batch';}
                                                                        if($code=='4'){$m='Fourth Batch';}
                                                                        if($code=='5'){$m='Fifth Batch';}
                                                                        if($code=='6'){$m='Sixth Batch';}
                                                                        if($code=='7'){$m='Seventh Batch';}
                                                                    echo htmlentities($m) ?></strong></td>
                                                                        <td width="415"><strong>Status:&nbsp;
                                            <?php  echo htmlentities($status) ?></strong></td>
                                        </tr>
                                    </table>
                        </div>
                   
                   
                                     <table  id="example2" class="table table-striped tab table-bordered " cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th class="th">#</th>
                                            <th class="th">Student ID</th>
                                            <th class="th">Student Name</th>
                                            <th class="th">CA</th>
                                            <th class="th">Exam</th>
                                            <th class="th">Total</th>
                                            <th class="th">Grade</th>
                                            <th class="th">Remark</th>
                                        </tr>
                                        </thead>
                                        <?php
                                            $sql = "SELECT * FROM studdata WHERE studdata.acadsession='$ccode' AND batch='$code' AND studdata.apptype='ABA'";
                                            $query = mysqli_query($con,$sql);
                                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                            $cnt=1;
                                            
                                            foreach($results as $result)
                                            {   
                                                 $caf=30;
                                                $xaf=70;
                                                stuname($con,$result['admno']);
                                                if (in_array($typ,$newfc)){
                                                    ?>
                                                    <tr class="table-row" style='color:red' id="table-row-<?php echo $result["admno"]; ?>">
                                                    <td class="td"><?php echo htmlentities($cnt);?></td>
                                                    <td class="td"><?php echo htmlentities($result['admno']) ?></td>
                                                    <td class="td" style="text-transform: capitalize;"><?php echo htmlentities(ucwords($_POST["harrd"]));?></td>
                                                    <td contenteditable="true" class="ca allow_only_numbers" onBlur="saveInlineEdit(this,'ca','<?php 
                                                echo $result["admno"]; ?>','<?php 
                                                echo $result["acadsession"]; ?>','<?php 
                                                echo $caf; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo $result["ca"]; ?></td>
                                                <td class="exam allow_only_numbers" contenteditable="true" onBlur="saveInlineEdit(this,'exam','<?php echo 
                                                $result["admno"]; ?>','<?php 
                                                echo $result["acadsession"]; ?>','<?php 
                                                echo $xaf; ?>')"  onClick="highlightEdit(this);" id="text-field"><?php 
                                                echo $result["exam"]; ?></td>
                                                    <td class="td"><?php echo $result['exam'] + $result['ca'] ?></td>
                                                     
                                                    <td class="td"><?php echo GetGrade($con,$result['ca'] + $result['exam']) ?></td>
                                                </tr>
                                                    <?php
                                                    }else{
                                                        ?>
                                                        <tr class="table-row" id="table-row-<?php echo $result["admno"]; ?>">
                                                 <td class="td"><?php echo htmlentities($cnt);?></td>
                                                    <td class="td"><?php echo htmlentities($result['admno']) ?></td>
                                                    <td class="td" style="text-transform: capitalize;"><?php echo htmlentities(ucwords($_POST["harrd"]));?></td>
                                                    <td class="td"><?php echo htmlentities($result['ca']);?></td>
                                                    <td class="td"><?php echo htmlentities($result["exam"]); ?></td>
                                                    <td class="td"><?php echo $result['exam'] + $result['ca'] ?></td>
                                                     
                                                    <td class="td"><?php echo GetGrade($con,$result['ca'] + $result['exam']) ?></td>
                                                    <?php 
                                                    $total= $result['exam'] + $result['ca'];
                                                    $rmark='Pass';
				if($total<70){$rmark='Resit';}
				if($total<40){$rmark='Fail';}?>
                                                    
                                                    <td class="td"><?php echo $rmark ?></td>
                                                </tr>
                                                    <?php 
                                                    }
                                                    ?>
                                            <?php 

                                            $cnt=$cnt+1;
                                             
                                            }// ?>
                                                       
                                                    
                                             </tbody>
                                             </table>
                                             <div>
                                             <span style='color:green;'>Result Summary:</span>
                                             <br>
                                            
<?php 
/**/

 $ts=0;

$ks=0;
$grades_count = [];
//      // $counts=0;
$sqlf="SELECT * FROM result WHERE acadsession='$ses'  AND coursecode='$ccode' AND semester='$sems'";
             
$_POST['totalunit']=0;
$stf = mysqli_query($con,$sqlf);
 $counts = mysqli_fetch_all($stf,MYSQLI_ASSOC);
            foreach($counts as $rowf)
            {
            $score=($rowf['ca'] + $rowf['exam']);
            GetGradeSystem($con);
                           for ($is = 1; $is <= $_SESSION['gradeCounter']; $is++)
                             {
                                 if ($score <= $_POST['Maxscore'][$is] && $score >= $_POST['Minscore'][$is])
                                {
                                   $_POST['myGrade'] = $_POST['Grade'][$is];
                                  // declare count for grade initially with 0
                                
                               }
                                   
                             }
                             if(isset($grades_count[$_POST['myGrade']]) === false) { 
                                    $grades_count[$_POST['myGrade']] = 0; 
                                }                               
                                $grades_count[$_POST['myGrade']]++;
                             // trying Nonsense
                
            }
            
      
      $cks=0;  
      $gcd=mysqli_query($con,"SELECT * FROM abagradetable ORDER BY id");
      $ress = mysqli_fetch_all($gcd, MYSQLI_ASSOC);
      foreach ($ress as $resu) { 
      foreach ($grades_count as $grade=>$count) {
            if ($resu['grade']==$grade) {
                $cks+=$count;
                echo $resu['grade']."=$count ";
            }

            /*echo "$grade=$count ";*/
            }
        }
        ?>
                                            <!-- <br> -->      
                                                <div style="margin:auto;width:50px;margin-top:5%;" class="button">
                                            <input type="button" value="Print-Detail" class="btn btn-primary" onclick="window.open('printax','_blank')" style="float: left;font-size: 20px;display: non;">               
                            </div> 
                                                    <?php
                                                }
                                                }
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
                    </section>
                     <?php }

                else{ ?>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-body panels">
                    <p style="font-size:17px;text-align: justify;">Dear:<b style="color:green;"> <?php echo htmlentities(ucwords($fullname)) ?></b> You Don't Have Access to this Page Kindly Contact the System Web Manager At The ICT Office Or Meet Your Head Of Department for Course Allocation To Enable You for Result Uploading</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
             <?php
                }
                ?>   
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
        <!-- Error Modal Box -->
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
                            <p>Result is Successfully Submitted</p>
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
                            <p>An Error Occured While Submitting</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
            <div class="modal fade" id="checkModall" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
<!-- Different Modal Box -->
            <div class="modal fade" id="myModalsk" role="dialog">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="float:left;color: red;">Warning Notice</h4>
                  <button type="button" class="close" data-dismiss="modal" style="float:right;">&times;</button>
                 
                </div>
                <div class="modal-body">
                  <p id="error" style="color:red"></p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
       <script type="text/javascript">
           
        
        /*Start Here*/
        function saveInlineEdit(editableObj,column,id,sesc,cad)
         {
          $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
          if(column == "ca"){
                var score=$(editableObj).text();
               
                if(parseInt(score) > cad) // in case it is not string.
                {
                    $(document).ready(function(){
                 
                    $("#error").html("Ca Score Can't More than "+cad);
                      $('#myModalsk').modal("show");
                    
                  });
                   
                }

                else{
        var customer={
            editval : $(editableObj).text(),
            column:column,
            studentid:id,
            sect:sesc,
          }
           $.ajax({
            url: "aedit",
            type: "POST",
            //data:'column='+column+'&editval='+$(editableObj).text()+'&studentid='+id+'&ccode='+codes+'&sect='+sesc+'&sems='+semes+'&lvs='+level,
            data:customer,
            success: function(data){
              $(editableObj).css("background","#FDFDFD");
              //alert(data);
            }
          });
          
      }
    }else if(column=="exam"){
        var score=$(editableObj).text();
        if(parseInt(score) > cad) // in case it is not string.
                {
                      $(document).ready(function(){
                 
                    $("#error").html("Exam Score Can't More than "+cad);
                      $('#myModalsk').modal("show");
                    
                  });
                }
                 else{
            var customer={
            editval : $(editableObj).text(),
            column:column,
            studentid:id,
            sect:sesc,
          }
                    $.ajax({
            url: "aedit",
            type: "POST",
            
            data:customer,
            success: function(data){
              $(editableObj).css("background","#FDFDFD");
              //alert(data);
            }
          });
             }
            }
        }
        /*End Here*/
        function highlightEdit(editableObj)
            {
          //     
            $(editableObj).css("background","#FFF");
            }
       
       </script>
       <script type="text/javascript">
       $(".allow_only_numbers").keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
              ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
              (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });


</script>
       <?php
        if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
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
    if(!empty($showModall)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModall").modal("show");
            });
        </script>';
    } 
       ?>
    </body>
</html>
<?php } 

function lectmail($con,$staffid,$dep,$sem,$ses,$cma)
{
    
    hodmail($con,$dep,$sem,$ses,$cma);
    
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cma'"));
    $ct=$pr['title'];

    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$staffid'"));
    $to=$stm['uniemail'];
    $firt=$stm['firstname'];
    $last=$stm['lastname'];
    $tit=$stm['title'];
    require_once "phpmailer/class.phpmailer.php";
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = '';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = '';                 // SMTP username
    $mail->Password = '';                           // SMTP password

    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; 

    $mail->setFrom('', 'Result Submitted');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Department Course Result Submit Notification';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear: '.$tit.'. '.$firt.' '.$last.' </strong></p>';
    $message .= '<p><strong>Please be Notified that You Have Submitted <br> <b style="color:blue;">Coursecode: '.$cma.' <br>Title: '.$ct.'</b><br> Results For <br> Semester: '.$sem.' <br>Session: '.$ses.' <br/> for your Hod review and Approval on the University Portal.</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}
function hodmail($con,$deps,$sems,$sess,$cmas)
{
    
    require_once "phpmailer/class.phpmailer.php";
    
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cmas'"));
    $ct=$pr['title'];
    $cd=$pr['department'];
    if ($cd=="98") {
    $st=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM gstcordinator WHERE acadsession='$sess'"));
    $sid=$st['staffid'];
    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$sid' AND status='active'"));
    $to=$stm['uniemail'];
    $firt=$stm['firstname'];
    $last=$stm['lastname'];
    $tit=$stm['title'];
    }else{
     $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffprive JOIN people ON staffprive.staffid=people.staffid  WHERE staffprive.department='$cd' AND staffprive.position='hod' AND people.status='active' "));
    $to=$stm['uniemail'];
    $firt=$stm['firstname'];
    $last=$stm['lastname'];
    $tit=$stm['title'];
    }
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = '';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = '';                 // SMTP username
    $mail->Password = '';                           // SMTP password

    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; 

    $mail->setFrom('', 'Result Submitted');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Department Course Result Submit Notification';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear: '.$tit.'. '.$firt.' '.$last.' </strong></p>';
    $message .= '<p><strong>Please be Notified that <br><b style="color:blue;">Coursecode: '.$cmas.' <br> Title: '.$ct.'</b> <br>Results For Semester: '.$sems.'<br> Session '.$sess.'  <br/>Have been submitted for your review and Approval on the University Portal.</strong></p>';
    $message.='<p><strong>Kindly Log in at <a href="my.uat.edu.ng">Portal</a> to Complete the Process.</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}
/*Non Department Staff*/

function lectmailn($con,$staffid,$sem,$ses,$cma)
{
    
    hodmailn($con,$sem,$ses,$cma);
    
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cma'"));
    $ct=$pr['title'];

    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$staffid'"));
    $to=$stm['uniemail'];
    $firt=$stm['firstname'];
    $last=$stm['lastname'];
    $tit=$stm['title'];
    require_once "phpmailer/class.phpmailer.php";
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = '';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = '';                 // SMTP username
    $mail->Password = '';                           // SMTP password

    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; 

    $mail->setFrom('', 'Result Submitted');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Department Course Result Submit Notification';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear: '.$tit.'. '.$firt.' '.$last.' </strong></p>';
    $message .= '<p><strong>Please be Notified that You Have Submitted <br> <b style="color:blue;">Coursecode: '.$cma.' <br>Title: '.$ct.'</b><br> Results For <br> Semester: '.$sem.' <br>Session: '.$ses.' <br/> for your Hod review and Approval on the University Portal.</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}
function hodmailn($con,$sems,$sess,$cmas)
{
    
    require_once "phpmailer/class.phpmailer.php";
    
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cmas'"));
    $ct=$pr['title'];
    $cd=$pr['department'];
    if ($cd=="98") {
    $st=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM gstcordinator WHERE acadsession='$sess'"));
    $sid=$st['staffid'];
    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$sid' AND status='active'"));
    $to=$stm['uniemail'];
    $firt=$stm['firstname'];
    $last=$stm['lastname'];
    $tit=$stm['title'];
    }else{
    $st=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffprive WHERE department='$cd' AND position='hod'"));
    $sid=$st['staffid'];
    $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail, title,firstname,lastname FROM people WHERE staffid='$sid' AND status='active'"));
    $to=$stm['uniemail'];
    $firt=$stm['firstname'];
    $last=$stm['lastname'];
    $tit=$stm['title'];
    }
    $mail = new PHPMailer;
    //$mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = '';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = '';                 // SMTP username
    $mail->Password = '';                           // SMTP password

    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; 

    $mail->setFrom('', 'Result Submitted');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = 'Department Course Result Submit Notification';
    //Message Body 
    $message ='<html><body>';
    $message.='<p><strong></strong></p>';
    $message.='<p><strong style="text-transform:capitalize">Dear: '.$tit.'. '.$firt.' '.$last.' </strong></p>';
    $message .= '<p><strong>Please be Notified that <br><b style="color:blue;">Coursecode: '.$cmas.' <br> Title: '.$ct.'</b> <br>Results For Semester: '.$sems.'<br> Session '.$sess.'  <br/>Have been submitted for your review and Approval on the University Portal.</strong></p>';
    $message.='<p><strong>Kindly Log in at <a href="my.uat.edu.ng">Portal</a> to Complete the Process.</strong></p>';
    $message.='<p><strong>Thank You.</strong></p>';
    $message .= '</body></html>';
    $mail->MsgHTML($message);
    $mail->send();
}

function GetGrade($con,$scores)
  {
     GetGradeSystem($con);
                          for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++)
                            {
                                if ($scores <= $_POST['Maxscore'][$i] && $scores >= $_POST['Minscore'][$i])
                                {
                                  $_POST['myGrade']   =   $_POST['Grade'][$i];
                                                   
                                }
                            }
                        
                return   $_POST['myGrade'];
  }
 function GetGradeSystem($con)
        {
     $gradeCounter=0;
     
     $sqlper = "SELECT  * FROM abagradetable";
      $result = mysqli_query($con,$sqlper);
     $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
     //$rowks=mysqli_num_rows($result);
     foreach($data as $row){
        $gradeCounter+=1;
        $_POST['Minscore'][$gradeCounter] =  $row["minscore"];
        $_POST['Maxscore'][$gradeCounter] =  $row["maxscore"];
        $_POST['Grade'][$gradeCounter]    =  $row["grade"];
        $_POST['point'][$gradeCounter]    =  $row["point"];
        $_POST['Remark'][$gradeCounter]    =  $row["remark"];
       }
         $_SESSION['gradeCounter']=$gradeCounter; 

 }
function stuname($con,$maxt)
{
  
      $sql =mysqli_query($con,"SELECT *  FROM  studdata WHERE admno='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $maks=$data['admno'];
        $maks=$data['admno'];
        $name =$sur." ".$first." ".$las;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
}

?>
