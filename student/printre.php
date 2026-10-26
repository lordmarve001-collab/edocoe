<?php
require "pdf/fpdf.php";
session_start();

$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
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
  $sectin=$_SESSION['section'] ;
  $semet=$_SESSION['semest'];
  $level=$_SESSION['level'];

$filename = $logo;
$name=$orgname.", ".$schadress;
$filepath = $filename;
     
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
    $h=6*$nb;
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
    $this->SetY(-15);
    // Arial italic 8
    //$this->SetFont('Arial','I',8);
    // Page number
    //$this->Cell(0,10,'Computer Generated Copy, Unauthorized '.$this->PageNo().'/{nb}',0,0,'C');
    $this->Cell(0,10,'Computer Generated , Student Copy',0,0,'C');
}
}
 $pdf=new PDF_MC_Table();

          stuname($_SESSION['staffid'],$con);
          $stp='../student/';
          $p=$stp.$_POST["mak"];
          $pdf->AddPage();
          $pdf->AliasNbPages();
          $pdf->SetFont('Arial','B',20); 
          $pdf->SetTitle($orgname);
          $pdf->Image($logo,90,5,$pdf->GetX()+15, $pdf->GetY()+15);
          $pdf->SetFont('Arial','B',16);
          $pdf->Text(40, 36, $orgname,'L');        
          $pdf->Ln(25);
          $pdf->Cell(0,16,'_________________________________________________________');
          $pdf->Ln(15);
          $pdf->SetFont('Arial','B',12);
          $pdf->Text(10,52,'Student Name : '.ucwords($_POST["harrd"]));
          $pdf->Text(10,62,'Matric No : '.$_POST["maks"]);
          $pdf->SetFont('Arial','B',12);
          $pdf->Text(10,70,'Session : '.$sectin);
          $pdf->SetFont('Arial','B',12);
          $pdf->Text(10,80,'Level : '.$level);
          $pdf->Text(10,90,'Programme: '.$progr);
          $pdf->Text(10,100,'Semester: '.$semet);
          $pdf->Image($p,148,51,$pdf->GetX()+32, $pdf->GetY());
          $pdf->Ln(60);
          $pdf->SetFont('Arial','B',13);
          $pdf->Text(55, 108, 'STUDENT SEMESTER RESULT DETAILS','L');
          $pdf->SetFont('Arial', '', 14);
          $width_cell=array(10,18,120,10,12,10);
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell($width_cell[0],10,'#',1,0); // First header column 
          $pdf->Cell($width_cell[1],10,'Code',1,0); // Second header column
          $pdf->Cell($width_cell[2],10,'Course Title',1,0); // Third header column 
          $pdf->Cell($width_cell[3],10,'Unit',1,0); // Fourth header column
          $pdf->Cell($width_cell[4],10,'Grade',1,0); // Fourth header column
          $pdf->Cell($width_cell[5],10,'Point',1,1); // Fourth header column
          $pdf->SetFont('helvetica', '', 28);
          $pdf->SetTextColor(255,222,233);
          $pdf->RotatedText(40,160,'Student Copy',25);
          //$pdf->RotatedImage($logo,90,5,$pdf->GetX()+15, $pdf->GetY()+15,45);
          $pdf->SetTextColor(0,0,0);
          $pdf->SetFont('Arial','',10);
          $pdf->SetWidths(array(10,18,120,10,12,10));
          $sql="SELECT  * FROM result INNER JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='".$_SESSION['staffid']."' AND result.acadsession='$sectin' AND result.semester='$semet' AND result.level='$level' AND result.programme='$progr' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";  
          $query = mysqli_query($con, $sql);
          $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
          $cnt = 1;

          
        GetGradeSystem($con);
        Carryover($level,$sectin,$progr,$_SESSION['staffid'],$semet,$con);
        GetCumulative($semet,$level,$sectin,$progr,$_SESSION['staffid'],$con);

          /*foreach ($results as $result)
          {*/
            for($k=1;$k<=$_SESSION['scoresCounter']; $k++):
           $pdf->Row(array($k,$_POST['Code'][$k],$_POST['Title'][$k],$_POST['Units'][$k],$_POST['G'][$k],$_POST['P'][$k]));
          //$cnt = $cnt + 1;
          //}
           endfor;
         $pdf->Cell(148,6,'Total', 'LBTR', 0, 'L', 0);
         $pdf->Cell(10, 6, $_POST['totalUnit'], 'LBTR', 0, 'C', 0);
         $pdf->Cell(12, 6, '', 'LBTR', 0, 'L', 0);
         $pdf->Cell(10, 6, $_POST['points'], 'LBTR', 0, 'L', 0);

         $pdf->ln(15);
         $pdf->SetFont('helvetica', '', 10);
         $pdf->Cell(3,8,'Previous CGPA: '.$_POST['PGP'],3,1); 
         $pdf->Cell(3,8,'Semester GPA: '.$_POST['GP'],3,1);
         $pdf->Cell(3,8,'Cummulative GPA: '.$_POST['CGP'],3,1);
         gradeclass($con,$_POST['CGP']);
         $pdf->Cell(3,8,'Classification: '.$_POST['myGracl'],3,1);
         $pdf->SetFont('helvetica', '', 9);
         //$pdf->SetTextColor(40,220,230);
         $pdf->MultiCell(256,8,'Carry Overs (If Any): '.$_POST['HolderCO'],3,1);
         $pdf->Output('_Result_PrintOut.pdf',"I");
}
function stuname($maxt,$con)
{
  
      $sql =mysqli_query($con,"SELECT *  FROM  student WHERE studentid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
         $_POST["mak"]="photo/avater.png";
         $_POST["maks"]="Nil";
         $_POST['emai']="Nil";
      }else{
        $first = $data['firstname'];
        $sur = $data['othername'];
        $las=$data['lastname'];
        $mak=$data['image'];
        $maks=$data['studentid'];
        $_POST['emai']=$data['uniemail'];
        $_POST['pro']=$data['programme'];
        $name =strtoupper($las).", ".$first." ".$sur;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["mak"]= $mak;
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

function GetScores($semc,$les,$sesc,$prob,$hars,$con)
{
    $scoresCounter=0;
    $_POST['points']=0;
    $_POST['totalUnit']=0;
     
    //$_SESSION['scoresCounter']=@$_POST['scoresCounter'];
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


     //function Carryover($level,$sectin,$proj,$harr,$sem)
     /*function Carryover($level,$sectin,$proj,$harr,$sem,$con)
     {
      
         $MyData="";
        $HolderCO='';
       $_POST['coufc']='';
        $COS='';
        $_POST['MyData']='';
        try {
             
          $CO="SELECT *,coursecode, MAX(ca+exam) FROM result WHERE acadsession<='$sectin' AND level<='$level' AND studentid='$harr' AND programme='$proj' AND publish='1' AND status='Y' GROUP BY coursecode ORDER BY coursecode";
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
     function Carryover($level,$sectin,$proj,$harr,$sem,$con)
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
function GetCumulative($semet,$levg,$sech,$proh,$hars,$con)
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
                            GetScores($semet,$levg,$sech,$proh,$hars,$con);
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
                          GetScores($semet,$levg,$sech,$proh,$hars,$con); //get first semester computation
                          GetSecondResult($semet,$sech,$proh,$hars,$con);//get second semester computation
                         
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
            
function GetSecondResult($smt,$sef,$prt,$hars,$con)
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