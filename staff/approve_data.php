<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

// Get current JUPEB session configuration
$jupebSession = mysqli_fetch_assoc(mysqli_query($con, "SELECT description, session, deadline FROM resultsession WHERE description='Jupeb'"));
$description = $jupebSession['description'];

// Handle AJAX request for student payment details
if (isset($_POST['userid'])) {
    $paymentId = mysqli_real_escape_string($con, $_POST['userid']);
    $_SESSION['appl'] = $paymentId;
    
    // Get student ID from payment record
    $paymentData = mysqli_fetch_assoc(mysqli_query($con, "SELECT studentid FROM studentpayment WHERE id='$paymentId'"));
    
    if (!$paymentData) {
        echo '<div class="alert alert-danger">Payment record not found!</div>';
        exit();
    }
    
    $studentId = $paymentData['studentid'];
    
    // Get personal data separately (not as JOIN to avoid duplicates)
    $personalQuery = "SELECT p.*, s.* 
                  FROM personaldata p
                  LEFT JOIN student s ON (s.studentid = p.studentid OR s.studentid = p.applid)
                  WHERE p.applid = '$studentId' OR p.studentid = '$studentId' OR s.studentid='$studentId' 
                  LIMIT 1";
$personalResult = mysqli_query($con, $personalQuery);
$student = mysqli_fetch_assoc($personalResult);
    
    if (!$student) {
        echo '<div class="alert alert-danger">Student record not found!</div>';
        exit();
    }
    
    // Display student information
    displayStudentInfo($student, $paymentId, $studentId);
    
    // Get and display payment history
    $payments = getSuccessfulPayments($con, $studentId);
    displayPaymentHistory($payments);
    
    // Get and display specific payment details
    $paymentDetails = getPaymentDetails($con, $paymentId);
    displayPaymentDetails($paymentDetails);
    
    // Display payment proof
    displayPaymentProof($con, $paymentId);
}

/**
 * Display student personal and admission information
 */
