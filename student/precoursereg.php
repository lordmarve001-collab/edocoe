<?php
session_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
if (!isset($_SESSION['staffid']))
    header("Location: ../logout");
    $harr = $_SESSION['staffid'];
    
 include('../include/dbconfig.php');
 include'dataco.php';
 set_time_limit(500);
 //die($prg);
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
ini_set('max_execution_time', '500');
require"phpmailer/class.phpmailer.php";
//echo $expires;
$sems=$_SESSION['semester'];
$ses=$_SESSION['sessionx'];
$lv=$_SESSION['level'];
$std=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentregsem WHERE semester='$sems' AND session='$ses' AND studentid='".$_SESSION['staffid']."' "));
            if ($std!="") {
            $lv=$std['level'];
            $prg=$std['programme'];
            $timc=$std['extention'];
            $expired=$std['extention'];
            $stat=$std['status'];
}else{
    $prg=$progr;
    $lv="";
}
if(isset($_POST['updateroll']))
    {
        $del_id = $_POST['delt'];
        Deletecou($_SESSION['staffid'],$del_id,$sems,$ses,$lv,$prg,$con);    //}}
            //return false;
    }
    /*new Session*/
    $newfc=array();
    $kanter=0;
    $kanters=0;
    $kant=0;
    $cosc='';
    $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$prg'"));
    if ($data!='') {
      $type = $data['passmark'];  
    }
      $cok="SELECT DISTINCT coursecode,ca,exam  FROM result  WHERE  studentid='".$_SESSION['staffid']."' AND acadsession<'$ses' AND level<='$lv' AND semester='$sems' AND programme='$prg' AND status='Y' AND publish='1'";
    $stk = mysqli_query($con,$cok);
    $resulterk = mysqli_fetch_all($stk,MYSQLI_ASSOC);
    foreach($resulterk as $rowers)
    {
         $CodeMe = $rowers['coursecode'];
         $kanter+=1;
         if ($rowers['ca'] != "" && $rowers['exam'] !="")
         {
             $SumTotal[$kanter]  =  $rowers['ca'] + $rowers['exam'];
             $SumTotal2[$kanter] =  $rowers['ca'] + $rowers['exam'];
         }

         if ($SumTotal[$kanter] < $type)
         {
        //trying to insert Records into the Process Table
        //try to implement the order foreach to hold override the second carrover
            //Algorithm
            $chp="SELECT coursecode,MAX(ca+exam) FROM result WHERE  coursecode='".$rowers['coursecode']."' AND level<='$lv' AND semester='$sems' AND studentid='".$_SESSION['staffid']."' AND acadsession<'$ses' AND programme='$prg' AND status='Y' AND publish='1'";
            $stmtp = mysqli_query($con,$chp);
                $resulterp = mysqli_fetch_all($stmtp,MYSQLI_ASSOC);
           foreach($resulterp as $rww)
            {

          if ($rww['MAX(ca+exam)'] < $type)
            {
                    $newfc[]=$rww['coursecode'];
            }
            }
         }

    }
    /*For Deleted Details*/
    $newfd=array();
    $ques= mysqli_query($con,"SELECT DISTINCT coursecode,studentid,custatus FROM studelecour WHERE coursecode NOT IN (SELECT DISTINCT coursecode FROM result WHERE studentid='".$_SESSION['staffid']."') AND semester='$sems' AND studentid='".$_SESSION['staffid']."'");
    while ($rowsd= mysqli_fetch_array($ques)){
                $newfd[]=$rowsd['coursecode'];
        }
$newf=array_merge($newfc,$newfd);
/*For Course Adding And Submission*/
if (isset($_POST['addprog'])) {
    if (empty($_POST['addcourse'])) {
        $showModal='true';
        $error="Please Select Course";
    }else{
        $cod=mysqli_escape_string($con,$_POST['addcourse']);
        $ccs=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$cod'"));
        if ($ccs['coursecode']=='') {
            $error='Course Code '.$cod. 'Selected Not Available';
        }else{
            $code=$ccs['coursecode'];
        //Checking if record have already Added by User
        $que= mysqli_query($con,"SELECT * FROM studentcourseregistration WHERE studentid='".$_SESSION['staffid']."' AND coursecode='$code' AND level='$lv' AND semester='$sems' AND session='$ses' ");
        if(mysqli_num_rows($que)>0){
        $error= $code." Already Taken By You";
        $showModal='true';
        
        }else{

        $sql=mysqli_fetch_assoc(mysqli_query($con,"SELECT unit FROM course WHERE coursecode='$code'"));
        $_POST['cousunit']=$sql['unit'];
        
        $quer=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(unit) AS sumunit FROM studentcourseregistration  JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='".$_SESSION['staffid']."' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses' ORDER BY studentcourseregistration.coursecode"));
            $_POST['tunits']=$quer['sumunit'];
    
            $_POST['totals']=$_POST['cousunit'] + $_POST['tunits'];
    
            // Trying the Comparesing
            $mamin=mysqli_fetch_assoc(mysqli_query($con,"SELECT maxm FROM registcheck WHERE programme='$prg' AND level='$lv'"));  
            $maz=$mamin['maxm'];
              
            if ($_POST['totals']>$maz) {
               
                $showModal='true';
                $error="Your Units ".$_POST['totals']." Have Past the Required ".$maz." Units";
                
                }else{
                    //$cst="";
                    $el=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM curriculum WHERE coursecode='$code' AND programme='$prg' "));
                        if ($el!="") {
                        $trs=$el['status'];
                        }
                        else{
                                $trs="Elective";
                        }//Close Tag curriculum Check
                        $inser=mysqli_query($con,"INSERT INTO studentcourseregistration (studentid,session,semester,coursecode,level,custatus) VALUES ('".$_SESSION['staffid']."','$ses','$sems','$code','$lv','$trs')");
                    if ($inser) {
                        $mys=mysqli_query($con,"DELETE FROM studelecour WHERE studentid='".$_SESSION['staffid']."' AND coursecode='$code' AND acadsession='$ses' AND level='$lv' AND semester='$sems'");
                        header('Refresh:0;');
                        }else{
                        $error="Error Occured while Adding the Course";
                        $showModal='true';
                        }
                      
                    }
                }
        }
    }
}
//Registering Button Code Now
if (isset($_POST['courreg'])) {


     /*Checking Stage
     *Remember To load it from Table When Login From Active Directory
     */
     
        $quer=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(unit) AS sumunit FROM studentcourseregistration  JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='".$_SESSION['staffid']."' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses' ORDER BY studentcourseregistration.coursecode"));
            $_POST['tunits']=$quer['sumunit'];
    //die($_POST['tunits']);

    //$har=0;
            $_POST['totals']=$_POST['tunits'];
    //print($_POST['totals']);

    // Trying the Comparesing
            $mamin=mysqli_fetch_assoc(mysqli_query($con,"SELECT maxm,minm FROM registcheck WHERE programme='$prg' AND level='$lv'"));
            $maz=$mamin['maxm'];
            $minx=$mamin['minm'];
            if ($_POST['totals']>$maz) {
                $error="Your ".$_POST['totals'] ." Units  Is Above the Required Maximum ".$maz." Units";
                $showModal='true';
                }elseif ($_POST['totals']<$minx) {
                    $error="Your ".$_POST['totals']." Units  Is Less Than the Required Minimum ".$minx." Units ";
                    //header("Refresh:2;");
                    $showModal='true';
                }

                else{

                    $tod=date("Y-m-d H:i:sa");
                    //echo $tod;
                    $update=mysqli_query($con,"UPDATE studentregsem SET status='Pending',submitted='$tod' WHERE studentid='".$_SESSION['staffid']."' AND semester='$sems' AND session='$ses' AND level='$lv'");
                    $updats=mysqli_query($con,"UPDATE studentlateregsem SET regstatus='Pending' WHERE studentid='".$_SESSION['staffid']."' AND semester='$sems' AND session='$ses' AND level='$lv'");
                    
                    if ($update) {
                        $mail=new PHPMailer();
                        $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$prg'"));
                        $dp=$pr['did'];
                        $st=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffprive JOIN staffaculty ON staffprive.staffid=staffaculty.staffid WHERE staffaculty.deptm='$dp' AND staffprive.position='hod'"));
                        $sid=$st['staffid'];
                        $stm=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail FROM people WHERE staffid='$sid' AND status='active' "));
                        $mal=$stm['uniemail'];
                        hodmail($prg,$_SESSION['staffid'],$sems,$ses,$lv,$mail,$mal,$con);
                        studmail($_SESSION['staffid'],$unmail,$sems,$ses,$lv,$mail,$con);
                         $showModals='true';
                    }
                    
                }
            }
            /*Trying Line If It Will Work End Here*/
