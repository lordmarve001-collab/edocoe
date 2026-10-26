<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
$sql = "SELECT * FROM student WHERE studentid='$appli'";
		$query = mysqli_query($con,$sql);
		$result = mysqli_fetch_all($query,MYSQLI_ASSOC);
			
			foreach($result as $row)
			{
		?>
			<div class="row">
					<div class="col-md-3">
						<img src="../student/<?php echo $row['image'];?>" class="img-thumbnail"  style="height: 150px;width: 160px;" />
					</div>
				<div class="col-md-9">
					<table class="table">
						<tr class="heading">
                			<td>
                    		<b>Personal Data</b>
                			</td>
                
                			<td>
                    
                			</td>
            				</tr>
							<tr>
							<th>Full-Name</th>
							<td style="text-transform:capitalize"> <?php  echo htmlentities($row['lastname'].", ".$row['firstname']." ".$row['othername'])?></td>
							</tr>
							<tr>
							<th>Student ID</th>
							<td><?php echo htmlentities($row["studentid"])?></td>
							</tr>
							<tr>
							<th>University Email</th>
							<td><a href="mailto:<?php echo $row['uniemail']?>"><?php echo htmlentities($row["uniemail"])?></a></td>
							</tr>
							<tr>
							<th>Phone</th>
							<td><a href="tel:<?php echo $row['phoneno']?>"><?php echo htmlentities($row["phoneno"])?></a></td>
							</tr>
							<tr>
							<th>Gender</th>
							<td><?php echo htmlentities($row["gender"])?></td>
							</tr>
							<tr>
							<th>Marital</th>
							<td><?php echo htmlentities($row["maritalstatus"])?></td>
						</tr>
						<tr>
							<th>D-O-B</th>
							<td><?php echo htmlentities($row["dob"])?></td>
						</tr>
						<tr>
							<th>Nationality</th>
							<td>
								<?php 
				            echo htmlentities($row['country']);
				            ?></td>
						</tr>
						<?php if($row['country']=="Foreign"){ ?>
							<tr>
							<th>Country</th>
							<td><?php echo htmlentities($row["state"])?></td>
						</tr>
				           <?php 
				            }
				            else{
				               ?>
				               <tr>
							<th>State</th>
							<td><?php echo htmlentities($row["state"])?></td>
						</tr>
						<tr>
							<th>L.G.A</th>
							<td><?php echo htmlentities($row["lga"])?></td>
						</tr>
				               <?php
				            }
				            ?>
						
						
						<tr>
							<th>Home Address</th>
							<td><?php echo htmlentities($row["address"])?></td>
						</tr>						
						<tr class="heading">
		                <td>
		                    <b>Academic Information</b>
		                </td>
		                
		                <tr>
							<th>Mode Of Entry</th>
							<td><?php echo htmlentities($row['entrysta'])?></td>
						</tr>
            			</tr>
						<tr>
							<th>Year Admitted</th>
							<td><?php echo htmlentities($row["acadsession"])?></td>
						</tr>
						<tr>
							<th>Faculty</th>
							<?php 
								$factn=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='".$row['faculty']."'"));
									$fac = $factn['fname'];
							?>
							<td><?php echo htmlentities($fac)?></td>
						</tr>
						<tr>
							<th>Departement</th>

							<td><?php 
								$depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='".$row['department']."' "));
								$dt = $depna['dept'];

							echo htmlentities($dt)?>
								
							</td>
						</tr>
						<tr>
							<th>Programme</th>
							<td><?php echo htmlentities($row['programme'])?></td>
						</tr>
						<tr class="heading">
		                <td>
		                    <b>Parent Information</b>
		                </td>
		                <td></td>
            			</tr>
						<tr>
							<th>Parent Name</th>
							<td><?php echo htmlentities($row["pname"])?></td>
						</tr>
						<tr>
							<th>Parent Email</th>
							<td><a href="mailto:<?php echo $row['pemail']?>"><?php echo htmlentities($row["pemail"])?></a></td>
						</tr>
						<tr>
							<th>Parent Phone</th>
							<td><a href="tel:<?php echo $row['pno']?>"><?php echo htmlentities($row["pno"])?></a></td>
						</tr>
					</table>
					
				</div>
			</div>
      <?php 
          $sqlys=mysqli_query($con,"SELECT DISTINCT result.acadsession,result.semester,result.level FROM result JOIN studentregsem ON result.studentid=studentregsem.studentid
         WHERE result.studentid='$appli' AND studentregsem.status='Approved'");
          $rowsys = mysqli_num_rows($sqlys);
          if($rowsys > 0){
          
          ?>
			<p align="center"><b style="text-transform:uppercase"> Student Course Information</b></p>
			<table class="table table-striped table-bordered">
						
						 <?php 
  try
   {
      
       //Get Session
       $counter=0;
         $sqlc="SELECT DISTINCT result.acadsession,result.semester,result.level FROM result JOIN studentregsem ON result.studentid=studentregsem.studentid
         WHERE result.studentid='$appli' AND studentregsem.status='Approved' ";
           
           $sts = mysqli_query($con,$sqlc);
                $counter = mysqli_fetch_all($sts,MYSQLI_ASSOC);
           $Acounter=0;
       foreach($counter as $rowa)
       {
         $Acounter+=1;
         $ACA[$Acounter]     =$rowa['acadsession'];
         $SMSTER[$Acounter]  =$rowa['semester'];
         $Lev[$Acounter]  =$rowa['level'];
       }
       //echo  " STD ".$_SESSION['programmeName'];
       $t=0;
    for($t=1;$t<=$Acounter; $t++)
     {
     $k=0;
     ?>
      
         <tr style="font-weight: bold;">
               <td width="20" colspan="2">Session: <span class="style14">   <?php echo  $ACA[$t] ?> </span></td>
               <td width="80">Level: <span class="style14">   <?php echo  $Lev[$t] ?> </span></td>
               <td width="180" colspan="2">Semester: <span class="style14">   <?php echo  $SMSTER[$t] ?> </span></td>
               
         </tr>
         <tr>
							<th>#</th>
							<th>Course Code</th>
							<th>Course Title</th>
							<th>Units</th>
							<th>Status</th>
						</tr>
     <?php
         $sql="SELECT * FROM result  JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$appli' AND result.semester='$SMSTER[$t]' AND result.level='$Lev[$t]' AND result.acadsession='$ACA[$t]'   ORDER BY result.coursecode";
             
              $_POST['totalunits']=0;
              $_POST['tupt']=0;
              $_POST['stupt']=0;
               $st = mysqli_query($con,$sql);
                $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
           foreach($count as $row)
           {
            
           	 $k+=1;
           	 $_POST['totalunits']+=$row['unit'];
       
            ?>
            <tr>
             
            <td width="50"><?php echo $k ?></td>
            <td width="50" ><?php echo $row['coursecode'] ?></td>
            <td width="200"><?php echo $row['title'] ?></td>
            <td  width="30"><?php echo $row['unit'] ?></td>             
            <td width="30"><?php echo substr($row["custatus"], 0, 1) ?></td>
           </tr>
           
          <?php
       }
       ?>
       <tr>
                
                <td colspan="4"><strong style="float: right;">Total:</strong></td>
                <td><strong><?php echo $_POST['totalunits']; ?></strong></td>
            </tr>
       <?php
   		}

     
      }
    catch(PDOException $e)
        {
          echo $e->getMessage();
        }
      }else{

      }
    ?>
					</table>
          <?php 
          $sqly=mysqli_query($con,"SELECT * FROM result WHERE studentid='$appli' AND status='Y'  AND publish='1'");
          $rowsy = mysqli_num_rows($sqly);
          if($rowsy > 0){
          
          ?>
					<p align="center" > <b style="text-transform:uppercase;">Student Result Information</b></p>
					
		<table width="754" style="text-transform: capitalize;" class="table table-striped table-bordered cotabl" cellspacing="0">
			<?php
				try {
					
			         $strp="SELECT DISTINCT acadsession,semester,level FROM result
			         WHERE studentid='$appli' AND status='Y' AND publish='1' ";
           			 $strs = mysqli_query($con,$strp);
                     $counterx = mysqli_fetch_all($strs,MYSQLI_ASSOC);
                     $Acounterx=0;
                     foreach($counterx as $rowal)
				       {
				         $Acounterx+=1;
				         $ACAs[$Acounterx]     =$rowal['acadsession'];
				         $SMSTERs[$Acounterx]  =$rowal['semester'];
				         $Levs[$Acounterx]  =$rowal['level'];
				        
				       }
				       $n=0;
				        $_POST['ptnt']=0;
				        $_POST['totalPointx']=0;
				        $_POST['totalUnitx']=0;
				        $_POST['pointsx']=0;
					    for($n=1;$n<=$Acounterx; $n++)
					    {
					     $m=0;
						GetCumulatives($con,$ACAs[$n],$SMSTERs[$n],$Levs[$n],$appli);
					?>
						<tr style="font-weight: bold;">
			               <td width="20" colspan="2">Session: <span class="style14">   <?php echo  $ACAs[$n] ?> </span></td>
			               <td width="80">Level: <span class="style14">   <?php echo  $Levs[$n] ?> </span></td>
			               <td width="80" colspan="4">Semester: <span class="style14">   <?php echo  $SMSTERs[$n] ?> </span></td>
               
         					</tr>
         
				         	<tr style="text-align:left;">
				            <th>#</th>
				            <th>Code</th>
				            <th>Course Title</th>
				            <th>Score</th>
				            <th>Grade</th>
				            <th>Course Unit</th>
				            <th>Course point</th>
				            </tr><?php
         $sqlv="SELECT * FROM result  JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$appli' AND result.semester='$SMSTERs[$n]' AND result.acadsession='$ACAs[$n]' AND result.status='Y' AND result.level='$Levs[$n]'  AND result.publish='1'   ORDER BY result.coursecode";
             
              $_POST['totalunit']=0;
              $_POST['tupt']=0;
              $_POST['stupt']=0;
              $_POST['mys']=0;
               $stv = mysqli_query($con,$sqlv);
                $countv = mysqli_fetch_all($stv,MYSQLI_ASSOC);
           foreach($countv as $rowv)
           {
            $m+=1;
              $_POST['totalunit']+=$rowv['unit'];
              /*Codes Using to Calculate De semester GPa

             */ 
              GetGradeSystem($con);
                $Scod=$rowv['ca'] + $rowv['exam'];
            for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++) {
            if ($Scod <= $_POST['Maxscore'][$i] && $Scod >= $_POST['Minscore'][$i]) {
                $_POST['mys']   =   $_POST['point'][$i];
                $_POST['tupt'] += (int)$_POST['mys']*(int)$rowv['unit'];
                $_POST['stupt'] += (int)$_POST['mys'];

                //computing d Semester GPA
                $sgpa=number_format($_POST['tupt']/$_POST['totalunit'],2);
               
            }

        }
        GetGPoint($con,$rowv['ca'] + $rowv['exam']);
            ?>
            <tr>
            <td width="50"><?php echo $m ?></td>
            <td width="50" ><?php echo $rowv['coursecode'] ?></td>
            <td width="200"><?php echo $rowv['title'] ?></td>
            <td width="45"><?php echo ($rowv['ca'] + $rowv['exam']) ?></td>
            <td width="25"><?php echo GetGrade($con,$rowv['ca'] + $rowv['exam']) ?></td>
            <td  width="30"><?php echo $rowv['unit'] ?></td>             
            <td width="55"><?php echo GetPoints($con,$rowv['ca'] + $rowv['exam']) * $rowv['unit']?></td>
          </tr>
          <?php
         	 } 
          ?>
                
        <tr>
                <td><strong>CUR: <?php echo $_POST['totalunit']; ?></strong></td>
                <td><strong>CUE: <?php echo $_POST['stupt']; ?></strong></td>
                <td><strong><span>WGP: <?php echo $_POST['tupt']; ?></span><span style="float: right;margin-right: 15px;"> GPA: <?php echo $sgpa; ?></span></strong></td>
                <td><strong>TCR: <?php echo $_POST['totalUnitx']; ?></strong></td>
                <td><strong>TCE: <?php echo $_POST['ptnt']; ?></strong></td>
                <td><strong>TWGP: <?php  echo $_POST['pointsx']; ?></strong></td>
                <td><strong>CGPA: <?php  echo  $_POST['GPS'];  ?></strong></td>
        </tr>
					<?php
						}
						} catch (Exception $e)
					{
					
					}

			?>
		</table>
			<?php
    GetGradeSystem($con);
    GetCumulative($con,$appli);
    $queryy=("SELECT SUM(unit) FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$appli' AND result.status='Y'  AND result.publish='1'");
 $resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt)){
                $numz=$row['SUM(unit)'];
                
            }

            ?>
