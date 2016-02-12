<?php

namespace WorkAnyWare\IPFO\IPRights;

use WorkAnyWare\IPFO\Exceptions\FileAccessException;
use WorkAnyWare\IPFO\IPF;
use ZipArchive;

/**
 * Class IPFFileHandler
 * @package WorkAnyWare\IPFO\IPRights
 */
class IPFFileHandler
{

    /**
     * @var string
     */
    private $defaultEncMethod = 'AES-128-CBC';
    /**
     * @var string
     */
    private $dataFileName = 'data.json';
    /**
     * @var string
     */
    private $ivFileName = 'iv';
    /**
     * @var string
     */
    private $encryptionMethodFileName = 'encMethod';
    /**
     * @var string
     */
    private $defaultPassword = 'ipf';

    private $filesFolderName = 'files/';

    private $currentInitVector = '';

    /**
     * IPFFileHandler constructor.
     */
    public function __construct()
    {
        $this->zip = new ZipArchive();
    }

    /**
     * @param     $filePath
     * @param IPF $IPF
     * @param     $password
     *
     * @throws FileAccessException
     */
    public function writeTo($filePath, IPF $IPF, $password)
    {
        $this->zip = new ZipArchive();
        if ($this->zip->open($filePath, ZipArchive::CREATE) !== true) {
            throw new FileAccessException("Unable to write IPF archive");
        }
        $this->zip->addFromString($this->dataFileName, $this->encryptDataFile($this->zip, $IPF, $password));
        $this->addDocumentsToArchive($IPF, $password);
        $this->zip->close();
    }

    /**
     * @param $filePath
     * @param $password
     *
     * @return string
     * @throws FileAccessException
     */
    public function readFrom($filePath, $password)
    {
        $this->zip = new ZipArchive();
        if ($this->zip->open($filePath) === true) {
            $dataFile = $this->getDataFile($this->zip);
            return $this->decryptDataFile($dataFile, $password);
        }
        throw new FileAccessException("Unable to open IPF archive");
    }



    public function appendDocumentsToIPFObject(IPF &$IPF, $filePath, $password)
    {
        $this->zip = new ZipArchive();
        if ($this->zip->open($filePath) !== true) {
            throw new FileAccessException("Unable to open IPF archive");
        }
        $this->getDocumentsFromArchive($IPF, $password);
    }

    /**
     * @param $dataFileContent
     * @param $password
     *
     * @return string
     * @throws FileAccessException
     */
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
            $this->hashPassword($password),
            OPENSSL_RAW_DATA,
            $iv
        );
        if ($json = json_decode($dataFileContent, true) === null) {
            throw new FileAccessException("Unable to decrypt data, potential password error");
        }
        return $dataFileContent;
    }

    /**
     * @param ZipArchive $archive
     *
     * @return string
     */
    private function getIV(ZipArchive $archive)
    {
        return $archive->getFromName($this->ivFileName);
    }

    /**
     * @param ZipArchive $archive
     *
     * @return string
     */
    private function getEncryptionMethod(ZipArchive $archive)
    {
        return $archive->getFromName($this->encryptionMethodFileName);
    }

    /**
     * @param ZipArchive $archive
     *
     * @return string
     * @throws FileAccessException
     */
    private function getDataFile(ZipArchive $archive)
    {
        $content = $archive->getFromName($this->dataFileName);
        if ($content === false) {
            throw new FileAccessException("Unable to open data file");
        }
        return $content;
    }

    /**
     * @param ZipArchive $archive
     * @param IPF        $IPF
     * @param            $password
     *
     * @return string
     */
    private function encryptDataFile(ZipArchive $archive, IPF $IPF, $password)
    {
        $dataString = json_encode($IPF->toArray());
        if (!$password) {
            $password = $this->defaultPassword;
        }
        $size                    = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
        $initVector              = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
        $this->currentInitVector = $initVector;
        $dataString              = openssl_encrypt($dataString, $this->defaultEncMethod, $this->hashPassword($password), OPENSSL_RAW_DATA, $initVector);
        $archive->addFromString($this->ivFileName, $initVector);
        $archive->addFromString($this->encryptionMethodFileName, $this->defaultEncMethod);
        return $dataString;
    }

    /**
     * @param $password
     *
     * @return string
     */
    private function hashPassword($password)
    {
        return hash_hmac('sha256', $password, 'ipf');
    }

    private function getDocumentsFromArchive(IPF &$IPF, $password)
    {
        foreach ($IPF->getDocuments()->getAll() as $docNumber => &$document) {
            $docContent = $this->zip->getFromName($this->filesFolderName . $docNumber);
            if (!$docContent) {
                throw new FileAccessException("Unable to read file " . $document->getFileName() . " from ipf");
            }
            $docContent = openssl_decrypt(
                $docContent,
                $this->getEncryptionMethod($this->zip),
                $this->hashPassword($password),
                OPENSSL_RAW_DATA,
                $this->getIV($this->zip)
            );
            $docContent = gzuncompress($docContent);
            $document->setContentFromString($docContent);
        }
    }

    private function addDocumentsToArchive(IPF $IPF, $password)
    {
        foreach ($IPF->getDocuments()->getAll() as $key => $file) {
            $fileContents = gzcompress($file->getContent(), 1);
            $fileContents = openssl_encrypt(
                $fileContents,
                $this->defaultEncMethod,
                $this->hashPassword($password),
                OPENSSL_RAW_DATA,
                $this->currentInitVector
            );
            $this->zip->addFromString($this->filesFolderName . $key,$fileContents);
        }
    }
}
