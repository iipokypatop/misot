<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.07.2016
 * Time: 14:02
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate;

use Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions;
use Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\VertexAndInitialForm;

/**
 * Фабрика по созданию кандидатов в словосочетания
 */
class BaseFactory implements IFactory
{
    /** @var  \Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\WordsCollocationAPI\API */
    protected $api;

    /**
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\BaseFactory
     */
    public static function createDefault()
    {
        $ob = new static();
        $ob->api = $api = WordsCollocationAPI\API::get();
        return $ob;
    }

    /**
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\BaseFactory
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }


    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation[]
     */
    public function createCollocationCandidatesByGraph(\Aot\Graph\Slovo\Graph $graph)
    {
        if ($this->api === null) {
            throw new \Aot\Exception("Для фабрики необходимо API");
        }

        // Карта всех начальных форм и вершин по каждой позиции
        $map_initial_forms_and_vertex_by_positions = $this->getMapInitialFormAndVertexByPositions($graph);

        // Карта вариантов словосочетаний начиная с каждой позиции
        $collocation_candidates = $this->buildCollocationCandidates($map_initial_forms_and_vertex_by_positions);

        $candidates_in_result = [];

        foreach ($collocation_candidates as $collocation_candidate) {
            /** @var \Aot\Graph\Slovo\Vertex[] $suitable_vertices */
            $suitable_vertices = [];
            foreach ($collocation_candidate->getInitialFormsOfWordsOfCollocation() as $relative_index => $collocation_element) {
                $start_position = $collocation_candidate->getStartPosition();
                $exists_initial_form_in_origin_text = false;
                /** @var VertexAndInitialForm $initial_form_and_vertex */
                foreach ($map_initial_forms_and_vertex_by_positions[$start_position + $relative_index] as $initial_form_and_vertex) {
                    if ($initial_form_and_vertex->getInitialForm() === $collocation_element) {
                        $suitable_vertices[$start_position + $relative_index] = $initial_form_and_vertex->getVertex();
                        $exists_initial_form_in_origin_text = true;
                    }
                }
                if (!$exists_initial_form_in_origin_text) {
                    continue 2;
                }
            }
            //Если код дошёл сюда, значит в графе есть словосочетание
            $collocation_candidate->setVerticesOfCollocation($suitable_vertices);
            $this->fillCollocationCandidateByCollocationSlovo($collocation_candidate);

            $candidates_in_result[] = $collocation_candidate;
        }
        return $candidates_in_result;
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @return VertexAndInitialForm[]
     */
    protected function getMapInitialFormAndVertexByPositions(\Aot\Graph\Slovo\Graph $graph)
    {
        $map_initial_forms_by_positions = [];
        foreach ($graph->getVerticesCollection() as $vertex) {
            if (!$vertex->hasPositionInSentence()) {
                continue;
            }
            $initial_form = $vertex->getSlovo()->getInitialForm();
            $position = $vertex->getPositionInSentence();
            $map_initial_forms_by_positions[$position][] = VertexAndInitialForm::create($vertex, $initial_form);
        }
        return $map_initial_forms_by_positions;
    }

    /**
     * @param VertexAndInitialForm[][] $map_initial_forms_by_positions
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation[]
     */
    protected function buildCollocationCandidates(array $map_initial_forms_by_positions)
    {
        $collocation_candidates = [];

        foreach ($map_initial_forms_by_positions as $position => $vertices_and_initial_forms) {
            foreach ($vertices_and_initial_forms as $vertex_and_initial_form) {
                //Получаем из базы данных словосочетания
                $temp_candidates = $this->getCandidateCollocationByFirstVertex(
                    $vertex_and_initial_form,
                    $position
                );

                if (empty($temp_candidates)) {
                    continue;
                }


                $collocation_candidates = array_merge($collocation_candidates, $temp_candidates);
            }
        }
        return $collocation_candidates;
    }

    /**
     * @param VertexAndInitialForm $vertex_and_initial_form
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation[]
     */
    protected function getCandidateCollocationByFirstVertex(VertexAndInitialForm $vertex_and_initial_form, $position)
    {
        $initial_form = $vertex_and_initial_form->getInitialForm();
        $map_collocation_ids_by_first_initial_form = $this->api->getMapCollocationIdsByFirstInitialForm();
        $map_collocation_initial_form_by_collocation_id = $this->api->getMapCollocationInitialFormByCollocationId();
        $map_initial_forms_by_collocation_id = $this->api->getMapInitialFormsByCollocationId();
        $map_main_position_by_collocation_id = $this->api->getMapMainPositionByCollocationId();

        if (!isset($map_collocation_ids_by_first_initial_form[$initial_form])) {
            return [];
        }
        $collocation_ids = $map_collocation_ids_by_first_initial_form[$initial_form];
        $candidates = [];

        foreach ($collocation_ids as $collocation_id) {
            $elements = $map_initial_forms_by_collocation_id[$collocation_id];
            $candidate = Additions\ContainerCollocation::create()
                ->setInitialFormsOfWordsOfCollocation($elements)
                ->setCollocationInitialForm($map_collocation_initial_form_by_collocation_id[$collocation_id])
                ->setStartPosition($position)
                ->setCount(count($elements))
                ->setEndPosition($position + count($elements))
                ->setPositionOfMainWord($map_main_position_by_collocation_id[$collocation_id]);
            $candidates[] = $candidate;

        }
        return $candidates;
    }

    /**
     * @param Additions\ContainerCollocation $collocation_candidate
     */
    protected function fillCollocationCandidateByCollocationSlovo(
        \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation $collocation_candidate
    )
    {
        /** @var \Aot\Graph\Slovo\Vertex[] $vertices */
        $vertices = array_values($collocation_candidate->getVerticesOfCollocation());
        /** @var \Aot\Graph\Slovo\Vertex $main_vertex */
        $main_vertex = $vertices[$collocation_candidate->getPositionOfMainWord() - 1];
        $main_slovo = $main_vertex->getSlovo();
        $slovo = clone($main_slovo);
        $slovo->initial_form = $collocation_candidate->getCollocationInitialForm();
        $texts = [];
        foreach ($vertices as $vertex) {
            $texts[] = $vertex->getSlovo()->getText();
        }
        $slovo->text = join(' ', $texts);
        $collocation_candidate->setCollocationSlovo($slovo);
    }

    /**
     * @param WordsCollocationAPI\API $api
     */
    public function setApi(
        \Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\WordsCollocationAPI\API $api
    )
    {
        $this->api = $api;
    }

}