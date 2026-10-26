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
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
$ses=$dat['session'];
$dead=$dat['deadline'];
$newfc=array();
$chp="SELECT * FROM labfefac";
$stmtp = mysqli_query($con,$chp);
$resulterp = mysqli_fetch_all($stmtp,MYSQLI_ASSOC);
foreach($resulterp as $rww)
{
    $newfc[]=$rww['facid'];
}
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
    $this->widths=$w;
}
function SetWidth($w)
{
    $this->widths=$w;
}

function SetAligns($a)
{
    $this->aligns=$a;
}

function Row($data)
{
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    $this->CheckPageBreak($h);
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'J';
        $x=$this->GetX();
        $y=$this->GetY();
        $this->Rect($x,$y,$w,$h);
        $this->MultiCell($w,5,$data[$i],0,$a);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
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
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}
function RotatedImage($file,$x,$y,$w,$h,$angle)
{
    $this->Rotate($angle,$x,$y);
    $this->Image($file,$x,$y,$w,$h);
    $this->Rotate(0);
}
/* ---- Ellipse method (drawn with Bezier curves) ---- */
function Ellipse($x, $y, $rx, $ry, $style = 'D')
{
    if($style=='F')
        $op='f';
    elseif($style=='FD' || $style=='DF')
        $op='B';
    else
        $op='S';
    $lx=4/3*(M_SQRT2-1);
    $this->_out(sprintf('%.2F %.2F m', ($x+$rx)*$this->k, ($this->h-$y)*$this->k));
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
        ($x+$rx)*$this->k, ($this->h-($y-$ry*$lx))*$this->k,
        ($x+$rx*$lx)*$this->k, ($this->h-($y-$ry))*$this->k,
        $x*$this->k, ($this->h-($y-$ry))*$this->k));
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
        ($x-$rx*$lx)*$this->k, ($this->h-($y-$ry))*$this->k,
        ($x-$rx)*$this->k, ($this->h-($y-$ry*$lx))*$this->k,
        ($x-$rx)*$this->k, ($this->h-$y)*$this->k));
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
        ($x-$rx)*$this->k, ($this->h-($y+$ry*$lx))*$this->k,
        ($x-$rx*$lx)*$this->k, ($this->h-($y+$ry))*$this->k,
        $x*$this->k, ($this->h-($y+$ry))*$this->k));
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
        ($x+$rx*$lx)*$this->k, ($this->h-($y+$ry))*$this->k,
        ($x+$rx)*$this->k, ($this->h-($y+$ry*$lx))*$this->k,
        ($x+$rx)*$this->k, ($this->h-$y)*$this->k));
    $this->_out($op);
}

/* ---- Filled triangle helper ---- */
function Tri($x1,$y1,$x2,$y2,$x3,$y3,$style='F')
{
    $op = ($style=='F') ? 'f' : (($style=='FD'||$style=='DF') ? 'B' : 'S');
    $this->_out(sprintf('%.2F %.2F m %.2F %.2F l %.2F %.2F l h %s',
        $x1*$this->k,($this->h-$y1)*$this->k,
        $x2*$this->k,($this->h-$y2)*$this->k,
        $x3*$this->k,($this->h-$y3)*$this->k, $op));
}

/* ---- Filled rotated rectangle (kept for compatibility) ---- */
function FillRotRect($cx, $cy, $length, $thick, $angleDeg, $style='F')
{
    $op = ($style=='F') ? 'f' : 'S';
    $a = $angleDeg * M_PI / 180;
    $cos = cos($a);
    $sin = sin($a);
    $hl = $length / 2;
    $ht = $thick / 2;
    $pts = [
        [-$hl, -$ht], [ $hl, -$ht],
        [ $hl,  $ht], [-$hl,  $ht]
    ];
    $out = '';
    foreach ($pts as $i => $p) {
        $x = $cx + $p[0]*$cos - $p[1]*$sin;
        $y = $cy + $p[0]*$sin + $p[1]*$cos;
        $out .= sprintf('%.2F %.2F %s', $x*$this->k, ($this->h-$y)*$this->k, $i===0 ? 'm' : 'l') . ' ';
    }
    $out .= 'h ' . $op;
    $this->_out($out);
}
}
$pdf=new PDF_MC_Table();
stuname($con,$_SESSION['staffid']);
pname($con,$_SESSION['staffid']);

$dta = new DateTime($dateFromDB);

$daa=$dta->format('jS F, Y');
    
    
    
    
    
