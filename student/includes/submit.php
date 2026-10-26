<?php
$empty='';
$emptys='';
$empt='';
$error='';
$hey='';
$heys='';
$cla='';
$sec='';
$sem='';
$fac='';
$dep='';
$pro='';
$lev='';
$harr='';
$err='';
$errors='';
$non='';
ob_start();
if (isset($_POST["submitresult"])) {
	if (empty($_POST["section"])) {
		$empty="Session Required";
		header("Refresh:2;");
	}elseif (empty($_POST["programs"])) {
		$cla="Programme Required";
		header("Refresh:1;");
	}elseif (empty($_POST["semest"])) {
		$emptys="Semester Required";
		header("Refresh:2;");
	}elseif (empty($_POST["level"])) {
		$empt="Level Required";
		header("Refresh:2;");
	}else{
	$program= mysqli_real_escape_string($con,$_POST["programs"]);
	$matric= mysqli_real_escape_string($con,$_POST["studentid"]);
	$sectin= mysqli_real_escape_string($con,$_POST["section"]);
	$semet= mysqli_real_escape_string($con,$_POST["semest"]);
	$level= mysqli_real_escape_string($con,$_POST["level"]);
		
//retrieving the amount and program from schoolfee table
$sql = mysqli_query($con,"SELECT * FROM schoolfee WHERE level='$level' AND program='$program'");
 while($row = mysqli_fetch_array($sql)){
	$amount = $row['amount'];
	$sect = $row['program'];
	$_SESSION['$amount']=$amount;
	$_SESSION['$sect']=$sect;
}
$queryx = mysqli_query($con,"SELECT * FROM `studentpayment` WHERE `matricno`='$matric' AND `section`='$sectin' AND `level`='$level ' AND `program`='$program' ");
 /*
*checking the amount payed by the student
$queryx = mysqli_query($con,"SELECT * FROM `studentpayment` WHERE `studentid`='$studentid'  AND `section`='$section' AND `classe`='$classe' AND amount='20000'");

*/
//$row = mysqli_num_rows($queryx);
	//$rowx = mysqli_num_rows($queryx);
	//$row = mysqli_num_rows($queryx);
	$rowx = mysqli_num_rows($queryx);
	$data = mysqli_fetch_array($queryx);
	$mount=$data['amount'];
if ($rowx==0) {
		$heys="You have Not Payed School Fee for " .$sectin;
	//header("Refresh:2;");
}
elseif ($mount<=$amount AND $semet=='First semester') {
	$sql=mysqli_query($con,"SELECT * FROM studentresult WHERE matricno='$matric' AND section='$sectin' AND semester='$semet' AND levels='$level' AND program='$program' AND approved='1'  AND publish='1' ");
$rows = mysqli_num_rows($sql);
		if($rows > 0){
		$_SESSION['studentid'] = $matric;
		$_SESSION['section'] = $sectin;
		$_SESSION['semest'] = $semet;
		$_SESSION['level'] = $level;
		$_SESSION['programs'] = $program;
		//header("location: result/5.php");
		header("location: result/resultall.php");
	}else{
		$error= "there is no result base on the criterial!!";
		//header("Refresh:2;");
	}
		}
		elseif ($mount<$amount AND $semet=='Second semester'){
			$hey=$sectin. " School Fees Not Complete";
			//header("Refresh:2;");
		}
		elseif($mount==$amount)
			{
				$sql=mysqli_query($con,"SELECT * FROM studentresult WHERE matricno='$matric' AND section='$sectin' AND semester='$semet' AND program='$program'  AND levels='$level' AND approved='1'  AND publish='1' ");
		$rows = mysqli_num_rows($sql);
				if($rows > 0){
				$_SESSION['studentid'] = $matric;
				$_SESSION['section'] = $sectin;
				$_SESSION['semest'] = $semet;
				$_SESSION['level'] = $level;
				$_SESSION['programs'] = $program;
				//header("location: result/5.php");
				header("location: MyResult.php");
			}else{
				$error= "there is no result base on the criterial!!";
				//header("Refresh:2;");
			}
			}

			/*elseif($mount==$amount AND $semet=='Second semester')
			{
				$sql=mysqli_query($con,"SELECT * FROM studentresult WHERE matricno='$matric' AND section='$sectin' AND semester='$semet' AND levels='$level' AND approved='1'  AND publish='1' ");
		$rows = mysqli_num_rows($sql);
				if($rows > 0){
				$_SESSION['studentid'] = $matric;
				$_SESSION['section'] = $sectin;
				$_SESSION['semest'] = $semet;
				$_SESSION['level'] = $level;
				//header("location: result/5.php");
				header("location: result/resultall.php");
			}else{
				$error= "there is no result base on the criterial!!";
				header("Refresh:2;");
			}
			}*/
		//seperate line on the code

}
}

