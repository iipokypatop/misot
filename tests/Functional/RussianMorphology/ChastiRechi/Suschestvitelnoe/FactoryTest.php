<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 19.06.2015
 * Time: 14:11
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Suschestvitelnoe;


use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Factory;
use AotTest\AotDataStorage;
use MivarTest\PHPUnitHelper;
use MorphAttribute;

class FactoryTest extends AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Factory::class, $factory);
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class, $result[0]);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Odushevlyonnoe::class, $result[0]->odushevlyonnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Vtoroe::class, $result[0]->sklonenie);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::class, $result[0]->naritcatelnost);
    }


    private function buildFactory($point)
    {
        $dw = new \Dw(
            $point->dw->id_word_form,
            $point->dw->initial_form,
            $point->dw->initial_form,
            $point->dw->id_word_class,
            $point->dw->name_word_class,
            $point->dw->parameters
        );

        $word = new \Word(
            $point->kw,
            $point->dw->initial_form,
            $point->id_sentence
        );
        return Factory::get()->build($dw, $word);
    }


    public function dataProviderPadeszh()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Null::class, -1],
            ['Exception', 1111],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::class, CASE_SUBJECTIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::class, CASE_GENITIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Datelnij::class, CASE_DATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::class, CASE_ACCUSATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::class, CASE_INSTRUMENTAL_ID],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Predlozshnij::class, CASE_PREPOSITIONAL_ID]
        ];
    }


    /**
     * @param $expectedResult
     * @param $padeszh
     * @dataProvider dataProviderPadeszh
     */
    public function testGetPadeszh($expectedResult, $padeszh)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if ($expectedResult === \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Null::class) {
            unset($point->dw->parameters[CASE_ID]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPadeszh', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Null::class, $result[0]);
            return;
        } elseif ($expectedResult === 'Exception') {
            // подменяем падеж
            $point->dw->parameters[CASE_ID]->id_value_attr = [$padeszh => $padeszh];
            try {
                $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPadeszh', [$point->dw->parameters]);
                $this->fail("Не должно было тут быть!");
            } catch (\RuntimeException $e) {
                $this->assertEquals("Unsupported value exception = $padeszh", $e->getMessage());
                return;
            }
        } else {
            // подменяем падеж
            $point->dw->parameters[CASE_ID]->id_value_attr = [$padeszh => $padeszh];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPadeszh', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }


    public function dataProviderChislo()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Null::class, -1],
            ['Exception', 1111],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::class, NUMBER_SINGULAR_ID],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::class, NUMBER_PLURAL_ID]
        ];
    }


    /**
     * @param $expectedResult
     * @param $chislo
     * @dataProvider dataProviderChislo
     */
    public function testGetChislo($expectedResult, $chislo)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if ($expectedResult === \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Null::class) {
            unset($point->dw->parameters[NUMBER_ID]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getChislo', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Null::class, $result[0]);
            return;
        } elseif ($expectedResult === 'Exception') {
            // подменяем число
            $point->dw->parameters[NUMBER_ID]->id_value_attr = [$chislo => $chislo];
            try {
                $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getChislo', [$point->dw->parameters]);
                $this->fail("Не должно было тут быть!");
            } catch (\RuntimeException $e) {
                $this->assertEquals("Unsupported value exception = $chislo", $e->getMessage());
                return;
            }
        } else {
            // создаем новый аттрибут
//            $point->dw->parameters[NUMBER_ID] = new MorphAttribute();
            // подменяем число
            $point->dw->parameters[NUMBER_ID]->id_value_attr = [$chislo => $chislo];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getChislo', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }


    public function dataProviderRod()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Null::class, -1],
            ['Exception', 1111],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::class, GENUS_MASCULINE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::class, GENUS_NEUTER_ID],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::class, GENUS_FEMININE_ID]
        ];
    }


    /**
     * @param $expectedResult
     * @param $rod
     * @dataProvider dataProviderRod
     */
    public function testGetRod($expectedResult, $rod)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if ($expectedResult === \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Null::class) {
            unset($point->dw->parameters[GENUS_ID]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRod', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Null::class, $result[0]);
            return;
        } elseif ($expectedResult === 'Exception') {
            // подменяем род
            $point->dw->parameters[GENUS_ID]->id_value_attr = [$rod => $rod];
            try {
                $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRod', [$point->dw->parameters]);
                $this->fail("Не должно было тут быть!");
            } catch (\RuntimeException $e) {
                $this->assertEquals("Unsupported value exception = $rod", $e->getMessage());
                return;
            }
        } else {
            // создаем новый аттрибут
//            $point->dw->parameters[GENUS_ID] = new MorphAttribute();
            // подменяем род
            $point->dw->parameters[GENUS_ID]->id_value_attr = [$rod => $rod];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRod', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }


    public function dataProviderOdushevlyonnost()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Null::class, -1],
            ['Exception', 1111],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Odushevlyonnoe::class, ANIMALITY_ANIMATE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::class, ANIMALITY_INANIMATE_ID]
        ];
    }


    /**
     * @param $expectedResult
     * @param $odushevlyonnost
     * @dataProvider dataProviderOdushevlyonnost
     */
    public function testGetOdushevlyonnost($expectedResult, $odushevlyonnost)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if ($expectedResult === \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Null::class) {
            unset($point->dw->parameters[ANIMALITY_ID]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getOdushevlennost', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Null::class, $result[0]);
            return;
        } elseif ($expectedResult === 'Exception') {
            // подменяем одушевленность
            $point->dw->parameters[ANIMALITY_ID]->id_value_attr = [$odushevlyonnost => $odushevlyonnost];
            try {
                $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getOdushevlennost', [$point->dw->parameters]);
                $this->fail("Не должно было тут быть!");
            } catch (\RuntimeException $e) {
                $this->assertEquals("Unsupported value exception = $odushevlyonnost", $e->getMessage());
                return;
            }
        } else {
            // создаем новый аттрибут
//            $point->dw->parameters[ANIMALITY_ID] = new MorphAttribute();
            // подменяем одушевленность
            $point->dw->parameters[ANIMALITY_ID]->id_value_attr = [$odushevlyonnost => $odushevlyonnost];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getOdushevlennost', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }

    public function dataProviderSklonenie()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::class, -1],
            ['Exception', 1111],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Pervoe::class, \OldAotConstants::DECLENSION_1],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Vtoroe::class, \OldAotConstants::DECLENSION_2],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Tretie::class, \OldAotConstants::DECLENSION_3]
        ];
    }


    /**
     * @param $expectedResult
     * @param $sklonenie
     * @dataProvider dataProviderSklonenie
     */
    public function testGetSklonenie($expectedResult, $sklonenie)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if ($expectedResult === \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::class) {
            unset($point->dw->parameters[\OldAotConstants::DECLENSION]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getSklonenie', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::class, $result[0]);
            return;
        } elseif ($expectedResult === 'Exception') {
            // подменяем склонение
            $point->dw->parameters[\OldAotConstants::DECLENSION]->id_value_attr = [$sklonenie => $sklonenie];
            try {
                $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getSklonenie', [$point->dw->parameters]);
                $this->fail("Не должно было тут быть!");
            } catch (\RuntimeException $e) {
                $this->assertEquals("Unsupported value exception = $sklonenie", $e->getMessage());
                return;
            }
        } else {
            // создаем новый аттрибут
//            $point->dw->parameters[\OldAotConstants::DECLENSION] = new MorphAttribute();
            // подменяем склонение
            $point->dw->parameters[\OldAotConstants::DECLENSION]->id_value_attr = [$sklonenie => $sklonenie];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getSklonenie', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }


    public function dataProviderNaritcatelnost()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\Null::class, -1],
            ['Exception', 1111],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaSobstvennoe::class, \OldAotConstants::SELF()],
            [\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::class, \OldAotConstants::NOMINAL()]
        ];
    }


    /**
     * @param $expectedResult
     * @param $naritcatelnost
     * @dataProvider dataProviderNaritcatelnost
     */
    public function testGetNaritcatelnost($expectedResult, $naritcatelnost)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if ($expectedResult === \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\Null::class) {
            unset($point->dw->parameters[\OldAotConstants::SELF_NOMINAL]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getNaritcatelnost', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\Null::class, $result[0]);
            return;
        } elseif ($expectedResult === 'Exception') {
            // подменяем падеж
            $point->dw->parameters[\OldAotConstants::SELF_NOMINAL]->id_value_attr = [$naritcatelnost => $naritcatelnost];
            try {
                $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getNaritcatelnost', [$point->dw->parameters]);
                $this->fail("Не должно было тут быть!");
            } catch (\RuntimeException $e) {
                $this->assertEquals("Unsupported value exception = $naritcatelnost", $e->getMessage());
                return;
            }
        } else {
            // создаем новый аттрибут
//            $point->dw->parameters[\OldAotConstants::SELF_NOMINAL] = new MorphAttribute();
            // подменяем число
            $point->dw->parameters[\OldAotConstants::SELF_NOMINAL]->id_value_attr = [$naritcatelnost => $naritcatelnost];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getNaritcatelnost', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }


    /**
     * человек (добавлен дополнительный винительный падеж)
     * @return array
     */
    private function getPoint()
    {

        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:14:"человек";s:11:"id_sentence";s:23:"55ae6046ed7834.53077208";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"3c1f368c-2f01-11e2-8995-2ff63fa05450";s:9:"word_form";s:14:"человек";s:12:"initial_form";s:14:"человек";s:13:"id_word_class";s:1:"2";s:15:"name_word_class";s:30:"существительное";s:10:"parameters";a:6:{i:10;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"10";s:4:"name";s:13:"соб-нар";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:25;s:2:"25";}s:11:"short_value";a:1:{s:6:"нар";s:6:"нар";}s:5:"value";a:1:{s:26:"нарицательное";s:26:"нарицательное";}}i:11;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"11";s:4:"name";s:21:"одуш-неодуш";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:26;s:2:"26";}s:11:"short_value";a:1:{s:8:"одуш";s:8:"одуш";}s:5:"value";a:1:{s:24:"одушевленное";s:24:"одушевленное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:19;s:2:"19";}s:11:"short_value";a:1:{s:6:"м.р.";s:6:"м.р.";}s:5:"value";a:1:{s:21:"мужской род";s:21:"мужской род";}}i:12;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"12";s:4:"name";s:18:"склонение";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:29;s:2:"29";}s:11:"short_value";a:1:{i:2;s:1:"2";}s:5:"value";a:1:{i:2;s:1:"2";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:2:{i:35;i:35;i:32;i:32;}s:11:"short_value";a:1:{s:6:"и.п.";s:6:"и.п.";}s:5:"value";a:1:{s:24:"именительный";s:24:"именительный";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }

}