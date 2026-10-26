<?php
session_start();
$connection = TRUE;
include('../include/dbconfig.php');
 if(strlen($_SESSION['staffid'])=="")
   {   
   header("Location: logout"); 
   }
   else{
    include 'dataco.php';
  $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
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
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
              <?php include('includes/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">

                    <?php include('includes/leftbar.php');?>  

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-sm-6">
                                    <h2 class="title">Transcript Dashboard</h2>
                                  
                                </div>
                                <!-- /.col-sm-6 -->
                            </div>
                            <!-- /.row -->
                      
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="panel">
                                        <div class="panel-body panels">
                                            <div class="col-sm-3">
                                                 
                                                    <img id="Image1" src='../student/<?php echo $passport ?>' alt="Photo" style="height:152px;width:152px;">
                                                    <p class="text-center">      
                                                    <strong><span id="Label1"></span></strong>&nbsp;<strong><span id="Label16"></span></strong></p>
                                                <br>
                                            </div>
                                            <div class="col-md-5">
                     
                                                  
                                                  <table class="nav-justified">
                                                     
                                                        <tbody>
                                                            <tr>
                                                          <td>Student ID<strong>:
                                                               <span> <?php echo htmlentities($userid) ?> </span>
                                                              </strong></td>
                                                            </tr>
                                                            <tr>
                                                          <td>Name<strong>:
                                                               <span style="text-transform: capitalize;"><?php echo htmlentities($fullname) ?> </span>
                                                              </strong></td>
                                                            </tr>
                                                      <tr>
                                                          <td>Gender<strong>:
                                                              <span><?php echo htmlentities($sex) ?> </span>
                                                              </strong></td>
                                                      </tr>
                                                      <tr>
                                                          <td>Marital<strong> </strong>Status<strong>:
                                                              <span><?php echo htmlentities($mart) ?> </span>
                                                              </strong></td>
                                                      </tr>
                                                      <tr>
                                                          <td>Date<strong> </strong>of<strong> </strong>Birth<strong>:
                                                              <span><?php echo htmlentities($dop) ?> </span></strong></td>
                                                      </tr>
                                                      <tr>
                                                          <td>Phone<strong> </strong>Number<strong>:
                                                              <span><?php echo htmlentities($phone) ?> </span></strong></td>
                                                      </tr>
                                                      <tr>
                                                          <td>State<strong> </strong>of<strong> </strong>Origin<strong>:
                                                              <span><?php echo htmlentities($statec) ?> </span></strong></td>
                                                      </tr>
                                                      <tr>
                                                      <td>L.G.A<strong> :
                                                          <span><?php echo htmlentities($lga) ?> </span>
                                                          </strong></td>
                                                      </tr>
                                                      <tr>
                                                          <td>Email:
                                                             <strong> <span><?php echo htmlentities($email) ?> </span></strong></td>
                                                      </tr>
                                                      
                                                      
                                                  </tbody>
                                              </table>
                                            </div>
                                            <div id="divb" class="col-md-4">

                                              <table class="nav-justified">
                                                  
                                                  <tbody>
                                                    <tr>
                                                        <td>Address<strong>:
                                                      <span><?php echo htmlentities($address) ?> </span>
                                                      </strong></td>
                                                    </tr>
                                                    <tr>
                                                       <td>Faculty<strong> :
                                                      <span><?php echo htmlentities($facu) ?> </span>
                                                      </strong></td>
                                                     </tr>
                                                   <tr>
                                                      <td>Department<strong> :
                                                      <span><?php echo htmlentities($def) ?> </span>
                                                      </strong></td>
                                                   </tr>
                                              <tr>
                                                  <td>Programme<strong>:
                                                      <span><?php echo htmlentities($progr) ?> </span>
                                                      </strong></td>
                                              </tr>
                                              <tr>
                                                      <td>Parent<strong> </strong>Name<strong>:
                                                          <span><?php echo htmlentities($pname) ?></span>
                                                          </strong></td>
                                                  </tr>
                                                  <tr>
                                                      <td>Parent<strong> </strong>Phone<strong> </strong>No<strong>:
                                                          <span><?php echo htmlentities($pnos) ?></span>
                                                          </strong></td>
                                                  </tr>
                                                  
                                                  <tr>
                                                      <td>Parent Email:<strong><span><?php echo htmlentities($pnem) ?> </span>
                                                          </strong></td>
                                                  </tr>

                                                  <tr>
                                                      <td>Status:<strong><span> <?php echo htmlentities(ucfirst($sta)) ?> </span>
                                                          </strong></td>
                                                  </tr>


                                              </tbody>
                                          </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    </div>
                    <!-- /.main-page -->

                    
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->

        </div>
        <!-- /.main-wrapper -->

        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <!-- ========== THEME JS ========== -->
        <script src="js/main.js"></script>
        
    </body>
</html>
<?php
//include 'ipcap.php';
 } ?>
