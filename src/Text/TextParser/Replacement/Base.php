<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:45
 */

namespace Aot\Text\TextParser\Replacement;


abstract class Base
{
    protected $registry;
    protected $logger;


    /**
     * @param $registry
     * @return object Aot\Text\TextParser\Replacement\Base
     */
    static public function create($registry, $logger)
    {
        return new static($registry, $logger);
    }

    public function __construct($registry, $logger)
    {
        $this->registry = $registry;
        $this->logger = $logger;
    }

    /**
     * @param $text
     * @return string
     */
    public function replace($text)
    {
        $text = preg_replace_callback(
            $this->getPatterns(),
            [$this, 'insertTemplate'],
            $text
        );

        return $text;
    }

    /**
     * @param $record
     * @return null
     */
    protected function insertTemplate($record)
    {
        $index = $this->registry->add($record[0]);
        $this->logger->notice("R: Заменили по шаблону [{$record[0]}], индекс {$index}");
        // add logger
        return "{%" . $index . "%}";
    }

    /**
     * @return array
     */
    abstract protected function getPatterns();
}

