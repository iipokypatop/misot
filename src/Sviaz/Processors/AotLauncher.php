<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 25/11/15
 * Time: 19:32
 */

namespace Aot\Sviaz\Processors;


class AotLauncher
{

    /**
     * Получение синтаксической модели через АОТ
     * @param $text
     * @return \Sentence_space_SP_Rel[]
     */
    public static function getSyntaxModel($text)
    {
        assert(is_string($text));

        $mivar = new \DMivarText(['txt' => $text]);

        $mivar->syntax_model();

        $result = $mivar->getSyntaxModel();

        return $result ?: [];
    }
}