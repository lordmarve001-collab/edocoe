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

if (isset($_POST['submitcor'])) {

    $coursecode = $_POST['coursecode'];
    $status     = $_POST['stasc'];
    $passmark   = (int)$_POST['passma'];
    $ca         = (int)$_POST['cascore'];
    $exam       = (int)$_POST['exscore'];

    if (($ca + $exam) > 100) {
        $error = "CA + Exam cannot exceed 100";
    } else {

        // GET UNIT FROM ALLOCATION
        $stmt = $con->prepare("
            SELECT unit FROM course_allocation
            WHERE coursecode=? AND faculty=? AND department=? AND programme=?
        ");
        $stmt->bind_param("ssss", $coursecode, $faclt, $deplt, $progra);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows == 0) {
            $error = "Course not assigned to this department!";
        } else {

            $unit = $res->fetch_assoc()['unit'];

            // CURRENT TOTAL
            $sumQ = $con->prepare("
                SELECT SUM(ca.unit) AS total
                FROM curriculum c
                JOIN course_allocation ca 
                ON c.coursecode = ca.coursecode
                AND c.faculty = ca.faculty
                AND c.department = ca.department
                AND c.programme = ca.programme
                WHERE c.level=? AND c.semester=? AND c.programme=?
            ");
            $sumQ->bind_param("sss", $levls, $semest, $progra);
            $sumQ->execute();
            $sumRes = $sumQ->get_result()->fetch_assoc();

            $total = $sumRes['total'] ?? 0;
            $newTotal = $total + $unit;

            // MAX LIMIT
            $maxQ = $con->prepare("SELECT maxm FROM registcheck WHERE programme=? AND level=?");
            $maxQ->bind_param("ss", $progra, $levls);
            $maxQ->execute();
            $max = $maxQ->get_result()->fetch_assoc()['maxm'];

            if ($newTotal > $max) {
                $error = "Unit exceeded! Max = $max";
            } else {

                // DUPLICATE CHECK
                $dup = $con->prepare("SELECT * FROM curriculum WHERE coursecode=? AND programme=?");
                $dup->bind_param("ss", $coursecode, $progra);
                $dup->execute();
                $dup->store_result();

                if ($dup->num_rows > 0) {
                    $error = "$coursecode already added";
                } else {

                    $insert = $con->prepare("
                        INSERT INTO curriculum 
                        (coursecode, level, semester, minpass, cascore, examscore, status, faculty, department, programme)
                        VALUES (?,?,?,?,?,?,?,?,?,?)
                    ");

                    $insert->bind_param(
                        "ssssssssss",
                        $coursecode,
                        $levls,
                        $semest,
                        $passmark,
                        $ca,
                        $exam,
                        $status,
                        $faclt,
                        $deplt,
                        $progra
                    );

                    if ($insert->execute()) {
                        $success = "Course added successfully!";
                    } else {
                        $error = "Insert failed!";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Curriculum Setup</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
<div class="container mt-4">

<h3>Curriculum Setup</h3>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="post">

<select name="coursecode" class="form-control mb-2" required>
<option disabled selected>Select Course</option>

<?php
$res = $con->query("
SELECT c.coursecode, c.title 
FROM course c
JOIN course_allocation ca ON c.coursecode = ca.coursecode
WHERE ca.faculty='$faclt' AND ca.department='$deplt' AND ca.programme='$progra'
ORDER BY c.coursecode
");

while ($row = $res->fetch_assoc()):
?>
<option value="<?= $row['coursecode'] ?>">
<?= $row['coursecode'] ?> - <?= $row['title'] ?>
</option>
<?php endwhile; ?>
</select>

<input type="number" name="passma" value="40" class="form-control mb-2">
<input type="number" name="cascore" value="40" class="form-control mb-2">
<input type="number" name="exscore" value="60" class="form-control mb-2">

<select name="stasc" class="form-control mb-2">
<option>Core</option>
<option>Elective</option>
</select>

<button class="btn btn-primary" name="submitcor">Add Course</button>

</form>

</div>
</body>
</html>
<?php }?>