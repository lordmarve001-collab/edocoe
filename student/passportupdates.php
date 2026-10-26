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
if (isset($_POST["submitpd"]))
 {
        /*insname
instad*/
        /*Image Query*/
        $filenamg =basename($_FILES['passport']['name']);
        $filetmp =$_FILES['passport']['tmp_name'];
        $filesize = $_FILES['passport']['size'];
        $extension = array("jpg","jpeg","JPG","JPEG");
        $file_extension = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
        // Validate file input to check if is not empty
        if (!file_exists($_FILES["passport"]["tmp_name"])) {
             $error="Choose image file to upload.";
             $showModalEr='true';
            
        }    // Validate file input to check if is with valid extension
        else if (!in_array($file_extension, $extension)) {
            $error="Upload valid images. Only JPEG are allowed.";
            $showModalEr='true';
        }    // Validate image file size
        else{
        $nwtourimg = explode(".", $filenamg);
        $fileExt = end($nwtourimg);
        $res = trim(str_ireplace( array( '\'', '"',
      ',' , ';', '<', '>','/','-','_',''), '', $_SESSION['staffid']));
        $fileBase = $res.".".$fileExt;
        $dir = "photo/";
        $final_dir= $dir . $fileBase;

        
        if(move_uploaded_file($filetmp, $final_dir))
        {
           /*Update the Query*/
                $upd=mysqli_query($con,"UPDATE student SET image='$final_dir' WHERE studentid='".$_SESSION['staffid']."'");
                if ($upd) {
           
                    $succ='Passport Successfully Updated';
                      $showModal='true';
                                              header("Location:student");

                    } else {
                
                $error="Sorry Error Occur When Submitting";
                $showModalEr='true';
                        }
                }
            }
                       
}
$data=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM personaldata WHERE studentid='".$_SESSION['staffid']."'"));
	$passports = $data['passport'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo "$orgname"; ?></title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script type="text/javascript" src="js/state.js"></script>
      <script type="text/javascript" src="js/country.js"></script>
      <style type="text/css">
          #blah{
            width:200.256px;
            height:180px;
            border:2px solid black;
            }
            .field-icon {
              float: right;
              margin-left: -35px;
              margin-top: -35px;
              position: relative;
              z-index: 2;
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
                  <?php
                    
                    include('includes/leftbar.php');
                    
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Passport Update Page</h2>
                                
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
    <div style="margin:auto;max-width: 200px;">
            <table>

                <tr>
                    <td>
                      <?php
                          if ($logo=='') {?>
                            <img id="blah" src="photo/avater.png" alt="No Photo">
                          <?php }else{?>
                            <img id="blah" src='../apply/<?php echo htmlentities($passports); ?>' alt="No Photo">
                        <?php  }
                      ?>
                        
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
                    <th><input type="file" name="passport" class="form-control" value="<?php echo $_SESSION['staffid']?>" required="required" accept=".jpeg,.jpg,.png"  onchange="readURL(this);">
                        
                    </th>
                </tr>
                <tr></tr>
                <tr>
                    <th><button type="submit" name="submitpd" id="submit" class="btn btn-primary form-control" style="width:200px;">Update Passport</button>
                    </th>
                </tr>
            </table>
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
            <!-- Error Modal Box -->
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
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-success" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
        <?php include'includes/buton.php'; ?>            
        
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
    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
 } ?>
