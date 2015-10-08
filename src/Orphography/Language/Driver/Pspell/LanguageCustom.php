<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 006, 06.10.2015
 * Time: 14:36
 */

namespace Aot\Orphography\Language\Driver\Pspell;


class LanguageCustom extends \Aot\Orphography\Language\Driver\Pspell\LanguageSTD
{
    const LANGUAGE_CUSTOM_MOR = 'mor';
    const LANGUAGE_CUSTOM_CUS = 'cus';
    const LANGUAGE_CUSTOM_TST = 'tst';

    /**
     * @return string[]
     */
    protected static function getAvailableDictionary()
    {
        return [
            self::LANGUAGE_CUSTOM_MOR,
            self::LANGUAGE_CUSTOM_CUS,
            self::LANGUAGE_CUSTOM_TST
        ];
    }

    /**
     * @param string $language_name
     * @return null|static
     */
    public static function create($language_name)
    {
        if (!in_array($language_name, static::getAvailableDictionary(), true)) {
            return null;
        }

        $language_builder = \Aot\Orphography\Language\Driver\Pspell\LanguageManager::create();
        $pspell_config = $language_builder->build($language_name);

        $ob = new static($pspell_config, $language_name);

        return $ob;
    }

    /**
     * @param string[] $words
     */
    public function generateDictionary(array $words)
    {
        foreach ($words as $word) {
            assert(is_string($word));
            pspell_add_to_personal($this->getPspellLink(), $word);
        }
    }
}