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

if (isset($_POST['allocate'])) {

    $coursecode = $_POST['coursecode'];
    $faculty    = $_POST['faculty'];
    $department = $_POST['department'];
    $programme  = $_POST['programme'];
    $unit       = (int)$_POST['unit'];

    // CHECK DUPLICATE
    $stmt = $con->prepare("
        SELECT id FROM course_allocation 
        WHERE coursecode=? AND faculty=? AND department=? AND programme=?
    ");
    $stmt->bind_param("ssss", $coursecode, $faculty, $department, $programme);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Course already allocated!";
    } else {

        $stmt = $con->prepare("
            INSERT INTO course_allocation (coursecode, faculty, department, programme, unit)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssi", $coursecode, $faculty, $department, $programme, $unit);

        if ($stmt->execute()) {
            $success = "Course allocated successfully!";
        } else {
            $error = "Allocation failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Course Allocation</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
<div class="container mt-4">

<h3>Course Allocation</h3>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="post">

<!-- COURSE -->
<select name="coursecode" class="form-control mb-2" required>
<option disabled selected>Select Course</option>
<?php
$res = $con->query("SELECT * FROM course ORDER BY coursecode");
while ($row = $res->fetch_assoc()):
?>
<option value="<?= $row['coursecode'] ?>">
<?= $row['coursecode'] ?> - <?= $row['title'] ?>
</option>
<?php endwhile; ?>
</select>

<!-- FACULTY -->
<input type="text" name="faculty" class="form-control mb-2" placeholder="Faculty" required>

<!-- DEPARTMENT -->
<input type="text" name="department" class="form-control mb-2" placeholder="Department" required>

<!-- PROGRAMME -->
<input type="text" name="programme" class="form-control mb-2" placeholder="Programme" required>

<!-- UNIT -->
<input type="number" name="unit" class="form-control mb-2" placeholder="Unit" required>

<button class="btn btn-success" name="allocate">Allocate Course</button>

</form>

</div>
</body>
</html>
<?php }?>