<?php

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Prichastie;


use Aot\RussianMorphology\ChastiRechi\Prichastie\Factory;
use Aot\RussianMorphology\FactoryException;

class FactoryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Prichastie\Factory::class, $factory);
    }

    public function testBuild_Success()
    {
        $point = $this->getPoint(); // берем точку тестовую
        $result = $this->buildFactory($point);

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
    }

    public function testBuild_wo_chislo(){
        # убираем число
        $point_wo_chislo = $this->getPoint();
        unset($point_wo_chislo->dw->parameters->{NUMBER_ID});
        try{
            $this->buildFactory($point_wo_chislo);
            $this->fail("Не должно было тут быть!");
        }
        catch(\Exception $e){
            $this->assertInstanceOf(FactoryException::class, $e);
            $this->assertEquals("chislo not defined", $e->getMessage());
            $this->assertEquals(24, $e->getCode());
        }
    }

    public function testBuild_wo_perehodnost(){
        # убираем переходность
        $point_wo_perehodnost = $this->getPoint();
        unset($point_wo_perehodnost->dw->parameters->{TRANSIVITY_ID});
        try{
            $this->buildFactory($point_wo_perehodnost);
        }
        catch(\Exception $e){
            $this->fail("Не должно было тут быть!");
        }
    }

    public function testBuild_wo_padeszh(){
        # убираем падеж
        $point_wo_padeszh = $this->getPoint();
        unset($point_wo_padeszh->dw->parameters->{CASE_ID});
        try{
            $this->buildFactory($point_wo_padeszh);
            $this->fail("Не должно было тут быть!");
        }
        catch(\Exception $e){
            $this->assertInstanceOf(FactoryException::class, $e);
            $this->assertEquals("padeszh not defined", $e->getMessage());
            $this->assertEquals(24, $e->getCode());
        }
    }

    public function testBuild_wo_vid(){
        # убираем вид
        $point_wo_vid = $this->getPoint();
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

    public function testBuild_wo_vozvratnost(){
        # убираем возвратность
        $point_wo_vozvratnost = $this->getPoint();
        unset($point_wo_vozvratnost->dw->parameters->{\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()});
        try{
            $this->buildFactory($point_wo_vozvratnost);
        }
        catch(\Exception $e){
            $this->fail("Не должно было тут быть!");
        }
    }

    public function testBuild_wo_vremya(){
        # убираем время
        $point_wo_vremya = $this->getPoint();
        unset($point_wo_vremya->dw->parameters->{TIME_ID});
        try{
            $this->buildFactory($point_wo_vremya);
            $this->fail("Не должно было тут быть!");
        }
        catch(\Exception $e){
            $this->assertInstanceOf(FactoryException::class, $e);
            $this->assertEquals("vremya not defined", $e->getMessage());
            $this->assertEquals(24, $e->getCode());
        }
    }

    public function testBuild_wo_razryad(){
        # убираем разряд
        $point_wo_razryad = $this->getPoint();
        unset($point_wo_razryad->dw->parameters->{DISCHARGE_COMMUNION_ID});
        try{
            $this->buildFactory($point_wo_razryad);
            $this->fail("Не должно было тут быть!");
        }
        catch(\Exception $e){
            $this->assertInstanceOf(FactoryException::class, $e);
            $this->assertEquals("razryad not defined", $e->getMessage());
            $this->assertEquals(24, $e->getCode());
        }
    }

    public function testBuild_wo_forma(){
        # убираем форму
        $point_wo_forma = $this->getPoint();
        unset($point_wo_forma->dw->parameters->{\OldAotConstants::WORD_FORM()});
        try{
            $this->buildFactory($point_wo_forma);
        }
        catch(\Exception $e){
            $this->fail("Не должно было тут быть!");
        }
    }

    public function testBuild_wo_rod(){
        # убираем род +++ и единственное число +++
        $point_wo_rod =  $this->getPoint();
        unset($point_wo_rod->dw->parameters->{GENUS_ID});
        try{
            $this->buildFactory($point_wo_rod);
            $this->fail("Не должно было тут быть!");
        }
        catch(\Exception $e){
            $this->assertInstanceOf(FactoryException::class, $e);
            $this->assertEquals("rod not defined", $e->getMessage());
            $this->assertEquals(24, $e->getCode());
        }
    }

    protected function buildFactory($point){
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