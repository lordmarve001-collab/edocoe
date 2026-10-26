<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_GET['userid']))
{

$appli= $_GET['userid'];

}
$_SESSION['did']=$appli;
$result=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM resultsession WHERE id='$appli'"));							
  ?>
			<form method="POST" action="">
				
				<div class="modal-body">
					<div class="col-md-12">
						<div class="form-group">
							<label>Description</label>
							<input type="text" name="subject" value="<?php echo $result['description']?>" class="form-control" style="width: 100%;" required="required" disabled/>
						</div>
						<div class="form-group">
							<label>Current Session</label>
							<select name="section" class="form-control clid" id="default" required="required">

						<option selected="selected" value="<?php echo  htmlentities($result['session'])?>"><?php echo htmlentities($result['session'])?></option>
						<?php 
                                            $res = mysqli_query($con,"SELECT * FROM sections ORDER BY section DESC");
                                            while ($row=mysqli_fetch_array($res))
                                            {
                                                
                                            ?>
                                            <option value="<?php echo htmlentities($row["section"]); ?>"><?php echo htmlentities($row["section"]); ?></option>
                                            <?php

                                            }
                                             ?>
						 </select>
						</div>
						
						<div class="form-group">
							<label>Deadline</label>
							<input type="date" name="deadline" class="form-control" value="<?php echo $result['deadline'] ?>">
						</div>
						
					</div>
				</div>
				<div style="clear:both;"></div>
				<div class="modal-footer">
					<button name="updateusea" class="btn btn-warning" style="float: left;"><span class="glyphicon glyphicon-edit"></span> Update</button>
					<button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
				</div>
				</div>
			</form>
		</div>
	</div>
</div>