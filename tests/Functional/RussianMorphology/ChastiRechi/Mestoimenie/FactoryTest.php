<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 02:01
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Mestoimenie;

use Aot\MivarTextSemantic\OldAotConstants;

use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Factory;
use AotTest\AotDataStorage;
use MivarTest\PHPUnitHelper;
use MorphAttribute;

class FactoryTest extends AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Factory::class, $factory);
    }


    public function testBuild_Success()
    {
        $point = $this->getPoint(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Tretie::class, $result[0]->litso);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
    }

    public function testBuild_Success2()
    {
        $point = $this->getPoint2(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Tretie::class, $result[0]->litso);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Roditelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Zhenskij::class, $result[0]->rod);
    }

    public function testBuild_Success3()
    {
        $point = $this->getPoint3(); // берем точку тестовую
        // убираем падеж
        unset($point->dw->parameters[13]);
        // убираем число
        unset($point->dw->parameters[6]);
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\ClassNull::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Pervoe::class, $result[0]->litso);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\ClassNull::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\ClassNull::class, $result[0]->rod);
    }

    public function testBuild_Success4()
    {
        $point = $this->getPoint4(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Vtoroe::class, $result[0]->litso);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\ClassNull::class, $result[0]->rod);
    }

    public function testBuild_Success5()
    {
        $point = $this->getPoint5(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Mnozhestvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\ClassNull::class, $result[0]->litso);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Roditelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\ClassNull::class, $result[0]->rod);
    }


    public function testBuild_Success6()
    {
        $point = $this->getPoint6(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Tretie::class, $result[0]->litso);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Srednij::class, $result[0]->rod);
    }

    public function testBuild_PadeszhFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем падеж на несуществующий
        $point->dw->parameters[\Aot\MivarTextSemantic\Constants::CASE_ID]->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }

    }

    public function testBuild_ChisloFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем число на несуществующий
        $point->dw->parameters[\Aot\MivarTextSemantic\Constants::NUMBER_ID]->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }

    }

    public function testBuild_LitsoFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем лицо на несуществующий
        $point->dw->parameters[\Aot\MivarTextSemantic\Constants::PERSON_ID]->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }
    }

    public function testBuild_RodFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем род на несуществующий
        $point->dw->parameters[\Aot\MivarTextSemantic\Constants::GENUS_ID]->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }
    }

    public function testBuild_RazryadFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // создаем новый аттрибут
        $point->dw->parameters[OldAotConstants::RANK_PRONOUNS()] = new MorphAttribute();
        // подменяем разряд на несуществующий
        $point->dw->parameters[OldAotConstants::RANK_PRONOUNS()]->id_morph_attr = OldAotConstants::RANK_PRONOUNS();
        $point->dw->parameters[OldAotConstants::RANK_PRONOUNS()]->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }
    }

    private function buildFactory($point)
    {
        $dw = \WrapperAot\ModelNew\Convert\DictionaryWord::create(
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
     * Местоимение "он"
     * @return array
     */
    private function getPoint()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:4:"он";s:11:"id_sentence";s:23:"55acee270a9f88.63302289";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"e9aa76c2-2f10-11e2-b6c7-2710c1e7728c";s:9:"word_form";s:4:"он";s:12:"initial_form";s:4:"он";s:13:"id_word_class";s:1:"4";s:15:"name_word_class";s:22:"местоимение";s:10:"parameters";a:5:{i:7;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"7";s:4:"name";s:8:"лицо";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:18;s:2:"18";}s:11:"short_value";a:1:{s:4:"3-е";s:4:"3-е";}s:5:"value";a:1:{s:10:"3 лицо";s:10:"3 лицо";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:32;s:2:"32";}s:11:"short_value";a:1:{s:6:"и.п.";s:6:"и.п.";}s:5:"value";a:1:{s:24:"именительный";s:24:"именительный";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:19;s:2:"19";}s:11:"short_value";a:1:{s:6:"м.р.";s:6:"м.р.";}s:5:"value";a:1:{s:21:"мужской род";s:21:"мужской род";}}i:25;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"25";s:4:"name";s:29:"тип местоимения";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:70;s:2:"70";}s:11:"short_value";a:1:{s:8:"одуш";s:8:"одуш";}s:5:"value";a:1:{s:23:"личное (одуш)";s:23:"личное (одуш)";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }


    /**
     * Местоимение "ее"
     * @return array
     */
    private function getPoint2()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:2;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:4:"ее";s:11:"id_sentence";s:23:"55aceefe04a283.38623597";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"e9c4b0a0-2f10-11e2-8636-7703a1d85872";s:9:"word_form";s:4:"ее";s:12:"initial_form";s:6:"она";s:13:"id_word_class";s:1:"4";s:15:"name_word_class";s:22:"местоимение";s:10:"parameters";a:5:{i:7;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"7";s:4:"name";s:8:"лицо";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:18;s:2:"18";}s:11:"short_value";a:1:{s:4:"3-е";s:4:"3-е";}s:5:"value";a:1:{s:10:"3 лицо";s:10:"3 лицо";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:33;s:2:"33";}s:11:"short_value";a:1:{s:6:"р.п.";s:6:"р.п.";}s:5:"value";a:1:{s:22:"родительный";s:22:"родительный";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:21;s:2:"21";}s:11:"short_value";a:1:{s:6:"ж.р.";s:6:"ж.р.";}s:5:"value";a:1:{s:21:"женский род";s:21:"женский род";}}i:25;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"25";s:4:"name";s:29:"тип местоимения";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:70;s:2:"70";}s:11:"short_value";a:1:{s:8:"одуш";s:8:"одуш";}s:5:"value";a:1:{s:23:"личное (одуш)";s:23:"личное (одуш)";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;

    }

    /**
     * местоимение "я"
     * @return mixed
     */
    private function getPoint3()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:2:"я";s:11:"id_sentence";s:23:"55acef2a9b70c2.64230191";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"efc1b8ae-2f10-11e2-a59d-b318a7eaeb89";s:9:"word_form";s:2:"я";s:12:"initial_form";s:2:"я";s:13:"id_word_class";s:1:"4";s:15:"name_word_class";s:22:"местоимение";s:10:"parameters";a:4:{i:7;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"7";s:4:"name";s:8:"лицо";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:16;s:2:"16";}s:11:"short_value";a:1:{s:4:"1-е";s:4:"1-е";}s:5:"value";a:1:{s:10:"1 лицо";s:10:"1 лицо";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:32;s:2:"32";}s:11:"short_value";a:1:{s:6:"и.п.";s:6:"и.п.";}s:5:"value";a:1:{s:24:"именительный";s:24:"именительный";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:25;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"25";s:4:"name";s:29:"тип местоимения";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:70;s:2:"70";}s:11:"short_value";a:1:{s:8:"одуш";s:8:"одуш";}s:5:"value";a:1:{s:23:"личное (одуш)";s:23:"личное (одуш)";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;

    }


    /**
     * местоимение "ты"
     * @return mixed
     */
    private function getPoint4()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:4:"ты";s:11:"id_sentence";s:23:"55acef4b33a066.20942810";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"eda94794-2f10-11e2-9a2f-ff4953d40a50";s:9:"word_form";s:4:"ты";s:12:"initial_form";s:4:"ты";s:13:"id_word_class";s:1:"4";s:15:"name_word_class";s:22:"местоимение";s:10:"parameters";a:4:{i:7;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"7";s:4:"name";s:8:"лицо";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:17;s:2:"17";}s:11:"short_value";a:1:{s:4:"2-е";s:4:"2-е";}s:5:"value";a:1:{s:10:"2 лицо";s:10:"2 лицо";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:32;s:2:"32";}s:11:"short_value";a:1:{s:6:"и.п.";s:6:"и.п.";}s:5:"value";a:1:{s:24:"именительный";s:24:"именительный";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:25;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"25";s:4:"name";s:29:"тип местоимения";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:70;s:2:"70";}s:11:"short_value";a:1:{s:8:"одуш";s:8:"одуш";}s:5:"value";a:1:{s:23:"личное (одуш)";s:23:"личное (одуш)";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;

    }



    /**
     * местоимение "твоих"
     * @return mixed
     */
    private function getPoint5()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:3;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:10:"твоих";s:11:"id_sentence";s:23:"55acef69167a83.93045825";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"ed13136e-2f10-11e2-8dee-dbb7ab3963d5";s:9:"word_form";s:10:"твоих";s:12:"initial_form";s:8:"твой";s:13:"id_word_class";s:1:"4";s:15:"name_word_class";s:22:"местоимение";s:10:"parameters";a:3:{i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:33;s:2:"33";}s:11:"short_value";a:1:{s:6:"р.п.";s:6:"р.п.";}s:5:"value";a:1:{s:22:"родительный";s:22:"родительный";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:15;s:2:"15";}s:11:"short_value";a:1:{s:8:"мн.ч.";s:8:"мн.ч.";}s:5:"value";a:1:{s:26:"множественное";s:26:"множественное";}}i:25;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"25";s:4:"name";s:29:"тип местоимения";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:69;s:2:"69";}s:11:"short_value";a:1:{s:8:"прил";s:8:"прил";}s:5:"value";a:1:{s:51:"местоимение-прилагательное";s:51:"местоимение-прилагательное";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;

    }


    /**
     * местоимение "оно"
     * @return mixed
     */
    private function getPoint6()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:2;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:6:"оно";s:11:"id_sentence";s:23:"55acef956fe1b4.30930019";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"e9f45fa8-2f10-11e2-a62a-9f539fd691a9";s:9:"word_form";s:6:"оно";s:12:"initial_form";s:6:"оно";s:13:"id_word_class";s:1:"4";s:15:"name_word_class";s:22:"местоимение";s:10:"parameters";a:5:{i:7;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"7";s:4:"name";s:8:"лицо";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:18;s:2:"18";}s:11:"short_value";a:1:{s:4:"3-е";s:4:"3-е";}s:5:"value";a:1:{s:10:"3 лицо";s:10:"3 лицо";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:32;s:2:"32";}s:11:"short_value";a:1:{s:6:"и.п.";s:6:"и.п.";}s:5:"value";a:1:{s:24:"именительный";s:24:"именительный";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:20;s:2:"20";}s:11:"short_value";a:1:{s:6:"с.р.";s:6:"с.р.";}s:5:"value";a:1:{s:21:"средний род";s:21:"средний род";}}i:25;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"25";s:4:"name";s:29:"тип местоимения";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:70;s:2:"70";}s:11:"short_value";a:1:{s:8:"одуш";s:8:"одуш";}s:5:"value";a:1:{s:23:"личное (одуш)";s:23:"личное (одуш)";}}}}s:9:"key_point";i:1;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }


    public function dataProviderPadeszh()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\ClassNull::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Imenitelnij::class, \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Roditelnij::class, \Aot\MivarTextSemantic\Constants::CASE_GENITIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Datelnij::class, \Aot\MivarTextSemantic\Constants::CASE_DATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Vinitelnij::class, \Aot\MivarTextSemantic\Constants::CASE_ACCUSATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Tvoritelnij::class, \Aot\MivarTextSemantic\Constants::CASE_INSTRUMENTAL_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Predlozshnij::class, \Aot\MivarTextSemantic\Constants::CASE_PREPOSITIONAL_ID]
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
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\ClassNull::class){
            unset($point->dw->parameters[\Aot\MivarTextSemantic\Constants::CASE_ID]);
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPadeszh', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\ClassNull::class, $result[0]);
            return;
        }
        else{
            // подменяем падеж
            $point->dw->parameters[\Aot\MivarTextSemantic\Constants::CASE_ID]->id_value_attr = [$padeszh => $padeszh];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPadeszh', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }



    public function dataProviderRazryad()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Lichnoe::class, OldAotConstants::PERSONAL_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Vozvratnoe::class, OldAotConstants::REFLEXIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Prityazhatelnoe::class, OldAotConstants::POSSESSIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Otricatelnoe::class, OldAotConstants::NEGATIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Neopredelennoe::class, OldAotConstants::INDEFINITE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Voprositelnoe::class, OldAotConstants::INTERROGATIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Otnositelnoe::class, OldAotConstants::RELATIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Ukazatelnoe::class, OldAotConstants::DEMONSTRATIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Opredelitelnoe::class, OldAotConstants::ATTRIBUTIVE_PRONOUN()]
        ];
    }


    /**
     * @param $expectedResult
     * @param $razryad
     * @dataProvider dataProviderRazryad
     */
    public function testGetRazryad($expectedResult, $razryad)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class){
            # разряда и так по умолчанию нет, поэтому unset делать не надо
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRazryad', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class, $result[0]);
            return;
        }
        else{
            // создаем новый аттрибут
            $point->dw->parameters[OldAotConstants::RANK_PRONOUNS()] = new MorphAttribute();
            // задаем значение разряда
            $point->dw->parameters[OldAotConstants::RANK_PRONOUNS()]->id_value_attr = [$razryad => $razryad];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRazryad', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }

}