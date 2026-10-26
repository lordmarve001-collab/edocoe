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
if (isset($_POST["submitcd"])) {
    if (empty($_POST["sect"])) {
        $req='Required';
        header("Refresh:2;");
    }elseif (empty($_POST["sects"])) {
        $rex="Required";
        header("Refresh:2;");
    }else{
    $sectionu = $_POST["sect"];
    $sectionz = $_POST["sects"];
    $section=$sectionu."/".$sectionz;
    $query=mysqli_query($con,"SELECT * FROM sections WHERE section='$section'");
    if(mysqli_num_rows($query)>0){
    $error="session ".$section." Already Exit";
    $showModalr='true';
}else{
$subu=mysqli_query($con,"INSERT INTO sections(section) VALUES('$section')");
    if ($subu>0) {
        $succ="Successfully Inserted";
        $showModal='true';
    }
}
}
}

if (isset($_POST["submitl"])) {
    
    $section =trim(mysqli_escape_string($con, $_POST["levels"]));
    $query=mysqli_query($con,"SELECT * FROM level WHERE levels='$section'");
    if(mysqli_num_rows($query)>0){
    $error="Level ".$section." Already Exit";
    $showModalr='true';
}else{
$subu=mysqli_query($con,"INSERT INTO level(levels) VALUES('$section')");
    if ($subu>0) {
        $succ="Successfully Inserted";
        $showModal='true';
    }else{
        $error="Error Occured While Submitting";
        $showModalr='true';

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
        
      <style type="text/css">
          #blah{
                width:170.256px;
                height:159px;
                border:2px solid black;
                }
                select{
    width: 200px;
    /*height: 27px;*/
    text-transform: lowercase;
    box-sizing: border-box;
    transition: .3s;
    border-radius: 4px;
    outline: none;
    border: 2px solid #aaa;
    margin: 4px 0;
    padding: 8px;
    -moz-border-radius:5px
    -webkit-border-radius:5px;
    font-family: "Time New Romans", sans-serif;
    -webkit-border-radius: 5px
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

                  if ($paych['position']=="superadmin")
                  {
                     include('includes/supbar.php');

                    }elseif ($paych['position']=="academicaffair") {
                        include('includes/acdbar.php');
                    }
                    else{
                    ?>
                    <?php
                        include('includes/leftbar.php');
                        }
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Session And Level Creation Setup</h2>
                                
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
                     if ($paych['position']=="superadmin" OR $paych['position']=="academicaffair") {
                      ?>
                                                <div class="col-lg-6">
                                                   <div class="col-lg-12">
                                                     <section class="section newsect">
                                                        <h2>Session</h2>
                                           <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                              
                                                      <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">
                                                           New Session:
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <select name="sect"  style="width:300px;">
                                            <option selected="selected" disabled="disabled">Year</option>
                                            <?php
                                        for($i=0;$i<=0;$i++){
                                        $year=date('Y',strtotime("last day of +$i year"));
                                        //echo "<option name='$year'>$year</option>";
                                        echo "<option value=\"$year\">$year</option>\n";
                                        }
                                        //for ($year = 1995;$year<=Date("Y");$year++) {
                                        //echo "<option value=\"$year\">$year</option>\n";
                                        //}
                                        ?>
                                        </select>
                                        <select name="sects"  style="width:300px;">
                                            <option selected="selected" disabled="disabled">Year</option>
                                            <?php
                                        for($i=1;$i<=1;$i++){
                                        $year=date('Y',strtotime("last day of +$i year"));
                                        //echo "<option name='$year'>$year</option>";
                                        echo "<option value=\"$year\">$year</option>\n";
                                        }
                                        //for ($year = 1995;$year<=Date("Y");$year++) {
                                        //echo "<option value=\"$year\">$year</option>\n";
                                        //}
                                        ?>
                                        </select>
                                                        </div>
                                                      </div>
                                                      
                                                 
                                              
                                                     <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcd" class="btn btn-primary">Submit Data</button>
                                                        </div>
                                                    </div>
                                                  
                                            </form>
                                        </section>
                                        
                      <div>
                          <table id="example" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Session</th>                               
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM sections ORDER BY section";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['section']) ?></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
                                                   </div>
                                                   </div>
                                                    <!-- For Level -->
                                                   <div class="col-lg-6">
                                                       <div class="col-lg-12">
                                                             <section class="section newsect">
                                                                <h2>Level</h2>
                                           <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                              
                                                      <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">
                                                           New Level:
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="levels" maxlength="3" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                            
                                                        </div>
                                                      </div>
                                                      
                                                 
                                              
                                                     <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitl" class="btn btn-primary">Submit Data</button>
                                                        </div>
                                                    </div>
                                                  
                                            </form>
                                        </section>
                                        
                      <div>
                          <table id="example3" class="table table-striped table-bordered">
                              <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Level</th>                               
                              </tr>
                          </thead>
                          <tbody>
                           <?php
                              $sql = "SELECT * FROM level ORDER BY levels";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {  
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['levels']) ?></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            } ?> 
                                                    </tbody>
                                                    </table>
                                                </div>
                                                       </div>
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
                                    <!-- /.col-md-12 -->
                                </div>
                    </div>
                </div>
                <!-- /.content-container -->
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
                            <p>Please Select <?php echo htmlentities($error); ?></p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="checkModalr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
                            <p>Details Successfully Submitted</p>
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
            if(!empty($showModalEr)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModalEr").modal("show");
                });
            </script>';
            }
            if(!empty($showModalr)) {
            // CALL MODAL HERE
            echo '<script type="text/javascript">
                $(document).ready(function(){
                    $("#checkModalr").modal("show");
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
        ?>  
    </body>
</html>
<?php } ?>