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
    const START_SHORT = "(^|[^а-яА-ЯЁё])";
    const END_SHORT = "(.|$)";

    protected function getPatterns()
    {
        /**
         * Регулярки для сокращений пишутся по шаблону:
         * (*начальные символы, указывающие на сокращение*)*сокращение*(*конечные символы, указывающие на сокращение*)
         *
         * TODO: Регулярки, заканчиваюшиеся на точку должны оставлять после замены точку, если дальше есть признак нового предложения(1):
         * 2000г. А в другом году...-> 2000{{1}}.
         * 1:
         * (\s)+[А-Я]
         */

        return [
            "/и\\s?т\\.?[пд]\\.?/u", // и тд и тп
            "/" . static::START_SHORT . "[Тт]ел\\.(.)/u", //
            "/" . static::START_SHORT . "[Сс]м\\.?(.|$)/u", // смотри (см.)
            "/" . static::START_SHORT . "см([^а-яА-Я]|$)/u", // сантиметр (см)
            "/[сю]\\.\\s?ш\\./u",
            "/[вз]\\.\\s?д\\./u",
            "/" . static::START_SHORT . "н\\.?э\\.?(.|$)/u", // н.э.
            "/" . static::START_SHORT . "б[\\/\\\\]у(\\.|\\s|$)/u",
            "/([\\d\\s])гг?(\\.|\\s|$)/u", // г,гг
            "/с\\.[\\-\\s]*х\\./u",
            "/" . static::START_SHORT . "[Бб]укв\\.(.|$)/u",
            "/" . static::START_SHORT . "[Тт]ыс\\.(.|$)/u",
            "/" . static::START_SHORT . "[МмТтр]лн(.|$)/u",
            "/" . static::START_SHORT . "[Мм]лрд(.|$)/u",
            "/([\\d\\s])[гт]([^а-яА-ЯёЁ])/u", // грамм/тонна TODO: без семантики могут быть ошибки
            "/(\\d\\s?)с\\.?([\\,\\;])/u", // секунда TODO: без семантики могут быть ошибки
            "/" . static::START_SHORT . "[Чч]ел\\.(.|$)/u", // чел.
            "/" . static::START_SHORT . "[Ээ]кз\\.(.|$)/u", // экз.
            "/" . static::START_SHORT . "к\\.\\s?[тгиюмпфх]\\.\\s?н." . static::END_SHORT . "/u", //  к.т.н., к.г.н.
            "/" . static::START_SHORT . "н\\.\\s?вр?." . static::END_SHORT . "/u", //  н.в.
            "/" . static::START_SHORT . "наст\\.\\s?вр?." . static::END_SHORT . "/u", //  н.в.
            "/" . static::START_SHORT . "б\\.\\s?вр?." . static::END_SHORT . "/u", //  б.в.
            "/" . static::START_SHORT . "буд\\.\\s?вр?." . static::END_SHORT . "/u", //  буд.в.
            "/" . static::START_SHORT . "будущ\\.\\s?вр?." . static::END_SHORT . "/u", //  будущ.в.
            "/" . static::START_SHORT . "пр\\.\\s?вр?." . static::END_SHORT . "/u", //  пр.в.
            "/" . static::START_SHORT . "прош\\.\\s?вр?." . static::END_SHORT . "/u", //  прош.в.
            "/" . static::START_SHORT . "прошедш\\.\\s?вр?." . static::END_SHORT . "/u", //  прошед.в.
            "/([^а-яА-ЯЁё])[дп]р(\\.|$)/u", // и др. и пр.
        ];
    }
}