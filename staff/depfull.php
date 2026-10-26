<?php
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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?> - Departmental Result Summary</title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
        <link rel="stylesheet" href="css/receipt.css" media="screen">
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/print.css" media="print" >
        <style type="text/css">
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            @media print {
                @page {
                    size: landscape;
                    margin: 1cm;
                }
                .WordSection1 td {
                    font-size: 9px;
                }
                .headerp {
                    position: fixed;
                }
                table { 
                    page-break-after: auto;
                    page-break-inside: avoid;
                }
                tr { 
                    page-break-inside: avoid;
                    page-break-after: auto;
                }
                td { 
                    page-break-inside: avoid;
                    page-break-after: auto;
                }
                .talk {
                    text-align: justify;
                }
                .no-print, .button {
                    display: none !important;
                }
                .signature-row {
                    page-break-inside: avoid;
                }
            }
            
            /* Main Container */
            .WordSection1 {
                max-width: 1400px;
                margin: 0 auto;
                padding: 20px;
                background: white;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            
            /* Header Styling */
            .header-container {
                text-align: center;
                margin-bottom: 25px;
                padding-bottom: 15px;
                border-bottom: 3px solid #2c3e50;
            }
            
            .logo {
                margin-bottom: 10px;
            }
            
            .logo img {
                max-width: 100px;
                height: auto;
            }
            
            .org-name {
                font-size: 20px;
                font-weight: bold;
                text-transform: uppercase;
                color: #2c3e50;
                margin: 5px 0;
            }
            
            .org-address {
                font-size: 12px;
                color: #7f8c8d;
                margin-bottom: 10px;
            }
            
            .report-title {
                font-size: 18px;
                font-weight: bold;
                color: #c0392b;
                margin-top: 10px;
                letter-spacing: 2px;
            }
            
            /* Info Cards */
            .info-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 12px;
                border-radius: 8px;
                margin: 15px 0;
                text-align: center;
            }
            
            .info-card p {
                margin: 5px 0;
                font-size: 13px;
            }
            
            .info-card strong {
                font-weight: bold;
            }
            
            /* Stats Bar */
            .stats-bar {
                background: #ecf0f1;
                padding: 10px;
                border-radius: 6px;
                margin: 15px 0;
                display: flex;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .stat-item {
                flex: 1;
                text-align: center;
                padding: 5px;
                background: white;
                border-radius: 4px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            
            .stat-label {
                font-size: 11px;
                color: #7f8c8d;
                display: block;
            }
            
            .stat-value {
                font-size: 14px;
                font-weight: bold;
                color: #2c3e50;
            }
            
            /* Table Styling */
            .MsoTableGrid {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                font-size: 11px;
            }
            
            .MsoTableGrid th {
                background: #34495e;
                color: white;
                padding: 8px 4px;
                text-align: center;
                font-weight: bold;
                border: 1px solid #2c3e50;
            }
            
            .MsoTableGrid td {
                padding: 6px 4px;
                text-align: center;
                border: 1px solid #bdc3c7;
                vertical-align: middle;
            }
            
            .MsoTableGrid tr:hover {
                background: #f5f6fa;
            }
            
            /* Course Header */
            .course-header {
                text-align: center;
            }
            
            .course-code {
                font-weight: bold;
                font-size: 11px;
                display: block;
            }
            
            .course-units {
                font-size: 9px;
                color: #ecf0f1;
                display: block;
            }
            
            .course-mgp {
                font-size: 8px;
                margin-top: 3px;
                display: block;
            }
            
            /* Grade Cells */
            .grade-cell {
                text-align: center;
                font-size: 9px;
                font-weight: bold;
            }
            
            .grade-pass {
                background-color: #d4edda;
                color: #155724;
            }
            
            .grade-fail {
                background-color: #f8d7da;
                color: #721c24;
            }
            
            .grade-pending {
                background-color: #fff3cd;
                color: #856404;
            }
            
            /* CGPA Cell Styling */
            .cgpa-cell {
                font-weight: bold;
                text-align: center;
                background-color: #e8f4f8;
                font-size: 11px;
            }
            
            .cgpa-excellent {
                background-color: #28a745;
                color: white;
            }
            
            .cgpa-good {
                background-color: #17a2b8;
                color: white;
            }
            
            .cgpa-average {
                background-color: #ffc107;
                color: #856404;
            }
            
            .cgpa-poor {
                background-color: #dc3545;
                color: white;
            }
            
            /* Legend Box */
            .legend-box {
                background: #f8f9fa;
                border-left: 4px solid #3498db;
                padding: 10px 15px;
                margin: 20px 0;
                border-radius: 4px;
            }
            
            .legend-title {
                font-weight: bold;
                margin-bottom: 8px;
                font-size: 12px;
            }
            
            .legend-item {
                display: inline-block;
                margin-right: 20px;
                margin-bottom: 5px;
                font-size: 10px;
            }
            
            .legend-color {
                display: inline-block;
                width: 16px;
                height: 16px;
                border-radius: 3px;
                margin-right: 5px;
                vertical-align: middle;
            }
            
            /* Course List Table */
            .course-list-table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                font-size: 11px;
            }
            
            .course-list-table th {
                background: #2c3e50;
                color: white;
                padding: 8px;
                text-align: center;
            }
            
            .course-list-table td {
                padding: 6px 8px;
                border: 1px solid #bdc3c7;
            }
            
            /* Signature Section */
            .signature-section {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 2px solid #2c3e50;
            }
            
            .signature-row {
                display: flex;
                justify-content: space-between;
                gap: 20px;
                flex-wrap: wrap;
            }
            
            .signature-box {
                flex: 1;
                text-align: center;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
                min-width: 200px;
            }
            
            .signature-title {
                font-weight: bold;
                font-size: 12px;
                color: #2c3e50;
                margin-bottom: 10px;
                border-bottom: 2px solid #3498db;
                display: inline-block;
                padding-bottom: 3px;
            }
            
            .signature-name {
                font-size: 11px;
                margin: 8px 0;
            }
            
            .signature-line {
                margin-top: 20px;
                padding-top: 5px;
            }
            
            /* Button Styling */
            .button-container {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin: 30px 0 20px;
                flex-wrap: wrap;
            }
            
            .btn-custom {
                padding: 10px 25px;
                font-size: 14px;
                font-weight: bold;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .btn-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            }
            
            .btn-close {
                background: #e74c3c;
                color: white;
            }
            
            .btn-print {
                background: #3498db;
                color: white;
            }
            
            /* Student Info */
            .student-id {
                font-weight: bold;
                font-size: 10px;
            }
            
            .student-name {
                font-size: 8px;
                color: #7f8c8d;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .stats-bar {
                    flex-direction: column;
                }
                .signature-row {
                    flex-direction: column;
                }
                .MsoTableGrid {
                    font-size: 8px;
                }
            }
        </style>
    </head>
    <body style="background: #f0f2f5;">
        <div class="WordSection1">
            <!-- Header Section -->
            <div class="header-container">
                <div class="logo">
                    <img src="<?php echo $logo; ?>" alt="Logo">
                </div>
                <div class="org-name"><?php echo htmlentities($orgname); ?></div>
                <div class="org-address"><?php echo htmlentities($schadress); ?></div>
                <div class="report-title">DEPARTMENTAL RESULT SUMMARY</div>
            </div>
            
            <?php 
            $daft = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM program WHERE pname='$prgs'"));
            $deplt = $daft['did'];
            $depna = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM department WHERE deptid='$deplt'"));
            $dt = $depna['dept'];
            ?>
            
            <!-- Info Card -->
            <div class="info-card">
                <p><strong>DEPARTMENT:</strong> <?php echo htmlentities($dt); ?> &nbsp;|&nbsp; 
                   <strong>PROGRAMME:</strong> <?php echo htmlentities($prgs); ?></p>
            </div>
            
            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="stat-item">
                    <span class="stat-label">Academic Session</span>
                    <span class="stat-value"><?php echo htmlentities($secc); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Semester</span>
                    <span class="stat-value"><?php echo htmlentities($sems); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Level</span>
                    <span class="stat-value"><?php echo htmlentities($clvl); ?></span>
                </div>
            </div>
            
            <!-- Result Table -->
            <table class="MsoTableGrid" border="1" cellspacing="0" cellpadding="0" align="center">
                <?php 
                try {
                    
                    $sqlc = "SELECT DISTINCT result.coursecode,result.semester,result.level,course_allocation.unit FROM result JOIN course_allocation ON result.coursecode=course_allocation.coursecode
                 WHERE result.programme='$prgs' AND course_allocation.programme='$prgs' AND result.level='$clvl' AND result.semester='$sems' AND result.acadsession='$secc' ORDER BY result.coursecode";
                                                            
                    $sts = mysqli_query($con, $sqlc);
                    $counter = mysqli_fetch_all($sts, MYSQLI_ASSOC);
                    $Acounter = 0;
                    foreach ($counter as $rowa) {
                        $Acounter+=1;
                        $code[$Acounter] = $rowa['coursecode'];
                        $SMSTER[$Acounter] = $rowa['semester'];
                        $un[$Acounter] = $rowa['unit'];
                        $Lev[$Acounter] = $rowa['level'];
                    }
                ?>
                <thead>
                    <tr>
                        <th width="5%">S/N</th>
                        <th width="12%">Student ID</th>
                        <?php for ($t = 1; $t <= $Acounter; $t++) { ?>
                        <th width="7%" class="course-header">
                            <span class="course-code"><?php echo $code[$t]; ?></span>
                            <span class="course-units">(<?php echo $un[$t]; ?> units)</span>
                            <span class="course-mgp">Score | Grade | Pts</span>
                        </th>
                        <?php } ?>
                        <th width="8%">Semester<br>CGPA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sqlv = "SELECT DISTINCT studentid FROM result 
                            WHERE acadsession='$secc' AND semester='$sems' 
                            AND level='$clvl' AND programme='$prgs' 
                            ORDER BY studentid ASC";
                    $stv = mysqli_query($con, $sqlv);
                    $countv = mysqli_fetch_all($stv, MYSQLI_ASSOC);
                    $d = 0;
                    $totalStudents = count($countv);
                    $passedCount = 0;
                    $failedCount = 0;
                    $cgpaSum = 0;
                    
                    foreach($countv as $rowv):
                        $d++;
                        stuname($rowv['studentid'], $con);
                        
                        // Calculate CGPA for this student
                        $studentCGPA = calculateStudentCGPA($con, $rowv['studentid'], $sems, $clvl, $secc, $prgs);
                        $cgpaSum += $studentCGPA;
                        
                        // Determine CGPA class for styling
                        $cgpaClass = '';
                        if ($studentCGPA >= 3.5) {
                            $cgpaClass = 'cgpa-excellent';
                        } elseif ($studentCGPA >= 3.0) {
                            $cgpaClass = 'cgpa-good';
                        } elseif ($studentCGPA >= 2.0) {
                            $cgpaClass = 'cgpa-average';
                        } elseif ($studentCGPA > 0) {
                            $cgpaClass = 'cgpa-poor';
                        }
                        
                        // Get all courses for this student
                        $sqlct = "SELECT DISTINCT coursecode FROM result 
                                 WHERE programme='$prgs' AND level='$clvl' 
                                 AND semester='$sems' AND acadsession='$secc'  
                                 ORDER BY coursecode";
                        $sts = mysqli_query($con, $sqlct);
                        $counterx = mysqli_fetch_all($sts, MYSQLI_ASSOC);
                        $Acounterc = 0;
                        foreach ($counterx as $rowac) {
                            $Acounterc+=1;
                            $cour[$Acounterc] = $rowac['coursecode'];
                        }
                    ?>
                    <tr>
                        <td><?php echo $d; ?></td>
                        <td>
                            <div class="student-id"><?php echo $rowv['studentid']; ?></div>
                            <div class="student-name"><?php echo $_POST["harrd"]; ?></div>
                        </td>
                        <?php
                        $studentFailed = false;
                        for ($tm = 1; $tm <= $Acounterc; $tm++) {
                            loadcorecourse($cour[$tm], $rowv['studentid'], $sems, $clvl, $secc, $prgs, $con);
                            $gradeClass = '';
                            if(strpos($_POST['couif'], 'FA') !== false || 
                               strpos($_POST['couif'], 'Not-Approved') !== false || 
                               strpos($_POST['couif'], 'Not-Upload') !== false || 
                               strpos($_POST['couif'], '---') !== false) {
                                $gradeClass = 'grade-fail';
                                $studentFailed = true;
                            } elseif(strpos($_POST['couif'], 'Not-Submitted') !== false) {
                                $gradeClass = 'grade-pending';
                            } elseif(is_numeric(substr($_POST['couif'], 0, 2)) && (int)substr($_POST['couif'], 0, 2) < 40) {
                                $gradeClass = 'grade-fail';
                                $studentFailed = true;
                            } elseif(is_numeric(substr($_POST['couif'], 0, 2)) && (int)substr($_POST['couif'], 0, 2) >= 40) {
                                $gradeClass = 'grade-pass';
                            }
                        ?>
                        <td class="grade-cell <?php echo $gradeClass; ?>">
                            <?php echo $_POST['couif']; ?>
                        </td>
                        <?php } 
                        if(!$studentFailed && $studentCGPA >= 1.0) {
                            $passedCount++;
                        } else {
                            $failedCount++;
                        }
                        ?>
                        <td class="cgpa-cell <?php echo $cgpaClass; ?>">
                            <strong><?php echo number_format($studentCGPA, 2); ?></strong>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php } catch (Exception $e) {
                    echo '<tr><td colspan="100" style="color:red; text-align:center;">Error: ' . $e->getMessage() . '</td></tr>';
                } ?>
            </table>
            
            <!-- Legend Box -->
            <div class="legend-box">
                <div class="legend-title">📖 Legend / Interpretation Guide</div>
                <div>
                    <span class="legend-item"><span class="legend-color" style="background: #28a745;"></span> Approved/Pass</span>
                    <span class="legend-item"><span class="legend-color" style="background: #ffc107;"></span> Pending/Not Submitted</span>
                    <span class="legend-item"><span class="legend-color" style="background: #dc3545;"></span> Failed/Not Registered</span>
                    <span class="legend-item"><span class="legend-color" style="background: #17a2b8;"></span> Not Uploaded</span>
                </div>
                <div style="margin-top: 8px;">
                    <span class="legend-item"><strong>FA:</strong> Failed due to Absence</span>
                    <span class="legend-item"><strong>---:</strong> Not Registered</span>
                    <span class="legend-item"><strong>Not-Approved:</strong> Pending Approval</span>
                    <span class="legend-item"><strong>Not-Submitted:</strong> Script Not Submitted</span>
                </div>
                <div style="margin-top: 8px; border-top: 1px solid #dee2e6; padding-top: 8px;">
                    <span class="legend-item"><span class="legend-color" style="background: #28a745;"></span> CGPA ≥ 3.5 - Excellent</span>
                    <span class="legend-item"><span class="legend-color" style="background: #17a2b8;"></span> CGPA 3.0 - 3.49 - Good</span>
                    <span class="legend-item"><span class="legend-color" style="background: #ffc107;"></span> CGPA 2.0 - 2.99 - Average</span>
                    <span class="legend-item"><span class="legend-color" style="background: #dc3545;"></span> CGPA {'<'} 2.0 - Poor</span>
                </div>
            </div>
            
            <!-- Statistics Summary -->
            <div class="stats-bar" style="background: #e8f4f8;">
                <div class="stat-item">
                    <span class="stat-label">Total Students</span>
                    <span class="stat-value"><?php echo $totalStudents; ?></span>
                </div>
                <div class="stat-item" style="background: #d4edda;">
                    <span class="stat-label">Passed</span>
                    <span class="stat-value" style="color: #155724;"><?php echo $passedCount; ?></span>
                </div>
                <div class="stat-item" style="background: #f8d7da;">
                    <span class="stat-label">Failed/At Risk</span>
                    <span class="stat-value" style="color: #721c24;"><?php echo $failedCount; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Success Rate</span>
                    <span class="stat-value"><?php echo $totalStudents > 0 ? round(($passedCount/$totalStudents)*100, 1) : 0; ?>%</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Average CGPA</span>
                    <span class="stat-value"><?php echo $totalStudents > 0 ? number_format($cgpaSum/$totalStudents, 2) : '0.00'; ?></span>
                </div>
            </div>
            
            <!-- Course List Table -->
            <table class="course-list-table">
                <thead>
                    <tr>
                        <th width="8%">S/N</th>
                        <th width="20%">Course Code</th>
                        <th width="60%">Course Title</th>
                        <th width="12%">Units</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                $cds = "SELECT DISTINCT r.coursecode, r.semester, r.level, c.title, ca.unit FROM result r JOIN course c ON r.coursecode = c.coursecode JOIN course_allocation ca 
    ON r.coursecode = ca.coursecode AND ca.programme='$prgs' WHERE r.programme='$prgs' AND r.level = '$clvl' AND r.semester = '$sems' AND r.acadsession = '$secc' ORDER BY r.coursecode";
                
                    
                    $ct = mysqli_query($con, $cds);
                    $cnt = mysqli_fetch_all($ct, MYSQLI_ASSOC);
                    $Acounterv = 0;
                    $dt = 0;
                    foreach ($cnt as $rowb) {
                        $Acounterv += 1;
                        $codev[$Acounterv] = $rowb['coursecode'];
                        $Tt[$Acounterv] = $rowb['title'];
                        $unv[$Acounterv] = $rowb['unit'];
                    }
                    
                    for ($kt=1; $kt <= $Acounterv; $kt++) {
                        $dt += 1;
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo htmlentities($dt); ?></td>
                        <td><?php echo htmlentities($codev[$kt]); ?></td>
                        <td><?php echo htmlentities($Tt[$kt]); ?></td>
                        <td style="text-align: center;"><?php echo htmlentities($unv[$kt]); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <!-- Signature Section -->
            <div class="signature-section">
                <?php 
                deanname($con, $prgs);
                hodname($con, $prgs);
                vcname($con);
                ?>     
                <div class="signature-row">
                    <div class="signature-box">
                        <div class="signature-title">DEPARTMENT OFFICER</div>
                        <div class="signature-name">Name: <?php echo htmlentities($_POST['nameh']); ?></div>
                        <div class="signature-line">Sign: __________________</div>
                        <div>Date: __________________</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-title">DEAN OF SCHOOL</div>
                        <div class="signature-name">Name: <?php echo htmlentities($_POST['names']); ?></div>
                        <div class="signature-line">Sign: __________________</div>
                        <div>Date: __________________</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-title">PROVOST</div>
                        <div class="signature-name">Name: <?php echo htmlentities($_POST['vname']); ?></div>
                        <div class="signature-line">Sign: __________________</div>
                        <div>Date: __________________</div>
                    </div>
                </div>
            </div>
            
            <!-- Footer / Action Buttons -->
            <div class="button-container no-print">
                <button class="btn-custom btn-print" onclick="window.print();">
                    <i class="fa fa-print"></i> Print Report
                </button>
                <button class="btn-custom btn-close" onclick="window.close();">
                    <i class="fa fa-times"></i> Close Window
                </button>
            </div>
        </div>
        
        <script>
            // Keyboard shortcut for print (Ctrl+P)
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.keyCode === 80) {
                    e.preventDefault();
                    window.print();
                }
            });
        </script>
    </body>
