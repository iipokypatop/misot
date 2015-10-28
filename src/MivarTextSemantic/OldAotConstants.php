<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 014, 14.10.2015
 * Time: 14:42
 */

namespace Aot\MivarTextSemantic;

class OldAotConstants
{

    # собственное-нарицательное
    const SELF_NOMINAL = 10;

    public static function SELF_NOMINAL()
    {
        return static::SELF_NOMINAL;
    }

    # собственное
    const SELF = 24;

    public static function SELF()
    {
        return static::SELF;
    }

    # нарицательное
    const NOMINAL = 25;

    public static function NOMINAL()
    {
        return static::NOMINAL;
    }

    # склонение
    const DECLENSION = 12;

    public static function DECLENSION()
    {
        return static::DECLENSION;
    }

    # склонение 1
    const DECLENSION_1 = 28;

    public static function DECLENSION_1()
    {
        return static::DECLENSION_1;
    }

    # склонение 1
    const DECLENSION_2 = 29;

    public static function DECLENSION_2()
    {
        return static::DECLENSION_2;
    }

    # склонение 1
    const DECLENSION_3 = 30;

    public static function DECLENSION_3()
    {
        return static::DECLENSION_3;
    }

    # форма слова
    const WORD_FORM = 17;

    public static function WORD_FORM()
    {
        return static::WORD_FORM;
    }

    # краткая форма слова
    const SHORT_WORD_FORM = 47;

    public static function SHORT_WORD_FORM()
    {
        return static::SHORT_WORD_FORM;
    }

    # полная форма слова
    const FULL_WORD_FORM = 46;

    public static function FULL_WORD_FORM()
    {
        return static::FULL_WORD_FORM;
    }

    # возвратность
    const RETRIEVABLE_IRRETRIEVABLE = 9;

    public static function RETRIEVABLE_IRRETRIEVABLE()
    {
        return static::RETRIEVABLE_IRRETRIEVABLE;
    }

    # возвратный
    const RETRIEVABLE = 22;

    public static function RETRIEVABLE()
    {
        return static::RETRIEVABLE;
    }

    # невозвратный
    const IRRETRIEVABLE = 23;

    public static function IRRETRIEVABLE()
    {
        return static::IRRETRIEVABLE;
    }


    # залог
    const VOICE = 9;

    public static function VOICE()
    {
        return static::VOICE;
    }

    # страдательный залог
    const PASSIVE_VOICE = 77;

    public static function PASSIVE_VOICE()
    {
        return static::PASSIVE_VOICE;
    }

    # действительный залог
    const ACTIVE_VOICE = 76;

    public static function ACTIVE_VOICE()
    {
        return static::ACTIVE_VOICE;
    }

    # переходный
    const TRANSITIVE = 6;

    public static function TRANSITIVE()
    {
        return static::TRANSITIVE;
    }

    # непереходный
    const INTRANSITIVE = 7;

    public static function INTRANSITIVE()
    {
        return static::INTRANSITIVE;
    }

    # положительная степень сравнения
    const POSITIVE_DEGREE_COMPARISON = 41;

    public static function POSITIVE_DEGREE_COMPARISON()
    {
        return static::POSITIVE_DEGREE_COMPARISON;
    }

    # сравнительная степень сравнения
    const COMPARATIVE_DEGREE_COMPARISON = 42;

    public static function COMPARATIVE_DEGREE_COMPARISON()
    {
        return static::COMPARATIVE_DEGREE_COMPARISON;
    }


    # спряжение
    const CONJUGATION = 2;

    public static function CONJUGATION()
    {
        return static::CONJUGATION;
    }

    # спряжение 1
    const CONJUGATION_1 = 4;

    public static function CONJUGATION_1()
    {
        return static::CONJUGATION_1;
    }

    # спряжение 2
    const CONJUGATION_2 = 5;

    public static function CONJUGATION_2()
    {
        return static::CONJUGATION_2;
    }

    # наклонение изъявительное
    const MOOD_INDICATIVE = 8;

    public static function MOOD_INDICATIVE()
    {
        return static::MOOD_INDICATIVE;
    }

    # наклонение повелительное
    const MOOD_IMPERATIVE = 9;

    public static function MOOD_IMPERATIVE()
    {
        return static::MOOD_IMPERATIVE;
    }

    # наклонение сослагательное (условное)
    const MOOD_SUBJUNCTIVE = 10;

    public static function MOOD_SUBJUNCTIVE()
    {
        return static::MOOD_SUBJUNCTIVE;
    }

    # разряд прилагательных
    const RANK_ADJECTIVES = 14;

    public static function RANK_ADJECTIVES()
    {
        return static::RANK_ADJECTIVES;
    }

    # качественное прилагательное
    const QUALIFYING_ADJECTIVE = 38;

    public static function QUALIFYING_ADJECTIVE()
    {
        return static::QUALIFYING_ADJECTIVE;
    }

    # относительное прилагательное
    const RELATIVE_ADJECTIVE = 39;

    public static function RELATIVE_ADJECTIVE()
    {
        return static::RELATIVE_ADJECTIVE;
    }

    # притяжательное прилагательное
    const POSSESSIVE_ADJECTIVE = 40;

    public static function POSSESSIVE_ADJECTIVE()
    {
        return static::POSSESSIVE_ADJECTIVE;
    }

    # разряд местоимений
    const RANK_PRONOUNS = 18;

    public static function RANK_PRONOUNS()
    {
        return static::RANK_PRONOUNS;
    }

    # личное местоимение
    const PERSONAL_PRONOUN = 48;

    public static function PERSONAL_PRONOUN()
    {
        return static::PERSONAL_PRONOUN;
    }

    # возвратное местоимение
    const REFLEXIVE_PRONOUN = 49;

    public static function REFLEXIVE_PRONOUN()
    {
        return static::REFLEXIVE_PRONOUN;
    }

    # вопросительное местоимение
    const INTERROGATIVE_PRONOUN = 50;

    public static function INTERROGATIVE_PRONOUN()
    {
        return static::INTERROGATIVE_PRONOUN;
    }

    #  относительное местоимение
    const RELATIVE_PRONOUN = 51;

    public static function RELATIVE_PRONOUN()
    {
        return static::RELATIVE_PRONOUN;
    }

    # неопределенное местоимение
    const INDEFINITE_PRONOUN = 52;

    public static function INDEFINITE_PRONOUN()
    {
        return static::INDEFINITE_PRONOUN;
    }

    # притяжательное местоимение
    const POSSESSIVE_PRONOUN = 53;

    public static function POSSESSIVE_PRONOUN()
    {
        return static::POSSESSIVE_PRONOUN;
    }

    # отрицательное местоимение
    const NEGATIVE_PRONOUN = 54;

    public static function NEGATIVE_PRONOUN()
    {
        return static::NEGATIVE_PRONOUN;
    }

    # определительное местоимение
    const ATTRIBUTIVE_PRONOUN = 55;

    public static function ATTRIBUTIVE_PRONOUN()
    {
        return static::ATTRIBUTIVE_PRONOUN;
    }

    # указательное местоимение
    const DEMONSTRATIVE_PRONOUN = 56;

    public static function DEMONSTRATIVE_PRONOUN()
    {
        return static::DEMONSTRATIVE_PRONOUN;
    }

}
