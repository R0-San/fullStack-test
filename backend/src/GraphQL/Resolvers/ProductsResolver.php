<?php namespace App\GraphQL\Resolvers;
use App\Models\Product;

class ProductsResolver extends Product
{
    public function getProduct($conn)
    {
        return $this->findAll($conn);
    }

    public function getProductById($conn, $id)
    {
        return $this->findById($conn, $id);
    }

    public function getProductByProductID($conn, $category_name)
    {
        return $this->findByProductID($conn, $category_name);
    }
}