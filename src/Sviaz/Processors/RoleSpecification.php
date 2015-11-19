<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 19/11/15
 * Time: 17:34
 */

namespace Aot\Sviaz\Processors;

use Aot\Sviaz\Role\Registry as RoleRegistry;
use DefinesAot;

/**
 * Class RoleSpecification
 */
class RoleSpecification
{
    /**
     * Конкретизация роли элемента
     * @param string $name_relation - зависима или главная точка, не имеет значения
     * @return int[]
     */
    public static function getRoles($name_relation)
    {
        assert(is_string($name_relation));

        // подлежащее-сказуемое
        if ($name_relation === DefinesAot::BASIS_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::OTNOSHENIE;
        } // составное сказуемое
        elseif ($name_relation === DefinesAot::COMPLEX_PREDICATE_MIVAR) {
            # todo: создание двух связей?
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
        } // не + глагол
        elseif ($name_relation === DefinesAot::NEGATIVE_NUMERAL_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // предлог + существительное
        elseif ($name_relation === DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {
            $role_main = RoleRegistry::SVOISTVO; // предлог
            $role_dep = RoleRegistry::VESCH; // сущ
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
        } else {
            throw new \LogicException('Unrecognized name relation between points: ' . var_export($name_relation, 1));
        }

        return [$role_main, $role_dep];

    }
}