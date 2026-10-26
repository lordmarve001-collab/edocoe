<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';
 $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT description,session,deadline FROM resultsession WHERE description='Jupeb'"));
$desc=$dat['description'];
if (isset($_POST['userid'])) {
$appli= $_POST['userid'];
$_SESSION['appli']=$appli;


  

//$appli= $_POST['userid'];
		$sql = "
		SELECT * FROM applicant JOIN  studdata ON studdata.admno=applicant.applid WHERE studdata.admno='$appli'";
		$query = mysqli_query($con,$sql);
		$result = mysqli_fetch_all($query,MYSQLI_ASSOC);
			
			foreach($result as $row)
			{
			    $harr = $_POST['userid'];
  $sectin=$row["acadsession"];
  $semet='Second Semester';
  $level='100';
  
   $_POST["sectin"]=$row["acadsession"];
  $_POST["semet"]='Second Semester';
  $_POST["level"]='100';
   $progr='PDE. Professional Diploma In Education';
				?>
				<div class="row">
				<div class="col-md-3">
					<img src="../pde/<?php echo $row['passport'];?>" class="img-thumbnail" style="width: 300px;height:200px;" />
				</div>
				<div class="col-md-9">
					<table class="table">
						<tr class="heading">
                <td>
                    Graduating Student Data Review
                </td>
                
                <td>
                    
                </td>
            </tr>
						<tr>
							<th>Full-Name</th>
							<td style="text-transform:capitalize;font-weight: bolder;"> <?php  echo htmlentities($row['firstname']." ".$row['surname']." ".$row['lastname'])?></td>
						</tr>
						<tr>
							<th>Application ID</th>
							<td><?php echo htmlentities($row["applid"])?></td>
						</tr>
						<tr>
							<th>Email</th>
							<td><a href="mailto:<?php echo $row['email']?>"><?php echo htmlentities($row["email"])?></a></td>
						</tr>
						<tr>
							<th>Phone</th>
							<td><a href="tel:<?php echo $row['phone']?>"><?php echo htmlentities($row["phone"])?></a></td>
							</tr>
						<tr>
							<th>Gender</th>
							<td><?php echo htmlentities($row["gender"])?></td>
						</tr>
						
						<tr>
							<th>D-O-B</th>
							<td><?php echo htmlentities($row["dob"])?></td>
						</tr>
						<tr>
							<th>Nationality</th>
							<td>
								<?php 
				            echo htmlentities($row['nationality']);
				            ?></td>
						</tr>
						<?php if($row['nationality']=="Foreign"){ ?>
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
                    Admission Information
                </td>
                
                <td>
                    
                </td>
            </tr>
						<tr>
							<th>Academic Session</th>
							<td><?php echo htmlentities($row["acadsession"])?></td>
						</tr>
						<tr>
							<th>Programme</th>
							<td><?php 
				               echo htmlentities($row['programme']); 
				            
				            ?></td>
						</tr>
						<tr>
							<th>Admission Type</th>
							<td><?php 
				               echo htmlentities($row['apptype']); 
				            
				            ?></td>
						</tr>
						
						
					</table>
				
	<?php
		 
		?>
		<table class="table">
			<tr class="heading">
                <td>
                    Secondary/ College School Information
                </td>
                
                <td>
                    
                </td>
            </tr>
            <tr>
            	<td>
            		<table>
            			<tr>
            				<th>First Sitting</th>
            			</tr>
            			<?php 
			/*Loading Secondary School Record*/
			$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE  applid='$appli' AND noofsit='1'"));
			if ($dat!="") {
				
			
		?>
            		<tr>
			<th>School Name</th>
			<td><?php echo htmlentities($dat['school'])?></td>
			</tr>
			<tr>
				<th>Exam Body</th>
				<td><?php echo htmlentities($dat['exambody'])?></td>
			</tr>
			<tr>
				<th>Exam No</th>
				<td><?php echo htmlentities($dat['examno'])?></td>
			</tr>
			<tr>
				<th>Exam Year</th>
				<td><?php echo htmlentities($dat['examyear'])?></td>
			</tr>
			<tr>
				<th>From</th><th>To</th></tr>
				<tr><td><?php echo htmlentities($dat['yearadmit'])?></td>
				
				<td><?php echo htmlentities($dat['yeargrad'])?></td>
			</tr>

			<tr>
				<!-- <th>#</th> -->
				<th>Subject</th>
				<th>Grade</th>
			</tr>
			<?php 
				$sql = "SELECT * FROM olevelresultdetail WHERE applid='$appli' AND examno='".$dat['examno']."' ORDER BY subject";

				$query=mysqli_query($con,$sql);
				$results=mysqli_fetch_all($query,MYSQLI_ASSOC);
				$cnt=1;
				foreach($results as $result)
				{   ?>
			<tr>
				<!-- <td><?php echo htmlentities($cnt);?></td> -->
                <td><?php echo htmlentities($result['subject']);?></td>
            	<td><?php echo htmlentities($result['grade']);?></td>
			</tr>
			<?php 
			$cnt=$cnt+1;
			}} ?>
            		</table>
            	</td>
            	<td>
            		<table>
            			<tr>
            				<th>Second Sitting</th>
            			</tr>
            			<?php 
			/*Loading Secondary School Record*/
			$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='$appli' AND noofsit='2'"));
			if ($dat!="") {
				
			
		?>
            		<tr>
			<th>School Name</th>
			<td><?php echo htmlentities($dat['school'])?></td>
			</tr>
			<tr>
				<th>Exam Body</th>
				<td><?php echo htmlentities($dat['exambody'])?></td>
			</tr>
			<tr>
				<th>Exam No</th>
				<td><?php echo htmlentities($dat['examno'])?></td>
			</tr>
			<tr>
				<th>Exam Year</th>
				<td><?php echo htmlentities($dat['examyear'])?></td>
			</tr>
			<tr>
				<th>From</th><th>To</th></tr>
				<tr><td><?php echo htmlentities($dat['yearadmit'])?></td>
				
				<td><?php echo htmlentities($dat['yeargrad'])?></td>
			</tr>

			<tr>
				<!-- <th>#</th> -->
				<th>Subject</th>
				<th>Grade</th>
			</tr>
			<?php 
				$sql = "SELECT * FROM olevelresultdetail WHERE applid='$appli' AND examno='".$dat['examno']."' ORDER BY subject";

				$query=mysqli_query($con,$sql);
				$results=mysqli_fetch_all($query,MYSQLI_ASSOC);
				$cnt=1;
				foreach($results as $result)
				{   ?>
			<tr>
				<!-- <td><?php echo htmlentities($cnt);?></td> -->
                <td><?php echo htmlentities($result['subject']);?></td>
            	<td><?php echo htmlentities($result['grade']);?></td>
			</tr>
			<?php 
			$cnt=$cnt+1;
			}} ?>
            		</table>
            	</td>
            </tr>
        </table>
		<table class="table">
			<!-- jupparentdetail -->
			<tr class="heading"><?php
            $dap=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM transappli WHERE applid='$appli'"));
            if ($dap!='') {
            	
            
            ?>
                <td>
                    Previous Institution Information
                </td>
                <td></td>
            </tr>
            
            <tr>
				<th>Institution Name</th>
				<td><?php echo htmlentities($dap["title"].". ".$dap['instname']) ?></td>
			</tr>
			<tr>
				<th>Course of study</th>
				<td><?php echo htmlentities($dap["ccourse"])?></td>
			</tr>
			<tr>
				<th>Faculty</th>
				<td><?php echo htmlentities($dap["faculty"])?></td>
			</tr>
			<tr>
				<th>Matriculation No</th>
				<td><?php echo htmlentities($dap["matricno"])?></td>
			</tr>
				<tr>
				<th>Year Graduated</th>
				<td><?php echo htmlentities($dap["level"])?></td>
			</tr>	<tr>
				<th>Certificate</th>
				<td><?php echo htmlentities($dap["qualifi"])?></td>
			</tr>
			
			
		</table>
<?php }else echo "No Previous Institution Information uploaded"
                                 ?> 		
Student Payment History
                        

                           
                            <table class="table table-bordered" style="width:500px;font-size: 11px;">
                <thead>
                <tr>
                <th>#</th>
                <th>Session</th>
                <th>Amount Paid</th>
                <th>Payment Type</th>
                <th>Purpose</th>
                <th>Reference</th>
                <th>Status</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $lf=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studdata WHERE admno='$appli'"));
                    $maxt=$lf['admno'];
             $sql="SELECT * FROM studentpayment WHERE studentid='$appli' ORDER BY purpose "; 
            //$sql = "SELECT * FROM studentpayment WHERE studentid='$stuid' ORDER BY level ";
                                               
                                                $query = mysqli_query($con,$sql);
                                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                                $cnt=1;
                                               
                                                foreach($results as $result)
                                                {   ?>
                                                <tr>
                                                 <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result['section']);?></td>
                                                    <td>&#8358;<?php echo htmlentities(number_format($result['amount'],2));?></td>
                                                    <?php
                                                        if ($result['semester']!="") {
                                                            ?>
                                                         <td><?php echo htmlentities($result['semester']);?>
                                                             
                                                         </td>   
                                                      <?php  }else{
                                                        
                                                        ?>
                                                       <td><?php echo 'Full Payment';?></td>
                                                        <?php
                                                      }
                                                     ?>
                                                    <td><?php echo htmlentities($result['purpose']);?></td>
                                                    <td><?php echo htmlentities($result['paymentreference']);?></td>
                                                    <td><?php echo htmlentities($result['status']);?></td>
                                                    
                                                </tr>
                                                <?php 

                                                $cnt=$cnt+1;
                                                 
                                                }
    $queryy="SELECT SUM(amount) AS sumunit FROM studentpayment WHERE studentid='$appli' ";
$resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
              $_POST['total']=$row['sumunit'];
                
            }

        ?>
                                                       
            <tr>
                <td colspan="2" style="text-align: right;text-transform: uppercase;font-weight: bolder;"> Total: </td>
                <td colspan="2" style="font-weight: bolder;text-align: left;">&#8358; <?php echo htmlentities(number_format($_POST['total'],2))?></td>
                <td></td>
                <td></td>
                <td></td>
               <td></td> 
                
            </tr>
        </tbody>
        
                                        <h3>STUDENT SEMESTER RESULT DETAILS</h3>

                                    <table class="table" border=1 cellspacing=0 cellpadding=0>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Title</th>
                                        <th>Unit</th>
                                        <th>Grade</th>
                                        <th>Point</th>
                                    </tr>
            <?php
            $rec = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='".$_SESSION['appli']."' AND result.acadsession='$sectin' AND result.semester='$semet' AND result.level='$level' AND result.programme='$progr' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
                $res = mysqli_query($con,$rec);
                 $datax = mysqli_fetch_all($res,MYSQLI_ASSOC);
                 if($datax>0){
                    
                        // Get Grade 
                         GetGradeSystem($con);
                        //GetScores($_POST['semester'],$_POST['level'],$_POST['mysession']);                    
                         // Get the carryovers
                         Carryover($con,$_POST['level'],$_POST['sectin'],$progr,$_SESSION['appli'],$semet);
                         //Compute Cumulative
                         GetCumulative($con,$_POST['semet'],$_POST['level'],$_POST['sectin'],$progr,$_SESSION['appli']);
           
                                  for($k=1;$k<=$_SESSION['scoresCounter']; $k++)://{
                                 
                              ?>

            <tr>
                
                                  <td><?php echo htmlentities($k)  ?></td>
                                  <td><?php echo htmlentities($_POST['Code'][$k]) ?></td>
                                  <td><?php echo htmlentities($_POST['Title'][$k]) ?></td>
                                  <td><?php echo htmlentities($_POST['Units'][$k]) ?></td>
                                  <td><?php echo htmlentities($_POST['G'][$k]) ?></td>
                                  <td><?php echo htmlentities($_POST['P'][$k]) ?></td>
                                  
                                
                                <?php
                                //}
                            endfor;
                                ?>
                            </tr>
                                <?php
                            }
                            ?>
                            
                                <tr>
                                    <td colspan='3'>  Total  </td>
                                    
                                   
                                    <td> <?php echo htmlentities($_POST['totalUnit']); ?> </td>
                                    <td> </td>
                                    
                                    <td> <?php echo htmlentities($_POST['points']); ?> </td>
                                </tr>
                           
                                    </table>

                                    <div style="text-transform: capitalize; font-size: 13px; margin-top: 3%;">
                                        <aside>
                                            <span><strong>Previous CGPA:
                                                <?php echo htmlentities($_POST['PGP']); ?></strong></span><br>
                                            <span><strong>Semester GPA:
                                                 <?php echo htmlentities($_POST['GP']); ?>
                                            </strong></span><br>
                                            
                                            <span><strong>Cummulative GPA:
                                                <?php echo htmlentities($_POST['CGP']); ?>
                                            </strong></span><br>
                                           
                                            <span style="color: green"><strong>Classification : 
                                                    <?php 
                                                        gradeclass($con,$_POST['CGP']);
                                                    echo htmlentities($_POST['myGracl']); ?>
                                            </strong></span>
                                            <br>
                                            <span style="color: red"><strong>Carry Overs (If Any): 
                                                    <?php echo htmlentities($_POST['HolderCO']); ?>
                                            </strong></span>

                                            
                                        </aside>
                                    </div>

                                </div>
                               
                            </div>
                        </div>
                    </div>             <div class="button"><button class="btn btn-info" onclick="window.open('ecertificate','_blank')"><i class="fa fa-print"></i> Print Certificate</button></div>                   

                            </table>
                        

</div>
			</div>
<?php
	}
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

