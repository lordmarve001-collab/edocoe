<?php
$mess='';
$error='';
$errors='';
$errorxs='';
$errorss='';
$succ='';
$da='';
$mon='';
$yr='';
$gen='';
$cla='';
$mar='';
$st='';
$sta='';
$lg='';
$cou='';
$err='';
if (isset($_POST["submitpd"])) {
	if (empty(mysqli_escape_string($con,$_POST["day"]))) {
		$da="Day Required";
		echo '<script type="text/javascript">alert("'.$da.'");</script>';
		//header("Refresh:2;");
	}elseif (empty(mysqli_escape_string($con,$_POST["month"]))){
		$mon="month Required";
		echo '<script type="text/javascript">alert("'.$mon.'");</script>';
	}elseif (empty(mysqli_escape_string($con,$_POST["year"]))) {
		$yr="Year Required";
		echo '<script type="text/javascript">alert("'.$yr.'");</script>';
	}elseif (empty(mysqli_escape_string($con,$_POST["sex"]))) {
		$gen="Gender Required";
		echo '<script type="text/javascript">alert("'.$gen.'");</script>';
	}
	elseif (empty(mysqli_escape_string($con,$_POST["marital"]))) {
		$mar="Marital Required";
		echo '<script type="text/javascript">alert("'.$mar.'");</script>';
	}elseif (empty(mysqli_escape_string($con,$_POST["country"]))) {
		# code...$country=$_POST['country'];
		$cou="Select Nationality";
		echo '<script type="text/javascript">alert("'.$cou.'");</script>';
	}
	else{
		//POSTING THE DATA INTO
		$firstname=mysqli_escape_string($con,$_POST["firstname"]);
		$surname=mysqli_escape_string($con,$_POST["surname"]);
		$lastname=mysqli_escape_string($con,$_POST["lastname"]);
		$email=mysqli_escape_string($con,strtolower($_POST["email"]));
		$phone=mysqli_escape_string($con,$_POST["phone"]);
		$day = mysqli_escape_string($con,$_POST["day"]);
		$year = mysqli_escape_string($con,$_POST["year"]);
		$month =mysqli_escape_string($con, $_POST["month"]);
		$dob = $day."-".$month."-".$year;
		$staffid=mysqli_escape_string($con,$_POST["staffid"]);
		$address=mysqli_escape_string($con,$_POST["address"]);
		$caddress=mysqli_escape_string($con,$_POST["caddress"]);
		$town=mysqli_escape_string($con,$_POST["town"]);
		$sex=mysqli_escape_string($con,$_POST["sex"]);
		//$position=mysqli_escape_string($con,$_POST["position"];
		$marital=mysqli_escape_string($con,$_POST["marital"]);
		$country=mysqli_escape_string($con,$_POST["country"]);
		$filename = $_FILES['passport']['name'];
		$filetmp =$_FILES['passport']['tmp_name'];
		$filesize = $_FILES['passport']['size'];
		$fileBase = basename($_FILES['passport']['name']);

		$dir = "photo/";
		$final_dir= $dir . $fileBase;
			if($country=="Nigeria") 
			{
			if (empty(mysqli_escape_string($con,$_POST["state"]))) {
			$st="Please select state";
			echo '<script type="text/javascript">alert("'.$st.'");</script>';
			//header("Refresh:1;");
			}elseif (empty(mysqli_escape_string($con,$_POST["lga"]))) {
				$lg="Please select lga";
				//header("Refresh:1;");
				echo '<script type="text/javascript">alert("'.$lg.'");</script>';
			}
			if (($filesize > 500000)) {
				$mess="file too large, filesize should be 100kb";
				//header("Refresh:2;");
				echo '<script type="text/javascript">alert("'.$mess.'");</script>';
				return false;
			}
			else{
				/*Script checking the numbers of Registration on the database*/
			$sq=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$staffid'");
			if (mysqli_num_rows($sq)==1) {
			$errorxs="You have Register";
			echo '<script type="text/javascript">alert("'.$errorxs.'");</script>';
				}
			/*Ends here*/
				else
				{
					//avoiding of duplicate of data of student in the table
					$sql="SELECT * FROM personaldata WHERE (email='$email' OR phone='$phone');";
				    $res=mysqli_query($con,$sql);
				    if (mysqli_num_rows($res) > 0) {
				    	$row = mysqli_fetch_assoc($res);
				    	if ($email==$row['email']) {
				    		
				    		$error="Email already exist";
				    		echo '<script type="text/javascript">alert("'.$error.'");</script>';
				    	}
				    	elseif ($phone==$row['phone']) {
				    		
				    		$errors="phone number already exist";
				    		echo '<script type="text/javascript">alert("'.$errors.'");</script>';
				    	}
				    }
				    else
					    {
				$state=mysqli_escape_string($con,$_POST["state"]);
				$lga=mysqli_escape_string($con,$_POST["lga"]);
				//Selecting State Record when inserting and convert it from id to Name before insertinto table
				$staname=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM state WHERE state_ID='$state' "));
				$sate = $staname['name'];
				// echo $sate;
				if(move_uploaded_file($filetmp, $final_dir))
				{
					$regist=mysqli_query($con,"INSERT INTO personaldata(firstname,surname,lastname,applid,email,phone,dob,address,caddress,gender,marital,nationality,state,lga,town,passport,status) VALUES ('$firstname','$surname','$lastname','$staffid','$email','$phone','$dob','$address','$caddress','$sex','$marital','$country','$sate','$lga','$town','$final_dir','active')");
				if ($regist) {
											//mysqli_commit($con);
											//mysqli_close($con);
											header("location: appsummary");
											//echo 1;
										} else {
											//mysqli_rollback($con);
											//mysqli_close($con);
											echo "Sorry Error Occur When Submitting";
										}
				}
			}
		}
	}
			}
			elseif ($country=="Foreign")
			{
			if(empty(mysqli_escape_string($con,$_POST['states']))) {
				$sta="Please select Country";
				echo '<script type="text/javascript">alert("'.$sta.'");</script>';
				}
				if (($filesize > 5000000)) {
				$mess="file too large, filesize should be 100kb";
				//header("Refresh:2;");
				echo '<script type="text/javascript">alert("'.$mess.'");</script>';
				return false;
			}
			else{
				/*Script checking the numbers of Registration on the database*/
			$sq=mysqli_query($con,"SELECT * FROM personaldata WHERE applid='$staffid'");
			if (mysqli_num_rows($sq)==1) {
			$errorxs="You have Register";
			echo '<script type="text/javascript">alert("'.$errorxs.'");</script>';
				}
			/*Ends here*/
				else
				{
					//avoiding of duplicate of data of student in the table
					$sql="SELECT * FROM personaldata WHERE (email='$email' OR phone='$phone');";
				    $res=mysqli_query($con,$sql);
				    if (mysqli_num_rows($res) > 0) {
				    	$row = mysqli_fetch_assoc($res);
				    	if ($email==$row['email']) {
				    		
				    		$error="Email already exist";
				    		echo '<script type="text/javascript">alert("'.$error.'");</script>';
				    	}
				    	elseif ($phone==$row['phone']) {
				    		# code...
				    		$errors="phone number already exist";
				    		echo '<script type="text/javascript">alert("'.$errors.'");</script>';
				    	}
				    }
				    else
					    {
				$state=mysqli_escape_string($con,$_POST['states']);
				// echo $sate;
				if(move_uploaded_file($filetmp, $final_dir))
				{
					$regist=mysqli_query($con,"INSERT INTO personaldata(firstname,surname,lastname,applid,email,phone,dob,address,caddress,gender,marital,nationality,state,town,passport,status) VALUES ('$firstname','$surname','$lastname','$staffid','$email','$phone','$dob','$address','$caddress','$sex','$marital','$country','$state','$town','$final_dir','active')");
				if ($regist) {
											//mysqli_commit($con);
											//mysqli_close($con);
											header("location: appsummary");
											//echo 1;
										} else {
											//mysqli_rollback($con);
											//mysqli_close($con);
											echo "Sorry Error Occur When Submitting";
										}
				}
			}
		}
	}
			}//End of the Code
		}
	}
	$harr=$_SESSION["staffid"];

	//die($harr);
	//Olevel Insertion Stage
	if(isset($_POST["submitol"])){
if (empty($_POST["exambody"])) {
		$exa="Required";
		echo '<script type="text/javascript">alert("' . $exa . '")</script>';
		//header("Refresh:1;");
	}elseif (empty($_POST["month"])) {
		$mon="Month Required";
		echo '<script type="text/javascript">alert("' . $mon . '")</script>';
	}elseif (empty($_POST["year"])) {
		$yer=" Year Required";
		echo '<script type="text/javascript">alert("' . $yer . '")</script>';
	}elseif (empty($_POST["siting"])) {
		$sat="Required";
		echo '<script type="text/javascript">alert("' . $sat . '")</script>';
	}else{
	//$teacherid=strtoupper($_POST["teacherid"]);
	$exambody=$_POST["exambody"];
	$examno=strtoupper( $_POST["examno"]);
	//$areas= $_POST["areas"];
	$siting= $_POST["siting"];
	$year = $_POST["year"];
	$month = $_POST["month"];
	$examyear= $month."-".$year; 
	$school = $_POST["school"];
	$eyearadm=$_POST['exmonth'];
	$eyeargra=$_POST['exyear'];
	$sql=mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='$harr' AND examno='$examno'");
    if(mysqli_num_rows($sql)>0){
	$error="Detail Already Exit";
	echo '<script type="text/javascript">alert("' . $error. '")</script>';
}else{
	if ($eyearadm>=$eyeargra) {

			$add="Graduating Year Must Be Greater Than Admit Year";
			echo '<script>alert("'.$add.'")</script>';

		}else{
    //inserting to the database
 		$qu=mysqli_query($con,"INSERT INTO  oleveldetail(applid,exambody,examno,examyear,yearadmit,yeargrad,school,noofsit) VALUES ('$harr','$exambody','$examno','$examyear','$eyearadm','$eyeargra','$school','$siting')");
	if ($qu>0) {
		$succ='Successfully submited';
		header("Refresh:2;  appsummary");
	
	}
	else
	{
		$errorss="Error Occured While Submitting";
		echo '<script type="text/javascript">alert("' . $errorss . '")</script>';
	}
	}
	}	
	}
	}

	//Olevel Subjects

	if (isset($_POST["submitsub"])) {
		if (empty($_POST["addcourse"])) {
		$add="Select Subject";
		echo '<script type="text/javascript">alert("' . $add . '")</script>';
		header("Refresh:2;");
	}elseif (empty($_POST["grade"])) {
		$gra="grade Required";
		header("Refresh:2;");
	 }
	//	elseif (empty($_POST[""])) {
	// 	# code...
	// }

	else{
		$addcourses=$_POST["addcourse"];
		//$studentid=$_POST["studentid"];
		//$utmereg=$_POST["jambreg"];
		$grad=$_POST["grade"];

		$sql=mysqli_query($con,"SELECT * FROM oleveldetail WHERE applid='$harr'");
		while ($data=mysqli_fetch_assoc($sql)) {
			$utmereg=$data["examno"];
		}
		//die($harr);
		$sq=mysqli_query($con,"SELECT * FROM olevelresultdetail WHERE applid='$harr'");
		if (mysqli_num_rows($sq)>=12) {
		$error="You have Maximum Subject";
		echo '<script type="text/javascript">alert("' . $error . '")</script>';
		header("Refresh:0;applidocupl");
		}
		else
		{

			$sql=mysqli_query($con,"SELECT * FROM olevelresultdetail WHERE applid='$harr' AND subject='$addcourses'");
		    if(mysqli_num_rows($sql)>0){
			$error="Subject ".$addcourses." Already Exit";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
			header("Refresh:2;");
		}else{
		    //inserting to the database
		 $qu=mysqli_query($con,"INSERT INTO olevelresultdetail(applid,examno,subject,grade) VALUES ('$harr','$utmereg','$addcourses','$grad')");
			if ($qu>0) {
			//$succ='Successfully submited';
			//header("Refresh:3;");
			}else{
				$mess="Error While Submiting";
			}

			}
			
		}
		// }
	}
}
//Sponsorship Insertion
if (isset($_POST['submitspd'])) {
	if (empty($_POST['sponsor'])) {
		$add="Select Sponsorship";
		echo '<script type="text/javascript">alert("' . $add . '")</script>';
	}else{
		$sponsors=$_POST['sponsor'];
		// $sponrname=$_POST['sponsorname'];
		// $address=$_POST['address'];
		$sql=mysqli_query($con,"SELECT * FROM sponsorship WHERE applid='$harr'");
    if(mysqli_num_rows($sql)>0){
		$error="Detail Already Exit";
		echo '<script type="text/javascript">alert("' . $error . '")</script>';
		}else{
			//Checking for Private DataS
			if ($sponsors=="Private") {
				
					$sponrname=$_POST['sponsorname'];
					$address=$_POST['address'];
					$qu=mysqli_query($con,"INSERT INTO sponsorship(applid,sponsorsh,sporname,address) VALUES ('$harr','$sponsors','$sponrname','$address')");
						if ($qu>0) {
						//$succ='Successfully submited';
						header("Refresh:0;  appsummary");
						}else{
							$mess="Error While Submiting";
						}
				
					}elseif ($sponsors=="Scholarship") {
				if (empty($_POST['addresss'])) {
					$errors="Please Fill The Sponsor Detail";
					echo '<script type="text/javascript">alert("' . $errors. '")</script>';
				}else{
					$addresss=$_POST['addresss'];
					$qu=mysqli_query($con,"INSERT INTO sponsorship(applid,sponsorsh,address) VALUES ('$harr','$sponsors','$addresss')");
						if ($qu>0) {
						//$succ='Successfully submited';
						header("Refresh:0;  appsummary");
						}else{
							$mess="Error While Submiting";
						}
				}
			}
			   //inserting to the database
		 

		}
	}
}

