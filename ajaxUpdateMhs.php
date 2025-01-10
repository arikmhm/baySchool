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
    <title>Sistem Informasi Akademik::Daftar Mahasiswa</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styleku.css">
    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" crossorigin="anonymous">
    <script>
    $(document).ready(function () {
        const $result = $('#result');
        const $feedback = $('#feedback');
        const $search = $('#search');
        let timer;

        // Fungsi untuk memuat data mahasiswa
        function loadData(page = 1, query = '') {
            $.ajax({
                url: 'getMhs.php',
                method: 'POST',
                data: { page: page, search: query },
                success: function (response) {
                    $result.html(response);
                },
                error: function () {
                    showFeedback('error', 'Gagal memuat data mahasiswa.');
                }
            });
        }

        // Fungsi untuk menampilkan feedback
        function showFeedback(type, message) {
            $feedback
                .removeClass('alert-success alert-danger')
                .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                .text(message)
                .show();

            setTimeout(function () {
                $feedback.fadeOut();
            }, 2000);
        }

        // Panggil loadData saat halaman dimuat
        loadData();

        // Event untuk pencarian
        $search.keyup(function () {
            clearTimeout(timer);
            const query = $(this).val();
            timer = setTimeout(function () {
                loadData(1, query);
            }, 500);
        });

        // Navigasi event untuk pagination
        $(document).on('click', '.page-link', function (e) {
            e.preventDefault();
            const page = $(this).data('page');
            const query = $search.val(); 
            loadData(page, query);
        });

        // Fungsi untuk menghapus data mahasiswa
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();

            const id = $(this).data('id');
            const row = $(this).closest('tr');

            if (confirm('Yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: 'hpsMhs.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { id: id },
                    success: function (response) {
                        if (response.status === 'success') {
                            showFeedback('success', response.message);
                            row.remove();
                        } else {
                            showFeedback('error', response.message);
                        }
                    },
                    error: function () {
                        showFeedback('error', 'Terjadi kesalahan saat menghapus data.');
                    }
                });
            }
        });
    });
    </script>
</head>
<body>
    <div class="utama">
        <h2 class="text-center">Daftar Mahasiswa</h2>
        <div id="feedback" class="alert" style="display:none;"></div>
        <span class="float-left">
            <a class="btn btn-success" href="addMhs.php">Tambah Data</a>
        </span>
        <span class="float-right">
            <form class="form-inline">
                <input class="form-control mr-2 ml-2" type="text" id="search" placeholder="cari data mahasiswa..." autofocus autocomplete="off">
            </form>
        </span>
        <br><br>
        <div id="result"></div>
    </div>
    <?php require "head.html"; ?>
</body>
</html>