//School Fees Payment Session
if (isset($_POST["makescpay"])) {
	# code...
}

//Course Registration Payment Session
if (isset($_POST["makecopay"])) {
	//Remember to Implement to check Payment status before loading to live
}

if (isset($_POST["coursereg"])) {
	//checking all dropped down are selected
	if (empty($_POST["Section"])) {
		$sec="Session Required";
		header("Refresh:2;");
	}
	else if (empty($_POST["semest"])) {
		$sem="Semester Required";
		header("Refresh:2;");
	}
	else if (empty($_POST["faculty"])) {
		$fac="faculty Required";
		header("Refresh:2;");
	}
	else if (empty($_POST["depart"])) {
		$dep="Department Required";
		header("Refresh:2;");
	}
	else if (empty($_POST["programme"])) {
		$pro="Program Required";
		header("Refresh:2;");
	}
	else if (empty($_POST["Levelx"])) {
		$lev="Level Required";
		header("Refresh:2;");
	}
	else{
		//Inserting the course information and the retrieving Stage
	//$studentid=mysqli_real_escape_string($con,$_POST["studentid"]);
	$section= mysqli_real_escape_string($con,$_POST["Section"]);
	$semester= mysqli_real_escape_string($con,$_POST["semest"]);
	$faculty= mysqli_real_escape_string($con,$_POST["faculty"]);
	$dept= mysqli_real_escape_string($con,$_POST["depart"]);
	$prog= mysqli_real_escape_string($con,$_POST["programme"]);
	$level= mysqli_real_escape_string($con,$_POST["Levelx"]);

	//Sessioning the looped record
	//$_SESSION["studentid"]=$studentid;
	$_SESSION["section"]=$section;
	$_SESSION["semester"]=$semester;
	$_SESSION["faculty"]=$faculty;
	$_SESSION["dept"]=$dept;
	$_SESSION["prog"]=$prog;
	$_SESSION["level"]=$level;
		//Calling on the carryover function
		//Carryover($studentid, $section, $semester, $level, $prog);
	//facu($faculty);
	$sel=mysqli_query($con,"SELECT * FROM faculty WHERE facid='$faculty'");
		while ($row = mysqli_fetch_array($sel)){
			$fax=$row["facultyname"];

			
		}

		//Department
		$sac=mysqli_query($con,"SELECT * FROM deptm WHERE depid='$dept'");
			while ($rowx = mysqli_fetch_array($sac)){
			$faxx=$rowx["deptname"];


			}
	//Start HEre
			//Checking the coursetake record table
		$sql = mysqli_query($con, "SELECT * FROM coursetake WHERE semester='$semester' AND faculty='$fax' AND dept='$faxx' AND program='$prog' AND levels='$level'");
		$rows = mysqli_num_rows($sql);

		if ($rows > 0) {
		Carryover($section, $semester, $level, $prog);
	}else{
		$err = "Your H.O.D Have Not Septup Your Courses";
		//header("Refresh:2;courseregs1.php");
	}

	}
}
function Carryover($sectin,$semet,$level,$program){
		$MyData="";
      	$faculty=$_SESSION["faculty"];
     	$dept=$_SESSION["dept"];
     	//$_POST["faxx"];
     	//echo $dept;
     	$sel=mysqli_query($con,"SELECT * FROM faculty WHERE facid='$faculty'");
		while ($row = mysqli_fetch_array($sel)){
			$fax=$row["facultyname"];

			
		}

		//Department
		$sac=mysqli_query($con,"SELECT * FROM deptm WHERE depid='$dept'");
			while ($rowx = mysqli_fetch_array($sac)){
			$faxx=$rowx["deptname"];


			}
		//Ends Here
         try
         {
            $ch=
 		      "SELECT coursecode,levels, MAX(testscore+examscore) FROM studentresult WHERE   coursecode='$codet' AND levels<'$level' AND semester='$seml' AND matricno='$materd' AND program='$prom' ORDER BY section DESC";
						$car = mysqli_query($con, "SELECT * FROM passmark WHERE program='$prom'");
						$data = mysqli_fetch_array($car);
						$type = $data['passmark'];
                        $stmt = mysqli_query($con,$ch);
                        $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
 		               foreach($resulter as $rw)
 					    {

 							if ($rw['MAX(testscore+examscore)'] < $type)
 							{
 								 $MyData .= $rw['coursecode']. ",";

 							$live=$rw["levels"];
 							$ser=mysqli_query($con,"SELECT * FROM coursetake WHERE coursecode='$codet' AND dept='$faxx' ");
 					      while($datax= mysqli_fetch_array($ser)){
					$statsx=$datax['statux'];
					$cousecod=$datax["coursecode"];//retrieving the course code
          			$cousecot=$datax["coursetitle"];//retrieving the course title
          			$cousecu=$datax["unit"];
          			//$live=$datax["leves"];
					//echo $cousecu;

					$insert=mysqli_query($con,"INSERT INTO processtable(matricno,coursecode,coursetitle,unit,semester,faculty,dept,program,section,levels,status) VALUES ('$materd','$cousecod','$cousecot','$cousecu','$seml','$fax','$faxx','$prom','$secx','$live','$statsx')");
				}
			}
                        }
 					   //$_POST['MyData']=$MyData;
 					   $_SESSION['MyData']=$MyData;
         }
              catch(PDOException $e)
 	         {
 		      echo $e->getMessage();
 		     }
}
function CheckCOS($codet,$materd,$prom,$level,$seml,$secx)
 {
 	$kanter_CO = 0;
     	$HolderCO='';
     	$COS='';
     	

           $CO="SELECT *  FROM studentresult  WHERE  matricno='$Matric' AND section<'$sectin' AND levels <'$level' AND semester='$semet' AND program='$program'";
			$car = mysqli_query($con, "SELECT * FROM passmark WHERE program='$program'");
			$data = mysqli_fetch_array($car);
			$type = $data['passmark'];
           $st = mysqli_query($con,$CO);
          $resulter = mysqli_fetch_all($st,MYSQLI_ASSOC);
 		        foreach($resulter as $rowers)
 			    {
                 $CodeMe = $rowers['coursecode'];
                 $kanter_CO = $kanter_CO + 1;
                 if ($rowers['testscore'] != "" && $rowers['examscore'] !="")
                 {
                     $SumTotal[$kanter_CO]  =  $rowers['testscore'] + $rowers['examscore'];
                     $SumTotal2[$kanter_CO] =  $rowers['testscore'] + $rowers['testscore'];
                 }
                 if ($SumTotal[$kanter_CO] < $type)
                 {
                     CheckCOS($CodeMe,$Matric,$program,$level,$semet,$sectin);
                     //$facul = facu($fal);
                     //echo $facul;
                     //echo "MAT ".$_SESSION['stdID'][$c]." MyData ".$_POST['MyData']."</br>";
 					      //$COS.=$_POST['MyData'];
 					      $COS.=$_SESSION['MyData'];
 					      //echo $COS;
 					      //trying to insert Records into the Process Table
 					      

                 }
                 if (($SumTotal2[$kanter_CO] >= $type))
                 {
                     $CO_Code2[$kanter_CO] = $rowers['coursecode'];
                     // Data = "Passed";

                 }

             }

             if (@$_SESSION['MyData'] == "")
             {
                 $HolderCO .= " " . "</br>";
             }
             else
             {
 				//echo "MAT ".$_SESSION['stdID'][$c]." COS  ". $_POST['MyData'] ."</br>" ;
                 $HolderCO .= "".$COS. "</br>" ; //"RP:" . "&nbsp;&nbsp;&nbsp;" . $_POST['MyData'] . "</br>";
             }
             $_POST['HolderCO'] = $HolderCO;
             
             //echo $HolderCO;
 }
