<?php 
require('pdf/fpdf.php');

session_start();

$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
 if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout"); 
    }
    else{ 
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
class PDF extends FPDF
{
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    //$this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
 //$pdf=new FPDFCellFit();
 //$pdf=new FPDF('L','mm','legal');
 $pdf=new PDF('L');
//Sessional Variable
$clvl=$_SESSION['level'];
$ses=$_SESSION['section'];
$sems=$_SESSION['semester'];
$prgs=$_SESSION['program'];
$daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$prgs'"));
  $deplt=$daft['did'];
  $depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='$deplt' "));
  $dt = $depna['dept'];
$pdf->AddPage();
$pdf->AliasNbPages();
/*$pdf->SetDisplayMode ("fullpage", "two", "0");
$pdf->SetDisplayMode("fullpage", "continuous");
$pdf->SetDisplayMode("fullpage", "continuous");*/
$pdf->SetTitle($orgname);
//$pdf->Image($logo,10,5,40);
$pdf->Image($logo,130,5,$pdf->GetX()+15, $pdf->GetY()+15);
$pdf->SetFont('Arial','B',12); 
$pdf->Text(110, 36, strtoupper($orgname.' , '.$schadress),'C'); 
$pdf->Text(115, 42, 'SENATE RESULT SUMMARY','C'); 
// $pdf->Text(30,47,strtoupper('PROGRAMME: '.$prgs.'                                      SESSION: '.$ses),'C');
// $pdf->Text(30,52,strtoupper('SEMESTER: '.$sems.'                                                             LEVEL: '.$clvl),'C');
$pdf->Text(100,48,strtoupper('DEPARTMENT: '.$dt),'C');
$pdf->Text(100,55,strtoupper('PROGRAMME: '.$prgs),'C');
$pdf->Text(30,63,strtoupper('SEMESTER: '.$sems.'                        SESSION : '.$ses.'                                     LEVEL: '.$clvl),'C');
$pdf->Ln(56);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(10, 6, "#", 'LBTR', 0, 'L', 0);
$pdf->Cell(35, 6, "Student ID", 'LBTR', 0, 'L', 0);
$pdf->Cell(55, 6, "Student Name", 'LBTR', 0, 'L', 0);
$pdf->Cell(39, 6, "Previous", 'LBTR', 0, 'C', 0);
$pdf->Cell(39, 6, "Present", 'LBTR', 0, 'C', 0);
$pdf->Cell(39, 6, "Cumulative", 'LBTR', 0, 'C', 0);
$pdf->Cell(15, 6, "Standing", 'LBTR', 0, 'C', 0);
$pdf->MultiCell(50, 6, "Remarks",1, 'C', 0);
//$pdf->Ln();
$pdf->Cell(10, 6, "", 'LBTR', 0, 'L', 0);
$pdf->Cell(35, 6, "", 'LBTR', 0, 'L', 0);
$pdf->Cell(55, 6, "", 'LBTR', 0, 'L', 0);
$pdf->Cell(12, 6, "TCP", 'LBTR', 0, 'C', 0);
$pdf->Cell(12, 6, "TNU", 'LBTR', 0, 'C', 0);
$pdf->Cell(15, 6, "CGPA", 'LBTR', 0, 'C', 0);
$pdf->Cell(12, 6, "TCP", 'LBTR', 0, 'C', 0);
$pdf->Cell(12, 6, "TNU", 'LBTR', 0, 'C', 0);
$pdf->Cell(15, 6, "GPA", 'LBTR', 0, 'C', 0);
$pdf->Cell(12, 6, "TCP", 'LBTR', 0, 'C', 0);
$pdf->Cell(12, 6, "TNU", 'LBTR', 0, 'C', 0);
$pdf->Cell(15, 6, "CGPA", 'LBTR', 0, 'C', 0);
$pdf->Cell(15, 6, "", 'LBTR', 0, 'C', 0);
$pdf->MultiCell(50, 6, "", 1, 'C', 0);
//$pdf->Ln();
try {
    $grades_count = [];
    $_POST['keys']='';
    $sqlv="SELECT DISTINCT studentid FROM result WHERE acadsession='$ses' AND semester='$sems' AND level='$clvl' AND programme='$prgs' ORDER BY studentid ASC";

        $_POST['totalunit']=0;
         $stv = mysqli_query($con,$sqlv);
            $countv = mysqli_fetch_all($stv,MYSQLI_ASSOC);
          $d=0;
          $Acct=0;
         foreach($countv as $rowv)
       {
        $d+=1;
        $Acct+=1;
        stuname($con,$rowv['studentid']);
        ComputePreviousResults($con,$rowv['studentid'],$sems,$clvl,$ses,$prgs);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Cell(10, 6, $d, 'LBTR', 0, 'L', 0);
        $pdf->Cell(35, 6,$rowv['studentid'], 'LBTR', 0, 'L', 0);
        $pdf->Cell(55, 6, ucwords($_POST["harrd"]), 'LBTR', 0, 'L', 0);
        $pdf->Cell(12, 6, $_POST['StrCums'], 'LBTR', 0, 'C', 0);
        $pdf->Cell(12, 6, $_POST['Strpos'], 'LBTR', 0, 'C', 0);
        $pdf->Cell(15, 6, $_POST['CGPs'], 'LBTR', 0, 'C', 0);
        // Current Semester Computation
        $sqlty="SELECT * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='".$rowv['studentid']."' AND result.programme='$prgs' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$ses'  ORDER BY result.studentid";
        //USing For Loop TO count the Courses
        $_POST['totalunit']=0;
        $_POST['tupt']=0;
        $_POST['stupt']=0;
        $_POST['mysk']='';
        $stt = mysqli_query($con,$sqlty);
        $countt = mysqli_fetch_all($stt,MYSQLI_ASSOC);
        foreach($countt as $rowt)
        {
        //$k+=1;
        $_POST['totalunit']+=$rowt['unit'];
        /*Codes Using to Calculate De semester GPa

        */
        GetGradeSystem($con);
        $Scod=$rowt['ca'] + $rowt['exam'];
        for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++) {
        if ($Scod <= $_POST['Maxscore'][$i] && $Scod >= $_POST['Minscore'][$i]) {
        $_POST['mysk']   =   $_POST['point'][$i];
        $_POST['tupt'] += (int)$_POST['mysk']*(int)$rowt['unit'];
        $_POST['stupt'] += (int)$_POST['mysk'];

        //computing d Semester GPA
       
        //unikx($_POST['totalPointx']);
        }

        }
        } 
        $sgpa=number_format($_POST['tupt']/$_POST['totalunit'],2);
        $pdf->Cell(12, 6, $_POST['tupt'], 'LBTR', 0, 'C', 0);
        $pdf->Cell(12, 6, $_POST['totalunit'], 'LBTR', 0, 'C', 0);
        $pdf->Cell(15, 6, $sgpa, 'LBTR', 0, 'C', 0);
        ComputeThePreviousResults($con,$rowv['studentid'],$sems,$clvl,$ses,$prgs);
        $pdf->Cell(12, 6, $_POST['StrCum'], 'LBTR', 0, 'C', 0);
        $pdf->Cell(12, 6, $_POST['Strpo'], 'LBTR', 0, 'C', 0);
        $pdf->Cell(15, 6, $_POST['CGP'], 'LBTR', 0, 'C', 0);
        //$pdf->SetFont('helvetica', 'B', 6);
        Carryover($con,$rowv['studentid'],$sems,$clvl,$ses,$prgs);
        Outstand($con,$rowv['studentid'],$prgs,$clvl);
        gradeclass($con,$_POST['CGP']);
        $pdf->SetFont('helvetica', '',8);
        $pdf->Cell(15, 6, $_POST['myGracl'], 'LBTR', 0, 'J', 0);
        $pdf->SetFont('helvetica', 'B',6);
        //$pdf->Cell(36, 6, $_POST['HolderCO'], 'LBTR', 0, 'L', 0);
        $_POST['keys']='';
        $newfc=array($_POST['HolderCO']);
        $newfd=array($_POST['cot']);
        $newf=array_merge($newfc,$newfd);
        $net=array_filter($newf);
        if (empty($net))
        {
        $pdf->MultiCell(50,6, 'Passed', 1, 'L', False);
        }else{
            foreach ($net as $keys) {
                $_POST['keys'].=$keys.'';
             }
             $pdf->MultiCell(50,6, 'RP: '.$_POST['keys'], 1, 'L', False);
            }

        
        $pdf->SetFont('helvetica', 'B', 8);
        //$pdf->Ln();
        $_POST['cote'][$Acct]=$_POST['myGraclc'];
    }
   $pdf->Ln(2); 
   $pdf->SetFont('helvetica', 'B', 10);
     $pdf->Cell(35,6,'SUMMARY ','LBTR',0,'C',0);
     $pdf->Ln();
     $pdf->Cell(19, 6, "Standing", 'LBTR', 0, 'L', 0);
     $pdf->Cell(16, 6, "No", 'LBTR', 0, 'L', 0);
     $pdf->Ln();
  $ts=0;
   for($ts=1;$ts<=$Acct; $ts++)
     {
    
    //echo $_POST['cote'][$ts];
    if(isset($grades_count[$_POST['cote'][$ts]]) === false) { 
     $grades_count[$_POST['cote'][$ts]] = 0;                               
    
    }
    $grades_count[$_POST['cote'][$ts]]++;
    }
//} 
      $cks=0;  
      $gcd=mysqli_query($con,"SELECT * FROM gradclass ORDER BY id");
      $ress = mysqli_fetch_all($gcd, MYSQLI_ASSOC);
      foreach ($ress as $resu) {          
     foreach ($grades_count as $grade=>$count) {
            //echo "$grade=$count ";
            
            $pdf->SetFont('helvetica', '', 10);
            if ($resu['remark']==$grade) {
              $cks+=$count;
              $pdf->Cell(19,6,$resu['remark'], 'LBTR', 0, 'L', 0);
              $pdf->Cell(16, 6, $count,'LBTR', 0, 'J', 0);  
              $pdf->Ln();
            }
            
            }
        }
            $pdf->Cell(19,6,"Total", 'LBTR', 0, 'L', 0);
            ;
            $pdf->Cell(16, 6,$cks,'LBTR', 0, 'J', 0);

    $pdf->Ln(4); 
    deanname($con,$prgs);
    hodname($con,$prgs);
    vcname($con); 
	$paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

	  if ($paych['position']=="hod")
	  {
      $dn="DEAN";
      $hn="HEAD OF DEPARTMENT";
      $vc="VICE CHANCELLOR";
      $pdf->ln(3);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Cell(124,6,$hn.'',0,0);
      $pdf->Cell(90,6,$dn.'',0,0);
      $pdf->Cell(4,6,$vc.'',0,0);
      $pdf->ln();
      $pdf->Cell(104,6,'NAME: '.$_POST['nameh'],0,0);
      $pdf->Cell(90,6,'NAME : '.$_POST['names'],0,0);
      $pdf->Cell(4,6,'NAME: '.$_POST['vname'],0,0);
      $pdf->ln();  
      $pdf->Cell(104,6,'Sign: ________________________________',0,0);
      $pdf->Cell(90,6,'Sign: ________________________________',0,0);
      $pdf->Cell(4,6,'Sign: ________________________________',0,0);  
      $pdf->ln();
      $pdf->Cell(104,6,'Date: ________________________________',0,0);
      $pdf->Cell(90,6,'Date: ________________________________',0,0);
      $pdf->Cell(4,6,'Date: ________________________________',0,0);
		  }else{
			  $dn="DEAN";
      $hn="HEAD OF DEPARTMENT";
     $vc="PROVOST";
      $pdf->ln(3);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Cell(124,6,$hn.'',0,0);
      $pdf->Cell(90,6,$dn.'',0,0);
      $pdf->Cell(4,6,$vc.'',0,0);
      $pdf->ln();
      $pdf->Cell(104,6,'NAME: '.$_POST['nameh'],0,0);
      $pdf->Cell(90,6,'NAME : '.$_POST['names'],0,0);
      $pdf->Cell(4,6,'NAME: '.$_POST['vname'],0,0);
      $pdf->ln();  
      $pdf->Cell(104,6,'Sign: ________________________________',0,0);
      $pdf->Cell(90,6,'Sign: ________________________________',0,0);
      $pdf->Cell(4,6,'Sign: ________________________________',0,0);  
      $pdf->ln();
      $pdf->Cell(104,6,'Date: ________________________________',0,0);
      $pdf->Cell(90,6,'Date: ________________________________',0,0);
      $pdf->Cell(4,6,'Date: ________________________________',0,0);
		  }

} catch (Exception $e) {
  echo $e;  
}
$resh = trim(str_ireplace( array( '\'', '"',',',';','<','>','/','-','_',''), '', $ses));
$reshd = trim(str_ireplace( array( '\'', '"',',',';','<','>','/','-','_',''), '', $sems));
$pdf->Output($clvl.'_'.$reshd.'_Senate_Summary_for_'.$resh.'PrintOut.pdf','I');
?>
<?php
}
function stuname($con,$maxt)
 {
        $sql =mysqli_query($con,"SELECT *  FROM  applicant WHERE applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
     
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        $name =$sur." ".$las." ".$first;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
               if ($data=="") {
    $sqlt =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $datat = mysqli_fetch_assoc($sqlt);
      if ($datat=="") {
        $_POST["harrd"]=" No Namess";
      }else{
        $first = $datat['firstname'];
        $sur = $datat['othername'];
        $las=$datat['lastname'];
        $name =$first." ".$las." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["first"]= $first;
        $_POST["las"]= $las;
        }
    }
    

 }
/*Loading Grades*/
function GetGradeSystem($con)
  {
   
  $gradeCounter=0;
    $sqlper = "SELECT  * FROM gradetable";
    $result = mysqli_query($con, $sqlper);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //$rowks=mysqli_num_rows($result);
    foreach ($data as $row) {
      $gradeCounter += 1;
      $_POST['Minscore'][$gradeCounter] =  $row["minscore"];
      $_POST['Maxscore'][$gradeCounter] =  $row["maxscore"];
      $_POST['Grade'][$gradeCounter]    =  $row["grade"];
      $_POST['point'][$gradeCounter]    =  $row["point"];
    }
    $_SESSION['gradeCounter'] = $gradeCounter;
  }

/*Cummulative Computation*/
function ReadingCurrentSemesterResult($con,$mat,$se1,$lv1,$as1,$pr1)
//function ReadingCurrentSemesterResult($std,$lv1,$pr1,$se1,$as1,$tr)
  {
   
    //Variable Declaration
    $Acode=0;
 $TPointds = 0;
 $TotalUnitds=0;
         $_POST['TotalUnitds'] = 0;
         $_POST['Pth'] = 0;
         $_POST['cue'] = 0;
         $_POST['stupts'] = 0;
         $_POST['mysz']=0;
 
         $harr = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$mat' AND result.acadsession='$as1' AND result.semester='$se1' AND result.level='$lv1' AND result.programme='$pr1' ORDER BY result.studentid";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
          foreach($resulter as $rowk)
          {
            $Acode+=1;
            $UnitGPCS[$Acode] = $rowk['unit'];
            $TotalUnitds += $rowk['unit'];
            $Totaleds =$rowk['ca'] + $rowk['exam'];

            GetGradeSystem($con);
      for ($k = 1; $k<= $_SESSION['gradeCounter']; $k++){
                                if ($Totaleds <= $_POST['Maxscore'][$k] && $Totaleds >= $_POST['Minscore'][$k])
                                 {
                                   
                                    $_POST['mysz']   =   $_POST['point'][$k];
                                    $TPointds+=(int)$_POST['mysz'] * (int)$rowk['unit'];
                                    $_POST['pointd']=$TPointds;
                                    $_POST['stupts'] += (int)$_POST['mysz'];

                                 }


                          }
        }
        //echo "Current Units ".$TotalUnitds;
        $_POST['TotalUnitds']=$TotalUnitds;
        $_POST['cue']=$_POST['stupts'];
        $_POST['Pth']=$_POST['pointd'];

  }
  function ComputeThePreviousResults($con,$st,$se11,$lv11,$as11,$pr11)
     {

       
       //Declaring all the Variables
         $TPointer = 0; $GP = 0; $TotalUniter = 0; $Totaler = 0;
         $Cum = 0; $GP_Cum = 0; $TotalUCum = 0; $TotalPointCum = 0; $MyGP = 0; $Counter = 0;
         $TPointd = 0;
         $TPointdsk=0;
         $TotalUnitd = 0;
         $_POST['totalunit']=0;
         $_POST['stuptc'] = 0;
         $_POST['Pthc'] = 0;
         $_POST['mysc'] = 0;
         $_POST['pointsk']=0;
       try {

        //Trying It Here
              $harr= "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$st' AND result.acadsession<'$as11' AND result.programme='$pr11'  ORDER BY result.studentid";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
      foreach($resulter as $rowz){

        $_POST['totalunit']+=$rowz['unit'];
        $Scodc=$rowz['ca'] + $rowz['exam'];
            GetGradeSystem($con);
        for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                                if ($Scodc <= $_POST['Maxscore'][$j] && $Scodc >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['mysc']=$_POST['point'][$j];
                                    $TPointdsk +=(int)$_POST['mysc'] * (int)$rowz['unit'];
                                    $_POST['pointsk']=$TPointdsk;
                                    $_POST['stuptc'] += (int)$_POST['mysc'];
                                 }
                          }
          }

        $_POST['Pthc']=$_POST['pointsk'];

        //Ends Here
         $Counter_P = 0;
                 if ($se11 =="First Semester")
                 {

                    ReadingCurrentSemesterResult($con,$st,$se11,$lv11,$as11,$pr11);
                    //echo $_POST['TotalUnitds'];
                    $_POST['Strpo']= ($_POST['totalunit'] +  $_POST['TotalUnitds']);
                    $_POST['StrCE']= ($_POST['stuptc']+$_POST['cue']);
                    $_POST['StrCum']=($_POST['Pthc']+$_POST['Pth']);
                    
                    $_POST['CGP']=number_format((($_POST['Pthc']+$_POST['Pth'])/($_POST['totalunit']+  $_POST['TotalUnitds'])),2);//compute cumulative

                 }
                 else if ($se11 == "Second Semester" ) //
                 {
                    
                    ReadingCurrentSemesterResult($con,$st,$se11,$lv11,$as11,$pr11);
                    //echo "<br>";
                    GetSecondResult($con,$st,$se11,$lv11,$as11,$pr11);
                    $_POST['Strpo']=($_POST['totalunit'] + $_POST['TotalUnitds'] + $_POST['Unitsd']);
                    
                    $_POST['StrCE']= ($_POST['stuptc']+$_POST['cues']+$_POST['cue']);
                    $_POST['StrCum']=($_POST['Pthc']+$_POST['pointsd']+$_POST['Pth']);
                    $_POST['CGP']=number_format((($_POST['Pthc']+$_POST['pointsd']+$_POST['Pth'])/($_POST['totalunit'] + $_POST['TotalUnitds'] + $_POST['Unitsd'])),2);
                    
                 }

       } catch (Exception $e) {

       }


    }

    function GetSecondResult($con,$matric,$semet,$lv11,$sectin,$program)
    {
        
        //declaring variable
        $scoresCounters=0;
        $_POST['Unitsd']=0;
        $_POST['cues']=0;
        $_POST['stupty']=0;
        $TPointdsky=0;
        $_POST['mys']=0;
        //checking semester
        $semet="First Semester";

        $mysqlercs = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matric' AND result.acadsession='$sectin' AND result.semester='$semet' AND result.programme='$program'  ORDER BY result.studentid";
   $resultfy = mysqli_query($con,$mysqlercs);
        $resulters = mysqli_fetch_all($resultfy,MYSQLI_ASSOC);
  foreach($resulters as $row){
    $scoresCounters+=1;

     $_POST['Unitsd']+=$row['unit'];
     $Totaledx  =(int)$row['exam'] + (int)$row['ca'];
      GetGradeSystem($con);
      for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                                if ($Totaledx <= $_POST['Maxscore'][$j] && $Totaledx >= $_POST['Minscore'][$j])
                                 {
                                   
                                    $_POST['mys']=$_POST['point'][$j];
                                    $TPointdsky +=(int)$_POST['mys'] * (int)$row['unit'];
                                    $_POST['pointsd']=$TPointdsky;
                                    $_POST['stupty'] += (int)$_POST['mys'];

                                 }


                          }



    }
    $_POST['cues']=$_POST['stupty'];
}

