<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 09/02/16
 * Time: 17:44
 */

namespace Aot\Text\TextParserByTokenizer;


class PseudoCodeDriver
{

    /**
     * @return \Aot\Text\TextParserByTokenizer\PseudoCodeDriver
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
    }

    /**
     * @param $tokens
     * @return \Aot\Text\TextParserByTokenizer\PseudoCode\TokenFoundPatterns[]
     */
    public function findBorderGroupsOfTokens($tokens)
    {
        // создание псевдокода по токенам
        $pseudo_code = $this->createPseudoCode($tokens);

        // поиск шаблонов в псевдокоде
        $uniting_patterns = PseudoCode\TokenUnitingPatterns::create();
        return $uniting_patterns->findEntryPatterns($pseudo_code);
    }

    /**
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @return string
     */
    protected function createPseudoCode(array $tokens)
    {
        $pseudo_code_array = [];

        // токены соответсвуют юнитам
        foreach ($tokens as $id => $token) {
            $pseudo_code_array[$id] = PseudoCode\TokenPseudoCodeRegistry::getTokenCode($token->getType());
        }
        return join('', $pseudo_code_array);
    }
}