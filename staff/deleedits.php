<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

$pur=$_POST['pur'];
$id=$_POST['id'];

?>
<form method="post">
<?php 

$_SESSION['pur']=$pur;
$_SESSION['id']=$id;
?>			
				
					<b style="font-size: 17px;text-align: justify;"><p>Are you sure you want to Removed  <?php echo htmlentities($pur) ?>  School Fee Information?</p></b> 
					
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button class="btn btn-primary" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
					<button name="updaterol" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Yes</button>
					
				</div>
				</div>
</form>