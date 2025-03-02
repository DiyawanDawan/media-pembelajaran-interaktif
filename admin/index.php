<!-- TODO (Dashboard Admin) admin/index.php -->

<?php
require_once '../includes/auth.php'; // Pastikan admin sudah login
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <h1>Dashboard Admin</h1>
    <nav>
        <a href="materi.php">Kelola Materi</a> |
        <a href="kuis.php">Kelola Kuis</a> |
        <a href="game.php">Kelola Game</a> |
        <a href="logout.php">Logout</a>
    </nav>
</body>
</html>