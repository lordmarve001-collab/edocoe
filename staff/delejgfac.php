<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_POST['userid']))
{

$appli= $_POST['userid'];
}
?>
			<form method="POST">
        <?php
          $_SESSION['staffds']=$appli;

          $new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jupfaculty WHERE fid='".$appli."'"));
          $frst=$new['fname'];
        ?>
				<div class="modal-body">
					<p>Are you sure you want to Remove <b style="color:blue;text-transform: capitalize;"><?php echo htmlentities($frst); ?>?</b> From Laboratory Fee Faculty</p>
				</div>
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button class="btn btn-primary" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
					<button name="deletsub" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Yes</button>

				</div>
			</form>
