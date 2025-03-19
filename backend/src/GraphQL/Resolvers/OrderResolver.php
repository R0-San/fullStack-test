<?php
namespace App\GraphQL\Resolvers;
use App\Models\PlaceOrder;

class OrderResolver extends PlaceOrder
{
    public function createOrder1($items)
    {
        return $this->createOrder($items);
    }
}