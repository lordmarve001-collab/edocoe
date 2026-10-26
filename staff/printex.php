<?php 
require('pdf/fpdf.php');
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';

if(strlen($_SESSION['staffid'])=="") {   
    header("Location: ../logout"); 
    exit();
} else { 
    $now = time();
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location: ../logout");
        exit();
    }
    
    // Session Variables
    $sect = $_SESSION['section'];
    $ccode = $_SESSION['courseallo'];
    
    // Get course information
    $pps = mysqli_fetch_assoc(mysqli_query($con, "SELECT title, unit FROM course WHERE coursecode='$ccode'"));
    $tit = $pps['title'];
    $un = $pps['unit'];
    
    // Get semester
    $qs = mysqli_fetch_assoc(mysqli_query($con, "SELECT DISTINCT semester FROM result WHERE coursecode='$ccode'"));
    $mes = $qs['semester'];
    
    // Get all distinct programs for this course
    $programQuery = "SELECT DISTINCT programme FROM result WHERE coursecode='$ccode' AND acadsession='$sect' ORDER BY programme";
    $programResult = mysqli_query($con, $programQuery);
    $programs = mysqli_fetch_all($programResult, MYSQLI_ASSOC);
    
    class PDF_MC_Table extends FPDF
    {
        var $widths;
        var $aligns;

        function SetWidths($w) {
            $this->widths = $w;
        }

        function SetAligns($a) {
            $this->aligns = $a;
        }

        function Row($data) {
            $nb = 0;
            for($i = 0; $i < count($data); $i++) {
                $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
            }
            $h = 5 * $nb;
            $this->CheckPageBreak($h);
            
            for($i = 0; $i < count($data); $i++) {
                $w = $this->widths[$i];
                $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'J';
                $x = $this->GetX();
                $y = $this->GetY();
                $this->Rect($x, $y, $w, $h);
                $this->MultiCell($w, 5, $data[$i], 0, $a);
                $this->SetXY($x + $w, $y);
            }
            $this->Ln($h);
        }

        function CheckPageBreak($h) {
            if($this->GetY() + $h > $this->PageBreakTrigger) {
                $this->AddPage($this->CurOrientation);
            }
        }

        function NbLines($w, $txt) {
            $cw = &$this->CurrentFont['cw'];
            if($w == 0) {
                $w = $this->w - $this->rMargin - $this->x;
            }
            $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
            $s = str_replace("\r", '', $txt);
            $nb = strlen($s);
            if($nb > 0 and $s[$nb - 1] == "\n") {
                $nb--;
            }
            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $nl = 1;
            while($i < $nb) {
                $c = $s[$i];
                if($c == "\n") {
                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                    continue;
                }
                if($c == ' ') {
                    $sep = $i;
                }
                $l += $cw[$c];
                if($l > $wmax) {
                    if($sep == -1) {
                        if($i == $j) {
                            $i++;
                        }
                    } else {
                        $i = $sep + 1;
                    }
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $nl++;
                } else {
                    $i++;
                }
            }
            return $nl;
        }

        function Footer() {
            $this->SetY(-15);
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
        
        function ProgramTitle($programName, $studentCount) {
            $this->SetFont('helvetica', 'B', 12);
            $this->SetFillColor(52, 152, 219);
            $this->SetTextColor(255, 255, 255);
            $this->Cell(0, 8, ' Programme: ' . strtoupper($programName) . ' (Total Students: ' . $studentCount . ')', 0, 1, 'L', true);
            $this->SetTextColor(0, 0, 0);
            $this->Ln(4);
        }
    }
    
    try {
        $pdf = new PDF_MC_Table('L');
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $pdf->SetTitle($orgname);
        
        // Header with Logo
        $pdf->Image($logo, 130, 5, $pdf->GetX() + 15, $pdf->GetY() + 15);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Text(55, 36, strtoupper($orgname), 'C');
        $pdf->Text(113, 42, "Submitted Result Sheet", 'C');
        
        // Course Information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Text(85, 47, 'Examination Result For ' . $ccode . ', ' . $mes . ' (' . $sect . ')');
        $pdf->Ln(50);
        
        // Process each program separately
        $firstProgram = true;
        
        foreach($programs as $program) {
            $programName = $program['programme'];
            
            // Get students for this program
            $sql = "SELECT * FROM result WHERE coursecode='$ccode' AND semester='$mes' AND acadsession='$sect' AND programme='$programName' ORDER BY studentid";
            $query = mysqli_query($con, $sql);
            $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
            $studentCount = count($results);
            
            if ($studentCount == 0) {
                continue;
            }
            
            // Add new page if not first program and page is getting full
            if (!$firstProgram && $pdf->GetY() > 180) {
                $pdf->AddPage();
                // Re-add header on new page
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Text(55, 36, strtoupper($orgname), 'C');
                $pdf->Text(113, 42, "Submitted Result Sheet", 'C');
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Text(85, 47, 'Examination Result For ' . $ccode . ', ' . $mes . ' (' . $sect . ')');
                $pdf->Ln(50);
            }
            
            // Add Program Title
            $pdf->ProgramTitle($programName, $studentCount);
            
            // Table Header
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetFillColor(200, 220, 255);
            $pdf->SetLeftMargin(15);
            $pdf->Cell(8, 8, "#", 'LBTR', 0, 'C', true);
            $pdf->Cell(45, 8, "Student ID", 'LBTR', 0, 'C', true);
            $pdf->Cell(85, 8, "Full Name", 'LBTR', 0, 'C', true);
            $pdf->Cell(15, 8, "CA", 'LBTR', 0, 'C', true);
            $pdf->Cell(20, 8, "Exam", 'LBTR', 0, 'C', true);
            $pdf->Cell(20, 8, "Total", 'LBTR', 0, 'C', true);
            $pdf->Cell(20, 8, "Grade", 'LBTR', 0, 'C', true);
            $pdf->Ln();
            
            // Display students
            $pdf->SetFont('helvetica', '', 10);
            $cnt = 1;
            
            foreach($results as $result) {
                stuname($con, $result['studentid']);
                $caScore = strtoupper($result['ca']);
                $examScore = strtoupper($result['exam']);
                $totalScore = $caScore + $examScore;
                
                // Calculate grade based on total score
                $grade = getLetterGrade($totalScore);
                
                $pdf->SetWidths(array(8, 45, 85, 15, 20, 20, 20));
                $pdf->Row(array(
                    $cnt,
                    $result['studentid'],
                    ucwords(strtolower($_POST["harrd"])),
                    $caScore,
                    $examScore,
                    $totalScore,
                    $grade
                ));
                $cnt++;
            }
            
            // Add program summary
            $pdf->Ln(3);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetFillColor(240, 240, 240);
            
            // Calculate program statistics
            $gradeDistribution = getGradeDistribution($con, $ccode, $mes, $sect, $programName);
            $passCount = $gradeDistribution['pass'] ?? 0;
            $failCount = $gradeDistribution['fail'] ?? 0;
            
            $summary = "Program Summary: Total Students = " . $studentCount . 
                       " | Pass = " . $passCount . 
                       " | Fail = " . $failCount;
            
            $pdf->Cell(0, 6, $summary, 0, 1, 'L', true);
            $pdf->Ln(5);
            
            $firstProgram = false;
        }
        
        // Add footer with generation date
        $pdf->SetY(-25);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 5, 'Generated on: ' . date('d-m-Y H:i:s'), 0, 0, 'L');
        
        $pdf->Output();
        
    } catch (Exception $e) {
        echo $e;
    }
}

