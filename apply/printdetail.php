<?php
require "pdf/fpdf.php";
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
if(!isset($_SESSION['staffid']))
    {   
    header("Location:logout"); 
    }
    else{ 
    $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:logout");
}
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];

$old_date = date($dead);
$old_date_timestamp = strtotime($old_date);
$_POST['termb'] = date("l jS,\\ F Y", $old_date_timestamp);
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
function SetWidth($w)
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
}
$pdf=new PDF_MC_Table();
stuname($con,$_SESSION['staffid']);
pname($con,$_SESSION['staffid']);
/*Biodata*/
$pdf->AddPage();
$p=$_POST["mak"]; 
$pdf->SetTitle($orgname);
$pdf->Image($logo,25,20,32);
//$pdf->SetTextColor(0,256,0);
$pdf->SetFont('times','B',17);
$pdf->Text(70, 26, 'Edo State College of Health Sciences','C'); 
$pdf->Text(90, 32, 'and Technology','C'); 
$pdf->SetFont('times','B',14);
$pdf->Text(70, 37, 'Website: www.edocohest.edu.ng','C'); 
$pdf->SetFont('times','B',12);
$pdf->Text(76, 42, strtoupper('CANDIDATE BIO DATA'),'C');
$pdf->Image($p,170,22,$pdf->GetX()+17, $pdf->GetY()+17);
$pdf->SetLeftMargin(25);
$pdf->SetFont('times','B',12);
$pdf->Text(25, 63, "APPLICANT'S PERSONAL DETAILS",'J');
$pdf->Ln(55);
$pdf->SetFont('times','',10);
$pdf->Cell(100,6,'Full Name: '.strtoupper($_POST["harrd"]), 1, 0);
$pdf->Cell(73,6,'ADMISSION ID: '.$_SESSION['staffid'], 1, 1);
$pdf->Cell(40,6,'Gender: '.$sex, 1, 0);
$pdf->Cell(50,6,'Date of Birth: '.$dop, 1, 0);
$pdf->Cell(83,6,'Email: '.$_POST['emai'], 1, 1);
$pdf->MultiCell(173,6,'Residential Address: '.$address, 1, 1);
$pdf->Cell(50,6,'Nationality: '.$nal, 1, 0);
if($nal=="Foreign"){

$pdf->Cell(123,6,'Country: '.$statec, 1, 1);
}
else{
   $pdf->Cell(60,6,'State: '.$state, 1, 0);
   $pdf->Cell(63,6,'LGA: '.$lga, 1, 1);
}
$pdf->Cell(50,6,'Phone: '.$phone, 1, 0);
$pdf->Cell(123,6,'NIN: '.$nin, 1, 1);
$pdf->Cell(80,6,'College Of: '.$facu, 1, 0);
$pdf->Cell(93,6,'Programme: '.$pro, 1, 1);
/*Parent Detail*/
$pdf->SetFont('times','B',10);
$pdf->Cell(173,6,'PARENT/GUARDIAN CONTACT DETAILS', 1, 1);
$pdf->SetFont('times','',10);
$pdf->Cell(103,6,'Parent Name: '.$_POST['pd'], 1, 0);
$pdf->Cell(70,6,'Parent Phone: '.$_POST['pho'], 1, 1);
$pdf->MultiCell(173,6,'Residential Address: '.$_POST["add"], 1, 1);

