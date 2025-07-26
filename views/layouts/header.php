<?php
// views/layouts/header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống sản phẩm</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php?controller=product&action=index">Trang chủ</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?controller=product&action=create">Đăng sản phẩm mới</a>
                <span>Chào mừng, <?= htmlspecialchars($_SESSION['user_name']) ?>!</span>
                <a href="index.php?controller=user&action=logout">Đăng xuất</a>
            <?php else: ?>
                <a href="index.php?controller=user&action=login">Đăng nhập</a>
                <a href="index.php?controller=user&action=register">Đăng ký</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='message'>" . htmlspecialchars($_SESSION['message']) . "</div>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='error'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']);
        }
        ?>