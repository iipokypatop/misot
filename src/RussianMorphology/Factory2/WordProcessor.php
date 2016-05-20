<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 006, 06, 03, 2016
 * Time: 15:53
 */

namespace Aot\RussianMorphology\Factory2;


class WordProcessor
{
    const REGULAR_FOR_WHITE_LIST = '/[A-Za-zА-Яа-яёЁ\\d]+/u';

    /** @var \Aot\RussianMorphology\Factory2\Cache */
    protected $word_form_cache;

    /** @var \Aot\RussianMorphology\Factory2\Cache */
    protected $initial_form_cache;

    /** @var \Aot\RussianMorphology\Factory2\CacheStatic */
    protected $cache_static;

    /** @var  \Aot\RussianMorphology\Factory2\Mode */
    protected $mode;

    public function __construct()
    {
        $this->mode = \Aot\RussianMorphology\Factory2\Mode::create();
        $this->cache_static = \Aot\RussianMorphology\Factory2\CacheStatic::create();
        $this->word_form_cache = \Aot\RussianMorphology\Factory2\Cache::create();
        $this->initial_form_cache = \Aot\RussianMorphology\Factory2\Cache::create();
    }

    public static function create()
    {
        $ob = new static;

        return $ob;
    }

    public function resetWordFormCache()
    {
        $this->word_form_cache = \Aot\RussianMorphology\Factory2\Cache::create();
    }

    public function resetInitialFormCache()
    {
        $this->initial_form_cache = \Aot\RussianMorphology\Factory2\Cache::create();
    }

    /**
     * @return Mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string[] $words
     * @return string[][]
     */
    public function splitWordsAndTrash(array $words)
    {
        if (empty($words)) {
            return [[], []];
        }

        $trash = [];
        $good = [];
        foreach ($words as $word) {
            if ($this->isWordNormal($word)) {
                $good[] = $word;
            } else {
                $trash[] = $word;
            }
        }

        return [$trash, $good];

    }

    /**
     * @param string $word
     * @return bool
     */
    protected function isWordNormal($word)
    {
        $res = preg_match(static::REGULAR_FOR_WHITE_LIST, $word);
        return $res !== false && $res > 0;
    }

    /**
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     */
    public function splitCachedAndNewWords(array $words)
    {
        if (empty($words)) {
            return [[], []];
        }

        $cache_store = $this->getCurrentCache();

        $cached = [];
        $new = [];
            foreach ($words as $word) {
            if (!empty($cache_store->__cache_slova[$word])) {
                $cached[$word] = $word;
            } else {
                $new[$word] = $word;
            }
        }

        return [$cached, $new];
    }

    /**
     * Разбиение массива слов на простые и составные
     * @param string[] $words
     * @return string[][]
     */
    public function splitArrayWords($words)
    {
        return CompositeWordProcessor::splitArrayWords($words);
    }

    /**
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     */
    public function processCompositeWords(array $words)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }


        if (empty($words)) {
            return [];
        }

        $this->assertNotDuplicates($words);

        $mask = [];
        $all_words = [];
        foreach ($words as $word) {

            $splitted = \Aot\RussianMorphology\Factory2\CompositeWordProcessor::splitWord($word);

            if (!isset($splitted[0], $splitted[1])) {
                throw new \LogicException("Wrong composite word: " . $word);
            }

            $mask[$word] = $splitted;
            $all_words[] = $splitted[0];
            $all_words[] = $splitted[1];
        }

        $slova = $this->processSimpleWords(
            array_unique($all_words)
        );


        $result = [];
        foreach ($mask as $word_text => $parts) {
            $result[$word_text] = [];
            /** @var \Aot\RussianMorphology\Slovo[] $slovo_parts1 */
            $slovo_parts1 = $slova[$parts[0]];

            /** @var \Aot\RussianMorphology\Slovo[] $slovo_parts2 */
            $slovo_parts2 = $slova[$parts[1]];

            $unique_forms_slovo_part2 = [];
            foreach ($slovo_parts2 as $slovo_part2) {
                $unique_forms_slovo_part2[$slovo_part2->getInitialForm()] = $slovo_part2->getInitialForm();
            }

            foreach ($slovo_parts1 as $slovo_part1) {
                foreach ($unique_forms_slovo_part2 as $text_part2) {

                    $ids = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getPriznakIdsByClasses(
                        $slovo_part1->getMorphologyStorage()
                    );

                    $ids = array_filter($ids, function ($v) {
                        return isset($v);
                    });

                    $result[$word_text][] = $this->build(
                        $word_text,
                        \Aot\RussianMorphology\Factory2\CompositeWordProcessor::get()
                            ->joinParts($slovo_part1->getInitialForm(), $text_part2),
                        \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getIdByMockClass($slovo_part1),
                        $ids
                    );
                }
            }
        }

        return $result;
    }

    public function assertNotDuplicates($words)
    {
        $counted_values = array_count_values($words);

        if (count($counted_values) === 0) {
            return;
        }

        if (max($counted_values) > 1) {

            $duplicates = array_filter($counted_values, function ($a) {
                return $a > 1;
            });

            throw new \Aot\RussianMorphology\Factory2\Exception\DuplicateException("входной массив слов не должен содержать дубликаты " . var_export($duplicates, 1));
        }
    }

    /**
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     * @throws \Aot\Exception
     * @throws \Exception
     */
    public function processSimpleWords(array $words)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }


        if (empty($words)) {
            return [];
        }

        $words_lower = [];
        foreach ($words as $index => $word) {
            $words_lower[$this->strToLowerAndProcessLetterYO($word)][] = $word;
            $words[$index] = $this->processLetterYO($word);
        }

        $this->assertNotDuplicates($words);

        $is_search_mode_sensitive = $this->mode->isSearchModeCaseSensitive();

        $is_search_mode_by_initial_form = $this->mode->isSearchModeByInitialForm();

        /** @var \Aot\RussianMorphology\Slovo[][] $slova */
        $slova = [];
        foreach ($words as $key_word => $word) {
            $slova[$key_word] = [];
        }

        if (empty($words)) {
            return $slova;
        }


