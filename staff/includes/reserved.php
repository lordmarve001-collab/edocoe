$subject = 'Course Registration Disapproved Notification';
	//$from = 'UATCourseRegistrationMailSystem@novenauniversity.edu.ng';
	$from = 'portal.uat.edu.ng';
	//$headers = "From: sender\'s email";
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	 
	//Create email headers
	$headers .= 'From: '.$from."\r\n".
	    'Reply-To: '.$from."\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	 
	// Compose a simple HTML email message
	$message = '<html><body> 
		<style type="text/css">
		table{
			border: 1px solid black;
			cellspacing:0px;
			cellpadding:0px;
			width: 100%;
		}
		table td, table th{
			border: 1px solid black;
		}
		
		</style>
		
	'; 
	//Getting the student information especially the full name
	
		  
		  $message .= '<span>Student Name: <b>'.$rows['firstname']. ', '. $rows['lastname'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Matric No: <b>'.$stude.'</b></span>';
          $message .= '<br>';
		  $message .= '<span class=MsoNormal>Session: <b>'.$sez.'</b></span>';
          $message .= '<br>';
		  $message .= '<span class=MsoNormal>Semester: <b>'.$sem.'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Email: <b>'.$rows['uniemail'].'</b></span>';
          $message .= '<br><span class=MsoNormal>Faculty: <b>'.$rows['faculty'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Programme: <b>'.$rows['programme'].'</b></span>';
          $message .= '<br>';
          $message .= '<span class=MsoNormal>Level: <b>'.$levl.'</b></span>';
		  $message .= '<p></p>';
		  $message .= '<p></p>';
		   //$message .= '<p align="centre"><strong> COURSE INFORMATION </strong></p>';
		  /*
	$sem,$sez,$levl,$progn,*/
	$message .= '<table rules="all" border="1" cellpadding="5" cellspacing="0">
					<tr>
					<th>#</th>
                    <th style="width: 130px">Course Code</th>
                    <th>Course Title</th>
                    <th style="width: 120px;">Credit Unit</th>
					</tr>';
     $ques= mysqli_query($con,"SELECT * FROM studentcourseregistration INNER JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$stude' AND studentcourseregistration.level='$levl' AND studentcourseregistration.semester='$sem' AND studentcourseregistration.session='$sez' ORDER BY studentcourseregistration.coursecode");
      $sr =1;
      while ($row= mysqli_fetch_array($ques)){
      	$message .= '<tr>';
		$message .='<td>' . $sr.'</td>';
      	$message .= '<td>' . $row['coursecode'].'</td>';
    	$message .= '<td>' . $row['title'].'</td>';
    	$message .= '<td>' . $row['unit'].'</td>';
		$message.= '</tr>';
       $sr++;  
	   } 
      $queryy="SELECT SUM(unit) AS sumunit FROM studentcourseregistration INNER JOIN course ON studentcourseregistration.coursecode=course.coursecode WHERE studentcourseregistration.studentid='$stude' AND studentcourseregistration.level='$levl' AND studentcourseregistration.semester='$sem' AND studentcourseregistration.session='$sez' ORDER BY studentcourseregistration.coursecode";
      $resultt= mysqli_query($con,$queryy);
            while($rowz = mysqli_fetch_array($resultt))
            {
                                  //$_POST['totalUnits']=$rowz['sumunit'];
								  
			  	$message .= '<tr>';
				$message .= '<td colspan="3" align="right" font-weight="bolder">Total Credit Units </td>';
				$message .= '<td>' .$rowz['sumunit'].'</td>';
				$message.= '</tr>';
			} 							
	$message .= '</table>';
	$message .= '</body></html>';
	$message.='<p><strong>Please Kindly Note: That Your Course Registration Is Disapproved. For the Following Reason '.$mes.'</strong></p>';
	/* End Here */
	mail($to, $subject, $message, $headers);