<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
$subj= $_GET['userip'];

}
?>
<form method="post">
  <div class="modal-body">
     <div class="col-lg-6">
         <?php 
          $_SESSION['staffids']=$appli;
          $_SESSION['coursecode']=$subj;
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptc='$subj'"));
                                      $sems=$dat['dept'];
        $result=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people WHERE staffid='$appli'"));

         ?>
    <div class="form-group">
          <label>Staff Name:</label>
           
           <input type="text" value="<?php echo htmlentities($result['title']." ".$result['firstname']. " ".$result['lastname']." ".$result['othername']) ?>" class="form-control clid" required="required" disabled="disabled">
      <!-- </div> -->
    </div>
    <div class="form-group">
          <label>Staffid:</label>
           
           <input type="text" value="<?php echo htmlentities($appli); ?>" class="form-control clid" required="required" disabled="disabled">
      </div>
  </div>
  <div class="col-lg-6">
      <!-- <div class="col-lg-12"> -->
        <div class="form-group">
          <label>Department::</label>
           
           <input type="text" value="<?php echo htmlentities($subj); ?>" class="form-control clid" required="required" disabled="disabled">
     <!--  </div> -->
  </div>
     <div class="form-group">
          <label>Current Role:</label>
           
           <input type="text"  value="Department Officer" class="form-control clid" required="required" disabled="disabled">
      
      </div>
  </div>
      <div class="form-group">
          <label>New Course Master Status:</label>
           <select name="courscon" class="form-control" id="default" required="required">
              <option selected="selected" value="<?php echo htmlentities($cusm); ?>"><?php echo htmlentities($cusm); ?></option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
           </select>
           
      </div>
      
      
  </div>
  <div style="clear:both;"></div>
    <div class="modal-footer">
      <button class="btn btn-danger" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-danger"></span> No</button>

      <button name="submitpr" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Submit</button>
        
    </div>
    
</form>