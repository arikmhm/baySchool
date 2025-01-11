<?php
require 'fungsi.php';

// Tangkap data dari form
$name = mysqli_real_escape_string($koneksi, $_POST['name']);
$nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
$nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
$parent_name = mysqli_real_escape_string($koneksi, $_POST['parent_name']);
$city = mysqli_real_escape_string($koneksi, $_POST['city']);
$gender = mysqli_real_escape_string($koneksi, $_POST['gender']);
$grade = mysqli_real_escape_string($koneksi, $_POST['grade']);

// Cek apakah NIS/NISN sudah ada
$check_query = "SELECT * FROM students WHERE nis = '$nis' OR nisn = '$nisn'";
$check_result = mysqli_query($koneksi, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo "NIS or NISN already exists";
    exit;
}

// Query insert
$sql = "INSERT INTO students (name, nis, nisn, parent_name, city, gender, grade) 
        VALUES ('$name', '$nis', '$nisn', '$parent_name', '$city', '$gender', '$grade')";

if (mysqli_query($koneksi, $sql)) {
    echo "success";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>