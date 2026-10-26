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
                
                    $new=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM tp_school WHERE tp_school.id='".$_GET['userid']."'"));
                    $frst=$new['school'];
                ?>
				<input type="hidden" name="staffid" value="<?php echo htmlentities($_GET['userid']) ?>">
                <input type="hidden" name="postc" value="<?php echo htmlentities($_GET['userip'])?>">
				<div class="modal-body">
					<div class="form-group">
                            <label>School Name:</label>
                             
                             <input type="text" name="name" value="<?php echo htmlentities($frst); ?>" class="form-control clid" required="required">
                             </div>
                                                
                                            
                            <div class="form-group">
                            <label>School Type:</label>
                            <select name="school" class="form-control" id="default" required="required">
                                
                        <option selected="selected" disabled="disabled"><?php echo htmlentities($new['type']) ?></option>
                        <option  value="Secondary">Secondary</option>
                        <option  value="Primary" >Primary</option>
                       
                         </select>
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                             
                             <input type="text" name="address" value="<?php echo htmlentities($new['address']) ?>" class="form-control clid" required="required">
                             </div>
                             <div class="form-group">
                            <label>City:</label>
                             
                             <input type="text" name="city" value="<?php echo htmlentities($new['city']) ?>" class="form-control clid" required="required">
                             </div>
                        <div class="form-group">
                            <label>LGA:</label>
                            <select name="lga" class="form-control" id="default" required="required">
                                
                        <option selected="selected" disabled="disabled"><?php echo htmlentities($new['lga']) ?></option>
                        <option  value="Akoko-Edo">Akoko-Edo</option>
                        <option value="Egor" >Egor</option>
                        <option value="Esan Central" >Esan Central</option>
                        <option value="Esan North-East" >Esan North-East</option>
                        <option value="Esan South-East" >Esan South-East</option>
                        <option value="Esan West" >Esan West</option>
                        <option value="Etsako Central" >Etsako Central</option>
                        <option value="Etsako East" >Etsako East</option>
                        <option value="Etsako West" >Etsako West</option>
                        <option value="Igueben" >Igueben</option>
                        <option value="Ikpoba-Okha" >Ikpoba-Okha</option>
                        <option value="Oredo" >Oredo</option>
                        <option value="Orhionmwon" >Orhionmwon</option>
                        <option value="Ovia North-East" >Ovia North-East</option>
                        <option value="Ovia South-West" >Ovia South-West</option>
                        <option value="Owan East" >Owan East</option>
                        <option value="Owan West" >Owan West</option>
                        <option value="Egor" >Uhunmwonde</option>


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