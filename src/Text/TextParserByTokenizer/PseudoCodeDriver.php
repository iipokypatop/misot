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

    public static function create()
    {
        return new static();
    }


    /**
     * @param $tokens
     * @return \Aot\Text\TextParserByTokenizer\PseudoCode\FoundPatterns[]
     */
    public function findBorderGroupsOfTokens($tokens)
    {

        // создание псевдокода по токенам
        $pseudo_code = $this->createPseudoCode($tokens);

        // поиск шаблонов в псевдокоде
        $uniting_patterns = PseudoCode\UnitingPatterns::create();
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
            $pseudo_code_array[$id] = PseudoCode\PseudoCodeRegistry::getTokenCode($token->getType());
        }
        return join('', $pseudo_code_array);
    }
}