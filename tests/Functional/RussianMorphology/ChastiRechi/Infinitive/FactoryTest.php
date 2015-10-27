<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13/07/15
 * Time: 16:47
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Infinitive;


use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\MorphAttribute;
use Aot\MivarTextSemantic\OldAotConstants;
use Aot\MivarTextSemanticOldAotConstants;
use Aot\MivarTextSemantic\Word;
use Aot\RussianMorphology\ChastiRechi\Infinitive\Factory;
use Aot\RussianMorphology\FactoryException;
use MivarTest\PHPUnitHelper;



class FactoryTest extends \AotTest\AotDataStorage
{

    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Factory::class, $factory);
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint();
        $result = $this->buildFactory($point);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Perehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Nevozvratnyj::class, $result[0]->vozvratnost);

    }

    public function dataProviderVid()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Sovershennyj::class, \Aot\MivarTextSemantic\Constants::VIEW_PERFECTIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Nesovershennyj::class, \Aot\MivarTextSemantic\Constants::VIEW_IMPERFECT_ID]
        ];
    }


    /**
     * @param $expectedResult
     * @param $vid
     * @dataProvider dataProviderVid
     */
    public function testGetVid($expectedResult, $vid)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Null::class){
            unset($point->dw->parameters[\Aot\MivarTextSemantic\Constants::VIEW_ID]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getVid', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Null::class, $result[0]);
            return;
        }
        else{
            // создаем новый аттрибут
            $point->dw->parameters[\Aot\MivarTextSemantic\Constants::VIEW_ID] = new MorphAttribute();
            // подменяем число
            $point->dw->parameters[\Aot\MivarTextSemantic\Constants::VIEW_ID]->id_value_attr = [$vid => $vid];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getVid', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }

    public function dataProviderPerehodnost()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Perehodnyj::class, OldAotConstants::TRANSITIVE()],
            [\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Neperehodnyj::class, OldAotConstants::INTRANSITIVE()]
        ];
    }


    /**
     * @param $expectedResult
     * @param $perehodnost
     * @dataProvider dataProviderPerehodnost
     */
    public function testGetPerehodnost($expectedResult, $perehodnost)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Null::class){
            unset($point->dw->parameters[\Aot\MivarTextSemantic\Constants::TRANSIVITY_ID]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPerehodnost', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Null::class, $result[0]);
            return;
        }
        else{
            // создаем новый аттрибут
            $point->dw->parameters[\Aot\MivarTextSemantic\Constants::TRANSIVITY_ID] = new MorphAttribute();
            // подменяем переходность
            $point->dw->parameters[\Aot\MivarTextSemantic\Constants::TRANSIVITY_ID]->id_value_attr = [$perehodnost => $perehodnost];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPerehodnost', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }

    public function dataProviderVozvratnost()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Vozvratnyj::class, OldAotConstants::RETRIEVABLE()],
            [\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Nevozvratnyj::class, OldAotConstants::IRRETRIEVABLE()]
        ];
    }


    /**
     * @param $expectedResult
     * @param $vozvratnost
     * @dataProvider dataProviderVozvratnost
     */
    public function testGetVozvratnost($expectedResult, $vozvratnost)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Null::class){
            unset($point->dw->parameters[OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getVozvratnost', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Null::class, $result[0]);
            return;
        }
        else{
            // создаем новый аттрибут
            $point->dw->parameters[OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()] = new MorphAttribute();
            // подменяем переходность
            $point->dw->parameters[OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()]->id_value_attr = [$vozvratnost => $vozvratnost];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getVozvratnost', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }


    protected function buildFactory($point)
    {
        $dw = new Dw(
            $point->dw->id_word_form,
            $point->dw->initial_form,
            $point->dw->initial_form,
            $point->dw->id_word_class,
            $point->dw->name_word_class,
            $point->dw->parameters
        );

        $word = new Word(
            $point->kw,
            $point->dw->initial_form,
            $point->id_sentence
        );
        return Factory::get()->build($dw, $word);
    }

    /**
     * пойти
     * @return mixed
     */
    protected function getPoint()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:10:"пойти";s:11:"id_sentence";s:23:"55e6e6b6258a44.52657013";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"1415b7fc-33ba-11e2-bf49-9b95eb498456";s:9:"word_form";s:10:"пойти";s:12:"initial_form";s:10:"пойти";s:13:"id_word_class";s:2:"13";s:15:"name_word_class";s:18:"инфинитив";s:10:"parameters";a:5:{i:1;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"1";s:4:"name";s:6:"вид";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:2;s:1:"2";}s:11:"short_value";a:1:{s:6:"сов";s:6:"сов";}s:5:"value";a:1:{s:22:"совершенный";s:22:"совершенный";}}i:2;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"2";s:4:"name";s:18:"спряжение";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:4;s:1:"4";}s:11:"short_value";a:1:{s:4:"1-е";s:4:"1-е";}s:5:"value";a:1:{s:4:"1-е";s:4:"1-е";}}i:3;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"3";s:4:"name";s:24:"переходность";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:6;s:1:"6";}s:11:"short_value";a:1:{s:10:"перех";s:10:"перех";}s:5:"value";a:1:{s:20:"переходный";s:20:"переходный";}}i:4;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"4";s:4:"name";s:20:"наклонение";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:8;s:1:"8";}s:11:"short_value";a:1:{s:14:"изъявит";s:14:"изъявит";}s:5:"value";a:1:{s:26:"изъявительное";s:26:"изъявительное";}}i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"9";s:13:"id_value_attr";a:1:{i:23;s:2:"23";}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }


}