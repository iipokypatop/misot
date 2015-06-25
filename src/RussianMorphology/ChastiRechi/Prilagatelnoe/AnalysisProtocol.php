<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 19:10
 */

namespace RussianMorphology\ChastiRechi\Prilagatelnoe;


class AnalysisProtocol extends \RussianMorphology\AnalysisProtocol
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
     * @var Morphology\Razriad\Base[]
     */
    public $razriad = [];

    /** @var  Morphology\Rod\Base[] */
    public $rod = [];

    /** @var Morphology\StepenSravneniia\Base[] */
    public $stepen_sravneniia = [];
}