<span style="color:red;">
	CarryOver (If Any):
<?php
$prs=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM student WHERE studentid='$appli'"));
Karryover($con,$appli,$prs['programme']);
 echo htmlentities($_POST['HolderCOs']);
?></span>
<br>
<span style="color:red;">
	Outstanding Course (If Any):
<?php

Outstand($con,$appli,$prs['programme']);
 echo htmlentities($_POST['cott']);
?></span>

<p>&nbsp;</p>
<table width="850" border="0">
  <tr>
    <td width="188"><strong>Total Unit Registered: </strong></td>
    <td width="67"><strong><?php echo  $numz;?></strong></td>
    <td width="350">&nbsp;</td>
    <td width="82">&nbsp;</td>
    <td width="70">&nbsp;</td>
    <td width="101"><div align="center"></div></td>
  </tr>
  <tr>
    <td><strong>Total Credit Point: </strong></td>
   
    <td><strong><?php echo $_POST['points']; ?></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Cumulative GPA: </strong></td>
    
    <td><strong> <?php echo $_POST['CGP']; ?></strong></td>
    <td>&nbsp;</td>
    <td colspan="3"><div align="center"></div></td>
  </tr>
</table>
		<?php
			}else{
        
      }
      } 
		?>
<?php
}


  function GetGrade($con,$scores)
  {
     GetGradeSystem($con);
                          for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++)
                            {
                                if ($scores <= $_POST['Maxscore'][$i] && $scores >= $_POST['Minscore'][$i])
                                {
                                  $_POST['myGrade']   =   $_POST['Grade'][$i];
                                                   
                                }
                            }
                        
                return   $_POST['myGrade'];
  }
 function GetPoints($con,$score)
    {

        GetGradeSystem($con);
        
        for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++) {
            if ($score <= $_POST['Maxscore'][$i] && $score >= $_POST['Minscore'][$i]) {
                $_POST['totalPointx']= $_POST['point'][$i];
            }

        }
        //echo ;
        return  $_POST['totalPointx'];
    }  

  
 function GetGradeSystem($con)
        {
     $gradeCounter=0;   	
     
     $sqlper = "SELECT  * FROM gradetable";
      $result = mysqli_query($con,$sqlper);
     $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
     //$rowks=mysqli_num_rows($result);
     foreach($data as $row){
        $gradeCounter+=1;
        $_POST['Minscore'][$gradeCounter] =  $row["minscore"];
        $_POST['Maxscore'][$gradeCounter] =  $row["maxscore"];
        $_POST['Grade'][$gradeCounter]    =  $row["grade"];
        $_POST['point'][$gradeCounter]    =  $row["point"];
       }
         $_SESSION['gradeCounter']=$gradeCounter;

 }
  function GetGradeSystems($con)
        {
     $gradeCounters=0;
    
     $sqlper = "SELECT  * FROM gradetable";
      $result = mysqli_query($con,$sqlper);
     $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
     foreach($data as $row){
        $gradeCounters+=1;
        $_POST['Minscore'][$gradeCounters] =  $row["minscore"];
        $_POST['Maxscore'][$gradeCounters] =  $row["maxscore"];
        $_POST['Grade'][$gradeCounters]    =  $row["grade"];
        $_POST['pointg'][$gradeCounters]    =  $row["point"];
       }
         $_SESSION['gradeCounters']=$gradeCounters;

 }
