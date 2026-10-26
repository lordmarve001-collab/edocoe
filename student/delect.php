<div class="modal fade" id="update_modal<?php echo $rows['coursecode']?>" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST">
				<div class="modal-header">
					<h3 class="modal-title">Delete Coure</h3> <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete  <?php echo htmlentities($rows['coursecode']) ?>?</p>
					<input type="hidden" name="delt" value="<?php echo htmlentities($rows['coursecode']) ?>">
				</div>
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button class="btn btn-primary" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
					<button name="updateroll" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Yes</button>
					
				</div>
				</div>
			</form>
		</div>
	</div>
</div>