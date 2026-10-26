<?php
session_start();
set_time_limit(500);
ini_set('max_execution_time', '500');
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';
require "phpmailer/class.phpmailer.php";
$mail = new PHPMailer();

if (strlen($_SESSION['staffid']) == "") {
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
    
    $dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT semester,session,deadline FROM resultsession WHERE description='admission processing'"));
    $ses = $dat['session'];
    $admy = $ses;
    $succ = '';
    $error = '';
    
    // Approve Payment - Single or Bulk
    if (isset($_POST['approve_payment'])) {
        if (isset($_POST['checks'])) {
            foreach ($_POST['checks'] as $check) {
                // Get student details
                $student = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE id='$check'"));
                $applicant = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='{$student['studentid']}'"));
                $personal = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM personaldata WHERE applid='{$student['studentid']}'"));
                
                // Update payment status
                $update = mysqli_query($con, "UPDATE studentpayment SET status='Success' WHERE id='$check'");
                
                if ($update) {
                    // Send approval notification
                    sendPaymentApprovalSMS($student['studentid'], $applicant, $personal, $con);
                    sendPaymentApprovalEmail($student['studentid'], $applicant, $personal, $con, $mail, $orgname, $schadress, $logo, $hostna, $passd, $usern);
                    $succ = 'Payment(s) Successfully Approved and Applicant(s) Notified';
                    $showModalS = 'true';
                } else {
                    $error = 'An Error Occurred While Approving Payment. Please Try Again!';
                    $showModalEr = 'true';
                }
            }
        } else {
            $error = 'Please Select At Least One Application to Approve';
            $showModalEr = 'true';
        }
    }
    
    // Reject Payment - Single
    if (isset($_POST["reject_payment"])) {
        $payment_id = $_POST['payment_id'];
        $reject_reason = mysqli_real_escape_string($con, $_POST['reject_reason']);
        
        if (empty($reject_reason)) {
            $error = 'Please provide a reason for rejection';
            $showModalEr = 'true';
        } else {
            // Get student details
            $student = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE id='$payment_id'"));
            $applicant = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='{$student['studentid']}'"));
            $personal = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM personaldata WHERE applid='{$student['studentid']}'")); 
            
            // Update payment status
            $update = mysqli_query($con, "UPDATE studentpayment SET status='Rejected' WHERE id='$payment_id'");
            // After updating payment status to 'Rejected', add: 
$insert_rejection = mysqli_query($con, "INSERT INTO payment_rejections (payment_id, studentid, reason, rejected_by, rejected_at) 
VALUES ('$payment_id', '{$student['studentid']}', '$reject_reason', '{$_SESSION['staffid']}', NOW())");
            if ($update) {
                // Send rejection notification
                sendPaymentRejectionSMS($student['studentid'], $applicant, $personal, $reject_reason, $con);
                sendPaymentRejectionEmail($student['studentid'], $applicant, $personal, $reject_reason, $con, $mail, $orgname, $schadress, $logo, $hostna, $passd, $usern);
                $succ = 'Payment has been Rejected and Applicant Notified';
                $showModalS = 'true';
            } else {
                $error = 'An Error Occurred While Rejecting Payment';
                $showModalEr = 'true';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlentities($orgname); ?> - Payment Verification</title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/receipt.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <link rel="stylesheet" href="css/animate.min.css" media="screen">
    <style>
        .payment-evidence-img {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }
        .payment-evidence-img:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .evidence-container {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 10px 0;
        }
        .status-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-pending {
            background: #ffc107;
            color: #000;
        }
        .status-success {
            background: #28a745;
            color: #fff;
        }
        .status-rejected {
            background: #dc3545;
            color: #fff;
        }
        /* Increased modal width */
        .modal-lg-custom {
            max-width: 1000px;
            width: 90%;
        }
        .info-row {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }
        .evidence-click-hint {
            font-size: 11px;
            color: #6c757d;
            margin-top: 5px;
        }
        .evidence-click-hint i {
            margin-right: 3px;
        }
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }
        /* Fix for modal display */
        .modal {
            z-index: 9999;
        }
        .modal-backdrop {
            z-index: 9990;
        }
        /* Table styling */
        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .table-bordered {
            border: 1px solid #ddd;
        }
        .table-bordered > thead > tr > th,
        .table-bordered > tbody > tr > td,
        .table-bordered > tfoot > tr > th {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: middle;
        }
        .table-striped > tbody > tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table > thead > tr > th {
            background-color: #2b5f5a;
            color: white;
            font-weight: bold;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        .panel {
            margin-bottom: 20px;
            background-color: #fff;
            border: 1px solid transparent;
            border-radius: 4px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.05);
        }
        .panel-body {
            padding: 15px;
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        
        <div class="content-wrapper">
            <div class="content-container">
                <?php
                $paych = mysqli_fetch_assoc(mysqli_query($con, "SELECT position FROM staffprive WHERE staffid='" . $_SESSION['staffid'] . "'"));
                if ($paych['position'] == "accountant") {
                    include('includes/accbar.php');
                } elseif ($paych['position'] == "admission") {
                    include('includes/admsec.php');
                } elseif ($paych['position'] == "superadmin") {
                    include('includes/supbar.php');
                } elseif ($paych['position'] == "subadmin") {
                    include('includes/subbar.php');
                } elseif ($paych['position'] == "admin_officer") {
                    include('includes/admof.php');
                } else {
                    include('includes/leftbar.php');
                }
                ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-12">
                                <h4 class="title">Payment Verification for <?php echo $admy; ?> Session</h4>
                                <p class="text-muted">Review and verify applicant payment evidence</p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <?php
                                        if ($paych['position'] == "accountant" || $paych['position'] == "subadmin" || $paych['position'] == "admin_officer" || $paych['position'] == "superadmin") {
                                            // Get students with pending payment verification
                                            $sql = "SELECT sp.*, a.firstname, a.lastname, a.othername, a.programme, a.phoneno, a.email 
                                                    FROM studentpayment sp 
                                                    JOIN applicant a ON sp.studentid = a.applid 
                                                    WHERE (sp.status = 'Pending Verification' OR sp.status='Rejected') 
                                                    AND sp.purpose = 'Application Fee'
                                                    ORDER BY sp.paytime DESC";
                                            
                                            $query = mysqli_query($con, $sql);
                                            $num_rows = mysqli_num_rows($query);
                                        ?>
                                            <div class="table-responsive">
                                                <form action="" method="post">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="9" style="background: #f5f5f5; color: #333;">
                                                                    <strong>Total Pending Applications:</strong> 
                                                                    <span style="color: red; font-weight: bold;"><?php echo $num_rows; ?></span>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th width="15%">Application ID</th>
                                                                <th width="20%">Full Name</th>
                                                                <th width="15%">Programme</th>
                                                                <th width="12%">Phone</th>
                                                                <th width="12%">Payment Date</th>
                                                                <th width="8%">Amount</th>
                                                                <th width="8%">Status</th>
                                                                <th width="8%">View</th>
                                                                <th width="5%"><input type="checkbox" name="check_all" id="check_all"> Select</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if ($num_rows > 0) {
                                                                $cnt = 1;
                                                                while ($result = mysqli_fetch_assoc($query)) {
                                                                    $fullname = trim($result['lastname'] . " " . $result['firstname'] . " " . $result['othername']);
                                                            ?>
                                                                    <tr>
                                                                        <td align="center"><?php echo $cnt; ?></td>
                                                                        <td><?php echo htmlspecialchars($result['studentid']); ?></td>
                                                                        <td><?php echo htmlspecialchars($fullname); ?></td>
                                                                        <td><?php echo htmlspecialchars($result['programme']); ?></td>
                                                                        <td><?php echo htmlspecialchars($result['phoneno']); ?></td>
                                                                        <td><?php echo date("d-m-Y H:i", strtotime($result['paytime'])); ?></td>
                                                                        <td><?php echo htmlspecialchars($result['status']); ?></td>
                                                                        <td align="right">₦<?php echo number_format($result['amount'], 2); ?></td>
                                                                        <td align="center">
                                                                            <button type="button" class="btn btn-info btn-sm view-evidence" 
                                                                                    data-id="<?php echo $result['id']; ?>"
                                                                                    data-studentid="<?php echo htmlspecialchars($result['studentid']); ?>"
                                                                                    data-name="<?php echo htmlspecialchars($fullname); ?>"
                                                                                    data-phone="<?php echo htmlspecialchars($result['phoneno']); ?>"
                                                                                    data-email="<?php echo htmlspecialchars($result['email']); ?>"
                                                                                    data-programme="<?php echo htmlspecialchars($result['programme']); ?>"
                                                                                    data-amount="<?php echo number_format($result['amount'], 2); ?>"
                                                                                    data-evidence="<?php echo htmlspecialchars($result['payment_evidence']); ?>"
                                                                                    data-status="<?php echo htmlspecialchars($result['status']); ?>"
                                                                                    data-paydate="<?php echo date("d-m-Y H:i", strtotime($result['paytime'])); ?>">
                                                                                <i class="fa fa-eye"></i> View
                                                                            </button>
                                                                        </td>
                                                                        <td align="center">
                                                                            <input type="checkbox" value="<?php echo $result['id']; ?>" name="checks[]" class="checkitem">
                                                                        </td>
                                                                    </tr>
                                                            <?php
                                                                    $cnt++;
                                                                }
                                                            } else {
                                                            ?>
                                                                <tr>
                                                                    <td colspan="9" align="center" style="color: green; padding: 30px;">
                                                                        <i class="fa fa-check-circle fa-2x"></i><br>
                                                                        No pending payment verification requests
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                        <?php if ($num_rows > 0) { ?>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="8" align="right">
                                                                    <button type="submit" name="approve_payment" class="btn btn-success" onclick="return confirm('Are you sure you want to approve selected payments?')">
                                                                        <i class="fa fa-check"></i> Approve Selected
                                                                    </button>
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                        <?php } ?>
                                                    </table>
                                                </form>
                                            </div>
                                        <?php } else { ?>
                                            <div class="alert alert-danger">
                                                <i class="fa fa-exclamation-triangle"></i> 
                                                Sorry Dear: <strong><?php echo htmlentities($fullname); ?></strong> 
                                                You Don't Have Access to this Page. Thank You.
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Evidence Modal - Increased Width -->
    <div class="modal fade" id="evidenceModal" tabindex="-1" role="dialog" aria-labelledby="evidenceModalLabel">
        <div class="modal-dialog modal-lg-custom" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #2b5f5a; color: white;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title" id="evidenceModalLabel"><i class="fa fa-receipt"></i> Payment Evidence Details</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label"><i class="fa fa-id-card"></i> Application ID:</span>
                                <span id="modal_studentid"></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="fa fa-user"></i> Full Name:</span>
                                <span id="modal_name"></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="fa fa-phone"></i> Phone Number:</span>
                                <span id="modal_phone"></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="fa fa-envelope"></i> Email:</span>
                                <span id="modal_email"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label"><i class="fa fa-graduation-cap"></i> Programme:</span>
                                <span id="modal_programme"></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="fa fa-money"></i> Amount Paid:</span>
                                <span id="modal_amount"></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label"><i class="fa fa-calendar"></i> Payment Date:</span>
                                <span id="modal_paydate"></span>
                            </div>
                        </div>
                    </div>
                    <div class="evidence-container">
                        <h5><i class="fa fa-image"></i> Payment Evidence</h5>
                        <div id="evidence_image"></div>
                        <div class="evidence-click-hint">
                            <i class="fa fa-hand-pointer"></i> Click on the image to view full size in a new tab
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="approveSingleBtn">
                        <i class="fa fa-check"></i> Approve Payment
                    </button>
                    <button type="button" class="btn btn-danger" id="rejectSingleBtn" data-toggle="modal" data-target="#rejectModal">
                        <i class="fa fa-times"></i> Reject Payment
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reject Reason Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #dc3545; color: white;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Reject Payment</h4>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <input type="hidden" name="payment_id" id="reject_payment_id" value="">
                        <div class="form-group">
                            <label>Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea name="reject_reason" id="reject_reason" class="form-control" rows="4" required 
                                      placeholder="Please provide detailed reason for rejecting this payment..."></textarea>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fa fa-info-circle"></i> The applicant will be notified via SMS and Email about this rejection.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="reject_payment" class="btn btn-danger">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #28a745; color: white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title"><i class="fa fa-check-circle"></i> Success</h5>
                </div>
                <div class="modal-body">
                    <p><?php echo htmlentities($succ); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dc3545; color: white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title"><i class="fa fa-exclamation-circle"></i> Error</h5>
                </div>
                <div class="modal-body">
                    <p><?php echo htmlentities($error); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    
    <script>
        $(document).ready(function() {
            console.log("Document ready - Payment Verification Page Loaded");
            
            // Select all checkbox functionality
            $('#check_all').click(function() {
                if ($(this).is(":checked")) {
                    $('.checkitem').prop('checked', true);
                } else {
                    $('.checkitem').prop('checked', false);
                }
            });
            
            // View evidence modal
            $('.view-evidence').click(function(e) {
                e.preventDefault();
                console.log("View button clicked");
                
                // Get data attributes
                var studentid = $(this).data('studentid');
                var name = $(this).data('name');
                var phone = $(this).data('phone');
                var email = $(this).data('email');
                var programme = $(this).data('programme');
                var amount = $(this).data('amount');
                var paydate = $(this).data('paydate');
                var evidence = $(this).data('evidence');
                var paymentId = $(this).data('id');
                
                console.log("Payment ID: " + paymentId);
                console.log("Evidence path: " + evidence);
                
                // Set modal content
                $('#modal_studentid').text(studentid);
                $('#modal_name').text(name);
                $('#modal_phone').text(phone);
                $('#modal_email').text(email);
                $('#modal_programme').text(programme);
                $('#modal_amount').text('₦' + amount);
                $('#modal_paydate').text(paydate);
                
                // Display evidence image
                if (evidence && evidence.trim() !== '') {
                    var evidencePath = '../applys/' + evidence;
                    console.log("Full image path: " + evidencePath);
                    
                    var imgHtml = '<img src="' + evidencePath + '" class="payment-evidence-img" alt="Payment Evidence" style="max-width:100%; max-height:300px;" onerror="this.onerror=null; this.src=\'img/no-image.png\'; this.alt=\'Image not found\';">';
                    $('#evidence_image').html(imgHtml);
                    
                    // Make image clickable to open in new tab
                    $('#evidence_image img').off('click').on('click', function() {
                        window.open(evidencePath, '_blank');
                    });
                } else {
                    $('#evidence_image').html('<p class="text-muted"><i class="fa fa-warning"></i> No payment evidence uploaded</p>');
                }
                
                // Store payment ID for approve/reject actions
                $('#approveSingleBtn').data('payment-id', paymentId);
                $('#reject_payment_id').val(paymentId);
                
                // Show modal
                $('#evidenceModal').modal('show');
            });
            
            // Approve single payment
            $('#approveSingleBtn').click(function() {
                var paymentId = $(this).data('payment-id');
                if (confirm('Are you sure you want to approve this payment?')) {
                    var form = $('<form action="" method="post"></form>');
                    form.append('<input type="hidden" name="approve_payment" value="1">');
                    form.append('<input type="hidden" name="checks[]" value="' + paymentId + '">');
                    $('body').append(form);
                    form.submit();
                }
            });
            
            // Make sure modals can be closed properly
            $('.modal .close').click(function() {
                $(this).closest('.modal').modal('hide');
            });
        });
    </script>
    
    <?php
    if (!empty($showModalS)) {
        echo '<script>$(document).ready(function() { $("#successModal").modal("show"); });</script>';
    }
    if (!empty($showModalEr)) {
        echo '<script>$(document).ready(function() { $("#errorModal").modal("show"); });</script>';
    }
    ?>
</body>
</html>

<?php
// SMS Functions
function sendPaymentApprovalSMS($studentid, $applicant, $personal, $con) {
    $email = "imarve.wiz@gmail.com";
    $password = "Passw20ord";
    $url = "https://edocohest.com/apply/applogin";
    
    $fullname = $applicant['lastname'] . " " . $applicant['firstname'];
    $message = "Dear " . $fullname . ",\n\n";
    $message .= "Your application payment has been successfully verified and approved!\n\n";
    $message .= "Application ID: " . $studentid . "\n";
    $message .= "Password: " . $applicant['phoneno'] . "\n\n";
    $message .= "You can now continue your application by logging in at:\n";
    $message .= $url . "\n\n";
    $message .= "Thank you for choosing Edo State College of Health Sciences and Technology.";
    
    $sender_name = "EDOCOHEST";
    $recipients = $applicant['phoneno'];
    $forcednd = "1";
    
    $data = array(
        "email" => $email,
        "password" => $password,
        "message" => $message,
        "sender_name" => $sender_name,
        "recipients" => $recipients,
        "forcednd" => $forcednd
    );
    $data_string = json_encode($data);
    $ch = curl_init('https://app.multitexter.com/v2/app/sms');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
    $result = curl_exec($ch);
    curl_close($ch);
}

function sendPaymentApprovalEmail($studentid, $applicant, $personal, $con, $mail, $orgname, $schadress, $logo, $host, $pass, $user) {
    try {
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom($user, 'EDOCOHEST Payment Verification');
        $mail->addAddress($applicant['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Payment Verification Successful - EDOCOHEST Admission';
        
        $fullname = $applicant['lastname'] . " " . $applicant['firstname'];
        $login_url = "https://edocohest.com/apply/applogin";
        
        $message = '
        <html>
        <head>
            <title>Payment Verification Successful</title>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2b5f5a; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .credentials { background: #f0f0f0; padding: 15px; border-radius: 5px; margin: 15px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Payment Verification Successful</h2>
                </div>
                <div class="content">
                    <p>Dear <strong>' . $fullname . '</strong>,</p>
                    <p>Your application payment has been successfully verified and approved!</p>
                    
                    <div class="credentials">
                        <p><strong>Your Login Credentials:</strong></p>
                        <p>Application ID: <strong>' . $studentid . '</strong></p>
                        <p>Password: <strong>' . $applicant['phoneno'] . '</strong></p>
                    </div>
                    
                    <p>Your application is waiting review, you will be notify via sms and email:</p>
                    <p><a href="' . $login_url . '">' . $login_url . '</a></p>
                    
                   
                    <p>Thank you for choosing Edo State College of Health Sciences and Technology.</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ' . $orgname . '. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAllRecipients();
    } catch (Exception $e) {
        // Log error if needed
    }
}

function sendPaymentRejectionSMS($studentid, $applicant, $personal, $reason, $con) {
    $email = "imarve.wiz@gmail.com";
    $password = "Passw20ord";
    
    $fullname = $applicant['lastname'] . " " . $applicant['firstname'];
    $message = "Dear " . $fullname . ",\n\n";
    $message .= "Your application payment has been REJECTED.\n\n";
    $message .= "Reason: " . $reason . "\n\n";
    $message .= "Please visit any Zenith Bank branch to make a fresh payment and re-upload your payment evidence.\n\n";
    $message .= "For assistance, contact the admissions office.\n\n";
    $message .= "Edo State College of Health Sciences and Technology.";
    
    $sender_name = "EDOCOHEST";
    $recipients = $applicant['phoneno'];
    $forcednd = "1";
    
    $data = array(
        "email" => $email,
        "password" => $password,
        "message" => $message,
        "sender_name" => $sender_name,
        "recipients" => $recipients,
        "forcednd" => $forcednd
    );
    $data_string = json_encode($data);
    $ch = curl_init('https://app.multitexter.com/v2/app/sms');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
    $result = curl_exec($ch);
    curl_close($ch);
}

function sendPaymentRejectionEmail($studentid, $applicant, $personal, $reason, $con, $mail, $orgname, $schadress, $logo, $host, $pass, $user) {
    try {
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom($user, 'EDOCOHEST Payment Verification');
        $mail->addAddress($applicant['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Payment Verification Failed - EDOCOHEST Admission';
        
        $fullname = $applicant['lastname'] . " " . $applicant['firstname'];
        
        $message = '
        <html>
        <head>
            <title>Payment Verification Failed</title>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .reason { background: #f8f9fa; padding: 15px; border-left: 4px solid #dc3545; margin: 15px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Payment Verification Failed</h2>
                </div>
                <div class="content">
                    <p>Dear <strong>' . $fullname . '</strong>,</p>
                    <p>We regret to inform you that your application payment has been <strong style="color: #dc3545;">REJECTED</strong>.</p>
                    
                    <div class="reason">
                        <p><strong>Reason for Rejection:</strong></p>
                        <p>' . nl2br(htmlspecialchars($reason)) . '</p>
                    </div>
                    
                    <p><strong>What to do next:</strong></p>
                    <ul>
                        <li>Visit any Zenith Bank branch to make a fresh payment</li>
                        <li>Ensure you upload a clear, readable image of your payment teller/receipt</li>
                        <li>Re-submit your application with the correct payment evidence</li>
                    </ul>
                    
                    <p>If you believe this is an error, please contact the admissions office for assistance 08164550281 - 08163226994.</p>
                    
                    <p>Thank you for your understanding.</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date('Y') . ' ' . $orgname . '. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->MsgHTML($message);
        $mail->send();
        $mail->SmtpClose();
        $mail->ClearAllRecipients();
    } catch (Exception $e) {
        // Log error if needed
    }
}
?>