$pdf->SetLeftMargin(15);
$pdf->AliasNbPages();
$p=$_POST["mak"]; 
$pdf->SetTitle($orgname);


 $sch=$orgname.",".$schadress;


$date = date("d F Y");

$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
regd($con);
$pdf->SetFont('times','B',14); 
$pdf->Text(28, 30, strtoupper($orgname),'C');
$pdf->SetFont('times','B',10); 
$pdf->Text(60, 36, strtoupper($schadress),'C');
$pdf->SetFont('times','B',11);
$pdf->SetFont('times','B',11); 
$pdf->Text(73,44, '(OFFICE OF THE REGISTRAR)',0,1,'C');
$pdf->Image($logo,90,10,$pdf->GetX()+6, $pdf->GetY()+6);
$pdf->Image($p,148,40,$pdf->GetX()+12, $pdf->GetY()+6);
$pdf->Ln(50);
$pdf->SetFont('times','BU',11);
$pdf->Cell(98,25, 'Dear '.ucwords($_POST['harrd']),0,0,'J');
$pdf->SetFont('times','',11);
$pdf->Cell(95,0,'Date: '.strtoupper($daa),0,1,'J');
$pdf->SetFont('times','',11);
$pdf->Cell(98,0,'Our Ref: REG/ADMS/2026',0,1,'J');
$pdf->SetFont('times','',11); 
$pdf->Ln();
$pdf->SetFont('times','B',9.1);
$pdf->Ln(20);
$pdf->MultiCell(160,4,'PROVISIONAL OFFER OF ADMISSION INTO EDO STATE COLLEGE OF HEALTH SCIENCE AND TECHNOLOGY,  '.$ses.' SESSION ','J');
$pdf->SetFont('times','',12);
$datc=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid= '".$_SESSION['staffid']."' AND purpose='Acceptance Fee'"));
$dats=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM faculty WHERE fid= '".$_POST['fac']."'"));
$pdf->MultiCell(160,6,'With reference to your application for admission into the '.$dats['fname'].' Edo State College of Health Science and Technology, Benin City, I am pleased to inform you that you have been offered provisional admission for the '.$ses.' Academic Session.');
$pdf->SetFont('times','B');
$pdf->MultiCell(160,8,'COURSE OF STUDY: '.$_POST['pro']);
$pdf->SetFont('times','',12);
$pdf->MultiCell(160,6,'You will be expected to abide by the rules and regulation as contained in the students Handbook of information.');
$pdf->SetFont('times','B',8.5);
$pdf->SetFont('times','',12);
$pdf->Ln(5);
$pdf->MultiCell(160,6,'All admitted students are sternly warned to disengage from any groupings or nocturnal associations inimical to the smooth operations of the college.');
$pdf->Ln(5);
$pdf->MultiCell(160,6,'You are required to submit evidence of payment of acceptance fee to the Registrar Office upon collection of the letter of provisional admission.');
$pdf->Ln(5);
$pdf->MultiCell(160,6,"If at any time your admission, clearance and registration, any irregularity is discovered in connection with any of your qualification or other relevant metters, you will be expelled from the college.");
$pdf->Ln(5);
$pdf->MultiCell(160,6,'Please accept my congratulations on behalf of the College.
Yours Faithfully,
');
$pdf->Ln(10);
$pdf->Image('../staff/'.$_POST['image'],$pdf->GetX(), $pdf->GetY(),30,8);
$pdf->Ln();
$pdf->Cell(13,8,ucwords($_POST['regname']),0,1,'L');
$pdf->SetFont('times','',9);
$pdf->Cell(13,8,'Chief Administrative Officer/Functioning Registrar',0,1,'L');

/* ============ FOOTER CONTACT DETAILS ============ */
$pdf->Ln(10);

$colWidth = 60;
$startX = 15;
$iconSize = 12;        // increased from 8
$rowY = $pdf->GetY();

// ---------- Column 1: Location (no background) ----------
$cx = $startX + $iconSize / 2;
$cy = $rowY + $iconSize / 2 - 0.8;

$pdf->SetFillColor(43, 95, 90);
$pdf->Ellipse($cx, $cy - 0.5, 3.0, 3.0, 'F');
$pdf->Tri($cx-2.0, $cy+1.7, $cx+2.0, $cy+1.7, $cx, $cy+5.9, 'F');

// Hollow white centre dot
$pdf->SetFillColor(255, 255, 255);
$pdf->Ellipse($cx, $cy - 0.5, 1.1, 1.1, 'F');

$pdf->SetXY($startX + $iconSize + 3, $rowY);
$pdf->SetFont('times','B',9.5);
$pdf->SetTextColor(43, 95, 90);
$pdf->Cell($colWidth - $iconSize - 3, $iconSize, 'Address', 0, 0, 'L');
$pdf->SetXY($startX + $iconSize + 3, $rowY + 7);
$pdf->SetFont('times','',9);
$pdf->SetTextColor(60, 60, 60);
$pdf->MultiCell($colWidth - $iconSize - 3, 4, 'Dumez Road, Benin City, Edo State.', 0, 'L');

// ---------- Column 2: Phone (PNG, no background) ----------
$startX2 = $startX + $colWidth + 5;

$phoneIconPath = __DIR__ . '/fonts/phone.png';
if (file_exists($phoneIconPath)) {
    $pdf->Image($phoneIconPath, $startX2, $rowY, $iconSize, $iconSize);
} else {
    $pdf->SetTextColor(43, 95, 90);
    $pdf->SetFont('times','B',12);
    $pdf->SetXY($startX2, $rowY);
    $pdf->Cell($iconSize, $iconSize, '?', 0, 0, 'C');
}

$pdf->SetXY($startX2 + $iconSize + 3, $rowY);
$pdf->SetFont('times','B',9.5);
$pdf->SetTextColor(43, 95, 90);
$pdf->Cell($colWidth - $iconSize - 3, $iconSize, 'Phone', 0, 0, 'L');
$pdf->SetXY($startX2 + $iconSize + 3, $rowY + 7);
$pdf->SetFont('times','',9);
$pdf->SetTextColor(60, 60, 60);
$pdf->MultiCell($colWidth - $iconSize - 3, 4, '+2347017865994', 0, 'L');

// ---------- Column 3: Email (no background) ----------
$startX3 = $startX2 + $colWidth + 5;

$envPad = 2.0;
$envW   = $iconSize - ($envPad * 2);
$envH   = $envW * 0.68;
$envX   = $startX3 + $envPad;
$envY   = $rowY + ($iconSize - $envH) / 2;

$pdf->SetDrawColor(43, 95, 90);
$pdf->SetLineWidth(0.6);
$pdf->Rect($envX, $envY, $envW, $envH, 'D');
$pdf->Line($envX, $envY, $envX + $envW/2, $envY + $envH * 0.65);
$pdf->Line($envX + $envW, $envY, $envX + $envW/2, $envY + $envH * 0.65);
$pdf->SetLineWidth(0.2);

$pdf->SetXY($startX3 + $iconSize + 3, $rowY);
$pdf->SetFont('times','B',9.5);
$pdf->SetTextColor(43, 95, 90);
$pdf->Cell($colWidth - $iconSize - 3, $iconSize, 'Email', 0, 0, 'L');
$pdf->SetXY($startX3 + $iconSize + 3, $rowY + 7);
$pdf->SetFont('times','',9);
$pdf->SetTextColor(60, 60, 60);
$pdf->MultiCell($colWidth - $iconSize - 3, 4, "registrar@edocohest.edu.ng\ninfo@edocohest.edu.ng", 0, 'L');

$pdf->SetTextColor(0, 0, 0);
/* ============ END FOOTER ============ */

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
       $sql =mysqli_query($con,"SELECT *  FROM  studdata WHERE admno='".$_SESSION['staffid']."'");
      $datas = mysqli_fetch_assoc($sql);
      if ($datas=="") {
         $_POST["pd"]=" No Name";
         $_POST["add"]="Nil";
         $_POST['pho']="Nil";
      }else{
        $_POST["pd"]=$datas['parentname'];
        $_POST["add"]=$datas['address'];
        $_POST['pho']=$datas['phone'];
        }
}