if (isset($_POST['submitfl'])) {
	
if (empty($_POST['month']))
 {
 	//Checking if the two droppeddown is empty
$empty = "year admit cannot be empty.";
echo '<script type="text/javascript">alert("' . $empty. '")</script>';
header("Refresh:1;");
}elseif (empty($_POST['year'])) {
	$emptyx = "Year graduate cannot be empty.";
	echo '<script type="text/javascript">alert("' . $emptyx. '")</script>';
}else{
//tracking the posting Data
	$insti=$_POST['schoolname'];
	$yearadm=$_POST['month'];

	$yeargra=$_POST['year'];
	//echo $yeargra;
	$degree=$_POST['certfic'];
	//Checking details Duplicate
	$sql=mysqli_query($con,"SELECT * FROM flscdetail WHERE applid='$harr'");
	if(mysqli_num_rows($sql)>0){
			$error="Detail Already Exist";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
			//header("Refresh:2;");
	}

	else{
		if ($yearadm>=$yeargra) {

			$add="Graduating Year Must Be Greater Than Admit Year";
			echo '<script>alert("'.$add.'")</script>';

		}else{
		$qu=mysqli_query($con,"INSERT INTO flscdetail(applid,schoolname,degree,yearadmit,yeargrad) VALUES ('$harr','$insti','$degree','$yearadm','$yeargra')");
			if ($qu>0) {
			//$succ='Successfully submited';
			header("Refresh:0;  appsummary");
			}else{
				$mess="Error While Submiting";
			}
		}
	}
	//}
		
}
}

