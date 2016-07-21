<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.07.2016
 * Time: 14:59
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions;


class VertexAndInitialForm
{
    /** @var  \Aot\Graph\Slovo\Vertex */
    protected $vertex;
    /** @var  string */
    protected $initial_form;

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @param string $initial_form
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\VertexAndInitialForm
     */
    public static function create(\Aot\Graph\Slovo\Vertex $vertex, $initial_form)
    {
        assert(is_string($initial_form));
        $ob = new static($vertex, $initial_form);
        return $ob;
    }

    /**
     * VertexAndInitialForm constructor.
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @param string $initial_form
     */
    protected function __construct(\Aot\Graph\Slovo\Vertex $vertex, $initial_form)
    {
        assert(is_string($initial_form));
        $this->vertex = $vertex;
        $this->initial_form = $initial_form;
    }

    /**
     * @return \Aot\Graph\Slovo\Vertex
     */
    public function getVertex()
    {
        return $this->vertex;
    }

    /**
     * @return string
     */
    public function getInitialForm()
    {
        return $this->initial_form;
    }


}