<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:38
 */

namespace Aot\Text\TextParser\Filters;


abstract class Base
{

    protected $logger;


    /**
     * @param $logger
     * @return object Aot\Text\TextParser\Filters\Base
     */
    public static function create($logger)
    {
        return new static($logger);
    }

    /**
     * Filter constructor.
     * @param $logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $text
     * @return string
     */
    abstract public function filter($text);


    /**
     * @return []
     */
    abstract protected function getPatterns();
}