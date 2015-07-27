<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 23.07.2015
 * Time: 17:02
 */

namespace Aot\Sviaz\Rule\AssertedLink\Builder;


use Aot\View\Chargeable;

class View extends \Aot\View\Base implements Chargeable
{
    const FIELD_SOGLASOVANIE = '100';

    public static function create()
    {
        return new static();
    }

    /**
     * @param array $data
     * @return void
     */
    public function chargeData(array $data)
    {

    }

    /***
     * @param int $id
     * @return void
     */
    public function chargeId($id)
    {
        // TODO: Implement chargeId() method.
    }
}