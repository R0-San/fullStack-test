<?php

namespace App\Models;

abstract class Gallery {
    public $gallery_id;
    public $url;
    public $object;

    public function setGalleryId($gallery_id) {
        $this->gallery_id = $gallery_id;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setObject($object) {
        $this->object = $object;
    }

    public function getGalleryId() {
        return $this->gallery_id;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getObject() {
        return $this->object;
    }

    public static function findAll($conn) {
        $sql = "SELECT * FROM gallery";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function findById($conn, $id) {
        $sql = "SELECT * FROM gallery WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    public static function findByObject($conn, $object) {
        $sql = "SELECT * FROM gallery WHERE to_what = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $object);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

}


?>