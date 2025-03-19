<?php

namespace App\GraphQL\Resolvers;

use App\Models\Category;

class CategoryResolver extends Category
{
    public function getCategories($conn)
    {
        return $this->findAll($conn);
    }

    public function getCategoryById($conn, $id)
    {
        return $this->findById($conn, $id);
    }

    public function getCategoryByName($conn, $category_name)
    {
        return $this->findByName($conn, $category_name);
    }
}