<?php
session_start();
error_reporting(1);
$connection = TRUE;
include '../include/dbconfig.php';

if (strlen($_SESSION['staffid'])==""){
    header("Location: ../logout");
}else{
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
include'dataco.php';
include'apc.php';
include '../include/dbautosave.php';
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
				<?php
					$paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

		          if ($paych['position']=="hod")
		          {
		          	$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						$dat = mysqli_fetch_array($qs);
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
		          	$qauery=mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'");
					$rows = mysqli_num_rows($qauery);
					//$data = mysqli_fetch_array($query);
					if ($rows == 0)
					{
						header("Location: facultyrecord");
					}
		          	 include('includes/hodbar.php');

					}else if ($paych['position']=="lecturer") {
 					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}	
 					$qauery=mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'");
					$rows = mysqli_num_rows($qauery);
					if ($rows == 0)
					{
						header("Location: facultyrecord");
					}

					include('includes/lectbar.php');
					}elseif ($paych['position']=="dean") {
						$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					$qauery=mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'");
					$rows = mysqli_num_rows($qauery);
					if ($rows == 0)
					{
						header("Location: facultyrecord");
					}

					 include('includes/deanbar.php');
					}
					else if ($paych['position']=="facofficer") {
						$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
						$qauery=mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'");
					$rows = mysqli_num_rows($qauery);
					if ($rows == 0)
					{
						header("Location: facutyrecord");
					}
					include('includes/facbar.php');
					}
					elseif ($paych['position']=="academicaffair") 
					{	 $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
						 include('includes/acdbar.php');
					}
					elseif ($paych['position']=="aba coordinator") 
					{	 $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						if ($row == 0) 
						{
							header("Location:abaplatform");
						}
						 include('includes/abbar.php');
					}
					
						elseif ($paych['position']=="pde coordinator") 
					{	 $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						if ($row == 0) 
						{
							header("Location:jupebplatform");
						}
						 include('includes/jupbar.php');
					}
					else if ($paych['position']=="bursar") 
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					include('includes/bursar.php'); 
					}
					
					elseif ($paych['position']=="superadmin") 
					{
						/*Staff Ranking Checking*/
						$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
						/*Checking Staffreg Table*/
						$qauery=mysqli_query($con,"SELECT * FROM schoolname");
						$rows = mysqli_num_rows($qauery);
						if ($rows == 0) 
						{
							header("Location:schoolpage");
						}
						 include('includes/supbar.php');
					}
					elseif ($paych['position']=="sys_analyst")
					{
                        $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
                        include('includes/sysbar.php');
					}
					elseif ($paych['position']=="admin_officer")
					{
                        $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
                        include('includes/admof.php');
					}
					elseif ($paych['position']=="provost")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					include('includes/provbar.php');

					}elseif ($paych['position']=="subbursar")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					include('includes/sububar.php');

					}
					elseif ($paych['position']=="accountant")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					include('includes/accbar.php');

					}
					elseif ($paych['position']=="dvc_acad")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
						$qauery=mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'");
					$rows = mysqli_num_rows($qauery);
					if ($rows == 0)
					{
						header("Location: facultyrecord");
					}
					include('includes/dvcbar.php');

					}
					elseif ($paych['position']=="subadmin")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					include('includes/subbar.php');

					}	elseif ($paych['position']=="subadministrator")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					include('includes/ictbar.php');

					}else if ($paych['position']=="exams") {
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					include('includes/exambar.php');
					}else if ($paych['position']=="registrar") {
					$qa=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qa);
						$dat = mysqli_fetch_array($qa);
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
						$qts=mysqli_query($con,"SELECT * FROM registsig WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qts);
						
						if ($row == 0) 
						{
							header("Location:registsign");
						}
					include('includes/admoff.php');
					}else if ($paych['position']=="vc") {
					$qa=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qa);
						$dat = mysqli_fetch_array($qa);
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
						
					include('includes/vcbar.php');
					}
					elseif ($paych['position']=="nysc")
					{
					
					include('includes/nyscbar.php');

					}
					else{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					include('includes/leftbar.php');

					}
					?>
				<!-- Main Page -->
				<div class="main-page">
					<div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-sm-6">
                                    <h2 class="title">Dashboard</h2>
                                  
                                </div>
                                
                            </div>
                                      
                    </div>
                    <!-- Section -->
                    <section class="section">
            			<div class="container-fluid">
            				<div class="row">
                    			<div class="col-md-12">

                        			<div class="panel">
                          				<div class="panel-body panels">
                            				<div class="col-sm-3">
                          						 
                          							<img id="Image1" src="<?php echo $passport;?>" alt="Photo" style="height:152px;width:152px;">
      												<p class="text-center">      
      												<strong><span id="Label1"></span></strong>&nbsp;<strong><span id="Label16"></span></strong></p>
                      							
            								</div>
            								<div class="col-md-5">
                     				           <table class="nav-justified">
							                         
							                            <tbody><tr>
							                              <td>Staffid ID<strong>:
							                                   <span><?php echo htmlentities($_SESSION['staffid']); ?></span>
							                                  </strong></td>
							                          </tr>
							                          <tr>
							                              <td>Name <strong>:
							                                  <span><?php echo htmlentities($fullnames); ?></span>
							                                  </strong></td>
							                          </tr>
							                          <tr>
							                              <td>Sex<strong>:
							                                  <span><?php echo htmlentities($sex); ?></span>
							                                  </strong></td>
							                          </tr>
							                          
							                         
							                         <?php 
							                         $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
							                         if ($paych['position']=="hod" OR $paych['position']=='lecturer' OR $paych['position']=='dean' OR $paych['position']=='dvc_acad')
		          										{
		          											$daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT faculty,deptm,programme FROM staffaculty WHERE staffid='".$_SESSION['staffid']."'"));
															$faclt=$daft['faculty'];
															$deplt=$daft['deptm'];
															$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$faclt' "));
															$fac = $factn['fname'];
															$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='$deplt' "));
															$dt = $depna['dept'];
		          											echo "<tr>
		          													<td>Faculty:<strong> ".$factn['fname']."</strong></td>
		          												  </tr>
		          												  <tr>
		          													<td>Department:<strong> ".$depna['dept']."</strong></td>
		          												  </tr>
		          												  <tr>
		          													
		          												  </tr>";
		          										}elseif ($paych['position']=='facofficer') {
		          											echo "<tr>
		          													<td>Faculty Officer Of :<strong> ".$factn['fname']."</strong></td>
		          												  </tr>";
		          										}elseif ($paych['position']=='dean') {
		          											echo "<tr>
		          													<td>Dean Of :<strong> ".$factn['fname']."</strong></td>
		          												  </tr>
		          												  <tr>
		          													<td>Department:<strong> ".$depna['dept']."</strong></td>
		          												  </tr>
		          												  ";
		          										}else{
		          											
		          									}
													$dtms=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'"));
													?>
		          											
														
							                      </tbody>
							                  </table>
						                    </div>
						                    <div id="divb" class="col-md-4">

					                          <table class="nav-justified">
					                              
					                              <tbody>
					                               	 
							                          <tr>
							                              <td>Email<strong>:
							                                  <span><?php echo htmlentities($unmail); ?></span></strong></td>
							                          </tr>
							                          <tr>
														<td>Rank: <strong style=text-transform:capitalize;><?php echo htmlentities($dtms['rankname']) ?></strong></td>
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
			</div>
		</div>
	</div>
	<script src="js/jquery-2.2.4.min.js"></script>
        
        <script src="js/bootstrap.min.js"></script>
        <!-- ========== THEME JS ========== -->
        <script src="js/main.js"></script>
</body>
</html>
<?php } ?>