<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23.10.2015
 * Time: 14:43
 */

namespace Aot\Tools;


class BuildTermTreeFromText
{
    /**
     * @param string $text
     * @return array
     */
    public static function run($text)
    {
        assert(is_string($text));
        $sentences = \Aot\Tools\ConvertTextIntoSlova::convert($text);
        $tmpl_sentences = [];
        foreach ($sentences as $sentence) {
            $tmpl_slova = [];
            foreach ($sentence as $word => $slova) {

                $tmpl_initial_forms = [];
                foreach ($slova as $slovo) {
                    $initial_form = ($slovo->getInitialForm());
                    $definitions = static::getDefinitionsForInitialForm($initial_form);
                    $tmpl_definitions = [];
                    foreach ($definitions as $definition) {
                        $tmpl_definitions[] = static::fillTemplate($definition, $initial_form, []);
                    }
                    $tmpl_initial_forms[] = static::fillTemplate($initial_form, $word, $tmpl_definitions);
                }
                $tmpl_slova[] = static::fillTemplate($word, $sentence->getRawSentenceText(), $tmpl_initial_forms);
            }
            $tmpl_sentences[] = static::fillTemplate($sentence->getRawSentenceText(), "Предложения", $tmpl_slova);
        }
        $result = static::fillTemplate("Предложения", null, $tmpl_sentences);
        return $result;
    }

    /**
     * @param string $initial_form
     * @return string[]
     */
    protected static function getDefinitionsForInitialForm($initial_form)
    {
        assert(is_string($initial_form));

        $result = [];

        $api = \SemanticPersistence\API\SemanticAPI::getAPI();
        $word_entities = $api->findBy(\SemanticPersistence\Entities\SemanticEntities\Word::class,
            ['name' => [$initial_form]]);
        /** @var \SemanticPersistence\Entities\SemanticEntities\Word $word_entity */
        foreach ($word_entities as $word_entity) {
            /** @var \SemanticPersistence\Entities\SemanticEntities\Term[] $terms */
            $terms = $api->findBy(\SemanticPersistence\Entities\SemanticEntities\Term::class,
                ['word' => $word_entity->getId()]);
            foreach ($terms as $term) {
                $definition = $term->getDefinition();
                $result[] = $definition;
            }

        }
        return $result;
    }

    protected static function fillTemplate($name, $parent, array $children)
    {
        $template =
            [
                'name' => $name,
                'parent' => $parent,
                'children' => $children
            ];
        return $template;
    }

}