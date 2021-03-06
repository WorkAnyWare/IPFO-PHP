<?php

namespace WorkAnyWare\IPFO\IPRights;

class Priority
{

    private $number;
    private $date;
    private $kind;
    private $country;

    /**
     * @return mixed
     */
    public function getCountry()
    {
        if (is_null($this->country)) {
            if (ctype_alpha(substr($this->number, 0, 2))) {
                return substr($this->number, 0, 2);
            }
        }
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    public static function fromNumber($number)
    {
        $priority = new Priority();
        $priority->setNumber($number);
        return $priority;
    }

    private function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param mixed $kind
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }
}