</html>

<?php
    }
    
    // ========== FUNCTIONS ==========
    
    function stuname($maxt, $con) {
        $sql = mysqli_query($con, "SELECT * FROM student WHERE studentid='$maxt'");
        $data = mysqli_fetch_assoc($sql);
        if ($data == "") {
            $_POST["harrd"] = "No Name";
        } else {
            $first = $data['firstname'];
            $las = $data['lastname'];
            $name = $first . " " . $las;
            $_POST["harrd"] = $name;
            $_POST["maks"] = $data['studentid'];
        }
    }
    
    /* Loading Grades System */
    function GetGradeSystem($con) {
        $sqlper = "SELECT * FROM gradetable ORDER BY maxscore DESC";
        $result = mysqli_query($con, $sqlper);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $gradeCounter = 0;
        foreach ($data as $row) {
            $gradeCounter++;
            $_POST['Minscore'][$gradeCounter] = $row["minscore"];
            $_POST['Maxscore'][$gradeCounter] = $row["maxscore"];
            $_POST['Grade'][$gradeCounter] = $row["grade"];
            $_POST['point'][$gradeCounter] = $row["point"];
        }
        $_SESSION['gradeCounter'] = $gradeCounter;
    }
    
    // Function to calculate CGPA for a student in a specific semester
    function calculateStudentCGPA($con, $studentId, $semester, $level, $session, $programme) {
        $query = "SELECT r.coursecode, r.ca, r.exam, r.status, ca.unit 
                  FROM result r 
                  JOIN course_allocation ca ON r.coursecode = ca.coursecode AND ca.programme = '$programme'
                  WHERE r.studentid = '$studentId' 
                  AND r.semester = '$semester' 
                  AND r.level = '$level' 
                  AND r.acadsession = '$session'
                  AND r.programme = '$programme'";
        
        $result = mysqli_query($con, $query);
        $totalPoints = 0;
        $totalUnits = 0;
        
        if (!$result) {
            return 0.00;
        }
        
        GetGradeSystem($con);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $totalScore = (int)$row['ca'] + (int)$row['exam'];
            $unit = (int)$row['unit'];
            $status = trim($row['status']);
            
            // Only calculate for approved results
            if (($status == 'Y' || $status == 'Approved') && $totalScore > 0) {
                // Find the grade point for this score
                for ($j = 1; $j <= $_SESSION['gradeCounter']; $j++) {
                    if ($totalScore <= $_POST['Maxscore'][$j] && $totalScore >= $_POST['Minscore'][$j]) {
                        $gradePoint = $_POST['point'][$j];
                        $totalPoints += $gradePoint * $unit;
                        $totalUnits += $unit;
                        break;
                    }
                }
            }
        }
        
        if ($totalUnits > 0) {
            return $totalPoints / $totalUnits;
        }
        
        return 0.00;
    }
    
    function loadcorecourse($ct, $mat, $sem, $le, $se, $pr, $con) {
        $_POST['pt'] = 0;
        
        $rowr = mysqli_fetch_assoc(mysqli_query($con, "SELECT r.coursecode, r.ca, r.exam, r.status, ca.unit FROM result r JOIN course_allocation ca ON r.coursecode = ca.coursecode AND ca.programme = '$pr'
              WHERE r.studentid = '$mat' AND r.semester = '$sem' AND r.level = '$le' AND r.acadsession = '$se' AND r.programme = '$pr' AND r.coursecode='$ct'"));
        
        if ($rowr != '') {
            $Totaledk = ((int)$rowr["ca"] + (int)$rowr["exam"]);
            $std = preg_replace('/\s+/', '', $rowr['status']);
            $unt = $rowr['unit'];
            
            if (($std == 'Y' OR $std == 'Approved') AND $Totaledk != '0') {
                GetGradeSystem($con);
                for ($j = 1; $j <= $_SESSION['gradeCounter']; $j++) {
                    if ($Totaledk <= $_POST['Maxscore'][$j] && $Totaledk >= $_POST['Minscore'][$j]) {
                        $_POST['pt'] = $_POST['point'][$j] * $unt;
                        $_POST['G'] = $_POST['Grade'][$j];
                    }
                }              
                $_POST['couif'] = $Totaledk . " | " . $_POST['G'] . " | " . $_POST['pt'];
            } elseif ($std == 'Pending' AND $Totaledk == '0') {
                $_POST['couif'] = 'Not-Approved';
            } elseif ($Totaledk == '0' AND $std != '') {
                $_POST['couif'] = 'F A';
            } elseif ($std == '' AND $Totaledk == '0') {
                $_POST['couif'] = 'Not-Upload';
            } elseif ($std == '' AND $Totaledk != '0') {
                $_POST['couif'] = 'Not-Submitted';
            } elseif ($std == 'Pending') {
                $_POST['couif'] = 'Not-Approved';
            }
        } elseif ($rowr == "") {
            $_POST['couif'] = '---';
        }
    }
    
    function deanname($con, $prgs) {
        $fac = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM program WHERE pname='$prgs'"));  
        $stf = mysqli_query($con, "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid = people.staffid 
            JOIN staffprive ON staffaculty.staffid = staffprive.staffid 
            WHERE people.status='active' AND staffprive.position='dean' AND staffaculty.faculty='" . $fac['fid'] . "'");
        $res = mysqli_fetch_assoc($stf);
        if ($res != '') {
            $stn = ucwords($res['title'] . ". " . $res['firstname'] . " " . strtoupper($res['lastname']));
            $_POST['names'] = $stn;
        } else {
            $_POST['names'] = "No Name Assigned";
        }
    }
    
    function vcname($con) {
        $dts = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM people JOIN staffprive ON staffprive.staffid = people.staffid 
            WHERE people.status='active' AND staffprive.position='provost'"));
        if ($dts != '') {
            $fulna = $dts['title'] . ". " . $dts['firstname'] . " " . strtoupper($dts['lastname']);
            $_POST['vname'] = $fulna;
        } else {
            $_POST['vname'] = 'No Name Assigned';
        }
    }
    
    function hodname($con, $prgs) {
        $fac = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM program WHERE pname='$prgs'"));  
        $stf = mysqli_query($con, "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid = people.staffid 
            JOIN staffprive ON staffaculty.staffid = staffprive.staffid 
            WHERE people.status='active' AND staffprive.position='hod' AND staffaculty.deptm='" . $fac['did'] . "'");
        $res = mysqli_fetch_assoc($stf);   
        if ($res != '') {
            $stn = ucwords($res['title'] . ". " . $res['firstname'] . " " . strtoupper($res['lastname']));
            $_POST['nameh'] = $stn;
        } else {
            $_POST['nameh'] = 'No Name Assigned';
        }
    }
?>