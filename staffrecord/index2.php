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

if (isset($_POST["registering"])) {
    if (empty(mysqli_escape_string($con,$_POST["staffid"]))) {
        $error="Staff ID is Required";
        $showModalEr='true';
    }elseif (empty(mysqli_escape_string($con,$_POST["dfa"]))){
        $error="Date of First Appointment is Required";
        $showModalEr='true';
    }elseif (empty(mysqli_escape_string($con,$_POST["marital"]))) {
        $error="Marital Status Required";
        $showModalEr='true';
    }elseif (empty(mysqli_escape_string($con,$_POST["sex"]))) {
        $error="Gender Required";
        $showModalEr='true';
    }
    elseif (empty(mysqli_escape_string($con,$_POST["country"]))) {
        
        $error="Select Nationality";
        $showModalEr='true';
    }
    else{
        $staffid=mysqli_escape_string($con,$_POST["staffid"]);
        $firstname=mysqli_escape_string($con,$_POST["firstname"]);
        $surname=mysqli_escape_string($con,$_POST["surname"]);
        $lastname=mysqli_escape_string($con,$_POST["lastname"]);
        $email=mysqli_escape_string($con,strtolower($_POST["email"]));
        $phone=mysqli_escape_string($con,$phf);
        $day = mysqli_escape_string($con,$_POST["day"]);
        $year = mysqli_escape_string($con,$_POST["year"]);
        $stafftype = mysqli_escape_string($con,$_POST["stafftype"]);
        $phone = mysqli_escape_string($con,$_POST["phone"]);
        $dfa = mysqli_escape_string($con,$_POST["dfa"]);        
        $grade = mysqli_escape_string($con,$_POST["grade"]);
        $rank = mysqli_escape_string($con,$_POST["rank"]);
        $qual = mysqli_escape_string($con,$_POST["qual"]);
        $spacial = mysqli_escape_string($con,$_POST["special"]);
        $school = mysqli_escape_string($con,$_POST["school"]);
        $campus = mysqli_escape_string($con,$_POST["campus"]);
        $disability = mysqli_escape_string($con,$_POST["disability"]);
        $blood = mysqli_escape_string($con,$_POST["blood"]);
        $genotype = mysqli_escape_string($con,$_POST["genotype"]);
        $nok = mysqli_escape_string($con,$_POST["nok"]);
        $nok_email = mysqli_escape_string($con,$_POST["nok_email"]);
        $nok_phone = mysqli_escape_string($con,$_POST["nok_phone"]);
        $nok_address = mysqli_escape_string($con,$_POST["nok_address"]);
        $published = mysqli_escape_string($con,$_POST["published"]);
        $books = mysqli_escape_string($con,$_POST["books"]);
        $confe = mysqli_escape_string($con,$_POST["confe"]);
        $profe = mysqli_escape_string($con,$_POST["profe"]);
        $month =mysqli_escape_string($con, $_POST["month"]);
        $dob = $year."-".$month."-".$day;
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

          
            
                /*Script checking the numbers of Registration on the database*/
            $sq=mysqli_query($con,"SELECT * FROM peoples WHERE staffid='$staffid'");
            if (mysqli_num_rows($sq)==1) {
            $error="You have Register";
            $showModalEr='true';
                }
            /*Ends here*/
                else
                {
                    //avoiding of duplicate of data of student in the table
                    $sql="SELECT * FROM peoples WHERE (email='$email' OR phone='$phone') ";
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
                    
                // echo $sate;
                if(move_uploaded_file($filetmp, $final_dir))
                {
                        $regist=mysqli_query($con,"INSERT INTO peoples(staffid, status, firstname, lastname, othername, gender, maritalstatus, dob, phoneno, email, state, address, image, country, lga, stafftype, dfa, grade, rank, qual, spacial, school, campus, disability, blood, genotype, nok, nok_email, nok_phone, nok_address, pub, book, con, prof) VALUES ('$taffid', 'active', '$firstname', '$surname', '$lastname', '$sex', '$marital', '$dob', '$phone', '$email', '$state', '$address', '$final_dir', '$country', '$lag', '$stafftype', '$dfa', '$grade', '$rank', '$qual', '$spacial', '$school', '$campus', '$disability', '$blood', '$genotype', '$nok', '$nok_email', '$nok_phone', '$nok_address', '$published', '$books', '$confe', '$profs')");

  if ($regist) {
                                        
                                            header("location: appsummary");
                                            //echo 1;
                                        } else {
                                           $error="Sorry Error Occur When Submitting $staffid";
                                            $showModalEr='true';
                                           
                                        }
                }
            }
        }
    }
            
        
    
}
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
                                                                                <label for="surname">First Name</label>

                                                             <div class="col-sm-10">
                                                                <input type="text" class="form-control"  autocomplete="off" name="firstname" required="required" maxlength="17">
                                                             </div>
                                                        </div>
                                                        <div class="form-group">
                                                        <label for="date" >Surname:</label>
                                                        <div class="col-sm-10">
                                                            <input type="text"  class="form-control"  autocomplete="off" name="surname"  maxlength="17">
                                                        </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date" >OtherName:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="lastname" maxlength="17">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="date">Staff ID:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text"  class="form-control"  autocomplete="off" name="staffid" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date">Email:</label>
                                                            <div class="col-sm-10">
                                                                <input type="email"  class="form-control" autocomplete="off"  name="email" required="required"  maxlength="137">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                        <label for="date">Phone No:</label>
                                                        <div class="col-sm-10">
                                                            <input type="tel"   class="form-control"  name="phone" autocomplete="off" required="required" maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                <label for="date">Home Address:</label>
                                <div class="col-sm-10">
                            <div  id="subject">
                               <textarea name="address"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required"></textarea>
                            </div>
                                </div>
                            </div>                             <h4>Appointment Record With EdoCoe</h4>
                             <div class="form-group">
