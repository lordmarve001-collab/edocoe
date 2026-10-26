<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: logout"); 
    }
    else{
 include 'dataco.php';

      if(isset($_POST['removedd']))
      {
              mysqli_query($con,"DELETE FROM cleardoc WHERE id= '".$_SESSION['ids']."'");
                  $error="Document is Successfully Removed";
                 $showModalEr='true';
      }
      
      
      if(isset($_POST['jchew']))
      {
          $qu=mysqli_query($con,"UPDATE personaldata SET cert='1' WHERE applid='".$_SESSION['staffid']."'");
            if ($qu>0) {
                header("Location:appsummary");
            }else{
                $error="Error While Updating";
                $showModalEr='true';
        }
      
      }
      
if (isset($_POST['savees'])) {
    if (empty($_POST['proty'])) {
       $error="Select Image Product Type";
       $showModalEr='true';
    }else{
        $prodt=$_POST["proty"];
        $id=$_POST['proty'];
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
        }else if (($_FILES["passport"]["size"] > 500000)) {
        $error="Image size exceeds 500KB.";
        $showModalEr='true';
        }     // Validate image file size
        else{
        $nwtourimg = explode(".", $filenamg);
        $fileExt = end($nwtourimg);
        $res = trim(str_ireplace( array( '\'', '"',
      ',' , ';', '<', '>','/','' ), '', $_SESSION['staffid']));
        $fileBase = $res."".$id.".".$fileExt;
        $dir = "photo/";
        $final_dir= $dir. $fileBase;
        
        if ($id=='9') {
           $igd="JCHEW Certificate";
        }elseif($id=='8'){
             $igd="JCHEW Licence";
        }elseif($id=='2'){
             $igd="Birth/Age Declaration";
        }
        $sq=mysqli_query($con,"SELECT * FROM cleardoc WHERE applid='".$_SESSION['staffid']."' AND documtype='$id' ");
            if (mysqli_num_rows($sq)==1) {
            $error="Document ".$igd." have Already Exist";
            $showModalEr='true';
                }
            /*Ends here*/
                else
                {

    //inserting to the database
        if(move_uploaded_file($filetmp, $final_dir))
        {
    $qu=mysqli_query($con,"INSERT INTO cleardoc(applid,document,documtype) VALUES ('".$_SESSION['staffid']."','$final_dir','$id')");
            }
           }
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
                                    <h2 class="title">JCHEW Support Document</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                        <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
                                            <?php $stanam=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$staffid' AND status='submitted' "));
               if($stanam>0){
                ?>
                                                <div class="table-responsive table-bordered">
                                                <table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#form_modal"><span class="fa fa-plus">Add Document</span></button></th>
                                                        </tr>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Document Type</th>
                                                            
                                                            <th>Image</th>
                                                           
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
<?php
  $sql = "SELECT * FROM cleardoc WHERE applid='".$_SESSION['staffid']."' ORDER BY document";


$query = mysqli_query($con,$sql);
$results = mysqli_fetch_all($query,MYSQLI_ASSOC);
$cnt=1;

foreach($results as $result)
{   ?>
<tr>
 <td><?php echo htmlentities($cnt);?></td>
    <?php if ($result['documtype']=='9') {
        ?>
    <td><?php echo htmlentities('JCHEW Certificate');?></td>
   <?php }elseif($result['documtype']=='8'){ ?>
   <td><?php echo htmlentities('JCHEW Licence');?></td>
    <?php }elseif($result['documtype']=='2'){ ?>
   <td><?php echo htmlentities('Birth/Age Declaration');?></td>
    <?php } ?>
    <td>
        <img data-u='image' src="<?php echo htmlentities($result['document']);?>"  class='img-fluid'  style="width:100px;height:50px;"> </td>
    <td>
<button class="btn btn-danger userinf" id="<?php echo $result['id']?>"><i class="fa fa-trash"></i></button>
<button class="btn btn-info  userinfo" id="<?php echo $result['id']?>"><span class="fa fa-eye"></span></button>
</td>
</tr>
<?php 

$cnt=$cnt+1;
}?>
                                                       
                                                    
                                                    </tbody>
                                                </table>
                                                <?php if(mysqli_num_rows($query)==3){?>
                                          
    <button name="jchew" class="btn btn-primary"><span class="fa fa-save"></span> Complete and Next Page</button>
     </form>                                    <?php } ?>
</div><?php }?>
                                        
                                <?php $stanam=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$staffid' AND (status='Pending' OR status='Approved') "));
               if($stanam>0){
                ?>
                                
                                                <div class="table-responsive table-bordered">
                                                <table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Document Type</th>
                                                            
                                                            <th>Image</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
<?php
  $sql = "SELECT * FROM cleardoc WHERE applid='".$_SESSION['staffid']."' ORDER BY document";


$query = mysqli_query($con,$sql);
$results = mysqli_fetch_all($query,MYSQLI_ASSOC);
$cnt=1;

foreach($results as $result)
{   ?>
<tr>
 <td><?php echo htmlentities($cnt);?></td>
    <?php if ($result['documtype']=='2') {
        ?>
    <td><?php echo htmlentities('Birth/Age Declaration');?></td>
   <?php }elseif($result['documtype']=='9'){ ?>
   <td><?php echo htmlentities('JCHEW Certificate');?></td>
   <?php }elseif($result['documtype']=='8'){ ?>
   <td><?php echo htmlentities('JCHEW Licence');?></td>
   <?php } ?>
    <td>
        <img data-u='image' src="<?php echo htmlentities($result['document']);?>"  class='img-fluid'  style="width:400px;height:300px;"> </td>
    <td>

</td>
</tr>
<?php 

$cnt=$cnt+1;
}?>
                                                       
                                                    
                                                    </tbody> 
                                                </table>
                                                <?php if(mysqli_num_rows($query)>1){?>
                                                                                        <form class="form-horizontal" method="post" enctype="multipart/form-data">

                                           <button name="jchew" class="btn btn-primary"><span class="fa fa-save"></span> Complete and Next Page</button>
</form>                                    <?php } ?>
</div><?php }?>                <!-- /.col-md-12
                                            </div> -->
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
  <div class="modal" id="viewModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Document Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="teacher_details">

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div> 
<div class="modal" id="viewModals">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Document Removal</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
  <form method="POST" action="">
      <!-- Modal body -->
      <div class="modal-body" id="teacher_detail">

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
       
        <button type="button" class="btn btn-primary btn-sm" style="float:left;" data-dismiss="modal">Close</button>
        <button name="removedd" class="btn btn-danger"><span class="fa fa-trash"></span> Yes</button>
      </div>
      </form>
    </div>
  </div>
</div> 
                                             <!-- /.col-md-6 -->
<div class="modal fade" id="form_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action=""  enctype="multipart/form-data">
                    <div class="modal-header">
                        <h3 class="modal-title">Add New Document</h3>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="form-group">
                            <label>Document Type</label>
                            <select name="proty" class="form-control" id="default" required="required">

                            <option selected="selected" disabled="disabled">Please Select</option>
                            <option value="9">JCHEW Certificate</option>
                            <option value="8">JCHEW Licence</option>
                            <option value="2">Birth/Age Declaration</option>
                            
                         </select>
                        </div>
                            <div class="form-group">
                                <label>Document Image </label>
                                <input type="file" name="passport" onchange="readURL(this);" accept=".jpg,.jpeg,.png" class="form-control"/>
                            </div> 
                            <div class="form-group.">
                                <div style="margin:auto;width: 200px;" class="img-thumbnail">
                            <table>

                                    <tr>
                                        <td>
                                            <img id="blah" src="img/document.png" alt="Your Document">
                                    <script>
                                        function readURL(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();

                                                reader.onload = function (e) {
                                                    $('#blah')
                                                        .attr('src', e.target.result)

                                                };

                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }
                                    </script>
                                        </td>
                                    </tr>
                                    
                            </table>
                        </div>
                            </div>
                            
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="modal-footer">
                        <button name="savees" class="btn btn-primary"><span class="fa fa-save"></span> Save</button>
                        <button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
                                                               
                                                </div>
              
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
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
       <script src="js/main.js"></script>

        
        <script>
$(document).ready(function(){

$('.userinfo').click(function(){

var id = $(this).attr('id');

// AJAX request
$.ajax({
url: 'actions',
type: 'GET',
data: {id: id},
success: function(response){ 
$('#teacher_details').html(response);

      // Display Modal
      $('#viewModal').modal('show');
}
});
});
});    


$(document).ready(function(){

$('.userinf').click(function(){

var id = $(this).attr('id');

// AJAX request
$.ajax({
url: 'action',
type: 'POST',
data: {id: id},
success: function(response){ 
$('#teacher_detail').html(response);

      // Display Modal
      $('#viewModals').modal('show');
}
});
});
});     
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
