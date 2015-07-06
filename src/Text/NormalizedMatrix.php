<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 1:01
 */

namespace Aot\Text;


class NormalizedMatrix implements \Iterator
{
    /** @var  Matrix */
    protected $matrix;

    /** @var [][] */
    protected $storage = [];

    /** @var array */
    protected $registry = [];

    /** @var array */
    protected $matrix_id_mask = [];
    protected $increment = 0;

    protected $index = 0;

    /**
     * NormalizedMatrix constructor.
     * @param Matrix $matrix
     */
    protected function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;

        $this->normalize();
    }

    protected function normalize()
    {
        $data = $this->matrix->getSentenceMatrix();

        foreach ($data as $index => $element) {
            if (is_array($element)) {
                foreach ($element as $value) {
                    $this->matrix_id_mask[$index][] = $this->register($value);;

                }
            } else {
                $this->matrix_id_mask[$index][] = $this->register($element);
            }
        }

        $this->build();

        #echo $this->getPrettyDump();
    }

    protected function register($value)
    {
        if (in_array($value, $this->registry, true)) {
            throw new \RuntimeException("one word or punctuation can't be here twice " . var_export($value, 1));
        }

        $this->registry[++$this->increment] = $value;

        return $this->increment;
    }

    protected function build()
    {
        foreach ($this->matrix_id_mask as $array) {

            if (empty($this->storage)) {

                foreach ($array as $value) {
                    $this->storage[] = [$value];
                }
            } else {

                $new_storage = [];

                foreach ($this->storage as $sequence) {

                    foreach ($array as $value) {

                        $new_storage[] = array_merge($sequence, [$value]);
                    }
                }

                $this->storage = $new_storage;
            }
        }
    }

    public function getPrettyDump()
    {
        $dump = [];
        foreach ($this->storage as $seq) {
            $dump [] = call_user_func_array(
                'sprintf',
                array_merge([str_repeat("%02d ", count($seq))], $seq)
            );

        }

        return join("\n", $dump);
    }

    public static function create(Matrix $matrix)
    {
        return new static($matrix);
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        $array = [];
        foreach ($this->storage[$this->index] as $id) {
            $array[] = $this->getElementById($id);
        }

        return $array;
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return !empty($this->storage[$this->index]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->index = 0;
    }

    protected function getElementById($id)
    {
        return $this->registry[$id];
    }
}