/*Sess     {ion for Course registration*/

if (isset($_POST["addcoursereg"])) {
	//for add Course registration
	header('location:addcourses.php');
}

if (isset($_POST["coursview"])) {
	//Viewing Course registration
	header("location:ApprView.php");
}

if (isset($_POST["examper"])) {
	//for add Course registration
}
if (isset($_POST["addprog"])) {
	if (empty($_POST["addcourse"])) {
		$add="Select Course";
		echo '<script type="text/javascript">alert("' . $add . '")</script>';
		header("Refresh:2;");
	}else{
		$addcourses=$_POST["addcourse"];
		$studentid=$_POST["studentid"];
		$section=$_POST["section"];
		$semester=$_POST["semester"];
		$faculty=$_POST["faculty"];
		$dept=$_POST["dept"];
		$prog=$_POST["prog"];
		$level=$_POST["level"];

		//Retrieving The Unit of the Adding Course
		/*$hay=("SELECT * FROM coursetable WHERE '");
			 		$red= mysqli_query($con,$hay);
						while($rowx = mysqli_fetch_assoc($redhg )){
							$numz=$rowx['unit'];
						}
		//ENd Here Sha
						*/
		$sql=mysqli_query($con,"SELECT * FROM coursetable WHERE coursecode='$addcourses'");
		while ($data=mysqli_fetch_assoc($sql)) {
		$coursecode=$data['coursecode'];
		$coursecod=$data['coursetitle'];
		//$coursecods=$data['coursetakes'];
		$units=$data['unit'];
		$stats=$data['status'];
		//echo '<script type="text/javascript">alert("' . $studentid . ','.$coursecode.','.$coursecod.','.$coursecods.'")</script>';
		//echo "$studentid $coursecode $coursecod $coursecods $units";

		$que= mysqli_query($con,"SELECT * FROM processtable WHERE matricno='$studentid' AND semester='$semester' AND faculty='$faculty' AND dept='$dept' AND coursecode='$coursecode' AND levels='$level' AND section='$section'");
		if(mysqli_num_rows($que)>0){
		$error= $coursecod." Already Taken By You";
		//echo '<script type="text/javascript">alert("' . $error . '")</script>';
			header("Refresh:2;");
		}else{
			//the old line 
			//$queryy=("SELECT SUM(unit) AS sumunit FROM processtable  WHERE matricno='$studentid' AND semester='$semester' AND faculty='$faculty' AND dept='$dept' AND levels='$level'");
			$queryy=("SELECT SUM(unit) AS sumunit FROM processtable  WHERE matricno='$studentid' AND semester='$semester' AND faculty='$faculty' AND dept='$dept'");
			 $resultt= mysqli_query($con,$queryy);
						while($rowc = mysqli_fetch_array($resultt)){
							$numz=$rowc['sumunit'];
							//$newnum=$numz;
							//array
				//$numx=$row['SUM(subject)'];
							//$numz1=$_SESSION["$numz"];
				//summing the units
					$har=0;
					//retrieving and summing the units before
					$har=$units+$numz;
				$maz=24;
				//if ($maz !=$numz || $maz == $numz) {
				if ($har>$maz) {
				//$exc="You have Reach the maximum Units";
				$exc="Your Units ".$har." Have Past the Required ".$maz." Units";
				header("Refresh:2;");
				//echo '<script type="text/javascript">alert("' . $exc . '")</script>';
				}else{
					$inser=mysqli_query($con,"INSERT INTO processtable (matricno,coursecode,coursetitle,unit,semester,faculty,dept,program,section,levels,status) VALUES ('$studentid','$coursecode','$coursecod','$units','$semester','$faculty','$dept','$prog','$section','$level','$stats')");
					if ($inser>0) {
							//$suc= $coursecod." Successfully ADDED";
						//echo '<script type="text/javascript">alert("' . $suc . '")</script>';
							//header("Refresh:2;");
						}else{
						$rom="Error Occured";
						echo '<script type="text/javascript">alert("' . $rom . '")</script>';
					}
				}
			}
		}
		}		
	}
}
if (isset($_POST['adddprog'])) {
	
}
?>

