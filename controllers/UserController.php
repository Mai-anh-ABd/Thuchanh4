<?php
// controllers/UserController.php

require_once 'config/database.php';
require_once 'models/User.php';

session_start();

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->name = $_POST['name'];
            $this->user->email = $_POST['email'];
            $this->user->password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash mật khẩu

            if ($this->user->register()) {
                $_SESSION['message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                header("Location: index.php?controller=user&action=login");
                exit();
            } else {
                $_SESSION['error'] = "Đăng ký thất bại. Email có thể đã tồn tại.";
            }
        }
        include 'views/auth/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password']; // Mật khẩu chưa hash để xác minh

            if ($this->user->login()) {
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_name'] = $this->user->name;
                $_SESSION['message'] = "Đăng nhập thành công!";
                header("Location: index.php?controller=product&action=index");
                exit();
            } else {
                $_SESSION['error'] = "Email hoặc mật khẩu không đúng.";
            }
        }
        include 'views/auth/login.php';
    }

    public function logout() {
        session_unset();
        session_destroy();
        $_SESSION['message'] = "Bạn đã đăng xuất.";
        header("Location: index.php?controller=product&action=index");
        exit();
    }
}
?>