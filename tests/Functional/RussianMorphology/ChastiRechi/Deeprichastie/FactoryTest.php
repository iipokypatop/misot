<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/07/15
 * Time: 19:16
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Deeprichastie;

use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Factory;
use Aot\RussianMorphology\FactoryException;

class FactoryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Factory::class, $factory);
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\ClassNull::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\ClassNull::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Nevozvratnyj::class, $result[0]->vozvratnost);
    }

    public function _testBuild_Success2()
    {
        $point = $this->getPoint2(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Nesovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\ClassNull::class, $result[0]->vozvratnost);
    }

    public function _testBuild_Success3()
    {
        $point = $this->getPoint3(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base::class, $result[0]);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\ClassNull::class, $result[0]->vozvratnost);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Nesovershennyj::class, $result[1]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj::class, $result[1]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\ClassNull::class, $result[1]->vozvratnost);
    }

    public function _testBuild_wo_vid()
    {
        # убираем вид
        $point_wo_vid = $this->getPoint();
        unset($point_wo_vid->dw->parameters[\Aot\MivarTextSemantic\Constants::VIEW_ID]);
        try {
            $this->buildFactory($point_wo_vid);
            $this->fail("Не должно было тут быть!");
        } catch (FactoryException $e) {
            $this->assertEquals("vid not defined", $e->getMessage());
            $this->assertEquals(24, $e->getCode());
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
     * пролетая
     * @return mixed
     */
    private function getPoint()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:16:"пролетая";s:11:"id_sentence";s:23:"55ace6c76bfce7.55154242";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"9a356e94-33d4-11e2-8340-bfa4d6942a66";s:9:"word_form";s:16:"пролетая";s:12:"initial_form";s:18:"пролетать";s:13:"id_word_class";s:2:"11";s:15:"name_word_class";s:24:"деепричастие";s:10:"parameters";a:3:{i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:23;s:2:"23";}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}i:23;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"23";s:4:"name";s:28:"неизменяемость";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:68;s:2:"68";}s:11:"short_value";a:1:{s:10:"неизм";s:10:"неизм";}s:5:"value";a:1:{s:24:"неизменяемый";s:24:"неизменяемый";}}i:5;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"5";s:4:"name";s:10:"время";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:11;s:2:"11";}s:11:"short_value";a:1:{s:8:"наст";s:8:"наст";}s:5:"value";a:1:{s:18:"настоящее";s:18:"настоящее";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }

    /**
     * прячась
     * @return mixed
     */
    private function getPoint2()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:14:"прячась";s:11:"id_sentence";s:23:"55af6812e6cfd1.20472767";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"312cb20e-33d9-11e2-88fc-cf8204ac02b7";s:9:"word_form";s:14:"прячась";s:12:"initial_form";s:18:"прятаться";s:13:"id_word_class";s:2:"11";s:15:"name_word_class";s:24:"деепричастие";s:10:"parameters";a:3:{i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:1:{i:23;s:2:"23";}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}i:23;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"23";s:4:"name";s:28:"неизменяемость";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:68;s:2:"68";}s:11:"short_value";a:1:{s:10:"неизм";s:10:"неизм";}s:5:"value";a:1:{s:24:"неизменяемый";s:24:"неизменяемый";}}i:5;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"5";s:4:"name";s:10:"время";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:11;s:2:"11";}s:11:"short_value";a:1:{s:8:"наст";s:8:"наст";}s:5:"value";a:1:{s:18:"настоящее";s:18:"настоящее";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }

    /**
     * прячась + доп. альтернатива в возвратности
     * @return mixed
     */
    private function getPoint3()
    {
        $ser = 'O:8:"PointWdw":6:{s:2:"kw";i:0;s:2:"ks";i:0;s:8:"count_dw";i:1;s:1:"w";O:4:"Word":7:{s:2:"kw";i:0;s:4:"word";s:14:"прячась";s:11:"id_sentence";s:23:"55af68c3d8be49.49828256";s:4:"data";b:0;s:8:"name_fio";b:0;s:4:"stop";b:0;s:3:"cut";b:0;}s:2:"dw";O:2:"Dw":6:{s:12:"id_word_form";s:36:"312cb20e-33d9-11e2-88fc-cf8204ac02b7";s:9:"word_form";s:14:"прячась";s:12:"initial_form";s:18:"прятаться";s:13:"id_word_class";s:2:"11";s:15:"name_word_class";s:24:"деепричастие";s:10:"parameters";a:3:{i:9;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"9";s:4:"name";s:24:"возвратность";s:17:"number_morph_attr";s:1:"2";s:13:"id_value_attr";a:2:{i:23;i:23;i:22;i:22;}s:11:"short_value";a:1:{s:12:"невозв";s:12:"невозв";}s:5:"value";a:1:{s:24:"невозвратный";s:24:"невозвратный";}}i:23;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:2:"23";s:4:"name";s:28:"неизменяемость";s:17:"number_morph_attr";s:1:"3";s:13:"id_value_attr";a:1:{i:68;s:2:"68";}s:11:"short_value";a:1:{s:10:"неизм";s:10:"неизм";}s:5:"value";a:1:{s:24:"неизменяемый";s:24:"неизменяемый";}}i:5;O:14:"MorphAttribute":6:{s:13:"id_morph_attr";s:1:"5";s:4:"name";s:10:"время";s:17:"number_morph_attr";s:1:"4";s:13:"id_value_attr";a:1:{i:11;s:2:"11";}s:11:"short_value";a:1:{s:8:"наст";s:8:"наст";}s:5:"value";a:1:{s:18:"настоящее";s:18:"настоящее";}}}}s:9:"key_point";i:0;}';
        $point = unserialize($ser);
        $point->id_sentence = '11111';
        return $point;
    }
}