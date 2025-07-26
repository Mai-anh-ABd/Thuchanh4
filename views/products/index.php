<?php include 'views/layouts/header.php'; ?>
    <div class="container">
        <h1>Danh sách sản phẩm</h1>
        <div class="product-list">
            <?php if (empty($products)): ?>
                <p>Chưa có sản phẩm nào được đăng.</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <h3><a href="index.php?controller=product&action=show&id=<?= $product['id'] ?>"><?= htmlspecialchars($product['title']) ?></a></h3>
                        <p>Đăng bởi: <?= htmlspecialchars($product['user_name'] ?? 'N/A') ?></p>
                        <p><img src="<?= htmlspecialchars($product['image'] ?? 'public/images/default.jpg') ?>" alt="<?= htmlspecialchars($product['title']) ?>" width="100"></p>
                        <p><?= nl2br(htmlspecialchars(substr($product['description'], 0, 150))) ?>...</p>
                        <a href="index.php?controller=product&action=show&id=<?= $product['id'] ?>">Xem chi tiết</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php include 'views/layouts/footer.php'; ?>