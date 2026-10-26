<?php 
session_start();
require('pdf/fpdf.php');
$connection = TRUE;
include '../include/dbconfig.php';
require"phpmailer/class.phpmailer.php";
$mail = new PHPMailer();
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
 if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: logout"); 
    }
    else{ 
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
}
/*Session Variable*/

  $data=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE purpose='Personal Transcript' AND studentid='".$_SESSION['staffid']."'"));
if ($data=='') {

    header("location: student");
}else{
    
$matrics=$_SESSION['staffid'];
class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;
var $angle=0;
function Rotate($angle,$x=-1,$y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}
function _endpage()
{
    if($this->angle!=0)
    {
        $this->angle=0;
        $this->_out('Q');
    }
    parent::_endpage();
}
function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'J';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
function RotatedText($x,$y,$txt,$angle)
{
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}
function RotatedImage($file,$x,$y,$w,$h,$angle)
{
    //Image rotated around its upper-left corner
    $this->Rotate($angle,$x,$y);
    $this->Image($file,$x,$y,$w,$h);
    $this->Rotate(0);
}
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-10);
    // Arial italic 8
    //$this->SetFont('Arial','I',8);
    // Page number
    //$this->Cell(0,10,'Computer Generated Copy, Unauthorized '.$this->PageNo().'/{nb}',0,0,'C');
    //$this->Cell(0,10,'Student Copy Unofficial',0,0,'C');
}
}
 $pdf=new PDF_MC_Table();
 $pdf->AddPage();
