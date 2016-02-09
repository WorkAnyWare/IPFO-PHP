<?php

namespace WorkAnyWare\IPFO;

use WorkAnyWare\IPFO\IPRights\RightType;
use WorkAnyWare\IPFO\IPRights\SearchSource;
use WorkAnyWare\IPFO\IPRights\RightNumberType;
use WorkAnyWare\IPFO\Requests\ParseRequest;
use WorkAnyWare\IPFO\Requests\SearchRequest;

/**
 * Establishes a connection to the IPFO API, so that responses can be returned
 * Class Connection
 * @package WorkAnyWare\IPFO
 */
class Connection
{
    private $endPoint;
    private $authentication;

    /**
     * Connection constructor, takes a username and API Key.
     *
     * Keys can be generated from the Control Panel in IPFO.
     *
     * @param string $userName
     * @param string $APIKey
     * @param string $endPoint
     */
    public function __construct($userName, $APIKey, $endPoint = 'https://ipfo.workanyware.co.uk/api/v1')
    {
        $this->authentication = new Authentication($userName, $APIKey);
        $this->endPoint = $endPoint;
    }

    /**
     * Attempts to parse the given documents into IPRight objects.
     * Takes a single string file name or array of file names
     *
     * @param string|array $files - string or array of file names of documents to convert
     *
     * @throws \InvalidArgumentException When an invalid list of files is provided
     *
     * @return bool|\WorkAnyWare\IPFO\IPRight[]
     */
    public function parseDocuments($files)
    {
        if (is_string($files)) {
            $files = [$files];
        }
        if (is_array($files)) {
            $parseRequest = new ParseRequest($files, $this->endPoint, $this->authentication);
            return $parseRequest->getIPRights();
        }
        throw new \InvalidArgumentException("Invalid file list received");
    }

    /**
     * Searches offices for the given Right and returns an IPRight on success
     *
*@param RightType       $rightType
     * @param RightNumberType $numberType
     * @param                 $number
     *
     * @return bool|\WorkAnyWare\IPFO\IPRight
     */
    public function search(RightType $rightType, RightNumberType $numberType, $number)
    {
        $searchRequest = new SearchRequest($this->endPoint, $this->authentication);
        return $searchRequest->search($numberType, $number, $rightType);
    }

    /**
     * Searches a specific office for the given right identified by the number type and number
     *
     * @param SearchSource    $searchSource
     * @param RightNumberType $numberType
     * @param                 $number
     *
     * @return bool|\WorkAnyWare\IPFO\IPRight
     */
    public function searchAtOffice(
        SearchSource $searchSource,
        RightNumberType $numberType,
        $number
    ) {
        $searchRequest = new SearchRequest($this->endPoint, $this->authentication);
        return $searchRequest->search($numberType, $number, null, $searchSource);
    }


}