<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
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
        $prgs =$_SESSION['prgs']; 
        $secc=$_SESSION['sect'];
        $sems=$_SESSION['sems'];

        $daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT did FROM program WHERE pname='$prgs' "));
        //Submit QUery to inserter Record
        $deplt=$daft['did'];
        if (isset($_POST['allocate'])) {
            if (empty($_POST['staffid'])) {
                $showModals='true';
                $error='Please Select Staff';
            }elseif (empty($_POST['course'])) {
                $showModals='true';
                $error='Please Select Course';
            }elseif (empty($_POST['courscon'])) {
                $showModals='true';
                $error='Please Select Cordination';
            }else{

            $stafs=mysqli_escape_string($con,$_POST['staffid']);
            $code=mysqli_escape_string($con,$_POST['course']);
             $conr=mysqli_escape_string($con,$_POST['courscon']);
            $subd=date('Y-m-d H:i:s');
            //die($stafs);
            $chck=mysqli_query($con,"SELECT * FROM allocatecourse WHERE coursecode='$code' AND staffid='$stafs' AND semester='$sems' AND acadsession='$secc' ");
                    if(mysqli_num_rows($chck)>0)
                    {
                        $error= $code." Already Assigned to this Staff";
                        $showModals='true';
                    }
                    else
                    {
                    $inst=mysqli_query($con,"INSERT INTO allocatecourse(staffid,coursecord,coursecode,semester,acadsession,subdate) VALUES ('$stafs','$conr','$code','$sems','$secc','$subd')");
                    $lms=mysqli_query($con,"INSERT INTO lms(acadsession,semester,idnumber,coursecode,type) VALUES ('$secc','$sems','$stafs','$code','editingteacher')");
                      if ($inst) {
                           $showModalsuc='true';
                        }else{
                            $error= "Error Occured while Assigning ".$code;
                        $showModals='true';   
                        }
                    }
        }
    }
    /*For Deleting Or Removing Of Course from Staff*/
    if (isset($_POST['deletsub'])) {
      $sub=mysqli_real_escape_string($con,$_POST['delt']);
      $stafid=mysqli_real_escape_string($con,$_SESSION['staffds']);
      //echo $sub;
      $clak=mysqli_real_escape_string($con,$_SESSION['claa']);
      $del=mysqli_query($con,"DELETE FROM allocatecourse WHERE staffid='".$_SESSION['staffds']."' AND coursecode='".$_SESSION['claa']."' AND acadsession='$secc'");
      $lms=mysqli_query($con,"DELETE FROM lms WHERE idnumber='".$_SESSION['staffds']."' AND coursecode='".$_SESSION['claa']."' AND acadsession='$secc'");

      if ($del) {
           $showModalsucs='true';
           $succ="Detail Successfully Delected From This Staff";
        }else{
            $error= "Error Occured while Delete Information";
        $showModals='true';
        }
    }
    if (isset($_POST['submitpr'])) {
        $cour=mysqli_real_escape_string($con,$_POST['courscon']);
        $upd=mysqli_query($con,"UPDATE allocatecourse SET coursecord='$cour' WHERE staffid='".$_SESSION['staffids']."' AND acadsession='".$_SESSION['acadsession']."' AND coursecode='".$_SESSION['coursecode']."' ");
        if ($upd) {
           $showModalsucs='true';
           $succ="Detail Successfully Updated From This Staff";
        }else{
            $error= "Error Occured while Updating Information";
        $showModals='true';
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="css/main.css" media="screen" >
<link rel="stylesheet" href="css/receipt.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
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
                     }elseif ($paych['position']=="dvc_acad") {
                        
                    
                    include('includes/dvcbar.php');
                     }elseif ($paych['position']=="vc") {
                        
                    
                    include('includes/vcbar.php');
                     }elseif ($paych['position']=="dean") {
                    include('includes/deanbar.php');
                     }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     
                        <?php 
                        $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                        if ($paych['position']=="superadmin") {
                            
                        
                       ?>
     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title">Admin Course Allocation Setup</h3>
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
                    <div> 
                        
                        <h5 style="font-size:17px;color:red;">
                            <ol>
                                <li>Please Select Lecturer From The Drop-Down</li>
                                <li>Then Select Course Then Click Assigned</li>
                                <li style="color:blue;">Current Session: <?php echo htmlentities($secc); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Semester: <?php echo htmlentities($sems);?></li> 
                                <li style="color:blue;">Programme: <?php echo htmlentities($prgs); ?></li>
                            </ol>
                        </h5>
                        </div> 
                    <div class="panel-body">                                                
                        <section class="section">
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">     
                                <div class="form-group">
                                    <label for="default" class="col-sm-2 control-label">Staff Name:</label>
                                        <div class="col-sm-10">
                                            <select name="staffid" class="form-control clid" required="required">
                                                <option selected="selected" disabled="disabled">Select Staff Name</option>
                                                <?php
                                                $res=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid  WHERE  staffaculty.deptm='$deplt' AND people.status='active' ORDER BY staffaculty.staffid");//
                                                while ($rows=mysqli_fetch_array($res))
                                                {?>
                                                <option value="<?php echo  $rows["staffid"]; ?>"><?php echo htmlentities($rows["staffid"]." ".$rows['title'].". ".$rows['lastname']." ".$rows['firstname']) ?></option>
                                                <?php }?>

                                        
                                            </select>
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label for="default" class="col-sm-2 control-label">Course:</label>
                                        <div class="col-sm-10">
                                            <select class="form-control selectpicker" data-live-search="true"  name="course" >
                                                <option selected='selected' disabled>Please Select Course</option>
                                                <?php
                                                        $res = mysqli_query($con, "SELECT * FROM course  ORDER BY coursecode");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["coursecode"]; ?>"><?php echo  $row["coursecode"]; ?> <?php echo  $row["title"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                </div>                
                            <div class="form-group">
                                <label for="default" class="col-sm-2 control-label">Course Master</label>
                                <div class="col-sm-10">
                                    <select name="courscon" class="form-control" id="default" required="required">
                                        <option selected="selected" disabled="disabled">Please Select Course Master</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>                 
                                    <!-- Sepreaaater -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" name="allocate" id="submit" class="btn btn-primary">Allocate Course</button>
                                    </div>
                                </div>
                                                           
                                                         
                            </form>
                        </section>
                        
                        <section>
                           <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staffid</th>
                                        <th>Staff Name</th>
                                        <th>Course Code</th>
                                        <th>Course Master</th>
                                        <th>Semester</th>
                                        <th>Session</th>
                                        <th>Action</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                            <?php

                            
                              $sql = "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN allocatecourse ON staffaculty.staffid=allocatecourse.staffid WHERE staffaculty.deptm='$deplt' AND allocatecourse.semester='$sems' AND allocatecourse.acadsession='$secc' ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   

                                ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td class="td"><?php echo htmlentities($result['staffid']) ?></td>
                                <td class="td"><?php echo htmlentities($result['title']." ".$result['lastname']." ".$result['firstname']) ?></td>
                                <td><?php echo htmlentities($result['coursecode']);?></td>
                                <td><?php echo htmlentities($result['coursecord']);?></td>
                                <td><?php echo htmlentities($result['semester']);?></td>
                                <td><?php echo htmlentities($result['acadsession']);?></td>
                                <td>
                                    <button class="btn btn-primary userinfof" id="<?php echo $result['staffid'] ?>" pos="<?php echo $result['coursecode'] ?>" cvls="<?php echo $result['semester']?>" ctma="<?php echo $result['coursecord']?>" sess="<?php echo $result['acadsession']?>">  <i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger userinfo" id="<?php echo $result['staffid'] ?>" pos="<?php echo $result['coursecode'] ?>" cvls="<?php echo $result['semester']?>"> <i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                            </tbody>
                            </table>
                    </section>
                    <?php 
                    }elseif($paych['position']=="dvc_acad" OR $paych['position']=='dean' OR $paych['position']=="vc"){ ?>
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title">Course Allocated Report Setup</h3>
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
                                            <section>
                           <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Staffid</th>
                                        <th>Staff Name</th>
                                        <th>Course Code</th>
                                        <th>Course Master</th>
                                        <th>Semester</th>
                                        <th>Session</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php

                            
                              $sql = "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN allocatecourse ON staffaculty.staffid=allocatecourse.staffid WHERE staffaculty.deptm='$deplt' AND allocatecourse.semester='$sems' AND allocatecourse.acadsession='$secc' ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   

                                ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td class="td"><?php echo htmlentities($result['staffid']) ?></td>
                                <td class="td"><?php echo htmlentities($result['title']." ".$result['lastname']." ".$result['firstname']) ?></td>
                                <td><?php echo htmlentities($result['coursecode']);?></td>
                                <td><?php echo htmlentities($result['coursecord']);?></td>
                                <td><?php echo htmlentities($result['semester']);?></td>
                                <td><?php echo htmlentities($result['acadsession']);?></td>
                                
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                            </tbody>
                            </table>
                                </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <?php
                        }else{
                            ?>
                   
    <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title">Admin Course Allocation Setup</h3>
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
                          <p style='color:red;margin-left:23px;'>Dear: <b style="color:blue;"><?php echo htmlentities($fullname) ?> </b>You Don't Have Access to this Page Thank You
                          </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<div class="modal" id="viewModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="float:left;">Delete Comfirmation <i class="fa fa-trash"></i> </h4>
                <button class="close" data-dismiss="modal" style="float:right">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="teacher_details">

            </div>

            <!-- Modal footer -->

        </div>
    </div>
</div>
<div class="modal" id="viewModals">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="float:left;">Update Course Comfirmation <i class="fa fa-refresh"></i> </h4>
                <button style="float:right;" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="teacher_detailss">

            </div>

            <!-- Modal footer -->

        </div>
    </div>
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
                            <p>Please Select Programme</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="modal fade" id="checkModalh" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p style='color:red;'>
                                <?php 
                                    echo htmlentities($error);
                                ?>
                            </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Success Message -->
            <div class="modal fade" id="checkModalsuc" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                            Details Is Successfully Submitted
                            </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModalsucc" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                            <?php echo htmlentities($succ, ENT_QUOTES, 'utf-8'); ?>
                            </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ========== COMMON JS FILES ========== -->
       <script src="js/main.js"></script>
        
        <script>
            $(function($) {
                $('#example').DataTable({
                    "aLengthMenu": [[10, 15, 25, -1], [10, 15, 25, "All"]],
                    "pageLength": 10
                });

                $('#example2').DataTable( {
                    
                    "paging":         false
                });

                $('#example3').DataTable();
            });

        </script>
        <?php
            //include 'includes/buton.php';

        if(!empty($showModalEr)) 
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
        } 
         
        if(!empty($showModals)) 
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalh").modal("show");
            });
        </script>';
        }
         if(!empty($showModalsuc)) 
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalsuc").modal("show");
            });
        </script>';
        } 
         if(!empty($showModalsucs))
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalsucc").modal("show");
            });
        </script>';
        }
        ?>
         
      <script type="text/javascript">
