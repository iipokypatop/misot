<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/07/15
 * Time: 20:14
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Narechie;

use Aot\RussianMorphology\ChastiRechi\Narechie\Factory;
use MorphAttribute;

class NarechieTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Narechie\Factory::class, $factory);
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Narechie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Sravnitelnaya::class, $result[0]->stepen_sravneniia);
    }

    public function testBuild_Success_point2()
    {
        $point = $this->getPoint2();
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Narechie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\ClassNull::class, $result[0]->stepen_sravneniia);
    }

    protected function buildFactory($point)
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
     * лучше
     * Возвращает точку
     * @return object
     */
    protected function getPoint()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:10:"лучше";s:11:"id_sentence";s:23:"55acfef1e32074.93903197";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"1a2abeee-2d75-11e2-a975-1b3aaa47306f";s:9:"word_form";s:10:"лучше";s:12:"initial_form";s:10:"лучше";s:13:"id_word_class";s:2:"12";s:15:"name_word_class";s:14:"наречие";s:10:"parameters";a:0:{}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        $point->dw->parameters[15] = new MorphAttribute();
        $point->dw->parameters[15]->id_morph_attr = 15;
        $point->dw->parameters[15]->id_value_attr = [ '42' => 42 ];
        return $point;
    }

    /**
     * быстро
     * @return mixed
     */
    protected function getPoint2()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:12:"быстро";s:11:"id_sentence";s:23:"55acffb26da731.45366072";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"06eaa240-2d75-11e2-99a3-7b51ba0f2d5d";s:9:"word_form";s:12:"быстро";s:12:"initial_form";s:12:"быстро";s:13:"id_word_class";s:2:"12";s:15:"name_word_class";s:14:"наречие";s:10:"parameters";a:0:{}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }

}