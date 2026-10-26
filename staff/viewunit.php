<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
//$applp= $_GET['userip'];
$clas= $_GET['clss'];

  //echo $appli;
}
  ?>

  <form method="POST" action="">
<div class="modal-body">
  <?php
    $_SESSION['staffds']=$appli;
    $_SESSION['claa']=$clas;
  ?> 
  <div class="form-group">
    <label>Office Unit: <?php echo htmlentities($_SESSION['claa']) ?> </label>
  </div>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Unit Names</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM unitlist WHERE unitid='".$appli."' ";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result['unitname']) ?></td>
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }//} ?> 
      </tbody>
    </table>
        
    </div>
    <div style="clear:both;"></div>
    <div class="modal-footer">
      <button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
    </div>
    </div>
  </form>
