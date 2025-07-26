<?php
// controllers/ProductController.php

require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'models/Comment.php'; // Cần để hiển thị bình luận

class ProductController {
    private $db;
    private $product;
    private $comment;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
        $this->comment = new Comment($this->db);
    }

    public function index() {
        $stmt = $this->product->getAll();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'views/products/index.php';
    }

    public function show() {
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            if ($this->product->getById($product_id)) {
                $product = [
                    'id' => $this->product->id,
                    'user_id' => $this->product->user_id,
                    'title' => $this->product->title,
                    'description' => $this->product->description,
                    'image' => $this->product->image,
                    'created_at' => $this->product->created_at,
                    'user_name' => $this->product->user_name // Tên người đăng sản phẩm
                ];
                $comments_stmt = $this->comment->getByProductId($product_id);
                $comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);

                include 'views/products/show.php';
            } else {
                $_SESSION['error'] = "Sản phẩm không tồn tại.";
                header("Location: index.php?controller=product&action=index");
                exit();
            }
        } else {
            $_SESSION['error'] = "Không có ID sản phẩm.";
            header("Location: index.php?controller=product&action=index");
            exit();
        }
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để đăng sản phẩm.";
            header("Location: index.php?controller=user&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->product->user_id = $_SESSION['user_id'];
            $this->product->title = $_POST['title'];
            $this->product->description = $_POST['description'];
            $this->product->image = $_POST['image']; // Trong thực tế sẽ xử lý upload file

            if ($this->product->create()) {
                $_SESSION['message'] = "Sản phẩm đã được đăng thành công!";
                header("Location: index.php?controller=product&action=index");
                exit();
            } else {
                $_SESSION['error'] = "Đăng sản phẩm thất bại.";
            }
        }
        include 'views/products/form.php'; // Dùng chung form cho cả tạo và sửa
    }

    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để sửa sản phẩm.";
            header("Location: index.php?controller=user&action=login");
            exit();
        }

        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            if ($this->product->getById($product_id)) {
                // Kiểm tra xem người dùng hiện tại có phải là người tạo sản phẩm hay không
                if ($this->product->user_id != $_SESSION['user_id']) {
                    $_SESSION['error'] = "Bạn không có quyền sửa sản phẩm này.";
                    header("Location: index.php?controller=product&action=index");
                    exit();
                }

                $product = [
                    'id' => $this->product->id,
                    'title' => $this->product->title,
                    'description' => $this->product->description,
                    'image' => $this->product->image
                ];
                include 'views/products/form.php'; // Hiển thị form với dữ liệu cũ
            } else {
                $_SESSION['error'] = "Sản phẩm không tồn tại.";
                header("Location: index.php?controller=product&action=index");
                exit();
            }
        } else {
            $_SESSION['error'] = "Không có ID sản phẩm để sửa.";
            header("Location: index.php?controller=product&action=index");
            exit();
        }
    }

    public function update() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để cập nhật sản phẩm.";
            header("Location: index.php?controller=user&action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $this->product->id = $_POST['id'];
            $this->product->user_id = $_SESSION['user_id']; // Lấy user_id từ session để kiểm tra quyền
            $this->product->title = $_POST['title'];
            $this->product->description = $_POST['description'];
            $this->product->image = $_POST['image'];

            // Kiểm tra quyền lại một lần nữa trước khi cập nhật
            // Đây là một lớp bảo mật quan trọng
            $original_product = new Product($this->db);
            if ($original_product->getById($this->product->id) && $original_product->user_id == $_SESSION['user_id']) {
                if ($this->product->update()) {
                    $_SESSION['message'] = "Sản phẩm đã được cập nhật thành công!";
                    header("Location: index.php?controller=product&action=show&id=" . $this->product->id);
                    exit();
                } else {
                    $_SESSION['error'] = "Cập nhật sản phẩm thất bại.";
                }
            } else {
                $_SESSION['error'] = "Bạn không có quyền cập nhật sản phẩm này.";
            }
        } else {
            $_SESSION['error'] = "Yêu cầu không hợp lệ để cập nhật sản phẩm.";
        }
        // Nếu có lỗi, quay lại trang sửa
        if (isset($_POST['id'])) {
             header("Location: index.php?controller=product&action=edit&id=" . $_POST['id']);
        } else {
             header("Location: index.php?controller=product&action=index");
        }
        exit();
    }
}
?>