<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.07.2015
 * Time: 17:53
 */

namespace AotTest\Functional\RussianMorphology;


use MivarTest\PHPUnitHelper;

class Factory2Test extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        if (0) {

            echo "ChastiRechiRegistry", "\n";
            $a = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::new_old();
            asort($a);
            var_export($a);
            echo "\n";
            var_export(array_count_values($a));

            // части речи
            $table = "transf_class";
            $dump1 = [];
            foreach ($a as $k => $v) {
                $dump1[] = "INSERT INTO {$table} (id_word_class, id_new) VALUES ({$v}, {$k});";
            }
            echo "\n";

            echo "MorphologyRegistry", "\n";
            $a = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::new_old();
            asort($a);
            var_export($a);
            echo "\n";
            var_export(array_count_values($a));

            $table = "transf_attr";
            $dump2 = [];
            foreach ($a as $k => $v) {
                $dump2[] = "INSERT INTO {$table} (id_morph_attr, id_new) VALUES ({$v}, {$k});";
            }
            echo "\n";


            echo "MorphologyRegistryParent", "\n";
            $a = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistryParent::new_old();
            asort($a);
            echo "\n";
            var_export($a);
            echo "\n";
            var_export(array_count_values($a));

            $table = "transf_val";
            $dump3 = [];
            foreach ($a as $k => $v) {
                $dump3[] = "INSERT INTO {$table} (id_value_attr, id_new) VALUES ({$v}, {$k});";
            }

            echo join("\n", $dump1);
            echo "\n";
            echo "\n";

            echo join("\n", $dump2);
            echo "\n";
            echo "\n";

            echo join("\n", $dump3);
            echo "\n";
            echo "\n";
        }
    }

    public function dpBuildFromEntity()
    {
        return [
            [
                \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class,
                'any_word_text',
                [1001, 2002, 3001, 4001, 5001, 19001, 20001, 25001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SUSCHESTVITELNOE
            ],
            [
                \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class,
                'any_word_text',
                [3001, 10001, 1001, 24001, 2001, 11001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRILAGATELNOE
            ], [
                \Aot\RussianMorphology\ChastiRechi\Glagol\Base::class,
                'any_word_text',
                [3001, 18001, 16001, 6001, 2001, 15001, 12001, 13001, 17001, 14001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::GLAGOL
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Narechie\Base::class,
                'any_word_text',
                [11001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::NARECHIE
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Prichastie\Base::class,
                'any_word_text',
                [3001, 10001, 1001, 6001, 2001, 12001, 13001, 17001, 14001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRICHASTIE
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base::class,
                'any_word_text',
                [12001, 6001, 13001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::DEEPRICHASTIE
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class,
                'any_word_text',
                [
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::VID_CHISLITELNOGO_KOLICHESTVENNIY,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::TIP_CELIY,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::PODVID_CHISLITELNOGO_PROSTOY,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::CHISLO_EDINSTVENNOE,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::ROD_MUZHSKOI,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::PADESZH_IMENITELNIJ

                ],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHISLITELNOE
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base::class,
                'any_word_text',
                [
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::CHISLO_EDINSTVENNOE,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::LITSO_PERVOE,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::PADESZH_IMENITELNIJ,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::RAZRYAD_MESTOIMENIE_OTNOSITELNOE,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::ROD_MUZHSKOI
                ],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MESTOIMENIE
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Infinitive\Base::class,
                'any_word_text',
                [
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::PEREHODNOST_PEREHODNII,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::VID_SOVERSHENNYJ,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::VOZVRATNOST_VOZVRATNYJ
                ],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::INFINITIVE
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Predlog\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PREDLOG
            ]

            , [
                \Aot\RussianMorphology\ChastiRechi\Soyuz\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SOYUZ
            ]

            , [
                \Aot\RussianMorphology\ChastiRechi\Chastica\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHASTICA
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Mezhdometie\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MEZHDOMETIE
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Pristavka\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRISTAVKA
            ]
        ];
    }


    /**
     * @dataProvider dpBuildFromEntity
     * @param string $expected_class
     * @param string $word_text
     * @param int[] $ids
     * @param int $word_class_id
     */
    public function testBuildFromEntity($expected_class, $word_text, $ids, $word_class_id)
    {
        $factory = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Factory::get();

        $form_entity = new \TextPersistence\Entities\TextEntities\Form;
        $mword_entity = new \TextPersistence\Entities\TextEntities\Mword();
        $word_class_entity = new \TextPersistence\Entities\TextEntities\WordClass();

        $form_entity->setMword($mword_entity);
        $form_entity->setWordClass($word_class_entity);

        // слово
        $mword_entity->setWord($word_text);

        // values
        $form_entity->setValuesAgg($ids);

        // word_class_id
        PHPUnitHelper::setProtectedProperty($word_class_entity, 'id', $word_class_id);

        $ob = $factory->buildFromEntity($form_entity);

        $this->assertEquals($expected_class, get_class($ob));
        $this->assertEquals($word_text, $ob->getText());

        foreach ($ob->getMorphologyStorage() as $morphology) {
            $this->assertNotInstanceOf(\Aot\RussianMorphology\MorphologyNull::class, $morphology);
        }
    }

    public function testGetNullClassByBaseClass()
    {
        $result = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistryParent::getNullClassByBaseClass(
            \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Base::class
        );

        $this->assertEquals(
            \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Null::class,
            $result
        );
    }


    public function testGetSlova2()
    {
        $this->markTestSkipped("ждем новые константы в новом морфике ");

        $words = [
            'безостановочнее',
            'безответно'
        ];

        $res = \Aot\RussianMorphology\Factory::getSlova2($words);
    }
}