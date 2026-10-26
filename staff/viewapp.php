<?php
session_start();
//error_reporting(0);
$connection = TRUE;
include('../include/dbconfig.php');

$userid = $_POST['applid'];
$sems = $_POST['ssid'];
$ses=$_POST['sess'];
$pro=$_POST['prog'];
$lv=$_POST['level'];
//include'includes/getSubmit.php';
        ?>
<div>
      <div>
        <div>
        	<?php 
        		$dt=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$userid'"));
					$firstnames = $dt['firstname'];
					$surnames = $dt['lastname'];
					$passprt = $dt['image'];
					$fulnames=$firstnames.", ". $surnames;
        	?>
               									
          <!-- Modal body -->
          <div class="modal-body">

          <span style='position:absolute;margin-left:700px;margin-top:3px;width:170px;height:170px'><img width=130 height=130 src="../student/<?php echo $passprt ?>" alt=" "></span>Student Name: <b style='text-transform: uppercase;'><?php echo htmlentities($fulnames) ?></b></span>
          <br>
          <span class=MsoNormal>Matric No: <b><?php echo htmlentities($userid); ?></b></span>
          <br>
          <span class=MsoNormal>Session: <b><?php echo htmlentities($ses); ?></b></span>
          <br>
           <span class=MsoNormal>Semester: <b><?php echo htmlentities($sems); ?></b></span>
          <br>
          <span class=MsoNormal>Programme: <b><?php echo htmlentities($pro) ?> </b></span>
          <br>
          <span class=MsoNormal>Level: <b><?php echo htmlentities($lv); ?></b></span>
          <p></p>
         
          <table  class="table cotable" border=1 cellspacing=0 cellpadding=5>
		<tr>
		  <th>#</th>
	      <th style="width: 130px">Course Code</th>
	      <th>Course Title</th>
	      <th style="width: 120px;">Credit Unit</th>
        <th style="width: 120px;">Status</th>
		</tr>
		<?php 
      
      $que= mysqli_query($con,"SELECT * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$userid' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses' ORDER BY result.coursecode");
                  
      
        $sr =1;
          while ($rows= mysqli_fetch_array($que)): ?>
          <tr>
              <td><?php echo $sr;?></td>
              <td><?php echo $rows["coursecode"];?></td>
              <td><?php echo $rows["title"];?></td>
              <td><?php echo $rows["unit"];?></td>
              <td><?php echo substr($rows["custatus"], 0, 1);?></td>
          </tr>
          <?php 
          $sr++;
      endwhile;
      GetUnit($userid,$lv,$sems,$ses,$pro);
      ?>
          <tr>
              <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
              
              <td><?php echo $_POST['totalUnit']; ?></td>
              <td></td>
          </tr>
		</table>

       </div>

                                          <!-- Modal footer -->
      </div>
</div> 
<script type="text/javascript">
                                    
$(document).ready(function(){
    $('.sendButton').attr('disabled',true);

        $('#message').keyup(function(){
        if($(this).val().length !=0){
        $('.sendButton').attr('disabled', false);
        }
        else
        {
        $('.sendButton').attr('disabled', true);        
        }
    });
});
</script>
<?php
function GetUnit($mat,$lv,$sems,$ses,$pr)
{
$connection = TRUE;
include('../include/dbconfig.php');
    $queryy="SELECT SUM(unit) AS sumunit FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$mat' AND result.level='$lv' AND result.semester='$sems' AND result.acadsession='$ses' ORDER BY result.coursecode";
$resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
              $_POST['totalUnit']=$row['sumunit'];
                
            }
}
/*
*Remember to implement Carryover Course View Function Here
*/
?>