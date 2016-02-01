<?php

namespace Aot\MivarTextSemantic;

class Constants
{
//    const MODE = "DEVELOP";
//    const DB_CONNECTION = "host=test-db.mivar.pro dbname=mivar_text user=postgres password=@Mivar123User@";
//    const DB_MIVAR_LOGIC = "host=test-db.mivar.pro dbname=mivar_logic user=postgres password=@Mivar123User@";
//    const DB_MIVAR_INTELLIGENCE = "host=test-db.mivar.pro dbname=mivar_intelligence_test user=postgres password=@Mivar123User@";
//    const DB_MIVAR_INTELLIGENCE_DIMA = "host=test-db.mivar.pro dbname=mivar_intelligence_dima user=postgres password=@Mivar123User@";
//    const DB_MIVAR_INTELLIGENCE_MONGO = "mongodb://mivar_intel:V1azfwd3@test-db.mivar.pro:27017/mivar_intelligence_test";
//    const XHPROF_ROOT = "/var/php_only/xhprof-0.9.4/";
//    const XHPROF_ON = "1";
//    const DB_TESTCASES = "mongodb://text_test:nusf7TQq@test-db.mivar.pro:27017/text_testcases";
//    const DB_TESTCASES_DBNAME = "text_testcases";
//    const REDIS_HOST_6379 = "127.0.0.1";
//    const REDIS_PORT_6379 = "6379";

    const CASE_SUBJECTIVE_SHORT = 'им';
    const CASE_GENITIVE_SHORT = 'рд';
    const CASE_GENITIVE2_SHORT = 'рд2';
    const CASE_DATIVE_SHORT = 'дт';
    const CASE_ACCUSATIVE_SHORT = 'вн';
    const CASE_INSTRUMENTAL_SHORT = 'тв';
    const CASE_PREPOSITIONAL_SHORT = 'пр';
    const CASE_PREPOSITIONAL2_SHORT = 'пр2';
    const GENUS_MASCULINE_SHORT = 'мр';
    const GENUS_FEMININE_SHORT = 'жр';
    const GENUS_NEUTER_SHORT = 'ср';
    const NUMBER_SINGULAR_SHORT = 'ед';
    const NUMBER_PLURAL_SHORT = 'мн';
    const FORM_IMMUTABLE_SHORT = 'неизм';
    const COMMUNION_PASSIVE_SHORT = 'стр';
    const COMMUNION_VALID_SHORT = 'дст';
    const FORM_TRANSITIONAL_SHORT = 'пе';
    const DEGREE_SUPERLATIVE_SHORT = 'прев';
    const ANIMALITY_ANIMATE_SHORT = 'од';
    const ANIMALITY_INANIMATE_SHORT = 'но';
    const TIME_FUTURE_SHORT = 'буд';
    const TIME_PAST_SHORT = 'прш';
    const TIME_SIMPLE_SHORT = 'нст';
    const MOOD_IMPERATIVE_SHORT = 'пвл';
    const PERSON_RIFST_SHORT = '1л';
    const PERSON_SECOND_SHORT = '2л';
    const PERSON_THIRD_SHORT = '3л';
    const VIEW_PERFECTIVE_SHORT = 'св';
    const VIEW_IMPERFECT_SHORT = 'нс';
    const FIO_FIRST_NAME_SHORT = 'нс';
    const FIO_LAST_NAME_SHORT = 'нс';
    const FIO_MIDDLE_NAME_SHORT = 'нс';

###############################################