function Carryover($con,$Matric,$sem,$level,$sectin,$prom){
     
        $MyData="";
        $HolderCO='';
       $_POST['coufc']='';
        $COS='';
        $_POST['MyData']='';
        //echo $codet;
         try
         {
            
              $ch="SELECT coursecode, MAX(ca+exam) FROM result WHERE  acadsession<='$sectin' AND level<='$level' AND studentid='$Matric' AND programme='$prom' AND (status='Y' OR status='Approved') GROUP BY coursecode ORDER BY coursecode";
              $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$prom'"));
              if ($data!='') {
                $type = $data['passmark'];  
                }
                        $stmt = mysqli_query($con,$ch);
                        $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
                   foreach($resulter as $rw)
              {

              if ($rw['MAX(ca+exam)'] < $type)
              {  
                
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
                 $HolderCO .="";
             }
             else
             {
                 $HolderCO .="" .$COS;
             }
             $_POST['HolderCO'] = $HolderCO;
     }
//Computation For the previous Record
  function ComputePreviousResults($con,$st,$se11,$lv11,$as11,$pr11)
     {

       
       //Declaring all the Variables
         $TPointer = 0; $GP = 0; $TotalUniter = 0; $Totaler = 0;
         $Cum = 0; $GP_Cum = 0; $TotalUCum = 0; $TotalPointCum = 0; $MyGP = 0; $Counter = 0;
         $TPointd = 0;
         $TPointdsk=0;
         $TotalUnitd = 0;
         $_POST['totalunit']=0;
         $_POST['stuptc'] = 0;
         $_POST['Pthc'] = 0;
         $_POST['mysc'] = 0;
         $_POST['pointsk']=0;
       try {

        //Trying It Here
              $harr= "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$st' AND result.acadsession<'$as11' AND result.programme='$pr11'  ORDER BY result.studentid";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
      foreach($resulter as $rowz){

        $_POST['totalunit']+=$rowz['unit'];
        $Scodc=$rowz['ca'] + $rowz['exam'];
            GetGradeSystem($con);
        for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                                if ($Scodc <= $_POST['Maxscore'][$j] && $Scodc >= $_POST['Minscore'][$j])
                                 {
                                   
                                    $_POST['mysc']=$_POST['point'][$j];
                                    $TPointdsk +=(int)$_POST['mysc'] * (int)$rowz['unit'];
                                    $_POST['pointsk']=$TPointdsk;
                                    $_POST['stuptc'] += (int)$_POST['mysc'];
                                 }
                          }

          }

        $_POST['Pthc']=$_POST['pointsk'];

        //Ends Here
         $Counter_P = 0;
                 if ($se11 =="First Semester")
                 {

                    
                    if($_POST['Pthc']!=0 && $_POST['totalunit']!=0)
                      {
                    $_POST['Strpos']= ($_POST['totalunit']);
                    $_POST['StrCEs']= ($_POST['stuptc']);
                    $_POST['StrCums']=($_POST['Pthc']);
                    
                    $_POST['CGPs']=number_format(($_POST['Pthc']/$_POST['totalunit']),2);//compute cumulative
                    }
                    else if($_POST['Pthc']==0 && $_POST['totalunit']==0)
                    {
                      $_POST['Strpos']="0.00";
                      $_POST['StrCEs']="0.00";
                      $_POST['StrCums']="0.00";
                      $_POST['CGPs']="0.00";
                    }               
                 }
                 else if ($se11 == "Second Semester" ) //
                 {
                   GetScores($con,$se11,$lv11,$as11,$pr11,$st);
                   GetSecResult($con,$st,$se11,$lv11,$as11,$pr11);
                    /*$_POST['Strpos']=($_POST['totalunit']  + $_POST['Unitsd']);
                    $_POST['StrCums']=($_POST['Pthc']+$_POST['pointsd']);
                    $_POST['CGPs']=number_format((($_POST['Pthc']+$_POST['pointsd'])/($_POST['totalunit'] + $_POST['Unitsd'])),2);*/
                     if ($_POST['cues']!=0 && $_POST['Unitsd']!=0 ) {
                     
                   
                    $_POST['Strpos']=($_POST['totalunit']  + $_POST['Unitsd']);
                    $_POST['StrCums']=($_POST['Pthc']+$_POST['pointsd']);
                    $_POST['CGPs']=number_format((($_POST['Pthc']+$_POST['pointsd'])/($_POST['totalunit'] + $_POST['Unitsd'])),2);
                    }elseif($_POST['cues']==0 && $_POST['Unitsd']==0 ){
                      $lvt=mysqli_fetch_assoc(mysqli_query($con,"SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$st' AND result.acadsession<'$as11' AND result.programme='$pr11'  ORDER BY result.studentid"));
                      if ($lvt=="") {
                        $_POST['Strpos']="0.00";
                        $_POST['StrCums']="0.00";
                       $_POST['CGPs']="0.00";
                      }else{
                    $_POST['Strpos']=($_POST['totalunit'] + $_POST['Unitsd']);
                    $_POST['StrCums']=($_POST['Pthc']+$_POST['pointsd']);
                    $_POST['CGPs']=number_format((($_POST['Pthc']+$_POST['pointsd'])/($_POST['totalunit'] + $_POST['Unitsd'])),2);
                      }//Closing else Statement
                    }
                    
                 }

       } catch (Exception $e) {

       }


    }

    function GetSecResult($con,$matric,$semet,$lv11,$sectin,$program)
    {
       
        //declaring variable
        $scoresCounters=0;
        $_POST['Unitsd']=0;
        $_POST['cues']=0;
        $_POST['stupty']=0;
        $TPointdsky=0;
        $_POST['mys']=0;
        $_POST['pointsd']=0;
        //checking semester
        $semet="First Semester";

        $mysqlercs = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matric' AND result.acadsession='$sectin' AND result.semester='$semet' AND result.programme='$program'  ORDER BY result.studentid";

   $resultfy = mysqli_query($con,$mysqlercs);
        $resulters = mysqli_fetch_all($resultfy,MYSQLI_ASSOC);
  foreach($resulters as $row){
    $scoresCounters+=1;

     $_POST['Unitsd']+=$row['unit'];
     $Totaledx  =(int)$row['exam'] + (int)$row['ca'];
      GetGradeSystem($con);
      for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                                if ($Totaledx <= $_POST['Maxscore'][$j] && $Totaledx >= $_POST['Minscore'][$j])
                                 {
                                    @$Code += 1;
                                    $_POST['mys']=$_POST['point'][$j];
                                    $TPointdsky +=(int)$_POST['mys'] * (int)$row['unit'];
                                    $_POST['pointsd']=$TPointdsky;
                                    $_POST['stupty'] += (int)$_POST['mys'];

                                 }


                          }



    }
    $_POST['cues']=$_POST['stupty'];
}

