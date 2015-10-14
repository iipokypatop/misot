<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13/07/15
 * Time: 16:47
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Glagol;

use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\Word;

use Aot\RussianMorphology\ChastiRechi\Glagol\Factory;
use Aot\RussianMorphology\FactoryException;

class FactoryTest extends \AotTest\AotDataStorage
{

    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Factory::class, $factory);
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint1();
        $result = $this->buildFactory($point);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Neperehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj::class, $result[0]->vozvratnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::class, $result[0]->vremya);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Null::class, $result[0]->razryad);

    }

    public function testBuild_Success2()
    {
        $point = $this->getPoint2();
        $result = $this->buildFactory($point);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj::class, $result[0]->vozvratnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::class, $result[0]->perehodnost);
//        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
//        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Nevozvratnyj::class, $result[0]->vozvratnost);
//        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Perehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::class, $result[0]->vremya);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Null::class, $result[0]->razryad);
    }

    public function testBuild_Success3WithAlternatives()
    {
        $point = $this->getPoint4();
        $result = $this->buildFactory($point);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj::class, $result[0]->vozvratnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::class, $result[0]->vremya);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Null::class, $result[0]->razryad);

    }

    public function testBuild_wo_vid()
    {
        # убираем число
        $point_wo_vid = $this->getPoint1();
        $false_vid = 222;
        $point_wo_vid->dw->parameters[\Aot\MivarTextSemantic\Constants::VIEW_ID]->id_value_attr = [$false_vid => $false_vid];
        try {
            $this->buildFactory($point_wo_vid);
            $this->fail("Не должно было тут быть!");
        } catch (\RuntimeException $e) {
            $this->assertEquals("Unsupported value exception = " . $false_vid, $e->getMessage());
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
     * пошел
     * @return mixed
     */
    protected function getPoint1()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:10:"пошел";s:11:"id_sentence";s:23:"55acf31b7c06d4.91529153";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"14181a56-33ba-11e2-9510-0771fd1be6a1";s:9:"word_form";s:10:"пошел";s:12:"initial_form";s:10:"пойти";s:13:"id_word_class";s:1:"1";s:15:"name_word_class";s:12:"глагол";s:10:"parameters";a:8:{i:1;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"1";s:4:"name";s:6:"вид";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:2;s:1:"2";}s:11:"short_value";a:1:{s:6:"сов";s:6:"сов";}s:5:"value";a:1:{s:22:"совершенный";s:22:"совершенный";}}i:2;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"2";s:4:"name";s:18:"спряжение";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:4;s:1:"4";}s:11:"short_value";a:1:{s:4:"1-е";s:4:"1-е";}s:5:"value";a:1:{s:4:"1-е";s:4:"1-е";}}i:3;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"3";s:4:"name";s:24:"переходность";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:7;s:1:"7";}s:11:"short_value";a:1:{s:14:"неперех";s:14:"неперех";}s:5:"value";a:1:{s:24:"непереходный";s:24:"непереходный";}}i:4;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"4";s:4:"name";s:20:"наклонение";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:8;s:1:"8";}s:11:"short_value";a:1:{s:14:"изъявит";s:14:"изъявит";}s:5:"value";a:1:{s:26:"изъявительное";s:26:"изъявительное";}}i:5;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"5";s:4:"name";s:10:"время";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:12;s:2:"12";}s:11:"short_value";a:1:{s:8:"прош";s:8:"прош";}s:5:"value";a:1:{s:18:"прошедшее";s:18:"прошедшее";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"8";s:13:"id_value_attr";a:1:{i:19;s:2:"19";}s:11:"short_value";a:1:{s:6:"м.р.";s:6:"м.р.";}s:5:"value";a:1:{s:21:"мужской род";s:21:"мужской род";}}i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"9";s:13:"id_value_attr";a:1:{i:23;s:2:"23";}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }

    /**
     * решил
     * @return mixed
     */
    protected function getPoint2()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:10:"решил";s:11:"id_sentence";s:23:"55acf3f6cbe917.10826198";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"a30ee8a8-3481-11e2-ae0e-c3132a8aeb21";s:9:"word_form";s:10:"решил";s:12:"initial_form";s:12:"решить";s:13:"id_word_class";s:1:"1";s:15:"name_word_class";s:12:"глагол";s:10:"parameters";a:8:{i:1;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"1";s:4:"name";s:6:"вид";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:2;s:1:"2";}s:11:"short_value";a:1:{s:6:"сов";s:6:"сов";}s:5:"value";a:1:{s:22:"совершенный";s:22:"совершенный";}}i:2;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"2";s:4:"name";s:18:"спряжение";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:5;s:1:"5";}s:11:"short_value";a:1:{s:4:"2-е";s:4:"2-е";}s:5:"value";a:1:{s:4:"2-е";s:4:"2-е";}}i:3;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"3";s:4:"name";s:24:"переходность";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:6;s:1:"6";}s:11:"short_value";a:1:{s:10:"перех";s:10:"перех";}s:5:"value";a:1:{s:20:"переходный";s:20:"переходный";}}i:4;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"4";s:4:"name";s:20:"наклонение";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:8;s:1:"8";}s:11:"short_value";a:1:{s:14:"изъявит";s:14:"изъявит";}s:5:"value";a:1:{s:26:"изъявительное";s:26:"изъявительное";}}i:5;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"5";s:4:"name";s:10:"время";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:12;s:2:"12";}s:11:"short_value";a:1:{s:8:"прош";s:8:"прош";}s:5:"value";a:1:{s:18:"прошедшее";s:18:"прошедшее";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"8";s:13:"id_value_attr";a:1:{i:19;s:2:"19";}s:11:"short_value";a:1:{s:6:"м.р.";s:6:"м.р.";}s:5:"value";a:1:{s:21:"мужской род";s:21:"мужской род";}}i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"9";s:13:"id_value_attr";a:1:{i:23;s:2:"23";}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;

    }

    /**
     * пойти
     * @return mixed
     */
    protected function getPoint3()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:10:"пойти";s:11:"id_sentence";s:23:"55acf4e2918fd2.51800003";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"1415b7fc-33ba-11e2-bf49-9b95eb498456";s:9:"word_form";s:10:"пойти";s:12:"initial_form";s:10:"пойти";s:13:"id_word_class";s:1:"1";s:15:"name_word_class";s:12:"глагол";s:10:"parameters";a:5:{i:1;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"1";s:4:"name";s:6:"вид";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:2;s:1:"2";}s:11:"short_value";a:1:{s:6:"сов";s:6:"сов";}s:5:"value";a:1:{s:22:"совершенный";s:22:"совершенный";}}i:2;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"2";s:4:"name";s:18:"спряжение";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:4;s:1:"4";}s:11:"short_value";a:1:{s:4:"1-е";s:4:"1-е";}s:5:"value";a:1:{s:4:"1-е";s:4:"1-е";}}i:3;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"3";s:4:"name";s:24:"переходность";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:6;s:1:"6";}s:11:"short_value";a:1:{s:10:"перех";s:10:"перех";}s:5:"value";a:1:{s:20:"переходный";s:20:"переходный";}}i:4;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"4";s:4:"name";s:20:"наклонение";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:8;s:1:"8";}s:11:"short_value";a:1:{s:14:"изъявит";s:14:"изъявит";}s:5:"value";a:1:{s:26:"изъявительное";s:26:"изъявительное";}}i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"9";s:13:"id_value_attr";a:1:{i:23;s:2:"23";}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }

    /**
     * решился + вид
     * @return mixed
     */
    protected function getPoint4()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:14:"решился";s:11:"id_sentence";s:23:"55acf571d9d674.84407444";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"a4879c34-3481-11e2-aee2-6b20e4d4014e";s:9:"word_form";s:14:"решился";s:12:"initial_form";s:16:"решиться";s:13:"id_word_class";s:1:"1";s:15:"name_word_class";s:12:"глагол";s:10:"parameters";a:8:{i:1;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"1";s:4:"name";s:6:"вид";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:2;s:1:"2";}s:11:"short_value";a:1:{s:6:"сов";s:6:"сов";}s:5:"value";a:1:{s:22:"совершенный";s:22:"совершенный";}}i:2;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"2";s:4:"name";s:18:"спряжение";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:4;s:1:"4";}s:11:"short_value";a:1:{s:4:"1-е";s:4:"1-е";}s:5:"value";a:1:{s:4:"1-е";s:4:"1-е";}}i:3;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"3";s:4:"name";s:24:"переходность";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:6;s:1:"6";}s:11:"short_value";a:1:{s:10:"перех";s:10:"перех";}s:5:"value";a:1:{s:20:"переходный";s:20:"переходный";}}i:4;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"4";s:4:"name";s:20:"наклонение";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:8;s:1:"8";}s:11:"short_value";a:1:{s:14:"изъявит";s:14:"изъявит";}s:5:"value";a:1:{s:26:"изъявительное";s:26:"изъявительное";}}i:5;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"5";s:4:"name";s:10:"время";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:12;s:2:"12";}s:11:"short_value";a:1:{s:8:"прош";s:8:"прош";}s:5:"value";a:1:{s:18:"прошедшее";s:18:"прошедшее";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"8";s:13:"id_value_attr";a:1:{i:19;s:2:"19";}s:11:"short_value";a:1:{s:6:"м.р.";s:6:"м.р.";}s:5:"value";a:1:{s:21:"мужской род";s:21:"мужской род";}}i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"9";s:13:"id_value_attr";a:1:{i:23;s:2:"23";}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        $point->dw->parameters[1]->id_value_attr = ['2' => 2, '3' => 3];
        return $point;
    }

    /**
     * решился
     * @return mixed
     */
    protected function getPoint5()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:14:"решился";s:11:"id_sentence";s:23:"55acf571d9d674.84407444";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"a4879c34-3481-11e2-aee2-6b20e4d4014e";s:9:"word_form";s:14:"решился";s:12:"initial_form";s:16:"решиться";s:13:"id_word_class";s:1:"1";s:15:"name_word_class";s:12:"глагол";s:10:"parameters";a:8:{i:1;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"1";s:4:"name";s:6:"вид";s:17:"number_morph_attr";s:1:"1";s:13:"id_value_attr";a:1:{i:2;s:1:"2";}s:11:"short_value";a:1:{s:6:"сов";s:6:"сов";}s:5:"value";a:1:{s:22:"совершенный";s:22:"совершенный";}}i:2;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"2";s:4:"name";s:18:"спряжение";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:4;s:1:"4";}s:11:"short_value";a:1:{s:4:"1-е";s:4:"1-е";}s:5:"value";a:1:{s:4:"1-е";s:4:"1-е";}}i:3;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"3";s:4:"name";s:24:"переходность";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:6;s:1:"6";}s:11:"short_value";a:1:{s:10:"перех";s:10:"перех";}s:5:"value";a:1:{s:20:"переходный";s:20:"переходный";}}i:4;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"4";s:4:"name";s:20:"наклонение";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:8;s:1:"8";}s:11:"short_value";a:1:{s:14:"изъявит";s:14:"изъявит";}s:5:"value";a:1:{s:26:"изъявительное";s:26:"изъявительное";}}i:5;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"5";s:4:"name";s:10:"время";s:17:"number_morph_attr";s:1:"5";s:13:"id_value_attr";a:1:{i:12;s:2:"12";}s:11:"short_value";a:1:{s:8:"прош";s:8:"прош";}s:5:"value";a:1:{s:18:"прошедшее";s:18:"прошедшее";}}i:6;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"6";s:4:"name";s:10:"число";s:17:"number_morph_attr";s:1:"6";s:13:"id_value_attr";a:1:{i:14;s:2:"14";}s:11:"short_value";a:1:{s:8:"ед.ч.";s:8:"ед.ч.";}s:5:"value";a:1:{s:24:"единственное";s:24:"единственное";}}i:8;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"8";s:4:"name";s:6:"род";s:17:"number_morph_attr";s:1:"8";s:13:"id_value_attr";a:1:{i:19;s:2:"19";}s:11:"short_value";a:1:{s:6:"м.р.";s:6:"м.р.";}s:5:"value";a:1:{s:21:"мужской род";s:21:"мужской род";}}i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"9";s:13:"id_value_attr";a:1:{i:23;s:2:"23";}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }


}