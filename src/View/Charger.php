<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 16.07.2015
 * Time: 17:06
 */

namespace Aot\View;

class Charger
{
    protected $counter = 0;
    protected $class_names = [];
    protected $class_ids = [];
    protected $temporary_ids = [];

    public function __construct()
    {

    }

    public static function charge($POST)
    {
        //var_export($POST);

        if (empty($POST[Chargeable::OBJECT_CONTAINER])) {
            return;
        }

        /** @var \Aot\View\Chargeable[] $objects */
        $objects = [];

        foreach ($POST[Chargeable::OBJECT_CONTAINER] as $className => $ids) {

            foreach ($ids as $id => $data) {
                /** @var \Aot\View\Chargeable $ob */
                $objects[] = $ob = forward_static_call([$className, 'create']);

                if (!isset($POST[Chargeable::OBJECT_CONTAINER][$className][$id])) {
                    continue;
                }

                $data = $POST[Chargeable::OBJECT_CONTAINER][$className][$id];

                $ob->chargeData((array)$data);

                if (strstr(Chargeable::NEW_ID_PREFIX, $id) !== false) {
                    $ob->chargeId(intval($id));
                }
            }
        }
        //var_export($objects);
    }
}