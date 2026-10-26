<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
 $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT description,session,deadline FROM resultsession WHERE description='Jupeb'"));
$desc=$dat['description'];
if (isset($_POST['userid'])) {
$appli= $_POST['userid'];
$_SESSION['appli']=$appli;
//$appli= $_POST['userid'];
		$sql = "
		SELECT * FROM applicant JOIN  studdata ON studdata.admno=applicant.applid WHERE studdata.admno='$appli'";
		$query = mysqli_query($con,$sql);
		$result = mysqli_fetch_all($query,MYSQLI_ASSOC);
			
			foreach($result as $row)
			{
				?>
				<div class="row">
				<div class="col-md-3">
					<img src="../pde/<?php echo $row['passport'];?>" class="img-thumbnail" style="width: 300px;height:200px;" />
				</div>
				<div class="col-md-9">
					<table class="table">
						<tr class="heading">
                <td>
                    Personal Data
                </td>
                
                <td>
                    
                </td>
            </tr>
						<tr>
							<th>Full-Name</th>
							<td style="text-transform:capitalize;font-weight: bolder;"> <?php  echo htmlentities($row['firstname']." ".$row['surname']." ".$row['lastname'])?></td>
						</tr>
						<tr>
							<th>Application ID</th>
							<td><?php echo htmlentities($row["applid"])?></td>
						</tr>
						<tr>
							<th>Email</th>
							<td><a href="mailto:<?php echo $row['email']?>"><?php echo htmlentities($row["email"])?></a></td>
						</tr>
						<tr>
							<th>Phone</th>
							<td><a href="tel:<?php echo $row['phone']?>"><?php echo htmlentities($row["phone"])?></a></td>
							</tr>
						<tr>
							<th>Gender</th>
							<td><?php echo htmlentities($row["gender"])?></td>
						</tr>
						
						<tr>
							<th>D-O-B</th>
							<td><?php echo htmlentities($row["dob"])?></td>
						</tr>
						<tr>
							<th>Nationality</th>
							<td>
								<?php 
				            echo htmlentities($row['nationality']);
				            ?></td>
						</tr>
						<?php if($row['nationality']=="Foreign"){ ?>
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
                    Admission Information
                </td>
                
                <td>
                    
                </td>
            </tr>
						<tr>
							<th>Academic Session</th>
							<td><?php echo htmlentities($row["acadsession"])?></td>
						</tr>
						<tr>
							<th>Programme</th>
							<td><?php 
				               echo htmlentities($row['programme']); 
				            
				            ?></td>
						</tr>
						<tr>
							<th>Admission Type</th>
							<td><?php 
				               echo htmlentities($row['apptype']); 
				            
				            ?></td>
						</tr>
						
						
					</table>
				
	<?php
		 
		?>
		<table class="table">
			<tr class="heading">
                <td>
                    Secondary/ College School Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr>
            	<td>
            		<table>
            			<tr>
            				<th>First Sitting</th>
            			</tr>
            			<?php 
			/*Loading Secondary School Record*/
			$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE  applid='$appli' AND noofsit='1'"));
			if ($dat!="") {
				
			
		?>
            		<tr>
			<th>School Name</th>
			<td><?php echo htmlentities($dat['school'])?></td>
			</tr>
			<tr>
				<th>Exam Body</th>
				<td><?php echo htmlentities($dat['exambody'])?></td>
			</tr>
			<tr>
				<th>Exam No</th>
				<td><?php echo htmlentities($dat['examno'])?></td>
			</tr>
			<tr>
				<th>Exam Year</th>
				<td><?php echo htmlentities($dat['examyear'])?></td>
			</tr>
			<tr>
				<th>From</th><th>To</th></tr>
				<tr><td><?php echo htmlentities($dat['yearadmit'])?></td>
				
				<td><?php echo htmlentities($dat['yeargrad'])?></td>
			</tr>

			<tr>
				<!-- <th>#</th> -->
				<th>Subject</th>
				<th>Grade</th>
			</tr>
			<?php 
				$sql = "SELECT * FROM olevelresultdetail WHERE applid='$appli' AND examno='".$dat['examno']."' ORDER BY subject";

				$query=mysqli_query($con,$sql);
				$results=mysqli_fetch_all($query,MYSQLI_ASSOC);
				$cnt=1;
				foreach($results as $result)
				{   ?>
			<tr>
				<!-- <td><?php echo htmlentities($cnt);?></td> -->
                <td><?php echo htmlentities($result['subject']);?></td>
            	<td><?php echo htmlentities($result['grade']);?></td>
			</tr>
			<?php 
			$cnt=$cnt+1;
			}} ?>
            		</table>
            	</td>
            	<td>
            		<table>
            			<tr>
            				<th>Second Sitting</th>
            			</tr>
            			<?php 
			/*Loading Secondary School Record*/
			$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='$appli' AND noofsit='2'"));
			if ($dat!="") {
				
			
		?>
            		<tr>
			<th>School Name</th>
			<td><?php echo htmlentities($dat['school'])?></td>
			</tr>
			<tr>
				<th>Exam Body</th>
				<td><?php echo htmlentities($dat['exambody'])?></td>
			</tr>
			<tr>
				<th>Exam No</th>
				<td><?php echo htmlentities($dat['examno'])?></td>
			</tr>
			<tr>
				<th>Exam Year</th>
				<td><?php echo htmlentities($dat['examyear'])?></td>
			</tr>
			<tr>
				<th>From</th><th>To</th></tr>
				<tr><td><?php echo htmlentities($dat['yearadmit'])?></td>
				
				<td><?php echo htmlentities($dat['yeargrad'])?></td>
			</tr>

			<tr>
				<!-- <th>#</th> -->
				<th>Subject</th>
				<th>Grade</th>
			</tr>
			<?php 
				$sql = "SELECT * FROM olevelresultdetail WHERE applid='$appli' AND examno='".$dat['examno']."' ORDER BY subject";

				$query=mysqli_query($con,$sql);
				$results=mysqli_fetch_all($query,MYSQLI_ASSOC);
				$cnt=1;
				foreach($results as $result)
				{   ?>
			<tr>
				<!-- <td><?php echo htmlentities($cnt);?></td> -->
                <td><?php echo htmlentities($result['subject']);?></td>
            	<td><?php echo htmlentities($result['grade']);?></td>
			</tr>
			<?php 
			$cnt=$cnt+1;
			}} ?>
            		</table>
            	</td>
            </tr>
        </table>
		<table class="table">
			<!-- jupparentdetail -->
			<tr class="heading"><?php
            $dap=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM transappli WHERE applid='$appli'"));
            if ($dap!='') {
            	
            
            ?>
                <td>
                    Previous Institution Information
                </td>
                <td></td>
            </tr>
            
            <tr>
				<th>Institution Name</th>
				<td><?php echo htmlentities($dap["title"].". ".$dap['instname']) ?></td>
			</tr>
			<tr>
				<th>Course of study</th>
				<td><?php echo htmlentities($dap["ccourse"])?></td>
			</tr>
			<tr>
				<th>Faculty</th>
				<td><?php echo htmlentities($dap["faculty"])?></td>
			</tr>
			<tr>
				<th>Matriculation No</th>
				<td><?php echo htmlentities($dap["matricno"])?></td>
			</tr>
				<tr>
				<th>Year Graduated</th>
				<td><?php echo htmlentities($dap["level"])?></td>
			</tr>	<tr>
				<th>Certificate</th>
				<td><?php echo htmlentities($dap["qualifi"])?></td>
			</tr>
			
			
		</table>
<?php }else echo "No Previous Institution Information uploaded"
                                 ?> 		<table class="table">
			<!-- jupparentdetail -->
			<tr class="heading">
                <td>
                </td>
                <td></td>
            </tr>
            <?php
            $dap=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='$appli'"));
            if ($dap!='') {
            	
            
            ?>
            <tr>
				<th>Name</th>
				<td><?php echo htmlentities($dap["title"].". ".$dap['parentname']) ?></td>
			</tr>
			<tr>
				<th>Address</th>
				<td><?php echo htmlentities($dap["address"])?></td>
			</tr>
			<tr>
				<th>Phone</th>
				<td><?php echo htmlentities($dap["phone"])?></td>
			</tr>
			<tr>
				<th>Email</th>
				<td><?php echo htmlentities($dap["email"])?></td>
			</tr>
			
			
		</table>
		Affidavit of Fact
		<div class="col-md-3">
					<img src="../aba/<?php echo $row['affidavid'];?>" class='img-fluid'  style="width:500px;height:500px;"/>
				</div>
		<?php } ?>
</div>
			</div>
<?php
	}
}
?>