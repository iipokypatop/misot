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
        $id_sviaz=$sviaz->getId();
        $main_member_sviaz=$sviaz->getMainSequenceMember();
        $depended_member_sviaz=$sviaz->getDependedSequenceMember();

        $sequence=$sviaz->getSequence();///<Получаем последовательность, в которой "существует" наша принятая связь
        $sviazi_from_sequence=$sequence->getSviazi();///<Получаем все все связи

        //оббегаем все связи и сравниваем их
        foreach ($sviazi_from_sequence as $sviaz_from_sequence) {
            //Если Id совпадают, значит перед нами таже самая связь, пропускаем её
            if ($sviaz_from_sequence->getId()===$id_sviaz)
                continue;
            //Если цикл дошёл сюда, значит это другая связь, нужно проверить, есть ли конфликт
            $main_member_sequence=$sviaz_from_sequence->getMainSequenceMember();
            $depended_member_sequence=$sviaz_from_sequence->getDependedSequenceMember();


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