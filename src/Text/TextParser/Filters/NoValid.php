<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:43
 */

namespace Aot\Text\TextParser\Filters;


class NoValid extends Base
{

    /**
     * @return array
     */
    protected function getPatterns()
    {
        return [
            "/[^а-яА-ЯёЁъЪ\\s\\:\\;\\-\"\\'\\`\\*\\%\\$\\,\\.\\(\\)\\{\\}\\[\\]\\d]/u",
        ];
    }

    /**
     * @param $text
     * @return string
     */
    public function filter($text)
    {
        $arr = [];
        foreach ($this->getPatterns() as $pattern) {
            $array = preg_split($pattern, $text, -1, PREG_SPLIT_OFFSET_CAPTURE);
            print_r($array);
            foreach ($array as $key => $value) {
                if ($value[0] === '') {
                    continue;
                }
                $arr[] = $value[0];
            }
        }

        return join('', $arr);
    }


}