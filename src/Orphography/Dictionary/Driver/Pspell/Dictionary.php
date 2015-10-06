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
     * @param \Aot\Orphography\Word $word
     * @return bool
     */
    public function check(\Aot\Orphography\Word $word)
    {
        return pspell_check($this->getDictionaryLink(), $word->getText());
    }

    /**
     * @param \Aot\Orphography\Word $word
     * @return \Aot\Orphography\Word[] with weight as keys
     */
    public function suggest(\Aot\Orphography\Word $word)
    {
        $variants = pspell_suggest($this->pspell_link, $word->getText());
        $variants_word = [];
        $weights_word = [];
        foreach ($variants as $variant) {
            $variant_word = \Aot\Orphography\Word::create($variant);
            $variants_word[] = $variant_word;
            $weights_word[] = $this->weight($variant_word, $word);
        }
        return \Aot\Orphography\Suggestion::create($variants_word, $weights_word, $this);
    }

    /**
     * @param \Aot\Orphography\Word $word1
     * @param \Aot\Orphography\Word $word2
     * @return int | null
     */
    public function weight(\Aot\Orphography\Word $word1, \Aot\Orphography\Word $word2)
    {
        $result = levenshtein(
            $word1->getText(),
            $word2->getText(),
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