<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 19:41
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Chislitelnoe;


use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Factory;
use AotTest\AotDataStorage;
use MivarTest\PHPUnitHelper;
use MorphAttribute;

class FactoryTest extends AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Factory::class, $factory);
    }

    public function testWDW(){
        $const = new \D_Constants();
        $const->defineConstants();
        $syntax_parser = new \SyntaxParserManager();
        $text = 'прячась';
        $syntax_parser->reg_parser->parse_text($text);
        $syntax_parser->create_dictionary_word();
        $wdw = [];
        foreach ($syntax_parser->reg_parser->get_sentences() as $sentence) {
            $wdw[] = $syntax_parser->create_sentence_space($sentence);
        }

        $space_kw = PHPUnitHelper::getProtectedProperty($wdw[0],'space_kw');
//        $wdw_s = json_encode($space_kw[0], JSON_PRETTY_PRINT | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
        $space_kw[0][0]->dw->parameters[9]->id_value_attr = ['23' => 23, '22' => 22];
        $wdw_s = serialize($space_kw[0][0]);

        print_r($wdw_s);
        print_r(unserialize($wdw_s));
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Vinitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Null::class, $result[0]->podvid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Zhenskiy::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Null::class, $result[0]->tip);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Poryadkoviy::class, $result[0]->vid);
    }


    public function testBuild_Success2()
    {
        $point = $this->getPoint2(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Null::class, $result[0]->podvid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Null::class, $result[0]->tip);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Kolichestvenniy::class, $result[0]->vid);
    }

    public function testBuild_Success_NullVid()
    {
        $point = $this->getPoint2(); // берем точку тестовую
        unset($point->dw->parameters[TYPE_OF_NUMERAL_ID]);
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Null::class, $result[0]->podvid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Null::class, $result[0]->tip);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Null::class, $result[0]->vid);
    }

    #TODO
    public function testBuild_ChisloFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // создаем новый аттрибут
        $point->dw->parameters[NUMBER_ID] = new MorphAttribute();
        // подменяем род на несуществующий
        $point->dw->parameters[NUMBER_ID]->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }
    }

    #TODO
    public function testBuild_PadeszhFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем род на несуществующий
        $point->dw->parameters[CASE_ID]->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }
    }

    #TODO
    public function testBuild_VidFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем род на несуществующий
        $point->dw->parameters[TYPE_OF_NUMERAL_ID]->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }
    }

    #TODO
    public function testBuild_RodFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем род на несуществующий
        $point->dw->parameters[GENUS_ID]->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }
    }



    public function dataProviderPadeszh()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Imenitelnij::class, CASE_SUBJECTIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Roditelnij::class, CASE_GENITIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Datelnij::class, CASE_DATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Vinitelnij::class, CASE_ACCUSATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Tvoritelnij::class, CASE_INSTRUMENTAL_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Predlozshnij::class, CASE_PREPOSITIONAL_ID]
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
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Null::class){
            unset($point->dw->parameters[CASE_ID]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPadeszh', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Null::class, $result[0]);
            return;
        }
        else{
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
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Edinstvennoe::class, NUMBER_SINGULAR_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Mnozhestvennoe::class, NUMBER_PLURAL_ID]
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
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class){
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getChislo', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, $result[0]);
            return;
        }
        else{
            // создаем новый аттрибут
            $point->dw->parameters[NUMBER_ID] = new MorphAttribute();
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
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Muzhskoy::class, GENUS_MASCULINE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Sredniy::class, GENUS_NEUTER_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Zhenskiy::class, GENUS_FEMININE_ID]
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
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class){
            unset($point->dw->parameters[GENUS_ID]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRod', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class, $result[0]);
            return;
        }
        else{
            // создаем новый аттрибут
            $point->dw->parameters[GENUS_ID] = new MorphAttribute();
            // подменяем число
            $point->dw->parameters[GENUS_ID]->id_value_attr = [$rod => $rod];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRod', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }



    /**
     * Числительное "второй"
     * @return array
     */
    private function getPoint()
    {

        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:9;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:12:"второй";s:11:"id_sentence";s:23:"55ace3ba6fbfc4.35403284";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"087504ac-d810-11e2-a002-232a1d54f374";s:9:"word_form";s:12:"второй";s:12:"initial_form";s:12:"второй";s:13:"id_word_class";s:2:"14";s:15:"name_word_class";s:24:"числительное";s:10:"parameters";a:4:{i:26;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"26";s:4:"name";s:33:"тип числительного";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:73;s:2:"73";}s:11:"short_value";a:1:{s:6:"пор";s:6:"пор";}s:5:"value";a:1:{s:20:"порядковое";s:20:"порядковое";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:35;s:2:"35";}s:11:"short_value";a:1:{s:6:"в.п.";s:6:"в.п.";}s:5:"value";a:1:{s:22:"винительный";s:22:"винительный";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:21;s:2:"21";}s:11:"short_value";a:1:{s:6:"ж.р.";s:6:"ж.р.";}s:5:"value";a:1:{s:21:"женский род";s:21:"женский род";}}i:11;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"11";s:4:"name";s:21:"одуш-неодуш";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:26;s:2:"26";}s:11:"short_value";a:1:{s:8:"одуш";s:8:"одуш";}s:5:"value";a:1:{s:24:"одушевленное";s:24:"одушевленное";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }

    /**
     * Числительное "пять"
     * @return array
     */
    private function getPoint2()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:3;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:8:"пять";s:11:"id_sentence";s:23:"55ace41502e791.79232664";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"58b67f4a-2f0f-11e2-868f-5f1d3fd25ec1";s:9:"word_form";s:8:"пять";s:12:"initial_form";s:8:"пять";s:13:"id_word_class";s:2:"14";s:15:"name_word_class";s:24:"числительное";s:10:"parameters";a:2:{i:26;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"26";s:4:"name";s:33:"тип числительного";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:74;s:2:"74";}s:11:"short_value";a:1:{s:6:"кол";s:6:"кол";}s:5:"value";a:1:{s:28:"количественное";s:28:"количественное";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:32;s:2:"32";}s:11:"short_value";a:1:{s:6:"и.п.";s:6:"и.п.";}s:5:"value";a:1:{s:24:"именительный";s:24:"именительный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }


    /**
     * Числительное "пятых"
     * @return array
     */
    private function getPoint3()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:5;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:10:"пятых";s:11:"id_sentence";s:23:"55ace4983816e1.10741578";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"535ebfda-2e5b-11e2-ba68-07d2514cce4a";s:9:"word_form";s:10:"пятых";s:12:"initial_form";s:10:"пятая";s:13:"id_word_class";s:1:"2";s:15:"name_word_class";s:30:"существительное";s:10:"parameters";a:5:{i:10;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"10";s:4:"name";s:13:"соб-нар";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:25;s:2:"25";}s:11:"short_value";a:1:{s:6:"нар";s:6:"нар";}s:5:"value";a:1:{s:26:"нарицательное";s:26:"нарицательное";}}i:11;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"11";s:4:"name";s:21:"одуш-неодуш";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:27;s:2:"27";}s:11:"short_value";a:1:{s:12:"неодуш";s:12:"неодуш";}s:5:"value";a:1:{s:28:"неодушевленное";s:28:"неодушевленное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:21;s:2:"21";}s:11:"short_value";a:1:{s:6:"ж.р.";s:6:"ж.р.";}s:5:"value";a:1:{s:21:"женский род";s:21:"женский род";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:33;s:2:"33";}s:11:"short_value";a:1:{s:6:"р.п.";s:6:"р.п.";}s:5:"value";a:1:{s:22:"родительный";s:22:"родительный";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:15;s:2:"15";}s:11:"short_value";a:1:{s:8:"мн.ч.";s:8:"мн.ч.";}s:5:"value";a:1:{s:26:"множественное";s:26:"множественное";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
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

}