<?php include 'views/layouts/header.php'; ?>
    <div class="container">
        <h1><?= htmlspecialchars($product['title']) ?></h1>
        <p>Đăng bởi: <?= htmlspecialchars($product['user_name'] ?? 'N/A') ?> vào lúc <?= date("d/m/Y H:i", strtotime($product['created_at'])) ?></p>
        <img src="<?= htmlspecialchars($product['image'] ?? 'public/images/default.jpg') ?>" alt="<?= htmlspecialchars($product['title']) ?>" width="300">
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $product['user_id']): ?>
            <p><a href="index.php?controller=product&action=edit&id=<?= $product['id'] ?>">Chỉnh sửa sản phẩm này</a></p>
        <?php endif; ?>

        ---
        <h2>Bình luận</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="index.php?controller=comment&action=add" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <label for="content">Thêm bình luận của bạn:</label><br>
                <textarea id="content" name="content" rows="4" cols="50" required></textarea><br>
                <button type="submit">Gửi bình luận</button>
            </form>
        <?php else: ?>
            <p>Bạn cần <a href="index.php?controller=user&action=login">đăng nhập</a> để bình luận.</p>
        <?php endif; ?>

        <div class="comments-list">
            <?php if (empty($comments)): ?>
                <p>Chưa có bình luận nào.</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item">
                        <p><strong><?= htmlspecialchars($comment['user_name'] ?? 'Ẩn danh') ?></strong> vào lúc <?= date("d/m/Y H:i", strtotime($comment['created_at'])) ?>:
                           <?= nl2br(htmlspecialchars($comment['content'])) ?>
                           <?php if (!empty($comment['updated_at'])): ?>
                               <small>(đã chỉnh sửa)</small>
                           <?php endif; ?>
                        </p>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                            <p><a href="index.php?controller=comment&action=edit&id=<?= $comment['id'] ?>">Sửa bình luận</a></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php include 'views/layouts/footer.php'; ?>