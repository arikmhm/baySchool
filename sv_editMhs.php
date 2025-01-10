<?php
require "fungsi.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $koneksi->prepare("UPDATE mhs SET nama = ?, email = ? WHERE id = ?");
    
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    // Validasi input
    if (empty($nama) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi!']);
        exit;
    }

    // Bind parameter
    $stmt->bind_param("ssi", $nama, $email, $id);

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate data']);
    }

    $stmt->close();
    exit;
}
?>