<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.07.2016
 * Time: 13:15
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions;

/**
 * Контейнер для переброски параметров между методами и для постепенного заполнения
 */
class ContainerCollocation
{
    /** @var  int */
    protected $start_position;
    /** @var  int */
    protected $end_position;
    /** @var  int */
    protected $count;

    /** @var string[] */
    protected $initial_forms_of_words_of_collocation = [];
    /** @var  \Aot\Graph\Slovo\Vertex */
    protected $vertex_collocation;
    /** @var \Aot\Graph\Slovo\Vertex[] */
    protected $vertices_of_collocation = [];
    /** @var  int */
    protected $position_of_main_word;

    /** @var  \Aot\RussianMorphology\Slovo */
    protected $collocation_slovo;
    /** @var  string */
    protected $collocation_initial_form;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @return int
     */
    public function getStartPosition()
    {
        if ($this->start_position === null) {
            throw new \Aot\Exception("Поле 'start_position' не задано.");
        }
        return $this->start_position;
    }

    /**
     * @param int $start_position
     * @return ContainerCollocation
     */
    public function setStartPosition($start_position)
    {
        assert(is_int($start_position));
        $this->start_position = $start_position;
        return $this;
    }

    /**
     * @return int
     */
    public function getEndPosition()
    {
        if ($this->end_position === null) {
            throw new \Aot\Exception("Поле 'end_position' не задано.");
        }
        return $this->end_position;
    }

    /**
     * @param int $end_position
     * @return ContainerCollocation
     */
    public function setEndPosition($end_position)
    {
        assert(is_int($end_position));
        $this->end_position = $end_position;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        if ($this->count === null) {
            throw new \Aot\Exception("Поле 'count' не задано.");
        }
        return $this->count;
    }

    /**
     * @param int $count
     * @return ContainerCollocation
     */
    public function setCount($count)
    {
        assert(is_int($count));
        $this->count = $count;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getInitialFormsOfWordsOfCollocation()
    {
        if ($this->initial_forms_of_words_of_collocation === null) {
            throw new \Aot\Exception("Поле 'initial_forms_of_words_of_collocation' не задано.");
        }
        return $this->initial_forms_of_words_of_collocation;
    }

    /**
     * @param string[] $initial_forms_of_words_of_collocation
     * @return ContainerCollocation
     */
    public function setInitialFormsOfWordsOfCollocation(array $initial_forms_of_words_of_collocation)
    {
        foreach ($initial_forms_of_words_of_collocation as $initial_form_of_word_of_collocation) {
            assert(is_string($initial_form_of_word_of_collocation));
        }
        $this->initial_forms_of_words_of_collocation = $initial_forms_of_words_of_collocation;
        return $this;
    }

    /**
     * @return \Aot\Graph\Slovo\Vertex
     */
    public function getVertexCollocation()
    {
        if ($this->vertex_collocation === null) {
            throw new \Aot\Exception("Поле 'vertex_collocation' не задано.");
        }
        return $this->vertex_collocation;
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex_collocation
     * @return ContainerCollocation
     */
    public function setVertexCollocation(\Aot\Graph\Slovo\Vertex $vertex_collocation)
    {
        $this->vertex_collocation = $vertex_collocation;
        return $this;
    }

    /**
     * @return \Aot\Graph\Slovo\Vertex[]
     */
    public function getVerticesOfCollocation()
    {
        if ($this->vertices_of_collocation === null) {
            throw new \Aot\Exception("Поле 'vertices_of_collocation' не задано.");
        }
        return $this->vertices_of_collocation;
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex[] $vertices_of_collocation
     * @return ContainerCollocation
     */
    public function setVerticesOfCollocation(array $vertices_of_collocation)
    {
        foreach ($vertices_of_collocation as $vertex_of_collocation) {
            assert(is_a($vertex_of_collocation, \Aot\Graph\Slovo\Vertex::class, true));
        }
        $this->vertices_of_collocation = $vertices_of_collocation;
        return $this;
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex_of_collocation
     * @return ContainerCollocation
     */
    public function addVertexOfCollocation(\Aot\Graph\Slovo\Vertex $vertex_of_collocation)
    {
        foreach ($this->vertices_of_collocation as $item) {
            if ($vertex_of_collocation === $item) {
                throw new \Aot\Exception("Данная вершина уже была добавлена");
            }
        }
        $this->vertices_of_collocation[] = $vertex_of_collocation;
        return $this;
    }

    /**
     * @return \Aot\RussianMorphology\Slovo
     */
    public function getCollocationSlovo()
    {
        if ($this->collocation_slovo === null) {
            throw new \Aot\Exception("Поле 'collocation_slovo' не задано.");
        }
        return $this->collocation_slovo;
    }

    /**
     * @param \Aot\RussianMorphology\Slovo $collocation_slovo
     * @return ContainerCollocation
     */
    public function setCollocationSlovo(\Aot\RussianMorphology\Slovo $collocation_slovo)
    {
        $this->collocation_slovo = $collocation_slovo;
        return $this;
    }

    /**
     * @return int
     */
    public function getPositionOfMainWord()
    {
        if ($this->position_of_main_word === null) {
            throw new \Aot\Exception("Поле 'position_of_main_word' не задано.");
        }
        return $this->position_of_main_word;
    }

    /**
     * @param int $position_of_main_word
     * @return ContainerCollocation
     */
    public function setPositionOfMainWord($position_of_main_word)
    {
        assert(is_int($position_of_main_word));
        $this->position_of_main_word = $position_of_main_word;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollocationInitialForm()
    {
        if ($this->collocation_initial_form === null) {
            throw new \Aot\Exception("Поле 'collocation_initial_form' не задано.");
        }
        return $this->collocation_initial_form;
    }

    /**
     * @param string $collocation_initial_form
     * @return ContainerCollocation
     */
    public function setCollocationInitialForm($collocation_initial_form)
    {
        assert(is_string($collocation_initial_form));
        $this->collocation_initial_form = $collocation_initial_form;
        return $this;
    }


}