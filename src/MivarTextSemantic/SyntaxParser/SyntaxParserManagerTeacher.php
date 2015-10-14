<?php

namespace Aot\MivarTextSemantic\SyntaxParser;

/**
 * class SyntaxParserManagerTeacher
 *
 * @brief Класс для администирования и запуска правил синтаксического парсера для обучения
 *
 */


class SyntaxParserManagerTeacher extends ParserManager
{

    public $path_to_rules = "/teacher_syntax_rules";
    /**< Путь к правилам от директории с абстрактным классом*/

    /**< Массив применяемых правил */

    public $array_rule = array('sub_pre_auto'
    );
}