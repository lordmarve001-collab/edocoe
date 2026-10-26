<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

// Check if request is AJAX
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    // Not an AJAX request, you can redirect or show error
    // For now, just continue
}


$courseCode = isset($_POST['ssid']) ? mysqli_real_escape_string($con, $_POST['ssid']) : '';
$session = isset($_POST['sess']) ? mysqli_real_escape_string($con, $_POST['sess']) : '';

if(empty($courseCode) || empty($session)) {
    echo '<div class="alert alert-danger">Invalid request. Missing course code or session.</div>';
    exit;
}

// Get course allocation details
$query = "SELECT ac.*, p.firstname, p.lastname, p.othername, p.title 
          FROM allocatecourse ac 
          JOIN people p ON ac.staffid = p.staffid 
          WHERE ac.coursecode = '$courseCode' AND ac.acadsession = '$session'";
$result = mysqli_query($con, $query);
$course = mysqli_fetch_assoc($result);

if(!$course) {
    echo '<div class="alert alert-warning">No course information found for ' . htmlspecialchars($courseCode) . ' in ' . htmlspecialchars($session) . '.</div>';
    exit;
}

// Get all students registered for this course
$studentsQuery = "SELECT DISTINCT r.studentid, s.firstname as sfirst, s.lastname as slast, s.othername as sother
                  FROM result r 
                  JOIN student s ON r.studentid = s.studentid
                  WHERE r.coursecode = '$courseCode' 
                  AND r.acadsession = '$session'
                  ORDER BY r.studentid";
$studentsResult = mysqli_query($con, $studentsQuery);
?>

