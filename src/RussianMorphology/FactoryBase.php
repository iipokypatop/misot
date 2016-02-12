<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 30/11/15
 * Time: 19:48
 */

namespace Aot\RussianMorphology;


abstract class FactoryBase
{

    protected static $uniqueInstances = null;

    protected function __construct()
    {
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

    abstract public function build(\DictionaryWord $dw);

    /**
     * @param \TextPersistence\Entities\TextEntities\Form $wordForm
     * @return \Aot\RussianMorphology\Slovo
     */
    public static function buildFromEntity(\TextPersistence\Entities\TextEntities\Form $wordForm)
    {
        $text = $wordForm->getMword()->getWord();
        $ids = $wordForm->getValuesAgg();
        $chast_rechi_id = $wordForm->getWordClass()->getId();


        $chast_rechi_class = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getClasses()[$chast_rechi_id];

        $priznaki = forward_static_call([$chast_rechi_class, 'getMorphology']);

        $arguments = [];
        foreach ($priznaki as $priznak_name => $priznak_base_class) {
            foreach ($ids as $id) {
                $priznak_class = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getClassByChastRechiAndPriznak(
                    $chast_rechi_id,
                    $id
                );
                if (is_null($priznak_class)) {

                    throw new \LogicException(
                        "chast rechi id = $chast_rechi_id doesn't support priznak value id =  $id"
                    );
                }
                if (is_a($priznak_class, $priznak_base_class, true)) {
                    $arguments[$priznak_name] = forward_static_call([$priznak_class, 'create']);
                }
            }
            if (!isset($arguments[$priznak_name])) {
                $null_class = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistryParent::getNullClassByBaseClass(
                    $priznak_base_class
                );
                $arguments[$priznak_name] = forward_static_call([$null_class, 'create']);
            }
        }

        array_unshift($arguments, $text);

        /**
         * @var \Aot\RussianMorphology\Slovo
         */
        $ob = forward_static_call_array(
            [$chast_rechi_class, 'create'],
            $arguments
        );

        return $ob;
    }
}