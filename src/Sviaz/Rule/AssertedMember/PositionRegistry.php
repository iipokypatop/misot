<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/08/15
 * Time: 20:02
 */

namespace Aot\Sviaz\Rule\AssertedMember;


use Aot\Registry\Uploader;

class PositionRegistry
{
    use Uploader;

    const POSITION_ANY = 1;
    const POSITION_BETWEEN_MAIN_AND_DEPENDED = 2;
    const POSITION_AFTER_MAIN = 3;
    const POSITION_BEFORE_MAIN = 4;
    const POSITION_AFTER_DEPENDED = 5;
    const POSITION_BEFORE_DEPENDED = 6;

    public static function getNames()
    {
        return [
            static::POSITION_ANY => 'позиция любая',
            static::POSITION_BETWEEN_MAIN_AND_DEPENDED => 'позиция между главным и зависимым',
            static::POSITION_AFTER_MAIN => 'позиция после главного',
            static::POSITION_BEFORE_MAIN => 'позиция перед главным',
            static::POSITION_AFTER_DEPENDED => 'позиция после зависимого',
            static::POSITION_BEFORE_DEPENDED => 'позиция перед зависимым',
        ];
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \SemanticPersistence\Entities\MisotEntities\Position::class;
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