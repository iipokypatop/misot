<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22.10.2015
 * Time: 11:30
 */

namespace Aot\Tools;


class Registry implements \Iterator, \Countable
{
    /** @var int */
    protected $position = 0;
    /** @var String[] */
    protected $texts = [];
    /** @var \Aot\RussianMorphology\Slovo[] */
    protected $slova = [];

    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param string $text
     * @param \Aot\RussianMorphology\Slovo[]|false $slova
     */
    public function add($text, $slova)
    {
        assert(is_string($text));
        if ($slova !== false) {
            foreach ($slova as $slovo) {
                assert(is_a($slovo, \Aot\RussianMorphology\Slovo::class, true));
            }
        }
        $this->texts [] = $text;
        $this->slova [] = $slova;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->slova[$this->position];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->texts[$this->position];
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return array_key_exists($this->position, $this->texts);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->slova);
    }
}