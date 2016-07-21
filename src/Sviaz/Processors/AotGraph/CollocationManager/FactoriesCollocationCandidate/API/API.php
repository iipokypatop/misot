<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21.07.2016
 * Time: 13:08
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\API;


class API
{
    // TODO заполнение данных полей не желательно при каждом запуске, хорошо бы положить всё это дело в кэш

    /** @var \Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\API\API */
    protected static $instance;
    /** @var int[][] Для поиска id всех словосочетаний по начальной форме первого слова словосочетания */
    protected $map_collocation_ids_by_first_initial_form = [];
    /** @var string[] Для поиска литературной начальной формы для словосочетания по его id */
    protected $map_collocation_initial_form_by_collocation_id = [];
    /** @var string[][] Для поиска всех начальных форм слов, входящих в словосочетание по id словосочетания */
    protected $map_initial_forms_by_collocation_id = [];
    /** @var int[] Для поиска позиции главного слова в словосочетании */
    protected $map_main_position_by_collocation_id = [];

    /**
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\API\API
     */
    public static function get()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->map_collocation_ids_by_first_initial_form = $this->fillMapCollocationIdsByFirstInitialForm();
        $this->map_collocation_initial_form_by_collocation_id = $this->fillMapCollocationInitialFormByCollocationId();
        $this->map_initial_forms_by_collocation_id = $this->fillMapInitialFormsByCollocationId();
        $this->map_main_position_by_collocation_id = $this->fillMapMainPositionByCollocationId();
    }

    /**
     * @return int[][]
     */
    protected function fillMapCollocationIdsByFirstInitialForm()
    {
        $api = \TextPersistence\API\TextAPI::getAPI();
        $em = $api->getEntityManager();
        $sql = RegistrySQL::getSqlMapCollocationIdsByFirstInitialForm();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $map = [];
        foreach ($result as $item) {
            $raw_ids = $item[RegistrySQL::COLLOCATION_IDS];
            $ids = $this->convertStringToArray($raw_ids);
            array_walk($ids, function (&$el) {
                $el = (int)$el;
            });
            $initial_form_of_first_word = $item[RegistrySQL::INITIAL_FORM_OF_FIRST_WORD];
            $map[$initial_form_of_first_word] = $ids;
        }
        return $map;
    }

    /**
     * @return string[]
     */
    protected function fillMapCollocationInitialFormByCollocationId()
    {
        $api = \TextPersistence\API\TextAPI::getAPI();
        $em = $api->getEntityManager();
        $sql = RegistrySQL::getSqlMapCollocationInitialFormByCollocationId();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $map = [];
        foreach ($result as $item) {
            $id = $item[RegistrySQL::COLLOCATION_ID];
            $map[$id] = $item[RegistrySQL::COLLOCATION_INITIAL_FORM];
        }
        return $map;
    }

    /**
     * @return string[][]
     */
    protected function fillMapInitialFormsByCollocationId()
    {
        $api = \TextPersistence\API\TextAPI::getAPI();
        $em = $api->getEntityManager();
        $sql = RegistrySQL::getSqlMapInitialFormsByCollocationId();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $map = [];
        foreach ($result as $item) {
            $id = $item[RegistrySQL::COLLOCATION_ID];
            $initial_forms_raw = $item[RegistrySQL::INITIAL_FORMS];
            $initial_forms = $this->convertStringToArray($initial_forms_raw);
            $map[$id] = $initial_forms;
        }
        return $map;
    }

    /**
     * @return int[]
     */
    protected function fillMapMainPositionByCollocationId()
    {
        $api = \TextPersistence\API\TextAPI::getAPI();
        $em = $api->getEntityManager();
        $sql = RegistrySQL::getSqlMapMainPositionByCollocationId();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $map = [];
        foreach ($result as $item) {
            $id = $item[RegistrySQL::COLLOCATION_ID];
            $map[$id] = $item[RegistrySQL::POSITION_MAIN_WORD];
        }
        return $map;
    }


    /**
     * @param string $string
     * @return string[]
     */
    protected function convertStringToArray($string)
    {
        $raw_ids = preg_replace('#[\{\}]+#ui', '', $string);
        $array = preg_split('#,#ui', $raw_ids);
        return $array;
    }

    /**
     * @return int[][]
     */
    public function getMapCollocationIdsByFirstInitialForm()
    {
        return $this->map_collocation_ids_by_first_initial_form;
    }

    /**
     * @return string[]
     */
    public function getMapCollocationInitialFormByCollocationId()
    {
        return $this->map_collocation_initial_form_by_collocation_id;
    }

    /**
     * @return string[][]
     */
    public function getMapInitialFormsByCollocationId()
    {
        return $this->map_initial_forms_by_collocation_id;
    }

    /**
     * @return int[]
     */
    public function getMapMainPositionByCollocationId()
    {
        return $this->map_main_position_by_collocation_id;
    }


}