<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 19:10
 */

namespace Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe;


class AnalysisProtocol extends \Aot\RussianMorphology\AnalysisProtocol
{
    /**
     * @var Morphology\Chislo\Base[]
     */
    public $chislo = [];

    /** @var  Morphology\Naritcatelnost\Base[] */
    public $naritcatelnost = [];

    /** @var   Morphology\Odushevlyonnost\Base[] */
    public $odushevlyonnost = [];

    /**
     * @var Morphology\Padeszh\Base[]
     */
    public $padeszh = [];

    /** @var  Morphology\Rod\Base[] */
    public $rod = [];

    /** @var Morphology\Sklonenie\Base[] */
    public $sklonenie = [];
}