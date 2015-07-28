<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:46
 */

namespace Aot\Text\TextParser\Replacement;


class Hooks extends Base
{

    protected function getPatterns()
    {
        return [
            "/[\\[\\]]/u"
        ];
    }
}