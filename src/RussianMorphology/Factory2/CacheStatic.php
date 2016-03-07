<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 006, 06, 03, 2016
 * Time: 15:24
 */

namespace Aot\RussianMorphology\Factory2;


class CacheStatic
{
    /** @var string[] */
    public $__cache_chast_rechi_class = [];
    /** @var bool[] */
    public $__cache_is_a = [];
    /** @var string[][] */
    public $__cache_priznak_class = [];
    /** @var string */
    public $__cache_null_class = [];
    /** @var \Aot\RussianMorphology\ChastiRechi\MorphologyBase[] */
    public $__cache_priznak_4_clone = [];
    /** @var \Aot\RussianMorphology\ChastiRechi\MorphologyBase[] */
    public $__cache_priznak_null_4_clone = [];
    /** @var string[] */
    public $__cache_priznaki = [];
    /** @var \Aot\RussianMorphology\Slovo[] */
    public $__cache_chast_rechi_4_clone = [];
    /** @var int[] */
    public $__cache_base_class_id = [];


    protected function __construct()
    {
        $this->__cache_chast_rechi_class = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getClasses();

        $this->__cache_priznaki = [];
        foreach (\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getClasses() as $chast_rechi_class) {
            $this->__cache_priznaki[$chast_rechi_class] = forward_static_call([$chast_rechi_class, 'getMorphology']);
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getLvl2() as $id) {
            foreach (\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getClasses() as $chast_rechi_id => $class) {
                $res = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getClassByChastRechiAndPriznak(
                    $chast_rechi_id,
                    $id
                );
                $this->__cache_priznak_class[$chast_rechi_id][$id] = $res;
            };
        }
        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantsLvl2() as $priznak_class => $id) {
            foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getBaseClasses() as $variants) {
                foreach ($variants as $priznak_base_class) {
                    $this->__cache_is_a[$priznak_base_class][$priznak_class] =
                        is_a($priznak_class, $priznak_base_class, true);
                }
            }
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getBaseClasses() as $variants) {
            foreach ($variants as $priznak_base_class) {

                $null = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistryParent::getNullClassByBaseClass(
                    $priznak_base_class
                );

                $this->__cache_priznak_null_4_clone[$priznak_base_class] = new $null;
            }
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getClasses() as $class) {
            $reflector = new \ReflectionClass($class);
            $this->__cache_chast_rechi_4_clone[$class] = $reflector->newInstanceWithoutConstructor();
            // todo сделать storage, text, initial form Доступными через рефлесию, и убрать у них публичность
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantsLvl2() as $priznak_class => $id) {
            $this->__cache_priznak_4_clone[$priznak_class] = new $priznak_class;
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getBaseClasses() as $base_id => $variants) {
            foreach ($variants as $chas_rechi_id => $priznak_base_class) {
                $base[$priznak_base_class] = $base_id;
            }
        }
    }


    /**
     * @return static
     */
    public static function create()
    {
        $ob = new static;

        return $ob;
    }
}