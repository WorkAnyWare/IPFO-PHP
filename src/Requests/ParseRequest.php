<?php

namespace WorkAnyWare\IPFO\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\Request as GuzzleRequest;
use GuzzleHttp\Message\Response;

class ParseRequest extends Request
{
    /**
     * An array of the documents to send with the request
     * @var array
     */
    private $documents;
    /**
     * The end point string for the API
     * @var string
     */
    private $endPoint;
    /**
     * The API Username for IPFO
     * @var string
     */
    private $userName;
    /**
     * The Current API key in use
     * @var string
     */
    private $APIKey;
    /** @var Client */
    private $client;

    public function __construct(array $documents, $endPoint, $userName, $APIKey)
    {
        $this->documents = $documents;
        $this->endPoint  = $endPoint;
        $this->userName  = $userName;
        $this->APIKey    = $APIKey;
        $this->client = new Client();
    }

    public function getIPRights()
    {
        $response = $this->assembleRequest();
        var_dump($response->__toString());
    }

    /**
     * Assembles the http request for use with parsing documents
     * @return Response
     */
    private function assembleRequest()
    {
        return $this->client->post(
            $this->endPoint . '/parse',
            [
                'body' => $this->assembleFilesIntoMultipart()
            ]
        );
    }

    /**
     * Assembles Documents attached into a multipart array suitable for Guzzle
     * @return array
     */
    private function assembleFilesIntoMultipart()
    {
        $contents = [];
        foreach ($this->documents as $document) {
            $contents[$document] = file_get_contents($document);
        }
        return $contents;
    }
}