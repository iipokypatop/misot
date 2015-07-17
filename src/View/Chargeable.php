<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 16.07.2015
 * Time: 22:50
 */

namespace Aot\View;


interface Chargeable
{
    const OBJECT_CONTAINER = self::class;
    const NEW_ID_PREFIX = self::class;

    /**
     * @param array $data
     * @return void
     */
    public function chargeData(array $data);

    /***
     * @param int $id
     * @return void
     */
    public function chargeId($id);
}