function GetGPoint($con,$score)
{
   
    for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++) {
        if ($score <= $_POST['Maxscore'][$i] && $score >= $_POST['Minscore'][$i]) {
            
            $_POST['ptnt']+= (int) $_POST['point'][$i];
            
        }
    }
    return   $_POST['ptnt'];
}

 function GetScores($con,$mt)
{

    $_POST['points']=0;
    $_POST['totalUnit']=0;
    $scoresCounter=0;
    $mysqler = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$mt' AND result.status='Y'  AND result.publish='1' ";
    $resultf = mysqli_query($con,$mysqler);
     $dataf = mysqli_fetch_all($resultf,MYSQLI_ASSOC);
     foreach($dataf as $row){
     $scoresCounter+=1;
     $_POST['Units'][$scoresCounter]=$row['unit'];
     $_POST['Code'][$scoresCounter]=$row['coursecode'];
     $_POST['Title'][$scoresCounter]=$row['title'];
     $_POST['sectins'][$scoresCounter]=$row['acadsession'];
     $_POST['Level'][$scoresCounter]=$row['level'];
     $_POST['semex'][$scoresCounter]=$row['semester'];
     $_POST['Scores'][$scoresCounter]=(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounter']=$scoresCounter;
  //compute GP
  GetGradeSystem($con); //Get the grade
   for($k=1; $k<=$_SESSION['scoresCounter']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scores'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scores'][$k] >= $_POST['Minscore'][$j])
                                 {
                               
                                    $_POST['G'][$k] = $_POST['Grade'][$j];
                                    $_POST['P'][$k] = $_POST['point'][$j]*$_POST['Units'][$k];
                                    $_POST['points']+=(int)$_POST['point'][$j] * (int)$_POST['Units'][$k];
                                    
                                 }
                                $_POST['totalUnit']+=$_POST['Units'][$k];
                          }
                            

}


