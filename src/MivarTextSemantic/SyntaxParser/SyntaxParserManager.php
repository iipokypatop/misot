<?php

namespace Aot\MivarTextSemantic\SyntaxParser;

/**
 * class SyntaxParserManager
 *
 * @brief Класс для администирования и запуска правил синтаксического парсера
 *
 */


class SyntaxParserManager extends ParserManager
{

    public $path_to_rules = "/syntax_rules";
    /**< Путь к правилам от директории с абстрактным классом*/

    /**< Массив применяемых правил */

    public $array_rule = array(
        'sub1_pre',
        'complex_predicate',
        'adjunct_verb',
        'adjective4noun',
        'pretextToNoun'
    );
}