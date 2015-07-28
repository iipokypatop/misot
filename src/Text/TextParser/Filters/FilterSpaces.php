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
        // $this->logger->notice замена чего на что на какой позиции

        $array = preg_split('/\s+/', $text);

        //$array = preg_match('/^\s+$/', $text);

        //return preg_replace("/\\s+/", " ", $text);
    }
}