<style>
    .result-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .course-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .course-info-card h4 {
        margin: 0 0 10px 0;
    }
    .students-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .students-table th {
        background: #2c3e50;
        color: white;
        padding: 10px;
        text-align: left;
    }
    .students-table td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    .students-table tr:hover {
        background: #f5f5f5;
    }
    .grade-pass {
        color: #28a745;
        font-weight: bold;
    }
    .grade-fail {
        color: #dc3545;
        font-weight: bold;
    }
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }
    .btn-approve {
        background: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }
    .btn-disapprove {
        background: #dc3545;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }
    .btn-approve:hover, .btn-disapprove:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }
    .score-cell {
        text-align: center;
        font-weight: bold;
    }
    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: bold;
    }
    .status-y {
        background: #d4edda;
        color: #155724;
    }
    .status-n {
        background: #f8d7da;
        color: #721c24;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
</style>

<div class="result-container">
    <div class="course-info-card">
        <h4><i class="fa fa-book"></i> Course Information</h4>
        <p><strong>Course Code:</strong> <?php echo htmlspecialchars($course['coursecode']); ?></p>
        <p><strong>Session:</strong> <?php echo htmlspecialchars($session); ?></p>
        <p><strong>Semester:</strong> <?php echo htmlspecialchars($course['semester']); ?></p>
        <p><strong>Lecturer:</strong> <?php echo htmlspecialchars($course['title'] . ' ' . $course['firstname'] . ' ' . $course['lastname']); ?></p>
        <p><strong>Current Status:</strong> 
            <span class="badge-status <?php echo $course['resultsatus'] == 'Approved' ? 'badge-approved' : 'badge-submitted'; ?>">
                <?php echo htmlspecialchars($course['resultsatus'] ?: 'Pending'); ?>
            </span>
        </p>
    </div>
    
    <h5><i class="fa fa-users"></i> Student Results (<?php echo mysqli_num_rows($studentsResult); ?> students)</h5>
    
    <table class="students-table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>CA Score</th>
                <th>Exam Score</th>
                <th>Total Score</th>
                <th>Grade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sn = 0;
            $totalStudents = 0;
            $passedCount = 0;
            
            while($student = mysqli_fetch_assoc($studentsResult)) {
                $sn++;
                $totalStudents++;
                
                // Get student result
                $resultQuery = "SELECT * FROM result 
                               WHERE coursecode = '$courseCode' 
                               AND acadsession = '$session' 
                               AND studentid = '{$student['studentid']}'";
                $resultRes = mysqli_query($con, $resultQuery);
                $resultData = mysqli_fetch_assoc($resultRes);
                
                $ca = isset($resultData['ca']) ? $resultData['ca'] : '-';
                $exam = isset($resultData['exam']) ? $resultData['exam'] : '-';
                $total = ($ca !== '-' && $exam !== '-') ? $ca + $exam : '-';
                $grade = isset($resultData['grade']) ? $resultData['grade'] : '-';
                $status = isset($resultData['status']) ? $resultData['status'] : '';
                
                $gradeClass = '';
                $statusClass = '';
                if($total !== '-' && $total >= 60) {
                    $gradeClass = 'grade-pass';
                    $passedCount++;
                } elseif($total !== '-' && $total < 60) {
                    $gradeClass = 'grade-fail';
                }
                
                if($status == 'Y') {
                    $statusClass = 'status-y';
                    $statusText = 'Approved';
                } elseif($status == '') {
                    $statusClass = 'status-pending';
                    $statusText = 'Pending';
                } else {
                    $statusClass = 'status-n';
                    $statusText = 'Not Approved';
                }
                
                
                $studentName = htmlspecialchars($student['slast'] . ' ' . $student['sfirst'] . ' ' . $student['sother']);
            ?>
            <tr>
                <td><?php echo $sn; ?></td>
                <td><?php echo htmlspecialchars($student['studentid']); ?></td>
                <td><?php echo $studentName; ?></td>
                <td class="score-cell"><?php echo $ca; ?></td>
                <td class="score-cell"><?php echo $exam; ?></td>
                <td class="score-cell <?php echo $gradeClass; ?>"><?php echo $total; ?></td>
                <td class="score-cell"><?php echo getGrade($total); ?></td>
                <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <?php if($totalStudents > 0): ?>
    <div class="stats-bar" style="background: #e8f4f8; padding: 10px; margin-top: 15px; border-radius: 5px; display: flex; justify-content: space-around;">
        <div><strong>Total Students:</strong> <?php echo $totalStudents; ?></div>
        <div><strong>Passed:</strong> <span style="color: #28a745;"><?php echo $passedCount; ?></span></div>
        <div><strong>Failed:</strong> <span style="color: #dc3545;"><?php echo $totalStudents - $passedCount; ?></span></div>
        <div><strong>Success Rate:</strong> <?php echo round(($passedCount/$totalStudents)*100, 1); ?>%</div>
    </div>
    <?php endif; ?>
    
    <div class="action-buttons">
        <?php if($course['resultsatus'] != 'Approved'): ?>
        <form method="post" action="" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to APPROVE this result?');">
            <input type="hidden" name="ccode" value="<?php echo htmlspecialchars($courseCode); ?>">
            <input type="hidden" name="ses" value="<?php echo htmlspecialchars($session); ?>">
            <button type="submit" name="approve" class="btn-approve">
                <i class="fa fa-check"></i> Approve Result
            </button>
        </form>
        <?php endif; ?>
        
        <?php if($course['resultsatus'] != 'Approved'): ?>
        <form method="post" action="" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to DISAPPROVE this result? This will allow the lecturer to edit scores.');">
            <input type="hidden" name="ccode" value="<?php echo htmlspecialchars($courseCode); ?>">
            <input type="hidden" name="ses" value="<?php echo htmlspecialchars($session); ?>">
            <button type="submit" name="disapproved" class="btn-disapprove">
                <i class="fa fa-times"></i> Disapprove Result
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>
<?php 
function getGrade($score) {
    if($score >= 95) return 'A+';
    elseif($score >= 90) return 'A';
    elseif($score >= 85) return 'B+';
    elseif($score >= 80) return 'B';
    elseif($score >= 75) return 'C+';
    elseif($score >= 70) return 'C';
    elseif($score >= 65) return 'D+';
    elseif($score >= 60) return 'D';
    else return 'F';
}?>