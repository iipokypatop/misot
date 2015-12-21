<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 021, 21, 12, 2015
 * Time: 17:46
 */

namespace Aot\Text\TextParser\PostHooks;


class PositionMap extends Base
{
    protected $prefix = 'sentence_';
    protected $suffix = '_word_';
    protected $map = [];

    /**
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }


    public static function create()
    {
        $ob = new static();
        return $ob;
    }

    protected function createRegexp(\Aot\Text\TextParser\TextParser $parser)
    {
        $regexp_parts = [];

        foreach ($parser->getSentenceWords() as $sentence_id => $sentence) {

            foreach ($sentence as $position => $word) {

                $name = $this->getName($sentence_id, $position);

                $part = "(" . "?<$name>" . preg_quote($word) . ")";

                $regexp_parts[] = $part;
            }
        }

        $regexp_string = join('.*', $regexp_parts);

        $regexp_string = '#^' . '.*' . $regexp_string . '.*?' . '$#imsu';

        return $regexp_string;
    }

    protected function setDefaultMap(\Aot\Text\TextParser\TextParser $parser)
    {
        $this->map = [];

        foreach ($parser->getSentenceWords() as $sentence_id => $sentence) {
            $this->map[$sentence_id] = [];
        }
    }

    /**
     * @return string[][]
     */
    public function run(\Aot\Text\TextParser\TextParser $parser)
    {
        $this->setDefaultMap($parser);

        $regexp_string = $this->createRegexp($parser);

        $text = $parser->getRawInputText();

        $matches = [];
        $status = preg_match(
            $regexp_string,
            $text,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        if ($status === 0) {
            return;
        }

        if (false === $status) {
            throw new \LogicException("map build fail (preg serror)");
        }

        if (count($matches) === 1) {
            return;
        }

        $result_matches = $this->unpackMatches($matches);

        $map2 = [];
        foreach ($parser->getSentenceWords() as $sentence_id => $sentence_word) {
            foreach ($sentence_word as $word_id => $item) {

                if (isset($result_matches[$sentence_id][$word_id])) {

                    $match = $result_matches[$sentence_id][$word_id];
                    $word = $match[0];
                    $offset = $match[1];

                    $symbols = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);

                    if (!empty($symbols)) {
                        $index2 = 0;
                        foreach ($symbols as $symbol) {
                            $map2[$sentence_id][$word_id][$index2 + mb_strlen(substr($parser->getRawInputText(), 0, $offset), 'utf-8')] = $symbol;
                            $index2++;
                        }
                    }
                }
            }
        }

        $this->map = $map2;
    }

    protected function unpackMatches(array $matches)
    {
        $res = [];
        foreach ($matches as $name => $match) {
            $m = [];
            $pattern = "/^$this->prefix(?<sentence_id>\\d+)$this->suffix(?<position>\\d+)$/u";
            if (false !== preg_match($pattern, $name, $m)) {
                if (isset($m['sentence_id']) && isset($m['position'])) {
                    $res[intval($m['sentence_id'])][intval($m['position'])] = $match;
                }
            }
        }
        //$name = $this->getName($sentence_id, $position);
        return $res;
    }


    protected function getName($sentence_id, $position)
    {
        return $this->prefix . $sentence_id . $this->suffix . $position;
    }
}