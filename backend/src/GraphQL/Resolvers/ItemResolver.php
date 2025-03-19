<?php
namespace App\GraphQL\Resolvers;
use App\Models\Item;

class ItemResolver extends Item
{
    public function getItem($conn)
    {
        return $this->findAll($conn);
    }

    public function getItemByID($conn, $id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Invalid ID');
        }
        return $this->findById($conn, $id);
    }

    public function getItemByValue($conn, $item_value)
    {
        return $this->findByValue($conn,$item_value);
    }
    public function getItemByType($conn,$item_type)
    {
        return $this->findByType($conn,$item_type);
    }
}
