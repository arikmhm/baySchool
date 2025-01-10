<?php
require "fungsi.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $folderupload = "foto/";
    
    // Gunakan prepared statement untuk mengambil foto lama
    $stmt_select = $koneksi->prepare("SELECT foto FROM mhs WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $row = $result->fetch_assoc();
    $old_foto = $row['foto'];
    $stmt_select->close();

    // Validasi file upload
    $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . "." . $extension;
    $fileupload = $folderupload . $new_filename;

    // Cek ukuran file
    if ($_FILES["foto"]["size"] > 1000000) {
        echo json_encode(['status' => 'error', 'message' => 'Ukuran file terlalu besar (maks. 1 MB)']);
        exit;
    }

    // Cek tipe file
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($extension, $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Hanya format JPG, JPEG, PNG, dan GIF yang diperbolehkan']);
        exit;
    }

    // Proses upload file
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $fileupload)) {
        // Gunakan prepared statement untuk update foto
        $stmt_update = $koneksi->prepare("UPDATE mhs SET foto = ? WHERE id = ?");
        $stmt_update->bind_param("si", $new_filename, $id);
        
        if ($stmt_update->execute()) {
            // Hapus foto lama
            if ($old_foto && file_exists($folderupload . $old_foto)) {
                unlink($folderupload . $old_foto);
            }
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'Foto berhasil diupdate', 
                'new_filename' => $new_filename
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate database']);
        }
        
        $stmt_update->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload foto']);
    }
    exit;
}
?>