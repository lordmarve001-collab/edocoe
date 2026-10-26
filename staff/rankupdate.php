<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout.php"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout.php");
}
// Retrieving Session
if (isset($_POST['submitcor'])) {
      
        $teld=trim(mysqli_real_escape_string($con,strtoupper($_POST['tellerid'])));
        
        $telcheck=mysqli_num_rows(mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='$teld' "));
        if ($telcheck>0) {
                  $showModalS='true';
                  $use=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='$teld' "));
                  $stuid=$use['staffid'];
                  $stof=$use['officename'];
                  $_SESSION['stu']=$stuid;                  
                  $_SESSION['off']=$stof;                  
                  stuname($con,$stuid);
              
                  
        }else{
                 $showModale='true';
                   $error='Staff ID '.$teld.' Does not Exit or Check and Try Again';
        }
}

if (isset($_POST['exten'])) {
    $newr=mysqli_escape_string($con,$_POST['newrank']);
    $ins=mysqli_query($con,"UPDATE staffrank SET rankname='$newr' WHERE staffid='".$_SESSION['stu']."' ");
        if ($ins) {
            approvedmail($con,$_SESSION['stu']);
            $showModall='true';
        }else{

        $showModale='true';
        $error="Error Occured While Inserting";
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
                $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="superadmin") 
                    {
                       
                         include('includes/supbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Staff Rank Updating Setup</h4>
                                
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
                     if ($paych['position']=="superadmin") {
                      ?>
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                   
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Staff ID:</label>
                                                         <div class="col-sm-10">
                                                         <input type="text" class="form-control clid" name="tellerid" required="required" placeholder="Enter Staff Id">
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
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please Select <?php echo htmlentities($error); ?> </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="checkModall" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Staff New Rank Is Successfully Updated</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModale" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo $error ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           <!-- Success Modal Box -->
            <div class="modal fade" id="checkModalS" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Staff Detail</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="POST">
                        <div class="modal-body">
                           <div style="width:150px; height: 150px; max-width:150px;margin: auto;"> <img src="<?php echo htmlentities($_POST['maks']); ?>" alt="Image" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;" ></div>
                           <?php htmlentities($_SESSION['stu']) ?>
                            <table>
                                 <tr>
                                     <td>Full Name:</td>
                                     <td><b style="text-transform: capitalize;"><?php echo htmlentities($_POST["harrd"]); ?></b></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>Staff id:</td>
                                     <td><?php echo htmlentities($stuid); ?></td>
                                 </tr>
                                 <tr>
                                     <td>Unit Name:</td>
                                     <td><?php echo htmlentities($_POST["un"]); ?></td>
                                 </tr>
                                 <tr>
                                     <td>Current Rank:</td>
                                     <td><?php echo htmlentities($_POST["prp"]); ?></td>
                                 </tr>
                                 
                                 <tr>
                                     <td>New Rank:</td>
                                     <td>
                                         <select class="form-control" name="newrank">
                                             <option selected disabled>Please select Designation</option>
                                                        <?php
                                                        $res = mysqli_query($con, "SELECT * FROM unitlist ORDER BY unitname");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["unitname"]; ?>"><?php echo  $row["unitname"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                         </select>
                                     </td>
                                 </tr>
                                
                                 
                            </table>
                        </div>
                        <div class="modal-footer">
                            
                                
                                <button name="exten" class="btn btn-success" style="float:left;">
                                    Submit <i class="fa fa-submit"></i>
                                </button>
                            </form>
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
                
                function selectfaculty()
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET", "units.php?facult=" + document.getElementById("faculty").value, false);
                    xmlhttp.send(null);
                    //alert(xmlhttp.responseText);
                    document.getElementById("department").innerHTML = xmlhttp.responseText;
                }


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
    
    $sql =mysqli_query($con,"SELECT  *  FROM  people JOIN staffrank ON people.staffid=staffrank.staffid WHERE people.staffid='$maxt' ");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["prp"]= "No Rank";
        $_POST["un"]= "No Unit";
      }else{
        $tite = $data['title'];
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $prp=$data['rankname'];
        $un=$data['officename'];
        $maks=$data['image'];
        $name =$tite.'. '.$las." ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["prp"]= $prp;
        $_POST["un"]= $un;
        }

 }
function approvedmail($con,$stud)
{

    $rows=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people  WHERE staffid='$stud'"));
    //die($maix);
        $to = $rows['uniemail'];
        require_once "phpmailer/class.phpmailer.php";
        $mail = new PHPMailer;
        try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        $mail->Password = '12345@uat';                           // SMTP password

        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; 

        $mail->setFrom('my@uat.edu.ng', 'New Rank Notification');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Your New Rank Info';
        //$content = 
        $message = '<html><body> 
        '; 
          $message .= '<span>Dear: <b style="text-transform:capitalize;">'.$rows['title']. '. '.$rows['firstname']. ' '. $rows['lastname'].' '.$rows['othername']. '</b></span>';
          $message .= '<br>';

          $message.='<p><strong>Your New Rank Have Been Successfully Updated, Kindly Visit ICT Unit With Your ID Card to get new ID Card<br> Many Thanks</strong></p>';
           $message .= '</body></html>';
          $mail->MsgHTML($message);
          $mail->send();
          $mail->SmtpClose();
          $mail->ClearAllRecipients();
            } catch (Exception $e) {
            
        }
            //return true;
}
 ?>
