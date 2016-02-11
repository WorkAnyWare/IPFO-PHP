<?php

namespace WorkAnyWare\IPFO\IPRights;

class FileCollection
{
    /** @var File[] */
    private $images;

    public function addFromString(File $image)
    {
        $this->images = $image;
    }

    public function toArray()
    {
        $return = [];
        foreach ($this->images as $image) {
            $return[] = $image->toArray();
        }
        return $return;
    }

    public function getAll()
    {
        return $this->images;
    }
}