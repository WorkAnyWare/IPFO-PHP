<?php

namespace WorkAnyWare\IPFO\Requests;

abstract class Request
{
    /**
     * Returns the standard format headers for a IPFO Request
     * @param $userName
     * @param $APIKey
     *
     * @return array
     */
    protected function getAuthenticationHeaders($userName, $APIKey)
    {
        return ['ipfo_user' => $userName, 'ipfo_key' => $APIKey];
    }
}