<?php

namespace App\GraphQL\Resolvers;
use App\Models\Attribute;


class AttributeResolver extends Attribute
{
    public function getAttributes($conn)
    {
        return $this->findAll($conn);
    }

    public function getAttributesByID($conn, $id)
    {
        return $this->findById($conn,$id);
    }

    public function getAttributesByType($conn, $attribute_type)
    {
        return $this->findByType($conn, $attribute_type);
    }

}
