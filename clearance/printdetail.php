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
$docut=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE (studentid='$matr' OR studentid='$userid') AND `purpose` LIKE '%Acceptance Fee%'"));
if ($docut!='') {
    /*Checking if Student College Belong to Laboratory Paying College*/
    if (in_array($data['faculty'],$newfc))
    {
        $labc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE (studentid='$matr' OR studentid='$userid') AND purpose='Laboratory Fee' "));
        if ($labc!='') {
            $pdf->SetLeftMargin(15); // before adding a page

$pdf->AddPage();
$pdf->AliasNbPages();
$p=$_POST["mak"]; 
$pdf->SetTitle($orgname);
$pdf->Image($logo,10,10,27);
$pdf->SetFont('times','B',17);
$pdf->Text(48, 17, strtoupper($orgname),'C'); 
$pdf->Text(80, 23, ucwords('EDO STATE.'),'C'); 
$pdf->SetFont('times','B',15);
$pdf->Text(60, 29, strtoupper('Office of the Registrar'),'C'); 
$pdf->SetFont('times','B',12);
$pdf->Text(48, 35, strtoupper("STUDENT REGISTRATION CLEARANCE CERTIFICATE"),'C'); 
$pdf->SetFont('times','',11);
$pdf->SetTextColor(194,8,8);
//$pdf->SetFillColor(255, 255, 255);
$pdf->Text(14, 42, "Student to return this Clearance Certificate to the Office of the Registrar once all approvals have been signed for",'J'); 
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(35);
$pdf->SetFont('times', '', 12);
$pdf->Cell(40,6,'', 1, 0);
$pdf->Cell(135,6,"STUDENT'S PROFILE", 1, 1,'C');
$pdf->Cell(40,6,'SURNAME: ', 1, 0);
$pdf->Cell(135,6,strtoupper($surname), 1, 1);
$pdf->Cell(40,6,'FIRST NAME: ', 1, 0);
$pdf->Cell(135,6,strtoupper($firstname), 1, 1);
$pdf->Cell(40,6,'OTHER NAME: ', 1, 0);
$pdf->Cell(135,6,strtoupper($lastname), 1, 1);
$pdf->Cell(40,6,'MATRIC NO: ', 1, 0);
$pdf->Cell(135,6,strtoupper($matr), 1, 1);
$pdf->Ln(3);
$pdf->SetFont('times', '', 12);
//$pdf->SetLeftMargin(15);
$pdf->MultiCell(176,8,'Please sign in the appropriate space below, to confirm compliance with the appropriate requirements for your section.','J');
$pdf->MultiCell(158,8,'This is to confirm that the above-named student has been offered provisional admission into the '.ucwords($_POST['pro']).' Programme in the School of '.($facu).'.','J');

$pdf->Ln(4);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(196,8,'1. This is to confirm that the student has paid the necessary fees.','J');
$pdf->Text(146, 128, "____________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(146, 132, "Bursary",'J');
$pdf->Ln(14);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(196,8,'2. This is to confirm that the student has completed for Library registration.','J');
$pdf->Text(146, 148, "____________________",'J'); 
$pdf->SetFont('times', 'B',12);
$pdf->Text(146, 152.5, "College Librarian",'J'); 
$pdf->Ln(10);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(135,8,'3. Confirmation that s/he has complied with the requirements for registration in the School of '.$facu.'.','J');
$pdf->Text(146, 174, "____________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(146, 178, "School Officer",'J');
$pdf->Ln(6);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(135,8,'4. Confirmation that s/he has complied with the requirements for registration in '.ucwords($_POST['pro']).'.','J');
$pdf->Text(146, 194, "____________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(146, 198, "Head of Department",'J');
$pdf->Ln(8);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(196,8,'5. Medical examination and registration at the Health Centre completed.','J');
$pdf->Text(146, 214, "____________________",'J'); 
$pdf->SetFont('times', 'B', 11);
$pdf->Text(146, 219, "Director, Health Services",'J');
$pdf->Ln(10);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(190,8,"6. Student data email been captured and matriculation no ".$matr."  email been issued.",'J');
$pdf->Text(155, 230.5, "_________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(165, 235, "ICT",'J'); 
$pdf->Ln(14);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(196,8,"7. Registration completed and file created/uploaded.",'J');
$pdf->Text(146, 253, "____________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(146, 258, "Registrar",'J'); 
 /*New File*/
 $sch=$orgname.",".$schadress;


$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
 $pdf->SetTopMargin(15);
$pdf->Image($logo,20,13,27);
//$pdf->Image($logo,100,5,$pdf->GetX()+15, $pdf->GetY()+15);
$pdf->SetFont('times','B',17);
$pdf->Text(50, 17, strtoupper($orgname),'C');
$pdf->Text(80, 23, strtoupper($schadress),'C');
$pdf->SetFont('times','B',12);
$pdf->Text(73,28, strtoupper('OFFICE OF THE REGISTRAR'),'C'); 
$pdf->Text(75,34, strtoupper('ADMISSION ACCEPTANCE'),'C'); 
$pdf->Text(72,40, 'FORM '.$ses.' ACADEMIC','C');
$pdf->Text(92,45, ' SESSION','C');
$pdf->Ln(45);
$pdf->SetFont('times','B',10);
$pdf->Cell(24,6,'FIRST NAME: ',0,0,'J');
$pdf->SetFont('times','',10);
$pdf->Cell(100,6,strtoupper($firstname),0,1,'J');
$pdf->SetFont('times','B',10);
$pdf->Cell(45,6,'SURNAME (Family Name): ',0,0,'J');
$pdf->SetFont('times','',10);
$pdf->Cell(91,6,strtoupper($surname),0,1,'J');
$pdf->SetFont('times','B',10);
$pdf->cell(26,6,'PROGRAMME: ',0,0,'J');
$pdf->SetFont('times','',10);
$pdf->cell(100,6,$pro,0,1,'J');

$pdf->Ln(5);
$pdf->SetFont('times','',11.5);
$pdf->MultiCell(152,6,'I accept the provisional admission offered to me to pursue an Academic Programme leading to the award of a '.$pro.', with effect from the '.$ses.' Academic Session.','J');
$datc=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid= '".$_SESSION['staffid']."' AND purpose='Acceptance Fee'"));
$pdf->Ln(5);

$pdf->MultiCell(152,6,'I understand that this acceptance letter must be returned accompanied by payment of a non-refundable Acceptance Fee of '.ucwords(numberTowords($datc['amount'])).' (#'.number_format($datc['amount'],2).').','J');
$pdf->Ln(5);
$pdf->MultiCell(152,6,"I understand that, by signing this Acceptance Form, I am obligated to pay the full annual tuition charges as specified in the College Fee Policy. No portion of such fees so paid or outstanding amount will be refunded or cancelled notwithstanding any subsequent absence, withdrawal or dismissal from the College by me.",'J');

$pdf->Ln(5);
$pdf->SetFont('times','B',10);
$pdf->Cell(10,6,'PARENT/GUARDIAN',0,1);
$pdf->SetFont('times','',10);
$pdf->Cell(150,6,'Name: '.$_POST["pd"],0,1,'J');
$pdf->MultiCell(118,8,'Signatured: .......................................... Date:........................','C');
$pdf->Ln(4);
$pdf->SetFont('times','B',10);
$pdf->Cell(8,6,'STUDENT',0,1);
$pdf->SetFont('times','',10);
$pdf->MultiCell(169,10,'Signature: .......................................... Date: .................................','C');
$pdf->Ln(5);
$pdf->SetFont('times','B',10);
$pdf->Cell(10,6,'BURSARY',0,1);
$pdf->SetFont('times','',10);
$pdf->MultiCell(189,10,'Name of Officer: .............................................................................','C');
$pdf->MultiCell(199,10,'Signature: .......................................... Date:..................................','C');
$datat=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid= '".$_SESSION['staffid']."' AND purpose='Acceptance Fee'"));
if ($datat!='') {
$_POST["amon"]=$datat["amount"];
$_POST["refe"]=$datat["paymentreference"];
$_POST["pp"]=$datat["purpose"];
$_POST["sta"]=$datat["status"];
$_POST["payti"]=$datat["paytime"];
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
//$pdf->Image($logo,10,8,32);
 $pdf->SetTopMargin(15);
$pdf->Image($logo,80,20,$pdf->GetX()+10, $pdf->GetY()+15);
$pdf->SetFont('times','B',11);
$pdf->Text(55, 55, strtoupper($orgname),'C'); 
$pdf->Text(80, 60, strtoupper($schadress),'C'); 
$pdf->SetFont('times','BI',11); 
$pdf->Text(70,65, strtoupper('OFFICE OF THE BURSAR'),'C');
$pdf->SetFont('times','B',11);
$pdf->Text(60,70, strtoupper('ACCEPTANCE PAYMENT RECEIPT'),'C');
$pdf->Image($p,154,40,$pdf->GetX()+10, $pdf->GetY()+10);

$pdf->Ln(60);
$pdf->SetFont('times','',12);
$pdf->Cell(45,6,"APPLICATION ID",1,0,'J');
$pdf->Cell(120,6,strtoupper($harr), 1, 1);
$pdf->Cell(45,6,"FULL NAME",1,0,'J');
$pdf->Cell(120,6,ucfirst($fullname), 1, 1);
$pdf->Cell(45,6,"PHONE",1,0,'J');
$pdf->Cell(120,6,$phone, 1, 1);
$pdf->Cell(45,6,"EMAIL",1,0,'J');
$pdf->Cell(120,6,$email, 1, 1);
$pdf->Cell(45,6,"MATRIC NO",1,0,'J');
$pdf->Cell(120,6,($matr), 1, 1);
$pdf->Cell(45,6,"College",1,0,'J');
$pdf->Cell(120,6,ucfirst($facu), 1, 1);
$pdf->Cell(45,6,"DEPARTMENT",1,0,'J');
$pdf->Cell(120,6,ucfirst($def), 1, 1);
$pdf->Cell(45,6,"PROGRAMME",1,0,'J');
$pdf->Cell(120,6,ucfirst($pro), 1, 1);
$pdf->Cell(45,6,"AMOUNT PAID",1,0,'J');
$pdf->Cell(120,6,'#'.number_format($_POST["amon"],2), 1, 1);
$pdf->Cell(45,6,"PAYMENT PURPOSE",1,0,'J');
$pdf->Cell(120,6,$_POST["pp"], 1, 1);
$pdf->Cell(45,6,"REFERENCE",1,0,'J');
$pdf->Cell(120,6,$_POST["refe"], 1, 1);
$pdf->Cell(45,6,"PAYMENT STATUS",1,0,'J');
$pdf->Cell(120,6,$_POST["sta"], 1, 1);
$pdf->Cell(45,6,"PAYMENT DATE",1,0,'J');
$pdf->Cell(120,6,$_POST["payti"], 1, 1);
$pdf->Ln(35);
$pdf->SetFont('times','B',12);
$pdf->Cell(10,6,'BURSARY');
$pdf->Ln(8);
$pdf->SetFont('times','',12);
$pdf->MultiCell(280,6,'Name of Officer: .............................................................................','C');
$pdf->ln();
$pdf->MultiCell(280,6,'Signature: .......................................... Date:........................','C');
}
/*For Official Payment Receipt for Laboratory Fee*/
$datat=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE (studentid='$matr' OR studentid='$userid') AND purpose='Laboratory Fee'"));
if ($datat!='') {
$_POST["amon"]=$datat["amount"];
$_POST["refe"]=$datat["paymentreference"];
$_POST["pp"]=$datat["purpose"];
$_POST["sta"]=$datat["status"];
$_POST["payti"]=$datat["paytime"];
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
//$pdf->Image($logo,10,8,32);
 $pdf->SetTopMargin(15);
$pdf->Image($logo,80,20,$pdf->GetX()+10, $pdf->GetY()+15);
$pdf->SetFont('times','B',11);
$pdf->Text(55, 55, strtoupper($orgname),'C'); 
$pdf->Text(80, 60, strtoupper($schadress),'C');
$pdf->SetFont('times','BI',9); 
$pdf->Text(74,65, strtoupper('OFFICE OF THE BURSAR'),'C');
$pdf->SetFont('times','B',9);
$pdf->Text(60,70, strtoupper('LABORATORY FEE PAYMENT RECEIPT'),'C');
$pdf->Image($p,154,40,$pdf->GetX()+10, $pdf->GetY()+10);

$pdf->Ln(60);
$pdf->SetFont('times','',12);
$pdf->Cell(45,6,"APPLICATION ID",1,0,'J');
$pdf->Cell(120,6,strtoupper($harr), 1, 1);
$pdf->Cell(45,6,"FULL NAME",1,0,'J');
$pdf->Cell(120,6,ucfirst($fullname), 1, 1);
$pdf->Cell(45,6,"PHONE",1,0,'J');
$pdf->Cell(120,6,$phone, 1, 1);
$pdf->Cell(45,6,"EMAIL",1,0,'J');
$pdf->Cell(120,6,$email, 1, 1);
$pdf->Cell(45,6,"MATRIC NO",1,0,'J');
$pdf->Cell(120,6,($matr), 1, 1);
$pdf->Cell(45,6,"College",1,0,'J');
$pdf->Cell(120,6,ucfirst($facu), 1, 1);
$pdf->Cell(45,6,"DEPARTMENT",1,0,'J');
$pdf->Cell(120,6,ucfirst($def), 1, 1);
$pdf->Cell(45,6,"PROGRAMME",1,0,'J');
$pdf->Cell(120,6,ucfirst($pro), 1, 1);
$pdf->Cell(45,6,"AMOUNT PAID",1,0,'J');
$pdf->Cell(120,6,'#'.number_format($_POST["amon"],2), 1, 1);
$pdf->Cell(45,6,"PAYMENT PURPOSE",1,0,'J');
$pdf->Cell(120,6,$_POST["pp"], 1, 1);
$pdf->Cell(45,6,"REFERENCE",1,0,'J');
$pdf->Cell(120,6,$_POST["refe"], 1, 1);
$pdf->Cell(45,6,"PAYMENT STATUS",1,0,'J');
$pdf->Cell(120,6,$_POST["sta"], 1, 1);
$pdf->Cell(45,6,"PAYMENT DATE",1,0,'J');
$pdf->Cell(120,6,$_POST["payti"], 1, 1);
$pdf->Ln(35);
$pdf->SetFont('times','B',12);
$pdf->Cell(10,6,'BURSARY');
$pdf->Ln(8);
$pdf->SetFont('times','',12);
$pdf->MultiCell(280,6,'Name of Officer: .............................................................................','C');
$pdf->ln();
$pdf->MultiCell(280,6,'Signature: .......................................... Date:........................','C');
}else{
    header('location:appsummary');
}
$datapk=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE (studentid='$matr' OR studentid='$userid') AND purpose='Tuition Fee' "));
if($datapk!=""){
    $datak=mysqli_query($con, "SELECT * FROM studentpayment WHERE (studentid='$matr' OR studentid='$userid') AND purpose='Tuition Fee' ");
    $sadap=mysqli_fetch_all($datak,MYSQLI_ASSOC);
    foreach($sadap as $datap)
    {
$_POST["amon"]=$datap["amount"];
$_POST["refe"]=$datap["paymentreference"];
$_POST["pp"]=$datap["purpose"];
$_POST["pt"]=$datap["paytype"];
$_POST["sm"]=$datap["semester"];
$_POST["sl"]=$datap["level"];
$_POST["sta"]=$datap["status"];
$_POST["payti"]=$datap["paytime"];
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
$pdf->Image($logo,20,18,35);
$pdf->SetFont('times','B',12);
$pdf->Text(55, 27, strtoupper($orgname),'C'); 
$pdf->Text(80, 33, strtoupper($schadress),'C'); 
$pdf->SetFont('times','BI',12);
$pdf->Text(70,38, strtoupper('OFFICE OF THE BURSAR'),'C');
$pdf->SetFont('times','B',12);
$pdf->Text(60,43, strtoupper('SCHOOL FEES PAYMENT RECEIPT'),'C');
$pdf->Image($p,140,20,$pdf->GetX()+19, $pdf->GetY()+19);
$pdf->Ln(50);
$pdf->SetFont('times','',12);
$pdf->Cell(45,6,"APPLICATION ID",1,0,'J');
$pdf->Cell(115,6,strtoupper($harr), 1, 1);
$pdf->Cell(45,6,"FULL NAME",1,0,'J');
$pdf->Cell(115,6,ucfirst($fullname), 1, 1);
$pdf->Cell(45,6,"PHONE",1,0,'J');
$pdf->Cell(115,6,$phone, 1, 1);
$pdf->Cell(45,6,"EMAIL",1,0,'J');
$pdf->Cell(115,6,$email, 1, 1);
$pdf->Cell(45,6,"MATRIC NO",1,0,'J');
$pdf->Cell(115,6,($matr), 1, 1);
$pdf->Cell(45,6,"College",1,0,'J');
$pdf->Cell(115,6,ucfirst($facu), 1, 1);
$pdf->Cell(45,6,"DEPARTMENT",1,0,'J');
$pdf->Cell(115,6,ucfirst($def), 1, 1);
$pdf->Cell(45,6,"PROGRAMME",1,0,'J');
$pdf->Cell(115,6,ucfirst($pro), 1, 1);
$pdf->Cell(45,6,"LEVEL",1,0,'J');
$pdf->Cell(115,6,ucfirst($_POST["sl"]), 1, 1);
$pdf->Cell(45,6,"AMOUNT PAID",1,0,'J');
$pdf->Cell(115,6,'#'.number_format($_POST["amon"],2), 1, 1);
$pdf->Cell(45,6,"PAYMENT PURPOSE",1,0,'J');
$pdf->Cell(115,6,$_POST["pp"], 1, 1);
if ($_POST["pt"]=='Full') {
$pdf->Cell(45,6,"PAY TYPE",1,0,'J');
$pdf->Cell(115,6,$_POST["pt"], 1, 1);
}else{
$pdf->Cell(45,6,"PAY TYPE",1,0,'J');
$pdf->Cell(115,6,$_POST["pt"], 1, 1);
$pdf->Cell(45,6,"SEMESTER",1,0,'J');
$pdf->Cell(115,6,$_POST["sm"], 1, 1); 
}

$pdf->Cell(45,6,"REFERENCE",1,0,'J');
$pdf->Cell(115,6,$_POST["refe"], 1, 1);
$pdf->Cell(45,6,"PAYMENT STATUS",1,0,'J');
$pdf->Cell(115,6,$_POST["sta"], 1, 1);
$pdf->Cell(45,6,"PAYMENT DATE",1,0,'J');
$pdf->Cell(115,6,$_POST["payti"], 1, 1);
$pdf->Ln(35);
$pdf->SetFont('times','B',12);
$pdf->Cell(10,6,'BURSARY',0,1);
$pdf->Ln(10);
$pdf->SetFont('times','',12);
$pdf->MultiCell(280,6,'Name of Officer: ............................................................................. ','C');
$pdf->Ln(8);
$pdf->MultiCell(280,6,'Signature: .......................................... Date:........................','C');

}
  }else{
$pdf->Cell(45,6,"",1,0,'J');
}

/*For Healt*/
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
$pdf->Image($logo,20,20,32);
$pdf->SetFont('times','B',15);
$pdf->Text(53, 26, strtoupper($orgname),'C'); 
$pdf->Text(83, 32, strtoupper($schadress),'C'); 
$pdf->SetFont('times','B',14);
$pdf->Text(62, 36, 'HEALTH SERVICES DEPARTMENT','C'); 
$pdf->SetFont('times','B',10.5);
$pdf->Text(52, 45, 'MEDICAL LABORATORY EXAMS/ROUTINE TEST RESULTS','C'); 
$pdf->SetFont('times','B',12);
$pdf->Image($p,160,22,$pdf->GetX()+20, $pdf->GetY()+15);
$pdf->SetLeftMargin(15);
$pdf->Ln(50);
$dateOfBirth =$dop ;
$today = date("Y-m-d");
$diff = date_diff(date_create($dateOfBirth), date_create($today));
$y=$diff->format('%y');
$pdf->SetFont('times','',10);
$pdf->Cell(90,6,'LABORATORY NO.: ', 1, 0);
$pdf->Cell(20,6,'AGE: '.$y, 1, 0);
$pdf->Cell(72,6,'SEX: '.$sex, 1, 1);
$pdf->Cell(110,6,"PATIENT'S NAME: ".$_POST["harrd"], 1, 0);
$pdf->Cell(72,6,'MATRIC NO: '.$matr, 1, 1);
$pdf->Cell(76,6,'College: '.$facu, 1, 0);
$pdf->Cell(106,6,'DEPARTMENT: '.$def, 1, 1);
$pdf->Cell(88,6,'SPECIMEN (S): ', 1, 0);
$pdf->Cell(94,6,'CONSULTANT: ', 1, 1);
$pdf->Cell(30,6,'DATE : ', 1, 0);
$pdf->Cell(36,6,'TIME : ', 1, 0);
$pdf->Cell(116,6,'DOCTOR NAME : ', 1, 1);
$pdf->Cell(182,6,'INVESTIGATION: ', 1, 1);
$pdf->Ln(6);
$pdf->SetFont('times','B',12);

$pdf->Cell(60,6, "Height:........................... Weight:...........................",0,1,'J');
//$pdf->Cell(60,6, "Weight:..............................................................",0,1,'J');
$pdf->Cell(60,6, "BMI:...................................................................",0,1,'J');
$pdf->Cell(60,6, "Genotype:..............................................................",0,1,'J');
$pdf->Cell(60,6, "Blood Group:...........................................................",0,1,'J');
$pdf->Cell(60,6, "Pregnancy Test:........................................................",0,1,'J');
$pdf->Cell(60,6, "PCV:...............(40-54)%     Hb.........(13-18)g/dl",0,1,'J');
$pdf->Cell(60,6, "Glucose:(/R/F)...............(        )................mmol/l",0,1,'J');
$pdf->Cell(60,6, "H. Pytori :.........................mm",0,1,'J');
$pdf->Cell(60,6, "MP:....................................................................",0,1,'J');
$pdf->Cell(60,6, "Hapatitis:....................................................................",0,1,'J');
$pdf->Cell(60,6, "RVS:....................................................................",0,1,'J');
$pdf->Cell(60,6, "VDRI:....................................................................",0,1,'J');
$pdf->Cell(60,6, "RBS:.......................................................X10^12/L",0,1,'J');
$pdf->Cell(60,6, "WBC:.......................................................X10^9/L",0,1,'J');
$pdf->SetXY(120,113);
//$pdf->SetLeftMargin(90);
$pdf->Cell(78,6,'Colour', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'PH', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'SG', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Glucose', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Protein', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Urobilinogen', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Bilirubin', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Leucocyte', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Nitrite', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Ascobic acid', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Ketone', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Blood', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Others', 1, 1);
//$pdf->SetY(-120);
$pdf->ln(10);
$pdf->Cell(78,6,'Urine Microscopic', 0, 1);
$pdf->Cell(46,6,'Epith Cells:...................', 1, 1);
$pdf->Cell(23,6,'Pus Cell', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'C' );
$pdf->Cell(23,6,'Pus Cell', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'C' );
$pdf->Cell(23,6,'Cast', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'L' );
$pdf->Cell(23,6,'RBC', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'C' );
$pdf->Cell(23,6,'Parasite', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'C' );
$pdf->SetXY(120,200);
$pdf->Cell(78,6,'WIDAL SCREENING TEST', 0, 1);
$pdf->SetX(120);
$pdf->Cell(46,6,'Widal Screening Test', 1, 0);
$pdf->Cell(16,6,'O', 1, 0,'C');
$pdf->Cell(16,6,'H', 1,1,'C' );$pdf->SetX(120);
$pdf->Cell(46,6,'Salm. Typhy', 1, 0);
$pdf->Cell(16,6,'', 1, 0,'C');
$pdf->Cell(16,6,'', 1,1,'C' );$pdf->SetX(120);
$pdf->Cell(46,6,'Salm. Paratyphi A', 1, 0);
$pdf->Cell(16,6,'', 1, 0,'C');
$pdf->Cell(16,6,'', 1,1,'C' );
$pdf->SetX(120);
$pdf->Cell(46,6,'Salm. Paratyphi B', 1, 0);
$pdf->Cell(16,6,'', 1, 0,'C');
$pdf->Cell(16,6,'', 1,1,'C' );
$pdf->SetX(120);
$pdf->Cell(46,6,'Salm. Paratyphi C', 1, 0);
$pdf->Cell(16,6,'', 1, 0,'C');
$pdf->Cell(16,6,'', 1,1,'C' );
$pdf->SetX(120);
$pdf->Cell(78,6,'Comment: ', 1, 0);

$pdf->Ln(25); 
 
$pdf->Text(25,255,'Sign:_______________Date:___________'); 
$pdf->Text(120,255,'Sign:_____________ Date:___________'); 
$pdf->SetFont('times','B',10);
$pdf->Text(39,260,'Consultant'); 
$pdf->Text(151,260,'Med. Lab. Scientist');

$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
regd($con);
$pdf->SetFont('times','B',20); 
$pdf->Text(30, 25, strtoupper($orgname),'C'); 
$pdf->Text(80, 32, strtoupper($schadress),'C');
$pdf->SetFont('times','B',11);
$pdf->SetFont('times','B',11); 
$pdf->Text(73,38, '(OFFICE OF THE REGISTRAR)',0,1,'C');
//$pdf->MultiCell(160,24,$pdf->Image($p,100,22,$pdf->GetX()+5, $pdf->GetY()+17));
$pdf->MultiCell(40,8,$pdf->Text(25,50,'Tel.: '.$_POST['phons'],'J'),$pdf->Text(25,54,'Website: https://www.edocoe.edu.ng'),$pdf->Text(25,58,'Email: registrar@edocoe.edu.ng,'),$pdf->Text(25,64,' admission@edocoe.edu.ng'));
$pdf->Image($logo,96,43,$pdf->GetX()+6, $pdf->GetY()+6);
$pdf->Image($p,148,43,$pdf->GetX()+12, $pdf->GetY()+6);
$pdf->Ln(55);
$pdf->SetFont('times','BU',11);
$pdf->Cell(98,4, ucwords($_POST['harrd']),0,0,'J');
$pdf->SetFont('times','',11);
$pdf->Cell(55,4,'MATRIC NO: '.strtoupper($matr),0,1,'J');
$pdf->SetFont('times','',11); 
$pdf->Ln();
$pdf->Cell(25, 4,'Dear '.ucwords($_POST['harrd']),'J');
$pdf->SetFont('times','B',9.1);
$pdf->Ln(10);
$pdf->MultiCell(160,8,'OFFER OF PROVISIONAL ADMISSION INTO NCE PROGRAMME ( '.$ses.' ACADEMIC SESSION )','J');
$pdf->SetFont('times','',10);
$datc=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid= '".$_SESSION['staffid']."' AND purpose='Acceptance Fee'"));
$pdf->MultiCell(160,6,'With reference to your application for admission into the NCE Programme of the College, I am pleased to inform you that you have been offered provisional admission to pursue an Academic Programme leading to the award of '.ucwords($_POST['pro']).', with effect from '.$ses.' Session.');
$pdf->SetFont('times','B');
$pdf->MultiCell(160,6,'Please take note of the following conditions, which are related to your admission and registration:');
$pdf->SetFont('times','',10);
$pdf->MultiCell(160,6,'1)   This offer of admission is strictly provisional and may be revoked if');
//$pdf->SetLeftMargin(27);
$pdf->SetFont('times','B',8.5);
$pdf->SetX(25);
$pdf->MultiCell(160,6,'     a)  You fail to formally accept this offer by paying the Acceptance Fee of #'.number_format($datc['amount'],2).' ('.ucwords(numberTowords($datc['amount'])).').');
$pdf->SetX(28);
$pdf->MultiCell(160,6,' b)  You cannot produce at the time of registration, the original copies of your certificates');
$pdf->SetFont('times','',10);

$pdf->MultiCell(160,6,'3)   The attached information on fees payable is for your further action.');
$pdf->MultiCell(160,6,'4)   Payment of all fees must be made not later than '.$_POST['termb'].' as provisional offer of admission will lapse thereafter.');
$pdf->MultiCell(160,6,"5)   It is mandatory for you to attend the Student's Orientation Programme and the Matriculation ceremony for fresh students.");
$pdf->MultiCell(160,6,'6)   Please note that if it is discovered at any time that you do not possess any of the qualifications which you claim to have obtained, the admission will be withdrawn immediately.');
$pdf->MultiCell(160,6,'Please accept my congratulations on behalf of the College.
Yours Faithfully,
');
$pdf->Ln(10);
$pdf->Image('../staff/'.$_POST['image'],$pdf->GetX(), $pdf->GetY(),30,8);
$pdf->Ln();
$pdf->Cell(13,8,ucwords($_POST['regname']),0,1,'L');
$pdf->SetFont('times','',9);
$pdf->Cell(13,8,'Registrar',0,1,'L');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
$pdf->Image($logo,90,20,$pdf->GetX()+10, $pdf->GetY()+15);
$pdf->SetFont('times','B',10);
$pdf->Text(72,55, strtoupper('DOCUMENTS TO BE SUBMITTED'),'C');
$pdf->Text(52,60, strtoupper('FOR THE '.$admy.' ADMISSION REGISTRATION PROCESS'),'C');
$pdf->Ln(55);
$pdf->SetFont('times','B',12);
$pdf->MultiCell(191,6,"1. WAEC OR NECO RESULT",'J');
$pdf->MultiCell(191,6,"2. LOCAL GOVERNMENT IDENTIFICATION",'J');
$pdf->MultiCell(191,6,"3. BIRTH CERTIFICATE OR AGE DECLARATION",'J');

/*Biodata*/
$pdf->AddPage();
$p=$_POST["mak"]; 
$pdf->SetTitle($orgname);
$pdf->Image($logo,20,20,30);
$pdf->SetFont('times','B',15);
//$pdf->Text(50, 26, strtoupper($orgname),'C'); 
//$pdf->Text(90, 32, strtoupper($schadress),'C'); 
$pdf->SetFont('times','B',14);
$pdf->Text(70, 37, 'Website: https://edocohest.edu.ng','C'); 
$pdf->SetFont('times','B',12);
$pdf->Text(76, 42, strtoupper('CANDIDATE BIO DATA'),'C');
$pdf->Image($p,158,22,$pdf->GetX()+17, $pdf->GetY()+17);
$pdf->SetLeftMargin(15);
$pdf->SetFont('times','B',12);
$pdf->Text(15, 62, "APPLICANT'S PERSONAL DETAILS",'J');
$pdf->Ln(50);
$pdf->SetFont('times','',10);
$pdf->Cell(176,6,'Full Name: '.$_POST["harrd"], 1, 1);
$pdf->Cell(83,6,'MATRIC NO: '.$matr, 1, 0);
$pdf->Cell(93,6,'ADMISSION ID: '.$_SESSION['staffid'], 1, 1);
$pdf->Cell(40,6,'Gender: '.$sex, 1, 0);
$pdf->Cell(50,6,'Date of Birth: '.$dop, 1, 0);
$pdf->Cell(86,6,'Email: '.$_POST['emai'], 1, 1);
$pdf->MultiCell(176,6,'Residential Address: '.$address, 1, 1);
$pdf->Cell(50,6,'Nationality: '.$nal, 1, 0);
if($nal=="Foreign"){

$pdf->Cell(126,6,'Country: '.$statec, 1, 1);
}
else{
   $pdf->Cell(60,6,'State: '.$state, 1, 0);
   $pdf->Cell(66,6,'LGA: '.$lga, 1, 1);
}
$pdf->Cell(176,6,'Phone: '.$phone, 1,1);
//$pdf->Cell(123,6,'University Email: '.$_POST["ed"], 1, 1);
$pdf->Cell(83,6,'College Of: '.$facu, 1, 0);
$pdf->Cell(93,6,'Programme: '.$pro, 1, 1);
/*Parent Detail*/
$pdf->SetFont('times','B',10);
$pdf->Cell(176,6,'PARENT/GUARDIAN CONTACT DETAILS', 1, 1);
$pdf->SetFont('times','',10);
$pdf->Cell(106,6,'Parent Name: '.$_POST["pd"], 1, 0);
$pdf->Cell(70,6,'Parent Phone: '.$phn, 1, 1);
$pdf->MultiCell(176,6,'Residential Address: '.$pad, 1, 1);

$pdf->Ln(2);
$pdf->SetFont('times','B',12);
$pdf->Cell(125, 8, "CANDIDATE'S EDUCATIONAL DETAILS",0,1,'J');

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
$sql = "SELECT * FROM olevelresultdetail WHERE (applid='".$_SESSION['staffid']."') AND examno='".$frs['examno']."' ORDER BY subject";

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
$pdf->SetXY(118,165);
$pdf->Cell(82,6,'Second Sitting', 0, 1);
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

 $sqlt = "SELECT * FROM olevelresultdetail WHERE (applid='".$_SESSION['staffid']."') AND examno='".$frsc['examno']."' ORDER BY subject";

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

}
$pdf->Ln(5);
$pdf->SetFont('times', 'B', 12);
$pdf->Cell(25,6,'Declaration.',0,1);
$pdf->SetFont('times', '', 10);
$pdf->MultiCell(185,6,'I, '.$_POST['harrd'].' affirm that the information provided above about me is correct and accurate.',0,1);
 $pdf->SetFont('times', 'B', 13);
 $pdf->Cell(25,6,'_________________',0,1);
 $pdf->Cell(25,4,'Signature/Date',0,0); 
        }
     }else{
$pdf->SetLeftMargin(15); // before adding a page
$pdf->AddPage();
$pdf->AliasNbPages();
$p=$_POST["mak"]; 
$pdf->SetTitle($orgname);
$pdf->Image($logo,10,5,27);
$pdf->SetFont('times','B',14);
$pdf->Text(40, 17, strtoupper($orgname),'C'); 
$pdf->SetFont('times','B',15);
$pdf->Text(60, 25, strtoupper('Office of the Registrar'),'C'); 
$pdf->SetFont('times','B',12);
$pdf->Text(48, 31, strtoupper("STUDENT REGISTRATION CLEARANCE CERTIFICATE"),'C'); 
$pdf->SetFont('times','',11);
$pdf->SetTextColor(194,8,8);
//$pdf->SetFillColor(255, 255, 255);
$pdf->Text(14, 42, "Student to return this Clearance Certificate to the Office of the Registrar once all approvals have been signed for",'J'); 
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(35);
$pdf->SetFont('times', '', 12);
$pdf->Cell(40,6,'', 1, 0);
$pdf->Cell(135,6,"STUDENT'S DETAILS", 1, 1,'C');
$pdf->Cell(40,6,'SURNAME: ', 1, 0);
$pdf->Cell(135,6,strtoupper($surname), 1, 1);
$pdf->Cell(40,6,'FIRST NAME: ', 1, 0);
$pdf->Cell(135,6,strtoupper($firstname), 1, 1);
$pdf->Cell(40,6,'OTHER NAME: ', 1, 0);
$pdf->Cell(135,6,strtoupper($lastname), 1, 1);
$pdf->Cell(40,6,'MATRIC NO: ', 1, 0);
$pdf->Cell(135,6,strtoupper($matr), 1, 1);
$pdf->Ln(3);
$pdf->SetFont('times', '', 12);
//$pdf->SetLeftMargin(15);
$pdf->MultiCell(176,8,'Please sign in the appropriate space below, to confirm compliance with the appropriate requirements for your section.','J');
$pdf->MultiCell(158,8,'This is to confirm that the above-named student has been offered provisional admission into the '.ucwords($_POST['pro']).' Programme in the School of '.($facu).'.','J');

$pdf->Ln(4);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(196,8,'1. This is to confirm that the student has paid the necessary fees.','J');
$pdf->Text(146, 128, "____________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(146, 132, "Bursary",'J');
$pdf->Ln(14);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(196,8,'2. This is to confirm that the student has completed for Library registration.','J');
$pdf->Text(146, 148, "____________________",'J'); 
$pdf->SetFont('times', 'B',12);
$pdf->Text(146, 152.5, "College Librarian",'J'); 
$pdf->Ln(10);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(135,8,'3. Confirmation that s/he has complied with the requirements for registration in the School of '.$facu.'.','J');
$pdf->Text(146, 174, "____________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(146, 178, "School Officer",'J');
$pdf->Ln(6);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(135,8,'4. Confirmation that s/he has complied with the requirements for registration in '.ucwords($_POST['pro']).'.','J');
$pdf->Text(146, 194, "____________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(146, 198, "Head of Department",'J');
$pdf->Ln(8);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(196,8,'5. Medical examination and registration at the Health Centre completed.','J');
$pdf->Text(146, 214, "____________________",'J'); 
$pdf->SetFont('times', 'B', 11);
$pdf->Text(146, 219, "Director, Health Services",'J');
$pdf->Ln(10);
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(190,8,"6. Student data email been captured and matriculation no ".$matr."  email been issued.",'J');
$pdf->Text(155, 230.5, "_________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(165, 235, "ICT",'J'); 
$pdf->Ln(14);
$pdf->SetFont('times', '', 12);
$pdf->MultiCell(196,8,"7. Registration completed and file created/uploaded.",'J');
$pdf->Text(146, 253, "____________________",'J'); 
$pdf->SetFont('times', 'B', 12);
$pdf->Text(146, 258, "Registrar",'J'); 
 /*New File*/
 $sch=$orgname.",".$schadress;


$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
 $pdf->SetTopMargin(15);
$pdf->Image($logo,20,9,27);
//$pdf->Image($logo,100,5,$pdf->GetX()+15, $pdf->GetY()+15);
$pdf->SetFont('times','B',14);
$pdf->Text(50, 17, strtoupper($orgname),'C');
$pdf->Text(60, 23, strtoupper($schadress),'C');
$pdf->SetFont('times','B',12);
$pdf->Text(73,28, strtoupper('OFFICE OF THE REGISTRAR'),'C'); 
$pdf->Text(75,34, strtoupper('ADMISSION ACCEPTANCE'),'C'); 
$pdf->Text(72,40, 'FORM '.$ses.' ACADEMIC','C');
$pdf->Text(92,45, ' SESSION','C');
$pdf->Ln(45);
$pdf->SetFont('times','B',10);
$pdf->Cell(24,6,'FIRST NAME: ',0,0,'J');
$pdf->SetFont('times','',10);
$pdf->Cell(100,6,strtoupper($firstname),0,1,'J');
$pdf->SetFont('times','B',10);
$pdf->Cell(45,6,'SURNAME (Family Name): ',0,0,'J');
$pdf->SetFont('times','',10);
$pdf->Cell(91,6,strtoupper($surname),0,1,'J');
$pdf->SetFont('times','B',10);
$pdf->cell(26,6,'PROGRAMME: ',0,0,'J');
$pdf->SetFont('times','',10);
$pdf->cell(100,6,$pro,0,1,'J');

$pdf->Ln(5);
$pdf->SetFont('times','',11.5);
$pdf->MultiCell(152,6,'I accept the provisional admission offered to me to pursue an Academic Programme leading to the award of a '.$pro.', with effect from the '.$ses.' Academic Session.','J');
$datc=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid= '".$_SESSION['staffid']."' AND purpose='Acceptance Fee'"));
$pdf->Ln(5);

$pdf->MultiCell(152,6,'I understand that this acceptance letter must be returned accompanied by payment of a non-refundable Acceptance Fee of '.ucwords(numberTowords($datc['amount'])).' (#'.number_format($datc['amount'],2).').','J');
$pdf->Ln(5);
$pdf->MultiCell(152,6,"I understand that, by signing this Acceptance Form, I am obligated to pay the full annual tuition charges as specified in the University's Fee Policy. No portion of such fees so paid or outstanding amount will be refunded or cancelled notwithstanding any subsequent absence, withdrawal or dismissal from the College by me.",'J');

$pdf->Ln(5);
$pdf->SetFont('times','B',10);
$pdf->Cell(10,6,'PARENT/GUARDIAN',0,1);
$pdf->SetFont('times','',10);
$pdf->Cell(150,6,'Name: '.$_POST["pd"],0,1,'J');
$pdf->MultiCell(118,8,'Signatured: .......................................... Date:........................','C');
$pdf->Ln(4);
$pdf->SetFont('times','B',10);
$pdf->Cell(8,6,'STUDENT',0,1);
$pdf->SetFont('times','',10);
$pdf->MultiCell(169,10,'Signature: .......................................... Date: .................................','C');
$pdf->Ln(5);
$pdf->SetFont('times','B',10);
$pdf->Cell(10,6,'BURSARY',0,1);
$pdf->SetFont('times','',10);
$pdf->MultiCell(189,10,'Name of Officer: .............................................................................','C');
$pdf->MultiCell(199,10,'Signature: .......................................... Date:..................................','C');
$datat=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid= '".$_SESSION['staffid']."' AND purpose='Acceptance Fee'"));
if ($datat!='') {
$_POST["amon"]=$datat["amount"];
$_POST["refe"]=$datat["paymentreference"];
$_POST["pp"]=$datat["purpose"];
$_POST["sta"]=$datat["status"];
$_POST["payti"]=$datat["paytime"];
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
//$pdf->Image($logo,10,8,32);
 $pdf->SetTopMargin(15);
$pdf->Image($logo,75,15,38);
$pdf->SetFont('times','B',11);
//$pdf->Text(55, 55, strtoupper($orgname),'C'); 
//$pdf->Text(80, 60, strtoupper($schadress),'C'); 
$pdf->SetFont('times','BI',11); 
$pdf->Text(70,65, strtoupper('OFFICE OF THE BURSAR'),'C');
$pdf->SetFont('times','B',11);
$pdf->Text(60,70, strtoupper('ACCEPTANCE PAYMENT RECEIPT'),'C');
//$pdf->Image($p,154,40,$pdf->GetX()+10, $pdf->GetY()+10);

$pdf->Ln(60);
$pdf->SetFont('times','',12);
$pdf->Cell(45,6,"APPLICATION ID",1,0,'J');
$pdf->Cell(120,6,strtoupper($harr), 1, 1);
$pdf->Cell(45,6,"FULL NAME",1,0,'J');
$pdf->Cell(120,6,ucfirst($fullname), 1, 1);
$pdf->Cell(45,6,"PHONE",1,0,'J');
$pdf->Cell(120,6,$phone, 1, 1);
$pdf->Cell(45,6,"EMAIL",1,0,'J');
$pdf->Cell(120,6,$email, 1, 1);
$pdf->Cell(45,6,"MATRIC NO",1,0,'J');
$pdf->Cell(120,6,($matr), 1, 1);
$pdf->Cell(45,6,"College",1,0,'J');
$pdf->Cell(120,6,ucfirst($facu), 1, 1);
$pdf->Cell(45,6,"DEPARTMENT",1,0,'J');
$pdf->Cell(120,6,ucfirst($def), 1, 1);
$pdf->Cell(45,6,"PROGRAMME",1,0,'J');
$pdf->Cell(120,6,ucfirst($pro), 1, 1);
$pdf->Cell(45,6,"AMOUNT PAID",1,0,'J');
$pdf->Cell(120,6,'#'.number_format($_POST["amon"],2), 1, 1);
$pdf->Cell(45,6,"PAYMENT PURPOSE",1,0,'J');
$pdf->Cell(120,6,$_POST["pp"], 1, 1);
$pdf->Cell(45,6,"REFERENCE",1,0,'J');
$pdf->Cell(120,6,$_POST["refe"], 1, 1);
$pdf->Cell(45,6,"PAYMENT STATUS",1,0,'J');
$pdf->Cell(120,6,$_POST["sta"], 1, 1);
$pdf->Cell(45,6,"PAYMENT DATE",1,0,'J');
$pdf->Cell(120,6,$_POST["payti"], 1, 1);
$pdf->Ln(35);
$pdf->SetFont('times','B',12);
$pdf->Cell(10,6,'BURSARY');
$pdf->Ln(8);
$pdf->SetFont('times','',12);
$pdf->MultiCell(280,6,'Name of Officer: .............................................................................','C');
$pdf->ln();
$pdf->MultiCell(280,6,'Signature: .......................................... Date:........................','C');
}

$datapk=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE (studentid='$matr' OR studentid='$userid') AND (purpose='ND Tuition Fee' OR purpose='CERT Tuition Fee') "));
if($datapk!=""){
    $datak=mysqli_query($con, "SELECT * FROM studentpayment WHERE (studentid='$matr' OR studentid='$userid') AND (purpose='ND Tuition Fee' OR purpose='CERT Tuition Fee') ");
    $sadap=mysqli_fetch_all($datak,MYSQLI_ASSOC);
    foreach($sadap as $datap)
    {
$_POST["amon"]=$datap["amount"];
$_POST["refe"]=$datap["paymentreference"];
$_POST["pp"]=$datap["purpose"];
$_POST["pt"]=$datap["paytype"];
$_POST["sm"]=$datap["semester"];
$_POST["sl"]=$datap["level"];
$_POST["sta"]=$datap["status"];
$_POST["payti"]=$datap["paytime"];
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
$pdf->Image($logo,75,15,38);
$pdf->SetFont('times','B',12);
//$pdf->Text(55, 27, strtoupper($orgname),'C'); 
//$pdf->Text(80, 33, strtoupper($schadress),'C'); 
$pdf->SetFont('times','BI',12);
$pdf->Text(70,65, strtoupper('OFFICE OF THE BURSAR'),'C');
$pdf->SetFont('times','B',12);
$pdf->Text(60,71, strtoupper('SCHOOL FEES PAYMENT RECEIPT'),'C');
//$pdf->Image($p,140,20,$pdf->GetX()+19, $pdf->GetY()+19);
$pdf->Ln(62);
$pdf->SetFont('times','',12);
$pdf->Cell(45,6,"APPLICATION ID",1,0,'J');
$pdf->Cell(115,6,strtoupper($harr), 1, 1);
$pdf->Cell(45,6,"FULL NAME",1,0,'J');
$pdf->Cell(115,6,ucfirst($fullname), 1, 1);
$pdf->Cell(45,6,"PHONE",1,0,'J');
$pdf->Cell(115,6,$phone, 1, 1);
$pdf->Cell(45,6,"EMAIL",1,0,'J');
$pdf->Cell(115,6,$email, 1, 1);
$pdf->Cell(45,6,"MATRIC NO",1,0,'J');
$pdf->Cell(115,6,($matr), 1, 1);
$pdf->Cell(45,6,"College",1,0,'J');
$pdf->Cell(115,6,ucfirst($facu), 1, 1);
$pdf->Cell(45,6,"DEPARTMENT",1,0,'J');
$pdf->Cell(115,6,ucfirst($def), 1, 1);
$pdf->Cell(45,6,"PROGRAMME",1,0,'J');
$pdf->Cell(115,6,ucfirst($pro), 1, 1);
$pdf->Cell(45,6,"LEVEL",1,0,'J');
$pdf->Cell(115,6,ucfirst($_POST["sl"]), 1, 1);
$pdf->Cell(45,6,"AMOUNT PAID",1,0,'J');
$pdf->Cell(115,6,'#'.number_format($_POST["amon"],2), 1, 1);
$pdf->Cell(45,6,"PAYMENT PURPOSE",1,0,'J');
$pdf->Cell(115,6,$_POST["pp"], 1, 1);
if ($_POST["pt"]=='Full') {
$pdf->Cell(45,6,"PAY TYPE",1,0,'J');
$pdf->Cell(115,6,$_POST["pt"], 1, 1);
}else{
$pdf->Cell(45,6,"PAY TYPE",1,0,'J');
$pdf->Cell(115,6,$_POST["pt"], 1, 1);
$pdf->Cell(45,6,"SEMESTER",1,0,'J');
$pdf->Cell(115,6,$_POST["sm"], 1, 1); 
}

$pdf->Cell(45,6,"REFERENCE",1,0,'J');
$pdf->Cell(115,6,$_POST["refe"], 1, 1);
$pdf->Cell(45,6,"PAYMENT STATUS",1,0,'J');
$pdf->Cell(115,6,$_POST["sta"], 1, 1);
$pdf->Cell(45,6,"PAYMENT DATE",1,0,'J');
$pdf->Cell(115,6,$_POST["payti"], 1, 1);
$pdf->Ln(35);
$pdf->SetFont('times','B',12);
$pdf->Cell(10,6,'BURSARY',0,1);
$pdf->Ln(10);
$pdf->SetFont('times','',12);
$pdf->MultiCell(280,6,'Name of Officer: ............................................................................. ','C');
$pdf->Ln(8);
$pdf->MultiCell(280,6,'Signature: .......................................... Date:........................','C');

}
  }else{
$pdf->Cell(45,6,"",1,0,'J');
}

/*For Healt*/
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
$pdf->Image($logo,20,20,32);
$pdf->SetFont('times','B',15);
//$pdf->Text(53, 26, strtoupper($orgname),'C'); 
//$pdf->Text(83, 32, strtoupper($schadress),'C'); 
$pdf->SetFont('times','B',14);
$pdf->Text(62, 36, 'HEALTH SERVICES DEPARTMENT','C'); 
$pdf->SetFont('times','B',10.5);
$pdf->Text(52, 45, 'MEDICAL LABORATORY EXAMS/ROUTINE TEST RESULTS','C'); 
$pdf->SetFont('times','B',12);
$pdf->SetLeftMargin(15);
$pdf->Ln(50);
$dateOfBirth =$dop ;
$today = date("Y-m-d");
$diff = date_diff(date_create($dateOfBirth), date_create($today));
$y=$diff->format('%y');
$pdf->SetFont('times','',10);
$pdf->Cell(90,6,'LABORATORY NO.: ', 1, 0);
$pdf->Cell(20,6,'AGE: '.$y, 1, 0);
$pdf->Cell(72,6,'SEX: '.$sex, 1, 1);
$pdf->Cell(110,6,"PATIENT'S NAME: ".$_POST["harrd"], 1, 0);
$pdf->Cell(72,6,'MATRIC NO: '.$matr, 1, 1);
$pdf->Cell(76,6,'College: '.$facu, 1, 0);
$pdf->Cell(106,6,'DEPARTMENT: '.$def, 1, 1);
$pdf->Cell(88,6,'SPECIMEN (S): ', 1, 0);
$pdf->Cell(94,6,'CONSULTANT: ', 1, 1);
$pdf->Cell(30,6,'DATE : ', 1, 0);
$pdf->Cell(36,6,'TIME : ', 1, 0);
$pdf->Cell(116,6,'DOCTOR NAME : ', 1, 1);
$pdf->Cell(182,6,'INVESTIGATION: ', 1, 1);
$pdf->Ln(6);
$pdf->SetFont('times','B',12);

$pdf->Cell(60,6, "Height:........................... Weight:...........................",0,1,'J');
//$pdf->Cell(60,6, "Weight:..............................................................",0,1,'J');
$pdf->Cell(60,6, "BMI:...................................................................",0,1,'J');
$pdf->Cell(60,6, "Genotype:..............................................................",0,1,'J');
$pdf->Cell(60,6, "Blood Group:...........................................................",0,1,'J');
$pdf->Cell(60,6, "Pregnancy Test:........................................................",0,1,'J');
$pdf->Cell(60,6, "PCV:...............(40-54)%     Hb.........(13-18)g/dl",0,1,'J');
$pdf->Cell(60,6, "Glucose:(/R/F)...............(        )................mmol/l",0,1,'J');
$pdf->Cell(60,6, "H. Pytori :.........................mm",0,1,'J');
$pdf->Cell(60,6, "MP:....................................................................",0,1,'J');
$pdf->Cell(60,6, "Hapatitis:....................................................................",0,1,'J');
$pdf->Cell(60,6, "RVS:....................................................................",0,1,'J');
$pdf->Cell(60,6, "VDRI:....................................................................",0,1,'J');
$pdf->Cell(60,6, "RBS:.......................................................X10^12/L",0,1,'J');
$pdf->Cell(60,6, "WBC:.......................................................X10^9/L",0,1,'J');
$pdf->SetXY(120,113);
//$pdf->SetLeftMargin(90);
$pdf->Cell(78,6,'Colour', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'PH', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'SG', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Glucose', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Protein', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Urobilinogen', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Bilirubin', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Leucocyte', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Nitrite', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Ascobic acid', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Ketone', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Blood', 1, 1);
$pdf->SetX(120);
$pdf->Cell(78,6,'Others', 1, 1);
//$pdf->SetY(-120);
$pdf->ln(10);
$pdf->Cell(78,6,'Urine Microscopic', 0, 1);
$pdf->Cell(46,6,'Epith Cells:...................', 1, 1);
$pdf->Cell(23,6,'Pus Cell', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'C' );
$pdf->Cell(23,6,'Pus Cell', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'C' );
$pdf->Cell(23,6,'Cast', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'L' );
$pdf->Cell(23,6,'RBC', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'C' );
$pdf->Cell(23,6,'Parasite', 1, 0,'L');
$pdf->Cell(23,6,'', 1,1,'C' );
$pdf->SetXY(120,200);
$pdf->Cell(78,6,'WIDAL SCREENING TEST', 0, 1);
$pdf->SetX(120);
$pdf->Cell(46,6,'Widal Screening Test', 1, 0);
$pdf->Cell(16,6,'O', 1, 0,'C');
$pdf->Cell(16,6,'H', 1,1,'C' );$pdf->SetX(120);
$pdf->Cell(46,6,'Salm. Typhy', 1, 0);
$pdf->Cell(16,6,'', 1, 0,'C');
$pdf->Cell(16,6,'', 1,1,'C' );$pdf->SetX(120);
$pdf->Cell(46,6,'Salm. Paratyphi A', 1, 0);
$pdf->Cell(16,6,'', 1, 0,'C');
$pdf->Cell(16,6,'', 1,1,'C' );
$pdf->SetX(120);
$pdf->Cell(46,6,'Salm. Paratyphi B', 1, 0);
$pdf->Cell(16,6,'', 1, 0,'C');
$pdf->Cell(16,6,'', 1,1,'C' );
$pdf->SetX(120);
$pdf->Cell(46,6,'Salm. Paratyphi C', 1, 0);
$pdf->Cell(16,6,'', 1, 0,'C');
$pdf->Cell(16,6,'', 1,1,'C' );
$pdf->SetX(120);
$pdf->Cell(78,6,'Comment: ', 1, 0);

$pdf->Ln(25); 
 
$pdf->Text(25,255,'Sign:_______________Date:___________'); 
$pdf->Text(120,255,'Sign:_____________ Date:___________'); 
$pdf->SetFont('times','B',10);
$pdf->Text(39,260,'Consultant'); 
$pdf->Text(151,260,'Med. Lab. Scientist');

$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
regd($con);
$pdf->SetFont('times','B',14); 
//$pdf->Text(40, 25, strtoupper($orgname),'C');
$pdf->SetFont('times','B',10); 
//$pdf->Text(60, 32, strtoupper($schadress),'C');
$pdf->SetFont('times','B',11);
$pdf->SetFont('times','B',11); 
$pdf->Text(73,44, '(OFFICE OF THE REGISTRAR)',0,1,'C');
//$pdf->MultiCell(160,24,$pdf->Image($p,100,22,$pdf->GetX()+5, $pdf->GetY()+17));
$pdf->MultiCell(40,8,$pdf->Text(25,50,'Tel.: '.$_POST['phons'],'J'),$pdf->Text(25,54,'Website: https://www.edocohest.edu.ng'),$pdf->Text(25,58,'Email: registrar@edocohest.edu.ng,'),$pdf->Text(25,64,' admission@edocohest.edu.ng'));
$pdf->Image($logo,90,10,$pdf->GetX()+6, $pdf->GetY()+6);
$pdf->Image($p,148,43,$pdf->GetX()+12, $pdf->GetY()+6);
$pdf->Ln(55);
$pdf->SetFont('times','BU',11);
$pdf->Cell(98,4, 'Dear '.ucwords($_POST['harrd']),0,0,'J');
$pdf->SetFont('times','',11);
$pdf->Cell(85,4,'MATRIC NO: '.strtoupper($matr),0,1,'J');
$pdf->SetFont('times','',11); 
$pdf->Ln();
$pdf->SetFont('times','B',9.1);
$pdf->Ln(10);
$pdf->MultiCell(160,8,'PROVISIONAL OFFER OF ADMISSION INTO EDO STATE COLLEGE OF HEALTH SCIENCE AND TECHNOLOGY, ( '.$ses.' ACADEMIC SESSION )','J');
$pdf->SetFont('times','',12);
$datc=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid= '".$_SESSION['staffid']."' AND purpose='Acceptance Fee'"));
$dats=mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM faculty WHERE fid= '".$_POST['fac']."'"));
$pdf->MultiCell(160,6,'With reference to your application for admission into the '.$dats['fname'].', of the Edo State College of Health Science and Technology, Benin City, I am pleased to inform you that you have been offered provisional admission for the '.$ses.' Academic Session.');
$pdf->SetFont('times','B');
$pdf->MultiCell(160,8,'COURSE OF STUDY:');
$pdf->SetFont('times','',12);
$pdf->MultiCell(160,6,'You will be expected to abide by the rules and regulation as contained in the student students Handbook of information.');
//$pdf->SetLeftMargin(27);
$pdf->SetFont('times','B',8.5);
$pdf->SetFont('times','',12);
$pdf->Ln(5);
$pdf->MultiCell(160,6,'All admitted students are stemly warned to disengage from any groups or nocturnal association inimical to the smooth operations of the college.');
$pdf->Ln(5);
$pdf->MultiCell(160,6,'You are required to submit edidence of payment of acceptance fee to the Registrar Office upon collection of the letter of provisional admission.');
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
$pdf->Cell(13,8,'Registrar',0,1,'L');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetTitle($orgname);
$pdf->Image($logo,90,20,$pdf->GetX()+10, $pdf->GetY()+15);
$pdf->SetFont('times','B',10);
$pdf->Text(72,55, strtoupper('DOCUMENTS TO BE SUBMITTED'),'C');
$pdf->Text(52,60, strtoupper('FOR THE '.$admy.' ADMISSION REGISTRATION PROCESS'),'C');
$pdf->Ln(55);
$pdf->SetFont('times','B',12);
$pdf->MultiCell(191,6,"1. WAEC OR NECO RESULT",'J');
$pdf->MultiCell(191,6,"2. LOCAL GOVERNMENT IDENTIFICATION",'J');
$pdf->MultiCell(191,6,"3. BIRTH CERTIFICATE OR AGE DECLARATION",'J');

/*Biodata*/
$pdf->AddPage();
$p=$_POST["mak"]; 
$pdf->SetTitle($orgname);
$pdf->Image($logo,20,20,30);

$pdf->SetFont('times','B',15);
//$pdf->Text(50, 26, strtoupper($orgname),'C'); 
//$pdf->Text(86, 32, strtoupper($schadress),'C'); 
$pdf->SetFont('times','B',14);
$pdf->Text(70, 37, 'Website: https://edocohest.edu.ng','C'); 
$pdf->SetFont('times','B',12);
$pdf->Text(76, 42, strtoupper('CANDIDATE BIO DATA'),'C');
$pdf->Image($p,158,22,$pdf->GetX()+17, $pdf->GetY()+17);
$pdf->SetLeftMargin(15);
$pdf->SetFont('times','B',12);
$pdf->Text(15, 62, "APPLICANT'S PERSONAL DETAILS",'J');
$pdf->Ln(50);
$pdf->SetFont('times','',10);
$pdf->Cell(176,6,'Full Name: '.$_POST["harrd"], 1, 1);
$pdf->Cell(83,6,'MATRIC NO: '.$matr, 1, 0);
$pdf->Cell(93,6,'ADMISSION ID: '.$_SESSION['staffid'], 1, 1);
$pdf->Cell(40,6,'Gender: '.$sex, 1, 0);
$pdf->Cell(50,6,'Date of Birth: '.$dop, 1, 0);
$pdf->Cell(86,6,'Email: '.$_POST['emai'], 1, 1);
$pdf->MultiCell(176,6,'Residential Address: '.$address, 1, 1);
$pdf->Cell(50,6,'Nationality: '.$nal, 1, 0);
if($nal=="Foreign"){

$pdf->Cell(126,6,'Country: '.$statec, 1, 1);
}
else{
   $pdf->Cell(60,6,'State: '.$state, 1, 0);
   $pdf->Cell(66,6,'LGA: '.$lga, 1, 1);
}
$pdf->Cell(176,6,'Phone: '.$phone, 1,1);
//$pdf->Cell(123,6,'University Email: '.$_POST["ed"], 1, 1);
$pdf->Cell(83,6,'College Of: '.$facu, 1, 0);
$pdf->Cell(93,6,'Programme: '.$pro, 1, 1);
/*Parent Detail*/
$pdf->SetFont('times','B',10);
$pdf->Cell(176,6,'PARENT/GUARDIAN CONTACT DETAILS', 1, 1);
$pdf->SetFont('times','',10);
$pdf->Cell(106,6,'Parent Name: '.$_POST["pd"], 1, 0);
$pdf->Cell(70,6,'Parent Phone: '.$phn, 1, 1);
$pdf->MultiCell(176,6,'Residential Address: '.$pad, 1, 1);

$pdf->Ln(2);
$pdf->SetFont('times','B',12);
$pdf->Cell(125, 8, "CANDIDATE'S EDUCATIONAL DETAILS",0,1,'J');

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
$sql = "SELECT * FROM olevelresultdetail WHERE (applid='".$_SESSION['staffid']."') AND examno='".$frs['examno']."' ORDER BY subject";

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
$pdf->SetXY(118,165);
$pdf->Cell(82,6,'Second Sitting', 0, 1);
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

 $sqlt = "SELECT * FROM olevelresultdetail WHERE (applid='".$_SESSION['staffid']."') AND examno='".$frsc['examno']."' ORDER BY subject";

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

}
$pdf->Ln(5);
$pdf->SetFont('times', 'B', 12);
$pdf->Cell(25,6,'Declaration.',0,1);
$pdf->SetFont('times', '', 10);
$pdf->MultiCell(185,6,'I, '.$_POST['harrd'].' affirm that the information provided above about me is correct and accurate.',0,1);
 $pdf->SetFont('times', 'B', 13);
 $pdf->Cell(25,6,'_________________',0,1);
 $pdf->Cell(25,4,'Signature/Date',0,0); 
     }
$resh = trim(str_ireplace( array( '\'', '"',',',';','<','>','/','-','_',''), '', $_SESSION['staffid']));
$pdf->Output($resh.'_PrintOut.pdf',"I"); 
 }else{
    header('location:admission');
 }

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
        $fac = $data['faculty'];
        $sur=$data['lastname'];
        $mak='../apply/'.$data['passport'];
        $maks=$data['acadsession'];
        $_POST['emai']=$data['email'];
        $_POST['pro']=$data['programme'];
        $_POST['fac'] = $data['faculty'];
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