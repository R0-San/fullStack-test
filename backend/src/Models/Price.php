<?php

namespace App\Models;

abstract class Price {
    public $price_id;
    public $amount;
    public $currency;

    function set_price_id($price_id) {
        $this->price_id = $price_id;
    }

    function set_amount($amount) {
        $this->amount = $amount;
    }

    public function set_currency($currency){
        $this->currency = $currency;
    }

    function get_price_id() {
        return $this->price_id;
    }

    function get_amount() {
        return $this->amount;
    }

    function get_currency() {
        return $this->currency;
    }

    public static function findAll($conn) {
        $sql = "SELECT prices.id AS price_id, prices.amount, currency.id AS currency_id, currency.label, currency.symbol 
            FROM prices 
            JOIN currency ON prices.currency = currency.id";

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

    public static function findById($conn, $id) {
        $sql = "SELECT prices.id AS price_id, prices.amount, currency.id AS currency_id, currency.label, currency.symbol 
            FROM prices 
            JOIN currency ON prices.currency = currency.id
            WHERE prices.id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            return null;
        }

        return [
            'id' => $row['price_id'],
            'amount' => $row['amount'],
            'currency' => [
                'id' => $row['currency_id'],
                'label' => $row['label'],
                'symbol' => $row['symbol']
            ]
        ];
    }

    public static function findByType($conn, $amount) {
        $sql = "SELECT prices.id AS price_id, prices.amount, currency.id AS currency_id, currency.label, currency.symbol 
            FROM prices 
            JOIN currency ON prices.currency = currency.id
            WHERE prices.amount = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $amount);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            return null;
        }

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
