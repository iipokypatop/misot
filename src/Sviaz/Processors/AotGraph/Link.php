<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 13/07/16
 * Time: 14:17
 */

namespace Aot\Sviaz\Processors\AotGraph;


class Link
{
    /** @var string */
    protected $name_of_link = '';

    /** @var  \Aot\RussianMorphology\Slovo */
    protected $main_slovo;

    /** @var  \Aot\RussianMorphology\Slovo */
    protected $depended_slovo;

    /** @var  int */
    protected $main_position;

    /** @var  int */
    protected $depended_position;

    /**
     * @param string $name_of_link
     * @return static
     */
    public static function create($name_of_link)
    {
        assert(is_string($name_of_link));
        return new static($name_of_link);
    }

    /**
     * @param string $name_of_link
     */
    protected function __construct($name_of_link)
    {
        assert(is_string($name_of_link));
        $this->name_of_link = $name_of_link;
    }

    /**
     * @return string
     */
    public function getNameOfLink()
    {
        return $this->name_of_link;
    }

    /**
     * @return \Aot\RussianMorphology\Slovo
     */
    public function getMainSlovo()
    {
        if (!isset($this->main_slovo)) {
            throw new \Aot\Exception('Main slovo is not set!');
        }
        return $this->main_slovo;
    }

    /**
     * @param \Aot\RussianMorphology\Slovo $main_slovo
     */
    public function setMainSlovo($main_slovo)
    {
        $this->main_slovo = $main_slovo;
    }

    /**
     * @return \Aot\RussianMorphology\Slovo
     */
    public function getDependedSlovo()
    {
        if (!isset($this->depended_slovo)) {
            throw new \Aot\Exception('Depended slovo is not set!');
        }
        return $this->depended_slovo;
    }

    /**
     * @param \Aot\RussianMorphology\Slovo $depended_slovo
     */
    public function setDependedSlovo($depended_slovo)
    {
        $this->depended_slovo = $depended_slovo;
    }

    /**
     * @param int $main_position
     */
    public function setMainPosition($main_position)
    {
        assert(is_int($main_position));
        $this->main_position = $main_position;
    }

    /**
     * @param int $depended_position
     */
    public function setDependedPosition($depended_position)
    {
        assert(is_int($depended_position));
        $this->depended_position = $depended_position;
    }

    /**
     * @return int
     */
    public function getDependedPosition()
    {
        if (!isset($this->depended_position)) {
            throw new \Aot\Exception('Depended position is not set!');
        }
        return $this->depended_position;
    }

    /**
     * @return int
     */
    public function getMainPosition()
    {
        if (!isset($this->main_position)) {
            throw new \Aot\Exception('Main position is not set!');
        }
        return $this->main_position;
    }
}