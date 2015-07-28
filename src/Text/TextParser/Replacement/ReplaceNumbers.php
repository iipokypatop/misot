<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28/07/15
 * Time: 02:20
 */

namespace Aot\Text\TextParser\Replacement;


class ReplaceNumbers extends Replace
{

    protected function putInRegistry($match)
    {
        if( $match[1] === '{{' ){
            return $match[0];
        }

        $match[0] = preg_replace(["/(\\,)/","/(\\s)/"], [".", ""], $match[0]);

        $this->registry[] = $match[0];
        $index = count($this->registry) - 1;
        return "{{" . $index . "}}";
    }
}