$pdf->SetTitle($orgname);
student($matrics,$con);
$pdf->Image($logo,90,5,$pdf->GetX()+15, $pdf->GetY()+15);
$pdf->SetFont('Arial','B',16);
$pdf->Text(60, 36, $orgname.' , '.$schadress,'L');        
$pdf->Text(68, 43, 'Sagbama, Bayelsa State.','C');   
$pdf->SetFont('Arial','B',12);
$pdf->Text(75, 57, strtoupper('Academic Transcript'),'C'); 
$pdf->SetFont('Arial','B',12);
$pdf->Text(10,75,'Matric Number : '.strtoupper($matrics));
$pdf->Text(10,79,'Student Name  : '.ucwords($_POST["harrd"]));
$pdf->Text(10,83,'Programme      : '.$_POST["pro"]);
$pdf->Text(10,88,'Faculty             : '.$_POST["maks"]);
$pdf->Text(10,92,'Department      : '.$_POST["det"]);
$pdf->Ln(86);
$pdf->Ln(1);
/*Start Here*/
try {
	$sqlc="SELECT DISTINCT acadsession,semester,level FROM result
     WHERE studentid='$matrics' AND status='Y' AND publish='1' ";
       
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
    $pdf->SetFont('helvetica', '', 28);
    $pdf->SetTextColor(255,222,233);
    $pdf->RotatedText(50,160,'Student  Copy',25);
    $pdf->SetTextColor(255,222,233);
    $pdf->RotatedText(50,160,'Student  Copy',-25);
    $pdf->SetTextColor(0,0,0);
   $t=0;
   $_POST['ptnt']=0;
   $_POST['totalPointx'] = 0;
   $_POST['totalUnitx']=0;
   $_POST['pointsx']=0;
 for($t=1;$t<=$Acounter; $t++)
 {
 $k=0;
 	GetCumulatives($con,$ACA[$t],$SMSTER[$t],$Lev[$t],$matrics);
	  $pdf->SetFont('helvetica', 'B', 9);
	  $pdf->Cell(31, 6, "Session:".$ACA[$t], 'LBTR', 0, 'L', 0);
	  $pdf->Cell(100, 6, "Level: ".$Lev[$t], 'LBTR', 0, 'L', 0);
	$pdf->Cell(57, 6, "Semester: ".$SMSTER[$t], 'LBTR', 0, 'L', 0);
	$pdf->Ln();
    $pdf->Cell(13, 6, "#", 'LBTR', 0, 'L', 0);
    $pdf->Cell(18, 6, "Code", 'LBTR', 0, 'L', 0);
    $pdf->Cell(100, 6, "Course Title", 'LBTR', 0, 'L', 0);
    $pdf->Cell(13, 6, "Score", 'LBTR', 0, 'L', 0);
    $pdf->Cell(12, 6, "Grade", 'LBTR', 0, 'L', 0);
    $pdf->Cell(16, 6, "Units", 'LBTR', 0, 'C', 0);
    $pdf->Cell(16, 6, "Points", 'LBTR', 0, 'L', 0);
    $pdf->Ln();
    
      $sql="SELECT * FROM result  JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matrics' AND result.semester='$SMSTER[$t]' AND result.acadsession='$ACA[$t]'  AND result.status='Y' AND result.level='$Lev[$t]' AND result.publish='1'  ORDER BY result.coursecode";
             
              $_POST['totalunit']=0;
              $_POST['tupt']=0;
              $_POST['stupt']=0;
              $_POST['mys']=0;
               $st = mysqli_query($con,$sql);
                $count = mysqli_fetch_all($st,MYSQLI_ASSOC);
           foreach($count as $row)
           {
            $k+=1;
              $_POST['totalunit']+=$row['unit'];
				GetGradeSystem($con);
                $Scod=$row['ca'] + $row['exam'];
            for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++) {
            if ($Scod <= $_POST['Maxscore'][$i] && $Scod >= $_POST['Minscore'][$i]) {
                $_POST['mys']   =   $_POST['point'][$i];
                $_POST['tupt'] += (int)$_POST['mys']*(int)$row['unit'];
                $_POST['stupt'] += (int)$_POST['mys'];
                $sgpa=number_format($_POST['tupt']/$_POST['totalunit'],2);
                
            }
        }
         GetGPoint($con,$row['ca'] + $row['exam']);
         $pdf->SetFont('helvetica', '', 10);
        $pdf->SetWidths(array(13,18,100,13,12,16,16));
         $pdf->Row(array($k,$row['coursecode'],$row['title'],($row['ca'] + $row['exam']),GetGrade($con,$row['ca'] + $row['exam']),$row['unit'],GetPoints($con,$row['ca'] + $row['exam']) * $row['unit']));
          //$k = $k + 1;

     }

     $pdf->SetFont('helvetica', 'B', 6.7);
      $pdf->Cell(13, 6, "CUR: ".$_POST['totalunit'], 'LBTR', 0, 'L', 0);
      $pdf->Cell(18, 6, "CUE: ".$_POST['stupt'], 'LBTR', 0, 'L', 0);
      $pdf->Cell(100, 6, "WGP: ".$_POST['tupt']."                                                                                                                   GPA: ".$sgpa, 'LBTR', 0, 'L', 0);
      $pdf->Cell(13, 6, "TCR: ".$_POST['totalUnitx'], 'LBTR', 0, 'L', 0);
      $pdf->Cell(12, 6, "TCE: ".$_POST['ptnt'], 'LBTR', 0, 'L', 0);
      $pdf->Cell(16, 6, "TWGP: ".$_POST['pointsx'], 'LBTR', 0, 'L', 0);
      $pdf->Cell(16, 6, "CGPA: ".$_POST['GPS'], 'LBTR', 0, 'L', 0);
      $pdf->Ln();
  }
} catch (Exception $e) {
	echo $e;
}
/*End Here*/
GetGradeSystem($con);
GetCumulative($con,$matrics);
$queryy=("SELECT SUM(unit) FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matrics' AND result.status='Y'  AND result.publish='1'");
$resultt= mysqli_query($con,$queryy);
while($row = mysqli_fetch_array($resultt)){
$numz=$row['SUM(unit)'];
}
//Querying of Class 
$qgd=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM gradclass WHERE id='1'"));
if ($qgd!='') {
    $dtc=$qgd['minscorep']." - ".$qgd['maxscorep']." ".$qgd['gracla'];
}
$qgd1=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM gradclass WHERE id='2'"));
if ($qgd1!='') {
    $dtc1=$qgd1['minscorep']." - ".$qgd1['maxscorep']." ".$qgd1['gracla'];
}
$qgd2=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM gradclass WHERE id='3'"));
if ($qgd2!='') {
    $dtc2=$qgd2['minscorep']." - ".$qgd2['maxscorep']." ".$qgd2['gracla'];
}
$qgd3=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM gradclass WHERE id='4'"));
if ($qgd3!='') {
    $dtc3=$qgd3['minscorep']." - ".$qgd3['maxscorep']." ".$qgd3['gracla'];
}
$qgd4=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM gradclass WHERE id='5'"));
if ($qgd4!='') {
    $dtc4=$qgd4['minscorep']." - ".$qgd4['maxscorep']." ".$qgd4['gracla'];
}
$qgd5=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM gradclass WHERE id='6'"));
if ($qgd5!='') {
    $dtc5=$qgd5['minscorep']." - ".$qgd5['maxscorep']." ".$qgd5['gracla'];
}
$pdf->Ln(3);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(80,4,"Total Unit Registered : ".$_POST['totalUnitx'],0,0); 
$pdf->Cell(53,4,$dtc." ,".$dtc1,0,1); 
$pdf->Cell(80,4,'Total Credit Point: '.$_POST['points'],0,0);
$pdf->Cell(53,4,$dtc2.', '.$dtc3,0,1);
$pdf->Cell(80,4,'Cumulative GPA:  '.$_POST['CGP'],0,0);
$pdf->Cell(53,4,$dtc4.' , '.$dtc5,0,1);
$pdf->Ln(2);
$pdf->GetX(+17); 
$pdf->GetY(+17);
$pdf->SetFont('helvetica', '', 6);