function GetScores($con,$semc,$les,$sesc,$prob,$hars)
{
    $scoresCounter=0;
    $_POST['points']=0;
    $_POST['totalUnit']=0;
    $mysqler = "SELECT  * FROM result INNER JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$hars' AND result.acadsession='$sesc' AND result.semester='$semc' AND result.level='$les' AND result.programme='$prob' ORDER BY result.coursecode";
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
    $_POST['Graclas'][$gradeCounterx]    =  $row["remark"];
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
function deanname($con,$prgs){
 $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$prgs'"));  
$stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='dean' AND staffaculty.faculty='".$fac['fid']."' ");
         $res=mysqli_fetch_assoc($stf);
    if ($res!='') {
     
    
        $stn=ucwords($res['title'].". ".$res['firstname']." ".strtoupper($res['lastname']));
        $_POST['names']=$stn;
      }else{
        $_POST['names']="No Name";
      }

}
function vcname($con){
$dts=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people JOIN staffprive ON staffprive.staffid=people.staffid WHERE people.status='active' AND staffprive.position='vc'"));
    if ($dts!='') {
        $fulna=$dts['title'].". ".$dts['firstname']." ".strtoupper($dts['lastname']);
        $_POST['vname']=$fulna;
    }else{
        $_POST['vname']='No Name';
    }
    
}

function hodname($con,$prgs){
 $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$prgs'"));  
$stf=mysqli_query($con,"SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid JOIN staffprive ON staffaculty.staffid=staffprive.staffid WHERE people.status='active' AND staffprive.position='hod' AND staffaculty.deptm='".$fac['did']."' ");
        $res=mysqli_fetch_assoc($stf);   
        if ($res!='') {
        $stn=ucwords($res['title'].". ".$res['firstname']." ".strtoupper($res['lastname']));
        $_POST['nameh']=$stn;
        }else{
          $_POST['nameh']='No Name';
        }

}
function  Outstand($con,$sdt,$program,$lvs){
    $_POST['cot']='';
    $_POST['cott']='';
    
    $ques= mysqli_query($con,"SELECT DISTINCT coursecode,studentid,custatus FROM studelecour WHERE coursecode NOT IN (SELECT DISTINCT coursecode FROM result WHERE studentid='$sdt' AND programme='$program') AND studentid='$sdt' AND level<='$lvs'");
            $resu= mysqli_fetch_all($ques,MYSQLI_ASSOC);
            foreach($resu as $rwt)
              {
                $_POST['cot'].=$rwt['coursecode'].',';
              }   
}   

?>