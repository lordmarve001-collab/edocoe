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

/*Updating Query*/
$error='';
$succ='';
if (isset($_POST['submitpr'])) {
   $nlev=mysqli_escape_string($con,$_POST['levelc']);
   $nsem=mysqli_escape_string($con,$_POST['semester']);
   $nsec=mysqli_escape_string($con,$_POST['sections']);
   $upd=mysqli_query($con,"UPDATE studentlateregsem SET level='$nlev',semester='$nsem',session='$nsec', WHERE studentid='".$_SESSION['userid']."' AND session='".$_SESSION['pur']."' AND semester='".$_SESSION['pty']."' AND level='".$_SESSION['lvd']."' AND programme='".$_SESSION['prg']."' ");
  if ($upd) {
    $showModall='true';
      $succ='Student Data Successfully Updated';
  }else{
    $showModale='true';
    $error='Purpose Selected Does not Exit';
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

                  if ($paych['position']=="accountant")
                  {
                    
                    include('includes/accbar.php');

                    }else if ($paych['position']=="supbursar") {
                    
                    include('includes/supbubar.php');
                    }else if ($paych['position']=="superadmin") {
                    
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
                                    <h2 class="title">Previous Cleared Student Record</h2>
                                
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

    if ($paych['position']=="superadmin")
    {
       ?>
       <section class="section">
                                                    <form method="POST" class="form-horizontal">
               
                        <div class="form-group">
                            <label for="default" class="col-sm-2 control-label">Session:</label>
                             <div class="col-sm-10">
                             <select name="section" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Session</option>
                              <?php
                                $res = mysqli_query($con, "SELECT *  FROM sections ORDER BY section DESC ");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                                                                                                                 
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="query" class="btn btn-primary">View Record</button>
                        </div>
                    </div>                    
                </form>
                </section>
                <?php
                $error='';
                          $succ='';
                      if (isset($_POST['query'])) {
                        if (empty($_POST['section'])) {
                         $showModal = "true";
                         $error="Session";
                        }else{
                        $ses=mysqli_escape_string($con,$_POST['section']);
                        $que=mysqli_num_rows(mysqli_query($con,"SELECT * FROM studentlateregsem WHERE session='$ses'"));
                        if ($que>0) {
                       
                       
                ?>                                             
                    <section>
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>StudentID</th>
                                <th>Student Name</th>
                                <th>Semester</th>
                                <th>Session</th>
                                <th>Level</th>
                                <th>Programme</th>
                                <th>Regstatus</th>
                                <th>Upload status</th>
                                <th>Action</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              $sql = "SELECT * FROM studentlateregsem WHERE session='$ses'  ORDER BY studentid";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   
                                stuname($con,$result['studentid']);
                                ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['studentid']) ?></td>
                                <td><?php echo htmlentities($_POST["harrd"]) ?></td>
                                <td><?php echo htmlentities($result['semester']);?></td>
                                <td><?php echo htmlentities($result['session']);?></td>
                                <td><?php echo htmlentities($result['level']);?></td>
                                <td contenteditable="true" class="code" onBlur="saveInlineEdit(this,'programme','<?php 
                                                echo $result["studentid"]; ?>','<?php 
                                                echo $result["semester"]; ?>','<?php 
                                                echo $result["session"]; ?>','<?php 
                                                echo $result["level"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['programme']);?></td>
                                <td contenteditable="true" class="code" onBlur="saveInlineEdit(this,'regstatus','<?php 
                                                echo $result["studentid"]; ?>','<?php 
                                                echo $result["semester"]; ?>','<?php 
                                                echo $result["session"]; ?>','<?php 
                                                echo $result["level"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['regstatus']);?></td>
                                <td contenteditable="true" class="code" onBlur="saveInlineEdit(this,'uploadst','<?php 
                                                echo $result["studentid"]; ?>','<?php 
                                                echo $result["semester"]; ?>','<?php 
                                                echo $result["session"]; ?>','<?php 
                                                echo $result["level"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['uploadst']);?></td>
                                <td>
                                    <button class="btn btn-primary userinfo" id="<?php echo $result['studentid']?>" pur="<?php echo $result['session']?>" pty="<?php echo $result['semester']?>" lv="<?php echo $result['level']?>" prg="<?php echo $result['programme']?>"><i class="fa fa-edit"></i> </button>
                                </td>
                                
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </section>
        <?php 
            }else{
                            $error='No Result Base on the Criteria';
                            $showModale='true';
                        }
                     }
                    } 
    }elseif ($paych['position']=="accountant") {
             ?>
             <section class="section">
                       <form method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="default" class="col-sm-2 control-label">Session:</label>
                             <div class="col-sm-10">
                             <select name="section" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Session</option>
                              <?php
                                $res = mysqli_query($con, "SELECT *  FROM sections ORDER BY section DESC ");
                                while ($row = mysqli_fetch_array($res)) {

                                    ?>
                                    <option value="<?php echo  $row["section"]; ?>"><?php echo  $row["section"]; ?></option>
                              <?php

                                }
                              ?>
                            </select>
                            
                            </div>
                        </div>
                                                                                                                 
                                                     
    <!-- Sepreaaater -->
                            <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="query" class="btn btn-primary">View Record</button>
                        </div>
                    </div>                    
                </form>
                </section>
        <?php
                $error='';
                          $succ='';
                      if (isset($_POST['query'])) {
                        if (empty($_POST['section'])) {
                         $showModal = "true";
                         $error="Session";
                        }else{
                        $ses=mysqli_escape_string($con,$_POST['section']);
                        $que=mysqli_num_rows(mysqli_query($con,"SELECT * FROM studentlateregsem WHERE session='$ses'"));
                        if ($que>0) {
                       
                       
                ?>        
             <section>
                        <table id="example" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>StudentID</th>
                                <th>Student Name</th>
                                <th>Semester</th>
                                <th>Session</th>
                                <th>Level</th>
                                <th>Programme</th>
                                <th>Action</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              $sql = "SELECT * FROM studentlateregsem ORDER BY studentid";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            { 
                                stuname($con,$result['studentid']);
                                ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['studentid']) ?></td>
                                <td><?php echo htmlentities($_POST["harrd"]) ?></td>
                                <td><?php echo htmlentities($result['semester']);?></td>
                                <td><?php echo htmlentities($result['session']);?></td>
                                <td><?php echo htmlentities($result['level']);?></td>
                                <td><?php echo htmlentities($result['programme']);?></td>
                                <td>
                                    <button class="btn btn-primary userinfo" id="<?php echo $result['studentid']?>" pur="<?php echo $result['session']?>" pty="<?php echo $result['semester']?>" lv="<?php echo $result['level']?>" prg="<?php echo $result['programme']?>"><i class="fa fa-edit"></i> </button>
                                </td>
                                
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </section>
        
                    <?php 
                    }else{
                            $error='No Result Base on the Criteria';
                            $showModale='true';
                        } 
                        }
                        }                                              
                    }
                    else{
                    
                   ?>
                   <p style="color:darkred;font-size: 16px;font-weight: bold;text-align: justify;">Sorry Dear: <b style="color:blue;"><?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page.</p>
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
                <div class="modal" id="viewModal">
            <div class="modal-dialog">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Previous Cleared Student Data Update
                    <button type="button" class="close" data-dismiss="modal" style="float:right;">&times;</button></h4>
                  </div>

      <!-- Modal body -->
                  <div class="modal-body" id="teacher_detail">

                  </div>

      <!-- Modal footer -->
              

            </div>
          </div>
        </div>
                <!-- Seperate -->
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
                            <p><?php echo htmlentities($succ)?></p>
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
                            <p><?php echo $error ?>.</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';


            if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
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
        ?>

        <script type="text/javascript">
            /*For Delete Query*/
    $(document).ready(function(){

            // $('.userinfo').click(function(){
            $('body').on('click','.userinfo',function(){
            var userid = $(this).attr('id');
            var lvd = $(this).attr('lv');
            var pur = $(this).attr('pur');
            var pty = $(this).attr('pty');
            var prg = $(this).attr('prg');
            // AJAX request
            $.ajax({
                    url: 'cledits',
                    type: 'POST',
                    data: {userid: userid,lvd:lvd,pur:pur,pty:pty,prg:prg},
                    /*data: {userid: userid,lvd:lvd,prg:prg},*/
                    success: function(response)
                    { 
                        $('#teacher_detail').html(response);
                        // Display Modal
                        $('#viewModal').modal('show');
                    }
                });
            });
       });
        </script>
        <script type="text/javascript">
           
         function saveInlineEdit(editableObj,column,studentid,semester,session,level)
         {
           // alert(unit);
          $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
        var customer={
            editval : $(editableObj).text(),
            codes:studentid,
            column:column,
            title:semester,
            sesct:session,
            unt:level
          } 
           $.ajax({
            url: "prect",
            type: "POST",
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
        </script>
    </body>
</html>
<?php }
function stuname($con,$maxt)
 {
   $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =strtoupper($las).", ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }

 }
 ?>
