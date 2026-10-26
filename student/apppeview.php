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

$mater=$sems ." ".$ses;

$_SESSION['lv'] = $lv;
$_SESSION['ses'] = $ses;
$_SESSION['sems'] = $sems;

include 'dataco.php';

$std=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentregsem WHERE semester='$sems' AND session='$ses' AND studentid='".$_SESSION['staffid']."' AND level='$lv' "));
$regda=$std['submitted'];
$app=$std['approved'];
        ?>

    <style type="text/css">
        .cotable{
    width: 700px;
}
.cotreg{
    width: 700px;
    margin: auto;
}
.cotable td, .cotable th{
    border: 1px solid black;
}
@media screen{
.lines{
  display: none;
}
}
    </style>
                               
<div class="cotreg">
<table cellpadding=0 cellspacing=0 align=left style='width:700px;' class="datas">
  
<tr>
 
  <td rowspan=2 align=left valign=top><img width=130 height=130
  src="<?php echo $logo ?>" alt="Institution Photo"></td>
 </tr>

<tr>
  
  <td style="font-weight: bolder;text-align: center;">
      <span style="font-size: 20.5px;text-align: center;width: 150px;"><?php echo $orgname ?>, <?php echo htmlentities($schadress); ?> &#13;&#10;</span>
      <br>
      <span style="font-size: 15.5px;text-align: center;width: 150px;">Student Course Registration Print-Out &#13;&#10;</span>
      <br>
      <span style="font-size: 15.5px;text-align: center;width: 150px;"> <?php echo htmlentities($mater)?> Academic Session &#13;&#10;</span>
  </td>
 </tr>

</table>
<!-- Seperate Line -->
<p class="lines" style="width: 700px;">_______________________________________________________________________________________</p>

<span><span style='position:absolute;margin-left:
530px;margin-top:3px;width:150px;height:150px'><img width=150 height=150
src="<?php echo $passport ?>" alt=" "></span>Student Name: <b><?php echo htmlentities($fullname) ?></b></span>
<br>
<span class=MsoNormal>Matric No: <b><?php echo htmlentities($_SESSION['staffid']); ?></b></span>
<br>
<span class=MsoNormal>Email: <b><?php echo htmlentities($unmail) ?></b></span>

<br><span class=MsoNormal>Faculty: <b><?php echo htmlentities($facu) ?> </b></span>
<br>
<span class=MsoNormal>Programme: <b><?php echo htmlentities($progr) ?></b></span>
<br>
<span class=MsoNormal>Level: <b><?php echo htmlentities($lv); ?></b></span>
<!-- Ends Here -->
<!-- <p class=MsoNormal>&nbsp;</p>-->
<p class=MsoNormal>&nbsp;</p> 
<table class="table table-striped table-bordered cotable" border=1 cellspacing=0 cellpadding=5>
                                        <tr>
                                            <th>#</th>
                                            <th style="width: 130px">Course Code</th>
                                            <th>Course Title</th>
                                            <th style="width: 120px;">Credit Unit</th>
                                            <th style="width:20px;">Status</th>
                                        </tr>
                                        <?php 
                                        
                                        $que= mysqli_query($con,"SELECT DISTINCT course.title,course.unit,studentcourseregistration.coursecode,studentcourseregistration.custatus FROM studentcourseregistration JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='".$_SESSION['staffid']."' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses'  ORDER BY studentcourseregistration.coursecode");
                                    
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
                                    GetUnit($_SESSION['staffid'],$lv,$sems,$ses);
                                    ?>
                                <tr><!-- <th> -->
                                    <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
                                    
                                    <td><?php echo $_POST['totalUnit']; ?></td>
                                    <td></td>
                                </tr>
                            
                                    </table>
 <p>Registration Date: <?php echo htmlentities($regda) ?></p>
 <p>Registration Status: <?php echo htmlentities($std['status']) ?></p>
 <p>Approved/Disapproved Date: <?php echo htmlentities($app) ?></p>
</div>
                                    


    
            <!-- /.content-wrapper -->
<?php
 
function GetUnit($matt,$lv,$sems,$ses)
{
$connection = TRUE;
include('../include/dbconfig.php');
        $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration  JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$matt' AND studentcourseregistration.level='$lv' AND studentcourseregistration.semester='$sems' AND studentcourseregistration.session='$ses' ORDER BY studentcourseregistration.coursecode";
          $resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
                $_POST['totalUnit']=$row['sumunit'];       
            }
}

?>  