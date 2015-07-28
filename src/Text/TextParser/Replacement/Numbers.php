<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28/07/15
 * Time: 02:20
 */

namespace Aot\Text\TextParser\Replacement;


class Numbers extends Base
{
    protected function getPatterns()
    {
        return [
            "/(\\{\\%)?(((8|\\+7)[\\- ]?)?(\\(?\\d{3}\\)?[\\- ]?)?[\\d\\- ]{7,10})/u",
            "/(\\{\\%)?(\\d+([\\s\\,]*\\d+)*)/u"
        ];
    }
    protected function putInRegistry($record)
    {
        if( $record[1] === '{%' ){
            return $record[0];
        }

        $record[0] = preg_replace(["/(\\,)/","/(\\s)/"], [".", ""], $record[0]);

//        $this->registry[] = $record[0];
//        $index = count($this->registry) - 1;
        return "{%" . 111 . "%}";
    }
}