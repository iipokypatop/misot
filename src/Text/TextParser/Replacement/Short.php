<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28/07/15
 * Time: 02:14
 */

namespace Aot\Text\TextParser\Replacement;


class Short extends Base
{
    protected function getPatterns()
    {
        /**
         * Регулярки для сокращений пишутся по шаблону:
         * (*Символы, указывающие на сокращение*)*сокращение*(*Символы, указывающие на сокращение*)
         *
         * TODO: Регулярки, заканчиваюшиеся на точку должны оставлять после замены точку, если дальше есть признак нового предложения(1):
         * 2000г. А в другом году...-> 2000{%1%}.
         * 1:
         * (\s)+[А-Я]
         */

        return [
            "/и\\s?т\\.?[пд]\\.?/u",
            "/тел./u", //
            "/см./u", // TODO
            "/[сю]\\.\\s?ш\\./u",
            "/[вз]\\.\\s?д\\./u",
            "/н\\.?э\\.?/u",

            "/б\\/у/u",
            "/гг?\\./u", // TODO
            "/с\\.[\\-\\s]*х\\./u",
            "/букв\\./u",
            "/тыс\\./u",
            "/млн|млрд|трлн/u",
            "/([\\d\\s])[сгт]([^а-яА-ЯёЁ])/u", // секунда/грамм TODO
            "/чел\\./u", //  TODO
            "/экз\\./u", //  TODO
            "/к\\.\\s?[тгиюмпфх]\\.\\s?н./u", //  TODO
            "/[дп]р\\./u", //  TODO и др. и пр.


//            "/т\\./u", // TODO
        ];
    }

    protected function insertTemplate($preg_replace_matches)
    {
        print_r($preg_replace_matches);
        if (count($preg_replace_matches) === 3) {
            $record_replace = $preg_replace_matches[0];
            foreach ($preg_replace_matches as $key => $match) {
                if ($key > 0) {
                    $record_replace = str_replace($match, "", $record_replace);
                }
            }
            $index = $this->registry->add($preg_replace_matches);
            $this->logger->notice("R: Заменили по шаблону [{$record_replace}], индекс {$index}");
            $preg_replace_matches[2] = ($preg_replace_matches[2] === '.') ? '' : $preg_replace_matches[2];
            return $this->format($index, [$preg_replace_matches[1], $preg_replace_matches[2]]);

        }

        return parent::insertTemplate($preg_replace_matches);
    }
}