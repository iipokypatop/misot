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


    public function dpBuildFromEntity()
    {
        return [
            [
                \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class,
                'any_word_text',
                [1001, 2002, 3001, 4001, 5001, 19001, 20001, 25001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SUSCHESTVITELNOE,
                'initial_form',
            ],
            [
                \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class,
                'any_word_text',
                [3001, 10001, 1001, 24001, 2001, 11001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRILAGATELNOE,
                'initial_form',
            ], [
                \Aot\RussianMorphology\ChastiRechi\Glagol\Base::class,
                'any_word_text',
                [3001, 18001, 16001, 6001, 2001, 15001, 12001, 13001, 17001, 14001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::GLAGOL,
                'initial_form',
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Narechie\Base::class,
                'any_word_text',
                [11001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::NARECHIE,
                'initial_form',
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Prichastie\Base::class,
                'any_word_text',
                [3001, 10001, 1001, 6001, 2001, 12001, 13001, 17001, 14001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRICHASTIE,
                'initial_form',
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base::class,
                'any_word_text',
                [12001, 6001, 13001],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::DEEPRICHASTIE,
                'initial_form',
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class,
                'any_word_text',
                [
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::VID_CHISLITELNOGO_KOLICHESTVENNIY,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::TIP_CHISLITELNOGO_CELIY,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::PODVID_CHISLITELNOGO_PROSTOY,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::CHISLO_EDINSTVENNOE,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::ROD_MUZHSKOI,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::PADESZH_IMENITELNIJ

                ],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHISLITELNOE,
                'initial_form',
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
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MESTOIMENIE,
                'initial_form',
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Infinitive\Base::class,
                'any_word_text',
                [
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::PEREHODNOST_PEREHODNII,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::VID_SOVERSHENNYJ,
                    \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::VOZVRATNOST_VOZVRATNYJ
                ],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::INFINITIVE,
                'initial_form',
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Predlog\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PREDLOG,
                'initial_form',
            ]

            , [
                \Aot\RussianMorphology\ChastiRechi\Soyuz\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SOYUZ,
                'initial_form',
            ]

            , [
                \Aot\RussianMorphology\ChastiRechi\Chastica\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHASTICA,
                'initial_form',
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Mezhdometie\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MEZHDOMETIE,
                'initial_form',
            ]
            , [
                \Aot\RussianMorphology\ChastiRechi\Pristavka\Base::class,
                'any_word_text',
                [],
                \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRISTAVKA,
                'initial_form',
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
    public function testBuildFromEntity($expected_class, $word_text, $ids, $word_class_id, $initial_form)
    {
        $factory = \Aot\RussianMorphology\FactoryFromEntity::get();


        $form_entity = new \TextPersistence\Entities\TextEntities\Form;
        $mword_entity = new \TextPersistence\Entities\TextEntities\Mword();
        $mword_initial_form_entity = new \TextPersistence\Entities\TextEntities\Mword();
        $word_class_entity = new \TextPersistence\Entities\TextEntities\WordClass();

        $form_entity->setMword($mword_entity);
        $form_entity->setWordClass($word_class_entity);
        $form_entity->setInitialForm($mword_initial_form_entity);

        // слово
        $mword_entity->setWord($word_text);

        // начальная форма
        $mword_initial_form_entity->setWord($initial_form);

        // values
        $form_entity->setValuesAgg($ids);

        // word_class_id
        PHPUnitHelper::setProtectedProperty($word_class_entity, 'id', $word_class_id);

        $ob = $factory->buildFromEntity($form_entity);

        $this->assertEquals($expected_class, get_class($ob));
        $this->assertEquals($word_text, $ob->getText());
        $this->assertEquals($initial_form, $ob->getInitialForm());

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

    /**
     * @dataProvider words_dp
     * @throws \Exception
     */
    public function testGetSlova2($word_text)

    {
        /** @var \TextPersistence\API\APIcurrent $api */
        $api = \TextPersistence\API\TextAPI::getAPI();

        /** @var \TextPersistence\Entities\TextEntities\Mword[] $mwords */
        $mwords = $api->findBy(
            \TextPersistence\Entities\TextEntities\Mword::class,
            [
                'word' => $word_text
            ]
        );

        if (!empty($mwords)) {

            $slova_group = \Aot\RussianMorphology\Factory::getSlova2([$word_text]);

            $this->assertNotEmpty($slova_group);

            foreach ($slova_group as $slova) {
                foreach ($slova as $slovo) {
                    $this->assertInstanceOf(\Aot\RussianMorphology\Slovo::class, $slovo);
                }
            }
        }
    }

    public function words_dp()
    {
        return [
            ['безостановочнее'],
            ['безответно'],
            ['леса'],
        ];
    }

    public function testWordDuplicates()
    {
        $word = [
            'лес',
            'дом',
            'лес',
            'лес',
        ];
        try {

            \Aot\RussianMorphology\Factory::getSlova2($word);

            $this->fail();

        } catch (\Aot\RussianMorphology\DuplicateException $e) {
        }
    }

    public function testGetSlova2ReturnsNothing()
    {
        $not_existing_word_form_in_db = static::class;

        $slova_group = \Aot\RussianMorphology\Factory::getSlova2([$not_existing_word_form_in_db]);

        $this->assertEmpty($slova_group[$not_existing_word_form_in_db]);
    }
}