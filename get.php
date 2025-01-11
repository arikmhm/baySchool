<?php
require 'fungsi.php';

$jmlDataPerHal = 5; // Data per page
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

$awalData = ($jmlDataPerHal * $page) - $jmlDataPerHal;

// Prepared statement for search
$stmt = $koneksi->prepare("SELECT * FROM students 
    WHERE (name LIKE ? OR nis LIKE ? OR nisn LIKE ? OR parent_name LIKE ?) 
    LIMIT ?, ?");

$searchParam = "%{$search}%";
$stmt->bind_param("ssssii", 
    $searchParam, 
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
    $output .= '<div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Parent Name</th>
                            <th>City</th>
                            <th>L/P</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>';
    
    $no = $awalData + 1;
    while ($row = $hasil->fetch_assoc()) {
        $firstLetter = strtoupper(substr($row["name"], 0, 1));
        $gradeBadgeClass = strpos(strtolower($row["grade"]), 'xi') !== false ? 'xi' : 'x';
        
        $output .= '<tr>
                        <td>' . $no . '</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">' . $firstLetter . '</div>
                                <span>' . htmlspecialchars($row["name"]) . '</span>
                            </div>
                        </td>
                        <td>' . htmlspecialchars($row["nis"]) . '</td>
                        <td>' . htmlspecialchars($row["nisn"]) . '</td>
                        <td>' . htmlspecialchars($row["parent_name"]) . '</td>
                        <td>' . htmlspecialchars($row["city"]) . '</td>
                        <td>' . htmlspecialchars($row["gender"]) . '</td>
                        <td>
                            <span class="grade-badge ' . $gradeBadgeClass . '">' 
                                . htmlspecialchars($row["grade"]) . 
                            '</span>
                        </td>
                        <td>
                            <a href="edit.php?id=' . $row["id"] . '" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger btn-delete" 
                                    data-id="' . $row["id"] . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>';
        $no++;
    }
    $output .= '</tbody></table></div>';

    // Calculate total pages for pagination
    $countStmt = $koneksi->prepare("SELECT COUNT(*) AS total FROM students 
        WHERE name LIKE ? OR nis LIKE ? OR nisn LIKE ? OR parent_name LIKE ?");
    $countStmt->bind_param("ssss", 
        $searchParam, 
        $searchParam, 
        $searchParam,
        $searchParam
    );
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalData = $countResult->fetch_assoc()['total'];
    $jmlHal = ceil($totalData / $jmlDataPerHal);

    if ($jmlHal > 1) {
        $output .= '<nav><ul class="pagination">';

        if ($page > 1) {
            $output .= '<li class="page-item">
                        <a class="page-link" href="#" data-page="' . ($page - 1) . '">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                       </li>';
        }

        for ($i = 1; $i <= $jmlHal; $i++) {
            $activeClass = $i == $page ? ' active' : '';
            $output .= '<li class="page-item">
                        <a class="page-link' . $activeClass . '" href="#" data-page="' . $i . '">' 
                            . $i . 
                        '</a>
                       </li>';
        }

        if ($page < $jmlHal) {
            $output .= '<li class="page-item">
                        <a class="page-link" href="#" data-page="' . ($page + 1) . '">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                       </li>';
        }

        $output .= '</ul></nav>';
    }
} else {
    $output .= '<div class="alert alert-info">No students found</div>';
}

echo $output;