<?php

namespace WorkAnyWare\IPFO;

use WorkAnyWare\IPFO\Requests\ParseRequest;

/**
 * Establishes a connection to the IPFO API, so that responses can be returned
 * Class Connection
 * @package WorkAnyWare\IPFO
 */
class Connection
{
//    private $endPoint = 'https://ipfo.workanyware.co.uk';
    private $endPoint = 'http://localhost:8081';
    private $authentication;

    /**
     * Connection constructor, takes a username and API Key.
     *
     * Keys can be generated from the Control Panel in IPFO.
     *
     * @param string $userName
     * @param string $APIKey
     */
    public function __construct($userName, $APIKey)
    {
        $this->authentication = new Authentication($userName, $APIKey);
    }

    /**
     * Attempts to parse the given documents into IPRight objects.
     * Takes a single string file name or array of file names
     *
     * @param string|array $files - string or array of file names of documents to convert
     *
     * @return bool|\SNicholson\IPFO\IPRight[]
     */
    public function parseDocuments($files)
    {
        if (is_string($files)) {
            $files = [$files];
        }
        $parseRequest = new ParseRequest($files, $this->endPoint, $this->authentication);
        return $parseRequest->getIPRights();
    }


}