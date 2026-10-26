<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
$subj= $_GET['userip'];
$clas= $_GET['clss'];

  //echo $appli;
}
?>
			<form method="POST">
        <?php
          $_SESSION['staffds']=$appli;
          $_SESSION['claa']=$subj;

          $new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people WHERE people.staffid='".$appli."'"));
          $frst=$new['title'].". ".$new['firstname']. " ".$new['lastname'];
        ?>
				<div class="modal-body">
					<p>Are you sure you want to delete  <b style="color:red"><?php echo htmlentities($subj) ?></b> From <b style="color:blue;text-transform: capitalize;"><?php echo htmlentities($frst); ?>?</b></p>
					<input type="hidden" name="delt" value="<?php echo htmlentities($subj) ?>">

				</div>
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button class="btn btn-primary" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
					<button name="deletsub" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Yes</button>

				</div>
			</form>
