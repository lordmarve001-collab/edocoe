<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
		$sql = "
		SELECT * FROM people WHERE staffid='$appli' ";
		$query = mysqli_query($con,$sql);
		$result = mysqli_fetch_all($query,MYSQLI_ASSOC);
		foreach($result as $row)
			{
		?>
		<div class="row">
					<div class="col-md-3">
						<img src="<?php echo $row['image'];?>" class="img-thumbnail" style="height: 150px;width: 160px;" />
					</div>
				<div class="col-md-9">
					<table class="table">
						<tr class="heading">
                			<td>
                    		<b>Personal Data</b>
                			</td>
                
                			<td>
                    
                			</td>
            				</tr>
							<tr>
							<th>Full-Name</th>
							<td style="text-transform:capitalize"> <?php  echo htmlentities($row['title'].". ".$row['lastname']." ".$row['firstname']." ".$row['othername'])?></td>
							</tr>
							<tr>
							<th>Staff_ID</th>
							<td><?php echo htmlentities($row["staffid"])?></td>
							</tr>
							
							<tr>
							<th>College Email</th>
							<td><a href="mailto:<?php echo $row['uniemail']?>"><?php echo htmlentities($row["uniemail"])?></a></td>
							</tr>
							<tr>
							<th>Phone</th>
							<td><a href="tel:<?php echo $row['phoneno']?>"><?php echo htmlentities($row["phoneno"])?></a></td>
							</tr>
							<tr>
							<th>Gender</th>
							<td><?php echo htmlentities($row["gender"])?></td>
							</tr>
							<tr>
							<th>Marital</th>
							<td><?php echo htmlentities($row["maritalstatus"])?></td>
						</tr>
						<tr>
							<th>D-O-B</th>
							<td><?php echo htmlentities($row["dob"])?></td>
						</tr>
						<tr>
							<th>Nationality</th>
							<td>
								<?php 
				            echo htmlentities($row['country']);
				            ?></td>
						</tr>
						<?php if($row['country']=="Foreign"){ ?>
							<tr>
							<th>Country</th>
							<td><?php echo htmlentities($row["state"])?></td>
						</tr>
				           <?php 
				            }
				            else{
				               ?>
				               <tr>
							<th>State</th>
							<td><?php echo htmlentities($row["state"])?></td>
						</tr>
						<tr>
							<th>L.G.A</th>
							<td><?php echo htmlentities($row["lga"])?></td>
						</tr>
				               <?php
				            }
				            ?>
						
						
						<tr>
							<th>Home Address</th>
							<td><?php echo htmlentities($row["address"])?></td>
						</tr>
						<?php }
						$off=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffrank WHERE staffid='$appli'"));
							if ($off=='') {
								// code...
							}else{ ?>
							<tr>
								<td></td>
								<td><strong>Office Unit Information</strong></td>
								<!-- <td></td> -->
							</tr>
							<tr>
								<th>Office Unit</th>
								<td><?php echo htmlentities($off["officename"])?></td>
							</tr>
							<tr>
								<th>Rank</th>
								<td><?php echo htmlentities($off["rankname"])?></td>
							</tr>
						 

						 <?php	
							}
						 $fase=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='$appli'"));
							if ($fase=='') {
								// code...
							}else{
						 ?>

						

						<tr>
							<td align="centre"><strong>Faculty Information</strong></td>
							<td></td>
						</tr>
						<?php 
							$st=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffaculty WHERE staffid='$appli' "));
							$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$st['faculty']."' "));
							$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='".$st['deptm']."' "));
							if ($st['faculty']!="" AND $st['deptm']=='') {
								
								echo "<tr>
		          													<th>Faculty  Of :</th>
		          													<td><strong> ".$factn['fname']."</strong></td>
		          												  </tr>";
							}else{
								echo "<tr>
		          													<th>Faculty:</th>
		          													<td><strong> ".$factn['fname']."</strong></td>
		          												  </tr>
		          												  <tr>
		          													<th>Department:</th>
		          													<td><strong> ".$depna['dept']."</strong></td>
		          												  </tr>";
							}
						?>
						
						</table>	
				</div>
		</div>
		<?php
	}
}
?>