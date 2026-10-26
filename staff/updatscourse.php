<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
$applp= $_GET['userip'];
$clas= $_GET['clss'];

  //echo $appli;
}
  ?>

  <form method="POST" action="">
<div class="modal-body">
  <?php
    $_SESSION['staffds']=$appli;
    $_SESSION['claa']=$clas;
    $_SESSION['sub']=$applp;

    //echo $clas;
  ?>
        <div class="form-group">
          <label>Current Course</label>
          <input type="text" name="subject" value="<?php echo htmlentities($applp)?>" class="form-control" style="width: 100%;" required="required" disabled/>
        </div>
        <div class="form-group">
          <label>Current Class</label>
          <input type="text" name="subject" value="<?php echo htmlentities($clas)?>" class="form-control" style="width: 100%;" required="required" disabled/>
        </div>
        <div class="form-group">
          <label>New Class</label>
          <select name="semes" class="form-control" id="default" required="required">
            <option disabled='disabled' selected="selected">Please Select New Class</option>
        <?php
                                        $res = mysqli_query($con,"SELECT * FROM classe ORDER BY classes");
                                        while ($row=mysqli_fetch_array($res))
                                        {

                                        ?>
                                        <option value="<?php echo htmlentities($row["classes"]); ?>"><?php echo htmlentities($row["classes"]); ?></option>
                                        <?php

                                        }
                                         ?>
         </select>
        </div>
    </div>
    <div style="clear:both;"></div>
    <div class="modal-footer">
      <button name="updateuse" class="btn btn-warning"><span class="glyphicon glyphicon-edit"></span> Update</button>
      <button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
    </div>
    </div>
  </form>
