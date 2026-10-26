<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';


$appli= $_POST['userid'];
$hids= $_POST['hid'];
$slid= $_POST['sid'];


$_SESSION['did']=$appli;
echo $appli;
?>
<table class="table table-striped table-bordered">
<thead>
	<tr>
		<th>#</th>
		<th>StudenID</th>
		<th>Name</th>
		<th>Level</th>
		<th>Bed Space</th>
	</tr>
</thead>
<?php
$sqls = "SELECT * FROM hostelroomalloc WHERE  hostelname='$hids' AND roomno='$appli' AND acadsession='$slid'";

	$querys=mysqli_query($con,$sqls);
	$resultst=mysqli_fetch_all($querys,MYSQLI_ASSOC);
	$cnt=1;

	foreach($resultst as $resultt)
	{ 
		stuname($con,$resultt['studentid']);
	?>
	<tbody>
		<tr>
			<td><?php echo htmlspecialchars($cnt) ?></td>
			<td><?php echo htmlspecialchars($resultt['studentid']) ?></td>
			<td><?php echo htmlspecialchars($_POST['harrd']) ?></td>
			<td><?php echo htmlspecialchars($resultt['level']) ?></td>
			<td><?php echo htmlspecialchars($resultt['bedno']) ?></td>
		</tr>	
	</tbody>
	
	<?php
	$cnt = $cnt + 1;
	}			
 ?>
</table>
<?php
function stuname($con,$maxt)
 {
    
    $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }

      $sql =mysqli_query($con,"SELECT *  FROM  personaldata WHERE studentid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }

 }
?>	