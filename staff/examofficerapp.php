<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
include 'dataco.php';

if (strlen($_SESSION['staffid']) == "") {
    header("Location: ../logout.php");
    exit();
} else {
    $now = time();
    if ($now > $_SESSION['expire']) {
        session_unset();
        session_destroy();
        header("Location: ../logout.php");
        exit();
    }

    $dat = mysqli_fetch_assoc(mysqli_query($con, "SELECT semester, session FROM resultsession WHERE description='Current Session'"));
    $sems = $dat['semester'];
    $ses = $dat['session'];

    // Handle Add/Assignment
    if (isset($_POST['submitclv'])) {
        if (empty($_POST['staffid'])) {
            $error = "Staff Detail Required";
            $showModal = true;
        } else {
            $staff = mysqli_escape_string($con, $_POST['staffid']);
            $dep = mysqli_escape_string($con, $_POST['department']);
            
            $sq = mysqli_query($con, "SELECT * FROM examoff WHERE staffid='$staff' AND acadsession='$ses'");
            if (mysqli_num_rows($sq) == 1) {
                $error = "Staff Detail already Exists For " . htmlentities($ses);
                $showModal = true;
            } else {
                $insrt = mysqli_query($con, "INSERT INTO examoff (staffid, acadsession, deptm, fac_id) VALUES ('$staff', '$ses', '$dep', '$faclt')");
                if ($insrt) {
                    $success = "Exam Officer assigned successfully!";
                    $showModalSuccess = true;
                } else {
                    $error = "Error occurred while assigning exam officer";
                    $showModal = true;
                }
            }
        }
    }

    // Handle Delete
    if (isset($_POST['deletesubmit'])) {
        $staffId = mysqli_escape_string($con, $_POST['staffid']);
        $acadSession = mysqli_escape_string($con, $_POST['acadsession']);
        
        $del = mysqli_query($con, "DELETE FROM examoff WHERE staffid='$staffId' AND acadsession='$acadSession'");
        if ($del) {
            $success = "Exam Officer details successfully deleted!";
            $showModalSuccess = true;
        } else {
            $error = "Error occurred while deleting information";
            $showModal = true;
        }
    }

    // Handle Update
    if (isset($_POST['updatesubmit'])) {
        $staffId = mysqli_escape_string($con, $_POST['staffid']);
        $oldSession = mysqli_escape_string($con, $_POST['oldacadsession']);
        $newSession = mysqli_escape_string($con, $_POST['newsession']);
        
        $upd = mysqli_query($con, "UPDATE examoff SET acadsession='$newSession' WHERE staffid='$staffId' AND acadsession='$oldSession'");
        if ($upd) {
            $success = "Exam Officer details successfully updated!";
            $showModalSuccess = true;
        } else {
            $error = "Error occurred while updating information";
            $showModal = true;
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
    <link rel="stylesheet" href="css/animate.min.css" media="screen">
    <style type="text/css">
        #blah {
            width: 170.256px;
            height: 159px;
            border: 2px solid black;
        }
        .modal-lg {
            max-width: 600px;
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/deanbar.php'); ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div>
                                <h2 class="title">Department Exam Officer Setup</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <b style="color:red;">Current Academic Session: <?php echo htmlentities($ses); ?></b>
                                    <div class="panel-body">
                                        <!-- Assignment Form -->
                                        <section class="section">
                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="staffid" class="col-sm-2 control-label">Staff Member:</label>
                                                    <div class="col-sm-10">
                                                        <select name="staffid" class="form-control clid" id="staffid" required="required">
                                                            <option selected disabled>Please select Staff</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM staffaculty JOIN people ON staffaculty.staffid=people.staffid WHERE staffaculty.faculty='$faclt' AND people.status='active' ORDER BY staffaculty.staffid");
                                                            while ($row = mysqli_fetch_array($res)) {
                                                                $fullName = trim($row['title'] . " " . $row['firstname'] . " " . $row['lastname'] . " " . $row['othername']);
                                                                echo '<option value="' . htmlentities($row["staffid"]) . '">' . htmlentities($fullName) . ' - ' . htmlentities($row["deptm"]) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="department" class="col-sm-2 control-label">Department:</label>
                                                    <div class="col-sm-10">
                                                        <select name="department" class="form-control clid" required="required" id="department">
                                                            <option disabled selected>Select Department</option>
                                                            <?php
                                                            $res = mysqli_query($con, "SELECT * FROM program WHERE fid='$faclt' ORDER BY pname");
                                                            while ($row = mysqli_fetch_array($res)) {
                                                                echo '<option value="' . htmlentities($row["pname"]) . '">' . htmlentities($row["pname"]) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" name="submitclv" id="submit" class="btn btn-primary">Assign Exam Officer</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </section>
                                        
                                        <!-- Data Table -->
                                        <section>
                                            <table id="example" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Staff Name</th>
                                                        <th>Staff ID</th>
                                                        <th>Session</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM examoff 
                                                            INNER JOIN people ON examoff.staffid = people.staffid 
                                                            JOIN staffaculty ON examoff.staffid = staffaculty.staffid 
                                                            WHERE examoff.acadsession = '$ses' 
                                                            AND examoff.deptm = '$pro' 
                                                            AND staffaculty.deptm = '$deplt'  
                                                            ORDER BY examoff.staffid";
                                                    
                                                    $query = mysqli_query($con, $sql);
                                                    $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                    $cnt = 1;
                                                    
                                                    foreach ($results as $result) {
                                                        $fullName = trim($result['title'] . " " . $result['firstname'] . " " . $result['lastname'] . " " . $result['othername']);
                                                        ?>
                                                        <tr>
                                                            <td><?php echo htmlentities($cnt); ?></td>
                                                            <td><?php echo htmlentities($fullName); ?></td>
                                                            <td><?php echo htmlentities($result['staffid']); ?></td>
                                                            <td><?php echo htmlentities($result['acadsession']); ?></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm edit-btn" 
                                                                        data-id="<?php echo htmlentities($result['staffid']); ?>" 
                                                                        data-session="<?php echo htmlentities($result['acadsession']); ?>"
                                                                        data-name="<?php echo htmlentities($fullName); ?>">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </button>
                                                                <button class="btn btn-danger btn-sm delete-btn" 
                                                                        data-id="<?php echo htmlentities($result['staffid']); ?>" 
                                                                        data-session="<?php echo htmlentities($result['acadsession']); ?>"
                                                                        data-name="<?php echo htmlentities($fullName); ?>">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $cnt++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: red;">
                        <i class="fa fa-trash"></i> Delete Confirmation
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <p>Are you sure you want to delete the following exam officer?</p>
                        <p><strong>Name:</strong> <span id="delete_staff_name"></span></p>
                        <p><strong>Staff ID:</strong> <span id="delete_staff_id"></span></p>
                        <p><strong>Session:</strong> <span id="delete_session"></span></p>
                        <input type="hidden" name="staffid" id="delete_staffid_input">
                        <input type="hidden" name="acadsession" id="delete_acadsession_input">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="deletesubmit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: blue;">
                        <i class="fa fa-edit"></i> Update Exam Officer
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <p><strong>Staff Name:</strong> <span id="edit_staff_name"></span></p>
                        <p><strong>Staff ID:</strong> <span id="edit_staff_id_display"></span></p>
                        <div class="form-group">
                            <label for="newsession">Academic Session:</label>
                            <input type="text" class="form-control" name="newsession" id="newsession" required>
                            <small class="form-text text-muted">Current Session: <span id="current_session"></span></small>
                        </div>
                        <input type="hidden" name="staffid" id="edit_staffid_input">
                        <input type="hidden" name="oldacadsession" id="oldacadsession_input">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="updatesubmit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: red;">Error Message</h5>
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><?php echo htmlentities($error ?? ''); ?></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: green;">Success Message</h5>
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><?php echo htmlentities($success ?? '', ENT_QUOTES, 'utf-8'); ?></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-dismiss="modal" onclick="location.reload();">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <?php include 'includes/buton.php'; ?>
    
    <script type="text/javascript">
        $(document).ready(function() {
            // Delete button click handler
            $('.delete-btn').click(function() {
                var staffId = $(this).data('id');
                var session = $(this).data('session');
                var staffName = $(this).data('name');
                
                $('#delete_staff_name').text(staffName);
                $('#delete_staff_id').text(staffId);
                $('#delete_session').text(session);
                $('#delete_staffid_input').val(staffId);
                $('#delete_acadsession_input').val(session);
                
                $('#deleteModal').modal('show');
            });
            
            // Edit button click handler
            $('.edit-btn').click(function() {
                var staffId = $(this).data('id');
                var session = $(this).data('session');
                var staffName = $(this).data('name');
                var currentSession = '<?php echo $ses; ?>';
                
                $('#edit_staff_name').text(staffName);
                $('#edit_staff_id_display').text(staffId);
                $('#current_session').text(session);
                $('#newsession').val(currentSession);
                $('#edit_staffid_input').val(staffId);
                $('#oldacadsession_input').val(session);
                
                $('#editModal').modal('show');
            });
            
            // Auto-hide success modal and reload
            $('#successModal').on('hidden.bs.modal', function() {
                location.reload();
            });
        });
    </script>
</body>
</html>

<?php
    // Display modals based on status
    if (!empty($showModal)) {
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#errorModal").modal("show");
            });
        </script>';
    }
    
    if (!empty($showModalSuccess)) {
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#successModal").modal("show");
            });
        </script>';
    }
}
?>