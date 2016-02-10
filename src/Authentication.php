<?php

namespace WorkAnyWare\IPFO;

class Authentication
{
    private $username;
    private $APIKey;

    /**
     * Authentication constructor.
     *
     * @param string $username - Your IPFO Username
     * @param string $APIKey   - A valid IPFO APIKey for your account
     */
    public function __construct($username, $APIKey)
    {
        $this->username = $username;
        $this->APIKey   = $APIKey;
    }

    /**
     * Returns the standard format headers for a IPFO Request
     *
     * @return array
     */
    public function toAuthHeaders()
    {
        return  [$this->username, $this->APIKey];
    }
}
