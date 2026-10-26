<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';

// Only allow AJAX requests
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    echo "ERROR: Direct access not allowed";
    exit;
}

// Check if user is logged in
if(!isset($_SESSION['staffid']) || strlen($_SESSION['staffid']) == "") {   
    echo "ERROR: Session expired";
    exit;
}

// Increase execution time for this script
set_time_limit(30);

// Process the save
if(isset($_POST['studentid']) && isset($_POST['coursecode']) && isset($_POST['column']) && isset($_POST['value'])) {
    
    $studentid = mysqli_real_escape_string($con, trim($_POST['studentid']));
    $coursecode = mysqli_real_escape_string($con, trim($_POST['coursecode']));
    $column = mysqli_real_escape_string($con, trim($_POST['column']));
    $value = trim($_POST['value']); // Don't escape yet
    $acadsession = mysqli_real_escape_string($con, trim($_POST['acadsession']));
    $semester = mysqli_real_escape_string($con, trim($_POST['semester']));
    
    // Quick validation
    if (!in_array($column, ['ca', 'exam'])) {
        echo "ERROR: Invalid column";
        exit;
    }
    
    // Handle empty value
    if ($value === '') {
        $sql_value = 'NULL';
    } else {
        // Ensure numeric
        if (!is_numeric($value)) {
            echo "ERROR: Score must be a number";
            exit;
        }
        // Ensure within range (0-100)
        if ($value < 0 || $value > 100) {
            echo "ERROR: Score must be between 0 and 100";
            exit;
        }
        $sql_value = "'" . mysqli_real_escape_string($con, $value) . "'";
    }
    
    // Get student programme quickly
    $prog_query = mysqli_query($con, "SELECT programme FROM student WHERE studentid='$studentid' LIMIT 1");
    if (mysqli_num_rows($prog_query) == 0) {
        $prog_query = mysqli_query($con, "SELECT programme FROM studdata WHERE studentid='$studentid' LIMIT 1");
    }
    
    if (mysqli_num_rows($prog_query) == 0) {
        echo "ERROR: Student not found";
        exit;
    }
    
    $student = mysqli_fetch_assoc($prog_query);
    $programme = $student['programme'];
    
    // Optimized query - use INSERT ON DUPLICATE KEY UPDATE
    if ($sql_value === 'NULL') {
        $query = "INSERT INTO result (studentid, coursecode, acadsession, semester, level, programme, $column, status) 
                  VALUES ('$studentid', '$coursecode', '$acadsession', '$semester', '100', '$programme', NULL, 'Pending')
                  ON DUPLICATE KEY UPDATE $column = NULL, status = 'Pending'";
    } else {
        $query = "INSERT INTO result (studentid, coursecode, acadsession, semester, level, programme, $column, status) 
                  VALUES ('$studentid', '$coursecode', '$acadsession', '$semester', '100', '$programme', $sql_value, 'Pending')
                  ON DUPLICATE KEY UPDATE $column = $sql_value, status = 'Pending'";
    }
    
    // Execute query
    if (mysqli_query($con, $query)) {
        echo "success";
    } else {
        echo "ERROR: " . mysqli_error($con);
    }
} else {
    echo "ERROR: Missing parameters";
}
?>