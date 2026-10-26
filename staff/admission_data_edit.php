<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

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
$succ='';
if (isset($_POST['submitpd'])) {
    //echo $_SESSION['staff'];
        $til=mysqli_escape_string($con,$_POST["ptitle"]);
        $firstname=mysqli_escape_string($con,$_POST["firstname"]);
        $surname=mysqli_escape_string($con,$_POST["surname"]);
        $othername=mysqli_escape_string($con,$_POST["othername"]);
        $email=mysqli_escape_string($con,strtolower($_POST["email"]));
        $phone=mysqli_escape_string($con,$_POST["phone"]);
        $dob = mysqli_escape_string($con,$_POST["dobs"]);
        $jamb = mysqli_escape_string($con,$_POST["jamb"]);
        $jambs = mysqli_escape_string($con,$_POST["jambs"]);
        $staff=mysqli_escape_string($con,$_SESSION['staff']);
        $address=mysqli_escape_string($con,$_POST["address"]);
        $sex=mysqli_escape_string($con,$_POST["sex"]);
        $nin=mysqli_escape_string($con,$_POST["nin"]);
        /*Remember to Implement function to load the faculty name from faculty table*/      
        $marital=mysqli_escape_string($con,$_POST["marital"]);
        $country=mysqli_escape_string($con,$_POST["country"]);
        $acad=mysqli_escape_string($con,$_POST['acadsess']);
        $paddress=mysqli_escape_string($con,$_POST['paddress']);
        $phn=mysqli_escape_string($con,$_POST['phno']);
        $pnam=mysqli_escape_string($con,$_POST['pname']);
        $relat=mysqli_escape_string($con,$_POST['relat']);
        $fac=mysqli_escape_string($con,$_POST['faculty']);
        $det=mysqli_escape_string($con,$_POST['depart']);
        $prg=mysqli_escape_string($con,$_POST['programme']);
        /*olevel Result edit first sitting*/
            $exambodp=mysqli_real_escape_string($con,$_POST["exambody"]);
    $examno=trim(mysqli_real_escape_string($con,strtoupper($_POST["examno"])));
    $examyear=mysqli_real_escape_string($con, $_POST["examyea"]); 
    $school = mysqli_real_escape_string($con,$_POST["school"]);
    $eyearadm=mysqli_real_escape_string($con,$_POST['exmonth']);
    $eyeargra=mysqli_real_escape_string($con,$_POST['exyear']);
    $subje=$_POST['addcourse'];
    $grad=$_POST['grade'];
    $dis=$_POST['did'];
            /*olevel Result edit Second sitting*/
$exambodps=mysqli_real_escape_string($con,$_POST["exambodys"]);
    $examnos=trim(mysqli_real_escape_string($con,strtoupper($_POST["examnos"])));
    $examyears=mysqli_real_escape_string($con, $_POST["examyeas"]); 
    $schools = mysqli_real_escape_string($con,$_POST["schools"]);
    $eyearadms=mysqli_real_escape_string($con,$_POST['exmonths']);
    $eyeargras=mysqli_real_escape_string($con,$_POST['exyears']);
    $subjes=$_POST['addcourses'];
    $grads=$_POST['grades'];
    $diss=$_POST['dids'];
        /*Email Updating End HEre*/ 
        if($country=="Nigeria") 
            {
        $state=mysqli_escape_string($con,$_POST["state"]);
        $lga=mysqli_escape_string($con,$_POST["lga"]);
        $staname=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM state WHERE state_ID='$state' OR name='$state' "));
        $sate = $staname['name'];
        
        $recup=mysqli_query($con,"UPDATE personaldata SET faculty='$fac',department='$det',acadsession='$acad',programme='$prg',firstname='$firstname',surname='$surname',lastname='$othername',jamb_score='$jambs',jamb_no='$jamb',gender='$sex',nin='$nin',marital='$marital',dob='$dob',phone='$phone',email='$email',state='$sate',address='$address',nationality='$country',lga='$lga',date='$tod' WHERE applid='".$_SESSION['staff']."' ");
        
        $upd=mysqli_query($con,"UPDATE parentdetail SET title='$til', parentname='$pnam',address='$paddress',phone='$phn',relat='$relat' WHERE applid='".$_SESSION['staff']."'");
        
        $upd=mysqli_query($con,"UPDATE oleveldetail SET exambody='$exambodp',examno='$examno',examyear='$examyear',yearadmit='$eyearadm',yeargrad='$eyeargra',school='$school' WHERE applid='".$_SESSION['staff']."' AND noofsit='1'"); 
         foreach ($subje as $checks=> $value) {
       
        $ques=mysqli_query($con,"UPDATE olevelresultdetail SET examno='$examno',subject='".$subje[$checks]."',grade='".$grad[$checks]."' WHERE applid='".$_SESSION['staff']."'  AND id='".$dis[$checks]."' ");
      }
      
      $upd=mysqli_query($con,"UPDATE oleveldetail SET exambody='$exambodps',examno='$examnos',examyear='$examyears',yearadmit='$eyearadms',yeargrad='$eyeargras',school='$schools' WHERE applid='".$_SESSION['staff']."' AND noofsit='2'");
       foreach ($subjes as $check=> $value) {
       
      $que=mysqli_query($con,"UPDATE olevelresultdetail SET examno='$examnos',subject='".$subjes[$check]."',grade='".$grads[$check]."' WHERE applid='".$_SESSION['staff']."' AND id='".$diss[$check]."' ");
      }
      
      
      
      
            if ($recup) {
                $showModal='TRUE';
                $succ="Details Successfully Updated";
            }else{
                $error="Error Occured While Updating";
                $showModalEr='true';
            }
            }else{
                $state=mysqli_escape_string($con,$_POST['country']);
                $recup=mysqli_query($con,"UPDATE personaldata SET faculty='$fac',department='$det',acadsession='$acad',programme='$prg',firstname='$firstname',surname='$surname',lastname='$othername',jamb_score='$jambs',jamb_no='$jamb',gender='$sex',nin='$nin',marital='$marital',dob='$dob',phone='$phone',email='$email',state='$sate',address='$address',nationality='Foreign',date='$tod' WHERE applid='".$_SESSION['staff']."' ");

            if ($recup) {
                $showModal='TRUE';
                $succ="Details Successfully Updated";
            }else{
                $error="Error Occured While Updating";
                $showModalEr='true';
            }
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
            <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                   <?php
                //'subbursar' 'supbursar'

                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="sys_analyst")
                  {
                    
                    include('includes/sysbar.php');

                    }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }else if ($paych['position']=="subadmin") {
                    
                    include('includes/subbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title">Edit Applicant Data</h4>
                                
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
<?php
$paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
 if ($paych['position']=="superadmin" OR $paych['position']=="subadmin" OR $paych['position']=="sys_analyst" OR $paych['position']=="subadmin") {
  ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Application-ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" name="tellerid" autocomplete="off" required="required" placeholder="Enter Application Id">
                                                       </div>

                                                    </div>
                                                   
                                                    
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-success">Click to Verified <i class="fa fa-check-circle"></i> </button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
            <?php 
                    }else{
                    ?>
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        }
            ?>
                                                    <?php 
                                                    if (isset($_POST['submitcor'])) {
      
                                                    $teld=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
                                                    
                                                    $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$teld' "));
                                                    if ($telcheck>0) {
                                                    $stad=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$teld'"));

                                                    ?>
                                                    <section>
            
                                                    <fieldset>
                                                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                        <?php
                                                            $_SESSION['staff']=$teld;
                                                        ?>
                                                        <div class="col-lg-6">
                                                            <div class="col-lg-12">
                                                            
                                                                <div class="form-group">
                                                                    <label for="default">Firstname:</label>
                                                                    <input type="text" class="form-control"  value="<?php  echo htmlentities($stad['firstname']) ?>" autocomplete="off" name="firstname"  maxlength="17">
                                                                    
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="date">Surname:</label>
                                                                    
                                                                    <input type="text"  class="form-control"  value="<?php echo htmlentities($stad['surname']) ?>" autocomplete="off" name="surname"  maxlength="17">
                                                                   
                                                                </div>
                                                                 <div class="form-group">
                                                                    <label for="date">Othername:</label>
                                                                    
                                                                    <input type="text"  class="form-control"  value="<?php echo htmlentities($stad['lastname']) ?>"  autocomplete="off" name="othername"  maxlength="17" >
                                                                    
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="date">Email:</label>
                                                                    <input type="email"  class="form-control" autocomplete="off" value="<?php echo htmlentities($stad['email']) ?>" name="email"  maxlength="137">
                                                   
                                                                   
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="date">Phone:</label>
                                                                     <input type="tel"  class="form-control"  value="<?php echo htmlentities($stad['phone']) ?>"   name="phone" autocomplete="off"  maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                    
                                                                  
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="default">DOB</label>
                                                                    
                                                                    <input type="date"  value="<?php echo htmlentities($stad['dob']) ?>"  name="dobs" class="form-control">
                                                           
                                                        </div>
                                                        
                             <div class="form-group">
                                            <label for="date">Jamb No:</label>
            <input type="text"  class="form-control"  value="<?php echo htmlentities($stad['jamb_no']) ?>"   name="jamb" autocomplete="off"  maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></div>
                                        <div class="form-group">
                                        <label for="date">Jamb Score:</label>
            <input type="text"  class="form-control"  value="<?php echo htmlentities($stad['jamb_score']) ?>"   name="jambs" autocomplete="off"  maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'></div>
                                        


                                                        <?php 
                                                           $factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$stad['faculty']."' "));
                                                                $facu = $factn['fname'];
                                                                //$fu= $factn['fst'];
                                                                $depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='".$stad['department']."'"));
                                                            $def = $depna['dept'];
                                                        ?>
                                                        <div class="form-group">
                                                            <label for="default">Faculty</label>
                                                            <select class="form-control clid" name="faculty" id="faculty" onchange="selectfaculty()">
                                                                <option selected="selected" value="<?php echo htmlentities($stad['faculty'])?>"><?php echo htmlentities($facu) ?></option>
                                                                <?php
                                                                $res = mysqli_query($con, "SELECT * FROM faculty ORDER BY fname");
                                                                while ($row = mysqli_fetch_array($res)) {

                                                                    ?>
                                                                    <option value="<?php echo  $row["fid"]; ?>"><?php echo  $row["fname"]; ?></option>
                                                                <?php

                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
        <label for="default">Department</label>
        
         <select name="depart" class="form-control clid" id="department" onchange='selectdepart()'>
        <option selected="selected" value="<?php echo htmlentities($def) ?>"><?php echo htmlentities($def) ?></option>
        </select>
       
    </div>
    <div class="form-group">
        <label for="default">Programme</label>
         
         <select name="programme" class="form-control clid" id="program">
        <option selected="selected" value="<?php echo htmlentities($stad['programme'])?>"><?php echo htmlentities($stad['programme'])?></option>
        </select>
      
    </div>
     <div class="form-group">
    <label for="date">Home Address</label>
    <textarea name="address"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;"><?php echo htmlentities($stad['address']) ?></textarea>
</div>
                            <h3>First Sitting</h3>

                            <?php 
                                $res=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='$teld' AND noofsit='1'")); 
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
                                    $sql = "SELECT * FROM olevelresultdetail WHERE examno='".$res['examno']."' AND applid='$teld' ORDER BY id";
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
                                    <input type="hidden" name="edid[]" class="form-control" value="<?php echo htmlentities($result['examno']) ?>">
                                    </label>
                                </div>
                                <?php 
                                $cnt=$cnt+1;
                                }}
                                ?>                                                       

                                 

                                                       </div>
                                                        </div>
                                            <div class="col-lg-6">
                                                <div class="col-lg-12">
                                                     <div class="form-group">
        <label for="default">NIN:</label>
         
            <input name="nin" type="text" value="<?php echo htmlentities($stad['nin']) ?>" class="form-control clid" placeholder="Enter NIN" >
        </div>                                                                   
                                                     <div class="form-group">
                                                        <label for="default">Gender</label>
                                                         
                                                         <select name="sex" class="form-control clid" id="classid">
                                                        <option selected="selected" value="<?php echo htmlentities($stad['gender']) ?>"><?php echo htmlentities($stad['gender']) ?></option>
                                        
                                                        <?php
                                                        $res = mysqli_query($con, "SELECT * FROM genders ORDER BY gender");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["gender"]; ?>"><?php echo  $row["gender"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        </select>
                                                        
                                                    </div>
                                                    <div class="form-group">
                                                    <label for="default">Marital</label>
                                                     
                                                        <select name="marital" class="form-control clid" id="classid">
                                                        <option selected="selected" value="<?php echo htmlentities($stad['marital']) ?>"><?php echo htmlentities($stad['marital']) ?></option>
                                                      <?php
                                                        $resz = mysqli_query($con, "SELECT * FROM maritals ORDER BY marital");
                                                        while ($row = mysqli_fetch_array($resz)) {

                                                            ?>
                                                            <option value="<?php echo  $row["marital"]; ?>"><?php echo  $row["marital"]; ?></option>
                                                        <?php

                                                        }
                                                      ?>                      
                                                     </select>
                                                        
                                                    </div>
                                                   
                                   <?php 
                                    $sqlc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='$teld'"));

                                 ?>  
                                 <div class="form-group">
                                                <label for="default">Parent Title</label>
                                                
                                                 <select name="ptitle" class="form-control clid" id="classid">
                                                    <option selected value="<?php echo htmlentities($sqlc['title'])?>"><?php echo htmlentities($sqlc['title'])?></option>
                                        
                                                    <?php
                                                    $res = mysqli_query($con, "SELECT * FROM titles ORDER BY title");
                                                    while ($row = mysqli_fetch_array($res)) {

                                                        ?>
                                                        <option value="<?php echo  $row["title"]; ?>"><?php echo  $row["title"]; ?></option>
                                                    <?php

                                                    }
                                                    ?>
                                                 </select> 
                                                
                                            </div>
     <div class="form-group">
        <label for="default">Parent Name:</label>
         
            <input name="pname" type="text" value="<?php echo htmlentities($sqlc['parentname']) ?>" class="form-control clid" placeholder="Enter Parent Full-Name" >
        </div>
     
     <div class="form-group">
        <label for="default">Parent No:</label>
            <input type="text" name="phno" class="form-control clid" placeholder="Enter Parent Phone Number"  value="<?php echo htmlentities($sqlc['phone']) ?>" maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
      </div>
      <div class="form-group">
    <label for="date">Parent Address</label>
    <textarea name="paddress"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;"><?php echo htmlentities($sqlc['address']) ?></textarea>
</div>
      <div class="form-group">
        <label for="default">Relationship:</label>
            <input type="text" name="relat" class="form-control clid" value="<?php echo htmlentities($sqlc['relat']) ?>" placeholder="Enter Parent Email">
       </div>
       
                                                    <!-- Query To Load Country and Nationality Information -->
                                                    <?php 
                                                        if ($stad['nationality']=="Nigeria") {
                                                    ?>
                                                        <div class="form-group">
                                                        <label for="default">Nationality:</label>
                                                    
                                                        <input type="text" class="form-control" name="country" value="<?php echo htmlentities($stad['nationality']) ?>" readonly="readonly">                                                            
                                                     
                                                     <div class="form-group">
                                                        <label for="default">State</label>
                                                     
                                                     <select name="state" class="form-control clid" id="state" onchange="selectstate()">
                                                    <option selected="selected" value="<?php echo htmlentities($stad['state']) ?>"><?php echo htmlentities($stad['state']) ?></option>
                                                       <?php
                                                        $resz = mysqli_query($con, "SELECT * FROM state ORDER BY name");
                                                        while ($row = mysqli_fetch_array($resz)) {

                                                            ?>
                                                            <option value="<?php echo  $row["state_ID"]; ?>"><?php echo  $row["name"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                    </select>
                                                        
                                                    </div>
                                                    <div class="form-group">
                                                <label for="default">Lga</label>
                                                 
                                                     <select name="lga" class="form-control clid" id="lga" >
                                                    <option selected="selected" value="<?php echo htmlentities($stad['lga']) ?>"><?php echo htmlentities($stad['lga']) ?></option>
                                                                                            
                                                     </select>
                                                    </div>
                                                    

                                                    <?php    }else{

                                                        ?>
                                                <div class="form-group">
                                                <label for="default">Country</label>
                                                 
                                                 <select name="country" class="form-controlclid " id="classid">
                                                <option selected="selected" value="<?php echo htmlentities($stad['country']) ?>"><?php echo htmlentities($stad['country']) ?></option>
                                        <?php
                                            $resz = mysqli_query($con, "SELECT * FROM countries ORDER BY country ASC");
                                            while ($row = mysqli_fetch_array($resz)) {

                                                ?>
                                                <option value="<?php echo  $row["country"]; ?>"><?php echo  $row["country"]; ?></option>
                                            <?php

                                            }
                                            ?>
                                            </select>
                                                        </div>

                                                        
                                                   
                                                    <?php
                                                    }
                                                    ?>
                                                   <div class="form-group">
<label>Session Admitted</label>
    <select class="form-control clid" name="acadsess">
    <option selected="selected" value="<?php echo htmlentities($stad['acadsession']) ?>"><?php echo htmlentities($stad['acadsession']) ?></option>
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
                                
                            <?php 
                                $res=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='$teld' AND noofsit='2'")); 
                                if ($res!="") {
                                    $exb=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM exambodys WHERE exambodyid='".$res['exambody']."'"));
                                    $exbb=$exb['exambodyid'];
                                    $exba=$exb['exambody'];
                                    $_SESSION['exano']=$res['examno'];
                            ?>
                                 <h3>Second Sitting</h3>
                                    <div class="form-group">
                                    <label for="default">Exam-Body:</label>
                                        <select name="exambodys" class="form-control clid" id="classid" required="required">
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
                                    <input type="text" class="form-control"  value="<?php echo htmlentities($res['examno']) ?>" autocomplete="off" name="examnos" required="required" maxlength="17">
                                </div>
                                <div class="form-group">
                                    <label for="default">Exam-Year</label>
                                        <input type="text" name="examyeas" class="form-control" value="<?php echo htmlentities($res['examyear'])?>">
                                </div>
                                <div class="form-group">
                                    <label for="default">Year Entered:</label>
                                    <select name="exmonths" class="form-control" required="required">
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
                                        <select name="exyears" class="form-control" required="required">
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
                                 <textarea name="schools"  class="form-control"  style="width:100%;height: 50px;resize: none;scroll-behavior: none;" required="required"><?php echo htmlentities($res['school'])?></textarea>
                                </div>
                                </div>
                                <?php 
                                    $sql = "SELECT * FROM olevelresultdetail WHERE examno='".$res['examno']."' AND applid='$teld' ORDER BY id";
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
                                    <select name="addcourses[]" class="days"  style="width:250px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
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
                                    <select name="grades[]" class="day"style="width:200px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
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
                                    <input type="hidden" name="dids[]" class="form-control" value="<?php echo htmlentities($result['id']) ?>">
                                    </label>
                                </div>
                                <?php 
                                $cnt=$cnt+1;
                                }};
                                ?>

                                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitpd" id="submit" class="btn btn-primary form-control">Update Data <i class="fa fa-refresh"></i></button>
                                                        </div>
                                                    </div>
                                                            </div>
                                                        </div>
                                                         
                                                    </form>
                                                </fieldset>
                                                </section>
                                                    <?php
                                                          
                                                    }else{
                                                             $showModalEr='true';
                                                               $error='Student ID '.$teld.' Does not Exit or Check and Try Again';
                                                    }
                                                    } ?>
                                                   
                                              <!-- End of Section -->
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
                            <p><?php echo htmlentities($succ); ?> </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
                            <p><?php echo $error ?>.</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           
        <!-- ========== COMMON JS FILES ========== -->
        <script type="text/javascript">
                function selectstate()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fetch?facult=" + document.getElementById("state").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("lga").innerHTML = xmlhttp.responseText;

                }
                function selectfaculty()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fet?facult=" + document.getElementById("faculty").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("department").innerHTML = xmlhttp.responseText;
                }

                function selectdepart()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "feck?state=" + document.getElementById("department").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("program").innerHTML = xmlhttp.responseText;
                }
  </script>
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
    if(!empty($showModalp)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalp").modal("show");
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
    if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
            });
        </script>';
    }
    if(!empty($showModalS)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalS").modal("show");
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
?>
    </body>
</html>
<?php }
function stuname($con,$maxt)
 {
    
    $sql =mysqli_query($con,"SELECT  *  FROM  student WHERE studentid='$maxt' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $prp=$data['programme'];
        //$maks=$data['studentid'];
        $maks="../student/".$data['image'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["prp"]= $prp;
        }

 }
 function stunames($con,$max)
 {
    
    $sql =mysqli_query($con,"SELECT  *  FROM  personaldata WHERE applid='$max'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $prp=$data['programme'];
        //$maks=$data['studentid'];
        $maks="../apply/applicant/".$data['passport'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["prp"]= $prp;
        }

 }
 ?>
