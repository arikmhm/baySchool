<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
}
require "fungsi.php";

// Hitung total data
$total_dosen = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM dosen"))['total'];
$total_matkul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM matkul"))['total'];
$total_kultawar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kultawar"))['total'];

// Ambil data kultawar terbaru
$query_latest = "SELECT k.*, m.namamatkul, d.namadosen 
                FROM kultawar k
                LEFT JOIN matkul m ON k.idmatkul = m.idmatkul
                LEFT JOIN dosen d ON k.npp = d.npp
                ORDER BY k.idkultawar DESC LIMIT 5";
$latest_kultawar = mysqli_query($koneksi, $query_latest);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Home Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styleku.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <!-- FullCalendar Core -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <!-- FullCalendar Locales -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    
    <style>
        .card-menu {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .card-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .icon-large {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .stat-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .welcome-section {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            border-radius: 15px;
        }
        .latest-section {
            margin-top: 30px;
        }
        .table-latest {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .dashboard-title {
            color: #333;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            padding: 10px 0;
            border-bottom: 2px solid #007bff;
        }
        #calendar {
        width: 100%;
        min-height: 400px;
        background: white;
        padding: 15px;
        border-radius: 8px;
    }

    .fc-toolbar-title {
        font-size: 1.2em !important;
    }

    .fc .fc-button {
        padding: 0.2em 0.4em !important;
        font-size: 0.9em !important;
    }

    .fc-event {
        border: none;
        padding: 2px 5px;
        margin: 2px 0;
        cursor: pointer;
    }

    .fc-day-today {
        background-color: rgba(0, 123, 255, 0.1) !important;
    }
    </style>
</head>

<body class="bg-light">
    <?php require "head.html"; ?>
    

   <!-- Navbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">
    <div class="container-fluid">
        <!-- Navbar brand -->
        <div class="navbar-brand">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Administrator</h1>
        </div>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Nav Item - Search -->
            <li class="nav-item">
                <div class="nav-link">
                    <div class="search-group">
                        <input type="text" class="form-control" placeholder="Search...">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </li>

            <!-- Nav Item - Notifications -->
            <li class="nav-item mx-3">
                <a class="nav-link" href="#">
                    <i class="fas fa-bell"></i>
                </a>
            </li>

            <!-- Nav Item - User -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-user"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4" >
        

         <!-- Statistics Card -->
         <div class="row mb-4 mt-4">
            <div class="col-md-12">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-user-graduate text-primary icon-large"></i>
                                    <h3 class="mt-2">932</h3>
                                    <p class="text-muted">Total Siswa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Performa Siswa</h5>
                        <div class="chart-container" style="position: relative; height:400px;">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar and Bar Chart Section -->
        <div class="row mb-4">
            <!-- Calendar -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Kalender Akademik</h5>
                    <div id="calendar" style="background: white; padding: 15px; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
            <!-- Bar Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Statistik Performa Semester</h5>
                        <div class="chart-container" style="position: relative; height:400px;">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-menu">
                    <div class="card-body text-center">
                        <i class="fas fa-user-tie text-primary icon-large"></i>
                        <h5 class="card-title">Kelola Dosen</h5>
                        <p class="card-text">Tambah, edit, dan hapus data dosen</p>
                        <a href="ajaxUpdateDsn.php" class="btn btn-primary">Akses</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-menu">
                    <div class="card-body text-center">
                        <i class="fas fa-book text-success icon-large"></i>
                        <h5 class="card-title">Kelola Mata Kuliah</h5>
                        <p class="card-text">Tambah, edit, dan hapus mata kuliah</p>
                        <a href="ajaxUpdateMatkul.php" class="btn btn-success">Akses</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-menu">
                    <div class="card-body text-center">
                        <i class="fas fa-chalkboard-teacher text-info icon-large"></i>
                        <h5 class="card-title">Kelola Kuliah Tawar</h5>
                        <p class="card-text">Atur penawaran mata kuliah</p>
                        <a href="ajaxUpdateKultawar.php" class="btn btn-info">Akses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Line Chart
    var ctxLine = document.getElementById('performanceChart').getContext('2d');
    var dataLine = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
            {
                label: 'Semester 1',
                data: [75, 68, 80, 75, 65, 70, 85, 80, 75, 70, 85, 80],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Semester 2',
                data: [65, 70, 75, 80, 70, 75, 80, 85, 80, 75, 90, 85],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    };

    new Chart(ctxLine, {
        type: 'line',
        data: dataLine,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { stepSize: 20 }
                }
            },
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            }
        }
    });

    // Bar Chart
    var ctxBar = document.getElementById('barChart').getContext('2d');
    var dataBar = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
            {
                label: 'Semester 1',
                data: [75, 68, 80, 75, 65, 70, 85, 80, 75, 70, 85, 80],
                backgroundColor: 'rgba(0, 123, 255, 0.7)',
            },
            {
                label: 'Semester 2',
                data: [65, 70, 75, 80, 70, 75, 80, 85, 80, 75, 90, 85],
                backgroundColor: 'rgba(255, 193, 7, 0.7)',
            }
        ]
    };

    new Chart(ctxBar, {
        type: 'bar',
        data: dataBar,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { stepSize: 20 }
                }
            },
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    // Calendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: '400px',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        buttonText: {
            today: 'Hari Ini'
        },
        events: [
            {
                title: 'UTS Semester Ganjil',
                start: '2025-01-15',
                end: '2025-01-20',
                backgroundColor: '#007bff',
                borderColor: '#007bff'
            },
            {
                title: 'UAS Semester Ganjil',
                start: '2025-01-25',
                end: '2025-01-30',
                backgroundColor: '#28a745',
                borderColor: '#28a745'
            },
            {
                title: 'Libur Semester',
                start: '2025-02-01',
                end: '2025-02-14',
                backgroundColor: '#ffc107',
                borderColor: '#ffc107'
            },
            {
                title: 'Awal Perkuliahan',
                start: '2025-02-15',
                backgroundColor: '#17a2b8',
                borderColor: '#17a2b8'
            }
        ],
        locale: 'id',
        firstDay: 1,
        displayEventTime: false,
        eventDisplay: 'block'
    });
    calendar.render();
});
</script>
</body>
</html>