<label for="default">Staff Type</label>
 <div class="col-sm-10">
 <select name="marital" class="form-control clid" id="classid" required="required">
<option selected disabled>Please select Staff Type </option>
                 
                                            <option value="Academic">Academic</option>
                                            <option value="Non Academic">Non Academic</option>

 </select>
                                                        </div>
                                                    </div>
                            <div class="form-group">
                                                            <label for="date">Date of First Appointment:</label>
                                                            <div class="col-sm-10">
                                                                <input type="date" class="form-control"   autocomplete="off" name="dfa" maxlength="17">
                                                            </div>
                                                        </div>
 <div class="form-group">
                                                            <label for="date">Grade Level:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="grade" maxlength="17">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date">Rank:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="rank" maxlength="17">
                                                            </div>
                                                        </div>

                            <h4>Qualification/School Information</h4>
                            <div class="form-group">
                            <label for="default">Academic Qualification:</label>
                             <div class="col-sm-10">
                            <input type="text" class="form-control"   autocomplete="off" name="qual" maxlength="17">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default">Area of Specialization:</label>
                             <div class="col-sm-10">
                            <input type="text" class="form-control"   autocomplete="off" name="special" maxlength="17">
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="default">School:</label>
                             <div class="col-sm-10">
                            <input type="text" class="form-control"   autocomplete="off" name="school" maxlength="17">
                            
                            </div>
                        </div><div class="form-group">
                            <label for="default">Campus:</label>
                             <div class="col-sm-10">
                            <input type="text" class="form-control"   autocomplete="off" name="campus" maxlength="17">
                            
                            </div>
                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="col-lg-12">
                                                    <div class="form-group">
                                                    <label for="default">DOB</label>
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
<label for="default">Gender</label>
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
<label for="default">Marital</label>
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
<label for="default">Disability</label>
 <div class="col-sm-10">
 <select name="marital" class="form-control clid" id="classid" required="required">
<option selected disabled>Please select Disability</option>
                 
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>

 </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label for="date">Blood Group:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="blood" maxlength="17">
                                                            </div>
                                                        </div><div class="form-group">
                                                            <label for="date">Genotype:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="genotype" maxlength="17">
                                                            </div>
                                                        </div>
<div class="form-group">
<label for="default" >Nationality</label>
 <div class="col-sm-10">
 
<select name="country"  class="form-control clid" id="show" onchange="change(this)" class="state">
    <option disabled="disabled" selected="selected">Select Nationality</option>
    <option value="Nigeria">Nigeria</option>
</select>
                                                        </div>
                                                    </div>
<div id="retCustDetails" class="custDetails" style="display:none">
 <div class="form-group">
<label for="default">State</label>
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
<label for="default">Lga</label>
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
                                                                                
                                                                                <h4>Next of Kin Information</h4>
                             
                            <div class="form-group">
                                                            <label for="date" >Name of Next of Kin:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="nok" maxlength="17">
                                                            </div>
                                                        </div>
 <div class="form-group">
                                                            <label for="date">Next Of Kin Email:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="nok_email" maxlength="17">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date">Next Of Kin Phone:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="nok_phone" maxlength="17">
                                                            </div>
                                                        </div>  <div class="form-group">
                                <label for="date">Next Of Kin Address:</label>
                                <div class="col-sm-10">
                            <div  id="subject">
                               <textarea name="address"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required"></textarea>
                            </div>
                                </div>
                            </div>                   
                                     <h4>Publication/Conference Information (Academic Staff Only)</h4>
                             
                            <div class="form-group">
                                                            <label for="date">Number of Publication(s):</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="published" maxlength="17">
                                                            </div>
                                                        </div>
 <div class="form-group">
                                                            <label for="date">Number of Chapter in a Book:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="books" maxlength="17">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date">Number of Conferences/Workshop Attended:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="confe" maxlength="17">
                                                            </div>
                                                        </div>     <div class="form-group">
                                                            <label for="date">Member of Professional Body:</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control"   autocomplete="off" name="profe" maxlength="17">
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
                                    <th><input type="file" class="form-control" name="passport"  accept=".jpeg,.jpg" onchange="readURL(this);" style="width: 209px;margin: auto;">
                                        
                                    </th>
                                </tr>
                            </table>
                        </div>
                                                    <!-- Sepreaaater -->
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="registering" id="submit" class="btn btn-primary form-control">Submit Data</button>
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
        </div><div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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