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
    <title>Bay School - Students Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.css">

    <link rel="stylesheet" type="text/css" href="css/sidebar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/page.css">

    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>
    
    <style>
        .page-header {
            display: flex;
            justify-content: right;
            align-items: center;
            margin-bottom: 2rem;
        }

        .search-container {
            position: relative;
            max-width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #E2E8F0;
            border-radius: 0.5rem;
            background-color: white;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
        }

        .btn-add {
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-add:hover {
            opacity: 0.9;
            color: white;
            text-decoration: none;
        }

        .table {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .table th {
            background-color: #F8FAFC;
            color: #64748B;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            color: var(--primary-color);
        }

        .grade-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .pagination {
            margin-top: 1.5rem;
            justify-content: center;
        }

        .page-link {
            border: none;
            color: #64748B;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
        }

        .page-link.active {
            background-color: var(--primary-color);
            color: white;
        }
    </style>

    <script src="js/script.js"></script>
</head>
<body>
    <?php require "sidebar.html"; ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-light bg-white shadow-sm py-4">
        <div class="container-fluid">
            <h1 class="h3 mb-0 text-gray-800">Add New Student</h1>
            
            <ul class="navbar-nav ml-auto">
                
                <li class="nav-item mx-3">
                    <a class="nav-link" href="#"><i class="fas fa-bell"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-user"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Page-header -->
    <div class="main-content">
        <div class="page-header">
            <div class="d-flex align-items-center gap-4">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="search" class="search-input" placeholder="Search students..." autofocus>
                </div>
                
                <a href="add.php" class="btn-add">
                    <i class="fas fa-plus"></i>
                    New Student
                </a>
            </div>
        </div>

        <div id="feedback" class="alert" style="display:none;"></div>
        <div id="result"></div>
    </div>

</body>
</html>