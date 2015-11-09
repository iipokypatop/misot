<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 09.11.2015
 * Time: 13:32
 */

namespace Aot\RussianSyntacsis\Sentence\SimpleSentence\Type;


abstract class Base
{
    /** @var  string */
    protected $description;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        assert(is_string($description));
        $this->description = $description;
    }
}