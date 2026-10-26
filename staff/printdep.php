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
$clvl=$_SESSION['level'];
$secc=$_SESSION['section'];
$sems=$_SESSION['semester'];
$prgs=$_SESSION['program'];
$daft=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM programs WHERE pname='$prgs'"));
  $deplt=$daft['did'];
  $depna=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM department WHERE deptid='$deplt' "));
  $dt = $depna['dept'];
class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;

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
function Header()
{
global $logo;
global $orgname;
global $schadress;
global $prgs;
global $ses;
global $sems;
global $clvl;
}
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
 $pdf=new PDF_MC_Table();
 //$pdf=new FPDF('L');
 //$pdf=new PDF_HTML('L','mm','Legal');
//Sessional Variable

$pdf->AddPage('L');

$pdf->SetTitle($orgname);
//$pdf->Image($logo,10,5,40);
$pdf->AliasNbPages();
$pdf->Image($logo,163,5,$pdf->GetX()+15, $pdf->GetY()+15);
$pdf->SetFont('Arial','B',12); 
$pdf->Text(140, 36, strtoupper($orgname.' , '.$schadress),'C');
 $pdf->SetFont('Arial','B',10);
$pdf->Text(145, 42, 'DEPARTMENTAL RESULT SUMMARY','C'); 
// $pdf->Text(125,47,strtoupper('PROGRAMME: '.$prgs),'C');
// $pdf->Text(75,52,strtoupper('SEMESTER: '.$sems.'                        SESSION : '.$secc.'                                     LEVEL: '.$clvl),'C');
//$pdf->Text(111, 42, 'DEPARTMENTAL RESULT SUMMARY','C'); 
$pdf->SetFont('Arial','B',10);
$pdf->Text(45,47,strtoupper('DEPARTMENT: '.$dt.'                                 PROGRAMME: '.$prgs),'C');
$pdf->Text(75,52,strtoupper('SEMESTER: '.$sems.'                        SESSION : '.$secc.'                                     LEVEL: '.$clvl),'C');
try {
    $pdf->Ln(45);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(10, 9, "#", 'LBTR', 0, 'L', 0);
$pdf->Cell(35, 9, "Student ID", 'LBTR', 0, 'L', 0);
  
    //Retrieving All Core Course
    $sqlc = "SELECT DISTINCT result.coursecode,result.semester,result.level,course.unit FROM result JOIN course ON result.coursecode=course.coursecode
    WHERE result.programme='$prgs' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$secc'  ORDER BY result.coursecode "; //

    $sts = mysqli_query($con, $sqlc);
    $counter = mysqli_fetch_all($sts, MYSQLI_ASSOC);
    $Acounter = 0;
    foreach ($counter as $rowa) {
    $Acounter += 1;
    $code[$Acounter]     = $rowa['coursecode'];
    $SMSTER[$Acounter]  = $rowa['semester'];
    // /$sta= $rowa['statux'];
    $un[$Acounter]  = $rowa['unit'];
    $Lev[$Acounter]  = $rowa['level'];
    // echo " SES ".$ACA;
        
    }
$t = 0;
  for ($t = 1; $t <= $Acounter; $t++) {
    $k = 0;
    $count = 0;
    $pdf->SetFont('helvetica', 'B', 6);
$pdf->Cell(11,9,$code[$t], 'LBTR', 0, 'L', 0);


}
$pdf->Ln();
$sqlv="SELECT DISTINCT studentid FROM result WHERE acadsession='$secc' AND semester='$sems' AND level='$clvl' AND programme='$prgs' ORDER BY studentid ASC";

              $_POST['totalunit']=0;
               $stv = mysqli_query($con,$sqlv);
                    $countv = mysqli_fetch_all($stv,MYSQLI_ASSOC);
                    $d=0;
           foreach($countv as $rowv)
           {
            $d+=1;
                stuname($rowv['studentid'],$con);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(10, 6, $d, 'LBTR', 0, 'L', 0);
                $pdf->Cell(35, 6, $rowv['studentid'], 'LBTR', 0, 'L', 0);
                $pdf->SetFont('helvetica', 'B', 7);
                
              
          $sqlct = "SELECT DISTINCT coursecode FROM result WHERE programme='$prgs' AND level='$clvl' AND semester='$sems' AND acadsession='$secc'  ORDER BY coursecode "; //

          $sts = mysqli_query($con, $sqlct);
          $counter = mysqli_fetch_all($sts, MYSQLI_ASSOC);
          $Acounterc = 0;
          foreach ($counter as $rowac) {
            $Acounterc += 1;
             $cour[$Acounterc]     = $rowac['coursecode'];

          }
  $tm = 0;
  for ($tm = 1; $tm <= $Acounterc; $tm++) {
   loadcorecourse($cour[$tm],$rowv['studentid'],$sems,$clvl,$secc,$prgs,$con);
    $pdf->SetFont('helvetica', 'B', 6);
   $pdf->Cell(11, 6, $_POST['couif'], 'LBTR', 0, 'C', 0);
    }
                $pdf->Ln();
    }
    $pdf->Ln(3);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(10, 6, "Legend: Not-App: Not Approved (Pending); 
Not-Up: Not Uploaded; FA: Failed due to Absence; Not-Sub: Not Submitted; ---: Not Registered", 0, 'L', 0);
   $pdf->Ln(3); 
    $pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(10, 6, "#", 'LBTR', 0, 'L', 0);
$pdf->Cell(17, 6, "Code", 'LBTR', 0, 'L', 0);
$pdf->Cell(105, 6, "Title", 'LBTR', 0, 'L', 0);
$pdf->Cell(10, 6, "Unit", 'LBTR', 0, 'C', 0);
$pdf->Ln();

    $cds = "SELECT DISTINCT result.coursecode,result.semester,result.level,course.unit,course.title FROM result JOIN course ON result.coursecode=course.coursecode
      WHERE result.programme='$prgs' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$secc'  ORDER BY result.coursecode ";
       
      $ct = mysqli_query($con, $cds);
           $cnt = mysqli_fetch_all($ct, MYSQLI_ASSOC);
           $Acounterv = 0;
           $dt=0;
           foreach ($cnt as $rowb) {
             $Acounterv += 1;
             
              $codev[$Acounterv] = $rowb['coursecode'];
             $Tt[$Acounterv]  = $rowb['title'];
             $unv[$Acounterv]  = $rowb['unit'];
        }
        $kt=0;
        
        for ($kt=1; $kt <=$Acounterv ; $kt++) {
         $dt+=1;
         $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetWidths(array(10,17,105,10));
        $pdf->Row(array($dt,$codev[$kt],$Tt[$kt],$unv[$kt]));
         }
$pdf->Ln();
  
        deanname($con,$prgs);
    hodname($con,$prgs);
    vcname($con); 
    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

      if ($paych['position']=="hod")
      {
      $dn="DEAN";
      $hn="HEAD OF DEPARTMENT";
      $vc="VICE CHANCELLOR";
      $pdf->ln(2);
      $pdf->SetFont('helvetica', 'B', 12);
      $pdf->Cell(124,6,$hn.'',0,0);
      $pdf->Cell(90,6,$dn.'',0,0);
      $pdf->Cell(4,6,$vc.'',0,0);
      $pdf->ln();
      $pdf->Cell(104,6,'NAME: '.ucwords($fullnames),0,0);
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
      $pdf->ln(2);
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


$pdf->Output();


}
function stuname($maxt,$con)
{
  
      $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'   ORDER BY studentid ASC");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $maks=$data['studentid'];
        $maks=$data['studentid'];
        $name =$first." ".$las." ".$sur;
        //$name =$first." ".$las;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
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

function loadcorecourse($ct,$mat,$sem,$le,$se,$pr,$con)
{
  //variable Declaration
    $_POST['pt']=0;
  
$rowr=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$mat' AND result.level='$le' AND result.programme='$pr' AND result.acadsession='$se' AND result.semester='$sem' AND result.coursecode='$ct'"));
if ($rowr!='') {
    $Totaledk=((int)$rowr["ca"] + (int)$rowr["exam"]);
    $coud=$rowr['coursecode'];
    $std=preg_replace('/\s+/', '',$rowr['status']);
    $unt=$rowr['unit'];
    //if ($std!="") {
    if (($std=='Y' OR $std=='Approved') AND $Totaledk!='0') {
  GetGradeSystem($con);
    for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++){
                              if ($Totaledk <= $_POST['Maxscore'][$j] && $Totaledk >= $_POST['Minscore'][$j])
                               {
                                      @$Code += 1;
                                      $_POST['pt']=$_POST['point'][$j]*$unt;
                                      $_POST['G']= $_POST['Grade'][$j];
                               }


                  }
                    
   $_POST['couif']=$Totaledk." | ".$_POST['G']." | ".$_POST['pt'];
   }elseif ($std=='Pending' AND $Totaledk=='0') {
    
    $_POST['couif']='Not-App';
   }
   elseif ($Totaledk=='0' AND $std!='') {
    $_POST['couif']='F A';
   }elseif ($std=='' AND $Totaledk=='0') {
     $_POST['couif']='Not-Up';
   }elseif ($std=='' AND $Totaledk!='0') {
     $_POST['couif']='Not-Sub';
   }
   elseif ($std=='Pending') {
    
    $_POST['couif']='Not-App';
   }
   }elseif ($rowr=='') {
  $_POST['couif']='---';
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
?>