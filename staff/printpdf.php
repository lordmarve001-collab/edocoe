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

if (!isset($_SESSION['export_mode'])) {
    die('Invalid export request');
}

$mode = $_SESSION['export_mode'];

class PDF extends FPDF
{
    function Footer()
    {
        $this->SetY(-15);
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}
$pdf = new PDF('L');
$pdf->SetMargins(30,15,30);       // 10% margins
$pdf->SetAutoPageBreak(true,20);
$pdf->AddPage();
$pdf->AliasNbPages();


/* ===== PAGE WIDTH CONTROL ===== */
$pageWidth   = $pdf->GetPageWidth();
$usableWidth = $pageWidth - 60;   // 80% usable width

/* ===== STANDARD COLUMN WIDTHS ===== */
$w_sn     = 0.07 * $usableWidth;
$w_label  = 0.45 * $usableWidth;
$w_count  = 0.18 * $usableWidth;
$w_amount = 0.30 * $usableWidth;

/* ===== HEADER ===== */
$pdf->Image($logo, ($pageWidth / 2) - 12, 5, 24);
$pdf->Ln(30);
$pdf->SetFont('Arial','B',16);
$pdf->Cell($usableWidth,10,strtoupper($orgname),0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Cell($usableWidth,8,$schadress,0,1,'C');
$pdf->Ln(8);

/* =====================================================
   SECTION SUMMARY (FEES + PROGRAMME + LEVEL)
===================================================== */
if ($mode === 'section_summary') {

    $section = $_SESSION['section'];

    $pdf->SetFont('Arial','B',13);
    $pdf->Cell($usableWidth,9,'PAYMENT SUMMARY REPORT',0,1,'C');
    $pdf->Cell($usableWidth,8,'Academic Session: '.$section,0,1,'C');
    $pdf->Ln(8);

    /* ================= FEES SUMMARY ================= */
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell($usableWidth,8,'1. SUMMARY BY FEES',0,1);
    $pdf->Ln(3);

    $sql = "
        SELECT purpose, COUNT(DISTINCT studentid) students,
               SUM(amount) total
        FROM studentpayment
        WHERE section='$section' AND LOWER(status)='success'
        GROUP BY purpose
        ORDER BY purpose
    ";
    $res = mysqli_query($con,$sql);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($w_sn,8,'#',1);
    $pdf->Cell($w_label,8,'Fee Purpose',1);
    $pdf->Cell($w_count,8,'Payments',1,0,'C');
    $pdf->Cell($w_amount,8,'Amount (₦)',1,0,'C');
    $pdf->Ln();

    $pdf->SetFont('Arial','',10);
    $i=1; $totalFees=0;

    while($r=mysqli_fetch_assoc($res)){
        $totalFees += $r['total'];
        $pdf->Cell($w_sn,8,$i++,1);
        $pdf->Cell($w_label,8,$r['purpose'],1);
        $pdf->Cell($w_count,8,$r['students'],1,0,'C');
        $pdf->Cell($w_amount,8,number_format($r['total'],2),1,0,'R');
        $pdf->Ln();
    }

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($w_sn+$w_label+$w_count,9,'FEES GRAND TOTAL',1);
    $pdf->Cell($w_amount,9,number_format($totalFees,2),1,0,'R');

    $pdf->Ln(12);

    /* ================= PROGRAMME SUMMARY ================= */
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell($usableWidth,8,'2. SUMMARY BY PROGRAMME',0,1);
    $pdf->Ln(3);

    $sql = "
        SELECT programme, purpose,
               COUNT(DISTINCT studentid) students,
               SUM(amount) total
        FROM studentpayment
        WHERE section='$section' AND LOWER(status)='success'
        GROUP BY programme, purpose
        ORDER BY programme, purpose
    ";
    $res = mysqli_query($con,$sql);

    $current = '';
    $progTotal = 0;

    while($r=mysqli_fetch_assoc($res)){
        if($current !== $r['programme']){
            if($current !== ''){
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell($w_sn+$w_label+$w_count,9,'PROGRAMME TOTAL',1);
                $pdf->Cell($w_amount,9,number_format($progTotal,2),1,0,'R');
                $pdf->Ln(10);
            }
            $current = $r['programme'];
            $progTotal = 0;

            $pdf->SetFont('Arial','B',11);
            $pdf->Cell($usableWidth,8,'Programme: '.$current,0,1);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell($w_sn,8,'#',1);
            $pdf->Cell($w_label,8,'Purpose',1);
            $pdf->Cell($w_count,8,'Payments',1,0,'C');
            $pdf->Cell($w_amount,8,'Amount (₦)',1,0,'C');
            $pdf->Ln();
            $sn=1;
        }

        $progTotal += $r['total'];
        $pdf->SetFont('Arial','',10);
        $pdf->Cell($w_sn,8,$sn++,1);
        $pdf->Cell($w_label,8,$r['purpose'],1);
        $pdf->Cell($w_count,8,$r['students'],1,0,'C');
        $pdf->Cell($w_amount,8,number_format($r['total'],2),1,0,'R');
        $pdf->Ln();
    }

    if($current !== ''){
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($w_sn+$w_label+$w_count,9,'PROGRAMME TOTAL',1);
        $pdf->Cell($w_amount,9,number_format($progTotal,2),1,0,'R');
    }

    $pdf->Ln(12);

    /* ================= LEVEL SUMMARY ================= */
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell($usableWidth,8,'3. SUMMARY BY LEVEL',0,1);
    $pdf->Ln(3);

    $sql = "
        SELECT level, COUNT(DISTINCT studentid) students,
               SUM(amount) total
        FROM studentpayment
        WHERE section='$section' AND LOWER(status)='success'
        GROUP BY level
        ORDER BY level
    ";
    $res = mysqli_query($con,$sql);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($w_sn,8,'#',1);
    $pdf->Cell($w_label,8,'Level',1);
    $pdf->Cell($w_count,8,'Payments',1,0,'C');
    $pdf->Cell($w_amount,8,'Amount (₦)',1,0,'C');
    $pdf->Ln();

    $pdf->SetFont('Arial','',10);
    $i=1; $levelTotal=0;

    while($r=mysqli_fetch_assoc($res)){
        $levelTotal += $r['total'];
        $pdf->Cell($w_sn,8,$i++,1);
        $pdf->Cell($w_label,8,$r['level'],1);
        $pdf->Cell($w_count,8,$r['students'],1,0,'C');
        $pdf->Cell($w_amount,8,number_format($r['total'],2),1,0,'R');
        $pdf->Ln();
    }

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($w_sn+$w_label+$w_count,9,'LEVEL GRAND TOTAL',1);
    $pdf->Cell($w_amount,9,number_format($levelTotal,2),1,0,'R');
}

/* =====================================================
   DATE RANGE SUMMARY (UNCHANGED, ALIGNED)
===================================================== */
elseif ($mode === 'date_range_summary') {

    $from = $_SESSION['from'];
    $to   = $_SESSION['to'];

    $pdf->SetFont('Arial','B',13);
    $pdf->Cell($usableWidth,9,"PAYMENT SUMMARY ($from to $to)",0,1,'C');
    $pdf->Ln(6);

    function drawSummary($pdf,$title,$sql,$con,$w_sn,$w_label,$w_count,$w_amount){
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(0,8,$title,0,1);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell($w_sn,8,'#',1);
        $pdf->Cell($w_label,8,'Name',1);
        $pdf->Cell($w_count,8,'Payments',1,0,'C');
        $pdf->Cell($w_amount,8,'Amount (₦)',1,0,'C');
        $pdf->Ln();

        $pdf->SetFont('Arial','',10);
        $i=1;
        $res=mysqli_query($con,$sql);
        while($r=mysqli_fetch_assoc($res)){
            $pdf->Cell($w_sn,8,$i++,1);
            $pdf->Cell($w_label,8,$r['label'],1);
            $pdf->Cell($w_count,8,$r['payments'],1,0,'C');
            $pdf->Cell($w_amount,8,number_format($r['total'],2),1,0,'R');
            $pdf->Ln();
        }
        $pdf->Ln(6);
    }

    drawSummary($pdf,"SUMMARY BY FEES",
        "SELECT purpose label, COUNT(*) payments, SUM(amount) total
         FROM studentpayment
         WHERE paytime BETWEEN '$from' AND '$to'
         AND LOWER(status)='success'
         GROUP BY purpose",
         $con,$w_sn,$w_label,$w_count,$w_amount);

    drawSummary($pdf,"SUMMARY BY PROGRAMME",
        "SELECT programme label, COUNT(*) payments, SUM(amount) total
         FROM studentpayment
         WHERE paytime BETWEEN '$from' AND '$to'
         AND LOWER(status)='success'
         GROUP BY programme",
         $con,$w_sn,$w_label,$w_count,$w_amount);

    drawSummary($pdf,"SUMMARY BY LEVEL",
        "SELECT level label, COUNT(*) payments, SUM(amount) total
         FROM studentpayment
         WHERE paytime BETWEEN '$from' AND '$to'
         AND LOWER(status)='success'
         GROUP BY level",
         $con,$w_sn,$w_label,$w_count,$w_amount);
}

$pdf->Output();
}