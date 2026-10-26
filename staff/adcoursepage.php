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
if (isset($_POST["deletsub"])) {
   $del=mysqli_escape_string($con,$_POST['del']);

    $upd=mysqli_query($con,"DELETE FROM course WHERE coursecode='$del'");

    if ($upd) {
        $showModalSs = "true";
    }
    else
    {
         $showModalEr='true';
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

                    <!-- ========== LEFT SIDEBAR ========== -->
                  <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Courses Update Page Setup</h2>
                                
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
                                                <section>
                                                    <table id="example" class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>course Code</th>
                                                            <th>course Title</th>
                                                            <th>course Unit</th>
                                                            <th>Faculty</th>
                                                            <th>Department</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <?php
                              $sql = "SELECT * FROM course  ORDER BY coursecode";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td contenteditable="true" class="code" onBlur="saveInlineEdit(this,'coursecode','<?php 
                                                echo $result["coursecode"]; ?>','<?php 
                                                echo $result["title"]; ?>','<?php 
                                                echo $result["unit"]; ?>','<?php 
                                                echo $result["faculty"]; ?>','<?php 
                                                echo $result["department"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['coursecode']) ?></td>
                                <td contenteditable="true" class="title" onBlur="saveInlineEdit(this,'title','<?php 
                                                echo $result["coursecode"]; ?>','<?php 
                                                echo $result["title"]; ?>','<?php 
                                                echo $result["unit"]; ?>','<?php 
                                                echo $result["faculty"]; ?>','<?php 
                                                echo $result["department"]; ?>')" 
                                                onClick="highlightEdit(this);" ><?php echo htmlentities($result['title']);?></td>
                                <td contenteditable="true" class="units allow_only_numbers" onBlur="saveInlineEdit(this,'unit','<?php 
                                                echo $result["coursecode"]; ?>','<?php 
                                                echo $result["title"]; ?>','<?php 
                                                echo $result["unit"]; ?>','<?php 
                                                echo $result["faculty"]; ?>','<?php 
                                                echo $result["department"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['unit']);?></td>
                                <td contenteditable="true" class="fac" onBlur="saveInlineEdit(this,'faculty','<?php 
                                                echo $result["coursecode"]; ?>','<?php 
                                                echo $result["title"]; ?>','<?php 
                                                echo $result["unit"]; ?>','<?php 
                                                echo $result["faculty"]; ?>','<?php 
                                                echo $result["department"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['faculty']);?></td>
                              <td contenteditable="true" class="dep" onBlur="saveInlineEdit(this,'department','<?php 
                                                echo $result["coursecode"]; ?>','<?php 
                                                echo $result["title"]; ?>','<?php 
                                                echo $result["unit"]; ?>','<?php 
                                                echo $result["faculty"]; ?>','<?php 
                                                echo $result["department"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['department']);?></td>
                            <td>
        <button class="userinfof btn btn-danger" data-course="<?php echo $result['coursecode'];?>" coursecode="<?php echo $result['coursecode']?>"href="#detailsmodal" data-target="#detailsModal">
            <i class="fa fa-trash"></i></button>
                            </td>    
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </section>
            <div id="myModal" class="modal fade">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content">
                        <div class="modal-header flex-column">
                                              
                            <h4 class="modal-title w-100">Are you sure?</h4>    
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body" id="teacher_detail">
                            
                        </div>
                     
                    </div>
                </div>
            </div> 
            <div class="modal fade" id="checkModalSs" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Detail Successfully Removed</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
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
                            <p>An Error Occured While Removing The Course Please Try Again!!!</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
        
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
            if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    } 
    if(!empty($showModalSs)) { 
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalSs").modal("show");
            });
        </script>';
    } 
        ?>
        <script type="text/javascript">
           
         function saveInlineEdit(editableObj,column,code,title,unit,department,faculty)
         {
           // alert(unit);
          $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
          var customer={
            editval : $(editableObj).text(),
            codes:code,
            column:column,
            title:title,
            unt:unit
          }
           $.ajax({
            url: "adco",
            type: "POST",
            //data:'column='+column+'&editval='+$(editableObj).text()+'&codes='+code+'&title='+title+'&unt='+unit,
            data:customer,
            success: function(data){
              $(editableObj).css("background","#FDFDFD");
              //alert(data);
            }
          });
          

    }
        function highlightEdit(editableObj)
            {
          //     
            $(editableObj).css("background","#FFF");
            }
            $(".allow_only_numbers").keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
              ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
              (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

$(document).ready(function(){

// $('.userinfo').click(function(){
$('body').on('click','.userinfof',function() {

var courd = $(this).attr('data-course');
var coursecode = $(this).attr('coursecode');

// AJAX request
$.ajax({
url: 'remo',
type: 'POST',
data: {courd:courd,coursecode:coursecode},
success: function(response){ 
$('#teacher_detail').html(response);

      // Display Modal
      $('#myModal').modal('show');
}
});
});
});  

       </script>
        
      
    </body>
</html>
<?php } ?>
