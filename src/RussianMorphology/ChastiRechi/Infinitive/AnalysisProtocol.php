<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 19:10
 */

namespace Aot\RussianMorphology\ChastiRechi\Infinitive;


class AnalysisProtocol extends \Aot\RussianMorphology\AnalysisProtocol
{
    /**
     * @var Morphology\Perehodnost\Base[]
     */
    public $perehodnost = [];

    /**
     * @var Morphology\Vid\Base[]
     */
    public $vid = [];

    /**
     * @var  Morphology\Vozvratnost\Base[]
     */
    public $vozvratnost = [];

}