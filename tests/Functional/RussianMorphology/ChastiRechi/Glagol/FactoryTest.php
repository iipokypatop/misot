<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13/07/15
 * Time: 16:47
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Glagol;


use Aot\RussianMorphology\ChastiRechi\Glagol\Factory;
use Aot\RussianMorphology\FactoryException;

class FactoryTest extends \AotTest\AotDataStorage
{

    public function testLaunch(){
//        $factory = Factory::get();
//        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Factory::class, $factory);
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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Null::class, $result[0]->vozvratnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::class, $result[0]->vremya);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Razryad\Null::class, $result[0]->razryad);

    }

    public function testBuild_Success2()
    {
        $point = $this->getPoint2();
        $result = $this->buildFactory($point);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Null::class, $result[0]->vozvratnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::class, $result[0]->vremya);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Razryad\Null::class, $result[0]->razryad);
    }

    public function testBuild_Success3WithAlternatives()
    {
        $point = $this->getPoint4();
        $result = $this->buildFactory($point);
        print_r($result);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Neperehodnyj::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Null::class, $result[0]->vozvratnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::class, $result[0]->vremya);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Razryad\Null::class, $result[0]->razryad);

    }

    public function testBuild_wo_vid(){
        # убираем число
        $point_wo_vid = $this->getPoint1();
        unset($point_wo_vid->dw->parameters->{VIEW_ID});
        try{
            $this->buildFactory($point_wo_vid);
            $this->fail("Не должно было тут быть!");
        }
        catch(\Exception $e){
            $this->assertInstanceOf(FactoryException::class, $e);
            $this->assertEquals("vid not defined", $e->getMessage());
            $this->assertEquals(24, $e->getCode());
        }
    }

    protected function buildFactory($point)
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

    protected function getPoint1()
    {
        $json = <<<JSON

{
        "kw": 1,
        "ks": 0,
        "dw": {
            "id_word_form": "55a3b15f556fd",
            "initial_form": "пойти",
            "name_word_class": "глагол",
            "id_word_class": 1,
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
                "8": {
                    "id_morph_attr": 8,
                    "name": "род",
                    "id_value_attr": {
                        "19": 19
                    },
                    "short_value": {
                        "м.р.": "м.р."
                    },
                    "value": {
                        "мужской род": "мужской род"
                    }
                },
                "6": {
                    "id_morph_attr": 6,
                    "name": "число",
                    "id_value_attr": {
                        "14": 14
                    },
                    "short_value": {
                        "ед.ч.": "ед.ч."
                    },
                    "value": {
                        "единственное число": "единственное число"
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
        "O": "subject_predicate",
        "Oz": "55a3b15f55583",
        "direction": "y",
        "id_sentence": "55a3b15f345c10.07241727",
        "denial": ""
    }
JSON;

        return json_decode($json);
    }

    protected function getPoint2()
    {
        $json = <<<JSON

{
        "kw": 1,
        "ks": 0,
        "dw": {
            "id_word_form": "55a3b258d076c",
            "initial_form": "решить",
            "name_word_class": "глагол",
            "id_word_class": 1,
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
                "8": {
                    "id_morph_attr": 8,
                    "name": "род",
                    "id_value_attr": {
                        "19": 19
                    },
                    "short_value": {
                        "м.р.": "м.р."
                    },
                    "value": {
                        "мужской род": "мужской род"
                    }
                },
                "6": {
                    "id_morph_attr": 6,
                    "name": "число",
                    "id_value_attr": {
                        "14": 14
                    },
                    "short_value": {
                        "ед.ч.": "ед.ч."
                    },
                    "value": {
                        "единственное число": "единственное число"
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
        "O": "subject_predicate",
        "Oz": "55a3b258d04dd",
        "direction": "y",
        "id_sentence": "55a3b258cf5e73.53032849",
        "denial": ""
    }
JSON;

        return json_decode($json);
    }


    protected function getPoint3()
    {
        $json = <<<JSON
{
        "kw": 2,
        "ks": 0,
        "dw": {
            "id_word_form": "55a3b258d08c7",
            "initial_form": "пойти",
            "name_word_class": "глагол",
            "id_word_class": 1,
            "parameters": {
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
        "O": "complex_predicates",
        "Oz": "55a3b258d0570",
        "direction": "y",
        "id_sentence": "55a3b258cf5e73.53032849",
        "denial": ""
    }
JSON;

        return json_decode($json);
    }

    protected function getPoint4()
    {
        $json = <<<JSON

{
        "kw": 1,
        "ks": 0,
        "dw": {
            "id_word_form": "55a3b312df8e4",
            "initial_form": "решиться",
            "name_word_class": "глагол",
            "id_word_class": 1,
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
                "8": {
                    "id_morph_attr": 8,
                    "name": "род",
                    "id_value_attr": {
                        "19": 19
                    },
                    "short_value": {
                        "м.р.": "м.р."
                    },
                    "value": {
                        "мужской род": "мужской род"
                    }
                },
                "6": {
                    "id_morph_attr": 6,
                    "name": "число",
                    "id_value_attr": {
                        "14": 14
                    },
                    "short_value": {
                        "ед.ч.": "ед.ч."
                    },
                    "value": {
                        "единственное число": "единственное число"
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
                        "сов": "сов"
                    },
                    "value": {
                        "совершенный": "совершенный"
                    }
                }
            }
        },
        "ps": "predicate",
        "O": "subject_predicate",
        "Oz": "55a3b312df705",
        "direction": "y",
        "id_sentence": "55a3b312dec015.13555078",
        "denial": ""
    }
JSON;

        return json_decode($json);
    }

    protected function getPoint5()
    {
        $json = <<<JSON


{
        "kw": 1,
        "ks": 0,
        "dw": {
            "id_word_form": "55a3b312df8e4",
            "initial_form": "решиться",
            "name_word_class": "глагол",
            "id_word_class": 1,
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
                "8": {
                    "id_morph_attr": 8,
                    "name": "род",
                    "id_value_attr": {
                        "19": 19
                    },
                    "short_value": {
                        "м.р.": "м.р."
                    },
                    "value": {
                        "мужской род": "мужской род"
                    }
                },
                "6": {
                    "id_morph_attr": 6,
                    "name": "число",
                    "id_value_attr": {
                        "14": 14
                    },
                    "short_value": {
                        "ед.ч.": "ед.ч."
                    },
                    "value": {
                        "единственное число": "единственное число"
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
        "O": "subject_predicate",
        "Oz": "55a3b312df705",
        "direction": "y",
        "id_sentence": "55a3b312dec015.13555078",
        "denial": ""
    }
JSON;

        return json_decode($json);
    }




}