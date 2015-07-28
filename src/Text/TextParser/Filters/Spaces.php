<?php
/**
 * Created by PhpStorm.
 * User: admin
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
            '/\\s/',
//            '/\\s/',
        ];

    }

    public function filter($text)
    {
        $arr = [];
        foreach ($this->getPatterns() as $pattern) {
            $array = preg_split($pattern, $text, -1, PREG_SPLIT_OFFSET_CAPTURE);
            foreach ($array as $key => $value) {
                if ($value[0] === '') {
                    continue;
                }
                $arr[] = $value[0];
            }
        }

        return join(' ', $arr);
    }
}