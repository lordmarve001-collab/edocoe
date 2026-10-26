<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
$error='';
if(strlen($_SESSION['staffid'])=="")
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
$pgk=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pname='$pgs'"));
 $cat=$pgk['fid'];
 $dedg=$pgk['did'];

$factm=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$cat' "));
$facur=$factm['fname'];
    $depnm=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='$dedg'"));
    $defr = $depnm['dept'];
if (isset($_POST["submitpd"])) {
    if (empty(mysqli_escape_string($con,$_POST["day"]))) {
        $error="Day Required";
        $showModalEr='true';
    }elseif (empty(mysqli_escape_string($con,$_POST["month"]))){
        $error="month Required";
        $showModalEr='true';
    }elseif (empty(mysqli_escape_string($con,$_POST["year"]))) {
        $error="Year Required";
        $showModalEr='true';
    }elseif (empty(mysqli_escape_string($con,$_POST["sex"]))) {
        $error="Gender Required";
        $showModalEr='true';
    }
    elseif (empty(mysqli_escape_string($con,$_POST["marital"]))) {
        $error="Marital Required";
        $showModalEr='true';
    }elseif (empty(mysqli_escape_string($con,$_POST["country"]))) {
        
        $error="Select Nationality";
        $showModalEr='true';
    }
    else{
        
        $firstname=mysqli_escape_string($con,$_POST["firstname"]);
        $surname=mysqli_escape_string($con,$_POST["surname"]);
        $lastname=mysqli_escape_string($con,$_POST["lastname"]);
        $email=mysqli_escape_string($con,strtolower($_POST["email"]));
        $phone=mysqli_escape_string($con,$phf);
        $day = mysqli_escape_string($con,$_POST["day"]);
        $year = mysqli_escape_string($con,$_POST["year"]);
        $jamb_score = mysqli_escape_string($con,$_POST["jamb_score"]);
        $jamb_no = mysqli_escape_string($con,$_POST["jamb_no"]);
        $month =mysqli_escape_string($con, $_POST["month"]);
        $dob = $year."-".$month."-".$day;
        $staffid=mysqli_escape_string($con,$_POST["staffid"]);
        $address=mysqli_escape_string($con,$_POST["address"]);
        $town=mysqli_escape_string($con,$_POST["town"]);
        $sex=mysqli_escape_string($con,$_POST["sex"]);
        $fact=mysqli_escape_string($con,$cat);
        $depn=mysqli_escape_string($con,$dedg);
        $marital=mysqli_escape_string($con,$_POST["marital"]);
        $country=mysqli_escape_string($con,$_POST["country"]);
		$filenamg =basename($_FILES['passport']['name']);
        //$filename = $_FILES['passport']['name'];
        $filetmp =$_FILES['passport']['tmp_name'];
        $filesize = $_FILES['passport']['size'];
        $fileinfo = @getimagesize($_FILES["passport"]["tmp_name"]);
                    $width = $fileinfo[0];
                    $height = $fileinfo[1];
                    $extension = array("jpg","jpeg","JPG","JPEG");
                     // Get image file extension
                    $file_extension = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
                    // Validate file input to check if is not empty
                    if (!file_exists($_FILES["passport"]["tmp_name"])) {
                         $error="Choose image file to upload.";
                         $showModalEr='true';
                        
                    }    // Validate file input to check if is with valid extension
                    else if (!in_array($file_extension, $extension)) {
                    $error="Upload valid images. Only .jpeg and .jpg are allowed.";
                    $showModalEr='true';
                    } else if (($_FILES["passport"]["size"] > 500000)) {
                        $error="Image size exceeds 500KB.";
                        $showModalEr='true';
                        }   // Validate image file size
                    else{
                        $nwtourimg = explode(".", $filenamg);
                        $fileExt = end($nwtourimg);
                        //unlink($filetmp);
                        $res = trim(str_ireplace( array( '\'', '"',
                      ',' , ';', '<', '>','/','-','_','' ), '', $staffid));
                        $fileBase = $res.".".$fileExt;
                        $dir = "photo/";
                        $final_dir= $dir . $fileBase;
        $programs=$pgs;
     
            if($country=="Nigeria") 
            {
            if (empty(mysqli_escape_string($con,$_POST["state"]))) {
            $error="Please select state";
            $showModalEr='true';
            //header("Refresh:1;");
            }elseif (empty(mysqli_escape_string($con,$_POST["lga"]))) {
                $error="Please select lga";
                $showModalEr='true';
            }
            if (($filesize > 500000)) {
                $error="file too large, filesize should be 100kb";
                $showModalEr='true';
            }
            else{
                /*Script checking the numbers of Registration on the database*/
            $sq=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$staffid'");
            if (mysqli_num_rows($sq)==1) {
            $error="You have Register";
            $showModalEr='true';
                }
            /*Ends here*/
                else
                {
                    //avoiding of duplicate of data of student in the table
                    $sql="SELECT * FROM personaldata WHERE (email='$email' OR phone='$phone') AND acadsession='$admy' ";
                    $res=mysqli_query($con,$sql);
                    if (mysqli_num_rows($res) > 0) {
                        $row = mysqli_fetch_assoc($res);
                        if ($email==$row['email']) {
                            
                            $error="Email already exist";
                            $showModalEr='true';
                        }
                        elseif ($phone==$row['phone']) {
                            
                            $error="phone number already exist";
                            $showModalEr='true';
                        }
                    }
                    else
                        {
                $state=mysqli_escape_string($con,$_POST["state"]);
                $lga=mysqli_escape_string($con,$_POST["lga"]);
                //Selecting State Record when inserting and convert it from id to Name before insertinto table
                $staname=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM state WHERE state_ID='$state' "));
                $sate = $staname['name'];
                // echo $sate;
                if(move_uploaded_file($filetmp, $final_dir))
                {
                    $regist=mysqli_query($con,"INSERT INTO personaldata(firstname,surname,lastname,applid,email,phone,dob,address,gender,marital,nationality,state,lga,town,acadsession,faculty,department,programme,passport,jamb_score,jamb_no,status) VALUES ('$firstname','$surname','$lastname','$staffid','$email','$phone','$dob','$address','$sex','$marital','$country','$sate','$lga','$town','$admy','$fact','$depn','$programs','$final_dir','$jamb_score','$jamb_no','active')");
                if ($regist) {
                                        
                                            header("location: appsummary");
                                            //echo 1;
                                        } else {
                                           $error="Sorry Error Occur When Submitting";
                                            $showModalEr='true';
                                           
                                        }
                }
            }
        }
    }
            }
            elseif ($country=="Foreign")
            {
            if(empty(mysqli_escape_string($con,$_POST['states']))) {
                $error="Please select Country";
                $showModalEr='true';
                }
                if (($filesize > 5000000)) {
                $error="file too large, filesize should be 100kb";
                $showModalEr='true';
            }
            else{
                /*Script checking the numbers of Registration on the database*/
            $sq=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$staffid'");
            if (mysqli_num_rows($sq)==1) {
            $error="You have Register";
            $showModalEr='true';
                }
            /*Ends here*/
                else
                {
                    //avoiding of duplicate of data of student in the table
                    $sql="SELECT * FROM personaldata WHERE (email='$email' OR phone='$phone') AND acadsession='$admy'";
                    $res=mysqli_query($con,$sql);
                    if (mysqli_num_rows($res) > 0) {
                        $row = mysqli_fetch_assoc($res);
                        if ($email==$row['email']) {
                            
                            $error="Email already exist";
                           $showModalEr='true';
                        }
                        elseif ($phone==$row['phone']) {
                            # code...
                            $error="phone number already exist";
                           $showModalEr='true';
                        }
                    }
                    else
                        {
                $state=mysqli_escape_string($con,$_POST['states']);
                // echo $sate;
                if(move_uploaded_file($filetmp, $final_dir))
                {
                    $regist=mysqli_query($con,"INSERT INTO personaldata(firstname,surname,lastname,applid,email,phone,dob,address,gender,marital,nationality,state,town,acadsession,faculty,department,programme,passport,status) VALUES ('$firstname','$surname','$lastname','$staffid','$email','$phone','$dob','$address','$sex','$marital','$country','$state','$town','$admy','$fact','$depn','$programs','$final_dir','active')");
                if ($regist) {
           
                        header("location: appsummary");
                      
                    } else {
                      
                        $error="Sorry Error Occur When Submitting";
                        $showModalEr='true';
                    }
                }
            }
        }
    }
            }//End of the Code
        }
    }
}
if (isset($_POST['submitpdu'])) {
    
        $firstname=mysqli_escape_string($con,$_POST["firstname"]);
        $surname=mysqli_escape_string($con,$_POST["surname"]);
        $othername=mysqli_escape_string($con,$_POST["othername"]);
        $email=mysqli_escape_string($con,strtolower($_POST["email"]));
        $phone=mysqli_escape_string($con,$_POST["phone"]);
        $dob = mysqli_escape_string($con,$_POST["dobs"]);
        $staffid=mysqli_escape_string($con,$_SESSION["staffid"]);
        $address=mysqli_escape_string($con,$_POST["address"]);
        $sex=mysqli_escape_string($con,$_POST["sex"]);
        /*Remember to Implement function to load the faculty name from faculty table*/      
        $marital=mysqli_escape_string($con,$_POST["marital"]);
        $country=mysqli_escape_string($con,$_POST["country"]);
        
        $fac=mysqli_escape_string($con,$_POST['faculty']);
        $det=mysqli_escape_string($con,$_POST['depart']);
        $prg=mysqli_escape_string($con,$_POST['programme']);
        $pd=mysqli_fetch_assoc(mysqli_query($con,"SELECT pname FROM program WHERE pid='$prg'"));
         if ($pd=='') {
           $prohk=$prg;
        }else{
            $prohk=$pd['pname'];
        }
        /*Email Updating End HEre*/
        if($country=="Nigeria") 
            {
        $state=mysqli_escape_string($con,$_POST["state"]);
        $lga=mysqli_escape_string($con,$_POST["lga"]);
        $staname=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM state WHERE state_ID='$state' OR name='$state' "));
        $sate = $staname['name'];
        $recup=mysqli_query($con,"UPDATE personaldata SET faculty='$fac',department='$det',programme='$prohk',firstname='$firstname',surname='$surname',lastname='$othername',gender='$sex',marital='$marital',dob='$dob',phone='$phone',email='$email',state='$sate',address='$address',nationality='$country',lga='$lga' WHERE applid='".$_SESSION['staffid']."' ");
            if ($recup) {
                $showModal='TRUE';
                $succ="Details Successfully Updated";
            }else{
                $error="Error Occured While Updating";
                $showModalEr='true';
            }
            }else{
                $state=mysqli_escape_string($con,$_POST['country']);
                $recup=mysqli_query($con,"UPDATE personaldata SET faculty='$fac',department='$det',programme='$prohk',firstname='$firstname',surname='$surname',lastname='$othername',gender='$sex',marital='$marital',dob='$dob',phone='$phone',email='$email',address='$address',state='$state',country='Foreign' WHERE applid='".$_SESSION['staffid']."' ");
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
        <link rel="stylesheet" href="css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel.min.css" media="screen" >
        <script type="text/javascript" src="js/state.js"></script>
      <script type="text/javascript" src="js/pic.js"></script>
      <script type="text/javascript" src="js/country.js"></script>
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
                                    <h2 class="title">Personal Data</h2>
                                
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
                                            $stad=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE applid='".$_SESSION['staffid']."' ")); 
                                            if ($stad=='') {
                                               ?>
                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                               <div class="col-lg-6">
                                                   <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="default" class="col-sm-2 control-label">Firstname:</label>
                                                             <div class="col-sm-10">
                                                                <input type="text" class="form-control"  value="<?php  echo $firstname ?>" autocomplete="off" name="firstname" required="required" maxlength="17">
                                                             </div>
                                                        </div>
                                                        <div class="form-group">
                                                        <label for="date" class="col-sm-2 control-label ">Surname:</label>
                                                        <div class="col-sm-10">
                                                            <input type="text"  class="form-control"  value="<?php echo $lastname ?>" autocomplete="off" name="surname"  maxlength="17">
                                                        </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date" class="col-sm-2 control-label">OtherName:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="lastname" maxlength="17">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="date" class="col-sm-2 control-label">Applid:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text"  class="form-control"  autocomplete="off" name="staffid" required="required" value="<?php echo $staffid; ?>" readonly="readonly">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date" class="col-sm-2 control-label">Email:</label>
                                                            <div class="col-sm-10">
                                                                <input type="email"  class="form-control" autocomplete="off" value="<?php echo $has; ?>" name="email" required="required" readonly="readonly" maxlength="137">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                        <label for="date" class="col-sm-2 control-label">Phone:</label>
                                                        <div class="col-sm-10">
                                                            <input type="tel"  readonly='readonly' class="form-control"  name="phone" value="<?php echo $phf ?>" autocomplete="off" required="required" maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                <label for="date" class="col-sm-2 control-label">Home Address:</label>
                                <div class="col-sm-10">
                            <div  id="subject">
                               <textarea name="address"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required"></textarea>
                            </div>
                                </div>
                            </div>                             <h4>Jamb Record</h4>
                            <div class="form-group">
                                                            <label for="date" class="col-sm-2 control-label">Jamb no:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="jamb_no" maxlength="17">
                                                            </div>
                                                        </div>
 <div class="form-group">
                                                            <label for="date" class="col-sm-2 control-label">Jamb Score:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="jamb_score" maxlength="17">
                                                            </div>
                                                        </div>

                            <h4>Programme Information</h4>
                            <div class="form-group">
                            <label for="default" class="col-sm-2 control-label">School:</label>
                             <div class="col-sm-10">
                            <input type="text" name="faculty" class="form-control" readonly value="<?php  echo htmlentities($facur) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" style='text-align:left;font-size: 12px;' class="col-sm-2 control-label">Department:</label>
                             <div class="col-sm-10">
                             <input type="text" name="depart" class="form-control clid" value="<?php echo htmlentities($defr)?>" readonly>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default" style='text-align:left;font-size: 12px;'  class="col-sm-2 control-label">Programme:</label>
                             <div class="col-sm-10">
                             <input type="text"  name="programme"  value="<?php echo htmlentities($pgs) ?>" class="form-control clid" id="program" readonly>
                            
                            </div>
                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="col-lg-12">
                                                    <div class="form-group">
                                                    <label for="default" class="col-sm-2 control-label">DOB</label>
                                                     <div class="col-sm-10">
                                                     <!-- <select name="class" class="form-control clid"> -->
                                                    <select name="day" class="dayss" required="required" style="text-transform: uppercase;width: 100px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                                    <option selected="selected" disabled="disabled">Day</option>
                                                    <option value='01'>01</option>
                                        <option value='02'>02</option>
                                        <option value='03'>03</option>
                                        <option value='04'>04</option>
                                        <option value='05'>05</option>
                                        <option value='06'>06</option>
                                        <option value='07'>07</option>
                                        <option value='08'>08</option>
                                        <option value='09'>09</option>
                                        <option value='10'>10</option>
                                        <option value='11'>11</option>
                                        <option value='12'>12</option>
                                        <option value='13'>13</option>
                                        <option value='14'>14</option>
                                        <option value='15'>15</option>
                                        <option value='16'>16</option>
                                        <option value='17'>17</option>
                                        <option value='18'>18</option>
                                        <option value='19'>19</option>
                                        <option value='20'>20</option>
                                        <option value='21'>21</option>
                                        <option value='22'>22</option>
                                        <option value='23'>23</option>
                                        <option value='24'>24</option>
                                        <option value='25'>25</option>
                                        <option value='26'>26</option>
                                        <option value='27'>27</option>
                                        <option value='28'>28</option>
                                        <option value='29'>29</option>
                                        <option value='30'>30</option>
                                        <option value='31'>31</option>
                                    </select>
                                    <select name="month" class="days" required="required" style="text-transform: uppercase;
    width: 100px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Month</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                    <select name="year" class="day" required="required"style="text-transform: uppercase;
    width: 100px;text-transform: capitalize;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                        <option selected="selected" disabled="disabled">Year</option>
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
                                                    </div>
                                                    <div class="form-group">
<label for="default" class="col-sm-2 control-label">Gender</label>
 <div class="col-sm-10">
 <select name="sex" class="form-control clid" id="classid" required="required">
<option selected disabled>Please select Gender</option>
                                        
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
                                                    </div>
<div class="form-group">
<label for="default" class="col-sm-2 control-label">Marital</label>
 <div class="col-sm-10">
 <select name="marital" class="form-control clid" id="classid" required="required">
<option selected disabled>Please select Marital</option>
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
                                                    </div>
<div class="form-group">
<label for="default" class="col-sm-2 control-label">Nationality</label>
 <div class="col-sm-10">
 
<select name="country"  class="form-control clid" id="show" onchange="change(this)" class="state">
    <option disabled="disabled" selected="selected">Select Nationality</option>
    <option value="Nigeria">Nigeria</option>
    <option value="Foreign">Foreign</option>
</select>
                                                        </div>
                                                    </div>
<div id="retCustDetails" class="custDetails" style="display:none">
 <div class="form-group">
<label for="default" class="col-sm-2 control-label">State</label>
 <div class="col-sm-10">
 <select name="state" class="form-control clid" id="state" onchange="selectstate()">
<option selected disabled>Please select State</option>
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
                                                    </div>
                                                    <div class="form-group">
<label for="default" class="col-sm-2 control-label">Lga</label>
 <div class="col-sm-10">
 <select name="lga" class="form-control clid" id="lga" >
<option selected disabled>Please select Lga</option>
                                        
 </select>
                                                        </div>
                                                    </div>
                                                </div>
<!-- For Foreign Country -->
<div style="display:none" id="tradeCustDetails" class="custDetails" >
    <div class="form-group">
<label for="default" class="col-sm-2 control-label">Country</label>
 <div class="col-sm-10">
 <select name="states" class="form-control clid" id="classid">
<option selected disabled>Please select Country</option>
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
                                                                                            </div>
                                        </div>
                                                                                                 
                                            
                            <div style="margin:auto;max-width: 200px;">
                            <table>

                                <tr>
                                    <td>
                                        <img id="blah" src="img/avater.png" alt="Your Passport">
                                        <script>
                                            function readURL(input) {
                                                if (input.files && input.files[0]) {
                                                    var reader = new FileReader();

                                                    reader.onload = function(e) {
                                                        $('#blah')
                                                            .attr('src', e.target.result)

                                                    };

                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }
                                        </script>
                                    </td>
                                </tr>
                                <tr>
                                    <th><input type="file" class="form-control" name="passport" required="required" accept=".jpeg,.jpg" onchange="readURL(this);" style="width: 209px;margin: auto;">
                                        
                                    </th>
                                </tr>
                            </table>
                        </div>
                                                    <!-- Sepreaaater -->
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitpd" id="submit" class="btn btn-primary form-control">Submit Data</button>
                                                        </div>
                                                </div>
                                                </div>
                                            </form>

                                            </div>
                                        </div>
                        <?php
                                            }else{
                                            ?>
                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                        <?php
                                                            $_SESSION['staff']=$_SESSION['staffid'];
                                                            $dpfd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pname='".$stad['programme']."' "));
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
                                                                    <label for="default">DOBy</label>
                                                                    
                                                                    <input type="date"  value="<?php echo htmlentities($stad['dob']) ?>"  name="dobs" class="form-control">
                                                           
                                                        </div>
                                                               <div class="form-group">
                                                                    <label for="default">Jamb No</label>
                                                                    
                                                                    <input type="text"  value="<?php echo htmlentities($stad['jamb_no']) ?>"  name="jamb_no" class="form-control">
                                                           
                                                        </div><div class="form-group">
                                                                    <label for="default">Jamb Score</label>
                                                                    
                                                                    <input type="text"  value="<?php echo htmlentities($stad['jamb_score']) ?>"  name="jamb_score" class="form-control">
                                                           
                                                        </div>
                                                        <?php 
                                                           $factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$stad['faculty']."' "));
                                                                $facu = $factn['fname'];
                                                                //$fu= $factn['fst'];
                                                                $depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='".$stad['department']."'"));
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
        <option selected="selected" value="<?php echo htmlentities($stad['department']) ?>"><?php echo htmlentities($def) ?></option>
        </select>
       
    </div>
    <div class="form-group">
        <label for="default">Programme</label>
         
         <select name="programme" class="form-control clid" id="program">
        <option selected="selected" value="<?php echo htmlentities($dpfd['pid'])?>"><?php echo htmlentities($stad['programme'])?></option>
        </select>
      
    </div>
     

                                                       </div>
                                                        </div>
                                            <div class="col-lg-6">
                                                <div class="col-lg-12">
                                                                                                                        
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
                                                        if ($stad['nationality']=="Nigeria") {
                                                    ?>
                                                        <div class="form-group">
                                                        <label for="default">Nationality:</label>
                                                    
                                                        <input type="text" class="form-control" name="country" value="<?php echo htmlentities($stad['nationality']) ?>" readonly="readonly">                                                      </div>      
                                                     
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
    <label for="date">Home Address</label>
    <textarea name="address"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;"><?php echo htmlentities($stad['address']) ?></textarea>
</div>
                                            
                                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitpdu" id="submit" class="btn btn-primary form-control">Update Data <i class="fa fa-refresh"></i></button>
                                                        </div>
                                                    </div>
                                                            </div>
                                                        </div>
                                                         
                                                    </form>
                                            <?php
                                            }
                                            ?>
                                            
                                    </div>
                                    <!-- /.col-md-12 -->
                                </div>
                    </div>
                </div>
                <!-- /.content-container -->
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
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
       <script src="js/main.js"></script>
        <script type="text/javascript">
    
                function selectstate(){
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fetch?facult=" + document.getElementById("state").value, false);
                    xmlhttp.send(null);
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
    </body>
</html>
<?php }

if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    }
 ?>
