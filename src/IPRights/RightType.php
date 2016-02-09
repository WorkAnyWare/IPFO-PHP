<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 08/02/2016
 * Time: 20:30
 */

namespace WorkAnyWare\IPFO\IPRights;


/**
 * Class RightType
 * @package WorkAnyWare\IPFO\IPRights
 */
class RightType
{
    /**
     * A Patent
     */
    const PATENT = 'patent';
    /**
     * A Trademark
     */
    const TRADEMARK = 'trademark';

    /**
     * @var type of this RightType
     */
    private $type;

    /**
     * Returns a new Trademark RightType
     * @return RightType
     */
    public static function trademark()
    {
        return new RightType(RightType::TRADEMARK);
    }

    /**
     * Returns a new Patent Right Type
     * @return RightType
     */
    public static function patent()
    {
        return new RightType(RightType::PATENT);
    }

    /**
     * @param $rightType
     *
     * @return RightType
     */
    public static function fromString($rightType)
    {
        return new RightType($rightType);
    }

    /**
     * RightType constructor.
     *
     * @param $type
     */
    private function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isTrademark()
    {
        return $this->type == RightType::TRADEMARK;
    }

    /**
     * @return bool
     */
    public function isPatent()
    {
        return $this->type == RightType::PATENT;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->type;
    }
}