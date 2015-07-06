<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 19:10
 */

namespace Aot\RussianMorphology\ChastiRechi\Glagol;


class AnalysisProtocol extends \Aot\RussianMorphology\AnalysisProtocol
{
    /**
     * @var Morphology\Chislo\Base[]
     */
    public $chislo = [];

    /** @var  Morphology\Litso\Base[] */
    public $litso = [];

    /**
     * @var Morphology\Naklonenie\Base[]
     */
    public $naklonenie = [];

    /**
     * @var Morphology\Perehodnost\Base[]
     */
    public $perehodnost = [];

    /** @var  Morphology\Rod\Base[] */
    public $rod = [];

    /** @var Morphology\Spryazhenie\Base[] */
    public $spryazhenie = [];

    /**
     * @var Morphology\Vid\Base[]
     */
    public $vid = [];

    /** @var  Morphology\Vozvratnost\Base[] */
    public $vozvratnost = [];

    /** @var Morphology\Vremya\Base[] */
    public $vremya = [];

    /** @var Morphology\Zalog\Base[] */
    public $zalog = [];

}