<?php
require "pdf/fpdf.php";
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=mysqli_real_escape_string($con, $_REQUEST["stuid"]);
$harr = $_SESSION["staffid"];
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
$docut=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND  purpose='PDE Application' AND status='Success' "));
if ($docut!='') {
    
$pdf->SetLeftMargin(15); // before adding a page
$pdf->AliasNbPages();
$p=$_POST["mak"]; 
$pdf->SetTitle($orgname);


 $sch=$orgname.",".$schadress;


/*For Healt*/
$date = date("d F Y");

$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
regd($con);
$pdf->SetFont('times','B',20);
$pdf->Image($logo,96,3,$pdf->GetX()+6, $pdf->GetY()+6);
$pdf->Text(30, 25, strtoupper($orgname),'C'); 
$pdf->Text(80, 32, strtoupper($schadress),'C');
$pdf->SetFont('times','B',11);
$pdf->SetFont('times','B',11); 
$pdf->Text(73,38, '(OFFICE OF THE REGISTRAR)',0,1,'C');
//$pdf->MultiCell(160,24,$pdf->Image($p,100,22,$pdf->GetX()+5, $pdf->GetY()+17));
$pdf->MultiCell(40,8,$pdf->Text(15,50,'The Director'),$pdf->Text(15,54,'Directorate of Professional Diploma in Education.'),$pdf->Text(15,58,'Our Ref: '.strtoupper($_SESSION['staffid'])));
$pdf->Cell(160,63,$date,0,'C',0);

$pdf->Ln(55);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(98,4,'Dear '. ucwords($_POST['harrd']),0,0,'J');
$pdf->SetFont('Arial','',11);
$pdf->SetFont('Arial','',11); 
$pdf->Ln();
$pdf->SetFont('Arial','BU',13);
$pdf->Ln(10);
$pdf->MultiCell(160,8,'OFFER OF PROVISIONAL ADMISSION INTO PDE PROGRAMME','J');
$pdf->SetFont('Arial','',12);
$datc=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid= '".$_SESSION['staffid']."' AND purpose='PDE Acceptance'"));
$pdf->MultiCell(160,6,'With reference to your application for admission to higher degree program, I have the pleasure in informing you that you have been offered provisional admission to pursue a course leading to the award of    PROFESSIONAL DIPLOMA IN EDUCATION');
$pdf->SetFont('Arial','B');
$pdf->MultiCell(160,6,'As a staff/candidate part time student for '.$ses.' Academic session. ');
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(160,6,'   Your admission is subject to the following conditions:');
//$pdf->SetLeftMargin(27);

$pdf->MultiCell(160,6,'1)  You are required to pay a non-refundable deposit of N20,000:00 (Twenty Thousand Naira) only via the college website www.edocoe.edu.ng');
$pdf->MultiCell(160,6,'2)  You should get the enclosed guarantee form signed by the person who will be responsible for your fees and other payments.');
$pdf->MultiCell(160,6,'3)  If at any time after admission, it is discovered that you do not possess any of the qualifications upon which this offer of admission has been made you will be required to withdraw from the program.');
$pdf->Ln(10);
$pdf->MultiCell(160,6,'Accept my congratulations on this admission.');
$pdf->MultiCell(160,6,'Yours Faithfully,
.');

$pdf->Ln(10);
$pdf->Image('admission.jpg',$pdf->GetX(), $pdf->GetY(),30,6);
$pdf->Ln(6);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(13,6,'Obaraye, Peter Osagie',0,1,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(13,6,'Admission Officer',0,1,'L');

/*Biodata*/

$resh = trim(str_ireplace( array( '\'', '"',',',';','<','>','/','-','_',''), '', $_SESSION['staffid']));
$pdf->Output($resh.'_PrintOut.pdf',"I"); 

 }else{
    header('location:student?id='.$_SESSION['staffid'].'');
 }

}
function stuname($con,$maxt)
{
      $sql =mysqli_query($con,"SELECT *  FROM  studdata WHERE admno='".$_SESSION['staffid']."'");
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
        $name =strtoupper($first).", ".$las." ".$sur;
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
        $_POST["pd"]=$datas['title'].'. '.$datas['parentname'];
        $_POST["add"]=$datas['address'];
        $_POST['pho']=$datas['phone'];
        }
}


function regd($con){
    $dts=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM people JOIN staffprive ON staffprive.staffid=people.staffid WHERE people.status='active' AND staffprive.position='registrar'"));
    if ($dts!='') {
        $fulna=ucwords($dts['title'].". ".$dts['firstname']." ".$dts['lastname']);
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