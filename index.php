<?php
// index.php

require_once 'config/database.php';
require_once 'controllers/UserController.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/CommentController.php'; // Cần để định tuyến cho CommentController

// Bắt đầu session (nếu chưa bắt đầu)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$controller_name = isset($_GET['controller']) ? $_GET['controller'] : 'product';
$action_name = isset($_GET['action']) ? $_GET['action'] : 'index';

// Định tuyến
switch ($controller_name) {
    case 'user':
        $controller = new UserController();
        if (method_exists($controller, $action_name)) {
            $controller->$action_name();
        } else {
            // Xử lý action không tồn tại
            echo "Lỗi: Hành động không hợp lệ.";
        }
        break;
    case 'product':
        $controller = new ProductController();
        if (method_exists($controller, $action_name)) {
            $controller->$action_name();
        } else {
            echo "Lỗi: Hành động không hợp lệ.";
        }
        break;
    case 'comment': // Định tuyến cho CommentController
        $controller = new CommentController();
        if (method_exists($controller, $action_name)) {
            $controller->$action_name();
        } else {
            echo "Lỗi: Hành động không hợp lệ.";
        }
        break;
    default:
        // Mặc định hiển thị danh sách sản phẩm
        $controller = new ProductController();
        $controller->index();
        break;
}
?>