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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Null::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj::class, $result[0]->perehodnost);
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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Null::class, $result[0]->vozvratnost);
    }

    public function _testBuild_Success3()
    {
        $point = $this->getPoint3(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base::class, $result[0]);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Null::class, $result[0]->vozvratnost);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Nesovershennyj::class, $result[1]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj::class, $result[1]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Null::class, $result[1]->vozvratnost);
    }

    public function _testBuild_wo_vid()
    {
        # убираем вид
        $point_wo_vid = $this->getPoint();
        unset($point_wo_vid->dw->parameters->{VIEW_ID});
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
        $json_p = <<<JSON
{
        "kw": 1,
        "ks": 0,
        "dw": {
            "id_word_form": "55a527c1941f3",
            "initial_form": "пролететь",
            "name_word_class": "деепричастие",
            "id_word_class": 11,
            "parameters": {
                "5": {
                    "id_morph_attr": 5,
                    "name": "время",
                    "id_value_attr": {
                        "12": 12
                    },
                    "short_value": {
                        "прош": "прош"
                    },
                    "value": {
                        "прошедшее": "прошедшее"
                    }
                },
                "3": {
                    "id_morph_attr": 3,
                    "name": "переходность",
                    "id_value_attr": {
                        "6": 6
                    },
                    "short_value": {
                        "перех": "перех"
                    },
                    "value": {
                        "переходный": "переходный"
                    }
                },
                "1": {
                    "id_morph_attr": 1,
                    "name": "вид",
                    "id_value_attr": {
                        "2": 2
                    },
                    "short_value": {
                        "сов": "сов"
                    },
                    "value": {
                        "совершенный": "совершенный"
                    }
                }
            }
        },
        "ps": "predicate",
        "O": "adjunct_verb",
        "Oz": "55a527c193ee6",
        "direction": "y",
        "id_sentence": "55a527c192f9e9.66186161",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }


    private function getPoint2()
    {
        $json_p = <<<JSON
{
        "kw": 2,
        "ks": 0,
        "dw": {
            "id_word_form": "55a51f3361433",
            "initial_form": "прятаться",
            "name_word_class": "деепричастие",
            "id_word_class": 11,
            "parameters": {
                "5": {
                    "id_morph_attr": 5,
                    "name": "время",
                    "id_value_attr": {
                        "11": 11
                    },
                    "short_value": {
                        "наст": "наст"
                    },
                    "value": {
                        "настоящее": "настоящее"
                    }
                },
                "1": {
                    "id_morph_attr": 1,
                    "name": "вид",
                    "id_value_attr": {
                        "3": 3
                    },
                    "short_value": {
                        "несов": "несов"
                    },
                    "value": {
                        "несовершенный": "несовершенный"
                    }
                }
            }
        },
        "ps": "predicate",
        "O": "adjunct_verb",
        "Oz": "55a51f33610de",
        "direction": "y",
        "id_sentence": "55a51f33605cb4.22572852",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }

    private function getPoint3()
    {
        $json_p = <<<JSON
{
        "kw": 2,
        "ks": 0,
        "dw": {
            "id_word_form": "55a51f3361433",
            "initial_form": "прятаться",
            "name_word_class": "деепричастие",
            "id_word_class": 11,
            "parameters": {
                "5": {
                    "id_morph_attr": 5,
                    "name": "время",
                    "id_value_attr": {
                        "11": 11
                    },
                    "short_value": {
                        "наст": "наст"
                    },
                    "value": {
                        "настоящее": "настоящее"
                    }
                },
                "1": {
                    "id_morph_attr": 1,
                    "name": "вид",
                    "id_value_attr": {
                        "2": 2,
                        "3": 3
                    },
                    "short_value": {
                        "несов": "несов"
                    },
                    "value": {
                        "несовершенный": "несовершенный"
                    }
                }
            }
        },
        "ps": "predicate",
        "O": "adjunct_verb",
        "Oz": "55a51f33610de",
        "direction": "y",
        "id_sentence": "55a51f33605cb4.22572852",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }
}