    const VIEW_PERFECTIVE_SHORT_MIVAR = 'сов';
    const VIEW_IMPERFECT_SHORT_MIVAR = 'несов';
    const FORM_TRANSITIONAL_SHORT_MIVAR = 'перех';
    const MOOD_IMPERATIVE_SHORT_MIVAR = 'повел';
    const TIME_SIMPLE_SHORT_MIVAR = 'наст';
    const TIME_PAST_SHORT_MIVAR = 'прош';
    const TIME_FUTURE_SHORT_MIVAR = 'буд';
    const NUMBER_SINGULAR_SHORT_MIVAR = 'ед.ч.';
    const NUMBER_PLURAL_SHORT_MIVAR = 'мн.ч.';
    const PERSON_RIFST_SHORT_MIVAR = '1-е';
    const PERSON_SECOND_SHORT_MIVAR = '2-е';
    const PERSON_THIRD_SHORT_MIVAR = '3-е';
    const GENUS_MASCULINE_SHORT_MIVAR = 'м.р.';
    const GENUS_FEMININE_SHORT_MIVAR = 'ж.р.';
    const GENUS_NEUTER_SHORT_MIVAR = 'с.р.';
    const ANIMALITY_ANIMATE_SHORT_MIVAR = 'одуш';
    const ANIMALITY_INANIMATE_SHORT_MIVAR = 'неодуш';
    const CASE_SUBJECTIVE_SHORT_MIVAR = 'и.п.';
    const CASE_GENITIVE_SHORT_MIVAR = 'р.п.';
    const CASE_DATIVE_SHORT_MIVAR = 'д.п.';
    const CASE_ACCUSATIVE_SHORT_MIVAR = 'в.п.';
    const CASE_INSTRUMENTAL_SHORT_MIVAR = 'т.п.';
    const CASE_PREPOSITIONAL_SHORT_MIVAR = 'п.п.';
###############################################
    const PRONOUN_ADJECTIVE_SHORT_MIVAR = 'прил';
    const FORM_ADJECTIVE_SHORT_MIVAR = 'кр';
    const COMPARATIVE_SHORT_MIVAR = 'сравн';
    const ORDINAL_SHORT_MIVAR = 'пор';
    const COMMUNION_VALID_SHORT_MIVAR = 'действ';
    const COMMUNION_PASSIVE_SHORT_MIVAR = 'стр';

###############################################
    const CASE_SUBJECTIVE_FULL = 'именительный';
    const CASE_GENITIVE_FULL = 'родительный';
    const CASE_DATIVE_FULL = 'дательный';
    const CASE_ACCUSATIVE_FULL = 'винительный';
    const CASE_INSTRUMENTAL_FULL = 'творительный';
    const CASE_PREPOSITIONAL_FULL = 'предложный';
    const GENUS_MASCULINE_FULL = 'мужской род';
    const GENUS_FEMININE_FULL = 'женский род';
    const GENUS_NEUTER_FULL = 'средний род';
    const NUMBER_SINGULAR_FULL = 'единственное число';
    const NUMBER_PLURAL_FULL = 'множественное число';
    const FORM_IMMUTABLE_FULL = 'неизменяемый';
    const FORM_PASSIVE_FULL = 'страдательное';
    const FORM_REAL_FULL = 'действительное';
    const FORM_TRANSITIONAL_FULL = 'переходный';
    const DEGREE_SUPERLATIVE_FULL = 'превосходная';

    const ANIMALITY_ANIMATE_FULL = 'одушевленное';
    const ANIMALITY_INANIMATE_FULL = 'неодушевленное';
    const TIME_FUTURE_FULL = 'будущее';
    const TIME_PAST_FULL = 'прошедшее';
    const TIME_SIMPLE_FULL = 'настоящее';
    const MOOD_IMPERATIVE_FULL = 'повелительное';
    const PERSON_RIFST_FULL = '1 лицо';
    const PERSON_SECOND_FULL = '2 лицо';
    const PERSON_THIRD_FULL = '3 лицо';
    const VIEW_PERFECTIVE_FULL = 'совершенный';
    const VIEW_IMPERFECT_FULL = 'несовершенный';
################################
    const PRONOUN_ADJECTIVE_FULL = 'местоимение-прилагательное';
    const SHORT_FORM_ADJECTIVE_FULL = 'краткая форма';
    const COMPARATIVE_FULL = 'сравнительная степень';
    const ORDINAL_FULL = 'порядковое';
################################

    const VIEW_PERFECTIVE_ID = 2;
    const VIEW_IMPERFECT_ID = 3;
    const FORM_TRANSITIONAL_ID = 6;
    const TIME_SIMPLE_ID = 11;
    const TIME_PAST_ID = 12;
    const TIME_FUTURE_ID = 13;
    const NUMBER_SINGULAR_ID = 14;
    const NUMBER_PLURAL_ID = 15;
    const PERSON_RIFST_ID = 16;
    const PERSON_SECOND_ID = 17;
    const PERSON_THIRD_ID = 18;
    const GENUS_MASCULINE_ID = 19;
    const GENUS_NEUTER_ID = 20;
    const GENUS_FEMININE_ID = 21;


