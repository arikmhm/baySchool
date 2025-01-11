<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:index.php");
    exit();
}

require "fungsi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    die('ID tidak valid!');
}

$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('Data tidak ditemukan!');
}
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bay School - Edit Student</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>

    <link rel="stylesheet" type="text/css" href="css/sidebar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/page.css">
    <style>
                .page-header {
            margin-bottom: 2rem;
        }

        .form-section {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .section-title {
            background: #6366F1;
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }

        .form-group label {
            font-weight: 500;
            color: #64748B;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #E2E8F0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: #6366F1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        }

        select.form-control {
            height: auto !important; /* Override bootstrap default */
            padding: 0.75rem 1rem;
            appearance: none; /* Menghilangkan default style browser */
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236366F1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
            padding-right: 2.5rem;
        }

        select.form-control:focus {
            border-color: #6366F1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
        }

        .btn-submit {
            background-color: #6366F1;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 500;
        }

        .btn-draft {
            background-color: transparent;
            color: #6366F1;
            border: 1px solid #6366F1;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            margin-right: 1rem;
        }

        .gender-select {
            display: inline-block;
            padding: 0.75rem 2rem;
            border: 1px solid #E2E8F0;
            border-radius: 0.5rem;
            margin-right: 1rem;
            cursor: pointer;
            color: #64748B;
        }

        .gender-select.active {
            background-color: #6366F1;
            color: white;
            border-color: #6366F1;
        }

        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>

<body>
    <?php require "sidebar.html"; ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-light bg-white shadow-sm py-4">
        <div class="container-fluid">
            <h1 class="h3 mb-0 text-gray-800">Edit Student</h1>
            
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
        <div class="form-section">
            <h4 class="section-title">Student Details</h4>
            
            <form id="editStudentForm">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required-field">Name</label>
                            <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($row['name']); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required-field">NIS</label>
                            <input type="text" class="form-control" name="nis" required value="<?php echo htmlspecialchars($row['nis']); ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required-field">Parent Name</label>
                            <input type="text" class="form-control" name="parent_name" required value="<?php echo htmlspecialchars($row['parent_name']); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required-field">NISN</label>
                            <input type="text" class="form-control" name="nisn" required value="<?php echo htmlspecialchars($row['nisn']); ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required-field">City</label>
                            <input type="text" class="form-control" name="city" required value="<?php echo htmlspecialchars($row['city']); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required-field">Grade</label>
                            <select class="form-control" name="grade" required>
                                <option value="">Select Grade</option>
                                <?php
                                $grades = ["X MIPA 1", "X MIPA 2", "X MIPA 3", "XI IPA 1", "XI IPA 2", "XI IPS 1", "XI IPS 2"];
                                foreach ($grades as $grade) {
                                    $selected = ($grade === $row['grade']) ? 'selected' : '';
                                    echo "<option value=\"$grade\" $selected>$grade</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label class="required-field">Gender</label><br>
                        <div class="gender-select <?php echo ($row['gender'] === 'L') ? 'active' : ''; ?>" data-value="L">
                            <input type="radio" name="gender" value="L" required hidden <?php echo ($row['gender'] === 'L') ? 'checked' : ''; ?>>
                            Laki-laki
                        </div>
                        <div class="gender-select <?php echo ($row['gender'] === 'P') ? 'active' : ''; ?>" data-value="P">
                            <input type="radio" name="gender" value="P" required hidden <?php echo ($row['gender'] === 'P') ? 'checked' : ''; ?>>
                            Perempuan
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-draft" onclick="window.location.href='students.php'">Cancel</button>
                    <button type="submit" class="btn btn-submit">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Gender selection
        $('.gender-select').click(function() {
            $('.gender-select').removeClass('active');
            $(this).addClass('active');
            $(this).find('input[type="radio"]').prop('checked', true);
        });

        // Form submission
        $('#editStudentForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: 'sv_edit.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.status === 'success') {
                        alert('Student data updated successfully');
                        window.location.href = 'students.php';
                    } else {
                        alert('Error updating student data: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error occurred while updating data');
                }
            });
        });
    });
    </script>
</body>
</html>