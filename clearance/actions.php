<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

if(isset($_GET['id']))
{

$appli= $_GET['id'];
//header("Content-type: image/jpg"); 
		$sql = "
		SELECT * FROM cleardoc WHERE id = '$appli'";
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
				if ($row["dis"]=='1'){
				$output .= '
				<div class="col-md-3" style="width:500px;height:auto;">
					<img src="../apply/'.$row["document"].'" class="img-thumbnail" />
				</div>
				
				';
			}else{$output .= '
				<div class="col-md-3" style="width:500px;height:auto;">
					<img src="'.$row["document"].'" class="img-thumbnail" />
				</div>
				
				';}}
			$output .= '</div>';
			echo $output;
		//}
}
	?>