    const CASE_SUBJECTIVE_ID = 32;
    const CASE_GENITIVE_ID = 33;
    const CASE_DATIVE_ID = 34;
    const CASE_ACCUSATIVE_ID = 35;
    const CASE_INSTRUMENTAL_ID = 36;
    const CASE_PREPOSITIONAL_ID = 37;
// const VIEW_IMPERFECT_ID = 24;
// const VIEW_IMPERFECT_ID = 25;
// const VIEW_IMPERFECT_ID = 26;
    const ANIMALITY_ANIMATE_ID = 26;
    const ANIMALITY_INANIMATE_ID = 27;
###################################
    const PRONOUN_ADJECTIVE_ID = 69;
    const SHORT_FORM_ADJECTIVE_ID = 47;
    const COMPARATIVE_ID = 42;
    const ORDINAL_ID = 73;
    const QUANTITATIVE_ID = 74;

    const COMMUNION_VALID_ID = 44;
    const COMMUNION_PASSIVE_ID = 45;
    const DEGREE_SUPERLATIVE_ID = 43;

###################################

    const CASE_WORD = 'падеж';
    const VIEW_WORD = 'вид';
    const TRANSIVITY_WORD = 'переходность';
    const MOOD_WORD = 'наклонение';
    const TIME_WORD = 'время';
    const NUMBER_WORD = 'число';
    const PERSON_WORD = 'лицо';
    const GENUS_WORD = 'род';
    const ANIMALITY_WORD = 'одуш-неодуш';
    const DEGREE_COMPOSITION_WORD = 'степень сравнения';
    const DISCHARGE_COMMUNION_WORD = 'разряд причастия';
    const FORM_IMMUTABLE_WORD = 'неизменяемость';
    const OTGLAGOLNOST_WORD= 'отглагольность';

#####################################
    const TYPE_OF_PRONOUN_WORD = 'тип местоимения';
    const FORM_ADJECTIVE_WORD = 'форма';
    const DEGREE_OF_COMPARISON_WORD = 'степень сравнения';
    const TYPE_OF_NUMERAL_WORD = 'тип числительного';

#####################################
    const OTGLAGOLNOST_ID = 28;
    const CASE_ID = 13;
    const VIEW_ID = 1;
    const TRANSIVITY_ID = 3;
    const MOOD_ID = 4;
    const TIME_ID = 5;
    const NUMBER_ID = 6;
    const PERSON_ID = 7;
    const GENUS_ID = 8;
    const ANIMALITY_ID = 11;
    const DEGREE_COMPOSITION_ID = 15;
    const DISCHARGE_COMMUNION_ID = 16;
    const FORM_IMMUTABLE_ID = 23;
######################################
    const TYPE_OF_PRONOUN_ID = 25;
    const FORM_ADJECTIVE_ID = 17;
    const COMPARATIVE_ADJECTIVE_ID = 15;
    const TYPE_OF_NUMERAL_ID = 26;

######################################

    const PRONOUN_ADJECTIVE_AOT = 'МС-П';
    const PRONOUN_AOT = 'МС';
    const NOUN_AOT = 'С';
    const SHORT_ADJECTIVE_AOT = 'КР_ПРИЛ';
    const SHORT_COMMUNION_AOT = 'КР_ПРИЧАСТИЕ';
    const ADJECTIVE_AOT = 'П';
    const COMPARATIVE_ADJECTIVE_AOT = 'СР_ПРИЛ';
    const INFINITIVE_AOT = 'ИНФИНИТИВ';
    const VERB_AOT = 'Г';
    const ADVERB_AOT = 'Н';
    const PREPOSITION_AOT = 'ПРЕДЛ';
    const PARTICIPLE_AOT = 'ДЕЕПРИЧАСТИЕ';
    const COMMUNION_AOT = 'ПРИЧАСТИЕ';
    const NUMERAL_AOT = 'ЧИСЛ';
    const TYPE_OF_NUMERAL_AOT = 'ЧИСЛ-П';
    const PREDICATIVE_AOT = 'ПРЕДК';
    const UNION_AOT = 'СОЮЗ';
    const SUBORDINATIVE_AOT = 'СОЮЗ_ПОДЧ';
    const INTERJECTION_AOT = 'МЕЖД';
    const PARTICLE_AOT = 'ЧАСТ';


#######################################

