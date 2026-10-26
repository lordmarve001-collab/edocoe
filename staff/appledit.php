<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_POST['userid']))
{
$appli= $_POST['userid'];

$sele=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM applicant WHERE applid='$appli'"));
}
?>
<form method="post">
	<?php 
		$_SESSION['userid']=$appli;
	?>
	<div class="modal-body">
		 <div class="col-lg-6">
           <!-- <div class="col-lg-12"> -->
		<div class="form-group">
	        <label>Firstname:</label>
	         
	         <input type="text" name="firstname" value="<?php echo htmlentities($sele['firstname']); ?>" class="form-control clid">
	    <!-- </div> -->
		</div>
		<div class="form-group">
	        <label>Lastname:</label>
	         
	         <input type="text" name="lastname" value="<?php echo htmlentities($sele['lastname']); ?>" class="form-control clid">
	    </div>
	</div>
	<div class="col-lg-6">
	    <!-- <div class="col-lg-12"> -->
	    	<div class="form-group">
	        <label>Application ID:</label>
	         
	         <input type="text" name="jamreg" value="<?php echo htmlentities($appli); ?>" class="form-control clid" required="required" >
	  
	</div>
	  <div class="form-group">
	        <label>Status:</label>
	         <select name="programe" class="form-control clid">
	         	<option value="<?php echo htmlentities($sele['status']); ?>"><?php echo htmlentities($sele['status']); ?></option>
	         	<option value="Approved">Approved</option>
	         	<option value="rejected">rejected</option>
	         	<option value="submitted">submitted</option>
	         </select>
	    </div>
	</div>
	
	   
	</div>
	<div style="clear:both;"></div>
		<div class="modal-footer">
			<button class="btn btn-danger" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-danger"></span> No</button>

			<button name="submitpr" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Submit</button>
				
		</div>
		
</form>