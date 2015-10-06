<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 006, 06.10.2015
 * Time: 14:28
 */

namespace Aot\Orphography\Dictionary;


abstract class Base
{
    /**
     * @param \Aot\Orphography\Word $word
     * @return bool
     */
    abstract public function check(\Aot\Orphography\Word $word);


    /**
     * @param \Aot\Orphography\Word $word
     * @return \Aot\Orphography\Word[] with weight as keys
     */
    abstract public function suggest(\Aot\Orphography\Word $word);


    /**
     * @param \Aot\Orphography\Word $word1
     * @param \Aot\Orphography\Word $word2
     * @return int
     */
    abstract public function weight(\Aot\Orphography\Word $word1, \Aot\Orphography\Word $word2);

}