$que= mysqli_query($con,"SELECT * FROM studentcourseregistration WHERE coursecode='$code' AND level='$lv' AND semester='$sems' AND session='$ses' ");
		if(mysqli_num_rows($que)>0){
		$error= $code." Already Taken By You";
		//echo '<script type="text/javascript">alert("' . $error . '")</script>';
			header("Refresh:2;");
		}else{
			//Retrieving Incoming Course Unit
			$sql=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$code'"));
			$units=$sql['unit'];
			//echo $units;
			//Ends Here
		$queryy="SELECT SUM(unit) AS sumunit FROM course INNER JOIN studentcourseregistration  ON studentcourseregistration.coursecode=course.coursecode  WHERE studentcourseregistration.studentid='UAT18-0001' AND studentcourseregistration.coursecode='$code'";
			 $resultt= mysqli_query($con,$queryy);
						while($rowc = mysqli_fetch_array($resultt)){
							$numz=$rowc['sumunit'];
					//Static Figure and Variable used		
					$har=0;
					//retrieving and summing the units before
					$har=$units+$numz;
					echo $numz;
				$maz=24;
				
				if ($maz>$har) {
				
				$error="Your Units ".$har." Have Past the Required ".$maz." Units";
				header("Refresh:2;");
				//echo '<script type="text/javascript">alert("' . $exc . '")</script>';
				}else{
					$inser=mysqli_query($con,"INSERT INTO studentcourseregistration (studentid,session,semester,coursecode,level) VALUES ('UAT18-0001','$ses','$sems','$code','$lv')");
					if ($inser>0) {
							//$suc= $coursecod." Successfully ADDED";
						//echo '<script type="text/javascript">alert("' . $suc . '")</script>';
							//header("Refresh:2;");
						}else{
						$rom="Error Occured";
						echo '<script type="text/javascript">alert("' . $rom . '")</script>';
					}
				}
			}
	}


	//Disapproved Course Registration Session add/Drop
