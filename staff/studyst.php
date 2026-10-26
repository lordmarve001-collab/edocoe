<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
$applp= $_GET['userip'];

  //echo $appli;
}
?>
<form method="post">
                <?php
                
                    $new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM study WHERE id='".$_GET['userid']."'"));
                    $frst=$new['title'];
	                    $_SESSION['studi']=$appli;

                ?>
				<input type="hidden" name="staffid" value="<?php echo htmlentities($_GET['userid']) ?>">
                <input type="hidden" name="postc" value="<?php echo htmlentities($_GET['userip'])?>">
				<div class="modal-body">
					<div class="form-group">
                            <label>Material Title:</label>
                             
                             <input type="text" name="staf" value="<?php echo htmlentities($frst); ?>" class="form-control clid" required="required" disabled="disabled">
                             </div>
                                                
                                            
                            <div class="form-group">
                            <label>Status:</label>
                            <select name="newprive" class="form-control" id="default" required="required">
                                
                        <option selected="selected" disabled="disabled"><?php echo htmlentities($new['status']) ?></option>
                         <option value="Active">Active</option>
                         <option value="Deactivated">Deactivate</option>
                         </select>
                        </div>
				</div>
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button class="btn btn-primary" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
					<button name="submitst" class="btn btn-success"><span class="fa fa-refresh"></span>Submit</button>
					
				</div>
				</div>
			</form>