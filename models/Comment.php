<?php
// models/Comment.php

class Comment {
    private $conn;
    private $table_name = "comments";

    public $id;
    public $user_id;
    public $product_id;
    public $content;
    public $created_at;
    public $updated_at; // Thêm cho Bài tập 2

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getByProductId($product_id) {
        $query = "SELECT c.id, c.user_id, c.content, c.created_at, c.updated_at, u.name as user_name
                  FROM " . $this->table_name . " c
                  LEFT JOIN users u ON c.user_id = u.id
                  WHERE c.product_id = ?
                  ORDER BY c.created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $product_id);
        $stmt->execute();
        return $stmt;
    }

    public function add() {
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id, product_id=:product_id, content=:content";
        $stmt = $this->conn->prepare($query);

        $this->content = htmlspecialchars(strip_tags($this->content));

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":content", $this->content);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // --- Bổ sung cho Bài tập 2 ---
    public function getById($id) {
        $query = "SELECT id, user_id, product_id, content FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->product_id = $row['product_id'];
            $this->content = $row['content'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET content=:content, updated_at=NOW() WHERE id=:id AND user_id=:user_id";
        $stmt = $this->conn->prepare($query);

        $this->content = htmlspecialchars(strip_tags($this->content));

        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":user_id", $this->user_id); // Đảm bảo chỉ người tạo mới sửa được

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>