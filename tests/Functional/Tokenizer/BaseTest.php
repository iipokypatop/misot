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
        /*
        .style.overflow=u),!!d},isMQuerySupported:function(t){var n=window.matchMedia||window.msMatchMedia,i=!1;return
n?i=n(t).matches:this.injectElementWithStyles("@media "+t+" { #"+e+" { position: absolute; }
}",function(e){i="absolute"===(window.getComputedStyle?getComputedStyle(e,null):e.currentStyle).position}),i},
isInlineBlockSupported:function(){var e,t=$('<span style="display:none"><div style="width:100px;display:inline-block"></div><div
         $string */
        $string = <<<'TEXT'
 \Aot\Tokenizer\Base::c
123.232

ФК «Зенит» и полузащитник сборной России и

TEXT;
        $tokenizer = \Aot\Tokenizer\Base::createEmptyConfiguration();

        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);


        $count = $tokenizer->tokenize($string);

//        $this->assertEquals(36, $count);

        var_export($tokenizer->getTokens());
        $recovered_string = '';
        foreach ($tokenizer->getTokens() as $token) {
            $recovered_string .= $token->getText();
        }

        $this->assertEquals($string, $recovered_string);
    }
}

