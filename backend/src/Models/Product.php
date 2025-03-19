<?php

namespace App\Models;

class Product {
    private int $id;
    private string $product_id;
    private string $name;
    private bool $inStock;
    private array $gallery = [];
    private string $description;
    private array $categories = [];
    private array $attributes = [];
    private array $prices = [];
    private string $brand;

    public function getId(): int {
        return $this->id;
    }

    public function getProductId(): string {
        return $this->product_id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function isInStock(): bool {
        return $this->inStock;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getGallery(): array {
        return $this->gallery;
    }

    public function getCategories(): array {
        return $this->categories;
    }

    public function getAttributes(): array {
        return $this->attributes;
    }

    public function getPrices(): array {
        return $this->prices;
    }

    public function getBrand(): string {
        return $this->brand;
    }

    public function setGallery(array $gallery): void {
        $this->gallery = $gallery;
    }

    public function setCategories(array $categories): void {
        $this->categories = $categories;
    }

    public function setAttributes(array $attributes): void {
        $this->attributes = $attributes;
    }

    public function setPrices(array $prices): void {
        $this->prices = $prices;
    }
    public static function findAll($conn) {
        $sql = "SELECT * FROM products";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        foreach ($products as &$product) {

            $product['gallery'] = self::getGalleryForProduct($product['gallery']) ?: [];
            $product['category'] = self::getCategoryForProduct($product['category']) ?: [];

            $product['attributes'] = array_merge(
                self::getAttributesForProduct($product['attributes_1']) ?: [],
                self::getAttributesForProduct($product['attributes_2']) ?: [],
                self::getAttributesForProduct($product['attributes_3']) ?: []
            );

            if (!empty($product['prices'])) {
                $product['prices'] = self::getPricesForProduct($product['prices']);
            } else {
                $product['prices'] = [];
            }
        }

        return $products;
    }

    public static function findById($conn, $id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        foreach ($products as &$product) {

            $product['gallery'] = self::getGalleryForProduct($product['gallery']) ?: [];
            $product['category'] = self::getCategoryForProduct($product['category']) ?: [];

            $product['attributes'] = array_merge(
                self::getAttributesForProduct($product['attributes_1']) ?: [],
                self::getAttributesForProduct($product['attributes_2']) ?: [],
                self::getAttributesForProduct($product['attributes_3']) ?: []
            );

            if (!empty($product['prices'])) {
                $product['prices'] = self::getPricesForProduct($product['prices']);
            } else {
                $product['prices'] = [];
            }
        }

        return $product;
    }

    public static function findByProductID($conn, $product_id) {
        $sql = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        foreach ($products as &$product) {

            $product['gallery'] = self::getGalleryForProduct($product['gallery']) ?: [];
            $product['category'] = self::getCategoryForProduct($product['category']) ?: [];

            $product['attributes'] = array_merge(
                self::getAttributesForProduct($product['attributes_1']) ?: [],
                self::getAttributesForProduct($product['attributes_2']) ?: [],
                self::getAttributesForProduct($product['attributes_3']) ?: []
            );

            if (!empty($product['prices'])) {
                $product['prices'] = self::getPricesForProduct($product['prices']);
            } else {
                $product['prices'] = [];
            }
        }

        return $product;
    }

    public static function getGalleryForProduct($product_id)
    {
        global $conn;

        $sql = "SELECT * FROM gallery WHERE to_what = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getCategoryForProduct($product_category)
    {
        global $conn;

        $sql = "SELECT * FROM categories WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $product_category);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getAttributesForProduct($product_attribute)
    {
        global $conn;

        $sql = "SELECT * FROM attributes WHERE item_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $product_attribute);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getPricesForProduct($productPriceId)
    {
        global $conn;

        $ids = is_array($productPriceId) ? implode(',', array_map('intval', $productPriceId)) : (int) $productPriceId;

        $sql = "SELECT prices.id AS price_id, prices.amount, currency.id AS currency_id, currency.label, currency.symbol 
            FROM prices 
            JOIN currency ON prices.currency = currency.id
            WHERE prices.id IN ($ids)";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $prices = [];
        while ($row = $result->fetch_assoc()) {
            $prices[] = [
                'id' => $row['price_id'],
                'amount' => $row['amount'],
                'currency' => [
                    'id' => $row['currency_id'],
                    'label' => $row['label'],
                    'symbol' => $row['symbol']
                ]
            ];
        }

        return $prices;
    }
}
?>