//Parent Insertion
if (isset($_POST['submitpar'])) {
	//Checking 
	if (empty($_POST['ptitle'])) {
	$add="Select Parent Title";
		echo '<script>alert("'.$add.'")</script>';
	}elseif (empty($_POST['famirelat'])) {
		$add="Select Family Relation";
		echo '<script>alert("'.$add.'")</script>';
	}elseif (empty($_POST['religh'])) {
		$add="Select Religion You Belong";
		echo '<script>alert("'.$add.'")</script>';
	}else{
		//Positing Record
		$titl=mysqli_escape_string($con,$_POST['ptitle']);
		$pfull=mysqli_escape_string($con,$_POST['fullname']);
		$padd=mysqli_escape_string($con,$_POST['address']);
		$pho=mysqli_escape_string($con,$_POST['phone']);
		$emai=mysqli_escape_string($con,$_POST['email']);
		$famre=mysqli_escape_string($con,$_POST['famirelat']);
		$occ=mysqli_escape_string($con,$_POST['ocupation']);
		$pregi=mysqli_escape_string($con,$_POST['religh']);
		//Checking if Details Already Exist
		 //Checking details Duplicate
	$sql=mysqli_query($con,"SELECT * FROM parentdetail WHERE applid='$harr'");
	if(mysqli_num_rows($sql)>0)
	{
			$error="Detail Already Exist";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
			//header("Refresh:2;");
	}else{

		$ins=mysqli_query($con,"INSERT INTO parentdetail(applid,title,parentname,address,phone,email,relat,occupation,relig) VALUES ('$harr','$titl','$pfull','$padd','$pho','$emai','$famre','$occ','$pregi')");
			//if ($qu>0) {
			if ($ins>0) {
			//$succ='Successfully submited';
			header("Refresh:0;  appsummary");
			}else{
				echo "Error While Submiting";
			}
		}

	}
}

