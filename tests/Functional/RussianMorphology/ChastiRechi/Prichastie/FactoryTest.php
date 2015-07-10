<?php

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Prichastie;


use Aot\RussianMorphology\ChastiRechi\Prichastie\Factory;

class FactoryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Factory::class, $factory);
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint();

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
        $result = Factory::get()->build($dw, $word);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Base::class, $result[1]);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Polnaya::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Perehodnij::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Nesovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Nevozvratnyj::class, $result[0]->vozvratnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Nastoyaschee::class, $result[0]->vremya);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Razryad\Dejstvitelnyj::class, $result[0]->razryad);
//        print_r($result);
    }


    public function testBuild_Failed(){
        $point = $this->getPoint();
        print_r($point);
        # убираем время
        $point_wo_vremya = $point;
        unset($point_wo_vremya->dw->parameters->{5});

        # убираем число
        $point_wo_chislo = $point;
        unset($point_wo_chislo->dw->parameters->{6});

        # убираем форму
        $point_wo_forma = $point;
        unset($point_wo_forma->dw->parameters->{});

        # убираем падеж
        $point_wo_padeszh = $point;
        unset($point_wo_padeszh->dw->parameters->{13});

        # убираем переходность
        $point_wo_perehodnost = $point;
        unset($point_wo_perehodnost->dw->parameters->{3});

        # убираем род
        $point_wo_rod = $point;
        unset($point_wo_rod->dw->parameters->{8});

        # убираем вид
        $point_wo_vid = $point;
        unset($point_wo_vid->dw->parameters->{});

        # убираем возвратность
        $point_wo_vozvratnost = $point;
        unset($point_wo_vozvratnost->dw->parameters->{});

        # убираем разряд
        $point_wo_razryad = $point;
        unset($point_wo_razryad->dw->parameters->{16});


//        print_r($point_wo_vremya);
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
        $result = Factory::get()->build($dw, $word);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Polnaya::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Perehodnij::class, $result[0]->perehodnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Nesovershennyj::class, $result[0]->vid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Nevozvratnyj::class, $result[0]->vozvratnost);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Nastoyaschee::class, $result[0]->vremya);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Razryad\Dejstvitelnyj::class, $result[0]->razryad);
//        print_r($result);
    }
    /**
     * Возвращает точку
     * @return object
     */
    protected function getPoint(){
        $json_p = <<<JSON
{
            "kw": 0,
        "ks": 0,
        "dw": {
            "id_word_form": "559e9e4664db1",
            "initial_form": "читать",
            "name_word_class": "причастие",
            "id_word_class": 5,
            "parameters": {
                "11": {
                    "id_morph_attr": 11,
                    "name": "одуш-неодуш",
                    "id_value_attr": {
                        "27": 27
                    },
                    "short_value": {
                        "неодуш": "неодуш"
                    },
                    "value": {
                        "неодушевленное": "неодушевленное"
                    }
                },
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
                "16": {
                    "id_morph_attr": 16,
                    "name": "разряд причастия",
                    "id_value_attr": {
                        "44": 44
                    },
                    "short_value": {
                        "действ": "действ"
                    },
                    "value": {
                        "действительное": "действительное"
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
                "13": {
                    "id_morph_attr": 13,
                    "name": "падеж",
                    "id_value_attr": {

                        "32": 32,

                        "33": 33

                    },
                    "short_value": {
                        "и.п.": "и.п."
                    },
                    "value": {
                        "именительный": "именительный"
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
        "ps": "attribute",
        "O": "attribute_noun",
        "Oz": "559e9e4664d1c",
        "direction": "y",
        "id_sentence": "559e9e46641b03.16380905",
        "denial": ""
        }
JSON;

        return json_decode($json_p);
    }

}