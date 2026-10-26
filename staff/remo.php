<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
$coursecode= $_POST['coursecode'];
?>

			<form method="post">
			<?php 
			$_SESSION['coursecode']=$coursecode;
			
		    ?>	
		    					<input type="hidden" name="del" value="<?php echo htmlentities($coursecode) ?>">
				
					<p>Are you sure you want to Removed  <b style="font-size: 17px;"><?php echo htmlentities($coursecode) ?>? </b>  from course list</p>
					
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button class="btn btn-danger" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
					
					<button name="deletsub" type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-trash"></span>Yes</button>
					
				</div>
				</div>
			</form>