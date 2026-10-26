<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

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
$error='';
$success='';

// CREATE COURSE
if (isset($_POST['submitcd'])) {

    $code = strtoupper(trim($_POST['coursecode']));
    $code = preg_replace('/\s+/', '', $code);
    $title = trim($_POST['title']);

    if (empty($code) || empty($title)) {
        $error = "All fields are required";
    } else {

        $stmt = $con->prepare("SELECT id FROM course WHERE coursecode=?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Course Code $code already exists";
        } else {
            $stmt = $con->prepare("INSERT INTO course (coursecode, title) VALUES (?, ?)");
            $stmt->bind_param("ss", $code, $title);

            if ($stmt->execute()) {
                $success = "Course created successfully!";
            } else {
                $error = "Error creating course!";
            }
        }
    }
}

// UPDATE COURSE
if (isset($_POST['update_course'])) {

    $id = $_POST['course_id'];
    $code = strtoupper(trim($_POST['coursecode']));
    $title = trim($_POST['title']);

    $stmt = $con->prepare("SELECT id FROM course WHERE coursecode=? AND id!=?");
    $stmt->bind_param("si", $code, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Course code already exists!";
    } else {
        $stmt = $con->prepare("UPDATE course SET coursecode=?, title=? WHERE id=?");
        $stmt->bind_param("ssi", $code, $title, $id);

        if ($stmt->execute()) {
            $success = "Course updated successfully!";
        } else {
            $error = "Update failed!";
        }
    }
}

// DELETE COURSE
if (isset($_POST['delete_course'])) {

    $id = $_POST['course_id'];

    $stmt = $con->prepare("DELETE FROM course WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success = "Course deleted!";
    } else {
        $error = "Delete failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Setup</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">

<h3>Create Course (Master)</h3>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="post" class="mb-4">
    <input type="text" name="coursecode" class="form-control mb-2" placeholder="Course Code" required>
    <input type="text" name="title" class="form-control mb-2" placeholder="Course Title" required>
    <button class="btn btn-primary" name="submitcd">Create Course</button>
</form>

<table class="table table-bordered">
<thead>
<tr>
    <th>#</th>
    <th>Code</th>
    <th>Title</th>
</tr>
</thead>

<tbody>
<?php
$res = $con->query("SELECT * FROM course ORDER BY coursecode");
$i = 1;
while ($row = $res->fetch_assoc()):
?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($row['coursecode']) ?></td>
    <td><?= htmlspecialchars($row['title']) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>
</body>
</html>
<?php }?>