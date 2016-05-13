<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 19/11/15
 * Time: 17:34
 */

namespace Aot\Sviaz\Processors\AotGraph;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use DefinesAot;

/**
 * Class RoleSpecification
 */
class RoleSpecificator
{
    /**
     * Конкретизация роли элемента
     * @param string $name_relation - зависима или главная точка, не имеет значения
     * @param int $id_word_class_main
     * @param int $id_word_class_dep
     * @return int[]
     */
    public static function getRoles($name_relation, $id_word_class_main, $id_word_class_dep)
    {
        assert(is_string($name_relation));
        assert(is_int($id_word_class_main));
        assert(is_int($id_word_class_dep));

        // подлежащее-сказуемое
        if ($name_relation === DefinesAot::BASIS_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::OTNOSHENIE;
        } // составное сказуемое
        elseif ($name_relation === DefinesAot::COMPLEX_PREDICATE_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // косвенное дополнение
        elseif ($name_relation === DefinesAot::INDIRECT_OBJECT_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::VESCH;
        } // прямое дополнение
        elseif ($name_relation === DefinesAot::DIRECT_OBJECT_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::VESCH;
        } // не + глагол/сущ/местоимение/прилагательное
        elseif ($name_relation === DefinesAot::NEGATIVE_NUMERAL_MIVAR) {
            if (in_array($id_word_class_main, [
                    ChastiRechiRegistry::SUSCHESTVITELNOE,
                    ChastiRechiRegistry::MESTOIMENIE,
                    ChastiRechiRegistry::PRILAGATELNOE
                ]
            )) {
                $role_main = RoleRegistry::VESCH;
            } else {
                $role_main = RoleRegistry::OTNOSHENIE;
            }
            $role_dep = RoleRegistry::SVOISTVO;
        } // предлог + существительное
        elseif ($name_relation === DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH; // сущ
            $role_dep = RoleRegistry::SVOISTVO; // предлог
        } // словосочетание (кресло директора)
        elseif ($name_relation === DefinesAot::GENITIVE_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } //  глагол и наречие
        elseif ($name_relation === DefinesAot::ADVERB_PHRASE_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // крайне редко
        elseif ($name_relation === DefinesAot::ADJECTIVE_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } // числительные
        elseif ($name_relation === DefinesAot::NUMERAL_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } elseif ($name_relation === DefinesAot::ATTRIBUTE_NOUN_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } elseif ($name_relation === DefinesAot::ADJUNCT_VERB_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // дядя Ваня
        elseif ($name_relation === DefinesAot::SEMANTIC_COORDINATION_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } // sub_conj[1,2,3,4,5,6,7] - связь отношений через подчинительные союзы
        elseif (strpos($name_relation, "sub_conj") !== false) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } else {
            throw new \LogicException('Unrecognized name relation between points: ' . var_export($name_relation, 1));
        }

        return [$role_main, $role_dep];

    }
}