function GetCumulative($con,$matk)
{
    //$matrics=$_SESSION['matricno'] ;
  $_POST['pointsc']=0;
$_POST['totalUnitc']=0;
    
   $scoresCounterc=0; //e
     $harr= "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matk' AND result.status='Y'  AND result.publish='1'  ";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
  foreach($resulter as $row){
     $scoresCounterc+=1;
     $_POST['Unitc'][$scoresCounterc]    =$row['unit'];
     $_POST['Codec'][$scoresCounterc]    =$row['coursecode'];
     $_POST['Titlec'][$scoresCounterc]   =$row['title'];
     $_POST['Scoresc'][$scoresCounterc]  =(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounterc']=$scoresCounterc;     
  //Compute CUMULATIVE
  GetGradeSystem($con);// Graing System
                        for($k=1; $k<=$_SESSION['scoresCounterc']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scoresc'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scoresc'][$k] >= $_POST['Minscore'][$j])
                                 {
                                   
                                    $_POST['Gc'][$k] = $_POST['Grade'][$j];
                                    $_POST['Pc'][$k] = $_POST['point'][$j];
                                    $_POST['pointsc']+=$_POST['point'][$j] * (int)$_POST['Unitc'][$k];
                                 }
                                $_POST['totalUnitc']+=(int)$_POST['Unitc'][$k];
                          }
                          
                        
                            GetScores($con,$matk);
                            $_POST['GP']=number_format(($_POST['points']/$_POST['totalUnit']),2);//Compute current semester
                            $_POST['CGP']=number_format((($_POST['pointsc'] +$_POST['points'])/($_POST['totalUnitc']+ $_POST['totalUnit'])),2);//compute cumulative
                              if($_POST['pointsc']!=0 && $_POST['totalUnitc']!=0)
                              {
                               $_POST['PGP']=number_format(($_POST['pointsc']/$_POST['totalUnitc']),2);//computer previous GP
                              } 
                              else if($_POST['pointsc']==0 && $_POST['totalUnitc']==0){
                                 $_POST['PGP']="0.00";
                              }               
                         
}   

