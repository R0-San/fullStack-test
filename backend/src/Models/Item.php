<?php

namespace App\Models;

abstract class Item {
    public $item_id;
    public $display_value;
    public $value;
    public $item_type;

    function set_item_id($item_id) {
        $this->item_id = $item_id;
    }

    function set_display_value($display_value) {
        $this->display_value = $display_value;
    }

    function set_value($value) {
        $this->value = $value;
    }

    function set_item_type($item_type) {
        $this->item_type = $item_type;
    }

    function get_item_id() {
        return $this->item_id;
    }

    function get_display_value() {
        return $this->display_value;
    }

    function get_value() {
        return $this->value;
    }

    function get_item_type() {
        return $this->item_type;
    }

    public static function findAll($conn) {
        $sql = "SELECT * FROM items";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function findById($conn, $id) {
        $sql = "SELECT * FROM items WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    public static function findByValue($conn, $value) {
        $sql = "SELECT * FROM items WHERE value = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function findByType($conn, $item_type) {
        $sql = "SELECT * FROM items WHERE item_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $item_type);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}

?>