$(document).ready(function() {
  //
    //$('.userinfo').click(function() {
    $('body').on('click','.userinfo',function() {
        var userid = $(this).attr('id');
        var userip = $(this).attr('pos');
        var clss = $(this).attr('cvls');
        // AJAX request
        $.ajax({
            url: 'deletecour',
            type: 'GET',
            data: {
                userid: userid,
                userip: userip,
                clss: clss
            },
            success: function(response) {
                $('#teacher_details').html(response);
                // Display Modal
                $('#viewModal').modal('show');
            }
        });
    });
});
$(document).ready(function() {

    //$('.userinfof').click(function() {
  $('body').on('click','.userinfof',function() {
        var userid = $(this).attr('id');
        var userip = $(this).attr('pos');
        var clss = $(this).attr('cvls');
        var cusm = $(this).attr('ctma');
        var sess = $(this).attr('sess');
        // AJAX request
        $.ajax({
            url: 'updatallcot',
            type: 'GET',
            data: {
                userid: userid,
                userip: userip,
                clss:clss,
                cusm:cusm,
                sess:sess
            },
            success: function(response) {
                $('#teacher_detailss').html(response);
                // Display Modal
                $('#viewModals').modal('show');
            }
        });
    });
});

</script>
    </body>
</html>
<?php } ?>
