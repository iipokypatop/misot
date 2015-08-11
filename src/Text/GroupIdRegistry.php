<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.07.2015
 * Time: 14:02
 */

namespace Aot\Text;


use Aot\Registry\Uploader;

class GroupIdRegistry
{
    use Uploader;

    const BIT = 1;
    const NIKTO = 2;
    const PRITYAZHATELNIE_1_AND_2_LITSO = 3;
    const UKAZATELNIE_MESTOIMENIYA = 4;
    const ETOVSE = 5;
    const NARECH_FOR_PRIL_OR_NARECH = 6;
    const NARECH_FOR_GL = 7;
    const DEFISNARECH_FOR_GL = 8;

    /**
     * @return array[] слова в нижнем регистре
     */
    public static function getWordVariants()
    {
        return [
            static::BIT => ['будет', 'быть', 'был', 'была', 'есть', 'есть', 'есть', 'есть', 'есть', 'будь', 'будьте', 'есть', 'были', 'было'], // нижний регистр
            static::NIKTO => ['никто', 'ничто'], // нижний регистр
            static::PRITYAZHATELNIE_1_AND_2_LITSO => ['мой', 'моя', 'моё', 'мое','мои', 'наш', 'наша', 'наше', 'наши', 'твой', 'твоя', 'твое', 'твоё','твои','твоим', 'ваш', 'ваша', 'ваше', 'ваши', 'их'], // нижний регистр
            static::UKAZATELNIE_MESTOIMENIYA => ['тот', 'та', 'то', 'те', 'этот', 'эта', 'это', 'эти', 'такими', 'какими', 'каков', 'таков', 'какова', 'такова', 'каково', 'таково', 'каковы', 'таковы', 'такой', 'такая', 'такое', 'такие', 'какой', 'какая', 'какое', 'какие'], // нижний регистр
            static::ETOVSE => ['это', 'все', 'всё'], // нижний регистр
            static::NARECH_FOR_PRIL_OR_NARECH => ['очень','преочень','очень-очень','очень-преочень','страшно','престрашно','страшно-престрашно','удивительно','исключительно','слишком','много','премного','много-много','много-премного','неестественно','гораздо','прямо','абсолютно','совершенно','чересчур','крайне','необычно','весьма','совсем','совсем-совсем','настолько','вдвое','впятеро','едва','едва-едва','еле','еле-еле','чуть-чуть','немного','несколько','капельку','крошечку'], // нижний регистр
            static::NARECH_FOR_GL => ['адски','ввосьмеро','внизу','аккуратно','вглубь','вниз','активно','вдалеке','вверху','анафемски','вдали','внутри','вдаль','внутрь','бегло','вдвоем','бегом','вдвое','вовек','безвременно','вдевятеро','вовеки','безгранично','вдевятером','бездейственно','вдесятеро','вовремя','бездеятельно','вдесятером','вовсю','безмерно','вдоволь','безмятежно','вдосталь','вокруг','безотлагательно','важно','вдребезги','восьмером','безотчетно','везде','вообще','безразлично','великолепно','вон','безучастно','верхом','бескрайне','верхом','вперевалку','бесконечно','весело','вперегонки','беспредельно','весной','вперед','беспрерывно','впереди','беспрестанно','вереницей','вплавь','бесполезно','верно','вполголоса','беспочвенно','вечерами','вполне','бессмысленно','вечерком','впоследствии','бессодержательно','вечером','вправо','бессознательно','вперед','бесцельно','впредь','впервые','благоразумно','взад','вприпрыжку','близко','винтообразно','вприсядку','быстро','вкратце','впятером','вкривь','впятером','близи','вконец','вразброд','вбок','вкось','вразброс','ввек','влево','вразвалку','вверх','вместе','вразлад','вверху','вмиг','вразнобой','ввечеру','вразумительно','ввысь','вначале','врассыпную','всегда','далеко','желчно','вседневно','даром','жизнерадостно','всемерно','жутко','всемером','дерзко','всерьез','деятельно','заблаговременно','всечасно','длительно','довольно','завсегда','все','время','днем','задаром','днями','задушевно','зазря','вскачь','докуда','занапрасно','вслух','долго','занимательно','долго-долго','заносчиво','всюду','долговечно','заново','втихаря','долговременно','занятно','втихомолку','долу','замечательно','втихую'], // нижний регистр
            static::DEFISNARECH_FOR_GL => ['во-первых','во-вторых','в-пятых','в-сотых','в-последних','по-хорошему','по-доброму','по-летнему','по-особенному','по-дружески','по-хозяйски','по-китайски','по-русски','по-латыни','по-собачьи','по-заячьи','по-лисьи','по-медвежьи','во-вдовьи','по-нашему','по-вашему','по-моему','по-всякому','по-ньюйоркски','вот-вот','еле-еле','давно','чуть-чуть','тихо-тихо','всего-навсего','крепко-накрепко','как-никак','мало-помалу','мало-мальски','перво-наперво','нежданно-негаданно','бухты-барахты','худо-бедно','не сегодня-завтра','подобру-поздорову','точь-в-точь','на-гора','где-то','когда-нибудь','как-либо','кое-как','долго-таки','вась-вась','перво-наперво','по-английски','по-барски','по-будничному','по-быстрому','по-волчьи','по-всякому','по-вчерашнему','по-гречески','по-детски','по-злодейски','по-иному','по-каковски','по-латыни','по-летнему','полным-полно','по-людски','по-медвежьи','по-мещански','по-моему','по-молодецки','по-настоящему','по-немецки','по-нищенски','по-новому','по-нынешнему','по-охотничьи','по-пластунски','по-прежнему','по-приятельски','по-пустому','по-славянски','по-собачьи','по-советски','по-соседски','по-старинному','по-старому','по-турецки','по-человечески','почему-нибудь','просто-напросто','раным-рано','строго-настрого','там-сям','точь-в-точь','чисто-начисто','чуть-чуть','шалтай-болтай','шаляй-валяй','шиворот-навыворот','как-нибудь','как-никак','какой-нибудь','какой-то','как-то','когда-нибудь','когда-то','кое-где','кой-где','кое-как','кой-как'],

        ];
    }

    public static function getNames()
    {
        return [
            static::BIT => 'глагол быть',
            static::NIKTO => 'никто ничто',
            static::PRITYAZHATELNIE_1_AND_2_LITSO => 'притяжательные местоимения 2 лицо',
            static::UKAZATELNIE_MESTOIMENIYA => 'указательные местоимения',
            static::ETOVSE => 'это все',
            static::NARECH_FOR_PRIL_OR_NARECH => 'наречия, образующие связь с прилагательными или наречиями',
            static::NARECH_FOR_GL => 'наречия, образующие связь с глаголом',
            static::DEFISNARECH_FOR_GL => 'наречия, написанные через дефис и образующие связь с глаголом',
        ];
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \AotPersistence\Entities\TextGroup::class;
    }

    /**
     * @return int[]
     */
    protected function getIds()
    {
        return array_keys(static::getNames());
    }

    /**
     * @return string[]
     */
    protected function getFields()
    {
        return[
            'name' => [static::class, 'getNames'],
        ];
    }
}