<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 19/11/15
 * Time: 17:34
 */

namespace Aot\Sviaz\Processors\Aot;

use Aot\Sviaz\Role\Registry as RoleRegistry;
use \WrapperAot\ModelNew\Convert\Defines;

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
     * @return \int[]
     */
    public static function getRoles($name_relation, $id_word_class_main, $id_word_class_dep)
    {
        assert(is_string($name_relation));
        assert(is_int($id_word_class_main));
        assert(is_int($id_word_class_dep));

        // подлежащее-сказуемое
        if ($name_relation === \WrapperAot\ModelNew\Convert\Defines::BASIS_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::OTNOSHENIE;
        } // составное сказуемое
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::COMPLEX_PREDICATE_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // косвенное дополнение
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::INDIRECT_OBJECT_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::VESCH;
        } // прямое дополнение
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::DIRECT_OBJECT_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::VESCH;
        } // не + глагол/сущ/местоимение/прилагательное
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::NEGATIVE_NUMERAL_MIVAR) {
            if (in_array($id_word_class_main, [
                \WrapperAot\ModelNew\Convert\Defines::NOUN_CLASS_ID,
                \WrapperAot\ModelNew\Convert\Defines::PRONOUN_CLASS_ID,
                \WrapperAot\ModelNew\Convert\Defines::ADJECTIVE_CLASS_ID
            ])) {
                $role_main = RoleRegistry::VESCH;
            } else {
                $role_main = RoleRegistry::OTNOSHENIE;
            }
            $role_dep = RoleRegistry::SVOISTVO;
        } // предлог + существительное
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::PREPOSITIONAL_PHRASE_MIVAR) {
            $role_main = RoleRegistry::SVOISTVO; // предлог
            $role_dep = RoleRegistry::VESCH; // сущ
        } // словосочетание (кресло директора)
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::GENITIVE_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } //  глагол и наречие
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::ADVERB_PHRASE_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // крайне редко
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::ADJECTIVE_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } // числительные
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::NUMERAL_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::ATTRIBUTE_NOUN_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::ADJUNCT_VERB_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // дядя Ваня
        elseif ($name_relation === \WrapperAot\ModelNew\Convert\Defines::SEMANTIC_COORDINATION_MIVAR) {
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