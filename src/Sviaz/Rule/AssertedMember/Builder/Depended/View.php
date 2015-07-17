<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.07.2015
 * Time: 13:04
 */

namespace Aot\Sviaz\Rule\AssertedMember\Builder\Depended;


class View extends \Aot\Sviaz\Rule\AssertedMember\Builder\View
{
    /**
     * @param int $chast_rechi_id
     * @param int $role_id
     * @return Base
     */
    protected function createBuilder($chast_rechi_id, $role_id)
    {
        assert(is_int($chast_rechi_id));
        assert(is_int($role_id));

        return Base::create(
            $chast_rechi_id,
            $role_id
        );
    }
}

