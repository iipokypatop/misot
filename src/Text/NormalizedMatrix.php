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
    public $storage = [];

    /**
     * @return mixed
     */
    public function getStorage()
    {
        return $this->storage;
    }

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
                    $this->matrix_id_mask[$index][] = $value;// $this->register($value);;

                }
            } else {
                $this->matrix_id_mask[$index][] = $element;//$this->register($element);
            }
        }

        //$this->build();

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

    public function recreateMatrix(callable $callable)
    {
        $new_matrix_id_mask = [];
        foreach ($this->matrix_id_mask as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $new_matrix_id_mask [$k1][$k2] = $callable($v2);
            }
        }

        $this->matrix_id_mask = $new_matrix_id_mask;
    }

    public function build()
    {
        if (empty($this->matrix_id_mask)) {
            return;
        }

        $elements_total = 1;
        $row_sizes = [];
        $row_value_change_periods = []; // кол-во итераций, через которое значение в столбце сменится на следующее

        foreach ($this->matrix_id_mask as $index => $element) {
            $row_size = count($element);
            $row_sizes[$index] = $row_size;
            $row_value_change_periods[$index] = $elements_total;
            $elements_total *= $row_size;
        }

        $row_periods = []; // кол-во итераций, через которое произойдет полный цикл смены значений в столбце
        $row_value_repeat_count = []; //кол-во итераций, в течение которых повторяется одно и тоже значение в сотлбце
        foreach ($row_value_change_periods as $index => $period) {
            $row_periods[$index] = $elements_total / $period;
            $row_value_repeat_count[$index] = $row_periods[$index] / $row_sizes[$index];
        }
        //print_r((memory_get_usage(true) / 1000000) . "\n");


//        $this->storage = array_fill(
//            0,
//            $elements_total,
//            array_fill(0, count($this->matrix_id_mask), null)
//        );


        $count = count($this->matrix_id_mask);
        $this->storage = new \Judy(\Judy::INT_TO_MIXED);

        $tmp = new \Judy(\Judy::INT_TO_MIXED);
        for ($j = 0; $j < $count; $j++) {
            $tmp [] = null;
        }

        for ($i = 0; $i < $elements_total; $i++) {
            //$tmp =  new \Judy(\Judy::INT_TO_MIXED);
            $this->storage[] = clone($tmp);
        }


        //print_r((memory_get_usage(true) / 1000000) . "\n");


        $C = pow(10, ceil(log10(count($this->matrix_id_mask))));

        $cache = [];
        foreach ($this->matrix_id_mask as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                $cache[$k1 * $C + $k2] = $v2;
            }
        }

        foreach ($this->storage as $y => $row) {
            foreach ($row as $x => $column) {
                $index = (int)(
                    ($y % $row_periods[$x])
                    /
                    $row_value_repeat_count[$x]
                );
                //$this->storage[$y][$x] = $this->matrix_id_mask[$x][$index];
                //$column = $this->matrix_id_mask[$x][$index];
                $row[$x] = $cache[$x * $C + $index];


            }

        }
        //print_r((memory_get_usage(true) / 1000000) . "\n");
    }

    /**
     * @return string
     */
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

        return $id;
        return $this->registry[$id];
    }


}