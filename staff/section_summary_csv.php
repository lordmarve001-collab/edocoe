<?php 
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

if (!isset($_SESSION['export_mode'])) {
    die('Invalid export request');
}

$mode = $_SESSION['export_mode'];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=payment_export_'.$mode.'.csv');  

$output = fopen('php://output', 'w');

switch ($mode) {

    /* =====================================================
       MULTI PROGRAMME + SECTION SUMMARY
    ===================================================== */
    case 'multi_programme_summary':

        $section = $_SESSION['section'];

        fputcsv($output, [
            'Programme',
            'Fee Purpose',
            'Total Students',
            'Total Amount',
            'Session'
        ]);

        $sql = "
        SELECT 
            UPPER(TRIM(programme)) AS programme,
            purpose,
            COUNT(DISTINCT studentid) AS total_students,
            SUM(CAST(amount AS UNSIGNED)) AS total_amount
        FROM studentpayment
        WHERE section = '$section'
          AND LOWER(status) = 'success'
        GROUP BY UPPER(TRIM(programme)), purpose
        ORDER BY programme, purpose
        ";

        $res = mysqli_query($con, $sql);

        while ($row = mysqli_fetch_assoc($res)) {
            fputcsv($output, [
                $row['programme'],
                $row['purpose'],
                $row['total_students'],
                $row['total_amount'],
                $section
            ]);
        }
        break;

    /* =====================================================
       SECTION ONLY SUMMARY
    ===================================================== */
    case 'section_summary':

    $section = $_SESSION['section'];

    /* ======================================
       TITLE
    ======================================= */
    fputcsv($output, ['PAYMENT SUMMARY REPORT']);
    fputcsv($output, ['Academic Session:', $section]);
    fputcsv($output, []); // empty row

    /* ======================================
       1. SUMMARY BY FEES
    ======================================= */
    fputcsv($output, ['SUMMARY BY FEES']);
    fputcsv($output, ['Fee Purpose', 'Total Payments', 'Total Amount (₦)']);

    $sql = "
        SELECT 
            purpose,
            COUNT(DISTINCT studentid) total_students,
            SUM(CAST(amount AS UNSIGNED)) total_amount
        FROM studentpayment
        WHERE section = '$section'
          AND LOWER(status) = 'success'
        GROUP BY purpose
        ORDER BY purpose
    ";
    $res = mysqli_query($con, $sql);

    $feesGrandTotal = 0;

    while ($row = mysqli_fetch_assoc($res)) {
        $feesGrandTotal += $row['total_amount'];

        fputcsv($output, [
            $row['purpose'],
            $row['total_students'],
            number_format($row['total_amount'], 2)
        ]);
    }

    fputcsv($output, ['FEES GRAND TOTAL', '', number_format($feesGrandTotal, 2)]);
    fputcsv($output, []);
    fputcsv($output, []);

    /* ======================================
       2. SUMMARY BY PROGRAMME
    ======================================= */
    fputcsv($output, ['SUMMARY BY PROGRAMME']);

    $sql = "
        SELECT 
            TRIM(UPPER(programme)) programme,
            purpose,
            COUNT(DISTINCT studentid) total_students,
            SUM(CAST(amount AS UNSIGNED)) total_amount
        FROM studentpayment
        WHERE section = '$section'
          AND LOWER(status) = 'success'
        GROUP BY programme, purpose
        ORDER BY programme, purpose
    ";
    $res = mysqli_query($con, $sql);

    $currentProgramme = '';
    $progTotal = 0;

    while ($row = mysqli_fetch_assoc($res)) {

        if ($currentProgramme !== $row['programme']) {

            if ($currentProgramme !== '') {
                fputcsv($output, ['PROGRAMME TOTAL', '', number_format($progTotal, 2)]);
                fputcsv($output, []);
            }

            $currentProgramme = $row['programme'];
            $progTotal = 0;

            fputcsv($output, ['Programme:', $currentProgramme]);
            fputcsv($output, ['Fee Purpose', 'Total Payments', 'Total Amount (₦)']);
        }

        $progTotal += $row['total_amount'];

        fputcsv($output, [
            $row['purpose'],
            $row['total_students'],
            number_format($row['total_amount'], 2)
        ]);
    }

    if ($currentProgramme !== '') {
        fputcsv($output, ['PROGRAMME TOTAL', '', number_format($progTotal, 2)]);
    }

    fputcsv($output, []);
    fputcsv($output, []);

    /* ======================================
       3. SUMMARY BY LEVEL
    ======================================= */
    fputcsv($output, ['SUMMARY BY LEVEL']);
    fputcsv($output, ['Level', 'Total Payments', 'Total Amount (₦)']);

    $sql = "
        SELECT 
            level,
            COUNT(DISTINCT studentid) total_students,
            SUM(CAST(amount AS UNSIGNED)) total_amount
        FROM studentpayment
        WHERE section = '$section'
          AND LOWER(status) = 'success'
        GROUP BY level
        ORDER BY level
    ";
    $res = mysqli_query($con, $sql);

    $levelGrandTotal = 0;

    while ($row = mysqli_fetch_assoc($res)) {
        $levelGrandTotal += $row['total_amount'];

        fputcsv($output, [
            $row['level'],
            $row['total_students'],
            number_format($row['total_amount'], 2)
        ]);
    }

    fputcsv($output, ['LEVEL GRAND TOTAL', '', number_format($levelGrandTotal, 2)]);

    break;

    /* =====================================================
       LEVEL ONLY SUMMARY
    ===================================================== */
    
         case 'level_section_summary':

        $section = $_SESSION['section'];

    fputcsv($output, ['Level','Fee Purpose','Total Students','Total Amount','Session']);

    $sql = "
    SELECT 
        level,
        purpose,
        COUNT(DISTINCT studentid) AS total_students,
        SUM(CAST(amount AS UNSIGNED)) AS total_amount
    FROM studentpayment
    WHERE section = '$section'
      AND LOWER(status)='success'
    GROUP BY level, purpose
    ORDER BY level, purpose
    ";

    $res = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($res)) {
        fputcsv($output, [
            $row['level'],
            $row['purpose'],
            $row['total_students'],
            $row['total_amount'],
            $section
        ]);
    }
        break;
         /* =====================================================
       DATE RANGE SUMMARY
    ===================================================== */
    
         case 'date_range_summary':

       $from=$_SESSION['from'];
$to=$_SESSION['to'];

fputcsv($output,["TYPE","NAME","PAYMENTS","TOTAL"]);

$blocks = [
    "Fees"=>"purpose",
    "Programme"=>"programme",
    "Level"=>"level"
];

foreach($blocks as $title=>$field){
    $q=mysqli_query($con,"
        SELECT $field AS name, COUNT(*) payments, SUM(amount) total
        FROM studentpayment
        WHERE paytime BETWEEN '$from' AND '$to'
        AND LOWER(status)='success'
        GROUP BY $field
    ");
    while($r=mysqli_fetch_assoc($q)){
        fputcsv($output,[$title,$r['name'],$r['payments'],$r['total']]);
    }}
        break;
        
}

fclose($output);
exit;