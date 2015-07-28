<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:38
 */

namespace Aot\Text\TextParser\Filters;


abstract class Filter
{

    protected $logger;

    /**
     * Filter constructor.
     * @param $logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }


    public static function create($logger)
    {
        return new static($logger);
    }


    /**
     * @param $text
     * @return string
     */
    abstract public function filter($text);
}