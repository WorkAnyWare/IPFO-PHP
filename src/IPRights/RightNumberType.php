<?php

namespace WorkAnyWare\IPFO\IPRights;

class RightNumberType
{
    const APPLICATION = 'application';
    const PUBLICATION = 'publication';

    private $rightType;

    /**
     * RightNumberType constructor.
     *
     * @param $rightNumberType
     */
    private function __construct($rightNumberType)
    {
        if ($rightNumberType !== RightNumberType::APPLICATION && $rightNumberType !== RightNumberType::PUBLICATION) {
            throw new \InvalidArgumentException("Invalid right type of $rightNumberType supplied");
        }
        $this->rightType = $rightNumberType;
    }

    /**
     * @return RightNumberType
     */
    public static function application()
    {
        return new RightNumberType(RightNumberType::APPLICATION);
    }

    /**
     * @return RightNumberType
     */
    public static function publication()
    {
        return new RightNumberType(RightNumberType::PUBLICATION);
    }

    /**
     * Returns the IPRight string
     * @return mixed
     */
    public function __toString()
    {
        return $this->rightType;
    }
}
