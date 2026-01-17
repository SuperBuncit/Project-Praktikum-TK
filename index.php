<?php
require_once 'config/config.php';
require_once 'helpers/functions.php';

session_start();

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Set Session
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            header("Location: dashboard.php");
            exit;
        }
    }

    $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .login-header {
            background: transparent;
            color: #4e73df;
        }
    </style>
</head>

<body>

    <div class="card login-card animate__animated animate__fadeInUp" style="width: 100%; max-width: 420px;">
        <div class="card-body p-5">
            <div class="text-center mb-5">
                <i class="fas fa-school fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold text-dark">TK Modern</h3>
                <p class="text-muted">Sistem Informasi Manajemen Sekolah</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center animate__animated animate__shakeX" role="alert">
                    <small>Username atau Password salah!</small>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                        required autofocus>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        required>
                    <label for="password">Password</label>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 py-3 fs-6 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Masuk Sekarang
                </button>
            </form>
        </div>
        <div class="card-footer text-center bg-transparent border-0 pb-4">
            <small class="text-muted">&copy; <?= date('Y'); ?> TK Modern System. Build with <i
                    class="fas fa-heart text-danger"></i></small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>

</html>