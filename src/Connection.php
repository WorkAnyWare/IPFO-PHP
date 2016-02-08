<?php

namespace WorkAnyWare\IPFO;

/**
 * Establishes a connection to the IPFO API, so that responses can be returned
 * Class Connection
 * @package WorkAnyWare\IPFO
 */
class Connection
{
    private $userName;
    private $APIKey;

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
        $this->userName = $userName;
        $this->APIKey = $APIKey;
    }


}