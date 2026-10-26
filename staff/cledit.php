<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
$studid= $_POST['userid'];
$level= $_POST['lvd'];
$sess= $_POST['pur'];
$sems= $_POST['pty'];
$prog=$_POST['prg'];
					
 ?>
	<form method="post">
		<?php 
			$_SESSION['userid']=$studid;
			$_SESSION['lvd']=$level;
			$_SESSION['prg']=$prog;
			$_SESSION['pur']=$sess;
			$_SESSION['pty']=$sems;
		?>
	<div class="modal-body">
		 <div class="col-lg-6">
           <!-- <div class="col-lg-12"> -->
		<div class="form-group">
	        <label>StudentId:</label>
	         
	         <input type="text" name="student" value="<?php echo htmlentities($studid); ?>" class="form-control clid" required="required">
	    <!-- </div> -->
		</div>
		<div class="form-group">
	        <label>Level:</label>
	         
	         <input type="text"  value="<?php echo htmlentities($level); ?>" class="form-control clid" required="required" disabled="disabled">
	    </div>
	</div>
	<div class="col-lg-6">
	    <!-- <div class="col-lg-12"> -->
	    	<div class="form-group">
	        <label>Semester:</label>
	         
	         <select name="semester" class="form-control" required>	
	         		<option selected="selected" value="<?php echo htmlentities($sems)?>"><?php echo htmlentities($sems)?></option>
	         		<?php
           $res = mysqli_query($con,"SELECT * FROM semester WHERE semest!='$sems' ORDER BY semest");
            while ($row=mysqli_fetch_array($res))
            {

            ?>
            <option value="<?php echo htmlentities($row["semest"]); ?>"><?php echo htmlentities($row["semest"]); ?></option>
            <?php

            }
             ?>
	         </select>
	   <!--  </div> -->
	</div>
	   <div class="form-group">
	        <label>Session:</label>
	         <select name="sections" class="form-control" required>	
	         		<option selected="selected" value="<?php echo htmlentities($sess)?>"><?php echo htmlentities($sess)?></option>
	         		<?php
           $res = mysqli_query($con,"SELECT * FROM sections WHERE section!='$sess' ORDER BY section DESC");
            while ($row=mysqli_fetch_array($res))
            {

            ?>
            <option value="<?php echo htmlentities($row["section"]); ?>"><?php echo htmlentities($row["section"]); ?></option>
            <?php

            }
             ?>
	         </select>
	    
	    </div>
	</div>

	<div class="col-lg-6">
	    <div class="form-group">
	        <label>Programme:</label>
			<select name="programm" class="form-control" required>	
	         		<option selected="selected" value="<?php echo htmlentities($prog)?>"><?php echo htmlentities($prog)?></option>
	         		<?php
           $res = mysqli_query($con,"SELECT * FROM program WHERE pname!='$prog' ORDER BY pname");
            while ($row=mysqli_fetch_array($res))
            {

            ?>
            <option value="<?php echo htmlentities($row["pname"]); ?>"><?php echo htmlentities($row["pname"]); ?></option>
            <?php

            }
             ?>
	         </select>
	        
	    </div>
	    </div>
	    <div class="col-lg-6">
	    <div class="form-group">
          <label>Level</label>
          <select name="levelc" class="form-control" required="required">
            <option selected="selected" value="<?php echo htmlentities($level)?>"><?php echo htmlentities($level)?></option>
        <?php
           $res = mysqli_query($con,"SELECT * FROM level WHERE levels!='$level' ORDER BY levels");
            while ($row=mysqli_fetch_array($res))
            {

            ?>
            <option value="<?php echo htmlentities($row["levels"]); ?>"><?php echo htmlentities($row["levels"]); ?></option>
            <?php

            }
             ?>
         </select>
        </div>
    </div>
	</div>
	<div style="clear:both;"></div>
		<div class="modal-footer">
			<button class="btn btn-danger" style="float: left;" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-danger"></span> No</button>

			<button name="submitpr" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Click to Update</button>
				
		</div>
		
</form>