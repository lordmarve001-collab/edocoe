<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_POST['userid']))
{

$appli= $_POST['userid'];
?>

<?php
$_SESSION['appli']=$appli;

                                        $sql = "SELECT * FROM studdata WHERE (admno='$appli')";
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
							<td style="text-transform:capitalize;font-weight: bolder;"> <?php  echo htmlentities($row['surname']." ".$row['firstname']." ".$row['lastname'])?></td>
						</tr>
						<tr>
							<th>Admission ID</th>
							<td><?php echo htmlentities($row["admno"])?></td>
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
							<th>Marital Status</th>
							<td><?php echo htmlentities($row["marital"])?></td>
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
						
						
					</table>
				
	<?php
		$stat=$row["entrysta"];
		} 
	
		?>
		
	 <table class="table">
            <!-- jupparentdetail -->
            <tr class="heading">
                <td>
                   PREVIOUS INSTITITUTION DATA
                </td>
                <td></td>
            </tr>
            <?php
             $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM transappli WHERE applid='$appli'"));
            if ($dat!="") {
                
            
        ?>
                    <tr>
            <th>Institution Name:</th>
            <td><?php echo htmlentities($dat['instname'])?></td>
            </tr>
             <tr>
                <th>Matric No:</th>
                <td><?php echo htmlentities($dat['matricno'])?></td>
            </tr>
            <tr>
                <th>Faculty:</th>
                <td><?php echo htmlentities($dat['faculty'])?></td>
            </tr>
            <tr>
                <th>Course of Study:</th>
                <td><?php echo htmlentities($dat['ccourse'])?></td>
            </tr>
             <tr>
                <th>Current Qualification:</th>
                <td><?php echo htmlentities($dat['qualifi'])?></td>
            </tr>
            <tr>
                <th>Year of Qualification:</th>
                <td><?php echo htmlentities($dat['level'])?></td>
            </tr>
           
            <?php 
            $cnt=$cnt+1;
            } ?>
        </table>
       
       
	 
<?php
}
?>