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
					}
					elseif ($paych['position']=="academicaffair") 
					{	 $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					}
					elseif ($paych['position']=="aba coordinator") 
					{	 $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						if ($row == 0) 
						{
							header("Location:abaplatform");
						}
					}
					
						elseif ($paych['position']=="pde coordinator") 
					{	 $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						if ($row == 0) 
						{
							header("Location:jupebplatform");
						}
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
					}
					elseif ($paych['position']=="sys_analyst")
					{
                        $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					}
					elseif ($paych['position']=="admin_officer")
					{
                        $qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					}
					elseif ($paych['position']=="provost")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}

					}elseif ($paych['position']=="subbursar")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}

					}
					elseif ($paych['position']=="accountant")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}

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

					}
					elseif ($paych['position']=="subadmin")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}

					}elseif ($paych['position']=="subadministrator")
					{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}

					}else if ($paych['position']=="bursar") {
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
						}
					include('includes/supbubar.php');
					}else if ($paych['position']=="exams") {
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
					}else if ($paych['position']=="registrar") {
					$qa=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qa);
						$dat = mysqli_fetch_array($qa);
						if ($row == 0) 
						{
						}
						$qts=mysqli_query($con,"SELECT * FROM registsig WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qts);
						
						if ($row == 0) 
						{
							header("Location:registsign");
						}
					}else if ($paych['position']=="vc") {
					$qa=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qa);
						$dat = mysqli_fetch_array($qa);
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}
						
					}
					elseif ($paych['position']=="nysc")
					{
					

					}
					else{
					$qs=mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='".$_SESSION['staffid']."'");
						$row = mysqli_num_rows($qs);
						
						if ($row == 0) 
						{
							header("Location:staffoffanking");
						}

					}
$query=mysqli_query($con,"SELECT * FROM people WHERE staffid='".$_SESSION['staffid']."'");
$rows = mysqli_num_rows($query);
$data = mysqli_fetch_array($query);
if ($rows == 0)
{
	header("Location: registration");
}
/*Question page*/

?>