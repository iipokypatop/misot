<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 006, 06.10.2015
 * Time: 14:36
 */

namespace Aot\Orphography\Dictionary\Driver\Pspell;


class Dictionary extends \Aot\Orphography\Dictionary\Base
{
    const DICTIONARY_RU = 'ru';
    const DICTIONARY_EN = 'en';

    const LEVENSHTEIN_COST_INS = 1;
    const LEVENSHTEIN_COST_REP = 1;
    const LEVENSHTEIN_COST_DEL = 1;

    /**
     * @var int
     */
    protected $pspell_config;

    /**
     * @var int
     */
    protected $pspell_link;

    protected static function getAvailableStdDictionary()
    {
        return [
            self::DICTIONARY_RU,
            self::DICTIONARY_EN
        ];
    }

    /**
     * Dictionary constructor.
     * @param int $pspell_config
     */
    protected function __construct($pspell_config)
    {
        assert(is_int($pspell_config));

        $this->pspell_config = $pspell_config;

        pspell_config_mode($pspell_config, PSPELL_FAST);

        $this->pspell_link = pspell_new_config($pspell_config);
    }

    protected function getDictionaryLink()
    {
        return $this->pspell_link;
    }

    /**
     * @param $dictionary_name
     * @return null|static
     */
    public static function createStd($dictionary_name)
    {
        if (!in_array($dictionary_name, static::getAvailableStdDictionary(), true)) {
            return null;
        }

        $pspell_config = pspell_config_create($dictionary_name);

        $ob = new static($pspell_config);

        return $ob;
    }


    /**
     * @param \Aot\Orphography\Subtext $subtext
     * @return bool
     */
    public function check(\Aot\Orphography\Subtext $subtext)
    {
        return pspell_check($this->getDictionaryLink(), $subtext->getText());
    }

    /**
     * @param \Aot\Orphography\Subtext $subtext
     * @return \Aot\Orphography\Subtext[] with weight as keys
     */
    public function suggest(\Aot\Orphography\Subtext $subtext)
    {
        $variants = pspell_suggest($this->pspell_link, $subtext->getText());
        $variants_subtext = [];
        $weights_subtext = [];
        foreach ($variants as $variant) {
            $variant_subtext = \Aot\Orphography\Subtext::create($variant);
            $variants_subtext[] = $variant_subtext;
            $weights_subtext[] = $this->weight($variant_subtext, $subtext);
        }
        return \Aot\Orphography\Suggestion::create($variants_subtext, $weights_subtext, $this);
    }

    /**
     * @param \Aot\Orphography\Subtext $subtext1
     * @param \Aot\Orphography\Subtext $subtext2
     * @return int | null
     */
    public function weight(\Aot\Orphography\Subtext $subtext1, \Aot\Orphography\Subtext $subtext2)
    {
        $result = levenshtein(
            $subtext1->getText(),
            $subtext2->getText(),
            static::LEVENSHTEIN_COST_INS,
            static::LEVENSHTEIN_COST_REP,
            static::LEVENSHTEIN_COST_DEL
        );

        if ($result < 0) {
            return null;
        };

        return $result;
    }
}