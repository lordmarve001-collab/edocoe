<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

$appli= $_POST['userid'];
$_SESSION['userid']=$appli;
$pt=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM feespope WHERE id='$appli'"));
?>
<form method="post">
	<div class="modal-body">
		 
		<div class="form-group">
	        <label>Purpose:</label>
	         
	         <input type="text" name="staf" value="<?php echo htmlentities($pt['feename']); ?>" class="form-control clid" required="required">
		</div>
		
	</div>
	<div style="clear:both;"></div>
		<div class="modal-footer">
			<button class="btn btn-danger" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-danger"></span> No</button>

			<button name="submitpr" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Submit</button>
				
		</div>
		
</form>