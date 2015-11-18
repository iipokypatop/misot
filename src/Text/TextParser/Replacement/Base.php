<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 27/07/15
 * Time: 20:45
 */

namespace Aot\Text\TextParser\Replacement;

abstract class Base
{
    /** @var \Aot\Text\TextParser\Registry */
    protected $registry;

    /** @var \Aot\Text\TextParser\Logger */
    protected $logger;

    const START = '{{';
    const END = '}}';


    /**
     * @param \Aot\Text\TextParser\Registry $registry
     * @param \Aot\Text\TextParser\Logger $logger
     * @return object Aot\Text\TextParser\Replacement\Base
     */
    static public function create(\Aot\Text\TextParser\Registry $registry, \Aot\Text\TextParser\Logger $logger)
    {
        return new static($registry, $logger);
    }

    /**
     * Base constructor.
     * @param \Aot\Text\TextParser\Registry $registry
     * @param \Aot\Text\TextParser\Logger $logger
     */
    public function __construct(\Aot\Text\TextParser\Registry $registry, \Aot\Text\TextParser\Logger $logger)
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
     * Вставка шаблона
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

            if ($preg_replace_matches[2] === '.') {
                $record_replace .= '.';
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

    /**
     * Вставка в строку символов, указывающих на замененные элементы
     * @param integer $index
     * @param array $sides
     * @return string
     */
    protected function format($index, array $sides = [])
    {
        assert(is_int($index));

        if (empty($sides)) {
            return static::START . $index . static::END;
        }

        return $sides[0] . static::START . $index . static::END . $sides[1];
    }

    /**
     * Получение шаблонов для замены
     * @return array
     */
    abstract protected function getPatterns();
}

