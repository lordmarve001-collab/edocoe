<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
$appli= $_POST['userid'];

 $_SESSION['staffds']=$appli;
?>

			<form method="post">
				
				
					<p>Are you sure you want to Removed Payment Data</p>
					
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button class="btn btn-primary" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
					<button name="updaterol" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Yes</button>
					
				</div>
				</div>
			</form>