function GetScorex($con,$semss, $lev, $sect,$matl)
{
   
    $_POST['Ptn']=0;
    $scoresCounterxh=0;
    $mysqler = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matl' AND result.acadsession='$sect' AND result.semester='$semss' AND result.level='$lev' AND result.status='Y'  AND result.publish='1'";
    $resultf = mysqli_query($con,$mysqler);
     $dataf = mysqli_fetch_all($resultf,MYSQLI_ASSOC);
     foreach($dataf as $row){
     $scoresCounterxh+=1;
     $_POST['Unitsx'][$scoresCounterxh]=$row['unit'];
     //$_POST['totalUnitx']+=$row['unit'];
     $_POST['Codex'][$scoresCounterxh]=$row['coursecode'];
     $_POST['Title'][$scoresCounterxh]=$row['title'];
     $_POST['scoresd'][$scoresCounterxh]=(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounterxh']=$scoresCounterxh;
  //compute GP
  GetGradeSystem($con); //Get the grade
   for($k=1; $k<=$_SESSION['scoresCounterxh']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['scoresd'][$k] <= $_POST['Maxscore'][$j] && $_POST['scoresd'][$k] >= $_POST['Minscore'][$j])
                                 {
                                   
                                    $_POST['Gx'][$k] = $_POST['Grade'][$j];
                                    $_POST['Px'][$k] = $_POST['point'][$j]*$_POST['Unitsx'][$k];
                                    $_POST['pointsx']+=(int)$_POST['point'][$j] * (int)$_POST['Unitsx'][$k];
                                    $_POST['Ptn']+= (int)$_POST['point'][$j];
                                    
                                    
                                 }
                                //
                                $_POST['totalUnitx']+=$_POST['Unitsx'][$k];
                                //echo $_POST['totalUnitx'];
                                
                          }
                          //Compute GP
                         //$_POST['GP']=number_format(($_POST['points']/$_POST['totalUnitx']),2);   

}


function  GetCumulatives($con,$sect,$sems,$lev,$matp)
{
   
    $_POST['pointscx']=0;
    $_POST['totalUnitcxc']=0;
    
   $scoresCountercd=0; //e
     $harr= "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matp' AND result.acadsession<'$sect' AND result.status='Y'  AND result.publish='1'";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
  foreach($resulter as $row){
     $scoresCountercd+=1;
     $_POST['Unitcd'][$scoresCountercd]    =$row['unit'];
     $_POST['Codecd'][$scoresCountercd]    =$row['coursecode'];
     $_POST['Titlecd'][$scoresCountercd]   =$row['title'];
     $_POST['Scorescd'][$scoresCountercd]  =(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCountercd']=$scoresCountercd;   

  //Compute CUMULATIVE
  GetGradeSystem($con);// Graing System
                        for($k=1; $k<=$_SESSION['scoresCountercd']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scorescd'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scorescd'][$k] >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['Gcd'][$k] = $_POST['Grade'][$j];
                                    $_POST['Pcd'][$k] = $_POST['point'][$j];
                                    $_POST['pointscx']+=$_POST['point'][$j] * (int)$_POST['Unitcd'][$k];
                                 }
                                $_POST['totalUnitcxc']+=(int)$_POST['Unitcd'][$k];
                                //echo $_POST['totalUnitcxc'];
                          }
                          
                        if($sems=="First Semester")
                         {
                            GetScorex($con,$sems, $lev, $sect,$matp);
                            $_POST['GPS']=number_format(($_POST['pointsx']/$_POST['totalUnitx']),2);//Compute current semester
                            $_POST['CGPS']=number_format((($_POST['pointscx'] +$_POST['pointsx'])/($_POST['totalUnitcxc']+ $_POST['totalUnitx'])),2);//compute cumulative
                              if($_POST['pointscx']!=0 && $_POST['totalUnitcxc']!=0)
                              {
                               $_POST['PGPS']=number_format(($_POST['pointscx']/$_POST['totalUnitcxc']),2);//computer previous GP
                              } 
                              else if($_POST['pointscx']==0 && $_POST['totalUnitcxc']==0){
                                 $_POST['PGPS']="0.00";
                              }               
                         } 
                         else if($sems=="Second Semester")
                         {
                          GetScorex($con,$sems, $lev, $sect,$matp); //get first semester computation
                          GetSecondResults($con,$sems, $lev, $sect,$matp);//get second semester computation
                          
                         $_POST['GPS']=number_format(($_POST['pointsx']/$_POST['totalUnitx']),2);//Current Semester GP
                        $_POST['CGPS']=number_format((($_POST['pointscx'] + $_POST['pointsx'] + $_POST['pointsd'])/($_POST['totalUnitcxc']+$_POST['totalUnitx']+ $_POST['totalUnitsd'])),2);//Cumulative Gpa
                         $_POST['PGPS']=number_format((($_POST['pointscx'] + $_POST['pointsd']) / ($_POST['totalUnitcxc']+$_POST['totalUnitsd'])),2);//Compute previous
                         } 
        
}                 
            
function GetSecondResults($con,$sems, $lev, $sect,$mat)
{   
    
    $_POST['pointsd']=0;
    $_POST['totalUnitsd']=0;
    $semet="First Semester";
   $scoresCountersx=0; //e
   $mysqlercs = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$mat' AND result.acadsession='$sect' AND result.semester='$sems'  AND result.status='Y' AND result.publish='1'";
   $resultfy = mysqli_query($con,$mysqlercs);
        $resulters = mysqli_fetch_all($resultfy,MYSQLI_ASSOC);
  foreach($resulters as $row){
     $scoresCountersx+=1;
     $_POST['Unitsd'][$scoresCountersx]    =$row['unit'];
     $_POST['Codesd'][$scoresCountersx]    =$row['coursecode'];
     $_POST['Titlesd'][$scoresCountersx]   =$row['title'];
     $_POST['Scoresd'][$scoresCountersx]  =(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCountersx']=$scoresCountersx;        
  //Compute CUMULATIVE
  GetGradeSystem($con);// Graing System
                        for($k=1; $k<=$_SESSION['scoresCountersx']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scoresd'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scoresd'][$k] >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['Gcd'][$k] = $_POST['Grade'][$j];
                                    $_POST['Pcd'][$k] = $_POST['point'][$j];
                                    $_POST['pointsd']+=$_POST['point'][$j] * (int)$_POST['Unitsd'][$k];
                                 }
                                $_POST['totalUnitsd']+=(int)$_POST['Unitsd'][$k];
                                //echo $_POST['pointsd'];
                          }
}   
function Karryover($con,$Matric,$program)
     {
      $kanter_CO = 0;
      $HolderCO='';
      $HolderCOs='';
      $_POST['coufc']='';
      $COS='';
      $COSt='';
      $MyDatas="";
      $MyData="";
        $_POST['MyData']='';
        $_POST['MyDatas']='';

        try
         {
            
              $ch="SELECT coursecode, MAX(ca+exam) FROM result WHERE  studentid='$Matric' AND programme='$program' AND status='Y' AND publish='1' GROUP BY coursecode";
              $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$program'"));
            //$ = ($car);
            $type = $data['passmark'];
                        $stmt = mysqli_query($con,$ch);
                        $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
                   foreach($resulter as $rw)
              {

              if ($rw['MAX(ca+exam)'] < $type)
              {
                 $MyData .=$rw['coursecode']. "";
                 $MyDatas .=$rw['coursecode']. ",";
                // echo $type;
              }
                        }
             $_POST['MyData']=$MyData;
             $_POST['MyDatas']=$MyDatas;
             $COS.=$_POST['MyData'];
             $COSt.=$_POST['MyDatas'];
         }
              catch(PDOException $e)
           {
          echo $e->getMessage();
         }

             if ($_POST['MyData'] == "")
             {
                 $HolderCO .= "";
             }
             else
             {
                 $HolderCO .="" .$COS;
             }
             $_POST['HolderCO'] = $HolderCO;

             /*Outputing Them for Carryover Course Display*/
            
             if ($_POST['MyDatas'] == "")
             {
                 $HolderCOs .= "";
             }
             else
             {
                 $HolderCOs .="" .$COSt;
             }
             $_POST['HolderCOs'] = $HolderCOs;
     }

     function CheckCOS($con,$codet,$ppl,$har)
   {
      
        $MyData="";
        //echo $ppl;
         try
         {
            $ch=
              "SELECT coursecode, MAX(ca+exam) FROM result WHERE coursecode='$codet' AND studentid='$har' AND programme='$ppl' AND status='Y' AND publish='1' ORDER BY acadsession DESC";

                $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$ppl'"));
            //$ = ($car);
            $type = $data['passmark'];
                        $stmt = mysqli_query($con,$ch);
                        $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
                       foreach($resulter as $rw)
                        {

                            if ($rw['MAX(ca+exam)'] < $type)
                            {
                                 $MyData .= $rw['coursecode']. ",";

                            }
                        }
                       //$_POST['MyData']=$MyData;
                       $_POST['MyData']=$MyData;
         }
              catch(PDOException $e)
             {
              echo $e->getMessage();
             }

     }
     //function Carryover($level,$sectin,$proj,$harr,$sem)
     function Karryover($con,$Matric,$prom)
     {
       $kanter_CO = 0;
        $HolderCOs='';
        $COS='';
        $_POST['MyData']='';
             $CO="SELECT DISTINCT coursecode,ca,exam  FROM result  WHERE  studentid='$Matric' AND  programme='$prom' AND status='Y' AND publish='1' ";
            $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$prom'"));
            //$ = ($car);
            $type = $data['passmark'];
           $st = mysqli_query($con,$CO);
          $resulter = mysqli_fetch_all($st,MYSQLI_ASSOC);
                foreach($resulter as $rowers)
                {
                 $CodeMe = $rowers['coursecode'];
                 $kanter_CO = $kanter_CO + 1;
                 if ($rowers['ca'] != "" && $rowers['exam'] !="")
                 {
                     $SumTotal[$kanter_CO]  =  $rowers['ca'] + $rowers['exam'];
                     $SumTotal2[$kanter_CO] =  $rowers['ca'] + $rowers['exam'];
                 }
                 if ($SumTotal[$kanter_CO] < $type)
                 {
                     
                     CheckCOS($con,$CodeMe,$prom,$Matric);
                     
                          $COS.=$_POST['MyData'];
                         
                 }
                 if (($SumTotal2[$kanter_CO] >= $type))
                 {
                     $CO_Code2[$kanter_CO] = $rowers['coursecode'];
                     // Data = "Passed";

                 }

             }

             if ($_POST['MyData']== "")
             {
                 $HolderCOs .= " ";
             }
             else
             {
                
                 $HolderCOs .= "".$COS ; 
             }
             $_POST['HolderCOs'] = $HolderCOs;
     }
     */
function  Outstand($con,$sdt,$program){
	$_POST['cot']='';
	$_POST['cott']='';
	
    $ques= mysqli_query($con,"SELECT DISTINCT coursecode,studentid,custatus FROM studelecour WHERE coursecode NOT IN (SELECT DISTINCT coursecode FROM result WHERE studentid='$sdt' AND programme='$program') AND studentid='$sdt'");
            $resu= mysqli_fetch_all($ques,MYSQLI_ASSOC);
            foreach($resu as $rwt)
              {
              	$_POST['cot'].=$rwt['coursecode'];
                $_POST['cott'].=$rwt['coursecode'].',';
              }   
}      
?>		