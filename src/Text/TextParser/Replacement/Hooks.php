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
            "/[\\[\\]\\{\\}]+/u"
        ];
    }

    protected function insertTemplate($preg_replace_matches)
    {
        $preg_replace_matches = $preg_replace_matches[0];
        $index = $this->registry->add($preg_replace_matches);
        $this->logger->notice("R: Заменили по шаблону [{$preg_replace_matches}], индекс {$index}");
        return $this->format($index);
    }
}