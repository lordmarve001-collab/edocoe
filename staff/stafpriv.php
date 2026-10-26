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
               
                    $new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people WHERE people.staffid='".$appli."'"));
                    $frst=$new['title']." ".$new['firstname']. " ".$new['lastname'];
                    $cnew=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM staffprive WHERE staffid='".$appli."'"));
                
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
                            <label>Current Privilege:</label>
                             
                             <input type="text" name="stafkkk" value="<?php echo htmlentities($cnew['position']); ?>" class="form-control clid" required="required" disabled="disabled">
                             </div>
                                                
                                            
                            <div class="form-group">
                            <label>New Privilege:</label>
                            <select name="newprive" class="form-control" id="default" required="required">
                                
                        <option selected="selected" disabled="disabled">Please Select Privilege</option>
                        <?php
                            $res = mysqli_query($con, "SELECT * FROM privilege WHERE status='1'");
                        while ($row = mysqli_fetch_array($res)) {

                            ?>
                            <option value="<?php echo  $row["privileg"];?>"><?php echo  $row["privileg"]; ?></option>
                        <?php
                            }
                        ?>
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