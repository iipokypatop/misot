<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28/07/15
 * Time: 18:52
 */

namespace Aot\Text\TextParser;


class Logger
{
    protected $notices = [];
    protected $warnings = [];
    protected $errors = [];


    public static function create()
    {
        return new static();
    }

    public function notice($msg)
    {
        $this->notices[] = $msg;
    }

    public function warn($msg)
    {
        $this->warnings[] = $msg;
    }

    public function error($msg)
    {
        $this->errors[] = $msg;
    }
}