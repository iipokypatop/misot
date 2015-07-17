<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 19:41
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Chislitelnoe;


use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Factory;
use AotTest\AotDataStorage;
use MivarTest\PHPUnitHelper;
use MorphAttribute;

class FactoryTest extends AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Factory::class, $factory);
    }

    public function _testWDW(){
        $const = new \Constants();
        $const->defineConstants();
        $syntax_parser = new \SyntaxParserManager();
        $text = 'три';
        $syntax_parser->reg_parser->parse_text($text);
        $syntax_parser->create_dictionary_word();
        $wdw = [];
        foreach ($syntax_parser->reg_parser->get_sentences() as $sentence) {
            $wdw[] = $syntax_parser->create_sentence_space($sentence);
        }

        $space_kw = PHPUnitHelper::getProtectedProperty($wdw[0],'space_kw');
        $wdw_s = json_encode($space_kw[0], JSON_PRETTY_PRINT | JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

        print_r($wdw_s);
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Vinitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Null::class, $result[0]->podvid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Zhenskiy::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Null::class, $result[0]->tip);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Poryadkoviy::class, $result[0]->vid);
    }


    public function testBuild_Success2()
    {
        $point = $this->getPoint2(); // берем точку тестовую
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Null::class, $result[0]->podvid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Null::class, $result[0]->tip);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Kolichestvenniy::class, $result[0]->vid);
    }

    public function testBuild_Success_NullVid()
    {
        $point = $this->getPoint2(); // берем точку тестовую
        unset($point->dw->parameters->{TYPE_OF_NUMERAL_ID});
        $result = $this->buildFactory($point);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class, $result[0]);
        $this->assertEquals(1, count($result));
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, $result[0]->chislo);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Imenitelnij::class, $result[0]->padeszh);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Null::class, $result[0]->podvid);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class, $result[0]->rod);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Null::class, $result[0]->tip);
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Null::class, $result[0]->vid);
    }

    #TODO
    public function testBuild_ChisloFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // создаем новый аттрибут
        $point->dw->parameters->{NUMBER_ID} = new MorphAttribute();
        // подменяем род на несуществующий
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

    #TODO
    public function testBuild_PadeszhFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем род на несуществующий
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

    #TODO
    public function testBuild_VidFailing()
    {
        $point = $this->getPoint(); // берем точку тестовую
        // подменяем род на несуществующий
        $point->dw->parameters->{TYPE_OF_NUMERAL_ID}->id_value_attr = [111 => 111];
        try{
            $result = $this->buildFactory($point);
            $this->fail("Не должно было тут быть!");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("Unsupported value exception = 111", $e->getMessage());
        }
    }

    #TODO
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



    public function dataProviderPadeszh()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Imenitelnij::class, CASE_SUBJECTIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Roditelnij::class, CASE_GENITIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Datelnij::class, CASE_DATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Vinitelnij::class, CASE_ACCUSATIVE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Tvoritelnij::class, CASE_INSTRUMENTAL_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Predlozshnij::class, CASE_PREPOSITIONAL_ID]
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
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Null::class){
            unset($point->dw->parameters->{CASE_ID});
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getPadeszh', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Null::class, $result[0]);
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


    public function dataProviderChislo()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Edinstvennoe::class, NUMBER_SINGULAR_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Mnozhestvennoe::class, NUMBER_PLURAL_ID]
        ];
    }


    /**
     * @param $expectedResult
     * @param $chislo
     * @dataProvider dataProviderChislo
     */
    public function testGetChislo($expectedResult, $chislo)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class){
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getChislo', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class, $result[0]);
            return;
        }
        else{
            // создаем новый аттрибут
            $point->dw->parameters->{NUMBER_ID} = new MorphAttribute();
            // подменяем число
            $point->dw->parameters->{NUMBER_ID}->id_value_attr = [$chislo => $chislo];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getChislo', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }


    public function dataProviderRod()
    {
        return [
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class, -1],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Muzhskoy::class, GENUS_MASCULINE_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Sredniy::class, GENUS_NEUTER_ID],
            [\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Zhenskiy::class, GENUS_FEMININE_ID]
        ];
    }


    /**
     * @param $expectedResult
     * @param $rod
     * @dataProvider dataProviderRod
     */
    public function testGetRod($expectedResult, $rod)
    {
        $point = $this->getPoint(); // берем точку тестовую
        if( $expectedResult === \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class){
            unset($point->dw->parameters->{GENUS_ID});
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRod', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class, $result[0]);
            return;
        }
        else{
            // создаем новый аттрибут
            $point->dw->parameters->{GENUS_ID} = new MorphAttribute();
            // подменяем число
            $point->dw->parameters->{GENUS_ID}->id_value_attr = [$rod => $rod];
            $result = PHPUnitHelper::callProtectedMethod(Factory::get(), 'getRod', [$point->dw->parameters]);
            $this->assertEquals(1, count($result));
            $this->assertInstanceOf($expectedResult, $result[0]);
            return;
        }
    }



    /**
     * Числительное "второй"
     * @return array
     */
    private function getPoint()
    {
        $json_p = <<<JSON
{
        "kw": 0,
        "ks": 0,
        "id_sentence": "55a7d755330628.05500727",
        "count_dw": 9,
        "w": {
            "kw": 0,
            "word": "второй",
            "data": false,
            "name_fio": false,
            "stop": false,
            "cut": false
        },
        "dw": {
            "id_word_form": "087504ac-d810-11e2-a002-232a1d54f374",
            "word_form": "второй",
            "initial_form": "второй",
            "id_word_class": 14,
            "name_word_class": "числительное",
            "parameters": {
                "26": {
                    "id_morph_attr": "26",
                    "name": "тип числительного",
                    "number_morph_attr": "1",
                    "id_value_attr": {
                        "73": 73
                    },
                    "short_value": {
                        "пор": "пор"
                    },
                    "value": {
                        "порядковое": "порядковое"
                    }
                },
                "13": {
                    "id_morph_attr": "13",
                    "name": "падеж",
                    "number_morph_attr": "2",
                    "id_value_attr": {
                        "35": 35
                    },
                    "short_value": {
                        "в.п.": "в.п."
                    },
                    "value": {
                        "винительный": "винительный"
                    }
                },
                "8": {
                    "id_morph_attr": "8",
                    "name": "род",
                    "number_morph_attr": "3",
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
                "11": {
                    "id_morph_attr": "11",
                    "name": "одуш-неодуш",
                    "number_morph_attr": "4",
                    "id_value_attr": {
                        "26": 26
                    },
                    "short_value": {
                        "одуш": "одуш"
                    },
                    "value": {
                        "одушевленное": "одушевленное"
                    }
                }
            }
        }
    }
JSON;

        return json_decode($json_p);
    }

    /**
     * Числительное "пять"
     * @return array
     */
    private function getPoint2()
    {
        $json_p = <<<JSON
{
        "kw": 0,
        "ks": 0,
        "count_dw": 3,
        "id_sentence": "55a7dba37801e2.01928268",
        "w": {
            "kw": 0,
            "word": "пять",
            "data": false,
            "name_fio": false,
            "stop": false,
            "cut": false
        },
        "dw": {
            "id_word_form": "58b67f4a-2f0f-11e2-868f-5f1d3fd25ec1",
            "word_form": "пять",
            "initial_form": "пять",
            "id_word_class": 14,
            "name_word_class": "числительное",
            "parameters": {
                "26": {
                    "id_morph_attr": "26",
                    "name": "тип числительного",
                    "number_morph_attr": "1",
                    "id_value_attr": {
                        "74": 74
                    },
                    "short_value": {
                        "кол": "кол"
                    },
                    "value": {
                        "количественное": "количественное"
                    }
                },
                "13": {
                    "id_morph_attr": "13",
                    "name": "падеж",
                    "number_morph_attr": "2",
                    "id_value_attr": {
                        "32": 32
                    },
                    "short_value": {
                        "и.п.": "и.п."
                    },
                    "value": {
                        "именительный": "именительный"
                    }
                }
            }
        }
    }
JSON;

        return json_decode($json_p);
    }


    /**
     * Числительное "пятых"
     * @return array
     */
    private function getPoint3()
    {
        $json_p = <<<JSON
{
        "kw": 0,
        "ks": 0,
        "count_dw": 3,
        "id_sentence": "55a7dba37801e2.01928268",
        "w": {
            "kw": 0,
            "word": "пять",
            "data": false,
            "name_fio": false,
            "stop": false,
            "cut": false
        },
        "dw": {
            "id_word_form": "58b67f4a-2f0f-11e2-868f-5f1d3fd25ec1",
            "word_form": "пять",
            "initial_form": "пять",
            "id_word_class": 14,
            "name_word_class": "числительное",
            "parameters": {
                "26": {
                    "id_morph_attr": "26",
                    "name": "тип числительного",
                    "number_morph_attr": "1",
                    "id_value_attr": {
                        "74": 74
                    },
                    "short_value": {
                        "кол": "кол"
                    },
                    "value": {
                        "количественное": "количественное"
                    }
                },
                "13": {
                    "id_morph_attr": "13",
                    "name": "падеж",
                    "number_morph_attr": "2",
                    "id_value_attr": {
                        "32": 32
                    },
                    "short_value": {
                        "и.п.": "и.п."
                    },
                    "value": {
                        "именительный": "именительный"
                    }
                }
            }
        }
    }
JSON;

        return json_decode($json_p);
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

}