<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 006, 06.10.2015
 * Time: 14:28
 */

namespace Aot\Orphography\Dictionary;


abstract class Subtext
{
    /**
     * @return void
     */
    abstract public function create(\Aot\Orphography\Subtext $subtext);

    /**
     * @return $word
     */
    abstract public function read();

    /**
     * @return void
     */
    abstract public function update();


    /**
     * @return void
     */
    abstract public function delete();
}