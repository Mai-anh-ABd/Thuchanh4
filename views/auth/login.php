<?php include 'views/layouts/header.php'; ?>
    <div class="container">
        <h2>Đăng nhập</h2>
        <form action="index.php?controller=user&action=login" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Đăng nhập</button>
        </form>
    </div>
<?php include 'views/layouts/footer.php'; ?>