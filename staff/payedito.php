<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

$appli= $_POST['userid'];
$lv=$_POST['lvd'];
$pur=$_POST['pur'];

?>
<form method="post">
	<div class="modal-body">
		 <div class="col-lg-6">
           <?php
			$_SESSION['userid']=$appli;
			$_SESSION['lvd']=$lv;
			$_SESSION['pur']=$pur;
           ?>
		<div class="form-group">
	        <label>Purpose:</label>
	         
	         <input type="text" name="staf" value="<?php echo htmlentities($pur); ?>" class="form-control clid" required="required" disabled="disabled">
		</div>
		
		<div class="form-group">
	        <label>Current Amount (&#8358;):</label>
	         
	         <input type="text" name="staf" value="<?php echo htmlentities(number_format($lv,2)); ?>" class="form-control clid" required="required" disabled="disabled">
	    
	    </div>
	</div>
	<div class="col-lg-6">
	    <div class="form-group">
	        <label>New Amount:</label>
	         
	         <input type="number"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="newmount" class="form-control clid" required="required">
	    </div>
	</div>
</div>
	<div style="clear:both;"></div>
		<div class="modal-footer">
			<button class="btn btn-danger" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-danger"></span> No</button>

			<button name="submitpr" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Submit</button>
				
		</div>
		
</form>