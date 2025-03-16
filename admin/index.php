<?php
session_start();

// Jika admin sudah login, redirect ke dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $email = $_POST['email_guru'];
    $password = $_POST['password_guru'];

    // Include koneksi database
    require '../includes/db.php';

    // Query untuk memeriksa email dan password di database
    $sql = "SELECT * FROM guru WHERE email_guru = :email_guru AND password_guru = :password_guru";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email_guru' => $email,
        ':password_guru' => $password
    ]);
    $guru = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data ditemukan, login berhasil
    if ($guru) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['id_guru'] = $guru['id_guru'];
        $_SESSION['nama_guru'] = $guru['nama_guru'];
        header("Location: dashboard/dashboard.php");
        exit;
    } else {
        // Jika data tidak ditemukan, tampilkan pesan error
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h1 class="login-title">Login Admin</h1>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="post" class="login-form">
            <div class="form-group">
                <label for="email_guru" class="form-label">Email:</label>
                <input type="email" id="email_guru" name="email_guru" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="password_guru" class="form-label">Password:</label>
                <input type="password" id="password_guru" name="password_guru" class="form-input" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>