if (isset($_POST['addprogs'])) {
	if (empty($_POST['addcourses'])) {
		$error="Please Select Course";
	}else{
		$code=mysqli_escape_string($con,$_POST['addcourses']);

		$que= mysqli_query($con,"SELECT * FROM studentcourseregistration WHERE coursecode='$code' AND level='$lv' AND semester='$sems' AND session='$ses' ");
		if(mysqli_num_rows($que)>0){
		$error= $code." Already Taken By You";
		//echo '<script type="text/javascript">alert("' . $error . '")</script>';
			header("Refresh:2;");
		}else{
			//Retrieving Incoming Course Unit
			$sql=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM course WHERE coursecode='$code'"));
			$units=$sql['unit'];
			//echo $units;
			//Ends Here
		$queryy="SELECT SUM(unit) AS sumunit FROM course INNER JOIN studentcourseregistration  ON course.coursecode=studentcourseregistration.coursecode  WHERE studentcourseregistration.studentid='UAT18-0001' AND course.coursecode='$code'";
			 $resultt= mysqli_query($con,$queryy);
						while($rowc = mysqli_fetch_array($resultt)){
							$numz=$rowc['sumunit'];
					//Static Figure and Variable used		
					$har=0;
					//retrieving and summing the units before
					$har=$units+$numz;
					echo $numz;
				$maz=24;
				
				if ($maz>$har) {
				
				$error="Your Units ".$har." Have Past the Required ".$maz." Units";
				header("Refresh:2;");
				//echo '<script type="text/javascript">alert("' . $exc . '")</script>';
				}else{
					$inser=mysqli_query($con,"INSERT INTO studentcourseregistration (studentid,session,semester,coursecode,level) VALUES ('UAT18-0001','$ses','$sems','$code','$lv')");
					if ($inser>0) {
							//$suc= $coursecod." Successfully ADDED";
						//echo '<script type="text/javascript">alert("' . $suc . '")</script>';
							//header("Refresh:2;");
						}else{
						$rom="Error Occured";
						echo '<script type="text/javascript">alert("' . $rom . '")</script>';
					}
				}
			}
	}
	}
}

if (isset($_POST['courreg'])) {

	$update=mysqli_query($con,"UPDATE studentregsem SET status= 'Pending' WHERE studentid='UAT18-0001' AND semester='$sems' AND session='$ses' AND level='$lv' ");
  //$succ="Course Registration Successfully Approved";
  header("Refresh:1; Courseregs.php");
}




function Getcounit($cd)
{
$connection = TRUE;
include('../include/dbconfig.php');//Database Connection
 $sql=mysqli_fetch_assoc(mysqli_query($con,"SELECT unit FROM course WHERE coursecode='$cd'"));
$_POST['cousunit']=$sql['unit'];

//die($_POST['cousunit']);
}

function Getregunit($cds)
{
	$connection = TRUE;
include('../include/dbconfig.php');//Database Connection
	$quer=mysqli_fetch_assoc(mysqli_query($con,"SELECT SUM(unit) AS sumunit FROM studentcourseregistration INNER JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='UAT18-0001' ORDER BY studentcourseregistration.coursecode"));
	$_POST['tunits']=$quer['sumunit'];
	//die($_POST['tunits']);
}