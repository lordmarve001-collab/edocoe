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

//student($userid,$con);
$dt=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$userid'"));
if ($dt!="") {
    

        $firstnames = $dt['firstname'];
        $surnames = $dt['lastname'];
        $other = $dt['othername'];
        $passprt = $dt['image'];
        $admin=$dt['admissionid'];
        $fulnames=$surnames." ". $firstnames ." ".$other;
    }else{
       $fulnames="No Name";
       $passprt='photo/avater.png'; 
    }
?>
        

<div class="">
        
        <table>
        <tr>
                <td>
        <span>Student Name: <b style='text-transform: capitalize;'><?php echo htmlentities(ucwords($fulnames))?></b>
        </span>
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
                       
         </td>

         <td>
                <?php 
                        if ($passprt!="") {
                                
                ?>

                                <img src="../student/<?php echo $passprt ?>" style="height: 150px;width: 150px;float: right;">
                <?php   } else{
                        ?>
                         <img src="photo/avater.png" style="height: 150px;width: 150px;float: right;">
                        <?php
                }

                ?>
        </td>
        </tr>
           </table>
           <br>
           
          <table  class="table" border="1" cellspacing="0" cellpadding="5" style="margin-top:5px;">
                <tr>
                  <th>#</th>
              <th style="width: 130px">Course Code</th>
              <th>Course Title</th>
              <th style="width: 120px;">Credit Unit</th>
              <th style="width: 120px;">Status</th>
                </tr>
                <?php 
      $que = mysqli_query($con, "
    SELECT 
        scr.coursecode,
        c.title,
        ca.unit,
        scr.custatus
    FROM studentcourseregistration scr
    JOIN course c 
        ON scr.coursecode = c.coursecode
    JOIN course_allocation ca 
        ON scr.coursecode = ca.coursecode
        AND ca.programme='$pro'
    WHERE scr.studentid = '$userid'
      AND scr.level = '$lv'
      AND scr.semester = '$sems'
      AND scr.session = '$ses'
    ORDER BY scr.coursecode
");
        $sr =1;
          while ($rows= mysqli_fetch_array($que)): ?>
          <tr>
              <td><?php echo $sr;?></td>
              <td><?php echo $rows["coursecode"];?></td>
              <td><?php echo $rows["title"];?></td>
              <td><?php echo $rows["unit"];?></td>
              <td><?php echo substr($rows["custatus"], 0,1);?></td>
          </tr>
          <?php 
          $sr++;
      endwhile;
      GetUnit($userid,$lv,$sems,$ses,$pro,$con);
      ?>
          <tr>
              <td colspan='4' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
              
              <td><b style="color: red;font-weight: bolder;"><?php echo $_POST['totalUnit']; ?></b></td>
          </tr>
                </table>
<div style="clear:both;"></div>
<div class="modal-footer">
        <table>
                <tr>
                        <th>
                                <form method="POST">
                                        <input type="hidden" name="student" value="<?php echo htmlentities($userid)?>">
                                         <input type="hidden" name="progname" value="<?php echo htmlentities($pro)?>">
                                         <input type="hidden" name="leve" value="<?php echo htmlentities($lv)?>">
                                         <input type="hidden" name="ses" value="<?php echo htmlentities($ses)?>">
                                         <input type="hidden" name="sem" value="<?php echo htmlentities($sems)?>">
                                        <button name="approved" class="btn btn-success" style="float: left;"><span class="glyphicon glyphicon"></span> Approve</button></form>
                        </th>
                         <th>
              <form method="POST" action="">
                <input type="hidden" name="student" value="<?php echo htmlentities($userid)?>">
                 <input type="hidden" name="progname" value="<?php echo htmlentities($pro)?>">
                 <input type="hidden" name="leve" value="<?php echo htmlentities($lv)?>">
                <input type="hidden" name="sem" value="<?php echo htmlentities($sems)?>">
                <input type="hidden" name="ses" value="<?php echo htmlentities($ses)?>">

                                <textarea id="mess" placeholder="Specify Reason for Disapproving Please" name="messag" style="resize: none;scroll-behavior: none;width: 450px;float: left;"></textarea>
                        <button name="disapproved" class="btn btn-danger sendButton" style="float: right;margin-top: 5px;" >Disapprove</button></th>
                </form>
                </tr>
        </table>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
    $('.sendButton').attr('disabled',true);

        $('#mess').keyup(function(){
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
function GetUnit($mat,$lv,$sems,$ses,$pr,$con)
{
    $queryy = "
        SELECT SUM(ca.unit) AS sumunit
        FROM studentcourseregistration scr
        JOIN course_allocation ca 
            ON scr.coursecode = ca.coursecode
            AND ca.programme='$pr'
        WHERE scr.studentid='$mat'
        AND scr.level='$lv'
        AND scr.semester='$sems'
        AND scr.session='$ses'
    ";

    $resultt = mysqli_query($con,$queryy);
    $row = mysqli_fetch_array($resultt);

    $_POST['totalUnit']= $row['sumunit'] ?? 0;
}


/*
*Remember to implement Carryover Course View Function Here
*/   
?>