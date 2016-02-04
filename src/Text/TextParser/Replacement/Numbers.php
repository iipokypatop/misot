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
            "/()(\\d+\\.\\d+)()/u",
            "/(\\{\\{)?(((8|\\+7)[\\-]?)?(\\(?\\d{3}\\)?[\\-]?)?[\\d\\-]{7,10})/u", // без пробелов в номере
//            "/(\\{\\%)?(((8|\\+7)[\\- ]?)?(\\(?\\d{3}\\)?[\\- ]?)?[\\d\\- ]{7,10})/u", // с пробелами в номере
//            "/(\\{\\{)?(\\d+([\\s\\,]*\\d+)*)/u",
            "/(\\{\\{)?(\\d+([\\s\\,]*\\d+)*(\\-?[оы]?м)?)/u",
        ];
    }

    protected function insertTemplate($preg_replace_matches)
    {
        if ($preg_replace_matches[1] === '{{' || trim($preg_replace_matches[0]) === '') {
            return $preg_replace_matches[0];
        }

        $preg_replace_matches[0] = preg_replace(["/(\\,)/", "/(\\s)/"], [".", ""], $preg_replace_matches[0]);
        $index = $this->registry->add($preg_replace_matches[0]);
        $this->logger->notice("R: Заменили по шаблону [{$preg_replace_matches[0]}], индекс {$index}");
        return $this->format($index);
    }
}