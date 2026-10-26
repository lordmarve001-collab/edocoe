<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
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
    //Data Retrieve Based on the User Faculty Record
        
//Here is to Insert into the record Tables
$levls=$_SESSION['levels'];
$semest=$_SESSION['semest'];
$progra=$_SESSION['progra'];

$succ='';
$error='';

// ================= DELETE COURSE =================
if (isset($_GET['delete']) && !empty($_GET['delete'])) {

    $coursecode = mysqli_real_escape_string($con, $_GET['delete']);

    $delete = mysqli_query($con, "DELETE FROM course_allocation 
        WHERE coursecode='$coursecode' 
        AND faculty='$faclt' 
        AND department='$deplt' 
        AND programme='$pro'");

    if ($delete) {
        header("Location: dep_allocation.php?msg=deleted");
        exit;
    } else {
        die("Delete failed: " . mysqli_error($con));
    }
}
if (isset($_POST['submitcor'])) {
    //if (empty(mysqli_escape_string($con,$_POST['coursecode']))) {
    if (empty($_POST['coursecode'])) {
        $error="Select Course";
    }else if (empty($_POST['stasc'])) {
        $error="Select Status";
    }
    else{
        $courcode=mysqli_escape_string($con,$_POST['coursecode']);
        $statu=mysqli_escape_string($con,$_POST['stasc']);
$sq=mysqli_query($con,"SELECT * FROM course_allocation WHERE coursecode='$courcode' AND department='$deplt' AND faculty='$faclt' AND programme='$pro'");
            if (mysqli_num_rows($sq)==1) {
            $error="Course Code ".$cogr." already Exist";
            $showModal='true';
        }else{

                        $inst=mysqli_query($con,"INSERT INTO course_allocation (coursecode,faculty,department,programme,unit) VALUES ('$courcode','$faclt','$deplt','$pro','$statu')");
						if(!$inst){
                            $showModal='true';
							$error="Error occur";
						}else{
                            $showModals='true';
                            $succ='Successfully Submitted';
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
        <link href="../favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/animate.min.css" media="screen">
        <link rel="stylesheet" href="css/print.css" media="print">
        <script type="text/javascript" src="js/state.js"></script>
        <script type="text/javascript" src="js/pic.js"></script>
        <script type="text/javascript" src="js/country.js"></script>
        <style type="text/css">  
          #blah{
            width:170.256px;
            height:159px;
            border:2px solid black;
        }
        .td{
             cursor: not-allowed;
             font-weight: bolder;
        }
        @media screen{
            logos,logoz{
                display: none;
            }
            .fac{
                display: none;
            }
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
                   <!-- <?php //include('includes/hodbar.php');?>   -->
                    <!-- /.left-sidebar -->
                    <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="hod")
                  {
                    $query=mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'");
                    $rows = mysqli_num_rows($query);
                    $data = mysqli_fetch_array($query);
                    if ($rows == 0)
                    {
                        header("Location: facultyrecord.php");
                    }
                    ?>
                    <?php include('includes/hodbar.php');

                    }elseif ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                  }else if ($paych['position']=="lecturer") {
                        
                        $query=mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'");
                    $rows = mysqli_num_rows($query);
                    $data = mysqli_fetch_array($query);
                    if ($rows == 0)
                    {
                        header("Location: facultyrecord.php");
                    }?>

                    <?php
                    include('includes/lectbar.php');
                     }//elseif ($paych['position']=="dean") {
                        
                    // }
                    else{
                    ?>
                    <?php
                        include('includes/leftbar.php');
                        }
                    ?>
                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Department Course Allocation Setup</h2>
                                
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
                                                
                                                <section class="section newsect">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Select Course:</label>
                                                         <div class="col-sm-10">
                                                         <select  name="coursecode" class="form-control clid" required="required">
                                                        <option selected disabled>Please select Course</option>
                                        
                                                        <?php
                                                            $res = mysqli_query($con, "SELECT * FROM course  ORDER BY coursecode");
                                                        while ($row = mysqli_fetch_array($res)) {

                                                            ?>
                                                            <option value="<?php echo  $row["coursecode"]; ?>"><?php echo  $row["coursecode"]; ?> <?php echo  $row["title"]; ?></option>
                                                        <?php

                                                        }
                                                        ?>
                                                        </select>
                                                        <span style="color: red;">
                                                       
                                                   </span>
                                                        </div>

                                                    </div>
                                                   <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Select Unit:</label>
                                                         <div class="col-sm-10">
                                                         <select  name="stasc" class="form-control clid" required="required">
                                                        <option selected disabled>Please select Course Unit</option>
													
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>

                                                        </select>
                                                        <span style="color: red;">
                                                      
                                                   </span>
                                                        </div>

                                                    </div>
                                                   
                                                   
                                    <!-- Sepreaaater -->
                                                            <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcor" id="submit" class="btn btn-primary">Allocate Course</button>
                                                        </div>
                                                    </div>
                                                           
                                                         
                                                    </form>
                                                </section>
                                                <?php
            $fact=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM program WHERE pname='$pro' "));
            $fid = $fact['fid'];
	       $did = $fact['did'];
	
    $factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$fid' "));
	$fac = $factn['fname'];
	$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='$did' "));
	if ($depna!="") {
		$dt = $depna['dept'];
	}  
                                                         
                  ?>
                                                    <!-- Session to hold course information -->
                                                    <div>
                                                             <div class="brek">
                                         <div class="logos" style="width: 150px;margin: auto;">
                                            
                                            <span><img src='<?php echo $logo?>' id="images1"  style="width: 130px;height: 130px;"></span>
                                            
                                            
                                        </div>
                                        <div align="center" class="logoz"><span align="center" style="margin-top: 4%; font-size: 20px;font-weight: bolder;"><?php echo htmlentities($orgname." ".$schadress)?></span>
                                        </div>
                                          
                                    <div>
                                    <strong style="text-transform: uppercase;">
                                     <p class="fac" style="text-align: center;">Faculty: <?php echo htmlentities($fac) ?> </p>
                                  
                                     <p class="fac" style="text-align: center;text-transform: uppercase;" >Department: <?php echo htmlentities($dt); ?></p> 
                                    
                                    <p style="text-align: center;" >PROGRAMME: <strong style="text-transform: uppercase;"><?php echo htmlentities("$pro") ?></strong></p>
                                    </strong>
                                    
                                    <br />
                                    <!--<table style="margin: auto;width: 600px;">
                                      <tr>
                                        <td width="400"><strong>LEVEL:&nbsp; 
                                        <?php echo htmlentities($levls) ?>
                                        </strong></td>
                                        <td width="300"><strong style="text-transform: uppercase;float: right;">SEMESTER:&nbsp;
                                            <?php echo htmlentities($semest) ?></strong></td>
                                        </tr>
                                    </table>-->
                        </div>
                    </div>
                                                    </div>
                                    <table class="table table-striped table-bordered curr" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Course Code</th>
                                            <th>Course Title</th>
                                           <th> Unit</th>
                                            <th class="hd">Action</th>
                                        </tr>
                                        </thead>
                                        <?php
$sql = "SELECT ca.coursecode,c.title,ca.unit FROM course_allocation ca JOIN course c ON ca.coursecode = c.coursecode
WHERE ca.programme = '$pro' AND ca.faculty = '$faclt' AND ca.department = '$deplt' ORDER BY ca.coursecode";              // $query = $con->prepare($sql);
                                            // $query->execute();
                                            // $results=$query->fetchAll(PDO::FETCH_OBJ);

                                            $query = mysqli_query($con,$sql);
                                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                            $cnt=1;
                                            // if($query->rowCount() > 0)
                                            // {
                                            foreach($results as $result)
                                            {   
                                                
                                                ?>
                                            <tr>
                                             <td class="td"><?php echo htmlentities($cnt);?></td>
                                                <td class="td"><?php echo htmlentities($result['coursecode']) ?></td>
                                                <td class="td" style="text-transform: capitalize;"><?php echo htmlentities($result["title"]);?></td>
                                                <td class="td"><?php echo htmlentities($result['unit']);?></td>
                                               <td class="hd">
    <a href="dep_allocation.php?delete=<?php echo urlencode($result['coursecode']); ?>" 
       class="btn btn-danger"
       onclick="return confirm('Are you sure you want to delete this course?');">
       <span class="fa fa-trash"></span> Delete
    </a>
</td>
                                            </tr>
                                            <?php 

                                            $cnt=$cnt+1;
                                             include 'delect.php';   
                                            }
                                                GetUnit($levls,$semest,$pro); 
                                            //} ?>
                                             <tr>
                                              <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
                                              
                                              <td colspan='6' ><?php echo $_POST['totalUnit']; ?></td>
                                          </tr>
                                                       
                                                    
                                </tbody>
                                                        </table>
                                <div style="margin: auto;" class="button">
                                    <input type="button"  class="btn btn-success" value="Print-Detail" onclick="window.print()">               
                                </div>                                      
                                 </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-12 -->
                                </div>
                    </div>
                </div> <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'){ ?>
    <div class="alert alert-success">Course deleted successfully</div>
<?php } ?>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
       
        <!-- /.main-wrapper -->
		<div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Sorry You Have Not Setup Passmark For This Level Click <a href='registpast.php' style="color: red;">Here</a> to Setup</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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

            <div class="modal fade" id="checkModals" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo htmlentities($succ); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-success" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ========== COMMON JS FILES ========== -->
       <?php
        include 'includes/buton.php';
       ?>
       <script type="text/javascript">
           
         function saveInlineEdit(editableObj,column,codes,semes,level)
         {
          $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
          //alert(column);
          //alert ($(editableObj).text());
          $.ajax({
            url: "cedit.php",
            type: "POST",
            data:'column='+column+'&editval='+$(editableObj).text()+'&ccode='+codes+'&sems='+semes+'&lvs='+level,
            success: function(data){
              $(editableObj).css("background","#FDFDFD");
              //alert(data);
            }
          });
        }
        function highlightEdit(editableObj)
            {
            $(editableObj).css("background","#FFF");
            }
            // var selectValues = {
            // "Core": "Core",
            // "Elective": "Elective"
            // };
            // var $mySelect = $('#mySelect');
            // //
            // $.each(selectValues, function(key, value) {
            // var $option = $("<option/>", {
            // value: key,
            // text: value
            // });
            // $mySelect.append($option);
            // });
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
    if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    }
    if(!empty($showModals)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModals").modal("show");
            });
        </script>';
    }
    

function GetUnit($lvs,$semst,$prgr)
{
$connection = TRUE;
include('../include/dbconfig.php');
    $queryy="SELECT SUM(unit) AS sumunit FROM course_allocation WHERE programme='$prgr' ORDER BY coursecode";
$resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
				if($row['sumunit']==0){
					$_POST['totalUnit']=0;
				}
				else{
				$_POST['totalUnit']=$row['sumunit'];	
				}	  
                
            }
}
//For Deleting Deletecou($del_id,$semest,$fact,$depn,$levls,$progra);
function Deletecou($codes,$semss,$fat,$dp,$lvs,$pg)
{
    include('../include/dbconfig.php');

    $delete = mysqli_query($con,"DELETE FROM course_allocation 
        WHERE coursecode='$codes' 
        AND faculty='$fat'  
        AND department='$dp' 
        AND programme='$pg'");

    if($delete){
        header("Location: dep_allocation.php?deleted=1");
        exit;
    } else {
        die("Delete failed: " . mysqli_error($con));
    }
}
?>