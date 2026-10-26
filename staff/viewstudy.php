<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
		$sql = "
		SELECT * FROM study WHERE id='$appli' ";
		$query = mysqli_query($con,$sql);
		$result = mysqli_fetch_all($query,MYSQLI_ASSOC);
		foreach($result as $row)
			{
		?>
		<div class="row">
					
				<div class="col-md-12">
					<table class="table">
                			
							<tr>
							<th>Staff ID</th>
							<td style="text-transform:capitalize"> <?php  echo htmlentities($row['staffid'])?></td>
							</tr>
							<tr>
							<th>Course</th>
							<td><?php echo htmlentities ($row["course"])?></td>
							</tr>
							<tr>
							<th>Material Title</th>
							<td><?php echo htmlentities($row["title"])?></td>
							</tr>
						<tr>
							<th>Discription/Instruction</th>
							<td><?php echo htmlentities($row["instructions"])?></td>
						</tr>
						<tr>
						<th>Study Material</th>
							<td>
						<iframe src="<?php echo $row['material'];?>" class="img-thumbnail" style="height: 500px;width: 500px;" /></iframe></td>
						</tr>
						
						</table>	
				</div>
		</div>
		<?php
	}
}
?>