<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
}
require "fungsi.php";

// Fetch statistics from database
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM students"))['total'];

// Get gender statistics
$gender_stats = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT 
        SUM(CASE WHEN gender = 'L' THEN 1 ELSE 0 END) as total_male,
        SUM(CASE WHEN gender = 'P' THEN 1 ELSE 0 END) as total_female
    FROM students
"));

// Get grade distribution
$grade_query = mysqli_query($koneksi, "SELECT grade, COUNT(*) as count FROM students GROUP BY grade");
$grade_labels = [];
$grade_data = [];
while ($row = mysqli_fetch_assoc($grade_query)) {
    $grade_labels[] = $row['grade'];
    $grade_data[] = $row['count'];
}

// Get latest students
$latest_students = mysqli_query($koneksi, "SELECT * FROM students ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Administrator - Bay School</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styleku.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    
    <style>
        :root {
            --primary-color: #6366F1;
            --secondary-color: #818CF8;
            --bg-color: #F3F4F9;
            --text-color: #1E293B;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Inter', sans-serif;
        }

        .main-content {
            margin-top: 48px;
            margin-left: 250px;
            padding: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .icon-large {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .chart-container {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .card-menu {
            border: none;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .card-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(99, 102, 241, 0.15);
        }

        .menu-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .calendar-container {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        

        .search-group {
            position: relative;
        }

        .search-group input {
            padding-left: 2.5rem;
            border-radius: 0.5rem;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
        }
    </style>
</head>

<body>
    <?php require "head.html"; ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-light bg-white mb-4 shadow-sm py-4">
        <div class="container-fluid">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Administrator</h1>
            
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <div class="search-group">
                        <input type="text" class="form-control" placeholder="Search...">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="#"><i class="fas fa-bell"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-user"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-content">
        <!-- Statistics Cards -->
        <div class="row mb-4 mt-4">
            <div class="col-md-3">
                <div class="stat-card p-4">
                    <div class="text-center">
                        <i class="fas fa-user-graduate text-primary icon-large"></i>
                        <h3><?php echo $total_siswa; ?></h3>
                        <p class="text-muted mb-0">Total Siswa</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-4">
                    <div class="text-center">
                        <i class="fas fa-male text-success icon-large"></i>
                        <h3><?php echo $gender_stats['total_male']; ?></h3>
                        <p class="text-muted mb-0">Siswa Laki-laki</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-4">
                    <div class="text-center">
                        <i class="fas fa-female text-info icon-large"></i>
                        <h3><?php echo $gender_stats['total_female']; ?></h3>
                        <p class="text-muted mb-0">Siswa Perempuan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-4">
                    <div class="text-center">
                        <i class="fas fa-chalkboard text-warning icon-large"></i>
                        <h3><?php echo count($grade_labels); ?></h3>
                        <p class="text-muted mb-0">Total Kelas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="chart-container">
                    <h5 class="card-title mb-4">Distribusi Siswa per Kelas</h5>
                    <canvas id="gradeDistributionChart"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="chart-container">
                    <h5 class="card-title mb-4">Rasio Gender</h5>
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Calendar and Recent Activity -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="calendar-container">
                    <h5 class="card-title mb-4">Kalender Akademik</h5>
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="chart-container">
                    <h5 class="card-title mb-4">Siswa Terbaru</h5>
                    <div class="recent-students">
                        <?php while($student = mysqli_fetch_assoc($latest_students)) : ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar bg-light rounded-circle p-2 mr-3">
                                    <?php echo substr($student['name'], 0, 1); ?>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?php echo $student['name']; ?></h6>
                                    <small class="text-muted"><?php echo $student['grade']; ?></small>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Menu -->
        <div class="row">
            <div class="col-md-4">
                <div class="card card-menu">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-user-graduate text-primary menu-icon"></i>
                        <h5 class="card-title">Kelola Siswa</h5>
                        <p class="card-text">Manajemen data dan informasi siswa</p>
                        <a href="ajaxUpdateMhs.php" class="btn btn-primary">Akses</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-menu">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-chalkboard-teacher text-success menu-icon"></i>
                        <h5 class="card-title">Kelola Guru</h5>
                        <p class="card-text">Manajemen data dan informasi guru</p>
                        <a href="ajaxUpdateGuru.php" class="btn btn-success">Akses</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-menu">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-book text-info menu-icon"></i>
                        <h5 class="card-title">Kelola Mapel</h5>
                        <p class="card-text">Manajemen mata pelajaran</p>
                        <a href="ajaxUpdateMapel.php" class="btn btn-info">Akses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grade Distribution Chart
        const gradeCtx = document.getElementById('gradeDistributionChart').getContext('2d');
        new Chart(gradeCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($grade_labels); ?>,
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: <?php echo json_encode($grade_data); ?>,
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // Gender Distribution Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [
                        <?php echo $gender_stats['total_male']; ?>,
                        <?php echo $gender_stats['total_female']; ?>
                    ],
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(244, 114, 182, 0.8)'
                    ],
                    borderColor: [
                        'rgba(99, 102, 241, 1)',
                        'rgba(244, 114, 182, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Calendar
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: [
                {
                    title: 'Ujian Tengah Semester',
                    start: '2025-01-15',
                    end: '2025-01-20',
                    backgroundColor: '#6366F1'
                },
                {
                    title: 'Ujian Akhir Semester',
                    start: '2025-01-25',
                    end: '2025-01-30',
                    backgroundColor: '#818CF8'
                },
                {
                    title: 'Libur Semester',
                    start: '2025-02-01',
                    end: '2025-02-14',
                    backgroundColor: '#C7D2FE'
                }
            ],
            height: 450
        });
        calendar.render();
    });
    </script>
</body>
</html>