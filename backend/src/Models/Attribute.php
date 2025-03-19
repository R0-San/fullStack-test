<?php

namespace App\Models;
abstract class Attribute {
    public $attribute_id;
    public $attribute_name;
    public $attribute_type;
    public $attribute_itemtype;
    public $attribute_items = []; 

    function set_attribute_id($attribute_id) {
        $this->attribute_id = $attribute_id;
    }

    function set_attribute_name($attribute_name) {
        $this->attribute_name = $attribute_name;
    }

    function set_attribute_type($attribute_type) {
        $this->attribute_type = $attribute_type;
    }

    function set_attribute_itemtype($attribute_itemtype) {
        $this->attribute_itemtype = $attribute_itemtype;
    }

    function add_item(Item $item) {
        $this->attribute_items[] = $item;
    }

    function get_attribute_id() {
        return $this->attribute_id;
    }

    function get_attribute_name() {
        return $this->attribute_name;
    }

    function get_attribute_type() {
        return $this->attribute_type;
    }

    function get_attribute_items() {
        return $this->attribute_items;
    }

    public static function findAll($conn) {
        $sql = "SELECT * FROM attributes";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function findById($conn, $id) {
        $sql = "SELECT * FROM attributes WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    public static function findByType($conn, $attribute_type) {
        $sql = "SELECT * FROM attributes WHERE attributes.type = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $attribute_type);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}


?>