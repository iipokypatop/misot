<?php

namespace AotTest\Functional\Tokenizer;

/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 026, 26, 01, 2016
 * Time: 16:40
 */
class BaseTest extends \MivarTest\Base
{
    public function testLaunch()
    {
        \Aot\Tokenizer\Base::createEmptyConfiguration();
    }

    public function testRun()
    {
        $string = <<<TEXT
 .style.overflow=u),!!d},isMQuerySupported:fu   ull):e.currentStyle).position}), \Base::c\n\r123.232\nФК «Зенит» и полузащитник сборной России и\\n
TEXT;
        $tokenizer = \Aot\Tokenizer\Base::createEmptyConfiguration();

        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);

        $count = $tokenizer->tokenize($string);

        //var_export($tokenizer->getTokens());
        $this->assertEquals(61, $count);

        $recovered_string = '';
        foreach ($tokenizer->getTokens() as $token) {
            $recovered_string .= $token->getText();
        }

        $this->assertEquals($string, $recovered_string);
    }
}

