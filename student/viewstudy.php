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
			$datat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM stafflogin WHERE staffid='".$row['staffid']."'"));
			$first = $datat['firstname'];
            $las=$datat['lastname'];
            $name =strtoupper($las)." ".strtolower($first);
		?>
		<div class="row">
					
				<div class="col-md-12">
					<table class="table">
                			
							<tr>
							<th>Course Lecturer</th>
							<td style="text-transform:capitalize"> <?php  echo htmlentities($name)?></td>
							</tr>
							<tr>
							<th>Course</th>
							<td><?php echo htmlentities (strtoupper($row["course"]))?></td>
							</tr>
							<tr>
							<th>Material Title</th>
							<td><?php echo htmlentities(strtoupper($row["title"]))?></td>
							</tr>
						<tr>
							<th>Discription/Instruction</th>
							<td><?php echo htmlentities(strtoupper($row["instructions"]))?></td>
						</tr>
						<tr>
						<th>Study Material</th>
							<td>
						<iframe src="../staff/<?php echo $row['material'];?>" class="img-thumbnail" style="height: 500px;width: 500px;" /></iframe></td>
						</tr>
			
						<a href="../staff/<?php echo $row['material'];?>" download><button class="btn btn-success" type="button"  style="float: left;"><i class="fa fa-upload"> Download</i></button></a>
						</table>	
				</div>
		</div>
		<?php
	}
}
?>