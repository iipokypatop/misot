<?php

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Prichastie;

use Aot\MivarTextSemantic\OldAotConstants;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Factory;
use MorphAttribute;

class FactoryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Factory::class, $factory);
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Base::class, $result[1]);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Polnaya::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Null::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Nesovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Nevozvratnyj::class, $result[0]->vozvratnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Nastoyaschee::class, $result[0]->vremya);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Dejstvitelnyj::class, $result[0]->razryad);
    }

    public function dataProviderFalseParameter()
    {
        $false_value = 111;
        return [
            [\Aot\MivarTextSemantic\Constants::NUMBER_ID, $false_value],
            [\Aot\MivarTextSemantic\Constants::TRANSIVITY_ID, $false_value],
            [\Aot\MivarTextSemantic\Constants::CASE_ID, $false_value],
            [\Aot\MivarTextSemantic\Constants::VIEW_ID, $false_value],
            [OldAotConstants::RETRIEVABLE_IRRETRIEVABLE(), $false_value],
            [\Aot\MivarTextSemantic\Constants::TIME_ID, $false_value],
            [\Aot\MivarTextSemantic\Constants::DISCHARGE_COMMUNION_ID, $false_value],
            [OldAotConstants::WORD_FORM(), $false_value],
            [\Aot\MivarTextSemantic\Constants::GENUS_ID, $false_value]
        ];
    }


    /**
     * @param $parameter
     * @param $false_value
     * @dataProvider dataProviderFalseParameter
     */
    public function testFalseParameter($parameter, $false_value)
    {
        $point = $this->getPoint();
        if( empty($point->dw->parameters[$parameter]) ){
            $point->dw->parameters[$parameter] = new MorphAttribute();
            $point->dw->parameters[$parameter]->id_morph_attr = $parameter;
        }
        $point->dw->parameters[$parameter]->id_value_attr = [$false_value => $false_value];
        try {
            $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        } catch (\RuntimeException $e) {
            $this->assertEquals("Unsupported value exception = " . $false_value, $e->getMessage());
        }
    }


    protected function buildFactory($point)
    {
        $dw = new \DictionaryWord(
            $point->dw->id_word_form,
            $point->dw->initial_form,
            $point->dw->initial_form,
            $point->dw->id_word_class,
            $point->dw->name_word_class,
            $point->dw->parameters
        );

        return Factory::get()->build($dw);
    }

    /**
     * читающий
     * @return object
     */
    protected function getPoint()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:2;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:16:"читающий";s:11:"id_sentence";s:23:"55ad0077a87f44.13996828";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"f2349b6c-34a9-11e2-9f8b-ff43d750e408";s:9:"word_form";s:16:"читающий";s:12:"initial_form";s:12:"читать";s:13:"id_word_class";s:1:"5";s:15:"name_word_class";s:18:"причастие";s:10:"parameters";a:8:{i:16;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"16";s:4:"name";s:21:"разряд прич";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:44;s:2:"44";}s:11:"short_value";a:1:{s:10:"дейст";s:10:"дейст";}s:5:"value";a:1:{s:28:"действительное";s:28:"действительное";}}i:1;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"1";s:4:"name";s:6:"вид";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:3;s:1:"3";}s:11:"short_value";a:1:{s:10:"несов";s:10:"несов";}s:5:"value";a:1:{s:26:"несовершенный";s:26:"несовершенный";}}i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:23;s:2:"23";}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}i:5;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"5";s:4:"name";s:10:"время";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:11;s:2:"11";}s:11:"short_value";a:1:{s:8:"наст";s:8:"наст";}s:5:"value";a:1:{s:18:"настоящее";s:18:"настоящее";}}i:17;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"17";s:4:"name";s:10:"форма";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:46;s:2:"46";}s:11:"short_value";a:1:{s:8:"полн";s:8:"полн";}s:5:"value";a:1:{s:23:"полная форма";s:23:"полная форма";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"7";s:13:"id_value_attr";a:1:{i:19;s:2:"19";}s:11:"short_value";a:1:{s:6:"м.р.";s:6:"м.р.";}s:5:"value";a:1:{s:21:"мужской род";s:21:"мужской род";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"8";s:13:"id_value_attr";a:1:{i:32;s:2:"32";}s:11:"short_value";a:1:{s:6:"и.п.";s:6:"и.п.";}s:5:"value";a:1:{s:24:"именительный";s:24:"именительный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        $point->dw->parameters[13] = new MorphAttribute();
        $point->dw->parameters[13]->id_morph_attr = 13;
        $point->dw->parameters[13]->id_value_attr = ['32' => 32, '33' => 33];
        return $point;
    }


}