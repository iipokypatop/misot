<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/07/15
 * Time: 20:14
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Narechie;


use Aot\RussianMorphology\ChastiRechi\Narechie\Factory;
use Aot\RussianMorphology\FactoryException;

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
        $this->assertInstanceOf(\Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Null::class, $result[0]->stepen_sravneniia);
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

    /**
     * Возвращает точку
     * @return object
     */
    protected function getPoint()
    {
        // слово - лучше
        $json_p = <<<JSON
{
        "kw": 1,
        "ks": 0,
        "dw": {
            "id_word_form": "559ffb37ef2e9",
            "initial_form": "хороший",
            "name_word_class": "наречие",
            "id_word_class": 12,
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
        "Oz": "559ffb37ef1c0",
        "direction": "y",
        "id_sentence": "559ffb37ee4942.53244756",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }

    protected function getPoint2()
    {
        // слово - тихо
        $json_p = <<<JSON
{
        "kw": 0,
        "ks": 0,
        "dw": {
            "id_word_form": "55a501fe0e773",
            "initial_form": "тихо",
            "name_word_class": "наречие",
            "id_word_class": 12,
            "parameters": null
        },
        "ps": "adjunct",
        "O": "adjunct_verb",
        "Oz": "55a501fe0e654",
        "direction": "y",
        "id_sentence": "55a501fe0dac79.47855019",
        "denial": ""
    }
JSON;

        return json_decode($json_p);
    }

}