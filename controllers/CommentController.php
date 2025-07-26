<?php
// controllers/CommentController.php

require_once 'config/database.php';
require_once 'models/Comment.php';

class CommentController {
    private $db;
    private $comment;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->comment = new Comment($this->db);
    }

    public function add() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để bình luận.";
            header("Location: index.php?controller=user&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['content'])) {
            $this->comment->user_id = $_SESSION['user_id'];
            $this->comment->product_id = $_POST['product_id'];
            $this->comment->content = $_POST['content'];

            if ($this->comment->add()) {
                $_SESSION['message'] = "Bình luận của bạn đã được thêm.";
            } else {
                $_SESSION['error'] = "Thêm bình luận thất bại.";
            }
            header("Location: index.php?controller=product&action=show&id=" . $this->comment->product_id);
            exit();
        } else {
            $_SESSION['error'] = "Dữ liệu bình luận không hợp lệ.";
            header("Location: index.php?controller=product&action=index"); // Hoặc trang lỗi
            exit();
        }
    }

    // --- Bổ sung cho Bài tập 2 ---
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để sửa bình luận.";
            header("Location: index.php?controller=user&action=login");
            exit();
        }

        if (isset($_GET['id'])) {
            $comment_id = $_GET['id'];
            if ($this->comment->getById($comment_id)) {
                // Kiểm tra xem người dùng hiện tại có phải là người tạo bình luận hay không
                if ($this->comment->user_id != $_SESSION['user_id']) {
                    $_SESSION['error'] = "Bạn không có quyền sửa bình luận này.";
                    header("Location: index.php?controller=product&action=show&id=" . $this->comment->product_id);
                    exit();
                }

                $comment = [
                    'id' => $this->comment->id,
                    'product_id' => $this->comment->product_id,
                    'content' => $this->comment->content
                ];
                include 'views/comments/edit.php';
            } else {
                $_SESSION['error'] = "Bình luận không tồn tại.";
                header("Location: index.php?controller=product&action=index"); // Hoặc trang lỗi
                exit();
            }
        } else {
            $_SESSION['error'] = "Không có ID bình luận để sửa.";
            header("Location: index.php?controller=product&action=index");
            exit();
        }
    }

    public function update() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để cập nhật bình luận.";
            header("Location: index.php?controller=user&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['product_id']) && isset($_POST['content'])) {
            $this->comment->id = $_POST['id'];
            $this->comment->product_id = $_POST['product_id']; // Giữ lại product_id để chuyển hướng
            $this->comment->user_id = $_SESSION['user_id']; // User ID từ session để kiểm tra quyền
            $this->comment->content = $_POST['content'];

            // Kiểm tra quyền lại một lần nữa trước khi cập nhật
            $original_comment = new Comment($this->db);
            if ($original_comment->getById($this->comment->id) && $original_comment->user_id == $_SESSION['user_id']) {
                if ($this->comment->update()) {
                    $_SESSION['message'] = "Bình luận đã được cập nhật thành công!";
                } else {
                    $_SESSION['error'] = "Cập nhật bình luận thất bại.";
                }
            } else {
                $_SESSION['error'] = "Bạn không có quyền cập nhật bình luận này.";
            }
            header("Location: index.php?controller=product&action=show&id=" . $this->comment->product_id);
            exit();
        } else {
            $_SESSION['error'] = "Yêu cầu cập nhật bình luận không hợp lệ.";
            header("Location: index.php?controller=product&action=index");
            exit();
        }
    }
}
?>