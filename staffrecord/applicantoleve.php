<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
if(!isset($_SESSION['staffid']))
    {   
    header("Location:logout"); 
    }
    else{
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
}
$error='';
$succ='';
if(isset($_POST["submitol"])){
 if (empty($_POST["exambody"])) {
        $error="Exam Body Required";
       $showModalEr='true';
    }elseif (empty($_POST["month"])) {
        $error="Month Required";
        $showModalEr='true';
    }elseif (empty($_POST["year"])) {
        $error=" Year Required";
        $showModalEr='true';
    }
    elseif (empty($_POST["grade1"])) {
        $error="Grade For General Mathematic Required";
        $showModalEr='true';
    }elseif (empty($_POST['grade2'])) {
        $error="Grade For English-Language Required";
        $showModalEr='true';
    }elseif(empty($_POST['addcourse3'])){
        $error="Subject 3 Required";
        $showModalEr='true';
    }elseif (empty($_POST['addcourse4'])) {
        $error="Subject 4 Required";
        $showModalEr='true';
    }elseif (empty($_POST['addcourse5'])) {
        $error="Subject 5 Required";
        $showModalEr='true';
    }elseif (empty($_POST['grade3'])) {
        $error="Grade For Subject 3";
        $showModalEr='true';
    }elseif (empty($_POST['grade4'])) {
        $error="Grade For Subject 4";
        $showModalEr='true';
    }elseif (empty($_POST['grade5'])) {
        $error="Grade For Subject 5";
        $showModalEr='true';
    }
    else{
    $exambodp=mysqli_real_escape_string($con,$_POST["exambody"]);
    /*$examno=mysqli_real_escape_string($con,strtoupper($_POST["examno"]));*/
    if ($_POST["examno"]!='') {
    $examno=trim(mysqli_real_escape_string($con,strtoupper($_POST["examno"])));
    }else{
       $examno=mysqli_real_escape_string($con,strtoupper($_SESSION['staffid'])); 
    }
    $year = mysqli_real_escape_string($con,$_POST["year"]);
    $month = mysqli_real_escape_string($con,$_POST["month"]);
    $examyear=mysqli_real_escape_string($con, $month."-".$year); 
    $school = mysqli_real_escape_string($con,$_POST["school"]);
    $eyearadm=mysqli_real_escape_string($con,$_POST['exmonth']);
    $eyeargra=mysqli_real_escape_string($con,$_POST['exyear']);
    /*For Subject and Grade Entry*/
    $addcourse=mysqli_real_escape_string($con,$_POST["addcourse1"]);
    $grade=mysqli_real_escape_string($con,$_POST["grade1"]);
    $addcourse1=mysqli_real_escape_string($con,$_POST["addcourse2"]);
    $grade1=mysqli_real_escape_string($con,$_POST["grade2"]);
    $addcourse2=mysqli_real_escape_string($con,$_POST["addcourse3"]);
    $grade2=mysqli_real_escape_string($con,$_POST["grade3"]);
    $addcourse3=mysqli_real_escape_string($con,$_POST["addcourse4"]);
    $grade3=mysqli_real_escape_string($con,$_POST["grade4"]);
    $addcourse4=mysqli_real_escape_string($con,$_POST["addcourse5"]);
    $grade4=mysqli_real_escape_string($con,$_POST["grade5"]);
    $addcours=$_POST['addcourse'];
    $addgrad=$_POST['grade'];
    $subjet=array($addcourse,$addcourse1,$addcourse2,$addcourse3,$addcourse4);
    $subje=array_merge($subjet,$addcours);
    $gradt=array($grade,$grade1,$grade2,$grade3,$grade4);
    $grad=array_merge($gradt,$addgrad);
    $pdp=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM exambodys WHERE exambodyid='$exambodp'"));
    if ($pdp['exambodyid']=='') {
       $error='Exam Body Selected Not Available';
       $showModalEr='true';
    }else{
        $exambody=$pdp['exambodyid'];
    $sql=mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='".$_SESSION['staffid']."' AND examno='$examno'");
    if(mysqli_num_rows($sql)>0){
    $error="Detail Already Exit";
    $showModalEr='true';
    }else{
    if ($eyearadm>=$eyeargra) {

            $error="Graduating Year Must Be Greater Than Admit Year";
            $showModalEr='true';

        }else{
            //inserting to the database
        $qu=mysqli_query($con,"INSERT INTO  oleveldetail(applid,exambody,examno,examyear,yearadmit,yeargrad,school,noofsit) VALUES ('".$_SESSION['staffid']."','$exambody','$examno','$examyear','$eyearadm','$eyeargra','$school','1')");
            foreach($subje As $index => $value){
             //echo $grad[$index];
            $ques=mysqli_query($con,"INSERT INTO olevelresultdetail(applid,examno,subject,grade) VALUES ('".$_SESSION['staffid']."','$examno','".$subje[$index]."','".$grad[$index]."')");
                }
            if ($qu>0) {
            $succ='Successfully submited';
            //header("Refresh:2;  applicolsub");
            }
            else
            {
            $error="Error Occured While Submitting";
            $showModalEr='true';
            }
      
            }
        }   
      }
    }
}
  if(isset($_POST["submitoll"])){
 if (empty($_POST["exambody"])) {
        $error="Exam Body Required";
       $showModalEr='true';
    }elseif (empty($_POST["month"])) {
        $error="Month Required";
        $showModalEr='true';
    }elseif (empty($_POST["year"])) {
        $error=" Year Required";
        $showModalEr='true';
    }
    elseif (empty($_POST["grade1"])) {
        $error="Grade For General Mathematic Required";
        $showModalEr='true';
    }elseif (empty($_POST['grade2'])) {
        $error="Grade For English-Language Required";
        $showModalEr='true';
    }elseif(empty($_POST['addcourse3'])){
        $error="Subject 3 Required";
        $showModalEr='true';
    }elseif (empty($_POST['addcourse4'])) {
        $error="Subject 4 Required";
        $showModalEr='true';
    }elseif (empty($_POST['addcourse5'])) {
        $error="Subject 5 Required";
        $showModalEr='true';
    }elseif (empty($_POST['grade3'])) {
        $error="Grade For Subject 3";
        $showModalEr='true';
    }elseif (empty($_POST['grade4'])) {
        $error="Grade For Subject 4";
        $showModalEr='true';
    }elseif (empty($_POST['grade5'])) {
        $error="Grade For Subject 5";
        $showModalEr='true';
    }
    else{
    $exambodp=mysqli_real_escape_string($con,$_POST["exambody"]);
    if ($_POST["examno"]!='') {
    $examno=trim(mysqli_real_escape_string($con,strtoupper($_POST["examno"])));
    }else{
       $examno=mysqli_real_escape_string($con,strtoupper($_SESSION['staffid'])); 
    }
    //$areas= $_POST["areas"];
    // /$siting= mysqli_real_escape_string($con,$_POST["siting"]);
    $year = mysqli_real_escape_string($con,$_POST["year"]);
    $month = mysqli_real_escape_string($con,$_POST["month"]);
    $examyear=mysqli_real_escape_string($con, $month."-".$year); 
    $school = mysqli_real_escape_string($con,$_POST["school"]);
    $eyearadm=mysqli_real_escape_string($con,$_POST['exmonth']);
    $eyeargra=mysqli_real_escape_string($con,$_POST['exyear']);
    /*For Subject and Grade Entry*/
    $addcourse=mysqli_real_escape_string($con,$_POST["addcourse1"]);
    $grade=mysqli_real_escape_string($con,$_POST["grade1"]);
    $addcourse1=mysqli_real_escape_string($con,$_POST["addcourse2"]);
    $grade1=mysqli_real_escape_string($con,$_POST["grade2"]);
    $addcourse2=mysqli_real_escape_string($con,$_POST["addcourse3"]);
    $grade2=mysqli_real_escape_string($con,$_POST["grade3"]);
    $addcourse3=mysqli_real_escape_string($con,$_POST["addcourse4"]);
    $grade3=mysqli_real_escape_string($con,$_POST["grade4"]);
    $addcourse4=mysqli_real_escape_string($con,$_POST["addcourse5"]);
    $grade4=mysqli_real_escape_string($con,$_POST["grade5"]);
    $addcours=$_POST['addcourse'];
    $addgrad=$_POST['grade'];
    $subjet=array($addcourse,$addcourse1,$addcourse2,$addcourse3,$addcourse4);
    $subje=array_merge($subjet,$addcours);
    $gradt=array($grade,$grade1,$grade2,$grade3,$grade4);
    $grad=array_merge($gradt,$addgrad);
    $pdp=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM exambodys WHERE exambodyid='$exambodp'"));
    if ($pdp['exambodyid']=='') {
       $error='Exam Body Selected Not Available';
       $showModalEr='true';
    }else{
        $exambody=$pdp['exambodyid'];
    $sql=mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='".$_SESSION['staffid']."' AND examno='$examno'");
    if(mysqli_num_rows($sql)>0){
    $error="Detail Already Exit";
    $showModalEr='true';
    }else{
    if ($eyearadm>=$eyeargra) {

            $error="Graduating Year Must Be Greater Than Admit Year";
            $showModalEr='true';

        }else{
            //inserting to the database
        $qu=mysqli_query($con,"INSERT INTO  oleveldetail(applid,exambody,examno,examyear,yearadmit,yeargrad,school,noofsit) VALUES ('".$_SESSION['staffid']."','$exambody','$examno','$examyear','$eyearadm','$eyeargra','$school','2')");
            foreach($subje As $index => $value){
        
                $ques=mysqli_query($con,"INSERT INTO olevelresultdetail(applid,examno,subject,grade) VALUES ('".$_SESSION['staffid']."','$examno','".$subje[$index]."','".$grad[$index]."')");
                }
            if ($qu>0) {
            $succ='Successfully submited';
            //header("Refresh:2;  applicolsub");
            }
            else
            {
            $error="Error Occured While Submitting";
            $showModalEr='true';
            }
      
            }
        }   
      }
    }
}
/*for update*/
if (isset($_POST['submitup'])) {
    $exambodp=mysqli_real_escape_string($con,$_POST["exambody"]);
    $examno=trim(mysqli_real_escape_string($con,strtoupper($_POST["examno"])));
    $examyear=mysqli_real_escape_string($con, $_POST["examyea"]); 
    $school = mysqli_real_escape_string($con,$_POST["school"]);
    $eyearadm=mysqli_real_escape_string($con,$_POST['exmonth']);
    $eyeargra=mysqli_real_escape_string($con,$_POST['exyear']);
    $subje=$_POST['addcourse'];
    $grad=$_POST['grade'];
    $dis=$_POST['did'];
    $upd=mysqli_query($con,"UPDATE oleveldetail SET exambody='$exambodp',examno='$examno',examyear='$examyear',yearadmit='$eyearadm',yeargrad='$eyeargra',school='$school' WHERE applid='".$_SESSION['staffid']."' AND noofsit='1'");        
      
      foreach ($subje as $checks=> $value) {
       
        $ques=mysqli_query($con,"UPDATE olevelresultdetail SET examno='$examno',subject='".$subje[$checks]."',grade='".$grad[$checks]."' WHERE applid='".$_SESSION['staffid']."'  AND id='".$dis[$checks]."' ");
      }
      
      //}
   //}
}