function regd($con){
    $dts=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people JOIN staffprive ON staffprive.staffid=people.staffid WHERE people.status='active' AND staffprive.position='registrar'"));
    if ($dts!='') {
        $fulna=ucwords($dts['lastname']." ".$dts['othername']."  ".$dts['firstname']);
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
1 => "one",2 => "two",3 => "three",4 => "four",5 => "five",
6 => "six",7 => "seven",8 => "eight",9 => "nine",10 => "ten",
11 => "eleven",12 => "twelve",13 => "thirteen",14 => "fourteen",15 => "fifteen",
16 => "sixteen",17 => "seventeen",18 => "eighteen",19 => "nineteen"
);
$tens = array(1 => "ten",2 => "twenty",3 => "thirty",4 => "forty",5 => "fifty",6 => "sixty",7 => "seventy",8 => "eighty",9 => "ninety");
$hundreds = array("hundred","thousand","million","billion","trillion","quadrillion");
$num = number_format($num,2,".",",");
$num_arr = explode(".",$num);
$wholenum = $num_arr[0];
$decnum = $num_arr[1];
$whole_arr = array_reverse(explode(",",$wholenum));
krsort($whole_arr);
$words = "";
foreach($whole_arr as $key => $i) {
if($i == 0) { continue; }
if($i < 20) { $words .= $ones[intval($i)]; }
elseif($i < 100) {
if(substr($i,0,1) == 0 && strlen($i) == 3) {
$words .= $tens[substr($i,1,1)];
if(substr($i,2,1) != 0) { $words .= " ".$ones[substr($i,2,1)]; }
} else {
$words .= $tens[substr($i,0,1)];
if(substr($i,1,1) != 0) { $words .= " ".$ones[substr($i,1,1)]; }
}
} else {
if(substr($i,1,1) != 0 || substr($i,2,1) != 0) { $words .= $ones[substr($i,0,1)]." ".$hundreds[0].' and '; }
else { $words .= $ones[substr($i,0,1)]." ".$hundreds[0]; }
if(substr($i,1,2) < 20 && substr($i,1,1) != 0) { $words .= " ".$ones[(substr($i,1,2))]; }
else {
if(substr($i,1,1) != 0) { $words .= " ".$tens[substr($i,1,1)]; }
if(substr($i,2,1) != 0) { $words .= " ".$ones[substr($i,2,1)]; }
}
}
if($key > 0) { $words .= " ".$hundreds[$key]." "; }
}
$words .= $unit??'Naira';
if($decnum > 0) {
$words .= " and ";
if($decnum < 20) { $words .= $ones[intval($decnum)]; }
elseif($decnum < 100) {
$words .= $tens[substr($decnum,0,1)];
if(substr($decnum,1,1) != 0) { $words .= " ".$ones[substr($decnum,1,1)]; }
}
$words .= $subunit??'Kobo';
}
return $words;
}

