<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 23:04
 */

namespace Aot\Sviaz\SequenceMember\Word;

use Aot\RussianMorphology\Slovo;

class WordWithPreposition extends Base
{
    /** @var  \Aot\RussianMorphology\ChastiRechi\Predlog\Base */
    protected $predlog;

    /**
     * @return \Aot\RussianMorphology\ChastiRechi\Predlog\Base
     */
    public function getPredlog()
    {
        return $this->predlog;
    }

    protected function __construct()
    {

    }

    public static function create(Slovo $slovo = null, \Aot\RussianMorphology\ChastiRechi\Predlog\Base $predlog = null)
    {
        assert($slovo !== null);
        assert($predlog !== null);

        /** @var static $ob */
        $ob = parent::create($slovo);

        $ob->predlog = $predlog;

        return $ob;
    }

}