$labfc=array();
$chp="SELECT * FROM labfefac";
            $stmtp = mysqli_query($con,$chp);
                $resulterp = mysqli_fetch_all($stmtp,MYSQLI_ASSOC);
           foreach($resulterp as $rww)
            {

                    $labfc[]=$rww['facid'];
                    //echo $rww['facid'];
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
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" > 
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <link rel="stylesheet" href="css/print.css" media="print">
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
        @media print{
            table{
                width:400px;
                margin:auto;
            }
            .btn-danger{
              display: none;
            }
        }
      .cotable{
    width: 700px;
}
.cotreg{
    width: 700px;
    margin: auto;
}
.cotable td, .cotable th{
    border: 1px solid black;
}
       
  @media screen{
.lines{
  display: none;
}
}     
    .mover{
    /*width:500px;
    margin:auto;*/

}
    </style>
<div class="main-page">
    <div class="container-fluid">
        <div class="row page-title-div">
            <div>
                <h5 class="title" style="color:green;">STUDENT PREVIOUS COURSE REGISTRATION SETUP</h5>

            </div>

                <!-- /.col-md-6 text-right -->
        </div>
           
    </div>
        <!-- /.container-fluid -->
        <section class="section">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="panel">
          <div class="panel-body panels">
            <!-- Code to be Implemented Here -->
            <?php
            //geting Current Date 
             $std=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentregsem JOIN  studentlateregsem ON studentregsem.studentid=studentlateregsem.studentid AND studentregsem.semester=studentlateregsem.semester AND studentregsem.session=studentlateregsem.session AND studentregsem.level=studentlateregsem.level  WHERE studentregsem.semester='$sems' AND studentregsem.session='$ses' AND studentregsem.programme='$prg' AND studentregsem.level='$lv' AND studentregsem.studentid='".$_SESSION['staffid']."' "));
                if ($std!="") {
                $lv=$std['level'];
                $prg=$std['programme'];
                if (in_array($datst['faculty'],$labfc))
                {
                $labc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND section='$ses' AND level='$lv' AND purpose='Laboratory Fee' "));
                /*Laboratory Payment Fee Cheking*/
                   if ($labc=='') { ?>
                    <p style='color: red;font-size: 16px;'>Dear: <b style="color:green;text-transform: capitalize;"><?php echo htmlentities($fullname) ?></b> You Have Not Payed <b style="color:blue;">Laboratory Fee</b> for this <b style="color:blue;"> <?php echo htmlentities($ses) ?></b> Academic Session Kindly Visit Bursary For Clearance to Enable You Register Your Courses Or Click Here To Make Online Payment <a href="labfeepay" style="color:blue;">Click Me To Pay</a></p>
                   <?php }else{
                        /*Sug Fee Check For Science Student
                        $sugp=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE section='$ses' AND level='$lv' AND (purpose='SUG Fee' OR purpose='SUG Fee Return') AND studentid='".$_SESSION['staffid']."' "));
                            if ($sugp=='') {    
                                ?>
                                <p style='color: red;font-size: 16px;'>Dear: <b style="color:green;text-transform: capitalize;"><?php echo htmlentities($fullname) ?></b> You Have Not payed SUG Fee for this <b style="color:blue;"> <?php echo htmlentities($ses) ?></b> Academic Session Kindly Visit Bursary For Clearance to Enable You Register Or Click Here To Make Online Payment <a href="paysugfee" style="color:blue;">Click Me To Pay</a></p>
                            <?php 
                                }else{*/
                                    if ($std['regstatus']=="") { 
                            ?>
                            <p style='font-weight: bolder;font-size: 18px;'>Course Registration <b style="color: red"> <?php echo htmlentities($ses); ?></b> Academic Session <b style="color: red"><?php echo htmlentities($sems) ?> </b> Level <b style='color: red'><?php echo htmlentities($lv); ?></b><br>
                <?php
                //Checking the curriculum Table if Hod Have setup thier curriculum 
                $cur=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM curriculum WHERE semester='$sems' AND programme='$prg'  AND level='$lv'"));

                if ($cur=="") { ?>
                
                  <p style="color: red;font-weight: bolder;font-family: Aria, sans-serif;">
                  Sorry Your Head of Department (Hod) Have Not Setup  Curriculum For You To Register
                  Kindly Meet Your Hod So that He/She Can Set It Up To Enable You 
                  Register Your Course
                </p>
            <?php
                }else{
               $stdc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentcourseregistration WHERE semester='$sems' AND session='$ses' AND level='$lv' AND studentid='".$_SESSION['staffid']."' "));
            if ($stdc=="") {
                
                      currichec($con,$_SESSION['staffid'],$ses,$sems,$lv,$prg);
                     Carryover($_SESSION['staffid'],$ses,$sems,$lv,$prg,$con);
                     deletedcourse($_SESSION['staffid'],$ses,$sems,$lv,$prg,$con);
            }
            ?>
            <form  method="POST">
            <div style="margin: auto;width: auto;">
            <table>
            <tbody>
              <tr>
                <td>
                  <select name="addcourse" id="selectt" class="select form-control">
                    <option selected="selected" disabled="disabled">SELECT COURSE</option>
                    <?php 
                      
                      $res = mysqli_query($con,"SELECT * FROM course ORDER BY coursecode");
                      while ($row=mysqli_fetch_array($res))
                      {
                        
                      ?>
                      <option value="<?php echo  $row["coursecode"]; ?>"><?php echo  $row["coursecode"]; ?> <?php echo  $row["title"]; ?></option>
                      <?php

                      }
                       ?>
                  </select>
                 
                </td>
                <td><input type="submit" name="addprog" value="ADD-COURSE" class="btn btn-success btn-label-right btn-label btn-label-right"></td>
              </tr>
            </tbody>
          </table>
                 
              </div>
             </form>
                 
                 <span style="font-weight: bold;text-transform: uppercase;">Semester Courses</span>
                <table align="center" width="400" class="table">
                <tr>
                    <th>S/NO</th>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Unit</th>
                    <th>Status</th>
                    <th>Delete</th>
                </tr>
                
                  
                <?php 
                /*this part fetchs the result of the applicant*/
           
              $ques= mysqli_query($con,"SELECT * FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='".$_SESSION['staffid']."' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses' ORDER BY studentcourseregistration.coursecode");
                $sr =1;
                while ($rows= mysqli_fetch_array($ques)):
                    //GetUnits($rows["coursecode"],$rows["studentid"]);
                   ?>
                  <tr>
                    <td class="tm"><?php echo $sr;?></td>
                    <td class="td" style="text-align:left;"><?php echo $rows["coursecode"];?></td>
                    <td class="td" id="unit" name="unit"><?php echo $rows["title"];?></td>
                    <td class="td"><?php echo $rows["unit"];?></td>
                    <td class="td"><?php echo $rows["custatus"];?></td>
                    <td class="td">
                      <?php 
                      if (in_array($rows['coursecode'],$newf))
                      {
                      ?>
                      <button class="btn btn-danger" disabled="disabled"><span class="fa fa-trash"></span>Delete</button>
                     <?php 
                          }else{
                      ?>
                      <button class="btn btn-danger" data-toggle="modal" type="button" data-target="#update_modal<?php echo $rows['coursecode']?>" ><span class="fa fa-trash"></span>Delete</button>
                      
                      <?php }
                      ?>
                  </td>

                  </tr>
                <?php 
                $sr++;
                include 'delect.php';
              endwhile;
                GetUnitz($_SESSION['staffid'],$lv,$sems,$ses,$con);
                  ?>

                    <tr>
                        <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
                        
                        <td style="color:red;font-weight: bolder;"><?php echo $_POST['totalUnit']; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                  </table>
                  <form action="" method="post" enctype="multipart/form-data">
                  <div class="button">
                 
                  <button class="btn btn-success" type="button"  data-toggle="modal" data-target="#myModals"><i class="fa fa-save">Complete Registration</i></button>      
                </div>
                </form> 
                <?php
                include 'warning.php';
                }
            }elseif($std['status']=="Pending"  OR $std['regstatus']=='Pending'){ ?>
                <p style='font-weight: bolder;'>Registration Status : <b style='color: red;font-weight: bold;'><?php echo htmlentities($stat); ?> Kindly Wait For Your HOD Approval</b></p>
            <div>
              <table class="table" border=1 cellspacing=0 cellpadding=0>
            <tr>
              <th>#</th>
              <th style="width: 130px">Course Code</th>
              <th>Course Title</th>
              <th style="width: 120px;">Credit Unit</th>
              <th style="width: 130px;">Course Status</th>
              </tr>
                  <?php 
                  //Loading Register Course Information From CourseregTable
                  $que= mysqli_query($con,"SELECT * FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='".$_SESSION['staffid']."' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses' ORDER BY studentcourseregistration.coursecode");
                  
              
                $sr =1;
                  while ($rows= mysqli_fetch_array($que)): ?>
                  <tr>
                      <td><?php echo $sr;?></td>
                      <td><?php echo $rows["coursecode"];?></td>
                      <td><?php echo $rows["title"];?></td>
                      <td><?php echo $rows["unit"];?></td>
                      <td><?php echo $rows["custatus"];?></td>
                  </tr>
                  <?php 
                  $sr++;
              endwhile;
              GetUnit($_SESSION['staffid'],$lv,$sems,$ses,$con);
              ?>
               <tr>
                  <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
                  
                  <td><b style="color:red;font-weight:bolder;"><?php echo $_POST['totalUnit']; ?></b></td>
                  <td></td>
               </tr>
      
              </table>
            </div>
           <?php }
            elseif($std['status']=="Approved" OR $std['regstatus']=='Approved'){
            $_SESSION['lv'] = $lv;
            $_SESSION['ses'] = $ses;
            $_SESSION['sems'] = $sems;
                ?>
                <div class="cotreg">
          <table cellpadding=0 cellspacing=0 align=left style='width:700px;'  class="datas">
        
          <tr>
       
          <td rowspan=2 align=left valign=top><img width=130 height=130
          src="<?php echo $logo?>" alt="Institution Photo"></td>
          </tr>

            <tr>
              
              <td style="font-weight: bolder;text-align: center;">
                  <span style="font-size: 20.5px;text-align: center;width: 150px;"><?php echo htmlentities($orgname) ;?>, <?php echo htmlentities($schadress); ?>&#13;&#10;</span>
                  <br>
                  <span style="font-size: 15.5px;text-align: center;width: 150px;">Student Course Registration Print-Out &#13;&#10;</span>
                  <br>
                  <span style="font-size: 15.5px;text-align: center;width: 150px;"><?php echo htmlentities($sems) ?> <?php echo htmlentities($ses); ?> Academic Session &#13;&#10;</span>
                  <br>
                  
              </td>
             </tr>

            </table>
          <!-- Seperate Line -->
          <p class="lines" style="width: 700px;">_______________________________________________________________________________________</p>
          <!-- <hr style="font-weight: bolder;">_______________________________________________________________________________________ -->
          <!-- For the Student Record -->
          <span style='position:absolute;margin-left:
          530px;margin-top:3px;width:170px;height:170px'><img width=170 height=170
          src="<?php echo $passport ?>" alt=" "></span>Student Name: <b><?php echo htmlentities($fulname) ?></b></span>
          <br>
          <span class=MsoNormal>Matric No: <b><?php echo htmlentities($_SESSION['staffid']); ?></b></span>
          <br>
          <span class=MsoNormal>Email: <b><?php echo htmlentities($unmail) ?></b></span>

          <br><span class=MsoNormal>Faculty: <b><?php echo htmlentities($facu) ?> </b></span>
          <br>
          <span class=MsoNormal>Programme: <b><?php echo htmlentities($prg) ?> </b></span>
          <br>
          <span class=MsoNormal>Level: <b><?php echo htmlentities($lv); ?></b></span>
          <!-- Ends Here -->
          <p class=MsoNormal>&nbsp;</p>
          <p class=MsoNormal>&nbsp;</p>
          <table class="cotable" border=1 cellspacing=0 cellpadding=0>
          <tr>
              <th>#</th>
              <th style="width: 130px">Course Code</th>
              <th>Course Title</th>
              <th style="width: 120px;">Credit Unit</th>
              <th style="width:20px;">Status</th>
          </tr>
          <?php 
          
          $que= mysqli_query($con,"SELECT DISTINCT course.title,course.unit,result.coursecode,result.custatus FROM result JOIN course ON result.coursecode=course.coursecode  WHERE result.studentid='".$_SESSION['staffid']."' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses'  ORDER BY result.coursecode");
      
          $sr =1;
          while ($rows= mysqli_fetch_array($que)): ?>
          <tr>
              <td><?php echo $sr;?></td>
              <td><?php echo $rows["coursecode"];?></td>
              <td><?php echo $rows["title"];?></td>
              <td><?php echo $rows["unit"];?></td>
              <td><?php echo substr($rows["custatus"], 0,1);?></td>
          </tr>
          <?php 
          $sr++;
          endwhile;
          GetUnita($_SESSION['staffid'],$lv,$sems,$ses,$con);
          ?>
      <tr>
          <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
          
          <td><?php echo $_POST['totalUnit']; ?></td>
          <td></td>
      </tr>
  
          </table>
          <p class=MsoNormal>&nbsp;</p>

          
           <div class="signt">
          <!-- <p class=MsoNorma>&nbsp;</p> -->
          <p class='MsoNorma'>__________________________<br>
          <b>Student’s Signature &amp; Date</b></p>
          
          <p class='MsoNorma'>__________________________<br>
          <b>HoD’s Signature &amp; Date</b></p>

          <p class='MsoNorma'>__________________________<br>
          <b>Dean’s Signature &amp; Date</b></p>
          </div>
          </div>
                                              
         <div style="margin:auto;width:100px;" class="button">
           
           <button type="button" class="btn btn-success"onclick="window.open('printco','_blank')" style="float: left;font-size: 20px;display: non;">Print Registration Detail<i class="fa fa-print"></i></button>               
          </div>
                <?php
            }else{
                ?>
                <p style="color:red;">Sorry Dear: <b style="color:blue;"><?php echo htmlentities($fulname) ?></b> You Have Not Have Not Paid for Previous Late Registration. Kindly Visit Bursary for Such Clearance Thank You</p>
                <?php
                        }   
                                           
                   }
                }else{
                        /*Sug Fee Check For Science Student
                        $sugp=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE section='$ses' AND level='$lv' AND (purpose='SUG Fee' OR purpose='SUG Fee Return')  AND studentid='".$_SESSION['staffid']."' "));
                            if ($sugp=='') {   
                            ?>
                            <p style='color: red;font-size: 16px;'>Dear: <b style="color:green;text-transform: capitalize;"><?php echo htmlentities($fullname) ?></b> You Have Not payed SUG Fee for this <b style="color:blue;"> <?php echo htmlentities($ses) ?></b> Academic Session Kindly Visit Bursary For Clearance to Enable You Register Or Click Here To Make Online Payment <a href="paysugfee" style="color:blue;">Click Me To Pay</a></p>
                            <?php 
                            }else{*/ 
                                if ($std['regstatus']=="") { 
                                ?>
                                <p style='font-weight: bolder;font-size: 18px;'>Course Registration <b style="color: red"> <?php echo htmlentities($ses); ?></b> Academic Session <b style="color: red"><?php echo htmlentities($sems) ?> </b> Level <b style='color: red'><?php echo htmlentities($lv); ?></b><br>
                <?php
                //Checking the curriculum Table if Hod Have setup thier curriculum 
                $cur=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM curriculum WHERE semester='$sems' AND programme='$prg'  AND level='$lv'"));

                if ($cur=="") { ?>
                
                  <p style="color: red;font-weight: bolder;font-family: Aria, sans-serif;">
                  Sorry Your Head of Department (Hod) Have Not Setup  Curriculum For You To Register
                  Kindly Meet Your Hod So that He/She Can Set It Up To Enable You 
                  Register Your Course
                </p>
            <?php
                }else{
               $stdc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentcourseregistration WHERE semester='$sems' AND session='$ses' AND level='$lv' AND studentid='".$_SESSION['staffid']."' "));
            if ($stdc=="") {
                
                      currichec($con,$_SESSION['staffid'],$ses,$sems,$lv,$prg);
                     Carryover($_SESSION['staffid'],$ses,$sems,$lv,$prg,$con);
                     deletedcourse($_SESSION['staffid'],$ses,$sems,$lv,$prg,$con);
            }
            ?>
            <form  method="POST">
            <div style="margin: auto;width: auto;">
            <table>
            <tbody>
              <tr>
                <td>
                  <select name="addcourse" id="selectt" class="select form-control">
                    <option selected="selected" disabled="disabled">SELECT COURSE</option>
                    <?php 
                      
                      $res = mysqli_query($con,"SELECT * FROM course ORDER BY coursecode");
                      while ($row=mysqli_fetch_array($res))
                      {
                        
                      ?>
                      <option value="<?php echo  $row["coursecode"]; ?>"><?php echo  $row["coursecode"]; ?> <?php echo  $row["title"]; ?></option>
                      <?php

                      }
                       ?>
                  </select>
                 
                </td>
                <td><input type="submit" name="addprog" value="ADD-COURSE" class="btn btn-success btn-label-right btn-label btn-label-right"></td>
              </tr>
            </tbody>
          </table>
                 
              </div>
             </form>
                 
                 <span style="font-weight: bold;text-transform: uppercase;">Semester Courses</span>
                <table align="center" width="400" class="table">
                <tr>
                    <th>S/NO</th>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Unit</th>
                    <th>Status</th>
                    <th>Delete</th>
                </tr>
                
                  
                <?php 
                /*this part fetchs the result of the applicant*/
           
              $ques= mysqli_query($con,"SELECT * FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='".$_SESSION['staffid']."' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses' ORDER BY studentcourseregistration.coursecode");
                $sr =1;
                while ($rows= mysqli_fetch_array($ques)):
                    //GetUnits($rows["coursecode"],$rows["studentid"]);
                   ?>
                  <tr>
                    <td class="tm"><?php echo $sr;?></td>
                    <td class="td" style="text-align:left;"><?php echo $rows["coursecode"];?></td>
                    <td class="td" id="unit" name="unit"><?php echo $rows["title"];?></td>
                    <td class="td"><?php echo $rows["unit"];?></td>
                    <td class="td"><?php echo $rows["custatus"];?></td>
                    <td class="td">
                      <?php 
                      if (in_array($rows['coursecode'],$newf))
                      {
                      ?>
                      <button class="btn btn-danger" disabled="disabled"><span class="fa fa-trash"></span>Delete</button>
                     <?php 
                          }else{
                      ?>
                      <button class="btn btn-danger" data-toggle="modal" type="button" data-target="#update_modal<?php echo $rows['coursecode']?>" ><span class="fa fa-trash"></span>Delete</button>
                      
                      <?php }
                      ?>
                  </td>

                  </tr>
                <?php 
                $sr++;
                include 'delect.php';
              endwhile;
                GetUnitz($_SESSION['staffid'],$lv,$sems,$ses,$con);
                  ?>

                    <tr>
                        <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
                        
                        <td style="color:red;font-weight: bolder;"><?php echo $_POST['totalUnit']; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                  </table>
                  <form action="" method="post" enctype="multipart/form-data">
                  <div class="button">
                 
                  <button class="btn btn-success" type="button"  data-toggle="modal" data-target="#myModals"><i class="fa fa-save">Complete Registration</i></button>      
                </div>
                </form> 
                <?php
                include 'warning.php';
                }
            }elseif($std['status']=="Pending"  OR $std['regstatus']=='Pending'){ ?>
                <p style='font-weight: bolder;'>Registration Status : <b style='color: red;font-weight: bold;'><?php echo htmlentities($stat); ?> Kindly Wait For Your HOD Approval</b></p>
            <div>
              <table class="table" border=1 cellspacing=0 cellpadding=0>
            <tr>
              <th>#</th>
              <th style="width: 130px">Course Code</th>
              <th>Course Title</th>
              <th style="width: 120px;">Credit Unit</th>
              <th style="width: 130px;">Course Status</th>
              </tr>
                  <?php 
                  //Loading Register Course Information From CourseregTable
                  $que= mysqli_query($con,"SELECT * FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='".$_SESSION['staffid']."' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses' ORDER BY studentcourseregistration.coursecode");
                  
              
                $sr =1;
                  while ($rows= mysqli_fetch_array($que)): ?>
                  <tr>
                      <td><?php echo $sr;?></td>
                      <td><?php echo $rows["coursecode"];?></td>
                      <td><?php echo $rows["title"];?></td>
                      <td><?php echo $rows["unit"];?></td>
                      <td><?php echo $rows["custatus"];?></td>
                  </tr>
                  <?php 
                  $sr++;
              endwhile;
              GetUnit($_SESSION['staffid'],$lv,$sems,$ses,$con);
              ?>
               <tr>
                  <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
                  
                  <td><b style="color:red;font-weight:bolder;"><?php echo $_POST['totalUnit']; ?></b></td>
                  <td></td>
               </tr>
      
              </table>
            </div>
           <?php }
            elseif($std['status']=="Approved" OR $std['regstatus']=='Approved'){
            $_SESSION['lv'] = $lv;
            $_SESSION['ses'] = $ses;
            $_SESSION['sems'] = $sems;
                ?>
                <div class="cotreg">
          <table cellpadding=0 cellspacing=0 align=left style='width:700px;'  class="datas">
        
          <tr>
       
          <td rowspan=2 align=left valign=top><img width=130 height=130
          src="<?php echo $logo?>" alt="Institution Photo"></td>
          </tr>

            <tr>
              
              <td style="font-weight: bolder;text-align: center;">
                  <span style="font-size: 20.5px;text-align: center;width: 150px;"><?php echo htmlentities($orgname) ;?>, <?php echo htmlentities($schadress); ?>&#13;&#10;</span>
                  <br>
                  <span style="font-size: 15.5px;text-align: center;width: 150px;">Student Course Registration Print-Out &#13;&#10;</span>
                  <br>
                  <span style="font-size: 15.5px;text-align: center;width: 150px;"><?php echo htmlentities($sems) ?> <?php echo htmlentities($ses); ?> Academic Session &#13;&#10;</span>
                  <br>
                  
              </td>
             </tr>

            </table>
          <!-- Seperate Line -->
          <p class="lines" style="width: 700px;">_______________________________________________________________________________________</p>
          <!-- <hr style="font-weight: bolder;">_______________________________________________________________________________________ -->
          <!-- For the Student Record -->
          <span style='position:absolute;margin-left:
          530px;margin-top:3px;width:170px;height:170px'><img width=170 height=170
          src="<?php echo $passport ?>" alt=" "></span>Student Name: <b><?php echo htmlentities($fulname) ?></b></span>
          <br>
          <span class=MsoNormal>Matric No: <b><?php echo htmlentities($_SESSION['staffid']); ?></b></span>
          <br>
          <span class=MsoNormal>Email: <b><?php echo htmlentities($unmail) ?></b></span>

          <br><span class=MsoNormal>Faculty: <b><?php echo htmlentities($facu) ?> </b></span>
          <br>
          <span class=MsoNormal>Programme: <b><?php echo htmlentities($prg) ?> </b></span>
          <br>
          <span class=MsoNormal>Level: <b><?php echo htmlentities($lv); ?></b></span>
          <!-- Ends Here -->
          <p class=MsoNormal>&nbsp;</p>
          <p class=MsoNormal>&nbsp;</p>
          <table class="cotable" border=1 cellspacing=0 cellpadding=0>
          <tr>
              <th>#</th>
              <th style="width: 130px">Course Code</th>
              <th>Course Title</th>
              <th style="width: 120px;">Credit Unit</th>
              <th style="width:20px;">Status</th>
          </tr>
          <?php 
          
          $que= mysqli_query($con,"SELECT DISTINCT course.title,course.unit,result.coursecode,result.custatus FROM result JOIN course ON result.coursecode=course.coursecode  WHERE result.studentid='".$_SESSION['staffid']."' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses'  ORDER BY result.coursecode");
      
          $sr =1;
          while ($rows= mysqli_fetch_array($que)): ?>
          <tr>
              <td><?php echo $sr;?></td>
              <td><?php echo $rows["coursecode"];?></td>
              <td><?php echo $rows["title"];?></td>
              <td><?php echo $rows["unit"];?></td>
              <td><?php echo substr($rows["custatus"], 0,1);?></td>
          </tr>
          <?php 
          $sr++;
          endwhile;
          GetUnita($_SESSION['staffid'],$lv,$sems,$ses,$con);
          ?>
      <tr>
          <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
          
          <td><?php echo $_POST['totalUnit']; ?></td>
          <td></td>
      </tr>
  
          </table>
          <p class=MsoNormal>&nbsp;</p>

          
           <div class="signt">
          <!-- <p class=MsoNorma>&nbsp;</p> -->
          <p class='MsoNorma'>__________________________<br>
          <b>Student’s Signature &amp; Date</b></p>
          
          <p class='MsoNorma'>__________________________<br>
          <b>HoD’s Signature &amp; Date</b></p>

          <p class='MsoNorma'>__________________________<br>
          <b>Dean’s Signature &amp; Date</b></p>
          </div>
          </div>
                                              
         <div style="margin:auto;width:100px;" class="button">
           
           <button type="button" class="btn btn-success"onclick="window.open('printco','_blank')" style="float: left;font-size: 20px;display: non;">Print Registration Detail<i class="fa fa-print"></i></button>               
          </div>
                <?php
            }else{
                ?>
                <p style="color:red;">Sorry Dear: <b style="color:blue;"><?php echo htmlentities($fulname) ?></b> You Have Not Have Not Paid for Previous Late Registration. Kindly Visit Bursary for Such Clearance Thank You</p>
                <?php
                    }
                  }
                

              ?> 
             <?php } ?>           
            </div>
          </div>
      </div>
  </div>
</div>
</section>
</div>
</div>
</div>
<!--  -->
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
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        <div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities("Course Registration Is Successfully"); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
<!--  -->
</div>
</body>
</html>

<?php 
include 'includes/buton.php';
if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
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

function GetUnit($mat,$lv,$sems,$ses,$con)
{

    $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$mat' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses' ORDER BY studentcourseregistration.coursecode";
$resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
              $_POST['totalUnit']=$row['sumunit'];
                
            }
}

