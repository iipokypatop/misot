<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:43
 */

namespace Aot\Text\TextParser\Filters;


class FilterNoValid extends Filter
{

    /**
     * @param $text
     * @return string
     */
    public function filter($text)
    {
        $valid = "а-яА-ЯёЁъЪ\\s\\:\\;\\-\"\\'\\`\\*\\%\\$\\,\\.\\(\\)\\{\\}\\[\\]\\d";
        return preg_replace("/[^".$valid."]/u", "", $text);
    }
}