$pdf->Cell(100, 4, "Legend: CUR: Credit Unit Registered; CUE: Credit Unit Earned; WGP: Weighted Grade Point; GPA:  Grade Point Average. ", '', 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(31, 4, "", '', 0, 'L', 0);
$pdf->Cell(53, 4, "DESCRIPTORS", 'LBTR', 0, 'C', 0);
$pdf->Ln();
$pdf->SetFont('helvetica', '', 6);
$pdf->Cell(100, 4, "TCR: Total Credit Registered; TCE: Total Credit Earned; TWGP: Total Weighted Grade Point; CGPA: Cumulative Grade Point Average", '', 0, 'L', 0);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(31, 4, "", '', 0, 'L', 0);
$pdf->Cell(20, 4, "% Score", 'LBTR', 0, 'C', 0);
$pdf->Cell(17, 4, "Grade", 'LBTR', 0, 'L', 0);
$pdf->Cell(16, 4, "Points", 'LBTR', 0, 'L', 0);
$pdf->Ln();
$grad="SELECT * FROM gradetable";
    $query = mysqli_query($con,$grad);
    $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
    $cnt=1;
    
    foreach($results as $result)
    { 
$pdf->Cell(131, 6, "", '', 0, 'L', 0);
$pdf->Cell(20, 6, $result['minscore']."-".$result['maxscore'], 'LBTR', 0, 'C', 0);
$pdf->Cell(17, 6, $result['grade'], 'LBTR', 0, 'C', 0);
$pdf->Cell(16, 6, $result['point'], 'LBTR', 0, 'C', 0);
$pdf->Ln();
}
// $pdf->SetFont('helvetica', 'B', 6);
// $pdf->MultiCell(153,4,"Legend: CUR: Credit Unit Registered; CUE: Credit Unit Earned; WGP: Weighted Grade Point; GPA:  Grade Point Average. \n TCR: Total Credit Registered; TCE: Total Credit Earned; TWGP: Total Weighted Grade Point; CGPA: Cumulative Grade Point Average.",0,1);
//Loading Current Registral Information $_POST['image']
$pdf->Ln(3);

regd($con);
    $pdf->Image('../staff/'.$_POST['image'],$pdf->GetX(), $pdf->GetY(),40,20);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Ln(10);
    $pdf->Cell(53,8,ucwords($_POST['regname']),0,1);
    $pdf->Cell(53,8,'Registrar',0,1);
 
        // $mail->SMTPDebug = 4;
        // $mail->isSMTP();
        // //$mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        // $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
        // $mail->SMTPAuth = true;                               // Enable SMTP authentication
        // $mail->Username = 'my@uat.edu.ng';                 // SMTP username
        // $mail->Password = '12345@uat';                           // SMTP password
        // $mail->SMTPKeepAlive = true;
        // //$mail->Priority = 1;
        // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        // $body='';
        // $mail->Port = 587; 
        // $mail->setFrom('my@uat.edu.ng', 'Personal Transcript Copy');
        // $mail->addAddress($email);
        // $mail->Subject = 'Personal Transcript Copy';
        // $mail->Body= $body;
        // $mail->addStringAttachment($pdf->Output("S",'PersonalTranscript.pdf'), 'PersonalTranscript.pdf', $encoding = 'base64', $type = 'application/pdf');
        // $mail->send();
        // $mail->SmtpClose();
        // $mail->ClearAddresses();
        // $mail->ClearAllRecipients();

$pdf->Output('Academic_Transcript_PrintOut.pdf',"I");
}
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
              $_POST['totalPointx']=$_POST['point'][$i];
            }

        }
        return   $_POST['totalPointx'];
    }  

  
 function GetGradeSystem($con)
        {
     $gradeCounter=0;       
    
     $sqlper = "SELECT  * FROM gradetable";
      $result = mysqli_query($con,$sqlper);
     $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
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
     //$rowks=mysqli_num_rows($result);
     foreach($data as $row){
        $gradeCounters+=1;
        $_POST['Minscore'][$gradeCounters] =  $row["minscore"];
        $_POST['Maxscore'][$gradeCounters] =  $row["maxscore"];
        $_POST['Grade'][$gradeCounters]    =  $row["grade"];
        $_POST['pointg'][$gradeCounters]    =  $row["point"];
       }
         $_SESSION['gradeCounters']=$gradeCounters;

 }