function GetUnitz($matt,$lv,$sems,$ses,$con)
{
        $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$matt' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses' ORDER BY studentcourseregistration.coursecode";
          $resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
                $_POST['totalUnit']=$row['sumunit'];       
            }
}

//For Deleting
function Deletecou($std,$codesg,$semss,$sess,$lvs,$prgs,$con)
{
  
    $del=mysqli_query($con,"DELETE FROM studentcourseregistration WHERE coursecode='$codesg' AND session='$sess' AND level='$lvs' AND semester='$semss' AND studentid='$std'");
    $dch=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM result WHERE studentid='$std' AND coursecode='$codesg' AND semester='$semss' AND programme='$prgs'"));
    if ($dch=="") {
        $corr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM curriculum WHERE semester='$semss' AND programme='$prgs' AND coursecode='$codesg' ORDER BY coursecode"));
            $sta = $corr['status'];
            if ($sta=='Core' OR $sta=='Required') {
               $tis=mysqli_query($con,"INSERT studelecour (studentid,acadsession,semester,coursecode,level,custatus) VALUES ('$std','$sess','$semss','$codesg','$lvs','$sta')");
            }
    }
}
/*
*Checking for the Carry over of Student
*/
function CheckCOS($codet,$prom,$leve,$seml,$secx,$stdi,$con)
   {
      
        $MyData="";
         try
         {
            $ch="SELECT coursecode,custatus,level,studentid,MAX(ca+exam) FROM result WHERE  coursecode='$codet' AND level<='$leve' AND semester='$seml' AND studentid='$stdi' AND acadsession<'$secx' AND programme='$prom' AND status='Y' AND publish='1'";
            $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$prom'"));
            if ($data!='') {
                $type = $data['passmark'];  
            }
                        $stmt = mysqli_query($con,$ch);
                        $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
                   foreach($resulter as $rw)
              {

              if ($rw['MAX(ca+exam)'] < $type)
              {
                 $MyData .= $rw['coursecode'];

              /*Loading the course information and unit from course table*/
              $el=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM curriculum WHERE coursecode='".$rw['coursecode']."' AND programme='$prom' "));
                        if ($el!="") {
                        $trs=$el['status'];
                        }
                        else{
                                $trs="Elective";
                        }
          $insert=mysqli_query($con,"INSERT INTO studentcourseregistration(studentid,session,semester,coursecode,level,custatus) VALUES ('$stdi','$secx','$seml','".$rw['coursecode']."','$leve','$trs')");
        }
      }
                        
             //$_POST['MyData']=$MyData;
            $_POST['MyData']=$MyData;
            //echo $_POST['MyData'];
         }
              catch(PDOException $e)
           {
          echo $e->getMessage();
         }

     }

     function Carryover($stdi,$sectin,$semet,$leve,$program,$con)
     {
      
      $kanter_CO = 0;
      $HolderCO='';
      $COS='';
      $_POST['MyData']='';
     //echo $stdi;
      //echo $program;
      $CO="SELECT DISTINCT coursecode,ca,exam  FROM result  WHERE  studentid='$stdi' AND acadsession<'$sectin' AND level<='$leve' AND semester='$semet' AND programme='$program'  AND status='Y' AND publish='1'";
        $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$program'"));
      if ($data!='') {
                $type = $data['passmark'];  
     }
      $st = mysqli_query($con,$CO);
      $resulter = mysqli_fetch_all($st,MYSQLI_ASSOC);
      foreach($resulter as $rowers)
          {
                 $CodeMe = $rowers['coursecode'];
                 $kanter_CO = $kanter_CO + 1;
                 if ($rowers['ca'] != "" && $rowers['exam'] !="")
                 {
                     $SumTotal[$kanter_CO]  =  $rowers['ca'] + $rowers['exam'];
                     $SumTotal2[$kanter_CO] =  $rowers['ca'] + $rowers['exam'];
                 }
                 if ($SumTotal[$kanter_CO] < $type)
                 {
                     CheckCOS($CodeMe,$program,$leve,$semet,$sectin,$stdi,$con);
                $COS.=$_POST['MyData'];
                
                 }
                 if (($SumTotal2[$kanter_CO] >= $type))
                 {
                     $CO_Code2[$kanter_CO] = $rowers['coursecode'];
                     // Data = "Passed";

                 }

             }

             if ($_POST['MyData'] == "")
             {
                 $HolderCO .= " " . "";
             }
             else
             {
        
                 $HolderCO .= "".$COS.""; 
             }
             $_POST['HolderCO'] = $HolderCO;
             
            
     }
 //Loading of CarryOver Ends Here