//other Qualification
if (isset($_POST['submitot'])) {
	if (empty($_POST['otheqa'])) {
		$error="Please Select";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
			//header("Refresh:2;");
	}else
	$ott=mysqli_escape_string($con,$_POST['otheqa']);
	$sql=mysqli_query($con,"SELECT * FROM otherquali WHERE applid='$harr'");
	if(mysqli_num_rows($sql)>0)
	{
			$error="Detail Already Exist";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
			//header("Refresh:2;");
	}else{
	if ($ott=="Yes") {
		if (empty(mysqli_escape_string($con,$_POST['certif']))) {
			$error="Please Fill Certificate Box";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
		}elseif (empty($_FILES['passport']['name'])) {
			$error="Please Upload Certificate Image";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
		}
		else{
			$cert=mysqli_escape_string($con,$_POST['certif']);
			$filename = $_FILES['passport']['name'];
			$filetmp =$_FILES['passport']['tmp_name'];
			$filesize = $_FILES['passport']['size'];
			$fileBase = basename($_FILES['passport']['name']);
			$dir = "docum/";
			$final_dir= $dir . $fileBase;
			$it=mysqli_query($con,"INSERT INTO otherquali(applid,status,instname,images) VALUES ('$harr','Yes','$cert','$final_dir')");
			header("Refresh:0; appsummary");
		}
	}elseif ($ott=="No") {
		$it=mysqli_query($con,"INSERT INTO otherquali(applid,status) VALUES ('$harr','No')");
		header("Refresh:0; appsummary");
	}
}
}
//Other institution
if(isset($_POST['submitano'])){
	if (empty($_POST['otheqa'])) {
		$error="Please Select";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
			//header("Refresh:2;");
	}else
	$ott=mysqli_escape_string($con,$_POST['otheqa']);
	$sql=mysqli_query($con,"SELECT * FROM prematric WHERE applid='$harr'");
	if(mysqli_num_rows($sql)>0)
	{
			$error="Detail Already Exist";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
			//header("Refresh:2;");
	}else{
	if ($ott=="Yes") {
		if (empty($_POST['couse'])) {
			$error="Please Course Box";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
		}elseif (empty($_POST['exmonth'])) {
			$error="Please Select Year Enter";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
		}elseif (empty($_POST['exyear'])) {
			$error="Please Year Leave";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
		}elseif (empty($_POST['instina'])) {
			$error="Please Provide Institution Name";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
		}elseif (empty($_POST['reason'])) {
			$error="Please Provide Reason For Leaving";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
		}elseif (empty($_POST['matricno'])) {
			$error="Please Your Previous Matricno";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
		}
		else{
			$cert=mysqli_escape_string($con,$_POST['couse']);
			$certr=mysqli_escape_string($con,$_POST['exmonth']);
			$certt=mysqli_escape_string($con,$_POST['exyear']);
			$certy=mysqli_escape_string($con,$_POST['instina']);
			$certu=mysqli_escape_string($con,$_POST['reason']);
			$certi=mysqli_escape_string($con,$_POST['matricno']);
			if ($certr>=$certt) {

			$add="Entery Year Must Be Greater Than Admit Year";
			echo '<script>alert("'.$add.'")</script>';

		}else{
			$it=mysqli_query($con,"INSERT INTO prematric(applid,status,instname,yearadmit,yeargra,prevmatr,precouse,reason) VALUES ('$harr','Yes','$certy','$certr','$certt','$certi','$cert','$certu')");
			header("Refresh:0; appsummary");
		}
		}
	}elseif ($ott=="No") {
		$it=mysqli_query($con,"INSERT INTO prematric(applid,status) VALUES ('$harr','No')");
		header("Refresh:0; appsummary");
	}
}
}
/*Inserting Foreign Data*/
if (isset($_POST['submitpdim'])) {
	$pasno=mysqli_escape_string($con,$_POST['paspno']);
	$dates=mysqli_escape_string($con,$_POST['dateisu']);
	$whes=mysqli_escape_string($con,$_POST['whereis']);
	$valit=mysqli_escape_string($con,$_POST['valiti']);
	$filename = $_FILES['passport']['name'];
	$filetmp =$_FILES['passport']['tmp_name'];
	$filesize = $_FILES['passport']['size'];
	$fileBase = basename($_FILES['passport']['name']);
	$dir = "docum/";
	$final_dir= $dir . $fileBase;
	$sql=mysqli_query($con,"SELECT * FROM foreignd WHERE applid='$harr'");
	if(mysqli_num_rows($sql)>0)
	{
			$error="Detail Already Exist";
			echo '<script type="text/javascript">alert("' . $error . '")</script>';
			//header("Refresh:2;");
	}else{


	if(move_uploaded_file($filetmp, $final_dir)){

	$regist=mysqli_query($con,"INSERT INTO foreignd(applid,passportno,dteissue,whereis,passvitill,images) VALUES ('$harr','$pasno','$dates','$whes','$valit','$final_dir')");
			if ($regist) {
					//mysqli_commit($con);
					//mysqli_close($con);
					header("location: appsummary");
					//echo 1;
				} else {
					//mysqli_rollback($con);
					//mysqli_close($con);
					echo "Sorry Error Occur When Submitting";
				}
			}
		}
}
//Updating Session
//Update User Status
if(ISSET($_POST['updateuse'])){
		 $user_id = $_POST['user_id'];
		//$firstname = $_POST['subject'];
		$lastname = $_POST['grade'];
		// $address = $_POST['address'];
		
		mysqli_query($con, "UPDATE `olevelresultdetail` SET `grade` = '$lastname' WHERE `id` = '$user_id'") or die(mysqli_error());

		header("Refresh:0; ApplicOlSub.php");
	}
//updating the degree info
	if (isset($_POST['submitdeg'])) {
		if (empty($_POST['degree'])) {
			$add="Please Set Degree You Want Undergo";
			echo '<script>alert("'.$add.'")</script>';
		}
		else
		{
			$lastname =mysqli_escape_string($con,$_POST['degree']);
			mysqli_query($con, "UPDATE `register` SET `programm` = '$lastname' WHERE `userid` = '$harr'") or die(mysqli_error());

		header("Refresh:0; appsummary");
		}
	}

?>