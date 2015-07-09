<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/07/15
 * Time: 11:07
 */

namespace Aot\RussianMorphology\ChastiRechi\Prichastie;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Edinstvennoe;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Mnozhestvennoe;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Kratkaya;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Polnaya;


use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Perehodnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Neperehodnij;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Sovershennyj;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Nesovershennyj;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Vozvratnyj;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Nevozvratnyj;


use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Buduschee;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Proshedshee;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Nastoyaschee;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Dejstvitelnyj;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Stradatelnyj;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Datelnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Imenitelnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Predlozshnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Roditelnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Tvoritelnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Vinitelnij;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Muzhskoi;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Srednij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Zhenskij;
use Dw;
use MorphAttribute;
use Word;


class Factory extends \Aot\RussianMorphology\Factory
{
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->initial_form;

        if (isset($word->word) && $dw->id_word_class === PARTICIPLE_CLASS_ID) {
        } else throw new \Exception('not implemented yet');
    }
}