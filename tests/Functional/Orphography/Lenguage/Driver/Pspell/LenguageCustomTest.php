<?php
/**
 * User: Яковенко Иван
 */

namespace AotTest\Functional\Orphography\Lenguage\Driver\Pspell;

use MivarTest\PHPUnitHelper;

class LenguageCustomTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {

        $language = \Orthography\Language\Driver\Pspell\LanguageCustom::create('tst');
        $this->assertEquals('Aot\\Orphography\\Language\\Driver\\Pspell\\LanguageCustom', get_class($language));
        /** @var \Orthography\Language\Driver\Pspell\LanguageCustom $language */
        $string_random = str_shuffle('абвгдежзийклмнопрстуфхцчшщыьъэюя');

        $language->addWordsToDictionary([]);
    }

}