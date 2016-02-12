<?php

namespace WorkAnyWare\IPFO\IPRights;


class Document
{
    private $content;
    private $description;
    private $fileName;

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContentFromString($content)
    {
        $this->content = $content;
    }

    /**
     * @param        $filePath
     * @param string $description
     */
    public function fromFile($filePath, $description = '')
    {
        $this->content = file_get_contents($filePath);
        $this->setDescription($description);
        $this->fileName = basename($filePath);
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function toArray($includeDocumentContent)
    {
        if ($includeDocumentContent) {
            return [
                'content'     => $this->getContent(),
                'description' => $this->getDescription(),
                'filename'    => $this->getFileName(),
            ];
        }
        return [
            'description' => $this->getDescription(),
            'filename'    => $this->getFileName(),
        ];
    }
}