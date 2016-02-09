<?php

namespace WorkAnyWare\IPFO\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use WorkAnyWare\IPFO\Authentication;
use WorkAnyWare\IPFO\IPRightFactory;
use WorkAnyWare\IPFO\IPRights\RightNumberType;
use WorkAnyWare\IPFO\IPRights\RightType;
use WorkAnyWare\IPFO\IPRights\SearchSource;

class SearchRequest
{
    private $endPoint;
    /**
     * @var Authentication
     */
    private $authentication;
    private $client;

    public function __construct($endPoint, Authentication $authentication)
    {
        $this->endPoint       = $endPoint;
        $this->authentication = $authentication;
        $this->client = new Client();
    }

    /**
     * Runs a search and returns the output
     * @param RightType         $rightType
     * @param RightNumberType   $numberType
     * @param                   $number
     * @param SearchSource|null $searchSource
     *
     * @return bool|\WorkAnyWare\IPFO\IPRight
     */
    public function search(
        RightNumberType $numberType,
        $number,
        RightType $rightType = null,
        SearchSource $searchSource = null
    ) {
        try {
            $uri = $this->getSearchURI($numberType, $number, $rightType, $searchSource);
            $response = $this->assembleRequest($uri)->json();
            if ($response['success']) {
                return IPRightFactory::fromArray($response['result']);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            //Fail Gracefully
        }
        return false;
    }

    /**
     * Assembles the http request for use with parsing documents
     * @return Response
     */
    private function assembleRequest($uri)
    {
        return $this->client->get(
            $uri,
            [
                'headers' => array_merge($this->authentication->toHeaders(), ['Content-Type' => 'multipart/form-data']),
            ]
        );
    }

    /**
     * @param RightType       $rightType
     * @param RightNumberType $numberType
     * @param                 $number
     * @param SearchSource    $searchSource
     *
     * @return string
     */
    private function getSearchURI(
        RightNumberType $numberType,
        $number,
        RightType $rightType = null,
        SearchSource $searchSource = null
    ) {
        if ($searchSource instanceof SearchSource) {
            return $this->endPoint . '/search/' . $searchSource . '/' . $numberType . '/' . $number;
        }
        return $this->endPoint . '/search/' . $rightType . '/' . $numberType . '/' . $number;
    }

}