/**
 * Get letter grade based on total score
 */
function getLetterGrade($totalScore) {
    if ($totalScore >= 95 && $totalScore <= 100) return 'A+';
    if ($totalScore >= 90 && $totalScore <= 94) return 'A';
    if ($totalScore >= 85 && $totalScore <= 89) return 'B+';
    if ($totalScore >= 80 && $totalScore <= 84) return 'B';
    if ($totalScore >= 75 && $totalScore <= 79) return 'C+';
    if ($totalScore >= 70 && $totalScore <= 74) return 'C';
    if ($totalScore >= 65 && $totalScore <= 69) return 'D+';
    if ($totalScore >= 60 && $totalScore <= 64) return 'D';
    if ($totalScore >= 0 && $totalScore <= 59) return 'F';
    return 'N/A';
}

/**
 * Get grade distribution for a program
 */
function getGradeDistribution($con, $ccode, $mes, $sect, $programName) {
    $sql = "SELECT ca, exam FROM result WHERE coursecode='$ccode' AND semester='$mes' AND acadsession='$sect' AND programme='$programName'";
    $query = mysqli_query($con, $sql);
    $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
    
    $pass = 0;
    $fail = 0;
    
    foreach($results as $result) {
        $total = $result['ca'] + $result['exam'];
        $grade = getLetterGrade($total);
        
        if ($grade != 'F') {
            $pass++;
        } else {
            $fail++;
        }
    }
    
    return ['pass' => $pass, 'fail' => $fail];
}

function stuname($con, $maxt) {
    $sql = mysqli_query($con, "SELECT * FROM student WHERE studentid = '$maxt'");
    $data = mysqli_fetch_assoc($sql);
    
    if ($data == "") {
        $_POST["harrd"] = "No Name";
    } else {
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las = $data['lastname'];
        $_POST["mat"] = $data['studentid'];
        $name = strtoupper($sur) . " " . strtolower($first . " " . $las);
        $_POST["harrd"] = $name;
    }
}
?>