<?php

namespace WorkAnyWare\IPFO\IPRights;

use WorkAnyWare\IPFO\Exceptions\FileAccessException;
use WorkAnyWare\IPFO\IPF;
use ZipArchive;

class IPFFileHandler
{

    private $hashMethod = 'aes256';
    private $dataFileName = 'data.json';
    private $ivFileName = 'iv';
    private $encryptionMethodFileName = 'encMethod';
    private $defaultPassword = 'ipf';

    public function __construct()
    {
        $this->zip = new ZipArchive();
    }

    public function writeTo($filePath, IPF $IPF, $password)
    {
        $this->zip = new ZipArchive();
        if ($this->zip->open($filePath, ZipArchive::CREATE) !== true) {
            throw new FileAccessException("Unable to write IPF archive");
        }
        $this->zip->addFromString($this->dataFileName, $this->encryptDataFile($this->zip, $IPF, $password));
        $this->zip->close();
    }

    public function readFrom($filePath, $password)
    {
        $this->zip = new ZipArchive();
        if ($this->zip->open($filePath) === true) {
            $dataFile = $this->getDataFile($this->zip);
            return $this->decryptDataFile($dataFile, $password);
        }
        throw new FileAccessException("Unable to open IPF archive");
    }

    private function decryptDataFile($dataFileContent, $password)
    {
        if (!$password) {
            $password = $this->defaultPassword;
        }
        $iv = $this->getIV($this->zip);
        $hashMethod = $this->getEncryptionMethod($this->zip);
        $dataFileContent = openssl_decrypt(
            $dataFileContent,
            $hashMethod,
            $password,
            false,
            $iv
        );
        var_dump($hashMethod);
        die;
        if ($json = json_decode($dataFileContent, true) === null) {
            throw new FileAccessException("Unable to decrypt data, potential password error");
        }
        return $dataFileContent;
    }

    private function getIV(ZipArchive $archive)
    {
        return $archive->getFromName($this->ivFileName);
    }

    private function getEncryptionMethod(ZipArchive $archive)
    {
        return $archive->getFromName($this->encryptionMethodFileName);
    }

    private function getDataFile(ZipArchive $archive)
    {
        if ($content = $archive->getFromName($this->dataFileName) === false) {
            throw new FileAccessException("Unable to open data file");
        }
        return $content;
    }

    private function encryptDataFile(ZipArchive $archive, IPF $IPF, $password)
    {
        $dataString = json_encode($IPF->toArray());
        if (!$password) {
            $password = $this->defaultPassword;
        }
        $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
        $initVector = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
        $dataString = openssl_encrypt($dataString, $this->hashMethod, $password, false, $initVector);
        $archive->addFromString($this->ivFileName, $initVector);
        $archive->addFromString($this->encryptionMethodFileName, $this->hashMethod);
        return $dataString;
    }
}
