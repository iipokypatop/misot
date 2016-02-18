<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 30/11/15
 * Time: 19:48
 */

namespace Aot\RussianMorphology;

class FactoryFromEntity
{
    protected static $uniqueInstances = null;

    /** @var array[][] */
    public $error_log = [];
    public $timer1 = 0;
    /** @var string[] */
    protected $__cache_chast_rechi_class = [];
    /** @var bool[] */
    protected $__cache_is_a = [];
    /** @var string[][] */
    protected $__cache_priznak_class = [];
    /** @var string */
    protected $__cache_null_class = [];
    /** @var \Aot\RussianMorphology\ChastiRechi\MorphologyBase[] */
    protected $__cache_priznak_4_clone = [];
    /** @var \Aot\RussianMorphology\ChastiRechi\MorphologyBase[] */
    protected $__cache_priznak_null_4_clone = [];
    /** @var string[] */
    protected $__cache_priznaki = [];
    /** @var \Aot\RussianMorphology\Slovo[] */
    protected $__cache_chast_rechi_4_clone = [];
    /** @var int[] */
    protected $__cache_base_class_id = [];

    /**
     * FactoryBase constructor.
     */
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
    public static function get()
    {
        if (empty(static::$uniqueInstances[static::class])) {
            static::$uniqueInstances[static::class] = new static;
        }

        return static::$uniqueInstances[static::class];
    }

    /**
     * @param \TextPersistence\Entities\TextEntities\Form $wordForm
     * @return \Aot\RussianMorphology\Slovo
     */
    public function buildFromEntity(\TextPersistence\Entities\TextEntities\Form $wordForm)
    {
        $text = $wordForm->getMword()->getWord();
        $initial_form = $wordForm->getInitialForm()->getWord();
        $ids = $wordForm->getValuesAgg();
        $chast_rechi_id = $wordForm->getWordClass()->getId();

        $chast_rechi_class = $this->__cache_chast_rechi_class[$chast_rechi_id];

        /** @var string[] $priznaki */
        $priznaki = $this->__cache_priznaki[$chast_rechi_class];

        $priznaki_objects = [];
        foreach ($priznaki as $priznak_name => &$priznak_base_class) {

            foreach ($ids as $id_string) {
                $id = (int)$id_string;

                if (!isset($this->__cache_priznak_class[$chast_rechi_id][$id])) {
                    $this->error_log[] = [
                        'id' => $wordForm->getId(),
                        'chast_rechi_id' => $chast_rechi_id,
                        'priznak_value_id' => $id
                    ];
                    /*throw new \LogicException(
                        "chast rechi id = $chast_rechi_id doesn't support priznak value id =  $id"
                    );*/
                    return null;
                }

                $priznak_class = $this->__cache_priznak_class[$chast_rechi_id][$id];

                if ($this->__cache_is_a[$priznak_base_class][$priznak_class]) {
                    $priznaki_objects[$priznak_name] = clone $this->__cache_priznak_4_clone[$priznak_class];
                }
            }

            if (empty($priznaki_objects[$priznak_name])) {
                $priznaki_objects[$priznak_name] = clone $this->__cache_priznak_null_4_clone[$priznak_base_class];
            }
        }


        $ob = clone $this->__cache_chast_rechi_4_clone[$chast_rechi_class];
        $ob->storage = $priznaki_objects;
        $ob->text = $text;
        $ob->initial_form = $initial_form;

        return $ob;
    }
}