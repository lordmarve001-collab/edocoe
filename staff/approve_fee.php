<?php
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
        header("Location:../logout");
        exit();
    }
    
    require "phpmailer/class.phpmailer.php";
    $mail = new PHPMailer();
    $error = '';
    $succ = '';
    
    $dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT description, session, deadline FROM resultsession WHERE description='admission processing'"));
    $desc = $dat['description'];
    $ses = $dat['session'];
    $dead = $dat['deadline'];
    
    // Handle Approve Payment
    if (isset($_POST['approve'])) {
        if(isset($_SESSION['appl']) && !empty($_SESSION['appl'])) {
            $update = mysqli_query($con, "UPDATE studentpayment SET status='Success' WHERE id='" . mysqli_real_escape_string($con, $_SESSION['appl']) . "'");
            if ($update) {
                $succ = 'Payment Approved Successfully';
                $showModalS = true;
                // Clear session after approval
                unset($_SESSION['appl']);
            } else {
                $error = 'An Error Occurred While Confirming Data. Please Try Again!!!';
                $showModalEr = true;
            }
        } else {
            $error = 'No payment record selected for approval';
            $showModalEr = true;
        }
    }
    
    // Handle Reject Payment with reason
    if (isset($_POST['disapproved'])) {
        if(isset($_SESSION['appl']) && !empty($_SESSION['appl'])) {
            $reason = isset($_POST['messag']) ? mysqli_real_escape_string($con, $_POST['messag']) : 'No reason provided';
            $update = mysqli_query($con, "UPDATE studentpayment SET status='Rejected', reject_reason='$reason', reject_date=NOW() WHERE id='" . mysqli_real_escape_string($con, $_SESSION['appl']) . "'");
            if ($update) {
                $succ = 'Payment Rejected Successfully';
                $showModalS = true;
                // Clear session after rejection
                unset($_SESSION['appl']);
            } else {
                $error = 'An Error Occurred While Rejecting Payment. Please Try Again!!!';
                $showModalEr = true;
            }
        } else {
            $error = 'No payment record selected for rejection';
            $showModalEr = true;
        }
    }
    
    // Handle Delete Payment
    if (isset($_POST['delete_payment'])) {
        $paymentId = mysqli_real_escape_string($con, $_POST['payment_id']);
        
        // First get the proof file path to delete it
        $proofQuery = mysqli_query($con, "SELECT proof FROM studentpayment WHERE id='$paymentId'");
        $proofData = mysqli_fetch_assoc($proofQuery);
        
        // Delete the record
        $delete = mysqli_query($con, "DELETE FROM studentpayment WHERE id='$paymentId'");
        
        if ($delete) {
            // Delete the proof file if it exists
            if (!empty($proofData['proof']) && file_exists("evidence/" . $proofData['proof'])) {
                unlink("evidence/" . $proofData['proof']);
            }
            $succ = 'Payment Record Deleted Successfully';
            $showModalS = true;
        } else {
            $error = 'An Error Occurred While Deleting Record. Please Try Again!!!';
            $showModalEr = true;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlentities($orgname); ?></title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/receipt.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <style type="text/css">
        #blah {
            width: 170.256px;
            height: 159px;
            border: 2px solid black;
        }
        .td {
            cursor: not-allowed;
        }
        strong {
            color: blue;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            margin-left: 5px;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
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
                if ($paych['position'] == "superadmin") {
                    include('includes/supbar.php');
                } elseif ($paych['position'] == "bursar") {
                    include('includes/bursar.php');
                } else {
                    include('includes/leftbar.php');
                }
                ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <?php 
                                        $paych = mysqli_fetch_assoc(mysqli_query($con, "SELECT position FROM staffprive WHERE staffid='" . $_SESSION['staffid'] . "'"));
                                        if ($paych['position'] == "superadmin" OR $paych['position'] == "bursar") {
                                        ?>
                                        <div class="main-page">
                                            <div class="container-fluid">
                                                <div class="row page-title-div">
                                                    <div>
                                                        <h3 class="title">PAYMENT APPROVAL DASHBOARD</h3>
                                                        <p class="subtitle">Review, approve, reject, or delete pending payment records</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel">
                                                        <div class="panel-body">
                                                            <?php 
                                                            $sql = mysqli_query($con, "SELECT * FROM studentpayment WHERE status='Pending' ORDER BY paytime DESC");
                                                            $rows = mysqli_num_rows($sql);
                                                            if ($rows > 0) {
                                                            ?>
                                                            <div class="table-responsive">
                                                                <table id="example" class="table table-striped table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="5%">#</th>
                                                                            <th width="10%">Student ID</th>
                                                                            <th width="20%">Fullname</th>
                                                                            <th width="10%">Session</th>
                                                                            <th width="10%">Amount</th>
                                                                            <th width="15%">Date of Payment</th>
                                                                            <th width="10%">Status</th>
                                                                            <th width="20%">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $sql = "SELECT * FROM studentpayment WHERE status='Pending' ORDER BY paytime DESC";
                                                                        $query = mysqli_query($con, $sql);
                                                                        $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                                        $cnt = 1;
                                                                        
                                                                        foreach($results as $result) {   
                                                                            stuname($con, $result['studentid']);
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo htmlentities($cnt); ?></td>
                                                                            <td><?php echo htmlentities($result['studentid']); ?></td>
                                                                            <td style="text-transform: capitalize;"><?php echo htmlentities($_POST['harrd']); ?></td>
                                                                            <td><?php echo htmlentities($result['section']); ?></td>
                                                                            <td>₦<?php echo number_format($result['amount'], 2); ?></td>
                                                                            <td><?php echo date('d M Y, h:i A', strtotime($result['paytime'])); ?></td>
                                                                            <td>
                                                                                <span class="label label-warning"><?php echo htmlentities($result['status']); ?></span>
                                                                            </td>
                                                                            <td>
                                                                                <div class="action-buttons">
                                                                                    <button class="btn btn-primary btn-sm view-btn" data-id="<?php echo $result['id']; ?>">
                                                                                        <i class="fa fa-eye"></i> View
                                                                                    </button>
                                                                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $result['id']; ?>" data-student="<?php echo htmlentities($_POST['harrd']); ?>">
                                                                                        <i class="fa fa-trash"></i> Delete
                                                                                    </button>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <?php 
                                                                            $cnt++;
                                                                        } 
                                                                        ?>
                                                                    </tbody>
                                                                 </table
                                                            </div>
                                                            <?php 
                                                            } else {
                                                                echo '<div class="alert alert-info">No pending payment records found.</div>';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Details Modal -->
    <div class="modal fade" id="viewModals" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="float:left;">Payment Details <i class="fa fa-credit-card"></i></h4>
                    <button style="float:right;" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="teacher_detailss">
                    <div class="text-center">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                        <p>Loading payment details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row" style="width: 100%;">
                        <div class="col-md-6">
                            <form method="POST">
                                <textarea id="mess" name="messag" placeholder="Specify Reason for Rejecting Please..." style="resize: none; width: 100%; height: 80px;" class="form-control"></textarea>
                                <button name="disapproved" type="submit" class="btn btn-danger sendButton" style="margin-top: 10px;" disabled>
                                    <i class="fa fa-close"></i> Reject Payment
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            <form method="POST">
                                <button class="btn btn-success" type="submit" name="approve" style="margin-top: 50px;">
                                    <i class="fa fa-check"></i> Approve Payment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #dc3545; color: white;">
                    <h4 class="modal-title"><i class="fa fa-trash"></i> Confirm Deletion</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this payment record?</p>
                        <p><strong>Student:</strong> <span id="deleteStudentName"></span></p>
                        <p><strong>Payment ID:</strong> <span id="deletePaymentId"></span></p>
                        <p class="text-danger"><strong>Warning:</strong> This action cannot be undone. The payment proof file will also be deleted.</p>
                        <input type="hidden" name="payment_id" id="deletePaymentIdInput">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_payment" class="btn btn-danger">Delete Permanently</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade" id="checkModalsucc" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: #28a745; color: white;">
                    <h5 class="modal-title"><i class="fa fa-check-circle"></i> Success</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-check-circle" style="font-size: 60px; color: #28a745;"></i>
                        <p style="margin-top: 15px; font-size: 16px;"><?php echo htmlentities($succ); ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" data-dismiss="modal" onclick="location.reload()">
                        <i class="fa fa-check"></i> OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Error Modal -->
    <div class="modal fade" id="checkModalEr" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: #dc3545; color: white;">
                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Error</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-exclamation-circle" style="font-size: 60px; color: #dc3545;"></i>
                        <p style="margin-top: 15px;"><?php echo htmlentities($error); ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/buton.php'; ?>
    
    <script type="text/javascript">
    $(document).ready(function() {
        // View button click handler
        $('body').on('click', '.view-btn', function() {
            var userid = $(this).data('id');
            
            $.ajax({
                url: 'approve_data',
                type: 'POST',
                data: {
                    userid: userid
                },
                success: function(response) {
                    $('#teacher_detailss').html(response);
                    $('#viewModals').modal('show');
                },
                error: function() {
                    $('#teacher_detailss').html('<div class="alert alert-danger">Error loading payment details.</div>');
                }
            });
        });
        
        // Delete button click handler
        $('body').on('click', '.delete-btn', function() {
            var paymentId = $(this).data('id');
            var studentName = $(this).data('student');
            
            $('#deleteStudentName').text(studentName);
            $('#deletePaymentId').text(paymentId);
            $('#deletePaymentIdInput').val(paymentId);
            $('#deleteModal').modal('show');
        });
        
        // Reject button textarea handler
        $('#mess').on('keyup', function() {
            if($(this).val().length != 0) {
                $('.sendButton').prop('disabled', false);
            } else {
                $('.sendButton').prop('disabled', true);
            }
        });
        
        // Initialize sendButton as disabled
        $('.sendButton').prop('disabled', true);
        
        // Auto-hide success modal and reload
        $('#checkModalsucc').on('hidden.bs.modal', function() {
            location.reload();
        });
    });
    </script>
</body>
</html>

<?php 
    // Display modals
    if(!empty($showModalEr)) {
        echo '<script type="text/javascript">$(document).ready(function(){$("#checkModalEr").modal("show");});</script>';
    }
    if(!empty($showModalS)) {
        echo '<script type="text/javascript">$(document).ready(function(){$("#checkModalsucc").modal("show");});</script>';
    }
}

function stuname($con, $maxt) {
    $sql = mysqli_query($con, "SELECT * FROM personaldata WHERE (applid='$maxt' OR studentid='$maxt')");
    $data = mysqli_fetch_assoc($sql);
    if ($data == "") {
        $_POST["harrd"] = "No Name";
    } else {
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las = $data['lastname'];
        $name = $sur . " " . $first . " " . $las;
        $_POST["harrd"] = $name;
    }
}
?>