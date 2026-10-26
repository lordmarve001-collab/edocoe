<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

if(isset($_GET['id']))
{

$appli= $_GET['id'];
//header("Content-type: image/jpg"); 
		$sql = "
		SELECT * FROM documents WHERE id = '$appli'";
		$query = mysqli_query($con,$sql);
		$result = mysqli_fetch_all($query,MYSQLI_ASSOC);
			//$result = $statement->fetchAll();
			$output = '
			<div class="row">
			';
			
			//echo $_POST["action"];
			foreach($result as $row)
			{
				header("Content-type: image/jpg");
				$output .= '
				<div class="col-md-3" style="width:100%;height:auto;">
					<img src="'.$row["documfile"].'" class="img-thumbnail" />
				</div>
				
				';
			}
			$output .= '</div>';
			echo $output;
		//}
}
	?>