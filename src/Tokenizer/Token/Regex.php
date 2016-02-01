<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 026, 26, 01, 2016
 * Time: 18:13
 */

namespace Aot\Tokenizer\Token;


class Regex
{
    const REGEX_MODIFIERS = 'misu';
    const REGEX_DELIMITER = '/';
    const REGEX_PLUS = '+';
    const REGEX_STAR = '*';

    const PATTERN_LETTER = '\p{L}'; // Буква	Включает следующие свойства: Ll, Lm, Lo, Lt и Lu.
    const PATTERN_NUMBER = '\p{N}'; // Число

    /*
      const PATTERN_ =  '/\p{C}/misu'; // Другое
      const PATTERN_ =  '/\p{Cc}/misu'; // Control
      const PATTERN_ =  '/\p{Cf}/misu'; // Формат
      const PATTERN_ =  '/\p{Cn}/misu'; // Не присвоено
      const PATTERN_ =  '/\p{Co}/misu'; // Частное использование
      const PATTERN_ =  '/\p{Cs}/misu'; // Суррогат

      const PATTERN_ =  '/\p{Ll}/misu'; // Строчная буква
      const PATTERN_ =  '/\p{Lm}/misu'; // Модификатор буквы
      const PATTERN_ =  '/\p{Lo}/misu'; // Другая буква
      const PATTERN_ =  '/\p{Lt}/misu'; // Заглавная буква
      const PATTERN_ =  '/\p{Lu}/misu'; // Прописная буква
      const PATTERN_ =  '/\p{M}/misu'; // Знак
      const PATTERN_ =  '/\p{Mc}/misu'; // Пробельный знак
      const PATTERN_ =  '/\p{Me}/misu'; // Окружающий знак
      const PATTERN_ =  '/\p{Mn}/misu'; // Не пробельный знак

      const PATTERN_ =  '/\p{Nd}/misu'; // Десятичное число
      const PATTERN_ =  '/\p{Nl}/misu'; // Буквенное число
      const PATTERN_ =  '/\p{No}/misu'; // Другое число
      const PATTERN_ =  '/\p{P}/misu'; // Пунктуация
      const PATTERN_ =  '/\p{Pc}/misu'; // Соединяющая пунктуация
      const PATTERN_ =  '/\p{Pd}/misu'; // Знаки тире
      const PATTERN_ =  '/\p{Pe}/misu'; // Закрывающая пунктуация
      const PATTERN_ =  '/\p{Pf}/misu'; // Заключительная пунктуация
      const PATTERN_ =  '/\p{Pi}/misu'; // Начальная пунктуация
      const PATTERN_ =  '/\p{Po}/misu'; // Другая пунктуация
      const PATTERN_ =  '/\p{Ps}/misu'; // Открывающая пунктуация
      const PATTERN_ =  '/\p{S}/misu'; // Символ
      const PATTERN_ =  '/\p{Sc}/misu'; // Денежный знак
      const PATTERN_ =  '/\p{Sk}/misu'; // Модификатор символа
      const PATTERN_ =  '/\p{Sm}/misu'; // Математический символ
      const PATTERN_ =  '/\p{So}/misu'; // Другой символ
      const PATTERN_ =  '/\p{Z}/misu'; // Разделитель
      const PATTERN_ =  '/\p{Zl}/misu'; // Разделитель строки
      const PATTERN_ =  '/\p{Zp}/misu'; // Разделитель абзаца
      const PATTERN_ =  '/\p{Zs}/misu'; // Пробельный разделитель
    */
    /**
     * @var string[]
     */
    protected $last_patterns = [];

    /**
     * @var string[]
     */
    protected $last_pattern_keys = [];

    /**
     * @var string[]
     */
    protected $last_token_characters = [];

    public static function create()
    {
        $ob = new static;

        return $ob;
    }

    public function run()
    {

    }

    /**
     * @param string $string
     * @return bool
     */
    public function charCanBeComplicated($string)
    {
        assert(is_string($string));

        foreach ($this->getComplicatedPatterns() as $comlicatedPattertn) {

            $res = preg_match($this->buildPattern($comlicatedPattertn), $string);

            if ($res === 1) {
                return true;
            }
        }

        return false;
    }

    protected function getComplicatedPatterns()
    {
        return [
            static::PATTERN_LETTER,
            static::PATTERN_NUMBER,
        ];
    }

    /**
     * @param string $heart
     * @return string
     */
    protected function buildPattern($heart)
    {
        return join('', [
            static::REGEX_DELIMITER,
            $heart,
            static::REGEX_DELIMITER,
            static::REGEX_MODIFIERS
        ]);
    }

    /**
     * @param array $characters
     * @return bool
     */
    public function stringCanBeComplicated(array $characters)
    {
        $this->last_token_characters = $characters;

        $string = join('', $characters);
        $this->last_token_characters = $characters;
        if (count($this->last_patterns) > 0) {

            $last_patterns = [];
            $last_pattern_keys = [];

            foreach ($this->last_patterns as $index => $pattern) {
                $key = $this->last_pattern_keys[$index];

                $res = $this->exec(
                    $this->buildPattern($pattern . static::REGEX_PLUS),
                    $string
                );

                if ($res === 1) {
                    $last_patterns[] = $pattern;
                    $last_pattern_keys[] = $key;
                }
            }

            $this->last_patterns = $last_patterns;
            $this->last_pattern_keys = $last_pattern_keys;

            return count($this->last_patterns) > 0;
        }

        foreach ($this->getComplicatedPatterns() as $key => $comlicatedPattertn) {

            $res = $this->exec(
                $this->buildPattern($comlicatedPattertn . static::REGEX_PLUS),
                $string
            );

            if ($res === 1) {
                $this->last_patterns[] = $comlicatedPattertn;
                $this->last_pattern_keys[] = $key;
            }
        }


        return count($this->last_patterns) > 0;
    }

    /**
     * @param string $pattern
     * @param string $string
     * @return int
     */
    protected function exec($pattern, $string)
    {
        $res = preg_match($pattern, $string);

        if ($res === false) {
            return -1;
        }

        if (is_int($res)) {
            return $res;
        }
    }

    public function reset()
    {
        $this->last_patterns = [];
        $this->last_pattern_keys = [];
    }

    /**
     * @return static
     */
    public function factoryTokenWithLastPatternType()
    {
        if (empty($this->last_pattern_keys)) {
            throw new \LogicException('последний тип обрабатываемого символа не задан');
        }

        $characters = array_slice($this->last_token_characters, 0, count($this->last_token_characters) - 1);

        $text = join('', $characters);

        return \Aot\Tokenizer\Token\Token::create($text, \Aot\Tokenizer\Token\Token::TOKEN_TYPE_WORD);
    }

    /**
     * @param string $c
     * @return Token
     */
    public function factoryTokenFromCharacter($c)
    {
        assert(is_string($c));

        $type = $this->autoDetectTypeOfToken($c);

        return Token::create($c, $type);
    }

    /**
     * @param string $c
     * @return int
     */
    protected function autoDetectTypeOfToken($c)
    {
        assert(is_string($c));

        //return \Aot\Tokenizer\Token\Token::create($c, \Aot\Tokenizer\Token\Token::TOKEN_TYPE_WORD);
        return \Aot\Tokenizer\Token\Token::TOKEN_TYPE_WORD;
    }
}