<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 006, 06, 03, 2016
 * Time: 15:45
 */

namespace Aot\RussianMorphology\Factory2;


class Mode 
{
    const SEARCH_MODE_CASE_SENSITIVE = 1;
    const SEARCH_MODE_BY_INITIAL_FORM = 2;
    /**
     * default mode:
     * 0. words must be unique
     * 1. INSENSITIVE
     * 2. NOT BY INITIAL FORM
     * 3. WITH PREDICTOR (if word not found)
     * 4. NO ADDITIONAL NULL WORDS
     */
    const SEARCH_MODE_DEFAULT = 0;
    const SEARCH_MODE_NOT_USE_PREDICTOR = 4;
    const SEARCH_MODE_ADD_NULL_WORDS = 8;
    /**
     * @var int
     */
    protected $search_mode;

    public static function create()
    {
        $ob = new static;

        return $ob;
    }

    /**
     * @param int $search_mode
     */
    public function setSearchMode($search_mode)
    {
        assert(is_int($search_mode));

        $this->search_mode = self::SEARCH_MODE_DEFAULT | $search_mode;
    }

    /**
     * @return bool
     */
    public function isSearchModeCaseSensitive()
    {
        return (boolean)($this->search_mode & static::SEARCH_MODE_CASE_SENSITIVE);
    }

    /**
     * @return bool
     */
    public function isSearchModeByInitialForm()
    {
        return (boolean)($this->search_mode & static::SEARCH_MODE_BY_INITIAL_FORM);
    }

    /**
     * @return bool
     */
    public function isSearchModeNotUsePredictor()
    {
        return (boolean)($this->search_mode & static::SEARCH_MODE_NOT_USE_PREDICTOR);
    }


    /**
     * @return bool
     */
    public function isSearchModeAddNullWords()
    {
        return (boolean)($this->search_mode & static::SEARCH_MODE_ADD_NULL_WORDS);
    }

}