<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 29.03.2016
 * Time: 12:05
 */

namespace Aot\Text\TextParserByTokenizer;


class ConfigurationEndOfSentence
{
    /** @var bool  */
    protected $checking_suitable_text = true;
    /** @var bool  */
    protected $checking_next_unit_is_space = true;
    /** @var bool  */
    protected $checking_next_capital_letter = true;
    /** @var bool  */
    protected $checking_closed_brackets = false;

    /** @var string[] */
    protected $suitable_text = [
        '.',
        '...',
        '!',
        '?',
    ];

    /** @var  \SplObjectStorage карта свойств Unit's. Необходимо для вычленения информации о том, находится ли Unit внутри скобок или снаружи */
    protected $map_property_of_units;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param \Aot\Text\TextParserByTokenizer\Unit[] $units
     * @param int $id
     * @return bool
     */
    public function isEnd(array $units, $id)
    {
        foreach ($units as $unit) {
            assert(is_a($unit, \Aot\Text\TextParserByTokenizer\Unit::class, true));
        }

        assert(is_int($id));

        //Проверка текстовки
        if ($this->checking_suitable_text) {
            if (!$this->isSuitableText($units[$id])) {
                return false;
            }
        }
        //Проверка следующего пробела
        if ($this->checking_next_unit_is_space) {
            if (!$this->existsId($units, $id + 1) || !$this->isSpace($units[$id + 1])) {
                return false;
            }
        }
        //Проверка позаследующей заглавной буквы
        if ($this->checking_next_capital_letter) {
            if (!$this->existsId($units, $id + 2) || !$this->isCapitalizedWord($units[$id + 2])) {
                return false;
            }
        }
        //Проверка всех закрытых скобок
        if ($this->checking_closed_brackets) {
            $this->fillMapPropertyOfUnits($units);
            if (!$this->isClosedEveryBrackets($units[$id])){
                return false;
            }
        }
        return true;
    }

    /**
     * @param \Aot\Text\TextParserByTokenizer\Unit $unit
     * @return bool
     */
    protected function isSuitableText(\Aot\Text\TextParserByTokenizer\Unit $unit)
    {
        foreach ($this->suitable_text as $suitable_text) {
            if ((string)$unit === $suitable_text) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \Aot\Text\TextParserByTokenizer\Unit[] $units
     * @param $id
     * @return bool
     */
    protected function existsId(array $units, $id)
    {
        return isset($units[$id]);
    }
    
    /**
     * Проверка на пробел
     * @param Unit $unit
     * @return bool
     */
    protected function isSpace(\Aot\Text\TextParserByTokenizer\Unit $unit)
    {
        return $unit->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_SPACE;
    }

    /**
     * Проверка на слово, начинающееся с большой буквы
     * @param Unit $unit
     * @return bool
     */
    protected function isCapitalizedWord(\Aot\Text\TextParserByTokenizer\Unit $unit)
    {
        if ($unit->getType() !== \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_WORD
        ) {
            return false;
        }
        $text = $unit->getTokens()[0]->getText();
        $regex = \Aot\Tokenizer\Token\Regex::create(\Aot\Tokenizer\Token\TokenRegexRegistry::PATTERN_UPPERCASE);
        $regex->addStartingCaret();
        return $regex->match($text);
    }

    /**
     * @param boolean $checking_suitable_text
     */
    public function setCheckingSuitableText($checking_suitable_text)
    {
        assert(is_bool($checking_suitable_text));
        $this->checking_suitable_text = $checking_suitable_text;
    }

    /**
     * @param boolean $checking_next_unit_is_space
     */
    public function setCheckingNextUnitIsSpace($checking_next_unit_is_space)
    {
        assert(is_bool($checking_next_unit_is_space));
        $this->checking_next_unit_is_space = $checking_next_unit_is_space;
    }

    /**
     * @param boolean $checking_next_capital_letter
     */
    public function setCheckingNextCapitalLetter($checking_next_capital_letter)
    {
        assert(is_bool($checking_next_capital_letter));
        $this->checking_next_capital_letter = $checking_next_capital_letter;
    }

    /**
     * @param boolean $checking_closed_brackets
     */
    public function setCheckingClosedBrackets($checking_closed_brackets)
    {
        assert(is_bool($checking_closed_brackets));
        $this->checking_closed_brackets = $checking_closed_brackets;
    }

    /**
     * @param \Aot\Text\TextParserByTokenizer\Unit $unit
     * @return bool
     */
    protected function isClosedEveryBrackets($unit)
    {
        return ($this->map_property_of_units[$unit] === 0);
    }
    
    /**
     * @brief Заполняет карту свойств Unit's. Необходимо для вычленения информации о том, находится ли Unit внутри скобок или снаружи
     *
     * @param \Aot\Text\TextParserByTokenizer\Unit[] $units
     */
    protected function fillMapPropertyOfUnits(array $units)
    {
        $this->map_property_of_units = new \SplObjectStorage();
        $open_skobka = '(';
        $close_skobka = ')';
        $nesting_level = 0;

        foreach ($units as $unit) {
            if ($unit->getStringRepresentation() === $open_skobka){
                $this->map_property_of_units[$unit] = $nesting_level;
                $nesting_level++;
                continue;
            }
            if ($unit->getStringRepresentation() === $close_skobka){
                $nesting_level--;
            }
            $this->map_property_of_units[$unit] = $nesting_level;
        }
    }
}