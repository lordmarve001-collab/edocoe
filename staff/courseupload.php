<?php
/**
 * Course Management System
 * Allows HOD/Superadmin to manage courses (CRUD operations)
 */

session_start();
$connection = TRUE;
include '../include/dbconfig.php';
include 'dataco.php';

// Check if user is logged in
if (empty($_SESSION['staffid'])) {
    header("Location: ../logout");
    exit();
}

// Check session expiry
if (time() > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location: ../logout");
    exit();
}

$error = '';
$success = '';
$showModal = false;

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new course
    if (isset($_POST['submitcd'])) {
        addCourse($con, $_POST, $faclt, $deplt);
    }
    
    // Update existing course
    if (isset($_POST['update_course'])) {
        updateCourse($con, $_POST, $faclt, $deplt);
    }
    
    // Delete course
    if (isset($_POST['delete_course'])) {
        deleteCourse($con, $_POST, $faclt, $deplt);
    }
}

// Function to add a new course
function addCourse($con, $data, $faculty, $department) {
    global $error, $success, $showModal;
    
    $courseCode = trim(mysqli_escape_string($con, strtoupper($data['coursecode'])));
    $courseCode = preg_replace('/\s+/', '', $courseCode);
    $courseTitle = mysqli_escape_string($con, $data['title']);
    $courseUnit = trim(mysqli_escape_string($con, $data['cunit']));
    
    // Check for duplicate course code
    $checkQuery = mysqli_query($con, "SELECT * FROM course WHERE coursecode='$courseCode'");
    if (mysqli_num_rows($checkQuery) > 0) {
        $error = "Course Code " . $courseCode . " already exists";
        $showModal = true;
        return;
    }
    
    // Insert new course
    $insert = mysqli_query($con, "INSERT INTO course (coursecode, title, unit, faculty, department) 
                                  VALUES ('$courseCode', '$courseTitle', '$courseUnit', '$faculty', '$department')");
    
    if ($insert) {
        mysqli_commit($con);
        $success = "Course added successfully!";
    } else {
        $error = "Error adding course!";
        $showModal = true;
    }
}

// Function to update a course
function updateCourse($con, $data, $faculty, $department) {
    global $error, $success, $showModal;
    
    $courseId = mysqli_escape_string($con, $data['course_id']);
    $courseCode = trim(mysqli_escape_string($con, strtoupper($data['coursecode'])));
    $courseCode = preg_replace('/\s+/', '', $courseCode);
    $courseTitle = mysqli_escape_string($con, $data['title']);
    $courseUnit = trim(mysqli_escape_string($con, $data['cunit']));
    
    // Check if course code exists for other courses
    $checkQuery = mysqli_query($con, "SELECT * FROM course WHERE coursecode='$courseCode' AND id != '$courseId'");
    if (mysqli_num_rows($checkQuery) > 0) {
        $error = "Course Code " . $courseCode . " already exists for another course";
        $showModal = true;
        return;
    }
    
    // Update course
    $update = mysqli_query($con, "UPDATE course SET coursecode='$courseCode', title='$courseTitle', unit='$courseUnit' 
                                  WHERE id='$courseId' AND faculty='$faculty' AND department='$department'");
    
    if ($update) {
        mysqli_commit($con);
        $success = "Course updated successfully!";
    } else {
        $error = "Error updating course!";
        $showModal = true;
    }
}

// Function to delete a course
function deleteCourse($con, $data, $faculty, $department) {
    global $error, $success;
    
    $courseId = mysqli_escape_string($con, $data['course_id']);
    
    $delete = mysqli_query($con, "DELETE FROM course WHERE id='$courseId' AND faculty='$faculty' AND department='$department'");
    
    if ($delete) {
        mysqli_commit($con);
        $success = "Course deleted successfully!";
    } else {
        $error = "Error deleting course!";
    }
}

// Function to get all courses for the department
function getDepartmentCourses($con, $faculty, $department) {
    $query = mysqli_query($con, "SELECT * FROM course WHERE faculty='$faculty' AND department='$department' ORDER BY coursecode");
    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlentities($orgname); ?> - Course Management</title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/receipt.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <link rel="stylesheet" href="css/animate.min.css" media="screen">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <style>
        #blah {
            width: 170.256px;
            height: 159px;
            border: 2px solid black;
        }
        .action-btns {
            white-space: nowrap;
        }
        .action-btns .btn {
            margin: 0 2px;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .alert {
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <?php include('includes/topbar.php'); ?>
        
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/hodbar.php'); ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Course Management System</h2>
                                <p class="subtitle">Add, edit, or remove courses for your department</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <?php if (!empty($success)): ?>
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <i class="fa fa-check-circle"></i> <?php echo htmlentities($success); ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php
                                        $userPosition = mysqli_fetch_assoc(mysqli_query($con, "SELECT position FROM staffprive WHERE staffid='" . $_SESSION['staffid'] . "'"));
                                        $position = $userPosition['position'] ?? '';
                                        
                                        if (in_array($position, ['hod', 'superadmin'])):
                                        ?>
                                        
                                        <!-- Add Course Form -->
                                        <div class="form-section">
                                            <h4><i class="fa fa-plus-circle"></i> Add New Course</h4>
                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="coursecode">Course Code:</label>
                                                            <input type="text" maxlength="6" name="coursecode" class="form-control" id="coursecode" 
                                                                   placeholder="e.g., CSC101" pattern="[A-Za-z0-9]+" required>
                                                            <small class="text-muted">Max 6 characters, alphanumeric only</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="title">Course Title:</label>
                                                            <input type="text" name="title" class="form-control" id="title" 
                                                                   placeholder="e.g., Introduction to Computer Science" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="cunit">Course Unit:</label>
                                                            <input type="number" name="cunit" class="form-control" id="cunit" 
                                                                   placeholder="e.g., 3" min="1" max="6" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" name="submitcd" class="btn btn-primary btn-block">
                                                                <i class="fa fa-save"></i> Add
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <!-- Courses List -->
                                        <h4><i class="fa fa-list"></i> Department Courses</h4>
                                        <div class="table-responsive">
                                            <table id="coursesTable" class="table table-striped table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th width="20%">Course Code</th>
                                                        <th width="50%">Course Title</th>
                                                        <th width="10%">Unit</th>
                                                        <th width="15%">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $courses = getDepartmentCourses($con, $faclt, $deplt);
                                                    $counter = 1;
                                                    foreach ($courses as $course):
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $counter++; ?></td>
                                                        <td><strong><?php echo htmlentities($course['coursecode']); ?></strong></td>
                                                        <td><?php echo htmlentities($course['title']); ?></td>
                                                        <td><span class="badge badge-info"><?php echo htmlentities($course['unit']); ?> unit(s)</span></td>
                                                        <td>
                                                            <div class="action-btns">
                                                                <button type="button" class="btn btn-sm btn-primary edit-course" 
                                                                        data-id="<?php echo $course['id']; ?>"
                                                                        data-code="<?php echo htmlentities($course['coursecode']); ?>"
                                                                        data-title="<?php echo htmlentities($course['title']); ?>"
                                                                        data-unit="<?php echo $course['unit']; ?>">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-danger delete-course" 
                                                                        data-id="<?php echo $course['id']; ?>"
                                                                        data-code="<?php echo htmlentities($course['coursecode']); ?>">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <?php if (empty($courses)): ?>
                                            <div class="alert alert-info text-center">
                                                <i class="fa fa-info-circle"></i> No courses found for your department. 
                                                Use the form above to add courses.
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php else: ?>
                                            <div class="alert alert-danger">
                                                <i class="fa fa-exclamation-triangle"></i> 
                                                Sorry Dear: <strong><?php echo htmlentities($fullname); ?></strong> 
                                                You don't have access to this page. Only HOD and Super Admin can manage courses.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="course_id" id="edit_course_id">
                        <div class="form-group">
                            <label for="edit_coursecode">Course Code:</label>
                            <input type="text" class="form-control" id="edit_coursecode" name="coursecode" maxlength="6" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_title">Course Title:</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_unit">Course Unit:</label>
                            <input type="number" class="form-control" id="edit_unit" name="cunit" min="1" max="6" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_course" class="btn btn-primary">Update Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fa fa-trash"></i> Confirm Delete</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="course_id" id="delete_course_id">
                        <p>Are you sure you want to delete the course:</p>
                        <p class="text-center"><strong><span id="delete_course_code"></span></strong></p>
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> 
                            <small>This action cannot be undone! The course will be permanently removed.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_course" class="btn btn-danger">Delete Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Error</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo htmlentities($error); ?></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/buton.php'; ?>
    
    <!-- DataTables Scripts -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        if (!$.fn.DataTable.isDataTable('#coursesTable')) {
            $('#coursesTable').DataTable({
                "order": [[1, "asc"]],
                "pageLength": 25,
                "language": {
                    "search": "Search courses:",
                    "lengthMenu": "Show _MENU_ courses per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ courses",
                    "infoEmpty": "No courses found",
                    "infoFiltered": "(filtered from _MAX_ total courses)",
                    "zeroRecords": "No matching courses found"
                },
                "columnDefs": [
                    { "orderable": false, "targets": [4] } // Disable sorting on Actions column
                ],
                "drawCallback": function() {
                    // Re-initialize tooltips or any other plugins after table redraw
                    console.log("Table redrawn - pagination changed");
                }
            });
        }
        
        // ========== EVENT DELEGATION FOR EDIT BUTTONS ==========
        // This ensures buttons on pages 2, 3, etc. also work
        $(document).on('click', '.edit-course', function() {
            console.log("Edit button clicked"); // Debug log
            
            var id = $(this).data('id');
            var code = $(this).data('code');
            var title = $(this).data('title');
            var unit = $(this).data('unit');
            
            console.log("Course ID: " + id); // Debug log
            
            $('#edit_course_id').val(id);
            $('#edit_coursecode').val(code);
            $('#edit_title').val(title);
            $('#edit_unit').val(unit);
            
            $('#editModal').modal('show');
        });
        
        // ========== EVENT DELEGATION FOR DELETE BUTTONS ==========
        $(document).on('click', '.delete-course', function() {
            console.log("Delete button clicked"); // Debug log
            
            var id = $(this).data('id');
            var code = $(this).data('code');
            
            console.log("Course ID to delete: " + id); // Debug log
            
            $('#delete_course_id').val(id);
            $('#delete_course_code').text(code);
            
            $('#deleteModal').modal('show');
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Optional: Refresh DataTable after modal close to maintain state
        $('#editModal').on('hidden.bs.modal', function() {
            // No action needed, but you can refresh table if needed
            console.log("Edit modal closed");
        });
        
        $('#deleteModal').on('hidden.bs.modal', function() {
            console.log("Delete modal closed");
        });
    });
    
    // Show error modal if needed
    <?php if (!empty($showModal)): ?>
    $(document).ready(function() {
        $("#errorModal").modal("show");
    });
    <?php endif; ?>
    
    // Function to reload table data if needed (optional)
    function reloadCoursesTable() {
        $('#coursesTable').DataTable().ajax.reload();
    }
    </script>
</body>
</html>