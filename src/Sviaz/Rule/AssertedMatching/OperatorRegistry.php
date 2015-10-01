<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:19
 */

namespace Aot\Sviaz\Rule\AssertedMatching;

use Aot\Registry\Uploader;

class OperatorRegistry
{
    use Uploader;

    const EQUAL = 1;


    public static function getNames()
    {
        return [
            static::EQUAL => 'эквивалентно',
        ];
    }

    public static function getClasses()
    {
        return [
            static::EQUAL => \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::class,
        ];
    }

    /**
     * @param \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Base $object
     * @return int|null
     */
    public static function getIdByObject($object)
    {
        foreach (static::getClasses() as $id => $class) {
            if (is_a($object, $class)) {
                return $id;
            }
        }
        return null;

    }

    /**
     * @param int $id
     * @param array $args
     * @return \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Base
     */
    public static function getObjectById($id, array $args = [])
    {
        assert(is_int($id));

        if (empty(static::getClasses()[$id])) {
            throw new \RuntimeException("unsupported id = " . var_export($id, 1));
        }

        return forward_static_call_array([static::getClasses()[$id], 'create'], $args);
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \SemanticPersistence\Entities\MisotEntities\Operator::class;
    }

    /**
     * @return int[]
     */
    protected function getIds()
    {
        return array_keys(static::getNames());
    }

    /**
     * @return string[]
     */
    protected function getFields()
    {
        return [
            'name' => [static::class, 'getNames'],
        ];
    }
}