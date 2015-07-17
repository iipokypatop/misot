<?php

namespace Aot\RussianMorphology;

use Dw;
use Word;


/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 17:42
 */
abstract class Factory
{
    protected static $uniqueInstances = null;

    protected function __construct()
    {
    }

    /**
     * @return static
     */
    public static function get()
    {
        if (empty(static::$uniqueInstances[static::class])) {
            static::$uniqueInstances[static::class] = new static;
        }

        return static::$uniqueInstances[static::class];
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

    protected function __clone()
    {
    }

    abstract public function build(Dw $dw, Word $word);
}

class FactoryException extends \RuntimeException
{

}