<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28/07/15
 * Time: 02:14
 */

namespace Aot\Text\TextParser\Replacement;


class Short extends Base
{
    protected function getPatterns()
    {
        return [
            "/и\\sт\\.{0,1}[пд]\\.{0,1}/u",
            "/тел./u",
            "/т\\./u",
            "/н\\.?э\\.?/u"
        ];
    }
}