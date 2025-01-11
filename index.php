<?php session_start() ?>

<?php
    if (isset($_POST['username'])) {
        require "fungsi.php";
        $username = $_POST['username'];
        $passw = $_POST['passw'];
        
        // Gunakan prepared statement untuk keamanan
        $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $passw);
        $stmt->execute();
        $hasil = $stmt->get_result();
        
        if ($hasil->num_rows > 0) {
            $_SESSION['username'] = $username;
            header("location:dashboard.php");
        }
        $stmt->close();
    }
    ?>
    
<!DOCTYPE html>
<html>
<head>
    <title>Bay School - Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap4/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background-image: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), url('assets/bg.png');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #E97451;
            border-radius: 12px;
            padding: 12px;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text {
            font-weight: 600;
            font-size: 1.5rem;
            color: #252525;
        }

        .welcome-text {
            color: #475569;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: #475569;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #6366F1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94A3B8;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: #6366F1;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background: #4F46E5;
        }

        .alert {
            margin-top: 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card">
                        <div class="brand-section">
                            <div class="brand-logo">
                                <img src="assets/logo.png" alt="Bay School Logo">
                            </div>
                            <div class="brand-text">Bay School</div>
                        </div>

                        <h4 class="welcome-text">WELCOME BACK</h4>
                        <h5 class="mb-4">Log In to your Account</h5>

                        <?php if(isset($_POST['username']) && !isset($_SESSION['username'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Login failed. Please try again.
                        </div>
                        <?php endif; ?>

                        <form method="post" action="">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input class="form-control" type="text" name="username" id="username" 
                                       placeholder="Enter your username" required autofocus>
                            </div>
                            
                            <div class="form-group">
                                <label for="passw">Password</label>
                                <div class="password-field">
                                    <input class="form-control" type="password" name="passw" id="passw" 
                                           placeholder="••••••••••••" required>
                                    <span class="password-toggle" onclick="togglePassword()">
                                        <i class="far fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <button class="btn-login" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap4/jquery/3.3.1/jquery-3.3.1.js"></script>
    <script src="bootstrap4/js/bootstrap.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passw');
            const toggleIcon = document.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>

    
</body>
</html>