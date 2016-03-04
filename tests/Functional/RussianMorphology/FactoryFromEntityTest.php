<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.07.2015
 * Time: 17:53
 */

namespace AotTest\Functional\RussianMorphology;


use Aot\RussianMorphology\NullWord;
use MivarTest\PHPUnitHelper;

class FactoryFromEntityTest extends \AotTest\AotDataStorage
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
            \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\ClassNull::class,
            $result
        );
    }

    public function words_dp()
    {
        return [
            ['безостановочнее'],
            ['безответно'],
            ['леса'],

        ];
    }

    /**
     * @dataProvider words_dp
     * @throws \Exception
     */
    public function testGetSlova2($word_text)
    {
        $mwords = $this->getWordsFromDb($word_text);

        if (!empty($mwords)) {

            $slova_group = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova([$word_text]);

            $this->assertNotEmpty($slova_group);

            foreach ($slova_group as $slova) {
                foreach ($slova as $slovo) {
                    $this->assertInstanceOf(\Aot\RussianMorphology\Slovo::class, $slovo);
                }
            }
        }
    }

    /**
     * @param $words
     * @return bool
     * @throws \Exception
     */
    protected function getWordsFromDb($words)
    {
        assert(is_string($words) || is_array($words));

        if (is_array($words)) {
            foreach ($words as $word) {
                assert(is_string($words));
            }
        }

        /** @var \TextPersistence\API\APIcurrent $api */
        $api = \TextPersistence\API\TextAPI::getAPI();

        $word_enitities = $api->findBy(
            \TextPersistence\Entities\TextEntities\Mword::class,
            [
                'word' => $words
            ]
        );

        return $word_enitities;
    }

    public function testCompositeWords()
    {
        $part1 = 'пошли';
        $part2 = 'леса';


        $word1 = $this->getWordsFromDb($part1);
        if (empty($word1)) {
            $this->markTestSkipped("в базе данных нет слова " . var_export($part1, 1));
        }

        $word2 = $this->getWordsFromDb($part2);
        if (empty($word2)) {
            $this->markTestSkipped("в базе данных нет слова " . var_export($word2, 1));
        }

        $composite_word = $part1 . "-" . $part2;

        $slova_group = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova([$composite_word]);

        $this->assertNotEmpty($slova_group);

        foreach ($slova_group as $slova) {
            foreach ($slova as $slovo) {
                $this->assertInstanceOf(\Aot\RussianMorphology\Slovo::class, $slovo);
            }
        }
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

            \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova($word);

            $this->fail();

        } catch (\Aot\RussianMorphology\DuplicateException $e) {

        }
    }

    public function testGetSlova2ReturnsNothing()
    {
        $not_existing_word_form_in_db = static::class;

        $slova_group = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova([$not_existing_word_form_in_db]);

        $this->assertEmpty($slova_group[$not_existing_word_form_in_db]);
    }

    public function testGetSlovaWithPunctuation()
    {
        $units = [
            'каприза',
            '.',
            'пошла',
            '!',
            'в',
            '?',
            'магазин',
            ';',
            'купить',

        ];

        $units_groups = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlovaWithPunctuation($units);

        foreach ($units_groups as $units_group) {
            foreach ($units_group as $unit) {
                $this->assertInstanceOf(\Aot\Unit::class, $unit);
            }
        }
    }

    public function testGetSlovaWithPunctuation2()
    {
        $units = [
            '(',
            ')',
            ':',
            '-',
            ';',
            '...',
            '?',
            '!',
            ',',
            '.',
        ];

        $units_groups = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlovaWithPunctuation($units);

        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\Skobki\Otkrivauchaya::class, $units_groups[0][0]);
        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\Skobki\Zakrivauchaya::class, $units_groups[1][0]);
        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\Dvoetochie::class, $units_groups[2][0]);
        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\Tire::class, $units_groups[3][0]);
        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\TochkaSZapiatoj::class, $units_groups[4][0]);
        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\Troetochie::class, $units_groups[5][0]);
        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\VoprositelnijZnak::class, $units_groups[6][0]);
        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\VosklicatelnijZnak::class, $units_groups[7][0]);
        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\Zapiataya::class, $units_groups[8][0]);
        $this->assertInstanceOf(\Aot\RussianSyntacsis\Punctuaciya\Tochka::class, $units_groups[9][0]);
    }

    public function testCaseSensitive()
    {
        $word = 'пошли';
        $Word = 'Пошли';

        $word1 = $this->getWordsFromDb($word);

        if (empty($word1)) {
            $this->markTestSkipped("в базе данных нет слова " . var_export($word, 1));
        }

        $slova_group = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova(
            [$word, $Word],
            \Aot\RussianMorphology\FactoryFromEntity::SEARCH_MODE_CASE_SENSITIVE
        );

        $this->assertNotEmpty($slova_group);

        $this->assertNotEmpty($slova_group[$word]);
    }

    public function testCaseSensitive2()
    {
        $word = 'ваня';
        $Word = 'Ваня';

        $word1 = $this->getWordsFromDb($Word);

        if (empty($word1)) {
            $this->markTestSkipped("в базе данных нет слова " . var_export($Word, 1));
        }

        $slova_group = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova(
            [$word, $Word],
            \Aot\RussianMorphology\FactoryFromEntity::SEARCH_MODE_CASE_SENSITIVE
        );

        $this->assertNotEmpty($slova_group);

        $this->assertNotEmpty($slova_group[$Word]);
    }

    public function testCaseSensitive3()
    {
        $Word = 'Ваня';

        try {
            \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova(
                [$Word, $Word],
                \Aot\RussianMorphology\FactoryFromEntity::SEARCH_MODE_CASE_SENSITIVE
            );
            $this->fail();
        } catch (\Aot\RussianMorphology\DuplicateException $e) {

        }
    }

    public function testByInitialForm()
    {
        $word1 = 'дом';
        $word2 = 'лететь';


        $e1 = $this->getWordsFromDb($word1);
        if (empty($e1)) {
            $this->markTestSkipped("в базе данных нет слова " . var_export($word1, 1));
        }

        $e2 = $this->getWordsFromDb($word2);
        if (empty($e2)) {
            $this->markTestSkipped("в базе данных нет слова " . var_export($word2, 1));
        }

        $slova_group = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova(
            [$word1, $word2],
            \Aot\RussianMorphology\FactoryFromEntity::SEARCH_MODE_BY_INITIAL_FORM
        );

        $this->assertNotEmpty($slova_group);

        $this->assertNotEmpty($slova_group[$word1]);
        $this->assertNotEmpty($slova_group[$word2]);
    }

    public function testSearchModeAddNullWords()
    {
        $not_existing_word_form_in_db = static::class;

        $slova_group = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova(
            [$not_existing_word_form_in_db],
            \Aot\RussianMorphology\FactoryFromEntity::SEARCH_MODE_ADD_NULL_WORDS
        );

        $this->assertInstanceOf(
            NullWord::class,
            $slova_group[static::class][0]
        );
    }

    public function testSearchModeUsePredictor()
    {
        $not_existing_word_form_in_db = 'фвфывдом';

        $slova_group = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova(
            [$not_existing_word_form_in_db]
        // without prediction
        );

        $this->assertNotEmpty(
            $slova_group[$not_existing_word_form_in_db]
        );

        $slova_group = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlova(
            [$not_existing_word_form_in_db],
            \Aot\RussianMorphology\FactoryFromEntity::SEARCH_MODE_NOT_USE_PREDICTOR
        );

        $this->assertEmpty(
            $slova_group[$not_existing_word_form_in_db]
        );
    }

    public function testGetNonUniqueWords()
    {
        $word = 'корова';
        $Word = 'Корова';
        $non_unique_words = [
            $word,
            'машина',
            $word,
            $Word
        ];

        $e = $this->getWordsFromDb($word);
        if (empty($e)) {
            $this->markTestSkipped("в базе данных нет слова " . var_export($word, 1));
        }

        $factory = \Aot\RussianMorphology\FactoryFromEntity::get();

        /** @var \Aot\RussianMorphology\Slovo[][] $slova */
        $slova = $factory->getNonUniqueWords($non_unique_words);

        $this->assertEquals(count($slova[0]), count($slova[2]));

        $this->assertEquals(
            $slova[0][0]->getText(),
            $slova[2][0]->getText()
        );

        $this->assertEquals(
            $slova[0][0]->getInitialForm(),
            $slova[2][0]->getInitialForm()
        );
    }
}