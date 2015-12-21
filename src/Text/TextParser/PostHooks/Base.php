<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 021, 21, 12, 2015
 * Time: 17:45
 */

namespace Aot\Text\TextParser\PostHooks;


abstract class Base
{
    /**
     * @param \Aot\Text\TextParser\TextParser $parser
     */
    abstract public function run(\Aot\Text\TextParser\TextParser $parser);
}