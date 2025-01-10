<?php
require "fungsi.php";

// Validasi apakah npp sudah ada di database
if (isset($_POST['check_npp'])) {
    $npp = $_POST['npp'];
    $query = "SELECT * FROM dosen WHERE npp = '$npp'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        echo 'exists'; 
    } else {
        echo 'not_exists'; 
    }
    exit(); 
}

// Tangkap data form
$npp = $_POST['npp'];
$namadosen = $_POST['namadosen'];
$homebase = $_POST['homebase'];

// Validasi NPP (16 karakter, format xxxx.xx.xxxx.xxx)
if (strlen($npp) !== 16) {
    echo "NPP harus terdiri dari 16 karakter.";
    exit;
}

if (!preg_match('/^[0-9]{4}\.[0-9]{2}\.[0-9]{4}\.[0-9]{3}$/', $npp)) {
    echo "Format NPP tidak valid. Contoh yang benar: 0686.11.1991.011";
    exit;
}

// Masukkan data ke tabel dosen
$sql = "INSERT INTO dosen (npp, namadosen, homebase) VALUES ('$npp', '$namadosen', '$homebase')";

if (mysqli_query($koneksi, $sql)) {
    echo 'success';
} else {
    echo "Error: " . mysqli_error($koneksi);
}
