<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 27/07/15
 * Time: 20:41
 */

namespace Aot\Text\TextParser\Filters;


class Spaces extends Base
{

    /**
     * @return array
     */
    protected function getPatterns()
    {
        return [
            '/(\\s+)/' => '',
        ];

    }

    public function filter($text)
    {
        $arr = [];
        foreach ($this->getPatterns() as $pattern => $replacement) {
            $array = preg_split($pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
            // определяем четность
            $par = (preg_match($pattern, $array[0][0])) ? 2 : 1;
            foreach ($array as $key => $value) {
                if (($key % 2) === $par) {
                    $cnt_spaces = strlen($value[0]);
                    if ($cnt_spaces > 1) {
                        $this->logger->notice("Убрали пробел [{$cnt_spaces}] на позиции [{$value[1]}]");
                    }
                    continue;
                }
                $arr[] = $value[0];
            }
        }
        return join(' ', $arr);
    }
}