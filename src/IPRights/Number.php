<?php

namespace WorkAnyWare\IPFO\IPRights;

class Number
{
    const APPLICATION = 'application';
    const PUBLICATION = 'publication';

    private $rightType;

    /**
     * Number constructor.
     *
     * @param $rightNumberType
     */
    private function __construct($rightNumberType)
    {
        if ($rightNumberType !== Number::APPLICATION && $rightNumberType !== Number::PUBLICATION) {
            throw new \InvalidArgumentException("Invalid right type of $rightNumberType supplied");
        }
        $this->rightType = $rightNumberType;
    }

    /**
     * @return Number
     */
    public static function application()
    {
        return new Number(Number::APPLICATION);
    }

    /**
     * @return Number
     */
    public static function publication()
    {
        return new Number(Number::PUBLICATION);
    }

    /**
     * Returns the IPF string
     * @return mixed
     */
    public function __toString()
    {
        return $this->rightType;
    }
}
