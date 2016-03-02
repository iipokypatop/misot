<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 30/11/15
 * Time: 19:48
 */

namespace Aot\RussianMorphology;

use Aot\Text\Encodings;

class FactoryFromEntity
{
    const REGULAR_FOR_WHITE_LIST = '/[A-Za-zА-Яа-яёЁ\\d]+/u';

    /**
     * default mode:
     * 0. words must be unique
     * 1. INSENSITIVE
     * 2. NOT BY INITIAL FORM
     * 3. WITH PREDICTOR (if word not found)
     * 4. NO ADDITIONAL NULL WORDS
     */
    const SEARCH_MODE_DEFAULT = 0;

    const SEARCH_MODE_CASE_SENSITIVE = 1;
    const SEARCH_MODE_BY_INITIAL_FORM = 2;
    const SEARCH_MODE_NOT_USE_PREDICTOR = 4;
    const SEARCH_MODE_ADD_NULL_WORDS = 8;

    protected static $uniqueInstances = null;
    /** @var array[][] */
    public $error_log = [];
    public $timer1 = 0;
    /** @var string[] */
    protected $__cache_chast_rechi_class = [];
    /** @var bool[] */
    protected $__cache_is_a = [];
    /** @var string[][] */
    protected $__cache_priznak_class = [];
    /** @var string */
    protected $__cache_null_class = [];
    /** @var \Aot\RussianMorphology\ChastiRechi\MorphologyBase[] */
    protected $__cache_priznak_4_clone = [];
    /** @var \Aot\RussianMorphology\ChastiRechi\MorphologyBase[] */
    protected $__cache_priznak_null_4_clone = [];
    /** @var string[] */
    protected $__cache_priznaki = [];
    /** @var \Aot\RussianMorphology\Slovo[] */
    protected $__cache_chast_rechi_4_clone = [];
    /** @var int[] */
    protected $__cache_base_class_id = [];
    /**
     * @var int
     */
    protected $search_mode;

