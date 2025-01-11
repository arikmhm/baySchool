<?php
require "fungsi.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $parent_name = mysqli_real_escape_string($koneksi, $_POST['parent_name']);
    $city = mysqli_real_escape_string($koneksi, $_POST['city']);
    $gender = mysqli_real_escape_string($koneksi, $_POST['gender']);
    $grade = mysqli_real_escape_string($koneksi, $_POST['grade']);

    // Validasi input
    if (empty($name) || empty($parent_name) || empty($city) || empty($gender) || empty($grade)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi!']);
        exit;
    }

    // Update data menggunakan prepared statement
    $stmt = $koneksi->prepare("UPDATE students SET 
        name = ?, 
        parent_name = ?, 
        city = ?, 
        gender = ?, 
        grade = ? 
        WHERE id = ?");

    $stmt->bind_param("sssssi", 
        $name, 
        $parent_name, 
        $city, 
        $gender, 
        $grade, 
        $id
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate data: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>