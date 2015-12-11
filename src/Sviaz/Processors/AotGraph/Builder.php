<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 11/12/15
 * Time: 12:59
 */

namespace Aot\Sviaz\Processors\AotGraph;


class Builder
{

    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
        $this->factories = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getFactories();
    }


    /**
     * Получение фабрики по id класса слова
     *
     * @param int $id_word_class
     * @return \Aot\RussianMorphology\FactoryBase
     */
    public function getFactory($id_word_class)
    {
        assert(is_int($id_word_class));

        if (empty($this->factories[$this->conformityPartsOfSpeech($id_word_class)])) {
            throw new \LogicException("Undefined part of speech = " . var_export($this->conformityPartsOfSpeech($id_word_class), 1));
        }

        return $this->factories[$this->conformityPartsOfSpeech($id_word_class)];
    }



    /**
     * Возвращаем соответствующий id части речи МИСОТа по id части речи АОТа
     *
     * @param integer $id_part_of_speech_aot
     * @return integer
     */
    public function conformityPartsOfSpeech($id_part_of_speech_aot)
    {
        assert(is_int($id_part_of_speech_aot));
        // соответвие id части речи из морфика и в мисоте
        $conformity = [
            \DefinesAot::VERB_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::GLAGOL, // гл
            \DefinesAot::NOUN_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SUSCHESTVITELNOE, // сущ
            \DefinesAot::ADJECTIVE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRILAGATELNOE, // прил
            \DefinesAot::PRONOUN_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MESTOIMENIE, // мест
            \DefinesAot::COMMUNION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRICHASTIE, // прич
            \DefinesAot::PREPOSITION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PREDLOG, // предлог
            # в МИСОТе нет
            # 7 =>\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::, // аббревиатура
            \DefinesAot::UNION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SOYUZ, // союз
            \DefinesAot::PARTICLE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHASTICA, // част
            \DefinesAot::INTERJECTION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MEZHDOMETIE, // межд
            \DefinesAot::PARTICIPLE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::DEEPRICHASTIE, // деепр
            \DefinesAot::ADVERB_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::NARECHIE, // нар
            \DefinesAot::PREDICATIVE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::INFINITIVE, // инф
            \DefinesAot::NUMERAL_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHISLITELNOE, // числ
            # в МИСОТе нет
            # 15 =>\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::, // сокращение
        ];

        return $conformity[$id_part_of_speech_aot];
    }
}