function GetUnita($matt,$lv,$sems,$ses,$con)
{

        $queryy="SELECT SUM(unit) AS sumunit FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matt' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses' ORDER BY result.coursecode";
          $resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
                $_POST['totalUnit']=$row['sumunit'];       
            }
}
     //Function Curriculum
     //$ses,$sems,$lv,$prg
     function currichec($con,$mak,$ses,$semst,$lvs,$prgs)
     {
          
          //Selecting from corriculum table
          $corr=mysqli_query($con,"SELECT * FROM curriculum WHERE semester='$semst' AND level='$lvs' AND programme='$prgs' ORDER BY coursecode");

          while ($row=mysqli_fetch_assoc($corr)) 
          {
            $codex = $row['coursecode'];
            $sta = $row['status'];

            $inser=mysqli_query($con,"INSERT INTO studentcourseregistration (studentid,session,semester,coursecode,level,custatus) VALUES ('$mak','$ses','$semst','$codex','$lvs','$sta')");
          }
      }
    function deletedcourse($sdt,$ses,$sems,$lv,$prg,$con)
     {
         
    $ques= mysqli_query($con,"SELECT DISTINCT coursecode,studentid,custatus FROM studelecour WHERE coursecode NOT IN (SELECT DISTINCT coursecode FROM result WHERE studentid='$sdt') AND semester='$sems' AND studentid='$sdt' AND level<='$lv'");
          while ($rows= mysqli_fetch_array($ques)){
            $codex = $rows['coursecode'];
            $sta = $rows['custatus'];

            $inser=mysqli_query($con,"INSERT INTO studentcourseregistration (studentid,session,semester,coursecode,level,custatus) VALUES ('$sdt','$ses','$sems','$codex','$lv','$sta')");
            }
      }
