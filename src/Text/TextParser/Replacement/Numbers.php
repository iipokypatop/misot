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
//            "/(\\{\\%)?(((8|\\+7)[\\-]?)?(\\(?\\d{3}\\)?[\\-]?)?[\\d\\-]{7,10})/u", // без пробелов в номере
            "/(\\{\\%)?(((8|\\+7)[\\- ]?)?(\\(?\\d{3}\\)?[\\- ]?)?[\\d\\- ]{7,10})/u", // с пробелами в номере
            "/(\\{\\%)?(\\d+([\\s\\,]*\\d+)*)/u",
        ];
    }
    protected function putInRegistry($record)
    {
        if( $record[1] === '{%' || trim($record[0]) === ''){
            return $record[0];
        }

        $record[0] = preg_replace(["/(\\,)/","/(\\s)/"], [".", ""], $record[0]);
        $index = $this->registry->add($record[0]);
        return "{%" . $index . "%}";
    }
}