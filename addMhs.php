<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sistem Informasi Akademik::Tambah Data Mahasiswa</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styleku.css">
    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>

    <script>
        $(document).ready(function() {
            // Set maxlength untuk input NIM
            $('#nim').attr('maxlength', '14');

            // Fungsi validasi NIM
            $('#nim').on('blur', function() {
                var nim = $(this).val();
                
                // Validasi panjang NIM
                if(nim.length > 0) { // Hanya validasi jika ada input
                    if(nim.length < 14) {
                        window.alert('Isian tidak sesuai, silahkan isi lagi');
                        $(this).val('').focus();
                        return false;
                    }

                    // Validasi format NIM
                    var regex = /^[A-Za-z0-9]{3}\.[0-9]{4}\.[0-9]{5}$/;
                    if(!regex.test(nim)) {
                        window.alert('Format NIM yang Anda masukkan tidak benar');
                        $(this).val('').focus();
                        return false;
                    }

                    // Cek NIM di database
                    $.ajax({
                        url: 'sv_addMhs.php',
                        type: 'POST',
                        data: {
                            check_nim: true,
                            nim: nim
                        },
                        success: function(response) {
                            if(response.trim() === 'exists') {
                                window.alert('Data sudah ada, silahkan isikan yang lain');
                                $('#nim').val('').focus();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            window.alert('Terjadi kesalahan saat memeriksa NIM');
                        }
                    });
                }
            });

            // Handle form submit dengan AJAX
            $('#formTambahMhs').on('submit', function(e) {
                e.preventDefault(); // Mencegah submit default
                
                // Ambil data dari form
                var formData = new FormData(this);

                // Kirim data menggunakan AJAX
                $.ajax({
                    url: 'sv_addMhs.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Tampilkan pesan sukses atau error
                        if (response.trim() === 'success') {
                            $('#success')
                                .text('Data mahasiswa berhasil ditambahkan.')
                                .show()
                                .delay(3000)
                                .fadeOut();
                            $('#formTambahMhs')[0].reset(); // Reset form
                        } else {
                            alert('Gagal menambahkan data mahasiswa: ' + response);
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menambahkan data mahasiswa');
                    }
                });
            });
        });
    </script>
</head>

<body>
    <?php require "head.html"; ?>
    <div class="utama">
        <br><br><br>
        <h3>TAMBAH DATA MAHASISWA</h3>
        <!-- Alert -->
        <div class="alert alert-success alert-dismissible" id="success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        </div>

         
        <form id="formTambahMhs" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nim">NIM:</label>
                <input class="form-control" type="text" name="nim" id="nim" style="max-width: 200px;" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input class="form-control" type="text" name="nama" id="nama" style="max-width: 500px;" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input class="form-control" type="email" name="email" id="email" style="max-width: 500px;" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input class="form-control" type="file" name="foto" id="foto" style="max-width: 500px;" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</body>

</html>