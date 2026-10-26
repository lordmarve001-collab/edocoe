<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
$subj= $_GET['userip'];
$clas= $_GET['clss'];
$cusm= $_GET['cusm'];
$sess= $_GET['sess'];
}
?>
<form method="post">
  <div class="modal-body">
     <div class="col-lg-6">
         <?php 
          $_SESSION['staffids']=$appli;
          $_SESSION['coursecode']=$subj;
          $_SESSION['acadsession']=$sess;

         ?>
    <div class="form-group">
          <label>Course Code:</label>
           
           <input type="text" value="<?php echo htmlentities($subj); ?>" class="form-control clid" required="required" disabled="disabled">
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
          <label>Semester:</label>
           
           <input type="text" value="<?php echo htmlentities($clas); ?>" class="form-control clid" required="required" disabled="disabled">
     <!--  </div> -->
  </div>
     <div class="form-group">
          <label>Current Course Master Status:</label>
           
           <input type="text"  value="<?php echo htmlentities($cusm); ?>" class="form-control clid" required="required" disabled="disabled">
      
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