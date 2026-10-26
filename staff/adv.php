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
               
                    $new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM requestform WHERE requestform.staffid='".$appli."'"));
                    $frst=$new['fullname'];
                    $amount=$new['amount'];
                    $purpose=$new['purpose'];

                $_SESSION['staffis']=$appli;
                ?>
                <input type="hidden" name="staffid" value="<?php echo htmlentities($appli); ?>">
                <input type="hidden" name="postc" value="<?php echo htmlentities($applp) ?>">
                <div class="modal-body">
                    <div class="form-group">
                            <label>Staff Name:</label>
                             
                             <input type="text" name="staf" value="<?php echo htmlentities($frst); ?>" class="form-control clid" required="required" disabled="disabled">
                             </div>
                <div class="form-group">
                            <label>Amount Requesting:</label>
                             
                             <input type="text" name="stafkkk" value="₦<?php echo htmlentities($amount); ?>" class="form-control clid" required="required" disabled="disabled">
                             </div>
                                    <div class="form-group">
                            <label>Purpose:</label>
                             
                             <input type="text" name="stafkkk" value="<?php echo htmlentities($purpose); ?>" class="form-control clid" required="required" disabled="disabled">
                             </div>            
                                            
                            <div class="form-group">
                            <label>Update Status:</label>
                            <select name="newprive" class="form-control" id="default" required="required">
                                
                        <option selected="selected" disabled="disabled">Please Select Status</option>
                        
                            <option value="Approved">Approve</option>
                            <option value="Rejected">Reject</option>

                         </select>
                        </div>
                </div>
                <div style="clear:both;"></div>
                <div class="modal-footer">
                    <button class="btn btn-primary" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
                    <button name="submitpr" class="btn btn-success"><span class="glyphicon glyphicon-save"></span>Submit</button>
                    
                </div>
                </div>
            </form>