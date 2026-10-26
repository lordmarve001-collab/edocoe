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
/*To Submit Personal Detail*/
set_time_limit(300);
ini_set('max_execution_time', '300');
//$mail = new PHPMailer;
if (isset($_POST["submitpd"])) {
    if (empty($_POST["day"])) {
        $showModalEr='true';
        $error="Day Required";
    }elseif (empty($_POST["month"])){
        $showModalEr='true';
        $error="month Required";
        
    }elseif (empty(mysqli_escape_string($con,$_POST["year"]))) {
        $showModalEr='true';
        $error="Year Required";
        //echo '<script type="text/javascript">alert("'.$yr.'");</script>';
    }elseif (empty(mysqli_escape_string($con,$_POST["sex"]))) {
        $showModalEr='true';
        $error="Gender Required";       
    }
    elseif (empty(mysqli_escape_string($con,$_POST["marital"]))) {
        $showModalEr='true';
        $error="Marital Required";
        
    }elseif (empty(mysqli_escape_string($con,$_POST["country"]))) {
        $showModalEr='true';
        $error="Select Nationality";
        
    }elseif (empty($_POST["title"])) {
        $showModalEr='true';
        $error="Select Title";
        
    }else{
        //POSTING THE DATA INTO
        $tiles=mysqli_escape_string($con,$_POST["title"]);
        $firstname=mysqli_escape_string($con,$_POST["firstname"]);
        $surname=mysqli_escape_string($con,$_POST["surname"]);
        $othername=mysqli_escape_string($con,$_POST["othername"]);
        $email=mysqli_escape_string($con,strtolower($_POST["email"]));
        $phone=mysqli_escape_string($con,$_POST["phone"]);
        $day = mysqli_escape_string($con,$_POST["day"]);
        $year = mysqli_escape_string($con,$_POST["year"]);
        $month =mysqli_escape_string($con, $_POST["month"]);
        $dob = $year."-".$month."-".$day;
        $staffid=mysqli_escape_string($con,$_SESSION["staffid"]);
        $address=mysqli_escape_string($con,$_POST["address"]);
        $sex=mysqli_escape_string($con,$_POST["sex"]);
        /*Remember to Implement function to load the faculty name from faculty table*/      
        $marital=mysqli_escape_string($con,$_POST["marital"]);
        $country=mysqli_escape_string($con,$_POST["country"]);
        /*Image Codes*/
        $filenamg =basename($_FILES['passport']['name']);
        $filetmp =$_FILES['passport']['tmp_name'];
        $filesize = $_FILES['passport']['size'];
        $extension = array("png","jpg","jpeg","PNG","JPG","JPEG");
         // Get image file extension
        $file_extension = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
        // Validate file input to check if is not empty
        if (!file_exists($_FILES["passport"]["tmp_name"])) {
             $error="Choose image file to upload.";
             $showModalEr='true';
            
        }    // Validate file input to check if is with valid extension
        else if (!in_array($file_extension, $extension)) {
            $error="Upload valid images. Only PNG and JPEG are allowed.";
            $showModalEr='true';
        }    // Validate image file size
        else{
        $nwtourimg = explode(".", $filenamg);
        $fileExt = end($nwtourimg);
        //unlink($filetmp);
        $res = trim(str_ireplace( array( '\'', '"',
      ',' , ';', '<', '>','/','-','_','' ), '', $staffid));
        $fileBase = $res.".".$fileExt;
        $dir = "photo/";
        $final_dir= $dir . $fileBase;
            if($country=="Nigeria") 
            {
            if (empty($_POST["state"])) {
            $showModalEr='true';
            $error="Please select state";
            
            }elseif (empty($_POST["lga"])) {
                $showModalEr='true';
                $error="Please select lga";
            }else{
            if (($filesize > 5000000000)) {
                $showModalEr='true';
                $error="file too large, filesize should be 200kb";
                //return false;
            }
            else{
                /*Script checking the numbers of Registration on the database*/
            $sq=mysqli_query($con,"SELECT * FROM people WHERE staffid='$staffid'");
            if (mysqli_num_rows($sq)==1) {
            $showModalEr='true';
            $error="You have Register";
                }
            /*Ends here*/
                else
                {
                    //avoiding of duplicate of data of student in the table
                    $sql="SELECT * FROM people WHERE (uniemail='$email' OR phoneno='$phone');";
                    $res=mysqli_query($con,$sql);
                    if (mysqli_num_rows($res) > 0) {
                        $row = mysqli_fetch_assoc($res);
                        if ($email==$row['uniemail']) {
                            
                            $showModalEr='true';
                            $error="Email already exist";
                        }
                        elseif ($phone==$row['phoneno']) {
                            
                            $showModalEr='true';
                            $error="phone number already exist";
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
                    $regist=mysqli_query($con,"INSERT INTO people(staffid,status,title,firstname,lastname,othername,gender,maritalstatus,dob,phoneno,uniemail,state,address,image,country,lga) VALUES ('$staffid','active','$tiles','$firstname','$surname','$othername','$sex','$marital','$dob','$phone','$email','$sate','$address','$final_dir','$country','$lga')");
                if ($regist) {
                                            //mysqli_commit($con);
                                            //mysqli_close($con);
                                            header("location: staffportal");
                                            //echo 1;
                                        } else {
                                            $showModalEr='true';
                                            $error="Sorry Error Occur When Submitting";
                                        }
                                }
                            }
                        }
                    }
                }//End of Country And Local Government  Selections
            }
            elseif ($country=="Foreign")
            {
            if(empty($_POST['states'])){
                $showModalEr='true';
                $error="Please select Country";
                
                }else{
                if (($filesize > 500000000)) {
                
                $showModalEr='true';
                $error="file too large, filesize should be 100kb";
                //return false;
            }
            else{
                /*Script checking the numbers of Registration on the database*/
            $sq=mysqli_query($con,"SELECT * FROM people WHERE staffid='$staffid'");
            if (mysqli_num_rows($sq)==1) {
            $showModalEr='true';
            $error="You have Register";
                }
            /*Ends here*/
                else
                {
                    //avoiding of duplicate of data of student in the table
                    $sql="SELECT * FROM people WHERE (uniemail='$email' OR phoneno='$phone');";
                    $res=mysqli_query($con,$sql);
                    if (mysqli_num_rows($res) > 0) {
                        $row = mysqli_fetch_assoc($res);
                        if ($email==$row['uniemail']) {
                            $showModalEr='true';
                            $error="Email already exist";
                        }
                        elseif ($phone==$row['phoneno']) {
                            $showModalEr='true';
                            $error="phone number already exist";
                        }
                    }
                    else
                        {
                $state=mysqli_escape_string($con,$_POST['states']);
                // echo $sate;
                if(move_uploaded_file($filetmp, $final_dir))
                {
                    $regist=mysqli_query($con,"INSERT INTO people(staffid,status,title,firstname,lastname,othername,gender,maritalstatus,dob,phoneno,uniemail,state,address,image,country) VALUES ('$staffid','active','$tiles','$firstname','$surname','$othername','$sex','$marital','$dob','$phone','$email','$state','$address','$final_dir','$country')");
                if ($regist) {
                                 
                                header("location: staffportal");
                                 
                              } 
                              else 
                              {
                                    $showModalEr='true';
                                    $error="Sorry Error Occur When Submitting";
                              }
                }
            }
        }
    }
    }//End of Country  Selection 
    }//End of the Code
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
  <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php include('includes/leftbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Bio-Data</h2>
                                
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
                                                
                                                <section class="section">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                        <div class="col-lg-6">
                                                            <div class="col-lg-12">
                                                            <div class="form-group">
                                                                    <label for="default" class="col-sm-2 control-label">Title:</label>
                                                                     <div class="col-sm-10">
                                                                        <select name="title" class="form-control">
                                                                            <option selected="selected" disabled="disabled">Please Select Title</option>
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
                                                                </div> 
                                                                <div class="form-group">
                                                                    <label for="default" class="col-sm-2 control-label">Firstname:</label>
                                                                     <div class="col-sm-10">
                                                                        <input type="text" class="form-control"  value="<?php  echo htmlentities($firstnames) ?>" autocomplete="off" name="firstname" required="required"  maxlength="17">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="date" class="col-sm-2 control-label ">Surname:</label>
                                                                    <div class="col-sm-10">
                                                                    <input type="text"  class="form-control"  value="<?php echo htmlentities($lastnames) ?>" autocomplete="off" name="surname"  maxlength="17">
                                                                    </div>
                                                                </div>
                                                                 <div class="form-group">
                                                                    <label for="date" class="col-sm-2 control-label ">Othername:</label>
                                                                    <div class="col-sm-10">
                                                                    <input type="text"  class="form-control"  autocomplete="off" name="othername"  maxlength="17" >
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="date" class="col-sm-2 control-label">Email:</label>
                                                                    <div class="col-sm-10">
                                                                
                                                                        <input type="email"  class="form-control" autocomplete="off"  name="email" required="required" maxlength="137">
                                                   
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="date" class="col-sm-2 control-label">Phone:</label>
                                                                     <div class="col-sm-10">
                                                    
                                                                        <input type="tel"  class="form-control"  name="phone" autocomplete="off" required="required" maxlength="11" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                                    
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="default" class="col-sm-2 control-label">DOB</label>
                                                                    <div class="col-sm-10">

                                                                    <select name="day" class="dayss" required="required" style="width: 100px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
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
                                                            <select name="month" class="days" required="required" style="width: 140px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
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
                                                            <select name="year" class="day" required="required"style="width: 110px;text-transform: uppercase;box-sizing: border-box;transition: .3s;border-radius: 4px;outline: none;border: 2px solid #aaa;margin: 4px 0;padding: 5px;-moz-border-radius:5px-webkit-border-radius:5px;font-family: 'Time New Romans', sans-serif;-webkit-border-radius: 5px;">
                                                                <option selected="selected" disabled="disabled">Year</option>
                                                                <?php
                                                                /*for ($year = 1995;$year<=Date("Y");$year++) {
                                                                echo "<option value=\"$year\">$year</option>\n";
                                                                }
                                                                */
                                                                $currently_selected = date('Y');
                                                                $earliest_year = 1940;

                                                                $latest_year = date('Y');
                                                                foreach (range($latest_year, $earliest_year) as $i) {
                                                                    // Prints the option with the next year in range.
                                                                    echo '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="date" class="col-sm-2 control-label">Home Address:</label>
                                                            <div class="col-sm-10">
                                                       
                                                           <textarea name="address"  class="form-control"  style="width:100%;height: 93px;resize: none;scroll-behavior: none;" required="required"></textarea>
                                                       
                                                            </div>
                                                        </div>
                                                       </div>
                                                        </div>
                                            <div class="col-lg-6">
                                                <div class="col-lg-12">
                                                                                                                        
                                                     <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Gender:</label>
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
                                                    <label for="default" class="col-sm-2 control-label">Nationality:</label>
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
                                                    <label for="default" class="col-sm-2 control-label">State:</label>
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
                                                <label for="default" class="col-sm-2 control-label">Lga:</label>
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
                                    
                                            <div style="margin:auto;max-width: 250px;">
                                                <label style="color:red;">Image Not Larger Than 500KB</label>
                                                            <table>

                                                                <tr>
                                                                    <td>
                                                                        <img id="blah" src="photo/avater.png" alt="Your Passport">
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
                                                                    <th><input type="file" name="passport" required="required" accept=".jpeg,.jpg" onchange="readURL(this);" class="form-control">
                                                                        <p style="font-size: 12px;"><?php //echo "$mess"; ?></p>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div>
                                                            <button type="submit" name="submitpd" id="submit" class="btn btn-primary form-control">Submit Data</button>
                                                        </div>
                                                    </div>
                                                            </div>
                                                        </div>
                                                         
                                                    </form>
                                                </section>

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
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
       <script src="js/main.js"></script>
        <script type="text/javascript">
                function selectstate()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fetch?facult=" + document.getElementById("state").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("lga").innerHTML = xmlhttp.responseText;

                }
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
