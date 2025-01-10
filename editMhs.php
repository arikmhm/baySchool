<!DOCTYPE html>
<html>
<head>
    <title>Sistem Informasi Akademik::Edit Data Mahasiswa</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styleku.css">
    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>
</head>
<body>
    <?php
    require "fungsi.php";
    require "head.html"; 
    $id = isset($_GET['kode']) ? intval($_GET['kode']) : 0;
    if ($id == 0) {
        die('ID tidak valid!');
    }
    $sql = "SELECT * FROM mhs WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        die('Data tidak ditemukan!');
    }
    $row = $result->fetch_assoc();
    ?>
    <div class="utama">
        <h2 class="mb-3 text-center">EDIT DATA MAHASISWA</h2>
        <div class="row">
            <div class="col-sm-3 text-center">
                <img id="profileImage" class="rounded img-thumbnail" src="foto/<?php echo $row['foto'] ?>">
                <div>
                    [ <a href="#" id="gantiFotoLink">Ganti Foto</a> ]
                </div>
                <input type="file" id="fotoUpload" name="foto" style="display:none;" accept="image/*">
            </div>
            <div class="col-sm-9">
                <div id="feedback" class="alert" style="display:none;"></div>
                <form id="editForm">
                    <div class="form-group">
                        <label for="nim">NIM:</label>
                        <input class="form-control" type="text" name="nim" id="nim" value="<?php echo $row['nim'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input class="form-control" type="text" name="nama" id="nama" value="<?php echo $row['nama'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input class="form-control" type="email" name="email" id="email" value="<?php echo $row['email'] ?>">
                    </div>
                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
                    <button class="btn btn-primary" type="button" id="submitBtn">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        // Edit data mahasiswa
        $('#submitBtn').on('click', function(e) {
            e.preventDefault();
            var formData = {
                id: $('#id').val(),
                nama: $('#nama').val(),
                email: $('#email').val()
            };

            if (!formData.nama || !formData.email) {
                showFeedback('error', 'Semua field harus diisi!');
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'sv_editMhs.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showFeedback('success', response.message);
                    } else {
                        showFeedback('error', response.message);
                    }
                },
                error: function() {
                    showFeedback('error', 'Terjadi kesalahan dalam pengolahan data.');
                }
            });
        });

        // Ganti foto
        $('#gantiFotoLink').on('click', function(e) {
            e.preventDefault();
            $('#fotoUpload').click();
        });

        $('#fotoUpload').on('change', function() {
            var file = this.files[0];
            var formData = new FormData();
            formData.append('foto', file);
            formData.append('id', $('#id').val());

            $.ajax({
                url: 'simpanGantifoto.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showFeedback('success', response.message);
                        $('#profileImage').attr('src', 'foto/' + response.new_filename);
                    } else {
                        showFeedback('error', response.message);
                    }
                },
                error: function() {
                    showFeedback('error', 'Gagal mengunggah foto.');
                }
            });
        });

        // Fungsi untuk menampilkan feedback
        function showFeedback(type, message) {
            var $feedback = $('#feedback');
            $feedback.removeClass('alert-success alert-danger')
                     .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                     .text(message)
                     .show();

            setTimeout(function() {
                $feedback.fadeOut();
            }, 2000);
        }
    });
    </script>
</body>
</html>