<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
require "phpmailer/class.phpmailer.php";
$mail = new PHPMailer();

if(!isset($_POST['coursecode']) || !isset($_POST['session'])) {
    echo '<div class="alert alert-danger">Invalid request.</div>';
    exit;
}

$courseCode = mysqli_real_escape_string($con, $_POST['coursecode']);
$session = mysqli_real_escape_string($con, $_POST['session']);

// Handle disapprove action
if(isset($_POST['disapproved'])) {
    $ccode = mysqli_real_escape_string($con, $_POST['ccode']);
    $ses = mysqli_real_escape_string($con, $_POST['ses']);
    $disapproveReason = isset($_POST['disapprove_reason']) ? mysqli_real_escape_string($con, $_POST['disapprove_reason']) : 'No specific reason provided.';
    
    $upda = mysqli_query($con, "UPDATE allocatecourse SET resultsatus='' WHERE coursecord='YES' AND acadsession='$ses' AND coursecode = '$ccode'");
    $updates = mysqli_query($con, "UPDATE result SET status='' WHERE acadsession='$ses' AND coursecode = '$ccode'");
    
    if($upda) {
        $succ = 'Result has been disapproved. Course Master can now edit scores.';
        
        // Get staff email for notification
        $pps = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM allocatecourse WHERE coursecord='Yes' AND coursecode = '$ccode'"));
        $stuid = $pps['staffid'];
        $pp = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM people WHERE staffid ='$stuid'"));
        $unmail = $pp['uniemail'];
        
        // Email configuration variables
        $host='portal.edocohest.edu.ng';
        $pass='Passw20ord.';
        $user='notice@portal.edocohest.edu.ng'; 
        
        hodmails($con, $unmail, $ccode, $ses, $host, $pass, $user, $mail, $disapproveReason);
        
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> ' . $succ . '
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>';
        
        // Refresh the page content after disapproval
        echo '<script>setTimeout(function(){ location.reload(); }, 2000);</script>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> Error disapproving result. Please try again.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>';
    }
}

// Get course information
$courseQuery = "SELECT ac.*, p.firstname, p.lastname, p.title, c.title as course_title
                FROM allocatecourse ac
                JOIN people p ON ac.staffid = p.staffid
                JOIN course c ON ac.coursecode = c.coursecode
                WHERE ac.coursecode = '$courseCode' AND ac.acadsession = '$session'";
$courseResult = mysqli_query($con, $courseQuery);
$course = mysqli_fetch_assoc($courseResult);

if(!$course) {
    echo '<div class="alert alert-warning">Course not found.</div>';
    exit;
}

// Get student results
$studentsQuery = "SELECT r.*, s.firstname, s.lastname, s.othername
                  FROM result r
                  JOIN student s ON r.studentid = s.studentid
                  WHERE r.coursecode = '$courseCode' AND r.acadsession = '$session'
                  ORDER BY r.studentid";
$studentsResult = mysqli_query($con, $studentsQuery);

$totalStudents = mysqli_num_rows($studentsResult);
$passedCount = 0;
$gradePoints = 0;
?>

