<?php

namespace WorkAnyWare\IPFO\IPRights;

class DocumentCollection
{
    /** @var Document[] */
    private $images = [];

    public function add(Document $image)
    {
        $this->images[] = $image;
    }

    public function toArray($includeDocumentContent)
    {
        $return = [];
        foreach ($this->images as $image) {
            $return[] = $image->toArray($includeDocumentContent);
        }
        return $return;
    }

    public function getAll()
    {
        return $this->images;
    }
}