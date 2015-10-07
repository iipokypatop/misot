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
     * @param \Aot\Orphography\Subtext $subtext
     * @return bool
     */
    abstract public function check(\Aot\Orphography\Subtext $subtext);


    /**
     * @param \Aot\Orphography\Subtext $subtext
     * @return \Aot\Orphography\Subtext[] with weight as keys
     */
    abstract public function suggest(\Aot\Orphography\Subtext $subtext);


    /**
     * @param \Aot\Orphography\Subtext $subtext1
     * @param \Aot\Orphography\Subtext $subtext2
     * @return int
     */
    abstract public function weight(\Aot\Orphography\Subtext $subtext1, \Aot\Orphography\Subtext $subtext2);

}