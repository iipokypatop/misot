<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 29.07.2015
 * Time: 14:28
 */

namespace Aot\Sviaz\Podchinitrelnaya;


use Aot\Registry\Uploader;

class Registry
{
    use Uploader;

    const SOGLASOVANIE = 1;
    const UPRAVLENIE = 2;
    const PRIMIKANIE = 3;

    public static function getClasses()
    {
        return [
            static::SOGLASOVANIE => \Aot\Sviaz\Podchinitrelnaya\Soglasovanie::class,
            static::UPRAVLENIE => \Aot\Sviaz\Podchinitrelnaya\Upravlenie::class,
            static::PRIMIKANIE => \Aot\Sviaz\Podchinitrelnaya\Primikanie::class,
        ];
    }


    public static function getNames()
    {
        return [
            static::SOGLASOVANIE => "Согласование",
            static::UPRAVLENIE => "Управление",
            static::PRIMIKANIE => "Примыкание",
        ];
    }

    /**
     * @param string $link_class
     * @return int|null
     */
    public static function getIdLinkByClass($link_class)
    {
        foreach (static::getClasses() as $id => $class) {
            if ($link_class === $class) {
                return $id;
            }
        }
        return null;

    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \SemanticPersistence\Entities\MisotEntities\TypeLink::class;
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