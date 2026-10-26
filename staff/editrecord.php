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
if (isset($_POST['submitpdu'])) {
    
        $firstname=mysqli_escape_string($con,$_POST["firstname"]);
        $surname=mysqli_escape_string($con,$_POST["surname"]);
        $othername=mysqli_escape_string($con,$_POST["othername"]);
        $email=mysqli_escape_string($con,strtolower($_POST["email"]));
        $phone=mysqli_escape_string($con,$_POST["phone"]);
        $dob = mysqli_escape_string($con,$_POST["dobs"]);
        $staffid=mysqli_escape_string($con,$_SESSION['staff']);
        $address=mysqli_escape_string($con,$_POST["address"]);
        $sex=mysqli_escape_string($con,$_POST["sex"]);
        /*Remember to Implement function to load the faculty name from faculty table*/      
        $marital=mysqli_escape_string($con,$_POST["marital"]);
        $country=mysqli_escape_string($con,$_POST["country"]);
      
        /*Email Updating End HEre*/
        if($country=="Nigeria") 
            {
        $state=mysqli_escape_string($con,$_POST["state"]);
        $lga=mysqli_escape_string($con,$_POST["lga"]);
        $staname=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM state WHERE state_ID='$state' OR name='$state' "));
        $sate = $staname['name'];
        $recup=mysqli_query($con,"UPDATE studdata SET firstname='$firstname',surname='$surname',lastname='$othername',gender='$sex',marital='$marital',dob='$dob',phone='$phone',email='$email',state='$sate',address='$address',nationality='$country',lga='$lga' WHERE (admno='".$_SESSION['staff']."' OR studentid='".$_SESSION['staff']."') ");
            if ($recup) {
                $showModal='TRUE';
                $succ="Details Successfully Updated";
            }else{
                $error="Error Occured While Updating";
                $showModalEr='true';
            }
            }else{
                $state=mysqli_escape_string($con,$_POST['country']);
                $recup=mysqli_query($con,"UPDATE studdata SET firstname='$firstname',surname='$surname',lastname='$othername',gender='$sex',marital='$marital',dob='$dob',phone='$phone',email='$email',address='$address',state='$state',country='Foreign' WHERE (applid='".$_SESSION['staff']."' OR studentid='".$_SESSION['staff']."') ");
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
        <!-- <script type="text/javascript" src="js/country.js"></script> -->
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
                                    <h4 class="title">Edit Student Personal Data (ABA/PDE)</h4>
                                
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
 if ($paych['position']=="superadmin" OR $paych['position']=="subadmin" OR $paych['position']=="sys_analyst") {
  ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student-ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" name="tellerid" autocomplete="off" required="required" placeholder="Enter Student Id OR Admission ID">
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
                                                    
                                                    $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM studdata WHERE studentid='$teld' OR admno='$teld' "));
                                                    if ($telcheck>0) {
                                                    $stad=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studdata WHERE studentid='$teld' OR admno='$teld'"));
                                                    $dpfd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='".$stad['programme']."' "));

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
                    xmlhttp.open("GET", "fetch.php?facult=" + document.getElementById("state").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("lga").innerHTML = xmlhttp.responseText;

                }
                function selectfaculty()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "fet.php?facult=" + document.getElementById("faculty").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("department").innerHTML = xmlhttp.responseText;
                }

                function selectdepart()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "feck.php?state=" + document.getElementById("department").value, false);
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

 ?>
