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

    protected $text = "/а-яА-ЯёЁъЪ\\s\\:\\;\\-\"\\'\\`\\*\\%\\$\\,\\.\\(\\)\\{\\}\\[\\]\\d/";

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }



    /**
     * @param $text
     * @return string
     */
    public function filter($text)
    {
        $valid = "/а-яА-ЯёЁъЪ\\s\\:\\;\\-\"\\'\\`\\*\\%\\$\\,\\.\\(\\)\\{\\}\\[\\]\\d/";

        //$this->logger->notice()
        // notice
        // warning
        // error
        return preg_replace("/[^".$valid."]/u", "", $text);
    }
}