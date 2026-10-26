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

$error='';

?>

<!DOCTYPE html>
<html>
<head>
   
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
    <script type="text/javascript" src="js/jquery.min.js"></script> 
    <link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet"> 
    <script type="text/javascript" src="js/jquery-ui.js"></script>
    <script type="text/javascript" src="js/html2canvas.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
    <script type="text/javascript" src="js/jquery.signaturepad.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.signature.css">
    <script src="js/main.js"></script>
</style>
 
</head>
<body>
<?php include('includes/topbar.php');?> 
<div class="content-wrapper">
 <?php include('includes/leftbar.php');?> 
 <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Registrar Signature Setup</h4>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
 <?php
$paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
 if ($paych['position']=="registrar") {
  ?> 
   <div class="modal-dialog">
   <div class="modal-content">
   <div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Please Sign Your Signature Here</h4>
    </div>
    <div class="modal-body">
    <div id="signArea" >

    <h2 class="tag-ingo" style="font-size:30px;color:blue"></h2>
    <input type="hidden" name="staffid" id="my_class" value="<?php echo htmlentities($_SESSION['staffid'])?>">
    <div class="typed" ></div>
   <p align="center">  
   <canvas class="sign-pad" id="sign-pad" width="" height="190"></canvas></p>
   </div>

  </div>
  <div class="modal-footer">
  <button type="reset" id='clear' class="btn btn-secondary">Reset</button> 
  <button id="btnSaveSign" type="submit" name="submit" class="btn btn-primary">Submit</button>
 <?php 
                   }else{
                    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    <?php
                        }
            ?>
 
</body>
         <script>
        $(document).ready(function() {
            $('#signArea').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
        });

        $("#btnSaveSign").click(function(e){
             //var userid = $(this).attr('my_class');
            var p = document.getElementById('my_class').value;
            //alert(p);
            html2canvas([document.getElementById('sign-pad')], {
                onrendered: function (canvas) {
                    var canvas_img_data = canvas.toDataURL('image/png');
                    var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
                    //var p = document.getElementById('my_class').innerHTML;
                    //ajax call to save image inside folder
                    $.ajax({
                        url: 'save_sign',
                        data:{ img_data:img_data,p:p },
                        type: 'post',
                        dataType: 'json',
                        success: function (response) {
                           window.location.href="staffportal";
                        }
                    });
                }
            });
        });
    
    $('#clear').click(function() {
  $('#signArea').signaturePad().clearCanvas();
});
      </script> 

</html>
<?php } ?>
