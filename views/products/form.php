<?php include 'views/layouts/header.php'; ?>
    <div class="container">
        <?php
        $is_edit = isset($product['id']);
        $form_action = $is_edit ? "index.php?controller=product&action=update" : "index.php?controller=product&action=create";
        $form_title = $is_edit ? "Chỉnh sửa sản phẩm" : "Đăng sản phẩm mới";
        ?>
        <h2><?= $form_title ?></h2>
        <form action="<?= $form_action ?>" method="POST">
            <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
            <?php endif; ?>

            <label for="title">Tiêu đề:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($product['title'] ?? '') ?>" required>

            <label for="description">Mô tả:</label>
            <textarea id="description" name="description" rows="10" required><?= htmlspecialchars($product['description'] ?? '') ?></textarea>

            <label for="image">Đường dẫn ảnh (URL):</label>
            <input type="text" id="image" name="image" value="<?= htmlspecialchars($product['image'] ?? '') ?>" placeholder="http://example.com/image.jpg">

            <button type="submit"><?= $is_edit ? "Cập nhật sản phẩm" : "Đăng sản phẩm" ?></button>
        </form>
    </div>
<?php include 'views/layouts/footer.php'; ?>