//        echo join(",", $words);
//        echo "\n";

        /** @var \TextPersistence\API\APIcurrent $api */
        $api = \TextPersistence\API\TextAPI::getAPI();

        $query_builder = $api
            ->createQueryBuilder()
            ->select(
                'f', 'w', 'wi', 'wc'
            )
            ->from(\TextPersistence\Entities\TextEntities\Form::class, 'f')
            ->leftJoin('f.mword', 'w')
            ->leftJoin('f.wordClass', 'wc')
            ->leftJoin('f.initialForm', 'wi');


        $param_name_words = ':words';

        if ($is_search_mode_by_initial_form) {
            $query_builder
//                ->leftJoin('f.initialForm', 'wi')
                ->where("wi.word in ($param_name_words)");
        } else {
            $query_builder
                ->where("w.word in ($param_name_words)");
        }


        if ($is_search_mode_sensitive) {
            $query_builder
                ->setParameter($param_name_words, array_values($words));
        } else {

            $query_builder
                ->setParameter($param_name_words, $this->arrayToLower($words));
        }

        $query = $query_builder->getQuery();


//        \Doctrine\DBAL\Driver\PDOStatement::$log_1[] = join(',', $words);
//        \Doctrine\DBAL\Driver\PDOStatement::$log_2[] = join(',', $words);


        /** @var \TextPersistence\Entities\TextEntities\Form[] $forms */
        $forms = $query->getResult();

        foreach ($forms as $form) {

            if ($is_search_mode_by_initial_form) {
                $word_form = $form->getInitialForm()->getWord();
            } else {
                $word_form = $form->getMword()->getWord();
            }

            if ($is_search_mode_sensitive) {

                $slova[$word_form][] = $this->buildFromEntity($form);

            } else {

                $word_form_lower = $this->strToLowerAndProcessLetterYO($word_form);

                if (!isset($words_lower[$word_form_lower])) {
                    throw new \Aot\Exception("ошибка получения слова в нижнем регистре для слова " . var_export($word_form, 1));
                }

                foreach ($words_lower[$word_form_lower] as $input_word_lower) {
                    $slova[$input_word_lower][] = $this->buildFromEntity($form);
                }
            }
        }


        return $slova;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function strToLowerAndProcessLetterYO($string)
    {
        $string = $this->strToLower($string);
        $string = $this->processLetterYO($string);
        return $string;
    }

    /**
     * @param $string
     * @return string
     */
    protected function strToLower($string)
    {
        return mb_strtolower($string, \Aot\Text\Encodings::UTF_8);
    }


    /**
     * @param string[] $words
     * @return string[]
     */
    protected function arrayToLower($words)
    {
        $lower_words = [];
        foreach ($words as $word) {
            $lower_words[] = mb_strtolower($word, \Aot\Text\Encodings::UTF_8);
        }

        return $lower_words;
    }

    /**
     * @param \TextPersistence\Entities\TextEntities\Form $wordForm
     * @return \Aot\RussianMorphology\Slovo
     */
    public function buildFromEntity(\TextPersistence\Entities\TextEntities\Form $wordForm)
    {
        $text = $wordForm->getMword()->getWord();
        $initial_form = $wordForm->getInitialForm()->getWord();
        /** @var int[] $ids */
        $ids = $wordForm->getValuesAgg();
        $chast_rechi_id = $wordForm->getWordClass()->getId();

        return $this->build($text, $initial_form, $chast_rechi_id, $ids);
    }

    /**
     * @param string $text
     * @param string $initial_form
     * @param int[] $ids
     * @param int $chast_rechi_id
     * @return \Aot\RussianMorphology\Slovo
     */
    public function build($text, $initial_form, $chast_rechi_id, array $ids)
    {
        assert(is_string($text));
        assert(is_string($initial_form));
        foreach ($ids as $id) {
            assert(is_int($id));
        }
        assert(is_int($chast_rechi_id));

        $chast_rechi_class = $this->cache_static->__cache_chast_rechi_class[$chast_rechi_id];

        /** @var string[] $priznaki */
        $priznaki = $this->cache_static->__cache_priznaki[$chast_rechi_class];

        $priznaki_objects = [];
        foreach ($priznaki as $priznak_name => &$priznak_base_class) {

            foreach ($ids as $id) {

                if (!isset($this->cache_static->__cache_priznak_class[$chast_rechi_id][$id])) {
                    /*$this->error_log[] = [
                        'id' => $wordForm->getId(),
                        'chast_rechi_id' => $chast_rechi_id,
                        'priznak_value_id' => $id
                    ];*/
                    throw new \LogicException(
                        "chast rechi id = $chast_rechi_id doesn't support priznak value id =  $id"
                    );
                }

                $priznak_class = $this->cache_static->__cache_priznak_class[$chast_rechi_id][$id];

                if ($this->cache_static->__cache_is_a[$priznak_base_class][$priznak_class]) {
                    $priznaki_objects[$priznak_name] = clone $this->cache_static->__cache_priznak_4_clone[$priznak_class];
                }
            }

            if (empty($priznaki_objects[$priznak_name])) {
                $priznaki_objects[$priznak_name] = clone $this->cache_static->__cache_priznak_null_4_clone[$priznak_base_class];
            }
        }

        $ob = clone $this->cache_static->__cache_chast_rechi_4_clone[$chast_rechi_class];
        $ob->storage = $priznaki_objects;
        $ob->text = $text;
        $ob->initial_form = $initial_form;

        return $ob;
    }

    /**
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     * @throws \Aot\Exception
     */
    public function processCachedWords(array $words)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }

        if (empty($words)) {
            return [];
        }

        $cache_store = $this->getCurrentCache();


        $words_from_cache = [];
        foreach ($words as $word) {
            if (empty($cache_store->__cache_slova[$word])) {
                throw new \Aot\Exception("слова " . var_export($word, 1) . " нет в кэше");
            }

            /** @var \Aot\RussianMorphology\Slovo $slovo */
            foreach ($cache_store->__cache_slova[$word] as $slovo) {
                $words_from_cache[$word][] = $slovo->reClone();
            }
        }

        return $words_from_cache;
    }

    /**
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     * @throws \Aot\Exception
     */
    public function processPunctuation(array $words)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }

        if (empty($words)) {
            return [];
        }

        $result = [];
        foreach ($words as $word) {
            $punctuation = \Aot\RussianSyntacsis\Punctuaciya\Factory::getInstance()->build($word);
            if (null !== $punctuation) {
                $result[$word] = [$punctuation];
            }
        }

        return $result;
    }


    /**
     * @param string[] $trash
     * @return string[][]
     */
    public function processTrash(array $trash)
    {
        $res = [];
        foreach ($trash as $item) {
            $res[$item] = [];

        }

        return $res;
    }

    /**
     * @return Cache
     */
    public function getCurrentCache()
    {
        if ($this->mode->isSearchModeByInitialForm()) {
            return $this->initial_form_cache;
        }

        return $this->word_form_cache;
    }

    /**
     * @brief Из числа записанного цифрами
     *
     * @param \Aot\RussianMorphology\Slovo[][] $words
     */
    public function processDigitalOfNumber(array &$words)
    {
        foreach ($words as $word => $slova) {
            if (is_numeric($word)) {
                $string_view = \Aot\Tools\ConverterOfNumeral\Base::convertToString((double)$word);
                $parts = $this->getChislitelnieForString($string_view);
                if (count($parts) === 1) {
                    $slovo = \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::create(
                        $string_view,
                        \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\ClassNull::create(),
                        \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\ClassNull::create(),
                        \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\ClassNull::create(),
                        \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\ClassNull::create(),
                        \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\ClassNull::create(),
                        \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\ClassNull::create()
                    );
                } elseif (count($parts) > 1) {
                    $slovo = \Aot\RussianMorphology\ChastiRechi\ChislitelnoeSostavnoe\Base::createNew(
                        $string_view,
                        $parts
                    );
                } else {
                    throw new \Aot\Exception("Что-то пошло не так.");
                }

                $slovo->setInitialForm($string_view);
                $slovo->setDigitalView((double)$word);
                $words[$word] = [$slovo];
            }
        }


    }

    /**
     * @param string $string
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base[]
     * @throws \Aot\Exception
     */
    protected function getChislitelnieForString($string)
    {
        $simple_words = preg_split('/\s/', $string);
        $slova = $this->processSimpleWords($simple_words);
        /** @var \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base[] $parts */
        $parts = [];
        foreach ($slova as $item) {
            foreach ($item as $slovo) {
                if ($slovo instanceof \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base) {
                    $parts[] = $slovo;
                    continue 2;
                }
            }
            throw new \Aot\Exception("Для числа $item не найдено объекта Slovo");
        }
        return $parts;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function processLetterYO($string)
    {
        $pattern = "/ё/ui";
        $replacement = 'е';
        $string = preg_replace($pattern, $replacement, $string);
        return $string;
    }
}