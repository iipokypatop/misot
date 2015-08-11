<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/08/15
 * Time: 20:02
 */

namespace Aot\Sviaz\Rule\AssertedMember;


use Aot\Registry\Uploader;

class PresenceRegistry
{
    use Uploader;

    const PRESENCE_PRESENT = 1;
    const PRESENCE_NOT_PRESENT = 2;

    public static function getNames()
    {
        return [
            static::PRESENCE_PRESENT => 'присутствует',
            static::PRESENCE_NOT_PRESENT => 'не присутствует',
        ];
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \AotPersistence\Entities\Presence::class;
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
        return[
            'name' => [static::class, 'getNames'],
        ];
    }
}