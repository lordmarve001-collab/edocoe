<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

$appli= $_POST['userid'];
?>
			<form method="POST">
        <?php
          $_SESSION['userid']=$appli;
          $stf=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people WHERE staffid='$appli'"));

        ?>
				<div class="modal-body">
					<p>Are you sure you want to Remove Hostel Record?</p>
				</div>
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button class="btn btn-primary" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
					<button name="updaterol" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Yes</button>

				</div>
			</form>