function displayStudentInfo($student, $paymentId, $studentId) {
    ?>
    <div class="row">
        <div class="col-md-3">
            <?php if (!empty($student['passport'])) { ?>
                <img src="../student/<?php echo htmlspecialchars($student['passport']); ?>" class="img-thumbnail" style="width: 300px; height: 200px;" alt="Student Passport" />
            <?php } else { ?>
                <img src="../images/default-avatar.png" class="img-thumbnail" style="width: 300px; height: 200px;" alt="No Image" />
            <?php } ?>
        </div>
        <div class="col-md-9">
            <!-- Personal Information Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr class="info">
                        <th colspan="2">Student Payment Review</th>
                    </tr>
                    <tr>
                        <th width="30%">Full Name</th>
                        <td style="text-transform: capitalize; font-weight: bolder;">
                            <?php echo htmlspecialchars(trim($student['firstname'] . " " . $student['surname'] . " " . $student['lastname'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Application ID</th>
                        <td><?php echo htmlspecialchars($student["applid"]); ?></td>
                    </tr>
                    <tr>
                        <th>Student ID</th>
                        <td><?php echo htmlspecialchars($student["studentid"] ?? 'Not assigned'); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a href="mailto:<?php echo htmlspecialchars($student['email']); ?>"><?php echo htmlspecialchars($student["email"]); ?></a></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><a href="tel:<?php echo htmlspecialchars($student['phone']); ?>"><?php echo htmlspecialchars($student["phone"]); ?></a></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo htmlspecialchars($student["gender"]); ?></td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td><?php echo htmlspecialchars($student["dob"]); ?></td>
                    </tr>
                    <tr>
                        <th>Nationality</th>
                        <td><?php echo htmlspecialchars($student['nationality']); ?></td>
                    </tr>
                    <?php if ($student['nationality'] == "Foreign") { ?>
                        <tr>
                            <th>Country</th>
                            <td><?php echo htmlspecialchars($student["state"]); ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <th>State</th>
                            <td><?php echo htmlspecialchars($student["state"]); ?></td>
                        </tr>
                        <tr>
                            <th>L.G.A</th>
                            <td><?php echo htmlspecialchars($student["lga"]); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th>Home Address</th>
                        <td><?php echo htmlspecialchars($student["address"]); ?></td>
                    </tr>
                    
                    <!-- Admission Information -->
                    <tr class="info">
                        <th colspan="2">Admission Information</th>
                    </tr>
                    <tr>
                        <th>Academic Session</th>
                        <td><?php echo htmlspecialchars($student["acadsession"] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Programme</th>
                        <td><?php echo htmlspecialchars($student['programme'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Admission Type</th>
                        <td><?php echo htmlspecialchars($student['apptype'] ?? 'N/A'); ?></td>
                    </tr>
                
            </div>
        </div>
    </div>
    <?php
}

/**
 * Get successful payments for a student
 */
function getSuccessfulPayments($con, $studentId) {
    $paymentQuery = "SELECT * FROM studentpayment WHERE studentid = '$studentId' AND status = 'Success' ORDER BY purpose";
    $paymentResult = mysqli_query($con, $paymentQuery);
    return mysqli_fetch_all($paymentResult, MYSQLI_ASSOC);
}

/**
 * Get total amount paid by student
 */
function getTotalAmountPaid($con, $studentId) {
    $totalQuery = "SELECT SUM(amount) AS total FROM studentpayment WHERE studentid = '$studentId' AND status = 'Success'";
    $totalResult = mysqli_query($con, $totalQuery);
    $totalAmount = mysqli_fetch_assoc($totalResult);
    return $totalAmount['total'] ?? 0;
}

/**
 * Display successful payment history
 */
function displayPaymentHistory($payments) {
    $totalAmount = array_sum(array_column($payments, 'amount'));
    ?>
    
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <h4>Payment History</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size: 13px;">
                    <thead>
                        <tr class="active">
                            <th width="5%">#</th>
                            <th width="15%">Session</th>
                            <th width="15%">Amount Paid</th>
                            <th width="15%">Payment Type</th>
                            <th width="20%">Purpose</th>
                            <th width="20%">Reference</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($payments) > 0) { ?>
                            <?php $counter = 1; ?>
                            <?php foreach ($payments as $payment) { ?>
                                <?php $paymentType = !empty($payment['semester']) ? htmlspecialchars($payment['semester']) : 'Full Payment'; ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($payment['section']); ?></td>
                                    <td>₦<?php echo number_format($payment['amount'], 2); ?></td>
                                    <td><?php echo $paymentType; ?></td>
                                    <td><?php echo htmlspecialchars($payment['purpose']); ?></td>
                                    <td><?php echo htmlspecialchars($payment['paymentreference']); ?></td>
                                    <td><span class="label label-success"><?php echo htmlspecialchars($payment['status']); ?></span></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7" class="text-center">No payment records found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="info">
                            <td colspan="2" style="text-align: right; font-weight: bold;">Total:</td>
                            <td colspan="5" style="font-weight: bold;">₦<?php echo number_format($totalAmount, 2); ?></td>
                        </tr>
                    </tfoot>
                66
            </div>
        </div>
    </div>
    <?php
}

/**
 * Get specific payment details
 */
function getPaymentDetails($con, $paymentId) {
    $detailQuery = "SELECT * FROM studentpayment WHERE id = '$paymentId'";
    $detailResult = mysqli_query($con, $detailQuery);
    return mysqli_fetch_all($detailResult, MYSQLI_ASSOC);
}

/**
 * Display specific payment details
 */
function displayPaymentDetails($paymentDetails) {
    ?>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <h4>Payment Details</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="active">
                            <th width="5%">#</th>
                            <th width="20%">Session</th>
                            <th width="15%">Amount</th>
                            <th width="25%">Purpose</th>
                            <th width="20%">Teller/Receipt No</th>
                            <th width="15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($paymentDetails) > 0) { ?>
                            <?php $counter = 1; ?>
                            <?php foreach ($paymentDetails as $detail) { ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($detail['section']); ?></td>
                                    <td>₦<?php echo number_format($detail['amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($detail['purpose']); ?></td>
                                    <td><?php echo htmlspecialchars($detail['paymentreference']); ?></td>
                                    <td>
                                        <?php if ($detail['status'] == 'Success') { ?>
                                            <span class="label label-success"><?php echo htmlspecialchars($detail['status']); ?></span>
                                        <?php } else { ?>
                                            <span class="label label-danger"><?php echo htmlspecialchars($detail['status']); ?></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="6" class="text-center">No payment details found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                66
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display payment proof/documentation
 */
function displayPaymentProof($con, $paymentId) {
    $proofQuery = "SELECT * FROM studentpayment WHERE id = '$paymentId'";
    $proofResult = mysqli_query($con, $proofQuery);
    $proofs = mysqli_fetch_all($proofResult, MYSQLI_ASSOC);
    ?>
    
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <h4>Payment Documentation</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="width: 50%;">
                    <thead>
                        <tr class="active">
                            <th width="30%">Document Type</th>
                            <th width="70%">Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($proofs) > 0) { ?>
                            <?php foreach ($proofs as $proof) { ?>
                                <tr>
                                    <td>Payment Proof</td>
                                    <td>
                                        <?php if (!empty($proof['proof']) && file_exists("evidence/" . $proof['proof'])) { ?>
                                            <a href="evidence/<?php echo htmlspecialchars($proof['proof']); ?>" target="_blank">
                                                <img src="evidence/<?php echo htmlspecialchars($proof['proof']); ?>" class="img-fluid img-thumbnail" style="width: 400px; height: 200px; object-fit: cover;" alt="Payment Proof" />
                                            </a>
                                        <?php } else { ?>
                                            <span class="text-muted">No proof uploaded</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="2" class="text-center">No documentation found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                66
            </div>
        </div>
    </div>
    <?php
}
?>