<?php
session_start();
date_default_timezone_set("africa/lagos");
$connection = TRUE;
if (!isset($_SESSION['staffid']))
    header("Location: ../logout.php");
    $harr = $_SESSION['staffid'];
    
 include('../include/dbconfig.php');
 include'dataco.php';
 set_time_limit(500);
 //die($prg);
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout.php");
}
ini_set('max_execution_time', '500');


?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlentities($orgname) ;?></title>
    <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <link rel="stylesheet" href="css/print.css" media="print">
    </head>
<body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
   <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
<?php include('includes/leftbar.php');?> 
<style>
        @media print{
            table{
                width:400px;
                margin:auto;
            }
            .btn-danger{
              display: none;
            }
        }
      .cotable{
    width: 700px;
}
.cotreg{
    width: 700px;
    margin: auto;
}
.cotable td, .cotable th{
    border: 1px solid black;
}
       
  @media screen{
.lines{
  display: none;
}
}     
    .mover{
    /*width:500px;
    margin:auto;*/

}
    </style>
<div class="main-page">
    <div class="container-fluid">
        <div class="row page-title-div">
            <div>
                <h5 class="title" style="color:green;">STUDENT OUTSTANDING COURSE INFORMATION</h5>

            </div>

                <!-- /.col-md-6 text-right -->
        </div>
           
    </div>
        <!-- /.container-fluid -->
        <section class="section">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="panel">
          <div class="panel-body panels">
            <table class="table table-striped table-bordered tjks" border="1" width="100%" cellspacing=2 cellpadding=4>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Unit</th>
                        <th>Semester</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $ques= mysqli_query($con,"SELECT DISTINCT coursecode,studentid,custatus,semester FROM studelecour WHERE coursecode NOT IN (SELECT DISTINCT coursecode FROM result WHERE studentid='".$_SESSION['staffid']."') AND studentid='".$_SESSION['staffid']."'");
                    
                    $sr =1;
                    $_POST['totalUnit']=0;
                while ($rows= mysqli_fetch_array($ques)):
                    
                   ?>
                  <tr>
                    <td class="tm"><?php echo $sr;?></td>
                    <td class="td" style="text-align:left;"><?php echo $rows["coursecode"];?></td>
                    <td class="td" id="unit" name="unit"><?php
                        $df=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='".$rows["coursecode"]."'"));
                        $_POST['totalUnit']+=$df["unit"];
                     echo $df["title"];?></td>
                    <td class="td"><?php echo $df["unit"];?></td>
                    <td class="td"><?php echo $rows["semester"];?></td>
                    <td class="td"><?php echo $rows["custatus"];?></td>
                    

                  </tr>
                <?php 
                $sr++;
              endwhile;
                
                  ?>

                    <tr>
                        <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Outstanding  Units </td>
                        
                        <td style="color:red;font-weight: bolder;"><?php echo $_POST['totalUnit']; ?></td>
                        <td></td>
                        <td></td>
                        
                    </tr>
                </tbody>
            </table>
          </div>
          </div>
      </div>
  </div>
</div>
</section>
</div>
</div>
</div>

<!--  -->
</div>
</body>
</html>

<?php 
include 'includes/buton.php';

?>