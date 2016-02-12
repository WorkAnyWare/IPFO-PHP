<?php

namespace WorkAnyWare\IPFO;

use WorkAnyWare\IPFO\IPRights\IPFFileHandler;

class IPF
{
    /** @var  IPRight[] */
    private $rights = [];

    public function save($filePath, $passWord = null)
    {
        $handler = new IPFFileHandler();
        if (substr($filePath, -4) !== '.ipf') {
            $filePath .= '.ipf';
        }
        $handler->writeTo($filePath, $this->rights, $passWord);
    }

    public function add(IPRight $IPRight)
    {
        $this->rights[] = $IPRight;
    }

    public function getRights()
    {
        return $this->rights;
    }
}