<style>
    .course-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .result-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .result-table th {
        background: #2c3e50;
        color: white;
        padding: 10px;
        text-align: left;
    }
    .result-table td {
        padding: 8px 10px;
        border-bottom: 1px solid #ddd;
    }
    .result-table tr:hover {
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
    .summary-stats {
        display: flex;
        justify-content: space-around;
        background: #e8f4f8;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .stat-box {
        text-align: center;
        padding: 10px;
    }
    .stat-value {
        font-size: 24px;
        font-weight: bold;
    }
    .btn-disapprove {
        background: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s ease;
        margin-left: 10px;
    }
    .btn-disapprove:hover {
        background: #c82333;
        transform: scale(1.02);
    }
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #dee2e6;
    }
    .btn-print {
        background: #3498db;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .btn-print:hover {
        background: #2980b9;
        transform: scale(1.02);
    }
    .badge-approved {
        background: #28a745;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    .badge-pending {
        background: #ffc107;
        color: #856404;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    .confirmation-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    .confirmation-content {
        background: white;
        padding: 25px;
        border-radius: 10px;
        max-width: 500px;
        width: 90%;
        text-align: center;
    }
    .confirmation-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }
    .btn-confirm {
        background: #dc3545;
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn-cancel {
        background: #6c757d;
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .reason-textarea {
        width: 100%;
        padding: 10px;
        margin: 15px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: inherit;
        resize: vertical;
    }
    .reason-textarea:focus {
        outline: none;
        border-color: #dc3545;
    }
    .warning-text {
        color: #856404;
        font-size: 12px;
        margin-top: 5px;
    }
    .char-count {
        font-size: 11px;
        color: #6c757d;
        text-align: right;
        margin-top: 5px;
    }
</style>

<div class="course-info-card">
    <h4><i class="fa fa-book"></i> <?php echo htmlspecialchars($courseCode); ?></h4>
    <p><strong>Course Title:</strong> <?php echo htmlspecialchars($course['course_title']); ?></p>
    <p><strong>Session:</strong> <?php echo htmlspecialchars($session); ?> | 
       <strong>Semester:</strong> <?php echo htmlspecialchars($course['semester']); ?> |
       <strong>Level:</strong> <?php echo htmlspecialchars($course['level']); ?></p>
    <p><strong>Lecturer:</strong> <?php echo htmlspecialchars($course['title'] . ' ' . $course['firstname'] . ' ' . $course['lastname']); ?></p>
    <p><strong>Approval Date:</strong> <?php echo htmlspecialchars($course['appdate']); ?></p>
    <p><strong>Status:</strong> 
        <?php if($course['resultsatus'] == 'Approved'): ?>
            <span class="badge-approved"><i class="fa fa-check"></i> Approved</span>
        <?php else: ?>
            <span class="badge-pending"><i class="fa fa-clock-o"></i> Pending</span>
        <?php endif; ?>
    </p>
</div>

<table class="result-table">
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
        while($student = mysqli_fetch_assoc($studentsResult)) {
            $sn++;
            $totalScore = (int)$student['ca'] + (int)$student['exam'];
            $gradeClass = ($totalScore >= 60) ? 'grade-pass' : 'grade-fail';
            
            if($totalScore >= 60 && $student['status'] == 'Y') {
                $passedCount++;
            }
            
            // Calculate grade points for average
            if($totalScore >= 70) $gradePoints += 4;
            elseif($totalScore >= 60) $gradePoints += 3;
            elseif($totalScore >= 50) $gradePoints += 2;
            elseif($totalScore >= 45) $gradePoints += 1;
            elseif($totalScore >= 40) $gradePoints += 0;
            else $gradePoints += 0;
        ?>
        <tr>
            <td><?php echo $sn; ?></td>
            <td><?php echo htmlspecialchars($student['studentid']); ?></td>
            <td><?php echo htmlspecialchars($student['lastname'] . ' ' . $student['firstname'] . ' ' . $student['othername']); ?></td>
            <td class="text-center"><?php echo $student['ca']; ?></td>
            <td class="text-center"><?php echo $student['exam']; ?></td>
            <td class="text-center <?php echo $gradeClass; ?>"><strong><?php echo $totalScore; ?></strong></td>
            <td class="text-center"><?php echo getGrade($totalScore); ?></td>
            <td class="text-center">
                <?php if($student['status'] == 'Y'): ?>
                    <span class="badge-approved">Approved</span>
                <?php else: ?>
                    <span class="badge-pending">Pending</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<div class="summary-stats">
    <div class="stat-box">
        <div class="stat-value"><?php echo $totalStudents; ?></div>
        <div>Total Students</div>
    </div>
    <div class="stat-box">
        <div class="stat-value" style="color: #28a745;"><?php echo $passedCount; ?></div>
        <div>Passed</div>
    </div>
    <div class="stat-box">
        <div class="stat-value" style="color: #dc3545;"><?php echo $totalStudents - $passedCount; ?></div>
        <div>Failed</div>
    </div>
    <div class="stat-box">
        <div class="stat-value"><?php echo $totalStudents > 0 ? round(($passedCount/$totalStudents)*100, 1) : 0; ?>%</div>
        <div>Success Rate</div>
    </div>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <button class="btn-print" onclick="printCourseDetails()">
        <i class="fa fa-print"></i> Print Details
    </button>
    
    <!-- DISAPPROVE BUTTON -->
    <button class="btn-disapprove" onclick="openDisapproveModal()">
        <i class="fa fa-times"></i> Disapprove Result
    </button>
</div>

<!-- Disapprove Modal with Reason Textarea -->
<div id="confirmModal" class="confirmation-modal">
    <div class="confirmation-content">
        <i class="fa fa-exclamation-triangle" style="font-size: 48px; color: #dc3545;"></i>
        <h3 style="margin: 15px 0;">Disapprove Result</h3>
        <p>Are you sure you want to disapprove this result?</p>
        <p><strong>Course:</strong> <span id="confirmCourse"><?php echo htmlspecialchars($courseCode); ?></span></p>
        <p><strong>Session:</strong> <span id="confirmSession"><?php echo htmlspecialchars($session); ?></span></p>
        
        <!-- Reason Textarea -->
        <div style="text-align: left; margin: 15px 0;">
            <label style="font-weight: bold; margin-bottom: 5px; display: block;">
                Reason for Disapproval <span style="color: #dc3545;">*</span>
            </label>
            <textarea id="disapproveReason" class="reason-textarea" rows="4" 
                      placeholder="Please provide a detailed reason why this result is being disapproved. This will be sent to the course lecturer." 
                      maxlength="500"></textarea>
            <div class="char-count">
                <span id="charCount">0</span> / 500 characters
            </div>
            <div class="warning-text">
                <i class="fa fa-info-circle"></i> The reason will be included in the email notification to the lecturer.
            </div>
        </div>
        
        <div style="color: #856404; font-size: 13px; text-align: left; background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0;">
            <strong><i class="fa fa-info-circle"></i> This action will:</strong>
            <ul style="margin: 5px 0 0 20px;">
                <li>Remove the approval status</li>
                <li>Allow the lecturer to edit scores</li>
                <li>Send a notification email with the reason to the lecturer</li>
            </ul>
        </div>
        
        <div class="confirmation-buttons">
            <button class="btn-cancel" onclick="closeConfirmModal()">
                <i class="fa fa-times"></i> Cancel
            </button>
            <button class="btn-confirm" onclick="submitDisapprovalWithReason()">
                <i class="fa fa-check"></i> Yes, Disapprove
            </button>
        </div>
    </div>
</div>

<!-- Hidden form for disapproval submission -->
<form id="disapproveForm" method="post" style="display: none;">
    <input type="hidden" name="ccode" value="<?php echo htmlspecialchars($courseCode); ?>">
    <input type="hidden" name="ses" value="<?php echo htmlspecialchars($session); ?>">
    <input type="hidden" name="disapproved" value="1">
    <input type="hidden" name="coursecode" value="<?php echo htmlspecialchars($courseCode); ?>">
    <input type="hidden" name="session" value="<?php echo htmlspecialchars($session); ?>">
    <input type="hidden" name="disapprove_reason" id="disapproveReasonHidden" value="">
</form>

<script>
    // Character counter for textarea
    const textarea = document.getElementById('disapproveReason');
    if(textarea) {
        textarea.addEventListener('input', function() {
            const charCount = this.value.length;
            document.getElementById('charCount').innerText = charCount;
            
            // Change color if exceeding limit
            if(charCount > 500) {
                document.getElementById('charCount').style.color = '#dc3545';
            } else {
                document.getElementById('charCount').style.color = '#6c757d';
            }
        });
    }
    
    function openDisapproveModal() {
        // Clear previous reason
        if(textarea) {
            textarea.value = '';
            document.getElementById('charCount').innerText = '0';
        }
        document.getElementById('confirmModal').style.display = 'flex';
    }
    
    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }
    
    function submitDisapprovalWithReason() {
        const reason = document.getElementById('disapproveReason').value.trim();
        
        if(reason === '') {
            alert('Please provide a reason for disapproving this result.');
            document.getElementById('disapproveReason').focus();
            return false;
        }
        
        if(reason.length > 500) {
            alert('Reason cannot exceed 500 characters. Please shorten your reason.');
            return false;
        }
        
        // Set the reason in hidden field
        document.getElementById('disapproveReasonHidden').value = reason;
        
        // Show loading state on button
        const confirmBtn = document.querySelector('.btn-confirm');
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        confirmBtn.disabled = true;
        
        // Submit the form
        document.getElementById('disapproveForm').submit();
    }
    
    function printCourseDetails() {
        var content = document.querySelector('.course-info-card').innerHTML;
        content += document.querySelector('.result-table').outerHTML;
        content += document.querySelector('.summary-stats').outerHTML;
        
        var printWindow = window.open('', '_blank', 'width=900,height=600');
        printWindow.document.write(`
            <html>
            <head>
                <title>Course Result Details - <?php echo htmlspecialchars($courseCode); ?></title>
                <link rel="stylesheet" href="css/bootstrap.min.css">
                <style>
                    body { padding: 20px; font-family: Arial, sans-serif; }
                    .course-info-card { background: #667eea; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
                    .result-table { width: 100%; border-collapse: collapse; }
                    .result-table th { background: #2c3e50; color: white; padding: 8px; }
                    .result-table td { padding: 8px; border-bottom: 1px solid #ddd; }
                    .summary-stats { display: flex; justify-content: space-around; background: #e8f4f8; padding: 15px; border-radius: 8px; margin-top: 20px; }
                    .grade-pass { color: #28a745; font-weight: bold; }
                    .grade-fail { color: #dc3545; font-weight: bold; }
                    @media print {
                        body { margin: 0; padding: 10px; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                ${content}
                <div class="text-center" style="margin-top: 30px;">
                    <p>Printed on: ${new Date().toLocaleString()}</p>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById('confirmModal');
        if (event.target == modal) {
            closeConfirmModal();
        }
    }
</script>

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
}

function hodmails($con, $unmail, $ccode, $ses, $host, $pass, $user, $mail, $reason) {
    $to = $unmail ?: 'default@email.com';
    
    try {
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->SMTPKeepAlive = true;
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom($user, 'Result Disapproval Notification');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Result Disapproval Notification - ' . $ccode;
        
        $message = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { padding: 20px; }
                .header { background: #dc3545; color: white; padding: 15px; text-align: center; }
                .content { padding: 20px; }
                .footer { background: #f8f9fa; padding: 10px; text-align: center; font-size: 12px; }
                .reason-box { 
                    background: #fff3cd; 
                    border-left: 4px solid #ffc107; 
                    padding: 15px; 
                    margin: 15px 0; 
                    border-radius: 5px;
                }
                .reason-title { font-weight: bold; margin-bottom: 10px; color: #856404; }
                .reason-text { color: #333; line-height: 1.5; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h3>Result Disapproval Notification</h3>
                </div>
                <div class="content">
                    <p>Dear Course Lecturer,</p>
                    <p>This is to notify you that the results for the following course have been <strong style="color: #dc3545;">DISAPPROVED</strong> by the Head of Department.</p>
                    
                    <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                        <tr><th style="background: #f2f2f2; padding: 8px; text-align: left;">Course Code</th><td style="padding: 8px;">' . htmlspecialchars($ccode) . '</td></tr>
                        <tr><th style="background: #f2f2f2; padding: 8px; text-align: left;">Session</th><td style="padding: 8px;">' . htmlspecialchars($ses) . '</td></tr>
                    ?</table>
                    
                    <!-- Reason for Disapproval -->
                    <div class="reason-box">
                        <div class="reason-title"><i class="fa fa-info-circle"></i> Reason for Disapproval:</div>
                        <div class="reason-text">' . nl2br(htmlspecialchars($reason)) . '</div>
                    </div>
                    
                    <p><strong>Action Required:</strong> Please review and edit the scores based on the feedback above, then resubmit for approval.</p>
                    
                    <p>Thank you for your cooperation.</p>
                    
                    <hr>
                    <p><small>This is an automated message from the Result Management System. Please do not reply to this email.</small></p>
                </div>
                <div class="footer">
                    &copy; ' . date('Y') . ' Result Management System
                </div>
            </div>
        </body>
        </html>';
        
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAllRecipients();
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
    }
}
?>