$pdf->Ln(2);
$pdf->SetFont('times','B',12);
$pdf->Cell(125, 8, "CANDIDATE'S EDUCATIONAL DETAILS",0,1,'J');
$pdf->Ln(2);
$frs=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE (applid='".$_SESSION['staffid']."') AND noofsit='1'"));
if ($frs!="") {
    $pdf->Ln(2);
    $pdf->SetFont('times','B',12);
    $pdf->Cell(54,6,'First Sitting', 0, 1); 
    $pdf->SetFont('times','',12);
    if ($frs['examno']!='') {
      $pdf->Cell(54,6,'Candidate No: '.$frs['examno'], 0, 1); 
    }else{
$pdf->Cell(54,6,'Candidate No: ', 0, 1);
}
$pdf->Cell(84,6,'Exam Body: '.$frs['exambody'], 0, 1);
$pdf->Cell(54,6,'Exam Year: '.$frs['examyear'], 0, 1);
//$pdf->Cell(54,6,'School Name: '.$frs['school'], 0, 1);
$width_cells=array(10,60,12.5);
$pdf->Cell($width_cells[0], 6, "#",  1, 0);
$pdf->Cell($width_cells[1], 6, "Subject",1, 0);
$pdf->Cell($width_cells[2], 6, "Grade", 1, 1);
$pdf->SetFont('times', '', 11);
$pdf->SetWidths(array(10,60,12.5));
$sql = "SELECT * FROM olevelresultdetail WHERE (applid='".$_SESSION['staffid']."') AND grade !='' AND examno='".$frs['examno']."' ORDER BY subject";

$query=mysqli_query($con,$sql);
$results=mysqli_fetch_all($query,MYSQLI_ASSOC);
$cnt=1;
foreach($results as $result)
{ 
$pdf->Row(array($cnt,ucwords(strtolower($result['subject'])),$result['grade']));
$cnt = $cnt + 1;
}
}
$pdf->Ln();
$frsc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM oleveldetail WHERE (applid='".$_SESSION['staffid']."') AND noofsit='2'"));
if ($frsc!="") {

 $pdf->SetFont('times', 'B', 12);
$pdf->SetXY(118,113);
$pdf->Cell(82,32,'Second Sitting', 0, 1);
$pdf->SetFont('times','',11);  
$pdf->SetX(118);
if ($frsc['examno']!='') {
      $pdf->Cell(53,6,'Candidate No: '.$frsc['examno'], 0, 1); 
    }else{
$pdf->Cell(54,6,'Candidate No: ', 0, 1);
}
$pdf->SetX(118);
$pdf->Cell(73,6,'Exam Body: '.$frsc['exambody'], 0, 1);
$pdf->SetX(118);
$pdf->Cell(73,6,'Exam Year: '.$frsc['examyear'], 0, 1);
$pdf->SetX(118);
$pdf->SetFont('times', 'B', 8);
$width_cell=array(10,53,12.5);
$pdf->Cell($width_cell[0], 6, "#",  1, 0);
$pdf->Cell($width_cell[1], 6, "Subject",1, 0);
$pdf->Cell($width_cell[2], 6, "Grade", 1, 1);
$pdf->SetWidth(array(10,53,12.5));

 $sqlt = "SELECT * FROM olevelresultdetail WHERE (applid='".$_SESSION['staffid']."')  AND grade !='' AND examno='".$frsc['examno']."' ORDER BY subject";

$queryt=mysqli_query($con,$sqlt);
$resultst=mysqli_fetch_all($queryt,MYSQLI_ASSOC);
$cnty=1;
$cns=2;
foreach($resultst as $resulty)
{
$pdf->SetX(118);
$pdf->Row(array($cnty,ucwords(strtolower($resulty['subject'])),$resulty['grade']));
$cnty=$cnty+1;
}

}$pdf->Ln(50);
$pdf->SetFont('times', 'B', 12);
$pdf->Cell(25,6,'Declaration.',0,1);
$pdf->SetFont('times', '', 10);
$pdf->MultiCell(185,6,'I, '.$_POST['harrd'].' affirm that the information provided above about me is correct and accurate.',0,1);
 $pdf->SetFont('times', 'B', 13);
 $pdf->Cell(25,6,'_________________',0,1);
 $pdf->Cell(25,4,'Signature/Date',0,0); 


