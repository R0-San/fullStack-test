<?php
namespace App\GraphQL\Resolvers;
use App\Models\Price;

class PriceResolver extends Price
{
    public function getPrice($conn)
    {
        return $this->findAll($conn);
    }

    public function getPriceByID($conn, $id)
    {
        return $this->findById($conn,$id);
    }

    public function getPriceByAmount($conn, $amount)
    {
        return $this->findByType($conn, $amount);
    }

}
