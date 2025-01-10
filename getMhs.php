<?php
require 'fungsi.php';

$jmlDataPerHal = 3; // Jumlah data per halaman
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1; // Halaman yang diminta
$search = isset($_POST['search']) ? trim($_POST['search']) : ''; // Query pencarian

$awalData = ($jmlDataPerHal * $page) - $jmlDataPerHal; // Offset untuk pagination

// Gunakan prepared statement untuk pencarian
$stmt = $koneksi->prepare("SELECT * FROM mhs 
    WHERE (nim LIKE ? OR nama LIKE ? OR email LIKE ?) 
    LIMIT ?, ?");

// Siapkan parameter pencarian
$searchParam = "%{$search}%";
$stmt->bind_param("sssii", 
    $searchParam, 
    $searchParam, 
    $searchParam, 
    $awalData, 
    $jmlDataPerHal
);

$stmt->execute();
$hasil = $stmt->get_result();

$output = '';
if ($hasil->num_rows > 0) {
    $output .= '<table class="table table-hover mt-4">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>';
    $no = $awalData + 1; // Mulai nomor
    while ($row = $hasil->fetch_assoc()) {
        $output .= '<tr>
                        <td>' . $no . '</td>
                        <td>' . htmlspecialchars($row["nim"]) . '</td>
                        <td>' . htmlspecialchars($row["nama"]) . '</td>
                        <td>' . htmlspecialchars($row["email"]) . '</td>
                        <td><img src="foto/' . htmlspecialchars($row["foto"]) . '" height="50"></td>
                        <td>
                            <a class="btn btn-outline-primary btn-sm" href="editMhs.php?kode=' . $row['id'] . '">Edit</a>
                            <button class="btn btn-outline-danger btn-sm btn-delete" data-id="' . $row["id"] . '">Hapus</button>
                        </td>
                    </tr>';
        $no++;
    }
    $output .= '</tbody></table>';

    // Hitung total data untuk pagination
    $countStmt = $koneksi->prepare("SELECT COUNT(*) AS total FROM mhs 
        WHERE nim LIKE ? OR nama LIKE ? OR email LIKE ?");
    $countStmt->bind_param("sss", 
        $searchParam, 
        $searchParam, 
        $searchParam
    );
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalData = $countResult->fetch_assoc()['total'];
    $jmlHal = ceil($totalData / $jmlDataPerHal);

    $output .= '<ul class="pagination">';

    if ($page > 1) {
        $prevPage = $page - 1;
        $output .= "<li class='page-item'><a class='page-link' href='#' data-page='$prevPage'>&laquo;</a></li>";
    }

    for ($i = 1; $i <= $jmlHal; $i++) {
        $active = $i == $page ? "style='font-weight:bold;color:red;'" : '';
        $output .= "<li class='page-item'><a class='page-link' href='#' data-page='$i' $active>$i</a></li>";
    }

    if ($page < $jmlHal) {
        $nextPage = $page + 1;
        $output .= "<li class='page-item'><a class='page-link' href='#' data-page='$nextPage'>&raquo;</a></li>";
    }
    $output .= '</ul>';
} else {
    $output .= '<div class="alert alert-info alert-dismissible fade show text-center">Data tidak ditemukan</div>';
}

echo $output;