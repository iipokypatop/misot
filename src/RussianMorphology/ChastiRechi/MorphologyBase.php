<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 14:50
 */

namespace Aot\RussianMorphology\ChastiRechi;

abstract class MorphologyBase
{
    public function getName()
    {
        $morph = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantsLvl2();

        if (empty($morph [get_class($this)])) {
            return null;
        }

        $id = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantsLvl2()[get_class($this)];

        if (!empty(\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getNames()[$id])) {
            return \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getNames()[$id];
        }

        return null;
    }


    public function getGroupId()
    {
        $result = null;

        $morph = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantsLvl2();

        if (empty($morph[get_class($this)])) {
            return null;
        }

        $id = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantsLvl2()[get_class($this)];

        if (!empty(\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getNames()[$id])) {
            return \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getNames()[$id];
        }


        return;
    }
}