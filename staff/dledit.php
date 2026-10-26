<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
$studid= $_POST['userid'];
$level= $_POST['lvd'];
$sess= $_POST['pur'];
$sems= $_POST['pty'];
$prog=$_POST['prg'];
?>

			<form method="post">
			<?php 
			$_SESSION['userid']=$studid;
			$_SESSION['lvd']=$level;
			$_SESSION['prg']=$prog;
			$_SESSION['sess']=$sess;
			$_SESSION['sems']=$sems;
		    ?>	
				
					<p>Are you sure you want to Removed  <b style="font-size: 17px;"><?php echo htmlentities($studid) ?>? </b>  Student Cleared Information</p>
					
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button class="btn btn-danger" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
					<button name="updaterol" type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-trash"></span>Yes</button>
					
				</div>
				</div>
			</form>