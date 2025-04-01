<?php

namespace App\Models;

class PlaceOrder
{

    public function createOrder($items,$conn)
    {
        $totalAmount = 0;

        foreach ($items as $item) {
            $productId = $item['productId'];
            $quantity = $item['quantity'];
            $price = $this->getProductPriceById($productId,$conn);
            $totalAmount += $price * $quantity;
        }

        $orderId = $this->insertOrder($totalAmount,$conn);

        foreach ($items as $item) {
            $orderItemId = $this->insertOrderItem($orderId, $item['productId'], $item['quantity'],$conn);
            foreach ($item['selectedAttributes'] as $attribute) {
                $this->insertOrderItemAttribute($orderItemId, $attribute['attributeId'], $attribute['value'],$conn);
            }
        }

        return [
            'id' => $orderId,
            'totalAmount' => $totalAmount,
            'status' => 'Pending',
            'createdAt' => date('Y-m-d H:i:s'),
            'items' => array_map(function ($item) {
                return [
                    'productId' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'selectedAttributes' => array_map(function ($attribute) {
                        return [
                            'attributeId' => $attribute['attributeId'],
                            'value' => $attribute['value'],
                        ];
                    }, $item['selectedAttributes']),
                ];
            }, $items),
        ];
    }

    private function getProductPriceById($productId,$conn)
    {
        $stmt = $conn->prepare("SELECT amount FROM prices WHERE id = (SELECT prices FROM products WHERE id = ?)");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? (float) $row['amount'] : 0;
    }

    private function insertOrder($totalAmount, $conn)
    {
        $stmt = $conn->prepare("INSERT INTO orders (total_amount, status, created_at) VALUES (?, 'Pending', NOW())");
        $stmt->bind_param("d", $totalAmount);
        if (!$stmt->execute()) {
            throw new \Exception("Failed to insert order: " . $stmt->error);
        }
        return $conn->insert_id;
    }

    private function insertOrderItem($orderId, $productId, $quantity, $conn)
    {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $orderId, $productId, $quantity);
        $stmt->execute();
        return $conn->insert_id;
    }

    private function insertOrderItemAttribute($orderItemId, $attributeId, $value, $conn)
    {
        $stmt = $conn->prepare("INSERT INTO order_item_attributes (order_item_id, attribute_id, value) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $orderItemId, $attributeId, $value);
        $stmt->execute();
    }
}