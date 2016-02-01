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
        \Aot\Tokenizer\Base::create();
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


ФК «Зенит» и полузащитник сборной России и

TEXT;
        $tokenizer = \Aot\Tokenizer\Base::create();

        $result = $tokenizer->tokenize($string);

        $this->assertEquals(true, $result);

        var_export($tokenizer->getTokens());

    }
}