function GetGPoint($con,$scores)
{
  GetGradeSystem($con);
    $_POST['totalPoints'] = 0;
    for ($i = 1; $i <= $_SESSION['gradeCounter']; $i++) {
        if ($scores <= $_POST['Maxscore'][$i] && $scores >= $_POST['Minscore'][$i]) {
            //$_POST['myPoint']   =   $_POST['point'][$i];
            $_SESSION['ptn'] = (int) $_POST['point'][$i];
            $_POST['myPoint']   =   (int) $_POST['point'][$i];
            $_POST['ptnt'] += (int) $_POST['point'][$i];
            //echo $_POST['point'][$i];
            $_POST['totalPoints'] += $_POST['myPoint'];
        }
    }
    return   $_POST['myPoint'];
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
     $harr= "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matk' AND result.status='Y'  AND result.publish='1' ";
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
    
    $mysqler = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$matl' AND result.acadsession='$sect' AND result.semester='$semss' AND result.level='$lev'  AND result.status='Y'  AND result.publish='1'";
    $resultf = mysqli_query($con,$mysqler);
     $dataf = mysqli_fetch_all($resultf,MYSQLI_ASSOC);
     foreach($dataf as $row){
     $scoresCounterxh+=1;
     $_POST['Unitsx'][$scoresCounterxh]=$row['unit'];
     
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
   $mysqlercs = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$mat' AND result.acadsession='$sect' AND result.semester='$sems' AND result.status='Y' AND result.publish='1'";
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
                                
                          }
}
// function to load the registrar Name
function regd($con){
    $dts=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people JOIN staffprive ON staffprive.staffid=people.staffid WHERE people.status='active' AND staffprive.position='registrar'"));
    if ($dts!='') {
        $fulna=$dts['title'].". ".$dts['firstname']." ".$dts['lastname'];
        $_POST['regname']=$fulna;
    }else{
        $_POST['regname']='No Name';
    }

    $dtms=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM registsig WHERE staffid='".$dts['staffid']."'"));
    if ($dtms!='') {
        $_POST['image']=$dtms['image'];
    }
    
}
function student($mat,$con){
$sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$mat'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
        $_POST["maks"]="";
        $_POST["det"]="";
        $_POST["pro"]="";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $facu=$data['faculty'];
        $def=$data['department'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $progr=$data['programme'];
        $name =$las." , ".$first." ".$sur;

        $dep=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='$def'"));
        $fac=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM faculty WHERE fid='$facu'"));
        $_POST["harrd"]= $name;
        $_POST["maks"]= $fac['fname'];
        $_POST["det"]= $dep['dept'];
        $_POST["pro"]= $progr;
        }
}
?>