$resh = trim(str_ireplace( array( '\'', '"',',',';','<','>','/','-','_',''), '', $_SESSION['staffid']));
$pdf->Output($resh.'_PrintOut.pdf',"I");
}
function stuname($con,$maxt)
{
      $sql =mysqli_query($con,"SELECT *  FROM  personaldata WHERE applid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
         $_POST["mak"]="photo/avater.png";
         $_POST["maks"]="Nil";
         $_POST['emai']="Nil";
      }else{
        $first = $data['firstname'];
        $las = $data['surname'];
        $sur=$data['lastname'];
        $mak=$data['passport'];
        $maks=$data['acadsession'];
        $_POST['emai']=$data['email'];
        $_POST['pro']=$data['programme'];
        $pro=$data['programme'];
        $name =strtoupper($las." ".$first." ".$sur);
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        $_POST["mak"]= $mak;
        }
}
function pname($con,$maxtt)
{
       $sql =mysqli_query($con,"SELECT *  FROM  parentdetail WHERE applid='$maxtt'");
      $datas = mysqli_fetch_assoc($sql);
      if ($datas=="") {
         $_POST["pd"]=" No Name";
         $_POST["add"]="Nil";
         $_POST['pho']="Nil";
      }else{
        $_POST["pd"]=$datas['title'].'. '.$datas['parentname'];
        $_POST["add"]=$datas['address'];
        $_POST['pho']=$datas['phone'];
        }
}

function studem($con,$maxte)
{
       $sql =mysqli_query($con,"SELECT *  FROM  stafflogin WHERE staffid='$maxte'");
      $datae = mysqli_fetch_assoc($sql);
      if ($datae=="") {
         $_POST["ed"]=" No Email";
      }else{
        $_POST["ed"]=$datae['unemail'];
        }
}
function regd($con){
    $dts=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people JOIN staffprive ON staffprive.staffid=people.staffid WHERE people.status='active' AND staffprive.position='registrar'"));
    if ($dts!='') {
        $fulna=$dts['title'].". ".$dts['firstname']." ".$dts['lastname'];
        $_POST['phons']=$dts['phoneno'];
        $_POST['regname']=$fulna;
    }else{
        $_POST['regname']='No Name';
    }
    $dtms=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM registsig WHERE staffid='".$dts['staffid']."'"));
    if ($dtms!='') {
        $_POST['image']=$dtms['image'];
    }
}
function numberTowords($num){
$ones = array(
1 => "one",
2 => "two",
3 => "three",
4 => "four",
5 => "five",
6 => "six",
7 => "seven",
8 => "eight",
9 => "nine",
10 => "ten",
11 => "eleven",
12 => "twelve",
13 => "thirteen",
14 => "fourteen",
15 => "fifteen",
16 => "sixteen",
17 => "seventeen",
18 => "eighteen",
19 => "nineteen"
);
$tens = array(
1 => "ten",
2 => "twenty",
3 => "thirty",
4 => "forty",
5 => "fifty",
6 => "sixty",
7 => "seventy",
8 => "eighty",
9 => "ninety"
);
$hundreds = array(
"hundred",
"thousand",
"million",
"billion",
"trillion",
"quadrillion"
);
$num = number_format($num,2,".",",");
$num_arr = explode(".",$num);
$wholenum = $num_arr[0];
$decnum = $num_arr[1];
$whole_arr = array_reverse(explode(",",$wholenum));
krsort($whole_arr);
$words = "";
foreach($whole_arr as $key => $i) {
if($i == 0) {
continue;
}
if($i < 20) {
$words .= $ones[intval($i)];
} elseif($i < 100) {
if(substr($i,0,1) == 0 && strlen($i) == 3) {
$words .= $tens[substr($i,1,1)];
if(substr($i,2,1) != 0) {
$words .= " ".$ones[substr($i,2,1)];
}
} else {
$words .= $tens[substr($i,0,1)];
if(substr($i,1,1) != 0) {
$words .= " ".$ones[substr($i,1,1)];
}
}
} else {
// $words .= $ones[substr($i,0,1)]." ".$hundreds[0].' and ';
if(substr($i,1,1) != 0 || substr($i,2,1) != 0) {
$words .= $ones[substr($i,0,1)]." ".$hundreds[0].' and ';
} else {
$words .= $ones[substr($i,0,1)]." ".$hundreds[0];
}
if(substr($i,1,2) < 20 && substr($i,1,1) != 0) {
$words .= " ".$ones[(substr($i,1,2))];
} else {
if(substr($i,1,1) != 0) {
$words .= " ".$tens[substr($i,1,1)];
}
if(substr($i,2,1) != 0) {
$words .= " ".$ones[substr($i,2,1)];
}
}
}
if($key > 0) {
$words .= " ".$hundreds[$key]." ";
}
}
$words .= $unit??'Naira';
if($decnum > 0) {
$words .= " and ";
if($decnum < 20) {
$words .= $ones[intval($decnum)];
} elseif($decnum < 100) {
$words .= $tens[substr($decnum,0,1)];
if(substr($decnum,1,1) != 0) {
$words .= " ".$ones[substr($decnum,1,1)];
}
}
$words .= $subunit??'Kobo';
}
return $words;
}

