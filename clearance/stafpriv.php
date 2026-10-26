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
             $new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM failpayment WHERE failpayment.studentid='".$appli."' AND failpayment.paymentreference='$applp' "));
                    
                    $frst=$new['firstname']. " ".$new['lastname'];

                $_SESSION['staffis']=$appli;
                $_SESSION['staffi']=$applP;
                ?>
                <input type="hidden" name="staffid" value="<?php echo htmlentities($appli); ?>">
                <input type="hidden" name="postc" value="<?php echo htmlentities($applp) ?>">
                <div class="modal-body">
                    <div class="form-group">
                            <label>Student ID:</label>
                             
                             <input type="text" name="sid" value="<?php echo htmlentities($new['studentid']); ?>" class="form-control clid" required="required" disabled="disabled">
                             </div>
                    <div class="form-group">
                            <label>Payment Reference:</label>
                             
                             <input type="text" name="did" value="<?php echo htmlentities($new['paymentreference']); ?>" class="form-control clid" required="required" disabled="disabled">
                             </div>
                <div class="form-group">
                            <label>Payment Purpose:</label>
                             
                             <input type="text" name="stafkkk" value="<?php echo htmlentities($new['purpose']); ?>" class="form-control clid" required="required" disabled="disabled">
                             </div>
                                                
                                            
                            <div class="form-group">
                            <label>Transaction Reference:</label>
<input type="text" class="form-control" id="ref" name="ref" placeholder="MNFY|60|20250810112123|216811"  required>                        </div>
                </div>
                <div style="clear:both;"></div>
                <div class="modal-footer">
                    <button class="btn btn-primary" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-success"></span> No</button>
                    <button name="verify" class="btn btn-success"><span class="glyphicon glyphicon-save"></span>Submit</button>
                    
                </div>
                </div>
            </form>