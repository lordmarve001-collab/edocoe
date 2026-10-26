<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
}


?>
<form method="post">
              <?php $new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM study WHERE id='".$appli."'"));
                    $_SESSION['studi']=$appli;?>
                				<div class="modal-body">
                					<div class="form-group">
                                            <label>You are about to delete <?php echo htmlentities ($new["course"])?> <?php echo htmlentities ($new["title"])?> </label><br>
<label>Please note that this action can not be undone </label>
                                            
                                             </div>

                                        </div>
                				</div>
<input type="hidden" name="newprive" value="delete">
                				<div style="clear:both;"></div>
                				<div class="modal-footer">
                					<button class="btn btn-success" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
                					<button name="submitst" class="btn btn-danger"><span class="fa fa-trash"></span>Delete</button>

                				</div>
                				</div>
                			</form>
		</div>
	</div>
</div>
