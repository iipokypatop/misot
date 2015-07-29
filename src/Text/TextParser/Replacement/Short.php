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

    protected function insertTemplate($record)
    {
        if( count($record) === 3)
        {
            $record_replace = $record[0];
            foreach ($record as $key => $match) {
                if($key > 0){
                    $record_replace = str_replace($match, "", $record_replace);
                }
            }
            $index = $this->registry->add($record);
            $this->logger->notice("R: Заменили по шаблону [{$record_replace}], индекс {$index}");
            return $record[1] . "{%" . $index . "%}" . $record[2];

        }
        else{
            $record = $record[0];
        }
        $index = $this->registry->add($record);
        $this->logger->notice("R: Заменили по шаблону [{$record}], индекс {$index}");
        // add logger
        return "{%" . $index . "%}";
    }
}