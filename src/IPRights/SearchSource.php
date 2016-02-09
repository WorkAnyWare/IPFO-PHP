<?php

namespace WorkAnyWare\IPFO\IPRights;

/**
 * Class SearchSource
 * @package WorkAnyWare\IPFO\IPRights
 */
class SearchSource
{

    /**
     * @var
     */
    private $source;

    /**
     * @return SearchSource
     */
    public static function EPO()
    {
        return new SearchSource('EPO');
    }

    /**
     * @return SearchSource
     */
    public static function USPTO()
    {
        return new SearchSource('USPTO');
    }

    /**
     * @return SearchSource
     */
    public static function WIPO()
    {
        return new SearchSource('WIPO');
    }

    /**
     * @param $source
     *
     * @return SearchSource
     */
    public static function fromString($source)
    {
        return new SearchSource($source);
    }

    /**
     * SearchSource constructor.
     *
     * @param $source
     */
    private function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->source;
    }
}