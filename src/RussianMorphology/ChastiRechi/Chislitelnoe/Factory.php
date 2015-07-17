<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 18:23
 */

namespace Aot\RussianMorphology\ChastiRechi\Chislitelnoe;


use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Edinstvennoe;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Mnozhestvennoe;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null as NullChislo;

use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Imenitelnij;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Datelnij;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Predlozshnij;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Roditelnij;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Tvoritelnij;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Vinitelnij;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Null as NullPadeszh;

use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Prostoy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Sostavnoy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Null as NullPodvid;

use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Muzhskoy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Sredniy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Zhenskiy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null as NullRod;

use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Celiy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Drobniy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Sobiratelniy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Null as NullTip;

use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Kolichestvenniy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Poryadkoviy;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Null as NullVid;

use Dw;
use Word;

class Factory extends \Aot\RussianMorphology\Factory
{

    /**
     * @param Dw $dw
     * @param Word $word
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base[]
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->initial_form;
        $words = [];

        if (isset($word->word) && $dw->id_word_class === NUMERAL_CLASS_ID) {

            # вид
            $vid = $this->getVid($dw->parameters);

            # тип
            $tip = $this->getTip($dw->parameters);

            # подвид
            $podvid = $this->getPodvid($dw->parameters);

            # число
            $chislo = $this->getChislo($dw->parameters);

            # род
            $rod = $this->getRod($dw->parameters);

            # падеж
            $padeszh = $this->getPadeszh($dw->parameters);

            foreach ($vid as $val_vid) {
                foreach ($tip as $val_tip) {
                    foreach ($podvid as $val_podvid) {
                        foreach ($chislo as $val_chislo) {
                            foreach ($rod as $val_rod) {
                                foreach ($padeszh as $val_padeszh) {
                                    $words[] = Base::create(
                                        $text,
                                        $val_vid,
                                        $val_tip,
                                        $val_podvid,
                                        $val_chislo,
                                        $val_rod,
                                        $val_padeszh
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
        return $words;
    }

    /**
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Base[]
     */
    private function getVid($parameters)
    {
        if (empty(get_object_vars($parameters)[TYPE_OF_NUMERAL_ID])) {
            return [NullVid::create()];
        }

        $param = get_object_vars($parameters)[TYPE_OF_NUMERAL_ID];

        $vid = [];

        foreach ($param->id_value_attr as $val) {
            if ($val === QUANTITATIVE_ID) {
                $vid[] = Kolichestvenniy::create();
            } elseif ($val === ORDINAL_ID) {
                $vid[] = Poryadkoviy::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $vid;
    }

    /**
     * @TODO добавить константы
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Base[]
     */
    private function getTip($parameters)
    {
        if (empty(get_object_vars($parameters)[-1])) {
            return [NullTip::create()];
        }

        $param = get_object_vars($parameters)[-1];

        $tip = [];

        foreach ($param->id_value_attr as $val) {
            if ($val === -1) {
                $tip[] = Celiy::create();
            } elseif ($val === -1) {
                $tip[] = Sobiratelniy::create();
            } elseif ($val === -1) {
                $tip[] = Drobniy::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $tip;
    }

    /**
     * @TODO добавить константы
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Base[]
     */
    private function getPodvid($parameters)
    {
        if (empty(get_object_vars($parameters)[-1])) {
            return [NullPodvid::create()];
        }

        $param = get_object_vars($parameters)[-1];

        $podvid = [];

        foreach ($param->id_value_attr as $val) {
            if ($val === -1) {
                $podvid[] = Prostoy::create();
            } elseif ($val === -1) {
                $podvid[] = Sostavnoy::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $podvid;
    }

    /**
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Base[]
     */
    private function getChislo($parameters)
    {
        if (empty(get_object_vars($parameters)[NUMBER_ID])) {
            return [NullChislo::create()];
        }

        $param = get_object_vars($parameters)[NUMBER_ID];

        $chislo = [];

        foreach ($param->id_value_attr as $val) {
            if ($val === NUMBER_SINGULAR_ID) {
                $chislo[] = Edinstvennoe::create();
            } elseif ($val === NUMBER_PLURAL_ID) {
                $chislo[] = Mnozhestvennoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $chislo;
    }

    /**
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Base[]
     */
    private function getRod($parameters)
    {
        if (empty(get_object_vars($parameters)[GENUS_ID])) {
            return [NullRod::create()];
        }

        $param = get_object_vars($parameters)[GENUS_ID];

        $rod = [];

        foreach ($param->id_value_attr as $val) {
            if ($val === GENUS_MASCULINE_ID) {
                $rod[] = Muzhskoy::create();
            } elseif ($val === GENUS_NEUTER_ID) {
                $rod[] = Sredniy::create();
            } elseif ($val === GENUS_FEMININE_ID) {
                $rod[] = Zhenskiy::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }
        return $rod;
    }

    /**
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Base[]
     */
    private function getPadeszh($parameters)
    {
        if (empty(get_object_vars($parameters)[CASE_ID])) {
            return [NullPadeszh::create()];
        }

        $param = get_object_vars($parameters)[CASE_ID];

        $padeszh = [];

        foreach ($param->id_value_attr as $val) {
            if ($val === CASE_SUBJECTIVE_ID) {
                $padeszh[] = Imenitelnij::create();
            } elseif ($val === CASE_GENITIVE_ID) {
                $padeszh[] = Roditelnij::create();
            } elseif ($val === CASE_DATIVE_ID) {
                $padeszh[] = Datelnij::create();
            } elseif ($val === CASE_ACCUSATIVE_ID) {
                $padeszh[] = Vinitelnij::create();
            } elseif ($val === CASE_INSTRUMENTAL_ID) {
                $padeszh[] = Tvoritelnij::create();
            } elseif ($val === CASE_PREPOSITIONAL_ID) {
                $padeszh[] = Predlozshnij::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $padeszh;
    }

}