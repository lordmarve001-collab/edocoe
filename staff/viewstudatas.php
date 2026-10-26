<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
	$sel=
$sql = "SELECT * FROM student WHERE studentid='$appli'";
		$query = mysqli_query($con,$sql);
		$result = mysqli_fetch_all($query,MYSQLI_ASSOC);
			
			foreach($result as $row)
			{
		?>
			<div class="row">
					<div class="col-md-3">
						<img src="../student/<?php echo $row['image'];?>" class="img-thumbnail"  style="height: 150px;width: 160px;" />
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
							<td style="text-transform:capitalize"> <?php  echo htmlentities($row['firstname']." ".$row['lastname'])?></td>
							</tr>
							<tr>
							<th>Student ID</th>
							<td><?php echo htmlentities($row["studentid"])?></td>
							</tr>
							<tr>
							<th>University Email</th>
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
						<tr class="heading">
		                <td>
		                    <b>Academic Information</b>
		                </td>
		                
		                <td>
		                    
		                </td>
            			</tr>
            			<tr>
							<th>Mode Of Entry</th>
							<td><?php echo htmlentities($row['entrysta'])?></td>
						</tr>
						<tr>
							<th>Year Admitted</th>
							<td><?php echo htmlentities($row["acadsession"])?></td>
						</tr>
						<tr>
							<th>Faculty</th>
							<?php 
								$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$row['faculty']."'"));
									$fac = $factn['fname'];
							?>
							<td><?php echo htmlentities($fac)?></td>
						</tr>
						<tr>
							<th>Departement</th>

							<td><?php 
								$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='".$row['department']."' "));
								$dt = $depna['dept'];

							echo htmlentities($dt)?>
								
							</td>
						</tr>
						<tr>
							<th>Programme</th>
							<td><?php echo htmlentities($row['programme'])?></td>
						</tr>
						<tr class="heading">
		                <td>
		                    <b>Parent Information</b>
		                </td>
		                <td></td>
            			</tr>
						<tr>
							<th>Parent Name</th>
							<td><?php echo htmlentities($row["pname"])?></td>
						</tr>
						<tr>
							<th>Parent Email</th>
							<td><a href="mailto:<?php echo $row['pemail']?>"><?php echo htmlentities($row["pemail"])?></a></td>
						</tr>
						<tr>
							<th>Parent Phone</th>
							<td><a href="tel:<?php echo $row['pno']?>"><?php echo htmlentities($row["pno"])?></a></td>
						</tr>
					</table>
					
				</div>
			</div>
			<?php

		}
	}
?>		