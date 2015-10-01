<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 20.07.2015
 * Time: 16:05
 */
// php run.php connection_string

class aaa
{
    public function __construct($connection_string)
    {
        dbMorph::up($connection_string);
        dbTxt::up($connection_string);
    }
}

