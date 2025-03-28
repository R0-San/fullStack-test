<?php
namespace App\GraphQL\Resolvers;
use App\Models\PlaceOrder;

class OrderResolver extends PlaceOrder
{
    public function newOrder($items)
    {
        return $this->createOrder($items);
    }
}