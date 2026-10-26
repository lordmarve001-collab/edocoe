<?php
session_start();
$error='';
ob_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
include '../include/dbconfig.php';

$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];

$prog='Application Fee';
/*Start Here*/
 /*End Here*/

 if (isset($_POST["submitrecord"])) {
            // AssinData To Variable
            $fname = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["firstname"])));
            $sname = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["surname"])));
            $lname = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["lastname"])));
            $sta = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["sta"])));
            $marital = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["marital"])));
            $phone = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["phone"])));
            $sex = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["sex"])));
            $school = trim(ucfirst(mysqli_escape_string($con, $_REQUEST["school"])));
            $address = strtolower(mysqli_escape_string($con, $_REQUEST["address"]));
            $email = strtolower(mysqli_escape_string($con, $_REQUEST["email"]));
            $lga = strtolower(mysqli_escape_string($con, $_REQUEST["lga"]));
            $stafftype = strtolower(mysqli_escape_string($con, $_REQUEST["stafftype"]));
            $dfa = strtolower(mysqli_escape_string($con, $_REQUEST["dfa"]));
            $grade = strtolower(mysqli_escape_string($con, $_REQUEST["grade"]));
            $rank = strtolower(mysqli_escape_string($con, $_REQUEST["rank"]));
            $qualification = strtolower(mysqli_escape_string($con, $_REQUEST["qualification"]));
            $special = strtolower(mysqli_escape_string($con, $_REQUEST["special"]));
            $nok = strtolower(mysqli_escape_string($con, $_REQUEST["nok"]));
            $nok_email = strtolower(mysqli_escape_string($con, $_REQUEST["nok_email"]));
            $nok_phone = strtolower(mysqli_escape_string($con, $_REQUEST["nok_phone"]));
            $nok_address = strtolower(mysqli_escape_string($con, $_REQUEST["nok_address"]));
            $published = strtolower(mysqli_escape_string($con, $_REQUEST["published"]));
            $books = strtolower(mysqli_escape_string($con, $_REQUEST["books"]));
            $confe = strtolower(mysqli_escape_string($con, $_REQUEST["confe"]));
            $profe = strtolower(mysqli_escape_string($con, $_REQUEST["profe"]));
            $blood = strtolower(mysqli_escape_string($con, $_REQUEST["blood"]));
            $genotype = strtolower(mysqli_escape_string($con, $_REQUEST["genotype"]));
            $title = strtolower(mysqli_escape_string($con, $_REQUEST["title"]));
            $department = strtolower(mysqli_escape_string($con, $_REQUEST["department"]));
            $dep = strtolower(mysqli_escape_string($con, $_REQUEST["dep"]));
            $unit = strtolower(mysqli_escape_string($con, $_REQUEST["unit"]));
            $ict = strtolower(mysqli_escape_string($con, $_REQUEST["ict"]));
            $country = strtolower(mysqli_escape_string($con, $_REQUEST["country"]));
            $state = strtolower(mysqli_escape_string($con, $_REQUEST["state"]));
            $religion = strtolower(mysqli_escape_string($con, $_REQUEST["religion"]));
            $tribe = strtolower(mysqli_escape_string($con, $_REQUEST["tribe"]));
            $disability = strtolower(mysqli_escape_string($con, $_REQUEST["disability"]));
            $emergency = strtolower(mysqli_escape_string($con, $_REQUEST["emergency"]));
            $spouse = strtolower(mysqli_escape_string($con, $_REQUEST["spouse"]));
            $sphone = strtolower(mysqli_escape_string($con, $_REQUEST["sphone"]));
            $salary = strtolower(mysqli_escape_string($con, $_REQUEST["salary"]));

            $city = mysqli_real_escape_string($con, $_REQUEST["city"]);
            $lga = mysqli_real_escape_string($con, $_REQUEST["lga"]);
            $day = mysqli_escape_string($con,$_REQUEST["day"]);
            $month = mysqli_escape_string($con,$_REQUEST["month"]);
            $year = mysqli_escape_string($con,$_REQUEST["year"]);
                    $dob = $year."-".$month."-".$day;
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
                      ',' , ';', '<', '>','/','-','_','' ), '', $sta));
                        $fileBase = $res.".".$fileExt;
                        $dir = "photo/";
                        $final_dir= $dir . $fileBase;
        $programs=$pgs;
     
            //$hashedPW = $escapedPW;
            
                
                $sql="SELECT * FROM staffdata WHERE (sta='$sta' OR phone='$phone') ";
                    $res=mysqli_query($con,$sql);
                    if (mysqli_num_rows($res) > 0) {
                        $row = mysqli_fetch_assoc($res);
                        if ($sta==$row['sta']) {
                            
                            $error="Staff Id already exist";
                            $showModalEr='true';
                        }
                        elseif ($phone==$row['phone']) {
                            
                            $error="Phone number already exist";
                            $showModalEr='true';
                        }
                    }
            /*Ends here*/
                else
                if(move_uploaded_file($filetmp, $final_dir))
                
                {
                    //inserting to the database
                   $qu = mysqli_query($con, "INSERT INTO staffdata(sta,firstname,surname,othername,sex,marital,dob,phone,email,address,lga,stafftype,dfa,grade,rank,qualification,special,school,nok,nok_email,nok_phone,nok_address,published,book,conf,prof,blood,genotype,title,passport,department,dep,unit,ict,country,state,religion,tribe,disability,emergency,spouse,sphone,salary) 
                    VALUES ('$sta','$fname','$sname','$lname','$sex','$marital','$dob','$phone','$email','$address', '$lga','$stafftype','$dfa','$grade','$rank','$qualification','$special','$school','$nok','$nok_email','$nok_phone','$nok_address','$published','$books','$confe','$profe','$blood','$genotype','$title','$final_dir','$department','$dep','$unit','$ict','$country','$state','$religion','$tribe','$disability','$emergency','$spouse','$sphone','$salary')");
                    if ($qu > 0) {
                        $succ = "Staff Record of $fname $lname With Staff ID $sta is Successfully submited ";
                        $showModal = 'true';
                    } else {
                        $error = "Error Occured While Submiting!!";
                        $showModalEr = 'true';
                    }
                }}}
                ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
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
<body>
    <?php include('include/menubar.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="page-head-line"></h5>
                </div>
            </div><div class="col-md-6">
                                    <h2 class="title">Staff Data Capture</h2>
                                
                                </div>
                                        <div class="container-fluid">
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
                                               
                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                             <h4>Personal Data</h4>

                            <label>Title:</label>
                            <select name="title" class="form-control" id="default" required="required">
                                
                        <option selected="selected" disabled="disabled">Select Title</option>
                        <option  value="Mr.">Mr.</option>
                        <option  value="Mrs." >Mrs.</option>
                        <option  value="Miss" >Miss</option>
                        <option  value="Dr," >Dr.</option>
                        <option  value="Prof." >Prof.</option>
                        <option  value="Engr." >Engr.</option>
                        <option  value="Sir" >Sir</option>

                         </select>
                        </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    First Name:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="firstname" class="form-control clid" required="required">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Surname:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="surname" class="form-control clid" required="required">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Other Name:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="lastname" class="form-control clid">
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Phone No:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="phone" class="form-control clid" required="required">
                                                                
                                                            </div> 
                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Email:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="email" class="form-control clid" >
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                    <label for="default">Date of Birth</label>
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
                                        $earliest_year = 1960;

                                        $latest_year = date('Y');
                                        foreach (range($latest_year, $earliest_year) as $i) {
                                           
                                            echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>                        </div>

                                                            <div class="form-group">
                            <label>Contact Address:</label>
                             
                               <textarea name="address"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required"></textarea>
                             </div>
                             <div class="form-group">
                            <label>Gender:</label>
                            <select name="sex" class="form-control" id="default" >
                                
                        <option selected="selected" disabled="disabled">Select Gender</option>
                        <option  value="Male">Male</option>
                        <option  value="Female">Female</option>

                         </select>
                        </div>
                        <div class="form-group">
                            <label>Marital Status:</label>
                            <select name="marital" class="form-control" id="default" >
                                
                        <option selected="selected" disabled="disabled">Select Gender</option>
                        <option  value="Single">single</option>
                        <option  value="Married">Married</option>
                        <option  value="Divorce">Divorce</option>
                        <option  value="Seperated">Seperated</option>

                         </select>
                        </div>
                        <div class="form-group">
                                                                <label for="default">
                                                                    Spouse Name:
                                                                </label>
                                                                <input type="text" maxlength="38" name="spouse" class="form-control clid" >
                                                            </div><div class="form-group">
                                                                <label for="default">
                                                                    Spouse Phone:
                                                                </label>
                                                                <input type="text" maxlength="38" name="sphone" class="form-control clid" >
                                                            </div>
                        <div class="form-group">
                            <label for="default">Religion:</label>
                            <input type="text" class="form-control"  name="religion">
                            
                        </div> <div class="form-group">
                            <label for="default">Tribe:</label>
                            <input type="text" class="form-control"  name="tribe">
                            
                        </div>
                                                    
                             <div class="form-group">
                            <label>Blood Group:</label>
                            <select name="blood" class="form-control" id="default" >
                                
                        <option selected="selected" disabled="disabled">Select Blood Group</option>
                        <option  value="A+">A+</option>
                        <option  value="A-">A-</option>
                        <option  value="B+">B+</option>
                        <option  value="B-">B-</option>
                        <option  value="O+">O+</option>
                        <option  value="O-">O-</option>
                        <option  value="AB+">AB+</option>
                        <option  value="AB-">AB-</option>

                         </select>
                        </div>
                        
                             <div class="form-group">
                            <label>Genotype:</label>
                            <select name="genotype" class="form-control" id="default">
                                
                        <option selected="selected" disabled="disabled">Select Genotype</option>
                        <option  value="AA">AA</option>
                        <option  value="AS">AS</option>
                        <option  value="SS">SS</option>

                         </select>
                        </div> <div class="form-group">
<label for="default" >Nationality</label>

<select name="country"  class="form-control clid" id="show" onchange="change(this)" class="state">
    <option disabled="disabled" selected="selected">Select Nationality</option>
    <option value="Nigeria">Nigeria</option>
</select>
                                                    </div>
<div id="retCustDetails" class="custDetails" style="display:none">
 <div class="form-group">
<label for="default">State</label>
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
                                                    <div class="form-group">
<label for="default">Lga</label>
 <select name="lga" class="form-control clid" id="lga" >
<option selected disabled>Please select Lga</option>
                                        
 </select>
                                                    </div>
                                                </div>
                                                                <div class="form-group">
                                                                <label for="default">
                                                                    Emergency Contact:
                                                                </label>
                                                                <input type="text" maxlength="38" name="emergency" class="form-control clid" >
                                                            </div>
                                                            
                                                                    <div class="form-group">
                            <label>Disability (Do you have Any Disability):</label>
                             
                               <textarea name="disability"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" placeholder="If yes, Please State Here"></textarea>
                             </div> 
                                                                    

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="col-lg-12">
                                                            
                                                     
                             
                                                            
<!-- For Foreign Country -->
<h4>Enployment Information (With EdoCoe)</h4>

                                                            <div class="form-group">
                                                                <label for="default">
                                                                    Staff ID:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="sta" class="form-control clid" required="required">
                                                                
                                                            </div>
                                                             <div class="form-group">
                            <label>Staff Type:</label>
                            <select name="stafftype" class="form-control" id="default" required="required">
                                
                        <option selected="selected" disabled="disabled">Select Staff Type</option>
                        <option  value="Academic">Academic</option>
                        <option  value="Non Academic" >Non Academic</option>
                       
                         </select>
                        </div>
                        <div class="form-group">
                                                                <label for="default">
                                                                    Date of first Appointment:
                                                                </label>
                                                                
                                                                    <input type="date" maxlength="38" name="dfa" class="form-control clid" >
                                                                
                                                            </div>
                                                             <div class="form-group">
                                                                <label for="default">
                                                                    Grade Level:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="grade" class="form-control clid" >
                                                                
                                                            </div>
                                                             <div class="form-group">
                                                                <label for="default">
                                                                    Rank:
                                                                </label>
                                                                
                                                                    <input type="text" maxlength="38" name="rank" class="form-control clid" >
                                                                
                                                            </div>
                                                             <div class="form-group">
                                                                    <input type="hidden" maxlength="38" value="Subvention" name="salary" class="form-control clid">
                                                                
                                                            </div>
                                                            <h4>Qualification/School Information</h4>
                            <div class="form-group">
                            <label for="default"> Qualification:</label>
                            <input type="text" class="form-control"    name="qualification" >
                        </div>
                        <div class="form-group">
                            <label for="default">Area of Specialization:</label>
                            <input type="text" class="form-control"  name="special">
                            
                        </div>

                                                    <div class="form-group">
                            <label>Campus:</label>
                            <select name="type" class="form-control" id="default" required="required">
                                
                        <option selected="selected" disabled="disabled">Select Campus</option>
                        <option  value="Abudu">Abudu</option>
                        <option  value="Afuze" >Afuze</option>
                        <option  value="Igueben" >Igueben</option>

                         </select>
                        </div>
                        <h4>Department and Unit (Non Academic Staff Only)</h4>
                            <div class="form-group">
                            <label for="default">Department:</label>
                            <input type="text" class="form-control"    name="dep" >
                        </div>
                        <div class="form-group">
                            <label for="default">Unit:</label>
                            <input type="text" class="form-control"  name="unit">
                            
                        </div>

                        <h4>Next of Kin Information</h4>
                             
                            <div class="form-group">
                                                            <label for="date" >Name of Next of Kin:</label>
                                                                <input type="text" class="form-control"   autocomplete="off" name="nok">
                                                        </div>
 <div class="form-group">
                                                            <label for="date">Next Of Kin Email:</label>
                                                                <input type="text" class="form-control"   autocomplete="off" name="nok_email" >
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date">Next Of Kin Phone:</label>
                                                                <input type="text" class="form-control"   autocomplete="off" name="nok_phone">
                                                        </div>  <div class="form-group">
                                <label for="date">Next Of Kin Address:</label>
                            <div  id="subject">
                               <textarea name="nok_address"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required"></textarea>
                                </div>
                            </div>                   
                                     <h4>Publication Information (Academic Staff Only)</h4>
                                                             <div class="form-group">
<label for="default">School</label>
 <select name="school" class="form-control clid" id="classid" required="required">
<option selected disabled>Please School</option>
                                        
                                        <?php
                                        $res = mysqli_query($con, "SELECT * FROM faculty WHERE prog='NCE' ORDER BY fname");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["fname"]; ?>"><?php echo  $row["fname"]; ?></option>
                                        <?php

                                        }
                                        ?>
 </select>
                                                       
                                                    </div>
                                                    <div class="form-group">
<label for="default">Departments</label>
 <select name="department" class="form-control clid" id="classid" required="required">
<option selected disabled>Please Department</option>
                                        
                                        <?php
                                        $res = mysqli_query($con, "SELECT * FROM department WHERE prog='NCE' ORDER BY dept");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["dept"]; ?>"><?php echo  $row["dept"]; ?></option>
                                        <?php

                                        }
                                        ?>
 </select>
                                                       
                                                    </div>
                             
                            <div class="form-group">
                                                            <label for="date">Number of Publication(s):</label>
                                                                <input type="text" class="form-control"   name="published">
                                                        </div>
 <div class="form-group">
                                                            <label for="date">Number of Chapter in a Book:</label>
                                                                <input type="text" class="form-control"    name="books">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date">Number of Conferences/Workshop Attended:</label>
                                                                <input type="text" class="form-control"    name="confe">
                                                        </div>     <div class="form-group">
                                                            <label for="date">Member of Professional Body:</label>
                                                                <input type="text" class="form-control"    name="profe">
                                                        </div> 
                                                        <div class="form-group">
                                                            
                            <label>Can you use computer?:</label>
                            <select name="comp" class="form-control" id="default" required="required">
                                
                        <option selected="selected" disabled="disabled">Select Staff Type</option>
                        <option  value="Yes">Yes</option>
                        <option  value="No" >No</option>
                       
                         </select>
                        </div>
                        <div class="form-group">
                                <label for="date">If Yes, Please State  package Below:</label>
                            <div  id="ict">
                               <textarea name="ict"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" placeholder="MS Word, Excel" ></textarea>
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
                                                            <div class="form-group">
                                                                <div class="">
                                                                    <button type="submit" name="submitrecord" id="submit" class="btn btn-primary form-control">Submit Data</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                        
                                            
                                    </div>
                                    <!-- /.col-md-12 -->
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
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
                            <p><?php echo htmlentities($succ) ?></p>
                            <p><?php echo htmlentities($suc) ?></p>
                            <p><?php echo htmlentities($su) ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-success" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('include/footer.php');?>
    <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
       <script src="js/main.js"></script>
       <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
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
    if(!empty($showModalEri)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalErl").modal("show");
            });
        </script>';
    }
?>
</html>