<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:41
 */

namespace Aot\Text\TextParser\Filters;


class FilterSpaces extends Filter
{
    /**
     * @param $text
     * @return string
     */
    public function filter($text)
    {
        return preg_replace("/\\s+/", " ", $text);
    }
}