    const PRONOUN_FULL = 'местоимение';
    const NOUN_FULL = 'существительное';
    const ADJECTIVE_FULL = 'прилагательное';
    const VERB_FULL = 'глагол';
    const ADVERB_FULL = 'наречие';
    const PREPOSITION_FULL = 'предлог';
    const PARTICIPLE_FULL = 'деепричастие';
    const COMMUNION_FULL = 'причастие';
    const NUMERAL_FULL = 'числительное';
    const INFINITIVE_FULL = 'инфинитив';
    const UNION_FULL = 'союз';
    const INTERJECTION_FULL = 'междометие';
    const PARTICLE_FULL = 'частица';

#######################################

    const PRONOUN_CLASS_ID = 4;
    const NOUN_CLASS_ID = 2;
    const ADJECTIVE_CLASS_ID = 3;
    const VERB_CLASS_ID = 1;
    const ADVERB_CLASS_ID = 12;
    const PREPOSITION_CLASS_ID = 6;
    const PARTICIPLE_CLASS_ID = 11;
    const COMMUNION_CLASS_ID = 5;
    const NUMERAL_CLASS_ID = 14;
    const INFINITIVE_CLASS_ID = 13;
    const UNION_CLASS_ID = 8;
    const INTERJECTION_CLASS_ID = 10;
    const PARTICLE_CLASS_ID = 9;

########################################

    const BASIS_AOT = 'ядро';
    const CO_COORDINATIVE_CLAUSE_AOT = 'ко-клауза-соч';
    const COMPLEX_PREDICATE_AOT = 'сост_сказ';
    const NEGATIVE_NUMERAL_AOT = 'частица_нег';
    const PREPOSITIONAL_PHRASE_AOT = 'пг';
    const GENITIVE_PHRASE_AOT = 'генит_гр';
    const ADVERB_PHRASE_AOT = 'гр_нар';
    const ADJECTIVE_PHRASE_AOT = 'гр_прил';
    const NUMERAL_PHRASE_AOT = 'гр_числ';
    const DIRECT_OBJECT_AOT = 'п_доп';
    const INDIRECT_OBJECT_AOT = 'к_доп';
    const POSSESSIVE_OBJECT_AOT = 'притяж';
    const ATTRIBUTIVE_OBJECT_AOT = 'определит';
    const PARTICIPIAL_AOT = 'пр_оборот';
    const PARTICIPIAL2_AOT = 'дпр_оборот';
    const ADJUNCT_VERB_AOT = 'обст';
    const COMPOSITION_AOT = 'сочинение';
    const SUBORDINATIVE_AOT1 = 'подч_союз';
    const CONJUNCTION_KK_AOT = 'соч_союз_кк';
    const CONJUNCTION_AOT = 'соч_союз';


########################################

    const PREDICATE_MIVAR = 'predicate';
    const SUBJECT_MIVAR = 'subject';
    const PREPOSITION_MIVAR = 'preposition';
    const OBJECT_MIVAR = 'object';
    const ADJUNCT_MIVAR = 'adjunct';
    const ATTRIBUTE_MIVAR = 'attribute';
    const ORDINAL_MIVAR = 'ordinal';
    const CONJUNCTION_MIVAR = 'conjunction';
########################################


    const BASIS_MIVAR = 'subject_predicate';
    const COMPLEX_PREDICATE_MIVAR = 'complex_predicates';
    const INDIRECT_OBJECT_MIVAR = 'indirect_object';
    const DIRECT_OBJECT_MIVAR = 'direct_object';
    const NEGATIVE_NUMERAL_MIVAR = 'negative';
    const PREPOSITIONAL_PHRASE_MIVAR = 'prepositional_phrase';
    const GENITIVE_PHRASE_MIVAR = 'genitive_phrase';
    const ADVERB_PHRASE_MIVAR = 'adverb_phrase';
    const ADJECTIVE_PHRASE_MIVAR = 'adjective_phrase';
    const NUMERAL_PHRASE_MIVAR = 'numeral_phrase';
    const ATTRIBUTE_NOUN_MIVAR = 'attribute_noun';
    const ADJUNCT_VERB_MIVAR = 'adjunct_verb';

    ########################################

    const UUD_BASIS_MIVAR = '60f720e4-f279-11e4-a0c7-005056010210';
    const UUID_COMPLEX_PREDICATE_MIVAR = '60f720e4-f279-11e4-a0c7-005056010210';
    const UUID_ADJUNCT_VERB_MIVAR = 'db7b7394-f993-11e4-b661-005056010210';

    ########################

    const NEOTGLAGOLNOE = 80;
    const OTGLAGOLNOE = 81;
}


