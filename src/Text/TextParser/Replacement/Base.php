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

    const START = '{{';
    const END = '}}';


    /**
     * @param $registry
     * @param $logger
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
     * @param $preg_replace_matches
     * @return null
     */
    protected function insertTemplate($preg_replace_matches)
    {
        if (count($preg_replace_matches) === 3) {
            $record_replace = $preg_replace_matches[0];
            foreach ($preg_replace_matches as $key => $match) {
                if ($key > 0) {
                    $record_replace = str_replace($match, "", $record_replace);
                }
            }

            if($preg_replace_matches[2] === '.'){
                $record_replace .= '.';
//                $preg_replace_matches[2] = '.';
            }
            $index = $this->registry->add($record_replace);

            $this->logger->notice("R: Заменили по шаблону [{$record_replace}], индекс {$index}");
            return $this->format($index, [$preg_replace_matches[1], $preg_replace_matches[2]]);

        }

        $preg_replace_matches = $preg_replace_matches[0];
        $index = $this->registry->add($preg_replace_matches);
        $this->logger->notice("R: Заменили по шаблону [{$preg_replace_matches}], индекс {$index}");

        // если точка в конце, то дублируем её и вставляем после шаблона
        if (preg_match("/\\.$/", $preg_replace_matches)) {
            return $this->format($index, ['', '.']);
        }

        return $this->format($index);
    }

    protected function format($index, $sides = [])
    {
        if (empty($sides)) {
            return static::START . $index . static::END;
        }

        return $sides[0] . static::START . $index . static::END . $sides[1];
    }

    /**
     * @return array
     */
    abstract protected function getPatterns();
}

