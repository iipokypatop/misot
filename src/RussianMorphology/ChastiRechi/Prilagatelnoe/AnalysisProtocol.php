<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 19:10
 */

namespace Aot\RussianMorphology\ChastiRechi\Prilagatelnoe;


class AnalysisProtocol extends \Aot\RussianMorphology\AnalysisProtocol
{
    /**
     * @var Morphology\Chislo\Base[]
     */
    public $chislo = [];

    /** @var  Morphology\Forma\Base[] */
    public $forma = [];

    /**
     * @var Morphology\Padeszh\Base[]
     */
    public $padeszh = [];

    /**
     * @var Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Base[]
     */
    public $razriad = [];

    /** @var  Morphology\Rod\Base[] */
    public $rod = [];

    /** @var Morphology\StepenSravneniia\Base[] */
    public $stepen_sravneniia = [];
}