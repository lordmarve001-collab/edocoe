<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_GET['userid']))
{
$appli= $_GET['userid'];
$lv=$_GET['lvd'];
$pur=$_GET['pur'];
$pty=$_GET['pty'];
$prg=$_GET['prg'];
$_SESSION['userid']=$appli;
$_SESSION['lvd']=$lv;
$_SESSION['pur']=$pur;
$_SESSION['pty']=$pty;
//$_SESSION['prg']=$prg;
}
?>
<form method="post">
	<div class="modal-body">
		 <div class="col-lg-6">
           <!-- <div class="col-lg-12"> -->
		<div class="form-group">
	        <label>Purpose:</label>
	         
	         <input type="text" name="staf" value="<?php echo htmlentities($pur); ?>" class="form-control clid" required="required" disabled="disabled">
	    <!-- </div> -->
		</div>
		<div class="form-group">
	        <label>Level:</label>
	         
	         <input type="text" name="staf" value="<?php echo htmlentities($appli); ?>" class="form-control clid" required="required" disabled="disabled">
	    </div>
	</div>
	<div class="col-lg-6">
	    <!-- <div class="col-lg-12"> -->
	    	<div class="form-group">
	        <label>Pay Type:</label>
	         
	         <input type="text" name="staf" value="<?php echo htmlentities($pty); ?>" class="form-control clid" required="required" disabled="disabled">
	   <!--  </div> -->
	</div>
	   <div class="form-group">
	        <label>Current Amount (&#8358;):</label>
	         
	         <input type="text" name="staf" value="<?php echo htmlentities(number_format($lv,2)); ?>" class="form-control clid" required="required" disabled="disabled">
	    
	    </div>
	</div>
	    
	    
	    <div class="form-group">
	        <label>New Amount:</label>
	         
	         <input type="number"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="newmount" class="form-control clid" required="required">
	    </div>
	</div>
	<div style="clear:both;"></div>
		<div class="modal-footer">
			<button class="btn btn-danger" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-danger"></span> No</button>

			<button name="submitpr" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Submit</button>
				
		</div>
		
</form>