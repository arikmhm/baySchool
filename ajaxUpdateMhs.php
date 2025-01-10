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
    <link rel="stylesheet" type="text/css" href="css/styleku.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>
    
    <style>
        :root {
            --primary-color: #6366F1;
            --bg-color: #F3F4F9;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Inter', sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
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

        .alert {
            border-radius: 0.5rem;
        }
    </style>

    <script>
    $(document).ready(function () {
        const $result = $('#result');
        const $feedback = $('#feedback');
        const $search = $('#search');
        let timer;

        function loadData(page = 1, query = '') {
            $.ajax({
                url: 'getMhs.php',
                method: 'POST',
                data: { page: page, search: query },
                success: function (response) {
                    $result.html(response);
                },
                error: function () {
                    showFeedback('error', 'Failed to load student data.');
                }
            });
        }

        function showFeedback(type, message) {
            $feedback
                .removeClass('alert-success alert-danger')
                .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
                .text(message)
                .show();

            setTimeout(() => $feedback.fadeOut(), 2000);
        }

        loadData();

        $search.keyup(function () {
            clearTimeout(timer);
            const query = $(this).val();
            timer = setTimeout(() => loadData(1, query), 500);
        });

        $(document).on('click', '.page-link', function (e) {
            e.preventDefault();
            const page = $(this).data('page');
            const query = $search.val(); 
            loadData(page, query);
        });

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const row = $(this).closest('tr');

            if (confirm('Are you sure you want to delete this student?')) {
                $.ajax({
                    url: 'deleteStudent.php',
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
                        showFeedback('error', 'Error occurred while deleting.');
                    }
                });
            }
        });
    });
    </script>
</head>
<body>
    <?php require "head.html"; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h2>Students Management</h2>
            
            <div class="d-flex align-items-center gap-4">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="search" class="search-input" placeholder="Search students..." autofocus>
                </div>
                
                <a href="addStudent.php" class="btn-add">
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