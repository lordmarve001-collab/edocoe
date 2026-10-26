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
$succ='';
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
                                    //Here is insert the accademic session detail into the database
    $admy=$ses;
/*
*Remember to Implement Welcome email to new User
*/
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
        <link rel="stylesheet" href="css/animate.min.css" media="screen">
        <style type="text/css">
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
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="hod")
                  {
                     include('includes/hodbar.php');

                  }elseif ($paych['position']=="lecturer")
                  {
                     include('includes/lectbar.php');

                  }else{
                  include('includes/leftbar.php');
                        }
                    ?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title"><?php echo htmlentities($admy) ?> Department Admitted Student(s) </h2>
                                
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
                     if ($paych['position']=="hod" OR $paych['position']=="lecturer") {
                       
                    
                     ?>      
                                       
        <section>
         <table id="example" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    
                    <th>Full Name</th>
                    <th>Student ID</th>
                    <th>Programme</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Action</th>
                   
                </tr>
            </thead>
            <tbody>
                  <?php
                  $sql = "SELECT * FROM applicant WHERE session='$admy' AND programme='$pro' AND status='Approved' ORDER BY  programme ASC, applid ASC";
                  
                $query = mysqli_query($con,$sql);
                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                $cnt=1;
                
                foreach($results as $result)
                {   

                ?>
                <tr>
                 <td><?php echo htmlentities($cnt);?></td>
                    
                    <td><?php echo htmlentities(strtoupper($result['surname']." ".$result['firstname']." ".$result['lastname']));?></td>
                    <td><?php echo htmlentities($result['applid']);?></td>
                    <td><?php echo htmlentities (strtoupper($result['programme']));?></td>
                    <td><?php echo htmlentities($result['phoneno']);?></td>
                    <td><?php echo htmlentities($result['email']);?></td>
                    <td>
                        <button class="btn btn-primary userinfor" id="<?php echo htmlentities($result['applid']) ?>" title="View Student Data"><i class="fa fa-eye"></i></button> 
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
        <!-- Error Modal Box -->
           
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';
       
       ?>
    </body>
</html>
<?php 
    }
 ?>
