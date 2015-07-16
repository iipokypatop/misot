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

    public function _testWDW(){
//        $const = new \Constants();
//        $const->defineConstants();
//        $syntax_parser = new \SyntaxParserManager();
//        $text = 'человек';
//        $syntax_parser->reg_parser->parse_text($text);
//        $syntax_parser->create_dictionary_word();
//        $wdw = [];
//        foreach ($syntax_parser->reg_parser->get_sentences() as $sentence) {
//            $wdw[] = $syntax_parser->create_sentence_space($sentence);
//        }
//
//        echo $wdw_s = serialize($wdw);
//        print_r(unserialize($wdw_s));
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Perehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Null::class, $result[0]->vozvratnost);
    }

    public function testBuild_Success2()
    {
        $point = $this->getPoint2(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Nesovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Null::class, $result[0]->vozvratnost);
    }

    public function testBuild_Success3()
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

    public function testBuild_wo_vid()
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

    private function getPoint()
    {
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