function studmail($mat,$maix,$semm,$ss,$liv,$mail,$con)
{
        try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; 
        $mail->SMTPKeepAlive = true;  
        //$mail->Priority = 1;                            // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 
        //$mail->setFrom('my@uat.edu.ng', 'University of Africa');
        $mail->setFrom('my@uat.edu.ng', 'Student Course Registration Notice');
        $mail->addAddress($maix);
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Notification';
        //$content = 
        $message = '<html><body> 
        <style type="text/css">
        table{
            border: 1px solid black;
            cellspacing:0px;
            cellpadding:0px;
            width: 100%;
        }
        table td, table th{
            border: 1px solid black;
        }
        
        </style>
        
    '; 
    
          $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student JOIN faculty ON student.faculty=faculty.fid WHERE student.studentid='$mat'"));
          $message .= '<span style="text-transform:capitalize;">Student Name: <b>'.$rows['firstname']. ', '. $rows['lastname'].' '. $rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$mat.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$ss.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$semm.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Email: <b>'.$maix.'</b></span>';
          $message .= '<br><span class=MsoNormal>Faculty: <b>'.$rows['fname'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$liv.'</b></span>';
          $message .= '<p></p>';
          $message .= '<p></p>';
           //$message .= '<p align="centre"><strong> COURSE INFORMATION </strong></p>';
    $message .= '<table rules="all" border="1" cellpadding="5" cellspacing="0">
                    <tr>
                    <th>#</th>
                    <th style="width: 130px">Course Code</th>
                    <th>Course Title</th>
                    <th style="width: 120px;">Credit Unit</th>
                    <th style="width: 120px;">Status</th>
                    </tr>';
     $ques= mysqli_query($con,"SELECT * FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$mat' AND studentcourseregistration.level='$liv' AND studentcourseregistration.semester='$semm' AND studentcourseregistration.session='$ss' ORDER BY studentcourseregistration.coursecode");
      $sr =1;
      while ($row= mysqli_fetch_array($ques)){
        $message .= '<tr>';
        $message .='<td>' . $sr.'</td>';
        $message .= '<td>' . $row['coursecode'].'</td>';
        $message .= '<td>' . $row['title'].'</td>';
        $message .= '<td>' . $row['unit'].'</td>';
        $message .= '<td>' . substr($row["custatus"], 0,1).'</td>';
        
        $message.= '</tr>';
       $sr++;  
       } 
      $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$mat' AND studentcourseregistration.level='$liv' AND studentcourseregistration.semester='$semm' AND studentcourseregistration.session='$ss' ORDER BY studentcourseregistration.coursecode";
      $resultt= mysqli_query($con,$queryy);
            while($rowz = mysqli_fetch_array($resultt)){
                                  //$_POST['totalUnits']=$rowz['sumunit'];
                                  
            $message .= '<tr>';
            $message .= '<td colspan="3" align="right" font-weight="bolder">Total Credit Units </td>';
            $message .= '<td>' .$rowz['sumunit'].'</td>';
            $message .= '<td></td>';
            $message.= '</tr>';
    }                           
    $message .= '</table>';
    $message .= '</body></html>';
    //$message.='<p><strong>Please Kindly Note: That Your Course Registration For '.$semm.' '.$ss.' '.$liv.' Level Is Successfully Done Kindly Meet Your Hod For Your Result Upload</strong></p>';
    $message.='<p><strong>Please Kindly Note: That Your Course Registration '.$semm.' '.$ss.' '.$liv.' Level Is Yet Approved i.e is Pending Meet Your HoD for Approval</strong></p>';
    $message.='<p>Many Thanks</p>';
    $mail->MsgHTML($message);
    $mail->send();
    $mail->SmtpClose();
    $mail->ClearAllRecipients();
    } catch (Exception $e) {
            
        }
    //return true;
}

