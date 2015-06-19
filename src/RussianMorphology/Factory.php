<?php

namespace RussianMorphology;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 17:42
 */
abstract class Factory
{
    protected static $uniqueInstance = null;

    protected function __construct()
    {
    }

    /**
     * @return static
     */
    public static function get()
    {
        if (static::$uniqueInstance === null) {
            static::$uniqueInstance = new static;
        }

        return static::$uniqueInstance;
    }

    public function create()
    {

    }

    /**
     * @param $text
     * @return AnalysisProtocol
     */
    public function analyze($text)
    {
        $analyser = $this->getAnalyser();

        $protocol = $analyser->run($text);

        return $protocol;
    }

    protected function getAnalyser()
    {
        return new Analyser();
    }

    private function __clone()
    {
    }
}