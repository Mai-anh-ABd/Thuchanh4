<?php include 'views/layouts/header.php'; ?>
    <div class="container">
        <h2>Đăng ký tài khoản</h2>
        <form action="index.php?controller=user&action=register" method="POST">
            <label for="name">Tên của bạn:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Đăng ký</button>
        </form>
    </div>
<?php include 'views/layouts/footer.php'; ?>