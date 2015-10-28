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
     * @return array предназначенный для вывода на интерфейс
     */
    public static function run($text)
    {
        assert(is_string($text));
        $sentences = \Aot\Tools\ConvertTextIntoSlova::convert($text);
        $tmpl_sentences = [];
        foreach ($sentences as $sentence) {
            $sentence_without_punctuation = $sentence->getSentenceWithoutPunctuation();
            $tmpl_slova = [];
            foreach ($sentence_without_punctuation as $word => $slova) {
                $tmpl_initial_forms = [];
                foreach ($slova as $slovo) {
                    $initial_form = $slovo->getInitialForm();
                    $terms = static::getTermsForInitialForm($initial_form);
                    $tmpl_definitions = [];
                    foreach ($terms as $term) {
                        $tmpl_definitions[] = static::fillTemplate($term->getDefinition(), $initial_form, []);
                    }
                    $tmpl_initial_forms[] = static::fillTemplate($initial_form, $word, $tmpl_definitions);
                }
                $tmpl_slova[] = static::fillTemplate($word, $sentence_without_punctuation->getRawSentenceText(), $tmpl_initial_forms);
            }
            $tmpl_sentences[] = static::fillTemplate($sentence_without_punctuation->getRawSentenceText(), "Предложения", $tmpl_slova);
        }
        return [static::fillTemplate("Предложения", null, $tmpl_sentences)];
    }

    /**
     * @param string $initial_form
     * @return \SemanticPersistence\Entities\SemanticEntities\Term[]
     */
    protected static function getTermsForInitialForm($initial_form)
    {
        assert(is_string($initial_form));
        $initial_form = strtolower($initial_form);

        $result = [];

        /** @var \SemanticPersistence\Entities\SemanticEntities\Word[] $word_entities */
        $word_entities =
            \SemanticPersistence\API\SemanticAPI::getAPI()
                ->findBy(
                    \SemanticPersistence\Entities\SemanticEntities\Word::class,
                    ['name' => [$initial_form]]
                );

        foreach ($word_entities as $word_entity) {
            $result = array_merge(
                $result,
                \SemanticPersistence\API\SemanticAPI::getAPI()
                    ->findBy(
                        \SemanticPersistence\Entities\SemanticEntities\Term::class,
                        ['word' => $word_entity->getId()]
                    )
            );
        }

        return $result;
    }

    protected static function fillTemplate($name, $parent, array $children)
    {
        return [
            'name' => $name,
            'parent' => $parent,
            'children' => $children
        ];
    }
}