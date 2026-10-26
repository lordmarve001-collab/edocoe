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
                <?php
                
                    $new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='".$appli."'"));
                    $frst=$new['firstname']. " ".$new['lastname'];
                    $_SESSION['studi']=$appli;

                   // echo$_SESSION['studi'];
                ?>
                <form method="post">

                				<div class="modal-body">
                					<div class="form-group">
                                            <label>Student Name:</label>

                                             <input type="text" name="staf" value="<?php echo htmlentities($frst); ?>" class="form-control clid" required="required" disabled="disabled">
                                             </div>


                                            <div class="form-group">
                                            <label>Status:</label>
                                            <select name="newprive" class="form-control" id="default" required="required">

                                        <option selected="selected" disabled="disabled"><?php echo htmlentities($new['status']) ?></option>
                                        <?php
                                            $res = mysqli_query($con, "SELECT * FROM statuses ORDER BY status");
                                        while ($row = mysqli_fetch_array($res)) {

                                            ?>
                                            <option value="<?php echo  $row["status"];?>"><?php echo  $row["status"]; ?></option>
                                        <?php
                                            }
                                        ?>
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
		</div>
	</div>
</div>