function numberTowordst($nums){
$ones = array(
1 => "one",
2 => "two",
3 => "three",
4 => "four",
5 => "five",
6 => "six",
7 => "seven",
8 => "eight",
9 => "nine",
10 => "ten",
11 => "eleven",
12 => "twelve",
13 => "thirteen",
14 => "fourteen",
15 => "fifteen",
16 => "sixteen",
17 => "seventeen",
18 => "eighteen",
19 => "nineteen"
);
$tens = array(
1 => "ten",
2 => "twenty",
3 => "thirty",
4 => "forty",
5 => "fifty",
6 => "sixty",
7 => "seventy",
8 => "eighty",
9 => "ninety"
);
$hundreds = array(
"hundred",
"thousand",
"million",
"billion",
"trillion",
"quadrillion"
);
$nums = number_format($nums,2,".",",");
$num_arr = explode(".",$nums);
$wholenum = $num_arr[0];
$decnum = $num_arr[1];
$whole_arr = array_reverse(explode(",",$wholenum));
krsort($whole_arr);
$words = "";
foreach($whole_arr as $key => $i) {
if($i == 0) {
continue;
}
if($i < 20) {
$words .= $ones[intval($i)];
} elseif($i < 100) {
if(substr($i,0,1) == 0 && strlen($i) == 3) {
$words .= $tens[substr($i,1,1)];
if(substr($i,2,1) != 0) {
$words .= " ".$ones[substr($i,2,1)];
}
} else {
$words .= $tens[substr($i,0,1)];
if(substr($i,1,1) != 0) {
$words .= " ".$ones[substr($i,1,1)];
}
}
} else {
// $words .= $ones[substr($i,0,1)]." ".$hundreds[0].' and ';
if(substr($i,1,1) != 0 || substr($i,2,1) != 0) {
$words .= $ones[substr($i,0,1)]." ".$hundreds[0].' and ';
} else {
$words .= $ones[substr($i,0,1)]." ".$hundreds[0];
}
if(substr($i,1,2) < 20 && substr($i,1,1) != 0) {
$words .= " ".$ones[(substr($i,1,2))];
} else {
if(substr($i,1,1) != 0) {
$words .= " ".$tens[substr($i,1,1)];
}
if(substr($i,2,1) != 0) {
$words .= " ".$ones[substr($i,2,1)];
}
}
}
if($key > 0) {
$words .= " ".$hundreds[$key]." ";
}
}
$words .= $unit??'';
if($decnum > 0) {
$words .= " and ";
if($decnum < 20) {
$words .= $ones[intval($decnum)];
} elseif($decnum < 100) {
$words .= $tens[substr($decnum,0,1)];
if(substr($decnum,1,1) != 0) {
$words .= " ".$ones[substr($decnum,1,1)];
}
}
$words .= $subunit??'';
}
return $words;
}


?>