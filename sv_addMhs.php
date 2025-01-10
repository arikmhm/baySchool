<?php
require "fungsi.php";

if (isset($_POST['check_nim'])) {
    $nim = $_POST['nim'];
    $query = "SELECT * FROM mhs WHERE nim = '$nim'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        echo 'exists'; 
    } else {
        echo 'not_exists'; 
    }
    exit(); 
}


// Tangkap data form
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$uploadOk = 1;

$folderupload = "foto/";
$extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
$new_filename = uniqid() . "." . $extension;
$fileupload = $folderupload . $new_filename;

if ($_FILES["foto"]["size"] > 1000000) {
    echo "Ukuran file terlalu besar. Mkas 1mb";
    exit;
}

if ($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif") {
    echo "Format file tidak diperbolehkan.";
    exit;
}

if ($uploadOk && move_uploaded_file($_FILES["foto"]["tmp_name"], $fileupload)) {
    $sql = "INSERT INTO mhs (nim, nama, email, foto) VALUES ('$nim', '$nama', '$email', '$new_filename')";

    if (mysqli_query($koneksi, $sql)) {
        echo 'success';
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    echo "Gagal mengupload file.";
}