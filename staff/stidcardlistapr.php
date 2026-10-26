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
     
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
        <link rel="stylesheet" href="css/main.css" media="screen">
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

                  if ($paych['position']=="sys_analyst")
                  {
                    
                    include('includes/sysbar.php');

                    }else if ($paych['position']=="subadmin") {
                    
                    include('includes/subbar.php');
                    }else if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }else if ($paych['position']=="dvc_acad") {
                    
                    include('includes/dvcbar.php');
                    }else if ($paych['position']=="vc") {
                    
                    include('includes/vcbar.php');
                    }else if ($paych['position']=="facofficer") {
                    
                    include('includes/facbar.php');
                    }else if ($paych['position']=="dean") {
                    
                    include('includes/deanbar.php');
                    }elseif($paych['position']=="registrar"){
                        include('includes/admoff.php');
                    }elseif($paych['position']=="admin_officer"){
                        include('includes/admof.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h4 class="title">Previous Admitted Student List Print Setup</h4>
                                
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
                     if ($paych['position']=="superadmin" OR $paych['position']=="subadmin" OR $paych['position']=="sys_analyst"  OR $paych['position']=='dvc_acad' OR $paych['position']=='registrar' OR $paych['position']=='admin_officer' OR $paych['position']=='vc' ) {
                      ?>                             
                        <section class="section not">
                                                   <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                       
                                            
                       <div class="form-group">
                            <label for="default" class="col-sm-2">ACADEMIC SESSION:</label>
                             <div class="col-sm-10">
                             <select name="section" class="form-control clid"  id="faculty" required="required">
                           
                            <option disabled="disabled" selected="selected">Select Academic Session</option>
                              <?php
                                $res = mysqli_query($con, "SELECT * FROM sections ORDER BY section DESC");
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
                            <button type="submit" name="query" class="btn btn-primary">View Student</button>
                        </div>
                    </div>                    
                    </form>
                </section>
                    <?php
                         if (isset($_POST['query'])) {
                        if (empty($_POST['section'])) {
                         $showModal = "true";
                         $error="Please Select Session";
                        }
                        else{
                        $secc=mysqli_escape_string($con,$_POST['section']);
                        
                        $sql=mysqli_query($con,"SELECT * FROM student WHERE acadsession='$secc'");
                          $rows = mysqli_num_rows($sql);
                          if($rows > 0)
                          {
                            $_SESSION['section']=$secc;
                    ?>
                    <section>
                            <table class="table table-striped table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Student ID</th>
                                                            <th>Fullname</th>
                                                            <th>Programme</th>
                                                            <th>Year Admitted</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                          <?php
                            try {
                                $sqlc="SELECT DISTINCT faculty FROM student WHERE  acadsession='$secc' ORDER BY faculty";
                                   
                                   $sts = mysqli_query($con,$sqlc);
                                        $counter = mysqli_fetch_all($sts,MYSQLI_ASSOC);
                                   $Acounter=0;
                                   foreach($counter as $rowa)
                                   {
                                     $Acounter+=1;
                                     $progd[$Acounter]=$rowa['faculty'];
                                    
                                   }
                                   $t=0;
                                        for($t=1;$t<=$Acounter; $t++)
                                    {
                                        $factt=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$progd[$t]."' "));
                                        $fact = $factt['fname'];
                                        ?>
                                        <tr style="font-weight: bold;">
               
                                                   <th colspan="5">Faculty: <?php echo htmlentities($fact) ?> <span class="style14"></span></th>
                                                </tr>
                                        
                                
                            <?php
                                /*Department Query*/
                                $sqlcf="SELECT DISTINCT programme FROM student WHERE  acadsession='$secc' AND faculty='".$progd[$t]."' ORDER BY programme";
                                   
                                   $stsf = mysqli_query($con,$sqlcf);
                                        $counterf = mysqli_fetch_all($stsf,MYSQLI_ASSOC);
                                   $Acounterf=0;
                                   foreach($counterf as $rowaf)
                                   {
                                     $Acounterf+=1;
                                     $progf[$Acounterf]  =$rowaf['programme'];
                                    
                                   }
                                   $tk=0;
                                   for($tk=1;$tk<=$Acounter; $tk++)
                                    {
                                        ?>
                                        <tr style="font-weight: bold;">
               
                                                   <th colspan="5">Programme: <?php echo htmlentities($progf[$tk]) ?> <span class="style14"></span></th>
                                        </tr>
                                         <?php
                                 $sql = "SELECT * FROM student  WHERE acadsession='$secc' AND programme='".$progf[$tk]."'  ORDER BY studentid";
                            
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
                                <td style="text-transform: capitalize;"><?php echo htmlentities($_POST['harrd'])?></td>
                                <td><?php echo htmlentities($result['programme']);?></td>
                                <td><?php echo htmlentities($result['acadsession']);?></td>
                            </tr>
                                        <?php
                                        $cnt=$cnt+1;
                                    }
                            
                            ?>
                                        <?php
                                    } 
                                    /*End of Programme Looping*/
                                 }
                            } catch (Exception $e) {
                                
                            }
                            $ght=mysqli_fetch_assoc(mysqli_query($con,"SELECT count(studentid) AS facul FROM student WHERE acadsession='$secc' "));
                             //echo $pp;
                          ?>  
                           </tbody>
                           <tr>
                                    <td colspan="5">Total No. Of Student's: <?php echo htmlentities($ght['facul']) ?></td>
                                    <!-- <td></td> -->
                                </tr>
                          </table> 
                          <div>
                                <button class="btn btn-primary" onclick="window.open('printstidsfp','_blank')">Print Detail <i class="fa fa-print"></i></button>
                            </div>                       
                        </section>
                        <?php
                            }
                            else{
                           $showModale='true';

                            }
                          }
                       }// Closing of Query If statement
                     }//closing of Level Checking 

                    else{
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
        <div class="modal" id="viewModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" >

      <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Student Details</h4>
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
                            <p><?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Error Modal Box -->
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
                            <p>There No Result Base on the Criterial Selected</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
           
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
    } 
    if(!empty($showModale)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModale").modal("show");
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
    if(!empty($showModalx)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalx").modal("show");
            });
        </script>';
    }
        ?>
    
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
        $maks=$data['programme'];
        $name =strtoupper($las)." ".strtolower($first." ".$sur);
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
}
?>