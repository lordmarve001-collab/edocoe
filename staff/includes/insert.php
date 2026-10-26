<?php
   
   
    die("Welcome");
    /*
    $name = mysqli_real_escape_string($connect, $_POST["name"]);  
    $address = mysqli_real_escape_string($connect, $_POST["address"]);  
    $gender = mysqli_real_escape_string($connect, $_POST["gender"]);  
    $designation = mysqli_real_escape_string($connect, $_POST["designation"]);  
    $age = mysqli_real_escape_string($connect, $_POST["age"]);
    $query = "
    INSERT INTO employee(name, address, gender, designation, age)  
     VALUES('$name', '$address', '$gender', '$designation', '$age')
    ";*/
    header("location:staffportal.php");
?>