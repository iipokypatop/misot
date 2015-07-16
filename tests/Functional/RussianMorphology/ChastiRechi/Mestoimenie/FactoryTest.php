<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 02:01
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Mestoimenie;


use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Factory;
use MivarTest\PHPUnitHelper;
use MorphAttribute;

class FactoryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Factory::class, $factory);
    }

    public function _testWDW(){
        $const = new \Constants();
        $const->defineConstants();
        $syntax_parser = new \SyntaxParserManager();
        $text = 'оно';
        $syntax_parser->reg_parser->parse_text($text);
        $syntax_parser->create_dictionary_word();
        $wdw = [];
        foreach ($syntax_parser->reg_parser->get_sentences() as $sentence) {
            $wdw[] = $syntax_parser->create_sentence_space($sentence);
        }

//        print_r($wdw);
        $wdw_s = json_encode($wdw, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
//        $wdw_uns = json_decode($wdw_s);
        print_r($wdw_s);
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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class, $result[0]->razryad);
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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Zhenskij::class, $result[0]->rod);
    }

    public function testBuild_Success3()
    {
        $point = $this->getPoint3(); // берем точку тестовую
        // убираем падеж
        unset($point->dw->parameters->{13});
        // убираем число
        unset($point->dw->parameters->{6});
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Null::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Pervoe::class, $result[0]->litso);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Null::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Null::class, $result[0]->rod);
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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Null::class, $result[0]->rod);
    }

    public function testBuild_Success5()
    {
        $point = $this->getPoint5(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Mnozhestvennoe::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Null::class, $result[0]->litso);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Roditelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Null::class, $result[0]->rod);
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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class, $result[0]->razryad);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Srednij::class, $result[0]->rod);
    }

    public function testBuild_PadeszhFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем падеж на несуществующий
        $point->dw->parameters->{CASE_ID}->id_value_attr = [111 => 111];
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
        $point->dw->parameters->{NUMBER_ID}->id_value_attr = [111 => 111];
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
        $point->dw->parameters->{PERSON_ID}->id_value_attr = [111 => 111];
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
        $point->dw->parameters->{GENUS_ID}->id_value_attr = [111 => 111];
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
        $point->dw->parameters->{\OldAotConstants::RANK_PRONOUNS()} = new MorphAttribute();
        // подменяем разряд на несуществующий
        $point->dw->parameters->{\OldAotConstants::RANK_PRONOUNS()}->id_value_attr = [111 => 111];
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
     * Местоимение "он"
     * @return array
     */
    private function getPoint()
    {
        $json_p = <<<JSON
{
                    "kw": 0,
                    "ks": 0,
                    "id_sentence": "55a6dd7621c619.06223093",
                    "dw": {
                        "id_word_form": "e9aa76c2-2f10-11e2-b6c7-2710c1e7728c",
                        "word_form": "он",
                        "initial_form": "он",
                        "id_word_class": 4,
                        "name_word_class": "местоимение",
                        "parameters": {
                            "7": {
                                "id_morph_attr": "7",
                                "name": "лицо",
                                "number_morph_attr": "2",
                                "id_value_attr": {
                                    "18": 18
                                },
                                "short_value": {
                                    "3-е": "3-е"
                                },
                                "value": {
                                    "3 лицо": "3 лицо"
                                }
                            },
                            "13": {
                                "id_morph_attr": "13",
                                "name": "падеж",
                                "number_morph_attr": "3",
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
                            "6": {
                                "id_morph_attr": "6",
                                "name": "число",
                                "number_morph_attr": "4",
                                "id_value_attr": {
                                    "14": 14
                                },
                                "short_value": {
                                    "ед.ч.": "ед.ч."
                                },
                                "value": {
                                    "единственное": "единственное"
                                }
                            },
                            "8": {
                                "id_morph_attr": "8",
                                "name": "род",
                                "number_morph_attr": "5",
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
                            "25": {
                                "id_morph_attr": "25",
                                "name": "тип местоимения",
                                "number_morph_attr": "6",
                                "id_value_attr": {
                                    "70": 70
                                },
                                "short_value": {
                                    "одуш": "одуш"
                                },
                                "value": {
                                    "личное (одуш)": "личное (одуш)"
                                }
                            }
                        }
                    }
                }
JSON;

        return json_decode($json_p);
    }


    /**
     * Местоимение "ее"
     * @return array
     */
    private function getPoint2()
    {
        $json_p = <<<JSON
{
                    "kw": 0,
                    "ks": 0,
                    "count_dw": 2,
                    "id_sentence": "55a6dd7621c619.06223093",
                    "w": {
                        "kw": 0,
                        "word": "ее",
                        "id_sentence": "55a77bb85188f3.25946494",
                        "data": false,
                        "name_fio": false,
                        "stop": false,
                        "cut": false
                    },
                    "dw": {
                        "id_word_form": "e9c4b0a0-2f10-11e2-8636-7703a1d85872",
                        "word_form": "ее",
                        "initial_form": "она",
                        "id_word_class": 4,
                        "name_word_class": "местоимение",
                        "parameters": {
                            "7": {
                                "id_morph_attr": "7",
                                "name": "лицо",
                                "number_morph_attr": "2",
                                "id_value_attr": {
                                    "18": 18
                                },
                                "short_value": {
                                    "3-е": "3-е"
                                },
                                "value": {
                                    "3 лицо": "3 лицо"
                                }
                            },
                            "13": {
                                "id_morph_attr": "13",
                                "name": "падеж",
                                "number_morph_attr": "3",
                                "id_value_attr": {
                                    "33": 33
                                },
                                "short_value": {
                                    "р.п.": "р.п."
                                },
                                "value": {
                                    "родительный": "родительный"
                                }
                            },
                            "6": {
                                "id_morph_attr": "6",
                                "name": "число",
                                "number_morph_attr": "4",
                                "id_value_attr": {
                                    "14": 14
                                },
                                "short_value": {
                                    "ед.ч.": "ед.ч."
                                },
                                "value": {
                                    "единственное": "единственное"
                                }
                            },
                            "8": {
                                "id_morph_attr": "8",
                                "name": "род",
                                "number_morph_attr": "5",
                                "id_value_attr": {
                                    "21": 21
                                },
                                "short_value": {
                                    "ж.р.": "ж.р."
                                },
                                "value": {
                                    "женский род": "женский род"
                                }
                            },
                            "25": {
                                "id_morph_attr": "25",
                                "name": "тип местоимения",
                                "number_morph_attr": "6",
                                "id_value_attr": {
                                    "70": 70
                                },
                                "short_value": {
                                    "одуш": "одуш"
                                },
                                "value": {
                                    "личное (одуш)": "личное (одуш)"
                                }
                            }
                        }
                    }
                }
JSON;

        return json_decode($json_p);
    }

    /**
     * местоимение "я"
     * @return mixed
     */
    private function getPoint3()
    {
        $json_p = <<<JSON
{
                    "kw": 0,
                    "ks": 0,
                    "count_dw": 1,
                    "id_sentence": "55a77ce9be6f76.19607984",
                    "w": {
                        "kw": 0,
                        "word": "я",
                        "data": false,
                        "name_fio": false,
                        "stop": false,
                        "cut": false
                    },
                    "dw": {
                        "id_word_form": "efc1b8ae-2f10-11e2-a59d-b318a7eaeb89",
                        "word_form": "я",
                        "initial_form": "я",
                        "id_word_class": 4,
                        "name_word_class": "местоимение",
                        "parameters": {
                            "7": {
                                "id_morph_attr": "7",
                                "name": "лицо",
                                "number_morph_attr": "2",
                                "id_value_attr": {
                                    "16": 16
                                },
                                "short_value": {
                                    "1-е": "1-е"
                                },
                                "value": {
                                    "1 лицо": "1 лицо"
                                }
                            },
                            "13": {
                                "id_morph_attr": "13",
                                "name": "падеж",
                                "number_morph_attr": "3",
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
                            "6": {
                                "id_morph_attr": "6",
                                "name": "число",
                                "number_morph_attr": "4",
                                "id_value_attr": {
                                    "14": 14
                                },
                                "short_value": {
                                    "ед.ч.": "ед.ч."
                                },
                                "value": {
                                    "единственное": "единственное"
                                }
                            },
                            "25": {
                                "id_morph_attr": "25",
                                "name": "тип местоимения",
                                "number_morph_attr": "6",
                                "id_value_attr": {
                                    "70": 70
                                },
                                "short_value": {
                                    "одуш": "одуш"
                                },
                                "value": {
                                    "личное (одуш)": "личное (одуш)"
                                }
                            }
                        }
                    },
                    "key_point": 0
                }
JSON;

        return json_decode($json_p);
    }


    /**
     * местоимение "ты"
     * @return mixed
     */
    private function getPoint4()
    {
        $json_p = <<<JSON
{
                    "kw": 0,
                    "ks": 0,
                    "count_dw": 1,
                    "id_sentence": "55a77e6cbc0ce5.00192305",
                    "w": {
                        "kw": 0,
                        "word": "ты",
                        "data": false,
                        "name_fio": false,
                        "stop": false,
                        "cut": false
                    },
                    "dw": {
                        "id_word_form": "eda94794-2f10-11e2-9a2f-ff4953d40a50",
                        "word_form": "ты",
                        "initial_form": "ты",
                        "id_word_class": 4,
                        "name_word_class": "местоимение",
                        "parameters": {
                            "7": {
                                "id_morph_attr": "7",
                                "name": "лицо",
                                "number_morph_attr": "2",
                                "id_value_attr": {
                                    "17": 17
                                },
                                "short_value": {
                                    "2-е": "2-е"
                                },
                                "value": {
                                    "2 лицо": "2 лицо"
                                }
                            },
                            "13": {
                                "id_morph_attr": "13",
                                "name": "падеж",
                                "number_morph_attr": "3",
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
                            "6": {
                                "id_morph_attr": "6",
                                "name": "число",
                                "number_morph_attr": "4",
                                "id_value_attr": {
                                    "14": 14
                                },
                                "short_value": {
                                    "ед.ч.": "ед.ч."
                                },
                                "value": {
                                    "единственное": "единственное"
                                }
                            },
                            "25": {
                                "id_morph_attr": "25",
                                "name": "тип местоимения",
                                "number_morph_attr": "6",
                                "id_value_attr": {
                                    "70": 70
                                },
                                "short_value": {
                                    "одуш": "одуш"
                                },
                                "value": {
                                    "личное (одуш)": "личное (одуш)"
                                }
                            }
                        }
                    }
                }
JSON;

        return json_decode($json_p);
    }



    /**
     * местоимение "твоих"
     * @return mixed
     */
    private function getPoint5()
    {
        $json_p = <<<JSON
{
                    "kw": 0,
                    "ks": 0,
                    "count_dw": 3,
                    "id_sentence": "55a77f067ff211.74154567",
                    "w": {
                        "kw": 0,
                        "word": "твоих",
                        "data": false,
                        "name_fio": false,
                        "stop": false,
                        "cut": false
                    },
                    "dw": {
                        "id_word_form": "ed13136e-2f10-11e2-8dee-dbb7ab3963d5",
                        "word_form": "твоих",
                        "initial_form": "твой",
                        "id_word_class": 4,
                        "name_word_class": "местоимение",
                        "parameters": {
                            "13": {
                                "id_morph_attr": "13",
                                "name": "падеж",
                                "number_morph_attr": "3",
                                "id_value_attr": {
                                    "33": 33
                                },
                                "short_value": {
                                    "р.п.": "р.п."
                                },
                                "value": {
                                    "родительный": "родительный"
                                }
                            },
                            "6": {
                                "id_morph_attr": "6",
                                "name": "число",
                                "number_morph_attr": "4",
                                "id_value_attr": {
                                    "15": 15
                                },
                                "short_value": {
                                    "мн.ч.": "мн.ч."
                                },
                                "value": {
                                    "множественное": "множественное"
                                }
                            },
                            "25": {
                                "id_morph_attr": "25",
                                "name": "тип местоимения",
                                "number_morph_attr": "6",
                                "id_value_attr": {
                                    "69": 69
                                },
                                "short_value": {
                                    "прил": "прил"
                                },
                                "value": {
                                    "местоимение-прилагательное": "местоимение-прилагательное"
                                }
                            }
                        }
                    }
                }
JSON;

        return json_decode($json_p);
    }


    /**
     * местоимение "оно"
     * @return mixed
     */
    private function getPoint6()
    {
        $json_p = <<<JSON
{
                    "kw": 0,
                    "ks": 0,
                    "count_dw": 2,
                    "id_sentence": "55a7ad281b8938.72815476",
                    "w": {
                        "kw": 0,
                        "word": "оно",
                        "data": false,
                        "name_fio": false,
                        "stop": false,
                        "cut": false
                    },
                    "dw": {
                        "id_word_form": "e9f45fa8-2f10-11e2-a62a-9f539fd691a9",
                        "word_form": "оно",
                        "initial_form": "оно",
                        "id_word_class": 4,
                        "name_word_class": "местоимение",
                        "parameters": {
                            "7": {
                                "id_morph_attr": "7",
                                "name": "лицо",
                                "number_morph_attr": "2",
                                "id_value_attr": {
                                    "18": 18
                                },
                                "short_value": {
                                    "3-е": "3-е"
                                },
                                "value": {
                                    "3 лицо": "3 лицо"
                                }
                            },
                            "13": {
                                "id_morph_attr": "13",
                                "name": "падеж",
                                "number_morph_attr": "3",
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
                            "6": {
                                "id_morph_attr": "6",
                                "name": "число",
                                "number_morph_attr": "4",
                                "id_value_attr": {
                                    "14": 14
                                },
                                "short_value": {
                                    "ед.ч.": "ед.ч."
                                },
                                "value": {
                                    "единственное": "единственное"
                                }
                            },
                            "8": {
                                "id_morph_attr": "8",
                                "name": "род",
                                "number_morph_attr": "5",
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
                            "25": {
                                "id_morph_attr": "25",
                                "name": "тип местоимения",
                                "number_morph_attr": "6",
                                "id_value_attr": {
                                    "70": 70
                                },
                                "short_value": {
                                    "одуш": "одуш"
                                },
                                "value": {
                                    "личное (одуш)": "личное (одуш)"
                                }
                            }
                        }
                    }
                }
JSON;

        return json_decode($json_p);
    }


    public function dataProviderPadeszh()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Imenitelnij::class, CASE_SUBJECTIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Roditelnij::class, CASE_GENITIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Datelnij::class, CASE_DATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Vinitelnij::class, CASE_ACCUSATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Tvoritelnij::class, CASE_INSTRUMENTAL_ID],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Predlozshnij::class, CASE_PREPOSITIONAL_ID]
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
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Null::class){
            unset($point->dw->parameters->{CASE_ID});
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPadeszh', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Null::class, $result[0]);
            return;
        }
        else{
            // подменяем падеж
            $point->dw->parameters->{CASE_ID}->id_value_attr = [$padeszh => $padeszh];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPadeszh', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }



    public function dataProviderRazryad()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Lichnoe::class, \OldAotConstants::PERSONAL_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Vozvratnoe::class, \OldAotConstants::REFLEXIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Prityazhatelnoe::class, \OldAotConstants::POSSESSIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Otricatelnoe::class, \OldAotConstants::NEGATIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Neopredelennoe::class, \OldAotConstants::INDEFINITE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Voprositelnoe::class, \OldAotConstants::INTERROGATIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Otnositelnoe::class, \OldAotConstants::RELATIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Ukazatelnoe::class, \OldAotConstants::DEMONSTRATIVE_PRONOUN()],
            [\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Opredelitelnoe::class, \OldAotConstants::ATTRIBUTIVE_PRONOUN()]
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
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class){
            # разряда и так по умолчанию нет, поэтому unset делать не надо
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRazryad', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class, $result[0]);
            return;
        }
        else{
            // создаем новый аттрибут
            $point->dw->parameters->{\OldAotConstants::RANK_PRONOUNS()} = new MorphAttribute();
            // задаем значение разряда
            $point->dw->parameters->{\OldAotConstants::RANK_PRONOUNS()}->id_value_attr = [$razryad => $razryad];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRazryad', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }

}