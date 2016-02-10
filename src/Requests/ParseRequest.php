<?php

namespace WorkAnyWare\IPFO\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\Request as GuzzleRequest;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Post\PostFile;
use WorkAnyWare\IPFO\IPRight;
use WorkAnyWare\IPFO\Authentication;
use WorkAnyWare\IPFO\IPRightFactory;

class ParseRequest
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
    /** @var Client */
    private $client;
    /**
     * The current authentication object
     * @var Authentication
     */
    private $authentication;

    public function __construct(array $documents, $endPoint, Authentication $authentication)
    {
        $this->documents = $documents;
        $this->endPoint  = $endPoint;
        $this->client = new Client();
        $this->authentication = $authentication;
    }

    /**
     * Returns the IP Rights from the parse request
     * @return IPRight[]|bool
     */
    public function getIPRights()
    {
        try {
            $response = $this->assembleRequest()->json();
            if ($response['success']) {
                $IPRights = [];
                foreach ($response['result'] as $result) {
                    $IPRights[] = IPRightFactory::fromArray($result);
                }
                return $IPRights;
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
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
                'body'    => $this->assembleFilesIntoMultipart(),
                'headers' => ['Content-Type' => 'multipart/form-data'],
                'auth'    => $this->authentication->toAuthHeaders()
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
            $contents[$document] = new PostFile($document, file_get_contents($document));
        }
        return $contents;
    }
}