function hodmail($pro,$std,$semss,$sess,$ll,$mail,$mal,$con)
{
    
    $pr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$pro'"));
    $dp=$pr['did'];
    try {   
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password
        $mail->SMTPKeepAlive = true;
        //$mail->Priority = 1;
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'Student Course Registration Notice');
        $mail->addAddress($mal);
        //toload lecturer
        $sts=mysqli_query($con,"SELECT * FROM staffprive WHERE department='$dp' AND position='lecturer' ");
        $results = mysqli_fetch_all($sts,MYSQLI_ASSOC);
        foreach($results as $result)
                {
                    $stlk=mysqli_query($con,"SELECT * FROM leveladv WHERE level='$ll' AND semester='$semss' AND session='$sess' AND staffid='".$result['staffid']."' ");
                    $resulk = mysqli_fetch_all($stlk,MYSQLI_ASSOC);
                    foreach ($resulk as $kls) {
                        $stmv=mysqli_fetch_assoc(mysqli_query($con,"SELECT uniemail FROM people WHERE status='active' AND staffid='".$kls['staffid']."'"));
                        $mail->AddCC($stmv['uniemail']);
                    }
                }
    //
        //$mail->AddCC=($email_1)
        $mail->isHTML(true);
        $mail->Subject = 'Course Registration Notification';
        $message ='<html><body> 
        <style type="text/css">
        table{
            border: 1px solid black;
            cellspacing:0px;
            cellpadding:0px;
            width: 100%;
        }
        table td, table th{
            border: 1px solid black;
        }
        
        </style>
        
    '; 
    
    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student JOIN faculty ON student.faculty=faculty.fid WHERE student.studentid='$std'"));
          $message .= '<span style="text-transform:capitalize;">Student Name: <b>'.$rows['firstname']. ', '. $rows['lastname'].' '. $rows['othername'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$std.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Session: <b>'.$sess.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Semester: <b>'.$semss.'</b></span>';
          $message .= '<br><span class=MsoNormal>Faculty: <b>'.$rows['fname'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$ll.'</b></span>';
          $message .= '<p></p>';
          $message .= '<p></p>';
           //$message .= '<p align="centre"><strong> COURSE INFORMATION </strong></p>';
    $message .= '<table rules="all" border="1" cellpadding="5" cellspacing="0">
                    <tr>
                    <th>#</th>
                    <th style="width: 130px">Course Code</th>
                    <th>Course Title</th>
                    <th style="width: 120px;">Credit Unit</th>
                    <th style="width: 120px;">Status</th>
                    </tr>';
                    
     $ques= mysqli_query($con,"SELECT * FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$std' AND studentcourseregistration.level='$ll' AND studentcourseregistration.semester='$semss' AND studentcourseregistration.session='$sess' ORDER BY studentcourseregistration.coursecode");
      $sr =1;
      while ($row= mysqli_fetch_array($ques)){
        $message .= '<tr>';
        $message .='<td>' . $sr.'</td>';
        $message .= '<td>' . $row['coursecode'].'</td>';
        $message .= '<td>' . $row['title'].'</td>';
        $message .= '<td>' . $row['unit'].'</td>';
        $message .= '<td>' . substr($row["custatus"], 0,1).'</td>';
        

        $message.= '</tr>';
       $sr++;  
       } 
      $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$std' AND studentcourseregistration.level='$ll' AND studentcourseregistration.semester='$semss' AND studentcourseregistration.session='$sess' ORDER BY studentcourseregistration.coursecode";
      $resultt= mysqli_query($con,$queryy);
            while($rowz = mysqli_fetch_array($resultt)){
                                  //$_POST['totalUnits']=$rowz['sumunit'];
                                  
            $message .= '<tr>';
            $message .= '<td colspan="3" align="right" font-weight="bolder">Total Credit Units </td>';
            $message .= '<td>' .$rowz['sumunit'].'</td>';
            $message .= '<td></td>';
            $message.= '</tr>';
    }                           
    $message .= '</table>';
    $message .= '</body></html>';
    $message.='<p><strong>Please Kindly Log In To  Approved The  Course Registration '.$semss.' '.$sess.' '.$ll.' <br> click <a href="my.uat.edu.ng"> Here </a> To Login</strong></p>';
        $mail->MsgHTML($message);
    
    $mail->send();
    $mail->SmtpClose();
    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
        } catch (Exception $e) {
            
        }   
    //return true;
}

?>