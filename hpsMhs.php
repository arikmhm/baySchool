<?php
require "fungsi.php";

header('Content-Type: application/json'); // Set response JSON

if (!isset($_POST['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
    exit;
}

// Ambil ID mahasiswa menggunakan prepared statement
$stmt_select = $koneksi->prepare("SELECT foto FROM mhs WHERE id = ?");
$id = intval($_POST["id"]);
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $foto = $row['foto'];

    // Gunakan prepared statement untuk menghapus data mahasiswa
    $stmt_delete = $koneksi->prepare("DELETE FROM mhs WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    try {
        $koneksi->begin_transaction(); // Mulai transaksi

        if ($stmt_delete->execute()) {
            // Hapus file foto jika ada
            if ($foto && file_exists("foto/" . $foto)) {
                unlink("foto/" . $foto);
            }

            $koneksi->commit(); // Commit transaksi
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } else {
            $koneksi->rollback(); // Rollback jika gagal
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
        }
    } catch (Exception $e) {
        $koneksi->rollback(); // Rollback jika terjadi kesalahan
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
}

$stmt_select->close();
$stmt_delete->close();