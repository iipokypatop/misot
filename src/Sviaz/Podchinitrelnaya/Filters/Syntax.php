<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 30.09.2015
 * Time: 15:35
 */

namespace Aot\Sviaz\Podchinitrelnaya\Filters;


class Syntax
{
    protected $sequence;
    protected $sviazi;

    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base $sviaz
     */
    public function run(\Aot\Sviaz\Podchinitrelnaya\Base $sviaz)
    {
        $id_sviaz = $sviaz->getId();
        $main_member_sviaz = $sviaz->getMainSequenceMember();
        $depended_member_sviaz = $sviaz->getDependedSequenceMember();

        $sequence = $sviaz->getSequence();///<Получаем последовательность, в которой "существует" наша принятая связь
        $sviazi_from_sequence = $sequence->getSviazi();///<Получаем все все связи

        //оббегаем все связи и сравниваем их
        foreach ($sviazi_from_sequence as $sviaz_from_sequence) {
            //Если Id совпадают, значит перед нами таже самая связь, пропускаем её
            if ($sviaz_from_sequence->getId() === $id_sviaz) {
                continue;
            }
            //Если цикл дошёл сюда, значит это другая связь, нужно проверить, есть ли конфликт
            $main_member_sequence = $sviaz_from_sequence->getMainSequenceMember();
            $depended_member_sequence = $sviaz_from_sequence->getDependedSequenceMember();
            //Если связь повторяется (не важно в какую сторону она будет направлена) нужно идти в БД и вытаскивать связь от туда
            if (
                ($main_member_sequence === $main_member_sviaz && $depended_member_sequence === $depended_member_sviaz)
                ||
                ($main_member_sequence === $depended_member_sviaz && $depended_member_sequence === $main_member_sviaz)
            ) {
                // получаем начальные формы слов
                $member1_initial_form = $main_member_sviaz->getSlovo()->getInitialForm();
                $member2_initial_form = $depended_member_sviaz->getSlovo()->getInitialForm();

                //Создаём слова
                $word1 = new \SemanticPersistence\Entities\SemanticEntities\Word;
                $word2 = new \SemanticPersistence\Entities\SemanticEntities\Word;
                $word1->setName($member1_initial_form);
                $word2->setName($member2_initial_form);

                //Ищем в БД, есть ли связь
                xxx($member1_initial_form, $member2_initial_form);
            }

        }


    }


    /**
     * @return mixed
     */
    protected function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param mixed $sequence
     */
    protected function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    /**
     * @return mixed
     */
    protected function getSviazi()
    {
        return $this->sviazi;
    }

    /**
     * @param mixed $sviazi
     */
    protected function setSviazi($sviazi)
    {
        $this->sviazi = $sviazi;
    }
}