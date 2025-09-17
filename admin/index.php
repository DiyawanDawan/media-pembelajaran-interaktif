<?php
session_start();

// Jika admin sudah login, redirect ke dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard/dashboard.php");
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
    <!--<link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* style.css */
.login-page {
    background: linear-gradient(135deg, #83a4d4 0%, #b6fbff 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Comic Neue', cursive;
}

.login-container {
    background: rgba(255, 255, 255, 0.95);
    padding: 2rem 3rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transform: translateY(0);
    animation: float 3s ease-in-out infinite;
    max-width: 400px;
    width: 90%;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.login-title {
    color: #2c3e50;
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 2rem;
    position: relative;
}

.login-title::after {
    content: "🔒";
    position: absolute;
    right: -10px;
    top: -15px;
    font-size: 1.5rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.form-group {
    margin-bottom: 1.5rem;
    position: relative;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-size: 1.1rem;
    transform-origin: left;
    transition: all 0.3s ease;
}

.form-input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #bdc3c7;
    border-radius: 10px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    background: #f9f9f9;
}

.form-input:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 10px rgba(52, 152, 219, 0.3);
    transform: scale(1.02);
}

.form-input:focus + .form-label {
    color: #3498db;
    transform: translateY(-25px) scale(0.9);
}

.login-button {
    width: 100%;
    padding: 15px;
    background: linear-gradient(45deg, #3498db, #2980b9);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.login-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
}

.login-button:active {
    transform: translateY(0);
}

.login-button::after {
    content: "→";
    position: absolute;
    right: 20px;
    opacity: 0;
    transition: all 0.3s ease;
}

.login-button:hover::after {
    opacity: 1;
    right: 15px;
}

.error-message {
    background: #ffebe6;
    color: #e74c3c;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: shake 0.5s ease;
}

.error-message::before {
    content: "⚠️";
    font-size: 1.2rem;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(10px); }
    50% { transform: translateX(-10px); }
    75% { transform: translateX(5px); }
}

/* Animasi awan */
.clouds {
    position: fixed;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: -1;
}

.cloud {
    position: absolute;
    background: white;
    border-radius: 20px;
    animation: cloud-float 20s infinite linear;
}

@keyframes cloud-float {
    from { transform: translateX(-100%); }
    to { transform: translateX(100%); }
}
/* Ubah bagian form-input */
.form-input {
    width: 100%;
    padding: 12px 15px;
    border: 3px solid #FFD700; /* Warna kuning emas */
    border-radius: 15px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    background: #FFF9C4; /* Kuning pastel */
    color: #2E86C1; /* Warna teks biru cerah */
    font-weight: bold;
}

.form-input::placeholder {
    color: #F39C12; /* Warna placeholder oranye */
    opacity: 0.8;
}

.form-input:focus {
    border-color: #FF6B6B; /* Merah muda saat focus */
    background: #FFFFFF;
    box-shadow: 0 0 15px rgba(255, 107, 107, 0.3);
    color: #E74C3C; /* Warna teks merah saat aktif */
}

.form-input:focus + .form-label {
    color: #FF6B6B; /* Warna label match dengan border */
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

/* Tambahkan animasi rainbow untuk hover */
.form-input:hover {
    animation: rainbow-border 2s infinite;
}

@keyframes rainbow-border {
    0% { border-color: #FF6B6B; }
    25% { border-color: #FFD700; }
    50% { border-color: #2ECC71; }
    75% { border-color: #3498DB; }
    100% { border-color: #9B59B6; }
}

/* Tambahkan ikon lucu saat input aktif */
.form-group:focus-within::after {
    content: "🌟";
    position: absolute;
    right: 15px;
    top: 35px;
    font-size: 1.2rem;
    animation: spin 1s ease-in-out;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
/* Tambahkan CSS untuk eye toggle */
.password-wrapper {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 1.3rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
    padding: 2px 5px;
    border-radius: 50%;
    animation: eye-blink 2s infinite;
}

.toggle-password:hover {
    transform: translateY(-50%) scale(1.2);
    filter: drop-shadow(0 0 3px rgba(255, 105, 180, 0.5));
}

@keyframes eye-blink {
    0%, 100% { transform: translateY(-50%) scale(1); }
    50% { transform: translateY(-50%) scale(0.9); }
}

.toggle-password.active {
    animation: none;
    content: "🚫";
    color: #ff4757;
}
    </style>
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
            <!-- Tambahkan di dalam form-group password -->
<div class="form-group">
    <label for="password_guru" class="form-label">Password:</label>
    <div class="password-wrapper">
        <input type="password" id="password_guru" name="password_guru" class="form-input" required>
        <span class="toggle-password" onclick="togglePassword()">👀</span>
    </div>
</div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
    <div class="clouds">
    <div class="cloud" style="top:20%; left:-200px; width:150px; height:60px;"></div>
    <div class="cloud" style="top:40%; left:-300px; width:200px; height:80px; animation-delay:-5s;"></div>
    <div class="cloud" style="top:60%; left:-250px; width:180px; height:70px; animation-delay:-8s;"></div>
</div>
<script>
    // Tambahkan fungsi toggle password
function togglePassword() {
    const passwordInput = document.getElementById('password_guru');
    const toggleIcon = document.querySelector('.toggle-password');
    
    if(passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = '🚫';
        toggleIcon.classList.add('active');
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = '👀';
        toggleIcon.classList.remove('active');
    }
    
    // Animasi tekan
    toggleIcon.style.transform = 'translateY(-50%) scale(0.9)';
    setTimeout(() => {
        toggleIcon.style.transform = 'translateY(-50%) scale(1)';
    }, 100);
}
</script>
</body>
</html>