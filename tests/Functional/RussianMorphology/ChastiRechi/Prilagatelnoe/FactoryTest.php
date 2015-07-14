<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/07/15
 * Time: 14:48
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Prilagatelnoe;


use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Factory;

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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Null::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Null::class, $result[0]->stepen_sravneniia);
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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Sravnitelnaia::class, $result[0]->stepen_sravneniia);
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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Null::class, $result[0]->stepen_sravneniia);
    }

    public function testBuild_Success_Point_prevosh_2alt()
    {
        $point = $this->getPoint_prevosh_2alt(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class, $result[0]);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class, $result[1]);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Edinstvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Null::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Prevoshodnaia::class, $result[0]->stepen_sravneniia);
    }

    public function testBuild_Success_Point_sravn_falseAlt()
    {
        $point = $this->getPoint_sravn_falseAlt(); // берем точку тестовую
        $result = $this->buildFactory($point);

        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Null::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Null::class, $result[0]->forma);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Null::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Sravnitelnaia::class, $result[0]->stepen_sravneniia);
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

    // обычное прилагательное
    private function getPoint_norm()
    {
        $json_p = <<<JSON
{
        "kw": 0,
        "ks": 0,
        "dw": {
            "id_word_form": "55a4e7141a0ef",
            "initial_form": "большой",
            "name_word_class": "прилагательное",
            "id_word_class": 3,
            "parameters": {
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
                "13": {
                    "id_morph_attr": 13,
                    "name": "падеж",
                    "id_value_attr": {
                        "32": 32
                    },
                    "short_value": {
                        "и.п.": "и.п."
                    },
                    "value": {
                        "именительный": "именительный"
                    }
                },
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
                }
            }
        },
        "ps": "attribute",
        "O": "attribute_noun",
        "Oz": "55a4e71419edd",
        "direction": "y",
        "id_sentence": "55a4e714192687.89169073",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }

    // сравнительное прилагательное
    private function getPoint_sravn()
    {
        $json_p = <<<JSON
{
        "kw": 1,
        "ks": 0,
        "dw": {
            "id_word_form": "55a501bb296c4",
            "initial_form": "большой",
            "name_word_class": "прилагательное",
            "id_word_class": 3,
            "parameters": {
                "15": {
                    "id_morph_attr": 15,
                    "name": "степень сравнения",
                    "id_value_attr": {
                        "42": 42
                    },
                    "short_value": {
                        "сравн": "сравн"
                    },
                    "value": {
                        "сравнительная степень": "сравнительная степень"
                    }
                },
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
                }
            }
        },
        "ps": "adjunct",
        "O": "adjunct_verb",
        "Oz": "55a501bb295ae",
        "direction": "y",
        "id_sentence": "55a501bb28dad6.78469914",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }

    // сравнительное прилагательное с невалидной альтернативой
    private function getPoint_sravn_falseAlt()
    {
        $json_p = <<<JSON
{
        "kw": 1,
        "ks": 0,
        "dw": {
            "id_word_form": "55a501bb296c4",
            "initial_form": "большой",
            "name_word_class": "прилагательное",
            "id_word_class": 3,
            "parameters": {
                "15": {
                    "id_morph_attr": 15,
                    "name": "степень сравнения",
                    "id_value_attr": {
                        "42": 42
                    },
                    "short_value": {
                        "сравн": "сравн"
                    },
                    "value": {
                        "сравнительная степень": "сравнительная степень"
                    }
                },
                "11": {
                    "id_morph_attr": 11,
                    "name": "одуш-неодуш",
                    "id_value_attr": {
                        "26": 26,
                        "27": 27
                    },
                    "short_value": {
                        "неодуш": "неодуш"
                    },
                    "value": {
                        "неодушевленное": "неодушевленное"
                    }
                }
            }
        },
        "ps": "adjunct",
        "O": "adjunct_verb",
        "Oz": "55a501bb295ae",
        "direction": "y",
        "id_sentence": "55a501bb28dad6.78469914",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }

    // прилагательное в краткой форме
    private function getPoint_shortForm()
    {
        $json_p = <<<JSON
{
        "kw": 0,
        "ks": 0,
        "dw": {
            "id_word_form": "55a501fe0e9a1",
            "initial_form": "тихий",
            "name_word_class": "прилагательное",
            "id_word_class": 3,
            "parameters": {
                "17": {
                    "id_morph_attr": 17,
                    "name": "форма",
                    "id_value_attr": {
                        "47": 47
                    },
                    "short_value": {
                        "кр": "кр"
                    },
                    "value": {
                        "краткая форма": "краткая форма"
                    }
                },
                "8": {
                    "id_morph_attr": 8,
                    "name": "род",
                    "id_value_attr": {
                        "20": 20
                    },
                    "short_value": {
                        "с.р.": "с.р."
                    },
                    "value": {
                        "средний род": "средний род"
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
                }
            }
        },
        "ps": "adjunct",
        "O": "adjunct_verb",
        "Oz": "55a501fe0e6ee",
        "direction": "y",
        "id_sentence": "55a501fe0dac79.47855019",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }

    // прилагательное в превосходной форме с альтернативами
    private function getPoint_prevosh_2alt()
    {
        $json_p = <<<JSON
{
        "kw": 0,
        "ks": 0,
        "dw": {
            "id_word_form": "55a5034310861",
            "initial_form": "красивый",
            "name_word_class": "прилагательное",
            "id_word_class": 3,
            "parameters": {
                "15": {
                    "id_morph_attr": 15,
                    "name": "степень сравнения",
                    "id_value_attr": {
                        "43": 43
                    },
                    "short_value": {
                        "прев": "прев"
                    },
                    "value": {
                        "превосходная": "превосходная"
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
                "13": {
                    "id_morph_attr": 13,
                    "name": "падеж",
                    "id_value_attr": {
                        "32": 32,
                        "35": 35
                    },
                    "short_value": {
                        "и.п.": "и.п."
                    },
                    "value": {
                        "именительный": "именительный"
                    }
                },
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
                }
            }
        },
        "ps": "attribute",
        "O": "attribute_noun",
        "Oz": "55a50343106bd",
        "direction": "y",
        "id_sentence": "55a503430feff2.79238495",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }

    // глагол
    private function getPoint_falsePartOfSpeech()
    {
        $json_p = <<<JSON
{
        "kw": 0,
        "ks": 0,
        "dw": {
            "id_word_form": "55a5034310861",
            "initial_form": "красивый",
            "name_word_class": "прилагательное",
            "id_word_class": 1,
            "parameters": {
                "15": {
                    "id_morph_attr": 15,
                    "name": "степень сравнения",
                    "id_value_attr": {
                        "43": 43
                    },
                    "short_value": {
                        "прев": "прев"
                    },
                    "value": {
                        "превосходная": "превосходная"
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
                "13": {
                    "id_morph_attr": 13,
                    "name": "падеж",
                    "id_value_attr": {
                        "32": 32,
                        "35": 35
                    },
                    "short_value": {
                        "и.п.": "и.п."
                    },
                    "value": {
                        "именительный": "именительный"
                    }
                },
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
                }
            }
        },
        "ps": "attribute",
        "O": "attribute_noun",
        "Oz": "55a50343106bd",
        "direction": "y",
        "id_sentence": "55a503430feff2.79238495",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }
}