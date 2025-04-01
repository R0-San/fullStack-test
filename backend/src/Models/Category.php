<?php

namespace App\Models;

abstract class Category {
    public $category_id;
    public $category_name;
    public $products = [];

    public function set_category_id($category_id) {
        $this->category_id = $category_id;
    }

    public function set_category_name($category_name) {
        $this->category_name = $category_name;
    }

    public function get_category_id() {
        return $this->category_id;
    }

    public function get_category_name() {
        return $this->category_name;
    }

    public function get_products() {
        return $this->products;
    }

    public static function findAll($conn) {
        $sql = "SELECT * FROM categories";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function findById($conn, $id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    public static function findByName($conn, $category_name) {
        $sql = "SELECT * FROM categories WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
