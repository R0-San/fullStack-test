<?php
namespace App\GraphQL\Resolvers;
use App\Models\Gallery;

class GalleryResolver extends Gallery
{
    public function getGallery($conn)
    {
        return $this->findAll($conn);
    }

    public function getGallleryByID($conn,$id)
    {
        return $this->findById($conn,$id);
    }

    public function getGalleryByObject($conn,$gallery_object)
    {
        return $this->findByObject($conn,$gallery_object);
    }

}