if (isset($_POST['submitupp'])) {
    $exambodp=mysqli_real_escape_string($con,$_POST["exambody"]);
    $examno=trim(mysqli_real_escape_string($con,strtoupper($_POST["examno"])));
    $examyear=mysqli_real_escape_string($con, $_POST["examyea"]); 
    $school = mysqli_real_escape_string($con,$_POST["school"]);
    $eyearadm=mysqli_real_escape_string($con,$_POST['exmonth']);
    $eyeargra=mysqli_real_escape_string($con,$_POST['exyear']);
    $subje=$_POST['addcourse'];
    $grad=$_POST['grade'];
    $dis=$_POST['did'];
    //echo $_SESSION['uid'];
    $upd=mysqli_query($con,"UPDATE oleveldetail SET exambody='$exambodp',examno='$examno',examyear='$examyear',yearadmit='$eyearadm',yeargrad='$eyeargra',school='$school' WHERE applid='".$_SESSION['staffid']."' AND noofsit='2'");
       foreach ($subje as $check=> $value) {
       
      $que=mysqli_query($con,"UPDATE olevelresultdetail SET examno='$examno',subject='".$subje[$check]."',grade='".$grad[$check]."' WHERE applid='".$_SESSION['staffid']."' AND id='".$dis[$check]."' ");
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
  <?php include('include/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php include('include/leftbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Secondary School Data</h2>
                                
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
                    <!-- For First Siting Query -->
                    <div class="col-lg-6">
                        <div class="col-lg-12">
                            <h3>First Sitting</h3>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <?php 
                                $res=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='".$_SESSION['staffid']."' AND noofsit='1'")); 
                                if ($res!="") {
                                    $exb=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM exambodys WHERE exambodyid='".$res['exambody']."'"));
                                    $exbb=$exb['exambodyid'];
                                    $exba=$exb['exambody'];
                                    $_SESSION['exano']=$res['examno'];
                            ?>
                                 
                                    <div class="form-group">
                                    <label for="default">Exam-Body:</label>
                                        <select name="exambody" class="form-control clid" id="classid" required="required">
                                            <option selected value="<?php echo htmlentities($exbb)?>"><?php echo htmlentities($exba) ?></option>
                                            <?php
                                            $resz = mysqli_query($con, "SELECT * FROM exambodys ORDER BY exambody");
                                            while ($row = mysqli_fetch_array($resz)) {

                                            ?>
                                                <option value="<?php echo  $row["exambodyid"]; ?>"><?php echo  $row["exambody"]; ?></option>
                                            <?php

                                            }
                                            ?>
                                         </select>
                                </div>
                                <div class="form-group">
                                    <label for="default">Examno:</label>
                                    <input type="text" class="form-control"  value="<?php echo htmlentities($res['examno']) ?>" autocomplete="off" name="examno" required="required" maxlength="17">
                                </div>
                                <div class="form-group">
                                    <label for="default">Exam-Year</label>
                                        <input type="text" name="examyea" class="form-control" value="<?php echo htmlentities($res['examyear'])?>">
                                </div>
                                <div class="form-group">
                                    <label for="default">Year Entered:</label>
                                    <select name="exmonth" class="form-control" required="required">
                                        <option selected="selected" value="<?php echo htmlentities($res['yearadmit'])?>"><?php echo htmlentities($res['yearadmit'])?></option>
                                        <?php
                                        
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                     </div>
                                <div class="form-group">
                                    <label for="default">Year Graduated:</label>
                                        <select name="exyear" class="form-control" required="required">
                                            <option selected="selected" value="<?php echo htmlentities($res['yeargrad'])?>"><?php echo htmlentities($res['yeargrad'])?></option>
                                        <?php
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                 <div class="form-group">
                                  <label for="date" >School-Name:</label>
                                <div  id="subject">
                                 <textarea name="school"  class="form-control"  style="width:100%;height: 50px;resize: none;scroll-behavior: none;" required="required"><?php echo htmlentities($res['school'])?></textarea>
                                </div>
                                </div>
                                <?php 
                                    $sql = "SELECT * FROM olevelresultdetail WHERE examno='".$res['examno']."' AND applid='".$_SESSION['staffid']."' ORDER BY id";
                                $query=mysqli_query($con,$sql);
                                $results=mysqli_fetch_all($query,MYSQLI_ASSOC);
                                $cnt=1;
                                foreach($results as $result)
                                { 

                                    $_SESSION['uid'] =$result['id'];
                                    //echo $_SESSION['uid'];
                                    ?><h4>Mininum selection is 5 Subjects</h4>
                                    <div class="form-group">
                                    <label for="default"><?php echo $cnt?>. 
                                    <select name="addcourse[]" class="days"  style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" value="<?php echo htmlentities($result['subject'])?>"><?php echo htmlentities($result['subject'])?></option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day"style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" value="<?php echo htmlentities($result['grade']);?>"><?php echo htmlentities($result['grade']);?></option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <input type="hidden" name="did[]" class="form-control" value="<?php echo htmlentities($result['id']) ?>">
                                    <input type="hidden" name="edid[]" class="form-control" value="<?php echo htmlentities($result['examno']) ?>">
                                    </label>
                                </div>
                                <?php 
                                $cnt=$cnt+1;
                                }
                                ?>
                                <div class="form-group">
                                <div class="">
                                    <button type="submit" name="submitup" id="submit" class="btn btn-primary form-control">Update Olevel Data <i class="fa fa-refresh"></i></button>
                                </div>
                               </div> 
                                </form>
                            <?php 
                                }else{ ?>
                                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                       <div class="form-group">
                                    <label for="default">Exam-Body:</label>
                                        <select name="exambody" class="form-control clid" id="classid" required="required">
                                            <option selected disabled>Select Exambody</option>
                                            <?php
                                            $resz = mysqli_query($con, "SELECT * FROM exambodys ORDER BY exambody");
                                            while ($row = mysqli_fetch_array($resz)) {

                                            ?>
                                                <option value="<?php echo  $row["exambodyid"]; ?>"><?php echo  $row["exambody"]; ?></option>
                                            <?php

                                            }
                                            ?>
                                         </select>
                                </div>
                                <div class="form-group">
                                    <label for="default">Examno:</label>
                                    <input type="text" class="form-control"  autocomplete="off" name="examno" maxlength="17">
                                </div>
                                <div class="form-group">
                                    <label for="default">Exam-Year</label>
                                        <select name="month" class="days" required="required" style="width: 250px;text-transform: uppercase;width: 200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                            <option selected="selected" disabled="disabled">Month</option>
                                            <?php
                                                    $resz = mysqli_query($con, "SELECT * FROM examyear ORDER BY year");
                                                    while ($row = mysqli_fetch_array($resz)) {

                                                    ?>
                                                        <option value="<?php echo  $row["year"]; ?>"><?php echo  $row["year"]; ?></option>
                                                    <?php

                                                    }
                                            ?>
                                    </select>
                                    <select name="year" class="day" required="required"style="width: 250px;text-transform: uppercase;width: 200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Year</option>
                                        <?php
                                        
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="default">Year Entered:</label>
                                    <select name="exmonth" class="form-control" required="required">
                                        <option selected="selected" disabled="disabled">Year Entered</option>
                                        <?php
                                        
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                     </div>
                                <div class="form-group">
                                    <label for="default">Year Graduated:</label>
                                        <select name="exyear" class="form-control" required="required">
                                            <option selected="selected" disabled="disabled">Year Graduated</option>
                                        <?php
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                 <div class="form-group">
                                  <label for="date" >School-Name:</label>
                                <div  id="subject">
                                 <textarea name="school"  class="form-control"  style="width:100%;height: 50px;resize: none;scroll-behavior: none;" required="required"></textarea>
                                </div>
                                </div><h5>Minimum Selection is 5 Subjects</h5>
                                <div class="form-group">
                                    <label for="default">1. 
                                    <select name="addcourse1" class="days"  style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" value="General Mathematics" readonly>General Mathematics</option>
                                       
                                    </select>
                                    <select name="grade1" class="day"style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">2. 
                                    <select name="addcourse2" class="days" style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" value="English-Language">English-Language</option>
                                       
                                    </select>
                                    <select name="grade2" class="day" style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">3. 
                                    <select name="addcourse3" class="days" style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade3" class="day" required="required"style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">4. 
                                    <select name="addcourse4" class="days"  style="width: 250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade4" class="day" style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">5. 
                                    <select name="addcourse5" class="days" style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade5" class="day" style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">6. 
                                    <select name="addcourse[]" class="days" style="width: 250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day" style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">7. 
                                    <select name="addcourse[]" class="days" style="width: 250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day" style="width:200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">8. 
                                    <select name="addcourse[]" class="days"  style="width: 250px;text-transform: uppercase;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day" style="width:200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="default">9. 
                                    <select name="addcourse[]" class="days" style="width: 250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day" style="width: 200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                <div class="">
                                    <button type="submit" name="submitol" id="submit" class="btn btn-primary form-control">Save Before You Continue <i class="fa fa-save"></i></button>
                                </div>
                               </div> 
                                    </form>
                               <?php 
                                    }
                                ?>
                        </div>
                    </div>
                     <div class="col-lg-6">
                        <div class="col-lg-12">
                            <h3>Second Sitting</h3>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <?php 
                                $res=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='".$_SESSION['staffid']."' AND noofsit='2'")); 
                                if ($res!="") {
                                    $exb=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM exambodys WHERE exambodyid='".$res['exambody']."'"));
                                    $exbb=$exb['exambodyid'];
                                    $exba=$exb['exambody'];
                                    $_SESSION['exano']=$res['examno'];
                            ?>
                                 
                                    <div class="form-group">
                                    <label for="default">Exam-Body:</label>
                                        <select name="exambody" class="form-control clid" id="classid" required="required">
                                            <option selected value="<?php echo htmlentities($exbb) ?>"><?php echo htmlentities($exba) ?></option>
                                            <?php
                                            $resz = mysqli_query($con, "SELECT * FROM exambodys ORDER BY exambody");
                                            while ($row = mysqli_fetch_array($resz)) {

                                            ?>
                                                <option value="<?php echo  $row["exambodyid"]; ?>"><?php echo  $row["exambody"]; ?></option>
                                            <?php

                                            }
                                            ?>
                                         </select>
                                </div>
                                <div class="form-group">
                                    <label for="default">Examno:</label>
                                    <input type="text" class="form-control"  value="<?php echo htmlentities($res['examno']) ?>" autocomplete="off" name="examno" required="required" maxlength="17">
                                </div>
                                <div class="form-group">
                                    <label for="default">Exam-Year</label>
                                        <input type="text" name="examyea" class="form-control" value="<?php echo htmlentities($res['examyear'])?>">
                                </div>
                                <div class="form-group">
                                    <label for="default">Year Entered:</label>
                                    <select name="exmonth" class="form-control" required="required">
                                        <option selected="selected" value="<?php echo htmlentities($res['yearadmit'])?>"><?php echo htmlentities($res['yearadmit'])?></option>
                                        <?php
                                        
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                     </div>
                                <div class="form-group">
                                    <label for="default">Year Graduated:</label>
                                        <select name="exyear" class="form-control" required="required">
                                            <option selected="selected" value="<?php echo htmlentities($res['yeargrad'])?>"><?php echo htmlentities($res['yeargrad'])?></option>
                                        <?php
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                 <div class="form-group">
                                  <label for="date" >School-Name:</label>
                                <div  id="subject">
                                 <textarea name="school"  class="form-control"  style="width:100%;height: 50px;resize: none;scroll-behavior: none;" required="required"><?php echo htmlentities($res['school'])?></textarea>
                                </div>
                                </div>
                                <?php 
                                    $sql = "SELECT * FROM olevelresultdetail WHERE examno='".$res['examno']."' AND applid='".$_SESSION['staffid']."' ORDER BY id";
                                $query=mysqli_query($con,$sql);
                                $results=mysqli_fetch_all($query,MYSQLI_ASSOC);
                                $cnt=1;
                                foreach($results as $result)
                                { 

                                    $_SESSION['uid'] =$result['id'];
                                    //echo $_SESSION['uid'];
                                    ?>
                                    <div class="form-group">
                                    <label for="default"><?php echo $cnt?>. 
                                    <select name="addcourse[]" class="days"  style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" value="<?php echo htmlentities($result['subject'])?>"><?php echo htmlentities($result['subject'])?></option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day"style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" value="<?php echo htmlentities($result['grade']);?>"><?php echo htmlentities($result['grade']);?></option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <input type="hidden" name="did[]" class="form-control" value="<?php echo htmlentities($result['id']) ?>">
                                    </label>
                                </div>
                                <?php 
                                $cnt=$cnt+1;
                                }
                                ?>
                                <div class="form-group">
                                <div class="">
                                    <button type="submit" name="submitupp" id="submit" class="btn btn-primary form-control">Update Olevel Data <i class="fa fa-refresh"></i></button>
                                </div>
                               </div> 
                                </form>
                            <?php 
                                }else{ ?>
                                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                       <div class="form-group">
                                    <label for="default">Exam-Body:</label>
                                        <select name="exambody" class="form-control clid" id="classid" required="required">
                                            <option selected disabled>Select Exambody</option>
                                            <?php
                                            $resz = mysqli_query($con, "SELECT * FROM exambodys ORDER BY exambody");
                                            while ($row = mysqli_fetch_array($resz)) {

                                            ?>
                                                <option value="<?php echo  $row["exambodyid"]; ?>"><?php echo  $row["exambody"]; ?></option>
                                            <?php

                                            }
                                            ?>
                                         </select>
                                </div>
                                <div class="form-group">
                                    <label for="default">Examno:</label>
                                    <input type="text" class="form-control"  autocomplete="off" name="examno"  maxlength="17">
                                </div>
                                <div class="form-group">
                                    <label for="default">Exam-Year</label>
                                        <select name="month" class="days" required="required" style="width: 250px;text-transform: uppercase;width: 200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                            <option selected="selected" disabled="disabled">Month</option>
                                            <?php
                                                    $resz = mysqli_query($con, "SELECT * FROM examyear ORDER BY year");
                                                    while ($row = mysqli_fetch_array($resz)) {

                                                    ?>
                                                        <option value="<?php echo  $row["year"]; ?>"><?php echo  $row["year"]; ?></option>
                                                    <?php

                                                    }
                                            ?>
                                    </select>
                                    <select name="year" class="day" required="required"style="width: 250px;text-transform: uppercase;width: 200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Year</option>
                                        <?php
                                        
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="default">Year Entered:</label>
                                    <select name="exmonth" class="form-control" required="required">
                                        <option selected="selected" disabled="disabled">Year Entered</option>
                                        <?php
                                        
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                     </div>
                                <div class="form-group">
                                    <label for="default">Year Graduated:</label>
                                        <select name="exyear" class="form-control" required="required">
                                            <option selected="selected" disabled="disabled">Year Graduated</option>
                                        <?php
                                        $currently_selected = date('Y');
                                        $earliest_year = 1990;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                            // Prints the option with the next year in range.
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                 <div class="form-group">
                                  <label for="date" >School-Name:</label>
                                <div  id="subject">
                                 <textarea name="school"  class="form-control"  style="width:100%;height: 50px;resize: none;scroll-behavior: none;" required="required"></textarea>
                                </div>
                                </div>
                                <div class="form-group">
                                    <label for="default">1. 
                                    <select name="addcourse1" class="days"  style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" value="General Mathematics" readonly>General Mathematics</option>
                                    </select>
                                    <select name="grade1" class="day"style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">2. 
                                    <select name="addcourse2" class="days" style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" value="English-Language" >English-Language</option>
                                      
                                    </select>
                                    <select name="grade2" class="day" style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">3. 
                                    <select name="addcourse3" class="days" style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade3" class="day" required="required"style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">4. 
                                    <select name="addcourse4" class="days"  style="width: 250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade4" class="day" style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">5. 
                                    <select name="addcourse5" class="days" style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade5" class="day" style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">6. 
                                    <select name="addcourse[]" class="days" style="width: 250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day" style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">7. 
                                    <select name="addcourse[]" class="days" style="width: 250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day" style="width:200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div><div class="form-group">
                                    <label for="default">8. 
                                    <select name="addcourse[]" class="days"  style="width: 250px;text-transform: uppercase;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day" style="width:200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="default">9. 
                                    <select name="addcourse[]" class="days" style="width: 250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Subject</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM waecsubject ORDER BY subject");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["subject"]; ?>"><?php echo  $row["subject"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    <select name="grade[]" class="day" style="width: 200px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Select Grade</option>
                                       <?php 
                                            $res = mysqli_query($con,"SELECT * FROM grades ORDER BY grade");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo  $row["grade"]; ?>"><?php echo  $row["grade"]; ?></option>
                                            <?php

                                            }
                                             ?>
                                    </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                <div class="">
                                    <button type="submit" name="submitoll" id="submit" class="btn btn-primary form-control">Save Olevel Data <i class="fa fa-save"></i></button>
                                </div>
                               </div> 
                                    </form>
                               <?php 
                                    }
                                ?>
                        </div>
                    </div>
                    <!--  -->
                    
                </div>
                <a href="applicparent"><button class="btn btn-primary">Complete and Next Page</button></a>
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
        <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog  modal-dialog-scrollable" role="document">
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
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
       <script src="js/main.js"></script>

        
        <script>
            

        </script>
       
    </body>
</html>
<?php 
if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    }


} ?>
