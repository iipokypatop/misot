<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/07/15
 * Time: 14:48
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Prilagatelnoe;


use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Factory;
use MorphAttribute;

class FactoryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Factory::class, $factory);
    }

    public function testBuild_Success_Point_norm()
    {
        $point = $this->getPoint_norm(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::class, $result[0]->stepen_sravneniia);
    }

    public function testBuild_Success_Point_sravn()
    {
        $point = $this->getPoint_sravn(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Null::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Null::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Null::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Sravnitelnaya::class, $result[0]->stepen_sravneniia);
    }

    public function testBuild_Success_Point_shortForm()
    {
        $point = $this->getPoint_shortForm(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Kratkaya::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Null::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Polozhitelnaya::class, $result[0]->stepen_sravneniia);
    }

    public function testBuild_Success_Point_prevosh_2alt()
    {
        $point = $this->getPoint_prevosh_2alt(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class, $result[1]);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Prevoshodnaya::class, $result[0]->stepen_sravneniia);
    }

    public function testBuild_Success_Point_sravn_falseAlt()
    {
        $point = $this->getPoint_sravn_falseAlt(); // берем точку тестовую
        try{

            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 26", $e->getMessage());
        }
    }


    public function testBuild_Success_Point_falsePartOfSpeech()
    {
        $point = $this->getPoint_falsePartOfSpeech(); // берем точку тестовую
        $result = $this->buildFactory($point);
        if (!empty($result)) {
            $this->fail('Должно быть пустым');
        }

    }

    private function buildFactory($point)
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
     * большой
     * @return mixed
     */
    private function getPoint_norm()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:6;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:14:"большой";s:11:"id_sentence";s:23:"55ad018a5e7460.27070203";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"edf42e6e-2c0c-11e2-b2e9-3f2c36681b6a";s:9:"word_form";s:14:"большой";s:12:"initial_form";s:14:"большой";s:13:"id_word_class";s:1:"3";s:15:"name_word_class";s:28:"прилагательное";s:10:"parameters";a:4:{i:17;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"17";s:4:"name";s:10:"форма";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:46;s:2:"46";}s:11:"short_value";a:1:{s:8:"полн";s:8:"полн";}s:5:"value";a:1:{s:23:"полная форма";s:23:"полная форма";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:19;s:2:"19";}s:11:"short_value";a:1:{s:6:"м.р.";s:6:"м.р.";}s:5:"value";a:1:{s:21:"мужской род";s:21:"мужской род";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:32;s:2:"32";}s:11:"short_value";a:1:{s:6:"и.п.";s:6:"и.п.";}s:5:"value";a:1:{s:24:"именительный";s:24:"именительный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }

    /**
     * тише
     * @return mixed
     */
    private function getPoint_sravn()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:8:"тише";s:11:"id_sentence";s:23:"55ad01e8577178.86807493";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"1a06c2dc-2d6b-11e2-ac8e-6b1c9f627c4c";s:9:"word_form";s:8:"тише";s:12:"initial_form";s:10:"тихий";s:13:"id_word_class";s:1:"3";s:15:"name_word_class";s:28:"прилагательное";s:10:"parameters";a:1:{i:15;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"15";s:4:"name";s:33:"степень сравнения";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:42;s:2:"42";}s:11:"short_value";a:1:{s:10:"сравн";s:10:"сравн";}s:5:"value";a:1:{s:41:"сравнительная степень";s:41:"сравнительная степень";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }

    /**
     * тише
     * сравнительное прилагательное с невалидной альтернативой
     * @return mixed
     */
    private function getPoint_sravn_falseAlt()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:8:"тише";s:11:"id_sentence";s:23:"55ad01e8577178.86807493";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"1a06c2dc-2d6b-11e2-ac8e-6b1c9f627c4c";s:9:"word_form";s:8:"тише";s:12:"initial_form";s:10:"тихий";s:13:"id_word_class";s:1:"3";s:15:"name_word_class";s:28:"прилагательное";s:10:"parameters";a:1:{i:15;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"15";s:4:"name";s:33:"степень сравнения";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:42;s:2:"42";}s:11:"short_value";a:1:{s:10:"сравн";s:10:"сравн";}s:5:"value";a:1:{s:41:"сравнительная степень";s:41:"сравнительная степень";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        $point->dw->parameters[15]->id_value_attr = ['26' => 26, '27' => 27];
        return $point;
    }

    /**
     * тихо - прилагательное в краткой форме
     * @return mixed
     */
    private function getPoint_shortForm()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:8:"тихо";s:11:"id_sentence";s:23:"55ad02ac99ab01.48590573";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"1a06c2dc-2d6b-11e2-bcbf-539839b39787";s:9:"word_form";s:8:"тихо";s:12:"initial_form";s:10:"тихий";s:13:"id_word_class";s:1:"3";s:15:"name_word_class";s:28:"прилагательное";s:10:"parameters";a:4:{i:15;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"15";s:4:"name";s:33:"степень сравнения";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:41;s:2:"41";}s:11:"short_value";a:1:{s:10:"полож";s:10:"полож";}s:5:"value";a:1:{s:41:"положительная степень";s:41:"положительная степень";}}i:17;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"17";s:4:"name";s:10:"форма";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:47;s:2:"47";}s:11:"short_value";a:1:{s:4:"кр";s:4:"кр";}s:5:"value";a:1:{s:25:"краткая форма";s:25:"краткая форма";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:20;s:2:"20";}s:11:"short_value";a:1:{s:6:"с.р.";s:6:"с.р.";}s:5:"value";a:1:{s:21:"средний род";s:21:"средний род";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;

    }

    /**
     * сильнейший
     * @return mixed
     */
    private function getPoint_prevosh_2alt()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:2;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:20:"сильнейший";s:11:"id_sentence";s:23:"55ad040f612a94.27530797";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"5c14cafe-2d69-11e2-bf9f-d3cf0a64751c";s:9:"word_form";s:20:"сильнейший";s:12:"initial_form";s:20:"сильнейший";s:13:"id_word_class";s:1:"3";s:15:"name_word_class";s:28:"прилагательное";s:10:"parameters";a:4:{i:17;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"17";s:4:"name";s:10:"форма";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:46;s:2:"46";}s:11:"short_value";a:1:{s:8:"полн";s:8:"полн";}s:5:"value";a:1:{s:23:"полная форма";s:23:"полная форма";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:19;s:2:"19";}s:11:"short_value";a:1:{s:6:"м.р.";s:6:"м.р.";}s:5:"value";a:1:{s:21:"мужской род";s:21:"мужской род";}}i:13;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"13";s:4:"name";s:10:"падеж";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:32;s:2:"32";}s:11:"short_value";a:1:{s:6:"и.п.";s:6:"и.п.";}s:5:"value";a:1:{s:24:"именительный";s:24:"именительный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        $point->dw->parameters[15] = new MorphAttribute();
        $point->dw->parameters[15]->id_value_attr = ['43' => 43];
        $point->dw->parameters[13]->id_value_attr = ['32' => 32, '35' => 35];
        return $point;
    }

    // глагол
    private function getPoint_falsePartOfSpeech()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:8:"тихо";s:11:"id_sentence";s:23:"55ad02ac99ab01.48590573";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"1a06c2dc-2d6b-11e2-bcbf-539839b39787";s:9:"word_form";s:8:"тихо";s:12:"initial_form";s:10:"тихий";s:13:"id_word_class";s:1:"3";s:15:"name_word_class";s:28:"прилагательное";s:10:"parameters";a:4:{i:15;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"15";s:4:"name";s:33:"степень сравнения";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:41;s:2:"41";}s:11:"short_value";a:1:{s:10:"полож";s:10:"полож";}s:5:"value";a:1:{s:41:"положительная степень";s:41:"положительная степень";}}i:17;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"17";s:4:"name";s:10:"форма";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:47;s:2:"47";}s:11:"short_value";a:1:{s:4:"кр";s:4:"кр";}s:5:"value";a:1:{s:25:"краткая форма";s:25:"краткая форма";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:20;s:2:"20";}s:11:"short_value";a:1:{s:6:"с.р.";s:6:"с.р.";}s:5:"value";a:1:{s:21:"средний род";s:21:"средний род";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        $point->dw->id_word_class = 1;
        return $point;
    }
}