    /**
     * FactoryBase constructor.
     */
    protected function __construct()
    {
        $this->__cache_chast_rechi_class = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getClasses();

        $this->__cache_priznaki = [];
        foreach (\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getClasses() as $chast_rechi_class) {
            $this->__cache_priznaki[$chast_rechi_class] = forward_static_call([$chast_rechi_class, 'getMorphology']);
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getLvl2() as $id) {
            foreach (\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getClasses() as $chast_rechi_id => $class) {
                $res = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getClassByChastRechiAndPriznak(
                    $chast_rechi_id,
                    $id
                );
                $this->__cache_priznak_class[$chast_rechi_id][$id] = $res;
            };
        }
        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantsLvl2() as $priznak_class => $id) {
            foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getBaseClasses() as $variants) {
                foreach ($variants as $priznak_base_class) {
                    $this->__cache_is_a[$priznak_base_class][$priznak_class] =
                        is_a($priznak_class, $priznak_base_class, true);
                }
            }
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getBaseClasses() as $variants) {
            foreach ($variants as $priznak_base_class) {

                $null = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistryParent::getNullClassByBaseClass(
                    $priznak_base_class
                );

                $this->__cache_priznak_null_4_clone[$priznak_base_class] = new $null;
            }
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getClasses() as $class) {
            $reflector = new \ReflectionClass($class);
            $this->__cache_chast_rechi_4_clone[$class] = $reflector->newInstanceWithoutConstructor();
            // todo сделать storage, text, initial form Доступными через рефлесию, и убрать у них публичность
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantsLvl2() as $priznak_class => $id) {
            $this->__cache_priznak_4_clone[$priznak_class] = new $priznak_class;
        }

        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getBaseClasses() as $base_id => $variants) {
            foreach ($variants as $chas_rechi_id => $priznak_base_class) {
                $base[$priznak_base_class] = $base_id;
            }
        }
    }

    /**
     * @brief Метод для генерирования слов и пунктуации с сохранением их последовательности
     *
     * @param string[] $words
     * @param int $search_mode
     * @return \Aot\Unit hashmap
     */
    public function getSlovaWithPunctuation(array $words, $search_mode = self::SEARCH_MODE_DEFAULT)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }

        assert(is_int($search_mode));

        $result = [];
        $words_to_find = [];
        foreach ($words as $index => $word) {
            if (preg_match(static::REGULAR_FOR_WHITE_LIST, $word)) {
                $result[$index] = [];
                $words_to_find[$index] = $word;
            } else {
                $result[$index] = [\Aot\RussianSyntacsis\Punctuaciya\Factory::getInstance()->build($word)];
            }
        }

        $slova = $this->getSlova(
            array_unique($words_to_find),
            $search_mode
        );

        foreach ($words_to_find as $index => $word) {
            $result[$index] = $slova[$word];
        }
        return $result;
    }

    /**
     * @param string[] $words
     * @param int $search_mode
     * @return Slovo[][]
     */
    public function getSlova(array $words, $search_mode = self::SEARCH_MODE_DEFAULT)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }

        assert(is_int($search_mode));

        $this->setSearchMode($search_mode);

        if (empty($words)) {
            return [];
        }

        $this->assertNotDuplicates($words);

        list($simple_words, $composite_words) = CompositeWordProcessor::splitArrayWords($words);

        $simple = $this->processSimpleWords($simple_words);

        $composite = $this->processCompositeWords($composite_words);

        $result = [];
        foreach ($words as $word) {

            if (isset($simple[$word])) {

                $result[$word] = $simple[$word];

            } elseif (isset($composite[$word])) {

                $result[$word] = $composite[$word];

            } else {

                throw new \LogicException ("missed word  " . var_export($word, 1));
            }
        }


        if (!$this->isSearchModeNotUsePredictor()) {

            foreach ($result as $word_name => $words_array) {

                if ($result[$word_name] !== []) {
                    continue;
                }

                $slova = $this->predict($word_name);

                if (!empty($slova[0])) {
                    $result[$word_name] = $slova[0];
                    continue;
                }
            }
        }

        if ($this->isSearchModeAddNullWords()) {
            foreach ($result as $word_name => $words_array) {
                $result[$word_name] = $result[$word_name] ?: [\Aot\RussianMorphology\NullWord::create($word_name)];
            }
        }

        return $result;
    }

    /**
     * @param int $search_mode
     */
    protected function setSearchMode($search_mode)
    {
        assert(is_int($search_mode));

        $this->search_mode = self::SEARCH_MODE_DEFAULT | $search_mode;
    }

    protected function assertNotDuplicates($words)
    {
        $counted_values = array_count_values($words);

        if (count($counted_values) === 0) {
            return;
        }

        if (max($counted_values) > 1) {

            $duplicates = array_filter($counted_values, function ($a) {
                return $a > 1;
            });

            throw new DuplicateException("входной массив слов не должен содержать дубликаты " . var_export($duplicates, 1));
        }
    }

    /**
     * @param string[] $words
     * @return Slovo
     * @throws \Exception
     */
    protected function processSimpleWords(array $words)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }

        $this->assertNotDuplicates($words);

        $is_search_mode_sensitive = $this->isSearchModeCaseSensitive();

        $is_search_mode_by_initial_form = $this->isSearchModeByInitialForm();

        /** @var Slovo $slova */
        $slova = [];
        foreach ($words as $word) {
            $slova[$word] = [];
        }

        /** @var \TextPersistence\API\APIcurrent $api */
        $api = \TextPersistence\API\TextAPI::getAPI();

        $query_builder = $api
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('f')
            ->from(\TextPersistence\Entities\TextEntities\Form::class, 'f')
            ->leftJoin('f.mword', 'w');


        $param_name_words = ':words';

        if ($is_search_mode_by_initial_form) {
            $query_builder
                ->leftJoin('f.initialForm', 'wi')
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


        $query->setFetchMode(
            \TextPersistence\Entities\TextEntities\Form::class,
            'wordClass',
            \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER
        );
        $query->setFetchMode(
            \TextPersistence\Entities\TextEntities\Form::class,
            'initialForm',
            \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER
        );

        /** @var \TextPersistence\Entities\TextEntities\Form[] $forms */
        $forms = $query->getResult();

        $factory = \Aot\RussianMorphology\FactoryFromEntity::get();

        foreach ($forms as $form) {

            if ($is_search_mode_by_initial_form) {
                $word_form = $form->getInitialForm()->getWord();
            } else {
                $word_form = $form->getMword()->getWord();
            }

            $slova[$word_form][] = $factory->buildFromEntity($form);

            if (!$is_search_mode_sensitive) {
                if ($word_form !== mb_strtolower($word_form, Encodings::UTF_8)) {
                    $slova[$word_form][] = $factory->buildFromEntity($form);
                }
            }
        }

        return $slova;
    }

    /**
     * @return bool
     */
    protected function isSearchModeCaseSensitive()
    {
        return (boolean)($this->search_mode & static::SEARCH_MODE_CASE_SENSITIVE);
    }

    /**
     * @return bool
     */
    protected function isSearchModeByInitialForm()
    {
        return (boolean)($this->search_mode & static::SEARCH_MODE_BY_INITIAL_FORM);
    }

    /**
     * @param string[] $words
     * @return string[]
     */
    protected function arrayToLower($words)
    {
        $lower_words = [];
        foreach ($words as $word) {
            $lower_words[] = mb_strtolower($word, Encodings::UTF_8);
        }

        return $lower_words;
    }

    /**
     * @return static
     */
    public static function get()
    {
        if (empty(static::$uniqueInstances[static::class])) {
            static::$uniqueInstances[static::class] = new static;
        }

        return static::$uniqueInstances[static::class];
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
     * @return Slovo|null
     */
    public function build($text, $initial_form, $chast_rechi_id, array $ids)
    {
        assert(is_string($text));
        assert(is_string($initial_form));
        foreach ($ids as $id) {
            assert(is_int($id));
        }
        assert(is_int($chast_rechi_id));

        $chast_rechi_class = $this->__cache_chast_rechi_class[$chast_rechi_id];

        /** @var string[] $priznaki */
        $priznaki = $this->__cache_priznaki[$chast_rechi_class];

        $priznaki_objects = [];
        foreach ($priznaki as $priznak_name => &$priznak_base_class) {

            foreach ($ids as $id) {

                if (!isset($this->__cache_priznak_class[$chast_rechi_id][$id])) {
                    /*$this->error_log[] = [
                        'id' => $wordForm->getId(),
                        'chast_rechi_id' => $chast_rechi_id,
                        'priznak_value_id' => $id
                    ];*/
                    throw new \LogicException(
                        "chast rechi id = $chast_rechi_id doesn't support priznak value id =  $id"
                    );
                }

                $priznak_class = $this->__cache_priznak_class[$chast_rechi_id][$id];

                if ($this->__cache_is_a[$priznak_base_class][$priznak_class]) {
                    $priznaki_objects[$priznak_name] = clone $this->__cache_priznak_4_clone[$priznak_class];
                }
            }

            if (empty($priznaki_objects[$priznak_name])) {
                $priznaki_objects[$priznak_name] = clone $this->__cache_priznak_null_4_clone[$priznak_base_class];
            }
        }

        $ob = clone $this->__cache_chast_rechi_4_clone[$chast_rechi_class];
        $ob->storage = $priznaki_objects;
        $ob->text = $text;
        $ob->initial_form = $initial_form;

        return $ob;
    }

    /**
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     */
    protected function processCompositeWords(array $words)
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

            $splitted = \Aot\RussianMorphology\CompositeWordProcessor::splitWord($word);

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

        $factory = \Aot\RussianMorphology\FactoryFromEntity::get();

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

                    $result[$word_text][] = $factory->build(
                        $word_text,
                        \Aot\RussianMorphology\CompositeWordProcessor::get()
                            ->joinParts($slovo_part1->getInitialForm(), $text_part2),
                        \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getIdByMockClass($slovo_part1),
                        $ids
                    );
                }
            }
        }

        return $result;
    }

    /**
     * @return bool
     */
    protected function isSearchModeNotUsePredictor()
    {
        return (boolean)($this->search_mode & static::SEARCH_MODE_NOT_USE_PREDICTOR);
    }

    /**
     * @param string $word_name
     * @return Slovo[][]
     */
    protected function predict($word_name)
    {
        return \Aot\RussianMorphology\Factory::getPredictions([$word_name]);
    }

    /**
     * @return bool
     */
    protected function isSearchModeAddNullWords()
    {
        return (boolean)($this->search_mode & static::SEARCH_MODE_ADD_NULL_WORDS);
    }

    /**
     * @param string[] $words
     * @param int $search_mode
     * @return  Slovo[][]
     */
    public function getNonUniqueWords(array $words, $search_mode = self::SEARCH_MODE_DEFAULT)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }

        $slova = $this->getSlova(array_unique($words), $search_mode);

        /** @var Slovo[][] $result */
        $result = [];
        foreach ($words as $word) {
            $found_words = [];

            if (!empty($slova[$word])) {
                foreach ($slova[$word] as $slovo) {
                    $found_words[] = $slovo->reClone();
                }
            }

            $result[] = $found_words;
        }


        return $result;
    }
}

class DuplicateException extends \Aot\RussianMorphology\FactoryException
{

}

class IncorrectMode extends \Aot\RussianMorphology\FactoryException
{

}
