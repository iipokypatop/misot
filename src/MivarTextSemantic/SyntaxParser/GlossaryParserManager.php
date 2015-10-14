<?php

namespace Aot\MivarTextSemantic\SyntaxParser;


/**
 * class SyntaxParserManager
 *
 * @brief Класс для администирования и запуска правил синтаксического парсера
 *
 */
class GlossaryParserManager extends ParserManager
{
    public $path_to_rules = "/glossary_rules";
    /**< Путь к правилам от директории с абстрактным классом*/
    /**< Массив применяемых правил */
    public $array_rule = array(
        'par_gen', 'synonyms'
    );
}