function numberTowordst($nums){
$ones = array(1 => "one",2 => "two",3 => "three",4 => "four",5 => "five",6 => "six",7 => "seven",8 => "eight",9 => "nine",10 => "ten",11 => "eleven",12 => "twelve",13 => "thirteen",14 => "fourteen",15 => "fifteen",16 => "sixteen",17 => "seventeen",18 => "eighteen",19 => "nineteen");
$tens = array(1 => "ten",2 => "twenty",3 => "thirty",4 => "forty",5 => "fifty",6 => "sixty",7 => "seventy",8 => "eighty",9 => "ninety");
$hundreds = array("hundred","thousand","million","billion","trillion","quadrillion");
$nums = number_format($nums,2,".",",");
$num_arr = explode(".",$nums);
$wholenum = $num_arr[0];
$decnum = $num_arr[1];
$whole_arr = array_reverse(explode(",",$wholenum));
krsort($whole_arr);
$words = "";
foreach($whole_arr as $key => $i) {
if($i == 0) { continue; }
if($i < 20) { $words .= $ones[intval($i)]; }
elseif($i < 100) {
if(substr($i,0,1) == 0 && strlen($i) == 3) {
$words .= $tens[substr($i,1,1)];
if(substr($i,2,1) != 0) { $words .= " ".$ones[substr($i,2,1)]; }
} else {
$words .= $tens[substr($i,0,1)];
if(substr($i,1,1) != 0) { $words .= " ".$ones[substr($i,1,1)]; }
}
} else {
if(substr($i,1,1) != 0 || substr($i,2,1) != 0) { $words .= $ones[substr($i,0,1)]." ".$hundreds[0].' and '; }
else { $words .= $ones[substr($i,0,1)]." ".$hundreds[0]; }
if(substr($i,1,2) < 20 && substr($i,1,1) != 0) { $words .= " ".$ones[(substr($i,1,2))]; }
else {
if(substr($i,1,1) != 0) { $words .= " ".$tens[substr($i,1,1)]; }
if(substr($i,2,1) != 0) { $words .= " ".$ones[substr($i,2,1)]; }
}
}
if($key > 0) { $words .= " ".$hundreds[$key]." "; }
}
$words .= $unit??'';
if($decnum > 0) {
$words .= " and ";
if($decnum < 20) { $words .= $ones[intval($decnum)]; }
elseif($decnum < 100) {
$words .= $tens[substr($decnum,0,1)];
if(substr($decnum,1,1) != 0) { $words .= " ".$ones[substr($decnum,1,1)]; }
}
$words .= $subunit??'';
}
return $words;
}

?>