function GetScores($con,$semc,$les,$sesc,$prob,$hars)
{
    $scoresCounter=0;
    $_POST['points']=0;
    $_POST['totalUnit']=0;
     
    $mysqler = "SELECT  * FROM result INNER JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$hars' AND result.acadsession='$sesc' AND result.semester='$semc' AND result.level='$les' AND result.programme='$prob' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
    $resultf = mysqli_query($con,$mysqler);
     $dataf = mysqli_fetch_all($resultf,MYSQLI_ASSOC);
     foreach($dataf as $row){
     $scoresCounter+=1;
     $_POST['Units'][$scoresCounter]=$row['unit'];
     $_POST['Code'][$scoresCounter]=$row['coursecode'];
     $_POST['Title'][$scoresCounter]=$row['title'];
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
//Carry over Section


     /*//function Carryover($level,$sectin,$proj,$harr,$sem)
     function Carryover($con,$level,$sectin,$proj,$harr,$sem)
     {
        
         $MyData="";
        $HolderCO='';
       $_POST['coufc']='';
        $COS='';
        $_POST['MyData']='';
        try {
            
          $CO="SELECT *,coursecode, MAX(ca+exam) FROM result WHERE  acadsession<='$sectin' AND level<='$level' AND studentid='$harr' AND programme='$proj' AND publish='1' AND status='Y' ";
            $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$proj'"));
            if ($data!='') {
            $type = $data['passmark'];  
            }
          $stmt = mysqli_query($con,$CO);
            $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
                   foreach($resulter as $rw)
              {

              if ($rw['MAX(ca+exam)'] < $type)
              {  
                //echo $rw['coursecode'];
                 $MyData .=$rw['coursecode']. ",";
                  
              }
                        }
             $_POST['MyData']=$MyData;
             
             $COS.=$_POST['MyData'];
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
                 $HolderCO .="".$COS;
             }
             $_POST['HolderCO'] = $HolderCO;
     }*/
function CheckCOS($con,$codet,$level,$ppl,$har)
   {
      
        $MyData="";
        //echo $ppl;
         try
         {
            $ch=
              "SELECT coursecode, MAX(ca+exam) FROM result WHERE coursecode='$codet' AND level<='$level' AND studentid='$har' AND programme='$ppl' AND publish='1' AND status='Y' ORDER BY acadsession DESC";

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
     function Carryover($con,$level,$sectin,$proj,$harr,$sem)
     {
       $kanter_CO = 0;
        $HolderCO='';
        $COS='';
        $_POST['MyData']='';
             $CO="SELECT DISTINCT coursecode,ca,exam  FROM result  WHERE  studentid='$harr' AND acadsession<='$sectin' AND level <='$level' AND programme='$proj' AND publish='1' AND status='Y'";
            $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$proj'"));
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
                     
                     CheckCOS($con,$CodeMe,$level,$proj,$harr);
                     
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
                 $HolderCO .= " ";
             }
             else
             {
                
                 $HolderCO .= "".$COS ; 
             }
             $_POST['HolderCO'] = $HolderCO;
     }

function GetCumulative($con,$semet,$levg,$sech,$proh,$hars)
{
    $_POST['pointsc']=0;
    $_POST['totalUnitc']=0;
     $scoresCounterc=0;
   
     $harr= "SELECT  * FROM result INNER JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$hars' AND result.acadsession<'$sech' AND result.programme='$proh' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
  foreach($resulter as $row){
     $scoresCounterc+=1;
     $_POST['Unitc'][$scoresCounterc]=$row['unit'];
     $_POST['Codec'][$scoresCounterc]=$row['coursecode'];
     $_POST['Scoresc'][$scoresCounterc]=(int)$row['exam'] + (int)$row['ca']; 
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
                                //echo $_POST['totalUnitc'];
                          }
                          
                        if($semet=="First Semester")
                         {
                            GetScores($con,$semet,$levg,$sech,$proh,$hars);
                            $_POST['GP']=number_format(($_POST['points']/$_POST['totalUnit']),2);//Compute current semester
                            $_POST['CGP']=number_format((($_POST['pointsc'] + $_POST['points'])/($_POST['totalUnitc']+ $_POST['totalUnit'])),2);//compute cumulative
                              if($_POST['pointsc']!=0 && $_POST['totalUnitc']!=0)
                              {
                               $_POST['PGP']=number_format(($_POST['pointsc']/$_POST['totalUnitc']),2);//computer previous GP
                              } 
                              else if($_POST['pointsc']==0 && $_POST['totalUnitc']==0){
                                 $_POST['PGP']="0.00";
                              }               
                         } 
                         else if($semet=="Second Semester")
                         {
                          GetScores($con,$semet,$levg,$sech,$proh,$hars); //get first semester computation
                          GetSecondResult($con,$semet,$sech,$proh,$hars);//get second semester computation
                          /*
                            $_POST['totalUnitsd']
                            $_POST['pointsd']
                          */
                          if ($_POST['pointsd']!=0 && $_POST['totalUnitsd']!=0) {
                            $_POST['GP']=number_format(($_POST['points']/$_POST['totalUnit']),2);//Current Semester GP
                         $_POST['CGP']=number_format((($_POST['pointsc'] +$_POST['points']+$_POST['pointsd'])/($_POST['totalUnitc']+$_POST['totalUnit']+$_POST['totalUnitsd'])),2);//Cumulative Gpa
                         $_POST['PGP']=number_format((($_POST['pointsc'] + $_POST['pointsd'])/($_POST['totalUnitc']+$_POST['totalUnitsd'])),2);//Compute previous
                             
                          }elseif ($_POST['pointsd']==0 && $_POST['totalUnitsd']==0) {
                            $_POST['PGP']="0.00";
                            $_POST['GP']=number_format(($_POST['points']/$_POST['totalUnit']),2);//Current Semester GP
                         $_POST['CGP']=number_format((($_POST['pointsc'] +$_POST['points']+$_POST['pointsd'])/($_POST['totalUnitc']+$_POST['totalUnit']+$_POST['totalUnitsd'])),2);//Cumulative Gpa
                          }
                         } 
    
    }                     
            
function GetSecondResult($con,$smt,$sef,$prt,$hars)
{   
    
    $scoresCounters=0;
    $_POST['pointsd']=0;
    $_POST['totalUnitsd']=0;

    $semet="First Semester";
     
   $mysqlercs = "SELECT  * FROM result INNER JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$hars' AND result.acadsession='$sef' AND result.semester='$semet' AND result.programme='$prt' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
   $resultfy = mysqli_query($con,$mysqlercs);
        $resulters = mysqli_fetch_all($resultfy,MYSQLI_ASSOC);
  foreach($resulters as $row){
     $scoresCounters+=1;
     $_POST['Unitsd'][$scoresCounters]=$row['unit'];
     $_POST['Codesd'][$scoresCounters]=$row['coursecode'];
     $_POST['Titlesd'][$scoresCounters]=$row['title'];
     $_POST['Scoresd'][$scoresCounters]=(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounters']=$scoresCounters;      
  //Compute CUMULATIVE
  GetGradeSystem($con);// Graing System
                        for($k=1; $k<=$_SESSION['scoresCounters']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scoresd'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scoresd'][$k] >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['Gcd'][$k] = $_POST['Grade'][$j];
                                    $_POST['Pcd'][$k] = $_POST['point'][$j];
                                    $_POST['pointsd']+=$_POST['point'][$j] * (int)$_POST['Unitsd'][$k];
                                 }
                                $_POST['totalUnitsd']+=(int)$_POST['Unitsd'][$k];
                               
                          }
}                                                  
function gradeclass($con,$CGP){
  /* 
  *Computing the graduating class of the Student From Graduating class table
  */
  $gradeCounterx=0;
  $sqlperx = "SELECT  * FROM gradclass ORDER BY id";
  $resultx = mysqli_query($con, $sqlperx);
  $datax = mysqli_fetch_all($resultx, MYSQLI_ASSOC);
  //$rowks=mysqli_num_rows($result);
  foreach ($datax as $row) {
    $gradeCounterx += 1;
    $_POST['Minscorep'][$gradeCounterx] =  $row["minscorep"];
    $_POST['Maxscorep'][$gradeCounterx] =  $row["maxscorep"];
    $_POST['Graclas'][$gradeCounterx]    =  $row["gracla"];
  }
  $_SESSION['gradeCounterx'] = $gradeCounterx;

  for ($i = 1; $i <= $_SESSION['gradeCounterx']; $i++) {
    //GetCumulative();
    if ($CGP <= $_POST['Maxscorep'][$i] && $CGP >= $_POST['Minscorep'][$i]) {
      
      $_POST['myGracl']   =   $_POST['Graclas'][$i];
      $_POST['myGraclc']   =   $_POST['Graclas'][$i];
      $_SESSION['myGracl']   =   $_POST['Graclas'][$i];

    }
  }
  
}
    ?>