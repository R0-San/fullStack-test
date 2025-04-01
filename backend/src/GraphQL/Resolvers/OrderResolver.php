<?php
namespace App\GraphQL\Resolvers;
use App\Models\PlaceOrder;

class OrderResolver extends PlaceOrder
{
    public function newOrder($items, $conn)
    {
        return $this->createOrder($items, $conn);
    }
}